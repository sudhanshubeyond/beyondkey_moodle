


<?php

defined('MOODLE_INTERNAL') || die();

function xmldb_local_proctoringapi_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2026062911) {
        $table = new xmldb_table('local_proctoring_quiz_startattemptlog');

        if (!$dbman->table_exists($table)) {

            $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null,
                XMLDB_NOTNULL, XMLDB_SEQUENCE, null);

            $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null,
                XMLDB_NOTNULL, null, '0');

            $table->add_field('attemptid', XMLDB_TYPE_INTEGER, '10', null,
                XMLDB_NOTNULL, null, '0');

            $table->add_field('cmid', XMLDB_TYPE_INTEGER, '10', null,
                XMLDB_NOTNULL, null, '0');

            $table->add_field('apiresponse', XMLDB_TYPE_TEXT, null, null,
                null, null, null);

            $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null,
                XMLDB_NOTNULL, null, '0');

            $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

            $table->add_index('userid_idx', XMLDB_INDEX_NOTUNIQUE, ['userid']);
            $table->add_index('attemptid_idx', XMLDB_INDEX_NOTUNIQUE, ['attemptid']);
            $table->add_index('cmid_idx', XMLDB_INDEX_NOTUNIQUE, ['cmid']);

            $dbman->create_table($table);
        }

        // Upgrade savepoint.
        upgrade_plugin_savepoint(true, 2026062911, 'local', 'proctoringapi');
    }

    if ($oldversion < 2026062912) {

        // Define table.
        $table = new xmldb_table('local_proctoring_images');

        // Fields.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL,
            XMLDB_SEQUENCE, null);

        $table->add_field('attemptid', XMLDB_TYPE_INTEGER, '10', null,
            XMLDB_NOTNULL, null, '0');

        $table->add_field('quizid', XMLDB_TYPE_INTEGER, '10', null,
            XMLDB_NOTNULL, null, '0');

        $table->add_field('cmid', XMLDB_TYPE_INTEGER, '10', null,
            XMLDB_NOTNULL, null, '0');

        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null,
            XMLDB_NOTNULL, null, '0');

        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null,
            XMLDB_NOTNULL, null, '0');

        $table->add_field('filepath', XMLDB_TYPE_TEXT, null, null,
            null, null, null);

        $table->add_field('filename', XMLDB_TYPE_CHAR, '255', null,
            null, null, null);

        $table->add_field('webcamraw', XMLDB_TYPE_TEXT, null, null,
            null, null, null);
        $table->add_field('status', XMLDB_TYPE_INTEGER, '1', null,
            XMLDB_NOTNULL, null, '0');

        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null,
            XMLDB_NOTNULL, null, '0');

        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null,
            XMLDB_NOTNULL, null, '0');

        // Primary key.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Indexes.
        $table->add_index('attemptid_idx', XMLDB_INDEX_NOTUNIQUE, ['attemptid']);
        $table->add_index('userid_idx', XMLDB_INDEX_NOTUNIQUE, ['userid']);
        $table->add_index('quizid_idx', XMLDB_INDEX_NOTUNIQUE, ['quizid']);

        // Create table.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Savepoint.
        upgrade_plugin_savepoint(true, 2026062912, 'local', 'proctoringapi');
    }

    if ($oldversion < 2026062913) {
        $table = new xmldb_table('local_proctoring_images');

        // Drop the table if it exists.
        if ($dbman->table_exists($table)) {
            $dbman->drop_table($table);
        }

        // Upgrade savepoint.
        upgrade_plugin_savepoint(true, 2026062913, 'local', 'proctoringapi');
    }

    return true;
}

