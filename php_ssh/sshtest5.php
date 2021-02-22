<?php
$connection = @ssh2_connect('192.168.96.113', 22);

if (@ssh2_auth_password($connection, 'root', '1q2w3e4ri')) {
    echo "Authentication Successful!\n";
    $stream = ssh2_exec($connection, 'ls -al');
    print_r($stream);
} else {
    die('Authentication Failed...');
}
?>
