<?php
global $CFG;
require_once($CFG->dirroot .'/blog/lib.php');
require_once($CFG->dirroot .'/blog/locallib.php');
require_once($CFG->dirroot . '/theme/edvik/inc/block_handler/get-content.php');
class block_edvik_blog_area_2 extends block_base {
    public function init() {
        $this->title = get_string('pluginname', 'block_edvik_blog_area_2');
    }

    // Declare second
    public function specialization()
    {
        global $CFG, $DB;
        include($CFG->dirroot . '/theme/edvik/inc/block_handler/specialization.php');
        if (empty($this->config)) {
            $this->config = new \stdClass();            
            $this->config->style = 1;
            $this->config->top_title        = 'BLOG ARTICLES';
            $this->config->title            = 'Want To <span>Learn</span> More? Read Blog';
            $this->config->by_title         = 'By';
            $this->config->read_more        = 'Read More';
            $this->config->button_text      = 'Explore All Articles';
            $this->config->button_link      = $CFG->wwwroot . '/blog';
        }
    }

    public function get_content() {
        global $CFG, $PAGE;

        if ($this->content !== null) {
            return $this->content;
        }
        $this->content         =  new stdClass;
        
        if(!empty($this->config->title)){$this->content->title = $this->config->title;} else {$this->content->title = '';}
        
        if(!empty($this->config->top_title)){$this->content->top_title = $this->config->top_title;} else {$this->content->top_title = '';}

        if(!empty($this->config->by_title)){$this->content->by_title = $this->config->by_title;} else {$this->content->by_title = '';}
        
        if(!empty($this->config->button_text)){$this->content->button_text = $this->config->button_text;} else {$this->content->button_text = '';}

        if(!empty($this->config->shape_image)){$this->content->shape_image = $this->config->shape_image;} else {$this->content->shape_image = '';}

        if(!empty($this->config->button_link)){$this->content->button_link = $this->config->button_link;} else {$this->content->button_link = '';}

        if(!empty($this->config->posts)){$this->content->posts = $this->config->posts;} else { $this->content->posts = '';}

        $url = new moodle_url('/blog/index.php');

        global $CFG;
        $bloglisting = new blog_listing();

        $entries = $bloglisting->get_entries();
        
        $entrieslist = array();
        $viewblogurl = new moodle_url('/blog/index.php');
        
        // $style = 1;
        // if(isset($this->config->style)){
        //     $style = $this->config->style;
        // }

        $text = '';
        $text .= '
        <div class="container ptb-100">
            <div class="row align-items-center mb-40">
                <div class="col-lg-8">
                    <div class="section-title">
                        <span class="fs-13 font-medium d-block text_primary">'.format_text($this->content->top_title, FORMAT_HTML, array('filter' => true)).'</span>
                        <h2 class="d-inline-block font-semibold position-relative mb-0">'.format_text($this->content->title, FORMAT_HTML, array('filter' => true)).'</h2>
                    </div>
                </div>
                <div class="col-lg-4 text-md-end mt-sm-20">';
                    if(!empty($this->content->button_text) && !empty($this->content->button_link)){
                        $text .= '
                            <a href="'.$this->content->button_link.'" class="link style-one">'.format_text($this->content->button_text, FORMAT_HTML, array('filter' => true)).'</a
                        ';
                    }
                    $text .= '
                </div>
            </div>
            </div>
            
            <div class="blog-slider-two swiper">
                <div class="swiper-wrapper">';
                    if($this->content->posts):
                        foreach ($entries as $entryid => $entry) {
                            $viewblogurl->param('entryid', $entryid);
                            $entrylink = html_writer::link($viewblogurl, shorten_text($entry->subject));
                            $entrieslist[] = $entrylink;
            
                            $blogentry = new blog_entry($entryid);
                            $blogattachments = $blogentry->get_attachments();

                            $short_summary = $entry->summary;
                            $short_summary = strip_tags( $short_summary);
                            $short_summary = implode(' ', array_slice(str_word_count($short_summary,1), 0, 15));

                            if(in_array($entry->id, $this->content->posts)):
                                $text .= '
                                <div class="swiper-slide">
                                    <div class="blog-card style-two round-10 mb-50">
                                        <div class="blog-img round-10">
                                            <img src="'.$blogattachments[0]->url.'" alt="'.strip_tags($entry->subject).'" class="round-10">
                                        </div>

                                        <div class="blog-info position-relative index-1 bg-mystic round-10">
                                            <ul class="blog-metainfo list-unstyle">
                                                <li class="position-relative"><img src="'.$CFG->wwwroot . '/'.'theme/edvik/pix/icons/calendar.svg" alt="Calendar Icon"><a href="'.$viewblogurl.'">'.format_text( userdate($entry->created, '%d %b %Y', 0), FORMAT_HTML, array('filter' => true) ).'</a></li>

                                                <li class="position-relative"><img src="'.$CFG->wwwroot . '/'.'theme/edvik/pix/icons/clock.svg" alt="Clock Icon">'.$minutes.' mins read</li>
                                            </ul>
                                            <h3 class="font-medium ls-1"><a href="'.$viewblogurl.'">'.format_text($entry->subject, FORMAT_HTML, array('filter' => true)).'</a></h3>
                                            <a href="'.$viewblogurl.'" class="link style-two fs-15">'.format_text($this->config->read_more, FORMAT_HTML, array('filter' => true)).'<i class="ri-arrow-right-line"></i></a>
                                        </div>
                                    </div>
                                </div>';
                            endif;
                        }
                    endif;
                    $text .= '
                </div>
                <div class="blog-pagination d-flex flex-wrap align-items-center justify-content-center"></div>
            </div>
        </div>'; 
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