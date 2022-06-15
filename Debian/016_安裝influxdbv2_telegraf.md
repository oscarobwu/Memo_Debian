# 安裝 LNMP + Grafana + influxdb 2.2 + telegraf

#### 017_intall_LNMP_Grafana_influxdb2_2_telegraf.md
----


## 安裝 LNMP

### Step 03 – 安裝 nginx 1.20

```
#$ echo deb http://nginx.org/packages/debian/ stretch nginx | sudo tee /etc/apt/sources.list.d/nginx.list
$ echo "deb http://nginx.org/packages/mainline/debian `lsb_release -cs` nginx" | sudo tee /etc/apt/sources.list.d/nginx.list
$ wget http://nginx.org/keys/nginx_signing.key && sudo apt-key add nginx_signing.key 
$ sudo apt update && apt install nginx -y

需要注意的是，這一步安裝的 Nginx 和系統自帶的 nginx 的配置目錄略有區別，可以用一下幾個簡單的命令修正：
讓設定習慣不用改變
sudo mkdir /etc/nginx/{sites-available,sites-enabled}
sudo mv /etc/nginx/conf.d/* /etc/nginx/sites-available
sudo rmdir -f /etc/nginx/conf.d/
sudo perl -pi -e 's/conf.d/sites-enabled/g' /etc/nginx/nginx.conf

要設定一下設定檔連結
ln -s /etc/nginx/sites-available/default.conf /etc/nginx/sites-enabled/

mkdir -p /var/www/html

chown -R nginx:nginx /var/www/html

檢查 nginx 設定檔是否正確

nginx -t

重新啟動 nginx 並 設定開機啟動

重新啟動
sudo systemctl restart nginx 

sudo systemctl enable --now nginx 
```

### Step 04 – 安裝 MariaDB10.7

