<?php
// Initiate curl session in a variable (resource)
$curl_handle = curl_init();

$url = "https://172.19.4.166/mgmt/tm/ltm/virtual";

// Set the curl URL option
curl_setopt($curl_handle, CURLOPT_URL, $url);

// This option will return data as a string instead of direct output
curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
// ###################
// user credencial
curl_setopt($curl_handle, CURLOPT_USERPWD, "admin:admin");
curl_setopt($curl_handle, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
curl_setopt($$curl_handle, CURLOPT_URL, $url);

curl_setopt($curl_handle, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl_handle, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
curl_setopt($curl_handle, CURLOPT_VERBOSE, true);

curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);

// ##################

// Execute curl & store data in a variable
$curl_data = curl_exec($curl_handle);

curl_close($curl_handle);

// Decode JSON into PHP array
$response_data = json_decode($curl_data);

// Print all data if needed
// print_r($response_data);
// die();

// All user data exists in 'data' object
$user_data = $response_data->data;

// Extract only first 5 user data (or 5 array elements)
$user_data = array_slice($user_data, 0, 4);

#print_r($response_data)
echo $response_data["items"]["name"] . "<br>";  // Output: Harry Potter
// Traverse array and print employee data
#foreach ($user_data as $user) {
#	echo "name: ".$user->name;
#	echo "<br />";
#}

?>
