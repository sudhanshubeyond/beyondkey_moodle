<?php
namespace mod_teamsintegration\task;

defined('MOODLE_INTERNAL') || die();

class sync_attendance_task extends \core\task\scheduled_task {
    public function get_name() {
        return get_string('task_syncattendance', 'mod_teamsintegration');
    }

    public function execute() {
        global $DB;
        $graph = new \mod_teamsintegration\api\graphclient();
        $meetings = $DB->get_records('teamsintegration');

        foreach ($meetings as $m) {
            if (!$m->meetingid) {
                continue;
            }

            $reports = $graph->get_attendance($m->meetingid, $m->joinurl ?? null);
            foreach ($reports['value'] ?? [] as $entry) {
                if (empty($entry['emailAddress'])) {
                    continue;
                }

                $jointime = !empty($entry['joinDateTime']) ? strtotime($entry['joinDateTime']) : null;
                $leavetime = !empty($entry['leaveDateTime']) ? strtotime($entry['leaveDateTime']) : null;
                $duration = (int)($entry['durationInSeconds'] ?? 0);

                $attendancerecord = $DB->get_record('teamsintegration_attendance', [
                    'meetingid' => $m->id,
                    'email' => $entry['emailAddress']
                ]);

                if ($attendancerecord) {
                    $data = (object)[
                        'id' => $attendancerecord->id,
                        'meetingid' => $m->id,
                        'userid' => $attendancerecord->userid ?? null,
                        'email' => $entry['emailAddress'],
                        'jointime' => $jointime,
                        'leavetime' => $leavetime,
                        'duration' => $duration,
                    ];
                    $DB->update_record('teamsintegration_attendance', $data);
                }
            }
        }
    }
}
