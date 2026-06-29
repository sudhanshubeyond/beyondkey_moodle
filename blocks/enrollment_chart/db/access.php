<?php
defined('MOODLE_INTERNAL') || die();

$capabilities = [
    'block/enrollment_chart:addinstance' => [
        'captype'      => 'write',
        'contextlevel' => CONTEXT_BLOCK,
        'archetypes'   => [
            'manager'        => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'administrator'  => CAP_ALLOW,
        ],
        'clonepermissionsfrom' => 'moodle/site:manageblocks'
    ],
    'block/enrollment_chart:myaddinstance' => [
        'captype'      => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes'   => [
            'manager'        => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'administrator'  => CAP_ALLOW,
        ],
        'clonepermissionsfrom' => 'moodle/my:manageblocks'
    ],
    'block/enrollment_chart:view' => [
        'captype'      => 'read',
        'contextlevel' => CONTEXT_BLOCK,
        'archetypes'   => [
            'manager'        => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'administrator'  => CAP_ALLOW,
            'student'        => CAP_ALLOW,
        ],
    ],
];

