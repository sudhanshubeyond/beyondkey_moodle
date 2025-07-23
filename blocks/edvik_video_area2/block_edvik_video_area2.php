<?php
global $CFG;
require_once($CFG->dirroot . '/theme/edvik/inc/block_handler/get-content.php');
class block_edvik_video_area2 extends block_base {
    public function init() {
        $this->title = get_string('pluginname', 'block_edvik_video_area2');
    }
    // Declare second
    public function specialization()
    {
        global $CFG, $DB;
        include($CFG->dirroot . '/theme/edvik/inc/block_handler/specialization.php');
        if (empty($this->config)) {
            $this->config = new \stdClass();

            $this->config->class = 'video-wrap style-two position-relative index-1';
            $this->config->content = '
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