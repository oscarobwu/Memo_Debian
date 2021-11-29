# 設定 Debian 11 NTP server 讓其他主機同步時間

### 設定 設定 讓其他主機來同步

```

apt -y install chrony

vi /etc/chrony/chrony.conf

# vi /etc/chrony/chrony.conf
# line 8 : comment out default settings and add NTP Servers for your timezone
# 對上 (或是internet) 同步時間的主機
#pool 2.debian.pool.ntp.org iburst
pool ntp.nict.jp iburst 

# add to the end : add network range you allow to receive time syncing requests from clients
# 設定允許跟本伺服器校時的IP區段
allow 192.168.88.0/24

########

# systemctl restart chrony


###
手動進行校時
chronyc -a makestep

查看系統日期

```

### 同步時間

```
~# chronyc makestep
200 OK
```
