<?php
define('CLI_SCRIPT', false);

require(__DIR__ . '/../../config.php');

$apikey = "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";
$post = (object)[
    "question" => 'Hello',
    "sessionId" => 'TEFRrWLieJ',
    "userName" => 'John'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://sanlamdemo.beyondkey.co/api/ChatbotQNA/GetQnAResponse");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));

curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "X-Api-Key: $apikey",
]);

curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  // Disable SSL verification (for local testing only)
$result = curl_exec($ch);

// Check for errors in the request
if ($result === false) {
    echo "cURL Error: " . curl_error($ch);
} else {
    // Print the result if successful
    echo "<pre>"; 
    print_r($result); 
    echo "</pre>";
}

//echo "<pre>"; print_r($result); die;
