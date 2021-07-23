<?php
// Start the buffering //
ob_start();
?>
<?php
$var = 'PHP Tutorial';
$now = date_create()->format('Y-m-d_H:i:s');
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $now;?>_Virtual_Service_<?php echo $_POST['bigip']; ?></title>
        <style>body{filter:progid:DXImageTransform.Microsoft.gradient(startcolorstr=#86CEFF,endcolorstr=#000099,gradientType=1);}</style>
</head>
<a href ="#" id="donwload-link" onClick="myFunction()">下載狀態檔 html </a>

<script>
function myFunction() {
  var content = document.documentElement.innerHTML;
download(content, document.title, "html")

}
function download(content, fileName, fileType) {
  var link = document.getElementById("donwload-link");
  var file = new Blob([content], {type: fileType});
  var donwloadFile = fileName + "." + fileType;
  link.href = URL.createObjectURL(file);
  link.download = donwloadFile
}
</script>
<br>
<script language="JavaScript">
document.write("Last updated on " + document.lastModified + ".")
</script>
<br>
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
$url_host = ("https://".$_SESSION['hostname']."/mgmt/tm/cm/device");
#
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
$book = json_decode($response, true);
//
$ch_host=curl_init();
// user credencial
curl_setopt($ch_host, CURLOPT_USERPWD, $_SESSION['username'].":".$_SESSION['password']);
curl_setopt($ch_host, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch_host, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch_host, CURLOPT_URL, $url_host);
curl_setopt($ch_host, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch_host, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
curl_setopt($ch_host, CURLOPT_VERBOSE, true);
curl_setopt($ch_host, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch_host, CURLOPT_SSL_VERIFYPEER, false);
$response_host = curl_exec($ch_host);
curl_close($ch_host);
$book_host = json_decode($response_host, true);
foreach($book_host['items'] as $host ) { $hostname = $host['name']; }
//
$g_color = "bgcolor=Lime";
$r_color = "bgcolor=red";
$b_color = "bgcolor=LightSkyBlue";
$d_color = "bgcolor=DeepSkyBlue";
$t_color = "bgcolor=#EAF2D3";
$w_color = "bgcolor=WhiteSmoke";
$f_color = "bgcolor=GoldenRod";
$l_color = "bgcolor=#48d1cc";
if ( $funtion == "virtual" ) {
    echo '<table border=4 align=center width=95% bgcolor=#D396FF cellspacing=5 cellpadding=6>';
    echo '<thead>';
        echo "<caption> $now Virtual_Service".$_SESSION['hostname']."_Table</caption>";
    echo "<tr $f_color><th colspan=\"19\">$hostname</th></tr>";
    echo "<tr $f_color>
            <th rowspan=\"1\"></th>
            <th rowspan=\"1\">VS_名稱</th>
            <th rowspan=\"1\">VS_描述說明</th>
            <th rowspan=\"1\">Source IP</th>
            <th rowspan=\"1\">destination IP:Port</th>
            <th rowspan=\"1\">Profile_All</th>
            <th rowspan=\"1\">Client_SSL</th>
            <th rowspan=\"1\">Server_SSL</th>
            <th rowspan=\"1\">SNAT Pool</th>
            <th rowspan=\"1\">使用 Pool</th>
            <th rowspan=\"1\"><div style=\"width: 250px;\">使用 Pool_member </div></th>
            <th rowspan=\"1\"><div style=\"width: 100px;\">Pool_member status </div></th>
            <th rowspan=\"1\">使用 Persisten</th>
            <th rowspan=\"1\">使用 Vlan</th>
            <th rowspan=\"1\">使用 iRule</th>
            <th rowspan=\"1\"><div style=\"width: 200px;\">最後修改時間</div></th>
            <th rowspan=\"1\">mirror</th>
            <th rowspan=\"1\">Action On Service Down</th>
          </tr>
          </thead>
          <tbody>";
    $counter = 0;
    foreach($book['items'] as $sectionData)
    {
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
            }
        //
        // pool_member
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
        $npmn = "";
        $tr_npmn = "";
        $pm_counter = 0;
        foreach($book_pool_mb['items'] as $section_pool_mb)
            {
                $pm_counter++;
                $npmnn = $section_pool_mb['name'];
                $npmsnn = $section_pool_mb['state'];
                if ( $pm_counter == '1'){
                    if ($npmsnn == "up"){
                        $npmn .= "<td $g_color>$pm_counter. $npmnn</td><td $g_color>$npmsnn</td>";
                    } elseif ( $npmsnn == "unchecked" ) {
                        $npmn .= "<td $d_color>$pm_counter. $npmnn</td><td $d_color>$npmsnn</td>";
                    } else {
                        $npmn .= "<td $r_color>$pm_counter. $npmnn</td><td $r_color>$npmsnn</td>";
                    }
                } else {
                    if ($npmsnn == "up"){
                        $tr_npmn .= "<tr><td $g_color>$pm_counter. $npmnn</td><td $g_color>$npmsnn</td></tr>";
                    } elseif ( $npmsnn == "unchecked" ) {
                        $npmn .= "<tr><td $d_color>$pm_counter. $npmnn</td><td $d_color>$npmsnn</td></tr>";
                    } else {
                        $tr_npmn .= "<tr><td $r_color>$pm_counter. $npmnn</td><td $r_color>$npmsnn</td></tr>";
                    }
                }
            }
        //
        if ($pm_counter == "0" or $pm_counter == "1") {
            $pmm_counter = '1';
        } else {
            $pmm_counter = $pm_counter;
        }
        if ($npmn == "") {
            $pnpmn = "<td $b_color>None</td><td bgcolor=LightSkyBlue>None</td>";
            $tr_pnpmn = "";
        } else {
            $pnpmn = $npmn;
            $tr_pnpmn = $tr_npmn;
        }
        $counter++;
        $n1n = $sectionData['name'];
        $n1an = $sectionData['description'];
        $n2n = $sectionData['source'];
        $n3n = ""; $p = explode("/", $sectionData['destination']); $n3n .= "$p[2]";
        if ( $sectionData['pool'] == ""){
            $n4n = "none";
           } else {
            $n4n = ""; $p = explode("/", $sectionData['pool']); $n4n .= "$p[2]";
           }
        if ( $sectionData['persist'][0]['name'] == ""){
            $n5n = "none";
           } else {
            $n5n = $sectionData['persist'][0]['name'];
           }
        $n6n = ""; foreach($sectionData['vlans'] as $k){ $p = explode("/", $k); $n6n .= "$p[2]<br>"; }
        $n7n = ""; foreach($sectionData['rules'] as $k){ $p = explode("/", $k); $n7n .= "$p[2]<br>"; }
        $n8n = $sectionData['lastModifiedTime'];
        $nirorn = $sectionData['mirror'];
        $serdown = $sectionData['serviceDownImmediateAction'];
        if ( $sectionData['sourceAddressTranslation']['type'] == "snat"){
            $nsnatn = $sectionData['sourceAddressTranslation']['pool'];
           } else {
            $nsnatn = $sectionData['sourceAddressTranslation']['type'];
           }
        if($counter%2==0){
            echo "<tr>
                  <td $l_color rowspan=\"$pmm_counter\" align=center>$counter</td>
                  <td $l_color rowspan=\"$pmm_counter\">$n1n</td>
                  <td $l_color rowspan=\"$pmm_counter\">$n1an</td>
                  <td $l_color rowspan=\"$pmm_counter\">$n2n</td>
                  <td $l_color rowspan=\"$pmm_counter\">$n3n</td>
                  <td $l_color rowspan=\"$pmm_counter\">$npn</td>
                  <td $l_color rowspan=\"$pmm_counter\">$ncn</td>
                  <td $l_color rowspan=\"$pmm_counter\">$nsn</td>
                  <td $l_color rowspan=\"$pmm_counter\">$nsnatn</td>
                  <td $l_color rowspan=\"$pmm_counter\">$n4n</td>
                  $pnpmn
                  <td $l_color rowspan=\"$pmm_counter\">$n5n</td>
                  <td $l_color rowspan=\"$pmm_counter\">$n6n</td>
                  <td $l_color rowspan=\"$pmm_counter\">$n7n</td>
                  <td $l_color rowspan=\"$pmm_counter\">$n8n</td>
                  <td $l_color rowspan=\"$pmm_counter\">$nirorn</td>
                  <td $l_color rowspan=\"$pmm_counter\">$serdown</td>
                  </tr>$tr_pnpmn";
        } else {
            echo "<tr>
                  <td $w_color rowspan=\"$pmm_counter\" align=center>$counter</td>
                  <td $w_color rowspan=\"$pmm_counter\">$n1n</td>
                  <td $w_color rowspan=\"$pmm_counter\">$n1an</td>
                  <td $w_color rowspan=\"$pmm_counter\">$n2n</td>
                  <td $w_color rowspan=\"$pmm_counter\">$n3n</td>
                  <td $w_color rowspan=\"$pmm_counter\">$npn</td>
                  <td $w_color rowspan=\"$pmm_counter\">$ncn</td>
                  <td $w_color rowspan=\"$pmm_counter\">$nsn</td>
                  <td $w_color rowspan=\"$pmm_counter\">$nsnatn</td>
                  <td $w_color rowspan=\"$pmm_counter\">$n4n</td>
                  $pnpmn
                  <td $w_color rowspan=\"$pmm_counter\">$n5n</td>
                  <td $w_color rowspan=\"$pmm_counter\">$n6n</td>
                  <td $w_color rowspan=\"$pmm_counter\">$n7n</td>
                  <td $w_color rowspan=\"$pmm_counter\">$n8n</td>
                  <td $w_color rowspan=\"$pmm_counter\">$nirorn</td>
                  <td $w_color rowspan=\"$pmm_counter\">$serdown</td>
                  </tr>$tr_pnpmn";
        }
        }
    echo"</table>";
    echo"</tbody>";
    echo"<br><br><script language=\"JavaScript\">
    document.write(\"end job on \" + document.lastModified + \".\")
    </script>";
} elseif ( $funtion == "pool" ) {
    echo '<br>';
    echo '<br>';
    echo '<table border=4 align=center width=80% bgcolor=black cellspacing=5 cellpadding=6>';
        echo '<thead>';
    echo "<tr $f_color>
            <th rowspan=\"1\">$hostname</th>
            <th rowspan=\"1\">Pool_名稱</th>
            <th rowspan=\"1\">LB_方式</th>
            <th rowspan=\"1\">監控方式</th>
            <th rowspan=\"1\"><div style=\"width: 250px;\">使用 Pool_member </div></th>
            <th rowspan=\"1\"><div style=\"width: 100px;\">Pool_member status </div></th>
          </tr>
          </thead>
          <tbody>";
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
        $npmn = "";
        $tr_npmn = "";
        $pm_counter = 0;
        foreach($book_pool_mb['items'] as $section_pool_mb)
            {
                 $pm_counter++;
                 $npmnn = $section_pool_mb['name'];
                 $npmsnn = $section_pool_mb['state'];
                 if ( $pm_counter == '1') {
                     if ($npmsnn == "up") {
                         $npmn .= "<td $g_color>$pm_counter. $npmnn</td><td $g_color>$npmsnn</td>";
                     } elseif ( $npmsnn == "unchecked" ) {
                         $npmn .= "<td $d_color>$pm_counter. $npmnn</td><td $d_color>$npmsnn</td>";
                     } else {
                         $npmn .= "<td $r_color>$pm_counter. $npmnn</td><td $r_color>$npmsnn</td>";
                     }
                 } else {
                     if ($npmsnn == "up") {
                         $tr_npmn .= "<tr><td $g_color>$pm_counter. $npmnn</td><td $g_color>$npmsnn</td></tr>";
                     } elseif ( $npmsnn == "unchecked" ) {
                         $npmn .= "<tr><td $d_color>$pm_counter. $npmnn</td><td $d_color>$npmsnn</td></tr>";
                     } else {
                         $tr_npmn .= "<tr><td $r_color>$pm_counter. $npmnn</td><td $r_color>$npmsnn</td></tr>";
                     }
                 }
            }
        //
        if ($pm_counter == "0" or $pm_counter == "1") {
                $pmm_counter = '1';
            } else {
                $pmm_counter = $pm_counter;
            }
                        //
        if ($npmn == "") {
                $pnpmn = "<td $b_color>None</td><td $b_color>None</td>";
                $tr_pnpmn = "";
            } else {
                $pnpmn = $npmn;
                $tr_pnpmn = $tr_npmn;
            }
        $counter++;
        $n1n = $sectionData['name'];
        $n2n = $sectionData['loadBalancingMode'];
        if ( $sectionData['monitor'] == ""){
            $n3n = "none";
           } else {
            $n3n = $sectionData['monitor'];
           }
        $n4n = $sectionData['pool'];
        if($counter%2==0){
            echo "<tr>
                  <td $t_color rowspan=\"$pmm_counter\">$counter</td>
                  <td $t_color rowspan=\"$pmm_counter\">$n1n</td>
                  <td $t_color rowspan=\"$pmm_counter\">$n2n</td>
                  <td $t_color rowspan=\"$pmm_counter\">$n3n</td>
                  $pnpmn
                  </tr>$tr_pnpmn";
            } else {
            echo "<tr>
                                  <td $w_color rowspan=\"$pmm_counter\">$counter</td>
                  <td $w_color rowspan=\"$pmm_counter\">$n1n</td>
                  <td $w_color rowspan=\"$pmm_counter\">$n2n</td>
                  <td $w_color rowspan=\"$pmm_counter\">$n3n</td>
                  $pnpmn
                  </tr>$tr_pnpmn";
        }
        }
    echo"</tbody>";
    echo"</table>";
} elseif ( $funtion == "node" ) {
    echo "Have a node !";
    echo '<br>';
    echo '<br>';
    echo '<style type="text/css">tr:nth-of-type(even) {background-color: #f3f3f3;} tr:nth-of-type(odd) { background-color: #48d1cc; }</style>';
    echo '<table border=4 align=center width=80% bgcolor=#D396FF cellspacing=5 cellpadding=6>';
    echo "<tr>
            <td>$hostname</td>
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

</body>
</html>

<?php
echo '1';

// Get the content that is in the buffer and put it in your file //
//file_put_contents("$now-$funtion-.$_SESSION['hostname']'", ob_get_contents());
file_put_contents("ltm_virtual/$now-$funtion-yourpage-".$_SESSION['hostname'].".html", ob_get_contents());
?>
