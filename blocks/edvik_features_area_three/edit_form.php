<?php

class block_edvik_features_area_three_edit_form extends block_edit_form {

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
            <div class="container pt-100 pb-70">
                <div class="row justify-content-center">
                    <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-6">
                        <div class="feature-card style-two mb-30">
                            <div class="feature-icon bg-yellow d-flex flex-column align-items-center justify-content-center rounded-circle">
                                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/badge.svg" alt="Icons">
                            </div>
                            <h3 class="font-medium">Best In Class Content</h3>
                            <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll.</p>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-6 ps-xxl-3">
                        <div class="feature-card style-two mb-30">
                            <div class="feature-icon bg-orange d-flex flex-column align-items-center justify-content-center rounded-circle">
                                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/advantage.svg" alt="Icons">
                            </div>
                            <h3 class="font-medium">Competitive Advantage</h3>
                            <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll.</p>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-6 ps-xxl-3">
                        <div class="feature-card style-two mb-30">
                            <div class="feature-icon bg-blue d-flex flex-column align-items-center justify-content-center rounded-circle">
                                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/growth.svg" alt="Icons">
                            </div>
                            <h3 class="font-medium">Growth Potential</h3>
                            <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll.</p>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-6 ps-xxl-4">
                        <div class="feature-card style-two mb-30">
                            <div class="feature-icon bg-green d-flex flex-column align-items-center justify-content-center rounded-circle">
                                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/revenue.svg" alt="Icons">
                            </div>
                            <h3 class="font-medium">Growing Revenue</h3>
                            <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll.</p>
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
