<?php
global $CFG;
require_once($CFG->dirroot . '/theme/edvik/inc/block_handler/get-content.php');
class block_edvik_about_area_two extends block_base {
    public function init() {
        $this->title = get_string('pluginname', 'block_edvik_about_area_two');
    }

    // Declare second
    public function specialization()
    {
        global $CFG, $DB;
        include($CFG->dirroot . '/theme/edvik/inc/block_handler/specialization.php');
        if (empty($this->config)) {
            $this->config = new \stdClass();

            $this->config->class = 'simple-wrap style-one ptb-100';
            $this->config->content = '
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <div class="simple-img-wrap position-relative index-1">
                                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/about/feature-img-1.webp" alt="Feature Image" class="simple-img d-block mx-auto">
                                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/about/feature-shape.webp" alt="Feature Image" class="simple-shape position-absolute">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="simple-content">
                                <div class="section-title style-one">
                                    <span class="fs-13 font-medium d-block text_primary">OVER 6500+ COURSES AVAILABLE</span>
                                    <h2 class="d-inline-block font-semibold position-relative mb-0">Enhance your Sskills with best Online courses<img src="'.$CFG->wwwroot.'/theme/edvik/pix/section-title-shape-2.webp" alt="Shape" class="position-absolute bottom-0 end-0"></h2>
                                    <p>Explore all of our courses and pick your suitable ones to enroll and start learning with us! Flexible easy to access learning opportunities.</p>
                                </div>
                                <div class="feature-item-wrap d-flex flex-wrap justify-content-between">
                                    <div class="feature-item style-one">
                                        <h4 class="fs-17 font-medium ls-1 round-10"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/check.svg" alt="Check Icon">Flexible Classes</h4>
                                        <div>
                                            <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll and start learning.</p>
                                            <a href="'.$CFG->wwwroot.'/course/index.php?categoryid=2" class="link style-one d-inline-block fs-15 font-medium">Explore More<i class="ri-arrow-right-line"></i></a>
                                        </div>
                                    </div>
                                    <div class="feature-item style-two">
                                        <h4 class="fs-17 font-medium ls-1 round-10"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/check.svg" alt="Check Icon">Learn From Anywhere</h4>
                                        <div>
                                            <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll and start learning.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
            $this->config->style = 2;
            $this->config->top_title = 'OVER 6500+ COURSES AVAILABLE';
            $this->config->title = 'Enhance your Skills with best Online courses';
            $this->config->title_shape = EDVIK_IMG .'section-title-shape-2.webp';
            $this->config->body = 'Explore all of our courses and pick your suitable ones to enroll and start learning with us! Flexible easy to access learning opportunities.';
            $this->config->list_title1 = '';
            $this->config->list_content1 = '';
            $this->config->list_title2 = '';
            $this->config->list_content2 = '';
            $this->config->list_icon = EDVIK_IMG .'icons/check.svg';
            $this->config->btn = 'Explore More';
            $this->config->btn_link = $CFG->wwwroot .'/course';
            $this->config->img = EDVIK_IMG .'about/feature-img-1.webp';
            $this->config->shape1 = EDVIK_IMG .'about/feature-shape.webp';
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
            <div class="'.$this->config->class.'">';
                if($this->config->style == 2):
                    $text .= '
                        <div class="container">
                            '.format_text($this->config->content, FORMAT_HTML, array('filter' => true)).' 
                        </div> ';
                else:
                    $text .= '
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-lg-6">
                                <div class="simple-img-wrap position-relative index-1">';
                                    if($this->config->img):
                                        $text .= '
                                        <img src="'.edvik_block_image_process($this->config->img).'" class="simple-img d-block mx-auto" alt="'.strip_tags($this->config->title).'">';
                                    endif;

                                    if($this->config->shape1):
                                        $text .= '
                                        <img src="'.edvik_block_image_process($this->config->shape1).'" class="simple-shape position-absolute" alt="'.strip_tags($this->config->title).'">';
                                    endif;
                                    $text .= '
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="simple-content">
                                    <div class="section-title style-one">
                                        <span class="fs-13 font-medium d-block text_primary">'.format_text($this->config->top_title, FORMAT_HTML, array('filter' => true)).'</span>
                                        <h2 class="d-inline-block font-semibold position-relative mb-0">
                                            '.format_text($this->config->title, FORMAT_HTML, array('filter' => true)).' ';
                                            if($this->config->title_shape):
                                                $text .= '
                                                <img src="'.edvik_block_image_process($this->config->title_shape).'" class="position-absolute bottom-0 end-0" alt="'.strip_tags($this->config->title).'">';
                                            endif;
                                            $text .= '
                                        </h2>
                                        <p>'.format_text($this->config->body, FORMAT_HTML, array('filter' => true)).'</p>
                                    </div>
                                    <div class="feature-item-wrap d-flex flex-wrap justify-content-between">
                                        
                                    <div class="feature-item style-one">
                                            <h4 class="fs-17 font-medium ls-1 round-10">';
                                                if($this->config->list_icon):
                                                    $text .= '
                                                    <img src="'.edvik_block_image_process($this->config->list_icon).'"  alt="'.strip_tags($this->config->title).'">';
                                                endif;
                                                $text .= '
                                                '.format_text($this->config->list_title1, FORMAT_HTML, array('filter' => true)).' </h4>
                                            <div>
                                                <p class="mb-0">'.format_text($this->config->list_content1, FORMAT_HTML, array('filter' => true)).' </p>';
                                                if($this->config->btn):
                                                    $text .= '
                                                    <a href="'.$this->config->btn_link.'" class="link style-one d-inline-block fs-15 font-medium">'.format_text($this->config->btn, FORMAT_HTML, array('filter' => true)).'<i class="ri-arrow-right-line"></i></a>';
                                                endif; 
                                                $text .= '
                                            </div>
                                        </div>

                                        <div class="feature-item style-two">
                                            <h4 class="fs-17 font-medium ls-1 round-10">';
                                                if($this->config->list_icon):
                                                    $text .= '
                                                    <img src="'.edvik_block_image_process($this->config->list_icon).'"  alt="'.strip_tags($this->config->list_title2).'">';
                                                endif;
                                                $text .= '
                                                '.format_text($this->config->list_title2, FORMAT_HTML, array('filter' => true)).'
                                                </h4>
                                            <div>
                                                <p class="mb-0">'.format_text($this->config->list_content2, FORMAT_HTML, array('filter' => true)).'</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    ';
                endif;
                $text .= '
            </div>';
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