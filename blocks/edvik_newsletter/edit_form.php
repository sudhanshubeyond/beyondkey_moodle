<?php

class block_edvik_newsletter_edit_form extends block_edit_form {

    protected function specific_definition($mform) {

        global $CFG;

        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));
 
        // Class
        $mform->addElement('text', 'config_class', get_string('config_class', 'theme_edvik'));
        $mform->setDefault('config_class', 'container​');
        $mform->setType('config_class', PARAM_RAW);

        // Content
        $mform->addElement('textarea', 'config_content', 'Content', 'wrap="virtual" rows="20" cols="40"');
        $mform->setDefault('config_content', 
            '
            <div class="newsletter-box position-relative index-1">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <div class="newsletter-content">
                            <div class="section-title style-three">
                                <span class="fs-13 d-block font-medium text_primary">SUBSCRIBE NEWSLETTER</span>
                                <h2 class="d-inline-block font-semibold position-relative ">Subscribe now to our Newsletter <img src="https://edvik-moodle.hibootstrap.com/pluginfile.php/1/theme_edvik/fn_title_shape_img/-1/section-title-shape-3.webp" class="position-absolute bottom-0 end-0"></h2>
                                <p>Explore all of our courses and pick your suitable ones to enroll and start learning.</p>
                            </div>
                            <form action="add_here_your_mailchimp" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form"  class="subscribe-form position-relative" target="_blank">
                                <input type="text" value="" name="EMAIL" class="email input-newsletter w-100 fs-14 bg-white border-0 round-10" id="mce-EMAIL" placeholder="Enter your email addrees" required>
                                <button type="submit" type="submit" name="subscribe" id="mc-embedded-subscribe" class="btnn style-one position-absolute top-0 end-0 h-100 bg_primary text-white border-0 fs-15 round-10">Subscribe Now<i class="ri-arrow-right-line"></i></button>
                            </form> 
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="newsletter-img-wrap position-relative index-1">
                            <img src="'.$CFG->wwwroot.'/theme/edvik/pix/shape-1.webp" alt="Shape" class="newsletter-shape position-absolute bounce">
                            <img src="'.$CFG->wwwroot.'/theme/edvik/pix/newsletter-img.webp" alt="Image" class="newsletter-img position-relative d-block mx-auto">
                        </div>
                    </div>
                </div>
            </div>
            ​');
        $mform->setType('config_content', PARAM_RAW);

        // Section Image header title according to language file.
        $mform->addElement('header', 'config_image_heading', get_string('config_image_heading', 'theme_edvik'));

        $mform->addElement('static', 'config_image_doc', '<b><a style="color: var(--primaryColor)" href="https://docs.hibootstrap.com/envydoc/edvik-moodle-theme-documentation/faqs/how-to-get-the-image-url//" target="_blank">Doc link: How to make Image URL?</a></b>');
    }
}
