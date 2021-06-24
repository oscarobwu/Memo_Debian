<html>

<head>
        <title>Standard Virtual Service Automation</title>
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
        <h1><span style="background-color:#00ffff;">LTM - Standard Virtual Service Automation</span></h1>
                <form method='post'>
                <p>
                <table>
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
                <option value='virtual' <?php if($_REQUEST['funtion'] == 'virtual'){ echo 'selected'; }?> >Pick an Virtual>
                <option value='pool' <?php if($_REQUEST['funtion'] == 'pool'){ echo 'selected'; }?> >Pool</option>
                <option value='node' <?php if($_REQUEST['funtion'] == 'node'){ echo 'selected'; }?> >Node</option>
                <option value='profiles' <?php if($_REQUEST['funtion'] == 'proflies'){ echo 'selected'; }?> >Profile</option>
                </select>
                <tr><td>Action:</td><td>
                <select name='action'>
                <option value='none' <?php if($_REQUEST['action'] == 'none'){ echo 'selected'; }?> >Pick an Action</option>
                <option value='list' <?php if($_REQUEST['action'] == 'list'){ echo 'selected'; }?> >list</option>
                <option value='create' <?php if($_REQUEST['action'] == 'create'){ echo 'selected'; }?> >create</option>
                <option value='disable' <?php if($_REQUEST['action'] == 'disable'){ echo 'selected'; }?> >disable</option>
                <option value='enable' <?php if($_REQUEST['action'] == 'enable'){ echo 'selected'; }?> >enable</option>
                <option value='delete' <?php if($_REQUEST['action'] == 'delete'){ echo 'selected'; }?> >delete</option>
                <option value='stats' <?php if($_REQUEST['action'] == 'stats'){ echo 'selected'; }?> >stats</option>
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
    echo "<table border=1 cellpadding=5 >";
    echo "<tr>
            <td>VS_名稱</td>
            <td>Source IP</td>
            <td>destination IP:Port</td>
            <td>使用 Pool</td>
          </tr>";
    foreach($book['items'] as $sectionData)
    {
        $n1n = $sectionData['name'];
        $n2n = $sectionData['source'];
        $n3n = $sectionData['destination'];
        $n4n = $sectionData['pool'];
            echo "<tr>
                  <td>$n1n</td>
                  <td>$n2n</td>
                  <td>$n3n</td>
                  <td>$n4n</td>
                  </tr>";
    }
    echo"</table>";
} elseif ( $funtion == "pool" ) {
    echo "Have a pool !";
    echo $book['kind'];
    echo '<br>';
    echo $book['items'][0]['name'];
    echo '<br>';
    echo "<table border=1 cellpadding=5 >";
    echo "<tr>
            <td>Pool_名稱</td>
            <td>LB_方式</td>
            <td>監控方式</td>
            <td>使用 Pool</td>
          </tr>";
    foreach($book['items'] as $sectionData)
    {
        $n1n = $sectionData['name'];
        $n2n = $sectionData['loadBalancingMode'];
        $n3n = $sectionData['monitor'];
        $n4n = $sectionData['pool'];
            echo "<tr>
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
