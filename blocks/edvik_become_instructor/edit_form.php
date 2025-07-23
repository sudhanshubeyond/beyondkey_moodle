<?php

class block_edvik_become_instructor_edit_form extends block_edit_form {

    protected function specific_definition($mform) {

        global $CFG;

        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));
 
        // Class
        $mform->addElement('text', 'config_class', get_string('config_class', 'theme_edvik'));
        $mform->setDefault('config_class', 'container pb-100​');
        $mform->setType('config_class', PARAM_RAW);

        // Content
        $mform->addElement('textarea', 'config_content', 'Content', 'wrap="virtual" rows="20" cols="40"');
        $mform->setDefault('config_content', 
            '
 <div class="row align-items-center">
    <div class="col-lg-6">
        <div class="be-insturctor-img position-relative index-1">
            <img src="'.$CFG->wwwroot.'/theme/edvik/pix/shape-4.webp" alt="Shape" class="shape-one position-absolute">
            <img src="'.$CFG->wwwroot.'/theme/edvik/pix/shape-5.webp" alt="Shape" class="shape-two position-absolute">
            <img src="'.$CFG->wwwroot.'/theme/edvik/pix/instructor/become-instructor.webp" alt="Image" class="d-block ms-auto">
        </div>
    </div>
    <div class="col-xxl-5 offset-xxl-1 col-lg-6 ps-xxl-0">
        <div class="be-instructor-content">
            <div class="section-title">
                <span class="fs-13 d-block font-medium text_primary">BECOME AN INSTRUCTOR</span>
                <h2 class="d-inline-block font-semibold position-relative mb-20">Join our community as a renowned educator</h2>
                <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll and start learning with us! Flexible easy to access learning opportunities.</p>
            </div>
            <ul class="feature-list list-unstyle">
                <li class="text-title position-relative"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/check.svg" alt="Check Icon" class="position-absolute start-0">Sell your course</li>
                <li class="text-title position-relative"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/check.svg" alt="Check Icon" class="position-absolute start-0">Join as a community partner of 1342+ members</li>
                <li class="text-title position-relative"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/check.svg" alt="Check Icon" class="position-absolute start-0">Become an affiliate partner</li>
            </ul>
            <a href="'.$CFG->wwwroot.'/course" class="btnn style-two round-10">Start Teaching Today</a>
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
