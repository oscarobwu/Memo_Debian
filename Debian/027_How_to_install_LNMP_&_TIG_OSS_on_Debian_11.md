# 027_How_to_install_LNMP_&_TIG_OSS_on_Debian_11.md

### Step 01 – 安裝 nginx 1.2x

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

# root@debian-s-1vcpu-1gb-sfo2-01:~#
# 
# 
# Step 3: Install MariaDB 10.7 on Debian 11.2 (Buster)
# 
# Step 5. Manage MariaDB 10.7 on Debian 11 / Debian 10.
# 
# sudo systemctl start mariadb
# 
# systemctl status mariadb
# 
# sudo systemctl enable mariadb
# 
# sudo systemctl restart mariadb


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
CREATE USER 'myuser'@'localhost' IDENTIFIED BY 'Str0ngPassw0rd';

#Grant all privileges to the user
GRANT ALL PRIVILEGES ON *.* TO 'myuser'@'localhost' IDENTIFIED BY 'Str0ngPassw0rd';

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
CREATE USER 'labstackuser'@'localhost' IDENTIFIED BY 'new_Str0ngPassw0rd_here';
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
  
####
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

mkdir /etc/nginx/ssl

sudo openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /etc/nginx/ssl/nginx.key -out /etc/nginx/ssl/nginx.crt -subj "/C=TW/ST=Taiwan/L=Taipei/O=MongoDB/OU=IT/CN=mylocaldomain.com/emailAddress=admin@mylocaldomain.com"

cp /etc/nginx/sites-available/default.conf ~/

###############
server {
        listen 80 default_server;
        listen [::]:80 default_server;

  # 導向至 HTTPS
  rewrite ^(.*) https://$host$1 permanent;
}
server {
        # SSL 設定
        listen 443 ssl default_server;
        listen [::]:443 ssl default_server;

        # 憑證與金鑰的路徑
        ssl_certificate /etc/nginx/ssl/nginx.crt;
        ssl_certificate_key /etc/nginx/ssl/nginx.key;
        client_max_body_size 100M;
        # SSL configuration
        #
        # listen 443 ssl default_server;
        # listen [::]:443 ssl default_server;
        #
        # Note: You should disable gzip for SSL traffic.
        # See: https://bugs.debian.org/773332
        #
        # Read up on ssl_ciphers to ensure a secure configuration.
        # See: https://bugs.debian.org/765782
        #
        # Self signed certs generated by the ssl-cert package
        # Don't use them in a production server!
        #
        # include snippets/snakeoil.conf;

        root /var/www/html;

        # Add index.php to the list if you are using PHP
        index index.html index.htm index.nginx-debian.html index.php;

        server_name _;

        location / {
                # First attempt to serve request as file, then
                # as directory, then fall back to displaying a 404.
                try_files $uri $uri/ =404;
        }
#
        location ~ \.php$ {
            #try_files $uri /index.php =404;
            fastcgi_split_path_info ^(.+\.php)(/.+)$;
            fastcgi_pass   unix:/var/run/php/php8.1-fpm.sock;
            fastcgi_index  index.php;
            #fastcgi_param  SCRIPT_FILENAME /var/www/html/$fastcgi_script_name;
            fastcgi_param  SCRIPT_FILENAME $document_root$fastcgi_script_name;
            include        fastcgi_params;
        }
#
        location /grafana/ {
            proxy_pass http://localhost:3000/;
            proxy_set_header Host $host;
            proxy_set_header X-Real-IP $remote_addr;
            proxy_set_header X-Forwarded-Host $host;
            proxy_set_header X-Forwarded-Server $host;
            proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        }

        location ^~ /jenkins/ {
                proxy_set_header        Host              $host;
                proxy_set_header        X-Real-IP         $remote_addr;
                proxy_set_header        X-Forwarded-For   $proxy_add_x_forwarded_for;
                proxy_set_header        X-Forwarded-Proto $http_x_forwarded_proto;
                proxy_set_header        X-Forwarded-Port  $http_x_forwarded_port;
                proxy_max_temp_file_size 0;

                #proxy_pass              http://localhost:8080/jenkins/;
                proxy_pass              http://127.0.0.1:8080/jenkins/;
                # The following settings from https://wiki.jenkins-ci.org/display/JENKINS/Running+Hudson+behind+Nginx
                sendfile off;

                # Required for new HTTP-based CLI
                proxy_http_version      1.1;
                proxy_request_buffering off;
                # This is the maximum upload size
                client_max_body_size       10m;
                client_body_buffer_size    128k;

        }
        # pass PHP scripts to FastCGI server
        #
        #location ~ \.php$ {
        #       include snippets/fastcgi-php.conf;
        #
        #       # With php-fpm (or other unix sockets):
        #       fastcgi_pass unix:/run/php/php7.3-fpm.sock;
        #       # With php-cgi (or other tcp sockets):
        #       fastcgi_pass 127.0.0.1:9000;
        #}

        # deny access to .htaccess files, if Apache's document root
        # concurs with nginx's one
        #
        #location ~ /\.ht {
        #       deny all;
        #}
}


