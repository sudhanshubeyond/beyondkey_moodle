<?php

class block_edvik_features_area_edit_form extends block_edit_form {

    protected function specific_definition($mform) {

        global $CFG;

        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));
 
        // Class
        $mform->addElement('text', 'config_class', get_string('config_class', 'theme_edvik'));
        $mform->setDefault('config_class', 'bg-aqua ptb-100​');
        $mform->setType('config_class', PARAM_RAW);

        // Content
        $mform->addElement('textarea', 'config_content', 'Content', 'wrap="virtual" rows="20" cols="40"');
        $mform->setDefault('config_content', 
            '
            <div class="container">
                <div class="section-title text-center style-seven mb-50">
                    <span class="fs-13 font-medium d-block text_primary">TOP FEATURES</span>
                    <h2 class="d-inline-block font-semibold position-relative mb-0">Top Features discover your perfect solution<img src="'.$CFG->wwwroot.'/theme/edvik/pix/section-title-shape-1.webp" alt="Shape" class="position-absolute bottom-0 end-0"></h2>
                </div>
                <div class="row justify-content-center gx-xxl-5">
                    <div class="col-lg-6 col-md-12">
                        <div class="feature-card position-relative bg-white style-one d-flex flex-wrap align-items-center round-6 mb-30">
                            <div class="feature-icon bg-green d-flex flex-column align-items-center justify-content-center round-6">
                                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/calender-2.svg" alt="Image" class="Icons">
                            </div>
                            <div class="feature-info">
                                <h3 class="font-medium"><a href="'.$CFG->wwwroot.'/course/index.php?categoryid=2">Earn certificates and degrees</a></h3>
                                <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll and start learning.</p>
                            </div>
                            <a href="'.$CFG->wwwroot.'/course/index.php?categoryid=2" class="feature-link d-flex flex-column align-items-center justify-content-center rounded-circle position-absolute bg-title text-white"><i class="ri-arrow-right-line"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="feature-card position-relative bg-white style-one d-flex flex-wrap align-items-center round-6 mb-30">
                            <div class="feature-icon bg-orange d-flex flex-column align-items-center justify-content-center round-6">
                                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/memory-2.svg" alt="Image" class="Icons">
                            </div>
                            <div class="feature-info">
                                <h3 class="font-medium"><a href="'.$CFG->wwwroot.'/course/index.php?categoryid=2">In-Demand Trendy Topics</a></h3>
                                <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll and start learning.</p>
                            </div>
                            <a href="'.$CFG->wwwroot.'/course/index.php?categoryid=2" class="feature-link d-flex flex-column align-items-center justify-content-center rounded-circle position-absolute bg-title text-white"><i class="ri-arrow-right-line"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="feature-card position-relative bg-white style-one d-flex flex-wrap align-items-center round-6 mb-30">
                            <div class="feature-icon bg-yellow d-flex flex-column align-items-center justify-content-center round-6">
                                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/segmant.svg" alt="Image" class="Icons">
                            </div>
                            <div class="feature-info">
                                <h3 class="font-medium"><a href="'.$CFG->wwwroot.'/course/index.php?categoryid=2">Segment Your Learning</a></h3>
                                <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll and start learning.</p>
                            </div>
                            <a href="'.$CFG->wwwroot.'/course/index.php?categoryid=2" class="feature-link d-flex flex-column align-items-center justify-content-center rounded-circle position-absolute bg-title text-white"><i class="ri-arrow-right-line"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="feature-card position-relative bg-white style-one d-flex flex-wrap align-items-center round-6 mb-30">
                            <div class="feature-icon bg-blue d-flex flex-column align-items-center justify-content-center round-6">
                                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/learning-2.svg" alt="Image" class="Icons">
                            </div>
                            <div class="feature-info">
                                <h3 class="font-medium"><a href="'.$CFG->wwwroot.'/course/index.php?categoryid=2">Always Interactive Learning</a></h3>
                                <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll and start learning.</p>
                            </div>
                            <a href="'.$CFG->wwwroot.'/course/index.php?categoryid=2" class="feature-link d-flex flex-column align-items-center justify-content-center rounded-circle position-absolute bg-title text-white"><i class="ri-arrow-right-line"></i></a>
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
