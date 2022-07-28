# Zabbix-Proxy 6.2 TLS PGSQL IN Debian Server 11.4

### 更新安裝套件

```
# wget https://repo.zabbix.com/zabbix/6.2/debian/pool/main/z/zabbix-release/zabbix-release_6.2-1+debian11_all.deb
# dpkg -i zabbix-release_6.2-1+debian11_all.deb
# apt update
```
### 安裝
```

apt install zabbix-proxy-pgsql zabbix-sql-scripts zabbix-agent2

```

### 新增 PostgreSQL 管理帳號和資料庫名稱

```
sudo -u postgres createuser --pwprompt zabbixproxyuseruser
# 建立使用者和資料庫 {PASSWORD_ZABBIXUSER}
sudo -u postgres createdb -O zabbixproxyuseruser -E Unicode -T template0 zabbixdb_proxy

```

### 匯入資料庫

```
舊版使用方式
cd /usr/share/doc/zabbix-proxy-pgsql/
gzip -d schema.sql.gz

psql -h 127.0.0.1 -d zabbixdb_proxy -U zabbixuser -f schema.sql

新版 6.2 
cd /usr/share/doc/zabbix-sql-scripts/postgresql

psql -h 127.0.0.1 -d zabbixdb_proxy -U zabbixproxyuseruser -f proxy.sql

OR

# psql -h 127.0.0.1 -d zabbixdb_proxy -U zabbixuser -f /usr/share/doc/zabbix-sql-scripts/postgresql/proxy.sql

# 如果只使用 主動(active) 模式 的話 就是設定 zabbix proxy IP
# 

vi /etc/zabbix/zabbix_proxy.conf

#
Server={IP_ZABBIX_SERVER}
#
#Hostname=
#
HostnameItem=system.hostname
#
DBName=zabbixdb_proxy
#
DBUser=zabbixuser
#
DBPassword={PASSWORD_ZABBIXUSER}
#
ConfigFrequency=100
#

```

### 設定加密

```
在 zabbix proxy 端設定

openssl rand -hex 32
vi /etc/zabbix/zabbix_proxy.psk
# copier la clé

chown zabbix: /etc/zabbix/zabbix_proxy.psk
chmod 644 /etc/zabbix/zabbix_proxy.psk

```

### 在proxy 端設定

```

vi /etc/zabbix/zabbix_proxy.conf
TLSConnect=psk
TLSAccept=psk
TLSPSKFile=/etc/zabbix/zabbix_proxy.psk
TLSPSKIdentity=zabbix-proxy-1


## 重啟服務
systemctl enable zabbix-proxy
systemctl restart zabbix-proxy

## 確認服務和狀態

systemctl status zabbix-proxy

cat /var/log/zabbix/zabbix_proxy.log
tail -f /var/log/zabbix/zabbix_proxy.log


```
