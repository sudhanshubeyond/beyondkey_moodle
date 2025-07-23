<?php
global $CFG;
require_once($CFG->dirroot . '/theme/edvik/inc/block_handler/get-content.php');
class block_edvik_about_banner extends block_base {
    public function init() {
        $this->title = get_string('pluginname', 'block_edvik_about_banner');
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
                <div class="breadcrumb-wrap style-four bg-f round-10 position-relative index-1" style="background-image:url('.$CFG->wwwroot.'/theme/edvik/pix/about/about-bg.webp);">
                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/shape-12.webp" alt="Shape" class="br-shape-one position-absolute moveHorizontal">
                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/shape-13.webp" alt="Shape" class="br-shape-two position-absolute bounce">
                    <div class="container text-center">
                        <div class="row">
                            <div class="col-xl-8 offset-xl-2 col-lg-8 offset-lg-2 px-xxl-5">
                                <h2 class="br-title font-semibold text-white ls-1">We share knowledge with the world to change learning for the better</h2>
                                <ul class="br-menu list-unstyle">
                                    <li><a href="'.$CFG->wwwroot.'">Home</a></li>
                                    <li>About Us</li>
                                </ul>
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