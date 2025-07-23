<?php
global $CFG;
require_once($CFG->dirroot . '/theme/edvik/inc/block_handler/get-content.php');
class block_edvik_about_area extends block_base {
    public function init() {
        $this->title = get_string('pluginname', 'block_edvik_about_area');
    }

    // Declare second
    public function specialization()
    {
        global $CFG, $DB;
        include($CFG->dirroot . '/theme/edvik/inc/block_handler/specialization.php');
        if (empty($this->config)) {
            $this->config = new \stdClass();

            $this->config->class = 'about-wrap style-one ptb-100';
            $this->config->top_title = 'ABOUT EDVIK​';
            $this->config->title = 'Affordable online courses​';
            $this->config->mid_title = 'learning';
            $this->config->last_title = 'opportunities​';
            $this->config->body = 'Break into a new field like information technology o data science. No prior experience necessary to get started.';
            $this->config->lists = 'Expert Instructors, Remote Learning, Lifetime Free Access, Self Development';
            $this->config->list_icon = EDVIK_IMG .'icons/check.svg';
            $this->config->btn = 'Learn More';
            $this->config->btn_link = $CFG->wwwroot .'/course';
            $this->config->btn2 = 'Explore All Courses';
            $this->config->btn2_link = $CFG->wwwroot .'/course';
            $this->config->card1_img = EDVIK_IMG .'about/pie-chart.webp';
            $this->config->card1_title = '75%';
            $this->config->card1_content = 'Improve In Learning';
            $this->config->card2_img = EDVIK_IMG .'icons/user-5.svg';
            $this->config->card2_title = '32K+';
            $this->config->card2_content = 'Students Enrolled';
            $this->config->img = EDVIK_IMG .'about/about-img-1.webp';
            $this->config->section_title_shape = EDVIK_IMG .'section-title-shape-2.webp';
            $this->config->shape1 = EDVIK_IMG .'about/shape-1.webp';
            $this->config->shape2 = EDVIK_IMG .'about/triangle.webp';
        }
    }

    public function get_content() {
        global $CFG, $DB;
        if ($this->content !== null) {
          return $this->content;
        }
        $this->content         =  new stdClass;

        $lists = $this->config->lists ;
        $items_array = array_map('trim', explode(",", $lists));

        $text = '';
        $text .= '
            <div class="'.$this->config->class.'">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <div class="about-img-wrap position-relative">

                                '; if($this->config->shape1):
                                    $text .= '
                                    <img src="'.edvik_block_image_process($this->config->shape1).'" class="shape-one position-absolute" alt="'.strip_tags($this->config->title).'">';
                                endif;

                                if($this->config->shape2):
                                    $text .= '
                                    <img src="'.edvik_block_image_process($this->config->shape2).'" class="shape-two position-absolute" alt="'.strip_tags($this->config->title).'">';
                                endif;
                                
                                if($this->config->card1_img || $this->config->card1_title || $this->config->card1_content):
                                    $text .= '
                                    <div class="learning-box d-inline-flex align-items-center bg-yellow round-10 position-absolute">'; 
                                        if($this->config->card1_img):
                                            $text .= '
                                            <img src="'.edvik_block_image_process($this->config->card1_img).'" alt="'.strip_tags($this->config->title).'">';
                                        endif;
                                        $text .= '
                                        <div>
                                            <h6 class="fs-30 font-bold lh-1 ls-1">'.$this->config->card1_title.'</h6>
                                            <span class="text-paragraph ls-1">'.$this->config->card1_content.'</span>
                                        </div>
                                    </div>';
                                endif;

                                if($this->config->img):
                                    $text .= '
                                    <img src="'.edvik_block_image_process($this->config->img).'" class="about-img d-block mx-auto" alt="'.strip_tags($this->config->title).'">';
                                endif;

                                if($this->config->card2_img || $this->config->card2_title || $this->config->card2_content):
                                    $text .= '
                                    <div class="student-box bg-orange round-20 d-inline-flex flex-column align-items-center justify-content-center position-absolute text-center">
                                        <div class="edvik-icon d-flex flex-column align-items-center justify-content-center rounded-circle">'; 
                                            if($this->config->card2_img):
                                                $text .= '
                                                <img src="'.edvik_block_image_process($this->config->card2_img).'"  alt="'.strip_tags($this->config->title).'">';
                                            endif;
                                            $text .= '
                                        </div>
                                        <h6 class="fs-30 font-semibold ls-1 text-white mb-0">'.$this->config->card2_title.'</h6>
                                        <span class="ls-1">'.$this->config->card2_content.'</span>
                                    </div>';
                                endif;
                                $text .= '
                            </div>
                        </div>

                        <div class="col-lg-6 col-xxl-5 offset-xxl-1">
                            <div class="about-content position-relative">
                                <div class="section-title style-one overflow-hidden">
                                    <span class="fs-13 font-medium d-block text_primary">'.format_text($this->config->top_title, FORMAT_HTML, array('filter' => true)).'</span>
                                    <h2 class="d-inline-block font-semibold position-relative">
                                        '.format_text($this->config->title, FORMAT_HTML, array('filter' => true)).'   
                                            <span class="ls-0 position-relative">
                                                '.format_text($this->config->mid_title, FORMAT_HTML, array('filter' => true)).' 
                                                '; if($this->config->section_title_shape): $text .= '
                                                    <img src="'.edvik_block_image_process($this->config->section_title_shape).'" class="position-absolute bottom-0" alt="'.strip_tags($this->config->title).'">';
                                                endif;
                                                $text .= '
                                            </span> 
                                        '.format_text($this->config->last_title, FORMAT_HTML, array('filter' => true)).'
                                    </h2>
                                    <p>'.format_text($this->config->body, FORMAT_HTML, array('filter' => true)).'</p>
                                </div>

                                <ul class="feature-list list-unstyle">';
                                    foreach ($items_array as $item):
                                        $text .= '
                                            <li class="position-relative text-title">'; 
                                                if($this->config->list_icon):
                                                    $text .= '
                                                    <img src="'.edvik_block_image_process($this->config->list_icon).'" alt="'.strip_tags($this->config->title).'">';
                                                endif;
                                                $text .= '
                                                '.format_text($item, FORMAT_HTML, array('filter' => true)).'
                                            </li>';
                                    endforeach;
                                    $text .= '
                                </ul> 
                                
                                <div class="about-btn">';
                                    if($this->config->btn):
                                        $text .= '
                                        <a href="'.$this->config->btn_link.'" class="btnn style-three">'.format_text($this->config->btn, FORMAT_HTML, array('filter' => true)).'</a>';
                                    endif;  

                                    if($this->config->btn2):
                                        $text .= '
                                        <a href="'.$this->config->btn2_link.'" class="btnn style-eight font-medium">'.format_text($this->config->btn2, FORMAT_HTML, array('filter' => true)).'</a>';
                                    endif;  
                                    $text .= '
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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