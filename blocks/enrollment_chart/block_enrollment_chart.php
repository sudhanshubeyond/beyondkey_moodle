<?php
defined('MOODLE_INTERNAL') || die();

class block_enrollment_chart extends block_base {

    public function init() {
        $this->title = get_string('pluginname', 'block_enrollment_chart');
    }

    public function applicable_formats() {
        return ['site' => true, 'my' => true, 'course-view' => true];
    }

    public function get_content() {
        global $DB, $PAGE, $OUTPUT, $USER;

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();

        $color = get_config('block_enrollment_chart', 'graph_color');
        $color = !empty($color) ? $color : '#FF8C00';
        $PAGE->requires->js_init_code("
            window.MoodleSettings = {
                graphColor: '$color'
            };
        ");

        // Get user context
        $context = context_user::instance($USER->id);

        // Check if the user is admin, teacher, or student
        if (has_capability('moodle/course:manageactivities', $context)) {
            // Teacher or Admin: Show all courses
            $courseEnrollments = $DB->get_records_sql("
                SELECT c.id, c.fullname, COUNT(ue.id) AS enrollment_count
                FROM {course} c
                JOIN {enrol} e ON e.courseid = c.id
                JOIN {user_enrolments} ue ON ue.enrolid = e.id
                WHERE c.visible = 1
                GROUP BY c.id
                HAVING enrollment_count > 0
                ORDER BY c.fullname
            ");
        } else {
            // Student: Show only enrolled courses
            $courseEnrollments = $DB->get_records_sql("
                SELECT c.id, c.fullname, COUNT(ue.id) AS enrollment_count
                FROM {course} c
                JOIN {enrol} e ON e.courseid = c.id
                JOIN {user_enrolments} ue ON ue.enrolid = e.id
                WHERE c.visible = 1 AND ue.userid = :userid
                GROUP BY c.id
                HAVING enrollment_count > 0
                ORDER BY c.fullname
            ", ['userid' => $USER->id]);
        }

        $courseoptions = [];
        foreach ($courseEnrollments as $course) {
            $courseoptions[] = [
                'id' => $course->id,
                'name' => format_string($course->fullname)
            ];
        }

        $years = [];
        $range = $DB->get_record_sql("SELECT MIN(timecreated) AS mincreated, MAX(timecreated) AS maxcreated FROM {user_enrolments}");
        if (!empty($range->mincreated) && !empty($range->maxcreated)) {
            $startyear = (int) userdate((int)$range->mincreated, '%Y');
            $endyear   = (int) userdate((int)$range->maxcreated, '%Y');
            for ($y = $startyear; $y <= $endyear; $y++) {
                $years[] = $y;
            }
        } else {
            $years[] = (int) userdate(time(), '%Y');
        }
        $initialyear = (int) end($years);

        $initialcourseid = !empty($courseoptions) ? (int)$courseoptions[0]['id'] : 0;

        $data = [
            'years' => array_map(fn($y) => ['value' => $y, 'label' => (string)$y, 'selected' => ($y === $initialyear)], $years),
            'courses' => array_map(fn($o) => ['value' => $o['id'], 'label' => $o['name'], 'selected' => ($o['id'] === $initialcourseid)], $courseoptions),
            'hascourses' => !empty($courseoptions),
            'nocoursesmessage' => get_string('nocourses', 'block_enrollment_chart'),
            'canvasid' => 'enrollment_chart_canvas_' . $this->instance->id,
            'yearselectid' => 'enrollment_chart_year_' . $this->instance->id,
            'courseselectid' => 'enrollment_chart_course_' . $this->instance->id,
        ];

        $this->content->text = $OUTPUT->render_from_template('block_enrollment_chart/block_enrollment_chart', $data);

        $PAGE->requires->js(new moodle_url('https://cdn.jsdelivr.net/npm/chart.js@4.4.0'), true);
        $PAGE->requires->js(new moodle_url('/blocks/enrollment_chart/assets/enrollment.js'));

        $ajaxurl = new moodle_url('/blocks/enrollment_chart/ajax.php');
            $init = [
                'canvasId' => $data['canvasid'],
                'yearSelectId' => $data['yearselectid'],
                'courseSelectId' => $data['courseselectid'],
                'initialYear' => $initialyear,
                'initialCourseId' => $initialcourseid,
                'ajaxUrl' => $ajaxurl->out(false),
                'sesskey' => sesskey(),
            ];

            $PAGE->requires->js_init_code('window.EnrollmentChartInit && window.EnrollmentChartInit(' . json_encode($init) . ');');

            return $this->content;
    }


    public function has_config() {
        return true;
    }
}
