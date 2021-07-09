<html>

<head>
        <title>Standard Virtual Service Automation</title>
    <style>body{filter:progid:DXImageTransform.Microsoft.gradient(startcolorstr=#86CEFF,endcolorstr=#000099,gradientType=1);}</style>
</head>

<body bgcolor="#EEEEEE">
</table>
        <center><h1><span style="background-color:#00ffff;">LTM - Standard Virtual Service Automation</span></h1></center>
                <form method='post'>
                <p>
        <table border=4 align=center width=60% bgcolor=yellow cellspacing=5 cellpadding=6>
                <tr><td>BIG-IP:  </td><td><input type='text' name='bigip' value='<?php echo $_POST['bigip'];?>'/></td></tr>
                <tr><td>Username:</td><td><input type='text' name='username' value='<?php echo $_POST['username'];?>'/></td></tr>
                <tr><td>Password:</td><td><input type='password' name='password' value='<?php echo $_SESSION['password'];?>'/></td></tr>
                <tr><td>Partition:</td><td><input type='text' name='partition' value='<?php echo $_SESSION['partition'];?>'/></td></tr>
                <tr><td colspan='2'><hr/></td></tr>
                <tr><td>Name:</td><td><input type='text' name='name' value='<?php echo $_REQUEST['name']; ?>'></input><br/>
                <tr><td>Address:</td><td><input type='text' name='address' value='<?php echo $_REQUEST['address']; ?>'></input><br/>
                <tr><td>Port:</td><td><input type='text' name='port' size='6' value='<?php echo $_REQUEST['port']; ?>'/></td></tr>
                <tr><td>Purpose:</td><td>
                <select name='purpose'>
                <option value='httpwan'>WAN Web Client</option>
                <option value='httplan'>LAN Web Client</option>
                <option value='httpmobile'>Mobile Web Client</option>
                </select></td></tr>
                <tr><td>Default Pool:</td><td><input type='text' name='default_pool' value='<?php echo $_REQUEST['default_pool']; ?>'></input><br/>
                <tr><td>Function:</td><td>
                <select name='funtion'>
                <option value='virtual' <?php if($_REQUEST['funtion'] == 'virtual'){ echo 'selected'; }?> >Default an Virtual</option>
                <option value='pool' <?php if($_REQUEST['funtion'] == 'pool'){ echo 'selected'; }?> >Pool</option>
                <option value='node' <?php if($_REQUEST['funtion'] == 'node'){ echo 'selected'; }?> >Node</option>
                <option value='profiles' <?php if($_REQUEST['funtion'] == 'proflies'){ echo 'selected'; }?> >Profile</option>
                </select>
                <input type='submit' value='Go'/></td></tr>
                </form>
                <tr><td colspan='2'><hr/></td></tr>
                </table>
                </p>
                <p>
                <?php
              if(! empty($result_text) ) {
                 echo "Last result:<br/>";
                 echo "<pre>".$result_text."</pre>";
              }
                ?>
                </p>
</body>
</html>
<?php
header('Content-Type: text/html; charset=utf-8');
$bigip=$_POST['bigip'];
$username=$_POST['username'];
$password=$_POST['password'];
$funtion=$_POST['funtion'];

$url = ("https://$bigip/mgmt/tm/ltm/$funtion");

if ($argc > 1){
    $url = $argv[1];
}

$ch=curl_init();
// user credencial
curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
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

#var_dump($response, JSON_PRETTY_PRINT ^ JSON_UNESCAPED_UNICODE);
//echo '<pre>';
//echo json_encode($response, JSON_PRETTY_PRINT ^ JSON_UNESCAPED_UNICODE);
$book = json_decode($response, true);
//$book = json_decode($response);
#print_r($book);
if ( $funtion == "virtual" ) {
    echo "have a virtual !";
    echo $book['kind'];
    echo '<br>';
    echo $book['items'][0]['name'];
    echo '<br>';
    echo '<style type="text/css">tr:nth-of-type(even) {background-color: #f3f3f3;} tr:nth-of-type(odd) { background-color: #48d1cc; }</style>';
    echo '<table border=4 align=center width=80% bgcolor=#D396FF cellspacing=5 cellpadding=6>';
    echo "<tr>
            <td></td>
            <td>VS_名稱</td>
            <td>Source IP</td>
            <td>destination IP:Port</td>
            <td>使用 Pool</td>
            <td>使用 Persisten</td>
            <td>最後修改時間</td>
          </tr>";
    $counter = 0;
    foreach($book['items'] as $sectionData)
    {
        $counter++;
        $n1n = $sectionData['name'];
        $n2n = $sectionData['source'];
        $n3n = $sectionData['destination'];
        $n4n = $sectionData['pool'];
                if ( $sectionData['persist'][0]['name'] == ""){
                    $n5n = "none";
                   } else {
                        $n5n = $sectionData['persist'][0]['name'];
               }
                $n6n = $sectionData['lastModifiedTime'];
            echo "<tr>
                  <td>$counter</td>
                  <td>$n1n</td>
                  <td>$n2n</td>
                  <td>$n3n</td>
                  <td>$n4n</td>
                  <td>$n5n</td>
                  <td>$n6n</td>
                  </tr>";
    }
    echo"</table>";
} elseif ( $funtion == "pool" ) {
    echo "Have a pool !";
    echo $book['kind'];
    echo '<br>';
    echo $book['items'][0]['name'];
    echo '<br>';
    echo '<style type="text/css">tr:nth-of-type(even) {background-color: #f3f3f3;} tr:nth-of-type(odd) { background-color: #48d1cc; }</style>';
    echo '<table border=4 align=center width=60% bgcolor=#D396FF cellspacing=5 cellpadding=6>';
    echo "<tr>
            <td></td>
            <td>Pool_名稱</td>
            <td>LB_方式</td>
            <td>監控方式</td>
            <td>使用 Pool</td>
          </tr>";
    $counter = 0;
    foreach($book['items'] as $sectionData)
    {
        $counter++;
        $n1n = $sectionData['name'];
        $n2n = $sectionData['loadBalancingMode'];
        $n3n = $sectionData['monitor'];
        $n4n = $sectionData['pool'];
            echo "<tr>
                  <td>$counter</td>
                  <td>$n1n</td>
                  <td>$n2n</td>
                  <td>$n3n</td>
                  <td>$n4n</td>
                  </tr>";
        }
    echo"</table>";
} elseif ( $funtion == "node" ) {
    echo "Have a node !";
    echo '<br>';
    echo '<br>';
    echo '<style type="text/css">tr:nth-of-type(even) {background-color: #f3f3f3;} tr:nth-of-type(odd) { background-color: #48d1cc; }</style>';
    echo '<table border=4 align=center width=60% bgcolor=#D396FF cellspacing=5 cellpadding=6>';
    echo "<tr>
            <td></td>
            <td>node_名稱</td>
            <td>Address_IP</td>
            <td>監控方式</td>
            <td>使用 Pool</td>
          </tr>";
    $counter = 0;
    foreach($book['items'] as $sectionData)
    {
        $counter++;
        $n1n = $sectionData['name'];
        $n2n = $sectionData['address'];
        $n3n = $sectionData['monitor'];
        $n4n = $sectionData['pool'];
            echo "<tr>
                  <td>$counter</td>
                  <td>$n1n</td>
                  <td>$n2n</td>
                  <td>$n3n</td>
                  <td>$n4n</td>
                  </tr>";
        }
    echo"</table>";
} else {
    echo "Have a Default !";
}

?>
