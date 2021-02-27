在Debian的環境下安裝使用 influxdb
```
Step1 安裝
Demo by Debian10.6 & influxDB1
下載安裝並啟動

wget -qO- https://repos.influxdata.com/influxdb.key | sudo apt-key add -
source /etc/os-release
echo "deb https://repos.influxdata.com/debian $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/influxdb.list

sudo apt update && sudo apt install influxdb
sudo systemctl unmask influxdb.service
sudo systemctl start influxdb

只有剛安裝完要自行開啟服務，以後開機就會自動帶起服務了。

sudo systemctl restart influxdb

連線測試

influx
 influxDB指令可以參考此文章

show databases

create database mydb

create database yourdb

show databases


Step2 設定
Influxdb的設定檔位置

#open with vi
sudo vi /etc/influxdb/influxdb.conf

資料庫的儲存路徑

[meta]

  # Where the metadata/raft database is stored

  dir = "/var/lib/influxdb/meta"

[data]

  # The directory where the TSM storage engine stores TSM files.

  dir = "/var/lib/influxdb/data"

  # The directory where the TSM storage engine stores WAL files.

  wal-dir = "/var/lib/influxdb/wal"
安全性管理(option)

[http]

  # Determines whether HTTP endpoint is enabled.

  enabled = true

  # The bind address used by the HTTP service.

  bind-address = ":8086"

  # Determines whether user authentication is enabled over HTTP/HTTPS.

  auth-enabled = false

step3 安全性管理
3.1 建立帳號

3.1.1建立管理者帳號

CREATE USER <username> WITH PASSWORD '<password>' WITH ALL PRIVILEGES

CREATE USER admin WITH PASSWORD 'strongpassword' WITH ALL PRIVILEGES

example
CREATE USER admin WITH PASSWORD 'admin' WITH ALL PRIVILEGES

3.1.2建立一般帳號

CREATE USER <username> WITH PASSWORD '<password>'

example:
CREATE USER usr WITH PASSWORD '1234'
3.1.3 帳號一覽

SHOW USERS
3.1.4帳號權限設定

授權全部的資料庫(等於管理者帳號)

GRANT ALL PRIVILEGES TO <username>

example:
GRANT ALL PRIVILEGES TO usr
全部除權

REVOKE ALL PRIVILEGES FROM <username>

example:
REVOKE ALL PRIVILEGES FROM usr
指定授權某資料庫

GRANT [READ,WRITE,ALL] ON <database_name> TO <username>

example:
GRANT ALL ON mydb TO usr
指定除權某資料庫

REVOKE [READ,WRITE,ALL] ON <database_name> TO <username>

example:
REVOKE ALL ON mydb TO usr
參考:

https://docs.influxdata.com/influxdb/v1.5/query_language/authentication_and_authorization/#user-management-commands

 

3.2 產生認證檔 (自我認證)

sudo openssl req -x509 -nodes -newkey rsa:2048 -keyout /etc/ssl/influxdb-selfsigned.key -out /etc/ssl/influxdb-selfsigned.crt -days <NUMBER_OF_DAYS>

#example:(建立十年的有效期)
sudo openssl req -x509 -nodes -newkey rsa:2048 -keyout /etc/ssl/influxdb-selfsigned.key -out /etc/ssl/influxdb-selfsigned.crt -days 3650

#照程序接下來會要求寫一些基本資料
#懶得寫的話就一路ENTER到底吧
參考

https://docs.influxdata.com/influxdb/v1.5/administration/https_setup/#step-4-verify-the-https-setup

3.3 修改設定檔

[http]

  # Determines whether HTTP endpoint is enabled.
  enabled = true

  # The bind address used by the HTTP service.
  bind-address = ":8086"


  # Determines whether user authentication is enabled over HTTP/HTTPS.
  auth-enabled = true

  # The SSL certificate to use when HTTPS is enabled.
  https-certificate = "/etc/ssl/influxdb-selfsigned.crt"

  # Use a separate private key location.
  https-private-key = "/etc/ssl/influxdb-selfsigned.key"
3.4 最後記得要重啟服務

sudo service influxdb restart
Step4 連線測試
4.1 加密連線

#使用管理員權限登入(admin-擁有所有db的權限)
influx -host 'localhost' -port '8086' -ssl -unsafeSsl -username 'admin' -password 'admin'

#使用一般帳號登入(usr-只有mydb的權限)
influx -host 'localhost' -port '8086' -ssl -unsafeSsl -username 'usr' -password '1234'
4.2帳號權限測試 使用這兩個帳號測試以下指令看看

--有權限才能看到資料

show databases
 ```