```
Step 1: Update system apt index

sudo apt -y update
sudo apt -y install software-properties-common gnupg2 dirmngr
sudo apt -y upgrade
sudo reboot


Step 2: Import MariaDB gpg key and add repository.
#####$ apt install software-properties-common dirmngr -y
#
curl -LsS https://downloads.mariadb.com/MariaDB/mariadb_repo_setup | sudo bash -s -- --mariadb-server-version=10.7 --skip-maxscale --skip-tools
sudo apt-key adv --fetch-keys 'https://mariadb.org/mariadb_release_signing_key.asc'
## Debian 11 ###
sudo add-apt-repository 'deb [arch=amd64,i386,arm64,ppc64el] https://mariadb.mirror.liquidtelecom.com/repo/10.7/debian bullseye main'



apt update

安裝
sudo apt update
sudo apt install mariadb-server mariadb-client -y

## $ systemctl start mysql.service
$ sudo systemctl enable --now mysql.service

$ mysql_secure_installation

$ mariadb --version

mariadb  Ver 15.1 Distrib 10.7.4-MariaDB, for debian-linux-gnu (x86_64) using readline EditLine wrapper

root@debian-s-1vcpu-1gb-sfo2-01:~#


Step 3: Install MariaDB 10.7 on Debian 11.2 (Buster)

Step 5. Manage MariaDB 10.7 on Debian 11 / Debian 10.

sudo systemctl start mariadb

systemctl status mariadb

sudo systemctl enable mariadb

sudo systemctl restart mariadb


Step 6. Secure MariaDB Installation on Debian

sudo mysql_secure_installation 

Step 7. Use MariaDB 10.7 on Debian 11 / Debian 10

mysql -u root -p
建立 DB

CREATE DATABASE testdb;
CREATE OR REPLACE DATABASE testdb;
MariaDB [(none)]> SHOW DATABASES;
建立使用者 和權限
#Create user mariadb
CREATE USER 'myuser'@'localhost' IDENTIFIED BY 'mypassword';

#Grant all privileges to the user
GRANT ALL PRIVILEGES ON *.* TO 'myuser'@'localhost' IDENTIFIED BY 'mypassword';

#Grant privileges to a specific database
GRANT ALL PRIVILEGES ON testdb.* TO myuser@localhost;


#Grant privileges to all databases
GRANT ALL PRIVILEGES ON *.* TO 'myuser'@'localhost';

#Remember to refresh the privileges
FLUSH privileges;

#To check user grants in MariaDB
SHOW GRANTS FOR 'myuser'@'locahost';
SHOW GRANTS FOR myuser@localhost;

#####
建立 TABLE
USE testdb;
CREATE TABLE employees (id INT, name VARCHAR(20), email VARCHAR(20));
INSERT INTO employees (id,name,email) VALUES(01,"thor","thor@example.com");

MariaDB [testdb]> show tables;

MariaDB [testdb]> SHOW COLUMNS FROM employees;

MariaDB [testdb]> exit
Bye

# 移除 MariaDB
##Remove data from MariaDB
sudo apt purge mariadb-server
sudo rm -rf /var/lib/mysql/

Completely uninstall MariaDB on your system.

sudo apt autoremove mariadb-server mariadb-client



Step 4: Secure MariaDB server
$ sudo mysql_secure_installation 

檢查版本
MariaDB [(none)]> SELECT VERSION();
+------------------------------------------+
| VERSION()                                |
+------------------------------------------+
| 10.5.12-MariaDB-1:10.5.12+maria~bullseye |
+------------------------------------------+
1 row in set (0.000 sec)

MariaDB [(none)]>


#
建立資料庫及給設定使用者權限

CREATE DATABASE labstack;
CREATE USER 'labstackuser'@'localhost' IDENTIFIED BY 'new_password_here';
GRANT ALL ON labstack.* TO 'labstackuser'@'localhost' IDENTIFIED BY 'user_password_here' WITH GRANT OPTION;
FLUSH PRIVILEGES;
EXIT;

#####
# 設定 mysql 讓PHP呼叫
'''
GRANT ALL PRIVILEGES ON database_name.* TO 'username'@'localhost';

create database argus_demo;
GRANT USAGE ON `argus_demo`.* to 'demouser'@'localhost' identified by 'gIWeWCa2k8GuMJSM61';
GRANT ALL PRIVILEGES ON `argus_demo`.* to 'demouser'@'localhost' with grant option;
flush privileges;


CREATE TABLE users (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);





create database argus_demo2;
GRANT USAGE ON `argus_demo2`.* to 'demouser2'@'localhost' identified by 'gIWeWCa2k8GuMJSM61';
GRANT ALL PRIVILEGES ON `argus_demo2`.* to 'demouser2'@'localhost' with grant option;
flush privileges;

mysql -u demouser2 -pgIWeWCa2k8GuMJSM61 -D argus_demo2 < users.sql

$link = mysqli_connect("localhost","demouser2",".98vfwL9zpLI","argus_demo2");
'''
to diable unix_socket auth (passwordless login on cli)

Mariadb config
/etc/mysql/mariadb.conf.d/50-server.cnf

MySQL config
/etc/mysql/my.cnf

add following parameter


plugin-load-add = auth_socket.so


on mysql cli


update mysql.user set password=password('GerP@ssword') where user='root';
update mysql.user set plugin=” where User='root';
or 
ALTER USER 'root'@'localhost' IDENTIFIED BY 'GerP@ssw0rd';

flush privileges;


restart MySQL server

systemctl restart mariadb.service

or

systemctl restart mysql.service


ALTER USER 'root'@'localhost' IDENTIFIED BY 'GerP@ssw0rd';

```

### #Step 05 – 安裝 PHP 8.1.11

