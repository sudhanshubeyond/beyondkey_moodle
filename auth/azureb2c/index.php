<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package auth_azureb2c
 * @author Gopal Sharma <gopalsharma66@gmail.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright (C) 2020 Gopal Sharma <gopalsharma66@gmail.com>
 */

require_once(__DIR__.'/../../config.php');
require_once(__DIR__.'/auth.php');
//$auth = new \auth_plugin_azureb2c('authcode');
//$auth->set_httpclient(new \auth_azureb2c\httpclient());
//$auth->handleredirect();
$tenant = "2956020b-89d7-495b-bbc4-1ceac9034674"; // your tenant ID
$clientId = "1c9d189c-f5e9-4257-ab8e-7e43390938fe";
$clientSecret = "vn98Q~ApuabVG-ou4cJ9~twC0CqldQ2bpA8jabpg";//"YOUR_CLIENT_SECRET"; // from Azure AD app
$redirectUri = "https://moodlepoc.beyondkey.co/auth/azureb2c/";
//print_r($_POST);die;
/*if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['code'])) {
print_r($_POST);die;
    
	$code = $_POST['code'];
$token_url = "https://login.microsoftonline.com/2956020b-89d7-495b-bbc4-1ceac9034674/oauth2/v2.0/token";

$post_fields = http_build_query([
    'client_id' => '1c9d189c-f5e9-4257-ab8e-7e43390938fe',
    'scope' => 'openid profile offline_access',
    'code' => $_POST['code'],  // the 'code' you got in the callback
    'redirect_uri' => 'https://moodlepoc.beyondkey.co/auth/azureb2c/',
    'grant_type' => 'authorization_code',
    'client_secret' => $clientSecret
]);

$options = [
    'http' => [
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => $post_fields,
    ],
];

$context = stream_context_create($options);
$response = file_get_contents($token_url, false, $context);

if ($response === FALSE) {
    $error = error_get_last();
    die("HTTP Request Failed:\n" . print_r($error, true));
}

$tokens = json_decode($response, true);

if (isset($tokens['error'])) {
    echo "Error: " . $tokens['error'] . "<br>";
    echo "Description: " . $tokens['error_description'] . "<br>";
    die();
}

echo "<pre>";
print_r($tokens);
echo "</pre>";
}else{ */
$auth = new \auth_plugin_azureb2c('authcode');
$auth->set_httpclient(new \auth_azureb2c\httpclient());
$auth->handleredirect();

//}


