# Debain 11.2 安裝 Grafana 

----

## Setep 安裝 LNMP ( linux + Nginx + mariadb + php )

### 安裝 nginx
```
安裝 nginx 

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

### 安裝 Mariadb 10.7

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
sudo apt install mariadb-server mariadb-client

## $ systemctl start mysql.service
$ sudo systemctl enable --now mysql.service

$ mysql_secure_installation

$ mariadb --version

##mysql  Ver 15.1 Distrib 10.5.12-MariaDB, for debian-linux-gnu (x86_64) using readline EditLine wrapper

## root@debian-s-1vcpu-1gb-sfo2-01:~#


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

### 安裝 PHP 8.1

```
############ 安裝 php 8.1 #######################################
sudo apt -y install lsb-release apt-transport-https ca-certificates
sudo wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg
echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | sudo tee /etc/apt/sources.list.d/php.list
apt update
apt upgrade

##########################################
sudo apt install php8.1-amqp php8.1-common php8.1-gd php8.1-ldap php8.1-odbc php8.1-readline php8.1-sqlite3 php8.1-xsl \
php8.1-curl php8.1-gmp php8.1-mailparse php8.1-opcache php8.1-redis php8.1-sybase php8.1-yac \
php8.1-ast php8.1-dba php8.1-igbinary php8.1-mbstring php8.1-pgsql php8.1-rrd php8.1-tidy php8.1-yaml \
php8.1-bcmath php8.1-dev php8.1-imagick php8.1-memcached php8.1-phpdbg php8.1-smbclient php8.1-uuid php8.1-zip \
php8.1-bz2 php8.1-ds php8.1-imap php8.1-msgpack php8.1-pspell php8.1-snmp php8.1-xdebug php8.1-zmq \
php8.1-cgi php8.1-enchant php8.1-interbase php8.1-mysql php8.1-psr php8.1-soap php8.1-xhprof \
php8.1-cli php8.1-fpm php8.1-intl php8.1-oauth php8.1-raphf php8.1-solr php8.1-xml php8.1-mcrypt -y
###################

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

# 8.1開始有支援不需要再跑這一段
# pecl install mcrypt-1.0.4

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
#########################################
#  mcrypt 結束 
#########################################
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
    fastcgi_pass unix:/run/php/php8.1-fpm.sock;
    include         fastcgi_params;
    fastcgi_param   SCRIPT_FILENAME    $document_root$fastcgi_script_name;
    fastcgi_param   SCRIPT_NAME        $fastcgi_script_name;
  }
## 如果使用 nginx 1.18 最新版需修改 www.conf
修改 /etc/php/8.1/fpm/pool.d/www.conf 設定檔，改變執行者及群組
# vi /etc/php/8.1/fpm/pool.d/www.conf
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

## 安裝 Grafana

### 安裝 Grafana 8.4.4

#### 確認使用哪一種 工具監控 
 
 * telegraf + influxdb
 * prometheus

### 安裝 telegraf

```
wget -qO- https://repos.influxdata.com/influxdb.key | sudo tee /etc/apt/trusted.gpg.d/influxdb.asc >/dev/null
source /etc/os-release
echo "deb https://repos.influxdata.com/${ID} ${VERSION_CODENAME} stable" | sudo tee /etc/apt/sources.list.d/influxdb.list
sudo apt-get update && sudo apt-get install telegraf

OR

wget https://dl.influxdata.com/telegraf/releases/telegraf_1.22.0-1_amd64.deb
sudo dpkg -i telegraf_1.22.0-1_amd64.deb

```

### 安裝 prometheus

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

cat /etc/prometheus/prometheus.yml

sudo mv consoles/ console_libraries/ /etc/prometheus/
cd ~/
rm -rf /tmp/prometheus

# Step 4: Create/Edit a Prometheus configuration file.


cat /etc/prometheus/prometheus.yml

# 我的 global config
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


## Step 5: Create a Prometheus systemd Service unit file

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

# 修改權限

for i in rules rules.d files_sd; do sudo chown -R prometheus:prometheus /etc/prometheus/${i}; done
for i in rules rules.d files_sd; do sudo chmod -R 775 /etc/prometheus/${i}; done
sudo chown -R prometheus:prometheus /var/lib/prometheus/

# 重新啟動

sudo systemctl daemon-reload
sudo systemctl start prometheus
sudo systemctl enable prometheus

