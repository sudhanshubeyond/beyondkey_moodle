<?php
require_once('../../config.php');
require_once(__DIR__.'/lib.php');
require_once($CFG->libdir.'/formslib.php');

$id = required_param('id', PARAM_INT);
$tab = optional_param('tab', '0', PARAM_ALPHA);
$cm = get_coursemodule_from_id('teamsintegration', $id, 0, false, MUST_EXIST);
$context = context_module::instance($cm->id);
require_login($cm->course, true, $cm);

$meeting = teamsintegration_get_meeting($cm->instance);

// Get enrolled users for the course
$context_course = context_course::instance($cm->course);
$enrolled_users = get_enrolled_users($context_course, '', 0, 'u.id, u.firstname, u.lastname, u.email');

// Filter out users already added as attendees
$existing_attendees = teamsintegration_get_attendees($meeting->id);
$existing_userids = array_column($existing_attendees, 'userid');
$available_users = array_filter($enrolled_users, function($user) use ($existing_userids) {
    return !in_array($user->id, $existing_userids);
});

// Pagination
$perpage = 10;
$totalusers = count($available_users);
$page = optional_param('page', 0, PARAM_INT);
$start = $page * $perpage;
$current_users = array_slice($available_users, $start, $perpage, true);

$pagingbar = new paging_bar($totalusers, $page, $perpage, $PAGE->url, 'page');
$pagingbarhtml = $OUTPUT->render($pagingbar);

$PAGE->set_url('/mod/teamsintegration/view.php', ['id' => $cm->id]);
$PAGE->set_title(format_string($meeting->name));
$PAGE->set_heading(format_string($meeting->name));
$PAGE->set_context($context);

echo $OUTPUT->header();

//echo $OUTPUT->heading(format_string($meeting->name));

// show the activity description if available (handled by course renderer normally)
// echo format_module_intro('teamsintegration', $meeting, $cm->id);

// only show the join link to teachers/admins or users who are attendees
$showlink = false;
if (has_capability('mod/teamsintegration:addattendee', $context)) {
    // teachers and managers
    $showlink = true;
} else {
    // check attendance table for current user
    global $USER, $DB;
    if ($DB->record_exists('teamsintegration_attendance', ['meetingid' => $meeting->id, 'userid' => $USER->id])) {
        $showlink = true;
    }
}

if ($showlink && $meeting->joinurl) {
    echo html_writer::tag('p', get_string('startdate', 'mod_teamsintegration').': '.userdate($meeting->starttime));
    echo html_writer::tag('p', get_string('enddate', 'mod_teamsintegration').': '.userdate($meeting->endtime));
    // render join link as Moodle button
    $link = html_writer::link($meeting->joinurl, get_string('joinmeeting', 'mod_teamsintegration'), ['class'=>'btn btn-primary']);
    echo html_writer::tag('p', $link);
} else {
    echo html_writer::tag('p', get_string('joinurlnotavailable', 'mod_teamsintegration'));
}

