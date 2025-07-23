<?php

class block_edvik_banner_2_edit_form extends block_edit_form {

    protected function specific_definition($mform) {

        global $CFG;
        $url = new moodle_url('/search/index.php');

        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));
 
        // Class
        $mform->addElement('text', 'config_class', get_string('config_class', 'theme_edvik'));
        $mform->setDefault('config_class', '​');
        $mform->setType('config_class', PARAM_RAW);

        // Content
        $mform->addElement('textarea', 'config_content', 'Content', 'wrap="virtual" rows="20" cols="40"');
        $mform->setDefault('config_content', 
            '
            <div class="hero-wrap style-two position-relative">
                <div class="container-fluid"> 
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <div class="hero-content position-relative">
                                <img src="'.$CFG->wwwroot.'/theme/edvik/pix/hero/shape-2.webp" alt="Shape" class="hero-shape-one position-absolute bounce">
                                <h1 class="font-bold">Join Edvik Online Academy for free</h1>
                                <p class="text-tandora ls-1">Flexible easy to access learning opportunities can bring a significant change in how individuals prefer to learn! The Edvik can offer you to enjoy the beauty of eLearning!</p>
                                <form action="'.$url->out().'" class="seach-form position-relative">
                                    <input type="search" name="q" placeholder="What do you wnat to learn today?" class="bg-white w-100 h-60 round-6 fs-14 ls-1">
                                    <button type="submit" class="h-100 top-0 end-0 fs-15 position-absolute text-white transition">Search Now<i class="ri-search-line"></i></button>
                                </form>
                                <div class="instructor-para d-flex align-items-center">
                                    <ul class="d-flex align-items-center list-unstyle">
                                        <li><img src="'.$CFG->wwwroot.'/theme/edvik/pix/blog/author-11.webp" alt="Author"></li>
                                        <li><img src="'.$CFG->wwwroot.'/theme/edvik/pix/blog/author-10.webp" alt="Author"></li>
                                        <li><img src="'.$CFG->wwwroot.'/theme/edvik/pix/blog/author-1.webp" alt="Author"></li>
                                    </ul>
                                    <p class="mb-0 text-tandora">Need help? Contact our <a href="'.$CFG->wwwroot.'/" class="link style-two">Edvik support</a>  Tell us about your query.</p>
                                </div>
                                <a href="'.$CFG->wwwroot.'/course/index.php?categoryid=2" class="link style-one fs-15">Explore All Courses<i class="ri-arrow-right-line"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-6 pe-lg-0">
                            <div class="hero-img-wrap position-relative">
                                <div class="lesson-box text-center position-absolute d-flex flex-column justify-content-center align-items-center">
                                    <h6 class="fs-40 font-bold lh-1">500+</h6>
                                    <span class="text-tandora ls-1">Free Lessons</span>
                                </div>
                                <div class="hero-students-box text-center round-10">
                                    <h4 class="fs-40 font-bold">100K+</h4>
                                    <span class="text-tandora ls-1">Active students in our courses</span>
                                </div>
                                <div class="instructor-box d-inline-block round-10">
                                    <ul class="d-flex align-items-center list-unstyle">
                                        <li><img src="'.$CFG->wwwroot.'/theme/edvik/pix/blog/author-5.webp" alt="Author"></li>
                                        <li><img src="'.$CFG->wwwroot.'/theme/edvik/pix/blog/author-3.webp" alt="Author"></li>
                                        <li><img src="'.$CFG->wwwroot.'/theme/edvik/pix/blog/author-6.webp" alt="Author"></li>
                                        <li><img src="'.$CFG->wwwroot.'/theme/edvik/pix/blog/author-12.webp" alt="Author"></li>
                                        <li><span class="d-flex flex-column align-items-center justify-content-center rounded-circle text-title font-medium">10k+</span></li>
                                    </ul>
                                    <span class="text-title ls-1">Worldwide students from different countries</span>
                                </div>
                            </div>
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