# 檢查服務

$ systemctl status prometheus

可以測試 連線

http://[ip_hostname]:9090.

Step 6: Install node_exporter onDebian 11 / Debian 10

cd ~/

curl -s https://api.github.com/repos/prometheus/node_exporter/releases/latest| grep browser_download_url|grep linux-amd64|cut -d '"' -f 4|wget -qi -

tar -xvf node_exporter*.tar.gz
cd  node_exporter*/
sudo cp node_exporter /usr/local/bin

# 確認是否安裝成功

$ node_exporter --version

# 安裝啟動服務

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

# 重新啟動服務

sudo systemctl daemon-reload
sudo systemctl start node_exporter
sudo systemctl enable node_exporter

# 確認服務啟動狀態

$ systemctl status node_exporter.service

# 將設定檔新增

sudo vim /etc/prometheus/prometheus.yml

# 新增到 scrape_config  底下:

scrape_configs:
  # The job name is added as a label `job=<job_name>` to any timeseries scraped from this config.
  - job_name: "prometheus"
    static_configs:
      - targets: ["localhost:9090"]
  - job_name: 'node_exporter'
    static_configs:
      - targets: ['localhost:9100']

## 重啟 prometheus

sudo systemctl restart prometheus



```

### 設定 telegraf to prometheus

```

#####################################################
#
# Check on status of snmp 
#
#####################################################

#####################################################
#
# Export Information to Prometheus
#
#####################################################
[[outputs.prometheus_client]]
  listen = ":9161"
  metric_version = 2
  


```

### prometheus 範例

```
[root@bigdata3 prometheus]# cat  prometheus.yml
# my global config
global:
  scrape_interval:     15s # Set the scrape interval to every 15 seconds. Default is every 1 minute.
  evaluation_interval: 15s # Evaluate rules every 15 seconds. The default is every 1 minute.
  # scrape_timeout is set to the global default (10s).
# Alertmanager configuration
alerting:
  alertmanagers:
  - static_configs:
    - targets: ['192.168.1.5:9093']        # alertmanagers所在地址
      # - alertmanager:9093
# Load rules once and periodically evaluate them according to the global 'evaluation_interval'.
rule_files:
  # - "first_rules.yml"
  # - "second_rules.yml"
#################rules#############################
 - "/opt/monitor/prometheus/rules/hosts/*.yml" # 告警规则存放目录
#################rules#############################
# A scrape configuration containing exactly one endpoint to scrape:
# Here it's Prometheus itself.
scrape_configs:
  # The job name is added as a label `job=<job_name>` to any timeseries scraped from this config.
  - job_name: 'prometheus'
    # metrics_path defaults to '/metrics'
    # scheme defaults to 'http'.
    static_configs:
    - targets: ['192.168.1.5:9090']          #Prometheus安装机器地址
#################hosts#############################
  - job_name: 'A-getway'	   # 标签用于区分各个监控项目的机器
    file_sd_configs:
    - files: ['/opt/monitor/prometheus/monitor-config/A-getway/*.yml']   # 监控dmp集群的机器配置放置目录
      refresh_interval: 5s
#################hosts#############################

# 建立 告警規則目錄

[root@bigdata3 prometheus]# mkdir /opt/monitor/prometheus/rules
[root@bigdata3 prometheus]# mkdir /opt/monitor/prometheus/rules/hosts
[root@bigdata3 prometheus]# mkdir /opt/monitor/prometheus/rules/mysql
[root@bigdata3 prometheus]# mkdir /opt/monitor/prometheus/rules/hdfs
[root@bigdata3 prometheus]# ls  /opt/monitor/prometheus/rules/
hdfs  hosts  mysql

[root@bigdata3 rules]# cd /opt/monitor/prometheus/rules/hosts
[root@bigdata3 rules]# cat disk_use.yml 
groups:
- name: host_disk     
  rules:
  - alert: NodediskUsage
    expr: round(disk_used_percent{kind="jkj"}) > 50
    for: 1m
    labels:
      sort: host_disk
      level: severity
    annotations:
      summary: "{{$labels.instance}}: High disk usage"
      description: "disk {{$labels.path}} already use {{ $value }}%,please check it"

# 建立監控主機群

