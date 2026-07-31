<?php

namespace local_proctoringapi\task;

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot . '/mod/quiz/accessrule/proctoring/lib.php');
require_once($CFG->libdir . '/filelib.php');

class process_proctoring_images extends \core\task\adhoc_task {

    public function execute() {
        global $DB, $CFG;

        $data = $this->get_custom_data();

        $attemptid = $data->attemptid;
        $cmid      = $data->cmid;
        $userid    = $data->userid;

        if ($data->callquizapi == true) {
            $curl = new \curl();
            $curl->setopt([
                'CURLOPT_HTTPHEADER' => [
                    'Content-Type: application/json',
                ],
                'CURLOPT_TIMEOUT_MS' => 5000,       // cap total wait
                'CURLOPT_CONNECTTIMEOUT' => 3,      // cap connection handshake
                'CURLOPT_NOSIGNAL' => 1,
            ]);

            $payload = json_encode([
                'quizID' => (string)$cmid,
                'status' => '',
                'createdOn' => gmdate('Y-m-d\TH:i:s.v\Z'),
                'modifiedOn' => gmdate('Y-m-d\TH:i:s.v\Z'),
                'studentCount' => 0,
            ]);

            $response = $curl->post('https://proctoringlms.azurewebsites.net/api/Proctoring/StartQuiz',
                $payload
            );

            $record = new \stdClass();
            $record->userid       = $userid;
            $record->attemptid    = $attemptid;
            $record->cmid         = $cmid;
            $record->apiresponse  = json_encode($response, true);
            $record->timecreated  = time();

            $insert = $DB->insert_record('local_proctoring_quiz_startattemptlog', $record);
        } else {
            $courseid  = $data->courseid;
            $filename  = $data->filename;
            $webcamraw = $data->webcamraw;

            $webcamraw = base64_decode(explode(',', explode(';', $webcamraw)[1])[1]);

            $azureblobpath = "quizzes/{$cmid}/{$userid}/{$attemptid}/New/webcam_{$filename}.png";

            $bloburl = quizaccess_proctoring_upload_to_azure($webcamraw, $azureblobpath);
        }
    }
}
