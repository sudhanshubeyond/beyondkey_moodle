<?php
/*
* COURSE HANDLER
*/

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot. '/course/renderer.php');
include_once($CFG->dirroot . '/course/lib.php');

class edvikCourseHandler {
    public function edvikGetCourseDetails($courseId) {
        global $CFG, $COURSE, $USER, $DB, $SESSION, $SITE, $PAGE, $OUTPUT;


        $courseId = (int)$courseId;
        if ($DB->record_exists('course', array('id' => $courseId))) {
        // @edvikComm: Initiate
        $edvikCourse = new \stdClass();
        $chelper = new coursecat_helper();
        $courseContext = context_course::instance($courseId);

        $courseRecord = $DB->get_record('course', array('id' => $courseId));

        $courseElement = new core_course_list_element($courseRecord);

        /* @edvikBreak */
        $courseId = $courseRecord->id;
        $courseShortName = $courseRecord->shortname;
        $courseFullName = $courseRecord->fullname;
        $courseSummary = $chelper->get_course_formatted_summary($courseElement, array('noclean' => true, 'para' => false));
        $courseFormat = $courseRecord->format;
        $courseAnnouncements = $courseRecord->newsitems;
        $courseStartDate = $courseRecord->startdate;
        $courseEndDate = $courseRecord->enddate;
        $courseVisible = $courseRecord->visible;
        $courseCreated = $courseRecord->timecreated;
        $courseUpdated = $courseRecord->timemodified;
        $courseRequested = $courseRecord->requested;
        $courseEnrolmentCount = count_enrolled_users($courseContext);
        $course_is_enrolled = is_enrolled($courseContext, $USER->id, '', true);

        /* @edvikBreak */
        $categoryId = $courseRecord->category;

        try {
            $courseCategory = core_course_category::get($categoryId);
            $categoryName = $courseCategory->get_formatted_name();
            $categoryUrl = $CFG->wwwroot . '/course/index.php?categoryid='.$categoryId;
        } catch (Exception $e) {
            $courseCategory = "";
            $categoryName = "";
            $categoryUrl = "";
        }

        /* @edvikBreak */
        $enrolmentLink = $CFG->wwwroot . '/enrol/index.php?id=' . $courseId;
        $courseUrl = new moodle_url('/course/view.php', array('id' => $courseId));
        // @edvikComm: Start Payment
        $enrolInstances = enrol_get_instances($courseId, true);

        $course_price = '';
        $course_currency = '';
        foreach($enrolInstances as $singleenrolInstances){
            if($singleenrolInstances->enrol == 'paypal'){
                $course_price = $singleenrolInstances->cost;
                $course_currency = $singleenrolInstances->currency;
            }elseif($singleenrolInstances->enrol == 'stripe'){
                $course_price = $singleenrolInstances->cost;
                $course_currency = $singleenrolInstances->currency;
            }elseif($singleenrolInstances->enrol == 'payfast'){
                $course_price = $singleenrolInstances->cost;
                $course_currency = $singleenrolInstances->currency;
            }else{
                $course_price =  $singleenrolInstances->cost;
                $course_currency = $singleenrolInstances->currency;
            }
        }
        

        $edvikArrayOfCosts = array();
            $edvikCourseContacts = array();
            if ($courseElement->has_course_contacts()) {
                foreach ($courseElement->get_course_contacts() as $key => $courseContact) {
                $edvikCourseContacts[$key] = new \stdClass();
                $edvikCourseContacts[$key]->userId = $courseContact['user']->id;
                $edvikCourseContacts[$key]->username = $courseContact['user']->username;
                $edvikCourseContacts[$key]->name = $courseContact['user']->firstname . ' ' . $courseContact['user']->lastname;
                $edvikCourseContacts[$key]->role = $courseContact['role']->displayname;
                $edvikCourseContacts[$key]->profileUrl = new moodle_url('/user/view.php', array('id' => $courseContact['user']->id, 'course' => SITEID));
                }
            }


        // @edvikComm: Process first image
        $contentimages = $contentfiles = $CFG->wwwroot . '/theme/edvik/pix/category.webp';
        foreach ($courseElement->get_course_overviewfiles() as $file) {
            $isimage = $file->is_valid_image();
            $url = file_encode_url("{$CFG->wwwroot}/pluginfile.php",
                    '/'. $file->get_contextid(). '/'. $file->get_component(). '/'.
                    $file->get_filearea(). $file->get_filepath(). $file->get_filename(), !$isimage);
            if ($isimage) {
                $contentimages = $url;
            } else {
                $contentfiles = $CFG->wwwroot . '/theme/edvik/pix/category.webp';
            }
        }

        /* Map data */
        $edvikCourse->courseId = $courseId;
        $edvikCourse->enrolments = $courseEnrolmentCount;
        $edvikCourse->categoryId = $categoryId;
        $edvikCourse->categoryName = $categoryName;
        $edvikCourse->categoryUrl = $categoryUrl;
        $edvikCourse->shortName = $courseShortName;
        $edvikCourse->fullName = format_text($courseFullName, FORMAT_HTML, array('filter' => true));
        $edvikCourse->summary = $courseSummary;
        $edvikCourse->imageUrl = $contentimages;
        $edvikCourse->format = $courseFormat;
        $edvikCourse->announcements = $courseAnnouncements;
        $edvikCourse->startDate = userdate($courseStartDate, get_string('strftimedatefullshort', 'langconfig'));
        $edvikCourse->endDate = userdate($courseEndDate, get_string('strftimedatefullshort', 'langconfig'));
        $edvikCourse->visible = $courseVisible;
        $edvikCourse->created = userdate($courseCreated, get_string('strftimedatefullshort', 'langconfig'));
        $edvikCourse->updated = userdate($courseUpdated, get_string('strftimedatefullshort', 'langconfig'));
        $edvikCourse->requested = $courseRequested;
        $edvikCourse->enrolmentLink = $enrolmentLink;
        $edvikCourse->url = $courseUrl;
        $edvikCourse->teachers = $edvikCourseContacts;
        $edvikCourse->course_price = $course_price;
        $edvikCourse->course_currency = $course_currency;
        $edvikCourse->course_is_enrolled = $course_is_enrolled;

        /* Render object */
        $edvikRender = new \stdClass();
        $edvikRender->enrolmentIcon = '';
        $edvikRender->enrolmentIcon1 = '';
        $edvikRender->announcementsIcon     =     '';
        $edvikRender->announcementsIcon1     =     '';
        $edvikRender->updatedDate           =     '';
        $edvikRender->updatedDate         =     userdate($courseUpdated, get_string('strftimedatefullshort', 'langconfig'));
        $edvikRender->title             =     '<h3><a href="'. $edvikCourse->url .'">'. $edvikCourse->fullName .'</a></h3>';
        $edvikRender->coverImage        =     '<img class="img-whp" src="'. $contentimages .'" alt="'.$edvikCourse->fullName.'">';
        $edvikRender->ImageUrl = $contentimages;
        /* @edvikBreak */
        $edvikCourse->edvikRender = $edvikRender;
        return $edvikCourse;
        }
        return null;
    }