[root@bigdata3 prometheus]# mkdir /opt/monitor/prometheus/monitor_config/
[root@bigdata3 prometheus]# mkdir /opt/monitor/prometheus/monitor_config/dmp
[root@bigdata3 prometheus]# mkdir /opt/monitor/prometheus/monitor_config/xl
[root@bigdata3 prometheus]# ls /opt/monitor/prometheus/monitor_config/
dmp  xl

cd /opt/monitor/prometheus/monitor_config/dmp
[root@bigdata3 dmp]# cat 192.168.1.5.yml 
- targets: [ "192.168.1.5：9275" ]
  labels:
    group: "monitor-server"

```

### Grafana 安裝

```

sudo apt-get install -y adduser libfontconfig1
wget https://dl.grafana.com/oss/release/grafana_8.4.4_amd64.deb
sudo dpkg -i grafana_8.4.4_amd64.deb

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


```

### 設定 nginx

```

設定ssl

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


### 設定 F5 SNMP telegraf to prometheus

```
# 建議一台使用一個台設定檔 一台使用一個port
#####################################################
#
# Check on status of snmp 
#
#####################################################

[[inputs.snmp]]
  name_prefix = "execf5_"
  #agents = [ "192.168.88.60", "xxx.xxx.xxx.xx2", "xxx.xxx.xxx.xx3" ]
  agents = [ "192.168.88.166" ]
  version = 2
  community = "public"
  interval = "10s"
  timeout = "10s"
  retries = 3
  name = "F5_system"

  [[inputs.snmp.field]]
    name = "hostname"
    oid = "RFC1213-MIB::sysName.0"
    is_tag = true
  [[inputs.snmp.field]]
    name = "F5_uptime"
    oid = "1.3.6.1.4.1.3375.2.1.6.6.0"
  [[inputs.snmp.field]]
    name = "F5_httpRequests"
    oid = "1.3.6.1.4.1.3375.2.1.1.2.1.56.0"
  [[inputs.snmp.field]]
    name = "F5_client_connections"
    oid = "1.3.6.1.4.1.3375.2.1.1.2.1.8.0"
  [[inputs.snmp.field]]
    name = "F5_client_bytes_in"
    oid = "1.3.6.1.4.1.3375.2.1.1.2.1.60.0"
  [[inputs.snmp.field]]
    name = "F5_Total_Connections"
    oid = "1.3.6.1.4.1.3375.2.1.1.2.1.8.0"
  [[inputs.snmp.field]]
    name = "F5_New_Connects"
    oid = "1.3.6.1.4.1.3375.2.1.1.2.12.8.0"
  [[inputs.snmp.field]]
    name = "F5_New_Accepts"
    oid = "1.3.6.1.4.1.3375.2.1.1.2.12.6.0"
  [[inputs.snmp.field]]
    name = "F5_Temperature"
    oid = "1.3.6.1.4.1.3375.2.1.3.2.3.2.1.2.1"
  [[inputs.snmp.field]]
    name = "F5_Global_HTTP_Responses_2xx"
    oid = "F5-BIGIP-SYSTEM-MIB::sysHttpStatResp2xxCnt.0"
  [[inputs.snmp.field]]
    name = "F5_Global_HTTP_Responses_3xx"
    oid = "F5-BIGIP-SYSTEM-MIB::sysHttpStatResp3xxCnt.0"
  [[inputs.snmp.field]]
    name = "F5_Global_HTTP_Responses_4xx"
    oid = "F5-BIGIP-SYSTEM-MIB::sysHttpStatResp4xxCnt.0"
  [[inputs.snmp.field]]
    name = "F5_Global_HTTP_Responses_5xx"
    oid = "F5-BIGIP-SYSTEM-MIB::sysHttpStatResp5xxCnt.0"

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
	
 [[inputs.snmp.table]]
    name = "F5_TMM_Memory_Usage"
    oid = "F5-BIGIP-SYSTEM-MIB::sysTmmPagesStatTable"
    inherit_tags = [ "hostname" ]

 [[inputs.snmp.table]]
   name = "F5_PoolStatus"
   oid = "F5-BIGIP-LOCAL-MIB::ltmPoolStatTable"
   inherit_tags = [ "hostname" ]

 [[inputs.snmp.table]]
   name = "F5_ClientSSLStatus"
   oid = "F5-BIGIP-LOCAL-MIB::ltmClientSslStatTable"
   inherit_tags = [ "hostname" ]

 [[inputs.snmp.table]]
   name = "F5_Fan"
   oid = "F5-BIGIP-SYSTEM-MIB::sysChassisFanTable"
   inherit_tags = [ "hostname" ]

 [[inputs.snmp.table]]
   name = "F5_Temperature"
   oid = "F5-BIGIP-SYSTEM-MIB::sysChassisTempTable"
   inherit_tags = [ "hostname" ]

 [[inputs.snmp.table]]
   name = "F5_VirtualStatus"
   oid = "F5-BIGIP-LOCAL-MIB::ltmVirtualServStatTable"
   inherit_tags = [ "hostname" ]

 [[inputs.snmp.table]]
    name = "F5_Nodes_Status"
    oid =  "F5-BIGIP-LOCAL-MIB::ltmNodeAddrStatTable"
    inherit_tags = [ "hostname" ]

 [[inputs.snmp.table]]
    name = "F5_Vlan_Status"
    oid =  "F5-BIGIP-SYSTEM-MIB::sysVlanStatTable"
    inherit_tags = [ "hostname" ]

 [[inputs.snmp.table]]
    name = "F5_DiskTable_Status"
    oid =  "F5-BIGIP-SYSTEM-MIB:sysHostDiskTable"
    inherit_tags = [ "hostname" ]

 [[inputs.snmp.table]]
    name = "F5_PoolMembers_Status"
    oid =  "F5-BIGIP-LOCAL-MIB::ltmPoolMemberStatTable"
    inherit_tags = [ "hostname" ]

 [[inputs.snmp.table]]
    name = "F5_PoolUpDowm_Status"
    oid =  "F5-BIGIP-LOCAL-MIB::ltmPoolMemberTable"
    inherit_tags = [ "hostname" ]
###############################################################################
# SSLVPN APM module #
###############################################################################

[[inputs.snmp.table]]
name = "F5_APM_IP_List"
oid = "F5-BIGIP-APM-MIB::apmLeasepoolStatTable"
inherit_tags = [ "hostname" ]

[[inputs.snmp.table]]
name = "F5_APM_Pauser_List"
oid = "F5-BIGIP-APM-MIB::apmPaStatTable"
inherit_tags = [ "hostname" ]

[[inputs.snmp.table]]
name = "F5_APM_ACL_List"
oid = "F5-BIGIP-APM-MIB::apmAclStatTable"
inherit_tags = [ "hostname" ]

###############################################################################
# SSLVPN #
###############################################################################
  #####################################################
  #
  # Gather Interface Statistics via SNMP Start
  #
  #####################################################

  # IF-MIB::ifTable contains counters on input and output traffic as well as errors and discards.
  [[inputs.snmp.table]]
    name = "F5_interface"
    inherit_tags = [ "hostname" ]
    oid = "IF-MIB::ifTable"

    # Interface tag - used to identify interface in metrics database
    [[inputs.snmp.table.field]]
      name = "ifDescr"
      oid = "IF-MIB::ifDescr"
      is_tag = true

  # IF-MIB::ifXTable contains newer High Capacity (HC) counters that do not overflow as fast for a few of the ifTable counters
  [[inputs.snmp.table]]
    name = "F5_interface"
    inherit_tags = [ "hostname" ]
    oid = "IF-MIB::ifXTable"

    # Interface tag - used to identify interface in metrics database
    [[inputs.snmp.table.field]]
      name = "ifDescr"
      oid = "IF-MIB::ifDescr"
      is_tag = true

  # EtherLike-MIB::dot3StatsTable contains detailed ethernet-level information about what kind of errors have been logged on an interface (such as FCS error, frame too long, etc)
  [[inputs.snmp.table]]
    name = "F5_interface"
    inherit_tags = [ "hostname" ]
    oid = "EtherLike-MIB::dot3StatsTable"

    # Interface tag - used to identify interface in metrics database
    [[inputs.snmp.table.field]]
      name = "ifDescr"
      oid = "IF-MIB::ifDescr"
      is_tag = true


#####################################################
#
# Export Information to Prometheus
#
#####################################################
[[outputs.prometheus_client]]
  listen = ":9301"
  metric_version = 2
  
##################

[root@bigdata3 prometheus]# mkdir /opt/monitor/prometheus/monitor_config/
[root@bigdata3 prometheus]# mkdir /opt/monitor/prometheus/monitor_config/dmp
[root@bigdata3 prometheus]# mkdir /opt/monitor/prometheus/monitor_config/xl
[root@bigdata3 prometheus]# mkdir /opt/monitor/prometheus/monitor_config/IDC-01-F5-LTM-Group
[root@bigdata3 prometheus]# mkdir /opt/monitor/prometheus/monitor_config/f5_apm
[root@bigdata3 prometheus]# mkdir /opt/monitor/prometheus/monitor_config/f5_gtm
[root@bigdata3 prometheus]# ls /opt/monitor/prometheus/monitor_config/
dmp  xl

cd /opt/monitor/prometheus/monitor_config/IDC-01-F5-LTM-Group
[root@bigdata3 dmp]# cat ltm01_192.168.1.5_9161.yml 
- targets: [ "127.0.0.1：9301" ]
  labels:   # 標籤
    group: "F5-LTM"



#################
# 調整 prometheus 設定檔
# 因為使用 * 所以自動會更新
# 
[root@bigdata3 prometheus]# cat  prometheus.yml
# my global config
global:
  scrape_interval:     15s # Set the scrape interval to every 15 seconds. Default is every 1 minute.
  evaluation_interval: 15s # Evaluate rules every 15 seconds. The default is every 1 minute.
  # scrape_timeout is set to the global default (10s).
# Alertmanager configuration
alerting:
  alertmanagers:
  - static_configs:
    - targets: ['192.168.1.5:9093']        # alertmanagers所在地址
      # - alertmanager:9093
# Load rules once and periodically evaluate them according to the global 'evaluation_interval'.
rule_files:
  # - "first_rules.yml"
  # - "second_rules.yml"
#################rules#############################
 - "/opt/monitor/prometheus/rules/hosts/*.yml" # 告警规则存放目录
#################rules#############################
# A scrape configuration containing exactly one endpoint to scrape:
# Here it's Prometheus itself.
scrape_configs:
  # The job name is added as a label `job=<job_name>` to any timeseries scraped from this config.
  - job_name: 'prometheus'
    # metrics_path defaults to '/metrics'
    # scheme defaults to 'http'.
    static_configs:
    - targets: ['192.168.1.5:9090']          #Prometheus安装机器地址
#################   hosts  #############################
  - job_name: 'A-getway'	   # 标签用于区分各个监控项目的机器
    file_sd_configs:
    - files: ['/opt/monitor/prometheus/monitor-config/A-getway/*.yml']   # 监控dmp集群的机器配置放置目录
      refresh_interval: 5s
	  
  - job_name: 'B-getway'
    file_sd_configs:
    - files: ['/opt/monitor/prometheus/monitor-config/B-getway/*.yml']
      refresh_interval:    5s
#################   hosts   #############################
#################   F5-LTM  #############################
  - job_name: 'IDC-01-F5-LTM-Group'	   # 标签用于区分各个监控项目的机器 標籤用於區分各個監控項目的機器
    file_sd_configs:
    - files: ['/opt/monitor/prometheus/monitor-config/IDC-01-F5-LTM-Group/*.yml']   # 监控F5-LTM集群的机器配置放置目录
      refresh_interval: 5s
#################hosts#############################


################################
# 其他範例
scrape_configs:
  - job_name: prometheus
    scrape_interval: 5s
    static_configs:
      - targets: ['127.0.0.1:9090']
      - targets: ['127.0.0.1:9100'] # 這裡開始增加的監控資訊
        labels:
          group: 'local-node-exporter'
##################################
```

