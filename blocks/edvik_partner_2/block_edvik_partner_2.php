<?php
global $CFG;
require_once($CFG->dirroot . '/theme/edvik/inc/block_handler/get-content.php');
class block_edvik_partner_2 extends block_base {
    public function init() {
        $this->title = get_string('pluginname', 'block_edvik_partner_2');
    }

    // Declare second
    public function specialization(){
        global $CFG, $DB;
        include($CFG->dirroot . '/theme/edvik/inc/block_handler/specialization.php');
        if (empty($this->config)) {
            $this->config = new \stdClass();

            $this->config->class = 'container pb-60';
            $this->config->content = '
            <div class="brand-wrap d-flex flex-wrap justify-content-between">
                <div class="brand-card d-flex flex-column align-items-center justify-content-center mb-40">
                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/brand/brand-1.webp" alt="Brand" class="d-block mx-auto">
                </div>
                <div class="brand-card d-flex flex-column align-items-center justify-content-center mb-40">
                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/brand/brand-2.webp" alt="Brand" class="d-block mx-auto">
                </div>
                <div class="brand-card d-flex flex-column align-items-center justify-content-center mb-40">
                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/brand/brand-3.webp" alt="Brand" class="d-block mx-auto">
                </div>
                <div class="brand-card d-flex flex-column align-items-center justify-content-center mb-40">
                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/brand/brand-4.webp" alt="Brand" class="d-block mx-auto">
                </div>
                <div class="brand-card d-flex flex-column align-items-center justify-content-center mb-40">
                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/brand/brand-5.webp" alt="Brand" class="d-block mx-auto">
                </div>
                <div class="brand-card d-flex flex-column align-items-center justify-content-center mb-40">
                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/brand/brand-6.webp" alt="Brand" class="d-block mx-auto">
                </div>
                <div class="brand-card d-flex flex-column align-items-center justify-content-center mb-40">
                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/brand/brand-7.webp" alt="Brand" class="d-block mx-auto">
                </div>
                <div class="brand-card d-flex flex-column align-items-center justify-content-center mb-40">
                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/brand/brand-8.webp" alt="Brand" class="d-block mx-auto">
                </div>
                <div class="brand-card d-flex flex-column align-items-center justify-content-center mb-40">
                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/brand/brand-9.webp" alt="Brand" class="d-block mx-auto">
                </div>
                <div class="brand-card d-flex flex-column align-items-center justify-content-center mb-40">
                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/brand/brand-10.webp" alt="Brand" class="d-block mx-auto">
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