<?php

namespace local_quiz\external;

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/externallib.php");
require_once($CFG->dirroot . '/mod/quiz/locallib.php');
require_once($CFG->libdir . '/questionlib.php');
require_once($CFG->dirroot . '/question/engine/lib.php');

use external_api;
use external_function_parameters;
use external_value;
use external_single_structure;
use external_multiple_structure;
use context_course;
use context_module;
use moodle_exception;
use question_engine;

class externallib extends external_api {

    // Define expected parameters for validation
    public static function airesponse_parameters_validater() {
        return new external_function_parameters([
            'userid' => new external_value(PARAM_INT, 'User ID'),
            'courseid' => new external_value(PARAM_INT, 'Course ID'),
            'quizid' => new external_value(PARAM_INT, 'Quiz ID'),
            'attemptid' => new external_value(PARAM_INT, 'Attempt ID'),
            'status' => new external_value(PARAM_INT, 'Status (0 = not graded, 1 = graded)'),
            'grade' => new external_value(PARAM_RAW, 'Grade', VALUE_OPTIONAL),
            'feedbackdesc' => new external_value(PARAM_RAW, 'Feedback description', VALUE_OPTIONAL),
            'errormessage' => new external_value(PARAM_RAW, 'Error Message', VALUE_OPTIONAL),
            'questions' => new external_multiple_structure(
                    new external_single_structure([
                        'id' => new external_value(PARAM_INT, 'ID'),
                        'grade' => new external_value(PARAM_RAW, 'Marks Awarded'),
                        'feedbackdesc' => new external_value(PARAM_RAW, 'AI Feedback Description')
                            ]),
                    'Quiz Question'
            )
        ]);
    }

    public static function airesponse_parameters() {
        return new external_function_parameters([]);
    }

    public static function airesponse() {
        global $DB, $USER, $CFG;

        // Get raw POST data
        $rawdata = file_get_contents('php://input');
        if (!$rawdata) {
            throw new \moodle_exception('No input data received');
        }

        // Decode JSON
        $data = json_decode($rawdata, true);
        if ($data === null) {
            throw new \moodle_exception('Invalid JSON data');
        }

        $fieldparams = self::airesponse_parameters_validater();
        try {

            $params = self::validate_parameters($fieldparams, $data);
            try {

                $transaction = $DB->start_delegated_transaction();
                // Insert or update graderesponse in DB if teacher approval is 1
                $record = new \stdClass();
                $record->userid = $params['userid'];
                $record->courseid = $params['courseid'];
                $record->quizid = $params['quizid'];
                $record->attemptid = $params['attemptid'];
                $record->status = $params['status'];
                $record->grade = $params['grade'];
                $record->feedbackdesc = $params['feedbackdesc'];
                $record->errormessage = isset($params['errormessage']) ? $params['errormessage'] : NULL;
                $record->timemodified = time();
                // $record->question = json_encode($params['questions']);

                $existing = $DB->get_record('quiz_response', ['attemptid' => $params['attemptid']]);
                if ($existing) {
                    $incoming_questions = $params['questions'];
                    $stored_questions = json_decode($existing->question, true);

                    // Loop over existing and apply new grade/feedback
                    foreach ($stored_questions as &$q) {
                        foreach ($incoming_questions as $new) {
                            if ($q['id'] == $new['id'] && ($q['qtype'] == 'coderunner' || $q['qtype'] == 'essay')) {
                                $attemptid = $params['attemptid'];
                                $attempt = $DB->get_record('quiz_attempts', ['id' => $attemptid], '*', MUST_EXIST);
                                $quiz = $DB->get_record('quiz', ['id' => $attempt->quiz], '*', MUST_EXIST);
                                if ($q['qtype'] == 'coderunner' || ((int) $quiz->teacher_approval == 0 && $q['qtype'] == 'essay')) {
                                    $qubaid = $attempt->uniqueid;

                                    $questionid = $q['id'];
                                    $slotrecord = $DB->get_record('question_attempts', [
                                        'questionusageid' => $qubaid,
                                        'questionid' => $questionid
                                            ], 'slot', IGNORE_MISSING);

                                    if (!$slotrecord) {
                                        throw new \moodle_exception("Slot not found for question ID {$questionid}");
                                    }

                                    $slot = $slotrecord->slot;
                                    $comment = $new['feedbackdesc']; // use the AI feedback
                                    //$fraction = $new['grade']; // Moodle expects 0.0 to 1.0

                                    $maxmark = $q['maxmark'];
                                    $grade = $new['grade'];
                                    $fraction = $grade / $maxmark; // Moodle expects 0.0 to 1.0

                                    self::custom_save_grade($qubaid, $slot, $grade, $fraction, $comment, $attempt, $quiz, $q['qtype']);
                                }
                            }
                            if ($q['id'] == $new['id'] && in_array($q['qtype'], ['essay', 'coderunner'])) {
                                $q['grade'] = $new['grade'];
                                $q['feedbackdesc'] = $new['feedbackdesc'];
                                break;
                            }
                        }
                    }

                    $record->question = json_encode($stored_questions, JSON_UNESCAPED_UNICODE);
                    $record->id = $existing->id;
                    $DB->update_record('quiz_response', $record);
                    $message = 'Record has been successfully updated.';

                    $transaction->allow_commit();
                    return ['status' => true, 'message' => $message, 'recordid' => $record->id];
                } else {
                    throw new \moodle_exception("Attemt not found for quizid");
                    // return ['status' => true, 'message' => $message, 'recordid' => $record->id];
                }
            } catch (\Exception $e) {
                // Handle DB errors
                $transaction->rollback($e->getMessage());
                throw new \moodle_exception('Error saving grade: ' . $e->getMessage());
            }
        } catch (\Exception $e) {
            throw new \moodle_exception('Validation failed: ' . $e->getMessage());
        }
    }

