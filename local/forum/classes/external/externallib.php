<?php
namespace local_forum\external;

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/externallib.php");
require_once($CFG->libdir . '/gradelib.php'); // To make sure grading functions are loaded
require_once($CFG->dirroot . '/mod/forum/lib.php'); // To make sure forum-related functions are loaded
use external_api;
use external_function_parameters;
use external_value;
use external_single_structure;
use external_multiple_structure;
use context_course;
use context_module;
use moodle_exception;

use core_grades\component_gradeitem;


class externallib extends external_api {

    // Define expected parameters for validation
    public static function airesponse_parameters_validater($data) {
	if ($data['gradingtype'] == 'rubric') {
        return new external_function_parameters([
            'userid' => new external_value(PARAM_INT, 'User ID'),
            'courseid' => new external_value(PARAM_INT, 'Course ID'),
            'forumid' => new external_value(PARAM_INT, 'Quiz ID'),
            'attemptid' => new external_value(PARAM_INT, 'Attempt ID'),
            'postid' => new external_value(PARAM_INT, 'Post ID'),
            'status' => new external_value(PARAM_INT, 'Status (0 = not graded, 1 = graded)'),
            'grade' => new external_value(PARAM_RAW, 'Grade', VALUE_OPTIONAL),
            'gradingtype' => new external_value(PARAM_RAW, 'Grade', VALUE_OPTIONAL),
            'feedbackdesc' => new external_value(PARAM_RAW, 'Feedback description', VALUE_OPTIONAL),
            'rubricbreakdown' => new external_multiple_structure(
                new external_single_structure([
                    'criterionid' => new external_value(PARAM_INT, 'Criterion ID'),
                    'selectedlevelid' => new external_value(PARAM_INT, 'Selected Level ID'),
                    'marksawarded' => new external_value(PARAM_FLOAT, 'Marks Awarded'),
                    'feedback' => new external_value(PARAM_TEXT, 'Criterion Feedback')
                ]),
                'Rubric Breakdown'
            ),
            'errormessage' => new external_value(PARAM_RAW, 'Error Message', VALUE_OPTIONAL),
        ]);
	} else {
	    return new external_function_parameters([
                'userid' => new external_value(PARAM_INT, 'User ID'),
                'courseid' => new external_value(PARAM_INT, 'Course ID'),
                'forumid' => new external_value(PARAM_INT, 'Quiz ID'),
                'attemptid' => new external_value(PARAM_INT, 'Attempt ID'),
    	        'postid' => new external_value(PARAM_INT, 'Post ID'),
                'status' => new external_value(PARAM_INT, 'Status (0 = not graded, 1 = graded)'),
                'grade' => new external_value(PARAM_RAW, 'Grade', VALUE_OPTIONAL),
                'gradingtype' => new external_value(PARAM_RAW, 'Grade', VALUE_OPTIONAL),
                'feedbackdesc' => new external_value(PARAM_RAW, 'Feedback description', VALUE_OPTIONAL),
                'rubricbreakdown' => new external_value(PARAM_RAW, 'Rubric Breakdown', VALUE_OPTIONAL),
                'errormessage' => new external_value(PARAM_RAW, 'Error Message', VALUE_OPTIONAL),
            ]);
	}
    }

    public static function airesponse_parameters() {
        return new external_function_parameters([]);
    }

    public static function airesponse() {
        global $DB, $CFG, $USER;;

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

        $fieldparams = self::airesponse_parameters_validater($data);
        try {

            $params = self::validate_parameters($fieldparams, $data);
            try {

                $transaction = $DB->start_delegated_transaction();
                $forum = $DB->get_record('forum', ['id' => $params['forumid']], 'id, teacher_approval', MUST_EXIST);
                
                if ((int)$forum->teacher_approval === 0) {

                    // Get course module id for the forum
                    $cm = get_coursemodule_from_instance('forum', $params['forumid'], $params['courseid']);
                    if (!$cm) {
                        throw new \moodle_exception('Invalid course module ID');
                    }

                    // Validate context and capability
                    $context = context_module::instance($cm->id);
                    self::validate_context($context);
                    require_capability('mod/forum:grade', $context);


                    $gradeitem = component_gradeitem::instance('mod_forum', $context, 'forum');
                    $student = \core_user::get_user($params['userid']);
                    if (!$student) {
                        throw new \moodle_exception('Invalid student ID');
                    }

                    $grader = \core_user::get_user(2);
                    if (!$grader) {
                        throw new \moodle_exception('Invalid grader ID');
                    }

                    try {
                    	if (!empty($params['status']) && isset($params['grade']) && $params['grade'] !== '') {
                        	if ($params['gradingtype'] == 'rubric') {
                                $criteria = [];
                                foreach ($params['rubricbreakdown'] as $row) {
                                    $criteria[$row['criterionid']] = [
                                        'levelid' => $row['selectedlevelid'],
                                        'remark' => $row['feedback']
                                    ];
                                }
                                $rubricdata = ['criteria' => $criteria];


            				    $grade = $gradeitem->get_grade_for_user($student, $grader);
                                $instance = $gradeitem->get_advanced_grading_instance($grader, $grade);

            	                // Store the grade with rubric data
                    	        $gradeitem->store_grade_from_formdata($student, $grader, (object) [
                                    'instanceid' => $instance->get_id(), // Forum instance ID
                                    'advancedgrading' => $rubricdata, // The rubric grading data
    	                        ]);
        	                } else {
                            	$gradeitem->store_grade_from_formdata($student, $grader, (object) ['grade' => $params['grade']]);
                        	}
                    	}
                        //$transaction->allow_commit();
        		    } catch (Exception $e) {
                        $transaction->rollback($e->getMessage());
                    	throw new \moodle_exception('Error saving grade: ' . $e->getMessage());
                    }
		            // return ['status' => true, 'message' => 'Grade saved and gradebook updated.', 'graderesponseid' => 0];
                }
                
                // Insert or update graderesponse in DB if teacher approval is 1
                $record = new \stdClass();
                $record->userid = $params['userid'];
                $record->courseid = $params['courseid'];
                $record->forumid = $params['forumid'];
                $record->attemptid = $params['attemptid'];
                $record->status = $params['status'];
                $record->grade = $params['grade'];
                $record->feedbackdesc = $params['feedbackdesc'];
                $record->gradingtype = $params['gradingtype'];
                $record->rubricbreakdown = isset($params['rubricbreakdown']) ? json_encode($params['rubricbreakdown']) : '';
                $record->errormessage = ($params['status']) ? NULL : $params['errormessage'];
                $record->timemodified = time();
                // $record->question = json_encode($params['questions']);

                $existing = $DB->get_record('forum_graderesponse', ['forumid' => $params['forumid'], 'userid' => $params['userid']]);
                if ($existing) {
                    $record->id = $existing->id;
                    $DB->update_record('forum_graderesponse', $record);
                    $message = 'Response has been successfully store.';
                    $status = true;
                } else {
                    $record->id = 0;
                    $message = 'Recored not exist in the LMS.';
                    $status = false;
                }

                $transaction->allow_commit();
                return ['status' => $status, 'message' => $message, 'graderesponseid' => $record->id];

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
            'graderesponseid' => new external_value(PARAM_INT, 'Record ID'),
        ]);
    }
}
