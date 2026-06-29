<?php
defined('MOODLE_INTERNAL') || die();

function local_resourcelibrary_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = []) {
    if ($filearea !== 'libraryfiles') return false;

    require_login();

    $fs = get_file_storage();

    $itemid = array_shift($args);
    $filename = array_pop($args);

    $filepath = '/';
    if (!empty($args)) {
        $filepath .= implode('/', $args) . '/';
    }

    $file = $fs->get_file($context->id, 'local_resourcelibrary', $filearea, $itemid, $filepath, $filename);
    if (!$file || $file->is_directory()) return false;

    $options['mimetype'] = $file->get_mimetype();
    $options['forcedownload'] = false;

    send_stored_file($file, 0, 0, false, $options);
}



