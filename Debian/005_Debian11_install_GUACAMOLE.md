# INSTALL GUACAMOLE 1.3.0 ON DEBIAN 11 WITH DB AUTHENTICATION (PART 1)
----

#####################
I started with a fresh Debian 10.3.0 with latest patches. I just installed vim and open-vm-tools because my server runs on an VMWare cluster

Tomcat 的port 改 8083

## 1. Install Tomcat 9

```language
apt install tomcat9 tomcat9-admin tomcat9-common tomcat9-user
If you now open http://<YOUR_SERVER>:8083 you should get an “It works !” website.
```


## 2. Install Guacamole Server

### 2.1 Install required packages
```language


apt install build-essential libcairo2-dev libjpeg62-turbo-dev libtool-bin libossp-uuid-dev libavcodec-dev libavutil-dev libswscale-dev freerdp2-dev libpango1.0-dev libssh2-1-dev libtelnet-dev libvncserver-dev libwebsockets-dev libpulse-dev libssl-dev    libvorbis-dev libwebp-dev
```
### 2.2 Download and install Guacamole Server
```
wget https://mirror.dkd.de/apache/guacamole/1.3.0/source/guacamole-server-1.3.0.tar.gz
tar vfx guacamole-server-1.3.0.tar.gz
cd guacamole-server-1.3.0/
autoreconf -fi
./configure --with-init-dir=/etc/init.d
make
make install
Activate Service and start it:

ldconfig
systemctl enable guacd
systemctl start guacd

```


## 3. Install Guacamole Client

### 3.1 Download
```
wget http://us.mirrors.quenda.co/apache/guacamole/1.3.0/binary/guacamole-1.3.0.war
 mkdir /etc/guacamole
 cp guacamole-1.3.0.war /etc/guacamole/guacamole.war
 ln -s /etc/guacamole/guacamole.war /var/lib/tomcat9/webapps/
 mkdir /etc/guacamole/{extensions,lib}
 echo "GUACAMOLE_HOME=/etc/guacamole" | tee -a /etc/default/tomcat9
```

## 4. Install Database Server

### 4.1 Install packages


```
如果已經安裝請忽略
apt install mariadb-server mariadb-client
Note: You should secure your DB installation by running ‘mysql_secure_installation’.
```
### 4.2 Create Database and user
```
mysql -p
CREATE DATABASE guacamole_db;
CREATE USER 'guacamole_user'@'localhost' IDENTIFIED BY 'passw0rd';
GRANT SELECT,INSERT,UPDATE,DELETE ON guacamole_db.* TO 'guacamole_user'@'localhost';
FLUSH PRIVILEGES;
quit;

CREATE DATABASE guacamole_db;
CREATE USER 'guacamole_user'@'localhost' IDENTIFIED BY 'some_password';
GRANT SELECT,INSERT,UPDATE,DELETE ON guacamole_db.* TO 'guacamole_user'@'localhost';
FLUSH PRIVILEGES;
quit;

```
### 4.3 Download jdbc-extension
```


wget http://apache.mirror.digionline.de/guacamole/1.3.0/binary/guacamole-auth-jdbc-1.3.0.tar.gz

tar vfx guacamole-auth-jdbc-1.3.0.tar.gz
```

### 4.4 Import Database
```
cat guacamole-auth-jdbc-1.3.0/mysql/schema/*.sql | mysql -u root -p guacamole_db

```

### 4.5 Install extension
```
cp guacamole-auth-jdbc-1.3.0/mysql/guacamole-auth-jdbc-mysql-1.3.0.jar /etc/guacamole/extensions/
```
### 4.6 JDBC driver installieren
```
wget https://dev.mysql.com/get/Downloads/Connector-J/mysql-connector-java-8.0.26.tar.gz

tar xvzf mysql-connector-java-8.0.26.tar.gz

cp mysql-connector-java-8.0.26/mysql-connector-java-8.0.26.jar /etc/guacamole/lib/
```

### 4.7 Configure DB Time zone

```
See this Issue: https://issues.apache.org/jira/browse/GUACAMOLE-760 to fix the following error after restart you have to configurate the time zone of your DB.

Error querying database. Cause: java.sql.SQLException: The server time zone value 'CEST' is unrecognized or represents more than one time zone. You must configure either the server or JDBC driver (via the serverTimezone configuration property) to use a more specifc time zone value if you want to utilize time zone support.
Import time zones to your database:

mysql_tzinfo_to_sql /usr/share/zoneinfo | mysql -u root -p mysql
Now open ‘/etc/mysql/mariadb.conf.d/50-server.cnf’ with your editor and add the following line:

default_time_zone='Europe/Berlin'
Replace ‘Europe/Berlin’ with your correct time zone! And restart the database:

systemctl restart mariadb.service
```
## 5. Configurate Guacamole

```
vim /etc/guacamole/guacamole.properties
# Hostname and Guacamole server port
guacd-hostname: localhost
guacd-port: 4822

# MySQL properties
mysql-hostname: localhost
mysql-port: 3306
mysql-database: guacamole_db
mysql-username: guacamole_user
mysql-password: passw0rd
#
After each modification of this file you have to restart your tomcat server.

systemctl restart tomcat9
```
## 6. Test
```
Open http://<YOUR_SERVER>:8080/guacamole in your browser and login with Username: ‘guacadmin’ and Password: ‘guacadmin’.
```
## 7. Apache reverse Proxy

### 7.1 Installation
```
apt install apache2 -y
```
### 7.2 Activate Modules
```
a2enmod rewrite
a2enmod proxy_http
a2enmod proxy_wstunnel
```
### 7.3 Apache config
```
vim /etc/apache2/sites-enabled/000-default.conf
And insert to the VirtualHost:

ProxyPass / http://127.0.0.1:8080/guacamole/ flushpackets=on
ProxyPassReverse / http://127.0.0.1:8080/guacamole/
ProxyPassReverseCookiePath /guacamole /
<Location /websocket-tunnel>
   Order allow,deny
   Allow from all
   ProxyPass ws://127.0.0.1:8080/guacamole/websocket-tunnel
   ProxyPassReverse ws://127.0.0.1:8080/guacamole/websocket-tunnel
</Location>
SetEnvIf Request_URI "^/tunnel" dontlog
CustomLog  /var/log/apache2/guac.log common env=!dontlog
```
### 7.4 Restart Apache
```
systemctl restart apache2.service
```
### 7.5 Test

Now you can access your Guacamole with http://<YOUR_SERVER>. But of course you should put this in an https site!

## 8. Debugging

```
Tomcat ist logging to tail /var/log/tomcat9/catalina.out

tail /var/log/tomcat9/catalina.out -f
show you the main log of your guacamole server.

If you need more details create ‘/etc/guacamole/logback.xml’ file:

<configuration>
 <!-- Appender for debugging -->
 <appender name="GUAC-DEBUG" class="ch.qos.logback.core.ConsoleAppender">
   <encoder>
    <pattern>%d{HH:mm:ss.SSS} [%thread] %-5level %logger{36} - %msg%n</pattern>
   </encoder>
 </appender>

 <!-- Log at Debug Level -->
 <root level="debug">
    <appender-ref ref="GUAC-DEBUG"/>
 </root>
</configuration>
and restart Tomcat:

systemctl restart tomcat9
```
