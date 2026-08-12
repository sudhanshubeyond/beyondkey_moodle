<?php
// This file is part of Moodle.
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
// See the GNU General Public License for more details.

namespace quizaccess_proctoring\external;

defined('MOODLE_INTERNAL') || die();

global $CFG;

require_once($CFG->libdir . '/externallib.php');
require_once($CFG->dirroot . '/mod/quiz/accessrule/proctoring/lib.php');
require_once($CFG->libdir . '/filelib.php');

use external_api;
use external_function_parameters;
use external_single_structure;
use external_multiple_structure;
use external_value;
use context_module;
use moodle_url;
use pix_icon;
use action_menu;
use action_menu_link_secondary;
use core_user;

require_once($CFG->dirroot . '/mod/quiz/accessrule/proctoring/lib.php');

class get_report extends external_api {

    /**
     * Parameters.
     *
     * @return external_function_parameters
     */
    public static function get_report_parameters() {
        return new external_function_parameters([
            'courseid' => new external_value(PARAM_INT, 'Course ID'),
            'cmid' => new external_value(PARAM_INT, 'Course module ID'),
            'studentid' => new external_value(PARAM_INT, 'Student ID', VALUE_DEFAULT, 0),
            'page' => new external_value(PARAM_INT, 'Page number', VALUE_DEFAULT, 0),
            'perpage' => new external_value(PARAM_INT, 'Records per page', VALUE_DEFAULT, 30),
            'searchkey' => new external_value( PARAM_TEXT, 'Search text', VALUE_DEFAULT, ''),
            'sort' => new external_value(PARAM_ALPHA, 'Sort field', VALUE_DEFAULT, 'timemodified'),
            'dir' => new external_value(PARAM_ALPHA, 'Sort direction', VALUE_DEFAULT, 'DESC'),
            'reportid' => new external_value(PARAM_INT, 'Report ID', VALUE_DEFAULT, 0),
        ]);
    }


