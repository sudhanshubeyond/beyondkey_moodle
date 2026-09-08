<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.

/**
 * Natural Search plugin settings.
 *
 * @package    local_naturalsearch
 * @copyright  2026
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {

    $settings = new admin_settingpage(
        'local_naturalsearch',
        get_string('pluginname', 'local_naturalsearch')
    );

    /*
     * API URL
     */
    $settings->add(new admin_setting_configtext(
        'local_naturalsearch/apiurl',
        get_string('apiurl', 'local_naturalsearch'),
        get_string('apiurl_desc', 'local_naturalsearch'),
        'https://naturalsearchapi-avdfb5e0h3ffhka8.canadacentral-01.azurewebsites.net/api/courses/search',
        PARAM_URL,
        80
    ));

    /*
     * API Key
     *
     * Keep this empty if the API does not require authentication.
     * If the AI/API team provides an API key, enter it here.
     */
    $settings->add(new admin_setting_configpasswordunmask(
        'local_naturalsearch/apikey',
        get_string('apikey', 'local_naturalsearch'),
        get_string('apikey_desc', 'local_naturalsearch'),
        '',
        PARAM_TEXT
    ));

    /*
     * Request timeout
     */
    $settings->add(new admin_setting_configtext(
        'local_naturalsearch/timeout',
        get_string('timeout', 'local_naturalsearch'),
        get_string('timeout_desc', 'local_naturalsearch'),
        30,
        PARAM_INT,
        10
    ));

    /*
     * Enable / Disable Natural Search
     */
    $settings->add(new admin_setting_configcheckbox(
        'local_naturalsearch/enabled',
        get_string('enabled', 'local_naturalsearch'),
        get_string('enabled_desc', 'local_naturalsearch'),
        1
    ));

    $ADMIN->add('localplugins', $settings);
}