<?php

class block_edvik_about_banner_edit_form extends block_edit_form {

    protected function specific_definition($mform) {

        global $CFG;

        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));
 
        // Class
        $mform->addElement('text', 'config_class', get_string('config_class', 'theme_edvik'));
        $mform->setDefault('config_class', '​');
        $mform->setType('config_class', PARAM_RAW);

        // Content
        $mform->addElement('textarea', 'config_content', 'Content', 'wrap="virtual" rows="20" cols="40"');
        $mform->setDefault('config_content', 
            '
            <div class="breadcrumb-wrap style-four bg-f round-10 position-relative index-1" style="background-image:url('.$CFG->wwwroot.'/theme/edvik/pix/about/about-bg.webp);">
                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/shape-12.webp" alt="Shape" class="br-shape-one position-absolute moveHorizontal">
                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/shape-13.webp" alt="Shape" class="br-shape-two position-absolute bounce">
                <div class="container text-center">
                    <div class="row">
                        <div class="col-xl-8 offset-xl-2 col-lg-8 offset-lg-2 px-xxl-5">
                            <h2 class="br-title font-semibold text-white ls-1">We share knowledge with the world to change learning for the better</h2>
                            <ul class="br-menu list-unstyle">
                                <li><a href="'.$CFG->wwwroot.'">Home</a></li>
                                <li>About Us</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            ​');
        $mform->setType('config_content', PARAM_RAW);

        // Section Image header title according to language file.
        $mform->addElement('header', 'config_image_heading', get_string('config_image_heading', 'theme_edvik'));

        $mform->addElement('static', 'config_image_doc', '<b><a style="color: var(--primaryColor)" href="https://docs.hibootstrap.com/envydoc/edvik-moodle-theme-documentation/faqs/how-to-get-the-image-url//" target="_blank">Doc link: How to make Image URL?</a></b>');
    }
}
