<?php
global $CFG;
require_once($CFG->dirroot . '/theme/edvik/inc/block_handler/get-content.php');
class block_edvik_instructor extends block_base {
    public function init() {
        $this->title = get_string('pluginname', 'block_edvik_instructor');
    }

    // Declare second
    public function specialization()
    {
        global $CFG, $DB;
        include($CFG->dirroot . '/theme/edvik/inc/block_handler/specialization.php');
        if (empty($this->config)) {
            $this->config = new \stdClass();

            $this->config->class = 'container ptb-100';
            $this->config->course_title = 'Courses';
            $this->config->students_title = 'Students';
            $this->config->style = 2;
            $this->config->content = '
                <div class="row justify-content-center">
                    <div class="col-xxl-3 col-lg-4 col-md-6">
                        <div class="instructor-card mb-50">
                            <div class="instructor-img">
                                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/instructor/instructor-1.webp" alt="Image">
                            </div>
                            <div class="instructor-info bg-spring position-relative index-1">
                                <div class="instructor-title d-flex align-items-start justify-content-between">
                                    <div>
                                        <h3 class="fs-20 font-medium"><a href="'.$CFG->wwwroot.'/course">Walter White</a></h3>
                                        <span class="text-firod">Web Developer</span>
                                    </div>
                                    <div class="rating d-flex align-items-center fs-13">
                                        <span class="text-title">4.9</span><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/star.svg" alt="Star Icon">
                                        <span class="text-firod">(120)</span>
                                    </div>
                                </div>
                                <ul class="instructor-metainfo list-unstyle d-flex align-items-center justify-content-between">
                                    <li class="text-paragraph fs-14"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/book-3.svg" alt="Icon">9 Courses</li>
                                    <li class="text-paragraph fs-14 text-end"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/user-2.svg" alt="Icon">52 Students</li>
                                </ul>
                                <ul class="social-profile style-one list-unstyle">
                                    <li><a href="https://www.facebook.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-facebook-fill"></i></a></li>
                                    <li><a href="https://www.twitter.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-twitter-x-fill"></i></a></li>
                                    <li><a href="https://www.instagram.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-instagram-fill"></i></a></li>
                                    <li><a href="https://www.linkedin.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-linkedin-fill"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-lg-4 col-md-6">
                        <div class="instructor-card mb-50">
                            <div class="instructor-img">
                                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/instructor/instructor-2.webp" alt="Image">
                            </div>
                            <div class="instructor-info bg-spring position-relative index-1">
                                <div class="instructor-title d-flex align-items-start justify-content-between">
                                    <div>
                                        <h3 class="fs-20 font-medium"><a href="'.$CFG->wwwroot.'/course">Angela Carter</a></h3>
                                        <span class="text-firod">Design Educator</span>
                                    </div>
                                    <div class="rating d-flex align-items-center fs-13">
                                        <span class="text-title">4.2</span><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/star.svg" alt="Star Icon">
                                        <span class="text-firod">(110)</span>
                                    </div>
                                </div>
                                <ul class="instructor-metainfo list-unstyle d-flex align-items-center justify-content-between">
                                    <li class="text-paragraph fs-14"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/book-3.svg" alt="Icon">11 Courses</li>
                                    <li class="text-paragraph fs-14 text-end"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/user-2.svg" alt="Icon">45 Students</li>
                                </ul>
                                <ul class="social-profile style-one list-unstyle">
                                    <li><a href="https://www.facebook.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-facebook-fill"></i></a></li>
                                    <li><a href="https://www.twitter.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-twitter-x-fill"></i></a></li>
                                    <li><a href="https://www.instagram.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-instagram-fill"></i></a></li>
                                    <li><a href="https://www.linkedin.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-linkedin-fill"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-lg-4 col-md-6">
                        <div class="instructor-card mb-50">
                            <div class="instructor-img">
                                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/instructor/instructor-3.webp" alt="Image">
                            </div>
                            <div class="instructor-info bg-spring position-relative index-1">
                                <div class="instructor-title d-flex align-items-start justify-content-between">
                                    <div>
                                        <h3 class="fs-20 font-medium"><a href="'.$CFG->wwwroot.'/course">Jane Ronan</a></h3>
                                        <span class="text-firod">Math Educator</span>
                                    </div>
                                    <div class="rating d-flex align-items-center fs-13">
                                        <span class="text-title">4.5</span><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/star.svg" alt="Star Icon">
                                        <span class="text-firod">(123)</span>
                                    </div>
                                </div>
                                <ul class="instructor-metainfo list-unstyle d-flex align-items-center justify-content-between">
                                    <li class="text-paragraph fs-14"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/book-3.svg" alt="Icon">15 Courses</li>
                                    <li class="text-paragraph fs-14 text-end"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/user-2.svg" alt="Icon">1.5k Students</li>
                                </ul>
                                <ul class="social-profile style-one list-unstyle">
                                    <li><a href="https://www.facebook.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-facebook-fill"></i></a></li>
                                    <li><a href="https://www.twitter.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-twitter-x-fill"></i></a></li>
                                    <li><a href="https://www.instagram.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-instagram-fill"></i></a></li>
                                    <li><a href="https://www.linkedin.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-linkedin-fill"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-lg-4 col-md-6">
                        <div class="instructor-card mb-50">
                            <div class="instructor-img">
                                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/instructor/instructor-4.webp" alt="Image">
                            </div>
                            <div class="instructor-info bg-spring position-relative index-1">
                                <div class="instructor-title d-flex align-items-start justify-content-between">
                                    <div>
                                        <h3 class="fs-20 font-medium"><a href="'.$CFG->wwwroot.'/course">Victor James</a></h3>
                                        <span class="text-firod">Engineer</span>
                                    </div>
                                    <div class="rating d-flex align-items-center fs-13">
                                        <span class="text-title">4.8</span><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/star.svg" alt="Star Icon">
                                        <span class="text-firod">(132)</span>
                                    </div>
                                </div>
                                <ul class="instructor-metainfo list-unstyle d-flex align-items-center justify-content-between">
                                    <li class="text-paragraph fs-14"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/book-3.svg" alt="Icon">12 Courses</li>
                                    <li class="text-paragraph fs-14 text-end"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/user-2.svg" alt="Icon">205 Students</li>
                                </ul>
                                <ul class="social-profile style-one list-unstyle">
                                    <li><a href="https://www.facebook.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-facebook-fill"></i></a></li>
                                    <li><a href="https://www.twitter.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-twitter-x-fill"></i></a></li>
                                    <li><a href="https://www.instagram.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-instagram-fill"></i></a></li>
                                    <li><a href="https://www.linkedin.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-linkedin-fill"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-lg-4 col-md-6">
                        <div class="instructor-card mb-50">
                            <div class="instructor-img">
                                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/instructor/instructor-5.webp" alt="Image">
                            </div>
                            <div class="instructor-info bg-spring position-relative index-1">
                                <div class="instructor-title d-flex align-items-start justify-content-between">
                                    <div>
                                        <h3 class="fs-20 font-medium"><a href="'.$CFG->wwwroot.'/course">Skyler White</a></h3>
                                        <span class="text-firod">English Educator</span>
                                    </div>
                                    <div class="rating d-flex align-items-center fs-13">
                                        <span class="text-title">4.4</span><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/star.svg" alt="Star Icon">
                                        <span class="text-firod">(140)</span>
                                    </div>
                                </div>
                                <ul class="instructor-metainfo list-unstyle d-flex align-items-center justify-content-between">
                                    <li class="text-paragraph fs-14"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/book-3.svg" alt="Icon">16 Courses</li>
                                    <li class="text-paragraph fs-14 text-end"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/user-2.svg" alt="Icon">1.3k Students</li>
                                </ul>
                                <ul class="social-profile style-one list-unstyle">
                                    <li><a href="https://www.facebook.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-facebook-fill"></i></a></li>
                                    <li><a href="https://www.twitter.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-twitter-x-fill"></i></a></li>
                                    <li><a href="https://www.instagram.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-instagram-fill"></i></a></li>
                                    <li><a href="https://www.linkedin.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-linkedin-fill"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-lg-4 col-md-6">
                        <div class="instructor-card mb-50">
                            <div class="instructor-img">
                                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/instructor/instructor-6.webp" alt="Image">
                            </div>
                            <div class="instructor-info bg-spring position-relative index-1">
                                <div class="instructor-title d-flex align-items-start justify-content-between">
                                    <div>
                                        <h3 class="fs-20 font-medium"><a href="'.$CFG->wwwroot.'/course">Britney Loren</a></h3>
                                        <span class="text-firod">Design Educator</span>
                                    </div>
                                    <div class="rating d-flex align-items-center fs-13">
                                        <span class="text-title">4.9</span><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/star.svg" alt="Star Icon">
                                        <span class="text-firod">(112)</span>
                                    </div>
                                </div>
                                <ul class="instructor-metainfo list-unstyle d-flex align-items-center justify-content-between">
                                    <li class="text-paragraph fs-14"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/book-3.svg" alt="Icon">14 Courses</li>
                                    <li class="text-paragraph fs-14 text-end"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/user-2.svg" alt="Icon">102 Students</li>
                                </ul>
                                <ul class="social-profile style-one list-unstyle">
                                    <li><a href="https://www.facebook.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-facebook-fill"></i></a></li>
                                    <li><a href="https://www.twitter.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-twitter-x-fill"></i></a></li>
                                    <li><a href="https://www.instagram.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-instagram-fill"></i></a></li>
                                    <li><a href="https://www.linkedin.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-linkedin-fill"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-lg-4 col-md-6">
                        <div class="instructor-card mb-50">
                            <div class="instructor-img">
                                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/instructor/instructor-7.webp" alt="Image">
                            </div>
                            <div class="instructor-info bg-spring position-relative index-1">
                                <div class="instructor-title d-flex align-items-start justify-content-between">
                                    <div>
                                        <h3 class="fs-20 font-medium"><a href="'.$CFG->wwwroot.'/course">Jonatho Smith</a></h3>
                                        <span class="text-firod">Math Educator</span>
                                    </div>
                                    <div class="rating d-flex align-items-center fs-13">
                                        <span class="text-title">5.00</span><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/star.svg" alt="Star Icon">
                                        <span class="text-firod">(112)</span>
                                    </div>
                                </div>
                                <ul class="instructor-metainfo list-unstyle d-flex align-items-center justify-content-between">
                                    <li class="text-paragraph fs-14"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/book-3.svg" alt="Icon">20 Courses</li>
                                    <li class="text-paragraph fs-14 text-end"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/user-2.svg" alt="Icon">1.4K Students</li>
                                </ul>
                                <ul class="social-profile style-one list-unstyle">
                                    <li><a href="https://www.facebook.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-facebook-fill"></i></a></li>
                                    <li><a href="https://www.twitter.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-twitter-x-fill"></i></a></li>
                                    <li><a href="https://www.instagram.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-instagram-fill"></i></a></li>
                                    <li><a href="https://www.linkedin.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-linkedin-fill"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-lg-4 col-md-6">
                        <div class="instructor-card mb-50">
                            <div class="instructor-img">
                                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/instructor/instructor-8.webp" alt="Image">
                            </div>
                            <div class="instructor-info bg-spring position-relative index-1">
                                <div class="instructor-title d-flex align-items-start justify-content-between">
                                    <div>
                                        <h3 class="fs-20 font-medium"><a href="'.$CFG->wwwroot.'/course">Jessy Pinkman</a></h3>
                                        <span class="text-firod">Web Designer</span>
                                    </div>
                                    <div class="rating d-flex align-items-center fs-13">
                                        <span class="text-title">4.2</span><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/star.svg" alt="Star Icon">
                                        <span class="text-firod">(123)</span>
                                    </div>
                                </div>
                                <ul class="instructor-metainfo list-unstyle d-flex align-items-center justify-content-between">
                                    <li class="text-paragraph fs-14"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/book-3.svg" alt="Icon">8 Courses</li>
                                    <li class="text-paragraph fs-14 text-end"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/user-2.svg" alt="Icon">122 Students</li>
                                </ul>
                                <ul class="social-profile style-one list-unstyle">
                                    <li><a href="https://www.facebook.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-facebook-fill"></i></a></li>
                                    <li><a href="https://www.twitter.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-twitter-x-fill"></i></a></li>
                                    <li><a href="https://www.instagram.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-instagram-fill"></i></a></li>
                                    <li><a href="https://www.linkedin.com" target="_blank" class="d-flex flex-column align-items-center justify-content-center rounded-circle transition"><i class="ri-linkedin-fill"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            ​';
        }
    }

