<?php
/*server.php*/
$cmds = array ('ls -al', 'ls -al /home/oscarwu');

$connection = ssh2_connect('192.168.96.113', 22);
ssh2_auth_password($connection, 'root', '1q2w3e4r');

foreach ($cmds as $cmd) {
    $stream = ssh2_exec($connection, $cmd);
    stream_set_blocking($stream, true);
    $stream_out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
	print_r($cmd . '<br><br>');
    #echo stream_get_contents($stream_out) . '<br><br>';
	$output = stream_get_contents($stream_out);
	echo "<pre>{$output}</pre>";
}
?>