    public function edvikGetCourseDescription($courseId, $maxLength){
        global $CFG, $COURSE, $USER, $DB, $SESSION, $SITE, $PAGE, $OUTPUT;
    
        if ($DB->record_exists('course', array('id' => $courseId))) {
        $chelper = new coursecat_helper();
        $courseContext = context_course::instance($courseId);
    
        $courseRecord = $DB->get_record('course', array('id' => $courseId));
        $courseElement = new core_course_list_element($courseRecord);
    
        if ($courseElement->has_summary()) {
            $courseSummary = $chelper->get_course_formatted_summary($courseElement, array('noclean' => false, 'para' => false));
            if($maxLength != null) {
            if (strlen($courseSummary) > $maxLength) {
                $courseSummary = wordwrap($courseSummary, $maxLength);
                $courseSummary = substr($courseSummary, 0, strpos($courseSummary, "\n")) . '...';
            }
            }
            return $courseSummary;
        }
    
        }
        return null;
    }

    public function edvikListCategories(){
        global $DB, $CFG;
        $topcategory = core_course_category::top();
        $topcategorykids = $topcategory->get_children();
        $areanames = array();
        foreach ($topcategorykids as $areaid => $topcategorykids) {
            $areanames[$areaid] = $topcategorykids->get_formatted_name();
            foreach($topcategorykids->get_children() as $k=>$child){
                $areanames[$k] = $child->get_formatted_name();
            }
        }
        return $areanames;
    }

