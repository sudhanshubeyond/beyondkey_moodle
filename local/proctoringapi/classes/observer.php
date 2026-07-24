<?php

namespace local_proctoringapi;

defined('MOODLE_INTERNAL') || die();

class observer {

/*    public static function attempt_submitted(\mod_quiz\event\attempt_submitted $event) {
        global $CFG;

        require_once($CFG->libdir . '/filelib.php');

        $cmid = $event->contextinstanceid;

        $curl = new \curl();

        $curl->setopt([
            'CURLOPT_HTTPHEADER' => [
                'Content-Type: application/json'
            ],
            'CURLOPT_TIMEOUT' => 300,
            'CURLOPT_CONNECTTIMEOUT' => 10,
        ]);

        $payload = json_encode(['quizID' => $cmid, 'status' => '',
            'createdOn' => gmdate('Y-m-d\TH:i:s.v\Z'),
            'modifiedOn' => gmdate('Y-m-d\TH:i:s.v\Z'),
            'studentCount' => 0]);

        $response = $curl->post(
                'https://proctoringlms.azurewebsites.net/api/Proctoring/StartQuiz', $payload
        );
    }*/

public static function attempt_submitted(\mod_quiz\event\attempt_submitted $event) {
    global $CFG, $DB;
    require_once($CFG->libdir . '/filelib.php');
 
    $cmid = $event->contextinstanceid;
 
    $curl = new \curl();
    $curl->setopt([
        'CURLOPT_HTTPHEADER' => [
            'Content-Type: application/json',
        ],
        'CURLOPT_TIMEOUT_MS' => 1000,       // cap total wait
        'CURLOPT_CONNECTTIMEOUT' => 3,      // cap connection handshake
        'CURLOPT_NOSIGNAL' => 1,
    ]);
 
    $payload = json_encode([
        'quizID' => $cmid,
        'status' => '',
        'createdOn' => gmdate('Y-m-d\TH:i:s.v\Z'),
        'modifiedOn' => gmdate('Y-m-d\TH:i:s.v\Z'),
        'studentCount' => 0,
    ]);
    
    $response = $curl->post(
        'https://proctoringlms.azurewebsites.net/api/Proctoring/StartQuiz',
        $payload
    );

    $record = new \stdClass();
    $record->userid       = $userid;
    $record->attemptid    = $attemptid;
    $record->cmid         = $cmid;
    $record->apiresponse     = json_encode($response, true);
    $record->timecreated  = time();
  
    $insert = $DB->insert_record('local_proctoring_quiz_startattemptlog', $record);
    
}

}
