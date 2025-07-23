<?php
defined('MOODLE_INTERNAL') || die();
echo $OUTPUT->doctype();

include($CFG->dirroot . '/theme/edvik/inc/edvik_themehandler.php');

$bodyattributes = $OUTPUT->body_attributes();
include($CFG->dirroot . '/theme/edvik/inc/edvik_themehandler_context.php');

echo $OUTPUT->render_from_template('theme_edvik/edvik_dashboard', $templatecontext);