<?php
namespace mod_teamsintegration;

defined('MOODLE_INTERNAL') || die();

class observer {
    /**
     * Ensure Graph meeting is cleaned up when the module is deleted.
     *
     * The normal delete_instance() implementation *should* run already, but some
     * environments (or manual DB deletions) might bypass it.  This observer
     * acts as a safety net and also gives us visibility when the module is
     * removed.
     *
     * @param \core\event\course_module_deleted $event
     */
    public static function course_module_deleted(\core\event\course_module_deleted $event) {
        if (empty($event->other['modulename']) || $event->other['modulename'] !== 'teamsintegration') {
            return;
        }
        $instanceid = $event->other['instanceid'];
        debugging('observer: course_module_deleted for teamsintegration instance ' . $instanceid, DEBUG_DEVELOPER);
        // call our delete logic; it is safe if the database entries have already
        // been removed, as delete_instance checks for record existence.
        teamsintegration_delete_instance($instanceid);
    }
}
