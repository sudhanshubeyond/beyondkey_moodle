<?php
global $CFG;
require_once($CFG->dirroot . '/theme/edvik/inc/block_handler/get-content.php');
class block_edvik_events extends block_base {
    public function init() {
        $this->title = get_string('pluginname', 'block_edvik_events');
    }

    // Declare second
    public function specialization()
    {
        global $CFG, $DB;
        include($CFG->dirroot . '/theme/edvik/inc/block_handler/specialization.php');
        if (empty($this->config)) {
            $this->config = new \stdClass();

            $this->config->class = 'bg-aqua ptb-100';
            $this->config->content = '
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