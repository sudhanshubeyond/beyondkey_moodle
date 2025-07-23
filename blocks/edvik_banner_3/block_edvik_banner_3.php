<?php
global $CFG;
require_once($CFG->dirroot . '/theme/edvik/inc/block_handler/get-content.php');

class block_edvik_banner_3 extends block_base {
    public function init() {
        $this->title = get_string('pluginname', 'block_edvik_banner_3');
    }

    // Declare second
    public function specialization()
    {
        global $CFG, $DB;
        include($CFG->dirroot . '/theme/edvik/inc/block_handler/specialization.php');

        if (empty($this->config)) {
            $this->config = new \stdClass();
            $this->config->title = 'Find Your Best Courses To Develop Your Skills';
            $this->config->body = 'Flexible easy to access learning opportunities can bring a significant change in how individuals prefer to learn! The Edvik can offer you to enjoy the beauty of eLearning!';
            $this->config->search_placeholder = 'What do you want to learn today?';
            $this->config->btn_icon = 'ri-search-line';
            $this->config->btn = 'Search Now';
            $this->config->total_student_title = 'Students';
            $this->config->course_btn = 'Enroll Now';
            $this->config->card_content1 = '
                <div class="hero-course-amt-wrap position-relative index-1 d-inline-block float-end">
                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/hero/arrow.webp" alt="Arrow Shape" class="shape-one position-absolute m-0">
                    <div class="hero-course-amt bg-white ms-auto position-relative index-1 d-inline-flex align-items-center round-6">
                        <img src="'.$CFG->wwwroot.'/theme/edvik/pix/hero/compus.svg" alt="Icon">
                        <div>
                            <h6 class="fs-14 font-semibold lh-1">23K+ Courses</h6>
                            <span class="fs-14 text-icon lh-1">Available for learners</span>
                        </div>
                    </div>
                </div>';
            $this->config->card_content2 = '
             <div class="instructor-box bg-white round-6 position-absolute">
                <ul class="d-flex align-items-center list-unstyle">
                    <li><img src="'.$CFG->wwwroot.'/theme/edvik/pix/blog/author-11.webp" alt="Author"></li>
                    <li><img src="'.$CFG->wwwroot.'/theme/edvik/pix/blog/author-10.webp" alt="Author"></li>
                    <li><img src="'.$CFG->wwwroot.'/theme/edvik/pix/blog/author-1.webp" alt="Author"></li>
                    <li><a href="'.$CFG->wwwroot.'/course"><i class="ri-add-line"></i></a></li>
                </ul>
                <h6 class="fs-14 font-semibold ls-1">230+ Instructors</h6>
                <span class="fs-14 text-icon ls-1">Joined our site to teach</span>
            </div>';

            $this->config->img = EDVIK_IMG .'hero/hero-img-3.svg';
            $this->config->shape = EDVIK_IMG .'hero/bulb.svg';
            $this->config->shape2 = EDVIK_IMG .'hero/circle-1.svg';
            $this->config->shape3 = EDVIK_IMG .'hero/shape-1.webp';
        }
    }

