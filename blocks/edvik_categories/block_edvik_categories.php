<?php
global $CFG;
require_once($CFG->dirroot . '/theme/edvik/inc/course_handler/edvik_course_handler.php');
require_once($CFG->dirroot . '/theme/edvik/inc/block_handler/get-content.php');
class block_edvik_categories extends block_base {
    public function init() {
        $this->title = get_string('pluginname', 'block_edvik_categories');
    }

    // Declare second
    public function specialization()
    {
        global $CFG, $DB;
        include($CFG->dirroot . '/theme/edvik/inc/block_handler/specialization.php');
        if (empty($this->config)) {
            $edvikCourseHandler = new edvikCourseHandler();
            $edvikCategories = $edvikCourseHandler->edvikGetExampleCategoriesIds(8);
            $this->config = new \stdClass();
            $this->config->class            = 'category-wrap style-one bg-aqua position-relative index-1 ptb-100 round-10';
            $this->config->top_title    = 'TOP CATEGORIES';
            $this->config->title        = 'Our Top Categories to learn';
            $this->config->img1 = EDVIK_IMG.'categories/cat-1.svg';
            $this->config->img2 = EDVIK_IMG.'categories/cat-2.svg';
            $this->config->img3 = EDVIK_IMG.'categories/cat-3.svg';
            $this->config->img4 = EDVIK_IMG.'categories/cat-4.svg';
            $this->config->img5 = EDVIK_IMG.'categories/cat-5.svg';
            $this->config->img6 = EDVIK_IMG.'categories/cat-6.svg';
            $this->config->shape_img1  = EDVIK_IMG .'shape-2.webp';
            $this->config->shape_img2  = EDVIK_IMG .'shape-3.webp';
            $this->config->section_title_shape  = EDVIK_IMG .'section-title-shape-1.webp';
            $this->config->body  = 'Explore all of our categories and pick your suitable ones to enroll and start learning with us! <a href="#" class="ms-1 link style-three font-regular">View All Categories<i class="ri-arrow-right-line"></i></a>';
        }
    }

    public function get_content() {
        global $CFG, $USER, $DB, $OUTPUT;

        if ($this->content !== null) {
            return $this->content;
        }

        if (isset($this->config->items)) {
            $data = $this->config;
            $data->items = is_numeric($data->items) ? (int)$data->items : 5;
        } else {
            $data = new stdClass();
            $data->items = '0';
        }

        $this->content         =  new stdClass;

        if(!empty($this->config->title)){$this->content->title = $this->config->title;} else {$this->content->title = '';}

        $text = '';
        $text .= '
        <div class="'.$this->config->class.'">';
            if($this->config->shape_img1):
                $text .= '
                <img src="'.edvik_block_image_process($this->config->shape_img1).'" class="section-shape-one position-absolute" alt="'.strip_tags($this->config->title).'">';
            endif;

            if($this->config->shape_img2):
                $text .= '
                <img src="'.edvik_block_image_process($this->config->shape_img2).'" class="section-shape-two position-absolute" alt="'.strip_tags($this->config->title).'">';
            endif;
            $text .= '

            <div class="container">
                <div class="section-title style-one text-center mb-40">
                    <span class="fs-13 font-medium d-block text_primary">'.format_text($this->config->top_title, FORMAT_HTML, array('filter' => true)).'</span>
                    <h2 class="d-inline-block font-semibold position-relative mb-0">
                        '.format_text($this->config->title, FORMAT_HTML, array('filter' => true)).'';

                        if($this->config->section_title_shape):
                            $text .= '
                            <img src="'.edvik_block_image_process($this->config->section_title_shape).'" class="position-absolute bottom-0" alt="'.strip_tags($this->config->title).'">';
                        endif;
                        $text .= '
                    </h2>
                </div>
                <div class="row justify-content-center mb-10">';
                    $topcategory = core_course_category::top();
                    
                    if ($data->items > 0) {
                        for ($i = 1; $i <= $data->items; $i++) {
                            $img            = 'img' . $i;
                            $categoryID     = 'category' . $i;
                            $category       = $DB->get_record('course_categories',array('id' => $data->$categoryID));

                            // Image
                            if(isset($this->config->$img)) { $img = $this->config->$img; }else{ $img = ''; }

                            if ($DB->record_exists('course_categories', array('id' => $data->$categoryID))) {
                                $chelper = new coursecat_helper();
                                $categoryID = $category->id;
                                $category = core_course_category::get($categoryID);
                                $categoryname = $category->get_formatted_name();
                                $text .= '
                                <div class="col-lg-2 col-md-6">
                                    <div class="cat-card style-one text-center bg-white d-flex flex-column round-10 mb-30 transition">';
                                        if($img):
                                            $text .= '
                                                <img src="'.edvik_block_image_process($img).'" class="d-block mx-auto" alt="'.$categoryname.'">';
                                        endif;
                                        $text .= '
                                        <h3 class="fs-18 fw-semibold"><a href="'.$CFG->wwwroot .'/course/index.php?categoryid='.$categoryID.'">'.$categoryname.'</a></h3>
                                    </div>
                                </div>';
                            }
                        }
                    }
                    $text .= '
                </div>

                <div class="row">
                    <div class="col-xl-6 offset-xl-3 px-xxl-5 px-xxl-5">
                        <p class="text-center text-paragraph mb-0 px-xxl-4">'.format_text($this->config->body, FORMAT_HTML, array('filter' => true)).'</p>
                    </div>
                </div>

            </div>
        </div>';

        $this->content->footer = '';
        $this->content->text   = $text;

        return $this->content;
    }

    function instance_allow_config() {
        return true;
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
        return false;
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