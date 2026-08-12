<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * The path to the report file for the quizaccess_proctoring plugin.
 *
 * This constant holds the relative path to the report.php file used by the
 * quiz access rule for proctoring. It is utilized in the plugin to access
 * the report generation functionality.
 *
 * @package    quizaccess_proctoring
 * @copyright  2024 Brain Station 23
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__.'/../../../../config.php');
require_once($CFG->dirroot.'/mod/quiz/accessrule/proctoring/lib.php');
require_once($CFG->libdir.'/tablelib.php');

// Parameters.
$courseid = required_param('courseid', PARAM_INT);
$cmid = required_param('cmid', PARAM_INT);
$studentid = optional_param('studentid', null, PARAM_INT);
$searchkey = optional_param('searchKey', null, PARAM_TEXT);
$submittype = optional_param('submitType', null, PARAM_TEXT);
$reportid = optional_param('reportid', null, PARAM_INT);
$logaction = optional_param('logaction', null, PARAM_TEXT);
$page = optional_param('page', 0, PARAM_INT);



$analyzebtn = get_string('analyzbtn', 'quizaccess_proctoring');
$analyzebtnconfirm = get_string('analyzbtnconfirm', 'quizaccess_proctoring');


// Context and validation.
$context = context_module::instance($cmid, MUST_EXIST);
require_capability('quizaccess/proctoring:viewreport', $context);

list($course, $cm) = get_course_and_cm_from_cmid($cmid, 'quiz');
require_login($course, true, $cm);

// Course and quiz data.
$coursedata = $DB->get_record('course', ['id' => $courseid]);
$quiz = $DB->get_record('quiz', ['id' => $cm->instance]);

// URL setup.
$params = [
    'courseid' => $courseid,
    'userid' => $studentid,
    'cmid' => $cmid,
];
// Pagination set.
$perpage = 30;
$offset = $page * $perpage;
$totalrecords = 0;

if ($studentid) {
    $params['studentid'] = $studentid;
}
if ($reportid) {
    $params['reportid'] = $reportid;
}

$url = new moodle_url('/mod/quiz/accessrule/proctoring/report.php', ['courseid' => $courseid, 'cmid' => $cmid]);
$fcmethod = get_config('quizaccess_proctoring', 'fcmethod');

// Page setup.
$context = context_module::instance($cmid, MUST_EXIST);
$PAGE->set_context($context);

$PAGE->set_url($url);
$PAGE->set_pagelayout('course');
$PAGE->set_title($coursedata->shortname . ': ' . get_string('pluginname', 'quizaccess_proctoring'));
$PAGE->set_heading($coursedata->fullname . ': ' . get_string('pluginname', 'quizaccess_proctoring'));
$PAGE->navbar->add(get_string('quizaccess_proctoring', 'quizaccess_proctoring'), $url);
$PAGE->requires->js_call_amd('quizaccess_proctoring/lightbox2', 'init', [$fcmethod , [
    'analyzebtn' => $analyzebtn,
    'analyzebtnconfirm' => $analyzebtnconfirm,
]]);
$PAGE->requires->css('/mod/quiz/accessrule/proctoring/styles.css');
// Add navbar for studnet report.
if ($studentid != null && $cmid != null && $courseid != null && $reportid != null) {
    $PAGE->navbar->add(get_string('studentreport', 'quizaccess_proctoring') . " - $studentid", $url);
}

// Button logic.
$settingsbtn = has_capability('quizaccess/proctoring:viewreport', $context, $USER->id);
$showclearbutton = ($submittype === 'Search' && !empty($searchkey));

if (has_capability('quizaccess/proctoring:deletecamshots', $context, $USER->id) && $studentid != null
    && $cmid != null && $courseid != null && $reportid != null&& !empty($logaction)) {

        $DB->delete_records('quizaccess_proctoring_logs', [
            'courseid' => $courseid,
            'quizid' => $cmid,
            'userid' => $studentid,
        ]);
        $DB->delete_records('quizaccess_proctoring_fm_warnings', [
            'courseid' => $courseid,
            'quizid' => $cmid,
            'userid' => $studentid,
        ]);

        $params = [
            'userid' => $studentid,
            'contextid' => $context->id,
            'component' => 'quizaccess_proctoring',
            'filearea'  => 'picture',
        ];

        $usersfile = $DB->get_records('files', $params);
        $fs = get_file_storage();
        foreach ($usersfile as $file) {
            $fileinfo = [
                'component' => 'quizaccess_proctoring',
                'filearea' => 'picture',
                'itemid' => $file->itemid,
                'contextid' => $context->id,
                'filepath' => '/',
                'filename' => $file->filename,
            ];
            $storedfile = $fs->get_file($fileinfo['contextid'], $fileinfo['component'], $fileinfo['filearea'],
                        $fileinfo['itemid'], $fileinfo['filepath'], $fileinfo['filename']);
            if ($storedfile) {
                $storedfile->delete();
            }
        }

        redirect(new moodle_url('/mod/quiz/accessrule/proctoring/report.php', [
            'courseid' => $courseid,
            'cmid' => $cmid,
        ]), 'Images deleted!', -11);
}

$proctoringprolink = new moodle_url(
    '/mod/quiz/accessrule/proctoring/proctoring_pro_promo.php',
    [
        'cmid' => $cmid,
        'courseid' => $courseid,
    ]
);

echo $OUTPUT->header();

$templatecontext = [
    'courseid' => $courseid,
    'cmid' => $cmid
];

echo $OUTPUT->render_from_template(
    'quizaccess_proctoring/report',
    $templatecontext
);

// Container for studentreport Mustache.
// It will be populated by AJAX when "View images" is clicked.
echo html_writer::div('', '', [
    'id' => 'student-report'
]);

$PAGE->requires->js_call_amd(
    'quizaccess_proctoring/report',
    'init',
    [$courseid, $cmid, $studentid, $searchkey, $reportid]
);



echo $OUTPUT->footer();
