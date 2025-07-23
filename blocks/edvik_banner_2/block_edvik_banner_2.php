<?php
global $CFG;
require_once($CFG->dirroot . '/theme/edvik/inc/block_handler/get-content.php');
class block_edvik_banner_2 extends block_base {
    public function init() {
        $this->title = get_string('pluginname', 'block_edvik_banner_2');
    }

    // Declare second
    public function specialization()
    {
        global $CFG, $DB;
        include($CFG->dirroot . '/theme/edvik/inc/block_handler/specialization.php');
        if (empty($this->config)) {
            $this->config = new \stdClass();
            $url = new moodle_url('/search/index.php');

            $this->config->class = '';
            $this->config->content = '
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
            ​';
        }
    }

    public function get_content() {
        global $CFG, $DB;
        if ($this->content !== null) {
          return $this->content;
        }
        $this->content         =  new stdClass;

        $text = '';
        $text .= '
            <div class="'.$this->config->class.'">
                '.format_text($this->config->content, FORMAT_HTML, array('filter' => true)).' 
            </div> ';
        $this->content         =  new stdClass;
        $this->content->footer = '';
        $this->content->text   = $text;

        return $this->content;
    }

    /**
     * The block can be used repeatedly in a page.
     */
    function instance_allow_multiple() {
        return true;
    }

    /**
     * Enables global configuration of the block in settings.php.
     *
     * @return bool True if the global configuration is enabled.
     */
    function has_config() {
        return true;
    }

    /**
     * Sets the applicable formats for the block.
     *
     * @return string[] Array of pages and permissions.
     */
    function applicable_formats() {
        return array(
            'all' => true,
            'my' => true,
            'admin' => true,
            'course-view' => true,
            'course' => true,
        );
    }

}