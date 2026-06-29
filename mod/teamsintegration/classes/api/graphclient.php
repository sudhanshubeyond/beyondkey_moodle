<?php
namespace mod_teamsintegration\api;

defined('MOODLE_INTERNAL') || die();

class graphclient {
    private $token;

    public function __construct() {
        $this->token = $this->authenticate();
    }

    /**
     * Obtain an access token from Azure AD using client credentials.
     *
     * The token is stored in the instance for subsequent requests. If the
     * request fails the method returns null and debugging information is
     * emitted at developer level.
     *
     * @return string|null access token or null on failure
     */
    private function authenticate() {
        $clientid     = get_config('mod_teamsintegration', 'clientid');
        $clientsecret = get_config('mod_teamsintegration', 'clientsecret');
        $tenantid     = get_config('mod_teamsintegration', 'tenantid');

        $url = "https://login.microsoftonline.com/$tenantid/oauth2/v2.0/token";
        $postfields = "client_id={$clientid}&scope=https://graph.microsoft.com/.default"
        . "&client_secret={$clientsecret}&grant_type=client_credentials";
        $ch = curl_init($url);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result   = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error    = curl_error($ch);

        curl_close($ch);

        if ($result === false) {
            debugging('cURL error while requesting token: ' . $error, DEBUG_DEVELOPER);
            return null;
        }
        
        $response = json_decode($result, true);

        if ($httpcode !== 200 || empty($response['access_token'])) {
            debugging('Failed to get Microsoft Graph token: ' . print_r($response, true), DEBUG_DEVELOPER);
            return null;
        }

        return $response['access_token'];
    }


    /**
     * Create a new Teams event on behalf of the configured service account.
     *
     * @param string $subject meeting subject/title
     * @param int $start unix timestamp for start time (UTC)
     * @param int $end unix timestamp for end time (UTC)
     * @param string $email organizer email (currently unused)
     * @return mixed Graph response decoded from JSON
     */
    public function create_meeting($subject, $start, $end, $email) {

        $payload = [
            "subject" => $subject,
            "body" => [
                "contentType" => "HTML",
                "content" => "Meeting scheduled via Moodle"
            ],
            "start" => [
                "dateTime" => gmdate('Y-m-d\TH:i:s', $start),
                "timeZone" => "UTC"
            ],
            "end" => [
                "dateTime" => gmdate('Y-m-d\TH:i:s', $end),
                "timeZone" => "UTC"
            ],
            "location" => [
                "displayName" => "Microsoft Teams Meeting"
            ],
            "isOnlineMeeting" => true,
            "onlineMeetingProvider" => "teamsForBusiness"
        ];

        $azureobjectid = get_config('mod_teamsintegration', 'objectid');
        return $this->post(
            "https://graph.microsoft.com/v1.0/users/{$azureobjectid}/events",
            $payload
        );
    }

