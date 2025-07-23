<?php
global $CFG;
require_once($CFG->dirroot . '/theme/edvik/inc/block_handler/get-content.php');
class block_edvik_newsletter extends block_base {
    public function init() {
        $this->title = get_string('pluginname', 'block_edvik_newsletter');
    }

    // Declare second
    public function specialization()
    {
        global $CFG, $DB;
        include($CFG->dirroot . '/theme/edvik/inc/block_handler/specialization.php');
      //  if (empty($this->config)) {
            $this->config = new \stdClass();

            $this->config->class = 'container';
            $this->config->content = '
                <div class="newsletter-box position-relative index-1">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="newsletter-content">
                                <div class="section-title style-three">
                                    <span class="fs-13 d-block font-medium text_primary">SUBSCRIBE NEWSLETTER</span>
                                    <h2 class="d-inline-block font-semibold position-relative">Subscribe now to our Newsletter <img src="https://edvik-moodle.hibootstrap.com/pluginfile.php/1/theme_edvik/fn_title_shape_img/-1/section-title-shape-3.webp" class="position-absolute bottom-0 end-0"></h2>
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
            ​';
        //}
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