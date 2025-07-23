<?php
global $CFG;
require_once($CFG->dirroot . '/theme/edvik/inc/block_handler/get-content.php');
class block_edvik_events_2 extends block_base {
    public function init() {
        $this->title = get_string('pluginname', 'block_edvik_events_2');
    }

    // Declare second
    public function specialization()
    {
        global $CFG, $DB;
        include($CFG->dirroot . '/theme/edvik/inc/block_handler/specialization.php');
        if (empty($this->config)) {
            $this->config = new \stdClass();

            $this->config->class = 'bg-aqua mt-20';
            $this->config->content = '
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
            ​';
        }
    }

    public function get_content() {
        global $CFG, $DB;
        if ($this->content !== null) {
          return $this->content;
        }
        $this->content         =  new stdClass;

        $text = '';
        $text .= '
            <div class="'.$this->config->class.'">
                '.format_text($this->config->content, FORMAT_HTML, array('filter' => true)).' 
            </div> ';
        $this->content         =  new stdClass;
        $this->content->footer = '';
        $this->content->text   = $text;

        return $this->content;
    }

    /**
     * The block can be used repeatedly in a page.
     */
    function instance_allow_multiple() {
        return true;
    }

    /**
     * Enables global configuration of the block in settings.php.
     *
     * @return bool True if the global configuration is enabled.
     */
    function has_config() {
        return true;
    }

    /**
     * Sets the applicable formats for the block.
     *
     * @return string[] Array of pages and permissions.
     */
    function applicable_formats() {
        return array(
            'all' => true,
            'my' => true,
            'admin' => true,
            'course-view' => true,
            'course' => true,
        );
    }

}