# 安裝_FreeRADIUS_and_Daloradius_on_Debian_11_(Bullseye)

----

##### 003_安裝_FreeRADIUS_and_Daloradius_on_Debian_11_(Bullseye).md

## Step 1: Update your Server

```language

sudo apt -y update

```

## Step 2: Install and Configure Database Server

```language
Update system apt index

sudo apt -y update
sudo apt -y install software-properties-common gnupg2 dirmngr
sudo apt -y upgrade
sudo reboot


Import MariaDB gpg key and add repository.
#####$ apt install software-properties-common dirmngr -y
$ apt-key adv --fetch-keys 'https://mariadb.org/mariadb_release_signing_key.asc'
$ add-apt-repository 'deb [arch=amd64,arm64,ppc64el] https://mirror.rackspace.com/mariadb/repo/10.5/debian bullseye main'


apt update

安裝

apt install mariadb-server mysqltuner -y

$ systemctl start mysql.service
$ mysql_secure_installation


$ mysql -u root -p
CREATE DATABASE radius;
GRANT ALL ON radius.* TO radius@localhost IDENTIFIED BY "StrongradIusPass";
FLUSH PRIVILEGES;
\q

mysql -u radius -p

SHOW DATABASES;

QUIT

```

## Step 3: Install Apache Web Server and PHP

```language

sudo apt -y install apache2
sudo apt -y install php libapache2-mod-php php-{gd,common,mail,mail-mime,mysql,pear,mbstring,xml,curl}

systemctl status apache2



```

Step 4: Installing FreeRADIUS on Debian 11 Linux

```language

sudo apt -y install freeradius freeradius-mysql freeradius-utils

sudo systemctl enable --now freeradius.service 

systemctl status freeradius

```

Step 5: Configure FreeRADIUS on Debian 11

```language

sudo su -
mysql -u root -p radius < /etc/freeradius/*/mods-config/sql/main/mysql/schema.sql

sudo ln -s /etc/freeradius/*/mods-available/sql /etc/freeradius/*/mods-enabled/

sudo vi /etc/freeradius/*/mods-enabled/sql
```

```language
sql {
driver = "rlm_sql_mysql"
dialect = "mysql"

# Connection info:

server = "localhost"
port = 3306
login = "radius"
password = "StrongradIusPass"

# Database table configuration for everything except Oracle

radius_db = "radius"
}

# Set to ‘yes’ to read radius clients from the database (‘nas’ table)
# Clients will ONLY be read on server startup.
read_clients = yes

# Table to keep radius client info
client_table = "nas"

```

```language

sudo chgrp -h freerad /etc/freeradius/*/mods-available/sql
sudo chown -R freerad:freerad /etc/freeradius/*/mods-enabled/sql

sudo systemctl restart freeradius

```

Step 6: Install and Configure Daloradius

```language

sudo apt -y install wget unzip
wget https://github.com/lirantal/daloradius/archive/master.zip
unzip master.zip
mv daloradius-master/ daloradius

cd daloradius


mysql -u root -p radius < contrib/db/fr2-mysql-daloradius-and-freeradius.sql 
mysql -u root -p radius < contrib/db/mysql-daloradius.sql

cd ..
sudo mv daloradius /var/www/html/


sudo mv /var/www/html/daloradius/library/daloradius.conf.php.sample /var/www/html/daloradius/library/daloradius.conf.php
sudo chown -R www-data:www-data /var/www/html/daloradius/
sudo chmod 664 /var/www/html/daloradius/library/daloradius.conf.php

sudo vi /var/www/html/daloradius/library/daloradius.conf.php

$configValues['CONFIG_DB_HOST'] = 'localhost';
$configValues['CONFIG_DB_PORT'] = '3306';
$configValues['CONFIG_DB_USER'] = 'radius';
$configValues['CONFIG_DB_PASS'] = 'StrongradIusPass';
$configValues['CONFIG_DB_NAME'] = 'radius';


sudo systemctl restart freeradius.service apache2

```

Step 7: Access daloRADIUS Web Interface

```language

sudo pear install DB
sudo pear install MDB2

#####################
 #  sudo pear install DB

WARNING: "pear/DB" is deprecated in favor of "pear/MDB2"
WARNING: channel "pear.php.net" has updated its protocols, use "pear channel-update pear.php.net" to update
downloading DB-1.11.0.tgz ...
Starting to download DB-1.11.0.tgz (132,549 bytes)
.............................done: 132,549 bytes
install ok: channel://pear.php.net/DB-1.11.0
 16:52:28  root@Debian11-0-FreeRADIUS-01  	  192.168.88.174  ~ 
 # 
 16:52:28  root@Debian11-0-FreeRADIUS-01  	  192.168.88.174  ~ 
 #  sudo pear install MDB2
WARNING: channel "pear.php.net" has updated its protocols, use "pear channel-update pear.php.net" to update
downloading MDB2-2.4.1.tgz ...
Starting to download MDB2-2.4.1.tgz (121,557 bytes)
..........................done: 121,557 bytes
install ok: channel://pear.php.net/MDB2-2.4.1
MDB2: Optional feature fbsql available (Frontbase SQL driver for MDB2)
MDB2: Optional feature ibase available (Interbase/Firebird driver for MDB2)
MDB2: Optional feature mysql available (MySQL driver for MDB2)
MDB2: Optional feature mysqli available (MySQLi driver for MDB2)
MDB2: Optional feature mssql available (MS SQL Server driver for MDB2)
MDB2: Optional feature oci8 available (Oracle driver for MDB2)
MDB2: Optional feature pgsql available (PostgreSQL driver for MDB2)
MDB2: Optional feature querysim available (Querysim driver for MDB2)
MDB2: Optional feature sqlite available (SQLite2 driver for MDB2)
MDB2: To install optional features use "pear install pear/MDB2#featurename"
 16:52:43  root@Debian11-0-FreeRADIUS-01  	  192.168.88.174  ~ 
 # 

#####################



http://server_ip_or_hostname/daloradius

預設密碼
Username: administrator
Password: radius

```
