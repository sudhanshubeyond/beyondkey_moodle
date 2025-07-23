<?php
global $CFG;
require_once($CFG->dirroot . '/theme/edvik/inc/block_handler/get-content.php');
class block_edvik_features_area extends block_base {
    public function init() {
        $this->title = get_string('pluginname', 'block_edvik_features_area');
    }

    // Declare second
    public function specialization()
    {
        global $CFG, $DB;
        include($CFG->dirroot . '/theme/edvik/inc/block_handler/specialization.php');
        if (empty($this->config)) {
            $this->config = new \stdClass();

            $this->config->class = 'bg-aqua ptb-100';
            $this->config->content = '
                <div class="container">
                    <div class="section-title text-center style-seven mb-50">
                        <span class="fs-13 font-medium d-block text_primary">TOP FEATURES</span>
                        <h2 class="d-inline-block font-semibold position-relative mb-0">Top Features discover your perfect solution<img src="'.$CFG->wwwroot.'/theme/edvik/pix/section-title-shape-1.webp" alt="Shape" class="position-absolute bottom-0 end-0"></h2>
                    </div>
                    <div class="row justify-content-center gx-xxl-5">
                        <div class="col-lg-6 col-md-12">
                            <div class="feature-card position-relative bg-white style-one d-flex flex-wrap align-items-center round-6 mb-30">
                                <div class="feature-icon bg-green d-flex flex-column align-items-center justify-content-center round-6">
                                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/calender-2.svg" alt="Image" class="Icons">
                                </div>
                                <div class="feature-info">
                                    <h3 class="font-medium"><a href="'.$CFG->wwwroot.'/course/index.php?categoryid=2">Earn certificates and degrees</a></h3>
                                    <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll and start learning.</p>
                                </div>
                                <a href="'.$CFG->wwwroot.'/course/index.php?categoryid=2" class="feature-link d-flex flex-column align-items-center justify-content-center rounded-circle position-absolute bg-title text-white"><i class="ri-arrow-right-line"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="feature-card position-relative bg-white style-one d-flex flex-wrap align-items-center round-6 mb-30">
                                <div class="feature-icon bg-orange d-flex flex-column align-items-center justify-content-center round-6">
                                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/memory-2.svg" alt="Image" class="Icons">
                                </div>
                                <div class="feature-info">
                                    <h3 class="font-medium"><a href="'.$CFG->wwwroot.'/course/index.php?categoryid=2">In-Demand Trendy Topics</a></h3>
                                    <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll and start learning.</p>
                                </div>
                                <a href="'.$CFG->wwwroot.'/course/index.php?categoryid=2" class="feature-link d-flex flex-column align-items-center justify-content-center rounded-circle position-absolute bg-title text-white"><i class="ri-arrow-right-line"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="feature-card position-relative bg-white style-one d-flex flex-wrap align-items-center round-6 mb-30">
                                <div class="feature-icon bg-yellow d-flex flex-column align-items-center justify-content-center round-6">
                                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/segmant.svg" alt="Image" class="Icons">
                                </div>
                                <div class="feature-info">
                                    <h3 class="font-medium"><a href="'.$CFG->wwwroot.'/course/index.php?categoryid=2">Segment Your Learning</a></h3>
                                    <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll and start learning.</p>
                                </div>
                                <a href="'.$CFG->wwwroot.'/course/index.php?categoryid=2" class="feature-link d-flex flex-column align-items-center justify-content-center rounded-circle position-absolute bg-title text-white"><i class="ri-arrow-right-line"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="feature-card position-relative bg-white style-one d-flex flex-wrap align-items-center round-6 mb-30">
                                <div class="feature-icon bg-blue d-flex flex-column align-items-center justify-content-center round-6">
                                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/learning-2.svg" alt="Image" class="Icons">
                                </div>
                                <div class="feature-info">
                                    <h3 class="font-medium"><a href="'.$CFG->wwwroot.'/course/index.php?categoryid=2">Always Interactive Learning</a></h3>
                                    <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll and start learning.</p>
                                </div>
                                <a href="'.$CFG->wwwroot.'/course/index.php?categoryid=2" class="feature-link d-flex flex-column align-items-center justify-content-center rounded-circle position-absolute bg-title text-white"><i class="ri-arrow-right-line"></i></a>
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