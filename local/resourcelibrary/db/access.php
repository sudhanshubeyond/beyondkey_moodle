<?php
defined('MOODLE_INTERNAL') || die();

$capabilities = [
    'local/resourcelibrary:manage' => [
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => [
            'manager' => CAP_ALLOW,
            'administrator' => CAP_ALLOW,
        ],
    ],
    'local/resourcelibrary:view' => [
        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => [
            'student' => CAP_ALLOW,
            'teacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW,
            'administrator' => CAP_ALLOW,
        ],
    ],
];
