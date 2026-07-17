<?php
defined('MOODLE_INTERNAL') || die();

/**
 * Executes on plugin installation.
 */
function xmldb_local_assign_submission_install() {
    global $DB;

    $dbman = $DB->get_manager();

    $table = new xmldb_table('assign');

    // teacher_approval field.
    $teacherapproval = new xmldb_field(
        'teacher_approval',
        XMLDB_TYPE_INTEGER,
        '1',
        null,
        XMLDB_NOTNULL,
        null,
        '1'
    );

    if (!$dbman->field_exists($table, $teacherapproval)) {
        $dbman->add_field($table, $teacherapproval);
    }

    // ai_grading field.
    $aigrading = new xmldb_field(
        'ai_grading',
        XMLDB_TYPE_INTEGER,
        '1',
        null,
        XMLDB_NOTNULL,
        null,
        '1'
    );

    if (!$dbman->field_exists($table, $aigrading)) {
        $dbman->add_field($table, $aigrading);
    }
}