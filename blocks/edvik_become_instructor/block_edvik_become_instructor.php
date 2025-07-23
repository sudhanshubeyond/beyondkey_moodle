<?php
global $CFG;
require_once($CFG->dirroot . '/theme/edvik/inc/block_handler/get-content.php');
class block_edvik_become_instructor extends block_base {
    public function init() {
        $this->title = get_string('pluginname', 'block_edvik_become_instructor');
    }
    // Declare second
    public function specialization()
    {
        global $CFG, $DB;
        include($CFG->dirroot . '/theme/edvik/inc/block_handler/specialization.php');
        if (empty($this->config)) {
            $this->config = new \stdClass();

            $this->config->class = 'container pb-100';
            $this->config->content = '
 <div class="row align-items-center">
    <div class="col-lg-6">
        <div class="be-insturctor-img position-relative index-1">
            <img src="'.$CFG->wwwroot.'/theme/edvik/pix/shape-4.webp" alt="Shape" class="shape-one position-absolute">
            <img src="'.$CFG->wwwroot.'/theme/edvik/pix/shape-5.webp" alt="Shape" class="shape-two position-absolute">
            <img src="'.$CFG->wwwroot.'/theme/edvik/pix/instructor/become-instructor.webp" alt="Image" class="d-block ms-auto">
        </div>
    </div>
    <div class="col-xxl-5 offset-xxl-1 col-lg-6 ps-xxl-0">
        <div class="be-instructor-content">
            <div class="section-title">
                <span class="fs-13 d-block font-medium text_primary">BECOME AN INSTRUCTOR</span>
                <h2 class="d-inline-block font-semibold position-relative mb-20">Join our community as a renowned educator</h2>
                <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll and start learning with us! Flexible easy to access learning opportunities.</p>
            </div>
            <ul class="feature-list list-unstyle">
                <li class="text-title position-relative"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/check.svg" alt="Check Icon" class="position-absolute start-0">Sell your course</li>
                <li class="text-title position-relative"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/check.svg" alt="Check Icon" class="position-absolute start-0">Join as a community partner of 1342+ members</li>
                <li class="text-title position-relative"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/check.svg" alt="Check Icon" class="position-absolute start-0">Become an affiliate partner</li>
            </ul>
            <a href="'.$CFG->wwwroot.'/course" class="btnn style-two round-10">Start Teaching Today</a>
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