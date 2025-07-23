<?php
global $CFG;
require_once($CFG->dirroot . '/theme/edvik/inc/block_handler/get-content.php');
class block_edvik_numbers extends block_base {
    public function init() {
        $this->title = get_string('pluginname', 'block_edvik_numbers');
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
                <div class="counter-wrap style-three position-relative index-1 pb-100">
                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/shape-10.webp" alt="Shape" class="section-shape position-absolute end-0">
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-lg-6">
                                <div class="counter-img">
                                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/counter-img.webp" alt="Image">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="counter-content">
                                    <div class="section-title">
                                        <span class="fs-13 font-medium d-block text_primary">NUMBERS</span>
                                        <h2 class="d-inline-block font-semibold position-relative">How Edvik is best among other platforms</h2>
                                        <p>Life is dynamic, and so is your learning journey. With our flexible approach, you have the freedom to learn at your own pace. </p>
                                    </div>
                                    <div class="counter-card-wrap d-flex flex-wrap position-relative">
                                        <div class="counter-card style-two round-10 position-relative index-1 mb-25">
                                            <h2 class="font-bold text-blue mb-0"><span class="counter">6500</span>+</h2>
                                            <p class="fs-13 font-medium mb-0">COURSES</p>
                                        </div>
                                        <div class="counter-card style-two round-10 position-relative index-1 mb-25">
                                            <h2 class="font-bold text-orange mb-0"><span class="counter">13500</span>+</h2>
                                            <p class="fs-13 font-medium mb-0">STUDENTS ENROLLED</p>
                                        </div>
                                        <div class="counter-card style-two round-10 position-relative index-1 mb-25">
                                            <h2 class="font-bold text-green mb-0"><span class="counter">1555</span>+</h2>
                                            <p class="fs-13 font-medium mb-0">INSTRUCTORS</p>
                                        </div>
                                        <div class="counter-card style-two round-10 position-relative index-1 mb-25">
                                            <h2 class="font-bold text-violet mb-0"><span class="counter">82.5</span>%</h2>
                                            <p class="fs-13 font-medium mb-0">LEARNERS PROGRESS</p>
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