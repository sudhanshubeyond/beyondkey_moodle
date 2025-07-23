<?php

class block_edvik_banner_3_edit_form extends block_edit_form {

    protected function specific_definition($mform) {

        global $CFG;
        $edvikFontList = include($CFG->dirroot . '/theme/edvik/inc/font_handler/edvik_font_select.php');

        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));

        // Title
        $mform->addElement('text', 'config_title', 'Banner Title');
        $mform->setDefault('config_title', 'Find Your Best Courses To Develop Your Skills');
        $mform->setType('config_title', PARAM_RAW);

        // Content
        $mform->addElement('textarea', 'config_body', 'Content', 'wrap="virtual" rows="6" cols="50"');
        $mform->setDefault('config_body', 'Flexible easy to access learning opportunities can bring a significant change in how individuals prefer to learn! The Edvik can offer you to enjoy the beauty of eLearning!');
        $mform->setType('config_body', PARAM_RAW);

        // Search Placeholder Text
        $mform->addElement('text', 'config_search_placeholder', get_string('config_search_placeholder', 'block_edvik_banner_3'));
        $mform->setDefault('config_search_placeholder', 'What do you want to learn today?');
        $mform->setType('config_search_placeholder', PARAM_RAW);

        // Search Field Icon
        $select = $mform->addElement('select', 'config_btn_icon', 'Search Button Icon', $edvikFontList, array('class'=>'edvik_icon_class'));
        $select->setSelected('ri-search-line');

        // Search Button Text
        $mform->addElement('text', 'config_btn', 'Button Text');
        $mform->setDefault('config_btn', 'Search Now');
        $mform->setType('config_btn', PARAM_RAW);

        // Card Content
        $mform->addElement('textarea', 'config_card_content1', 'Card Content', 'wrap="virtual" rows="6" cols="50"');
        $mform->setDefault('config_card_content1', '
            <div class="hero-course-amt-wrap position-relative index-1 d-inline-block float-end">
                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/hero/arrow.webp" alt="Arrow Shape" class="shape-one position-absolute m-0">
                <div class="hero-course-amt bg-white ms-auto position-relative index-1 d-inline-flex align-items-center round-6">
                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/hero/compus.svg" alt="Icon">
                    <div>
                        <h6 class="fs-14 font-semibold lh-1">23K+ Courses</h6>
                        <span class="fs-14 text-icon lh-1">Available for learners</span>
                    </div>
                </div>
            </div>
        ');
        $mform->setType('config_card_content1', PARAM_RAW);

        // Card Content 2
        $mform->addElement('textarea', 'config_card_content2', 'Card Content', 'wrap="virtual" rows="6" cols="50"');
        $mform->setDefault('config_card_content2', '
            <div class="instructor-box bg-white round-6 position-absolute">
                <ul class="d-flex align-items-center list-unstyle">
                    <li><img src="'.$CFG->wwwroot.'/theme/edvik/pix/blog/author-11.webp" alt="Author"></li>
                    <li><img src="'.$CFG->wwwroot.'/theme/edvik/pix/blog/author-10.webp" alt="Author"></li>
                    <li><img src="'.$CFG->wwwroot.'/theme/edvik/pix/blog/author-1.webp" alt="Author"></li>
                    <li><a href="'.$CFG->wwwroot.'/course"><i class="ri-add-line"></i></a></li>
                </ul>
                <h6 class="fs-14 font-semibold ls-1">230+ Instructors</h6>
                <span class="fs-14 text-icon ls-1">Joined our site to teach</span>
            </div>
        ');
        $mform->setType('config_card_content2', PARAM_RAW);

        /**
         * Course header title 
         */
        $mform->addElement('header', 'config_course_heading', 'Banner Courses');

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
         * Section Image header title 
         */
        $mform->addElement('header', 'config_image_heading', get_string('config_image_heading', 'theme_edvik'));

        $mform->addElement('static', 'config_image_doc', '<b><a style="color: var(--primaryColor)" href="https://docs.hibootstrap.com/envydoc/edvik-moodle-theme-documentation/faqs/how-to-get-the-image-url//" target="_blank">Doc link: How to make Image URL?</a></b>');        
 
        // Banner Background Image
        $mform->addElement('text', 'config_banner_bg', 'Banner Background Image');
        $mform->setType('config_banner_bg', PARAM_TEXT);

        // Banner Shape Image
        $mform->addElement('text', 'config_img', 'Banner Image');
        $mform->setDefault('config_img', EDVIK_IMG .'hero/hero-img-3.svg');
        $mform->setType('config_img', PARAM_TEXT);

        // Banner Shape Image
        $mform->addElement('text', 'config_shape', 'Banner Shape Image 1');
        $mform->setDefault('config_shape', EDVIK_IMG .'hero/bulb.svg');
        $mform->setType('config_shape', PARAM_TEXT);

        // Banner Shape Image 2
        $mform->addElement('text', 'config_shape2', 'Banner Shape Image 2');
        $mform->setDefault('config_shape2', EDVIK_IMG .'hero/circle-1.svg');
        $mform->setType('config_shape2', PARAM_TEXT);
        // Banner Shape Image 3
        $mform->addElement('text', 'config_shape3', 'Banner Shape Image 3');
        $mform->setDefault('config_shape3', EDVIK_IMG .'hero/shape-1.webp');
        $mform->setType('config_shape3', PARAM_TEXT);

      
    }
}