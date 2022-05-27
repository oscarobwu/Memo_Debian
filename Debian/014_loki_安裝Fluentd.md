##Grafana + Loki + Fluentd 當作syslog view

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
                   |   | fluentd  +-->   loki   +-->  grafana   |   |
                   |   +----------+  +----------+  +------------+   |
                   |                                                |
                   +------------------------------------------------+

```
## 安裝 Loki

```
#建立目錄
sudo mkdir /etc/loki
sudo mkdir -p /data/loki

curl -O -L "https://github.com/grafana/loki/releases/download/v2.5.0/loki-linux-amd64.zip"

unzip loki-linux-amd64.zip

# chmod a+x "loki-linux-amd64"

sudo mv loki-linux-amd64 promtool /usr/local/bin/

sudo vi /etc/loki/config-loki.yml
######################################################################
# 新增以下內容

auth_enabled: false

server:
  http_listen_port: 3100
  grpc_listen_port: 9096

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


chunk_store_config:
  # 最大可查詢歷史日期 90天
  max_look_back_period: 2160h

# 表的保留期90天
table_manager:
  retention_deletes_enabled: true
  retention_period: 2160h
#######################################################################

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

## 安裝 fluentd

# td-agent 4
curl -fsSL https://toolbelt.treasuredata.com/sh/install-debian-bullseye-td-agent4.sh | sh

sudo td-agent-gem install fluent-plugin-grafana-loki


$ sudo systemctl start td-agent.service
$ sudo systemctl status td-agent.service

sudo vi /etc/td-agent/td-agent.conf

##
<source>
  @type syslog
  @id syslog_in
  port 5514
  bind 0.0.0.0
  severity_key level
  facility_key facility
  tag syslog
  <transport tcp>
  </transport>
  <parse>
    message_format rfc5424
  </parse>
</source>

<match syslog.**>
  @type loki
  @id syslog_out
  url "http://localhost:3100"
  flush_interval 1s
  flush_at_shutdown true
  buffer_chunk_limit 1m
  extra_labels {"app":"syslog"}
  <label>
    pid
    host
    level
    facility
  </label>
</match>
##



##設定 rsyslog 拋送

vi /etc/rsyslog.d/50-forward.conf

$ModLoad imudp
$UDPServerRun 514

$ActionQueueType LinkedList # use asynchronous processing
$ActionQueueFileName srvrfwd # set file name, also enables disk mode
$ActionResumeRetryCount -1 # infinite retries on insert failure
$ActionQueueSaveOnShutdown on # save in-memory data if rsyslog shuts down

*.* @@127.0.0.1:5514;RSYSLOG_SyslogProtocol23Format

sudo systemctl restart rsyslog
sudo systemctl restart td-agent


開通防火牆

####

檢視grafana


###################################################################################
## 安裝Promtal

curl -LO https://github.com/grafana/loki/releases/download/v2.5.0/promtail-linux-amd64.zip

unzip promtail-linux-amd64.zip
sudo mv promtail-linux-amd64 /usr/local/bin/promtail

# 確認版本

$ promtail --version

sudo mkdir /etc/promtail
sudo mkdir -p /data/promtail

sudo vi /etc/promtail/config.yaml

server:
  http_listen_port: 9080
  grpc_listen_port: 0

positions:
  filename: /data/promtail/positions.yaml

clients:
  - url: http://127.0.0.1:3100/loki/api/v1/push

scrape_configs:
- job_name: system
  static_configs:
  - targets:
      - localhost
    labels:
      job: varlogs
      __path__: /var/log/*log
- job_name: grafanalogs
  static_configs:
  - targets:
      - localhost
    labels:
      job: grafana
      __path__: /var/log/grafana/grafana.log


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

開通本機防火牆
```