```
############ 安裝 php 8.1 #######################################
sudo apt -y install lsb-release apt-transport-https ca-certificates
sudo wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg
echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | sudo tee /etc/apt/sources.list.d/php.list
apt update
apt upgrade


sudo apt install php8.1-amqp php8.1-common php8.1-gd php8.1-ldap php8.1-odbc php8.1-readline php8.1-sqlite3 php8.1-xsl \
php8.1-curl php8.1-gmp php8.1-mailparse php8.1-opcache php8.1-redis php8.1-sybase php8.1-yac \
php8.1-ast php8.1-dba php8.1-igbinary php8.1-mbstring php8.1-pgsql php8.1-rrd php8.1-tidy php8.1-yaml \
php8.1-bcmath php8.1-dev php8.1-imagick php8.1-memcached php8.1-phpdbg php8.1-smbclient php8.1-uuid php8.1-zip \
php8.1-bz2 php8.1-ds php8.1-imap php8.1-msgpack php8.1-pspell php8.1-snmp php8.1-xdebug php8.1-zmq \
php8.1-cgi php8.1-enchant php8.1-interbase php8.1-mysql php8.1-psr php8.1-soap php8.1-xhprof \
php8.1-cli php8.1-fpm php8.1-intl php8.1-oauth php8.1-raphf php8.1-solr php8.1-xml php8.1-mcrypt -y

#####
php8.1-amqp            php8.1-decimal         php8.1-grpc            php8.1-maxminddb       php8.1-opcache         php8.1-redis           php8.1-tidy            php8.1-yac
php8.1-apcu            php8.1-dev             php8.1-igbinary        php8.1-mbstring        php8.1-pcov            php8.1-rrd             php8.1-uopz            php8.1-yaml
php8.1-ast             php8.1-ds              php8.1-imagick         php8.1-mcrypt          php8.1-pgsql           php8.1-smbclient       php8.1-uploadprogress  php8.1-zip
php8.1-bcmath          php8.1-enchant         php8.1-imap            php8.1-memcache        php8.1-phpdbg          php8.1-snmp            php8.1-uuid            php8.1-zmq
php8.1-bz2             php8.1-fpm             php8.1-inotify         php8.1-memcached       php8.1-protobuf        php8.1-soap            php8.1-vips            php8.1-zstd
php8.1-cgi             php8.1-gd              php8.1-interbase       php8.1-mongodb         php8.1-ps              php8.1-solr            php8.1-xdebug
php8.1-cli             php8.1-gearman         php8.1-intl            php8.1-msgpack         php8.1-pspell          php8.1-sqlite3         php8.1-xhprof
php8.1-common          php8.1-gmagick         php8.1-ldap            php8.1-mysql           php8.1-psr             php8.1-ssh2            php8.1-xml
php8.1-curl            php8.1-gmp             php8.1-lz4             php8.1-oauth           php8.1-raphf           php8.1-swoole          php8.1-xmlrpc
php8.1-dba             php8.1-gnupg           php8.1-mailparse       php8.1-odbc            php8.1-readline        php8.1-sybase          php8.1-xsl
#####
sudo apt install php8.1-{common,mysql,xml,xmlrpc,curl,gd,imagick,cli,dev,imap,mbstring,opcache,soap,zip,intl,bcmath} -y

pecl install mcrypt-1.0.4

# Install PHP mcrypt on Debian 11/PHP 預設
#
# Install pre-requisites

apt install php-dev libmcrypt-dev php-pear -y

# Install mcrypt PHP module

pecl channel-update pecl.php.net

pecl install channel://pecl.php.net/mcrypt-1.0.4

###########################################################
Build process completed successfully
Installing '/usr/lib/php/20190902/mcrypt.so'
install ok: channel://pecl.php.net/mcrypt-1.0.3
configuration option "php_ini" is not set to php.ini location
You should add "extension=mcrypt.so" to php.ini

Add mcrypt.so to the php.ini file
vi /etc/php/8.0/cli/php.ini

For Nginx, use this:

vi /etc/php/8.0/fpm/php.ini

For Apache
vi /etc/php/8.0/apache2/php.ini

修改 php.ini 設定
file_uploads = On
allow_url_fopen = On
memory_limit = 256M
upload_max_filesize = 100M
cgi.fix_pathinfo = 0
max_execution_time = 360
date.timezone = Asia/Taipei

# 修改 php.ini 使用指令
cd /etc
cp php.ini php.ini.`date +"%Y%m%d%H%M%S"`
sed -i 's/memory_limit = 128M/memory_limit = 512M/g' php.ini
sed -i 's/post_max_size = 8M/post_max_size = 20M/g' php.ini
sed -i 's/upload_max_filesize = 2M/upload_max_filesize = 20M/g' php.ini
sed -i 's/;date.timezone =/date.timezone = "Asia\/Taipei"/g' php.ini

## 20201111 新增
# 修改 php.ini 使用指令
cd /etc/php/8.1/fpm/
cp /etc/php/8.1/fpm/php.ini /etc/php/8.1/fpm/php.ini.`date +"%Y%m%d%H%M%S"`
sed -i 's/memory_limit = 128M/memory_limit = 512M/g' /etc/php/8.1/fpm/php.ini
sed -i 's/post_max_size = 8M/post_max_size = 100M/g' /etc/php/8.1/fpm/php.ini
sed -i 's/upload_max_filesize = 2M/upload_max_filesize = 100M/g' /etc/php/8.1/fpm/php.ini
sed -i 's/;cgi.fix_pathinfo=1/cgi.fix_pathinfo = 0/g' /etc/php/8.1/fpm/php.ini
sed -i 's/max_execution_time = 30/max_execution_time = 360/g' /etc/php/8.1/fpm/php.ini
sed -i 's/;date.timezone =/date.timezone = "Asia\/Taipei"/g' /etc/php/8.1/fpm/php.ini

cd /etc/php/8.1/cli/
cp /etc/php/8.1/cli/php.ini /etc/php/8.1/cli/php.ini.`date +"%Y%m%d%H%M%S"`
sed -i 's/memory_limit = -1/memory_limit = 512M/g' /etc/php/8.1/cli/php.ini
sed -i 's/post_max_size = 8M/post_max_size = 100M/g' /etc/php/8.1/cli/php.ini
sed -i 's/upload_max_filesize = 2M/upload_max_filesize = 100M/g' /etc/php/8.1/cli/php.ini
sed -i 's/;cgi.fix_pathinfo=1/cgi.fix_pathinfo = 0/g' /etc/php/8.1/cli/php.ini
sed -i 's/max_execution_time = 30/max_execution_time = 360/g' /etc/php/8.1/cli/php.ini
sed -i 's/;date.timezone =/date.timezone = "Asia\/Taipei"/g' /etc/php/8.1/cli/php.ini


#########

#########
修改run php

        # php-fpm
                location ~ \.php$ {
                fastcgi_split_path_info ^(.+\.php)(/.+)$;
                fastcgi_pass   unix:/var/run/php/php8.0-fpm.sock;
                fastcgi_index  index.php;
                fastcgi_param  SCRIPT_FILENAME /var/www/html$fastcgi_script_name;
                include        fastcgi_params;
        }

  location ~* \.php$ {
    fastcgi_pass unix:/run/php/php8.0-fpm.sock;
    include         fastcgi_params;
    fastcgi_param   SCRIPT_FILENAME    $document_root$fastcgi_script_name;
    fastcgi_param   SCRIPT_NAME        $fastcgi_script_name;
  }
## 如果使用 nginx 1.18 最新版需修改 www.conf
修改 /etc/php/8.0/fpm/pool.d/www.conf 設定檔，改變執行者及群組
# vi /etc/php/8.0/fpm/pool.d/www.conf
user = nginx
group = nginx
listen = /run/php/php8.0-fpm.sock
listen.owner = nginx
listen.group = nginx
listen.mode = 0666

#新增修改方式
cp /etc/php/8.1/fpm/pool.d/www.conf /etc/php/8.1/fpm/pool.d/www.conf.`date +"%Y%m%d%H%M%S"`
#
sed -i 's/user = www-data/user = nginx/g' /etc/php/8.1/fpm/pool.d/www.conf
sed -i 's/group = www-data/group = nginx/g' /etc/php/8.1/fpm/pool.d/www.conf
sed -i 's/listen.owner = www-data/listen.owner = nginx/g' /etc/php/8.1/fpm/pool.d/www.conf
sed -i 's/listen.group = www-data/listen.group = nginx/g' /etc/php/8.1/fpm/pool.d/www.conf
sed -i 's/;listen.mode = 0660/listen.mode = 0666/g' /etc/php/8.1/fpm/pool.d/www.conf


systemctl restart php8.1-fpm.service
systemctl status php* | grep fpm.service
```

