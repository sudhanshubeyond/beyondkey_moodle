<?php

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {

    $name = 'block_enrollment_chart/graph_color';
    $title = get_string('graph_color', 'block_enrollment_chart');
    $description = get_string('graph_color_desc', 'block_enrollment_chart');
    $default = '#FF8C00';
    
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);
}
