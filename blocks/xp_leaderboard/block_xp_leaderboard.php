<?php
defined('MOODLE_INTERNAL') || die();

class block_xp_leaderboard extends block_base {

    public function init() {
        $this->title = get_string('pluginname', 'block_xp_leaderboard');
    }

    public function applicable_formats() {
        // Allow on dashboard (my), courses, site, etc.
        return ['all' => true, 'my' => true, 'site' => true, 'course' => true];
    }

    public function instance_allow_multiple() {
        return false;
    }

    public function get_content() {
        global $PAGE, $OUTPUT, $USER;

        if ($this->content !== null) {
            return $this->content;
        }

        require_login();

        $this->content = new stdClass();
        $this->content->text  = '';
        $this->content->footer = '';

        // Include block CSS & JS.
        $PAGE->requires->css(new moodle_url('/blocks/xp_leaderboard/styles.css'));
        $PAGE->requires->js(new moodle_url('/blocks/xp_leaderboard/assets/leaderboard.js'), true);

        // Build allowed courses for this user (admin → all, others → enrolled).
        $courses = $this->get_user_course_options();
        if (empty($courses)) {
            $this->content->text = html_writer::div(get_string('nocourses', 'block_xp_leaderboard'), 'xplb-empty');
            return $this->content;
        }

        // Select first course by default.
        $firstcourseid = array_key_first($courses);

        // Build dropdown.
        $selectattrs = [
            'id' => 'xplb-course',
            'class' => 'xplb-select',
            'data-ajax' => (new moodle_url('/blocks/xp_leaderboard/ajax.php'))->out(false),
            'data-sesskey' => sesskey()
        ];
        $label = html_writer::label(get_string('course', 'block_xp_leaderboard'), 'xplb-course', false, ['class' => 'xplb-label mr-2']);
        $courseselect = $label . html_writer::select($courses, 'xplb-course', $firstcourseid, false, $selectattrs);

        // Table skeleton.
        $table  = html_writer::start_tag('table', ['class' => 'xplb-table', 'id' => 'xplb-table']);
        $table .= html_writer::start_tag('thead');
        $table .= html_writer::tag('tr',
            html_writer::tag('th', get_string('th_username', 'block_xp_leaderboard')) .
            html_writer::tag('th', get_string('th_points', 'block_xp_leaderboard')) .
            html_writer::tag('th', get_string('th_rank', 'block_xp_leaderboard'))
        );
        $table .= html_writer::end_tag('thead');
        $table .= html_writer::tag('tbody', '', ['id' => 'xplb-tbody']); // Filled via AJAX.
        $table .= html_writer::end_tag('table');

        // Container.
        $wrapper  = html_writer::start_div('xplb-wrapper');
        $wrapper .= html_writer::div($courseselect, 'xplb-toolbar');
        $wrapper .= html_writer::div($table, 'xplb-tablewrap xplb-leaderboard-table');
        $wrapper .= html_writer::div(get_string('loading', 'block_xp_leaderboard'), 'xplb-loading', ['id' => 'xplb-loading']);
        $wrapper .= html_writer::end_div();

        $this->content->text = $wrapper;

        return $this->content;
    }

    /**
     * Get courses for dropdown:
     * - Admin: all visible courses (except front page id=1)
     * - Everyone else: courses the user is enrolled in.
     */
    private function get_user_course_options(): array {
        global $DB, $USER;

        $options = [];
        $site = get_site();
        $siteid = $site ? $site->id : 1;

        // Admin sees all courses.
        if (is_siteadmin($USER->id)) {
            $records = $DB->get_records_select('course', 'visible = 1 AND id <> :siteid', ['siteid' => $siteid], 'fullname ASC', 'id, fullname');
        } else {
            // Enrolled courses for user (student/teacher).
            $records = enrol_get_users_courses($USER->id, true, 'id, fullname', 'fullname ASC');
        }

        foreach ($records as $c) {
            $options[$c->id] = format_string($c->fullname);
        }
        return $options;
    }
}
