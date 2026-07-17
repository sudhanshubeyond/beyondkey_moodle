<?php

define('AJAX_SCRIPT', true);
require_once(__DIR__ . '/../../config.php');
require_login();

$action = required_param('action', PARAM_ALPHA);
$cmid = required_param('cmid', PARAM_INT);
$userid = optional_param('userid', 1, PARAM_INT);

global $DB;

$coursemoduledata = $DB->get_record('course_modules', ['id' => $cmid], 'instance');
$data = $DB->get_record('forum_graderesponse', ['forumid' => $coursemoduledata->instance, 'userid' => $userid]);

if ($action == 'displayai') {
    $forumid = $coursemoduledata->instance;

    $forum = $DB->get_record(
            'forum',
            ['id' => $forumid],
            'id, teacher_approval, ai_grading',
            IGNORE_MISSING
    );

    $status = 1; // Default

    if ($forum) {
        if ((int) $forum->teacher_approval == 0 || (int) $forum->ai_grading == 0) {
            $status = 0;
        }
    }
    $response = ['status' => $status];
} else {
    if (!empty($data) && $data->status && $data->isdeleted == 0) {
        $errormessage = ($data->errormessage == '') ? NULL : $data->errormessage;
        switch ($action) {
            case 'getgrades':
                $response = ['status' => 1, 'grade' => $data->grade, 'gradingtype' => $data->gradingtype, 'feedback' => $data->feedbackdesc, 'rubricbreakdown' => $data->rubricbreakdown, 'errormessage' => $errormessage];
                break;
            default:
                $response = ['status' => 0, 'errormessage' => 'Invalid action'];
                break;
        }
    } else {
        $response = ['status' => 0, 'errormessage' => 'AI grading is not available.'];
    }
}

echo json_encode($response);
die;
