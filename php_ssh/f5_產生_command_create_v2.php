<html>
<head>
<title>Standard Virtual Service Config explode</title>
<meta charset="utf-8">
</head>
<body>
        <table>
                <tr>
                        <th>LTM:</th>
                        <td><a href='node.php'>Node</a></td>
                        <td><a href='pool.php'>Pool</a></td>
                        <td><a href='poolmember.php'>Pool Member</a></td>
                        <td><a href='virtualservice.php'>Standard Virtual Service</a></td>
                </tr>
        </table>
        <table>
                <tr>
                        <th>GTM:</th>
                        <td><a href='gtmserver.php'>Server</a></td>
                        <td><a href='gtmvirtualserver.php'>Virtual Server</a></td>
                        <td><a href='gtmpool.php'>Pool</a></td>
                        <td><a href='gtmpoolmember.php'>Pool Member</a></td>
                        <td><a href='gtmwideip.php'>WideIP</a></td>
                </tr>
        </table>
        <h1>LTM 設定檔網頁產生器 - Standard Virtual Service Config explode</h1>
<form action="#" method="POST">
<!--tr><td>輸入VIP:</td><td><input type='text' name='vip_address' value='<?php $var = '10.100.2.100'; echo $_SESSION[ $var ];?>'/></td></tr-->
<tr><td>輸入VIP : </td><td><input type="text" name="vip_address" value="<?php $vip_address = '99.99.99.100'; if(isset($_POST['vip_address'])) echo strip_tags($_POST['vip_address']); ?>"/></td></tr>
<br/>
<h3>輸入 提供服務的 Port</h3>
<textarea name="vip_port" rows="8" cols="45">
80
443
21</textarea>
<br/>
<h3>輸入 Node_IP_Address</h3>
<textarea name="node" rows="8" cols="45">
192.68.88.111
192.68.88.112
192.68.88.113</textarea>
<br/>
<input type="submit" name="SubmitButton" />
</form>
</body>
</html>
<?php
header('Content-Type: text/html; charset=utf-8');
if (isset($_POST['SubmitButton'])) {
    $counter = 0;
    $vip_address = $_POST['vip_address'];
    $nodeaddress = $_POST['node'];
    $nodeaddress_arr = explode( "\n", $nodeaddress );
    #$nodeaddress_list = rtrim($nodeaddress_arr, '  ');
    #echo $nodeaddress_arr;
    foreach ($nodeaddress_arr as $ndlist) {
        //$counter++;
        $ndolist = trim($ndlist); //處理空白行
        if ($ndolist != "") {
            $counter++;
            //echo '[' .$counter .'}' .' ===> https://' . $ndlist . '<br>';
                        echo 'tmsh create ltm node node_' . $ndlist . '  address ' . $ndlist . '<br>';
        }
    }
        echo '<br>';
    $vip_port = $_POST['vip_port'];
    $vip_port_arr = explode( "\n", $vip_port );
        foreach ($vip_port_arr as $n_vipportlist) {
        $nvipportlist = trim($n_vipportlist); //處理空白行
        if ($nvipportlist != "") {
        //$counter++;
            $counter++;
            //echo '[' .$counter .'}' .' ===> https://' . $ndlist . '<br>';
                        echo 'tmsh create ltm monitor tcp-half-open TCP-' . $n_vipportlist . ' destination *:' . $n_vipportlist . '<br>';
        }
    }
        echo '<br>';
    $vip_port = $_POST['vip_port'];
    $vip_port_arr = explode( "\n", $vip_port );
        foreach ($vip_port_arr as $n_vipportlist) {
        $nvipportlist = trim($n_vipportlist); //處理空白行
        if ($nvipportlist != "") {
        //$nodeaddress = $_POST['node'];
        //$nodeaddress_arr = explode( "\n", $nodeaddress );
            foreach ($nodeaddress_arr as $ndlist) {
               //$counter++;
               $ndolist = trim($ndlist); //處理空白行
               if ($ndolist != "") {
                   $counter++;
                   $cip = trim($ndlist); //處理空白行
                        //echo 'tmsh create ltm node node_' . $ndlist . '  address ' . $ndlist . '<br>';
                        echo 'tmsh create ltm pool pool_' . $vip_address . '_' . $n_vipportlist . '  monitor TCP-' . $n_vipportlist . 'load-balancing-mode least-connections-member members add { node_' . $cip . ':' . $n_vipportlist . ' { address ' . $cip . ' } }' . '<br>';
               }
                }
        }
        echo '<br>';
    }
}
?>