if (has_capability('mod/teamsintegration:addattendee', $context)) {
    // Tab links
    $tabs = [];
    $tabs[] = new tabobject('attendees', new moodle_url('/mod/teamsintegration/view.php', ['id' => $cm->id, 'tab' => 'attendees']), get_string('attendeelist', 'mod_teamsintegration'));
    if (has_capability('mod/teamsintegration:addattendee', $context)) {
        $tabs[] = new tabobject('addattendee', new moodle_url('/mod/teamsintegration/view.php', ['id' => $cm->id, 'tab' => 'addattendee']), get_string('addattendee', 'mod_teamsintegration'));
    }
    if (has_capability('mod/teamsintegration:report', $context)) {
        $tabs[] = new tabobject('report', new moodle_url('/mod/teamsintegration/view.php', ['id' => $cm->id, 'tab' => 'report']), get_string('report', 'mod_teamsintegration'));
    }

    if (empty($tab) || $tab === '0') {
        $tab = 'attendees';
    }

    echo html_writer::start_div('tabtable');
    print_tabs([$tabs], $tab);
    echo html_writer::end_div();

    // Tab content
    if ($tab === 'attendees' && has_capability('mod/teamsintegration:addattendee', $context)) {
        $attendees = teamsintegration_get_attendees($meeting->id);
        if (!empty($attendees)) {
            $table = new html_table();
            $table->head = [get_string('fullname', 'moodle'), get_string('email', 'mod_teamsintegration'), get_string('addedat', 'mod_teamsintegration'), ''];
            foreach ($attendees as $att) {
                $row = [];
		$fullname = (!empty($att->firstname) || !empty($att->lastname)) ? fullname($att) : get_string('notapplicable', 'moodle');
                $row[] = s($fullname);
                $row[] = s($att->email ?: get_string('notapplicable', 'moodle'));
                $row[] = userdate($att->addedat);
                if (has_capability('mod/teamsintegration:removeattendee', $context)) {
                    $url = new moodle_url('/mod/teamsintegration/attendees.php', ['id' => $cm->id, 'action' => 'delete', 'attid' => $att->id]);
                    $row[] = html_writer::link($url, get_string('removeattendee', 'mod_teamsintegration'));
                } else {
                    $row[] = '';
                }
                $table->data[] = $row;
            }
            echo html_writer::table($table);
        } else {
            echo html_writer::tag('p', get_string('noattendees', 'mod_teamsintegration'));
        }
    } elseif ($tab === 'addattendee' && has_capability('mod/teamsintegration:addattendee', $context)) {
        // manual form submission handling
        if ($formdata = data_submitted()) {
            // ensure the POST is really coming from this form
            require_sesskey();
            $useridlist = optional_param_array('attendees', [], PARAM_INT);
            $addedcount = 0;
            foreach ($useridlist as $userid) {
                if ($userid) {
                    $user = $available_users[$userid] ?? null;
                    if ($user && !empty($user->email)) {
                        $result = teamsintegration_add_attendee($meeting->id, $userid, $user->email);
                        if ($result) {
                            $addedcount++;
                            unset($available_users[$userid]);
                        }
                    }
                }
            }
            if ($addedcount > 0) {
		\core\notification::success(get_string('addedselectedattendees', 'mod_teamsintegration', $addedcount));
            } else {
                \core\notification::warning(get_string('noattendeesselected', 'mod_teamsintegration'));
            }
        }

        // build form HTML; include tab so POST lands back in this branch
        $formaction = new moodle_url('/mod/teamsintegration/view.php', ['id' => $cm->id, 'tab' => 'addattendee']);
        echo '<form method="post" action="' . $formaction->out(false) . '">';    echo html_writer::empty_tag('input', ['type'=>'hidden','name'=>'id','value'=>$cm->id]);
        echo html_writer::empty_tag('input', ['type'=>'hidden','name'=>'meetingid','value'=>$meeting->id]);
        echo html_writer::empty_tag('input', ['type'=>'hidden','name'=>'sesskey','value'=>sesskey()]);

        if ($pagingbarhtml) {
            // echo '<div class="paging">' . $pagingbarhtml . '</div>';
        }
        echo '<p>' . get_string('selectattendeestoinvite', 'mod_teamsintegration') . '</p>';

        echo '<table class="table table-striped" style="width:100%;">';
        echo '<thead><tr>';
        echo '<th style="width:50px;"><input type="checkbox" id="selectall" /></th>';
        echo '<th>' . get_string('fullname', 'moodle') . '</th>';
        echo '<th>' . get_string('email', 'mod_teamsintegration') . '</th>';
        echo '</tr></thead><tbody>';
        foreach ($current_users as $user) {
            $fullname = fullname($user);
            echo '<tr>';
            echo '<td><input type="checkbox" name="attendees[]" value="' . $user->id . '" class="attendee-checkbox" /></td>';
            echo '<td>' . s($fullname) . '</td>';
            echo '<td>' . s($user->email) . '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
        if ($pagingbarhtml) {
            echo '<div class="paging">' . $pagingbarhtml . '</div>';
        }
        // use Moodle button classes for consistent styling
        echo html_writer::tag('button', get_string('addselectedattendees', 'mod_teamsintegration'), ['type'=>'submit', 'class'=>'btn btn-primary']);
        echo '</form>';

        // select all javascript
        echo '<script>
        document.addEventListener("DOMContentLoaded",function(){
            var sa=document.getElementById("selectall");
            var cbs=document.querySelectorAll(".attendee-checkbox");
            if(sa){sa.addEventListener("change",function(){
                cbs.forEach(cb=>cb.checked=sa.checked);
            });}
            cbs.forEach(cb=>cb.addEventListener("change",function(){
                sa.checked=Array.from(cbs).every(x=>x.checked);
            }));
        });
        </script>';
    } elseif ($tab === 'report' && has_capability('mod/teamsintegration:report', $context)) {
        $rows = teamsintegration_get_attendance_details($meeting->id);
        if (!empty($rows)) {
            $table = new html_table();
            $table->head = [
                get_string('fullname', 'moodle'),
                get_string('jointime', 'mod_teamsintegration'),
                get_string('leavetime', 'mod_teamsintegration'),
                get_string('duration', 'mod_teamsintegration'),
            ];
            foreach ($rows as $row) {
                $name = !empty($row->firstname) ? fullname($row) : s($row->email ?: get_string('notapplicable', 'moodle'));
		$durationdisplay = '-';
                if ($row->duration !== null) {
                    $totalseconds = (int)$row->duration;
                    $minutes = intdiv($totalseconds, 60);
                    $seconds = $totalseconds % 60;
                    $durationdisplay = $minutes . 'm ' . $seconds . 's';
                }
                $table->data[] = [
                    $name,
                    $row->jointime  ? userdate($row->jointime)  : 'Not joined',
                    $row->leavetime ? userdate($row->leavetime) : '-',
                    $durationdisplay,
                ];
            }
            echo html_writer::table($table);
        } else {
            echo html_writer::tag('p', get_string('noattendees', 'mod_teamsintegration'));
        }
    }
}

echo $OUTPUT->footer();
