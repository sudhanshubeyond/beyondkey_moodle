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
 * Parent theme: boost
 *
 * @package   theme_edvik
 * @copyright HiBootstrap
 *
 */

// Protects the file from being accessed by a URL directly.
defined('MOODLE_INTERNAL') || die();

$string['choosereadme'] = 'Edvik Education & LMS Moodle Theme';
$string['pluginname'] = 'Edvik';

$string['edvik_settings_menu'] = 'Options';
$string['edvik_page_settings_menu'] = 'Page Settings';

// The name of the second tab in the theme settings.
$string['advancedsettings'] = 'Advanced settings';
// The brand colour setting.
$string['brandcolor'] = 'Brand colour';
// The brand colour setting description.
$string['brandcolor_desc'] = 'The primary colour.';
$string['secondarycolor'] = 'Secondary colour';
$string['secondarycolor_desc'] = 'The secondary colour.';
$string['footer_bg'] = 'Footer background colour';
// A description shown in the admin theme selector.
$string['configtitle'] = 'Edvik settings';
// Name of the first settings tab.
$string['generalsettings'] = 'General settings';
// Preset files setting.
$string['presetfiles'] = 'Additional theme preset files';
// Preset files help text.
$string['presetfiles_desc'] = 'Preset files can be used to dramatically alter the appearance of the theme. See <a href=https://docs.moodle.org/dev/Boost_Presets>Boost presets</a> for information on creating and sharing your own preset files, and see the <a href=http://moodle.net/boost>Presets repository</a> for presets that others have shared.';
// Preset setting.
$string['preset'] = 'Theme preset';
// Preset help text.
$string['preset_desc'] = 'Pick a preset to broadly change the look of the theme.';
// Raw SCSS setting.
$string['rawscss'] = 'Raw SCSS';
// Raw SCSS setting help text.
$string['rawscss_desc'] = 'Use this field to provide SCSS or CSS code which will be injected at the end of the style sheet.';
// Raw initial SCSS setting.
$string['rawscsspre'] = 'Raw initial SCSS';
// Raw initial SCSS setting help text.
$string['rawscsspre_desc'] = 'In this field you can provide initialising SCSS code, it will be injected before everything else. Most of the time you will use this setting to define variables.';
$string['region-side-pre'] = 'Sidebar right';
$string['iconset_edvik'] = 'All Icons';
$string['region-side-pre'] = 'Right';
$string['region-user-notif'] = 'User notifications';
$string['region-user-messages'] = 'User messages';
$string['region-fullwidth-top'] = 'Fullwidth top';
$string['region-fullwidth-bottom'] = 'Fullwidth bottom';
$string['region-above-content'] = 'Above content';
$string['region-below-content'] = 'Below content';
$string['total_student'] = 'Students';
$string['last_updated'] = 'Last Updated ';

