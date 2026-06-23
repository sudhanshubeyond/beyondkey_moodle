<?php
require_once('../../config.php');
require_login();
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_title('Teams Meetings');
$PAGE->set_heading('Teams Meetings');
echo $OUTPUT->header();
echo $OUTPUT->heading('Teams Meetings for all courses');
echo $OUTPUT->footer();
