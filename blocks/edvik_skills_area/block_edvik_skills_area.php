<?php
global $CFG;
require_once($CFG->dirroot . '/theme/edvik/inc/block_handler/get-content.php');
class block_edvik_skills_area extends block_base {
    public function init() {
        $this->title = get_string('pluginname', 'block_edvik_skills_area');
    }

    // Declare second
    public function specialization()
    {
        global $CFG, $DB;
        include($CFG->dirroot . '/theme/edvik/inc/block_handler/specialization.php');
        if (empty($this->config)) {
            $this->config = new \stdClass();

            $this->config->class = 'simple-wrap style-two position-relative index-1 ptb-100';
            $this->config->content = '
                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/shape-15.webp" alt="Shape" class="section-shape-one position-absolute bounce sm-none">
                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/shape-14.webp" alt="Shape" class="section-shape-two position-absolute moveHorizontal sm-none">
                <div class="container">
                    <div class="row align-items-center mb-35">
                        <div class="col-xxl-5 col-xl-6 col-lg-6">
                            <div class="section-title">
                                <span class="fs-13 font-medium d-block text_primary">OVER 6500+ COURSES AVAILABLE</span>
                                <h2 class="d-inline-block font-semibold position-relative mb-0">Enhance your skills with best Online courses</h2>
                            </div>
                        </div>
                        <div class="col-xxl-5 offset-xxl-2 col-xl-5 offset-xl-1 col-lg-6 ps-xxl-4">
                            <p class="section-para mb-0">Explore all of our courses and pick your suitable ones to enroll and start learning with us! Flexible easy to access learning opportunities.</p>
                        </div>
                    </div>
                    <div class="simple-content">
                        <div class="feature-item-wrap d-flex flex-wrap justify-content-between">
                            <div class="feature-item style-three">
                                <h4 class="fs-17 font-medium ls-1 round-10"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/check.svg" alt="Check Icon">Flexible Classes</h4>
                                <div>
                                    <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll and start learning.</p>
                                </div>
                            </div>
                            <div class="feature-item style-four">
                                <h4 class="fs-17 font-medium ls-1 round-10"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/check.svg" alt="Check Icon">Learn From Anywhere</h4>
                                <div>
                                    <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll and start learning.</p>
                                </div>
                            </div>
                            <div class="feature-item style-one">
                                <h4 class="fs-17 font-medium ls-1 round-10"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/check.svg" alt="Check Icon">WorldClass Instructor</h4>
                                <div>
                                    <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll and start learning.</p>
                                </div>
                            </div>
                            <div class="feature-item style-two">
                                <h4 class="fs-17 font-medium ls-1 round-10"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/check.svg" alt="Check Icon">Learn From Expert</h4>
                                <div>
                                    <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll and start learning.</p>
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