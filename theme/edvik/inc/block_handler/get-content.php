<?php
/**
 * Block Content Handler
 */

// function edvik_block_image_process($img) {
//     global $CFG;

//     // Old URL base part (to be replaced)
//     $old_url = 'http://localhost:8888/moodle/edvik/';
    
//     // New URL base part (from the Moodle configuration)
//     $new_url = $CFG->wwwroot . '/';  // Uses the current Moodle site's base URL

//     // Debugging: Output the current image URL
//     die($img);

//     // Check if the image URL contains the old URL base
//     if (strpos($img, $old_url) !== false) {
//         // Replace the old URL with the new URL
//         $img = str_replace($old_url, $new_url, $img);
//     }

//     return $img;  // Return the modified (or unchanged) image URL
// }

function edvik_block_image_process($img) {
    global $CFG;

    // Define the new base URL (current Moodle site URL)
    $new_url = $CFG->wwwroot . '/';

    // Replace the old base URL with the new URL, no need to check for specific patterns
    $img = str_replace("http://localhost:8888/moodle/edvik/", $new_url, $img);

    // Return the updated image URL
    return $img;
}

