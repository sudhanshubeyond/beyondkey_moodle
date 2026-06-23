<?php
defined('MOODLE_INTERNAL') || die();

$functions = [
    'local_proctoringapi_save_data' => [
        'classname' => 'local_proctoringapi\external\save_proctoring_data',
        'methodname' => 'save_proctoring_data',
        'description' => 'Save proctoring monitoring data',
        'type' => 'write',
        'ajax' => true,
    ]
];

$services = [
    'Proctoring API Service' => [
        'functions' => [
            'local_proctoringapi_save_data'
        ],
        'restrictedusers' => 0,
        'enabled' => 1
    ]
];