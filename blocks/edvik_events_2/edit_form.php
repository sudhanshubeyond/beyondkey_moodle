<?php

class block_edvik_events_2_edit_form extends block_edit_form {

    protected function specific_definition($mform) {

        global $CFG;

        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));
 
        // Class
        $mform->addElement('text', 'config_class', get_string('config_class', 'theme_edvik'));
        $mform->setDefault('config_class', 'bg-aqua mt-20​');
        $mform->setType('config_class', PARAM_RAW);

        // Content
        $mform->addElement('textarea', 'config_content', 'Content', 'wrap="virtual" rows="20" cols="40"');
        $mform->setDefault('config_content', 
            '
                <div class="container ptb-100">
                    <div class="row justify-content-center">
                        <div class="col-xl-4 col-md-6">
                            <div class="event-card style-one position-relative index-1 mb-50 overflow-hidden">
                                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/event-shape.webp" alt="Shape" class="event-shape position-absolute bottom-0 end-0 transition">
                                <span class="fs-14 d-inline-block text-title event-date bg-yellow round-4"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/calendar-3.svg" alt="Icon">Mar 28, 2024</span>
                                <ul class="event-metainfo list-unstyle">
                                    <li class="position-relative text-paragraph"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/map-2.svg" alt="Calendar Icon"><a href="'.$CFG->wwwroot.'">Vancuver</a></li>
                                    <li class="position-relative text-paragraph"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/clock-5.svg" alt="Clock Icon">10:00 AM - 12:00 PM</li>
                                </ul>
                                <h3 class="fs-22 font-medium"><a href="'.$CFG->wwwroot.'">The Learning Odyssey 2024: Unleashing the Power of Real Knowledge</a></h3>
                                <a href="'.$CFG->wwwroot.'" class="btnn style-one">Get The Ticket<i class="ri-arrow-right-line"></i></a>
                            </div>
                        </div> 
                        <div class="col-xl-4 col-md-6">
                            <div class="event-card style-one position-relative index-1 mb-50 overflow-hidden">
                                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/event-shape.webp" alt="Shape" class="event-shape position-absolute bottom-0 end-0 transition">
                                <span class="fs-14 d-inline-block text-title event-date bg-yellow round-4"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/calendar-3.svg" alt="Icon">Mar 25, 2024</span>
                                <ul class="event-metainfo list-unstyle">
                                    <li class="position-relative text-paragraph"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/map-2.svg" alt="Calendar Icon"><a href="'.$CFG->wwwroot.'">Montreal</a></li>
                                    <li class="position-relative text-paragraph"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/clock-5.svg" alt="Clock Icon">11:00 AM - 12:00 PM</li>
                                </ul>
                                <h3 class="fs-22 font-medium"><a href="'.$CFG->wwwroot.'">Digital Classroom Symposium: Navigating The Future Of Education</a></h3>
                                <a href="'.$CFG->wwwroot.'" class="btnn style-one">Get The Ticket<i class="ri-arrow-right-line"></i></a>
                            </div>
                        </div> 
                        <div class="col-xl-4 col-md-6">
                            <div class="event-card style-one position-relative index-1 mb-50 overflow-hidden">
                                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/event-shape.webp" alt="Shape" class="event-shape position-absolute bottom-0 end-0 transition">
                                <span class="fs-14 d-inline-block text-title event-date bg-yellow round-4"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/calendar-3.svg" alt="Icon">Mar 22, 2024</span>
                                <ul class="event-metainfo list-unstyle">
                                    <li class="position-relative text-paragraph"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/map-2.svg" alt="Calendar Icon"><a href="'.$CFG->wwwroot.'">British Columbia</a></li>
                                    <li class="position-relative text-paragraph"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/clock-5.svg" alt="Clock Icon">10:00 AM - 12:00 PM</li>
                                </ul>
                                <h3 class="fs-22 font-medium"><a href="'.$CFG->wwwroot.'">Edutech Confluence: Bridging The Gap Between Learning And Technology</a></h3>
                                <a href="'.$CFG->wwwroot.'" class="btnn style-one">Get The Ticket<i class="ri-arrow-right-line"></i></a>
                            </div>
                        </div> 
                        <div class="col-xl-4 col-md-6">
                            <div class="event-card style-one position-relative index-1 mb-50 overflow-hidden">
                                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/event-shape.webp" alt="Shape" class="event-shape position-absolute bottom-0 end-0 transition">
                                <span class="fs-14 d-inline-block text-title event-date bg-yellow round-4"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/calendar-3.svg" alt="Icon">Mar 20, 2024</span>
                                <ul class="event-metainfo list-unstyle">
                                    <li class="position-relative text-paragraph"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/map-2.svg" alt="Calendar Icon"><a href="'.$CFG->wwwroot.'">Montreal</a></li>
                                    <li class="position-relative text-paragraph"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/clock-5.svg" alt="Clock Icon">08:00 AM - 12:00 PM</li>
                                </ul>
                                <h3 class="fs-22 font-medium"><a href="'.$CFG->wwwroot.'">Lms Revolution Summit: Transforming Learning Paradigms</a></h3>
                                <a href="'.$CFG->wwwroot.'" class="btnn style-one">Get The Ticket<i class="ri-arrow-right-line"></i></a>
                            </div>
                        </div> 
                        <div class="col-xl-4 col-md-6">
                            <div class="event-card style-one position-relative index-1 mb-50 overflow-hidden">
                                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/event-shape.webp" alt="Shape" class="event-shape position-absolute bottom-0 end-0 transition">
                                <span class="fs-14 d-inline-block text-title event-date bg-yellow round-4"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/calendar-3.svg" alt="Icon">Mar 17, 2024</span>
                                <ul class="event-metainfo list-unstyle">
                                    <li class="position-relative text-paragraph"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/map-2.svg" alt="Calendar Icon"><a href="'.$CFG->wwwroot.'">New Brunswick</a></li>
                                    <li class="position-relative text-paragraph"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/clock-5.svg" alt="Clock Icon">10:00 AM - 12:00 PM</li>
                                </ul>
                                <h3 class="fs-22 font-medium"><a href="'.$CFG->wwwroot.'">Mastermind Webinars: Elevate Your Learning Experience With Edvik Learning Platform</a></h3>
                                <a href="'.$CFG->wwwroot.'" class="btnn style-one">Get The Ticket<i class="ri-arrow-right-line"></i></a>
                            </div>
                        </div> 
                        <div class="col-xl-4 col-md-6">
                            <div class="event-card style-one position-relative index-1 mb-50 overflow-hidden">
                                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/event-shape.webp" alt="Shape" class="event-shape position-absolute bottom-0 end-0 transition">
                                <span class="fs-14 d-inline-block text-title event-date bg-yellow round-4"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/calendar-3.svg" alt="Icon">Mar 15, 2024</span>
                                <ul class="event-metainfo list-unstyle">
                                    <li class="position-relative text-paragraph"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/map-2.svg" alt="Calendar Icon"><a href="'.$CFG->wwwroot.'">Vancuver</a></li>
                                    <li class="position-relative text-paragraph"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/clock-5.svg" alt="Clock Icon">08:00 AM - 12:00 PM</li>
                                </ul>
                                <h3 class="fs-22 font-medium"><a href="'.$CFG->wwwroot.'">Mastermind Webinars: Elevate Your Learning Experience With Edvik Learning Platform</a></h3>
                                <a href="'.$CFG->wwwroot.'" class="btnn style-one">Get The Ticket<i class="ri-arrow-right-line"></i></a>
                            </div>
                        </div> 
                        <div class="col-xl-4 col-md-6">
                            <div class="event-card style-one position-relative index-1 mb-50 overflow-hidden">
                                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/event-shape.webp" alt="Shape" class="event-shape position-absolute bottom-0 end-0 transition">
                                <span class="fs-14 d-inline-block text-title event-date bg-yellow round-4"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/calendar-3.svg" alt="Icon">Mar 14, 2024</span>
                                <ul class="event-metainfo list-unstyle">
                                    <li class="position-relative text-paragraph"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/map-2.svg" alt="Calendar Icon"><a href="'.$CFG->wwwroot.'">Vancuver</a></li>
                                    <li class="position-relative text-paragraph"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/clock-5.svg" alt="Clock Icon">10:00 AM - 12:00 PM</li>
                                </ul>
                                <h3 class="fs-22 font-medium"><a href="'.$CFG->wwwroot.'">Virtual Learning Fest: A Celebration Of Digital Education</a></h3>
                                <a href="'.$CFG->wwwroot.'" class="btnn style-one">Get The Ticket<i class="ri-arrow-right-line"></i></a>
                            </div>
                        </div> 
                        <div class="col-xl-4 col-md-6">
                            <div class="event-card style-one position-relative index-1 mb-50 overflow-hidden">
                                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/event-shape.webp" alt="Shape" class="event-shape position-absolute bottom-0 end-0 transition">
                                <span class="fs-14 d-inline-block text-title event-date bg-yellow round-4"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/calendar-3.svg" alt="Icon">Mar 11, 2024</span>
                                <ul class="event-metainfo list-unstyle">
                                    <li class="position-relative text-paragraph"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/map-2.svg" alt="Calendar Icon"><a href="'.$CFG->wwwroot.'">British Columbia</a></li>
                                    <li class="position-relative text-paragraph"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/clock-5.svg" alt="Clock Icon">11:00 AM - 12:00 PM</li>
                                </ul>
                                <h3 class="fs-22 font-medium"><a href="'.$CFG->wwwroot.'">Global Learning Gala: Connecting Minds Across Borders</a></h3>
                                <a href="'.$CFG->wwwroot.'" class="btnn style-one">Get The Ticket<i class="ri-arrow-right-line"></i></a>
                            </div>
                        </div> 
                        <div class="col-xl-4 col-md-6">
                            <div class="event-card style-one position-relative index-1 mb-50 overflow-hidden">
                                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/event-shape.webp" alt="Shape" class="event-shape position-absolute bottom-0 end-0 transition">
                                <span class="fs-14 d-inline-block text-title event-date bg-yellow round-4"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/calendar-3.svg" alt="Icon">Mar 10, 2024</span>
                                <ul class="event-metainfo list-unstyle">
                                    <li class="position-relative text-paragraph"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/map-2.svg" alt="Calendar Icon"><a href="'.$CFG->wwwroot.'">New Brunswick</a></li>
                                    <li class="position-relative text-paragraph"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/clock-5.svg" alt="Clock Icon">07:00 AM - 12:00 PM</li>
                                </ul>
                                <h3 class="fs-22 font-medium"><a href="'.$CFG->wwwroot.'">Empowered Series: Harnessing The Potential Of Online Learning</a></h3>
                                <a href="'.$CFG->wwwroot.'" class="btnn style-one">Get The Ticket<i class="ri-arrow-right-line"></i></a>
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