    public function get_content() {
        global $CFG, $DB;

        if ($this->content !== null) {
          return $this->content;
        }

        $this->content  =  new stdClass;

        if (\core_search\manager::is_global_search_enabled() === false) {
            $this->content->search_placeholder = 'Global searching is not enabled.';
        }else{
            if(isset($this->config->search_placeholder) && !empty($this->config->search_placeholder)){
                $this->content->search_placeholder = $this->config->search_placeholder;
            }else{
                $this->content->search_placeholder = '';
            }
        }
        $url = new moodle_url('/search/index.php');

        // Course Area
        $categories = array();
        if(!empty($this->config->courses)){
            $coursesArr = $this->config->courses;
            $courses = new stdClass();
            foreach ($coursesArr as $key => $course) {
                $courseObj = new stdClass();
                $courseObj->id = $course;
                $courseRecord = $DB->get_record('course', array('id' => $courseObj->id), 'category');
                $courseCategory = $DB->get_record('course_categories',array('id' => $courseRecord->category));
                $courseCategory = core_course_category::get($courseCategory->id);
                $courseObj->category = $courseCategory->id;
                $courseObj->category_name = $courseCategory->get_formatted_name();
                $courses->$course = $courseObj;
            }
            $categories = array();
            foreach ($courses as $key => $course) {
                $categories[$course->category] = $course->category_name;
            }
            $categories = array_unique($categories);
        }

        $text = '';

        if($this->config->banner_bg){
            $text .= '
                <style>
                    .hero-wrap.style-three:after {
                        background-image: url('.$this->config->banner_bg.');
                    }
                </style>
            ';
        }
        $text .= '
        <div class="hero-wrap style-three position-relative index-1">';
            if($this->config->shape2):
                $text .= '<img class="hero-shape-one position-absolute start-0" src="'.edvik_block_image_process($this->config->shape2).'" alt="'.strip_tags($this->config->title).'">';
            endif; 
            if($this->config->shape3):
                $text .= '<img class="hero-shape-two position-absolute" src="'.edvik_block_image_process($this->config->shape3).'" alt="'.strip_tags($this->config->title).'">';
            endif; 
            $text .= '
            <div class="container">
                <div class="row">
                    <div class="col-xl-4 col-lg-7 col-md-6 order-xl-1 order-lg-1 order-md-1 pe-xxl-0">
                        <div class="hero-content">
                            <h1 class="font-semibold ls-1">'.format_text($this->config->title, FORMAT_HTML, array('filter' => true)).'</h1>
                            <p class="text-tandora">'.format_text($this->config->body, FORMAT_HTML, array('filter' => true)).'</p> ';
                            if($this->config->search_placeholder || $this->config->btn):
                                $text .= '
                                <form action="'.$url->out().'" class="seach-form position-relative">
                                    <input type="search" name="q" placeholder="'.format_text($this->config->search_placeholder, FORMAT_HTML, array('filter' => true)).'" class="bg-white border-0 w-100 h-60 round-4 fs-14 ls-1">
                                    <button type="submit" class="bg-title round-4 border-0 fs-15 position-absolute text-white">'.format_text($this->config->btn, FORMAT_HTML, array('filter' => true)).'<i class="'.$this->config->btn_icon.'"></i></button>
                                </form>';
                            endif;  
                            $text .= '
                            '.format_text($this->config->card_content1, FORMAT_HTML, array('filter' => true)).'
                        </div>
                    </div>
                    <div class="col-xl-5 col-lg-12 order-xl-2 order-lg-3 order-md-3 ps-xxl-0">
                        <div class="hero-center-content position-relative">
                            ';
                            if($this->config->shape):
                                $text .= '<img class="bulb-shape position-absolute" src="'.edvik_block_image_process($this->config->shape).'" alt="'.strip_tags($this->config->title).'">';
                            endif; 
                            $text .= '

                            '.format_text($this->config->card_content2, FORMAT_HTML, array('filter' => true)).'

                            ';
                            if($this->config->img):
                                $text .= '<img class="hero-img" src="'.edvik_block_image_process($this->config->img).'" alt="'.strip_tags($this->config->title).'">';
                            endif; 
                            $text .= '
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-5 col-md-6 order-xl-3 order-lg-2 order-md-2">
                        <div class="hero-course-slider swiper">
                            <div class="swiper-wrapper">';
                                if(!empty($this->config->courses)){
                                    $chelper = new coursecat_helper();
                                    $total_courses = count($coursesArr);
                                    foreach ($courses as $course) {
                                        if ($DB->record_exists('course', array('id' => $course->id))) {
                                            $edvikCourseHandler = new edvikCourseHandler();
                                            $edvikCourse = $edvikCourseHandler->edvikGetCourseDetails($course->id);
                                            // Get Teacher Name
                                            foreach($edvikCourse->teachers as $teacher):
                                                $teacher = $teacher->name;
                                            endforeach;
                                            $text .= '
                                            <div class="swiper-slide">
                                                <div class="course-card style-one position-relative bg-white round-10 overflow-hidden">
                                                    <div class="course-img">
                                                        '.$edvikCourse->edvikRender->coverImage.'
                                                    </div>
                                                    <div class="course-info">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <div class="course-teacher">
                                                                <div class="instructor-img rounded-circle d-inline-block">
                                                                    <div class="edvik-icon">
                                                                        <i class="bx bx-user"></i>
                                                                    </div>
                                                                </div>
                                                                <a href="'. $edvikCourse->url .'" class="ms-1 text-orange">'.$teacher.'</a>
                                                            </div>
                                                            
                                                            <div class="course-price">';
                                                                if($edvikCourse->course_price) {
                                                                    $text .= '
                                                                    <span class="fs-20 font-semibold">'.get_config('theme_edvik', 'site_currency') .''.$edvikCourse->course_price.'</span>';
                                                                }else{
                                                                    $text .= '
                                                                    <span class="fs-20 font-semibold">'.get_config('theme_edvik', 'free_course_price') .'</span>';
                                                                } $text .= '
                                                            </div>
                                                        </div>
                                                        <h3 class="fs-18 font-medium"><a href="'. $edvikCourse->url .'">'.$edvikCourse->fullName.'</a></h3>
                                                    </div>
                                                    <ul class="course-metainfo list-unstyle d-flex align-items-center">
                                                        <li class="position-relative fs-14 text-paragraph d-flex align-items-center"><i class="ri-calendar-line"></i>'. $edvikCourse->edvikRender->updatedDate .' </li>
                                                        <li class="position-relative fs-14 text-paragraph d-flex align-items-center"><img src="'.EDVIK_IMG .'icons/user-3.svg" alt="Icon">'.$edvikCourse->enrolments.' '.get_string('course_students', 'theme_edvik').'</li>
                                                    </ul>
                                                    <div class="course-hover-content round-10 bg-white position-absolute top-0 w-100 h-100 transition">
                                                        <div class="course-teacher">
                                                            <div class="instructor-img rounded-circle d-inline-block">
                                                                <div class="edvik-icon">
                                                                    <i class="bx bx-user"></i>
                                                                </div>
                                                            </div>
                                                            <a href="'. $edvikCourse->url .'" class="ms-1 text-orange">'.$teacher.'</a>
                                                        </div>

                                                        <h3 class="fs-18 font-semibold"><a href="'. $edvikCourse->url .'">'.$edvikCourse->fullName.'</a></h3>
                                                        <ul class="course-metainfo list-unstyle d-flex align-items-center">
                                                            <li class="position-relative fs-14 text-tandora d-flex align-items-center"><i class="ri-calendar-line"></i>'. $edvikCourse->edvikRender->updatedDate .' </li>
                                                            
                                                            <li class="position-relative fs-14 text-tandora d-flex align-items-center"><img src="'.EDVIK_IMG .'icons/user-3.svg" alt="Icon">'.$edvikCourse->enrolments.' '.get_string('course_students', 'theme_edvik').'</li>
                                                        </ul>';
                                                        if($this->config->course_btn):
                                                            $text .= '
                                                            <a href="'. $edvikCourse->url .'" class="btnn style-three w-100 d-block">'.format_text($this->config->course_btn, FORMAT_HTML, array('filter' => true)).'</a>';
                                                        endif;
                                                        $text .= '
                                                    </div>
                                                </div>
                                            </div>';
                                        }
                                    }
                                }
                                $text .= '
                            </div>
                            <div class="hero-pagination d-flex justify-content-center align-items-center"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        ';
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