### 安裝 InfluxDB v2

```
安裝 influxdb_v2 

wget -qO- https://repos.influxdata.com/influxdb.key | gpg --dearmor | sudo tee /etc/apt/trusted.gpg.d/influxdb.gpg > /dev/null

#
export DISTRIB_ID=$(lsb_release -si); export DISTRIB_CODENAME=$(lsb_release -sc)

# 設定安裝來源
echo "deb [signed-by=/etc/apt/trusted.gpg.d/influxdb.gpg] \
https://repos.influxdata.com/${DISTRIB_ID,,} ${DISTRIB_CODENAME} stable" | \
sudo tee /etc/apt/sources.list.d/influxdb.list > /dev/null

#
sudo apt update -y && sudo apt install influxdb2 -y

# 確認安裝
# Verify Installation

dpkg -L influxdb2

influx version

# Influx CLI 2.3.0 (git: 88ba346) build_date: 2022-04-06T19:30:53Z

# 啟動influxdb
root@cyberithub:~# systemctl start influxdb
root@cyberithub:~# systemctl status influxdb
# 開機啟動
root@cyberithub:~# systemctl enable influxdb

# 初始設定influxdb 
Step 10: Setup InfluxDB

root@cyberithub:~# influx setup
> Welcome to InfluxDB 2.0!
? Please type your primary username oobcyberithub
? Please type your password ***********
? Please type your password again ***********
? Please type your primary organization name oobCyberITHub
? Please type your primary bucket name oobcyberithub_bucket
? Please type your retention period in hours, or 0 for infinite 0
? Setup with these parameters?
  Username:          oobcyberithub
  Organization:      oobCyberITHub
  Bucket:            oobcyberithub_bucket
  Retention Period:  infinite
 Yes
User            Organization    Bucket
oobcyberithub   oobCyberITHub   oobcyberithub_bucket


Step 11: Check Token List

#  influx auth list
ID                      Description             Token                                                                                           User Name       User ID                   Permissions
097f26d6b2c9b000        oobcyberithub's Token   IfBSF8zG2Ytb_zyK6mEpSgypWGHLFpBpzPjmY8fVHbXpGLeZtyaEtOl3T6SmwfME4RiSHTEeXBfi5uUHWofG1A==        oobcyberithub   097f26d69bc9b000  [read:/authorizations write:/authorizations read:/buckets write:/buckets read:/dashboards write:/dashboards read:/orgs write:/orgs read:/sources write:/sources read:/tasks write:/tasks read:/telegrafs write:/telegrafs read:/users write:/users read:/variables write:/variables read:/scrapers write:/scrapers read:/secrets write:/secrets read:/labels write:/labels read:/views write:/views read:/documents write:/documents read:/notificationRules write:/notificationRules read:/notificationEndpoints write:/notificationEndpoints read:/checks write:/checks read:/dbrp write:/dbrp read:/notebooks write:/notebooks read:/annotations write:/annotations read:/remotes write:/remotes read:/replications write:/replications]

Step 12: Create an User

root@cyberithub:~# influx user create --name test --org oobCyberITHub --password Test@123$
ID               Name
08ada1af52c60000 test

Step 13: Create User Authorization
 #  influx auth create --user test --read-buckets --write-buckets
ID                      Description     Token                                                                                           User Name       User ID  Permissions
097f278eea09b000                        J8JaZdTliM6x8NmTKPqcPBFNPv_wyMDGfK6vhIM2_zbuxsBmAStEwxOy1rYV3Em2tpvCyUp9-USiLT7GOzXRHg==        test            097f2777f049b000  [read:orgs/bb16b74e5be8d294/buckets write:orgs/bb16b74e5be8d294/buckets]




Step 1: Prerequisites
Step 2: Add GPG Key
Step 3: Setup Repo
Step 4: Update Your Server
Step 5: Install InfluxDB2
Step 6: Verify Installation
Step 7: Check InfluxDB Version
Step 8: Start InfluxDB Service
Step 9: Enable Service
Step 10: Setup InfluxDB
Step 11: Check Token List
Step 12: Create an User
Step 13: Create User Authorization



```

