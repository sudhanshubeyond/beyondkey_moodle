<?php

class block_edvik_skills_area_edit_form extends block_edit_form {

    protected function specific_definition($mform) {

        global $CFG;

        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));
 
        // Class
        $mform->addElement('text', 'config_class', get_string('config_class', 'theme_edvik'));
        $mform->setDefault('config_class', 'simple-wrap style-two position-relative index-1 ptb-100​');
        $mform->setType('config_class', PARAM_RAW);

        // Content
        $mform->addElement('textarea', 'config_content', 'Content', 'wrap="virtual" rows="20" cols="40"', 'wrap="virtual" rows="20" cols="40"');
        $mform->setDefault('config_content', 
            '
            <img src="'.$CFG->wwwroot.'/theme/edvik/pix/shape-15.webp" alt="Shape" class="section-shape-one position-absolute bounce sm-none">
            <img src="'.$CFG->wwwroot.'/theme/edvik/pix/shape-14.webp" alt="Shape" class="section-shape-two position-absolute moveHorizontal sm-none">
            <div class="container">
                <div class="row align-items-center mb-35">
                    <div class="col-xxl-5 col-xl-6 col-lg-6">
                        <div class="section-title">
                            <span class="fs-13 font-medium d-block text_primary">OVER 6500+ COURSES AVAILABLE</span>
                            <h2 class="d-inline-block font-semibold position-relative mb-0">Enhance your skills with best Online courses</h2>
                        </div>
                    </div>
                    <div class="col-xxl-5 offset-xxl-2 col-xl-5 offset-xl-1 col-lg-6 ps-xxl-4">
                        <p class="section-para mb-0">Explore all of our courses and pick your suitable ones to enroll and start learning with us! Flexible easy to access learning opportunities.</p>
                    </div>
                </div>
                <div class="simple-content">
                    <div class="feature-item-wrap d-flex flex-wrap justify-content-between">
                        <div class="feature-item style-three">
                            <h4 class="fs-17 font-medium ls-1 round-10"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/check.svg" alt="Check Icon">Flexible Classes</h4>
                            <div>
                                <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll and start learning.</p>
                            </div>
                        </div>
                        <div class="feature-item style-four">
                            <h4 class="fs-17 font-medium ls-1 round-10"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/check.svg" alt="Check Icon">Learn From Anywhere</h4>
                            <div>
                                <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll and start learning.</p>
                            </div>
                        </div>
                        <div class="feature-item style-one">
                            <h4 class="fs-17 font-medium ls-1 round-10"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/check.svg" alt="Check Icon">WorldClass Instructor</h4>
                            <div>
                                <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll and start learning.</p>
                            </div>
                        </div>
                        <div class="feature-item style-two">
                            <h4 class="fs-17 font-medium ls-1 round-10"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/check.svg" alt="Check Icon">Learn From Expert</h4>
                            <div>
                                <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll and start learning.</p>
                            </div>
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
