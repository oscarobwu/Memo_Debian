<?php
$var = 'PHP Tutorial';
$now = date_create()->format('Y-m-d_H:i:s');
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $now;?>_Virtual_Service_<?php echo $_POST['bigip']; ?></title>
        <style>body{filter:progid:DXImageTransform.Microsoft.gradient(startcolorstr=#86CEFF,endcolorstr=#000099,gradientType=1);}</style>
<style type="text/css">
　　.myTable {border-collapse:collapse;width: 300px;}
　　.myTable td {border: 1px solid #888888;padding: 10px;}
    .myTable tr:nth-child(even){background-color:#e8e8e8;}
    .myTable tr:nth-child(odd){background-color:#0094ff;}
</style>
<script src='//cdnjs.cloudflare.com/ajax/libs/material-components-web/4.0.0/material-components-web.min.css'></script>
<script src='//cdn.datatables.net/1.10.25/css/dataTables.material.min.css'></script>
<script src='https://code.jquery.com/jquery-3.5.1.js'></script>
<script src='https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js'></script>
<script src='https://cdn.datatables.net/1.10.25/js/dataTables.material.min.js'></script>
<link href='//cdn.datatables.net/1.10.25/css/dataTables.material.min.css' rel='stylesheet'></link>
</head>

<body bgcolor="#EEEEEE">
                <center><h1><span style="background-color:#00ffff;">LTM - Standard Virtual Service Automation</span></h1></center>
                <form class="form-horizontal" action="f5_list_config_b.php" method="post" enctype="multipart/form-data" role="form" name="form1" >
                <p>
                <table border=4 width=400 bgcolor=yellow cellspacing=5 cellpadding=6 class="myTable" align=center>
                <tr><td>BIG-IP:</td><td><input type='text' name='bigip' width="200" style="width: 200px" value='<?php echo $_POST['bigip'];?>'/></td></tr>
                <tr><td>Username:</td><td><input type='text' name='username' width="200" style="width: 200px" value='<?php echo $_POST['username'];?>'/></td></tr>
                <tr><td>Password:</td><td><input type='password' name='password' width="200" style="width: 200px" value='<?php echo $_SESSION['password'];?>'/></td></tr>
                <tr><td colspan='2'><hr/></td></tr>
                <tr><td>Function:</td><td>
                <select name='funtion' width="200" style="width: 200px" >
                <option value='virtual' <?php if($_REQUEST['funtion'] == 'virtual'){ echo 'selected'; }?> >Virtual</option>
                <option value='pool' <?php if($_REQUEST['funtion'] == 'pool'){ echo 'selected'; }?> >Pool</option>
                <option value='node' <?php if($_REQUEST['funtion'] == 'node'){ echo 'selected'; }?> >Node</option>
                </select>
                <button type="submit" class="btn btn-lg btn-primary" id="submit_once" onclick="dosubmit()">Go</button>
                </form>
                </table>
                </p>
<script type="text/javascript">
  function dosubmit() {
  var btnSubmit = document.getElementById("submit_once");
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
