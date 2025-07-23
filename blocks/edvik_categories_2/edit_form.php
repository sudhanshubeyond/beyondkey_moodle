<?php
require_once($CFG->dirroot . '/theme/edvik/inc/course_handler/edvik_course_handler.php');

class block_edvik_categories_2_edit_form extends block_edit_form {

    protected function specific_definition($mform) {

        global $CFG;
        $edvikFontList = include($CFG->dirroot . '/theme/edvik/inc/font_handler/edvik_font_select.php');
        $edvikCourseHandler = new edvikCourseHandler();
        $edvikCourseCategories = $edvikCourseHandler->edvikListCategories();

        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));

        // Class
        $mform->addElement('text', 'config_class', get_string('config_class', 'theme_edvik'));
        $mform->setDefault('config_class', 'category-wrap bg-spring position-relative index-1 ptb-100 round-10');
        $mform->setType('config_class', PARAM_RAW);

        // Top Title
        $mform->addElement('text', 'config_top_title', 'Top Title');
        $mform->setDefault('config_top_title', 'TOP CATEGORIES');
        $mform->setType('config_top_title', PARAM_RAW);

        // Title
        $mform->addElement('text', 'config_title', get_string('config_title', 'theme_edvik'));
        $mform->setDefault('config_title', 'Our Top Categories to learn');
        $mform->setType('config_title', PARAM_RAW);

        $items = 8;
        if(isset($this->block->config->items)){
            $items = $this->block->config->items;
        }

        $items_range = array(
            1 => '1',
            2 => '2',
            3 => '3',
            4 => '4',
            5 => '5',
            6 => '6',
            7 => '7',
            8 => '8',
            9 => '9',
            10 => '10',
            11 => '11',
            12 => '12',
            13 => '13',
            14 => '14',
            15 => '15',
            16 => '16',
            17 => '17',
            18 => '18',
            19 => '19',
            20 => '20',
            21 => '21',
            22 => '22',
            23 => '23',
            24 => '24',
            25 => '25',
            26 => '26',
            27 => '27',
            28 => '28',
            29 => '29',
            30 => '30',
        );
        $items_max = 30;

        $mform->addElement('select', 'config_items', get_string('config_items', 'theme_edvik'), $items_range);
        $mform->setDefault('config_items', 8);

        for($i = 1; $i <= $items; $i++) {
            $mform->addElement('header', 'config_edvik_item' . $i , get_string('config_item', 'theme_edvik') .' '. $i);

            $options = array(
                'multiple' => false,
            );

            $mform->addElement('autocomplete', 'config_category' . $i, get_string('category'), $edvikCourseCategories, $options);

            $mform->addElement('text', 'config_img' . $i, 'Category Icon ' . $i);
            if($i <= 7):
                $mform->setDefault('config_img' . $i, $CFG->wwwroot.'/theme/edvik/pix/categories/cat2-'.$i.'.svg');
            else:
                $mform->setDefault('config_img' . $i, $CFG->wwwroot.'/theme/edvik/pix/categories/cat2-1.svg');
            endif;
            $mform->setType('config_img' . $i, PARAM_TEXT);
        }

        $mform->addElement('header', 'config_edvik_content', 'Section Content');

        // Content
        $mform->addElement('textarea', 'config_body', get_string('config_body', 'theme_edvik'));
        $mform->setDefault('config_body', 'Explore all of our categories and pick your suitable ones to enroll and start learning with us! <a href="#" class="ms-1 link style-three font-regular">View All Categories<i class="ri-arrow-right-line"></i></a>');
        $mform->setType('config_body', PARAM_RAW); 
    }
}
