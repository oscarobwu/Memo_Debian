# How to setup Redmine 4 on Debian11

007_安裝Redmine_4_在debina11上.md

### Prerequisites:

Full SSH root access or a user with sudo privileges.

## Step 1: Connect to your Server
To connect to your server via SSH as the root user, use the following command:

```shell
$ ssh root@IP_ADDRESS -p PORT_NUMBER
```
and replace `IP_ADDRESS` and `PORT_NUMBER` with your actual server IP address and SSH port number.

Once logged in, make sure that your server is up-to-date by running the following commands:
```shell
$ apt-get update
$ apt-get upgrade
```
## Step 2: Install MariaDB
Next, we need to install the MariaDB server. Debian11 has the latest stable version of MariaDB ready for installation through the pre-installed repositories.

The following command will install the latest MariaDB10.5 server :
```shell
sudo apt -y update
sudo apt -y install software-properties-common gnupg2 dirmngr
sudo apt -y upgrade

$ apt-key adv --fetch-keys 'https://mariadb.org/mariadb_release_signing_key.asc'
$ add-apt-repository 'deb [arch=amd64,arm64,ppc64el] https://mirror.rackspace.com/mariadb/repo/10.5/debian bullseye main'

apt install mariadb-server mysqltuner -y

```
The MariaDB server will be started automatically as soon as the installation is completed.
You can also enable the MariaDB service to automatically start up upon server reboot with the following command:
```shell
$ systemctl start mariadb.service
$ systemctl enable mariadb.service
$ systemctl restart mariadb.service
```
Run the following command to further secure your installation:
```
$ mysql_secure_installation
```
This script will help you to perform important security tasks like setting up a root password, disable remote root login, remove anonymous users, etc. If the script asks for the root password, just press the [Enter] key, as no root password is set by default.

## Step 3: Create a Database for Redmine
Next, we need to create a database for our Redmine installation. Log in to your MariaDB server with the following command and enter your MariaDB root password:
```shell
$ mysql -uroot -p
```
In this section, we will create a new MariaDB database:
```sql
CREATE DATABASE redmine_db;
GRANT ALL PRIVILEGES ON redmine_db.* TO 'redmine_user'@'localhost' IDENTIFIED BY 'S3cur3P4ssw0rd';
FLUSH PRIVILEGES;
exit;

OR

CREATE DATABASE redmine CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'redmine'@'localhost' IDENTIFIED BY 'S3cur3P4ssw0rd';
GRANT ALL PRIVILEGES ON redmine.* TO 'redmine'@'localhost';
FLUSH PRIVILEGES;
quit;

```
Make sure to replace the password **Password** with a real, strong password.

## Step 4: Install Ruby
The easiest way to install Ruby on your Ubuntu 18.04 server is through the apt package manager. The current version in the Ubuntu repositories is 2.5.1 which is the latest stable version of Ruby at the time of writing this tutorial.

Install Ruby with the following command:
```
$ apt install ruby-full -y
```
To verify everything is done correctly, use the command `ruby --version.`
The output should be similar to the following:
```shell
$ ruby --version
ruby 2.5.1p57 (2018-03-29 revision 63029) [x86_64-linux-gnu]
```

## Step 5: Install Nginx and Passenger
To install Nginx on your Ubuntu 18.04 server, you need to enter the following command:
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
Enable Nginx to start on boot and start the service using these two lines:
```
$ systemctl start nginx
$ systemctl enable nginx
```
To install Passenger, an Nginx module, start by installing the necessary package prerequisites:
```shell
$ apt-get install dirmngr gnupg apt-transport-https ca-certificates
```
Import the repository GPG key and enable the “Phusionpassenger” repository:

```shell
(不要安裝)
apt-get install -y dirmngr gnupg
apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv-keys 561F9B9CAC40B2F7
apt-get install -y apt-transport-https ca-certificates
sh -c 'echo deb https://oss-binaries.phusionpassenger.com/apt/passenger `lsb_release -cs` main > /etc/apt/sources.list.d/passenger.list'
apt update
```
Once the repository is enabled, update the packages list and install the Passenger Nginx module with:

