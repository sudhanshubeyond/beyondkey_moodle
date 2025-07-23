<?php
/*
@edvikRef: @
*/

defined('MOODLE_INTERNAL') || die();
include_once($CFG->dirroot . '/course/lib.php');

class edvikPageHandler {
  public function edvikGetPageTitle() {
    global $PAGE, $COURSE, $DB, $CFG;

    $edvikReturn = $PAGE->heading;

    if(
      $DB->record_exists('course', array('id' => $COURSE->id))
      && $COURSE->format == 'site'
      && $PAGE->cm
      && $PAGE->cm->name !== NULL
    ){
      $edvikReturn = $PAGE->cm->name;
    } elseif($PAGE->pagetype == 'blog-index') {
      $edvikReturn = get_string("blog", "blog");
    }

    return $edvikReturn;
  }
}
