<?php

require_once('../../config.php');
require_once($CFG->libdir . '/tablelib.php');

$id = required_param('cmid', PARAM_INT); // Course module id.

$cm = get_coursemodule_from_id('quiz', $id, 0, false, MUST_EXIST);
$course = $DB->get_record('course', ['id' => $cm->course], '*', MUST_EXIST);

require_login($course, false, $cm);

$context = context_module::instance($cm->id);
require_capability('mod/quiz:viewreports', $context);

$PAGE->set_url('/local/proctoringapi/proctoring_ai_report.php', ['id' => $id]);
$PAGE->set_context($context);
$PAGE->set_title(get_string('proctoringaireport', 'local_proctoringapi'));
$PAGE->set_heading($course->fullname);

echo $OUTPUT->header();

echo $OUTPUT->heading(get_string('proctoringaireport', 'local_proctoringapi'));

$table = new flexible_table('local-proctoring-ai-report');

$table->define_columns([
    'student',
    'attempt',
    'focus',
    'risk',
    'risklevel',
    'observations',
    'timecreated'
]);

$table->define_headers([
    get_string('student', 'local_proctoringapi'),
    get_string('attempt', 'local_proctoringapi'),
    get_string('focus', 'local_proctoringapi'),
    get_string('risk', 'local_proctoringapi'),
    get_string('risklevel', 'local_proctoringapi'),
    get_string('observations', 'local_proctoringapi'),
    get_string('date', 'local_proctoringapi'),
]);

$table->set_attribute('class', 'generaltable generalbox');
$table->sortable(true);
$table->pageable(true);
$table->setup();

$reportsql = "SELECT pr.*, CONCAT(u.firstname, ' ', u.lastname) AS fullname
			    FROM {local_proctoring_results} pr
				JOIN {user} u ON u.id = pr.studentid
				JOIN {quiz_attempts} qa ON qa.id = pr.attemptid
			   WHERE pr.quizid = :cmid
			ORDER BY pr.timecreated DESC";

$records = $DB->get_records_sql($reportsql, ['cmid' => $cm->id]);
// print_r($cm->id);die();

foreach ($records as $record) {

    $riskbadge = html_writer::span(
        s($record->risk_level),
        'badge badge-secondary'
    );

    $table->add_data([
        format_string($record->fullname),
        $record->attemptid,
        $record->focus_score_percent . '%',
        $record->cheating_risk_percent . '%',
        $riskbadge,
        format_text($record->finalobservations, FORMAT_PLAIN),
        date('d-m-Y H:i:s', $record->timecreated)
    ]);
}

$table->finish_output();

echo $OUTPUT->footer();