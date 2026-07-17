<?php

$functions = [
    'local_forum_ai_graderesponse' => [
        'classname'   => 'local_forum\\external\\externallib',
        'methodname'  => 'airesponse',
        'classpath'   => '',
        'description' => 'Insert ai response to a record into mdl_forum_response table',
        'type'        => 'write',
        'ajax'        => false,
        //'capabilities'=> ['mod/forum:manage']
    ],
];
