<?php

function local_proctoringapi_extend_settings_navigation($settings, $context) {
    global $PAGE;

    if (!isset($PAGE->cm) || $PAGE->cm->modname !== 'quiz') {
        return;
    }

    $url = new moodle_url(
        '/local/proctoringapi/proctoring_ai_report.php',
        ['cmid' => $PAGE->cm->id]
    );

    $settings->add(
        get_string('proctoringaireport', 'local_proctoringapi'),
        $url,
        navigation_node::TYPE_SETTING
    );
}
