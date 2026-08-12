<?php

require_once('../../config.php');

$id = required_param('cmid', PARAM_INT);

$cm = get_coursemodule_from_id('quiz', $id, 0, false, MUST_EXIST);
$course = $DB->get_record('course', ['id' => $cm->course], '*', MUST_EXIST);

require_login($course, false, $cm);

$context = context_module::instance($cm->id);
require_capability('mod/quiz:viewreports', $context);
$PAGE->activityheader->disable();
$PAGE->set_url('/local/proctoringapi/proctoring_ai_report.php', ['cmid' => $id]);
$PAGE->set_context($context);
$PAGE->set_title(get_string('proctoringaireport', 'local_proctoringapi'));
$PAGE->set_heading($course->fullname);

// Load AMD JS
$PAGE->requires->js_call_amd('local_proctoringapi/report', 'init', ['cmid' => $id]);

echo $OUTPUT->header();

// Render Mustache template
echo $OUTPUT->render_from_template('local_proctoringapi/report', ['cmid' => $id]);

echo $OUTPUT->footer();
