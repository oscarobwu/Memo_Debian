# 030_Debian12_0_a_安裝_LNMP_loki_Grafana.md

## Debian 12.0 安裝 新版 LNPP + loki + Grafana

```
###

客製化 登入

vi on_login.sh

#! /usr/bin/env bash

# Basic info
HOSTNAME=`uname -n`
ROOT=`df -Ph | grep xvda1 | awk '{print $4}' | tr -d '\n'`

# System load
MEMORY1=`free -t -m | grep Total | awk '{print $3" MB";}'`
MEMORY2=`free -t -m | grep "Mem" | awk '{print $2" MB";}'`
LOAD1=`cat /proc/loadavg | awk {'print $1'}`
LOAD5=`cat /proc/loadavg | awk {'print $2'}`
LOAD15=`cat /proc/loadavg | awk {'print $3'}`

echo "
===============================================
 - Hostname............: $HOSTNAME
 - Disk Space..........: $ROOT remaining
===============================================
 - CPU usage...........: $LOAD1, $LOAD5, $LOAD15 (1, 5, 15 min)
 - Memory used.........: $MEMORY1 / $MEMORY2
 - Swap in use.........: `free -m | tail -n 1 | awk '{print $3}'` MB
===============================================
"

sudo mv on_login.sh /etc/update-motd.d/05-info
sudo chmod +x /etc/update-motd.d/05-info

```

# Step 01 – Nginx


```
apt install sudo -y
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


# Step 02 – Postgresql 15

```

# apt update && sudo apt upgrade -y

# apt install wget sudo curl gnupg2 -y

# sh -c 'echo "deb http://apt.postgresql.org/pub/repos/apt $(lsb_release -cs)-pgdg main" > /etc/apt/sources.list.d/pgdg.list'

# wget --quiet -O - https://www.postgresql.org/media/keys/ACCC4CF8.asc | sudo apt-key add -

# apt -y update

# apt install postgresql-15 -y

# systemctl start postgresql

# systemctl enable postgresql

# systemctl status postgresql

# ss -antpl | grep 5432

# sudo -u postgres psql -c "SELECT version();"


管理 psql

sudo -u postgres psql

CREATE ROLE admin WITH LOGIN SUPERUSER CREATEDB CREATEROLE PASSWORD 'f99XVu73Spfcgxw';
ALTER USER postgres WITH PASSWORD 'f99XVu73Spfcgxw';
Manage application users

修改密碼 

postgres=# ALTER USER postgres PASSWORD 'Pa55WordD8m0';

#################################

create database grafana_db;
create user grafana_user with encrypted password 'f99XVu73Spfcgxw';
grant all privileges on database grafana_db to grafana_user;
\q

GRANT ALL ON DATABASE grafana_db TO grafana_user;
ALTER DATABASE grafana_db OWNER TO grafana_user;

$ psql -h 127.0.0.1 -U grafana_user -d grafana_db


grafana_db=> \dt

#######################

create database test_db;
create user test_user with encrypted password 'f99XVu73Spfcgxw';
grant all privileges on database test_db to test_user;
\q

##################################

CREATE TABLE accounts (
	user_id serial PRIMARY KEY,
	username VARCHAR ( 50 ) UNIQUE NOT NULL,
	password VARCHAR ( 50 ) NOT NULL,
	email VARCHAR ( 255 ) UNIQUE NOT NULL,
	created_on TIMESTAMP NOT NULL,
        last_login TIMESTAMP 
);



##################################


####
create database zabbix;
create user zabbix with encrypted password 'f99XVu73Spfcgxw';
grant all privileges on database zabbix to zabbix;

# 確認 postgresql-14 服務是否啟動
ss -tunelp | grep 5432

sudo su - postgres
psql -c "alter user postgres with password 'StrongDBPassw0rd'"
exit

Step 4a – Allow Remote Connections to PostgreSQL for Grafana HA Architecture

