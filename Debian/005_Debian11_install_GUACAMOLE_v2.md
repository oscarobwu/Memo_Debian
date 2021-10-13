
# 安裝 tomcat
----

```
sudo apt install tomcat9 tomcat9-admin tomcat9-common tomcat9-user
sudo ss -lnpt | grep java


apt install build-essential libcairo2-dev libjpeg62-turbo-dev libpng-dev libtool-bin libossp-uuid-dev libvncserver-dev freerdp2-dev libssh2-1-dev libtelnet-dev libwebsockets-dev libpulse-dev libvorbis-dev libwebp-dev libssl-dev libpango1.0-dev libswscale-dev libavcodec-dev libavutil-dev libavformat-dev


wget https://mirror.dkd.de/apache/guacamole/1.3.0/source/guacamole-server-1.3.0.tar.gz
tar vfx guacamole-server-1.3.0.tar.gz
cd guacamole-server-1.3.0/

autoreconf -fi
./configure --with-init-dir=/etc/init.d
make
make install


ldconfig
systemctl enable guacd
systemctl start guacd

```

```

cd ~
wget https://downloads.apache.org/guacamole/1.3.0/binary/guacamole-1.3.0.war
mkdir /etc/guacamole
cp guacamole-1.3.0.war /etc/guacamole/guacamole.war
ln -s /etc/guacamole/guacamole.war /var/lib/tomcat9/webapps/
mkdir /etc/guacamole/{extensions,lib}
echo "GUACAMOLE_HOME=/etc/guacamole" | tee -a /etc/default/tomcat9

```

建立資料庫

```
mysql -p

CREATE DATABASE guacamole_db;
CREATE USER 'guacamole_user'@'localhost' IDENTIFIED BY 'passw0rd';
GRANT SELECT,INSERT,UPDATE,DELETE ON guacamole_db.* TO 'guacamole_user'@'localhost';
FLUSH PRIVILEGES;
quit;

```


```
wget http://apache.mirror.digionline.de/guacamole/1.3.0/binary/guacamole-auth-jdbc-1.3.0.tar.gz

tar vfx guacamole-auth-jdbc-1.3.0.tar.gz

cat guacamole-auth-jdbc-1.3.0/mysql/schema/*.sql | mysql -u root -p guacamole_db

cp guacamole-auth-jdbc-1.3.0/mysql/guacamole-auth-jdbc-mysql-1.3.0.jar /etc/guacamole/extensions/

wget https://dev.mysql.com/get/Downloads/Connector-J/mysql-connector-java-8.0.26.tar.gz

tar xvzf mysql-connector-java-8.0.26.tar.gz

cp mysql-connector-java-8.0.26/mysql-connector-java-8.0.26.jar /etc/guacamole/lib/

```



```
vim /etc/guacamole/guacamole.properties

```

```
# Hostname and Guacamole server port
guacd-hostname: localhost
guacd-port: 4822

# MySQL properties
mysql-hostname: localhost
mysql-port: 3306
mysql-database: guacamole_db
mysql-username: guacamole_user
mysql-password: passw0rd
```
```
systemctl restart tomcat9

```

```

Open http://<YOUR_SERVER>:8080/guacamole in your browser and login with Username: 'guacadmin' and Password: 'guacadmin'.

```