    public function edvikGetCategoryDetails($categoryId){
        global $CFG, $COURSE, $USER, $DB, $SESSION, $SITE, $PAGE, $OUTPUT;
    
        if ($DB->record_exists('course_categories', array('id' => $categoryId))) {
    
        $categoryRecord = $DB->get_record('course_categories', array('id' => $categoryId));
    
        $chelper = new coursecat_helper();
        $categoryObject = core_course_category::get($categoryId);
    
        $edvikCategory = new \stdClass();
    
        $categoryId = $categoryRecord->id;
        $categoryName = format_text($categoryRecord->name, FORMAT_HTML, array('filter' => true));
        $categoryDescription = $chelper->get_category_formatted_description($categoryObject);
    
        $categorySummary = format_string($categoryRecord->description, $striplinks = true,$options = null);
        $isVisible = $categoryRecord->visible;
        $categoryUrl = $CFG->wwwroot . '/course/index.php?categoryid=' . $categoryId;
        $categoryCourses = $categoryObject->get_courses();
        $categoryCoursesCount = count($categoryCourses);
    
        $categoryGetSubcategories = [];
        $categorySubcategories = [];
        if (!$chelper->get_categories_display_option('nodisplay')) {
            $categoryGetSubcategories = $categoryObject->get_children($chelper->get_categories_display_options());
        }
        foreach($categoryGetSubcategories as $k=>$edvikSubcategory) {
            $edvikSubcat = new \stdClass();
            $edvikSubcat->id = $edvikSubcategory->id;
            $edvikSubcat->name = $edvikSubcategory->name;
            $edvikSubcat->description = $edvikSubcategory->description;
            $edvikSubcat->depth = $edvikSubcategory->depth;
            $edvikSubcat->coursecount = $edvikSubcategory->coursecount;
            $categorySubcategories[$edvikSubcategory->id] = $edvikSubcat;
        }
    
        $categorySubcategoriesCount = count($categorySubcategories);
    
        /* Do image */
        $outputimage = '';
        //edvikComm: Fetching the image manually added to the coursecat description via the editor.
        $description = $chelper->get_category_formatted_description($categoryObject);
        $src = "";
        if ($description) {
            $dom = new DOMDocument();
            $dom->loadHTML($description);
            $xpath = new DOMXPath($dom);
            $src = $xpath->evaluate("string(//img/@src)");
        }
        if ($src && $description){
            $outputimage = $src;
        } else {
            foreach($categoryCourses as $child_course) {
            if ($child_course === reset($categoryCourses)) {
                foreach ($child_course->get_course_overviewfiles() as $file) {
                    if ($file->is_valid_image()) {
                        $imagepath = '/' . $file->get_contextid() . '/' . $file->get_component() . '/' . $file->get_filearea() . $file->get_filepath() . $file->get_filename();
                        $imageurl = file_encode_url($CFG->wwwroot . '/pluginfile.php', $imagepath, false);
                        $outputimage  =  $imageurl;
                        // Use the first image found.
                        break;
                    }
                }
            }
            }
        }
    
        /* Map data */
        $edvikCategory->categoryId = $categoryId;
        $edvikCategory->categoryName = $categoryName;
        $edvikCategory->categoryDescription = $categoryDescription;
        $edvikCategory->categorySummary = $categorySummary;
        $edvikCategory->isVisible = $isVisible;
        $edvikCategory->categoryUrl = $categoryUrl;
        $edvikCategory->coverImage = $outputimage;
        $edvikCategory->ImageUrl = $outputimage;
        $edvikCategory->courses = $categoryCourses;
        $edvikCategory->coursesCount = $categoryCoursesCount;
        $edvikCategory->subcategories = $categorySubcategories;
        $edvikCategory->subcategoriesCount = $categorySubcategoriesCount;
        return $edvikCategory;
    
        }
    }

    public function edvikGetExampleCategories($maxNum) {
        global $CFG, $DB;
    
        $edvikCategories = $DB->get_records('course_categories', array(), $sort='', $fields='*', $limitfrom=0, $limitnum=$maxNum);
    
        $edvikReturn = array();
        foreach ($edvikCategories as $edvikCategory) {
        $edvikReturn[] = $this->edvikGetCategoryDetails($edvikCategory->id);
        }
        return $edvikReturn;
    }

    public function edvikGetExampleCategoriesIds($maxNum) {
        global $CFG, $DB;
    
        $edvikCategories = $this->edvikGetExampleCategories($maxNum);
    
        $edvikReturn = array();
        foreach ($edvikCategories as $key => $edvikCategory) {
        $edvikReturn[] = $edvikCategory->categoryId;
        }
        return $edvikReturn;
    }
}
