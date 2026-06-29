<?php
namespace mod_teamsintegration\form;

defined('MOODLE_INTERNAL') || die();

class attendee extends \moodleform {
    public function __construct($action = null, $customdata = null, $method = 'post', $target = '', $attributes = null, $editable = true) {
        // Set the form action URL to include the id parameter
        if ($customdata && isset($customdata['cmid'])) {
            $action = new \moodle_url('/mod/teamsintegration/view.php', ['id' => $customdata['cmid'], 'tab' => 'addattendee']);
        }
        parent::__construct($action, $customdata, $method, $target, $attributes, $editable);
    }

    public function definition() {
        $mform = $this->_form;
        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);
        
        $mform->addElement('hidden', 'meetingid');
        $mform->setType('meetingid', PARAM_INT);

        // Get current users and paging bar from customdata
        $current_users = $this->_customdata['current_users'] ?? [];
        $pagingbar = $this->_customdata['pagingbar'] ?? '';

        if (!empty($current_users)) {
            // Add paging bar at top
            if ($pagingbar) {
                $mform->addElement('html', '<div class="paging">' . $pagingbar . '</div>');
            }
            
            $mform->addElement('html', '<p>' . get_string('selectattendeestoinvite', 'mod_teamsintegration') . '</p>');
            
            // Add select all checkbox and script
            $mform->addElement('checkbox', 'selectall', '', get_string('selectall', 'mod_teamsintegration'));
            
            // List each user with a checkbox element so it is included in form data
            foreach ($current_users as $user) {
                $fullname = fullname($user);
                $label = s($fullname) . ' (' . s($user->email) . ')';
                $mform->addElement('advcheckbox', "attendees[{$user->id}]", '', $label, ['class' => 'attendee-checkbox']);
                $mform->setType("attendees[{$user->id}]", PARAM_INT);
            }
            
            // Add paging bar at bottom
            if ($pagingbar) {
                $mform->addElement('html', '<div class="paging">' . $pagingbar . '</div>');
            }
            
            // Add JavaScript for select all and form handling
            $mform->addElement('html', '
            <script>
            document.addEventListener("DOMContentLoaded", function() {
                var selectAll = document.querySelector("input[name=selectall]");
                var checkboxes = document.querySelectorAll(".attendee-checkbox");
                
                if (selectAll) {
                    selectAll.addEventListener("change", function() {
                        checkboxes.forEach(function(cb) {
                            cb.checked = selectAll.checked;
                        });
                    });
                }
                
                // Update select all checkbox state
                checkboxes.forEach(function(cb) {
                    cb.addEventListener("change", function() {
                        var allChecked = Array.from(checkboxes).every(function(c) { return c.checked; });
                        if (selectAll) {
                            selectAll.checked = allChecked;
                        }
                    });
                });
            });
            </script>');
        } else {
            $mform->addElement('static', '', '', get_string('noavailableusers', 'mod_teamsintegration'));
        }

        $this->add_action_buttons(true, get_string('addselectedattendees', 'mod_teamsintegration'));
    }
}
