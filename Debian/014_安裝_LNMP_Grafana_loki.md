# 安裝 LNMP + Grafana + loki

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

### 安裝 loki

```
#建立目錄
sudo mkdir /etc/loki
sudo mkdir -p /data/loki

curl -O -L "https://github.com/grafana/loki/releases/download/v2.5.0/loki-linux-amd64.zip"

unzip loki-linux-amd64.zip
unzip loki-linux-amd64.zip

# chmod a+x "loki-linux-amd64"

sudo mv loki-linux-amd64 /usr/local/bin/

sudo vi /etc/loki/config-loki.yml
######################################################################
# 新增以下內容

auth_enabled: false

server:
  http_listen_port: 3100
  grpc_listen_port: 9096
  #grpc_listen_port :  39095 #grpc listening port, default is 9095 
  grpc_server_max_recv_msg_size :  15728640   #grpc maximum received message value, default 4m 
  grpc_server_max_send_msg_size :  15728640   #grpc maximum send message value, default 4m

common:
  path_prefix: /data/loki
  storage:
    filesystem:
      chunks_directory: /data/loki/chunks
      rules_directory: /data/loki/rules
  replication_factor: 1
  ring:
    instance_addr: 127.0.0.1
    kvstore:
      store: inmemory

schema_config:
  configs:
    - from: 2020-10-24
      store: boltdb-shipper
      object_store: filesystem
      schema: v11
      index:
        prefix: index_
        period: 24h

storage_config:
  boltdb:
    directory: /data/loki/index   #索引檔案儲存地址

  filesystem:
    directory: /data/loki/chunks  #塊儲存地址

limits_config:
  enforce_metric_name: false
  reject_old_samples: true
  reject_old_samples_max_age: 168h


chunk_store_config:
  # 最大可查詢歷史日期 90天
  max_look_back_period: 2160h

# 表的保留期90天
table_manager:
  retention_deletes_enabled: true
  retention_period: 2160h
#######################################################################

sudo tee /etc/systemd/system/loki.service<<EOF
[Unit]
Description=Loki service
After=network.target

[Service]
Type=simple
User=root

ExecStart=/usr/local/bin/loki-linux-amd64 -config.file /etc/loki/config-loki.yml

[Install]
WantedBy=multi-user.target
EOF

#
sudo systemctl daemon-reload
sudo systemctl start loki
sudo systemctl enable --now loki

```

### 安裝 fluentd

```
## 安裝 fluentd

# td-agent 4
curl -fsSL https://toolbelt.treasuredata.com/sh/install-debian-bullseye-td-agent4.sh | sh

sudo td-agent-gem install fluent-plugin-grafana-loki


$ sudo systemctl start td-agent.service
$ sudo systemctl status td-agent.service

sudo vi /etc/td-agent/td-agent.conf

##
<source>
  @type syslog
  @id syslog_in
  port 5514
  bind 0.0.0.0
  severity_key level
  facility_key facility
  tag syslog
  <transport tcp>
  </transport>
  <parse>
    message_format rfc5424
  </parse>
</source>

<match syslog.**>
  @type loki
  @id syslog_out
  url "http://localhost:3100"
  flush_interval 1s
  flush_at_shutdown true
  buffer_chunk_limit 1m
  extra_labels {"app":"syslog"}
  <label>
    pid
    host
    level
    facility
  </label>
</match>
##

```

### 設定 rsyslog 拋送

```
##設定 rsyslog 拋送

vi /etc/rsyslog.d/50-forward.conf

$ModLoad imudp
$UDPServerRun 514

$ActionQueueType LinkedList # use asynchronous processing
$ActionQueueFileName srvrfwd # set file name, also enables disk mode
$ActionResumeRetryCount -1 # infinite retries on insert failure
$ActionQueueSaveOnShutdown on # save in-memory data if rsyslog shuts down

*.* @@127.0.0.1:5514;RSYSLOG_SyslogProtocol23Format

sudo systemctl restart rsyslog
sudo systemctl restart td-agent


開通防火牆

####

檢視grafana

```

### 安裝 Grafana

```
sudo apt-get install -y adduser libfontconfig1
wget https://dl.grafana.com/oss/release/grafana_8.5.3_amd64.deb
sudo dpkg -i grafana_8.5.3_amd64.deb

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


######

設定ssl

mkdir /etc/nginx/ssl

sudo openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /etc/nginx/ssl/nginx.key -out /etc/nginx/ssl/nginx.crt -subj "/C=TW/ST=Taiwan/L=Taipei/O=MongoDB/OU=IT/CN=mylocaldomain.com/emailAddress=admin@mylocaldomain.com"

# 設定 nginx conf

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

####

touch /var/www/html/phpinfo.php && echo '<?php phpinfo(); ?>' >> /var/www/html/phpinfo.php

#
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

## 安裝 prometh


```
sudo groupadd --system prometheus
sudo useradd -s /sbin/nologin --system -g prometheus prometheus

