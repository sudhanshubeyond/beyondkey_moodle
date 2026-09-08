<?php

namespace local_naturalsearch\external;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');

use external_api;
use external_function_parameters;
use external_value;
use external_multiple_structure;
use external_single_structure;
use local_naturalsearch\service\search_service;

class search extends external_api {

    /**
     * Define parameters accepted by the AJAX request.
     *
     * @return external_function_parameters
     */
    public static function execute_parameters() {

        return new external_function_parameters([
            'query' => new external_value(
                PARAM_TEXT,
                'Natural language search query'
            ),

            'page' => new external_value(
                PARAM_INT,
                'Page number',
                VALUE_DEFAULT,
                0
            ),

            'limit' => new external_value(
                PARAM_INT,
                'Maximum number of results',
                VALUE_DEFAULT,
                20
            ),
        ]);
    }

    /**
     * Execute search.
     *
     * @param string $query
     * @param int $page
     * @param int $limit
     * @return array
     */
    public static function execute(
        string $query,
        int $page = 0,
        int $limit = 20
    ) {

        global $USER;

        $params = self::validate_parameters(
            self::execute_parameters(),
            [
                'query' => $query,
                'page' => $page,
                'limit' => $limit,
            ]
        );

        $query = trim($params['query']);

        /*
         * Return empty result when query is empty.
         */
        if ($query === '') {
            return [
                'status' => 'success',
                'query' => '',
                'total' => 0,
                'page' => 0,
                'limit' => $params['limit'],
                'results' => [],
            ];
        }

        /*
         * Prevent excessively large requests.
         */
        $limit = min(max($params['limit'], 1), 50);

        /*
         * Call Natural Search service.
         *
         * The service calls the AI team's API.
         */
        $service = new search_service();

        $results = $service->search(
            $query,
            $params['page'],
            $limit,
            $USER->id
        );

        /*
         * Return results to Moodle AJAX.
         */
        return [
            'status' => 'success',
            'query' => $query,
            'total' => count($results),
            'page' => $params['page'],
            'limit' => $limit,
            'results' => $results,
        ];
    }

    /**
     * Define response structure.
     *
     * @return external_single_structure
     */
    public static function execute_returns() {

        return new external_single_structure([

            'status' => new external_value(
                PARAM_ALPHANUMEXT,
                'Response status'
            ),

            'query' => new external_value(
                PARAM_TEXT,
                'Original search query'
            ),

            'total' => new external_value(
                PARAM_INT,
                'Number of results'
            ),

            'page' => new external_value(
                PARAM_INT,
                'Current page'
            ),

            'limit' => new external_value(
                PARAM_INT,
                'Maximum number of results'
            ),

            'results' => new external_multiple_structure(

                new external_single_structure([

                    'type' => new external_value(
                        PARAM_ALPHANUMEXT,
                        'Result type'
                    ),

                    'courseid' => new external_value(
                        PARAM_INT,
                        'Moodle course ID'
                    ),

                    'contentid' => new external_value(
                        PARAM_INT,
                        'Moodle content ID'
                    ),

                    'title' => new external_value(
                        PARAM_TEXT,
                        'Result title'
                    ),

                    'score' => new external_value(
                        PARAM_FLOAT,
                        'Relevance score'
                    ),

                    'matchedcontent' => new external_value(
                        PARAM_RAW,
                        'Content matching the search'
                    ),

                    'url' => new external_value(
                        PARAM_URL,
                        'Moodle result URL'
                    ),
                ])
            ),
        ]);
    }
}