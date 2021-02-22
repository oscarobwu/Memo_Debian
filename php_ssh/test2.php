<?php
$username='admin';
$password='admin';
#
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://172.19.4.166/mgmt/tm/ltm/virtual");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
$parsed_json = curl_exec($ch);
$parsed_json = json_decode($parsed_json);

foreach($parsed_json->results->collection1 as $collection){
    echo $collection->title->text . '<br>';
    echo $collection->title->href . '<br>';
    echo $collection->posted . '<br><br>';
}

curl_close($ch);
?>
