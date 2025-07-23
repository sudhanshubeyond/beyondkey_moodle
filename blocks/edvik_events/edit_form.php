<?php

class block_edvik_events_edit_form extends block_edit_form {

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
                <div class="container position-relative">
                    <div class="section-title text-center style-five mb-40">
                        <span class="fs-13 font-medium d-block text_primary">EVENTS</span>
                        <h2 class="d-inline-block font-semibold position-relative mb-0">Take part in our upcoming events<img src="'.$CFG->wwwroot.'/theme/edvik/pix/section-title-shape-1.webp" alt="Shape" class="position-absolute bottom-0 end-0"></h2>
                    </div>
                    <div class="event-slider swiper">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="event-card style-one position-relative index-1 overflow-hidden">
                                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/event-shape.webp" alt="Shape" class="event-shape position-absolute bottom-0 end-0 transition">
                                    <span class="fs-14 d-inline-block text-title event-date bg-yellow round-4"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/calendar-3.svg" alt="Icon">Mar 28, 2024</span>
                                    <ul class="event-metainfo list-unstyle">
                                        <li class="position-relative text-paragraph"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/map-2.svg" alt="Calendar Icon"><a href="posts-by-date.html">Vancuver</a></li>
                                        <li class="position-relative text-paragraph"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/clock-5.svg" alt="Clock Icon">10:00 AM - 12:00 PM</li>
                                    </ul>
                                    <h3 class="fs-22 font-medium"><a href="'.$CFG->wwwroot.'/mod/page/view.php?id=8">The Learning Odyssey 2024: Unleashing the Power of Real Knowledge</a></h3>
                                    <a href="'.$CFG->wwwroot.'/mod/page/view.php?id=8" class="btnn style-one">Get The Ticket<i class="ri-arrow-right-line"></i></a>
                                </div>
                            </div> 
                            <div class="swiper-slide">
                                <div class="event-card style-one position-relative index-1 overflow-hidden">
                                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/event-shape.webp" alt="Shape" class="event-shape position-absolute bottom-0 end-0 transition">
                                    <span class="fs-14 d-inline-block text-title event-date bg-yellow round-4"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/calendar-3.svg" alt="Icon">Mar 25, 2024</span>
                                    <ul class="event-metainfo list-unstyle">
                                        <li class="position-relative text-paragraph"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/map-2.svg" alt="Calendar Icon"><a href="posts-by-date.html">Montreal</a></li>
                                        <li class="position-relative text-paragraph"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/clock-5.svg" alt="Clock Icon">11:00 AM - 12:00 PM</li>
                                    </ul>
                                    <h3 class="fs-22 font-medium"><a href="'.$CFG->wwwroot.'/mod/page/view.php?id=8">Digital Classroom Symposium: Navigating The Future Of Education</a></h3>
                                    <a href="'.$CFG->wwwroot.'/mod/page/view.php?id=8" class="btnn style-one">Get The Ticket<i class="ri-arrow-right-line"></i></a>
                                </div>
                            </div> 
                            <div class="swiper-slide">
                                <div class="event-card style-one position-relative index-1 overflow-hidden">
                                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/event-shape.webp" alt="Shape" class="event-shape position-absolute bottom-0 end-0 transition">
                                    <span class="fs-14 d-inline-block text-title event-date bg-yellow round-4"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/calendar-3.svg" alt="Icon">Mar 22, 2024</span>
                                    <ul class="event-metainfo list-unstyle">
                                        <li class="position-relative text-paragraph"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/map-2.svg" alt="Calendar Icon"><a href="posts-by-date.html">British Columbia</a></li>
                                        <li class="position-relative text-paragraph"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/clock-5.svg" alt="Clock Icon">10:00 AM - 12:00 PM</li>
                                    </ul>
                                    <h3 class="fs-22 font-medium"><a href="'.$CFG->wwwroot.'/mod/page/view.php?id=8">Edutech Confluence: Bridging The Gap Between Learning And Technology</a></h3>
                                    <a href="'.$CFG->wwwroot.'/mod/page/view.php?id=8" class="btnn style-one">Get The Ticket<i class="ri-arrow-right-line"></i></a>
                                </div>
                            </div> 
                            <div class="swiper-slide">
                                <div class="event-card style-one position-relative index-1 overflow-hidden">
                                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/event-shape.webp" alt="Shape" class="event-shape position-absolute bottom-0 end-0 transition">
                                    <span class="fs-14 d-inline-block text-title event-date bg-yellow round-4"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/calendar-3.svg" alt="Icon">Mar 20, 2024</span>
                                    <ul class="event-metainfo list-unstyle">
                                        <li class="position-relative text-paragraph"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/map-2.svg" alt="Calendar Icon"><a href="posts-by-date.html">Montreal</a></li>
                                        <li class="position-relative text-paragraph"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/clock-5.svg" alt="Clock Icon">08:00 AM - 12:00 PM</li>
                                    </ul>
                                    <h3 class="fs-22 font-medium"><a href="'.$CFG->wwwroot.'/mod/page/view.php?id=8">Lms Revolution Summit: Transforming Learning Paradigms</a></h3>
                                    <a href="'.$CFG->wwwroot.'/mod/page/view.php?id=8" class="btnn style-one">Get The Ticket<i class="ri-arrow-right-line"></i></a>
                                </div>
                            </div> 
                            <div class="swiper-slide">
                                <div class="event-card style-one position-relative index-1 overflow-hidden">
                                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/event-shape.webp" alt="Shape" class="event-shape position-absolute bottom-0 end-0 transition">
                                    <span class="fs-14 d-inline-block text-title event-date bg-yellow round-4"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/calendar-3.svg" alt="Icon">Mar 17, 2024</span>
                                    <ul class="event-metainfo list-unstyle">
                                        <li class="position-relative text-paragraph"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/map-2.svg" alt="Calendar Icon"><a href="posts-by-date.html">New Brunswick</a></li>
                                        <li class="position-relative text-paragraph"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/clock-5.svg" alt="Clock Icon">10:00 AM - 12:00 PM</li>
                                    </ul>
                                    <h3 class="fs-22 font-medium"><a href="'.$CFG->wwwroot.'/mod/page/view.php?id=8">Mastermind Webinars: Elevate Your Learning Experience With Edvik Learning Platform</a></h3>
                                    <a href="'.$CFG->wwwroot.'/mod/page/view.php?id=8" class="btnn style-one">Get The Ticket<i class="ri-arrow-right-line"></i></a>
                                </div>
                            </div> 
                        </div>
                    </div>
                    <div class="slider-pagination">
                        <div class="event-prev"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/left-arrow.svg" alt="Left icon"></div>
                        <div class="event-next"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/right-arrow.svg" alt="Right icon"></div>
                    </div>
                </div>
            ​');
        $mform->setType('config_content', PARAM_RAW);

        // Section Image header title according to language file.
        $mform->addElement('header', 'config_image_heading', get_string('config_image_heading', 'theme_edvik'));

        $mform->addElement('static', 'config_image_doc', '<b><a style="color: var(--primaryColor)" href="https://docs.hibootstrap.com/envydoc/edvik-moodle-theme-documentation/faqs/how-to-get-the-image-url//" target="_blank">Doc link: How to make Image URL?</a></b>');
    }
}
