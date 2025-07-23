<?php
global $CFG;
require_once($CFG->dirroot . '/theme/edvik/inc/block_handler/get-content.php');
class block_edvik_video_area extends block_base {
    public function init() {
        $this->title = get_string('pluginname', 'block_edvik_video_area');
    }

    // Declare second
    public function specialization()
    {
        global $CFG, $DB;
        include($CFG->dirroot . '/theme/edvik/inc/block_handler/specialization.php');
        if (empty($this->config)) {
            $this->config = new \stdClass();

            $this->config->class = 'video-wrap style-one position-relative index-1';
            $this->config->content = '
                <div class="container position-relative">
                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/video/video-shape-1.webp" alt="Image" class="video-shape-one position-absolute">
                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/video/video-shape-2.webp" alt="Image" class="video-shape-two position-absolute">
                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/video/video-shape-3.webp" alt="Image" class="video-shape-three position-absolute">
                    <div class="row">
                        <div class="col-xl-10 offset-xl-1 col-md-10 offset-md-1">
                            <div class="video-bg position-relative index-1 bg-f round-20 ptb-100" style="background-image:url('.$CFG->wwwroot.'/theme/edvik/pix/video/video-bg-1.webp);">
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