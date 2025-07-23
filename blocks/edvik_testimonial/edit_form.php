<?php

class block_edvik_testimonial_edit_form extends block_edit_form {

    protected function specific_definition($mform) {

        global $CFG;

        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));
 
        // Class
        $mform->addElement('text', 'config_class', get_string('config_class', 'theme_edvik'));
        $mform->setDefault('config_class', 'container ptb-100​');
        $mform->setType('config_class', PARAM_RAW);

        // Content
        $mform->addElement('textarea', 'config_content', 'Content', 'wrap="virtual" rows="20" cols="40"');
        $mform->setDefault('config_content', 
            '
            <div class="row align-items-center mb-30">
                <div class="col-md-8">
                    <div class="section-title style-eight">
                        <span class="fs-13 font-medium d-block text_primary">TESTIMONIAL</span>
                        <h2 class="d-inline-block font-semibold position-relative mb-0">What Learners say about Edvik<img src="'.$CFG->wwwroot.'/theme/edvik/pix/section-title-shape-2.webp" alt="Shape" class="position-absolute bottom-0 end-0"></h2>
                    </div>
                </div>
                <div class="col-md-4 text-md-end mt-sm-20">
                    <a href="#" class="btnn style-one">Explore All Testimonials<i class="ri-arrow-right-line"></i></a>
                </div>
            </div>
            <div class="testimonial-slider-one swiper">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="testimonial-card style-one position-relative">
                            <img src="'.$CFG->wwwroot.'/theme/edvik/pix/testimonial-shape.webp" alt="Shape" class="testimonial-shape position-absolute transition">
                            <img src="'.$CFG->wwwroot.'/theme/edvik/pix/testimonial-shape-2.webp" alt="Shape" class="testimonial-shape-two position-absolute bottom-0 start-0 transition">
                            <img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/quote-1.svg" alt="Quote Icon">
                            <h3 class="fs-22 font-medium ls-1 text-orange">“Greate Support & Quality Trainer!”</h3>
                            <p class="font-medium text-tandora ls-1">Instructors from around the world teach millions of students on Edvik. They provide the top best tools and learning materials.</p>
                            <div class="client-info-wrap d-flex flex-wrap align-items-center">
                                <div class="client-img rounded-circle">
                                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/instructor/instructor-thumb-6.webp" alt="Client" class="rounded-circle">
                                </div>
                                <div class="client-info">
                                    <h5 class="fs-18 font-regular">Jehny Watson</h5>
                                    <span class="fs-15 text-paraTwo">Student</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="testimonial-card style-one position-relative">
                            <img src="'.$CFG->wwwroot.'/theme/edvik/pix/testimonial-shape-3.webp" alt="Shape" class="testimonial-shape position-absolute transition">
                            <img src="'.$CFG->wwwroot.'/theme/edvik/pix/testimonial-shape-2.webp" alt="Shape" class="testimonial-shape-two position-absolute bottom-0 start-0 transition">
                            <img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/quote-1.svg" alt="Quote Icon">
                            <h3 class="fs-22 font-medium ls-1 text-orange">“Best Quality Trainer I’ve Ever Seen!”</h3>
                            <p class="font-medium text-tandora ls-1">Instructors from around the world teach millions of students on Edvik. They provide the top best tools and learning materials.</p>
                            <div class="client-info-wrap d-flex flex-wrap align-items-center">
                                <div class="client-img rounded-circle">
                                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/instructor/instructor-thumb-7.webp" alt="Client" class="rounded-circle">
                                </div>
                                <div class="client-info">
                                    <h5 class="fs-18 font-regular">Angela Carter</h5>
                                    <span class="fs-15 text-paraTwo">Student</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="testimonial-card style-one position-relative">
                            <img src="'.$CFG->wwwroot.'/theme/edvik/pix/testimonial-shape.webp" alt="Shape" class="testimonial-shape position-absolute transition">
                            <img src="'.$CFG->wwwroot.'/theme/edvik/pix/testimonial-shape-2.webp" alt="Shape" class="testimonial-shape-two position-absolute bottom-0 start-0 transition">
                            <img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/quote-1.svg" alt="Quote Icon">
                            <h3 class="fs-22 font-medium ls-1 text-orange">“Greate Support & Quality Trainer!”</h3>
                            <p class="font-medium text-tandora ls-1">Instructors from around the world teach millions of students on Edvik. They provide the top best tools and learning materials.</p>
                            <div class="client-info-wrap d-flex flex-wrap align-items-center">
                                <div class="client-img rounded-circle">
                                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/instructor/instructor-thumb-8.webp" alt="Client" class="rounded-circle">
                                </div>
                                <div class="client-info">
                                    <h5 class="fs-18 font-regular">Tony Ronan</h5>
                                    <span class="fs-15 text-paraTwo">Web Designer</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="testimonial-card style-one position-relative">
                            <img src="'.$CFG->wwwroot.'/theme/edvik/pix/testimonial-shape-3.webp" alt="Shape" class="testimonial-shape position-absolute transition">
                            <img src="'.$CFG->wwwroot.'/theme/edvik/pix/testimonial-shape-2.webp" alt="Shape" class="testimonial-shape-two position-absolute bottom-0 start-0 transition">
                            <img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/quote-1.svg" alt="Quote Icon">
                            <h3 class="fs-22 font-medium ls-1 text-orange">“Best Quality Trainer I’ve Ever Seen!”</h3>
                            <p class="font-medium text-tandora ls-1">Instructors from around the world teach millions of students on Edvik. They provide the top best tools and learning materials.</p>
                            <div class="client-info-wrap d-flex flex-wrap align-items-center">
                                <div class="client-img rounded-circle">
                                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/instructor/instructor-thumb-5.webp" alt="Client" class="rounded-circle">
                                </div>
                                <div class="client-info">
                                    <h5 class="fs-18 font-regular">Jay Cutler</h5>
                                    <span class="fs-15 text-paraTwo">Fitness Trainer</span>
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
