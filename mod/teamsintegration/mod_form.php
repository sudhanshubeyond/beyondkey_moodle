<?php
require_once("$CFG->dirroot/course/moodleform_mod.php");

class mod_teamsintegration_mod_form extends moodleform_mod {
    function definition() {
        $mform = $this->_form;

        $mform->addElement('text', 'name', get_string('meetingname', 'mod_teamsintegration'), ['size' => '64']);
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');

        $mform->addElement('date_time_selector', 'starttime', get_string('startdate', 'mod_teamsintegration'));
        $mform->addElement('date_time_selector', 'endtime', get_string('enddate', 'mod_teamsintegration'));

        // add description field (intro) provided by the course module API
        $this->standard_intro_elements();

        $this->standard_coursemodule_elements();
        $this->add_action_buttons();
    }

    public function validation($data, $files) {
        $errors = parent::validation($data, $files);
        if (!empty($data['starttime']) && !empty($data['endtime'])) {
            if ($data['endtime'] <= $data['starttime']) {
                $errors['endtime'] = get_string('endtimegreater', 'mod_teamsintegration');
            }
        }
        return $errors;
    }
}