###############

重新啟動 nginx 並 設定開機啟動

systemctl restart nginx 
systemctl enable nginx 

touch /var/www/html/phpinfo.php && echo '<?php phpinfo(); ?>' >> /var/www/html/phpinfo.php

vi /var/www/html/index.html
<!DOCTYPE html>
<html>
<head>
   <!-- HTML meta refresh URL redirection -->
   <meta http-equiv="refresh"
   content="0; url=/grafana">
</head>
<body>
   <p>The page has moved to:
   <a href="/grafana">this page</a></p>
</body>
</html>

```

### 安裝 influxdb v2 

```

sudo apt -y update

wget -qO- https://repos.influxdata.com/influxdb.key | gpg --dearmor | sudo tee /etc/apt/trusted.gpg.d/influxdb.gpg > /dev/null

export DISTRIB_ID=$(lsb_release -si); export DISTRIB_CODENAME=$(lsb_release -sc)
echo "deb [signed-by=/etc/apt/trusted.gpg.d/influxdb.gpg] https://repos.influxdata.com/${DISTRIB_ID,,} ${DISTRIB_CODENAME} stable" | sudo tee /etc/apt/sources.list.d/influxdb.list > /dev/null


sudo apt update && sudo apt install influxdb2

# Confirm

$ apt-cache policy influxdb2

#### 

sudo systemctl start influxdb

sudo systemctl enable influxdb

sudo systemctl status influxdb

### Installing InfluxDB v2 cli

sudo apt install -y influxdb2-cli

確認

apt-cache policy influxdb2-cli

$ influx version

$ influx ping

### Set up InfluxDB

$ influx setup

influx setup
> Welcome to InfluxDB 2.0!
? Please type your primary username administrator
? Please type your password ***********
? Please type your password again ***********
? Please type your primary organization name f5mon
? Please type your primary bucket name f5monitor
? Please type your retention period in hours, or 0 for infinite 0
? Setup with these parameters?
  Username:          administrator
  Organization:      f5mon
  Bucket:            f5monitor
  Retention Period:  infinite
 Yes
User            Organization    Bucket
administrator   f5mon        f5monitor



$ influx config list
Active  Name    URL                     Org
*       default http://localhost:8086   f5mon

$ influx auth list


```

### 安裝 telegraf

```

# influxdb.key GPG Fingerprint: 05CE15085FC09D18E99EFB22684A14CF2582E0C5
wget -q https://repos.influxdata.com/influxdb.key
echo '23a1c8836f0afc5ed24e0486339d7cc8f6790b83886c4c96995b88a061c5bb5d influxdb.key' | sha256sum -c && cat influxdb.key | gpg --dearmor | sudo tee /etc/apt/trusted.gpg.d/influxdb.gpg > /dev/null
echo 'deb [signed-by=/etc/apt/trusted.gpg.d/influxdb.gpg] https://repos.influxdata.com/debian stable main' | sudo tee /etc/apt/sources.list.d/influxdata.list
sudo apt-get update && sudo apt-get install telegraf

OR

curl -LO -C - https://dl.influxdata.com/telegraf/releases/telegraf_1.24.1-1_amd64.deb

sudo dpkg -i telegraf_1.24.1-1_amd64.deb


  name_prefix = "linuxa_"
  
  
