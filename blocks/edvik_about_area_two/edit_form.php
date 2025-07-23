<?php

class block_edvik_about_area_two_edit_form extends block_edit_form {

    protected function specific_definition($mform) {

        global $CFG;

        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));
        
        // Class
        $mform->addElement('text', 'config_class', get_string('config_class', 'theme_edvik'));
        $mform->setDefault('config_class', 'simple-wrap style-one ptb-100​');
        $mform->setType('config_class', PARAM_RAW);

        $style = 1;
        if(isset($this->block->config->style)){
            $style = $this->block->config->style;
        }
        $mform->addElement('select', 'config_style', 'Select Output Mode', array(1 => 'General Output', 2 => 'HTML Output'));
        $mform->setDefault('config_style', 2);

         // HTML Output
         $mform->addElement('header', 'config_html_output', 'HTML Output');

        // Content
        $mform->addElement('textarea', 'config_content', 'HTML Output', 'wrap="virtual" rows="20" cols="40"');
        $mform->setDefault('config_content', 
            '
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="simple-img-wrap position-relative index-1">
                            <img src="'.$CFG->wwwroot.'/theme/edvik/pix/about/feature-img-1.webp" alt="Feature Image" class="simple-img d-block mx-auto">
                            <img src="'.$CFG->wwwroot.'/theme/edvik/pix/about/feature-shape.webp" alt="Feature Image" class="simple-shape position-absolute">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="simple-content">
                            <div class="section-title style-one">
                                <span class="fs-13 font-medium d-block text_primary">OVER 6500+ COURSES AVAILABLE</span>
                                <h2 class="d-inline-block font-semibold position-relative mb-0">Enhance your Sskills with best Online courses<img src="'.$CFG->wwwroot.'/theme/edvik/pix/section-title-shape-2.webp" alt="Shape" class="position-absolute bottom-0 end-0"></h2>
                                <p>Explore all of our courses and pick your suitable ones to enroll and start learning with us! Flexible easy to access learning opportunities.</p>
                            </div>
                            <div class="feature-item-wrap d-flex flex-wrap justify-content-between">
                                <div class="feature-item style-one">
                                    <h4 class="fs-17 font-medium ls-1 round-10"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/check.svg" alt="Check Icon">Flexible Classes</h4>
                                    <div>
                                        <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll and start learning.</p>
                                        <a href="'.$CFG->wwwroot.'/course/index.php?categoryid=2" class="link style-one d-inline-block fs-15 font-medium">Explore More<i class="ri-arrow-right-line"></i></a>
                                    </div>
                                </div>
                                <div class="feature-item style-two">
                                    <h4 class="fs-17 font-medium ls-1 round-10"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/check.svg" alt="Check Icon">Learn From Anywhere</h4>
                                    <div>
                                        <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll and start learning.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            ​');
        $mform->setType('config_content', PARAM_RAW);

        // General Output
        $mform->addElement('header', 'config_general_output', 'General Output');

        // Top Title
        $mform->addElement('text', 'config_top_title', 'Top Title');
        $mform->setDefault('config_top_title', 'OVER 6500+ COURSES AVAILABLE');
        $mform->setType('config_top_title', PARAM_RAW);

        // Title
        $mform->addElement('text', 'config_title', get_string('config_title', 'theme_edvik'));
        $mform->setDefault('config_title', 'Enhance your Skills with best Online courses');
        $mform->setType('config_title', PARAM_RAW);

        // Title Shape IMage
        $mform->addElement('text', 'config_title_shape', 'Title Shape Image URL');
        $mform->setDefault('config_title_shape', EDVIK_IMG .'section-title-shape-2.webp');
        $mform->setType('config_title_shape', PARAM_TEXT);

        // Content
        $mform->addElement('textarea', 'config_body', get_string('config_body', 'theme_edvik'), 'wrap="virtual" rows="6" cols="50"');
        $mform->setDefault('config_body', 'Explore all of our courses and pick your suitable ones to enroll and start learning with us! Flexible easy to access learning opportunities.​');
        $mform->setType('config_body', PARAM_RAW);

        // List Title 1
        $mform->addElement('text', 'config_list_title1','List Title 1');
        $mform->setDefault('config_list_title1', 'Flexible Classes');
        $mform->setType('config_list_title1', PARAM_RAW);

        // List Content 1
        $mform->addElement('text', 'config_list_content1','List Title 1');
        $mform->setDefault('config_list_content1', 'Explore all of our courses and pick your suitable ones to enroll and start learning.');
        $mform->setType('config_list_content1', PARAM_RAW);

        // List Title 2
        $mform->addElement('text', 'config_list_title2','List Title 2');
        $mform->setDefault('config_list_title2', 'Learn From Anywhere');
        $mform->setType('config_list_title2', PARAM_RAW);

        // List Content 2
        $mform->addElement('text', 'config_list_content2','List Title 2');
        $mform->setDefault('config_list_content2', 'Explore all of our courses and pick your suitable ones to enroll and start learning.');
        $mform->setType('config_list_content2', PARAM_RAW);

        // List Icon 
        $mform->addElement('text', 'config_list_icon', 'List Icon Image URL');
        $mform->setDefault('config_list_icon', EDVIK_IMG .'icons/check.svg');
        $mform->setType('config_list_icon', PARAM_TEXT);

        //  Button Text
        $mform->addElement('text', 'config_btn', get_string('config_button_text', 'theme_edvik'));
        $mform->setDefault('config_btn', 'Explore More');
        $mform->setType('config_btn', PARAM_RAW);

        //  Button Link
        $mform->addElement('text', 'config_btn_link', get_string('config_button_link', 'theme_edvik'));
        $mform->setDefault('config_btn_link', $CFG->wwwroot .'/course');
        $mform->setType('config_btn_link', PARAM_RAW);

        // Section Image header title according to language file.
        $mform->addElement('header', 'config_image_heading', get_string('config_image_heading', 'theme_edvik'));

        $mform->addElement('static', 'config_image_doc', '<b><a style="color: var(--primaryColor)" href="https://docs.hibootstrap.com/envydoc/edvik-moodle-theme-documentation/faqs/how-to-get-the-image-url//" target="_blank">Doc link: How to make Image URL?</a></b>');

            $mform->addElement('text', 'config_img', get_string('config_image', 'theme_edvik'));
            $mform->setDefault('config_img', EDVIK_IMG .'about/feature-img-1.webp');
            $mform->setType('config_img', PARAM_TEXT);

            $mform->addElement('text', 'config_shape1', 'Shape 1');
            $mform->setDefault('config_shape1', EDVIK_IMG .'about/feature-shape.webp');
            $mform->setType('config_shape1', PARAM_TEXT);
    }
}
