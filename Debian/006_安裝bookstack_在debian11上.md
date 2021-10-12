# 安裝 bookstack 在 Debian 11上
----
006_安裝bookstack_在debian11上.md
## 設定資料庫

```language


mysql -u root -p   # 登入資料庫裡設定資表及權限

CREATE DATABASE IF NOT EXISTS bookstackdb DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci; #在資料庫裡新增BookStack表單
GRANT ALL PRIVILEGES ON bookstackdb.* TO 'bookstackuser'@'localhost' IDENTIFIED BY 'RANDOMP@ssw0rd' WITH GRANT OPTION; #密碼請自己輸入好記錄的密碼
FLUSH PRIVILEGES;
EXIT;

cd /var/www/html
git clone https://github.com/BookStackApp/BookStack.git --branch release --single-branch bookstack

cd /var/www/html/bookstack
curl -s https://getcomposer.org/installer > composer-setup.php
php composer-setup.php --quiet
rm -f composer-setup.php
export COMPOSER_ALLOW_SUPERUSER=1
php composer.phar install --no-dev --no-plugins

```

安裝目錄

```language


cd /var/www/html/bookstack
mv .env.example .env

sudo mv .env.example .env
sudo vi .env

##
# 權宜之計為新增一個 vhost 在nginx上

APP_URL=http://bookstack.bigtalk.info
# Database details
DB_HOST=localhost
DB_DATABASE=bookstackdb
DB_USERNAME=bookstackuser
DB_PASSWORD=RANDOMP@ssw0rd

php artisan key:generate --no-interaction --force
php artisan migrate --no-interaction --force

chown nginx:nginx -R bootstrap/cache public/uploads storage
chmod -R 755 bootstrap/cache public/uploads storage
```
設定nginx 

```language


vi /etc/hosts
127.0.0.1   bookstack.example.com



sudo vi /etc/nginx/sites-available/bookstack.conf

server {
    listen 80;
    listen [::]:80;
    root /var/www/html/bookstack/public;
    index  index.php index.html index.htm;
    server_name  example.com www.example.com bookstack.example.com bookstack.bigtalk.info;

    client_max_body_size 100M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
       }

    location ~ \.php$ {
        #try_files $uri /index.php =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass   unix:/var/run/php/php8.0-fpm.sock;
        fastcgi_index  index.php;
        #fastcgi_param  SCRIPT_FILENAME /var/www/html/$fastcgi_script_name;
        fastcgi_param  SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include        fastcgi_params;
    }
}

ln -s /etc/nginx/sites-available/bookstack.conf /etc/nginx/sites-enabled/

sudo ln -s /etc/nginx/sites-available/bookstack.conf /etc/nginx/sites-enabled/
```

### 或是使用https

```language


###################### sudo vi /etc/nginx/sites-available/default.conf

server {
        listen 80;
        listen [::]:80;
        server_name example.com www.example.com bookstack.example.com bookstack.bigtalk.info;

  # 導向至 HTTPS
  rewrite ^(.*) https://$host$1 permanent;
}

server {
        # SSL 設定
        listen 443 ssl;
        listen [::]:443 ssl;

        # 憑證與金鑰的路徑
        ssl_certificate /etc/nginx/ssl/nginx.crt;
        ssl_certificate_key /etc/nginx/ssl/nginx.key;
        client_max_body_size 100M;
        # SSL configuration

    root /var/www/html/bookstack/public;
    index  index.php index.html index.htm;
    server_name  example.com www.example.com bookstack.example.com bookstack.bigtalk.info;

    #client_max_body_size 100M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
       }

    location ~ \.php$ {
        #try_files $uri /index.php =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass   unix:/var/run/php/php8.0-fpm.sock;
        fastcgi_index  index.php;
        #fastcgi_param  SCRIPT_FILENAME /var/www/html/$fastcgi_script_name;
        fastcgi_param  SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include        fastcgi_params;
    }
}

```
