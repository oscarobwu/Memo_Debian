/*
Class - iCR_class.php
PRW v1 8/10/2018
https://devcentral.f5.com/codeshare/php-and-icontrol-rest

This class is used to connect to F5 devices for management via iControl REST

Example Usage:
include("iCR_class.php");
$r = new iCR ("172.24.9.129");
echo "<html><body><pre>";
echo "<h2>Non-existent Virtual Server</h2><pre>";
 
print_r($r->get("/ltm/virtual/iCR_Test_VS"));
echo $r->code;
 
$vs = array('name'=>"iCR_Test_VS",'destination'=>'192.168.1.1:80','mask'=>'255.255.255.255','ipProtocol'=>'tcp');
 
echo "</pre><h2>Created Virtual Server</h2><pre>";
print_r($r->create("/ltm/virtual",$vs));
 
echo "</pre><h2>Edited Virtual Server</h2><pre>";
$description = array('description' => 'This is a modified Virtual Server');
print_r($r->modify("/ltm/virtual/iCR_Test_VS",$description));
echo "Modify HTTP Return Code: $r->code\n";
 
echo "</pre><h2>Deleted Virtual Server</h2><pre>";
print_r($r->delete("/ltm/virtual/iCR_Test_VS"));
echo "Delete HTTP Return Code: $r->code\n";
print_r($r->get("/ltm/virtual/iCR_Test_VS"));
 
echo "</pre></body></html>";

*/
 
class iCR {
	public $username = "admin";
	public $password = "admin";
	public $hostname = null;
	public $code = null;
	
	public function __construct ($hostname,$username = "admin",$password = "admin") {
	$this->BASE_URL = "https://$hostname/mgmt/tm";
	}
	
	public function get($url){
 
		$ch = curl_init($this->BASE_URL.$url);                                                                      
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");                                                                 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_USERPWD, "$this->username:$this->password");		
		echo $this->BASE_URL.$url;
		$result = curl_exec($ch);
		$this->code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		return json_decode($result);
	}
 
	public function create($url, $data){
		$encoded_data = json_encode($data);
		$ch = curl_init($this->BASE_URL.$url);                                                                      
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
		curl_setopt($ch, CURLOPT_FAILONERROR, true);                                                                    
		curl_setopt($ch, CURLOPT_POSTFIELDS, $encoded_data);                                                                  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_USERPWD, "$this->username:$this->password");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
		    'Content-Type: application/json',                                                                                
		    'Content-Length: ' . strlen($encoded_data))                                                                       
		);                                                                                                                   
		 
		$result = curl_exec($ch);
		$this->code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		return json_decode($result);
	}
 
	public function modify($url, $data){
		$encoded_data = json_encode($data);
		$ch = curl_init($this->BASE_URL.$url);                                                                      
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); 
		curl_setopt($ch, CURLOPT_FAILONERROR, true);                                                                    
		curl_setopt($ch, CURLOPT_POSTFIELDS, $encoded_data);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_USERPWD, "$this->username:$this->password");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
		    'Content-Type: application/json',                                                                                
		    'Content-Length: ' . strlen($encoded_data))                                                                       
		);                                                                                                                   
		 
		$result = curl_exec($ch);
		$this->code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		return json_decode($result);
	}
 
	public function delete($url){
 
		$ch = curl_init($this->BASE_URL.$url);                                                                      
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE"); 
		curl_setopt($ch, CURLOPT_FAILONERROR, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_USERPWD, "$this->username:$this->password");                                                                  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json') );                                                                                                                   
		 
		$result = curl_exec($ch);
		$this->code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		return json_decode($result);
	}
 
}