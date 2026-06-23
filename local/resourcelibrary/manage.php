<?php
require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/classes/form/upload_form.php');
use local_resourcelibrary\form\upload_form;
$context = context_system::instance();

require_login();
require_capability('local/resourcelibrary:manage', $context);
// $canmanage = has_capability('local/resourcelibrary:manage', $context);

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/resourcelibrary/manage.php'));
$PAGE->set_title(get_string('managefiles', 'local_resourcelibrary'));
$PAGE->set_heading(get_string('managefiles', 'local_resourcelibrary'));

global $DB;

$mform = new upload_form();
if ($mform->is_cancelled()) {
    redirect(new moodle_url('/'));
}

$draftitemid = file_get_submitted_draft_itemid('userfiles');

file_prepare_draft_area(
    $draftitemid,
    $context->id,
    'local_resourcelibrary',
    'libraryfiles',
    0,
    ['subdirs' => 1, 'maxfiles' => -1]
);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Load existing files into form draft area.
    $toform = new stdClass();
    $toform->userfiles = $draftitemid;
    $mform->set_data($toform);
}

if ($data = $mform->get_data()) {

    $fs = get_file_storage();

    // Get files in the draft area (before form submission)
    $files = $fs->get_area_files($context->id, 'local_resourcelibrary', 'libraryfiles', 0, 'filepath, filename', false);
    
    // Save files permanently.
    file_save_draft_area_files(
        $data->userfiles,
        $context->id,
        'local_resourcelibrary',
        'libraryfiles',
        0,
        ['subdirs' => 1, 'maxfiles' => -1]
    );

    redirect(new moodle_url('/local/resourcelibrary/manage.php'));
}

echo $OUTPUT->header();

// Only show form if user has manage capability.

$mform->display();

echo $OUTPUT->footer();