<?php
$username = $_SERVER["PHP_AUTH_USER"]; //經過 AuthType Basic 認證的使用者名稱
$authed_pass = $_SERVER["PHP_AUTH_PW"]; //經過 AuthType Basic 認證的密碼
$input_oldpass = (isset($_REQUEST["oldpass"]) ? $_REQUEST["oldpass"] : ""); //從介面上輸入的原密碼
$newpass = (isset($_REQUEST["newpass"]) ? $_REQUEST["newpass"] : ""); //介面上輸入的新密碼
$repeatpass = (isset($_REQUEST["repeatpass"]) ? $_REQUEST["repeatpass"] : ""); //介面上輸入的重複密碼
$action = (isset($_REQUEST["action"]) ? $_REQUEST["action"] : ""); //以hide方式提交到伺服器的action
if($action!="modify"){
$action = "view";
}
else if($authed_pass!=$input_oldpass){
$action = "oldpasswrong";
}
else if(empty($newpass)){
$action = "passempty";
}
else if($newpass!=$repeatpass){
$action = "passnotsame";
}
else{
$action = "modify";
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Nginx 目錄帳號權限 線上自助密碼修改</title>
</head>
<body>
<?php
//action=view 顯示普通的輸入資訊
if ($action == "view"){
?>
<script language = "javaScript">
<!--
function loginIn(myform)
{
var newpass=myform.newpass.value;
var repeatpass=myform.repeatpass.value;
if(newpass==""){
alert("請輸入密碼！");
return false;
}
if(repeatpass==""){
alert("請重複輸入密碼！");
return false;
}
if(newpass!=repeatpass){
alert("兩次輸入密碼不一致，請重新輸入！");
return false;
}
return true;
}
//-->
</script>
<style type="text/css">
<!--
table {
border: 1px solid #CCCCCC;
background-color: #f9f9f9;
text-align: center;
vertical-align: middle;
font-size: 9pt;
line-height: 15px;
}
th {
font-weight: bold;
line-height: 20px;
border-top-width: 1px;
border-right-width: 1px;
border-bottom-width: 1px;
border-left-width: 1px;
border-bottom-style: solid;
color: #333333;
background-color: f6f6f6;
}
input{
height: 18px;
}
.button {
height: 20px;
}
-->
</style>
<br><br><br>
<form method="post">
<input type="hidden" name="action" value="modify"/>
<table width="320" cellpadding="3" cellspacing="8" align="center">
<tr>
<th colspan=2>Nginx 目錄帳號權限 密碼修改</th>
</tr>
<tr>
<td>使用者名稱：</td>
<td align="left"> <?=$username?></td>
</tr>
<tr>
<td>原密碼：</td>
<td><input type=password size=12 name=oldpass></td>
</tr>
<tr>
<td>使用者密碼：</td>
<td><input type=password size=12 name=newpass></td>
</tr>
<tr>
<td>確認密碼：</td>
<td><input type=password size=12 name=repeatpass></td>
</tr>
<tr>
<td colspan=2>
<input onclick="return loginIn(this.form)" class="button" type=submit value="修 改">
<input name="reset" type=reset class="button" value="取 消">
</td>
</tr>
</table>
</form>
<?php
}
else if($action == "oldpasswrong"){
$msg="原密碼錯誤！";
}
else if($action == "passempty"){
$msg="請輸入新密碼！";
}
else if($action == "passnotsame"){
$msg="兩次輸入密碼不一致，請重新輸入！";
}
else{
$passwdfile="/etc/nginx/conf.d/.pwdfile";
$command='"/usr/bin/htpasswd" -b '.$passwdfile." ".$username." ".$newpass;
system($command, $result);
if($result==0){
$msg="使用者[".$username."]密碼修改成功，請用新密碼登陸.";
}
else{
$msg="使用者[".$username."]密碼修改失敗，返回值為".$result."，請和管理員聯絡！";
}
}
if (isset($msg)){
?>
<script language="javaScript">
//<!--
alert("<?=$msg?>");
// window.location.href="<?=$_SERVER["PHP_SELF"]?>"
window.location.href="./"
//-->
</script>
<?php
}
?>
</body>
</html>
