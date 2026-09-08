<?php

defined('MOODLE_INTERNAL') || die();

$functions = [

    'local_naturalsearch_search' => [
        'classname'   => 'local_naturalsearch\external\search',
        'methodname'  => 'execute',
        'description' => 'Perform natural language search for Moodle courses and course content.',
        'type'        => 'read',
        'ajax'        => true,
    ],

];