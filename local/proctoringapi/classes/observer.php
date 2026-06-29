<?php

namespace local_proctoringapi;

defined('MOODLE_INTERNAL') || die();

class observer {

    public static function attempt_submitted(\mod_quiz\event\attempt_submitted $event) {
        global $CFG;

        require_once($CFG->libdir . '/filelib.php');

        // error_log('attempt_started event triggered');

        $cmid = $event->contextinstanceid;

        $curl = new \curl();

        $payload = json_encode(['quizID' => $cmid, 'createdOn' => time(), 'modifiedOn' => time()]);

        $response = $curl->post(https://proctoringlms.azurewebsites.net/api/Proctoring/StartQuiz, $payload,
            ['CURLOPT_HTTPHEADER' => ['Content-Type: application/json']]
        );

    }
}
