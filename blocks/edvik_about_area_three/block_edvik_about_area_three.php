<?php
global $CFG;
require_once($CFG->dirroot . '/theme/edvik/inc/block_handler/get-content.php');
class block_edvik_about_area_three extends block_base {
    public function init() {
        $this->title = get_string('pluginname', 'block_edvik_about_area_three');
    }

    // Declare second
    public function specialization()
    {
        global $CFG, $DB;
        include($CFG->dirroot . '/theme/edvik/inc/block_handler/specialization.php');
        if (empty($this->config)) {
            $this->config = new \stdClass();

            $this->config->class = 'about-wrap style-two position-relative pb-100';
            $this->config->content = '
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