<?php

defined('MOODLE_INTERNAL') || die();

/**
 * Upgrade code for the plugin
 *
 * @package   local_resourcelibrary
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Upgrade the plugin instance
 *
 * @param int $oldversion The old version of the plugin
 * @return bool
 */
function xmldb_local_resourcelibrary_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    // Check if the plugin is at a version lower than 2025050502
    if ($oldversion < 2025120409) {

        // Define the table and field to be modified
        $table = new xmldb_table('files');
        $field = new xmldb_field('accessrole', XMLDB_TYPE_CHAR, '40', null, null, null, '0', 'license');

        // Conditionally launch add field teacher_approval
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Mark this upgrade step as complete
        upgrade_plugin_savepoint(true, 2025120409, 'local', 'resourcelibrary');
    }

    return true;
}
