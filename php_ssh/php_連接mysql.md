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