    /**
     * Get report.
     *
     * @param int $courseid
     * @param int $cmid
     * @param int $studentid
     * @param int $page
     * @param int $perpage
     * @param string $searchkey
     * @param string $sort
     * @param string $dir
     * @param int $reportid
     *
     * @return array
     */
    public static function get_report($courseid, $cmid, $studentid = 0, $page = 0, $perpage = 30, $searchkey = '', $sort = 'timemodified', $dir = 'DESC', $reportid = 0) {
        global $DB, $CFG, $PAGE, $OUTPUT;

        // Validate parameters.
        $params = self::validate_parameters(
            self::get_report_parameters(),
            [
                'courseid' => $courseid,
                'cmid' => $cmid,
                'studentid' => $studentid,
                'page' => $page,
                'perpage' => $perpage,
                'searchkey' => $searchkey,
                'sort' => $sort,
                'dir' => $dir,
                'reportid' => $reportid,
            ]
        );

        $courseid = $params['courseid'];
        $cmid = $params['cmid'];
        $studentid = $params['studentid'];
        $page = $params['page'];
        $perpage = $params['perpage'];
        $searchkey = trim($params['searchkey']);
        $sort = $params['sort'];
        $dir = strtoupper($params['dir']);
        $reportid = $params['reportid'];

        /*
         * ---------------------------------------------------------
         * Validate course/module/context.
         * ---------------------------------------------------------
         */

        list($course, $cm) = get_course_and_cm_from_cmid($cmid, 'quiz');

        require_login($course, true, $cm);

        $context = context_module::instance($cmid, MUST_EXIST);

        require_capability('quizaccess/proctoring:viewreport', $context);

        /*
         * ---------------------------------------------------------
         * Sort validation.
         * ---------------------------------------------------------
         */

        $allowedcolumns = ['fullname', 'email', 'timemodified'];

        if (!in_array($sort, $allowedcolumns, true)) {
            $sort = 'timemodified';
        }

        $dir = ($dir === 'ASC') ? 'ASC' : 'DESC';

        /*
         * ---------------------------------------------------------
         * Quiz.
         * ---------------------------------------------------------
         */

        $quiz = $DB->get_record('quiz', ['id' => $cm->instance], '*', MUST_EXIST);

        /*
         * ---------------------------------------------------------
         * Pagination.
         * ---------------------------------------------------------
         */

        $offset = $page * $perpage;

        /*
         * ---------------------------------------------------------
         * Main report SQL.
         * ---------------------------------------------------------
         */

        $sql = '';
        $sqlparams = [];

        /*
         * Student report.
         */
        if (!empty($studentid) && !empty($reportid)) {

            $sql = "SELECT
                        e.id AS reportid,
                        e.userid AS studentid,
                        e.webcampicture AS webcampicture,
                        e.status AS status,
                        e.timemodified AS timemodified,
                        u.firstname AS firstname,
                        u.lastname AS lastname,
                        u.email AS email,
                        pfw.reportid AS warningid
                    FROM
                        {quizaccess_proctoring_logs} e
                    INNER JOIN
                        {user} u
                        ON u.id = e.userid
                    LEFT JOIN
                        {quizaccess_proctoring_fm_warnings} pfw
                        ON e.courseid = pfw.courseid
                        AND e.quizid = pfw.quizid
                        AND e.userid = pfw.userid
                    WHERE
                        e.courseid = :courseid
                        AND e.quizid = :cmid
                        AND u.id = :studentid
                        AND e.id = :reportid";

            $sqlparams = [
                'courseid' => $courseid,
                'cmid' => $cmid,
                'studentid' => $studentid,
                'reportid' => $reportid,
            ];

        } else {

            /*
             * All users / search.
             */
            $sql = "SELECT DISTINCT
                        e.userid AS studentid,
                        u.firstname AS firstname,
                        u.lastname AS lastname,
                        u.email AS email,
                        pfw.reportid AS warningid,
                        MAX(e.webcampicture) AS webcampicture,
                        MAX(e.id) AS reportid,
                        MAX(e.status) AS status,
                        MAX(e.timemodified) AS timemodified
                    FROM
                        {quizaccess_proctoring_logs} e
                    INNER JOIN
                        {user} u
                        ON u.id = e.userid
                    LEFT JOIN
                        {quizaccess_proctoring_fm_warnings} pfw
                        ON e.courseid = pfw.courseid
                        AND e.quizid = pfw.quizid
                        AND e.userid = pfw.userid
                    WHERE
                        e.courseid = :courseid
                        AND e.quizid = :cmid";

            $sqlparams = [
                'courseid' => $courseid,
                'cmid' => $cmid,
            ];

            if ($searchkey !== '') {

                $likefirstname = $DB->sql_like('u.firstname', ':firstnamelike', false);

                $likeemail = $DB->sql_like('u.email', ':emaillike', false);

                $likelastname = $DB->sql_like('u.lastname', ':lastnamelike', false);

                $formatteddate = "FROM_UNIXTIME(e.timemodified, '%d %b %Y, %l:%i %p')";

                $liketimemodified = $DB->sql_like(
                    $formatteddate,
                    ':timemodifiedlike',
                    false
                );

                $sql .= " AND (
                    $likefirstname
                    OR $likeemail
                    OR $likelastname
                    OR $liketimemodified
                )";

                $sqlparams['firstnamelike'] = '%' . $searchkey . '%';
                $sqlparams['emaillike'] = '%' . $searchkey . '%';
                $sqlparams['lastnamelike'] = '%' . $searchkey . '%';
                $sqlparams['timemodifiedlike'] = '%' . $searchkey . '%';
            }

            $sql .= " GROUP BY
                        e.userid,
                        u.firstname,
                        u.lastname,
                        u.email,
                        pfw.reportid";
        }

        /*
         * ---------------------------------------------------------
         * Total records.
         * ---------------------------------------------------------
         */

        $countsql = "SELECT COUNT(1) FROM ({$sql}) reportquery";

        $totalrecords = $DB->count_records_sql($countsql, $sqlparams);

        /*
         * ---------------------------------------------------------
         * Sorting.
         * ---------------------------------------------------------
         */

        if ($sort === 'fullname') {

            $sortsql = 'u.firstname';

        } else if ($sort === 'email') {

            $sortsql = 'u.email';

        } else {

            // $sortsql = 'MAX(e.timemodified)';
            $sortsql = 'e.timemodified';
            // $sortsql = '%'.date('d M Y, g:i A', 'e.timemodified').'%';
        }

        /*
         * For the grouped query, append ORDER BY.
         */
        $sql .= " ORDER BY {$sortsql} {$dir}";

        /*
         * ---------------------------------------------------------
         * Fetch records.
         * ---------------------------------------------------------
         */

        $records = $DB->get_records_sql($sql, $sqlparams, $offset, $perpage);

        /*
         * ---------------------------------------------------------
         * Build report rows.
         * ---------------------------------------------------------
         */

        $rows = [];

