<?php
global $CFG;
require_once($CFG->dirroot. '/theme/edvik/inc/course_handler/edvik_course_handler.php');

class block_edvik_course_desc extends block_base {
    public function init() {
        $this->title = get_string('pluginname', 'block_edvik_course_desc');
    }

    // Declare second
    public function specialization()
    {
        global $CFG, $DB;
        include($CFG->dirroot . '/theme/edvik/inc/block_handler/specialization.php');
        
        if (empty($this->config)) {
            $this->config = new \stdClass();
            $this->config->author_label = "";
            $this->config->student_label = "";
            $this->config->date_label = "";
            $this->config->body = "Break into a new field like information technology or data science. No prior experience necessar. Best time for enroll in this course";
        }
    }

    public function get_content() {
        global $CFG, $DB, $COURSE, $USER, $PAGE;

        $this->content         =  new stdClass;     
        
        if(!empty($this->config->author_label)){$this->content->author_label = format_text($this->config->author_label, FORMAT_HTML, array('filter' => true));}else{$this->content->author_label = '';}
        if(!empty($this->config->student_label)){$this->content->student_label = format_text($this->config->student_label, FORMAT_HTML, array('filter' => true));}else{$this->content->student_label = '';}
        if(!empty($this->config->date_label)){$this->content->date_label = format_text($this->config->date_label, FORMAT_HTML, array('filter' => true));}else{$this->content->date_label = '';}

        if(!empty($this->config->body)){$this->content->body = format_text($this->config->body, FORMAT_HTML, array('filter' => true));}else{$this->content->body = '';}

        $edvikCourseHandler = new edvikCourseHandler();
        $edvikCourse = $edvikCourseHandler->edvikGetCourseDetails($COURSE->id);
        $edvikCourseDescription = $edvikCourseHandler->edvikGetCourseDescription($COURSE->id, 99999999999999999999999);

        $edvikCourseShortDescription = strip_tags($edvikCourseHandler->edvikGetCourseDescription($COURSE->id, 99999999999999));
         $edvikCourseShortDescription = substr($edvikCourseDescription, 0, 150);

        // Get Teacher Name
        foreach($edvikCourse->teachers as $teacher):
            $teacher = $teacher->name;
        endforeach;

        $text = '';

        $text .= '
        <div class="course-details-desc">
            <h3>'.format_text($edvikCourse->fullName, FORMAT_HTML, array('filter' => true)).'</h3>
            <p>'.format_text($this->content->body, FORMAT_HTML, array('filter' => true)).'</p>
            <ul class="meta-list">
                <li>
                    <i class="ri-user-3-line"></i>
                    <span>'.format_text($this->content->author_label, FORMAT_HTML, array('filter' => true)).' '.format_text($teacher, FORMAT_HTML, array('filter' => true)).'</span>
                </li>
                <li>
                    <i class="ri-user-2-line"></i>
                    '.format_text($this->content->student_label, FORMAT_HTML, array('filter' => true)).' '.$edvikCourse->enrolments.'
                </li>
                <li>
                    <i class="ri-calendar-line"></i> '.format_text($this->content->date_label, FORMAT_HTML, array('filter' => true)).' '. $edvikCourse->edvikRender->updatedDate .'
                </li>            
            </ul>';
            if($edvikCourse->course_price) {
                $text .= '
                <div class="price">'.format_text(get_config('theme_edvik', 'site_currency') .''.$edvikCourse->course_price, FORMAT_HTML, array('filter' => true)).'</div>';
            }else{
                $text .= '
                <div class="price">'.format_text(get_config('theme_edvik', 'free_course_price'), FORMAT_HTML, array('filter' => true) ).'</div>';
            } $text .= '
        </div>
        
        <div class="courses-overview pt-75">
            '.$edvikCourseDescription.'
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
        return true;
    }

    /**
     * Sets the applicable formats for the block.
     *
     * @return string[] Array of pages and permissions.
     */
    function applicable_formats() {
        return array(
            'all' => false,
            'my' => false,
            'admin' => false,
            'course-view' => true,
            'course' => false,
        );
    }

}