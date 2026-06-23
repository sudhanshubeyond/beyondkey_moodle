<?php
defined('MOODLE_INTERNAL') || die();

$observers = [
    [
        'eventname'   => '\core\event\course_module_deleted',
        'callback'    => '\\mod_teamsintegration\\observer::course_module_deleted',
        'internal'    => false,
    ],
];
