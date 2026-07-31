<?php

namespace local_proctoringapi;

defined('MOODLE_INTERNAL') || die();

use local_proctoringapi\task\process_proctoring_images;

class observer {
    public static function attempt_submitted(\mod_quiz\event\attempt_submitted $event) {
        $task = new process_proctoring_images();
        $task->set_custom_data([
            'attemptid' => $event->objectid,
            'cmid'    => $event->contextinstanceid,
            'userid' => $event->userid,
            'callquizapi' => true,
        ]);
        \core\task\manager::queue_adhoc_task($task);
    }
}
