<?php
namespace mod_teamsintegration\event;
defined('MOODLE_INTERNAL') || die();
class attendance_updated extends \core\event\base {
    protected function init() {
        $this->data['crud'] = 'u';
        $this->data['edulevel'] = self::LEVEL_PARTICIPATING;
    }
    public static function get_name() {
        return get_string('attendanceupdated', 'mod_teamsintegration');
    }
    public function get_description() {}
}
