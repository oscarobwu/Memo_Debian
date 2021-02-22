<?php
$con=ssh2_connect('192.168.19.113', 22);
ssh2_auth_password($con, 'root', '1q2w3e4r');
$shell=ssh2_shell($con, 'xterm');
$stream = ssh2_exec($con, 'ls -ltrapR');
stream_set_blocking($stream, true);
$out = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
echo stream_get_contents($out);
?>
