<?php
global $CFG;
require_once($CFG->dirroot . '/theme/edvik/inc/block_handler/get-content.php');

class block_edvik_banner_1 extends block_base {
    public function init() {
        $this->title = get_string('pluginname', 'block_edvik_banner_1');
    }

    // Declare second
    public function specialization()
    {
        global $CFG, $DB;
        include($CFG->dirroot . '/theme/edvik/inc/block_handler/specialization.php');

        if (empty($this->config)) {
            $this->config = new \stdClass();
            $this->config->title = 'eLearning Solutions';
            $this->config->body = 'Deliver impactful, flexible learning experiences. Empower individual growth with scalable digital training, accessible anytime, anywhere.';
            $this->config->search_placeholder = 'What do you want to learn today?';
            $this->config->btn_icon = 'ri-search-line';
            $this->config->btn = 'Search Now';

            $this->config->support_text = 'Need help? Contact our <a href="'.$CFG->wwwroot .'/course'.'" class="link style-two">Edvik support</a> Tell us about your query.';
            $this->config->banner_btn = 'Explore All Courses';
            $this->config->banner_btn_link = $CFG->wwwroot .'/course';;
            $this->config->banner_btn_icon = 'ri-arrow-right-line';

            $this->config->course_title = 'Featured Courses';
            $this->config->total_student_title = 'Students';
            $this->config->course_btn = 'Enroll Now';

            $this->config->bg_rb = EDVIK_IMG .'hero/trophy.svg';
            $this->config->rb_title = '1.2K+';
            $this->config->rb_content = 'Career Development Certification Couse For Future Career';
            $this->config->rb_link_text = 'Learn More';
            $this->config->rb_link = $CFG->wwwroot .'/course';;
            $this->config->rb_img = EDVIK_IMG .'hero/hero-img-4.webp';
            $this->config->rb_icon = EDVIK_IMG .'hero/badge.svg';
            $this->config->rb_pt = '<span class="text-title">Congratulations!</span> You’ve earned a certification.';
            $this->config->title_shape = EDVIK_IMG .'section-title-shape-1.webp';
            $this->config->user_img1 = EDVIK_IMG .'banner/author-1.webp';
            $this->config->user_img2 = EDVIK_IMG .'banner/author-2.webp';
            $this->config->user_img3 = EDVIK_IMG .'banner/author-3.webp';
            $this->config->shape = EDVIK_IMG .'hero/hero-shape-1.webp';
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

        if($this->config->course_bg_shape){
            $text .= '
                <style>
                    .hero-wrap.style-one .hero-content-right .hero-course-wrap:after {
                        background-image: url('.$this->config->course_bg_shape.');
                    }
                </style>
            ';
        }
        if($this->config->banner_bg_shape){
            echo $this->config->banner_bg_shape;
            $text .= '
                <style>
                .hero-wrap.style-one:after {
                        background-image: url('.$this->config->banner_bg_shape.');
                    }
                </style>
            ';
        }
        $text .= '
        <div class="hero-wrap style-one">
            <div class="container-fluid d-flex flex-wrap pe-xl-1">
                <div class="hero-content-left position-relative index-1 round-10 mb-20">';
                    if($this->config->shape):
                        $text .= '<img class="hero-shape-one position-absolute sm-none" src="'.edvik_block_image_process($this->config->shape).'" alt="">';
                    endif; 
                    $text .= '
                    <div class="row">
                        <div class="col-xxl-8 col-xl-10 col-lg-10 pe-xxl-0">

                            <h1 class="font-semibold position-relative ls-1">
                                '.format_text('eLearning Solutions', FORMAT_HTML, array('filter' => true)).' ';
                                
                                if($this->config->title_shape):
                                    $text .= '<img src="'.edvik_block_image_process($this->config->title_shape).'" alt="">';
                                endif; 
                                $text .= '
                            </h1>

                            <p class="text-tandora">'.format_text('Deliver impactful, flexible learning experiences. Empower individual growth with scalable digital training, accessible anytime, anywhere.', FORMAT_HTML, array('filter' => true)).'</p>';

                            if($this->config->search_placeholder || $this->config->btn):
                                $text .= '
                                <form action="'.$url->out().'" class="search-form position-relative">
                                    <input type="search" id="searchform-search" name="q" placeholder="'.format_text('Search courses, skills, or training modules', FORMAT_HTML, array('filter' => true)).'" class="bg-white border-0 w-100 h-60 round-4 fs-14 ls-1">
                                    <button type="submit" class="bg-title round-4 border-0 fs-15 position-absolute text-white">'.format_text($this->config->btn, FORMAT_HTML, array('filter' => true)).'<i class="'.$this->config->btn_icon.'"></i></button>
                                </form>';
                            endif;  
                            $text .= '

                            <div class="instructor-para d-flex align-items-center">
                                <ul class="d-flex align-items-center list-unstyle">';
                                    $support_image_count = 3;
                                    for($i = 1; $i <= $support_image_count; $i++) {
                                        $user_img = 'user_img' .$i;
                                        if(isset($this->config->$user_img) && !empty($this->config->$user_img)){
                                            $user_img = $this->config->$user_img;
                                            $text .= '<li><img src="'.edvik_block_image_process($user_img).'" alt=""></li>';
                                        }
                                    }
                                    $text .= '
                                </ul>
                                <p class="mb-0 text-tandora">'.format_text('Need help? Contact our <a href="'.$CFG->wwwroot .'/course'.'" class="link style-two">Administrator</a> Tell us about your query.', FORMAT_HTML, array('filter' => true)).'</p>
                            </div>';

                            if($this->config->banner_btn):
                                $text .= '
                                <a href="'.$this->config->banner_btn_link.'" class="btnn style-seven font-medium fs-15">'.format_text($this->config->banner_btn, FORMAT_HTML, array('filter' => true)).'<i class="'.$this->config->banner_btn_icon.'"></i></a>';
                            endif;  
                            $text .= '
                        </div>
                    </div>
                </div>

                <div class="hero-content-right">
                    <div class="hero-course-wrap overflow-hidden round-10 mb-25">
                        <div class="row align-items-center mb-25">
                            <div class="col-md-8">
                                <h4 class="hero-course-title fs-24 ls-1 font-bold mb-0">'.format_text($this->config->course_title, FORMAT_HTML, array('filter' => true)).'</h4>
                            </div>
                            <div class="col-md-4">
                                <div class="hero-pagination d-flex align-items-center justify-content-md-end m-0">
                                    <div class="hero-prev"><i class="ri-arrow-left-line"></i></div>
                                    <div class="hero-next"><i class="ri-arrow-right-line"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="hero_course-slider swiper">
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
                        </div>
                    </div>
                    <div class="row ">

                        <div class="col-md-6">
                            <div class="hero-card style-one bg-orange d-flex flex-column justify-content-center round-10 mb-20">';
                                if($this->config->bg_rb):
                                    $text .= '<img src="'.edvik_block_image_process($this->config->bg_rb).'" alt="">';
                                endif; 
                                $text .= '

                                <h3 class="fs-40 font-semibold text-white lh-1 ls-1">'.format_text($this->config->rb_title, FORMAT_HTML, array('filter' => true)).'</h3>

                                <p class="ls-1">'.format_text($this->config->rb_content, FORMAT_HTML, array('filter' => true)).'</p>';

                                if($this->config->rb_link_text):
                                    $text .= '
                                    <a href="'.$this->config->rb_link.'" class="link style-three font-regular">'.format_text($this->config->rb_link_text, FORMAT_HTML, array('filter' => true)).'<i class="ri-arrow-right-line"></i></a>';
                                endif;
                                $text .= '
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="hero-card style-two round-10 mb-20 position-relative" style="background-image:url('.edvik_block_image_process($this->config->rb_img).');">';
                                if($this->config->rb_img):
                                    $text .= '<img src="'.edvik_block_image_process($this->config->rb_img).'" alt="" class="d-md-none round-10">';
                                endif; 
                                $text .= '

                                <div class="notification-popup d-inline-flex align-items-center bg-white round-6 position-absolute">
                                    ';
                                    if($this->config->rb_icon):
                                        $text .= '<img src="'.edvik_block_image_process($this->config->rb_icon).'" alt="">';
                                    endif; 
                                    $text .= '                                        
                                    <p class="text-icon fs-14 mb-0"><span class="text-title">'.format_text($this->config->rb_pt, FORMAT_HTML, array('filter' => true)).'</p>
                                </div>
                            </div>
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