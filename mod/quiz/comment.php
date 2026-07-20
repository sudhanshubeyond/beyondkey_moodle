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
 * This page allows the teacher to enter a manual grade for a particular question.
 * This page is expected to only be used in a popup window.
 *
 * @package   mod_quiz
 * @copyright gustav delius 2006
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use mod_quiz\output\attempt_summary_information;

require_once('../../config.php');
require_once('locallib.php');
//code added from beyond key
require_once($CFG->dirroot . '/local/quiz/locallib.php');
//end of code added from beyond key

$attemptid = required_param('attempt', PARAM_INT);
$slot = required_param('slot', PARAM_INT); // The question number in the attempt.
$cmid = optional_param('cmid', null, PARAM_INT);

$PAGE->set_url('/mod/quiz/comment.php', ['attempt' => $attemptid, 'slot' => $slot]);

$attemptobj = quiz_create_attempt_handling_errors($attemptid, $cmid);
$attemptobj->preload_all_attempt_step_users();
$student = $DB->get_record('user', ['id' => $attemptobj->get_userid()]);

// Can only grade finished attempts.
if (!$attemptobj->is_finished()) {
    throw new \moodle_exception('attemptclosed', 'quiz');
}

// Check login and permissions.
require_login($attemptobj->get_course(), false, $attemptobj->get_cm());
$attemptobj->require_capability('mod/quiz:grade');

// Print the page header.
$PAGE->set_pagelayout('popup');
$PAGE->set_title(get_string('manualgradequestion', 'quiz', [
        'question' => format_string($attemptobj->get_question_name($slot)),
        'quiz' => format_string($attemptobj->get_quiz_name()), 'user' => fullname($student)]));
$PAGE->set_heading($attemptobj->get_course()->fullname);
$output = $PAGE->get_renderer('mod_quiz');
echo $output->header();

// Prepare summary information about this question attempt.
$summary = new attempt_summary_information();
// Set the caption.
$summary->set_caption(get_string('summaryofattempt', 'quiz'));
// Student name.
$userpicture = new user_picture($student);
$userpicture->courseid = $attemptobj->get_courseid();
$summary->add_item('user', $userpicture, new action_link(
        new moodle_url('/user/view.php', [ 'id' => $student->id, 'course' => $attemptobj->get_courseid()]),
        fullname($student, true)));

// Quiz name.
$summary->add_item('quizname', get_string('modulename', 'quiz'), format_string($attemptobj->get_quiz_name()));

// Question name.
$summary->add_item('questionname', get_string('question', 'quiz'), $attemptobj->get_question_name($slot));

// Error message in case of input invalid mark.
$submiterror = false;

// Process any data that was submitted.
if (data_submitted() && confirm_sesskey()) {
    if (optional_param('submit', false, PARAM_BOOL) && question_engine::is_manual_grade_in_range($attemptobj->get_uniqueid(), $slot)) {
        $transaction = $DB->start_delegated_transaction();
        $attemptobj->process_submitted_actions(time());
        $transaction->allow_commit();

        // Log this action.
        $params = [
            'objectid' => $attemptobj->get_question_attempt($slot)->get_question_id(),
            'courseid' => $attemptobj->get_courseid(),
            'context' => $attemptobj->get_quizobj()->get_context(),
            'other' => [
                'quizid' => $attemptobj->get_quizid(),
                'attemptid' => $attemptobj->get_attemptid(),
                'slot' => $slot
            ]
        ];
        $event = \mod_quiz\event\question_manually_graded::create($params);
        $event->trigger();

        echo $output->notification(get_string('changessaved'), 'notifysuccess');
        close_window(2, true);
        die;
    } else {
        $submiterror = true;
    }
}

// Print quiz information.
echo html_writer::div($output->render($summary), 'mb-3');

// Display notification if current mark is invalid.
if ($submiterror) {
    echo $output->notification(get_string('savemanualgradingfailed', 'quiz'), \core\output\notification::NOTIFY_ERROR);
}

//code added froom Beyond Key
$quiz = $DB->get_record('quiz', ['id' => $attemptobj->get_quizid()], 'id, 
teacher_approval');
$isgraded = ai_grade_status($attemptobj->get_quizid(), $attemptobj->get_attemptid(), $attemptobj->get_userid());
$question = $attemptobj->get_question_attempt($slot)->get_question(); 
if ($question->get_type_name() == 'essay' && $isgraded && $quiz->teacher_approval == 1) {
echo '<button class="btn btn-primary ml-0 mb-3" name="my_button" id="id_fill_ai_grade" data-qid="'.$attemptobj->get_question_attempt($slot)->get_question_id().'" type="button">Load AI Grading</button>';
}
//end of code added from beyond key

// Print the comment form.
echo '<form method="post" class="mform" id="manualgradingform" action="' .
        $CFG->wwwroot . '/mod/quiz/comment.php">';
echo $attemptobj->render_question_for_commenting($slot);
?>
<div>
    <input type="hidden" name="attempt" value="<?php echo $attemptobj->get_attemptid(); ?>" />
    <input type="hidden" name="slot" value="<?php echo $slot; ?>" />
    <input type="hidden" name="slots" value="<?php echo $slot; ?>" />
    <input type="hidden" name="sesskey" value="<?php echo sesskey(); ?>" />
</div>
<fieldset class="hidden">
    <div>
        <div class="fitem fitem_actionbuttons fitem_fsubmit mt-3">
            <fieldset class="felement fsubmit">
                <input id="id_submitbutton" type="submit" name="submit" class="btn btn-primary" value="<?php
                        print_string('save', 'quiz'); ?>"/>
            </fieldset>
        </div>
    </div>
</fieldset>
<?php
echo '</form>';
$PAGE->requires->js_init_call('M.mod_quiz.init_comment_popup', null, false, quiz_get_js_module());
//code added from beyond key
$PAGE->requires->js_call_amd('local_quiz/grading_actions_ajax', 'init', array($attemptobj->get_attemptid()));
//end of code added from beyond key
// End of the page.
echo $output->footer();
