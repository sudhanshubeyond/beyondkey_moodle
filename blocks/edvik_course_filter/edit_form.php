<?php

class block_edvik_course_filter_edit_form extends block_edit_form {

    protected function specific_definition($mform) {

        global $CFG;
        $edvikFontList = include($CFG->dirroot . '/theme/edvik/inc/font_handler/edvik_font_select.php');

        // $style = 1;
        // if(isset($this->block->config->style)){
        //     $style = $this->block->config->style;
        // }

        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));

        // $mform->addElement('select', 'config_style', get_string('config_style', 'theme_edvik'), array(1 => 'Style 1', 2 => 'Style 2', 3 => 'Style 3', 4 => 'Style 4'));
        // $mform->setDefault('config_style', 1);
        
        // Title
        $mform->addElement('text', 'config_title', 'Top Title');
        $mform->setDefault('config_title', 'FEATURED COURSES');
        $mform->setType('config_title', PARAM_RAW);

        // Title
        $mform->addElement('text', 'config_subtitle', 'Title');
        $mform->setDefault('config_subtitle', 'Our Top Courses to learn');
        $mform->setType('config_subtitle', PARAM_RAW);

        // Banner Shape Image
        $mform->addElement('text', 'config_title_shape', ' Title Shape Image');
        $mform->setDefault('config_title_shape', EDVIK_IMG .'section-title-shape-1.webp');
        $mform->setType('config_title_shape', PARAM_TEXT);

        // Button Text
        $mform->addElement('text', 'config_button_text', get_string('config_button_text', 'theme_edvik'));
        $mform->setDefault('config_button_text', 'Explore All Courses');
        $mform->setType('config_button_text', PARAM_RAW);

        // Button Link
        $mform->addElement('text', 'config_button_link', get_string('config_button_link', 'theme_edvik'));
        $mform->setDefault('config_button_link', $CFG->wwwroot . '/course');
        $mform->setType('config_button_link', PARAM_RAW);

        $options = array(
            '0' => 'Hidden',
            '1' => 'Visible',
        );
        $select = $mform->addElement('select', 'config_price', get_string('config_price', 'theme_edvik'), $options);
        $select->setSelected('1');

        // All Text
        $mform->addElement('text', 'config_alltitle', get_string('config_alltitle', 'theme_edvik'));
        $mform->setDefault('config_alltitle', 'All');
        $mform->setType('config_alltitle', PARAM_RAW);

        $options = array(
            'multiple' => true,
            'noselectionstring' => get_string('select_from_dropdown_multiple', 'theme_edvik'),
        );
        $mform->addElement('course', 'config_courses', get_string('courses'), $options);

                // Button Text
                $mform->addElement('text', 'config_course_btn', 'Enroll Button Text');
                $mform->setDefault('config_course_btn', 'Enroll Now');
                $mform->setType('config_course_btn', PARAM_RAW);
    }
}