### Prometheus監控（Rules篇） Alertmanager整合

```
alerting:
  alert_relabel_configs:
    [ - <relabel_config> ... ]
  alertmanagers:
    [ - <alertmanager_config> ... ]
# alertmanagers 為 alertmanager_config 陣列，

# 設定範例

alerting:
  alert_relabel_configs: # 動態修改 alert 屬性的規則配置。
    - source_labels: [dc] 
      regex: (.+)\d+
      target_label: dc1
  alertmanagers:
    - static_configs:
        - targets: ['127.0.0.1:9093'] # 單例項配置
        #- targets: ['172.31.10.167:19093','172.31.10.167:29093','172.31.10.167:39093'] # 叢集配置
  - job_name: 'Alertmanager'
    # metrics_path defaults to '/metrics'
    # scheme defaults to 'http'.
    static_configs:
    - targets: ['localhost:19093']

###################################
上面的配置中的 alert_relabel_configs是指警報重新標記在傳送到Alertmanager之前應用於警報。它具有與目標重新標記相同的配置格式和操作，外部標籤標記後應用警報重新標記，主要是針對叢集配置。

這個設定的用途是確保具有不同外部label的HA對Prometheus服務端傳送相同的警報資訊。

Alertmanager 可以通過 static_configs 引數靜態配置，也可以使用其中一種支援的服務發現機制動態發現，我們上面的配置是靜態的單例項，針對叢集HA配置，後面會講。

此外，relabel_configs 允許從發現的實體中選擇 Alertmanager，並對使用的API路徑提供高階修改，該路徑通過 __alerts_path__ 標籤公開。

完成以上配置後，重啟Prometheus服務，用以載入生效，也可以使用前文說過的熱載入功能，使其配置生效。然後通過瀏覽器，訪問 http://192.168.1.220:19090/alerts 就可以看 inactive pending firing 三個狀態，沒有警報資訊是因為我們還沒有配置警報規則 rules。

https://www.gushiciku.cn/pl/p38S/zh-tw


ALERT memory_high
  IF prometheus_local_storage_memory_series >= 0
  FOR 15s
  ANNOTATIONS {
    summary = "Prometheus using more memory than it should {{ $labels.instance }}",
    description = "{{ $labels.instance }} has lots of memory man (current value: {{ $value }}s)",
  }

####
  alert.rules: |-
    ## alert.rules ##
    #
    # CPU Alerts
    #
    ALERT HighCPU
      IF (100 - (avg(irate(node_cpu{job="kubernetes-service-endpoints",mode="idle"}[1m])) BY (instance) * 100)) > 80
      FOR 10m
      ANNOTATIONS {
        summary = "High CPU Usage",
        description = "This machine  has really high CPU usage for over 10m",
      }
    #
    # DNS Lookup failures
    #
    ALERT DNSLookupFailureFromPrometheus
      IF prometheus_dns_sd_lookup_failures_total > 5
      FOR 1m
      LABELS { service = "frontend" }
      ANNOTATIONS {
        summary = "Prometheus reported over 5 DNS lookup failure",
        description = "The prometheus unit reported that it failed to query the DNS.  Look at the kube-dns to see if it is having any problems",
      }
	  
####
groups:
- name: example
  rules:

  # Alert for any instance that is unreachable for >5 minutes.
  - alert: InstanceDown
    expr: up == 0
    for: 5m
    labels:
      severity: page
    annotations:
      summary: "Instance {{ $labels.instance }} down"
      description: "{{ $labels.instance }} of job {{ $labels.job }} has been down for more than 5 minutes."

  # Alert for any instance that has a median request latency >1s.
  - alert: APIHighRequestLatency
    expr: api_http_request_latencies_second{quantile="0.5"} > 1
    for: 10m
    annotations:
      summary: "High request latency on {{ $labels.instance }}"
      description: "{{ $labels.instance }} has a median request latency above 1s (current value: {{ $value }}s)"
	  
	  
	  
groups:
- name: sample_alert
  rules:
  - alert: AlertTest
    expr: node_filesystem_avail_bytes{mountpoint="/"} < 8589934592
    for: 3m
    labels:
      severity: test
      test_type: SampleAlert
    annotations:
      summary: sample_alert

###################
config:
  global:
    slack_api_url: SLACK_HOOK_URL  // 置換成自己設定好的 slack hook url
  route:
    group_by: ['alertname', 'datacenter', 'app', 'job']
    receiver: 'slack-notifications'
  receivers:
    - name: 'slack-notifications'
      slack_configs:
      - channel: '#k8s-alertmanager'
         text: '{{ template "slack.k8sbridge.txt" . }}'

最後那行是說 text 要套用指定的 template，所以同樣在 values.yaml 設定 template 的部分
templates:
  - '*.tmpl'
這兩行是告訴 alertmanager 要 include 預設路徑下的所有 .tmpl 檔案
然後把後面 templatesFiles 那一大段都 uncomment，只修改 define 的 tempalte name，要對應前面設定的 text 裡的 template name，例如:
  templateFiles:
    template_1.tmpl: |-
        {{ define "cluster" }}{{ .ExternalURL | reReplaceAll ".*alertmanager\\.(.*)" "$1" }}{{ end }}
        {{ define "slack.k8sbridge.tet" }}

```
