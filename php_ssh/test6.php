<?php

$url = 'https://172.19.4.166/mgmt/tm/ltm/virtual';

if ($argc > 1){
    $url = $argv[1];
}

$ch=curl_init();
// user credencial
curl_setopt($ch, CURLOPT_USERPWD, "admin:admin");
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, $url);

curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
curl_setopt($ch, CURLOPT_VERBOSE, true);

curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);

curl_close($ch);

#var_dump(json_decode($response));
// $arr2 = var_dump(json_decode($response, true));
$arr = json_decode($response, true);
#$arr = json_decode($response);
#print_r(json_decode($response, true));
#echo $arr["name"];
#echo $arr["items"];
#echo $arr["kind"] . "<br>";
#echo $arr["selfLink"];
#echo $arr["items"];
#echo $arr["items"][0] . "<br>";  // Output: Harry Potter
#print_r($response);
#print_r($response);
// Display the value of json object 
#print $arr->{'items'}; 
print_r($arr["items"][0]["name"]);
print_r($arr["items"][1]["name"]);

#$filter = [name,destination];
#$links = json_decode($response)->links;
#$filtered = array_filter($links, function ($item) use ($filter) {
#    return in_array($item->id, $filter);
#});
#
#print_r($filtered);
?>