[outputs.influxdb_v2]
   namepass= ["linuxa_*"]
   urls = ["http://127.0.0.1:8086"]
   ## Token for authentication.
   token = "YhR9aO7xvfa9EjCYgeXKHatY-rY9ZGxS1IFTo1YEn96Ylny8kH7YL-8uOaDiF18zY_NNyjylLyiBWO2wANxIbA=="
   ## Organization is the name of the organization you wish to write to; must exist.
   organization = "f5mon"
   ## Destination bucket to write into.
   bucket = "f5monitor"

$ cp /etc/telegraf/telegraf.conf ~./telegraf.conf_org

$ vi /etc/telegraf/telegraf.conf

##############################################
# Configuration for telegraf agent 設定開始
[global_tags]


# Configuration for telegraf agent
[agent]
    interval = "10s"
    debug = false
#    hostname = "server-hostname"
    hostname = ""
    round_interval = true
    flush_interval = "10s"
    flush_jitter = "0s"
    collection_jitter = "0s"
    metric_batch_size = 1000
    metric_buffer_limit = 10000
    quiet = false
    logfile = ""
    omit_hostname = false
    precision = ""
	name_prefix = "linuxa_"

###############################################################################
# OUTPUT PLUGINS
###############################################################################

# Configuration for sending metrics to InfluxDB_v2
[outputs.influxdb_v2]
   namepass= ["linuxa_*"]
   urls = ["http://127.0.0.1:8086"]
   ## Token for authentication.
   token = "<chang token>"
   ## Organization is the name of the organization you wish to write to; must exist.
   organization = "f5mon"
   ## Destination bucket to write into.
   bucket = "f5monitor"


###############################################################################
# PROCESSOR PLUGINS
###############################################################################


###############################################################################
# AGGREGATOR PLUGINS
###############################################################################


###############################################################################
# INPUT PLUGINS
###############################################################################

# Read metrics about cpu usage
[[inputs.cpu]]
	## Whether to report per-cpu stats or not
	percpu = true
	## Whether to report total system cpu stats or not
	totalcpu = true
	## If true, collect raw CPU time metrics
	collect_cpu_time = false
	## If true, compute and report the sum of all non-idle CPU states
	report_active = false

# Read metrics about disk usage by mount point
[[inputs.disk]]
	## By default stats will be gathered for all mount points.
	## Set mount_points will restrict the stats to only the specified mount points.
	# mount_points = ["/"]

	## Ignore mount points by filesystem type.
	ignore_fs = ["tmpfs", "devtmpfs", "devfs"]

[[inputs.diskio]]
# Get kernel statistics from /proc/stat
[[inputs.kernel]]

# Read metrics about memory usage
[[inputs.mem]]

# Get the number of processes and group them by status
[[inputs.processes]]

# Read metrics about swap memory usage
[[inputs.swap]]

# Read metrics about system load & uptime
[[inputs.system]]
	## Uncomment to remove deprecated metrics.
	# fielddrop = ["uptime_format"]

# Read metrics about network interface usage
[[inputs.net]]
	## By default, telegraf gathers stats from any up interface (excluding loopback)
	## Setting interfaces will tell it to gather these explicit interfaces,
	## regardless of status.
	##
	# interfaces = ["eth0"]
	##
	## On linux systems telegraf also collects protocol stats.
	## Setting ignore_protocol_stats to true will skip reporting of protocol metrics.
	##
	# ignore_protocol_stats = false

# Read TCP metrics such as established, time wait and sockets counts.
[[inputs.netstat]]

# Read metrics about IO
[[inputs.io]]


###############################################################################
# SERVICE INPUT PLUGINS Eed
###############################################################################


##############
# telegraf -test -config /etc/telegraf/telegraf.conf --input-filter cpu
# telegraf -test -config /etc/telegraf/telegraf.conf --input-filter net
# telegraf -test -config /etc/telegraf/telegraf.conf --input-filter mem




