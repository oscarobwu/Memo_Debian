# 指令方式安裝 ELK stack Debian 11 

----

#### 安裝Elastic stack PGP repository signing Key
```
curl -sL https://artifacts.elastic.co/GPG-KEY-elasticsearch | gpg --dearmor > /etc/apt/trusted.gpg.d/elastic.gpg

echo "deb https://artifacts.elastic.co/packages/7.x/apt stable main" | tee /etc/apt/sources.list.d/elastic-7.x.list

```

#### 更新 package cache

```
sudo apt update && sudo apt -y install elasticsearch

```

#### 修改 Elasticsearch Cluster 名稱為 kifarunix-demo

```
sed -i '/cluster.name:/s/#//;s/my-application/kifarunix-demo/' /etc/elasticsearch/elasticsearch.yml

```

#### 更新網路設定 Update the Network Settings 為192.168.58.25 或是為 0.0.0.0
```
sed -i '/network.host:/s/#//;s/192.168.0.1/192.168.58.25/' /etc/elasticsearch/elasticsearch.yml

sed -i '/network.host:/s/#//;s/192.168.0.1/0.0.0.0/' /etc/elasticsearch/elasticsearch.yml

選項設定 更新預設port 9200

sed -i '/http.port:/s/#//' /etc/elasticsearch/elasticsearch.yml

```

#### Cluster Discovery Settings

```
echo 'discovery.type: single-node' >> /etc/elasticsearch/elasticsearch.yml

```

#### 關閉 swapping
```
Disable Swapping

sed -i '/bootstrap.memory_lock:/s/^#//' /etc/elasticsearch/elasticsearch.yml

```

#### 設定 JVM 記憶體設定
```language
一般來說為總共記憶體的四分之一 預設為 4G

sed -i '/4g/s/^## //;s/4g/512m/' /etc/elasticsearch/jvm.options
OR
sed -i '/4g/s/^## //;s/4g/2g/' /etc/elasticsearch/jvm.options

```

#### 準備執行 Elasticsearch

```language
開機啟動
systemctl enable --now elasticsearch

確認服務

systemctl status elasticsearch

檢查服務 9200 是否正常

curl http://IP-Address:9200
or
curl http://127.0.0.1:9200

```

## 安裝 Kibana

```language
apt install kibana -y

```

#### 修改預設port
```language
修改預設port (選項)
sed -i '/server.port:/s/^#//' /etc/kibana/kibana.yml 

修改 host ip 為192.168.58.25
sed -i '/server.host:/s/^#//;s/localhost/192.168.58.25/' /etc/kibana/kibana.yml

OR

sed -i '/server.host:/s/^#//;s/localhost/0.0.0.0/' /etc/kibana/kibana.yml

設定 elasticsearch 主機位置
# 找到 elasticsearch.hosts 將最前面的# 取消 並將 localhost 換成 192.168.58.25 (請設定你安裝elasticsearch IP 或是 VIP)
sed -i '/elasticsearch.hosts:/s/^#//;s/localhost/192.168.58.25/' /etc/kibana/kibana.yml



```
#### 啟動 kibana
```
開機啟動
systemctl enable --now kibana



```

## 安裝 Logstash

```language

apt install logstash -y

#安裝套件
sudo /usr/share/logstash/bin/logstash-plugin install logstash-filter-dns
sudo /usr/share/logstash/bin/logstash-plugin install logstash-filter-geoip
#############

# /usr/share/logstash/bin/logstash-plugin install logstash-codec-sflow
# /usr/share/logstash/bin/logstash-plugin update logstash-codec-netflow
# /usr/share/logstash/bin/logstash-plugin update logstash-input-udp
# /usr/share/logstash/bin/logstash-plugin update logstash-input-tcp
# /usr/share/logstash/bin/logstash-plugin update logstash-filter-dns
# /usr/share/logstash/bin/logstash-plugin update logstash-filter-geoip
# /usr/share/logstash/bin/logstash-plugin update logstash-filter-translate
```

## 安裝 Filebeat
主要輕量收集本機log
```language
apt install filebeat -y

用 Filebeat 收集 系統log

確認模組
filebeat modules list

模組目錄
ls -al /etc/filebeat/modules.d/

啟動模組指令

filebeat modules enable <name-of-module>

filebeat modules enable system

確認啟動指令

cat /etc/filebeat/modules.d/system.yml

```

##### 設定Filebeat 輸出
```language

vim /etc/filebeat/filebeat.yml

# ---------------------------- Elasticsearch Output ----------------------------
output.elasticsearch:
  # Array of hosts to connect to.
  hosts: ["192.168.58.25:9200"]

Filebeat Logging

新增下面幾行

logging.level: info
logging.to_files: true
logging.files:
  path: /var/log/filebeat
  name: filebeat
  keepfiles: 7
  permissions: 0644


##
filebeat test config

```
