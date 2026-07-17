<?php

defined('MOODLE_INTERNAL') || die();

/**
 * Upgrade code for the plugin
 *
 * @package   local_quiz
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Upgrade the plugin instance
 *
 * @param int $oldversion The old version of the plugin
 * @return bool
 */
function xmldb_local_quiz_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    // Check if the plugin is at a version lower than 2025080502
    if ($oldversion < 2025080503) {

        // Define the table and field to be modified
        $table = new xmldb_table('quiz');
        $field_teacher_approval = new xmldb_field('teacher_approval', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '1', 'allowofflineattempts');

        // Conditionally launch add field teacher_approval
        if (!$dbman->field_exists($table, $field_teacher_approval)) {
            $dbman->add_field($table, $field_teacher_approval);
        }

        $field_ai_grading = new xmldb_field('ai_grading', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '1', 'teacher_approval');
        if (!$dbman->field_exists($table, $field_ai_grading)) {
            $dbman->add_field($table, $field_ai_grading);
        }

        // Mark this upgrade step as complete
        upgrade_plugin_savepoint(true, 2025080503, 'local', 'quiz');
    }

    return true;
}