### 設定 Telegraf

```
curl -LO -C - https://dl.influxdata.com/telegraf/releases/telegraf_1.22.4-1_amd64.deb
#
wget -qO- https://repos.influxdata.com/influxdb.key | sudo tee /etc/apt/trusted.gpg.d/influxdata.asc >/dev/null
echo "deb https://repos.influxdata.com/debian stable main" | sudo tee /etc/apt/sources.list.d/influxdata.list
sudo apt update && sudo apt install telegraf

sudo systemctl status telegraf

sudo systemctl enable --now start telegraf


## 建立新的組織

influx org create -n f5_ltm_org

influx org create -n f5_apm_org

## 確認是否建立成功

influx bucket find --org f5_ltm_org

## Creating influxdb bucket and user
# Create bucket for telegraf_ltm_bucket with data retention for 6 months (4380h).

influx bucket create --org f5_ltm_org --name telegraf_f5ltm_bucket --retention 4380h

# Then create a telegraf user
# 要記下 username 和 token
influx user create --name telegraf_f5ltm_user --org f5_ltm_org --password SecretP4ss!

# 給予user 權限 一定要機加組織才算是有權限 這樣才能對應到 Grafana 中的設定

$ influx auth create -o f5_ltm_org --user telegraf_f5ltm_user --read-buckets --write-buckets

# 刪除權限
# 先要list
influx auth list
# 確認ID後再山族
influx auth delete -i 09853f9a218f5000

## 
influx user list

## 刪除 user
influx user delete -i 

# 輸出 influxdb_v2 
# 修改 bucket 組織 
[[outputs.influxdb_v2]]
  urls = ["http://127.0.0.1:8086"]
  token = "xKMCGABR3S34mRCWjYlvMw0d6J-igMZqqxPoWjt9Kx1VWS5kDHdoiXp-gCzWjdC0cEodaNZ0rzE33VssIwwXPw=="
  organization = "f5_ltm_org"
  bucket = "telegraf_f5ltm_bucket"
  
############################

```

