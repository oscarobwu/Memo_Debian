
#安裝 phpMyAdmin 來管理 MariaDB
```
Step 2: Download phpMyAdmin

cd ~/
wget -P Downloads https://www.phpmyadmin.net/downloads/phpMyAdmin-latest-all-languages.tar.gz

Step 3: Check phpMyAdmin GPG Key
wget -P Downloads https://files.phpmyadmin.net/phpmyadmin.keyring
cd Downloads
gpg --import phpmyadmin.keyring

cd ~/
wget -P Downloads https://www.phpmyadmin.net/downloads/phpMyAdmin-latest-all-languages.tar.gz.asc
cd Downloads
gpg --verify phpMyAdmin-latest-all-languages.tar.gz.asc


sudo mkdir /var/www/html/phpMyAdmin
cd ~/
cd Downloads

sudo tar xvf phpMyAdmin-latest-all-languages.tar.gz --strip-components=1 -C /var/www/html/phpMyAdmin

sudo cp /var/www/html/phpMyAdmin/config.sample.inc.php /var/www/html/phpMyAdmin/config.inc.php

sudo vi /var/www/html/phpMyAdmin/config.inc.php
將
$cfg['blowfish_secret'] = '';
修改為
$cfg['blowfish_secret'] = 'my_secret_passphrase';
設定檔案權限
sudo chmod 660 /var/www/html/phpMyAdmin/config.inc.php
修改使用者權限
sudo chown -R nginx:nginx /var/www/html/phpMyAdmin

systemctl restart nginx
```


```
php + mysql 練習

建立 mysql DB和 table資料

# 新增資料庫
CREATE DATABASE `my_db`;
# 新增使用者，設定密碼
CREATE USER 'my_user'@'localhost' IDENTIFIED BY 'my_password';
# 設定使用者權限
GRANT ALL PRIVILEGES ON my_db.* TO 'my_user'@'localhost';

SELECT * FROM books;

SELECT * FROM employee;

DROP TABLE books;
DROP TABLE employee;

flush privileges;


建立 資料庫 使用到的 table

# 重點在於我麼 key IP要對 一致
CREATE TABLE `books` (  # 新增產品資料表
  `C_Id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, # 書籍 ID
  `book_name` varchar(150) NOT NULL,  # 名稱
  `book_descr` varchar(200),  # 說明
  `book_price` varchar(200),  # 價格
  `book_location` varchar(200),  # 書櫃位置
  `book_stork` varchar(200),  # 庫存
  PRIMARY KEY(C_Id)      # 主要索引
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


