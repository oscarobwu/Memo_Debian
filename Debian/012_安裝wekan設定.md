How to Install Wekan Kanban with Nginx on Debian 11.3

----

```

apt update -y
apt upgrade -y
 
```

### Install Wekan

```

apt install snapd -y

snap install wekan

systemctl status snap.wekan.wekan

設定 port 和 url 讓 nginx 可以 對接

snap set wekan port='3001'
sudo snap set wekan root-url="http://local.example.com/wekan"

# 重啟 mongodb

systemctl restart snap.wekan.mongodb
systemctl restart snap.wekan.wekan


systemctl stop snap.wekan.wekan
systemctl start snap.wekan.wekan

systemctl stop snap.wekan.mongodb
systemctl start snap.wekan.mongodb

```


# 設定 wekan 使用 sub url 

----

#### 設定 host檔案

```
設定nginx 

http {
    .......
    server_names_hash_bucket_size 64;
    
    .....
}

/etc/nginx/nginx.conf

vi /etc/hosts

127.0.0.1	local.example.com

新增nginx 設定 將 nginx 目錄加入 wekan 目錄

設定 default.conf 

新增下面 目錄

location ^~/wekan/ {
      proxy_pass http://local.example.com;
}
```

## 新增 nginx 站台

```

vi /etc/nginx/sites-available/wekan.conf


map $http_upgrade $connection_upgrade {
    default upgrade;
    ''      close;
}
server {
    listen 80;
    server_name local.example.com;
    if ($http_user_agent ~ "MSIE" ) {
        return 303 https://browser-update.org/update.html;
    }
    location /wekan {
    # location / {
        proxy_pass http://127.0.0.1:3001/wekan;
        # proxy_pass http://127.0.0.1:3001;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade; # allow websockets
        proxy_set_header Connection $connection_upgrade;
        proxy_set_header X-Forwarded-For $remote_addr; # preserve client IP

        # this setting allows the browser to cache the application in a way compatible with Meteor
        # on every applicaiton update the name of CSS and JS file is different, so they can be cache infinitely (here: 30 days)
        # the root path (/) MUST NOT be cached
        #if ($uri != '/wekan') {
        #    expires 30d;
        #}
    }
}


ln -s /etc/nginx/sites-available/wekan.conf /etc/nginx/sites-enabled/


sudo apt install snapd
sudo snap install wekan
sudo snap set wekan root-url="http://local.example.com/wekan"
sudo snap set wekan port="3001"
sudo systemctl restart snap.wekan.mongodb
sudo systemctl restart snap.wekan.wekan

這樣就可以 換任何IP都無須修正 root-url


```
