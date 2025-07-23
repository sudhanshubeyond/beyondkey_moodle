<?php

class block_edvik_about_area_three_edit_form extends block_edit_form {

    protected function specific_definition($mform) {

        global $CFG;

        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));
 
        // Class
        $mform->addElement('text', 'config_class', get_string('config_class', 'theme_edvik'));
        $mform->setDefault('config_class', 'about-wrap style-two position-relative pb-100​');
        $mform->setType('config_class', PARAM_RAW);

        // Content
        $mform->addElement('textarea', 'config_content', 'Content', 'wrap="virtual" rows="20" cols="40"');
        $mform->setDefault('config_content', 
            '
            <img src="'.$CFG->wwwroot.'/theme/edvik/pix/about/shape-3.webp" alt="shape" class="shape-three position-absolute rotate">
            <div class="container">
                <div class="row">
                    <div class="col-xxl-5 col-xl-6 col-lg-5">
                        <div class="about-img position-relative index-1">
                            <img src="'.$CFG->wwwroot.'/theme/edvik/pix/about/about-img-5.webp" alt="About Image">
                            <a class="play-now position-absolute d-flex flex-column justify-content-center align-items-center rounded-circle transition popup-youtube" href="https://www.youtube.com/watch?v=u31qwQUeGuM">
                                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/video-icon.svg" alt="Play Icon" class="transition">
                            </a>
                        </div>
                    </div>
                    <div class="col-xxl-7 col-xl-6 col-lg-7">
                        <div class="about-content">
                            <div class="row">
                                <div class="section-title">
                                    <span class="fs-13 font-medium d-block text_primary">ABOUT EDVIK</span>
                                    <h2 class="d-inline-block font-semibold position-relative ls-1">We provide Affordable online courses & learning opportunities for all without limit</h2>
                                    <p class="mb-0">Break into a new field like information technology o data science. No prior experience necessary to get started.</p>
                                </div>
                                <div class="d-flex flex-wrap align-items-center">
                                    <ul class="feature-list list-unstyle">
                                        <li class="position-relative text-title"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/check.svg" alt="Icon">Expert Instructors</li>
                                        <li class="position-relative text-title"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/check.svg" alt="Icon">Lifetime Free Access</li>
                                        <li class="position-relative text-title"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/check.svg" alt="Icon">Access Anywhere</li>
                                    </ul>
                                    <div class="students-success round-10 text-center">
                                        <span class="fs-13 text-greenTwo d-block font-medium">STUDENT’S SUCCESS</span>
                                        <h4 class="fs-40 font-bold lh-1 mb-0">90%</h4>
                                    </div>
                                    <div class="review-box round-10 text-center">
                                        <span class="fs-13 text-greenTwo d-block font-medium">AVERAGE REVIEWS</span>
                                        <h4 class="fs-40 font-bold lh-1 mb-0">5.00</h4>
                                    </div>
                                </div>
                                <div class="about-btn d-flex align-items-center">
                                    <a href="'.$CFG->wwwroot.'" class="btnn style-two round-10 font-medium">Learn More</a>
                                    <a href="'.$CFG->wwwroot.'/course" class="link style-one fs-15">Explore All Courses<i class="ri-arrow-right-line"></i></a>
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
