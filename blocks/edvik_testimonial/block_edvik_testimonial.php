<?php
global $CFG;
require_once($CFG->dirroot . '/theme/edvik/inc/block_handler/get-content.php');
class block_edvik_testimonial extends block_base {
    public function init() {
        $this->title = get_string('pluginname', 'block_edvik_testimonial');
    }

    // Declare second
    public function specialization()
    {
        global $CFG, $DB;
        include($CFG->dirroot . '/theme/edvik/inc/block_handler/specialization.php');
        if (empty($this->config)) {
            $this->config = new \stdClass();

            $this->config->class = 'container ptb-100';
            $this->config->content = '
                <div class="row align-items-center mb-30">
                    <div class="col-md-8">
                        <div class="section-title style-eight">
                            <span class="fs-13 font-medium d-block text_primary">TESTIMONIAL</span>
                            <h2 class="d-inline-block font-semibold position-relative mb-0">What Learners say about Edvik<img src="'.$CFG->wwwroot.'/theme/edvik/pix/section-title-shape-2.webp" alt="Shape" class="position-absolute bottom-0 end-0"></h2>
                        </div>
                    </div>
                    <div class="col-md-4 text-md-end mt-sm-20">
                        <a href="#" class="btnn style-one">Explore All Testimonials<i class="ri-arrow-right-line"></i></a>
                    </div>
                </div>
                <div class="testimonial-slider-one swiper">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="testimonial-card style-one position-relative">
                                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/testimonial-shape.webp" alt="Shape" class="testimonial-shape position-absolute transition">
                                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/testimonial-shape-2.webp" alt="Shape" class="testimonial-shape-two position-absolute bottom-0 start-0 transition">
                                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/quote-1.svg" alt="Quote Icon">
                                <h3 class="fs-22 font-medium ls-1 text-orange">“Greate Support & Quality Trainer!”</h3>
                                <p class="font-medium text-tandora ls-1">Instructors from around the world teach millions of students on Edvik. They provide the top best tools and learning materials.</p>
                                <div class="client-info-wrap d-flex flex-wrap align-items-center">
                                    <div class="client-img rounded-circle">
                                        <img src="'.$CFG->wwwroot.'/theme/edvik/pix/instructor/instructor-thumb-6.webp" alt="Client" class="rounded-circle">
                                    </div>
                                    <div class="client-info">
                                        <h5 class="fs-18 font-regular">Jehny Watson</h5>
                                        <span class="fs-15 text-paraTwo">Student</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="testimonial-card style-one position-relative">
                                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/testimonial-shape-3.webp" alt="Shape" class="testimonial-shape position-absolute transition">
                                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/testimonial-shape-2.webp" alt="Shape" class="testimonial-shape-two position-absolute bottom-0 start-0 transition">
                                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/quote-1.svg" alt="Quote Icon">
                                <h3 class="fs-22 font-medium ls-1 text-orange">“Best Quality Trainer I’ve Ever Seen!”</h3>
                                <p class="font-medium text-tandora ls-1">Instructors from around the world teach millions of students on Edvik. They provide the top best tools and learning materials.</p>
                                <div class="client-info-wrap d-flex flex-wrap align-items-center">
                                    <div class="client-img rounded-circle">
                                        <img src="'.$CFG->wwwroot.'/theme/edvik/pix/instructor/instructor-thumb-7.webp" alt="Client" class="rounded-circle">
                                    </div>
                                    <div class="client-info">
                                        <h5 class="fs-18 font-regular">Angela Carter</h5>
                                        <span class="fs-15 text-paraTwo">Student</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="testimonial-card style-one position-relative">
                                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/testimonial-shape.webp" alt="Shape" class="testimonial-shape position-absolute transition">
                                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/testimonial-shape-2.webp" alt="Shape" class="testimonial-shape-two position-absolute bottom-0 start-0 transition">
                                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/quote-1.svg" alt="Quote Icon">
                                <h3 class="fs-22 font-medium ls-1 text-orange">“Greate Support & Quality Trainer!”</h3>
                                <p class="font-medium text-tandora ls-1">Instructors from around the world teach millions of students on Edvik. They provide the top best tools and learning materials.</p>
                                <div class="client-info-wrap d-flex flex-wrap align-items-center">
                                    <div class="client-img rounded-circle">
                                        <img src="'.$CFG->wwwroot.'/theme/edvik/pix/instructor/instructor-thumb-8.webp" alt="Client" class="rounded-circle">
                                    </div>
                                    <div class="client-info">
                                        <h5 class="fs-18 font-regular">Tony Ronan</h5>
                                        <span class="fs-15 text-paraTwo">Web Designer</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="testimonial-card style-one position-relative">
                                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/testimonial-shape-3.webp" alt="Shape" class="testimonial-shape position-absolute transition">
                                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/testimonial-shape-2.webp" alt="Shape" class="testimonial-shape-two position-absolute bottom-0 start-0 transition">
                                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/quote-1.svg" alt="Quote Icon">
                                <h3 class="fs-22 font-medium ls-1 text-orange">“Best Quality Trainer I’ve Ever Seen!”</h3>
                                <p class="font-medium text-tandora ls-1">Instructors from around the world teach millions of students on Edvik. They provide the top best tools and learning materials.</p>
                                <div class="client-info-wrap d-flex flex-wrap align-items-center">
                                    <div class="client-img rounded-circle">
                                        <img src="'.$CFG->wwwroot.'/theme/edvik/pix/instructor/instructor-thumb-5.webp" alt="Client" class="rounded-circle">
                                    </div>
                                    <div class="client-info">
                                        <h5 class="fs-18 font-regular">Jay Cutler</h5>
                                        <span class="fs-15 text-paraTwo">Fitness Trainer</span>
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