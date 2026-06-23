<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Block XP observer.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_xp\local\observer;

/**
 * Block XP observer class.
 *
 * @package    block_xp
 * @copyright  2014 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class observer {

    /**
     * Act when a course is deleted.
     *
     * @param  \core\event\course_deleted $event The event.
     * @return void
     */
    public static function course_deleted(\core\event\course_deleted $event) {
        global $DB;

        $courseid = $event->objectid;

        // Clean up the data that could be left behind.
        $conditions = ['courseid' => $courseid];
        $DB->delete_records('block_xp', $conditions);
        $DB->delete_records('block_xp_config', $conditions);
        $DB->delete_records('block_xp_filters', $conditions);
        $DB->delete_records('block_xp_log', $conditions);

        // Flags. Note that this is based on the actually implementation.
        $sql = $DB->sql_like('name', ':name');
        $DB->delete_records_select('user_preferences', $sql, [
            'name' => 'block_xp-notice-block_intro_' . $courseid,
        ]);
        $DB->delete_records_select('user_preferences', $sql, [
            'name' => 'block_xp_notify_level_up_' . $courseid,
        ]);

        // Delete the files.
        $fs = get_file_storage();
        $fs->delete_area_files($event->contextid, 'block_xp', 'badges');
    }

    /**
     * Observe all events for different activity types.
     *
     * @param \core\event\base $event The event.
     * @return void
     */
    public static function catch_all(\core\event\base $event) {
        global $DB;
        $courseid = $event->courseid;

        // Fetch the rule data for the course
        $rule = $DB->get_record('block_xp_filters', ['courseid' => $courseid], 'ruledata');

        // Ensure the rule data exists before checking events
        if ($rule && isset($rule->ruledata)) {
            // Check for "course_module_completion_updated" event for tracking all activity completions
            if (strpos($rule->ruledata, 'course_module_completion_updated') !== false) {
                if ($event instanceof \core\event\course_module_completion_updated) {
                    self::handle_module_completion($event);
                }
            }
        }

        $cs = \block_xp\di::get('collection_strategy');
        if ($cs instanceof \block_xp\local\strategy\event_collection_strategy) {
            $cs->collect_event($event);
        }
    }

    /**
     * Handle generic activity completion (any module) and award points once.
     *
     * Awards points when the learner reaches COMPLETE or COMPLETE_PASS.
     * Skips INCOMPLETE or COMPLETE_FAIL. Idempotent via a simple log check.
     *
     * @param \core\event\course_module_completion_updated $event
     * @return void
     */
  
    public static function handle_module_completion(\core\event\course_module_completion_updated $event) {
        global $DB;
        $courseid = $event->courseid;
        $studentid = $event->relateduserid;
       
        $completionstate = $event->other['completionstate'] ?? null;
        
        $completion = $DB->get_record('course_modules_completion', ['id' => $event->objectid]);
        if (!$completion) {
            return;
        }
        $cmid = $completion->coursemoduleid;

        // Define the states for awardable completions
        $awardablestates = [
            COMPLETION_COMPLETE,       // 1 - Completed
            COMPLETION_COMPLETE_PASS,  // 2 - Passed
            COMPLETION_COMPLETE_FAIL,  // 3 - Failed (award 0 points)
        ];

        // If the completion state is not one of the valid states, exit the function
        if (!in_array((int)$completionstate, $awardablestates, true)) {
            return;
        }

        // Idempotency: Skip awarding points if we've already logged the award for this user and module
        $alreadyawarded = $DB->record_exists('block_xp_log', [
            'courseid'  => $courseid,
            'userid'    => $studentid,
            'eventname' => 'cm_completed',
            'activityid'  => $cmid,
        ]);

        if ($alreadyawarded) {
            return;
        }

        // Determine points based on completion state
        $points = ($completionstate == COMPLETION_COMPLETE_FAIL) ? 0 : (int) self::get_points_from_rule($courseid);

        // Award the points to the user
        self::award_points_to_user($studentid, $points, $courseid);

        // Log the point award for this module completion
        $log = (object)[
            'time'      => time(),
            'courseid'  => $courseid,
            'userid'    => $studentid,
            'eventname' => 'cm_completed',
            'activityid'=> $cmid,
            'xp'        => $points,
        ];

        try {
            $DB->insert_record('block_xp_log', $log);
        } catch (\Exception $e) {
        }
    }

    /**
     * Fetch points from mdl_block_xp_filters for the course.
     *
     * @param int $courseid The course ID.
     * @return int|null Points to award, or null if no points are found.
     */
    public static function get_points_from_rule($courseid) {
        global $DB;

        $rule = $DB->get_record('block_xp_filters', ['courseid' => $courseid], 'points');

        if ($rule && isset($rule->points)) {
            return $rule->points;
        }
        
        return null;
    }

    /**
     * Award points to the user.
     *
     * @param int $userid The user ID.
     * @param int $points The points to award.
     * @param int $courseid The course ID.
     * @return void
     */
    public static function award_points_to_user($studentid, $points, $courseid) {
        global $DB;

        // Check if the user already has XP points in the mdl_block_xp table
        $record = $DB->get_record('block_xp', ['userid' => $studentid, 'courseid' => $courseid], 'id, xp, lvl');
        if ($record) {
            // Update XP points if the user already has a record
            $record->xp += $points;
            $record->lvl = 1;
            $DB->update_record('block_xp', $record);
        } else {
            $DB->insert_record('block_xp', [
                'userid' => $studentid,
                'xp' => $points,
                'lvl' => 1,
                'courseid' => $courseid,
            ]);
        }
    }
}
