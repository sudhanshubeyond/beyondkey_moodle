<?php

namespace local_proctoringapi\external;

defined('MOODLE_INTERNAL') || die();

use core_external\external_api;
use core_external\external_function_parameters;
use core_external\external_value;
use core_external\external_single_structure;
use context_system;

class save_proctoring_data extends external_api {

    public static function save_proctoring_data_parameters() {
        return new external_function_parameters([
            'StudentID' => new external_value(PARAM_INT),
            'AttemptID' => new external_value(PARAM_INT),
            'QuizID' => new external_value(PARAM_INT),
            'focus_score_percent' => new external_value(PARAM_INT),
            'cheating_risk_percent' => new external_value(PARAM_INT),
            'FinalObservations' => new external_value(PARAM_TEXT),
            'risk_level' => new external_value(PARAM_TEXT),
            'ErrorMessage' => new external_value(PARAM_TEXT)
        ]);
    }

    public static function save_proctoring_data($StudentID, $AttemptID, $QuizID, $focus_score_percent, $cheating_risk_percent, $FinalObservations, $risk_level, $ErrorMessage) {
        global $DB;

        $context = context_system::instance();
        self::validate_context($context);

        $record = new \stdClass();
        $record->studentid = $StudentID;
        $record->attemptid = $AttemptID;
        $record->errormessage = $ErrorMessage;
        $record->quizid = $QuizID;
        $record->focus_score_percent = $focus_score_percent;
        $record->cheating_risk_percent = $cheating_risk_percent;
        $record->risk_level = $risk_level;
        $record->finalobservations = $FinalObservations;
        $record->timecreated = time();

        $existing = $DB->get_record(
            'local_proctoring_results',
            [
                'studentid' => $StudentID,
                'attemptid' => $AttemptID,
                'quizid' => $QuizID
            ]
        );

        if ($existing) {
            $record->id = $existing->id;
            $DB->update_record('local_proctoring_results', $record);
            $message = 'Record updated successfully';
        } else {
            $DB->insert_record('local_proctoring_results', $record);
            $message = 'Record inserted successfully';
        }

        return [
            'status' => true,
            'message' => $message
        ];
    }

    public static function save_proctoring_data_returns() {
        return new external_single_structure([
            'status' => new external_value(PARAM_BOOL),
            'message' => new external_value(PARAM_TEXT)
        ]);
    }
}
