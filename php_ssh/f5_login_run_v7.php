<html>
<head>
        <title>Standard Virtual Service Automation</title>
    <style>body{filter:progid:DXImageTransform.Microsoft.gradient(startcolorstr=#86CEFF,endcolorstr=#000099,gradientType=1);}</style>
<style type="text/css">
 　/*關於Tabel的CSS設定*/
　　.myTable {border-collapse:collapse;width: 400px;}
　　.myTable td {border: 1px solid #888888;padding: 10px;}
 　/*表格中的第一個tr : 虛擬選擇器*/
   .myTable tr:nth-child(1){background-color:#7788aa;color:#ffffff;}
  /*表格中的偶數對 tr*/
   .myTable tr:nth-child(even){background-color:#e8e8e8;}
  /*表格中的奇數對 tr*/
   .myTable tr:nth-child(odd){background-color:#0094ff;}
</style>
</head>

<body bgcolor="#EEEEEE">
                <center><h1><span style="background-color:#00ffff;">LTM - Standard Virtual Service Automation</span></h1></center>
                <form method='post'>
                <p>
                <table border=4 align=center width=60% bgcolor=yellow cellspacing=5 cellpadding=6 class="myTable">
                <tr><td>BIG-IP:  </td><td><input type='text' name='bigip' width="200" style="width: 200px" value='<?php echo $_POST['bigip'];?>'/></td></tr>
                <tr><td>Username:</td><td><input type='text' name='username' width="200" style="width: 200px" value='<?php echo $_POST['username'];?>'/></td></tr>
                <tr><td>Password:</td><td><input type='password' name='password' width="200" style="width: 200px" value='<?php echo $_SESSION['password'];?>'/></td></tr>
                <tr><td>Partition:</td><td><input type='text' name='partition' width="200" style="width: 200px" value='<?php echo $_SESSION['partition'];?>'/></td></tr>
                <tr><td colspan='2'><hr/></td></tr>
                <tr><td>Name:</td><td><input type='text' name='name' width="200" style="width: 200px" value='<?php echo $_REQUEST['name']; ?>'></input><br/>
                <tr><td>Address:</td><td><input type='text' name='address' width="200" style="width: 200px" value='<?php echo $_REQUEST['address']; ?>'></input><br/>
                <tr><td>Port:</td><td><input type='text' name='port' size='6' value='<?php echo $_REQUEST['port']; ?>'/></td></tr>
                <tr><td>Default Pool:</td><td><input type='text' name='default_pool' value='<?php echo $_REQUEST['default_pool']; ?>'></input><br/>
                <tr><td>Function:</td><td>
                <select name='funtion' width="200" style="width: 200px" >
                <option value='virtual' <?php if($_REQUEST['funtion'] == 'virtual'){ echo 'selected'; }?> >Virtual</option>
                <option value='pool' <?php if($_REQUEST['funtion'] == 'pool'){ echo 'selected'; }?> >Pool</option>
                <option value='node' <?php if($_REQUEST['funtion'] == 'node'){ echo 'selected'; }?> >Node</option>
                <option value='profiles' <?php if($_REQUEST['funtion'] == 'proflies'){ echo 'selected'; }?> >Profile</option>
                </select>
                <input type='submit' value='Go'/></td></tr>
                </form>
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
<script language="JavaScript">
document.write("Last updated on " + document.lastModified + ".")
</script>
<br>
</body>
</html>
<?php
header('Content-Type: text/html; charset=utf-8');
    session_start();
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
                if(! ( empty($_REQUEST['username']) ||  empty($_REQUEST['password']) ||  empty($_REQUEST['bigip']) )) {
                        $_SESSION['username'] = $_REQUEST['username'];
                        $_SESSION['password'] = $_REQUEST['password'];
                        $_SESSION['hostname'] = $_REQUEST['bigip'];
                }

        }
$funtion=$_POST['funtion'];
#
$url = ("https://".$_SESSION['hostname']."/mgmt/tm/ltm/$funtion");
#
if ($argc > 1){
    $url = $argv[1];
}

$ch=curl_init();
// user credencial
curl_setopt($ch, CURLOPT_USERPWD, $_SESSION['username'].":".$_SESSION['password']);
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
            <td>使用 Vlan</td>
            <td>使用 iRule</td>
            <td>最後修改時間</td>
            <td>Profile</td>
          </tr>";
    $counter = 0;
    foreach($book['items'] as $sectionData)
    {
        $counter++;
        $n1n = $sectionData['name'];
        $n2n = $sectionData['source'];
        $n3n = ""; $p = explode("/", $sectionData['destination']); $n3n .= "$p[2]";
        $n4n = ""; $p = explode("/", $sectionData['pool']); $n4n .= "$p[2]";
        if ( $sectionData['persist'][0]['name'] == ""){
            $n5n = "none";
           } else {
            $n5n = $sectionData['persist'][0]['name'];
           }
                $n6n = ""; foreach($sectionData['vlans'] as $k){ $p = explode("/", $k); $n6n .= "$p[2]<br>"; }
                $n7n = ""; foreach($sectionData['rules'] as $k){ $p = explode("/", $k); $n7n .= "$p[2]<br>"; }
        $n8n = $sectionData['lastModifiedTime'];
        $profun=$_POST['$n1n'];
        $url_profiles = ("https://".$_SESSION['hostname']."/mgmt/tm/ltm/virtual/$profun/profiles");
        $ch1=curl_init();
        // user credencial
        curl_setopt($ch1, CURLOPT_USERPWD, $_SESSION['username'].":".$_SESSION['password']);
        curl_setopt($ch1, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch1, CURLOPT_URL, $url_profiles);
        curl_setopt($ch1, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
        curl_setopt($ch1, CURLOPT_VERBOSE, true);
        curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
        $response_profiles = curl_exec($ch1);
        curl_close($ch1);
        $book_profiles = json_decode($response_profiles, true);
                foreach($book_profiles['items'] as $sectionProfile)
                { $n10n = $sectionProfile['name'];}
            echo "<tr>
                  <td>$counter</td>
                  <td>$n1n</td>
                  <td>$n2n</td>
                  <td>$n3n</td>
                  <td>$n4n</td>
                  <td>$n5n</td>
                  <td>$n6n</td>
                  <td>$n7n</td>
                  <td>$n8n</td>
                  <td>$n10n</td>
                  </tr>";
    }
    echo"</table>";
} elseif ( $funtion == "pool" ) {
    echo "Have a pool !";
    echo $book['kind'];
    echo '<br>';
    echo $book['items'][0]['name'];
    echo '<br>';
    echo '<style type="text/css"> .mynode { background-color: #48d1cc; } .mynode tr:nth-child(1){background-color:#7788aa;color:#ffffff;} .mynode tr:nth-child(even){background-color:#e8e8e8;} .mynode tr:nth-child(odd){background-color:#0094ff;} </style>';
    echo '<table border=4 align=center width=80% bgcolor=#D396FF cellspacing=5 cellpadding=6 class="mynode">';
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
    echo '<table border=4 align=center width=80% bgcolor=#D396FF cellspacing=5 cellpadding=6>';
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

##############
$post = [
   'teste' => $_POST['teste']
];
httpPost('url.com', $post);
// function
function httpPost($url, $data)
{
   	$curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}
