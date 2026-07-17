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
 * @package   local_quiz
 * @copyright  Bhupendra Patidar<bhupendra.patidar@beyondkey.com>
 */
defined('MOODLE_INTERNAL') || die();
global $CFG;

require_once(dirname(__FILE__) . '/../../config.php');
require_once($CFG->libdir . '/gradelib.php');
use core_course\customfield\course_handler;

function attempt_submitted(\mod_quiz\event\attempt_submitted $event) {
    try {

        global $DB;

        $attemptid = $event->objectid;
        $userid = $event->userid;
        $user  = core_user::get_user($userid);

        // Get attempt details
        $attempt = $DB->get_record('quiz_attempts', ['id' => $attemptid], '*', MUST_EXIST);

        // Get quiz info
        $quiz = $DB->get_record('quiz', ['id' => $attempt->quiz], '*', MUST_EXIST);

        $fieldshortname = 'ai_required'; // Replace with your field shortname
        $indexingflag = get_course_custom_field_value_quiz($quiz->course, $fieldshortname);

        if ($indexingflag && $quiz->ai_grading) {
            // Get grade info
            $grade = quiz_get_best_grade($quiz, $userid);

            $maxgrade = $quiz->grade; // Total max grade (e.g., 10)
            $gradetopass = '';

            // Get the grade item for the quiz in the course gradebook
            $grade_item = grade_item::fetch(array(
                'itemtype'   => 'mod',
                'itemmodule' => 'quiz',
                'iteminstance' => $quiz->id,
                'courseid'   => $quiz->course,
            ));

            if ($grade_item && $grade_item->gradepass > 0) {
                // gradepass is stored as the raw value (e.g. 5 out of maxgrade 10)
                $gradetopass = $grade_item->gradepass;
            }

            // Load question usage
            $quba = question_engine::load_questions_usage_by_activity($attempt->uniqueid);
            $contextid = $quba->get_owning_context()->id;
            $fs = get_file_storage();

            // Get previous attempts
            $attemptsql = "SELECT id FROM {quiz_attempts}
                           WHERE quiz = :quizid
                           AND userid = :userid
                           AND id <> :excludedattempt
                           AND state = 'finished'
                           ORDER BY attempt ASC";

            $params = [
                'quizid' => $attempt->quiz,
                'userid' => $userid,
                'excludedattempt' => $attemptid
            ];

            $previousattempts = $DB->get_records_sql($attemptsql, $params);
            $previousattemptids = [];
            foreach ($previousattempts as $previousattemptkey => $previousattemptvalue) {
                $previousattemptids[] = $previousattemptkey;
            }

            $previoussubmissions = implode(',', $previousattemptids);

            $fieldshortname = 'indexing_required'; // Replace with your field shortname
            $courseindexing =  get_course_custom_field_value_quiz($quiz->course, $fieldshortname);

            $data = [
                'userid' => $userid,
                'courseid' => $quiz->course,
                'quizid' => $attempt->quiz,
                'quiztitle' => $quiz->name,
                'quizdescription' => $quiz->intro,
                'attemptid' => $attemptid,
                'maxgrade' => intval($maxgrade),
                'gradetopass' => intval($gradetopass),
                'previoussubmissions' => trim($previoussubmissions),
                'studentname' => fullname($user),
                'isdeleted' => false,
                'indexingflag' => ($courseindexing == 1) ? true : false,
                'questions' => []
            ];

            $is_qtype_grage = 0;
            $i = 0;
            foreach ($quba->get_attempt_iterator() as $slot => $qa) {
                $question = $qa->get_question();
                $questionname = format_string($question->name);
                $questiontext = strip_tags(format_text($question->questiontext, $question->questiontextformat));
                $responses = $qa->get_last_qt_data(); // raw response data
                $summary = $qa->get_response_summary(); // readable summary
                if ($summary) {
                    $summarytext = explode('Attachments', $summary);
                    $summary = $summarytext[0];
                }
                $maxmark = $qa->get_max_mark();
                $fraction = $qa->get_fraction();
                // $iscorrect = ($fraction == 1.0 ? "Yes" : "No");
                if ($fraction == 1.0) {
                    $iscorrect = 'Correct';
                } elseif ($fraction == 0) {
                    $iscorrect = 'Incorrect';
                } else {
                    $iscorrect = 'Partially Correct';
                }

                $data['questions'][$i]['id'] = $question->id;
                $data['questions'][$i]['qtitle'] = $questionname;
                $data['questions'][$i]['qtext'] = $questiontext;
                $data['questions'][$i]['qtype'] = $question->qtype->name();
                $data['questions'][$i]['maxmark'] = $maxmark;
                $data['questions'][$i]['answer'] = ($summary == null) ? "No code" : $summary; //$summary;
                $data['questions'][$i]['resultflag'] = $iscorrect;
                $correctresponse = $qa->get_correct_response();
                $rightanswer = $correctresponse ? $question->summarise_response($correctresponse) : 'false';
                $data['questions'][$i]['rightanswer'] = $rightanswer;

                $options = [];
                $data['questions'][$i]['options'] = json_encode($options);

                // Handle by question type
                switch ($question->qtype->name()) {
                    case 'truefalse':
                        $data['questions'][$i]['options'] = json_encode($options);
                        break;

                    case 'match':
                        $options['stems'] = $question->stems;
                        $options['choices'] = $question->choices;
                        $options['right'] = $question->right;
                        $data['questions'][$i]['options'] = json_encode([$options]);
                        break;
                    
                    case 'ddmatch':
                        $options['stems'] = $question->stems;
                        $options['choices'] = $question->choices;
                        $options['right'] = $question->right;
                        $data['questions'][$i]['options'] = json_encode([$options]);
                        break;

                    case 'randomsamatch':
                        $options['stems'] = $question->stems;
                        $options['choices'] = $question->choices;
                        $options['right'] = $question->right;
                        $data['questions'][$i]['options'] = json_encode([$options]);
                        break;

                    case 'ddwtos':
                        $options['choices'] = $question->choices;
                        $options['places'] = $question->choices;
                        $options['rightchoices'] = $question->rightchoices;
                        $data['questions'][$i]['options'] = json_encode([$options]);
                        break;

                    case 'ddimageortext':
                        $data['questions'][$i]['options'] = json_encode($options);
                        break;

                    case 'multianswer':
                        foreach ($question->subquestions as $sub) {
                            $options[] =  $subquestiontext = format_text($sub->questiontext, FORMAT_HTML);
                        }
                        $data['questions'][$i]['options'] = json_encode([$options]);
                        break;

                    case 'gapselect':
                        $options['choices'] = $question->choices;
                        $options['places'] = $question->choices;
                        $options['rightchoices'] = $question->rightchoices;
                        $data['questions'][$i]['options'] = json_encode([$options]);
                        break;

                    case 'ddmarker':
                        $data['questions'][$i]['options'] = json_encode($options);
                        break;

                    case 'coderunner':
                        $is_qtype_grage = 1;

                        $data['questions'][$i]['testcases'] = json_encode($question->testcases);
                        $data['questions'][$i]['options'] = json_encode($options);
                        break;

                    case 'essay':
                        $is_qtype_grage = 1;

                        // Get all steps via full_step_iterator
                        $steps = $quba->get_question_attempt($slot)->get_full_step_iterator();
                        $foundFiles = false;
                        $fileids = [];

                        // Loop over steps in reverse to find the one that has the file
                        foreach (array_reverse(iterator_to_array($steps)) as $step) {
                            $itemid = $step->get_id();

                            $files = $fs->get_area_files(
                                $contextid,
                                'question',
                                'response_attachments',
                                $itemid,
                                'itemid, filepath, filename',
                                false
                            );

                            if (!empty($files)) {
                                foreach ($files as $file) {
                                    $fileids[] = $file->get_id();
                                }

                                $foundFiles = true;
                                break;
                            }
                        }

                        $fileids = implode(',', $fileids);
                        if ($summary) {
                            $summaryanswer = explode('Attachments', $summary);
                            $summary = $summaryanswer[0];
                        }

                        $data['questions'][$i]['options'] = json_encode($options);
                        $data['questions'][$i]['minwordlimit']= $question->minwordlimit ? '"'.$question->minwordlimit.'"' : '';
                        $data['questions'][$i]['maxwordlimit']= $question->maxwordlimit ? '"'.$question->maxwordlimit.'"' : '';
                        $data['questions'][$i]['fileids'] = $fileids;
                        break;

                    default:
                        foreach ($question->answers as $aid => $answer) {
                            $optiontext = format_string($answer->answer);
                            $options[] = $optiontext;
                        }
                        $data['questions'][$i]['options'] = json_encode($options);
                }

                $i++;
            }

            if ($is_qtype_grage) {
                $response = execute_curl_postapi_quiz($data);
                $record = new stdClass();
                $record->userid = $userid;
                $record->courseid = $quiz->course;
                $record->quizid = $attempt->quiz;
                $record->attemptid = $attemptid;
                $record->gradetopass = $gradetopass;
                $record->feedbackdesc = '';

                if (!empty($data['questions'])) {
                    $record->question = json_encode($data['questions']);
                } else {
                    $record->question = '';
                }

                $record->status = ($response->status) ? 1 : 0;
                $record->timecreated = $record->timemodified = time();

                $DB->insert_record('quiz_response', $record);
            }
        }
    } catch (Exception $e) {
        debugging("Error in event {$event->eventname}: " . $e->getMessage(), DEBUG_DEVELOPER);
    }
}

