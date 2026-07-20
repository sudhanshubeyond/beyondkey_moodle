<?php

namespace local_proctoringapi;

defined('MOODLE_INTERNAL') || die();

class observer {

    public static function attempt_submitted(\mod_quiz\event\attempt_submitted $event) {
        global $CFG;

        require_once($CFG->libdir . '/filelib.php');

        $cmid = $event->contextinstanceid;

        $curl = new \curl();

        $curl->setopt([
            'CURLOPT_HTTPHEADER' => [
                'Content-Type: application/json'
            ]
        ]);

        $payload = json_encode(['quizID' => $cmid, 'status' => '', 
                'createdOn' => gmdate('Y-m-d\TH:i:s.v\Z'),
                'modifiedOn' => gmdate('Y-m-d\TH:i:s.v\Z'),
                'studentCount' => 0]);

        $response = $curl->post(
            'https://proctoringlms.azurewebsites.net/api/Proctoring/StartQuiz', $payload
        );
    }
}
