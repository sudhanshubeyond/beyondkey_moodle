<?php
defined('MOODLE_INTERNAL') || die();

$functions = [
    'block_xp_leaderboard_get_leaderboard' => [
        'classname'   => 'block_xp_leaderboard_external',
        'methodname'  => 'get_leaderboard',
        'description' => 'Get leaderboard data for the selected course',
        'type'        => 'read',
        'ajax'        => true,
        'capabilities'=> ''
    ]
];