$ find / -name postgresql.conf 2>/dev/null
/etc/postgresql/15/main/postgresql.conf
$ sudo vi /etc/postgresql/15/main/postgresql.conf
listen_addresses = '*'     ### add this line


$ sudo vi /etc/grafana/grafana.ini
(...)
[database]
type = postgres
host = 127.0.0.1:5432
name = grafana_db
user = grafana_user
password = f99XVu73Spfcgxw


```


# Step 03 – 安裝 php 8.1



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
                fastcgi_pass   unix:/var/run/php/php8.1-fpm.sock;
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



# Step 04 – 設定 Nginx 設定檔


```

############################################################

mkdir /etc/nginx/ssl

sudo openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /etc/nginx/ssl/nginx.key -out /etc/nginx/ssl/nginx.crt

Country Name (2 letter code) [AU]:TW1
State or Province Name (full name) [Some-State]:Taiwan2
Locality Name (eg, city) []:Taipei3
Organization Name (eg, company) [Internet Widgits Pty Ltd]:My Company4
Organizational Unit Name (eg, section) []:My Unit5
Common Name (e.g. server FQDN or YOUR name) []:myhost.loacldomain.tw
Email Address []:user@loacldomain.tw

# 一行指令執行
mkdir /etc/nginx/ssl

sudo openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /etc/nginx/ssl/nginx.key -out /etc/nginx/ssl/nginx.crt -subj "/C=TW/ST=Taiwan/L=Taipei/O=MongoDB/OU=IT/CN=mylocaldomain.com/emailAddress=admin@mylocaldomain.com"

##################################
#
# 一行指令執行
#
##################################
```

```
mkdir /etc/nginx/ssl
sudo openssl req -x509 -nodes -days 3650 -newkey rsa:2048 -keyout /etc/nginx/ssl/nginx.key -out /etc/nginx/ssl/nginx.crt -subj "/C=TW/ST=Taiwan/L=Taipei/O=MongoDB/OU=IT/CN=$HOSTNAME.mylocaldomain.com/emailAddress=admin@mylocaldomain.com"
```

```
#nginx 設定檔

cp /etc/nginx/sites-available/default.conf default.conf.`date +"%Y%m%d%H%M%S"`

vi /etc/nginx/sites-available/default.conf