    public static function airesponse_returns() {
        return new external_single_structure([
            'status' => new external_value(PARAM_BOOL, 'True if grading succeeded'),
            'message' => new external_value(PARAM_TEXT, 'Result or warning message'),
            'recordid' => new external_value(PARAM_INT, 'Record ID'),
        ]);
    }

    /**
     * Override a student attempt for a CodeRunner/Essey question.
     *
     * @param int $qubaid The ID of the question usage by activity.
     * @param int $slot The question slot in the attempt.
     * @param float $fraction Fraction to assign (0..1).
     * @param string $comment Optional comment.
     */
    public static function custom_save_grade($qubaid, $slot, $grade = 0, $fraction = 0, $comment = '', $attempt, $quiz, $qtype) {
        global $DB;

        if ($qtype == 'coderunner') {
            $attemptstepdatasql = "SELECT qasd.id, qasd.name, qasd.value,
    				qas.id as attemptstepid, qas.sequencenumber, qas.fraction
                                FROM {question_attempts} qa
                                JOIN {question_attempt_steps} qas
                                     ON qas.questionattemptid = qa.id
                                JOIN {question_attempt_step_data} qasd
                                     ON qasd.attemptstepid = qas.id
                                WHERE qa.questionusageid = :questionusageid AND qa.behaviour = :behaviour AND (qasd.name = '-_rawfraction')";
            $params = [
                'questionusageid' => $qubaid,
                'behaviour' => 'adaptive_adapted_for_coderunner'
            ];

            $attemptstepdata = $DB->get_records_sql($attemptstepdatasql, $params);
            foreach ($attemptstepdata as $stepdata) {
                $stepdata->value = $fraction;
                $DB->update_record('question_attempt_step_data', $stepdata);
            }
        }

        $quba = question_engine::load_questions_usage_by_activity($qubaid);

        // Apply grade + comment directly (Moodle 4+ syntax)
        $quba->manual_grade(
                $slot,
                $comment,
                $grade,
                FORMAT_HTML
        );

        // Save grading changes
        question_engine::save_questions_usage_by_activity($quba);

        $attempt->sumgrades = $quba->get_total_mark();
        $DB->update_record('quiz_attempts', $attempt);

        // Update overall quiz grade
        quiz_save_best_grade($quiz, $attempt->userid);
    }
}
