<?php

class block_edvik_video_area_edit_form extends block_edit_form {

    protected function specific_definition($mform) {

        global $CFG;

        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));
 
        // Class
        $mform->addElement('text', 'config_class', get_string('config_class', 'theme_edvik'));
        $mform->setDefault('config_class', 'video-wrap style-one position-relative index-1​');
        $mform->setType('config_class', PARAM_RAW);

        // Content
        $mform->addElement('textarea', 'config_content', 'Content', 'wrap="virtual" rows="20" cols="40"');
        $mform->setDefault('config_content', 
            '
            <div class="container position-relative">
                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/video/video-shape-1.webp" alt="Image" class="video-shape-one position-absolute">
                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/video/video-shape-2.webp" alt="Image" class="video-shape-two position-absolute">
                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/video/video-shape-3.webp" alt="Image" class="video-shape-three position-absolute">
                <div class="row">
                    <div class="col-xl-10 offset-xl-1 col-md-10 offset-md-1">
                        <div class="video-bg position-relative index-1 bg-f round-20 ptb-100">
                            <div class="row">
                                <div class="col-xl-5 offset-xl-7 col-lg-5 offset-lg-7 col-md-6 offset-md-6">
                                    <div class="section-title style-seven">
                                        <span class="fs-13 font-medium d-block text_secondary">VIDEO</span>
                                        <h2 class="d-inline-block text-white font-semibold position-relative mb-20">How our <span class="d-inline-block position-relative">courses help in <img src="'.$CFG->wwwroot.'/theme/edvik/pix/section-title-shape-1.webp" alt="Shape" class="position-absolute end-0"></span> learning</h2>
                                        <a class="play-now d-flex flex-column justify-content-center align-items-center rounded-circle transition popup-youtube"  href="https://www.youtube.com/watch?v=u31qwQUeGuM">
                                            <img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/play-yellow.svg" alt="Play Icon" class="transition">
                                        </a>
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
