<?php
/*
@edvikRef: @block_edvik/block.php
*/

defined('MOODLE_INTERNAL') || die();

// print_object($this);
$edvikBlockType = $this->instance->blockname;

$edvikCollectionFullwidthTop =  array(
    "edvik_banner_1",
    "edvik_banner_3",
    "edvik_categories",
    "edvik_course_filter",
    "edvik_about_area",
    "edvik_about_area_two",
    "edvik_about_area_three",
    "edvik_funfacts",
    "edvik_features_area",
    "edvik_video_area",
    "edvik_partner",
    "edvik_features_area_two",
    "edvik_testimonial",
    "edvik_events",
    "edvik_blog_area",
    "edvik_blog_area_2",
    "edvik_newsletter",
    "edvik_banner_2",
    "edvik_features_area_three",
    "edvik_course_filter_2",
    "edvik_skills_area",
    "edvik_video_area2",
    "edvik_become_instructor",
    "edvik_faq",
    "edvik_promo_area",
    "edvik_categories_2",
    "edvik_course_filter_3",
    "edvik_partner_2",
    "edvik_cards_area",
    "edvik_numbers",
    "edvik_about_banner",
    "edvik_events_2",
    "edvik_instructor",
);

$edvikCollectionAboveContent =  array(
    "edvik_contact_form",
    "edvik_course_desc",
);

$edvikCollectionBelowContent =  array(
    "edvik_course_rating",
    "edvik_more_courses",
    "edvik_course_instructor",
);

$edvikCollection = array_merge($edvikCollectionFullwidthTop, $edvikCollectionAboveContent, $edvikCollectionBelowContent);

if (empty($this->config)) {
    if(in_array($edvikBlockType, $edvikCollectionFullwidthTop)) {
        $this->instance->defaultregion = 'fullwidth-top';
        $this->instance->region = 'fullwidth-top';
        $DB->update_record('block_instances', $this->instance);
    }
    if(in_array($edvikBlockType, $edvikCollectionAboveContent)) {
        $this->instance->defaultregion = 'above-content';
        $this->instance->region = 'above-content';
        $DB->update_record('block_instances', $this->instance);
    }
    if(in_array($edvikBlockType, $edvikCollectionBelowContent)) {
        $this->instance->defaultregion = 'below-content';
        $this->instance->region = 'below-content';
        $DB->update_record('block_instances', $this->instance);
    }
}