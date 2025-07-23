<?php

class block_edvik_course_filter_3_edit_form extends block_edit_form {

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


        // Shape Image 1
        $mform->addElement('text', 'config_shape1', ' Shape Image 1');
        $mform->setDefault('config_shape1', EDVIK_IMG .'shape-11.webp');
        $mform->setType('config_shape1', PARAM_TEXT);

        // Shape Image 2
        $mform->addElement('text', 'config_shape2', ' Shape Image 2');
        $mform->setDefault('config_shape2', EDVIK_IMG .'shape-2.webp');
        $mform->setType('config_shape2', PARAM_TEXT);

        $mform->addElement('header', 'config_edvik_content', 'Section Content');

        // Content
        $mform->addElement('textarea', 'config_body', get_string('config_body', 'theme_edvik'));
        $mform->setDefault('config_body', 'Explore all of our categories and pick your suitable ones to enroll and start learning with us! <a href="#" class="ms-1 link style-three font-regular">View All Categories<i class="ri-arrow-right-line"></i></a>');
        $mform->setType('config_body', PARAM_RAW); 
    }
}
