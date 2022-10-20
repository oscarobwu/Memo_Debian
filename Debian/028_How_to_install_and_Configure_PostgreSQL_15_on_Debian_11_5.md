# How to install and Configure PostgreSQL 15 on Debian 11.

### 028_How_to_install_and_Configure_PostgreSQL_15_on_Debian_11_5.md


### 1. Update your system packages

```
sudo apt update
```

### 2. Install PostgreSQL 15 on Debian 11

```

sudo sh -c 'echo "deb http://apt.postgresql.org/pub/repos/apt $(lsb_release -cs)-pgdg main 15" > /etc/apt/sources.list.d/pgdg.list'

wget --quiet -O - https://www.postgresql.org/media/keys/ACCC4CF8.asc | sudo apt-key add -


sudo apt update 



# 最後安裝 

sudo apt -y install postgresql-15


# 確認安裝狀態

$ sudo systemctl status postgresql

sudo systemctl start postgresql
sudo systemctl restart postgresql
sudo systemctl reload postgresql


```

### 3. Configuring PostgreSQL

```
$ sudo -u postgres psql
could not change directory to "/root": Permission denied
psql (15rc2 (Debian 15~rc2-1.pgdg110+1))
Type "help" for help.

postgres=# 


Create User & Database

sudo su - postgres -c "createuser <name>"

sudo su - postgres -c "createdb <namedb>"


sudo su - postgres -c "createuser grafanauser"

sudo su - postgres -c "createdb grafanadb"


# 先連接 再給user權限

sudo -u postgres psql

ALTER DATABASE grafanadb OWNER TO grafanauser;


GRANT ALL PRIVILEGES ON DATABASE <usernamedb> TO <name>;

GRANT ALL PRIVILEGES ON DATABASE grafanadb TO grafanauser;



Configure UFW Firewall


Subnet range:
sudo ufw allow proto tcp from 192.168.1.0/24 to any port 5432

Individual IP:

sudo ufw allow proto tcp from 192.168.1.0 to any port 5432

```

### Remote Access to PostgreSQL

```
ls /etc/postgresql/

sudo vi /etc/postgresql/15/main/postgresql.conf

find "Connection Settings" 
 
change the (listen_addresses = 'localhost')  to <IP>

listen_addresses = '192.168.0.10'

especially when running in multiple servers requiring connecting to the PostgreSQL database by changing localhost to (*).
 

如果用 nano編輯

Now save the file (CTRL+O), exit (CTRL+X), and restart your PostgreSQL instance.

sudo systemctl restart postgresql

ss -nlt | grep 5432

sudo netstat -antup | grep 5432


For further customization and securing PostgreSQL, 
you can configure the server to accept remote connections by editing the “pg_hba.conf” file using the nano text editor.

sudo nano /etc/postgresql/15/main/pg_hba.conf

```

### Access PostgreSQL

```
sudo -u postgres psql

postgres@server:~$ psql
psql (13.4 (Debian 13.4-1.pgdg100+1))
Type "help" for help.

postgres=#

# 設定密碼

postgres=# \password
或
postgres=# \password postgres



# Create PostgreSQL Database and User

CREATE USER mydb_user WITH ENCRYPTED PASSWORD 'password';

CREATE DATABASE mydb WITH OWNER mydb_user;

GRANT ALL PRIVILEGES ON DATABASE mydb TO mydb_user;


# 查看 user

postgres=# \du

# 出現
postgres=# \du
                                   List of roles
 Role name |                         Attributes                         | Member of
-----------+------------------------------------------------------------+-----------
 mydb_user |                                                            | {}
 postgres  | Superuser, Create role, Create DB, Replication, Bypass RLS | {}

postgres=#

# list DB

postgres=# \l

postgres=# \l
                                   List of databases
   Name    |   Owner   | Encoding |   Collate   |    Ctype    |    Access privileges
-----------+-----------+----------+-------------+-------------+-------------------------
 mydb      | mydb_user | UTF8     | en_US.UTF-8 | en_US.UTF-8 | =Tc/mydb_user          +
           |           |          |             |             | mydb_user=CTc/mydb_user
 postgres  | postgres  | UTF8     | en_US.UTF-8 | en_US.UTF-8 |
 template0 | postgres  | UTF8     | en_US.UTF-8 | en_US.UTF-8 | =c/postgres            +
           |           |          |             |             | postgres=CTc/postgres
 template1 | postgres  | UTF8     | en_US.UTF-8 | en_US.UTF-8 | =c/postgres            +
           |           |          |             |             | postgres=CTc/postgres
(4 rows)

postgres=#

# 也可以使用 帳號直接登入

psql -h localhost -d mydb -U mydb_user

##

Password for user mydb_user:
psql (13.4 (Debian 13.4-1.pgdg110+1))
SSL connection (protocol: TLSv1.3, cipher: TLS_AES_256_GCM_SHA384, bits: 256, compression: off)
Type "help" for help.

mydb=>


```