    /**
     * Fetch attendance reports for a given online meeting.
     *
     * @param string $meetingid Graph event/meeting id
     * @param string|null $joinurl Teams join URL for fallback lookup
     * @return mixed Graph API response
     */
    public function get_attendance($meetingid, $joinurl = null) {

        if (empty($meetingid)) {
            return ['value' => []];
        }

        $azureobjectid = get_config('mod_teamsintegration', 'objectid');

        $onlinemeetingid = null;
        $eventresponse = $this->get_online_meeting_id($meetingid);

        if (!empty($eventresponse['onlineMeeting']['id'])) {
            $onlinemeetingid = $eventresponse['onlineMeeting']['id'];
        }

        if (empty($onlinemeetingid)) {
            $fallbackjoinurl = $eventresponse['onlineMeeting']['joinUrl'] ?? $joinurl;
            $onlinemeeting = $this->get_online_meeting_by_joinurl($fallbackjoinurl);
            if (!empty($onlinemeeting['id'])) {
                $onlinemeetingid = $onlinemeeting['id'];
            }
        }

        if (empty($onlinemeetingid)) {
            return ['value' => []];
        }

        $encodedmeetingid = rawurlencode($onlinemeetingid);

        // Step 1: list all attendance reports for the meeting.
        $reportslist = $this->get(
            "https://graph.microsoft.com/v1.0/users/{$azureobjectid}/onlineMeetings/{$encodedmeetingid}/attendanceReports"
        );

        if (!empty($reportslist['error'])) {
            debugging(
                'Failed to fetch Teams attendance reports from Graph. '
                . 'Ensure the app has OnlineMeetingArtifact.Read.All and an application access policy '
                . 'for the organizer user. Response: ' . print_r($reportslist, true),
                DEBUG_DEVELOPER
            );
            return ['value' => []];
        }

        $entries = [];

        // Step 2: for each report fetch the full detail including attendanceRecords.
        foreach ($reportslist['value'] ?? [] as $reportstub) {
            $reportid = $reportstub['id'] ?? null;
            if (empty($reportid)) {
                continue;
            }

            $report = $this->get(
                "https://graph.microsoft.com/v1.0/users/{$azureobjectid}/onlineMeetings/{$encodedmeetingid}/attendanceReports/{$reportid}?\$expand=attendanceRecords"
            );

            if (!empty($report['error'])) {
                debugging('Failed to fetch attendanceRecords for report ' . $reportid . ': ' . print_r($report, true), DEBUG_DEVELOPER);
                continue;
            }
            
            foreach ($report['attendanceRecords'] ?? [] as $record) {
                $email = $record['emailAddress'] ?? null;

                if (empty($email) && !empty($record['identity']['emailAddress'])) {
                    $email = $record['identity']['emailAddress'];
                }

                $intervals = $record['attendanceIntervals'] ?? [];
                if (empty($intervals)) {
                    $entries[] = [
                        'emailAddress' => $email,
                        'joinDateTime' => null,
                        'leaveDateTime' => null,
                        'durationInSeconds' => (int)($record['totalAttendanceInSeconds'] ?? 0),
                    ];
                    continue;
                }

                foreach ($intervals as $interval) {
                    $jointime = $interval['joinDateTime'] ?? null;
                    $leavetime = $interval['leaveDateTime'] ?? null;
                    $duration = isset($interval['durationInSeconds']) ? (int)$interval['durationInSeconds'] : null;

                    if ($duration === null && !empty($jointime) && !empty($leavetime)) {
                        $duration = max(0, strtotime($leavetime) - strtotime($jointime));
                    }

                    $entries[] = [
                        'emailAddress' => $email,
                        'joinDateTime' => $jointime,
                        'leaveDateTime' => $leavetime,
                        'durationInSeconds' => (int)($duration ?? 0),
                    ];
                }
            }
        }
        
        return ['value' => $entries];
    }
    
    public function get_online_meeting_id($eventid) {
        $azureobjectid = get_config('mod_teamsintegration', 'objectid');
        //  $eventid = urlencode($eventid);
        return $this->get("https://graph.microsoft.com/v1.0/users/{$azureobjectid}/events/{$eventid}?\$select=onlineMeeting");
    }

    public function get_online_meeting_by_joinurl($joinurl) {
        if (empty($joinurl)) {
            return null;
        }

        $azureobjectid = get_config('mod_teamsintegration', 'objectid');
        $normalizedjoinurl = html_entity_decode(trim($joinurl), ENT_QUOTES);
        $safejoinurl = str_replace("'", "''", $normalizedjoinurl);

        $filter = rawurlencode("joinWebUrl eq '{$safejoinurl}'");
        $response = $this->get("https://graph.microsoft.com/v1.0/users/{$azureobjectid}/onlineMeetings?\$filter={$filter}");

        if (empty($response['value'])) {
            $filter = rawurlencode("JoinWebUrl eq '{$safejoinurl}'");
            $response = $this->get("https://graph.microsoft.com/v1.0/users/{$azureobjectid}/onlineMeetings?\$filter={$filter}");
        }
        
        return $response['value'][0] ?? null;
    }

    /**
     * Update basic details of an existing event/meeting on Graph.
     *
     * @param string $eventid Graph event id.
     * @param string $subject  New subject/title.
     * @param int    $start    Unix timestamp start.
     * @param int    $end      Unix timestamp end.
     * @return mixed Response from Graph (patched event).
     */
    public function update_meeting($eventid, $subject, $start, $end) {
        $azureobjectid = get_config('mod_teamsintegration', 'objectid');
        $payload = [
            'subject' => $subject,
            'start' => [
                'dateTime' => gmdate('Y-m-d\TH:i:s', $start),
                'timeZone' => 'UTC'
            ],
            'end' => [
                'dateTime' => gmdate('Y-m-d\TH:i:s', $end),
                'timeZone' => 'UTC'
            ],
        ];
        return $this->patch("https://graph.microsoft.com/v1.0/users/{$azureobjectid}/events/{$eventid}", $payload);
    }

