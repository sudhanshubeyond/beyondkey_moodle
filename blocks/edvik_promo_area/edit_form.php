<?php

class block_edvik_promo_area_edit_form extends block_edit_form {

    protected function specific_definition($mform) {

        global $CFG;

        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));
 
        // Class
        $mform->addElement('text', 'config_class', get_string('config_class', 'theme_edvik'));
        $mform->setDefault('config_class', '');
        $mform->setType('config_class', PARAM_RAW);

        // Content
        $mform->addElement('textarea', 'config_content', 'Content', 'wrap="virtual" rows="20" cols="40"');
        $mform->setDefault('config_content', 
            '
            <div class="promo-wrap">
                <div class="container">
                    <div class="promo-box bg-title round-20">
                        <div class="row justify-content-center">
                            <div class="col-xxl-3 col-xl-4 col-md-6 pe-xxl-0">
                                <div class="promo-card d-flex flex-wrap transition mb-30">
                                    <div class="promo-icon d-flex flex-column align-items-center justify-content-center rounded-circle">
                                        <img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/expert-instructor.svg" alt="Icon" class="transition">
                                    </div>
                                    <div class="promo-info">
                                        <h6 class="fs-20 font-semibold ls-1 text-white">Expert Instructors</h6>
                                        <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll and start learning.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-3 col-xl-4 col-md-6 pe-xxl-0">
                                <div class="promo-card d-flex flex-wrap transition mb-30">
                                    <div class="promo-icon d-flex flex-column align-items-center justify-content-center rounded-circle">
                                        <img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/free-access.svg" alt="Icon" class="transition">
                                    </div>
                                    <div class="promo-info">
                                        <h6 class="fs-20 font-semibold ls-1 text-white">Lifetime Free Access</h6>
                                        <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll and start learning.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-3 col-xl-4 col-md-6 pe-xxl-0">
                                <div class="promo-card active d-flex flex-wrap transition mb-30">
                                    <div class="promo-icon d-flex flex-column align-items-center justify-content-center rounded-circle">
                                        <img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/access.svg" alt="Icon" class="transition">
                                    </div>
                                    <div class="promo-info">
                                        <h6 class="fs-20 font-semibold ls-1 text-white">Access Anywhere</h6>
                                        <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll and start learning.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-3 col-xl-4 col-md-6 pe-xxl-0">
                                <div class="promo-card d-flex flex-wrap transition mb-30">
                                    <div class="promo-icon d-flex flex-column align-items-center justify-content-center rounded-circle">
                                        <img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/certificate.svg" alt="Icon" class="transition">
                                    </div>
                                    <div class="promo-info">
                                        <h6 class="fs-20 font-semibold ls-1 text-white">Certificate</h6>
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

        // Section Image header title according to language file.
        $mform->addElement('header', 'config_image_heading', get_string('config_image_heading', 'theme_edvik'));

        $mform->addElement('static', 'config_image_doc', '<b><a style="color: var(--primaryColor)" href="https://docs.hibootstrap.com/envydoc/edvik-moodle-theme-documentation/faqs/how-to-get-the-image-url//" target="_blank">Doc link: How to make Image URL?</a></b>');
    }
}
