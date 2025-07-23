<?php
global $CFG;
require_once($CFG->dirroot . '/theme/edvik/inc/block_handler/get-content.php');
class block_edvik_promo_area extends block_base {
    public function init() {
        $this->title = get_string('pluginname', 'block_edvik_promo_area');
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
            <div class="promo-wrap">
                <div class="container">
                    <div class="promo-box bg-title round-20">
                        <div class="row justify-content-center">
                            <div class="col-xxl-3 col-xl-4 col-md-6 pe-xxl-0">
                                <div class="promo-card d-flex flex-wrap transition mb-30">
                                    <div class="promo-icon d-flex flex-column align-items-center justify-content-center rounded-circle">
                                        <img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/expert-instructor.svg" alt="Icon" class="transition">
                                    </div>
                                    <div class="promo-info">
                                        <h6 class="fs-20 font-semibold ls-1 text-white">Expert Instructors</h6>
                                        <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll and start learning.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-3 col-xl-4 col-md-6 pe-xxl-0">
                                <div class="promo-card d-flex flex-wrap transition mb-30">
                                    <div class="promo-icon d-flex flex-column align-items-center justify-content-center rounded-circle">
                                        <img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/free-access.svg" alt="Icon" class="transition">
                                    </div>
                                    <div class="promo-info">
                                        <h6 class="fs-20 font-semibold ls-1 text-white">Lifetime Free Access</h6>
                                        <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll and start learning.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-3 col-xl-4 col-md-6 pe-xxl-0">
                                <div class="promo-card active d-flex flex-wrap transition mb-30">
                                    <div class="promo-icon d-flex flex-column align-items-center justify-content-center rounded-circle">
                                        <img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/access.svg" alt="Icon" class="transition">
                                    </div>
                                    <div class="promo-info">
                                        <h6 class="fs-20 font-semibold ls-1 text-white">Access Anywhere</h6>
                                        <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll and start learning.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-3 col-xl-4 col-md-6 pe-xxl-0">
                                <div class="promo-card d-flex flex-wrap transition mb-30">
                                    <div class="promo-icon d-flex flex-column align-items-center justify-content-center rounded-circle">
                                        <img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/certificate.svg" alt="Icon" class="transition">
                                    </div>
                                    <div class="promo-info">
                                        <h6 class="fs-20 font-semibold ls-1 text-white">Certificate</h6>
                                        <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll and start learning.</p>
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