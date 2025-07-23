<?php
global $CFG;
require_once($CFG->dirroot . '/theme/edvik/inc/block_handler/get-content.php');
class block_edvik_faq extends block_base {
    public function init() {
        $this->title = get_string('pluginname', 'block_edvik_faq');
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
                <div class="col-lg-6 ps-xxl-3 pe-xxl-3">
                    <div class="faq-img-wrap position-relative index-1 ps-xxl-2">
                        <img src="'.$CFG->wwwroot.'/theme/edvik/pix/about/faq-shape.webp" alt="Image" class="faq-shape position-absolute">
                        <img src="'.$CFG->wwwroot.'/theme/edvik/pix/about/faq-img-1.webp" alt="FAQ" class="bounce">
                    </div>
                </div>
                <div class="col-lg-6 ps-xxl-2">
                    <div class="faq-content style-one">
                        <div class="section-title">
                            <span class="fs-13 d-block font-medium text_primary">FAQ</span>
                            <h2 class="d-inline-block font-semibold position-relative mb-20">Affordable online courses and learning opportunities​</h2>
                            <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll and start learning with us! Flexible easy to access learning opportunities.</p>
                        </div>
                        <div class="accordion" id="accordionExample_two">
                            <div class="accordion-item collapsed bg-white" 
                            data-toggle="collapse" data-target="#collapseFour"
                            aria-expanded="false" aria-controls="collapseFour" role="button">
                                <div class="accordion-header" id="headingFour">
                                    <div class="accordion-button text-title font-medium">
                                        <span>
                                            <i class="ri-arrow-down-s-line plus"></i>
                                            <i class="ri-arrow-up-s-line minus"></i>
                                        </span>
                                        How to admit Edvik?
                                    </div>
                                </div>
                                <div id="collapseFour" class="accordion-collapse collapse"
                                    aria-labelledby="headingFour" data-parent="#accordionExample_two">
                                    <div class="accordion-body">
                                        <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll and start learning with us! Flexible easy to access learning opportunities.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item collapsed bg-white" 
                            data-toggle="collapse" data-target="#collapseFive"
                            aria-expanded="false" aria-controls="collapseFive" role="button">
                                <div class="accordion-header" id="headingFive">
                                    <div class="accordion-button text-title font-medium">
                                        <span>
                                            <i class="ri-arrow-down-s-line plus"></i>
                                            <i class="ri-arrow-up-s-line minus"></i>
                                        </span>
                                        How to pay online in Edvik?
                                    </div>
                                </div>
                                <div id="collapseFive" class="accordion-collapse collapse "
                                    aria-labelledby="headingFive" data-parent="#accordionExample_two">
                                    <div class="accordion-body">
                                        <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll and start learning with us! Flexible easy to access learning opportunities.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item collapsed bg-white" 
                            data-toggle="collapse" data-target="#collapseSix"
                            aria-expanded="false" aria-controls="collapseSix" role="button">
                                <div class="accordion-header" id="headingSix">
                                    <div class="accordion-button text-title font-medium">
                                        <span>
                                            <i class="ri-arrow-down-s-line plus"></i>
                                            <i class="ri-arrow-up-s-line minus"></i>
                                        </span>
                                        How to teach in Edvik?
                                    </div>
                                </div>
                                <div id="collapseSix" class="accordion-collapse collapse"
                                    aria-labelledby="headingSix" data-parent="#accordionExample_two">
                                    <div class="accordion-body">
                                        <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll and start learning with us! Flexible easy to access learning opportunities.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a href="#" class="btnn style-two fw-semibold round-10">Ask Your Questions</a>
                    </div>
                </div>
            </div>';
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