# 重點在於我麼 key IP要對 一致
CREATE TABLE `employee` (  # 新增產品資料表
  `employee_Id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, # 書籍 ID
  `employee_c_name` varchar(150) NOT NULL,  # 名稱
  `employee_username` varchar(200),  # 說明
  `employee_password` varchar(200),  # 價格
  `employee_location` varchar(200),  # 書櫃位置
  `employee_stork` varchar(200),  # 庫存
  PRIMARY KEY(employee_Id)      # 主要索引
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


輸入Table 資料
單筆方式
INSERT INTO `books` (book_name, book_descr, book_price)
  VALUES ("葵花寶典", "蓋世武功密集", 990);

INSERT INTO `books` (book_name, book_descr, book_price)
  VALUES ("獨孤九劍", "劍魔獨孤求敗所創劍法", 890);

多筆方式
INSERT INTO `books` (book_name, book_descr, book_price)
  VALUES ("獨孤001劍", "劍魔獨孤求敗所創劍法", 810),
         ("葵花寶典", "蓋世武功密集", 990),
         ("獨孤九劍", "劍魔獨孤求敗所創劍法", 890),
         ("獨孤002劍", "劍魔獨孤求敗所創劍法", 820),
         ("獨孤003劍", "劍魔獨孤求敗所創劍法", 830),
         ("獨孤004劍", "劍魔獨孤求敗所創劍法", 840),
         ("獨孤005劍", "劍魔獨孤求敗所創劍法", 850),
         ("獨孤006劍", "劍魔獨孤求敗所創劍法", 860),
         ("獨孤007劍", "劍魔獨孤求敗所創劍法", 870),
         ("獨孤008劍", "劍魔獨孤求敗所創劍法", 890),
         ("獨孤009劍", "劍魔獨孤求敗所創劍法", 900);

INSERT INTO `employee` (employee_c_name, employee_username, employee_password)
  VALUES ("吳天進", "chen@yahoo.com.tw", "ad810"),
         ("李天進", "zhang@gmail.com", "c820"),
         ("趙天進", "chen002@yahoo.com.tw", "c830"),
         ("錢天進", "hsing@yahoo.com.tw", "sd840"),
         ("孫天進", "sdsd@yahoo.com.tw", "c850"),
         ("李天進", "eesdf@yahoo.com.tw", "d860"),
         ("越天進", "csdfs@yahoo.com.tw", "e870"),
         ("楊天進", "cdssd@yahoo.com.tw", "f890"),
         ("樓天進", "csdfsdf@yahoo.com.tw", "d900");

建立 使用php 顯示 資料庫tabal資料

1. 建立 conn_mysql.php 

<?php
        $db_link=@mysqli_connect("127.0.0.1","my_user","my_password");
        if(!$db_link){
                die("資料庫連線失敗<br>");
        }else{
                echo"資料庫連線成功<br>";
        }
        mysqli_query($db_link,"SET NAMES 'utf-8'");  //設定字元集與編碼為utf-8
        $seldb=@mysqli_select_db($db_link,"my_db");
        if(!$seldb){
                die("資料庫選擇失敗<br>");
        }else{
                echo"資料庫選擇成功<br>";
        }
?>


2. 建立 登入帳密 login.html


<html>
        <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

        </head>
        <body>
                <form method="POST" action="login_php.php">
                        請輸入帳號：<input type="text" name="username"/><br>
                        請輸入密碼：<input type="Password" name="password"/><br>
                        <input type="submit" value="登入"/>
                </form>
        </body>
</html>

3. 建立將資料返回到網頁上 login_php.php

<?php
        header('Content-Type: text/html; charset=utf-8');
        $username=$_POST['username'];
        $password=$_POST['password'];

        require("conn_mysql.php");
        $sql_query_login="SELECT * FROM employee where employee_username='$username' AND employee_password='$password'";
        $result1=mysqli_query($db_link,$sql_query_login) or die("查詢失敗");
        if(mysqli_num_rows($result1)){
                $sql_query="SELECT * FROM books";
                $result=mysqli_query($db_link,$sql_query);
                echo "<table border=1 width=400 cellpadding=5>";
                echo "<tr>
                        <td>書籍編號</td>
                        <td>書籍名稱</td>
                        <td>負責員工編號</td>
                        <td>價錢</td>
                      </tr>";
                while($row=mysqli_fetch_array($result)){

                        echo "<tr>
                                <td>$row[0]</td>
                                <td>$row[1]</td>
                                <td>$row[2]</td>
                                <td>$row[3]</td>
                              </tr>";


                }
                echo"</table>";
        }else{
                echo"登入失敗";
        }


?>

```
```
練習用php建立資料庫
<?php
$dbhost = 'localhost:3306';  // mysql伺服器主機地址
$dbuser = 'my_user';            // mysql使用者名稱
$dbpass = 'my_password';          // mysql使用者名稱密碼
$conn = mysqli_connect($dbhost, $dbuser, $dbpass);
if(! $conn )
{
    die('連線失敗: ' . mysqli_error($conn));
}
echo '連線成功<br />';
$sql = "CREATE TABLE ITREAD01_tbl( ".
        "ITREAD01_id INT NOT NULL AUTO_INCREMENT, ".
        "ITREAD01_title VARCHAR(100) NOT NULL, ".
        "ITREAD01_author VARCHAR(40) NOT NULL, ".
        "submission_date DATE, ".
        "PRIMARY KEY ( ITREAD01_id ))ENGINE=InnoDB DEFAULT CHARSET=utf8; ";
mysqli_select_db( $conn, 'my_db' );
$retval = mysqli_query( $conn, $sql );
if(! $retval )
{
    die('資料表建立失敗: ' . mysqli_error($conn));
}
echo "資料表建立成功\n";
mysqli_close($conn);
?>

記憶體釋放
在我們執行完 SELECT 語句後，釋放遊標記憶體是一個很好的習慣。

可以通過 PHP 函式 mysqli_free_result() 來實現記憶體的釋放。

以下例項演示了該函式的使用方法。
使用 mysqli_free_result 釋放記憶體：
<?php
$dbhost = 'localhost:3306';  // mysql伺服器主機地址
$dbuser = 'my_user';            // mysql使用者名稱
$dbpass = 'my_password';          // mysql使用者名稱密碼
$conn = mysqli_connect($dbhost, $dbuser, $dbpass);
if(! $conn )
{
    die('連線失敗: ' . mysqli_error($conn));
}
// 設定編碼，防止中文亂碼
mysqli_query($conn , "set names utf8");
 
$sql = 'SELECT ITREAD01_id, ITREAD01_title, 
        ITREAD01_author, submission_date
        FROM ITREAD01_tbl';
 
mysqli_select_db( $conn, 'my_db' );
$retval = mysqli_query( $conn, $sql );
if(! $retval )
{
    die('無法讀取資料: ' . mysqli_error($conn));
}
echo '<h2>入門教學 mysqli_fetch_array 測試<h2>';
echo '<table border="1"><tr><td>教程 ID</td><td>標題</td><td>作者</td><td>提交日期</td></tr>';
while($row = mysqli_fetch_array($retval, MYSQLI_NUM))
{
    echo "<tr><td> {$row[0]}</td> ".
         "<td>{$row[1]} </td> ".
         "<td>{$row[2]} </td> ".
         "<td>{$row[3]} </td> ".
         "</tr>";
}
echo '</table>';
// 釋放記憶體
mysqli_free_result($retval);
mysqli_close($conn);
?>
```