function attempt_deleted(\mod_quiz\event\attempt_deleted $event) {
    global $DB;

    $attemptid = $event->objectid;
    $userid = $event->relateduserid;

    $attempt = $DB->get_record('quiz_response', ['attemptid' => $attemptid, 'userid' => $userid], 'id');
    if ($attempt) {
        $record = new stdClass();
        $record->id = $attempt->id;
        $record->isdeleted = 1;
        $record->timemodified = time();
        $DB->update_record('quiz_response', $record);
    }
}

function get_course_custom_field_value_quiz($courseid, $fieldshortname) {
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
               return 1;  // Return 1
            } else {
               return 0;
            }
        }

        /* if ($fielddata->get_field()->get('shortname') === $fieldshortname) {
            return $fielddata->get_value();
        } */
    }
    return null; // Return null if field not found
}

function get_genapi_headers_quiz() {

    $apikey = get_config('local_quiz', 'api_keys');
    $headers = [
        "x-api-key: $apikey",
        "Content-Type: application/json"
    ];

    return $headers;
}

function execute_curl_postapi_quiz($data) {

    $endpoint = get_config('local_quiz', 'create_end_points');
    $headers = get_genapi_headers_quiz();

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

function get_overall_aifeedback($attemptid, $userid) {
    global $DB;
    $attempt = $DB->get_record('quiz_response', ['attemptid' => $attemptid, 'userid' => $userid], 'feedbackdesc');
    if ($attempt) {
        return $attempt->feedbackdesc;
    } else {
        return '';
    }
}

function ai_grade_status($quizid, $attemptid, $userid) {
    global $DB;
    $status = false;
    
    $sql = "SELECT * FROM {quiz_response}
        WHERE userid = :userid AND quizid = :quizid AND attemptid = :attemptid AND isdeleted = 0 AND grade IS NOT NULL AND grade <> ''";

    $params = [
        'userid' => $userid,
        'quizid' => $quizid,
        'attemptid' => $attemptid
    ];

    $data = $DB->get_record_sql($sql, $params);
    
    if ($data) {
      $status =  true;
    } 
    
    return $status;
}

?>