        foreach ($records as $info) {

            $row = [];

            $row['studentid'] = (int)$info->studentid;

            $row['userlink'] = (
                $CFG->wwwroot .'/user/view.php?id=' .(int)$info->studentid .'&course=' .(int)$courseid);

            $row['fullname'] = $info->firstname . ' ' . $info->lastname;

            $row['email'] = $info->email;

            $row['timemodified'] = date('d M Y, g:i A', $info->timemodified);

            $row['warningicon'] = ($info->warningid == '') ? true : false;

            /*
             * Action menu.
             */
            $actionmenu = new action_menu();

            $actionmenu->set_kebab_trigger(
                get_string('actions')
            );

            /*
             * View images.
             *
             * IMPORTANT:
             * This is now a JavaScript action instead of navigating
             * to report.php.
             */
            // $viewurl = new moodle_url('#');

            $viewurl = new moodle_url(
                '/mod/quiz/accessrule/proctoring/report.php',
                [
                    'courseid' => $courseid,
                    'cmid' => $cmid,
                    'quizid' => $cmid,
                    'studentid' => $info->studentid,
                    'reportid' => $info->reportid,
                ]
            );

            $viewattributes = [
                'class' => 'view-student-report',
                'data-courseid' => $courseid,
                'data-cmid' => $cmid,
                'data-studentid' => $info->studentid,
                'data-reportid' => $info->reportid,
            ];

            $viewaction = new action_menu_link_secondary(
                $viewurl,
                new pix_icon(
                    'e/insert_edit_image',
                    get_string('viewimages', 'quizaccess_proctoring'),
                    'moodle'
                ),
                get_string('viewimages', 'quizaccess_proctoring'),
                $viewattributes
            );

            $actionmenu->add($viewaction);

            /*
             * Delete.
             */
            if (has_capability('quizaccess/proctoring:deletecamshots', $context)) {

                $deleteurl = new moodle_url(
                    '/mod/quiz/accessrule/proctoring/report.php',
                    [
                        'courseid' => $courseid,
                        'quizid' => $cmid,
                        'cmid' => $cmid,
                        'studentid' => $info->studentid,
                        'reportid' => $info->reportid,
                        'logaction' => 'delete',
                        'sesskey' => sesskey(),
                    ]
                );

                $deleteattributes = [
                    'data-confirmation' => 'modal',
                    'data-confirmation-type' => 'delete',
                    'data-confirmation-title-str' =>
                        json_encode(['delete', 'core']),
                    'data-confirmation-content-str' =>
                        json_encode([
                            'areyousure_delete_record',
                            'quizaccess_proctoring'
                        ]),
                    'data-confirmation-yes-button-str' =>
                        json_encode(['delete', 'core']),
                    'data-confirmation-action-url' =>
                        $deleteurl->out(false),
                    'data-confirmation-destination' =>
                        $deleteurl->out(false),
                    'class' => 'text-danger',
                ];

                $deleteaction = new action_menu_link_secondary($deleteurl, new pix_icon('t/delete', get_string('delete'), 'moodle'), get_string('delete'), $deleteattributes);

                $actionmenu->add($deleteaction);
            }

            $row['actionmenu'] = $OUTPUT->render(
                $actionmenu
            );

            $rows[] = $row;
        }

        /*
         * ---------------------------------------------------------
         * Main report context.
         * ---------------------------------------------------------
         */

        $templatecontext = [
            'quizname' => get_string('eprotroringreports', 'quizaccess_proctoring') . $quiz->name,
            'settingsbtn' => has_capability('quizaccess/proctoring:viewreport', $context),
            'settingspageurl' => $CFG->wwwroot .'/mod/quiz/accessrule/proctoring/proctoringsummary.php?cmid=' .$cmid,
            'proctoringsummary' => get_string('eprotroringreportsdesc', 'quizaccess_proctoring'),
            'url' => $CFG->wwwroot .'/mod/quiz/accessrule/proctoring/report.php',
            'courseid' => (int)$courseid,
            'cmid' => (int)$cmid,
            'searchkey' => $searchkey,
            'showclearbutton' => false,
            'checkrow' => !empty($rows),
            'rows' => $rows,
            'backbutton' => $CFG->wwwroot .'/mod/quiz/view.php?id=' .$cmid,
            'total' => (int)$totalrecords,
        ];

        /*
         * ---------------------------------------------------------
         * Student report.
         *
         * ALWAYS return an array.
         * This is important for external_api validation.
         * ---------------------------------------------------------
         */

        $records2 = [
            'featuresimageurl' => '',
            'proctoringprolink' => '',
            'issiteadmin' => false,
            'redirecturl' => '',
            'data' => [],
            'userimageurl' => '',
            'firstname' => '',
            'lastname' => '',
            'email' => '',
            'fcmethod' => false,
            'analyzeurl' => '',
        ];