##################################
#
#### 以下為最後OK設定檔 20211006更新
#
##################################

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

                proxy_pass              http://localhost:8080/jenkins/;
                #proxy_pass              http://127.0.0.1:8081/jenkins/;
                # The following settings from https://wiki.jenkins-ci.org/display/JENKINS/Running+Hudson+behind+Nginx
                sendfile off;

                # Required for new HTTP-based CLI
                proxy_http_version      1.1;
                proxy_request_buffering off;
                # This is the maximum upload size
                client_max_body_size       10m;
                client_body_buffer_size    128k;

        }

        location /gitlab/ {
                   proxy_pass http://127.0.0.1:10987/gitlab/;
                   proxy_set_header Host $http_host;
                   proxy_set_header X-Real-IP $remote_addr;
                   proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
                   proxy_set_header X-Forwarded-Proto https;
                   proxy_set_header X-Forwarded-Protocol https;
                   proxy_set_header X-Url-Scheme https;
                   proxy_set_header X-Forwarded-Ssl on;
                   proxy_read_timeout 90;
                   client_max_body_size 0;
                   gzip off;
                   proxy_http_version 1.1;
                                   #
                                   #proxy_redirect      http://localhost:10987/gitlab $scheme://gitlab.example.com:10987/gitlab;
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


systemctl restart nginx

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
   <a href="/jenkins">this page</a></p>
</body>
</html>

```



# Step 05 – Prometheus 加 node-exporter


```

步骤 1.

apt update && sudo apt upgrade -y

步骤 2. 創建 Prometheus 用户。

sudo groupadd --system prometheus
sudo useradd -s /sbin/nologin --system -g prometheus prometheus

mkdir -p /tmp/prometheus && cd /tmp/prometheus
curl -s https://api.github.com/repos/prometheus/prometheus/releases/latest|grep browser_download_url|grep linux-amd64|cut -d '"' -f 4|wget -qi -

tar xvf prometheus*.tar.gz

cd prometheus-2.45.0.linux-amd64
 
mkdir /etc/prometheus
mkdir /var/lib/prometheus

sudo mv prometheus.yml /etc/prometheus/prometheus.yml
sudo mv consoles/ console_libraries/ /etc/prometheus/
sudo cp prometheus /usr/local/bin

步骤 4. 配置 Prometheus。

vi /etc/prometheus/prometheus.yml

# my global config
global:
  scrape_interval: 15s # Set the scrape interval to every 15 seconds. Default is every 1 minute.
  evaluation_interval: 15s # Evaluate rules every 15 seconds. The default is every 1 minute.
  # scrape_timeout is set to the global default (10s).# Alertmanager configuration
alerting:
  alertmanagers:
    - static_configs:
        - targets:
          # - alertmanager:9093# Load rules once and periodically evaluate them according to the global 'evaluation_interval'.
rule_files:
  # - "first_rules.yml"
  # - "second_rules.yml"# A scrape configuration containing exactly one endpoint to scrape:
# Here it's Prometheus itself.
scrape_configs:
  # The job name is added as a label `job=` to any timeseries scraped from this config.
  - job_name: "prometheus"

    # metrics_path defaults to '/metrics'
    # scheme defaults to 'http'.

    static_configs:
      - targets: ["localhost:9090"]

步骤 5. 创建 Prometheus Systemd 服务

vi /etc/systemd/system/prometheus.service

#### 新增內容如下 

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
ExecStart=/usr/local/bin/prometheus 
  --config.file=/etc/prometheus/prometheus.yml 
  --storage.tsdb.path=/var/lib/prometheus 
  --web.console.templates=/etc/prometheus/consoles 
  --web.console.libraries=/etc/prometheus/console_libraries 
  --web.listen-address=0.0.0.0:9090 
  --web.external-url=

SyslogIdentifier=prometheus
Restart=always

[Install]
WantedBy=multi-user.target
 
#################################################

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

Save 和 close 文件，然後我们将更改其權限：

for i in rules rules.d files_sd; do sudo chown -R prometheus:prometheus /etc/prometheus/${i}; done
for i in rules rules.d files_sd; do sudo chmod -R 775 /etc/prometheus/${i}; done
sudo chown -R prometheus:prometheus /var/lib/prometheus/

或

chown -R prometheus:prometheus /etc/prometheus/
chmod -R 775 /etc/prometheus/
chown -R prometheus:prometheus /var/lib/prometheus/


systemctl daemon-reload
systemctl start prometheus
systemctl enable prometheus

步骤 6. 访问 Prometheus Web 界面。

https://your-ip-address:9090 


Step E: Install node_exporter on Debian 10 Buster

curl -s https://api.github.com/repos/prometheus/node_exporter/releases/latest \
| grep browser_download_url \
| grep linux-amd64 \
| cut -d '"' -f 4 \
| wget -qi -

tar -xvf node_exporter*.tar.gz
cd  node_exporter*/
sudo cp node_exporter /usr/local/bin
# 確認版本
$ node_exporter --version

設定啟動服務

sudo tee /etc/systemd/system/node_exporter.service<<EOF
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

sudo systemctl daemon-reload
sudo systemctl start node_exporter
sudo systemctl enable node_exporter

$  systemctl status node_exporter.service 

#將服務監控加入普羅米修斯
sudo vi /etc/prometheus/prometheus.yml

scrape_configs:
  # The job name is added as a label `job=<job_name>` to any timeseries scraped from this config.
  - job_name: "prometheus"

    # metrics_path defaults to '/metrics'
    # scheme defaults to 'http'.

    static_configs:
      - targets: ["localhost:9090"]

  - job_name: 'node_exporter'
    static_configs:
      - targets: ['localhost:9100']


sudo systemctl restart prometheus

Grafana Dashborad ID: 1860

```

# Step 06 – Grafana 10 使用 PostgreSQL 15 


```

#sudo apt-get install -y adduser libfontconfig1
#wget https://dl.grafana.com/oss/release/grafana_10.0.1_amd64.deb
#sudo dpkg -i grafana_10.0.1_amd64.deb

sudo apt-get install -y adduser libfontconfig1
wget https://dl.grafana.com/oss/release/grafana_10.0.2_amd64.deb
sudo dpkg -i grafana_10.0.2_amd64.deb

systemctl daemon-reload
systemctl start grafana-server
systemctl enable grafana-server.service

vi /etc/grafana/grafana.ini

######
修改 設定檔

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

systemctl restart grafana-server


管理 psql

sudo -u postgres psql

CREATE ROLE admin WITH LOGIN SUPERUSER CREATEDB CREATEROLE PASSWORD 'f99XVu73Spfcgxw';
ALTER USER postgres WITH PASSWORD 'f99XVu73Spfcgxw';
Manage application users

修改密碼 

postgres=# ALTER USER postgres PASSWORD 'Pa55WordD8m0';


############

$ psql -h 127.0.0.1 -U postgres -d postgres
(...)
postgres=# CREATE USER grafana_user WITH LOGIN NOSUPERUSER INHERIT NOCREATEDB NOCREATEROLE NOREPLICATION;
CREATE ROLE
postgres=# ALTER ROLE grafana_user WITH PASSWORD '';
ALTER ROLE
postgres=# CREATE DATABASE grafana WITH OWNER = grafana_user ENCODING = 'UTF8' LC_COLLATE = 'en_US.UTF-8' LC_CTYPE = 'en_US.UTF-8' TABLESPACE = pg_default CONNECTION LIMIT = -1;
CREATE DATABASE

$ psql -h 127.0.0.1 -U postgres -d postgres
(...)
postgres=# CREATE USER grafana_user WITH LOGIN NOSUPERUSER INHERIT NOCREATEDB NOCREATEROLE NOREPLICATION;
CREATE ROLE
postgres=# ALTER ROLE grafana_user WITH PASSWORD 'f99XVu73Spfcgxw';
ALTER ROLE
postgres=# CREATE DATABASE grafana_db WITH OWNER = grafana_user ENCODING = 'UTF8' LC_COLLATE = 'en_US.UTF-8' LC_CTYPE = 'en_US.UTF-8' TABLESPACE = pg_default CONNECTION LIMIT = -1;
CREATE DATABASE

# 如果遇到 資料庫權限不足 可以設定
# 可以確認 grafana.log
GRANT ALL ON DATABASE grafana_db TO grafana_user;

ALTER DATABASE grafana_db OWNER TO grafana_user;


#################################

create database grafana_db;
create user grafana_user with encrypted password 'f99XVu73Spfcgxw';
grant all privileges on database grafana_db to grafana_user;
\q

$ psql -h 127.0.0.1 -U grafana_user -d grafana_db


grafana_db=> \dt

##### 將sqlite3 換成 postgresql-15   
修改 Grafana 設定檔

$ sudo vi /etc/grafana/grafana.ini
(...)
[database]
type = postgres
host = :5432
name = grafana_db
user = grafana_user
password = 

$ sudo systemctl restart grafana-server


修改 Grafana 設定檔

$ sudo vi /etc/grafana/grafana.ini
(...)
[database]
type = postgres
host = 127.0.0.1:5432
name = grafana_db
user = grafana_user
password = f99XVu73Spfcgxw

$ sudo systemctl restart grafana-server

```



# Step 07 – Promtail Agent


```

需要確認最新版本


Download and unzip Promtail
curl -O -L "https://github.com/grafana/loki/releases/download/v2.6.1/promtail-linux-amd64.zip"

更新為 :

curl -O -L "https://github.com/grafana/loki/releases/download/v2.8.2/promtail-linux-amd64.zip"

unzip promtail-linux-amd64.zip

chmod a+x promtail-linux-amd64

sudo mv promtail-linux-amd64 /usr/local/bin/promtail

sudo mkdir /etc/promtail

vi /etc/promtail/promtail-local-config.yaml

server:  
  http_listen_port: 9080  
  grpc_listen_port: 0  
positions:  
  filename: /tmp/positions.yaml  
clients:  
  - url: http://localhost:3100/loki/api/v1/push 
scrape_configs: 
  - job_name: system
    static_configs:
    - targets:
        - localhost #Promtail target is localhost
      labels:
        instance: Debian12-0-loki02 #Label identifier for instance (hostname -f)
        env: Debian-Linux #Environment label
        job: secure #Job label
        __path__: /var/log/secure

  - job_name: syslog 
    syslog: 
      listen_address: 0.0.0.0:1514 
      labels: 
        job: syslog 
    relabel_configs: 
      - source_labels: [__syslog_message_hostname] 
        target_label: host 
      - source_labels: [__syslog_message_hostname] 
        target_label: hostname 
      - source_labels: [__syslog_message_severity] 
        target_label: level 
      - source_labels: [__syslog_message_app_name] 
        target_label: application 
      - source_labels: [__syslog_message_facility] 
        target_label: facility 
      - source_labels: [__syslog_connection_hostname] 
        target_label: connection_hostname
		
###########################################


sudo vi /etc/systemd/system/promtail.service


[Unit]
Description=Promtail service
After=network.target

[Service]
Type=simple
User=root
ExecStart=/usr/local/bin/promtail -config.file /etc/promtail/promtail-local-config.yaml

[Install]
WantedBy=multi-user.target

##############

sudo tee /etc/systemd/system/promtail.service<<EOF
[Unit]
Description=Promtail service
After=network.target

[Service]
Type=simple
User=root
ExecStart=/usr/local/bin/promtail -config.file /etc/promtail/promtail-local-config.yaml

[Install]
WantedBy=multi-user.target
EOF


##################################################################################

sudo systemctl daemon-reload

sudo systemctl enable promtail.service

systemctl start promtail.service

```

# Step 08 – 安裝 loki

```

需要確認最新版本 https://github.com/grafana/loki/releases/ 在修改版本 如現在最新為 v2.8.2

curl -LO https://github.com/grafana/loki/releases/download/v2.4.2/loki-linux-amd64.zip

更新為 :

curl -LO https://github.com/grafana/loki/releases/download/v2.8.2/loki-linux-amd64.zip

sudo apt install -y unzip

# 解壓後 將執行檔案 放到 執行目錄統一管理
unzip loki-linux-amd64.zip
sudo mv loki-linux-amd64 /usr/local/bin/loki

建立工作目錄
sudo mkdir /etc/loki
sudo mkdir -p /data/loki


Download and unzip Promtail
curl -O -L "https://github.com/grafana/loki/releases/download/v2.6.1/promtail-linux-amd64.zip"

更新為 :

curl -O -L "https://github.com/grafana/loki/releases/download/v2.8.2/promtail-linux-amd64.zip"

unzip promtail-linux-amd64.zip

chmod a+x promtail-linux-amd64

sudo mv promtail-linux-amd64 /usr/local/bin/promtail

sudo mkdir /etc/promtail

sudo vi /etc/loki/loki-local-config.yaml

################################################
auth_enabled: false 
server: 
  http_listen_port: 3100 
ingester: 
  lifecycler: 
    address: 127.0.0.1 
    ring: 
      kvstore: 
        store: inmemory 
      replication_factor: 1 
    final_sleep: 0s 
  chunk_idle_period: 5m 
  chunk_retain_period: 30s 
schema_config: 
  configs: 
  - from: 2020-05-15 
    store: boltdb 
    object_store: filesystem 
    schema: v11 
    index: 
      prefix: index_ 
      period: 168h 
storage_config: 
  boltdb: 
    directory: /tmp/loki/index 
  filesystem: 
    directory: /tmp/loki/chunks 
limits_config: 
  enforce_metric_name: false 
  reject_old_samples: true 
  reject_old_samples_max_age: 168h 
  max_entries_limit_per_query: 500000 
# By default, Loki will send anonymous, but uniquely-identifiable usage and configuration 
# analytics to Grafana Labs. These statistics are sent to https://stats.grafana.org/ 
# 
# Statistics help us better understand how Loki is used, and they show us performance 
# levels for most users. This helps us prioritize features and documentation. 
# For more information on what's sent, look at 
# https://github.com/grafana/loki/blob/main/pkg/usagestats/stats.go 
# Refer to the buildReport method to see what goes into a report. 
# 
# If you would like to disable reporting, uncomment the following lines: 
#analytics: 
#  reporting_enabled: false
###################################################################################


vi /etc/promtail/promtail-local-config.yaml

server:  
  http_listen_port: 9080  
  grpc_listen_port: 0  
positions:  
  filename: /tmp/positions.yaml  
clients:  
  - url: http://localhost:3100/loki/api/v1/push 
scrape_configs: 
  - job_name: syslog 
    syslog: 
      listen_address: 0.0.0.0:1514 
      labels: 
        job: syslog 
    relabel_configs: 
      - source_labels: [__syslog_message_hostname] 
        target_label: host 
      - source_labels: [__syslog_message_hostname] 
        target_label: hostname 
      - source_labels: [__syslog_message_severity] 
        target_label: level 
      - source_labels: [__syslog_message_app_name] 
        target_label: application 
      - source_labels: [__syslog_message_facility] 
        target_label: facility 
      - source_labels: [__syslog_connection_hostname] 
        target_label: connection_hostname
		

###########################################

sudo vi /etc/systemd/system/loki.service

[Unit]
Description=Loki service
After=network.target

[Service]
Type=simple
User=root
ExecStart=/usr/local/bin/loki -config.file /etc/loki/loki-local-config.yaml

[Install]
WantedBy=multi-user.target

## 使用一次建立 檔案
sudo tee /etc/systemd/system/loki.service<<EOF
[Unit]
Description=Loki service
After=network.target

[Service]
Type=simple
User=root

ExecStart=/usr/local/bin/loki -config.file /etc/loki/loki-local-config.yaml

[Install]
WantedBy=multi-user.target
EOF

### 結束



sudo systemctl daemon-reload

sudo systemctl enable loki.service

sudo systemctl enable promtail.service

systemctl start loki.service
systemctl start promtail.service



```


# Step 09 – 設定 rsyslog 轉發到 Promtail


```

apt update && apt install -y rsyslog

sudo systemctl status rsyslog

vi /etc/rsyslog.config 

或是 

#################
#### MODULES ####
#################

module(load="imuxsock") # provides support for local system logging
#module(load="immark")  # provides --MARK-- message capability

# provides UDP syslog reception
module(load="imudp")
input(type="imudp" port="514")

# provides TCP syslog reception
module(load="imtcp")
input(type="imtcp" port="514")

# provides kernel logging support and enable non-kernel klog messages
module(load="imklog" permitnonkernelfacility="on")

# Forward everything
*.*  action(type="omfwd"
       protocol="tcp" target="127.0.0.1" port="1514"
       Template="RSYSLOG_SyslogProtocol23Format"
       TCP_Framing="octet-counted" KeepAlive="on"
       action.resumeRetryCount="-1"
       queue.type="linkedlist" queue.size="50000")
	   
	   
	   
vi /etc/rsyslog.d/00-promtail-relay.conf

#########################
# https://www.rsyslog.com/doc/v8-stable/concepts/multi_ruleset.html#split-local-and-remote-logging
ruleset(name="remote"){
  # https://www.rsyslog.com/doc/v8-stable/configuration/modules/omfwd.html
  # https://grafana.com/docs/loki/latest/clients/promtail/scraping/#rsyslog-output-configuration
  action(type="omfwd" Target="localhost" Port="1514" Protocol="tcp" Template="RSYSLOG_SyslogProtocol23Format" TCP_Framing="octet-counted")
}


# https://www.rsyslog.com/doc/v8-stable/configuration/modules/imudp.html
module(load="imudp")
input(type="imudp" port="514" ruleset="remote")

# https://www.rsyslog.com/doc/v8-stable/configuration/modules/imtcp.html
module(load="imtcp")
input(type="imtcp" port="514" ruleset="remote")

###################################################################################################

 systemctl restart rsyslog.service

```

#### 紀錄 bash log

```
export PROMPT_COMMAND='RETRN_VAL=$?; if [ -f /tmp/lastoutput.tmp ]; then LAST_OUTPUT=$(cat /tmp/lastoutput.tmp); rm /tmp/lastoutput.tmp; fi; logger -S 10000 -p local6.debug "{\"user\": \"$(whoami)\", \"path\": \"$(pwd)\", \"pid\": \"$$\", \"b64_command\": \"$(history 1 | sed "s/^[ ]*[0–9]\+[ ]*//" | base64 -w0 )\", \"status\": \"$RETRN_VAL\", \"b64_output\": \"$LAST_OUTPUT\"}"; unset LAST_OUTPUT; '
logoutput() { output=$(while read input; do echo "$input"; done < "${1:-/dev/stdin}"); echo -e "$output"; echo -e "$output" | head -c 10000 | base64 -w0 > /tmp/lastoutput.tmp; return $?; }
```

### Loki 语法

```

|=：日志行包含字符串
!=：日志行不包含字符串
|~：日志行匹配正则表达式
!~：日志行与正则表达式不匹配

1 # 精确匹配：|="2020-11-16 "
2 {app_kubernetes_io_instance="admin-service-test2-container-provider"}|="2020-11-16 "

1 # 模糊匹配：|~"2020-11-16 "
2 {app_kubernetes_io_instance="admin-service-test2-container-provider"}|~"2020-11-16 "

1 # 排除过滤：!=/!~ "数据中心"
2 {app_kubernetes_io_instance="admin-service-master-container-provider"}!="資料中心"
3 {app_kubernetes_io_instance="admin-service-master-container-provider"}!~"資料中心"

1 # 正则匹配： |~ "()"
2 {app_kubernetes_io_instance="admin-service-master-container-provider"}!~"(admin|web)"
3 {app_kubernetes_io_instance="admin-service-master-container-provider"}|~"ERROR|error"

```

## Step 10

```
## 確認最新檔案

## https://prometheus.io/download/

wget https://github.com/prometheus/alertmanager/releases/download/v0.25.0/alertmanager-0.25.0.linux-amd64.tar.gz

tar -xzvf alertmanager-0.25.0.linux-amd64.tar.gz

cd alertmanager-0.25.0.linux-amd64

mv amtool alertmanager /usr/local/bin

alertmanager -h

mkdir -p /etc/alertmanager

mkdir -p /dbdata/alertmanager

useradd -rs /bin/false alertmanager

mv alertmanager.yml /etc/alertmanager

chown -R alertmanager:alertmanager /dbdata/alertmanager /etc/alertmanager/*

sudo tee /etc/systemd/system/alertmanager.service<<EOF
[Unit]
Description=Alert Manager
Wants=network-online.target
After=network-online.target

[Service]
Type=simple
User=alertmanager
Group=alertmanager
ExecReload=/bin/kill -HUP $MAINPID
ExecStart=/usr/local/bin/alertmanager \
--config.file=/etc/alertmanager/alertmanager.yml \
--storage.path=/dbdata/alertmanager \

Restart=always

[Install]
WantedBy=multi-user.target
EOF

sudo systemctl daemon-reload

systemctl enable alertmanager

systemctl start alertmanager

systemctl status alertmanager

ss -aulntp | grep 9093


修改好設定檔後，可以使用amtool工具檢查設定

/usr/local/bin/amtool check-config /etc/alertmanager/alertmanager.yml


```
