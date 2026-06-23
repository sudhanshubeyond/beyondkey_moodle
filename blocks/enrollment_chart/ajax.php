<?php
require('../../config.php');
require_login();

$courseid = required_param('courseid', PARAM_INT);
$year     = required_param('year', PARAM_INT);

$course  = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);
$context = context_course::instance($course->id);
//require_capability('moodle/site:viewparticipants', $context);

$starts = $ends = [];
for ($m = 1; $m <= 12; $m++) {
    $starts[$m] = make_timestamp($year, $m, 1, 0, 0, 0, 0);
    $ends[$m]   = ($m === 12) ? make_timestamp($year+1, 1, 1, 0,0,0,0)
                              : make_timestamp($year, $m+1, 1, 0,0,0,0);
}

$cases = [];
$params = ['courseid' => $courseid];
for ($m = 1; $m <= 12; $m++) {
    $cases[] = "SUM(CASE WHEN ue.timecreated >= :s$m AND ue.timecreated < :e$m THEN 1 ELSE 0 END) AS m$m";
    $params["s$m"] = $starts[$m];
    $params["e$m"] = $ends[$m];
}
$sql = "SELECT ".implode(", ", $cases)."
          FROM {user_enrolments} ue
          JOIN {enrol} e ON e.id = ue.enrolid
          WHERE e.courseid = :courseid
           AND ue.status = 0";

$row = $DB->get_record_sql($sql, $params);

$labels = $counts = [];
for ($m = 1; $m <= 12; $m++) {
    $labels[] = userdate(make_timestamp($year,$m,1,0,0,0,0), '%b');
    $counts[] = (int)($row->{"m$m"} ?? 0);
}

@header('Content-Type: application/json');
echo json_encode(['labels'=>$labels,'counts'=>$counts]);
exit;
