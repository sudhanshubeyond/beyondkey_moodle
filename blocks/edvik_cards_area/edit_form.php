<?php

class block_edvik_cards_area_edit_form extends block_edit_form {

    protected function specific_definition($mform) {

        global $CFG;

        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));
 
        // Class
        $mform->addElement('text', 'config_class', get_string('config_class', 'theme_edvik'));
        $mform->setDefault('config_class', 'container pb-70​');
        $mform->setType('config_class', PARAM_RAW);

        // Content
        $mform->addElement('textarea', 'config_content', 'Content', 'wrap="virtual" rows="20" cols="40"');
        $mform->setDefault('config_content', 
            '
            <div class="row">
                <div class="col-lg-6">
                    <div class="event-card style-two d-flex align-items-center justify-content-between position-relative index-1 mb-30">
                        <img src="'.$CFG->wwwroot.'/theme/edvik/pix/event/event-shape.webp" alt="Image" class="event-card-shape position-absolute bottom-0 end-0">
                        <div class="event-content">
                            <span class="fs-13 font-medium text-orange">EVENT</span>
                            <h3 class="fs-32 ls-1 font-semibold">Join Our Virtual Event On AI</h3>
                            <p class="text-tandora ls-1">Elevate your learning experience by tapping into the wealth of knowledge.</p>
                            <a href="'.$CFG->wwwroot.'/login/signup.php" class="link style-one fs-15 bg-transparent border-0 p-0" >Register Now<i class="ri-arrow-right-line"></i></a>
                        </div>
                        <div class="event-img">
                            <img src="'.$CFG->wwwroot.'/theme/edvik/pix/event/event-2.webp" alt="Event Image">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="event-card style-three d-flex align-items-center justify-content-between position-relative index-1 mb-30">
                        <img src="'.$CFG->wwwroot.'/theme/edvik/pix/event/event-shape.webp" alt="Image" class="event-card-shape position-absolute bottom-0 end-0">
                        <div class="event-content">
                            <span class="fs-13 font-medium text-greenTwo">DISCOUNT</span>
                            <h3 class="fs-32 ls-1 font-semibold">10% Discount for the new learners</h3>
                            <p class="text-tandora ls-1">Elevate your learning experience by tapping into the wealth of knowledge.</p>
                            <a href="'.$CFG->wwwroot.'/login/signup.php" class="link style-one fs-15 bg-transparent border-0 p-0" >Register Now<i class="ri-arrow-right-line"></i></a>
                        </div>
                        <div class="event-img">
                            <img src="'.$CFG->wwwroot.'/theme/edvik/pix/event/event-3.webp" alt="Event Image">
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
