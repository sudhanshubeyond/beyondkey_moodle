<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.

/**
 * Upgrade script for teamsintegration module
 *
 * @package    mod_teamsintegration
 * @copyright  2026 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

function xmldb_teamsintegration_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2026031200) {
        // add intro/introformat fields to table if missing
        $table = new xmldb_table('teamsintegration');
        $field = new xmldb_field('intro', XMLDB_TYPE_TEXT, null, null, false, null, null, 'endtime');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        $field = new xmldb_field('introformat', XMLDB_TYPE_INTEGER, '4', null, false, false, '0', 'intro');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        upgrade_mod_savepoint(true, 2026031200, 'teamsintegration');
    }

    if ($oldversion < 2026031201) {
        $table = new xmldb_table('teamsintegration');
        $field = new xmldb_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, null, null, 0);
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        upgrade_mod_savepoint(true, 2026031201, 'teamsintegration');
    }

    if ($oldversion < 2026031202) {
        $table = new xmldb_table('teamsintegration_attendance');
        $field = new xmldb_field('addedat', XMLDB_TYPE_INTEGER, '10', null, false, null, null);
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        upgrade_mod_savepoint(true, 2026031202, 'teamsintegration');
    }

    return true;
}
