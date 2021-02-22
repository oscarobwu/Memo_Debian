<?php
if (!function_exists("ssh2_connect")) die("function ssh2_connect doesn't exist");
// log in at server1.example.com on port 22
if (!($con = ssh2_connect("192.168.96.113", 22))) {
    echo "fail: unable to establish connection\n";
} else {
    // try to authenticate with username root, password secretpassword
    if (!ssh2_auth_password($con, "root", "1q2w3e4r")) {
        echo "fail: unable to authenticate\n";
    } else {
        // allright, we're in!
        echo "okay: logged in...\n";

        // create a shell
        if (!($shell = ssh2_shell($con, 'vt102', null, 80, 40, SSH2_TERM_UNIT_CHARS))) {
            echo "fail: unable to establish shell\n";
        } else {
            stream_set_blocking($shell, true);
            // send a command
            fwrite($shell, "ls -al\n");
            sleep(1);

            // & collect returning data
            $data = "";
            while ($buf = fread($shell,4096)) {
                $data .= $buf;
            }
            fclose($shell);
        }
    }
}
?>
