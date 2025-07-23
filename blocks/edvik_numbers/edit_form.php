<?php

class block_edvik_numbers_edit_form extends block_edit_form {

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
                <div class="counter-wrap style-three position-relative index-1 pb-100">
                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/shape-10.webp" alt="Shape" class="section-shape position-absolute end-0">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-lg-6">
                                <div class="counter-img">
                                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/counter-img.webp" alt="Image">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="counter-content">
                                    <div class="section-title">
                                        <span class="fs-13 font-medium d-block text_primary">NUMBERS</span>
                                        <h2 class="d-inline-block font-semibold position-relative">How Edvik is best among other platforms</h2>
                                        <p>Life is dynamic, and so is your learning journey. With our flexible approach, you have the freedom to learn at your own pace. </p>
                                    </div>
                                    <div class="counter-card-wrap d-flex flex-wrap position-relative">
                                        <div class="counter-card style-two round-10 position-relative index-1 mb-25">
                                            <h2 class="font-bold text-blue mb-0"><span class="counter">6500</span>+</h2>
                                            <p class="fs-13 font-medium mb-0">COURSES</p>
                                        </div>
                                        <div class="counter-card style-two round-10 position-relative index-1 mb-25">
                                            <h2 class="font-bold text-orange mb-0"><span class="counter">13500</span>+</h2>
                                            <p class="fs-13 font-medium mb-0">STUDENTS ENROLLED</p>
                                        </div>
                                        <div class="counter-card style-two round-10 position-relative index-1 mb-25">
                                            <h2 class="font-bold text-green mb-0"><span class="counter">1555</span>+</h2>
                                            <p class="fs-13 font-medium mb-0">INSTRUCTORS</p>
                                        </div>
                                        <div class="counter-card style-two round-10 position-relative index-1 mb-25">
                                            <h2 class="font-bold text-violet mb-0"><span class="counter">82.5</span>%</h2>
                                            <p class="fs-13 font-medium mb-0">LEARNERS PROGRESS</p>
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