    /**
     * Add an attendee to a Teams event by patching the attendees array.
     *
     * @param string $eventid Graph event id
     * @param string $email attendee email
     * @param string $type "required" or "optional"
     * @return mixed Graph response (existing event if already present)
     */
    public function add_attendee($eventid, $email, $type = 'required') {
        // Get current event to get all existing attendees
        $azureobjectid = get_config('mod_teamsintegration', 'objectid');
        $event = $this->get("https://graph.microsoft.com/v1.0/users/{$azureobjectid}/events/{$eventid}");
        
        $attendees = $event['attendees'] ?? [];
        
        // Check if attendee already exists
        foreach ($attendees as $att) {
            if (strtolower($att['emailAddress']['address']) === strtolower($email)) {
                return $event; // Already an attendee
            }
        }
        
        // Add new attendee to existing list
        $attendees[] = [
            'emailAddress' => [
                'address' => $email,
                'name' => $email,
            ],
            'type' => $type,
        ];
        
        // Patch the event with updated attendees
        $payload = [
            'attendees' => $attendees,
        ];
        
        return $this->patch("https://graph.microsoft.com/v1.0/users/{$azureobjectid}/events/{$eventid}", $payload);
    }

    /**
     * Remove an attendee from an existing event/meeting on Graph.
     *
     * @param string $eventid Event (meeting) id.
     * @param string $email   Email address of the attendee to remove.
     * @return mixed Response from Graph.
     */
    public function remove_attendee($eventid, $email) {
        // Get current event to get all attendees
        $azureobjectid = get_config('mod_teamsintegration', 'objectid');
        $event = $this->get("https://graph.microsoft.com/v1.0/users/{$azureobjectid}/events/{$eventid}");
        
        if (empty($event['attendees'])) {
            return $event; // No attendees to remove
        }
        
        // Filter out the attendee to be removed
        $updatedAttendees = [];
        foreach ($event['attendees'] as $attendee) {
            if (strtolower($attendee['emailAddress']['address']) !== strtolower($email)) {
                $updatedAttendees[] = $attendee;
            }
        }
        
        // Patch the event with updated attendees
        $payload = [
            'attendees' => $updatedAttendees,
        ];
        return $this->patch("https://graph.microsoft.com/v1.0/users/{$azureobjectid}/events/{$eventid}", $payload);
    }

    /**
     * Cancel a meeting and notify attendees with an optional comment.
     *
     * @param string $eventid Graph event id
     * @param string $comment cancellation message
     * @return mixed Graph response
     */
    public function cancel_meeting($eventid, $comment = '') {
        $azureobjectid = get_config('mod_teamsintegration', 'objectid');
        $url = "https://graph.microsoft.com/v1.0/users/{$azureobjectid}/events/{$eventid}/cancel";
        $payload = ['comment' => $comment];
        return $this->post($url, $payload);
    }

    /**
     * Delete a meeting/event from Graph outright.
     *
     * @param string $eventid Graph event id
     * @return mixed Graph response (usually empty)
     */
    public function delete_meeting($eventid) {
        $azureobjectid = get_config('mod_teamsintegration', 'objectid');
        $ch = curl_init("https://graph.microsoft.com/v1.0/users/{$azureobjectid}/events/{$eventid}");
        curl_setopt_array($ch, [
            CURLOPT_HTTPHEADER => ["Authorization: Bearer {$this->token}"],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'DELETE',
        ]);
        $res = curl_exec($ch);
        curl_close($ch);
        return json_decode($res, true);
    }

    /**
     * Internal helper: send a PATCH request with JSON payload.
     *
     * @param string $url endpoint
     * @param mixed $data data to encode as JSON
     * @return mixed decoded JSON response
     */
    private function patch($url, $data) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer {$this->token}",
                "Content-Type: application/json"
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'PATCH',
            CURLOPT_POSTFIELDS => json_encode($data)
        ]);
        $res = json_decode(curl_exec($ch), true);
        curl_close($ch);
        return $res;
    }

    /**
     * Internal helper: send a POST request with JSON payload.
     *
     * @param string $url endpoint
     * @param mixed $data data to encode
     * @return mixed decoded response
     */
    private function post($url, $data) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer {$this->token}",
                "Content-Type: application/json"
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data)
        ]);
        $res = json_decode(curl_exec($ch), true);
        curl_close($ch);
        return $res;
    }

    /**
     * Internal helper: send a GET request and decode JSON response.
     *
     * @param string $url endpoint
     * @return mixed decoded JSON response
     */
    private function get($url) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_HTTPHEADER => ["Authorization: Bearer {$this->token}"],
            CURLOPT_RETURNTRANSFER => true
        ]);
        $res = json_decode(curl_exec($ch), true);
        curl_close($ch);

        return $res;
    }
}
