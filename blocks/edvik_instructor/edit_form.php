<?php

class block_edvik_instructor_edit_form extends block_edit_form {

    protected function specific_definition($mform) {

        global $CFG;

        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));
 
        // Class
        $mform->addElement('text', 'config_class', get_string('config_class', 'theme_edvik'));
        $mform->setDefault('config_class', 'container ptb-100​');
        $mform->setType('config_class', PARAM_RAW);

        $style = 1;
        if(isset($this->block->config->style)){
            $style = $this->block->config->style;
        }
        $mform->addElement('select', 'config_style', 'Select Output Mode', array(1 => 'Dynamic Output', 2 => 'HTML Output'));
        $mform->setDefault('config_style', 2);

        // Courses Text
        $mform->addElement('text', 'config_course_title', 'Course Text');
        $mform->setDefault('config_course_title', 'Courses');
        $mform->setType('config_course_title', PARAM_RAW);

        // Courses Text
        $mform->addElement('text', 'config_students_title', 'Students Text');
        $mform->setDefault('config_students_title', 'Students');
        $mform->setType('config_students_title', PARAM_RAW);

         // HTML Output
         $mform->addElement('header', 'config_html_output', 'HTML Output');

        // Content
        $mform->addElement('textarea', 'config_content', 'Content', 'wrap="virtual" rows="20" cols="40"');
        $mform->setDefault('config_content', 
            '
            <div class="row justify-content-center">
                <div class="col-xxl-3 col-lg-4 col-md-6">
                    <div class="instructor-card mb-50">
                        <div class="instructor-img">
                            <img src="'.$CFG->wwwroot.'/theme/edvik/pix/instructor/instructor-1.webp" alt="Image">
                        </div>
                        <div class="instructor-info bg-spring position-relative index-1">
                            <div class="instructor-title d-flex align-items-start justify-content-between">
                                <div>
                                    <h3 class="fs-20 font-medium"><a href="'.$CFG->wwwroot.'/course">Walter White</a></h3>
                                    <span class="text-firod">Web Developer</span>
                                </div>
                                <div class="rating d-flex align-items-center fs-13">
                                    <span class="text-title">4.9</span><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/star.svg" alt="Star Icon">
                                    <span class="text-firod">(120)</span>
                                </div>
                            </div>
                            <ul class="instructor-metainfo list-unstyle d-flex align-items-center justify-content-between">
                                <li class="text-paragraph fs-14"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/book-3.svg" alt="Icon">9 Courses</li>
                                <li class="text-paragraph fs-14 text-end"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/user-2.svg" alt="Icon">52 Students</li>
                            </ul>
                            <ul class="social-profile style-one list-unstyle">
                                <li><a href="https://www.facebook.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-facebook-fill"></i></a></li>
                                <li><a href="https://www.twitter.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-twitter-x-fill"></i></a></li>
                                <li><a href="https://www.instagram.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-instagram-fill"></i></a></li>
                                <li><a href="https://www.linkedin.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-linkedin-fill"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-lg-4 col-md-6">
                    <div class="instructor-card mb-50">
                        <div class="instructor-img">
                            <img src="'.$CFG->wwwroot.'/theme/edvik/pix/instructor/instructor-2.webp" alt="Image">
                        </div>
                        <div class="instructor-info bg-spring position-relative index-1">
                            <div class="instructor-title d-flex align-items-start justify-content-between">
                                <div>
                                    <h3 class="fs-20 font-medium"><a href="'.$CFG->wwwroot.'/course">Angela Carter</a></h3>
                                    <span class="text-firod">Design Educator</span>
                                </div>
                                <div class="rating d-flex align-items-center fs-13">
                                    <span class="text-title">4.2</span><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/star.svg" alt="Star Icon">
                                    <span class="text-firod">(110)</span>
                                </div>
                            </div>
                            <ul class="instructor-metainfo list-unstyle d-flex align-items-center justify-content-between">
                                <li class="text-paragraph fs-14"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/book-3.svg" alt="Icon">11 Courses</li>
                                <li class="text-paragraph fs-14 text-end"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/user-2.svg" alt="Icon">45 Students</li>
                            </ul>
                            <ul class="social-profile style-one list-unstyle">
                                <li><a href="https://www.facebook.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-facebook-fill"></i></a></li>
                                <li><a href="https://www.twitter.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-twitter-x-fill"></i></a></li>
                                <li><a href="https://www.instagram.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-instagram-fill"></i></a></li>
                                <li><a href="https://www.linkedin.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-linkedin-fill"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-lg-4 col-md-6">
                    <div class="instructor-card mb-50">
                        <div class="instructor-img">
                            <img src="'.$CFG->wwwroot.'/theme/edvik/pix/instructor/instructor-3.webp" alt="Image">
                        </div>
                        <div class="instructor-info bg-spring position-relative index-1">
                            <div class="instructor-title d-flex align-items-start justify-content-between">
                                <div>
                                    <h3 class="fs-20 font-medium"><a href="'.$CFG->wwwroot.'/course">Jane Ronan</a></h3>
                                    <span class="text-firod">Math Educator</span>
                                </div>
                                <div class="rating d-flex align-items-center fs-13">
                                    <span class="text-title">4.5</span><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/star.svg" alt="Star Icon">
                                    <span class="text-firod">(123)</span>
                                </div>
                            </div>
                            <ul class="instructor-metainfo list-unstyle d-flex align-items-center justify-content-between">
                                <li class="text-paragraph fs-14"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/book-3.svg" alt="Icon">15 Courses</li>
                                <li class="text-paragraph fs-14 text-end"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/user-2.svg" alt="Icon">1.5k Students</li>
                            </ul>
                            <ul class="social-profile style-one list-unstyle">
                                <li><a href="https://www.facebook.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-facebook-fill"></i></a></li>
                                <li><a href="https://www.twitter.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-twitter-x-fill"></i></a></li>
                                <li><a href="https://www.instagram.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-instagram-fill"></i></a></li>
                                <li><a href="https://www.linkedin.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-linkedin-fill"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-lg-4 col-md-6">
                    <div class="instructor-card mb-50">
                        <div class="instructor-img">
                            <img src="'.$CFG->wwwroot.'/theme/edvik/pix/instructor/instructor-4.webp" alt="Image">
                        </div>
                        <div class="instructor-info bg-spring position-relative index-1">
                            <div class="instructor-title d-flex align-items-start justify-content-between">
                                <div>
                                    <h3 class="fs-20 font-medium"><a href="'.$CFG->wwwroot.'/course">Victor James</a></h3>
                                    <span class="text-firod">Engineer</span>
                                </div>
                                <div class="rating d-flex align-items-center fs-13">
                                    <span class="text-title">4.8</span><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/star.svg" alt="Star Icon">
                                    <span class="text-firod">(132)</span>
                                </div>
                            </div>
                            <ul class="instructor-metainfo list-unstyle d-flex align-items-center justify-content-between">
                                <li class="text-paragraph fs-14"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/book-3.svg" alt="Icon">12 Courses</li>
                                <li class="text-paragraph fs-14 text-end"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/user-2.svg" alt="Icon">205 Students</li>
                            </ul>
                            <ul class="social-profile style-one list-unstyle">
                                <li><a href="https://www.facebook.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-facebook-fill"></i></a></li>
                                <li><a href="https://www.twitter.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-twitter-x-fill"></i></a></li>
                                <li><a href="https://www.instagram.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-instagram-fill"></i></a></li>
                                <li><a href="https://www.linkedin.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-linkedin-fill"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-lg-4 col-md-6">
                    <div class="instructor-card mb-50">
                        <div class="instructor-img">
                            <img src="'.$CFG->wwwroot.'/theme/edvik/pix/instructor/instructor-5.webp" alt="Image">
                        </div>
                        <div class="instructor-info bg-spring position-relative index-1">
                            <div class="instructor-title d-flex align-items-start justify-content-between">
                                <div>
                                    <h3 class="fs-20 font-medium"><a href="'.$CFG->wwwroot.'/course">Skyler White</a></h3>
                                    <span class="text-firod">English Educator</span>
                                </div>
                                <div class="rating d-flex align-items-center fs-13">
                                    <span class="text-title">4.4</span><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/star.svg" alt="Star Icon">
                                    <span class="text-firod">(140)</span>
                                </div>
                            </div>
                            <ul class="instructor-metainfo list-unstyle d-flex align-items-center justify-content-between">
                                <li class="text-paragraph fs-14"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/book-3.svg" alt="Icon">16 Courses</li>
                                <li class="text-paragraph fs-14 text-end"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/user-2.svg" alt="Icon">1.3k Students</li>
                            </ul>
                            <ul class="social-profile style-one list-unstyle">
                                <li><a href="https://www.facebook.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-facebook-fill"></i></a></li>
                                <li><a href="https://www.twitter.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-twitter-x-fill"></i></a></li>
                                <li><a href="https://www.instagram.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-instagram-fill"></i></a></li>
                                <li><a href="https://www.linkedin.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-linkedin-fill"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-lg-4 col-md-6">
                    <div class="instructor-card mb-50">
                        <div class="instructor-img">
                            <img src="'.$CFG->wwwroot.'/theme/edvik/pix/instructor/instructor-6.webp" alt="Image">
                        </div>
                        <div class="instructor-info bg-spring position-relative index-1">
                            <div class="instructor-title d-flex align-items-start justify-content-between">
                                <div>
                                    <h3 class="fs-20 font-medium"><a href="'.$CFG->wwwroot.'/course">Britney Loren</a></h3>
                                    <span class="text-firod">Design Educator</span>
                                </div>
                                <div class="rating d-flex align-items-center fs-13">
                                    <span class="text-title">4.9</span><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/star.svg" alt="Star Icon">
                                    <span class="text-firod">(112)</span>
                                </div>
                            </div>
                            <ul class="instructor-metainfo list-unstyle d-flex align-items-center justify-content-between">
                                <li class="text-paragraph fs-14"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/book-3.svg" alt="Icon">14 Courses</li>
                                <li class="text-paragraph fs-14 text-end"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/user-2.svg" alt="Icon">102 Students</li>
                            </ul>
                            <ul class="social-profile style-one list-unstyle">
                                <li><a href="https://www.facebook.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-facebook-fill"></i></a></li>
                                <li><a href="https://www.twitter.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-twitter-x-fill"></i></a></li>
                                <li><a href="https://www.instagram.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-instagram-fill"></i></a></li>
                                <li><a href="https://www.linkedin.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-linkedin-fill"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-lg-4 col-md-6">
                    <div class="instructor-card mb-50">
                        <div class="instructor-img">
                            <img src="'.$CFG->wwwroot.'/theme/edvik/pix/instructor/instructor-7.webp" alt="Image">
                        </div>
                        <div class="instructor-info bg-spring position-relative index-1">
                            <div class="instructor-title d-flex align-items-start justify-content-between">
                                <div>
                                    <h3 class="fs-20 font-medium"><a href="'.$CFG->wwwroot.'/course">Jonatho Smith</a></h3>
                                    <span class="text-firod">Math Educator</span>
                                </div>
                                <div class="rating d-flex align-items-center fs-13">
                                    <span class="text-title">5.00</span><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/star.svg" alt="Star Icon">
                                    <span class="text-firod">(112)</span>
                                </div>
                            </div>
                            <ul class="instructor-metainfo list-unstyle d-flex align-items-center justify-content-between">
                                <li class="text-paragraph fs-14"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/book-3.svg" alt="Icon">20 Courses</li>
                                <li class="text-paragraph fs-14 text-end"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/user-2.svg" alt="Icon">1.4K Students</li>
                            </ul>
                            <ul class="social-profile style-one list-unstyle">
                                <li><a href="https://www.facebook.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-facebook-fill"></i></a></li>
                                <li><a href="https://www.twitter.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-twitter-x-fill"></i></a></li>
                                <li><a href="https://www.instagram.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-instagram-fill"></i></a></li>
                                <li><a href="https://www.linkedin.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-linkedin-fill"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-lg-4 col-md-6">
                    <div class="instructor-card mb-50">
                        <div class="instructor-img">
                            <img src="'.$CFG->wwwroot.'/theme/edvik/pix/instructor/instructor-8.webp" alt="Image">
                        </div>
                        <div class="instructor-info bg-spring position-relative index-1">
                            <div class="instructor-title d-flex align-items-start justify-content-between">
                                <div>
                                    <h3 class="fs-20 font-medium"><a href="'.$CFG->wwwroot.'/course">Jessy Pinkman</a></h3>
                                    <span class="text-firod">Web Designer</span>
                                </div>
                                <div class="rating d-flex align-items-center fs-13">
                                    <span class="text-title">4.2</span><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/star.svg" alt="Star Icon">
                                    <span class="text-firod">(123)</span>
                                </div>
                            </div>
                            <ul class="instructor-metainfo list-unstyle d-flex align-items-center justify-content-between">
                                <li class="text-paragraph fs-14"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/book-3.svg" alt="Icon">8 Courses</li>
                                <li class="text-paragraph fs-14 text-end"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/user-2.svg" alt="Icon">122 Students</li>
                            </ul>
                            <ul class="social-profile style-one list-unstyle">
                                <li><a href="https://www.facebook.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-facebook-fill"></i></a></li>
                                <li><a href="https://www.twitter.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-twitter-x-fill"></i></a></li>
                                <li><a href="https://www.instagram.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-instagram-fill"></i></a></li>
                                <li><a href="https://www.linkedin.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-linkedin-fill"></i></a></li>
                            </ul>
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
