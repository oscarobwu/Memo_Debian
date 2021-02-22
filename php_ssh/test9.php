<!doctype html>
<html>
<head>
<title>Login</title>
    <style>
        body{

    margin-top: 100px;
    margin-bottom: 100px;
    margin-right: 150px;
    margin-left: 80px;
    background-color: azure ;
    color: palevioletred;
    font-family: verdana;
    font-size: 100%

        }
            h1 {
    color: indigo;
    font-family: verdana;
    font-size: 100%;
}
        h3 {
    color: indigo;
    font-family: verdana;
    font-size: 100%;
} </style>
</head>
<body>
     <center><h1>CREATE REGISTRATION AND LOGIN FORM USING PHP AND MYSQL</h1></center>
   <p><a href="register.php">Register</a> | <a href="login.php">Login</a></p>
<h3>Login Form</h3>
<form action="" method="POST">
F5-Host: <input type="text" name="f5mgmt"><br />
Username: <input type="text" name="user"><br />
Password: <input type="password" name="pass"><br />
<input type="submit" value="Login" name="submit" />
</form>

<?php
if(isset($_POST["submit"])){
$f5mgmt=$_POST['f5mgmt'];
$user=$_POST['user'];
$pass=$_POST['pass'];
$url = "https://$f5mgmt/mgmt/tm/ltm/virtual";

if ($argc > 1){
    $url = $argv[1];
}

$ch=curl_init();
// user credencial
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
foreach($arr['items'] as $pss_json)
{

    echo '<br>VS : ' .$pss_json["name"];
    echo '<br>IP : ' .$pss_json["destination"];
    echo '<br>Pool : ' .$pss_json["pool"];
    echo '<br>Rule : ' .$pss_json["rules"] . '<br>';

}
#$filter = [name,destination];
#$links = json_decode($response)->links;
#$filtered = array_filter($links, function ($item) use ($filter) {
#    return in_array($item->id, $filter);
#});
#
#print_r($filtered);
}
?>
</body>
</html>
