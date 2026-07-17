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
 *
 * @package   local_forum
 */
defined('MOODLE_INTERNAL') || die();
global $CFG;

require_once(dirname(__FILE__) . '/../../config.php');
require_once($CFG->dirroot . '/grade/grading/lib.php');
use core_course\customfield\course_handler;

function post_created(\mod_forum\event\post_created $event) {
    try {
        local_forum_event_handle($event, 'post_created');
    } catch (Exception $e) {
        debugging("Error in event {$event->eventname}: " . $e->getMessage(), DEBUG_DEVELOPER);
    }
}

function post_updated(\mod_forum\event\post_updated $event) {
    try {
        local_forum_event_handle($event, 'post_updated');
    } catch (Exception $e) {
        debugging("Error in event {$event->eventname}: " . $e->getMessage(), DEBUG_DEVELOPER);
    }
}

function post_deleted(\mod_forum\event\post_deleted $event) {
    try {
        local_forum_event_handle($event, 'post_deleted');
    } catch (Exception $e) {
        debugging("Error in event {$event->eventname}: " . $e->getMessage(), DEBUG_DEVELOPER);
    }
}

function discussion_created(\mod_forum\event\discussion_created $event) {
    try {
        local_forum_event_handle($event, 'discussion_created');
    } catch (Exception $e) {
        debugging("Error in event {$event->eventname}: " . $e->getMessage(), DEBUG_DEVELOPER);
    }
}

function discussion_deleted(\mod_forum\event\discussion_deleted $event) {
    try {
        local_forum_event_handle($event, 'discussion_deleted');
    } catch (Exception $e) {
        debugging("Error in event {$event->eventname}: " . $e->getMessage(), DEBUG_DEVELOPER);
    }
}

function discussion_updated(\mod_forum\event\discussion_updated $event) {
    try {
        local_forum_event_handle($event, 'discussion_updated');
    } catch (Exception $e) {
        debugging("Error in event {$event->eventname}: " . $e->getMessage(), DEBUG_DEVELOPER);
    }
}