```shell
$ apt-get update
$ apt-get install libnginx-mod-http-passenger

OR
apt install -y passenger

```
## Step 6: Download and Install Redmine
We need to install the dependencies necessary to build Redmine:
```shell
$ apt install build-essential libmysqlclient-dev imagemagick libmagickwand-dev
```
Go to Redmine’s official website and download the latest stable release of the application. At the time of this article being published, the latest version of Redmine is version 4.0.5.
```shell
$ wget --no-check-certificate https://www.redmine.org/releases/redmine-4.2.3.tar.gz -o /opt/redmine.zip
```
Once the `zip` archive is downloaded, unpack it to the `/opt` directory on your server:

```
$ cd /opt
$ apt install unzip
$ unzip redmine.zip
$ mv redmine-4.2.3 /opt/redmine

tar -xf redmine-4.2.3.tar.gz -C /opt/

ln -s /opt/redmine-4.2.3/ /opt/redmine

```
Now apply the following required file and folder permissions (these are needed in order for Nginx to have access to the files):
```shell
$ chown -R nginx:nginx /opt/redmine/
$ chmod -R 755 /opt/redmine/
```
Configure database settings:
```shell
$ cd /opt/redmine/config/
$ cp configuration.yml.example configuration.yml
$ cp database.yml.example database.yml
```
Open the database.yml file using your preferred text editor and update the username/password details based on the ones you set in Step 3:
```shell
$ vi database.yml
```
```yml
production:
  adapter: mysql2
  database: redmine_db
  host: localhost
  username: redmine_user
  password: "S3cur3P4ssw0rd"
  encoding: utf8
```
Then save and exit the file.