    public function get_content() {
        global $CFG, $DB, $OUTPUT;
        if ($this->content !== null) {
          return $this->content;
        }
        $this->content         =  new stdClass;

        $text = '';

        // Define role ids for teacher and editingteacher
        $teacher_role_id = 3; // Role ID for 'teacher'
        $editingteacher_role_id = 4; // Role ID for 'editingteacher'

        // Query to get all instructors
        $sql = "SELECT u.id, u.firstname, u.lastname, u.email, u.description
        FROM {user} u
        JOIN {role_assignments} ra ON ra.userid = u.id
        JOIN {context} c ON c.id = ra.contextid
        JOIN {course} co ON co.id = c.instanceid
        WHERE ra.roleid IN (?, ?)";

        $instructors = $DB->get_records_sql($sql, [$teacher_role_id, $editingteacher_role_id]);

        $text .= '
            <div class="'.$this->config->class.'">';
                if($this->config->style == 1): 
                    $text .= '
                    <div class="row justify-content-center">';
                        foreach ($instructors as $instructor) {
                            // Display instructor's profile image
                            $user_picture = new user_picture($instructor);
                            $user_picture->size = 500; // Change size as needed

                            // Fetch total courses created by the instructor
                            $total_courses_sql = "SELECT COUNT(*) AS total_courses 
                                FROM {course} 
                                WHERE id IN (
                                SELECT c.instanceid 
                                FROM {context} c 
                                JOIN {role_assignments} ra ON ra.contextid = c.id
                                WHERE ra.userid = ? AND ra.roleid IN (?, ?)
                                )";
                                $total_courses = $DB->get_field_sql($total_courses_sql, [$instructor->id, $teacher_role_id, $editingteacher_role_id]);

                                // Fetch total enrolled students across all courses
                                $total_students_sql = "SELECT COUNT(DISTINCT ue.userid) AS total_students
                                    FROM {user_enrolments} ue
                                    JOIN {enrol} e ON ue.enrolid = e.id
                                    WHERE e.courseid IN (
                                    SELECT id FROM {course} WHERE id IN (
                                        SELECT c.instanceid 
                                        FROM {context} c 
                                        JOIN {role_assignments} ra ON ra.contextid = c.id
                                        WHERE ra.userid = ? AND ra.roleid IN (?, ?)
                                    )
                                    )";
                                $total_students = $DB->get_field_sql($total_students_sql, [$instructor->id, $teacher_role_id, $editingteacher_role_id]);
                            $text .= '
                            <div class="col-xxl-3 col-lg-4 col-md-6">
                                <div class="instructor-card mb-50">
                                    <div class="instructor-img">
                                        '.$OUTPUT->render($user_picture).'
                                    </div>
                                    <div class="instructor-info bg-spring position-relative index-1">
                                        <div class="instructor-title d-flex align-items-start justify-content-between">
                                            <div>
                                                <h3 class="fs-20 font-medium">'.$instructor->firstname.'  '.$instructor->lastname.'</h3>
                                                <span class="text-firod">'.strip_tags($instructor->description).'</span>
                                            </div>
                                        </div>
                                        <ul class="instructor-metainfo list-unstyle d-flex align-items-center justify-content-between">
                                            <li class="text-paragraph fs-14"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/book-3.svg" alt="Icon">'.$total_courses.' '.$this->config->course_title.'</li>
                                            <li class="text-paragraph fs-14 text-end"><img src="'.$CFG->wwwroot.'/theme/edvik/pix/icons/user-2.svg" alt="Icon">'.$total_students.' '.$this->config->students_title.'</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>';
                        } $text .= '
                    </div>';
                else:
                    $text .= ''.format_text($this->config->content, FORMAT_HTML, array('filter' => true)).' ';
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