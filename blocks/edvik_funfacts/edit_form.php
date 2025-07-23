<?php

class block_edvik_funfacts_edit_form extends block_edit_form {

    protected function specific_definition($mform) {

        global $CFG;

        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block'));
 
        // Class
        $mform->addElement('text', 'config_class', get_string('config_class', 'theme_edvik'));
        $mform->setDefault('config_class', 'container pb-70​');
        $mform->setType('config_class', PARAM_RAW);

         $style = 1;
        if(isset($this->block->config->style)){
            $style = $this->block->config->style;
        }
        $mform->addElement('select', 'config_style', 'Select Output Mode', array(1 => 'General Output', 2 => 'HTML Output'));
        $mform->setDefault('config_style', 2);

        // HTML Output
        $mform->addElement('header', 'config_html_output', 'HTML Output');

        // Content
        $mform->addElement('textarea', 'config_content', 'Content', 'wrap="virtual" rows="20" cols="40"');
        $mform->setDefault('config_content', 
            '
            <div class="row justify-content-center">
                <div class="col-xl-3 col-lg-4 col-sm-6">
                    <div class="counter-card style-one bg-one round-10 position-relative index-1 mb-30">
                        <img src="'.$CFG->wwwroot.'/theme/edvik/pix/counter-shape.webp" alt="Image" class="counter-shape position-absolute top-0 end-0">
                        <p class="fs-13 font-semibold text_primary">COURSES</p>
                        <h2 class="font-bold mb-0"><span class="counter">6500</span>+</h2>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-sm-6">
                    <div class="counter-card style-one bg-two round-10 position-relative index-1 mb-30">
                        <img src="'.$CFG->wwwroot.'/theme/edvik/pix/counter-shape.webp" alt="Image" class="counter-shape position-absolute top-0 end-0">
                        <p class="fs-13 font-semibold text_primary">INSTRUCTORS</p>
                        <h2 class="font-bold mb-0"><span class="counter">1560</span>+</h2>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-sm-6">
                    <div class="counter-card style-one bg-three round-10 position-relative index-1 mb-30">
                        <img src="'.$CFG->wwwroot.'/theme/edvik/pix/counter-shape.webp" alt="Image" class="counter-shape position-absolute top-0 end-0">
                        <p class="fs-13 font-semibold text_primary">STUDENTS ENROLLED</p>
                        <h2 class="font-bold mb-0"><span class="counter">13500</span>+</h2>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-sm-6">
                    <div class="counter-card style-one bg-four round-10 position-relative index-1 mb-30">
                        <img src="'.$CFG->wwwroot.'/theme/edvik/pix/counter-shape.webp" alt="Image" class="counter-shape position-absolute top-0 end-0">
                        <p class="fs-13 font-semibold text_primary">LEARNERS PROGRESS</p>
                        <h2 class="font-bold mb-0"><span class="counter">82.5</span>%</h2>
                    </div>
                </div>
            </div>
            ​');
        $mform->setType('config_content', PARAM_RAW);

        // General Output
        $mform->addElement('header', 'config_general_output', 'General Output');

        $item_number = 4;
        if(isset($this->block->config->item_number)){
            $item_number = $this->block->config->item_number;
        }

        $itemRange = array(
            1 => '1',
            2 => '2',
            3 => '3',
            4 => '4',
            5 => '5',
            6 => '6',
            7 => '7',
            8 => '8',
            9 => '9',
            10 => '10',
            11 => '11',
            12 => '12',
            13 => '13',
            14 => '14',
            15 => '15',
            16 => '16',
            17 => '17',
            18 => '18',
            19 => '19',
            20 => '20',
            21 => '21',
            22 => '22',
            23 => '23',
            24 => '24',
            25 => '25',
            26 => '26',
            27 => '27',
            28 => '28',
            29 => '29',
            30 => '30',
        );

        $mform->addElement('select', 'config_item_number', get_string('config_items', 'theme_edvik'), $itemRange);
        $mform->setDefault('config_item_number', 4);

        for($i = 1; $i <= $item_number; $i++) {
            $mform->addElement('header', 'config_edvik_item' . $i , get_string('config_item', 'theme_edvik') . $i);

            // Background Color
            $mform->addElement('text', 'config_bg_color' . $i, 'Card Background Color Code [ex: #DCD7E]');
            $mform->setType('config_bg_color' . $i, PARAM_TEXT);

            // Title
            $mform->addElement('text', 'config_item_title' . $i, get_string('config_title', 'theme_edvik', $i));
            $mform->setDefault('config_item_title' . $i, 'COURSES');
            $mform->setType('config_item_title' . $i, PARAM_TEXT);

            // Number
            $mform->addElement('text', 'config_number' . $i, 'Number');
            $mform->setDefault('config_number' . $i, '6500+');
            $mform->setType('config_number' . $i, PARAM_TEXT);
        }

        // Section Image header title according to language file.
        $mform->addElement('header', 'config_image_heading', get_string('config_image_heading', 'theme_edvik'));

        $mform->addElement('static', 'config_image_doc', '<b><a style="color: var(--primaryColor)" href="https://docs.hibootstrap.com/envydoc/edvik-moodle-theme-documentation/faqs/how-to-get-the-image-url//" target="_blank">Doc link: How to make Image URL?</a></b>');

        $mform->addElement('text', 'config_shape1', 'Card Shape Image URL');
        $mform->setDefault('config_shape1', EDVIK_IMG .'counter-shape.webp');
        $mform->setType('config_shape1', PARAM_TEXT);

    }
}
