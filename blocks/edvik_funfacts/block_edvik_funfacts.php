<?php
global $CFG;
require_once($CFG->dirroot . '/theme/edvik/inc/block_handler/get-content.php');
class block_edvik_funfacts extends block_base {
    public function init() {
        $this->title = get_string('pluginname', 'block_edvik_funfacts');
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
                <div class="row justify-content-center">
                    <div class="col-xl-3 col-lg-4 col-sm-6">
                        <div class="counter-card style-one bg-one round-10 position-relative index-1 mb-30">
                            <img src="'.$CFG->wwwroot.'/theme/edvik/pix/counter-shape.webp" alt="Image" class="counter-shape position-absolute top-0 end-0">
                            <p class="fs-13 font-semibold text_primary">COURSES</p>
                            <h2 class="font-bold mb-0"><span class="counter">6500</span>+</h2>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-sm-6">
                        <div class="counter-card style-one bg-two round-10 position-relative index-1 mb-30">
                            <img src="'.$CFG->wwwroot.'/theme/edvik/pix/counter-shape.webp" alt="Image" class="counter-shape position-absolute top-0 end-0">
                            <p class="fs-13 font-semibold text_primary">INSTRUCTORS</p>
                            <h2 class="font-bold mb-0"><span class="counter">1560</span>+</h2>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-sm-6">
                        <div class="counter-card style-one bg-three round-10 position-relative index-1 mb-30">
                            <img src="'.$CFG->wwwroot.'/theme/edvik/pix/counter-shape.webp" alt="Image" class="counter-shape position-absolute top-0 end-0">
                            <p class="fs-13 font-semibold text_primary">STUDENTS ENROLLED</p>
                            <h2 class="font-bold mb-0"><span class="counter">13500</span>+</h2>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-sm-6">
                        <div class="counter-card style-one bg-four round-10 position-relative index-1 mb-30">
                            <img src="'.$CFG->wwwroot.'/theme/edvik/pix/counter-shape.webp" alt="Image" class="counter-shape position-absolute top-0 end-0">
                            <p class="fs-13 font-semibold text_primary">LEARNERS PROGRESS</p>
                            <h2 class="font-bold mb-0"><span class="counter">82.5</span>%</h2>
                        </div>
                    </div>
                </div>';
            $this->config->style = 2;
            $this->config->shape1 = EDVIK_IMG .'counter-shape.webp';

            $this->config->bg_color1 = '#DCD7EA';
            $this->config->item_title1 = 'COURSES';
            $this->config->number1 = '6500+';

            $this->config->bg_color2 = '#D7E5EA';
            $this->config->item_title2 = 'INSTRUCTORS';
            $this->config->number2 = '1560+';

            $this->config->bg_color3 = '#DBEAD7';
            $this->config->item_title3 = 'STUDENTS ENROLLED';
            $this->config->number3 = '1560+';

            $this->config->bg_color4 = '#E9D7EA';
            $this->config->item_title4 = 'LEARNERS PROGRESS';
            $this->config->number4 = '82.5%';

        }
    }

    public function get_content() {
        global $CFG, $DB;
        if ($this->content !== null) {
          return $this->content;
        }
        $this->content         =  new stdClass;
        $item_number = 4;
        if(isset($this->config->item_number)){
            $item_number = $this->config->item_number;
        }

        $text = '';
        $text .= '
            <div class="'.$this->config->class.'">';
                if($this->config->style == 2):
                    $text .= '
                    <div class="container">
                        '.format_text($this->config->content, FORMAT_HTML, array('filter' => true)).' 
                    </div>';
                else:
                    $text .= '
                    <div class="container">
                        <div class="row justify-content-center">';
                        for($i = 1; $i <= $item_number; $i++) {
                            $bg_color          = 'bg_color' . $i;
                            $item_title        = 'item_title' . $i;
                            $number            = 'number' . $i;

                            // BG Color
                            if(isset($this->config->$bg_color)) { $bg_color = $this->config->$bg_color; }else{ $bg_color = ''; }

                            // Title
                            if(isset($this->config->$item_title)) { $item_title = $this->config->$item_title; }else{ $item_title = ''; }

                            // Content
                            if(isset($this->config->$number)) { $number = $this->config->$number; }else{ $number = ''; }

                            $text .= '
                                <div class="col-xl-3 col-lg-4 col-sm-6">
                                    <div class="counter-card style-one bg-one round-10 position-relative index-1 mb-30" style="background-color: '.$bg_color.'">'; 
                                        if($this->config->shape1):
                                            $text .= '
                                            <img src="'.edvik_block_image_process($this->config->shape1).'" alt="'.strip_tags($item_title).'" class="counter-shape position-absolute top-0 end-0">';
                                        endif;
                                        $text .= '
                                        
                                        <p class="fs-13 font-semibold text_primary">'.$item_title.'</p>
                                        <h2 class="font-bold mb-0"><span class="counter">'.$number.'</span></h2>
                                    </div>
                                </div>';
                         } $text .= '
                        </div>
                    </div>';
                endif;
                $text .= '
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