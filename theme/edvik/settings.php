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
 * edvik.
 *
 * @package    theme_edvik
 * @copyright  2023 HiBootstrap
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// This line protects the file from being accessed by a URL directly.
defined('MOODLE_INTERNAL') || die();

$edvikFontList = include($CFG->dirroot . '/theme/edvik/inc/font_handler/edvik_font_select.php');

// This is used for performance, we don't need to know about these settings on every page in Moodle, only when
// we are looking at the admin settings pages.
if ($ADMIN->fulltree) {

    // Boost provides a nice setting page which splits settings onto separate tabs. We want to use it here.
    $settings = new theme_boost_admin_settingspage_tabs('themesettingedvik', get_string('configtitle', 'theme_edvik'));

    /*
    * ----------------------
    * General setting
    * ----------------------
    */
    $page = new admin_settingpage('theme_edvik_general', get_string('generalsettings', 'theme_edvik'));

        // Back to Top
        $setting = new admin_setting_configselect('theme_edvik/back_to_top', get_string('back_to_top', 'theme_edvik') , get_string('back_to_top_desc', 'theme_edvik') , null, array(
            '0' => 'Visible',
            '1' => 'Hidden'
        ));
        $page->add($setting);

        // Favicon
        $name='theme_edvik/favicon';
        $title = get_string('favicon', 'theme_edvik');
        $description = get_string('favicon_desc', 'theme_edvik');
        $setting = new admin_setting_configstoredfile($name, $title, $description, 'favicon');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Preset files setting.
        $name = 'theme_edvik/preset';
        $title = get_string('preset', 'theme_edvik');
        $description = get_string('preset_desc', 'theme_edvik');
        $default = 'default.scss';

        $context = context_system::instance();
        $fs = get_file_storage();
        $files = $fs->get_area_files($context->id, 'theme_edvik', 'preset', 0, 'itemid, filepath, filename', false);

        $choices = [];
        foreach ($files as $file) {
            $choices[$file->get_filename()] = $file->get_filename();
        }
        // These are the built in presets from Boost.
        $choices['default.scss'] = 'default.scss';
        $choices['plain.scss'] = 'plain.scss';

        $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Preset files setting.
        $name = 'theme_edvik/presetfiles';
        $title = get_string('presetfiles','theme_edvik');
        $description = get_string('presetfiles_desc', 'theme_edvik');

        $setting = new admin_setting_configstoredfile($name, $title, $description, 'preset', 0,
            array('maxfiles' => 20, 'accepted_types' => array('.scss')));
        $page->add($setting);

        // Primary Color 
        $name = 'theme_edvik/brandcolor';
        $title = get_string('brandcolor', 'theme_edvik');
        $description = get_string('brandcolor_desc', 'theme_edvik');
        $default = '#2A1DF3';
        $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Secondary Color 
        $name = 'theme_edvik/secondarycolor';
        $title = get_string('secondarycolor', 'theme_edvik');
        $description = get_string('secondarycolor_desc', 'theme_edvik');
        $default = '#04D597';
        $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Footer Color 
        $name = 'theme_edvik/footer_bg';
        $title = get_string('footer_bg', 'theme_edvik');
        $default = '#F5F6F9';
        $setting = new admin_setting_configcolourpicker($name, $title, '', $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Hide Course Curriculum For Guest Access
        $setting = new admin_setting_configselect('theme_edvik/hide_guest_access_curriculum', get_string('hide_guest_access_curriculum', 'theme_edvik') , get_string('hide_guest_access_curriculum_desc', 'theme_edvik') , null, array(
            '0' => 'Visible',
            '1' => 'Hidden'
        ));
        $page->add($setting);

        // Free Course Price
        $name = 'theme_edvik/free_course_price';
        $title = get_string('free_course_price', 'theme_edvik');
        $setting = new admin_setting_configtext($name, $title, '', '');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Site Currency
        $name = 'theme_edvik/site_currency';
        $title = get_string('site_currency', 'theme_edvik');
        $setting = new admin_setting_configtext($name, $title, '', '');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        $name = 'theme_edvik/hide_banner';
        $title = get_string('hide_banner', 'theme_edvik');
        $description = get_string('hide_banner_desc', 'theme_edvik');
        $setting = new admin_setting_configtextarea ($name, $title, $description, '');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Global Banner
        $setting = new admin_setting_configselect('theme_edvik/hide_global_banner', get_string('hide_global_banner', 'theme_edvik') , get_string('hide_global_banner_desc', 'theme_edvik') , null, array(
            '0' => 'Visible',
            '1' => 'Hidden'
        ));
        $page->add($setting);
        
        $name = 'theme_edvik/hide_page_bottom_content';
        $title = get_string('hide_page_bottom_content', 'theme_edvik');
        $description = get_string('hide_page_bottom_content_desc', 'theme_edvik');
        $setting = new admin_setting_configtextarea ($name, $title, $description, '');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

    $settings->add($page);

    /*
    * ----------------------
    * Logo settings
    * ----------------------
    */
    $page = new admin_settingpage('theme_edvik_logo', get_string('logo_settings', 'theme_edvik'));

        // Header logos
        $page->add(new admin_setting_heading('theme_edvik/header_logos', get_string('header_logos', 'theme_edvik'), NULL));

        // Logotype
        $setting = new admin_setting_configselect('theme_edvik/logo_visibility',
            get_string('logo_visibility', 'theme_edvik'), '', null,
            array(
                '0' => 'Visible',
                '1' => 'Hidden'
            ));
        $page->add($setting);

        // Main Logo
        $name='theme_edvik/main_logo';
        $title = get_string('main_logo', 'theme_edvik');
        $description = get_string('main_logo_desc', 'theme_edvik');
        $setting = new admin_setting_configstoredfile($name, $title, $description, 'main_logo');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Logo Width
        $setting = new admin_setting_configtext('theme_edvik/logo_image_width', get_string('logo_image_width','theme_edvik'), get_string('logo_image_width_desc', 'theme_edvik'), '', PARAM_NOTAGS, 50);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Logo Height
        $setting = new admin_setting_configtext('theme_edvik/logo_image_height', get_string('logo_image_height','theme_edvik'), get_string('logo_image_height_desc', 'theme_edvik'), '', PARAM_NOTAGS, 50);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Mobile Logo
        $name='theme_edvik/mobile_logo';
        $title = get_string('mobile_logo', 'theme_edvik');
        $description = get_string('mobile_logo_desc', 'theme_edvik');
        $setting = new admin_setting_configstoredfile($name, $title, $description, 'mobile_logo');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Mobile Logo Width
        $setting = new admin_setting_configtext('theme_edvik/mobile_logo_width', get_string('mobile_logo_width','theme_edvik'), get_string('mobile_logo_width_desc', 'theme_edvik'), '', PARAM_NOTAGS, 50);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Logo Height
        $setting = new admin_setting_configtext('theme_edvik/mobile_logo_height', get_string('mobile_logo_height','theme_edvik'), get_string('mobile_logo_height_desc', 'theme_edvik'), '', PARAM_NOTAGS, 50);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Footer logo
        $page->add(new admin_setting_heading('theme_edvik/footer_logo_sec', get_string('footer_logo_sec', 'theme_edvik'), NULL));

        // Logotype
        $setting = new admin_setting_configselect('theme_edvik/footer_logo_visibility',
            get_string('footer_logo_visibility', 'theme_edvik'), '', null,
            array(
                '0' => 'Visible',
                '1' => 'Hidden'
            ));
        $page->add($setting);

        // Footer  Logo
        $name='theme_edvik/main_footer_logo';
        $title = get_string('main_footer_logo', 'theme_edvik');
        $description = get_string('main_footer_logo_desc', 'theme_edvik');
        $setting = new admin_setting_configstoredfile($name, $title, $description, 'main_footer_logo');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Logo Width
        $setting = new admin_setting_configtext('theme_edvik/footer_logo_width', get_string('footer_logo_width','theme_edvik'), get_string('footer_logo_width_desc', 'theme_edvik'), '', PARAM_NOTAGS, 50);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Logo Height
        $setting = new admin_setting_configtext('theme_edvik/footer_logo_height', get_string('footer_logo_height','theme_edvik'), get_string('footer_logo_height_desc', 'theme_edvik'), '', PARAM_NOTAGS, 50);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

    $settings->add($page);

    /*
    * ----------------------
    * Header settings tab
    * ----------------------
    */
    $page = new admin_settingpage('theme_edvik_header', get_string('header_settings', 'theme_edvik'));

        $name = 'theme_edvik/enable_navbar_style_two';
        $title = 'Add your website page link that you want to make Navbar and Footer Style Two';
        $description = 'Enter each link on a new line';
        $setting = new admin_setting_configtextarea ($name, $title, $description, '');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Top Header
        $setting = new admin_setting_configselect('theme_edvik/top_header', get_string('top_header', 'theme_edvik'), '', null,
            array(
                '1' => 'Show',
                '0' => 'Hide'
            ));
        $page->add($setting);

        // Top Header Content
        $name = 'theme_edvik/top_header_content';
        $title = get_string('top_header_content', 'theme_edvik');
        $default = 'Our live podcast is here to learn more. Listen and learn to work, lead, and live better.<a href="#" class="link style-three">Learn More<i class="ri-arrow-right-line"></i></a>';
        $description = '';
        $setting = new admin_setting_configtextarea($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Top Header Content
        $name = 'theme_edvik/top_header_right_content';
        $title = get_string('top_header_right_content', 'theme_edvik');
        $default = '<a href="#">Become An Instructor</a>';
        $description = '';
        $setting = new admin_setting_configtextarea($name, $title, $description, $default, PARAM_RAW);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Category Title
        $name = 'theme_edvik/category_title';
        $title = 'Category Title';
        $default = 'Category';
        $description = '';
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Category Content
        $name = 'theme_edvik/category_content';
        $title = 'Category Content';
        $default = '
            <li><a href="#"><i class="ri-arrow-right-line"></i>Web Development</a></li>
            <li><a href="#"><i class="ri-arrow-right-line"></i>Digital Marketing</a></li>';
        $description = '';
        $setting = new admin_setting_configtextarea($name, $title, $description, $default, PARAM_RAW);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Search
        $setting = new admin_setting_configselect('theme_edvik/header_search', get_string('header_search', 'theme_edvik'),
        get_string('header_search_desc', 'theme_edvik'), null,
            array(
                '1' => 'Show',
                '0' => 'Hide'
            ));
        $page->add($setting);

        // Navbar Search Placeholder Title.
        $name = 'theme_edvik/search_placeholder';
        $title = get_string('search_placeholder', 'theme_edvik');
        $default = 'Search for anything';
        $description = '';
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Left Button Text
        $setting = new admin_setting_configtext('theme_edvik/header_left_btn_text', get_string('header_left_btn_text','theme_edvik'), get_string('header_left_btn_text_desc', 'theme_edvik'), 'Get Started', PARAM_NOTAGS, 50);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Left Button URL
        $setting = new admin_setting_configtext('theme_edvik/header_left_btn_url', get_string('header_left_btn_url','theme_edvik'), get_string('header_left_btn_url_desc', 'theme_edvik'), '', PARAM_NOTAGS, 50);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Button URL
        $setting = new admin_setting_configtext('theme_edvik/header_btn_url', get_string('header_btn_url','theme_edvik'), get_string('header_btn_url_desc', 'theme_edvik'), '', PARAM_NOTAGS, 50);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

    $settings->add($page);

    /*
    * ----------------------
    * Banner settings tab
    * ----------------------
    */
    $page = new admin_settingpage('theme_edvik_banner', get_string('banner_settings', 'theme_edvik'));

        // Banner Shape Image 1
        $name='theme_edvik/banner_bg_image';
        $title = get_string('banner_bg_image', 'theme_edvik');
        $description = get_string('banner_bg_image_desc', 'theme_edvik');
        $setting = new admin_setting_configstoredfile($name, $title, $description, 'banner_bg_image');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
    
    $settings->add($page);

    // Social settings
    $page = new admin_settingpage('theme_edvik_social_settings', get_string('social_settings', 'theme_edvik'));

        // New Window
        $setting = new admin_setting_configselect('theme_edvik/social_target', get_string('social_target', 'theme_edvik') , get_string('social_target_desc', 'theme_edvik') , null, array(
            '0' => 'Open URLs in the same page',
            '1' => 'Open URLs in a new window'
        ));
        $page->add($setting);

        // Facebook URL
        $setting = new admin_setting_configtext('theme_edvik/edvik_facebook_url', get_string('edvik_facebook_url', 'theme_edvik') , get_string('edvik_facebook_url_desc', 'theme_edvik') , '#', PARAM_NOTAGS, 50);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
        // X URL
        $setting = new admin_setting_configtext('theme_edvik/edvik_x_url', get_string('edvik_x_url', 'theme_edvik') , get_string('edvik_x_url_desc', 'theme_edvik') , '#', PARAM_NOTAGS, 50);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
        // Instagram URL
        $setting = new admin_setting_configtext('theme_edvik/edvik_instagram_url', get_string('edvik_instagram_url', 'theme_edvik') , get_string('edvik_instagram_url_desc', 'theme_edvik') , '#', PARAM_NOTAGS, 50);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
        // Pinterest URL
        $setting = new admin_setting_configtext('theme_edvik/edvik_pinterest_url', get_string('edvik_pinterest_url', 'theme_edvik') , get_string('edvik_pinterest_url_desc', 'theme_edvik') , '#', PARAM_NOTAGS, 50);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
        // Dribbble URL
        $setting = new admin_setting_configtext('theme_edvik/edvik_dribbble_url', get_string('edvik_dribbble_url', 'theme_edvik') , get_string('edvik_dribbble_url_desc', 'theme_edvik') , '#', PARAM_NOTAGS, 50);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
        // Google URL
        $setting = new admin_setting_configtext('theme_edvik/edvik_google_url', get_string('edvik_google_url', 'theme_edvik') , get_string('edvik_google_url_desc', 'theme_edvik') , '#', PARAM_NOTAGS, 50);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
        // YouTube URL
        $setting = new admin_setting_configtext('theme_edvik/edvik_youtube_url', get_string('edvik_youtube_url', 'theme_edvik') , get_string('edvik_youtube_url_desc', 'theme_edvik') , null, PARAM_NOTAGS, 50);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
        // VK URL
        $setting = new admin_setting_configtext('theme_edvik/edvik_vk_url', get_string('edvik_vk_url', 'theme_edvik') , get_string('edvik_vk_url_desc', 'theme_edvik') , null, PARAM_NOTAGS, 50);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
        // 500px URL
        $setting = new admin_setting_configtext('theme_edvik/edvik_500px_url', get_string('edvik_500px_url', 'theme_edvik') , get_string('edvik_500px_url_desc', 'theme_edvik') , null, PARAM_NOTAGS, 50);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
        // Behance URL
        $setting = new admin_setting_configtext('theme_edvik/edvik_behance_url', get_string('edvik_behance_url', 'theme_edvik') , get_string('edvik_behance_url_desc', 'theme_edvik') , null, PARAM_NOTAGS, 50);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
        // Digg URL
        $setting = new admin_setting_configtext('theme_edvik/edvik_digg_url', get_string('edvik_digg_url', 'theme_edvik') , get_string('edvik_digg_url_desc', 'theme_edvik') , null, PARAM_NOTAGS, 50);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
        // Flickr URL
        $setting = new admin_setting_configtext('theme_edvik/edvik_flickr_url', get_string('edvik_flickr_url', 'theme_edvik') , get_string('edvik_flickr_url_desc', 'theme_edvik') , null, PARAM_NOTAGS, 50);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
        // Foursquare URL
        $setting = new admin_setting_configtext('theme_edvik/edvik_foursquare_url', get_string('edvik_foursquare_url', 'theme_edvik') , get_string('edvik_foursquare_url_desc', 'theme_edvik') , null, PARAM_NOTAGS, 50);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
        // LinkedIn URL
        $setting = new admin_setting_configtext('theme_edvik/edvik_linkedin_url', get_string('edvik_linkedin_url', 'theme_edvik') , get_string('edvik_linkedin_url_desc', 'theme_edvik') , null, PARAM_NOTAGS, 50);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
        // Medium URL
        $setting = new admin_setting_configtext('theme_edvik/edvik_medium_url', get_string('edvik_medium_url', 'theme_edvik') , get_string('edvik_medium_url_desc', 'theme_edvik') , null, PARAM_NOTAGS, 50);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
        // Meetup URL
        $setting = new admin_setting_configtext('theme_edvik/edvik_meetup_url', get_string('edvik_meetup_url', 'theme_edvik') , get_string('edvik_meetup_url_desc', 'theme_edvik') , null, PARAM_NOTAGS, 50);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
        // Snapchat URL
        $setting = new admin_setting_configtext('theme_edvik/edvik_snapchat_url', get_string('edvik_snapchat_url', 'theme_edvik') , get_string('edvik_snapchat_url_desc', 'theme_edvik') , null, PARAM_NOTAGS, 50);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
        // Tumblr URL
        $setting = new admin_setting_configtext('theme_edvik/edvik_tumblr_url', get_string('edvik_tumblr_url', 'theme_edvik') , get_string('edvik_tumblr_url_desc', 'theme_edvik') , null, PARAM_NOTAGS, 50);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
        // Vimeo URL
        $setting = new admin_setting_configtext('theme_edvik/edvik_vimeo_url', get_string('edvik_vimeo_url', 'theme_edvik') , get_string('edvik_vimeo_url_desc', 'theme_edvik') , null, PARAM_NOTAGS, 50);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
        // WeChat URL
        $setting = new admin_setting_configtext('theme_edvik/edvik_wechat_url', get_string('edvik_wechat_url', 'theme_edvik') , get_string('edvik_wechat_url_desc', 'theme_edvik') , null, PARAM_NOTAGS, 50);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
        // WhatsApp URL
        $setting = new admin_setting_configtext('theme_edvik/edvik_whatsapp_url', get_string('edvik_whatsapp_url', 'theme_edvik') , get_string('edvik_whatsapp_url_desc', 'theme_edvik') , null, PARAM_NOTAGS, 50);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
        // WordPress URL
        $setting = new admin_setting_configtext('theme_edvik/edvik_wordpress_url', get_string('edvik_wordpress_url', 'theme_edvik') , get_string('edvik_wordpress_url_desc', 'theme_edvik') , null, PARAM_NOTAGS, 50);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
        // Weibo URL
        $setting = new admin_setting_configtext('theme_edvik/edvik_weibo_url', get_string('edvik_weibo_url', 'theme_edvik') , get_string('edvik_weibo_url_desc', 'theme_edvik') , null, PARAM_NOTAGS, 50);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
        // Telegram URL
        $setting = new admin_setting_configtext('theme_edvik/edvik_telegram_url', get_string('edvik_telegram_url', 'theme_edvik') , get_string('edvik_telegram_url_desc', 'theme_edvik') , null, PARAM_NOTAGS, 50);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
        // Moodle URL
        $setting = new admin_setting_configtext('theme_edvik/edvik_moodle_url', get_string('edvik_moodle_url', 'theme_edvik') , get_string('edvik_moodle_url_desc', 'theme_edvik') , null, PARAM_NOTAGS, 50);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
        // Amazon URL
        $setting = new admin_setting_configtext('theme_edvik/edvik_amazon_url', get_string('edvik_amazon_url', 'theme_edvik') , get_string('edvik_amazon_url_desc', 'theme_edvik') , null, PARAM_NOTAGS, 50);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
        // Slideshare URL
        $setting = new admin_setting_configtext('theme_edvik/edvik_slideshare_url', get_string('edvik_slideshare_url', 'theme_edvik') , get_string('edvik_slideshare_url_desc', 'theme_edvik') , null, PARAM_NOTAGS, 50);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
        // SoundCloud URL
        $setting = new admin_setting_configtext('theme_edvik/edvik_soundcloud_url', get_string('edvik_soundcloud_url', 'theme_edvik') , get_string('edvik_soundcloud_url_desc', 'theme_edvik') , null, PARAM_NOTAGS, 50);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
        // LeanPub URL
        $setting = new admin_setting_configtext('theme_edvik/edvik_leanpub_url', get_string('edvik_leanpub_url', 'theme_edvik') , get_string('edvik_leanpub_url_desc', 'theme_edvik') , null, PARAM_NOTAGS, 50);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
        // Xing URL
        $setting = new admin_setting_configtext('theme_edvik/edvik_xing_url', get_string('edvik_xing_url', 'theme_edvik') , get_string('edvik_xing_url_desc', 'theme_edvik') , null, PARAM_NOTAGS, 50);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
        // Bitcoin URL
        $setting = new admin_setting_configtext('theme_edvik/edvik_bitcoin_url', get_string('edvik_bitcoin_url', 'theme_edvik') , get_string('edvik_bitcoin_url_desc', 'theme_edvik') , null, PARAM_NOTAGS, 50);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
        // Twitch URL
        $setting = new admin_setting_configtext('theme_edvik/edvik_twitch_url', get_string('edvik_twitch_url', 'theme_edvik') , get_string('edvik_twitch_url_desc', 'theme_edvik') , null, PARAM_NOTAGS, 50);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
        // Github URL
        $setting = new admin_setting_configtext('theme_edvik/edvik_github_url', get_string('edvik_github_url', 'theme_edvik') , get_string('edvik_github_url_desc', 'theme_edvik') , null, PARAM_NOTAGS, 50);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
        // Gitlab URL
        $setting = new admin_setting_configtext('theme_edvik/edvik_gitlab_url', get_string('edvik_gitlab_url', 'theme_edvik') , get_string('edvik_gitlab_url_desc', 'theme_edvik') , null, PARAM_NOTAGS, 50);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
        // Forumbee URL
        $setting = new admin_setting_configtext('theme_edvik/edvik_forumbee_url', get_string('edvik_forumbee_url', 'theme_edvik') , get_string('edvik_forumbee_url_desc', 'theme_edvik') , null, PARAM_NOTAGS, 50);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
        // Trello URL
        $setting = new admin_setting_configtext('theme_edvik/edvik_trello_url', get_string('edvik_trello_url', 'theme_edvik') , get_string('edvik_trello_url_desc', 'theme_edvik') , null, PARAM_NOTAGS, 50);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
        // Weixin URL
        $setting = new admin_setting_configtext('theme_edvik/edvik_weixin_url', get_string('edvik_weixin_url', 'theme_edvik') , get_string('edvik_weixin_url_desc', 'theme_edvik') , null, PARAM_NOTAGS, 50);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
        // Slack URL
        $setting = new admin_setting_configtext('theme_edvik/edvik_slack_url', get_string('edvik_slack_url', 'theme_edvik') , get_string('edvik_slack_url_desc', 'theme_edvik') , null, PARAM_NOTAGS, 50);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

    $settings->add($page);

    /*
    * --------------------
    * Footer settings tab
    * --------------------
    */
    $page = new admin_settingpage('theme_edvik_footer', get_string('footersettings', 'theme_edvik'));

    // Footer column 1
    $page->add(new admin_setting_heading('theme_edvik/footer_news', 'Footer Newslatter' , NULL));

        // Newslatter
        $setting = new admin_setting_configselect('theme_edvik/fNewslatter',
        'Newslatter Section', '', null,
        array(
            '1' => 'Visible',
            '0' => 'Hidden'
        ));
        $page->add($setting);

        // Newslatter Top Title
        $setting = new admin_setting_configtext('theme_edvik/fn_top_title', 'Top Title' , '', 'SUBSCRIBE NEWSLETTER', PARAM_RAW);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Newslatter Title
        $setting = new admin_setting_configtext('theme_edvik/fn_title', 'Title' , '', 'Subscribe now to our Newsletter', PARAM_RAW);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Newslatter Content
        $setting = new admin_setting_configtextarea('theme_edvik/fn_content', 'Content', '' , 'Explore all of our courses and pick your suitable ones to enroll and start learning.', PARAM_RAW);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Newslatter Action URL
        $setting = new admin_setting_configtext('theme_edvik/fn_action_url', 'Mailchimp Action URL' , '', '#', PARAM_RAW);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Newslatter Placeholder
        $setting = new admin_setting_configtext('theme_edvik/fn_placeholder', 'Placeholder Text' , '', 'Enter your email address', PARAM_RAW);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Newslatter Button
        $setting = new admin_setting_configtext('theme_edvik/fn_btn', 'Button Text' , '', 'Subscribe Now', PARAM_RAW);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Image
        $name='theme_edvik/fn_img';
        $title = 'Section Image';
        $description = '';
        $setting = new admin_setting_configstoredfile($name, $title, $description, 'fn_img');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Shape Image
        $name='theme_edvik/fn_shape_img';
        $title = 'Section Shape Image';
        $description = '';
        $setting = new admin_setting_configstoredfile($name, $title, $description, 'fn_shape_img');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Title Shape Image
        $name='theme_edvik/fn_title_shape_img';
        $title = 'Section Title Shape Image';
        $description = '';
        $setting = new admin_setting_configstoredfile($name, $title, $description, 'fn_title_shape_img');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Backgorund Shape Image
        $name='theme_edvik/fn_bg_img';
        $title = 'Section Background Image';
        $description = '';
        $setting = new admin_setting_configstoredfile($name, $title, $description, 'fn_bg_img');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

     // Footer Content
     $setting = new admin_setting_configtextarea('theme_edvik/footer_info', get_string('footer_info', 'theme_edvik') , get_string('footer_info_desc', 'theme_edvik') , 'Explore all of our courses and pick your suitable ones to enroll and start learning.', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Footer column 1
    $page->add(new admin_setting_heading('theme_edvik/footer_col_1', get_string('footer_col_1', 'theme_edvik') , NULL));
    // Footer column title
    $setting = new admin_setting_configtext('theme_edvik/footer_col_1_title', get_string('footer_col_title', 'theme_edvik') , get_string('footer_col_title_desc', 'theme_edvik') , 'Our Company', PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Footer column body
    $setting = new admin_setting_configtextarea('theme_edvik/footer_col_1_body', get_string('footer_col_body', 'theme_edvik') , get_string('footer_col_body_desc', 'theme_edvik') , '<ul class="footer-menu list-unstyle">
        <li><a href="#">About Us</a></li>
        <li><a href="#">Contact Us</a></li>
        <li><a href="#">Shop</a></li>
        <li><a href="#">Upcoming Events</a></li>
        <li><a href="#">Our Instructors</a></li>
    </ul>', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Footer column 2
    $page->add(new admin_setting_heading('theme_edvik/footer_col_2', get_string('footer_col_2', 'theme_edvik') , NULL));
    // Footer column title
    $setting = new admin_setting_configtext('theme_edvik/footer_col_2_title', get_string('footer_col_title', 'theme_edvik') , get_string('footer_col_title_desc', 'theme_edvik') , 'Popular Courses', PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Footer column body
    $setting = new admin_setting_configtextarea('theme_edvik/footer_col_2_body', get_string('footer_col_body', 'theme_edvik') , get_string('footer_col_body_desc', 'theme_edvik') , 
        '<ul class="footer-menu list-unstyle">
            <li><a href="#">HTML</a></li>
            <li><a href="#">Machine Learning</a></li>
            <li><a href="#">Design</a></li>
            <li><a href="#">Development</a></li>
            <li><a href="#">Video Editing</a></li>
        </ul>', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Footer column 3
    $page->add(new admin_setting_heading('theme_edvik/footer_col_3', get_string('footer_col_3', 'theme_edvik') , NULL));
    // Footer column title
    $setting = new admin_setting_configtext('theme_edvik/footer_col_3_title', get_string('footer_col_title', 'theme_edvik') , get_string('footer_col_title_desc', 'theme_edvik') , 'Tracks', PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Footer column body
    $setting = new admin_setting_configtextarea('theme_edvik/footer_col_3_body', get_string('footer_col_body', 'theme_edvik') , get_string('footer_col_body_desc', 'theme_edvik') , 
        '<ul class="footer-menu list-unstyle">
            <li><a href="#">Web Development</a></li>
            <li><a href="#">Web Design</a></li>
            <li><a href="#">Health &amp; Food</a></li>
            <li><a href="#">Business</a></li>
            <li><a href="#">Life Skills</a></li>
        </ul>', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Footer column 4
    $page->add(new admin_setting_heading('theme_edvik/footer_col_4', get_string('footer_col_4', 'theme_edvik') , NULL));
    // Footer column title
    $setting = new admin_setting_configtext('theme_edvik/footer_col_4_title', get_string('footer_col_title', 'theme_edvik') , get_string('footer_col_title_desc', 'theme_edvik') , 'Download App', PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Footer column body
    $setting = new admin_setting_configtextarea('theme_edvik/footer_col_4_body', get_string('footer_col_body', 'theme_edvik') , get_string('footer_col_body_desc', 'theme_edvik'), 
        '<p class="text-optional mb-25">Download Edvik mobile app for better learning experience.</p>
        <div class="app-btn">
            <a href="https://play.google.com/store/games?hl=en&amp;gl=US&amp;pli=1" target="_blank"><img src="https://themes.hibootstrap.com/tools/assets/google-play.svg" alt="Google Play Store">GET IT ON <span>Google Play</span></a>
            <a href="https://www.apple.com/app-store/" target="_blank"><img src="https://themes.hibootstrap.com/tools/assets/apple.svg" alt="Apple Store">GET IT ON <span>Apple Store</span></a>
        </div>', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Footer column 5
    $page->add(new admin_setting_heading('theme_edvik/footer_col_5', get_string('footer_col_5', 'theme_edvik') , NULL));
    // Footer column title
    $setting = new admin_setting_configtext('theme_edvik/footer_col_5_title', get_string('footer_col_title', 'theme_edvik') , get_string('footer_col_title_desc', 'theme_edvik') , '', PARAM_NOTAGS, 50);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Footer column body
    $setting = new admin_setting_configtextarea('theme_edvik/footer_col_5_body', get_string('footer_col_body', 'theme_edvik') , get_string('footer_col_body_desc', 'theme_edvik') , '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Footer Copyright Text
    $name = 'theme_edvik/footer_copyright';
    $title = get_string('footer_copyright', 'theme_edvik');
    $description = '';
    $setting = new admin_setting_configtextarea ($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $settings->add($page);

    /*
    * ----------------------
    * Advanced settings tab
    * ----------------------
    */
    $page = new admin_settingpage('theme_edvik_advanced', get_string('advancedsettings', 'theme_edvik'));

    // Raw SCSS to include before the content.
    $setting = new admin_setting_configtextarea('theme_edvik/scsspre',
        get_string('rawscsspre', 'theme_edvik'), get_string('rawscsspre_desc', 'theme_edvik'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Raw SCSS to include after the content.
    $setting = new admin_setting_configtextarea('theme_edvik/scss', get_string('rawscss', 'theme_edvik'), get_string('rawscss_desc', 'theme_edvik'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $settings->add($page);
}