### 設定本機 telegraf 收集設定

```

vi /etc/telegraf/telegraf.conf
[global_tags]

# Configuration for telegraf agent
[agent]
    interval = "10s"
    debug = false
    hostname = "server-hostname"
    round_interval = true
    flush_interval = "10s"
    flush_jitter = "0s"
    collection_jitter = "0s"
    metric_batch_size = 1000
    metric_buffer_limit = 10000
    quiet = false
    logfile = ""
    omit_hostname = false

###############################################################################
#                                  OUTPUTS                                    #
###############################################################################

[[outputs.influxdb_v2]]
  urls = ["http://127.0.0.1:8086"]
  token = "BsABtLV5-1ulXr-YZALcoO8d8VxRMaO4j_a-GQkoypqB8oNh-NrnEQgUxP7D7v0sfjtkwfF5cngiaFQu8EOnfA=="
  organization = "citizix_org"
  bucket = "telegraf_bucket"

###############################################################################
#                                  INPUTS                                     #
###############################################################################

[[inputs.cpu]]
    percpu = true
    totalcpu = true
    collect_cpu_time = false
    report_active = false
[[inputs.disk]]
    ignore_fs = ["tmpfs", "devtmpfs", "devfs", "iso9660", "overlay", "aufs", "squashfs"]
[[inputs.io]]
[[inputs.mem]]
[[inputs.net]]
[[inputs.system]]
[[inputs.swap]]
[[inputs.netstat]]
[[inputs.processes]]
[[inputs.kernel]]


telegraf --config /etc/telegraf/telegraf.conf --test
telegraf --config /etc/telegraf/telegraf.conf --debug
# 安裝 15650 的grafana 圖表


```