        /*
         * Only generate student report when View Images
         * was clicked and studentid + reportid were supplied.
         */
        if (!empty($studentid) && !empty($reportid)) {

            $featuresimageurl = $OUTPUT->image_url(
                'proctoring_pro_report_overview',
                'quizaccess_proctoring'
            );

            $profileimageurl = quizaccess_proctoring_get_image_url($studentid);

            $redirecturl = new moodle_url(
                '/mod/quiz/accessrule/proctoring/upload_image.php',
                [
                    'id' => $studentid
                ]
            );

            $proctoringprolink = new moodle_url(
                '/mod/quiz/accessrule/proctoring/proctoring_pro_promo.php',
                [
                    'cmid' => $cmid,
                    'courseid' => $courseid,
                ]
            );

            $analyzeurl = new moodle_url(
                '/mod/quiz/accessrule/proctoring/analyzeimage.php',
                [
                    'studentid' => $studentid,
                    'cmid' => $cmid,
                    'courseid' => $courseid,
                    'reportid' => $reportid,
                ]
            );

            $userimageurl = quizaccess_proctoring_get_image_url($studentid);

            if (!$userimageurl) {
                $userimageurl = $OUTPUT->image_url('u/f2');
            }

            /*
             * Get student image records.
             */
            $studentsql = "SELECT
                                e.id AS reportid,
                                e.userid AS studentid,
                                e.webcampicture AS webcampicture,
                                e.status AS status,
                                e.timemodified AS timemodified,
                                u.firstname AS firstname,
                                u.lastname AS lastname,
                                u.email AS email,
                                e.awsscore,
                                e.awsflag
                           FROM
                                {quizaccess_proctoring_logs} e
                           INNER JOIN
                                {user} u
                                ON u.id = e.userid
                           WHERE
                                e.courseid = :courseid
                                AND e.quizid = :cmid
                                AND u.id = :studentid
                                AND e.deletionprogress = :deletionprogress
                           ORDER BY
                                e.status DESC,
                                e.id ASC";

            $studentparams = [
                'courseid' => $courseid,
                'cmid' => $cmid,
                'studentid' => $studentid,
                'deletionprogress' => 0,
            ];

            $studentrecords = $DB->get_records_sql($studentsql, $studentparams);

            $thresholdvalue = (int)quizaccess_proctoring_get_proctoring_settings('threshold');

            $studentdata = [];

            foreach ($studentrecords as $info) {

                /*
                 * Get actual quiz attempt number.
                 */
                $attemptid = $DB->get_field('quiz_attempts', 'attempt', ['id' => $info->status]);

                /*
                 * Avoid empty attempt names.
                 */
                if (empty($attemptid)) {
                    $attemptid = 0;
                }

                if (!isset($studentdata[$attemptid])) {
                    $studentdata[$attemptid] = [
                        'attemptname' => 'Attempt ' . $attemptid,
                        'images' => [],
                    ];
                }

                /*
                 * Determine border colour.
                 */
                if ($info->awsflag == 2 && $info->awsscore > $thresholdvalue) {
                    $bordercolor = 'green';
                } else if ($info->awsflag == 2 && $info->awsscore < $thresholdvalue) {
                    $bordercolor = 'red';
                } else if ($info->awsflag == 3 && $info->awsscore < $thresholdvalue) {
                    $bordercolor = 'yellow';
                } else {
                    $bordercolor = 'none';
                }

                $studentdata[$attemptid]['images'][] = [
                    'firstname' => $info->firstname,
                    'lastname' => $info->lastname,
                    'image_url' => $info->webcampicture,
                    'border_color' => $bordercolor,
                    'img_id' => 'reportid-' . $info->reportid,
                    'lightbox_data' => basename($info->webcampicture, '.png'),
                ];
            }

            $studentdata = array_values($studentdata);

            /*
             * Get student.
             */
            $user = core_user::get_user($studentid, '*', MUST_EXIST);

            /*
             * IMPORTANT:
             * All URLs are converted to strings.
             */
            $records2 = [
                'featuresimageurl' => $featuresimageurl->out(false),
                'proctoringprolink' => $proctoringprolink->out(false),
                'issiteadmin' => (bool)(is_siteadmin() && !$profileimageurl),
                'redirecturl' => $redirecturl->out(false),
                'data' => $studentdata,
                'userimageurl' => is_object($userimageurl) ? $userimageurl->out(false) : $userimageurl,
                'firstname' => (string)$user->firstname,
                'lastname' => (string)$user->lastname,
                'email' => (string)$user->email,
                'fcmethod' => ($fcmethod = get_config('quizaccess_proctoring', 'fcmethod')) === 'BS',
                'analyzeurl' => $analyzeurl->out(false),
            ];
        }