// Theme Settings
    $string['logo_settings']    = 'Logo';
    $string['header_logos']     = 'Header Logo';
    $string['logo_visibility']  = 'Logo Visibility';

    $string['main_logo']                = 'Main Logo';
    $string['main_logo_desc']           = 'Your website main logo.';
    $string['logo_image_width']         = 'Main Logo Image Width';
    $string['logo_image_width_desc']    = 'The width in pixels for the main logo. Enter the numerical value only, and do not add "px".';
    $string['logo_image_height']        = 'Main Logo Image Height';
    $string['logo_image_height_desc']   = 'The height in pixels for the main logo. Enter the numerical value only, and do not add "px".';

    $string['hide_banner']        = 'Add your website page link that you want to hide page banner';
    $string['hide_banner_desc']   = 'Enter each link on a new line';
    
    $string['hide_page_bottom_content']        = 'Add your website page link that you want to hide page bottom content';
    $string['hide_page_bottom_content_desc']   = 'Enter each link on a new line. Note: It will not work when Edit mode is on';

    $string['mobile_logo']              = 'Mobile Logo';
    $string['mobile_logo_desc']         = 'Your website mobile logo.';
    $string['mobile_logo_width']        = 'Mobile Logo Image Width';
    $string['mobile_logo_width_desc']   = 'The width in pixels for the mobile logo. Enter the numerical value only, and do not add "px".';
    $string['mobile_logo_height']       = 'Mobile Logo Image Height';
    $string['mobile_logo_height_desc']  = 'The height in pixels for the mobile logo. Enter the numerical value only, and do not add "px".';


    $string['footersettings']           = 'Footer';
    $string['footer_copyright']         = 'Copyright Text';
    $string['footer_logo_sec']          = 'Footer Logo';
    $string['footer_logo_visibility']   = 'Footer Logo Visibility';
    $string['main_footer_logo']         = 'Footer Logo';
    $string['main_footer_logo_desc']    = 'Your website footer logo.';
    $string['footer_logo_width']        = 'Footer Logo Image Width';
    $string['footer_logo_width_desc']   = 'The width in pixels for the footer logo. Enter the numerical value only, and do not add "px".';
    $string['footer_logo_height']       = 'Footer Logo Image Height';
    $string['footer_logo_height_desc']  = 'The height in pixels for the footer logo. Enter the numerical value only, and do not add "px".';

    $string['header_settings']      = 'Header';
    $string['top_header']        = 'Top Header';
    $string['header_search']        = 'Header Search';
    $string['search_placeholder']   = 'Search Placeholder Title';
    $string['header_search_desc']   = 'Settings for the search functionality in the header.';
    $string['header_settings']      = 'Header';
    $string['header_left_btn_text']      = 'Header Button Text';
    $string['header_left_btn_text_desc'] = 'Settings for the header button link text(This link only display when user not logged in).';
    $string['top_header_content']      = 'Top Header Content';
    $string['top_header_content_desc'] = 'Support HTML';
    $string['top_header_right_content']      = 'Top Header Right Content';
    $string['top_header_right_content_desc'] = 'Support HTML';
    $string['header_left_btn_url']       = 'Header Button URL';
    $string['header_left_btn_url_desc']  = 'The link for the header button link. Note: Leave it blank for default login URL';
    $string['header_btn_url']       = 'Header Icon Button URL';
    $string['header_btn_url_desc']  = 'The link for the header button. Note: Leave it blank for default signup URL';

    $string['social_target'] = 'Social URL window target';
    $string['social_target_desc'] = 'Determine whether social URLs should open on the same page or in a new window.';
    $string['social_settings'] = 'Social';
    $string['edvik_facebook_url'] = 'Facebook URL';
    $string['edvik_facebook_url_desc'] = 'The link to your company\'s Facebook profile.';
    $string['edvik_x_url'] = 'X URL';
    $string['edvik_x_url_desc'] = 'The link to your company\'s X profile.';
    $string['edvik_instagram_url'] = 'Instagram URL';
    $string['edvik_instagram_url_desc'] = 'The link to your company\'s Instagram profile.';
    $string['edvik_dribbble_url'] = 'Dribbble URL';
    $string['edvik_dribbble_url_desc'] = 'The link to your company\'s Dribbble profile.';
    $string['edvik_pinterest_url'] = 'Pinterest URL';
    $string['edvik_pinterest_url_desc'] = 'The link to your company\'s Pinterest profile.';
    $string['edvik_google_url'] = 'Google URL';
    $string['edvik_google_url_desc'] = 'The link to your company\'s Google profile.';
    $string['edvik_youtube_url'] = 'YouTube URL';
    $string['edvik_youtube_url_desc'] = 'The link to your company\'s YouTube profile.';
    $string['edvik_vk_url'] = 'VK URL';
    $string['edvik_vk_url_desc'] = 'The link to your company\'s VK profile.';
    $string['edvik_500px_url'] = '500px URL';
    $string['edvik_500px_url_desc'] = 'The link to your company\'s 500px profile.';
    $string['edvik_behance_url'] = 'Behance URL';
    $string['edvik_behance_url_desc'] = 'The link to your company\'s Behance profile.';
    $string['edvik_digg_url'] = 'Digg URL';
    $string['edvik_digg_url_desc'] = 'The link to your company\'s Digg profile.';
    $string['edvik_flickr_url'] = 'Flickr URL';
    $string['edvik_flickr_url_desc'] = 'The link to your company\'s Flickr profile.';
    $string['edvik_foursquare_url'] = 'Foursquare URL';
    $string['edvik_foursquare_url_desc'] = 'The link to your company\'s Foursquare profile.';
    $string['edvik_linkedin_url'] = 'LinkedIn URL';
    $string['edvik_linkedin_url_desc'] = 'The link to your company\'s LinkedIn profile.';
    $string['edvik_medium_url'] = 'Medium URL';
    $string['edvik_medium_url_desc'] = 'The link to your company\'s Medium profile.';
    $string['edvik_meetup_url'] = 'Meetup URL';
    $string['edvik_meetup_url_desc'] = 'The link to your company\'s Meetup profile.';
    $string['edvik_snapchat_url'] = 'Snapchat URL';
    $string['edvik_snapchat_url_desc'] = 'The link to your company\'s Snapchat profile.';
    $string['edvik_tumblr_url'] = 'Tumblr URL';
    $string['edvik_tumblr_url_desc'] = 'The link to your company\'s Tumblr profile.';
    $string['edvik_vimeo_url'] = 'Vimeo URL';
    $string['edvik_vimeo_url_desc'] = 'The link to your company\'s Vimeo profile.';
    $string['edvik_wechat_url'] = 'WeChat URL';
    $string['edvik_wechat_url_desc'] = 'The link to your company\'s WeChat profile.';
    $string['edvik_whatsapp_url'] = 'WhatsApp URL';
    $string['edvik_whatsapp_url_desc'] = 'The link to your company\'s WhatsApp profile.';
    $string['edvik_wordpress_url'] = 'WordPress URL';
    $string['edvik_wordpress_url_desc'] = 'The link to your company\'s WordPress profile.';
    $string['edvik_weibo_url'] = 'Weibo URL';
    $string['edvik_weibo_url_desc'] = 'The link to your company\'s Weibo profile.';
    $string['edvik_telegram_url'] = 'Telegram URL';
    $string['edvik_telegram_url_desc'] = 'The link to your company\'s Telegram profile.';
    $string['edvik_moodle_url'] = 'Moodle URL';
    $string['edvik_moodle_url_desc'] = 'The link to your company\'s Moodle profile.';
    $string['edvik_amazon_url'] = 'Amazon URL';
    $string['edvik_amazon_url_desc'] = 'The link to your company\'s Amazon profile.';
    $string['edvik_slideshare_url'] = 'SlideShare URL';
    $string['edvik_slideshare_url_desc'] = 'The link to your company\'s SlideShare profile.';
    $string['edvik_soundcloud_url'] = 'Soundcloud URL';
    $string['edvik_soundcloud_url_desc'] = 'The link to your company\'s Soundcloud profile.';
    $string['edvik_leanpub_url'] = 'Leanpub URL';
    $string['edvik_leanpub_url_desc'] = 'The link to your company\'s Leanpub profile.';
    $string['edvik_xing_url'] = 'Xing URL';
    $string['edvik_xing_url_desc'] = 'The link to your company\'s Xing profile.';
    $string['edvik_bitcoin_url'] = 'Bitcoin URL';
    $string['edvik_bitcoin_url_desc'] = 'The link to your company\'s Bitcoin profile.';
    $string['edvik_twitch_url'] = 'Twitch URL';
    $string['edvik_twitch_url_desc'] = 'The link to your company\'s Twitch profile.';
    $string['edvik_github_url'] = 'Github URL';
    $string['edvik_github_url_desc'] = 'The link to your company\'s Github profile.';
    $string['edvik_gitlab_url'] = 'Gitlab URL';
    $string['edvik_gitlab_url_desc'] = 'The link to your company\'s Gitlab profile.';
    $string['edvik_forumbee_url'] = 'Forumbee URL';
    $string['edvik_forumbee_url_desc'] = 'The link to your company\'s Forumbee profile.';
    $string['edvik_trello_url'] = 'Trello URL';
    $string['edvik_trello_url_desc'] = 'The link to your company\'s Trello profile.';
    $string['edvik_weixin_url'] = 'Weixin URL';
    $string['edvik_weixin_url_desc'] = 'The link to your company\'s Weixin profile.';
    $string['edvik_slack_url'] = 'Slack URL';
    $string['edvik_slack_url_desc'] = 'The link to your company\'s Slack profile.';

    $string['banner_bg_image']              = 'Banner Shape Image 1';
    $string['banner_bg_image_desc']         = 'Your website banner shape image 1.';

    $string['offcanvas_social_title']              = 'Left Modal Sidebar Social Title';
    $string['offcanvas_social_title_desc']         = 'Your website menu modal social title';

    $string['back_to_top'] = 'Back to Top';
    $string['back_to_top_desc'] = 'Show or hide the back-to-top button on the frontend.';

    $string['hide_global_banner'] = 'Global Banner';
    $string['hide_global_banner_desc'] = 'Show or hide the banner for whole site. If you hide banner for whole site then hide_banner field will not work';

    $string['hide_guest_access_curriculum'] = 'Course Curriculum For Guest Access';
    $string['hide_guest_access_curriculum_desc'] = 'Show or hide curriculum from guest user';

    $string['footer_info'] = 'Footer Info';
    $string['footer_info_desc'] = '';
    $string['footer_col_1'] = 'Footer column 1';
    $string['footer_col_2'] = 'Footer column 2';
    $string['footer_col_3'] = 'Footer column 3';
    $string['footer_col_4'] = 'Footer column 4';
    $string['footer_col_5'] = 'Footer column 5';
    $string['footer_col_title'] = 'Column title';
    $string['footer_col_title_desc'] = 'The title for the footer column.';
    $string['footer_col_body'] = 'Column body';
    $string['footer_col_body_desc'] = 'The body for the footer column. HTML is allowed.';

