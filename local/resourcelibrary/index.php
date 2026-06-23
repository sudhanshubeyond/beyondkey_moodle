<?php
require_once(__DIR__ . '/../../config.php');
$context = context_system::instance();

require_login();
//require_capability('local/resourcelibrary:view', context_system::instance()); // User must have 'view' capability
// $context = context_user::instance($USER->id);
// $usercontext = $context;

$PAGE->set_url('/local/resourcelibrary/index.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_title('Resource Library');
$PAGE->requires->css('/local/resourcelibrary/styles.css');

echo $OUTPUT->header();
echo $OUTPUT->heading('Resource Library');

$roleids = [7];
$roles = get_user_roles($context, $USER->id, false);
foreach ($roles as $role) {
    $roleids[] = $role->roleid;
    // echo "User's role at system level: " . $role->shortname . "<br>";
}   

// Get all courses the user is enrolled in
$sql = "SELECT c.id
        FROM {course} c
        JOIN {enrol} e ON e.courseid = c.id
        JOIN {user_enrolments} ue ON ue.enrolid = e.id
        WHERE ue.userid = :userid";
$courses = $DB->get_records_sql($sql, ['userid' => $USER->id]);

// Loop through courses to get user roles in each course context
foreach ($courses as $course) { 
    $coursecontext = context_course::instance($course->id);
    $roles_in_course = get_user_roles($coursecontext, $USER->id, true);
    foreach ($roles_in_course as $assignment) {
        $roleids[] = $assignment->roleid;
    }
}

$fs = get_file_storage();
// Retrieve files in your filearea
$files = $fs->get_area_files($context->id, 'local_resourcelibrary', 'libraryfiles', 0, 'filepath, filename', false);

$table = new html_table();
$table->head = ['Filename', 'Filepath', 'Mimetype', 'Action'];

// Loop through files and add rows.
foreach ($files as $file) {

    if ($file->get_accessrole() != 0 && !is_siteadmin($USER)) {
        if (!$file->get_accessrole()) {
            continue;
        } elseif ($file->get_accessrole()) {
            $accessflag = false;
            $roles = explode(', ', $file->get_accessrole());
            foreach ($roles as $role) {
                if (in_array($role, $roleids)){
                    $accessflag = true;
                }    
            }
            if (!$accessflag){
                continue;
            }     
        }
    }

    // if (!in_array($file->get_role(), $roleids) && !is_siteadmin($USER) && $file->get_role()){
    //     continue;
    // }

    if ($file->is_directory()) {
        continue;
    }

    $fileurl = moodle_url::make_pluginfile_url(
        $file->get_contextid(),
        $file->get_component(),
        $file->get_filearea(),
        $file->get_itemid(),
        $file->get_filepath(),
        $file->get_filename()
    );

    $table->data[] = [
        $file->get_filename(),
        $file->get_filepath(),
        $file->get_mimetype(),
        html_writer::link($fileurl, 'View')
    ];
}

echo html_writer::table($table);

// Only show form if user has manage capability.
if ($canmanage) {
    $mform->display();
}

echo $OUTPUT->footer();