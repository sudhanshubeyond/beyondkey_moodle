<?php

class block_edvik_video_area2_edit_form extends block_edit_form {

    protected function specific_definition($mform) {

        global $CFG;

        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));
 
        // Class
        $mform->addElement('text', 'config_class', get_string('config_class', 'theme_edvik'));
        $mform->setDefault('config_class', 'video-wrap style-two position-relative index-1​');
        $mform->setType('config_class', PARAM_RAW);

        // Content
        $mform->addElement('textarea', 'config_content', 'Content', 'wrap="virtual" rows="20" cols="40"');
        $mform->setDefault('config_content', 
            '
            <div class="video-wrap style-two position-relative index-1">
                <div class="container">
                    <div class="video-bg position-relative index-1 bg-f round-10 ptb-100" style="background-image:url('.$CFG->wwwroot.'/theme/edvik/pix/video/video-bg-2.webp);">
                        <div class="row align-items-center">
                            <div class="col-xl-3 col-lg-4 col-md-5 pe-xxl-0">
                                <div class="section-title style-seven">
                                    <span class="fs-13 font-medium d-block text_secondary">VIDEO</span>
                                    <h2 class="d-inline-block text-white font-semibold position-relative mb-30">How our courses help in learning</h2>
                                    <a class="play-now d-flex flex-column justify-content-center align-items-center rounded-circle transition popup-youtube" href="https://www.youtube.com/watch?v=u31qwQUeGuM">
                                        <img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/play-2.svg" alt="Play Icon" class="transition">
                                    </a>
                                </div>
                            </div>
                            <div class="col-xl-4 offset-xl-5 col-lg-5 offset-lg-3 col-md-6 offset-md-1">
                                <div class="course-card position-relative bg-white round-10 overflow-hidden ms-auto">
                                    <div class="course-img">
                                        <img src="'.$CFG->wwwroot.'/theme/edvik/pix/courses/course-100.webp" alt="Image">
                                        <div class="course-info">
                                            <span class="fs-12 text-title course-label bg-blue round-4"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/label-2.svg" alt="Icon">Expert</span>
                                            <h3 class="fs-18 font-medium"><a href="'.$CFG->wwwroot.'/course/view.php?id=3">VR Learning Method For The First Time</a></h3>
                                            <div class="course-tag">
                                                <a href="'.$CFG->wwwroot.'/course/view.php?id=3" class="fs-12 text-title course-label bg-white round-4"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/label-2.svg" alt="Icon">24 Classes</a>,
                                                <a href="'.$CFG->wwwroot.'/course/view.php?id=3" class="fs-12 text-title course-label bg-white round-4"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/label-2.svg" alt="Icon">12 Videos</a>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="'.$CFG->wwwroot.'/course/view.php?id=3" class="course-link d-block fs-15 font-medium transition">Take The Course Now <i class="ri-arrow-right-line"></i></a>
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
