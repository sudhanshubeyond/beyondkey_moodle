<?php
namespace mod_teamsintegration\output;
defined('MOODLE_INTERNAL') || die();
use plugin_renderer_base;
class renderer extends plugin_renderer_base {
    public function render_meeting($meeting) {
        return html_writer::link($meeting->joinurl, $meeting->name);
    }
}
