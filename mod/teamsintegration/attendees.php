<?php
require_once('../../config.php');
require_once(__DIR__.'/lib.php');

$id = required_param('id', PARAM_INT);
$cm = get_coursemodule_from_id('teamsintegration', $id, 0, false, MUST_EXIST);
$context = context_module::instance($cm->id);
require_login($cm->course, true, $cm);

$action = optional_param('action', '', PARAM_ALPHA);
$attid = optional_param('attid', 0, PARAM_INT);

if ($action === 'delete' && $attid && has_capability('mod/teamsintegration:removeattendee', $context)) {
    // teamsintegration_remove_attendee($attid);
    $removed = teamsintegration_remove_attendee($attid);
    if ($removed) {
        $message = get_string('attendeeremoved', 'mod_teamsintegration');
        $messagetype = \core\output\notification::NOTIFY_SUCCESS;
    } else {
        $message = get_string('attendeeremovefailed', 'mod_teamsintegration');
        $messagetype = \core\output\notification::NOTIFY_ERROR;
    }
}

redirect(new moodle_url('/mod/teamsintegration/view.php', ['id' => $cm->id]), $message, null, $messagetype);
// redirect(new moodle_url('/mod/teamsintegration/view.php', ['id' => $cm->id]));
