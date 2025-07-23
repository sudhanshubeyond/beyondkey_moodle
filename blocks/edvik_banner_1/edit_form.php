<?php

class block_edvik_banner_1_edit_form extends block_edit_form {

    protected function specific_definition($mform) {

        global $CFG;
        $edvikFontList = include($CFG->dirroot . '/theme/edvik/inc/font_handler/edvik_font_select.php');

        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));

        // Title
        $mform->addElement('text', 'config_title', 'Banner Title');
        $mform->setDefault('config_title', 'Start distant learning free from the world’s best institutions');
        $mform->setType('config_title', PARAM_RAW);

        // Content
        $mform->addElement('textarea', 'config_body', 'Content', 'wrap="virtual" rows="6" cols="50"');
        $mform->setDefault('config_body', 'Flexible easy to access learning opportunities can bring a significant change in how individuals prefer to learn! The Edvik can offer you to enjoy the beauty of eLearning!');
        $mform->setType('config_body', PARAM_RAW);

        // Search Placeholder Text
        $mform->addElement('text', 'config_search_placeholder', get_string('config_search_placeholder', 'block_edvik_banner_1'));
        $mform->setDefault('config_search_placeholder', 'What do you want to learn today?');
        $mform->setType('config_search_placeholder', PARAM_RAW);

        // Search Field Icon
        $select = $mform->addElement('select', 'config_btn_icon', 'Search Button Icon', $edvikFontList, array('class'=>'edvik_icon_class'));
        $select->setSelected('ri-search-line');

        // Search Button Text
        $mform->addElement('text', 'config_btn', 'Button Text');
        $mform->setDefault('config_btn', 'Search Now');
        $mform->setType('config_btn', PARAM_RAW);

        // Support Text
        $mform->addElement('textarea', 'config_support_text', 'Support Content', 'wrap="virtual" rows="6" cols="50"');
        $mform->setDefault('config_support_text', 'Need help? Contact our <a href="#" class="link style-two">Edvik support</a> Tell us about your query.');

        // Banner Button Text
        $mform->addElement('text', 'config_banner_btn', 'Button Text');
        $mform->setDefault('config_banner_btn', 'Explore All Courses');
        $mform->setType('config_banner_btn', PARAM_RAW);

        // Banner Button Link
        $mform->addElement('text', 'config_banner_btn_link', 'Button Link');
        $mform->setDefault('config_banner_btn_link', $CFG->wwwroot .'/course');
        $mform->setType('config_banner_btn_link', PARAM_RAW);

        // Banner Button Icon
        $select = $mform->addElement('select', 'config_banner_btn_icon', 'Button Icon', $edvikFontList, array('class'=>'edvik_icon_class'));
        $select->setSelected('ri-arrow-right-line');

        /**
         * Course header title 
         */
        $mform->addElement('header', 'config_course_heading', 'Banner Courses');

        // Course Title
        $mform->addElement('text', 'config_course_title', 'Course Title');
        $mform->setDefault('config_course_title', 'Featured Courses');
        $mform->setType('config_course_title', PARAM_RAW);

        // Select Courses
        $options = array(
            'multiple' => true,
            'noselectionstring' => get_string('select_from_dropdown_multiple', 'theme_edvik'),
        );
        $mform->addElement('course', 'config_courses', get_string('courses'), $options);

        // Total Student Title
        $mform->addElement('text', 'config_total_student_title', 'Total Students Title');
        $mform->setDefault('config_total_student_title', 'Students');
        $mform->setType('config_total_student_title', PARAM_RAW);
     
        // Button Text
        $mform->addElement('text', 'config_course_btn', 'Enroll Button Text');
        $mform->setDefault('config_course_btn', 'Enroll Now');
        $mform->setType('config_course_btn', PARAM_RAW);

        /**
         * Right Bottom Area
         */
        $mform->addElement('header', 'config_rb_heading', 'Banner Right Bottom Content');

        // Right Bottom Icon Image
        $mform->addElement('text', 'config_bg_rb', 'Icon Image URL');
        $mform->setDefault('config_bg_rb', EDVIK_IMG .'hero/trophy.svg');
        $mform->setType('config_bg_rb', PARAM_TEXT);
        
        // Right Bottom Title
        $mform->addElement('text', 'config_rb_title', 'Title');
        $mform->setDefault('config_rb_title', '1.2K+');
        $mform->setType('config_rb_title', PARAM_RAW);

        // Right Bottom Short Content
        $mform->addElement('text', 'config_rb_content', 'Short Content');
        $mform->setDefault('config_rb_content', 'Career Development Certification Couse For Future Career');
        $mform->setType('config_rb_content', PARAM_RAW);

        // Right Bottom Link Text
        $mform->addElement('text', 'config_rb_link_text', 'Link Text');
        $mform->setDefault('config_rb_link_text', 'Learn More');
        $mform->setType('config_rb_link_text', PARAM_RAW);

        // Right Bottom Link
        $mform->addElement('text', 'config_rb_link', 'Link');
        $mform->setDefault('config_rb_link', $CFG->wwwroot .'/course');
        $mform->setType('config_rb_link', PARAM_RAW);

        // Right Bottom Image
        $mform->addElement('text', 'config_rb_img', 'Image');
        $mform->setDefault('config_rb_img', EDVIK_IMG .'hero/hero-img-4.webp');
        $mform->setType('config_rb_img', PARAM_TEXT);

        // Right Bottom Card Icon
        $mform->addElement('text', 'config_rb_icon', 'Image Popup Icon');
        $mform->setDefault('config_rb_icon', EDVIK_IMG .'hero/badge.svg');
        $mform->setType('config_rb_icon', PARAM_TEXT);

        // Right Bottom Link
        $mform->addElement('text', 'config_rb_pt', 'Popup Card Text');
        $mform->setDefault('config_rb_pt', '<span class="text-title">Congratulations!</span> You’ve earned a certification.');
        $mform->setType('config_rb_pt', PARAM_RAW);

        /**
         * Section Image header title 
         */
        $mform->addElement('header', 'config_image_heading', get_string('config_image_heading', 'theme_edvik'));

        // Banner Shape Image
        $mform->addElement('text', 'config_title_shape', 'Banner Title Shape Image');
        $mform->setDefault('config_title_shape', EDVIK_IMG .'section-title-shape-1.webp');
        $mform->setType('config_title_shape', PARAM_TEXT);

        $mform->addElement('static', 'config_image_doc', '<b><a style="color: var(--primaryColor)" href="https://docs.hibootstrap.com/envydoc/edvik-moodle-theme-documentation/faqs/how-to-get-the-image-url//" target="_blank">Doc link: How to make Image URL?</a></b>');        
 
        // Support Images
        $support_image_count = 3;
        for($i = 1; $i <= $support_image_count; $i++) {
            $mform->addElement('text', 'config_user_img' . $i, 'Support User Image URL ' . $i);
            $mform->setDefault('config_user_img', EDVIK_IMG .'banner/author-'.$i.'.webp');
            $mform->setType('config_user_img' . $i, PARAM_TEXT);
        }

        // Banner Background Shape Image
        $mform->addElement('text', 'config_banner_bg_shape', 'Banner Left Background Image');
        $mform->setType('config_banner_bg_shape', PARAM_TEXT);

        // Banner Shape Image
        $mform->addElement('text', 'config_shape', 'Banner Left Shape Image');
        $mform->setDefault('config_shape', EDVIK_IMG .'hero/hero-shape-1.webp');
        $mform->setType('config_shape', PARAM_TEXT);

        // Course Background Image
        $mform->addElement('text', 'config_course_bg_shape', 'Course Area Background Image');
        $mform->setType('config_course_bg_shape', PARAM_TEXT);

        // Right Bottom Background Image
        $mform->addElement('text', 'config_bg_rb', 'Right Bottom Card Background Image');
        $mform->setDefault('config_bg_rb', EDVIK_IMG .'hero/trophy.svg');
        $mform->setType('config_bg_rb', PARAM_TEXT);
    }
}