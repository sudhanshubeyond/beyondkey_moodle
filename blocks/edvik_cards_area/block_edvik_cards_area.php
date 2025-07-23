<?php
global $CFG;
require_once($CFG->dirroot . '/theme/edvik/inc/block_handler/get-content.php');
class block_edvik_cards_area extends block_base {
    public function init() {
        $this->title = get_string('pluginname', 'block_edvik_cards_area');
    }

    // Declare second
    public function specialization()
    {
        global $CFG, $DB;
        include($CFG->dirroot . '/theme/edvik/inc/block_handler/specialization.php');
        if (empty($this->config)) {
            $this->config = new \stdClass();

            $this->config->class = 'container pb-70';
            $this->config->content = '
            <div class="row">
                <div class="col-lg-6">
                    <div class="event-card style-two d-flex align-items-center justify-content-between position-relative index-1 mb-30">
                        <img src="'.$CFG->wwwroot.'/theme/edvik/pix/event/event-shape.webp" alt="Image" class="event-card-shape position-absolute bottom-0 end-0">
                        <div class="event-content">
                            <span class="fs-13 font-medium text-orange">EVENT</span>
                            <h3 class="fs-32 ls-1 font-semibold">Join Our Virtual Event On AI</h3>
                            <p class="text-tandora ls-1">Elevate your learning experience by tapping into the wealth of knowledge.</p>
                            <a href="'.$CFG->wwwroot.'/login/signup.php" class="link style-one fs-15 bg-transparent border-0 p-0" >Register Now<i class="ri-arrow-right-line"></i></a>
                        </div>
                        <div class="event-img">
                            <img src="'.$CFG->wwwroot.'/theme/edvik/pix/event/event-2.webp" alt="Event Image">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="event-card style-three d-flex align-items-center justify-content-between position-relative index-1 mb-30">
                        <img src="'.$CFG->wwwroot.'/theme/edvik/pix/event/event-shape.webp" alt="Image" class="event-card-shape position-absolute bottom-0 end-0">
                        <div class="event-content">
                            <span class="fs-13 font-medium text-greenTwo">DISCOUNT</span>
                            <h3 class="fs-32 ls-1 font-semibold">10% Discount for the new learners</h3>
                            <p class="text-tandora ls-1">Elevate your learning experience by tapping into the wealth of knowledge.</p>
                            <a href="'.$CFG->wwwroot.'/login/signup.php" class="link style-one fs-15 bg-transparent border-0 p-0" >Register Now<i class="ri-arrow-right-line"></i></a>
                        </div>
                        <div class="event-img">
                            <img src="'.$CFG->wwwroot.'/theme/edvik/pix/event/event-3.webp" alt="Event Image">
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