## Step 7: Install Ruby Dependencies, Generate Keys, and Migrate the Database
Navigate to the Redmine directory and install bundler and other Ruby dependencies:
```shell
$ cd /opt/redmine/
$ gem install bundler --no-rdoc --no-ri 
$ bundle install --without development test postgresql sqlite

OR

gem install bundler
bundle install --without development test

```
Run the following command to generate the keys and migrate the database:
```shell
$ bundle exec rake generate_secret_token
$ RAILS_ENV=production bundle exec rake db:migrate

bundle exec rake generate_secret_token
RAILS_ENV=production bundle exec rake db:migrate
RAILS_ENV=production bundle exec rake redmine:load_default_data


## 1. 下載官方 Image
cd /data/redmine
wget https://www.redmine.org/releases/redmine-4.0.3.tar.gz
tar zxvf redmine-4.0.3.tar.gz
ln -s redmine-4.0.3 current
cd current

## 2. 設定資料庫, 只改 production 就好
cp -pR config/database.yml.example config/database.yml
vi config/database.yml

## 3. 執行 gem, bundle 安裝依賴套件
### pwd: /data/redmine/current
~$ gem install bundler  # < 1m
Fetching: bundler-2.0.1.gem (100%)
Successfully installed bundler-2.0.1
Parsing documentation for bundler-2.0.1
Installing ri documentation for bundler-2.0.1
Done installing documentation for bundler after 6 seconds
1 gem installed

### pwd: /data/redmine/current
~$ bundle install --without development test # < 10m
The dependency tzinfo-data (>= 0) will be unused by any of the platforms Bundler is installing for. Bundler is installing for ruby but the dependency is only for x86-mingw32, x64-mingw32, x86-mswin32. To add those platforms to the bundle, run `bundle lock --add-platform x86-mingw32 x64-mingw32 x86-mswin32`.
Fetching gem metadata from https://rubygems.org/.............
Fetching gem metadata from https://rubygems.org/.
Resolving dependencies....
Fetching rake 12.3.2
Installing rake 12.3.2

... 略 ...

Installing roadie 3.5.0
Fetching roadie-rails 1.3.0
Installing roadie-rails 1.3.0
Fetching rouge 3.3.0
Installing rouge 3.3.0
Bundle complete! 27 Gemfile dependencies, 59 gems now installed.
Gems in the groups development and test were not installed.
Use `bundle info [gemname]` to see where a bundled gem is installed.


## 4. 用 Rake 啟動 redmine
### pwd: /data/redmine/current
~$ bundle exec rake generate_secret_token

## 5. Migrate Database Schema
### pwd: /data/redmine/current
~$ RAILS_ENV=production bundle exec rake db:migrate
== 1 Setup: migrating =========================================================
-- adapter_name()
   -> 0.0000s
-- adapter_name()
   -> 0.0000s
-- adapter_name()

... 略 ...

== 20180923082945 ChangeSqliteBooleansTo0And1: migrating ======================
== 20180923082945 ChangeSqliteBooleansTo0And1: migrated (0.0000s) =============

== 20180923091603 ChangeSqliteBooleansDefault: migrating ======================
== 20180923091603 ChangeSqliteBooleansDefault: migrated (0.0000s) =============


## 6. Load Initial Data
### pwd: /data/redmine/current
~$ RAILS_ENV=production bundle exec rake redmine:load_default_data

Select language: ar, az, bg, bs, ca, cs, da, de, el, en, en-GB, es, es-PA, et, eu, fa, fi, fr, gl, he, hr, hu, id, it, ja, ko, lt, lv, mk, mn, nl, no, pl, pt, pt-BR, ro, ru, sk, sl, sq, sr, sr-YU, sv, th, tr, uk, vi, zh, zh-TW [en]
====================================
Default configuration data loaded.

```
## Step 8: Configure Nginx
Open your preferred text editor and create the following Nginx server block file:
```shell
$ vi /etc/nginx/sites-available/redmine.mydomain.com.conf
```
Add this configuration to your config file:
```nginx
# Redirect HTTP -> HTTPS
server {
    listen 80;
    server_name www.redmine.mydomain.com redmine.mydomain.com;

    # include snippets/letsencrypt.conf;
    return 301 https://redmine.mydomain.com$request_uri;
}

# Redirect WWW -> NON WWW
server {
    listen 443 ssl http2;
    server_name www.redmine.mydomain.com;
    return 301 https://redmine.mydomain.com$request_uri;
}

server {
    listen 443 ssl http2;
    server_name redmine.mydomain.com;

    root /opt/redmine/public;

    # SSL parameters will be injected by Certbot in a later step
    
    # log files
    access_log /var/log/nginx/redmine.mydomain.com.access.log;
    error_log /var/log/nginx/redmine.mydomain.com.error.log;

    passenger_enabled on;
    passenger_min_instances 1;
    client_max_body_size 10m;
}
```
Remember to replace **redmine.mydomain.com** with your own custom domain
Then save and exit the file.

## Step 9: Install SSL Certificates with Let's Encrypt and Certbot

Install certbot:
```
$ apt-get install certbot python-certbot-nginx
```
Generate the certificate and configuration for Nginx
```shell
$ certbot --nginx
```
follow the instructions provided by `certbot`. at the end, the certificate should be installed and verified.

## Step 10: Enable NGINX and Redmine
To enable the server configuration that we just created, run the following command:
```shell
$ ln -s /etc/nginx/sites-available/redmine.mydomain.com.conf /etc/nginx/sites-enabled/redmine.mydomain.com.conf
```
Now, check the config file to make sure that there are no syntax errors. Any errors could crash the web server on restart.
```
$ nginx -t
```
Output:
```shell
nginx: the configuration file /etc/nginx/nginx.conf syntax is ok
nginx: configuration file /etc/nginx/nginx.conf test is successful
If there are no errors, you can reload the Nginx config.
```
Now reload Nginx
```
$ service nginx reload
```

## Easy Gantt plugin issues
[DB Migration issue](https://survivalguides.wordpress.com/2018/04/02/redmine-install-easy-gantt/)
