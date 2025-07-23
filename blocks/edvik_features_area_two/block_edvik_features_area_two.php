<?php
global $CFG;
require_once($CFG->dirroot . '/theme/edvik/inc/block_handler/get-content.php');
class block_edvik_features_area_two extends block_base {
    public function init() {
        $this->title = get_string('pluginname', 'block_edvik_features_area_two');
    }

    // Declare second
    public function specialization()
    {
        global $CFG, $DB;
        include($CFG->dirroot . '/theme/edvik/inc/block_handler/specialization.php');
        if (empty($this->config)) {
            $this->config = new \stdClass();

            $this->config->class = '';
            $this->config->content = '
                <div class="container">
                    <div class="wh-wrap style-one position-relative index-1 pt-100 pb-70">
                        <div class="section-title style-nine text-center mb-60">
                            <span class="fs-13 font-medium d-block text_primary">WHY EDVIK</span>
                            <h2 class="d-inline-block font-semibold position-relative mb-0">Here is the future of distant learning<img src="'.$CFG->wwwroot.'/theme/edvik/pix/section-title-shape-1.webp" alt="Shape" class="position-absolute bottom-0 end-0"></h2>
                        </div>
                        <div class="wh-card-wrap d-flex flex-wrap">
                            <div class="wh-card position-relative index-1 mb-30">
                                <div class="wh-title d-flex align-items-center">
                                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/about/rocket.svg" alt="Image">
                                    <h3 class="fs-22 font-medium ls-1 mb-0">Learn the latest top skills</h3>
                                </div>
                                <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll and start learning.</p>
                            </div>
                            <div class="wh-card position-relative index-1 mb-30">
                                <div class="wh-title d-flex align-items-center">
                                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/about/video-conference.svg" alt="Image">
                                    <h3 class="fs-22 font-medium ls-1 mb-0">Learn from industry experts</h3>
                                </div>
                                <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll and start learning.</p>
                            </div>
                            <div class="wh-card position-relative index-1 mb-30">
                                <div class="wh-title d-flex align-items-center">
                                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/about/goal.svg" alt="Image">
                                    <h3 class="fs-22 font-medium ls-1 mb-0">Learn in your own pace</h3>
                                </div>
                                <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll and start learning.</p>
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