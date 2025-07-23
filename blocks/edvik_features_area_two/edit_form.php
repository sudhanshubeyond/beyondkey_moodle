<?php

class block_edvik_features_area_two_edit_form extends block_edit_form {

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
            <div class="container">
                <div class="wh-wrap style-one position-relative index-1 pt-100 pb-70">
                    <div class="section-title style-nine text-center mb-60">
                        <span class="fs-13 font-medium d-block text_primary">WHY EDVIK</span>
                        <h2 class="d-inline-block font-semibold position-relative mb-0">Here is the future of distant learning<img src="'.$CFG->wwwroot.'/theme/edvik/pix/section-title-shape-1.webp" alt="Shape" class="position-absolute bottom-0 end-0"></h2>
                    </div>
                    <div class="wh-card-wrap d-flex flex-wrap">
                        <div class="wh-card position-relative index-1 mb-30">
                            <div class="wh-title d-flex align-items-center">
                                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/about/rocket.svg" alt="Image">
                                <h3 class="fs-22 font-medium ls-1 mb-0">Learn the latest top skills</h3>
                            </div>
                            <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll and start learning.</p>
                        </div>
                        <div class="wh-card position-relative index-1 mb-30">
                            <div class="wh-title d-flex align-items-center">
                                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/about/video-conference.svg" alt="Image">
                                <h3 class="fs-22 font-medium ls-1 mb-0">Learn from industry experts</h3>
                            </div>
                            <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll and start learning.</p>
                        </div>
                        <div class="wh-card position-relative index-1 mb-30">
                            <div class="wh-title d-flex align-items-center">
                                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/about/goal.svg" alt="Image">
                                <h3 class="fs-22 font-medium ls-1 mb-0">Learn in your own pace</h3>
                            </div>
                            <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll and start learning.</p>
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
