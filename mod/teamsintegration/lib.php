<?php
function teamsintegration_add_instance($data, $mform = null) {
    global $DB, $USER;

    require_once(__DIR__.'/classes/api/graphclient.php');
    $graph = new \mod_teamsintegration\api\graphclient();

    $meeting = $graph->create_meeting($data->name, $data->starttime, $data->endtime, $USER->email);
    $record = (object)[
        'courseid' => $data->course,
        'name' => $data->name,
        'meetingid' => $meeting['id'] ?? null,
        'joinurl' => $meeting['onlineMeeting']['joinUrl'] ?? null,
        'starttime' => $data->starttime,
        'endtime' => $data->endtime,
        // description fields from form
        'intro' => $data->intro ?? '',
        'introformat' => $data->introformat ?? FORMAT_HTML,
        'timecreated' => time(),
    ];
    $data->id = $DB->insert_record('teamsintegration', $record);

    return $data->id;

}

function teamsintegration_update_instance($data, $mform = null) {
    global $DB;
    $data->id = $data->instance;

    // attempt to update the corresponding Teams meeting via Graph
    try {
        require_once(__DIR__ . '/classes/api/graphclient.php');
        $graph = new \mod_teamsintegration\api\graphclient();
        if ($meeting = $DB->get_record('teamsintegration', ['id' => $data->id], 'meetingid')) {
            if (!empty($meeting->meetingid)) {
                $graph->update_meeting($meeting->meetingid, $data->name, $data->starttime, $data->endtime);
            }
        }
    } catch (\Exception $e) {
        debugging('Failed to update Graph meeting: ' . $e->getMessage(), DEBUG_DEVELOPER);
    }

    return $DB->update_record('teamsintegration', $data);
}

function teamsintegration_delete_instance($id) {
    global $DB;

    if (!$meeting = $DB->get_record('teamsintegration', ['id' => $id])) {
        return false;
    }

    // before removing database records attempt to cancel/delete the Graph meeting
    try {
        require_once(__DIR__ . '/classes/api/graphclient.php');
        $graph = new \mod_teamsintegration\api\graphclient();
        if (!empty($meeting->meetingid)) {
            $attcount = $DB->count_records('teamsintegration_attendance', ['meetingid' => $id]);
            if ($attcount > 0) {
                // attendees exist – cancel so participants are notified
                $graph->cancel_meeting($meeting->meetingid, get_string('meetingcancelled', 'mod_teamsintegration'));

		// Delete attendance records
		$DB->delete_records('teamsintegration_attendance', ['meetingid' => $id]);
            } else {
                // no attendees – simply delete the event
                $graph->delete_meeting($meeting->meetingid);
            }
        } else {
            debugging('No Graph meetingid stored', DEBUG_DEVELOPER);
        }
    } catch (\Exception $e) {
        debugging('Failed to clean up Graph meeting on delete: ' . $e->getMessage(), DEBUG_DEVELOPER);
    }

    // Delete attendance records first
    // $DB->delete_records('teamsintegration_attendance', ['meetingid' => $id]);

    // Delete the meeting record
    $DB->delete_records('teamsintegration', ['id' => $id]);

    return true;
}

// helpers for the new attendee/report features
function teamsintegration_get_meeting($id) {
    global $DB;
    // include intro fields; result used by view.php if needed
    return $DB->get_record('teamsintegration', ['id' => $id], '*', MUST_EXIST);
}

function teamsintegration_get_attendeesBK($meetingid) {
    global $DB;
    return $DB->get_records('teamsintegration_attendance', ['meetingid' => $meetingid]);
}

function teamsintegration_get_attendees($meetingid) {
    global $DB;
    $sql = "SELECT ta.*, u.firstname, u.lastname
            FROM {teamsintegration_attendance} ta
 	    LEFT JOIN {user} u ON u.id = ta.userid
            WHERE ta.meetingid = :meetingid";
    return $DB->get_records_sql($sql, ['meetingid' => $meetingid]);
}

function teamsintegration_add_attendee($meetingid, $userid = null, $email = null) {
    global $DB;

    // attempt to add attendee via Graph API if configured
    try {
        require_once(__DIR__.'/classes/api/graphclient.php');
        $graph = new \mod_teamsintegration\api\graphclient();
        if ($meeting = $DB->get_record('teamsintegration', ['id' => $meetingid], 'meetingid')) {
            // meetingid field stores Graph event id
            $graph->add_attendee($meeting->meetingid, $email);
        }
    } catch (\Exception $e) {
        // log but don't break the flow
        debugging('Failed to add attendee via Graph: '.$e->getMessage(), DEBUG_DEVELOPER);
    }

    $record = (object)[
        'meetingid' => $meetingid,
        'userid'    => $userid,
        'email'     => $email,
        'addedat'   => time(),
    ];
    return $DB->insert_record('teamsintegration_attendance', $record);
}

function teamsintegration_remove_attendee($attid) {
    global $DB;

    // Get the attendance record to find email and meeting
    if (!$att = $DB->get_record('teamsintegration_attendance', ['id' => $attid])) {
        return false;
    }

    // attempt to remove attendee via Graph API if configured
    try {
        require_once(__DIR__.'/classes/api/graphclient.php');
        $graph = new \mod_teamsintegration\api\graphclient();
        if ($meeting = $DB->get_record('teamsintegration', ['id' => $att->meetingid], 'meetingid')) {
            // meetingid field stores Graph event id
            if (!empty($att->email)) {
                $graph->remove_attendee($meeting->meetingid, $att->email);
            }
        }
    } catch (\Exception $e) {
        // log but don't break the flow
        debugging('Failed to remove attendee via Graph: '.$e->getMessage(), DEBUG_DEVELOPER);
    }

    return $DB->delete_records('teamsintegration_attendance', ['id' => $attid]);
}

function teamsintegration_attendance_report($meetingid) {
    // simple report, returns count and duration sums
    global $DB;
    $sql = "SELECT COUNT(*) as count, SUM(duration) as totalduration
            FROM {teamsintegration_attendance}
            WHERE meetingid = ?";
    return $DB->get_record_sql($sql, [$meetingid]);
}

function teamsintegration_get_attendance_details($meetingid) {
    global $DB;
    $sql = "SELECT a.id, a.jointime, a.leavetime, a.duration, u.firstname, u.lastname, a.email
            FROM {teamsintegration_attendance} a
            LEFT JOIN {user} u ON u.id = a.userid
            WHERE a.meetingid = ?";
    return $DB->get_records_sql($sql, [$meetingid]);
}
