<?php
require_once($CFG->dirroot. '/course/renderer.php');
require_once($CFG->dirroot . '/theme/edvik/inc/course_handler/edvik_course_handler.php');
require_once($CFG->dirroot . '/theme/edvik/inc/block_handler/get-content.php');
global $CFG;
class block_edvik_course_filter_2 extends block_base {
    public function init() {
        $this->title = get_string('pluginname', 'block_edvik_course_filter_2');
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
            $this->config->shape1 = EDVIK_IMG .'shape-11.webp';
            $this->config->shape2 = EDVIK_IMG .'shape-2.webp';
            $this->config->alltitle = 'All';
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
        <div class="course-wrap bg-aqua position-relative index-1 round-10 pt-100 pb-50">';
            if($this->config->shape1):
                $text .= '<img class="section-shape-one position-absolute moveHorizontal" src="'.edvik_block_image_process($this->config->shape1).'" alt="'.strip_tags($this->config->subtitle).'">';
            endif; 
            if($this->config->shape2):
                $text .= '<img class="section-shape-two position-absolute bounce" src="'.edvik_block_image_process($this->config->shape2).'" alt="'.strip_tags($this->config->subtitle).'">';
            endif; 
            $text .= '

            <div class="container">
                <div class="section-title text-center mb-40">
                    <span class="fs-13 font-medium d-block text_primary">'.format_text($this->config->title, FORMAT_HTML, array('filter' => true)).'</span>
                    <h2 class="d-inline-block font-semibold position-relative mb-0">'.format_text($this->config->subtitle, FORMAT_HTML, array('filter' => true)).'</h2>
                </div>
                
                <ul class="nav nav-tabs course-tablist style-three list-unstyle d-flex justify-content-center border-0" role="tablist">';
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

                <div class="tab-content product-tab-content mt-40">';
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
                                            <div class="course-card style-two position-relative bg-white round-10 transition mb-50">
                                                <div class="course-img rounded-circle position-relative mx-auto">
                                                    <img src="'.EDVIK_IMG .'/shape-6.webp" alt="Shape" class="shape-one position-absolute transition">
                                                    '. $edvikCourse->edvikRender->coverImage.'
                                                    <img src="'.EDVIK_IMG .'/shape-7.webp" alt="Shape" class="shape-two position-absolute transition">
                                                </div>
                                                <div class="course-info">
                                                    <h3 class="fs-22 font-medium ls-1"><a href="'. $edvikCourse->url .'">'.$edvikCourse->fullName.'</a></h3>
                                                    <div class="course-instructor fs-14 ls-1 text-paragraph">
                                                        by <a href="'. $edvikCourse->url .'" class="text-black">'.$teacher.'</a>
                                                    </div>

                                                    <div class="course-price-wrap d-flex align-items-center justify-content-between">
                                                        <div class="course-price">';
                                                            if($edvikCourse->course_price) {
                                                                $text .= '
                                                                <span class="fs-24 font-semibold">'.get_config('theme_edvik', 'site_currency') .''.$edvikCourse->course_price.'</span>';
                                                            }else{
                                                                $text .='
                                                                <span class="ffs-24 font-semibold">'.get_config('theme_edvik', 'free_course_price') .'</span>';
                                                            } $text .= '
                                                        </div>';
                                                        if($this->config->course_btn):
                                                            $text .= '
                                                            <a href="'. $edvikCourse->url .'" class="link style-one bg-transparent p-0 border-0">'.$this->config->course_btn.' <i class="ri-arrow-right-line"></i></a>';
                                                        endif;
                                                        $text .= '
                                                    </div>
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
                                                    <div class="course-card style-two position-relative bg-white round-10 transition mb-50">
                                                        <div class="course-img rounded-circle position-relative mx-auto">
                                                            <img src="'.EDVIK_IMG .'/shape-6.webp" alt="Shape" class="shape-one position-absolute transition">
                                                            '. $edvikCourse->edvikRender->coverImage.'
                                                            <img src="'.EDVIK_IMG .'/shape-7.webp" alt="Shape" class="shape-two position-absolute transition">
                                                        </div>
                                                        <div class="course-info">
                                                            <h3 class="fs-22 font-medium ls-1"><a href="'. $edvikCourse->url .'">'.$edvikCourse->fullName.'</a></h3>
                                                            <div class="course-instructor fs-14 ls-1 text-paragraph">
                                                                by <a href="'. $edvikCourse->url .'" class="text-black">'.$teacher.'</a>
                                                            </div>

                                                            <div class="course-price-wrap d-flex align-items-center justify-content-between">
                                                                <div class="course-price">';
                                                                    if($edvikCourse->course_price) {
                                                                        $text .= '
                                                                        <span class="fs-24 font-semibold">'.get_config('theme_edvik', 'site_currency') .''.$edvikCourse->course_price.'</span>';
                                                                    }else{
                                                                        $text .='
                                                                        <span class="ffs-24 font-semibold">'.get_config('theme_edvik', 'free_course_price') .'</span>';
                                                                    } $text .= '
                                                                </div>';
                                                                if($this->config->course_btn):
                                                                    $text .= '
                                                                    <a href="'. $edvikCourse->url .'" class="link style-one bg-transparent p-0 border-0">'.format_text($this->config->course_btn, FORMAT_HTML, array('filter' => true)).' <i class="ri-arrow-right-line"></i></a>';
                                                                endif;
                                                                $text .= '
                                                            </div>
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