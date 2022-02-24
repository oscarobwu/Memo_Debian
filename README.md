讓自己的記憶加深
===============

```
Git1.7.0以後加入了Sparse Checkout模式，該模式可以實現Check Out指定檔或者資料夾
舉個例子：
現在有一個 Memo 倉庫 https://github.com/oscarobwu/Memo.git
你要git clone裡面的 Python 子目錄：


git init Python && cd Python     // 新建倉庫並進入資料夾
git config core.sparsecheckout true // 設置允許克隆子目錄
echo 'Python' >> .git/info/sparse-checkout // 設置要克隆的倉庫的子目錄路徑   //空格別漏 將要下載目錄填入
git remote add origin https://github.com/oscarobwu/Memo.git // 這裡換成你要克隆的項目和庫
git pull origin master    // 下載代碼
```

```
開始使用 F5 BIGREST API 重寫開關機
```
```
重新設定mysql 密碼
mysql> argus_grafana;
mysql> update argus_grafana.user set password='59acf18b94d7eb0694c61e60ce44c110c7a683ac6a8f09580d626f90f4a242000746579358d77dd9e570e83fa24faa88a8a6', salt = 'F3FAxVm33R' where login = 'admin';
mysql> quit;
```
```
Step 1 — Identifying the Database Version

mysql --version

MySQL output
mysql  Ver 14.14 Distrib 5.7.16, for Linux (x86_64) using  EditLine wrapper

OR

MariaDB output
mysql  Ver 15.1 Distrib 5.5.52-MariaDB, for Linux (x86_64) using readline 5.1

Step 2 — Stopping the Database Server

sudo systemctl stop mysql

sudo systemctl stop mariadb

Step 3 — Restarting the Database Server Without Permission Checking

sudo mysqld_safe --skip-grant-tables --skip-networking &

登入

mysql -u root


Step 4 — Changing the Root Password

MySQL 5.7.6 and newer as well as MariaDB 10.1.20 and newer, use the following command.
ALTER USER 'root'@'localhost' IDENTIFIED BY 'new_password';

For MySQL 5.7.5 and older as well as MariaDB 10.1.20 and older, use:
SET PASSWORD FOR 'root'@'localhost' = PASSWORD('new_password');

注意：如果該ALTER USER命令不起作用，通常表明存在更大的問題。但是，您可以嘗試UPDATE ... SET重設root密碼。

UPDATE mysql.user SET authentication_string = PASSWORD('new_password') WHERE User = 'root' AND Host = 'localhost';


請記住，此後要重新加載授權表。

FLUSH PRIVILEGES;




對於MySQL，請使用：

sudo kill `cat /var/run/mysqld/mysqld.pid`
 
對於MariaDB，請使用：

sudo kill `/var/run/mariadb/mariadb.pid`
 
然後，使用重新啟動服務systemctl。

對於MySQL，請使用：

sudo systemctl start mysql
 
對於MariaDB，請使用：

sudo systemctl start mariadb
 
現在，您可以通過運行以下命令來確認新密碼已正確應用：

mysql -u root -p
 
現在，該命令將提示您輸入新分配的密碼。輸入它，您應該可以按預期訪問數據庫提示。

# 更新 mysql Table 紀錄不重複 (比對主機名稱 )

INSERT INTO f5_host_list(
host_name,mgmt_ip,description_text,mgmt_url,administrator_user,soft_version,hardware_version,hardware_key,module_list,master_key,location_dc,create_time,update_time
) 
SELECT 'LAB-VPN08-PRD-DC01.lab.com','172.16.50.204','','','','','','','','','DC08',CURTiME(),NOW() FROM DUAL 
WHERE 'LAB-VPN08-PRD-DC01.lab.com' NOT IN (SELECT host_name FROM f5_host_list);

# 比對兩個欄位

INSERT INTO f5_host_list (
host_name,mgmt_ip,description_text,mgmt_url,administrator_user,soft_version,hardware_version,hardware_key,module_list,master_key,location_dc,create_time,update_time
) 
SELECT 'LAB-VPN08-PRD-DC01.lab.com','172.16.50.204','','','','','','','','','DC08',CURTiME(),NOW() FROM DUAL WHERE NOT EXISTS(
SELECT host_name FROM f5_host_list WHERE host_name = 'LAB-VPN08-PRD-DC01.lab.com' AND mgmt_ip = '172.16.50.204');


```

```
要先安裝 node.js mysql 套件
進入到套件路徑
cd /var/ilx/workspaces/Common/irules_lx_mysql_workspace/extensions/mysql_extension/
#
npm install mysql --save
# 確認套件
# ls node_modules/
```
