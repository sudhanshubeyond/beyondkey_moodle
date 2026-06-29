<?php
define('AJAX_SCRIPT', true);
require_once(__DIR__ . '/../../config.php');
require_login();

$courseid = required_param('courseid', PARAM_INT);
require_sesskey();

global $DB, $USER;

// ---- Validate course exists ----
$course = $DB->get_record('course', ['id' => $courseid], '*', IGNORE_MISSING);
if (!$course) {
    header('Content-Type: text/html; charset=utf-8');
    echo html_writer::tag('tr',
        html_writer::tag('td', get_string('norecords', 'block_xp_leaderboard'), ['colspan' => 3])
    );
    exit;
}

$ctx = context_course::instance($courseid, MUST_EXIST);

// ---- Access checks ----
// Allow if: site admin OR has view capability in the course OR is enrolled in the course.
$canview = is_siteadmin($USER->id)
    || has_capability('moodle/course:view', $ctx)
    || is_enrolled($ctx, $USER, '', true);

if (!$canview) {
    header('Content-Type: text/html; charset=utf-8');
    echo html_writer::tag('tr',
        html_writer::tag('td', get_string('norecords', 'block_xp_leaderboard'), ['colspan' => 3])
    );
    exit;
}

// ---- Check if Admin or Teacher (can see all students) ----
$seeall = false;

// Allow Teacher and Admin to see all students' points in the selected course
// if (is_siteadmin($USER->id) || has_capability('block/xp_leaderboard:viewallstudents', $ctx) || has_capability('moodle/course:view', $ctx)) {
//     $seeall = true; // Allow Teacher and Admin to see all students
// }

if (is_siteadmin($USER->id) || has_capability('block/xp_leaderboard:viewallstudents', $ctx)) {
    $seeall = true; // Allow Teacher and Admin to see all students
}

// ---- Fetch XP data for this course ----
$sql = "SELECT u.id AS userid, u.username, x.xp
          FROM {block_xp} x
          JOIN {user} u ON u.id = x.userid
         WHERE x.courseid = :courseid
      ORDER BY x.xp DESC, u.id ASC";
$rows = $DB->get_records_sql($sql, ['courseid' => $courseid]);

// ---- Build HTML rows ----
$tbody = '';
$rank = 0; 
$prevxp = null; 
$pos = 0;
$firstRankPoints = null;

foreach ($rows as $r) {
    $pos++;
    if ($prevxp === null || ((int)$r->xp !== (int)$prevxp)) {
        $rank = $pos;
        $prevxp = (int)$r->xp;
    }

    // Store the first-ranked user (the one with the highest points)
    if ($rank == 1) {
        $firstRankPoints = $r->xp; // Store the first rank points (XP)
    }

    // If the user is a student, show only their points and the first-ranked user's points.
    // Otherwise, if teacher or admin, show all students' points.
    if (!$seeall && (int)$r->userid !== (int)$USER->id && $rank != 1) {
        continue; // If the user is a student, show only their own row and the first-ranked user
    }

    // Highlight users with the same XP as the first-ranked user
    $highlight = ($r->xp == $firstRankPoints) ? ' class="xplb-top-rank"' : '';

    $tbody .= html_writer::tag('tr' . $highlight,
        html_writer::tag('td', s($r->username)) .
        html_writer::tag('td', (int)$r->xp) .
        html_writer::tag('td', $rank)
    );
}

// Get the XP for the logged-in user and the selected course
$xp = $DB->get_field('block_xp', 'xp', ['userid' => $USER->id, 'courseid' => $courseid]);

// Check if the logged-in user has XP and if the user is a student
if (!$xp && !is_siteadmin($USER->id) && !has_capability('block/xp_leaderboard:viewallstudents', $ctx)) {
    // If no XP found for a student, assign 0 points for the logged-in user
    $noRankUser = (object) [
        'username' => $USER->username,
        'xp' => 0,  // Show 0 XP for the student if no record exists
        'rank' => 0,  // No rank assigned for students without XP
    ];

    // Append the student's row with 0 points and rank
    $tbody .= html_writer::tag('tr',
        html_writer::tag('td', s($noRankUser->username)) .
        html_writer::tag('td', (int)$noRankUser->xp) .
        html_writer::tag('td', $noRankUser->rank)
    );
}

// If no results are found
if ($tbody === '') {
    $tbody = html_writer::tag('tr',
        html_writer::tag('td', get_string('norecords', 'block_xp_leaderboard'), ['colspan' => 3])
    );
}

header('Content-Type: text/html; charset=utf-8');
echo $tbody;