```
### LTM 設定

```
#############################################################
# cat /etc/telegraf/telegraf.d/f5_XXX.XXX.XXX.XXX_v2.conf
[[inputs.snmp]]
  agents = [ "XXX.XXX.XXX.XXX" ]
  version = 2
  community = "public"
  interval = "60s"
  timeout = "10s"
  retries = 3
  name = "F5_system"

  [[inputs.snmp.field]]
    name = "hostname"
    oid = "RFC1213-MIB::sysName.0"
    is_tag = true

  [[inputs.snmp.field]]
    name = "F5_uptime"
    oid = "F5-BIGIP-SYSTEM-MIB::sysSystemUptime.0"
  [[inputs.snmp.field]]
    name = "F5_httpRequests"
    oid = "F5-BIGIP-SYSTEM-MIB::sysStatHttpRequests.0"
  [[inputs.snmp.field]]
    name = "F5_version"
    oid = "F5-BIGIP-SYSTEM-MIB::sysProductVersion.0"
  [[inputs.snmp.field]]
    name = "F5_Platform"
    oid = "SNMPv2-MIB::sysDescr.0"
  [[inputs.snmp.field]]
    name = "F5_Temperature"
    oid = "F5-BIGIP-SYSTEM-MIB::sysChassisTempTemperature.1"
  [[inputs.snmp.field]]
    name = "F5_Device_status"
    oid = "F5-BIGIP-SYSTEM-MIB::sysCmFailoverStatusId.0"
 [[inputs.snmp.field]]
    name = "F5_Synchronization_status_color"
    oid = "F5-BIGIP-SYSTEM-MIB::sysCmSyncStatusColor.0"


 [[inputs.snmp.table]]
    name = "F5_CPU"
    oid = "F5-BIGIP-SYSTEM-MIB::sysMultiHostCpuTable"
    inherit_tags = [ "hostname" ]

 [[inputs.snmp.table]]
    name = "F5_Memory_Usage"
    oid = "F5-BIGIP-SYSTEM-MIB::sysMultiHostTable"
    inherit_tags = [ "hostname" ]

## ------------------------------------------- ##
## For Virtual Server Group
## ------------------------------------------- ##
  [[inputs.snmp.table]]
    name = "F5_virtual_server"
    inherit_tags = [ "hostname" ]

    [[inputs.snmp.table.field]]
      name = "ltmVirtualServStatName"
      oid = "F5-BIGIP-LOCAL-MIB::ltmVirtualServStatName"
      is_tag = true

    [[inputs.snmp.table.field]]
      name = "ltmVirtualServStatClientCurConns"
      oid = "F5-BIGIP-LOCAL-MIB::ltmVirtualServStatClientCurConns"
    [[inputs.snmp.table.field]]
      name = "ltmVirtualServStatClientBytesIn"
      oid = "F5-BIGIP-LOCAL-MIB::ltmVirtualServStatClientBytesIn"
    [[inputs.snmp.table.field]]
      name = "ltmVirtualServStatClientBytesOut"
      oid = "F5-BIGIP-LOCAL-MIB::ltmVirtualServStatClientBytesOut"

## ------------------------------------------- ##
## For Pool Group
## ------------------------------------------- ##
  [[inputs.snmp.table]]
    name = "F5_PoolStatus"
    inherit_tags = [ "hostname" ]

    [[inputs.snmp.table.field]]
      name = "ltmPoolStatName"
      oid = "F5-BIGIP-LOCAL-MIB::ltmPoolStatName"
      is_tag = true

    [[inputs.snmp.table.field]]
      name = "ltmPoolStatServerCurConns"
      oid = "F5-BIGIP-LOCAL-MIB::ltmPoolStatServerCurConns"
    [[inputs.snmp.table.field]]
      name = "ltmPoolStatServerBytesIn"
      oid = "F5-BIGIP-LOCAL-MIB::ltmPoolStatServerBytesIn"
    [[inputs.snmp.table.field]]
      name = "ltmPoolStatServerBytesOut"
      oid = "F5-BIGIP-LOCAL-MIB::ltmPoolStatServerBytesOut"

