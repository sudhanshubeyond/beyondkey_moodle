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
 * Edvik.
 *
 * @package    theme_edvik
 * @copyright  2023 HiBootstrap
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// This line protects the file from being accessed by a URL directly.
defined('MOODLE_INTERNAL') || die();

/**
 * Load the Jquery and migration files
 * Load the our theme js file
 * @param moodle_page $page.
 */
function theme_edvik_page_init(moodle_page $page) {
    $page->requires->jquery();

    $page->requires->js(
        new moodle_url('/theme/edvik/javascript/naturalsearch.js')
    );
}

function theme_edvik_get_main_scss_content($theme) {
    global $CFG;

    $scss = '';
    $filename = !empty($theme->settings->preset) ? $theme->settings->preset : null;
    $fs = get_file_storage();

    $context = context_system::instance();
    if ($filename == 'default.scss') {
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/default.scss');
    } else if ($filename == 'plain.scss') {
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/plain.scss');
    } else if ($filename && ($presetfile = $fs->get_file($context->id, 'theme_boost', 'preset', 0, '/', $filename))) {
        $scss .= $presetfile->get_content();
    } else {
        // Safety fallback - maybe new installs etc.
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/default.scss');
    }

    // Pre CSS - this is loaded AFTER any prescss from the setting but before the main scss.
    $pre = file_get_contents($CFG->dirroot . '/theme/edvik/scss/pre.scss');
    // Post CSS - this is loaded AFTER the main scss but before the extra scss from the setting.
    $post = file_get_contents($CFG->dirroot . '/theme/edvik/scss/post.scss');

    // Combine them together.
    return $pre . "\n" . $scss . "\n" . $post;

    return $scss;
}

/**
 * Loads the CSS Styles and replace the background images.
 * If background image not available in the settings take the default images.
 *
 * @param string $css
 * @param string $theme
 * @return string $css
 */
function theme_edvik_process_css($css, $theme) {
    global $CFG;

    $tag = '[[hibootstrap:edvik]]';
    $css = str_replace($tag, $CFG->wwwroot . '/theme/edvik', $css);

    $tag = '[[string:edvik_settings_menu]]';
    $css = str_replace($tag, get_string('edvik_settings_menu', 'theme_edvik'), $css);

    $tag = '[[string:edvik_page_settings_menu]]';
    $css = str_replace($tag, get_string('edvik_page_settings_menu', 'theme_edvik'), $css);

    $setting = $theme->settings->brandcolor;
    $tag = '[[setting:brandcolor]]';
    $replacement = $setting;
    if(is_null($replacement)){$replacement = '#2A1DF3';}
    $css = str_replace($tag, $replacement, $css);

    $setting = $theme->settings->secondarycolor;
    $tag = '[[setting:secondarycolor]]';
    $replacement = $setting;
    if(is_null($replacement)){$replacement = '#04D597';}
    $css = str_replace($tag, $replacement, $css);

    $setting = $theme->settings->footer_bg;
    $tag = '[[setting:footer_bg]]';
    $replacement = $setting;
    if(is_null($replacement)){$replacement = '#F5F6F9';}
    $css = str_replace($tag, $replacement, $css);

    return $css;
}


/**
 * Logo Image URL Fetch from theme settings
 *
 * @param string $type
 * @return image $logo
 */
function theme_edvik_get_logo_url($type='header') {
    global $OUTPUT;
    static $theme;
    if (empty($theme)) {
        $theme = theme_config::load('edvik');
    }

    if ($type == "header") {
        $logo = $theme->setting_file_url('logo', 'logo');
        $logo = empty($logo) ? $OUTPUT->image_url('home/logo', 'theme') : $logo;
    }
    return $logo;
}

/**
 * Get theme setting
 *
 * @param string $setting
 * @param bool $format
 * @return string
 */
function theme_edvik_get_setting($setting, $format = false) {
    $theme = theme_config::load('edvik');

    if (empty($theme->settings->$setting)) {
        return false;
    }

    if (!$format) {
        return $theme->settings->$setting;
    }

    if ($format === 'format_text') {
        return format_text($theme->settings->$setting, FORMAT_PLAIN);
    }

    if ($format === 'format_html') {
        return format_text($theme->settings->$setting, FORMAT_HTML, array('trusted' => true, 'noclean' => true));
    }

    return format_string($theme->settings->$setting);
}

/**
 * Outputs the pix url base
 *
 * @return string an URL.
 */
function get_pix_image_url_base() {
    global $CFG;

    return $CFG->wwwroot . "/theme/edvik/pix";
}

/**
 * Serves any files associated with the theme settings.
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options
 * @return bool
 */
function theme_edvik_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    if ($context->contextlevel == CONTEXT_SYSTEM && (
        $filearea === 'main_logo'||
        $filearea === 'mobile_logo'||
        $filearea === 'fn_title_shape_img'||
        $filearea === 'fn_img'||
        $filearea === 'fn_shape_img'||
        $filearea === 'fn_bg_img'||
        $filearea === 'main_footer_logo'||
        $filearea === 'favicon' ||
        
        $filearea === 'banner_bg_image' 
    )) {
        $theme = theme_config::load('edvik');
        // By default, theme files must be cache-able by both browsers and proxies.
        if (!array_key_exists('cacheability', $options)) {
            $options['cacheability'] = 'public';
        }
        return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
    } else {
        send_file_not_found();
    }
}