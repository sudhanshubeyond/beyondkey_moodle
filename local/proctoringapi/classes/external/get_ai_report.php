<?php

namespace local_proctoringapi\external;

defined('MOODLE_INTERNAL') || die();

use external_api;
use external_function_parameters;
use external_value;
use external_multiple_structure;
use external_single_structure;

require_once($CFG->libdir . '/externallib.php');

class get_ai_report extends \external_api {

    public static function get_ai_report_parameters() {
        return new external_function_parameters([
            'cmid' => new external_value(PARAM_INT, 'Course module id'),
            'sort' => new external_value(PARAM_ALPHANUMEXT, 'Sort field', VALUE_DEFAULT, 'timecreated'),
            'dir'  => new external_value(PARAM_ALPHA, 'Sort direction', VALUE_DEFAULT, 'DESC'),
            'firstinitial' => new external_value(PARAM_ALPHA, 'First letter filter', VALUE_DEFAULT, ''),

            'search' => new external_value(PARAM_RAW_TRIMMED, 'Search text', VALUE_DEFAULT, ''),
            'page' => new external_value(PARAM_INT, 'Page number', VALUE_DEFAULT, 0),
            'perpage' => new external_value(PARAM_INT, 'Records per page', VALUE_DEFAULT, 10),
        ]);
    }

    public static function get_ai_report($cmid, $sort, $dir, $firstinitial, $search, $page, $perpage) {
        global $DB;

        $validfields = ['fullname', 'attemptid', 'focus_score_percent', 
                        'cheating_risk_percent', 'risk_level', 'timecreated'];

        if (!in_array($sort, $validfields)) {
            $sort = 'timecreated';
        }

        $dir = strtoupper($dir) === 'ASC' ? 'ASC' : 'DESC';

        $params['cmid'] = $cmid;

        $where = " AND pr.quizid = :cmid ";

        if (!empty($search)) {

            $where .= " AND (
                CONCAT(u.firstname,' ',u.lastname) LIKE :search1
                OR CAST(pr.attemptid AS CHAR) LIKE :search2
                OR pr.risk_level LIKE :search3
            ) ";

            $params['search1'] = '%' . trim($search) . '%';
            $params['search2'] = '%' . trim($search) . '%';
            $params['search3'] = '%' . trim($search) . '%';
        }

        if (!empty($firstinitial)) {
            $where .= " AND u.firstname LIKE :initial";

            $params['initial'] = $firstinitial . '%';
        }

        $sql = " SELECT pr.id, qa.attempt as attemptid, pr.focus_score_percent, 
                        pr.cheating_risk_percent, pr.risk_level, pr.finalobservations,
                        pr.timecreated, CONCAT(u.firstname, ' ', u.lastname) AS fullname
                   FROM {local_proctoring_results} pr
                   JOIN {user} u ON u.id = pr.studentid
                   JOIN {quiz_attempts} qa ON qa.id = pr.attemptid
                  WHERE 1 = 1 " . $where ."
               ORDER BY {$sort} {$dir}";

        $countsql = " SELECT COUNT(*)
                        FROM {local_proctoring_results} pr
                        JOIN {user} u ON u.id = pr.studentid
                        JOIN {quiz_attempts} qa ON qa.id = pr.attemptid
                        WHERE  1 = 1 $where ";

        $total = $DB->count_records_sql($countsql, $params);

        $params2['cmid'] = $cmid;
        $countsql2 = " SELECT COUNT(*)
                        FROM {local_proctoring_results} pr
                        JOIN {user} u ON u.id = pr.studentid
                        JOIN {quiz_attempts} qa ON qa.id = pr.attemptid
                        WHERE 1 = 1 AND pr.quizid = :cmid ";
        $recordscount = $DB->count_records_sql($countsql2, $params2);

        $offset = $page * $perpage;

        $records = $DB->get_records_sql($sql, $params, $offset, $perpage);

        if ($total > 10) {
            $enablepagenation = true;
        }

        return [
            'total' => $total,
            'records' => array_values($records),
            'enablepagenation' => $enablepagenation,
            'enablesearch' => ($recordscount > 0) ? true : false,
        ];
    }

    public static function get_ai_report_returns() {
        return new external_single_structure([
            'total' => new external_value(PARAM_INT, 'Total'),
            'records' => new external_multiple_structure(
                new external_single_structure([
                    'id' => new external_value(PARAM_INT, 'id'),
                    'attemptid' => new external_value(PARAM_INT, 'attempt'),
                    'focus_score_percent' => new external_value(PARAM_FLOAT, 'focus'),
                    'cheating_risk_percent' => new external_value(PARAM_FLOAT, 'risk'),
                    'risk_level' => new external_value(PARAM_TEXT, 'risk'),
                    'finalobservations' => new external_value(PARAM_RAW, 'obs'),
                    'timecreated' => new external_value(PARAM_INT, 'time'),
                    'fullname' => new external_value(PARAM_TEXT, 'name')
                ])
            ),
            'enablepagenation' => new external_value(PARAM_BOOL, 'Enable Pagenation'),
            'enablesearch' => new external_value(PARAM_BOOL, 'Enable Search'),
        ]);
    }    
}
