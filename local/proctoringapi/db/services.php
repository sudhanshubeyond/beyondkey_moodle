<?php
defined('MOODLE_INTERNAL') || die();

$functions = [
    'local_proctoringapi_save_data' => [
        'classname' => 'local_proctoringapi\external\save_proctoring_data',
        'methodname' => 'save_proctoring_data',
        'description' => 'Save proctoring monitoring data',
        'type' => 'write',
        'ajax' => true,
    ],

    'local_proctoringapi_get_ai_report' => [
        'classname'   => 'local_proctoringapi\external\get_ai_report',
        'methodname'  => 'get_ai_report',
        'description' => 'Get proctoring AI report data',
        'type'        => 'read',
        'ajax'        => true,
    ],
];

$services = [
    'Proctoring API Service' => [
        'functions' => [
            'local_proctoringapi_save_data',
            'local_proctoringapi_get_ai_report'
        ],
        'restrictedusers' => 0,
        'enabled' => 1
    ]
];
