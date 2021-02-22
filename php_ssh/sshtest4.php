<?php
$connection = ssh2_connect('192.168.96.113', 22);
ssh2_auth_password($connection, 'root', '1q2w3e4r');

#$stream = ssh2_exec($connection, '/usr/local/bin/php -i');
$stream = ssh2_exec($connection, 'ls -al');
print_r($stream)
?>
