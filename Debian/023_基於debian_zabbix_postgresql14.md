# 基於 Debian + Nginx + php + postgresql14 + timescaledb 安装zabbix6.0

# 安装postgresql 資料庫

```
# Debian11_3_Zabbix60_pqls_timescaledb_01

# Debian11-Zabbix-Server01

022_基於debian_zabbix_postgresql14.md

sudo apt update && sudo apt upgrade

sudo apt -y install gnupg2 wget vim

Step 1 – Install PostgreSQL 14 on Debian 11 | Debian 10

sudo apt-cache search postgresql | grep postgresql

sudo sh -c 'echo "deb http://apt.postgresql.org/pub/repos/apt $(lsb_release -cs)-pgdg main" > /etc/apt/sources.list.d/pgdg.list'

wget --quiet -O - https://www.postgresql.org/media/keys/ACCC4CF8.asc | sudo apt-key add -

sudo apt -y update

sudo apt install postgresql-14

確認

sudo -u postgres psql -c "SELECT version();"

systemctl status postgresql

# 设置postgres passwd

# 切换postgres用户

# 修改postgres用户Passwd

# postgres=# ALTER USER postgres WITH PASSWORD 'PASSWORD';

# 开放登录端口


登入 postgresql-14 兩種方式
第一種
sudo -i -u postgres
psql

第二種
sudo -u postgres psql

修改允許遠端存取

sudo sed -i '/^local/s/peer/trust/' /etc/postgresql/14/main/pg_hba.conf

sudo sed -i '/^host/s/ident/md5/' /etc/postgresql/14/main/pg_hba.conf

確認檔案

sudo vi /etc/postgresql/14/main/pg_hba.conf
# 新增 md5 兩行
# Database administrative login by Unix domain socket
local   all             postgres                                trust
# TYPE  DATABASE        USER            ADDRESS                 METHOD

# "local" is for Unix domain socket connections only
local   all             all                                     trust
# IPv4 local connections:
host    all             all             127.0.0.1/32            scram-sha-256
host    all             all             0.0.0.0/24              md5
# IPv6 local connections:
host    all             all             ::1/128                 scram-sha-256
host    all             all             0.0.0.0/0               md5

# 編輯允許存取 位址
# 找到 listen_addresses
sudo vi /etc/postgresql/14/main/postgresql.conf

# CONNECTIONS AND AUTHENTICATION
........
listen_addresses='*'
max_connections = 1000

## 重新啟動
sudo systemctl restart postgresql
sudo systemctl enable postgresql

管理 psql

sudo -u postgres psql

CREATE ROLE admin WITH LOGIN SUPERUSER CREATEDB CREATEROLE PASSWORD 'f99XVu73Spfcgxw';
ALTER USER postgres WITH PASSWORD 'f99XVu73Spfcgxw';
Manage application users

create database test_db;
create user test_user with encrypted password 'f99XVu73Spfcgxw';
grant all privileges on database test_db to test_user;
\q

# 確認 postgresql-14 服務是否啟動
ss -tunelp | grep 5432
 
 
```

### 安裝 postgresql timescaledb

```
apt install gnupg postgresql-common apt-transport-https lsb-release wget

/usr/share/postgresql-common/pgdg/apt.postgresql.org.sh

echo "deb https://packagecloud.io/timescale/timescaledb/debian/ $(lsb_release -c -s) main" > /etc/apt/sources.list.d/timescaledb.list

wget --quiet -O - https://packagecloud.io/timescale/timescaledb/gpgkey | apt-key add -

apt update

apt install timescaledb-2-postgresql-14

# timescaledb-tune --pg-config=/usr/pgsql-14/bin/pg_config
#
# timescaledb-tune --pg-config=/usr/lib/postgresql/14/bin/pg_config
# debian 11 指令
sudo timescaledb-tune
Or
sudo timescaledb-tune --quiet --yes

sudo systemctl restart postgresql

測試登入

psql -U postgres -h localhost

postgres=# CREATE EXTENSION timescaledb;

postgres=#\dx


```

### 更新 php 8.1 

```
############ 安裝 php 8.1 #######################################
sudo apt -y install lsb-release apt-transport-https ca-certificates
sudo wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg
echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | sudo tee /etc/apt/sources.list.d/php.list
apt update
apt upgrade

```

### Step 03 – 安裝 zabbix

