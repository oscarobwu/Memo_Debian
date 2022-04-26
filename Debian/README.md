# Debian 技巧

```
在 jenkins 執行 下面指令
ssh-keygen -t rsa


將key複製到 nginx 上 免密碼登入

scp -p /root/.ssh/id_rsa.pub root@192.168.182.4:/root/.ssh/authorized_keys

# 同步 dist 資料夾 到遠端 html 下
scp -o "StrictHostKeyChecking no" -r dist/* root@192.168.182.4:/home/nginx/html

```