// End Theme Settings

// Edvik Plugin Constants: Backend
$string['config_title'] = 'Title';
$string['config_top_title'] = 'Top Title';
$string['config_title_desc'] = 'The main title to use for the item.';
$string['config_body'] = 'Body';
$string['config_image_heading'] = 'Images';
$string['config_items'] = 'Items';
$string['config_item'] = 'Item ';
$string['config_number'] = 'Number';
$string['config_number_prefix'] = 'Number Prefix';
$string['config_icon'] = 'Icon';
$string['config_button_link'] = 'Button link';
$string['config_button_text'] = 'Button text';
$string['config_price'] = 'Price';
$string['config_enrol_btn'] = 'Enrol button';
$string['config_enrol_btn_text'] = 'Enrol button text';
$string['select_from_dropdown'] = 'Please select an item from the dropdown below.';
$string['select_from_dropdown_multiple'] = 'Please select multiple items from the dropdown below.(Use Max 2)';
$string['config_group_courses_filter'] = 'Enable filtering';
$string['config_icon_class'] = 'Icon';
$string['config_icon_class_desc'] = 'Select the icon to use for the item.';
$string['config_text'] = 'Text';
$string['config_image'] = 'Image Link';
$string['config_video'] = 'YouTube Video Link';
$string['config_style'] = 'Section Style';
$string['config_class'] = 'Section Class';
$string['config_placeholder'] = 'Placeholder Text';
$string['config_btn'] = 'Button Text';
$string['config_contact_from_code'] = 'Form Code';
$string['course_buy_access'] = 'Paid course entry';
$string['course_enrolled'] = 'You\'re enrolled';
$string['course_enrolled_text'] = 'You are currently enrolled in this course.';
$string['course_enrolled_teacher'] = 'You\'re teaching';
$string['course_enrolled_teacher_text'] = 'You are currently teaching this course.';
$string['course_error_title'] = 'Enrolment Error';
$string['course_error_text'] = 'Your administrator has not yet configured PayPal or Stripe Enrolment for this course.';
$string['course_price'] = 'Price';
$string['course_currency'] = '$';
$string['site_currency'] = 'Enter your site currency';
$string['free_course_price'] = 'Course Free Title';
$string['config_price'] = 'Price Title';
$string['course_enrolment'] = 'Enrol Now';
$string['course_enrolment_free'] = 'Join & Enrol Now';
$string['course_free_access'] = 'Enrolment is Free';
$string['course_free'] = 'Free';
$string['course_students'] = 'Students';
$string['config_alltitle'] = 'All Text';
$string['config_social_heading'] = 'Social Links';
$string['config_link'] = 'Link';
$string['config_top_title'] = 'Top Title';
$string['config_content'] = 'Content';
$string['config_button'] = 'Button Text';
$string['course_total_students'] = 'Total: ';
$string['course_format'] = 'Format: ';
$string['course_total_announcements'] = 'Total Announcements: ';
$string['config_btn_img'] = 'Button Icon Image URL';
$string['config_quote'] = 'Quote';
$string['config_video_title'] = 'Video Title';
$string['config_by_text'] = 'By Text';
$string['config_name_text'] = 'Name Text';
$string['config_name_link'] = 'Name Link';
$string['config_text_items'] = 'Slider Text Item';
$string['config_btn_icon'] = 'Button Icon';
$string['config_bg_img'] = 'Section Background Image URL';
$string['config_student_title'] = 'Total Students Title';
$string['config_bottom_body'] = 'Bottom Content';
$string['config_number_suffix'] = 'Number Suffix';
$string['config_fun_heading'] = 'FunFacts';
$string['config_img'] = 'Image';
$string['config_date'] = 'Date';
$string['config_location'] = 'Location';
// Edvik Plugin Constants: Backend

$string['region-left'] = 'Region Left';
$string['banner_settings'] = 'Banner';
$string['config_subtitle'] = 'Top Title';

$string['favicon'] = 'Favicon';
$string['favicon_desc'] = 'The favicon for the website. Recommended size is 16 x 16px.';