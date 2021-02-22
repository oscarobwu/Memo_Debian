<?

// iCR with Functions
 
include("iCR.php");
$hostname = "172.19.4.166";
$username = "admin";
$password = "admin";
 
$BIGIP_URL_BASE = "https://$hostname/mgmt/tm";
$url = $BIGIP_URL_BASE."/ltm/virtual";
$handle = curl_init();
echo "<html><body><h2><pre>";
 
echo "</pre><h2>Non-existent Virtual Server</h2><pre>";
print_r(iCR($handle,$username,$password,"GET",$url));
 
echo "</pre></body></html>";
 

 
// Function - iCR.php
 
function iCR($handle,$username,$password,$method = "GET",$url,$data = NULL) {
	curl_setopt($handle, CURLOPT_URL, $url);
	curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($handle, CURLOPT_USERPWD, "$username:$password");
	$data_encoded = json_encode($data);
 
	switch($method) {
		case 'GET':
			$headers = array( 'Content-Type: application/json' );
			curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($handle, CURLOPT_POST, false);
			break;
		case 'POST':
			curl_setopt($handle, CURLOPT_POST, true);
			curl_setopt($handle, CURLOPT_POSTFIELDS, $data_encoded);
			$headers = array(
				'Content-Type: application/json',
				'Content-Length: ' . strlen($data_encoded)
			);
			curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
			break;
		case 'PUT':
			curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'PUT');
			curl_setopt($handle, CURLOPT_POSTFIELDS, $data_encoded);
			$headers = array(
				'Content-Type: application/json',
				'Content-Length: ' . strlen($data_encoded)
			);
			curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
			break;
		case 'DELETE':
			curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'DELETE');
			curl_setopt($handle, CURLOPT_POST, false);
			curl_setopt($handle, CURLOPT_POSTFIELDS, "");
			$headers = array( 'Content-Type: application/json' );
			curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
			break;
	}
	
	$response = curl_exec($handle);
	$code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
	if ($code == "200") {
		return json_decode($response);
	} else {
		// This is helpful for debugging issues, you may want to change this for production
		return $response;
	}
}
?>
