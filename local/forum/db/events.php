<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 *
 * @package   local_forum
 */
defined('MOODLE_INTERNAL') || die();

$observers = array(
    array(
       'eventname' => '\mod_forum\event\post_created',
       'includefile' => '/local/forum/locallib.php',
       'callback' => 'post_created',
       'internal' => false,
    ),
    array(
       'eventname' => '\mod_forum\event\post_updated',
       'includefile' => '/local/forum/locallib.php',
       'callback' => 'post_updated',
       'internal' => false,
    ),
    array(
       'eventname' => '\mod_forum\event\post_deleted',
       'includefile' => '/local/forum/locallib.php',
       'callback' => 'post_deleted',
       'internal' => false,
    ),
    array(
       'eventname' => '\mod_forum\event\discussion_created',
       'includefile' => '/local/forum/locallib.php',
       'callback' => 'discussion_created',
       'internal' => false,
    ),
    array(
       'eventname' => '\mod_forum\event\discussion_deleted',
       'includefile' => '/local/forum/locallib.php',
       'callback' => 'discussion_deleted',
       'internal' => false,
    ),
    array(
       'eventname' => '\mod_forum\event\discussion_updated',
       'includefile' => '/local/forum/locallib.php',
       'callback' => 'discussion_updated',
       'internal' => false,
    ), 
);
