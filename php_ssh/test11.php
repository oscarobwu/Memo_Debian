<h3>Login Form</h3>
<form action="" method="POST">
Username: <input type="text" name="user"><br />
Password: <input type="password" name="pass"><br />
<input type="submit" value="Login" name="submit" />
</form>

<?php
if(isset($_POST["submit"])){
$user=$_POST['user'];
$pass=$_POST['pass'];
$url = 'https://172.19.4.166/mgmt/tm/ltm/virtual';

if ($argc > 1){
    $url = $argv[1];
}

$ch=curl_init();
// user credencial
//curl_setopt($ch, CURLOPT_USERPWD, "admin:admin");
curl_setopt($ch, CURLOPT_USERPWD, "$user:$pass");
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

#var_dump($response);
print_r($response);
}
?>