### 安裝 Grafana

```
sudo apt-get install -y adduser libfontconfig1
wget https://dl.grafana.com/oss/release/grafana_8.5.3_amd64.deb
sudo dpkg -i grafana_8.5.3_amd64.deb
##
#20220609 更新安裝新版
sudo apt-get install -y adduser libfontconfig1
wget https://dl.grafana.com/oss/release/grafana_8.5.5_amd64.deb
sudo dpkg -i grafana_8.5.5_amd64.deb
###
# 更新版本 9.0.0
sudo apt-get install -y adduser libfontconfig1
wget https://dl.grafana.com/oss/release/grafana_9.0.0_amd64.deb
sudo dpkg -i grafana_9.0.0_amd64.deb

###
systemctl daemon-reload
systemctl start grafana-server
systemctl enable grafana-server.service

安裝套件
grafana-cli plugins install grafana-piechart-panel

grafana-cli plugins install grafana-worldmap-panel

grafana-cli plugins install natel-discrete-panel

grafana-cli plugins install grafana-image-renderer

grafana-cli plugins install flant-statusmap-panel

grafana-cli plugins install grafana-clock-panel

grafana-cli plugins install cloudflare-app

#安裝流程圖 for 架構圖使用
grafana-cli plugins install agenty-flowcharting-panel

# 修改設定檔 url 目錄 ，並使用mysql 資料庫

##### 將sqlite3 換成 mariadb mysql  
create database argus_grafana;
GRANT USAGE ON `argus_grafana`.* to 'grafana'@'127.0.0.1' identified by 'gIWeWCa2k8GuMJSM61';
GRANT ALL PRIVILEGES ON `argus_grafana`.* to 'grafana'@'127.0.0.1' with grant option;
flush privileges;

##############################################

vi /etc/grafana/grafana.ini

#  修改
# The http port  to use
;http_port = 3000

# The public facing domain name used to access grafana from a browser與送出連結有關
;domain = localhost
;domain = xxx.xxx.xxx.xxx

# Redirect to correct domain if host header does not match domain
# Prevents DNS rebinding attacks
;enforce_domain = false

# The full public facing url you use in browser, used for redirects and emails
# If you use reverse proxy and sub path specify full url (with sub path)
;root_url = %(protocol)s://%(domain)s:%(http_port)s/
root_url = %(protocol)s://%(domain)s/grafana/

systemctl start grafana-server

##### 將sqlite3 換成 mariadb mysql  
create database argus_grafana;
GRANT USAGE ON `argus_grafana`.* to 'grafana'@'127.0.0.1' identified by 'gIWeWCa2k8GuMJSM61';
GRANT ALL PRIVILEGES ON `argus_grafana`.* to 'grafana'@'127.0.0.1' with grant option;
flush privileges;

#################################### Database ####################################
[database]
# You can configure the database connection by specifying type, host, name, user and password
# as separate properties or as on string using the url properties.

# Either "mysql", "postgres" or "sqlite3", it's your choice
;type = sqlite3
;host = 127.0.0.1:3306
;name = grafana
;user = root
# If the password contains # or ; you have to wrap it with triple quotes. Ex """#password;"""
;password =
type = mysql
host = 127.0.0.1:3306
name = argus_grafana
user = grafana
password = gIWeWCa2k8GuMJSM61
#url = mysql://grafana:gIWeWCa2k8GuMJSM61@127.0.0.1:3306/argus_grafana

systemctl restart grafana-server

######
```


