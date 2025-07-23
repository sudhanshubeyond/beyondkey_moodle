<?php

class block_edvik_faq_edit_form extends block_edit_form {

    protected function specific_definition($mform) {

        global $CFG;

        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));
 
        // Class
        $mform->addElement('text', 'config_class', get_string('config_class', 'theme_edvik'));
        $mform->setDefault('config_class', 'container pb-100');
        $mform->setType('config_class', PARAM_RAW);

        // Content
        $mform->addElement('textarea', 'config_content', 'Content', 'wrap="virtual" rows="20" cols="40"');
        $mform->setDefault('config_content', 
            '
            <div class="row align-items-center">
                <div class="col-lg-6 ps-xxl-3 pe-xxl-3">
                    <div class="faq-img-wrap position-relative index-1 ps-xxl-2">
                        <img src="'.$CFG->wwwroot.'/theme/edvik/pix/about/faq-shape.webp" alt="Image" class="faq-shape position-absolute">
                        <img src="'.$CFG->wwwroot.'/theme/edvik/pix/about/faq-img-1.webp" alt="FAQ" class="bounce">
                    </div>
                </div>
                <div class="col-lg-6 ps-xxl-2">
                    <div class="faq-content style-one">
                        <div class="section-title">
                            <span class="fs-13 d-block font-medium text_primary">FAQ</span>
                            <h2 class="d-inline-block font-semibold position-relative mb-20">Affordable online courses and learning opportunities​</h2>
                            <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll and start learning with us! Flexible easy to access learning opportunities.</p>
                        </div>
                        <div class="accordion" id="accordionExample_two">
                            <div class="accordion-item collapsed bg-white" 
                            data-toggle="collapse" data-target="#collapseFour"
                            aria-expanded="false" aria-controls="collapseFour" role="button">
                                <div class="accordion-header" id="headingFour">
                                    <div class="accordion-button text-title font-medium">
                                        <span>
                                            <i class="ri-arrow-down-s-line plus"></i>
                                            <i class="ri-arrow-up-s-line minus"></i>
                                        </span>
                                        How to admit Edvik?
                                    </div>
                                </div>
                                <div id="collapseFour" class="accordion-collapse collapse"
                                    aria-labelledby="headingFour" data-parent="#accordionExample_two">
                                    <div class="accordion-body">
                                        <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll and start learning with us! Flexible easy to access learning opportunities.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item collapsed bg-white" 
                            data-toggle="collapse" data-target="#collapseFive"
                            aria-expanded="false" aria-controls="collapseFive" role="button">
                                <div class="accordion-header" id="headingFive">
                                    <div class="accordion-button text-title font-medium">
                                        <span>
                                            <i class="ri-arrow-down-s-line plus"></i>
                                            <i class="ri-arrow-up-s-line minus"></i>
                                        </span>
                                        How to pay online in Edvik?
                                    </div>
                                </div>
                                <div id="collapseFive" class="accordion-collapse collapse "
                                    aria-labelledby="headingFive" data-parent="#accordionExample_two">
                                    <div class="accordion-body">
                                        <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll and start learning with us! Flexible easy to access learning opportunities.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item collapsed bg-white" 
                            data-toggle="collapse" data-target="#collapseSix"
                            aria-expanded="false" aria-controls="collapseSix" role="button">
                                <div class="accordion-header" id="headingSix">
                                    <div class="accordion-button text-title font-medium">
                                        <span>
                                            <i class="ri-arrow-down-s-line plus"></i>
                                            <i class="ri-arrow-up-s-line minus"></i>
                                        </span>
                                        How to teach in Edvik?
                                    </div>
                                </div>
                                <div id="collapseSix" class="accordion-collapse collapse"
                                    aria-labelledby="headingSix" data-parent="#accordionExample_two">
                                    <div class="accordion-body">
                                        <p class="mb-0">Explore all of our courses and pick your suitable ones to enroll and start learning with us! Flexible easy to access learning opportunities.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a href="#" class="btnn style-two fw-semibold round-10">Ask Your Questions</a>
                    </div>
                </div>
            </div>
            ');
        $mform->setType('config_content', PARAM_RAW);

        // Section Image header title according to language file.
        $mform->addElement('header', 'config_image_heading', get_string('config_image_heading', 'theme_edvik'));

        $mform->addElement('static', 'config_image_doc', '<b><a style="color: var(--primaryColor)" href="https://docs.hibootstrap.com/envydoc/edvik-moodle-theme-documentation/faqs/how-to-get-the-image-url//" target="_blank">Doc link: How to make Image URL?</a></b>');
    }
}
