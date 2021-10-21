# 安裝How_to_Install_Redmine_4.2.3_on_Debian_11.md


Step 1 – Create Atlantic.Net Cloud Server


Step 2 – Install Apache and Other Packages

```language

apt-get install apt-transport-https ca-certificates dirmngr gnupg2 -y
```

由於有些已經安裝故不需要安裝
```language
apt-get install apache2 apache2-dev libapache2-mod-passenger mariadb-server mariadb-client build-essential ruby-dev libxslt1-dev libmariadb-dev libxml2-dev zlib1g-dev imagemagick libmagickwand-dev curl -y

改為

apt-get install apache2 apache2-dev libapache2-mod-passenger build-essential ruby-dev libxslt1-dev libmariadb-dev libxml2-dev zlib1g-dev imagemagick libmagickwand-dev curl -y

要先修改 apache port 到 3005

vi /etc/apache2/ports.conf
vi /etc/apache2/sites-available/000-default.conf
vi /etc/apache2/sites-available/default-ssl.conf

systemctl enable apache2
systemctl start apache2
systemctl restart apache2

```

Step 3 – Create a Database for Redmine

```language

创建MySQL数据库
mysql -u root -p


CREATE DATABASE redmine_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'redmine_user'@'localhost' IDENTIFIED BY 'S3cur3P4ssw0rd';
GRANT ALL PRIVILEGES ON redmine_db.* TO 'redmine_user'@'localhost';
FLUSH PRIVILEGES;
quit;


```

Step 4 – Download and Configure Redmine

```language

useradd -r -m -d /opt/redmine -s /usr/bin/bash redmine

usermod -aG redmine nginx

```

```language
su - redmine
wget --no-check-certificate https://www.redmine.org/releases/redmine-4.2.3.tar.gz
```

```language
tar -xvzf redmine-4.2.3.tar.gz -C /opt/redmine/ --strip-components=1

```

```language
cp /opt/redmine/config/configuration.yml{.example,}
cp /opt/redmine/public/dispatch.fcgi{.example,}
cp /opt/redmine/config/database.yml{.example,}

```

```language
vi /opt/redmine/config/database.yml

```

```language
production:
  adapter: mysql2
  database: redmine_db
  host: localhost
  username: redmine_user
  password: "S3cur3P4ssw0rd"
  encoding: utf8mb4
```

```language
exit
```

```language
cd /opt/redmine
gem install bundler
```

```language
su - redmine
bundle install --without development test --path vendor/bundle

```

```language

bundle exec rake generate_secret_token

```

```language
RAILS_ENV=production bundle exec rake db:migrate
RAILS_ENV=production REDMINE_LANG=en bundle exec rake redmine:load_default_data
```

```language
for i in tmp tmp/pdf public/plugin_assets; do [ -d $i ] || mkdir -p $i; done

```

```language
chown -R redmine:redmine files log tmp public/plugin_assets
chmod -R 755 /opt/redmine
```

```language
exit
```

Step 5 – Configure Apache for Redmine

```language
vi /etc/apache2/sites-available/redmine.conf
```

```language
<VirtualHost *:3005>
	ServerName redmine.example.com
	RailsEnv production
	DocumentRoot /opt/redmine/public

	<Directory "/opt/redmine/public">
	        Allow from all
	        Require all granted
	</Directory>

    ErrorLog ${APACHE_LOG_DIR}/redmine_error.log
    CustomLog ${APACHE_LOG_DIR}/redmine_access.log combined
</VirtualHost>



```

```language
a2ensite redmine
systemctl reload apache2
```

```language
vi /opt/redmine/config/environment.rb

將
# Initialize the Rails application
Rails.application.initialize!

修改為

Redmine::Utils::relative_url_root = "/redmine" 

RedmineApp::Application.routes.default_scope = "/redmine" 
# Initialize the Rails application
Rails.application.initialize!

URL : http://adresse_ip/redmine

```

```language

vi /etc/nginx/sites-available/redmine.mydomain.com.conf

server {
    listen 80;
    server_name redmine.mydomain.com;
    return 301 https://$host$request_uri;
}

server {

    listen 443;
    server_name redmine.mydomain.com;

    ssl_certificate           /etc/nginx/ssl/nginx.crt;
    ssl_certificate_key       /etc/nginx/ssl/nginx.key;
    client_max_body_size 100M;

    #ssl on;
    #ssl_session_cache  builtin:1000  shared:SSL:10m;
    #ssl_protocols  TLSv1 TLSv1.1 TLSv1.2;
    #ssl_ciphers HIGH:!aNULL:!eNULL:!EXPORT:!CAMELLIA:!DES:!MD5:!PSK:!RC4;
    #ssl_prefer_server_ciphers on;

    # log files
    access_log /var/log/nginx/redmine.mydomain.com.access.log;
    error_log /var/log/nginx/redmine.mydomain.com.error.log;

    location / {

      proxy_set_header        Host $host;
      proxy_set_header        X-Real-IP $remote_addr;
      proxy_set_header        X-Forwarded-For $proxy_add_x_forwarded_for;
      proxy_set_header        X-Forwarded-Proto $scheme;

      # Fix the “It appears that your reverse proxy set up is broken" error.
      proxy_pass          http://redmine.example.com:3005;
      proxy_read_timeout  90;

      #proxy_redirect      http://127.0.0.1:8080 https://redmine.mydomain.com;
    }
  }

ln -s /etc/nginx/sites-available/redmine.mydomain.com.conf /etc/nginx/sites-enabled/

vi /etc/hosts

127.0.0.1       redmine.mydomain.com


http {
    server {
        listen        80; 
        server_name       service1.domain.com;
        location / { 
            proxy_pass       http://192.168.0.2:8181;
            proxy_set_header   host  service1.domain.com
        }   
    }
    server {
        listen        80; 
        server_name       service2.domain.com;
        location / { 
            proxy_pass       http://192.168.0.3:8080;
            proxy_set_header     host service2.domain.com;
        }   
    }
}
#####################################

server {
    listen        443;
    listen        [::]:443;
    server_name       redmine.mydomain.com;
    ssl_certificate           /etc/nginx/ssl/nginx.crt;
    ssl_certificate_key       /etc/nginx/ssl/nginx.key;
    client_max_body_size 100M;
    location / { 
        proxy_pass       http://redmine.mydomain.com:3005;
        proxy_set_header   host  redmine.mydomain.com
        proxy_read_timeout  90;
    }   
}

location ^~ /redmine {
    rewrite ^/redmine/?(.*)$ http://redmine.mydomain.com:3005/$1 permanent;
}

```
