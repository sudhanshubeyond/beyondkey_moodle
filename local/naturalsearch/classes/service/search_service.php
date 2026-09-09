<?php

namespace local_naturalsearch\service;

defined('MOODLE_INTERNAL') || die();

use moodle_url;

class search_service {

    /**
     * Perform natural language course search.
     *
     * @param string $query
     * @param int $page
     * @param int $limit
     * @param int $userid
     * @return array
     */
    public function search(
        string $query,
        int $page = 0,
        int $limit = 20,
        int $userid = 0
    ): array {

        $query = trim($query);

        if ($query === '') {
            return [];
        }

        /*
         * Get plugin configuration.
         */
        $config = get_config('local_naturalsearch');

        /*
         * Check whether Natural Search is enabled.
         */
        if (isset($config->enabled) && empty($config->enabled)) {
            return [];
        }

        /*
         * AI API currently supports "top", not page/offset.
         *
         * Request enough results from the API so that we can
         * perform pagination inside Moodle.
         */
        $top = ($page + 1) * $limit;

        $response = $this->call_ai_api($query, $top);

        if (empty($response) || empty($response->results)) {
            return [];
        }

        $results = [];

        foreach ($response->results as $item) {

            if (empty($item->courseId)) {
                continue;
            }

            $courseid = (int)$item->courseId;

            try {
                $course = get_course($courseid);
            } catch (\Exception $e) {
                debugging(
                    'Unable to load Moodle course ' . $courseid .
                    ': ' . $e->getMessage(),
                    DEBUG_DEVELOPER
                );

                continue;
            }

            /*
             * Build matched section information.
             */
            $matchedcontent = '';

            if (!empty($item->matchedSections)) {
                $matchedcontent = implode(
                    ', ',
                    (array)$item->matchedSections
                );
            }

            $results[] = [
                'type' => 'course',
                'courseid' => $courseid,
                'contentid' => 0,
                'title' => $course->fullname,
                'score' => !empty($item->finalScore)
                    ? (float)$item->finalScore
                    : 0,
                'matchedcontent' => $matchedcontent,
                'url' => (new moodle_url(
                    '/course/view.php',
                    ['id' => $courseid]
                ))->out(false),
            ];
        }

        /*
         * API already returns relevance ordering.
         *
         * Apply Moodle pagination.
         */
        $offset = $page * $limit;

        return array_slice(
            $results,
            $offset,
            $limit
        );
    }

    /**
     * Call AI team's Natural Language Search API.
     *
     * @param string $query
     * @param int $top
     * @return object|null
     */
    private function call_ai_api(
        string $query,
        int $top = 25
    ): ?object {

        /*
         * Get plugin configuration.
         */
        $config = get_config('local_naturalsearch');

        /*
         * Get API URL from Moodle settings.
         */
        $endpoint = $config->apiurl ?? '';

        /*
         * Get API key from Moodle settings.
         *
         * This can remain empty if the API does not require
         * authentication.
         */
        $apikey = $config->apikey ?? '';

        /*
         * Get timeout from Moodle settings.
         *
         * Use 30 seconds as fallback.
         */
        $timeout = !empty($config->timeout)
            ? (int)$config->timeout
            : 30;

        /*
         * Make sure API URL is configured.
         */
        if (empty($endpoint)) {
            debugging(
                'Natural Search API URL is not configured.',
                DEBUG_DEVELOPER
            );

            return null;
        }

        /*
         * Prepare API request.
         *
         * Current AI API expects:
         *
         * {
         *     "query": "search text",
         *     "top": 20
         * }
         */
        $data = [
            'query' => $query,
            'top' => (int)$top,
        ];

        /*
         * Initialize cURL.
         */
        $ch = curl_init($endpoint);

        /*
         * Default HTTP headers.
         */
        $headers = [
            'Content-Type: application/json',
            'Accept: application/json',
        ];

        /*
        * Add X-Api-Key header when an API key
        * has been configured.
        */
        if (!empty($apikey)) {
            $headers[] = 'X-Api-Key: ' . $apikey;
        }

        /*
         * Configure cURL.
         */
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_TIMEOUT => $timeout,
        ]);

        /*
         * Execute API request.
         */
        $response = curl_exec($ch);

        /*
         * Check cURL errors.
         */
        if (curl_errno($ch)) {

            debugging(
                'Natural Search API cURL error: ' .
                curl_error($ch),
                DEBUG_DEVELOPER
            );

            curl_close($ch);

            return null;
        }

        /*
         * Get HTTP status code.
         */
        $httpcode = curl_getinfo(
            $ch,
            CURLINFO_HTTP_CODE
        );

        /*
         * Close cURL connection.
         */
        curl_close($ch);

        /*
         * Check HTTP response.
         */
        if ($httpcode < 200 || $httpcode >= 300) {

            debugging(
                'Natural Search API returned HTTP ' .
                $httpcode . ': ' . $response,
                DEBUG_DEVELOPER
            );

            return null;
        }

        /*
         * Decode JSON response.
         */
        $decoded = json_decode($response);

        /*
         * Check JSON errors.
         */
        if (json_last_error() !== JSON_ERROR_NONE) {

            debugging(
                'Natural Search API returned invalid JSON: ' .
                $response,
                DEBUG_DEVELOPER
            );

            return null;
        }

        /*
         * Return decoded API response.
         */
        return $decoded;
    }
}