```
# wget https://repo.zabbix.com/zabbix/6.0/debian/pool/main/z/zabbix-release/zabbix-release_6.0-3+debian11_all.deb
# dpkg -i zabbix-release_6.0-3+debian11_all.deb
# apt update

不安裝 PHP 8.1
apt install zabbix-server-pgsql zabbix-frontend-php php7.4-pgsql zabbix-apache-conf zabbix-sql-scripts zabbix-agent

安裝 PHP 8.1
apt install zabbix-server-pgsql zabbix-frontend-php php-pgsql zabbix-apache-conf zabbix-sql-scripts zabbix-agent
 

# sudo -u postgres createuser --pwprompt zabbix
# sudo -u postgres createdb -O zabbix zabbix


# zcat /usr/share/doc/zabbix-sql-scripts/postgresql/server.sql.gz | sudo -u zabbix psql zabbix

編輯 Edit file /etc/zabbix/zabbix_server.conf

DBPassword=password
# 重要
# 開啟 timesscaledb 插件
echo "CREATE EXTENSION IF NOT EXISTS timescaledb CASCADE;" | sudo -u postgres psql zabbix
cat /usr/share/doc/zabbix-sql-scripts/postgresql/timescaledb.sql | sudo -u zabbix psql zabbix
#
# systemctl restart zabbix-server zabbix-agent apache2
# systemctl enable zabbix-server zabbix-agent apache2

cp /etc/zabbix/zabbix_proxy.conf /etc/zabbix/zabbix_proxy.conf_bak_`date +"%Y%m%d%H%M%S"`
# 調整server優化值
sed -i 's/# CacheSize=8M/CacheSize=4G/g' /etc/zabbix/zabbix_proxy.conf
sed -i 's/Timeout=4/Timeout=30/g' /etc/zabbix/zabbix_proxy.conf
#
sed -i 's/# StartPollers=5/StartPollers=500/g' /etc/zabbix/zabbix_server.conf
sed -i 's/# StartTrappers=5/StartTrappers=200/g' /etc/zabbix/zabbix_server.conf
sed -i 's/# StartDBSyncers=4/StartDBSyncers=100/g' /etc/zabbix/zabbix_server.conf
sed -i 's/# StartPingers=1/StartPingers=500/g' /etc/zabbix/zabbix_server.conf
sed -i 's/# StartPollersUnreachable=1/StartPollersUnreachable=500/g' /etc/zabbix/zabbix_server.conf
sed -i 's/# StartDiscoverers=1/StartDiscoverers=120/g' /etc/zabbix/zabbix_server.conf
sed -i 's/# HistoryCacheSize=16M/HistoryCacheSize=1G/g' /etc/zabbix/zabbix_server.conf
#
# 第二部分
#
sed -i 's/# VMwareCacheSize=8M/VMwareCacheSize=1G/g' /etc/zabbix/zabbix_server.conf
sed -i 's/# HistoryCacheSize=16M/HistoryCacheSize=2G/g' /etc/zabbix/zabbix_server.conf
sed -i 's/# HistoryIndexCacheSize=4M/HistoryIndexCacheSize=2G/g' /etc/zabbix/zabbix_server.conf
sed -i 's/# TrendCacheSize=4M/TrendCacheSize=1G/g' /etc/zabbix/zabbix_server.conf
sed -i 's/# ValueCacheSize=8M/ValueCacheSize=2G/g' /etc/zabbix/zabbix_server.conf

 grep CacheSize= /etc/zabbix/zabbix_server.conf
 
# VMwareCacheSize=8M
# CacheSize=8M
# HistoryCacheSize=16M
# HistoryIndexCacheSize=4M
# TrendCacheSize=4M
# ValueCacheSize=8M

sed -i 's/# VMwareCacheSize=8M/VMwareCacheSize=1G/g' /etc/zabbix/zabbix_server.conf
sed -i 's/# CacheSize=8M/CacheSize=4G/g' /etc/zabbix/zabbix_server.conf
sed -i 's/# HistoryCacheSize=16M/HistoryCacheSize=2G/g' /etc/zabbix/zabbix_server.conf
sed -i 's/# HistoryIndexCacheSize=4M/HistoryIndexCacheSize=2G/g' /etc/zabbix/zabbix_server.conf
sed -i 's/# TrendCacheSize=4M/TrendCacheSize=1G/g' /etc/zabbix/zabbix_server.conf
sed -i 's/# ValueCacheSize=8M/ValueCacheSize=2G/g' /etc/zabbix/zabbix_server.conf

```