sudo mkdir /var/lib/prometheus
for i in rules rules.d files_sd; do sudo mkdir -p /etc/prometheus/${i}; done


sudo apt-get update
sudo apt-get -y install wget curl
mkdir -p /tmp/prometheus && cd /tmp/prometheus
curl -s https://api.github.com/repos/prometheus/prometheus/releases/latest|grep browser_download_url|grep linux-amd64|cut -d '"' -f 4|wget -qi -

tar xvf prometheus*.tar.gz
cd prometheus*/

sudo mv prometheus promtool /usr/local/bin/

sudo mv prometheus.yml  /etc/prometheus/prometheus.yml

sudo mv consoles/ console_libraries/ /etc/prometheus/
cd ~/
rm -rf /tmp/prometheus

vi /etc/prometheus/prometheus.yml

# my global config
global:
  scrape_interval:     15s # Set the scrape interval to every 15 seconds. Default is every 1 minute.
  evaluation_interval: 15s # Evaluate rules every 15 seconds. The default is every 1 minute.
  # scrape_timeout is set to the global default (10s).

# Alertmanager configuration
alerting:
  alertmanagers:
  - static_configs:
    - targets:
      # - alertmanager:9093

# Load rules once and periodically evaluate them according to the global 'evaluation_interval'.
rule_files:
  # - "first_rules.yml"
  # - "second_rules.yml"

# A scrape configuration containing exactly one endpoint to scrape:
# Here it's Prometheus itself.
scrape_configs:
  # The job name is added as a label `job=<job_name>` to any timeseries scraped from this config.
  - job_name: 'prometheus'

    # metrics_path defaults to '/metrics'
    # scheme defaults to 'http'.

    static_configs:
    - targets: ['localhost:9090']
	
	
## 建立系統執行檔

sudo tee /etc/systemd/system/prometheus.service<<EOF
[Unit]
Description=Prometheus
Documentation=https://prometheus.io/docs/introduction/overview/
Wants=network-online.target
After=network-online.target

[Service]
Type=simple
User=prometheus
Group=prometheus
ExecReload=/bin/kill -HUP $MAINPID
ExecStart=/usr/local/bin/prometheus \
  --config.file=/etc/prometheus/prometheus.yml \
  --storage.tsdb.path=/var/lib/prometheus \
  --web.console.templates=/etc/prometheus/consoles \
  --web.console.libraries=/etc/prometheus/console_libraries \
  --web.listen-address=0.0.0.0:9090 \
  --web.external-url=

SyslogIdentifier=prometheus
Restart=always

[Install]
WantedBy=multi-user.target
EOF

# 設定權限

for i in rules rules.d files_sd; do sudo chown -R prometheus:prometheus /etc/prometheus/${i}; done
for i in rules rules.d files_sd; do sudo chmod -R 775 /etc/prometheus/${i}; done
sudo chown -R prometheus:prometheus /var/lib/prometheus/


sudo systemctl daemon-reload
sudo systemctl start prometheus
sudo systemctl enable prometheus

sudo systemctl status prometheus


```

### 安裝 node

```

curl -s https://api.github.com/repos/prometheus/node_exporter/releases/latest| grep browser_download_url|grep linux-amd64|cut -d '"' -f 4|wget -qi -

tar -xvf node_exporter*.tar.gz
cd  node_exporter*/
sudo cp node_exporter /usr/local/bin

node_exporter --version

# 設定啟動檔

sudo tee /etc/systemd/system/node_exporter.service <<EOF
[Unit]
Description=Node Exporter
Wants=network-online.target
After=network-online.target

[Service]
User=prometheus
ExecStart=/usr/local/bin/node_exporter

[Install]
WantedBy=default.target
EOF


##

sudo systemctl daemon-reload
sudo systemctl start node_exporter
sudo systemctl enable node_exporter

sudo systemctl status node_exporter.service

# 更新 prometheus 設定檔

vi /etc/prometheus/prometheus.yml


scrape_configs:
  # The job name is added as a label `job=<job_name>` to any timeseries scraped from this config.
  - job_name: "prometheus"
    static_configs:
      - targets: ["localhost:9090"]
  - job_name: 'node_exporter'
    static_configs:
      - targets: ['localhost:9100']
	  
	  
sudo systemctl restart prometheus


設定 Grafana

安裝 telegraf
curl -LO -C - https://dl.influxdata.com/telegraf/releases/telegraf_1.22.2-1_amd64.deb
sudo dpkg -i telegraf_1.22.2-1_amd64.deb


```
