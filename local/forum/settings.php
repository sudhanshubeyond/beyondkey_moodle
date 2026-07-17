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
 * Settings file.
 *
 * @package   local_forum
 * @copyright 2024 Eduardo Kraus {@link http://eduardokraus.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) {

    global $CFG, $PAGE;

    $settings = new admin_settingpage("local_forum", get_string("pluginname", "local_forum"));

    $ADMIN->add("localplugins", $settings);

    $setting = new admin_setting_configtextarea(
        "local_forum/create_end_points",
        get_string("create_end_points", "local_forum"),
        get_string("create_end_points", "local_forum"),
        'https://genai-woodmontcollege-app.azurewebsites.net/api/StudentGrading/SubmitForumAsync', PARAM_RAW);
    $settings->add($setting);

    $setting = new admin_setting_configtextarea(
        "local_forum/post_end_points",
        get_string("post_end_points", "local_forum"),
        get_string("post_end_points", "local_forum"),
        'https://genai-woodmontcollege-app.azurewebsites.net/api/StudentGrading/GetForumDocumentsSummary', PARAM_RAW);
    $settings->add($setting);

    $setting = new admin_setting_configtext(
        "local_forum/api_keys",
        get_string("api_keys", "local_forum"),
        get_string("api_keys", "local_forum"),
        123456, PARAM_RAW);
    $settings->add($setting);

}