```
#!/bin/bash

IPTABLES=/sbin/iptables

echo " * flushing old rules"
${IPTABLES} --flush
${IPTABLES} --delete-chain
${IPTABLES} --table nat --flush
${IPTABLES} --table nat --delete-chain

echo " * setting default policies"
${IPTABLES} -P INPUT DROP
${IPTABLES} -P FORWARD DROP
${IPTABLES} -P OUTPUT ACCEPT

echo " * allowing loopback devices"
${IPTABLES} -A INPUT -i lo -j ACCEPT
${IPTABLES} -A OUTPUT -o lo -j ACCEPT

${IPTABLES} -A INPUT -p tcp ! --syn -m state --state NEW -j DROP
${IPTABLES} -A INPUT -m state --state ESTABLISHED,RELATED -j ACCEPT

## BLOCK ABUSING IPs HERE ##
#echo " * BLACKLIST"
#${IPTABLES} -A INPUT -s _ABUSIVE_IP_ -j DROP
#${IPTABLES} -A INPUT -s _ABUSIVE_IP2_ -j DROP

echo " * allowing ssh on port 5622"
${IPTABLES} -A INPUT -p tcp --dport 5622  -m state --state NEW -j ACCEPT

echo " * allowing ftp on port 21"
${IPTABLES} -A INPUT -p tcp --dport 21  -m state --state NEW -j ACCEPT

echo " * allowing dns on port 53 udp"
${IPTABLES} -A INPUT -p udp -m udp --dport 53 -j ACCEPT

echo " * allowing dns on port 53 tcp"
${IPTABLES} -A INPUT -p tcp -m tcp --dport 53 -j ACCEPT

echo " * allowing http on port 80"
${IPTABLES} -A INPUT -p tcp --dport 80  -m state --state NEW -j ACCEPT

echo " * allowing https on port 443"
${IPTABLES} -A INPUT -p tcp --dport 443 -m state --state NEW -j ACCEPT

echo " * allowing smtp on port 25"
${IPTABLES} -A INPUT -p tcp -m state --state NEW -m tcp --dport 25 -j ACCEPT

echo " * allowing submission on port 587"
${IPTABLES} -A INPUT -p tcp -m state --state NEW -m tcp --dport 587 -j ACCEPT

echo " * allowing imaps on port 993"
${IPTABLES} -A INPUT -p tcp -m state --state NEW -m tcp --dport 993 -j ACCEPT

echo " * allowing pop3s on port 995"
${IPTABLES} -A INPUT -p tcp -m state --state NEW -m tcp --dport 995 -j ACCEPT

echo " * allowing imap on port 143"
${IPTABLES} -A INPUT -p tcp -m state --state NEW -m tcp --dport 143 -j ACCEPT

echo " * allowing pop3 on port 110"
${IPTABLES} -A INPUT -p tcp -m state --state NEW -m tcp -s 210.xx.xx.xx --dport 110 -j ACCEPT

echo " * allowing ping responses"
${IPTABLES} -A INPUT -p ICMP --icmp-type 8 -j ACCEPT

# DROP everything else and Log it
${IPTABLES} -A INPUT -j LOG
${IPTABLES} -A INPUT -j DROP

#
# Save settings
#
echo " * SAVING RULES"

if [[ -d /etc/network/if-pre-up.d ]]; then
    if [[ ! -f /etc/network/if-pre-up.d/iptables ]]; then
        echo -e "#!/bin/bash" > /etc/network/if-pre-up.d/iptables
        echo -e "test -e /etc/iptables.rules && iptables-restore -c /etc/iptables.rules" >> /etc/network/if-pre-up.d/iptables
        chmod +x /etc/network/if-pre-up.d/iptables
    fi
fi

iptables-save > /etc/fwall.rules
iptables-restore -c /etc/fwall.rules


## chmod +x /usr/local/bin/fwall-rules


Debian11_3_TIv2G_01


```
