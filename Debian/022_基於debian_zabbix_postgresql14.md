# 基於 Debian + Nginx + php + postgresql14+timescaledb安装zabbix6.0

# 安装postgresql数据库

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
sudo vim /etc/postgresql/14/main/postgresql.conf]

# CONNECTIONS AND AUTHENTICATION
........
listen_addresses='*'

## 重新啟動
sudo systemctl restart postgresql
sudo systemctl enable postgresql

管理 psql

sudo -u postgres psql

CREATE ROLE admin WITH LOGIN SUPERUSER CREATEDB CREATEROLE PASSWORD 'Passw0rd';

Manage application users

create database test_db;
create user test_user with encrypted password 'dbpassword';
grant all privileges on database test_db to test_user;
\q

# 確認 postgresql-14 服務是否啟動
ss -tunelp | grep 5432
 
```

### 安裝 timescaledb

```
apt install gnupg postgresql-common apt-transport-https lsb-release wget

/usr/share/postgresql-common/pgdg/apt.postgresql.org.sh

echo "deb https://packagecloud.io/timescale/timescaledb/debian/ $(lsb_release -c -s) main" > /etc/apt/sources.list.d/timescaledb.list

wget --quiet -O - https://packagecloud.io/timescale/timescaledb/gpgkey | apt-key add -

apt update

apt install timescaledb-2-postgresql-14

timescaledb-tune --pg-config=/usr/pgsql-14/bin/pg_config

systemctl restart postgresql-14

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

# systemctl restart zabbix-server zabbix-agent apache2
# systemctl enable zabbix-server zabbix-agent apache2


```
