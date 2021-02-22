
<h3>Login Form</h3>
<form action="" method="POST">
SSH-Host: <input type="text" name="sship"><br />
SSH-Port: <input type="text" name="sshp"><br />
Username: <input type="text" name="user"><br />
Password: <input type="password" name="pass"><br />
Command: <input type="text" name="command"><br />
<input type="submit" value="Login" name="submit" />
<input type="reset" value="RESET" name="reset" />
</form>
<?php
/*server.php*/
if(isset($_POST["submit"])){
$sshhost=$_POST['sship'];
$sshport=$_POST['sshp'];
$user=$_POST['user'];
$pass=$_POST['pass'];
$comm=$_POST['command'];
$connection_string = ssh2_connect("$sshhost", $sshport);

// $connection_string = ssh2_connect('127.0.0.1', 22);

#if (@ssh2_auth_password($connection_string, 'root', '1q2w3e4r'))
if (@ssh2_auth_password($connection_string, $user, $pass))
{
	echo "Authentication Successful!\n";
}
else
{
	throw new Exception("Authentication failed!");
}

#$stream = ssh2_exec($connection_string, 'ls -al');
$stream = ssh2_exec($connection_string, $comm);
stream_set_blocking($stream, true);
$stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
#echo stream_get_contents($stream_out);
$output =  stream_get_contents($stream_out);
echo "<pre>{$output}</pre>";
}
?>