function local_forum_event_handle($event, $eventname) {

    global $DB;
    $context  = $event->get_context();
    $courseid = $event->courseid;
    $userid   = $event->userid;
    if ($eventname == 'discussion_created') {
        $discussionid   = $event->objectid;
    } else {
        $postid   = $event->objectid;
    }
    $gradingdata = '';

    try {

        $forumid = $event->get_data()['other']['forumid'];
        $forum = $DB->get_record('forum', ['id' => $forumid], '*', MUST_EXIST);
	if ($forum->ai_grading != 1) {
            return;
        }
        // Get forum title
        $forumname = $forum->name;
        // Get formatted description (with images, HTML)
        $forumdescription = format_text($forum->intro, $forum->introformat);

        $completionposts = $forum->completionposts;             // Required number of posts
        $completiondiscussions = $forum->completiondiscussions; // Required number of discussions
        $completionreplies = $forum->completionreplies;         // Required number of replies

        // Count discussions started by user
        $discussioncount = $DB->count_records('forum_discussions', [
            'forum' => $forumid,
            'userid' => $userid
        ]);

        // Count total posts by user in this forum
        $totalposts = $DB->get_records_sql("
            SELECT p.*
            FROM {forum_posts} p
            JOIN {forum_discussions} d ON d.id = p.discussion
            WHERE d.forum = ? AND p.userid = ?", [$forumid, $userid]);

        $postcount = 0;
        $replycount = 0;

        foreach ($totalposts as $post) {
            if ($post->parent == 0) {
                // This is the first post in a discussion (already counted above)
                continue;
            } else {
                $replycount++;
            }
            $postcount++;
        }

	    if ($eventname != 'discussion_created') {
            $forumpostdata = get_forum_postdata($forumid, $postid, $userid, $context, $eventname);
            if ($forumpostdata['fileids']) {
        		$endpoint = get_config('local_forum', 'post_end_points');
        		execute_curl_userforumapi($forumpostdata, $endpoint);
            }
        }

        $meets_posts = ($completionposts == 0 || ($postcount + $discussioncount) >= $completionposts);
        $meets_discussions = ($completiondiscussions == 0 || $discussioncount >= $completiondiscussions);
        $meets_replies = ($completionreplies == 0 || $replycount >= $completionreplies);

        $fieldshortname = 'ai_required'; // Replace with your field shortname
        $indexingflag = get_course_custom_field_value_forum($courseid, $fieldshortname);
        if ($meets_posts && $meets_discussions && $meets_replies && $indexingflag==1) {

            $gradingmanager = get_grading_manager($context, 'mod_forum', 'forum');
            $gradingmethod = $gradingmanager->get_active_method();
            if ($gradingmethod && $controller = $gradingmanager->get_controller($gradingmethod)) {
                if ($controller->is_form_defined()) {
                    $gradingdata = $controller->get_definition();
                }
            }

            $cm = get_coursemodule_from_instance('forum', $forumid);
            $context = context_module::instance($cm->id);

            $discussiontree = build_discussion_tree_for_user($forumid, $userid, $context);
            $user = core_user::get_user($userid);

            $gradeitem = $DB->get_record('grade_items', [
                'itemtype' => 'mod',
                'itemmodule' => 'forum',
                'iteminstance' => $forumid,
                'courseid' => $courseid
            ]);

            if ($gradeitem) {
                $gradingtype = $gradeitem->gradetype;    // 1 = value, 2 = scale
                $grademax = $gradeitem->grademax;        // Max grade (for value type)
                $gradetopass = $gradeitem->gradepass;    // Grade required to pass
            } else {
                $grademax = 0;
                $gradetopass = 0;
            }

            $fieldshortname = 'indexing_required'; // Replace with your field shortname
            $courseindexing =  get_course_custom_field_value_forum($courseid, $fieldshortname);

            $postdata = [
                'forumid' => $forumid,
                'forumtitle' => $forumname,
                'forumdescription' => $forumdescription,
                'userid' => $userid,
                'studentname'=> fullname($user),
                'courseid' => $courseid,
                'gradingtype' => ($gradingmethod) ? $gradingmethod : 'simple',
                'gradingdata' => ($gradingdata) ? json_encode($gradingdata) : '',
                'discussiondata' => ($discussiontree) ? json_encode($discussiontree) : '',
                'maxgrade' => intval($grademax),
                'gradetopass' => $gradetopass,
                'indexingflag' => ($courseindexing == 1) ? true : false,
            ];

            $endpoint = get_config('local_forum', 'create_end_points');
            $response = execute_curl_userforumapi($postdata, $endpoint);
            $record = new stdClass();
            $record->userid = $userid;
            $record->courseid = $courseid;
            $record->forumid = $forumid;
            $record->status = ($response->status) ? 1 : 0;
            $record->grade = '';
            $record->feedbackdesc = '';
            $record->gradingtype = ($gradingmethod) ? $gradingmethod : 'simple';
            $record->isdeleted = 0;
            $record->iscompletioncriteriamet = 1;
            $record->timemodified = $timecreated = time();

            $graderrow = $DB->get_record('forum_graderesponse', ['userid' => $userid, 'forumid' => $forumid], '*', IGNORE_MISSING);
            if (empty($graderrow)) {
                $record->timecreated = $timecreated;
                $DB->insert_record('forum_graderesponse', $record);
            } else {
                $record->id = $graderrow->id;
                $DB->update_record('forum_graderesponse', $record);
            }
        } else {
            if ($record = $DB->get_record('forum_graderesponse', ['userid' => $userid, 'forumid' => $forumid])) {
                $record->iscompletioncriteriamet = 0;
                $DB->update_record('forum_graderesponse', $record);
            }
        }
    } catch (Exception $e) {
        debugging("Error in event {$event->eventname}: " . $e->getMessage(), DEBUG_DEVELOPER);
    }
}

function get_forum_postdata($forumid, $postid, $userid, $context, $eventname) {
    global $DB;

    if ($eventname == 'post_deleted') {
        $discussionid = 0;
        $isdeleted = true;
    } else {
        $post = $DB->get_record('forum_posts', ['id' => $postid], 'discussion');
        $discussionid = $post->discussion;
        $isdeleted = false;
    }

    $fs = get_file_storage();
    // Get files in 'attachment' file area
    $files = $fs->get_area_files(
        $context->id,         // Context ID
        'mod_forum',          // Component
        'attachment',         // File area
        $postid,              // Item ID (post ID)
        "filename",           // Sort order
        false                 // Exclude directories
    );

    $fileids = [];
    foreach ($files as $file) {
        $fileids[] = $file->get_id();
    }

    $forumpostdata = [
        'userid' => $userid,
        'forumid' => $forumid,
        "postid" => $postid,
        "fileids" => implode(',', $fileids),
        "discussionid" => $discussionid,
        "isdeleted" => $isdeleted,
    ];

    return $forumpostdata;
}

function build_discussion_tree_for_user($forumid, $userid, $context) {
    global $DB;

    $fs = get_file_storage();

    $discussions = $DB->get_records('forum_discussions', ['forum' => $forumid]);
    $discussiontrees = [];
    foreach ($discussions as $discussion) {
        // Get all posts in this discussion
        $posts = $DB->get_records('forum_posts', ['discussion' => $discussion->id], 'created ASC');

        if (!$posts) continue;

        // Index posts by ID
        $postindex = [];
        foreach ($posts as $post) {

            // Get files in 'attachment' file area
            $files = $fs->get_area_files(
                $context->id,         // Context ID
                'mod_forum',          // Component
                'attachment',         // File area
                $post->id,              // Item ID (post ID)
                "filename",           // Sort order
                false                 // Exclude directories
            );
            $fileids = [];
            foreach ($files as $file) {
                $fileids[] = $file->get_id();
            }

            $postindex[$post->id] = (object)[
                'id' => $post->id,
                'parent' => $post->parent,
                'userid' => $post->userid,
                'message' => format_text($post->message, $post->messageformat),
                'fileids'  => implode(',', $fileids),
                'child' => []
            ];
        }

        // Build parent-child tree
        $tree = [];
        foreach ($postindex as $postid => $post) {
            if ($post->parent == 0) {
                $tree[$postid] = &$postindex[$postid]; // Root post
            } else {
                if (isset($postindex[$post->parent])) {
                    $postindex[$post->parent]->child[] = &$postindex[$postid];
                }
            }
        }

        // Filter to paths involving the user
        $filtered_tree = filter_post_tree_for_user($tree, $userid);

        if (!empty($filtered_tree)) {
            $discussiontrees[] = [
                'discussionid' => $discussion->id,
                'discussiontitle' => $discussion->name,
                'tree' => $filtered_tree
            ];
        }
    }

    return $discussiontrees;
}

function filter_post_tree_for_user($tree, $userid) {
    $result = [];

    foreach ($tree as $node) {
        $filtered = filter_node_recursive($node, $userid);
        if ($filtered !== null) {
            $result[] = $filtered;
        }
    }

    return $result;
}

function filter_node_recursive($node, $userid) {
    $include = ($node->userid == $userid);

    $filtered_child = [];

    foreach ($node->child as $child) {
        $filtered = filter_node_recursive($child, $userid);
        if ($filtered !== null) {
            $filtered_child[] = $filtered;
            $include = true; // If a child is included, include parent
        }
    }

    if ($include) {
        return (object)[
            'id' => $node->id,
            'userid' => $node->userid,
            'message' => $node->message,
            'fileids'  => $node->fileids,
            'child' => $filtered_child
        ];
    }

    return null;
}

function get_course_custom_field_value_forum($courseid, $fieldshortname) {
    $handler = course_handler::create();
    $data = $handler->get_instance_data($courseid);
    foreach ($data as $fielddata) {

	$field = $fielddata->get_field();
        // Check if this is the correct field
        if ($field->get('shortname') === $fieldshortname) {

            // Get the field options (for select fields, options are stored in the field data)
            $options = $field->get_options();  // This is the correct way to get options for select fields

            // Get the selected value from the field data
            $selectedvalue = $fielddata->get_value();

            // Now look for the label of the selected value
            if (isset($options[$selectedvalue]) && $options[$selectedvalue] == 'Yes'){
               return 1;  // Return the label of the selected option
            } else {
                return 0;
            }
        }

    }
    return null; // Return null if field not found
}

function get_genapi_headers_forum() {
    $apikey = get_config('local_forum', 'api_keys');
    $headers = [
        "x-api-key: $apikey",
        "Content-Type: application/json"
    ];

    return $headers;
}

function execute_curl_userforumapi($data, $endpoint) {
    //$endpoint = get_config('local_forum', 'create_end_points');
    $headers = get_genapi_headers_forum();

    $ch = curl_init($endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $responseRaw = curl_exec($ch);
    $response = json_decode($responseRaw);

    if (curl_errno($ch)) {
        debugging("cURL PUT error: " . curl_error($ch), DEBUG_DEVELOPER);
    }

    curl_close($ch);
    $response = json_decode($responseRaw);

    if (isset($response->errors)) {
        debugging("API error response: " . json_encode($response), DEBUG_DEVELOPER);
    } else {
        debugging("API response success: " . json_encode($response), DEBUG_DEVELOPER);
    }
    return $response;
}
?>
