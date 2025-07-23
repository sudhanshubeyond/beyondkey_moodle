<?php
defined('MOODLE_INTERNAL') || die();

$edvik_globalsearch_navbar = '';

$placeholder    = get_config('theme_edvik', 'search_placeholder');

// if(class_exists('filter_multilang2')){
//     $filter = new filter_multilang2();
//     $placeholder = $filter->filter($placeholder);
// }

if (\core_search\manager::is_global_search_enabled() === false) {
    $placeholder = get_string('globalsearchdisabled', 'search');
}

$url = new moodle_url('/search/index.php');

$edvik_globalsearch_navbar .= html_writer::start_tag('form', array('class' => 'position-relative','action' => $url->out()));
$edvik_globalsearch_navbar .= html_writer::start_tag('fieldset');

// Input.
$inputoptions = array('name' => 'q', 'class' => 'bg-transparent fs-18 w-100', 'placeholder' => $placeholder, 'type' => 'text',);
$edvik_globalsearch_navbar .= html_writer::empty_tag('input', $inputoptions);

// Context id.
if ($this->page->context && $this->page->context->contextlevel !== CONTEXT_SYSTEM) {
    $edvik_globalsearch_navbar .= html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'context', 'value' => $this->page->context->id]);
}
// Search button.
$edvik_globalsearch_navbar .= '<button type="submit" class="position-absolute bg-transparent border-0 padding-0 end-0"><i class="ri-search-line"></i></button>';
$edvik_globalsearch_navbar .= html_writer::end_tag('fieldset');
$edvik_globalsearch_navbar .= html_writer::end_tag('form');
