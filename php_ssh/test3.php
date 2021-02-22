<?php
// jSON URL which should be requested
$json_url = 'https://172.19.4.166/mgmt/tm/ltm/virtual';

$username = 'admin';  // authentication
$password = 'admin';  // authentication

// jSON String for request
#$json_string = '[your json string here]';
$json_string = '?';

// Initializing curl
$ch = curl_init( $json_url );

// Configuring curl options
$options = array(
CURLOPT_RETURNTRANSFER => true,
CURLOPT_USERPWD => $username . “:” . $password,  // authentication
CURLOPT_HTTPHEADER => array('Content-type: application/json') ,
CURLOPT_POSTFIELDS => $json_string
);

// Setting curl options
curl_setopt_array( $ch, $options );

// Getting results
$result = curl_exec($ch); // Getting jSON result string
?>
