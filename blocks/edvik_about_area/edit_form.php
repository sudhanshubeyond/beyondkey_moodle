<?php

class block_edvik_about_area_edit_form extends block_edit_form {

    protected function specific_definition($mform) {

        global $CFG;

        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));

        // $mform->addElement('select', 'config_style', get_string('config_style', 'theme_edvik'), array(1 => 'Style 1', 2 => 'Style 2'));
        // $mform->setDefault('config_style', 1);

        // Class
        $mform->addElement('text', 'config_class', get_string('config_class', 'theme_edvik'));
        $mform->setDefault('config_class', 'about-wrap style-one ptb-100​');
        $mform->setType('config_class', PARAM_RAW);

        // Top Title
        $mform->addElement('text', 'config_top_title', 'Top Title');
        $mform->setDefault('config_top_title', 'ABOUT EDVIK​');
        $mform->setType('config_top_title', PARAM_RAW);

        // Title
        $mform->addElement('text', 'config_title', get_string('config_title', 'theme_edvik'));
        $mform->setDefault('config_title', 'Affordable online courses &');
        $mform->setType('config_title', PARAM_RAW);

        // Mid Title
        $mform->addElement('text', 'config_mid_title', 'Middle Title');
        $mform->setDefault('config_mid_title', 'learning');
        $mform->setType('config_mid_title', PARAM_RAW);

        // Last Title
        $mform->addElement('text', 'config_last_title', 'Last Title');
        $mform->setDefault('config_last_title', 'opportunities​');
        $mform->setType('config_last_title', PARAM_RAW);

        // Content
        $mform->addElement('textarea', 'config_body', get_string('config_body', 'theme_edvik'), 'wrap="virtual" rows="6" cols="50"');
        $mform->setDefault('config_body', 'Break into a new field like information technology o data science. No prior experience necessary to get started.​');
        $mform->setType('config_body', PARAM_RAW);

        // List
        $mform->addElement('textarea', 'config_lists', 'Lists (add comma for a new item)', 'wrap="virtual" rows="6" cols="50"');
        $mform->setDefault('config_lists', 'Expert Instructors, Remote Learning, Lifetime Free Access, Self Development​');
        $mform->setType('config_lists', PARAM_RAW);

        $mform->addElement('text', 'config_list_icon', 'List Icon Image');
        $mform->setDefault('config_list_icon', EDVIK_IMG .'icons/check.svg');
        $mform->setType('config_list_icon', PARAM_TEXT);

        //  Button Text
        $mform->addElement('text', 'config_btn', get_string('config_button_text', 'theme_edvik'));
        $mform->setDefault('config_btn', 'Learn More');
        $mform->setType('config_btn', PARAM_RAW);

        //  Button Link
        $mform->addElement('text', 'config_btn_link', get_string('config_button_link', 'theme_edvik'));
        $mform->setDefault('config_btn_link', $CFG->wwwroot .'/course');
        $mform->setType('config_btn_link', PARAM_RAW);

        //  Right Button Text
        $mform->addElement('text', 'config_btn2', 'Right Button Text');
        $mform->setDefault('config_btn2', 'Explore All Courses');
        $mform->setType('config_btn2', PARAM_RAW);

        //  Button Link
        $mform->addElement('text', 'config_btn_link2', 'Right Button Link');
        $mform->setDefault('config_btn_link2', $CFG->wwwroot .'/course');
        $mform->setType('config_btn_link2', PARAM_RAW);

        // Card One Area
        $mform->addElement('text', 'config_card1_img','Card One Image URL');
        $mform->setDefault('config_card1_img', EDVIK_IMG .'about/pie-chart.webp');
        $mform->setType('config_card1_img', PARAM_TEXT);

        $mform->addElement('text', 'config_card1_title', 'Card One Title');
        $mform->setDefault('config_card1_title', '75%');
        $mform->setType('config_card1_title', PARAM_RAW);

        $mform->addElement('text', 'config_card1_content', 'Card One Content');
        $mform->setDefault('config_card1_content', 'Improve In Learning');
        $mform->setType('config_card1_content', PARAM_RAW);

        // Card Two Area
        $mform->addElement('text', 'config_card2_img','Card Two Image URL');
        $mform->setDefault('config_card2_img', EDVIK_IMG .'icons/user-5.svg');
        $mform->setType('config_card2_img', PARAM_TEXT);

        $mform->addElement('text', 'config_card2_title', 'Card Two Title');
        $mform->setDefault('config_card2_title', '32K+');
        $mform->setType('config_card2_title', PARAM_RAW);

        $mform->addElement('text', 'config_card2_content', 'Card Two Content');
        $mform->setDefault('config_card2_content', 'Students Enrolled');
        $mform->setType('config_card2_content', PARAM_RAW);

        // Section Image header title according to language file.
        $mform->addElement('header', 'config_image_heading', get_string('config_image_heading', 'theme_edvik'));

        $mform->addElement('static', 'config_image_doc', '<b><a style="color: var(--primaryColor)" href="https://docs.hibootstrap.com/envydoc/edvik-moodle-theme-documentation/faqs/how-to-get-the-image-url//" target="_blank">Doc link: How to make Image URL?</a></b>');
            
        $mform->addElement('text', 'config_img', get_string('config_image', 'theme_edvik'));
        $mform->setDefault('config_img', EDVIK_IMG .'about/about-img-1.webp');
        $mform->setType('config_img', PARAM_TEXT);

        $mform->addElement('text', 'config_section_title_shape', 'Section Title Shape Image');
        $mform->setDefault('config_section_title_shape', EDVIK_IMG .'section-title-shape-2.webp');
        $mform->setType('config_section_title_shape', PARAM_TEXT);

        $mform->addElement('text', 'config_shape1', 'Shape 1');
        $mform->setDefault('config_shape1', EDVIK_IMG .'about/shape-1.webp');
        $mform->setType('config_shape1', PARAM_TEXT);

        $mform->addElement('text', 'config_shape2', 'Shape 2');
        $mform->setDefault('config_shape2', EDVIK_IMG .'about/triangle.webp');
        $mform->setType('config_shape2', PARAM_TEXT);
    }
}