## ------------------------------------------- ##
## For Pool member
## ------------------------------------------- ##
  [[inputs.snmp.table]]
    name = "F5_PoolMembers_Status"
    inherit_tags = [ "hostname" ]

    [[inputs.snmp.table.field]]
      name = "ltmPoolMemberStatNodeName"
      oid = "F5-BIGIP-LOCAL-MIB::ltmPoolMemberStatNodeName"
      is_tag = true

    [[inputs.snmp.table.field]]
      name = "ltmPoolMemberStatServerCurConns"
      oid = "F5-BIGIP-LOCAL-MIB::ltmPoolMemberStatServerCurConns"
    [[inputs.snmp.table.field]]
      name = "ltmPoolMemberStatServerBytesIn"
      oid = "F5-BIGIP-LOCAL-MIB::ltmPoolMemberStatServerBytesIn"
    [[inputs.snmp.table.field]]
      name = "ltmPoolMemberStatServerBytesOut"
      oid = "F5-BIGIP-LOCAL-MIB::ltmPoolMemberStatServerBytesOut"

## ------------------------------------------- ##
## For Client SSL Profile
## ------------------------------------------- ##
  [[inputs.snmp.table]]
    name = "F5_ClientSSLStatus"
    inherit_tags = [ "hostname" ]

    [[inputs.snmp.table.field]]
      name = "ltmClientSslStatName"
      oid = "F5-BIGIP-LOCAL-MIB::ltmClientSslStatName"
      is_tag = true

    [[inputs.snmp.table.field]]
      name = "ltmClientSslStatCurConns"
      oid = "F5-BIGIP-LOCAL-MIB::ltmClientSslStatCurConns"

#####################################################
#
# Export Information to influxdb_v2
#
#####################################################

[outputs.influxdb_v2]
   ## filter data
   namepass= ["F5_*"]
   urls = ["http://127.0.0.1:8086"]
   ## Token for authentication.
   token = "<chang token>"
   ## Organization is the name of the organization you wish to write to; must exist.
   organization = "f5mon"
   ## Destination bucket to write into.
   bucket = "f5monitor"

```


### 安裝 Grafana 

```

sudo apt-get install -y adduser libfontconfig1
wget https://dl.grafana.com/oss/release/grafana_9.2.0_amd64.deb
sudo dpkg -i grafana_9.2.0_amd64.deb

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

### 非必要安裝
######
raintank-worldping-app
Missing signature
digrich-bubblechart-panel
Missing signature
worldping-cta
Missing signature
worldping-endpoint-list
Missing signature
worldping-endpoint-nav
###########
grafana-cli plugins install jdbranham-diagram-panel
# 氣泡圖
grafana-cli plugins install digrich-bubblechart-panel
#
grafana-cli plugins install raintank-worldping-app
# json資料
grafana-cli plugins install grafana-simple-json-datasource
#zabbix報警
grafana-cli plugins install alexanderzobnin-zabbix-app


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

# 重啟 Grafana
systemctl restart grafana-server.service

#################################################

https://luppeng.wordpress.com/2022/05/01/configuring-postgresql-for-grafana-high-availability-setup/



Create Application Database and User for Grafana

$ psql -h 127.0.0.1 -U postgres -d postgres
(...)
postgres=# CREATE USER grafana_user WITH LOGIN NOSUPERUSER INHERIT NOCREATEDB NOCREATEROLE NOREPLICATION;
CREATE ROLE
postgres=# ALTER ROLE grafana_user WITH PASSWORD '<GRAFANA_USER_PASSWORD>';
ALTER ROLE
postgres=# CREATE DATABASE grafana WITH OWNER = grafana_user ENCODING = 'UTF8' LC_COLLATE = 'en_US.UTF-8' LC_CTYPE = 'en_US.UTF-8' TABLESPACE = pg_default CONNECTION LIMIT = -1;
CREATE DATABASE

postgres=# \q 
# 離開

修改 Grafana 設定

$ sudo vi /etc/grafana/grafana.ini
(...)
[database]
type = postgres
host = <HOST_IP>:5432
name = grafana
user = grafana_user
password = <REPLACE WITH GRAFANA_USER PASSWORD FROM STEP 3>

$ sudo systemctl restart grafana-server

$ sudo systemctl status grafana-server



Config Grafana

From

[database]
;type = sqlite3
;host = 127.0.0.1:3306
;name = grafana
;user = root
# If the password contains # or ; you have to wrap it with triple quotes. Ex """#password;"""
;password =
;url =

[database]
type = postgres
url = postgres://dbuser_grafana:DBUser.Grafana@meta/grafana

```
