<html>
<head>
        <title>Standard Virtual Service Automation</title>
    <style>body{filter:progid:DXImageTransform.Microsoft.gradient(startcolorstr=#86CEFF,endcolorstr=#000099,gradientType=1);}</style>
<style type="text/css">
 　/*關於Tabel的CSS設定*/
　　.myTable {border-collapse:collapse;width: 300px;}
　　.myTable td {border: 1px solid #888888;padding: 10px;}
 　/*表格中的第一個tr : 虛擬選擇器*/
   .myTable tr:nth-child(1){background-color:#7788aa;color:#ffffff;}
  /*表格中的偶數對 tr*/
   .myTable tr:nth-child(even){background-color:#e8e8e8;}
  /*表格中的奇數對 tr*/
   .myTable tr:nth-child(odd){background-color:#0094ff;}

</style>
<script src='//cdnjs.cloudflare.com/ajax/libs/material-components-web/4.0.0/material-components-web.min.css'></script>
<script src='//cdn.datatables.net/1.10.25/css/dataTables.material.min.css'></script>
<script src='https://code.jquery.com/jquery-3.5.1.js'></script>
<script src='https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js'></script>
<script src='https://cdn.datatables.net/1.10.25/js/dataTables.material.min.js'></script>
<!--link href='//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css' rel='stylesheet'></link-->
<link href='//cdn.datatables.net/1.10.25/css/dataTables.material.min.css' rel='stylesheet'></link>
</head>

<body bgcolor="#EEEEEE">
                <center><h1><span style="background-color:#00ffff;">LTM - Standard Virtual Service Automation</span></h1></center>
                <form class="form-horizontal" action="" method="post" enctype="multipart/form-data" role="form" name="form1" >
                <p>
                <table border=4 width=400 bgcolor=yellow cellspacing=5 cellpadding=6 class="myTable" >
                <tr><td>BIG-IP:  </td><td><input type='text' name='bigip' width="200" style="width: 200px" value='<?php echo $_POST['bigip'];?>'/></td></tr>
                <tr><td>Username:</td><td><input type='text' name='username' width="200" style="width: 200px" value='<?php echo $_POST['username'];?>'/></td></tr>
                <tr><td>Password:</td><td><input type='password' name='password' width="200" style="width: 200px" value='<?php echo $_SESSION['password'];?>'/></td></tr>
                <tr><td colspan='2'><hr/></td></tr>
                <tr><td>Function:</td><td>
                <select name='funtion' width="200" style="width: 200px" >
                <option value='virtual' <?php if($_REQUEST['funtion'] == 'virtual'){ echo 'selected'; }?> >Virtual</option>
                <option value='pool' <?php if($_REQUEST['funtion'] == 'pool'){ echo 'selected'; }?> >Pool</option>
                <option value='node' <?php if($_REQUEST['funtion'] == 'node'){ echo 'selected'; }?> >Node</option>
                <option value='profiles' <?php if($_REQUEST['funtion'] == 'proflies'){ echo 'selected'; }?> >Profile</option>
                </select>
                <button type="submit" class="btn btn-lg btn-primary" id="submit_once" onclick="dosubmit()">Go</button>
                </form>
                </table>
                </p>
<script type="text/javascript">
  function dosubmit() {
  var btnSubmit = document.getElementById("submit_once");
  //将表单提交按钮设置为不可用，可以避免用户再次点击提交按钮进行提交
  btnSubmit.disabled = "true";
  document.form1.submit();
  }
</script>
<script language="JavaScript">
document.write("Last updated on " + document.lastModified + ".")
</script>
<script language="JavaScript">
$(document).ready(function() {
    $('#myTable').DataTable();
} );
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
//* $profun = $book['items'][0]['name']; *//
//
        //* #echo $vs_name = ""; foreach($book['items'][0]['name'] as $k) { $vs_name .= "$k<br>"; }*//
#print_r($book);
if ( $funtion == "virtual" ) {
    echo "have a virtual !";
    echo $book['kind'];
    echo '<br>';
    echo '<br>';
    echo '<style type="text/css">tr:nth-of-type(even) {background-color: #f3f3f3;} tr:nth-of-type(odd) { background-color: #48d1cc; }</style>';
    echo '<table border=4 align=center width=95% bgcolor=#D396FF cellspacing=5 cellpadding=6 class="mynode">';
    echo '<thead>';
    echo "<tr>
            <th></th>
            <th>VS_名稱</th>
            <th>VS_描述說明</th>
            <th>Source IP</th>
            <th>destination IP:Port</th>
            <th>Profile_All</th>
            <th>Client_SSL</th>
            <th>Server_SSL</th>
            <th>SNAT Pool</th>
            <th>使用 Pool</th>
            <th><div style=\"width: 250px;\">使用 Pool_member </div></th>
            <th>使用 Persisten</th>
            <th>使用 Vlan</th>
            <th>使用 iRule</th>
            <th><div style=\"width: 200px;\">最後修改時間</div></th>
            <th>mirror</th>
          </tr>
          </thead>
          <tbody>";
    $counter = 0;
    foreach($book['items'] as $sectionData)
    {
        $n11n = "";
        // profile
        $profun = $sectionData['name'];
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
            //$n10n = "";
                    $npn = "";
                    $ncn = "";
                    $nsn = "";
            foreach($book_profiles['items'] as $sectionProfile)
                {
                                 if ( $sectionProfile['context'] == 'all') {
                                         $npnn = $sectionProfile['name'];
                                         $npn .= "$npnn<br>";}
                                 if ( $sectionProfile['context'] == 'clientside') {
                                         $ncnn = $sectionProfile['name'];
                                         $ncn .= "$ncnn<br>";}
                                 if ( $sectionProfile['context'] == 'serverside') {
                                         $nsnn = $sectionProfile['name'];
                                         $nsn .= "$nsnn<br>";}
                         $n11nn = $sectionProfile['name'];
                         $n11n .= "$n11nn<br>";
                }
        //
        // pool_member
        //$pool_mb = $sectionData['pool'];
        $pool_mb = ""; $p = explode("/", $sectionData['pool']); $pool_mb .= "$p[2]";
        $url_pool_mb = ("https://".$_SESSION['hostname']."/mgmt/tm/ltm/pool/~Common~$pool_mb/members");
        $ch2=curl_init();
        // user credencial
        curl_setopt($ch2, CURLOPT_USERPWD, $_SESSION['username'].":".$_SESSION['password']);
        curl_setopt($ch2, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch2, CURLOPT_URL, $url_pool_mb);
        curl_setopt($ch2, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch2, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
        curl_setopt($ch2, CURLOPT_VERBOSE, true);
        curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
        $response_pool_mb = curl_exec($ch2);
        curl_close($ch2);
        $book_pool_mb = json_decode($response_pool_mb, true);
            //$n10n = "";
            $npmn = "";
            foreach($book_pool_mb['items'] as $section_pool_mb)
                {
                         $npmnn = $section_pool_mb['name'];
                         $npmsnn = $section_pool_mb['state'];
                         $npmn .= "$npmnn - $npmsnn<br>";
                }
        //
        $counter++;
        $n1n = $sectionData['name'];
        $n1an = $sectionData['description'];
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
        $nirorn = $sectionData['mirror'];
        if ( $sectionData['sourceAddressTranslation']['type'] == "snat"){
            $nsnatn = $sectionData['sourceAddressTranslation']['pool'];
           } else {
            $nsnatn = $sectionData['sourceAddressTranslation']['type'];
           }
            echo "<tr>
                  <td align=center>$counter</td>
                  <td>$n1n</td>
                  <td>$n1an</td>
                  <td>$n2n</td>
                  <td>$n3n</td>
                  <td>$npn</td>
                  <td>$ncn</td>
                  <td>$nsn</td>
                  <td>$nsnatn</td>
                  <td>$n4n</td>
                  <td>$npmn</td>
                  <td>$n5n</td>
                  <td>$n6n</td>
                  <td>$n7n</td>
                  <td>$n8n</td>
                  <td>$nirorn</td>
                  </tr>";
    }
    echo"</tbody>";
    echo"</table>";
    echo "<script>
    $('#myTable').DataTable({
        autoWidth: false,
        columnDefs: [
            {
                targets: ['_all'],
                className: 'mdc-data-table__cell'
            }
        ]
    language: {
    \"emptyTable\": \"無資料...\",
    \"processing\": \"處理中...\",
    \"loadingRecords\": \"載入中...\",
    \"lengthMenu\": \"每頁 _MENU_ 筆資料\",
    \"zeroRecords\": \"無搜尋結果\",
    \"info\": \"_START_ 至 _END_ / 共 _TOTAL_ 筆\",
    \"infoEmpty\": \"尚無資料\",
    \"infoFiltered\": \"(從 _MAX_ 筆資料過濾)\",
    \"infoPostFix\": \"\",
    \"search\": \"搜尋字串:\",
    \"paginate\": {
    \"first\": \"首頁\",
    \"last\": \"末頁\",
    \"next\": \"下頁\",
    \"previous\": \"前頁\"
    },
    \"aria\": {
    \"sortAscending\": \": 升冪\",
    \"sortDescending\": \": 降冪\"
    }
    }
    });
    </script>";
    echo"<script language=\"JavaScript\">
    document.write(\"end job on \" + document.lastModified + \".\")
    </script>";
} elseif ( $funtion == "pool" ) {
    echo "Have a pool !";
    echo $book['kind'];
    echo '<br>';
    echo $book['items'][0]['name'];
    echo '<br>';
    echo '<style type="text/css"> .mynode { background-color: #48d1cc; } .mynode tr:nth-child(1){background-color:#7788aa;color:#ffffff;} .mynode tr:nth-child(even){background-color:#e8e8e8;} .mynode tr:nth-child(odd){background-color:#0094ff;} table.dataTable tbody tr:hover { background-color: #ffff00; } </style>';
    echo '<table border=4 align=center width=80% bgcolor=#D396FF cellspacing=5 cellpadding=6 class="mynode">';
    echo "<tr>
            <td></td>
            <td>Pool_名稱</td>
            <td>LB_方式</td>
            <td>監控方式</td>
            <td>使用 Pool_member</td>
          </tr>";
    $counter = 0;
    foreach($book['items'] as $sectionData)
    {
        $pool_mb = $sectionData['name'];
        $url_pool_mb = ("https://".$_SESSION['hostname']."/mgmt/tm/ltm/pool/~Common~$pool_mb/members");
        $ch2=curl_init();
        // user credencial
        curl_setopt($ch2, CURLOPT_USERPWD, $_SESSION['username'].":".$_SESSION['password']);
        curl_setopt($ch2, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch2, CURLOPT_URL, $url_pool_mb);
        curl_setopt($ch2, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch2, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
        curl_setopt($ch2, CURLOPT_VERBOSE, true);
        curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
        $response_pool_mb = curl_exec($ch2);
        curl_close($ch2);
        $book_pool_mb = json_decode($response_pool_mb, true);
            //$n10n = "";
            $npmn = "";
            foreach($book_pool_mb['items'] as $section_pool_mb)
                {
                         $npmnn = $section_pool_mb['name'];
                         $npmsnn = $section_pool_mb['state'];
                         $npmn .= "$npmnn - $npmsnn<br>";
                }
        //
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
                  <td>$npmn</td>
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
