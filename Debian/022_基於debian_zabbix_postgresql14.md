# 基於 Debian + Nginx + php + postgresql14 + timescaledb 安装zabbix6.0

# 安装postgresql 資料庫

```
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
sudo vim /etc/postgresql/14/main/postgresql.conf

# CONNECTIONS AND AUTHENTICATION
........
listen_addresses='*'

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


```
