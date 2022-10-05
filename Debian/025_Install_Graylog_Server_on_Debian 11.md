# 安裝 Garylog

### 025_Install_Graylog_Server_on_Debian 11


```
Java /OpenJDK– which is used as a runtime environment for ElasticSearch.
ElasticSearch– this is the log analysis tool for the Graylog Server.
MongoDB – it stores the data and configurations.
Graylog Server– The sever that passes logs for visualization using the provides a

Setup Requirements.
Memory above 4 GB.
Storage above 20 GB.
4 CPU cores
Debian 10/11 installed and updated.
All packages upgraded.



```

### Step 1: Install Java on Debian 11|10

```
sudo apt update
sudo apt install -y apt-transport-https openjdk-11-jre-headless uuid-runtime pwgen curl dirmngr

# 確認 JAVA 

$ java -version


```

### Step 2: Install ElasticSearch on Debian 11|10.

```
wget -qO - https://artifacts.elastic.co/GPG-KEY-elasticsearch | sudo apt-key add -

echo "deb https://artifacts.elastic.co/packages/oss-7.x/apt stable main" | sudo tee /etc/apt/sources.list.d/elastic-7.x.list

sudo apt update
sudo apt install -y elasticsearch-oss

### sudo apt install vim
sudo vi /etc/elasticsearch/elasticsearch.yml

# auto_create_index参数的默认值为false，表示不允许自动创建索引。一般建议您不要调整该值，否则会引起索引太多、索引Mapping和Setting不符合预期等问题。

cluster.name: f5_graylog
action.auto_create_index: false

sudo systemctl daemon-reload
sudo systemctl start elasticsearch
sudo systemctl enable elasticsearch

# 確認狀態
$ systemctl status elasticsearch

## 建議調整jvm 記憶替
sudo vi /etc/elasticsearch/jvm.options

# In the file, find and replace the options below if your RAM is below 4GB.

# Xms represents the initial size of total heap space
# Xmx represents the maximum size of total heap space
-Xms512m
-Xmx512m

# 確認服務
curl -X GET http://localhost:9200

```

### Step 3: Install MongoDB on Debian 11|10


```
wget -qO - https://www.mongodb.org/static/pgp/server-5.0.asc | sudo apt-key add -
sudo apt update

echo "deb http://repo.mongodb.org/apt/debian buster/mongodb-org/5.0 main" | sudo tee /etc/apt/sources.list.d/mongodb-org-5.0.list

sudo apt-get update
sudo apt-get install -y mongodb-org mongodb-org-database mongodb-org-server mongodb-org-shell mongodb-org-mongos mongodb-org-tools

sudo systemctl start mongod
sudo systemctl enable mongod

# 確認服務

$ systemctl status mongod





```

### Step 4: Install Graylog Server on Debian 11|10

```

wget https://packages.graylog2.org/repo/packages/graylog-4.3-repository_latest.deb
sudo dpkg -i graylog-4.3-repository_latest.deb

sudo apt-get install apt-transport-https
wget https://packages.graylog2.org/repo/packages/graylog-4.3-repository_latest.deb
sudo dpkg -i graylog-4.3-repository_latest.deb
sudo apt-get update
sudo apt-get install graylog-server


pwgen -N 1 -s 96

nnDZKkO4gN4HA2qG2cBZQVxyxmw1qgH4tIVBetfv8CsMpfM3SUuxMj4g0staNvuz9AQbPMg4RiuCy9NozKe72tzdXkLqD0sU

# 編輯 server.conf
sudo vi /etc/graylog/server/server.conf

# 新稱密碼
password_secret = 98KM6k7W6CtfQPc0EFKS3EMsb3bgYK1qPwDZcNezkqx4usSOMZE1rbKtuHuRwllkzm37cAp5U07jD9Hv6hCybkk3vJdVlC38

# In the server.conf file, also add the below lines.
rest_listen_uri = http://127.0.0.1:9000/api/
web_listen_uri = http://127.0.0.1:9000/


########### 
# Graylog
 ProxyPass /graylog/ http://servername:9000/
 ProxyPassReverse /graylog/ http://servername:9000/


upstream graylog_web_interface {
         server 10.10.10.100:9000;
         server 10.10.10.101:9000;
}

server {
  listen 80 default_server;
  listen [::]:80 default_server;

  # only log critical
  access_log  off;
  error_log off;
  
   location / {
    rewrite ^ https://$host$request_uri? permanent;
  }

}

  	# Graylog reverse proxy
    	location /graylog/ {
        	proxy_set_header    Host $http_host;
        	proxy_set_header    X-Forwarded-Host $host;
        	proxy_set_header    X-Forwarded-Server $host;
        	proxy_set_header    X-Forwarded-For $proxy_add_x_forwarded_for;
        	proxy_set_header    Remote-User admin;
        	proxy_set_header    X-Forwarded-User admin;
        	proxy_set_header    X-Graylog-Server-URL https://$http_host/graylog/api;
        	proxy_pass          http://graylog_web_interface;
        }


      - GRAYLOG_REST_LISTEN_URI=http://0.0.0.0:9000/graylog/api
      - GRAYLOG_WEB_LISTEN_URI=http://0.0.0.0:9000/graylog

###############

echo -n Str0ngPassw0rd | sha256sum

434e27fac24a15cbf8b160b7b28c143a67d9e6939cbb388874e066e16cb32d75

$ echo -n "Enter Password: " && head -1 </dev/stdin | tr -d '\n' | sha256sum | cut -d" " -f1
Enter Password: Str0ngPassw0rd


ab38eadaeb746599f2c1ee90f8267f31f467347462764a24d71ac1843ee77fe3

# 修改密碼
sudo vi /etc/graylog/server/server.conf


root_password_sha2 = ab38eadaeb746599f2c1ee90f8267f31f467347462764a24d71ac1843ee77fe3

sudo systemctl daemon-reload
sudo systemctl restart graylog-server
sudo systemctl enable graylog-server

sudo tail -f /var/log/graylog-server/server.log

# 修改登入 IP 

sudo vi /etc/graylog/server/server.conf

http_bind_address = 0.0.0.0:9000

 找到「root_timezone =」將後方時區改成 Asia/Taipei (完整時區表)

sudo systemctl restart graylog-server



```

```
######

### 設定ssl

mkdir /etc/nginx/ssl

sudo openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /etc/nginx/ssl/nginx.key -out /etc/nginx/ssl/nginx.crt -subj "/C=TW/ST=Taiwan/L=Taipei/O=MongoDB/OU=IT/CN=mylocaldomain.com/emailAddress=admin@mylocaldomain.com"




```

### 設定 nginx conf

```
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
		
    	location /graylog {
        	proxy_set_header Host $http_host;
        	proxy_set_header X-Forwarded-Host $host;
        	proxy_set_header X-Forwarded-Server $host;
        	proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        	proxy_set_header X-Graylog-Server-URL https://$server_name/graylog/api;
        	proxy_pass       http://127.0.0.1:9000/graylog;
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

####
```

```
touch /var/www/html/phpinfo.php && echo '<?php phpinfo(); ?>' >> /var/www/html/phpinfo.php

#
vi /var/www/html/index.html

<!DOCTYPE html>
<html>
<head>
   <!-- HTML meta refresh URL redirection -->
   <meta http-equiv="refresh"
   content="0; url=/graylog">
</head>
<body>
   <p>The page has moved to:
   <a href="/grafana">this page</a></p>
</body>
</html>

```