### 中文化

```
修改中文語系

中文化
事前準備 
設定語系
# locale -a
# dpkg-reconfigure locales (安裝語系)
# apt install xfonts-intl-chinese

修改中文語系  # 要等 zabbix 安裝完成後才能使用下列指令

sed -i '/zh_TW/s/false/true/' /usr/share/zabbix/include/locales.inc.php

上傳 中文自行檔 # 重要

修改簡體中文語系　亂碼
[root@zabbix ~]# sed -i  's/graphfont/simkai/g'  /usr/share/zabbix/include/defines.inc.php
換成繁體中文字形
[root@zabbix ~]# sed -i 's/graphfont/kaiu/g' /usr/share/zabbix/include/defines.inc.php

將 kaiu.ttf 上傳到 /usr/share/zabbix/assets/fonts

cp ~/kaiu.ttf /usr/share/zabbix/assets/fonts

cd /usr/share/zabbix/assets/fonts
#
mv graphfont.ttf graphfont.ttf.back
#
ln -s kaiu.ttf graphfont.ttf


# systemctl restart zabbix-server zabbix-agent apache2




```


### 安裝 zabbix proxy (目前使用 mariadb )

```
Debian11_3_Zabbix60_pqls_timescaledb_01
Debian11_3_Zabbix60_pqls_timescaledb_01

Debian11_3_Zabbix60_pqls_uProxy_01

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


# wget https://repo.zabbix.com/zabbix/6.0/debian/pool/main/z/zabbix-release/zabbix-release_6.0-3+debian11_all.deb
# dpkg -i zabbix-release_6.0-3+debian11_all.deb
# apt update


apt install zabbix-proxy-mysql zabbix-sql-scripts zabbix-agent

mysql -uroot -p
#输入密码
create database zabbix_proxydb character set utf8 collate utf8_bin;
create user zabbixproxyuser@localhost identified by 'f99XVu73Spfcgxw';
grant all privileges on zabbix_proxydb.* to zabbixproxyuser@localhost;
quit; 

vi /etc/zabbix/zabbix_proxy.conf

Server = xxx.xxx.xxx.xxx 
Hostname =DC01-Debian11-Zabbix-Proxy01
LogFile =/tmp/zabbix_proxy.log
DBHost=localhost
DBName=zabbix_proxydb
DBUser=zabbixproxyuser
DBPassword=f99XVu73Spfcgxw


# systemctl restart zabbix-proxy zabbix-agent
# systemctl enable zabbix-proxt zabbix-agent

sed -i "s/Server=127.0.0.1/Server=192.168.88.128/" /etc/zabbix/zabbix_agentd.conf
sed -i "s/Hostname=Zabbix server/Hostname=DC01-Debian11-Zabbix-Proxy01/" /etc/zabbix/zabbix_agentd.conf
sed -i "s/^ServerActive=127.0.0.1/ServerActive=192.168.88.128/" /etc/zabbix/zabbix_agentd.conf
sed -i "s/# HostMetadata=/HostMetadata=${data_d}/" /etc/zabbix/zabbix_agent2.conf

```
### 定期重啟服務

```
00 4 * * * /usr/bin/systemctl restart zabbix-server.service


vi check-httpd.sh

#!/bin/bash
 
 
# Check httpd header
checkhttpd=`/usr/bin/curl -s --head --request GET http://localhost | /usr/bin/grep HTTP | /usr/bin/wc -l`
 
if [ $checkhttpd != 1 ]
then
        # restart httpd
        /usr/bin/systemctl restart httpd
        sleep 10
 
        # check again
        recheckhttpd=`/usr/bin/curl -s --head --request GET http://localhost | /usr/bin/grep HTTP | /usr/bin/wc -l`
        if [ $recheckhttpd == 1 ]
        then
                echo "Apache start successfully!" | /bin/mail -s "Apache restarted" you@email.com
                exit
        else
                echo "Apache restart fail!" | /bin/mail -s "Apache restart fail" you@email.com
                exit
        fi
        exit
fi

# chmod +x check-httpd.sh

# crontab -e

# */5 * * * * root /path/to/check-named.sh >/dev/null 2>&1
```
