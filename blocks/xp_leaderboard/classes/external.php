<?php
defined('MOODLE_INTERNAL') || die();
require_once("$CFG->libdir/externallib.php");

class block_xp_leaderboard_external extends external_api {

    public static function get_leaderboard_parameters() {
        return new external_function_parameters([
            'courseid' => new external_value(PARAM_INT, 'Course ID')
        ]);
    }

    public static function get_leaderboard($courseid) {
        global $DB, $USER;

        $params = self::validate_parameters(self::get_leaderboard_parameters(), ['courseid' => $courseid]);

        $ctx = context_course::instance($params['courseid']);
        self::validate_context($ctx);
        //require_capability('moodle/course:view', $ctx);

        // Fetch leaderboard data.
        $sql = "SELECT u.id AS userid, u.username, x.xp
                  FROM {block_xp} x
                  JOIN {user} u ON u.id = x.userid
                 WHERE x.courseid = :cid
              ORDER BY x.xp DESC, u.id ASC";
        $records = $DB->get_records_sql($sql, ['cid' => $params['courseid']]);

        $data = [];
        $rank = 0;
        $pos = 0;
        $prevxp = null;

        foreach ($records as $r) {
            $pos++;
            if ($prevxp === null || $r->xp != $prevxp) {
                $rank = $pos;
                $prevxp = $r->xp;
            }
            $data[] = [
                'userid' => $r->userid,
                'username' => $r->username,
                'xp' => (int)$r->xp,
                'rank' => $rank,
            ];
        }

        return ['leaderboard' => $data];
    }

    public static function get_leaderboard_returns() {
        return new external_single_structure([
            'leaderboard' => new external_multiple_structure(
                new external_single_structure([
                    'userid' => new external_value(PARAM_INT, 'User ID'),
                    'username' => new external_value(PARAM_RAW, 'Username'),
                    'xp' => new external_value(PARAM_INT, 'XP points'),
                    'rank' => new external_value(PARAM_INT, 'Rank')
                ])
            )
        ]);
    }
}
