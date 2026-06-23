<?php
defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) { // Only add settings if user can configure site.

    // use the standard section name used by plugininfo/mod
    // ('modsetting' + modulename) ensures the settings page is correctly
    // referenced by admin/settings.php
    $settings = new admin_settingpage(
        'modsettingteamsintegration',
        get_string('pluginname', 'mod_teamsintegration')
    );

    $settings->add(new admin_setting_configtext(
        'mod_teamsintegration/clientid',
        'Client ID',
        '',
        '',
        PARAM_TEXT
    ));

    $settings->add(new admin_setting_configtext(
        'mod_teamsintegration/clientsecret',
        'Client Secret',
        '',
        '',
        PARAM_TEXT
    ));

    $settings->add(new admin_setting_configtext(
        'mod_teamsintegration/tenantid',
        'Tenant ID',
        '',
        '',
        PARAM_TEXT
    ));

    $settings->add(new admin_setting_configtext(
        'mod_teamsintegration/objectid',
        'Object ID',
        '',
        '',
        PARAM_TEXT
    ));

    // the plugininfo loader will add this page automatically;
    // no manual $ADMIN->add() call is needed here.
}
