<?php
global $CFG;
require_once($CFG->dirroot . '/theme/edvik/inc/block_handler/get-content.php');
class block_edvik_features_area_three extends block_base {
    public function init() {
        $this->title = get_string('pluginname', 'block_edvik_features_area_three');
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
                <div class="container pt-100 pb-70">
                    <div class="row justify-content-center">
                        <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-6">
                            <div class="feature-card style-two mb-30">
                                <div class="feature-icon bg-yellow d-flex flex-column align-items-center justify-content-center rounded-circle">
                                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/badge.svg" alt="Icons">
                                </div>
                                <h3 class="font-medium">Best In Class Content</h3>
                                <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll.</p>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-6 ps-xxl-3">
                            <div class="feature-card style-two mb-30">
                                <div class="feature-icon bg-orange d-flex flex-column align-items-center justify-content-center rounded-circle">
                                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/advantage.svg" alt="Icons">
                                </div>
                                <h3 class="font-medium">Competitive Advantage</h3>
                                <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll.</p>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-6 ps-xxl-3">
                            <div class="feature-card style-two mb-30">
                                <div class="feature-icon bg-blue d-flex flex-column align-items-center justify-content-center rounded-circle">
                                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/growth.svg" alt="Icons">
                                </div>
                                <h3 class="font-medium">Growth Potential</h3>
                                <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll.</p>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-6 ps-xxl-4">
                            <div class="feature-card style-two mb-30">
                                <div class="feature-icon bg-green d-flex flex-column align-items-center justify-content-center rounded-circle">
                                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/revenue.svg" alt="Icons">
                                </div>
                                <h3 class="font-medium">Growing Revenue</h3>
                                <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll.</p>
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