# 安裝 Promtail, Grafana and Loki for free Log Management in Debian 11

### 015_安裝_Promtail_Grafana_loki.md
```



                   +------------------------------------------------+
                   |                                                |
                   |   +---------+                         server   |
+--------+ 514/udp |   |         |                                  |
| client +------------->         |                                  |
+--------+         |   |         |                                  |
                   |   | rsyslog |                                  |
+--------+ 514/udp |   |         |                                  |
| client +------------->         |                                  |
+--------+         |   |         |                                  |
                   |   |         |                                  |
+--------+ 514/udp |   |         |                                  |
| client +------------->         |                                  |
+--------+         |   |         |                                  |
                   |   |         |                                  |
                   |   +----+----+                                  |
                   |        |                                       |
                   |        | 5514/tcp                              |
                   |        |                                       |
                   |   +----v-----+  +----------+  +------------+   |
                   |   | promtail +-->   loki   +-->  grafana   |   |
                   |   +----------+  +----------+  +------------+   |
                   |                                                |
                   +------------------------------------------------+




```


# Install Grafana Loki

```
#建立目錄
sudo mkdir /etc/loki
sudo mkdir -p /data/loki

curl -O -L "https://github.com/grafana/loki/releases/download/v2.5.0/loki-linux-amd64.zip"

unzip loki-linux-amd64.zip
unzip loki-linux-amd64.zip

sudo mv loki-linux-amd64 /usr/local/bin/

# 編輯檔案
vi /etc/loki/config-loki.yml

######################################################################
# 新增以下內容

auth_enabled: false

server:
  http_listen_port: 3100
  grpc_listen_port: 9096
  #grpc_listen_port :  39095 #grpc listening port, default is 9095
  grpc_server_max_recv_msg_size :  15728640   #grpc maximum received message value, default 4m
  grpc_server_max_send_msg_size :  15728640   #grpc maximum send message value, default 4m

common:
  path_prefix: /data/loki
  storage:
    filesystem:
      chunks_directory: /data/loki/chunks
      rules_directory: /data/loki/rules
  replication_factor: 1
  ring:
    instance_addr: 127.0.0.1
    kvstore:
      store: inmemory

schema_config:
  configs:
    - from: 2020-10-24
      store: boltdb-shipper
      object_store: filesystem
      schema: v11
      index:
        prefix: index_
        period: 24h

storage_config:
  boltdb:
    directory: /data/loki/index   #索引檔案儲存地址

  filesystem:
    directory: /data/loki/chunks  #塊儲存地址

limits_config:
  enforce_metric_name: false
  reject_old_samples: true
  reject_old_samples_max_age: 168h
  ingestion_rate_mb :  30   #Modify the intake rate limit per user, which is the sample size per second, the default value is 4M
  ingestion_burst_size_mb :  15   #Modify the intake rate limit per user, which is the sample size per second , The default value is 6M


chunk_store_config:
  # 最大可查詢歷史日期 90天
  max_look_back_period: 2160h

# 表的保留期90天
table_manager:
  retention_deletes_enabled: true
  retention_period: 2160h

######################################################################

################################################


sudo tee /etc/systemd/system/loki.service<<EOF
[Unit]
Description=Loki service
After=network.target

[Service]
Type=simple
User=root

ExecStart=/usr/local/bin/loki-linux-amd64 -config.file /etc/loki/config-loki.yml

[Install]
WantedBy=multi-user.target
EOF

#
sudo systemctl daemon-reload
sudo systemctl start loki


```

# Installing Promtail Agent

```

curl -LO https://github.com/grafana/loki/releases/download/v2.5.0/promtail-linux-amd64.zip

unzip promtail-linux-amd64.zip
sudo mv promtail-linux-amd64 /usr/local/bin/promtail

$ promtail --version

# 新增以下內容
# Add this content to the file:

server:
  http_listen_port: 9080
  grpc_listen_port: 0
positions:
  filename: /tmp/positions.yaml
clients:
  - url: http://loki:3100/loki/api/v1/push
scrape_configs:
  
  - job_name: syslog
    syslog:
      listen_address: 0.0.0.0:5514
      idle_timeout: 30s
      label_structured_data: yes
      labels:
        job: "syslog"
    relabel_configs:
      - source_labels: ['__syslog_message_hostname']
        target_label: 'host'
      - source_labels: ["__syslog_connection_ip_address"]
        target_label: "ip_address"
      - source_labels: ["__syslog_message_severity"]
        target_label: "severity"
      - source_labels: ["__syslog_message_app_name"]
        target_label: "app_name"
      - source_labels: ["__syslog_message_facility"]
        target_label: "facility"
		

###################
sudo tee /etc/systemd/system/promtail.service<<EOF
[Unit]
Description=Promtail service
After=network.target

[Service]
Type=simple
User=root
ExecStart=/usr/local/bin/promtail -config.file /etc/promtail/config.yaml

[Install]
WantedBy=multi-user.target
EOF


sudo systemctl daemon-reload
sudo systemctl start promtail.service

$ systemctl status promtail


```
