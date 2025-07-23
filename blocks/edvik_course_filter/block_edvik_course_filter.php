<?php
require_once($CFG->dirroot. '/course/renderer.php');
require_once($CFG->dirroot . '/theme/edvik/inc/course_handler/edvik_course_handler.php');
require_once($CFG->dirroot . '/theme/edvik/inc/block_handler/get-content.php');
global $CFG;
class block_edvik_course_filter extends block_base {
    public function init() {
        $this->title = get_string('pluginname', 'block_edvik_course_filter');
    }

    // Declare second
    public function specialization()
    {
        global $CFG, $DB;
        include($CFG->dirroot . '/theme/edvik/inc/block_handler/specialization.php');
        if (empty($this->config)) {
            $this->config = new \stdClass();
            $this->config->title = 'FEATURED COURSES';
            $this->config->subtitle = 'Our Top Courses to learn';
            $this->config->title_shape = EDVIK_IMG .'section-title-shape-1.webp';
            $this->config->button_text = 'Explore All Courses';
            $this->config->alltitle = 'All';
            $this->config->button_link = $CFG->wwwroot . '/course';
            $this->config->price = '1';
            $this->config->course_btn = 'Enroll Now';
        }
    }

    public function get_content() {
        global $CFG, $DB, $COURSE, $USER, $PAGE;

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content         =  new stdClass;
        
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

        $text .= '
        <div class="course-wrap bg_gradient round-10 pt-100 pb-50">
            <div class="container">
                <div class="row align-items-center mb-40">
                    <div class="col-md-8">
                        <div class="section-title style-six">
                            <span class="fs-13 font-medium d-block text_primary">'.format_text($this->config->title, FORMAT_HTML, array('filter' => true)).'</span>
                            <h2 class="d-inline-block font-semibold position-relative mb-0">'.format_text($this->config->subtitle, FORMAT_HTML, array('filter' => true)).'';
                            if($this->config->title_shape):
                                $text .= '<img class="position-absolute bottom-0 end-0" src="'.edvik_block_image_process($this->config->title_shape).'" alt="'.strip_tags($this->config->subtitle).'">';
                            endif; 
                            $text .= '
                        </div>
                    </div>';

                    if($this->config->button_text):
                        $text .= '
                        <div class="col-md-4 text-md-end">
                            <a href="'.$this->config->button_link.'" class="btnn style-one">'.format_text($this->config->button_text, FORMAT_HTML, array('filter' => true)).'<i class="ri-arrow-right-line"></i></a>
                        </div>';
                    endif; 
                    $text .= '
                </div>
                
                <ul class="nav nav-tabs course-tablist style-one list-unstyle d-flex border-0" role="tablist"> ';
                    if($this->config->alltitle):
                        $text .= '
                        <li class="nav-item">
                            <button class="nav-link active" data-toggle="tab" data-target="#all" type="button" role="tab">'.format_text($this->config->alltitle, FORMAT_HTML, array('filter' => true)).'</button>
                        </li>';
                    endif; 
                    
                    foreach ($categories as $key => $category) {
                    $key = 'tab_'.$key;
                        $text .='
                        <li class="nav-item">
                            <button class="nav-link" data-toggle="tab" data-target="#'.$key.'" type="button" role="tab">'.$category.'</button>
                        </li>
                        ';
                    }
                    $text .='
                </ul>

                <div class="tab-content mt-30">';
                    $text .='
                    <div class="tab-pane fade show active" id="all" role="tabpanel">
                        <div class="row justify-content-center">';
                            if(!empty($this->config->courses)){
                                $chelper = new coursecat_helper();
                                $total_courses = count($coursesArr);

                                foreach ($courses as $course) {
                                    if ($DB->record_exists('course', array('id' => $course->id))) {
                                        
                                        $edvikCourseHandler = new edvikCourseHandler();
                                        $edvikCourse = $edvikCourseHandler->edvikGetCourseDetails($course->id);
                                        $edvikCourseDescription = strip_tags($edvikCourseHandler->edvikGetCourseDescription($course->id, 99999999999999));
                                        $edvikCourseDescription = substr($edvikCourseDescription, 0, 98);
                                        // Get Teacher Name
                                        foreach($edvikCourse->teachers as $teacher):
                                            $teacher = $teacher->name;
                                        endforeach;
                                        
                                        $text .= '
                                        <div class="col-xl-4 col-md-6">
                                            <div class="course-card style-one position-relative bg-white round-10 overflow-hidden mb-30">
                                                <div class="course-img">
                                                    '. $edvikCourse->edvikRender->coverImage.'
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
                                                                $text .='
                                                                <span class="fs-20 font-semibold">'.get_config('theme_edvik', 'free_course_price') .'</span>';
                                                            } $text .= '
                                                        </div>
                                                    </div>
                                                    <h3 class="fs-18 font-medium"><a href="'. $edvikCourse->url .'">'.$edvikCourse->fullName.'</a></h3>
                                                    <p>'.$edvikCourseDescription.'</p>
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
                                                    <p>'.$edvikCourseDescription.'</p>
                                                    
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
                    </div> ';

                    foreach ($categories as $key => $category) {
                        $i = 0;
                        if($i == 0){
                            $activate_class="show active";
                        }else{
                            $activate_class="";
                        }
                        $key = 'tab_'.$key;
                        $text .='
                        <div class="tab-pane fade" id="'.$key.'" role="tabpanel">
                            <div class="row justify-content-center">';
                                if(!empty($this->config->courses)){
                                    $chelper = new coursecat_helper();
                                    $total_courses = count($coursesArr);

                                    foreach ($courses as $course) {
                                        if( $category == $course->category_name){
                                            if ($DB->record_exists('course', array('id' => $course->id))) {
                                                
                                                $edvikCourseHandler = new edvikCourseHandler();
                                                $edvikCourse = $edvikCourseHandler->edvikGetCourseDetails($course->id);
                                                $edvikCourseDescription = strip_tags($edvikCourseHandler->edvikGetCourseDescription($course->id, 99999999999999));
                                                $edvikCourseDescription = substr($edvikCourseDescription, 0, 98);
                                                // Get Teacher Name
                                                foreach($edvikCourse->teachers as $teacher):
                                                    $teacher = $teacher->name;
                                                endforeach;
                                                
                                                $text .= '
                                                <div class="col-xl-4 col-md-6">
                                                    <div class="course-card style-one position-relative bg-white round-10 overflow-hidden">
                                                        <div class="course-img">
                                                            '. $edvikCourse->edvikRender->coverImage.'
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
                                                                        $text .='
                                                                        <span class="fs-20 font-semibold">'.get_config('theme_edvik', 'free_course_price') .'</span>';
                                                                    } $text .= '
                                                                </div>
                                                            </div>
                                                            <h3 class="fs-18 font-medium"><a href="'. $edvikCourse->url .'">'.$edvikCourse->fullName.'</a></h3>
                                                            <p>'.$edvikCourseDescription.'</p>
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
                                                            <p>'.$edvikCourseDescription.'</p>
                                                            
                                                            <ul class="course-metainfo list-unstyle d-flex align-items-center">
                                                                <li class="position-relative fs-14 text-tandora d-flex align-items-center"><i class="ri-calendar-line"></i>'. $edvikCourse->edvikRender->updatedDate .' </li>
                                                                
                                                                <li class="position-relative fs-14 text-tandora d-flex align-items-center"><img src="'.EDVIK_IMG .'icons/user-3.svg" alt="Icon">'.$edvikCourse->enrolments.' '.get_string('course_students', 'theme_edvik').'</li>
                                                            </ul>';
                                                            if($this->config->course_btn):
                                                                $text .= '
                                                                <a href="'. $edvikCourse->url .'" class="btnn style-three w-100 d-block">'.$this->config->course_btn.'</a>';
                                                            endif;
                                                            $text .= '
                                                        </div>
                                                    </div>
                                                </div>';
                                            }
                                        }
                                    }                        
                                }
                                $text .= '
                            </div>
                        </div>
                        ';
                        $i++;
                    }
                    $text .='
                </div>
            </div>
        </div>';

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