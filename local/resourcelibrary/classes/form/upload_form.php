<?php
namespace local_resourcelibrary\form;

defined('MOODLE_INTERNAL') || die();

// Include Moodle's formslib
require_once("$CFG->libdir/formslib.php");

// Import necessary core context classes
use context_system;
use role;

class upload_form extends \moodleform {
    public function definition() {
        $mform = $this->_form;

        // Add the filemanager element to upload files
        $mform->addElement('filemanager', 'userfiles', get_string('uploadfile', 'local_resourcelibrary'), null, [
            'subdirs' => 1,
            'maxfiles' => -1,
            'accepted_types' => '*',
        ]);
        
        // Add the submit button
        $mform->addElement('submit', 'submitbutton', get_string('savechanges'));

        global $PAGE;
        $PAGE->requires->js_init_code('
            YUI().use("node", function(Y) {
                var form = Y.one(".mform");  // Target the form using the default "mform" class
                if (form) {
                    form.addClass("custom-form-class");  // Add your custom class
                }
            });
        ');
        
        
    }
}