        /*
         * ---------------------------------------------------------
         * Final AJAX response.
         * ---------------------------------------------------------
         */

        return [
            'records' => $templatecontext,
            'records2' => $records2,
        ];

        // echo "<pre>";
        // print_r($a);die();
    }


    /**
     * Return structure.
     *
     * @return external_single_structure
     */
    public static function get_report_returns() {

        return new external_single_structure([
            /*
             * Main report.
             */
            'records' => new external_single_structure([
                'quizname' => new external_value(PARAM_TEXT, 'Quiz name'),
                'settingsbtn' => new external_value(PARAM_BOOL, 'Settings button'),
                'settingspageurl' => new external_value(PARAM_URL, 'Settings page URL'),
                'proctoringsummary' => new external_value(PARAM_TEXT, 'Proctoring summary'),
                'url' => new external_value(PARAM_URL, 'Report URL'),
                'courseid' => new external_value(PARAM_INT, 'Course ID'),
                'cmid' => new external_value(PARAM_INT, 'Course module ID'),
                'searchkey' => new external_value(PARAM_TEXT, 'Search key'),
                'showclearbutton' => new external_value(PARAM_BOOL, 'Show clear button'),
                'checkrow' => new external_value(PARAM_BOOL, 'Whether records exist'),
                'rows' => new external_multiple_structure(
                    new external_single_structure([
                        'studentid' => new external_value(PARAM_INT, 'Student ID'),
                        'fullname' => new external_value(PARAM_TEXT, 'Full name'),
                        'email' => new external_value(PARAM_EMAIL, 'Email'),
                        'timemodified' => new external_value(PARAM_TEXT, 'Modified time'),
                        'userlink' => new external_value(PARAM_URL, 'User profile URL'),
                        'warningicon' => new external_value(PARAM_BOOL, 'Warning icon'),
                        'actionmenu' => new external_value(PARAM_RAW, 'Action menu'),
                    ])
                ),
                'backbutton' => new external_value(PARAM_URL, 'Back button URL'),
                'total' => new external_value(PARAM_INT, 'Total records'),
            ]),

            /*
             * Student report.
             *
             * This is ALWAYS an object/array.
             */
            'records2' => new external_single_structure([
                'featuresimageurl' => new external_value(PARAM_URL, 'Features image URL', VALUE_DEFAULT, ''),
                'proctoringprolink' => new external_value(PARAM_URL, 'Proctoring pro link', VALUE_DEFAULT, ''),
                'issiteadmin' => new external_value(PARAM_BOOL, 'Whether site admin', VALUE_DEFAULT, false),
                'redirecturl' => new external_value(PARAM_URL, 'Upload image URL', VALUE_DEFAULT, ''),
                'data' => new external_multiple_structure(
                    new external_single_structure([
                        'attemptname' => new external_value(PARAM_TEXT, 'Attempt name'),
                        'images' => new external_multiple_structure(
                            new external_single_structure([
                                'firstname' => new external_value(PARAM_TEXT, 'First name'),
                                'lastname' => new external_value(PARAM_TEXT, 'Last name'),
                                'image_url' => new external_value(PARAM_URL, 'Image URL'),
                                'border_color' => new external_value(PARAM_TEXT, 'Border colour'),
                                'img_id' => new external_value(PARAM_TEXT, 'Image ID'),
                                'lightbox_data' => new external_value(PARAM_TEXT, 'Lightbox data'),
                            ])
                        ),
                    ])
                ),
                'userimageurl' => new external_value(PARAM_URL, 'User image URL', VALUE_DEFAULT, ''),
                'firstname' => new external_value(PARAM_TEXT, 'First name', VALUE_DEFAULT, ''),
                'lastname' => new external_value(PARAM_TEXT, 'Last name', VALUE_DEFAULT, ''),
                'email' => new external_value(PARAM_EMAIL, 'Email', VALUE_DEFAULT, ''),
                'fcmethod' => new external_value(PARAM_BOOL, 'Face comparison method', VALUE_DEFAULT, false),
                'analyzeurl' => new external_value(PARAM_URL, 'Analyze URL', VALUE_DEFAULT, ''),
            ]),
        ]);
    }
}
