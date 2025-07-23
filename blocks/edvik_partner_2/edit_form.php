<?php

class block_edvik_partner_2_edit_form extends block_edit_form {

    protected function specific_definition($mform) {

        global $CFG;

        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));
 
        // Class
        $mform->addElement('text', 'config_class', get_string('config_class', 'theme_edvik'));
        $mform->setDefault('config_class', 'container pb-60​');
        $mform->setType('config_class', PARAM_RAW);

        // Content
        $mform->addElement('textarea', 'config_content', 'Content', 'wrap="virtual" rows="20" cols="40"');
        $mform->setDefault('config_content', 
            '
            <div class="brand-wrap d-flex flex-wrap justify-content-between">
                <div class="brand-card d-flex flex-column align-items-center justify-content-center mb-40">
                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/brand/brand-1.webp" alt="Brand" class="d-block mx-auto">
                </div>
                <div class="brand-card d-flex flex-column align-items-center justify-content-center mb-40">
                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/brand/brand-2.webp" alt="Brand" class="d-block mx-auto">
                </div>
                <div class="brand-card d-flex flex-column align-items-center justify-content-center mb-40">
                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/brand/brand-3.webp" alt="Brand" class="d-block mx-auto">
                </div>
                <div class="brand-card d-flex flex-column align-items-center justify-content-center mb-40">
                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/brand/brand-4.webp" alt="Brand" class="d-block mx-auto">
                </div>
                <div class="brand-card d-flex flex-column align-items-center justify-content-center mb-40">
                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/brand/brand-5.webp" alt="Brand" class="d-block mx-auto">
                </div>
                <div class="brand-card d-flex flex-column align-items-center justify-content-center mb-40">
                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/brand/brand-6.webp" alt="Brand" class="d-block mx-auto">
                </div>
                <div class="brand-card d-flex flex-column align-items-center justify-content-center mb-40">
                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/brand/brand-7.webp" alt="Brand" class="d-block mx-auto">
                </div>
                <div class="brand-card d-flex flex-column align-items-center justify-content-center mb-40">
                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/brand/brand-8.webp" alt="Brand" class="d-block mx-auto">
                </div>
                <div class="brand-card d-flex flex-column align-items-center justify-content-center mb-40">
                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/brand/brand-9.webp" alt="Brand" class="d-block mx-auto">
                </div>
                <div class="brand-card d-flex flex-column align-items-center justify-content-center mb-40">
                    <img src="'.$CFG->wwwroot.'/theme/edvik/pix/brand/brand-10.webp" alt="Brand" class="d-block mx-auto">
                </div>
            </div>
            ​');
        $mform->setType('config_content', PARAM_RAW);

        // Section Image header title according to language file.
        $mform->addElement('header', 'config_image_heading', get_string('config_image_heading', 'theme_edvik'));

        $mform->addElement('static', 'config_image_doc', '<b><a style="color: var(--primaryColor)" href="https://docs.hibootstrap.com/envydoc/edvik-moodle-theme-documentation/faqs/how-to-get-the-image-url//" target="_blank">Doc link: How to make Image URL?</a></b>');
    }
}
