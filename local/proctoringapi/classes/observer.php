<?php

namespace local_proctoringapi;

defined('MOODLE_INTERNAL') || die();

// Hemanth added start--
use local_proctoringapi\task\process_proctoring_images;
// Hemanth added end---
class observer {
    public static function attempt_submitted(\mod_quiz\event\attempt_submitted $event) {
        global $CFG;
        require_once($CFG->libdir . '/filelib.php');

        $task = new process_proctoring_images();

        $task->set_custom_data([
            'attemptid' => $event->objectid,
            'userid'    => $event->userid,
            'quizid'    => $event->contextinstanceid,
        ]);
        \core\task\manager::queue_adhoc_task($task);
    }
}
