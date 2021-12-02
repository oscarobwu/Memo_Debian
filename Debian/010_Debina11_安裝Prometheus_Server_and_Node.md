# 010_Debina11_安裝Prometheus_Server_and_Node

----

### 010_Debina11_安裝Prometheus_Server_and_Node.md

### Step 1: Create Prometheus system user / group

```
sudo groupadd --system prometheus
sudo useradd -s /sbin/nologin --system -g prometheus prometheus
```

### Step 2: Create configuration and data directories

```
sudo mkdir /var/lib/prometheus
for i in rules rules.d files_sd; do sudo mkdir -p /etc/prometheus/${i}; done
```

### Step 3: Download and Install Prometheus onDebian 11

```
sudo apt-get update
sudo apt-get -y install wget curl
mkdir -p /tmp/prometheus && cd /tmp/prometheus
curl -s https://api.github.com/repos/prometheus/prometheus/releases/latest|grep browser_download_url|grep linux-amd64|cut -d '"' -f 4|wget -qi -

解壓縮檔案

tar xvf prometheus*.tar.gz
cd prometheus*/

# Move the prometheus binary files to /usr/local/bin/
# 移動 prometheus 到 /usr/local/bin/

sudo mv prometheus promtool /usr/local/bin/

sudo mv prometheus.yml  /etc/prometheus/prometheus.yml

sudo mv consoles/ console_libraries/ /etc/prometheus/
cd ~/
rm -rf /tmp/prometheus

```

### Step 4: Create/Edit a Prometheus configuration file.

```
cat /etc/prometheus/prometheus.yml

# 應該會列出以下相關資訊

# my global config
global:
  scrape_interval:     15s # Set the scrape interval to every 15 seconds. Default is every 1 minute.
  evaluation_interval: 15s # Evaluate rules every 15 seconds. The default is every 1 minute.
  # scrape_timeout is set to the global default (10s).

# Alertmanager configuration
alerting:
  alertmanagers:
  - static_configs:
    - targets:
      # - alertmanager:9093

# Load rules once and periodically evaluate them according to the global 'evaluation_interval'.
rule_files:
  # - "first_rules.yml"
  # - "second_rules.yml"

# A scrape configuration containing exactly one endpoint to scrape:
# Here it's Prometheus itself.
scrape_configs:
  # The job name is added as a label `job=<job_name>` to any timeseries scraped from this config.
  - job_name: 'prometheus'

    # metrics_path defaults to '/metrics'
    # scheme defaults to 'http'.

    static_configs:
    - targets: ['localhost:9090']
	
###


```

### Step 5: Create a Prometheus systemd Service unit file

```
# 建立起動檔案指令
# 調整資料保存 90 天
#
sudo tee /etc/systemd/system/prometheus.service<<EOF
[Unit]
Description=Prometheus
Documentation=https://prometheus.io/docs/introduction/overview/
Wants=network-online.target
After=network-online.target

[Service]
Type=simple
User=prometheus
Group=prometheus
ExecReload=/bin/kill -HUP $MAINPID
ExecStart=/usr/local/bin/prometheus \
  --config.file=/etc/prometheus/prometheus.yml \
  --storage.tsdb.path=/var/lib/prometheus \
  --storage.tsdb.retention.time=30d \
  --web.console.templates=/etc/prometheus/consoles \
  --web.console.libraries=/etc/prometheus/console_libraries \
  --web.listen-address=0.0.0.0:9090 \
  --web.external-url=

SyslogIdentifier=prometheus
Restart=always

[Install]
WantedBy=multi-user.target
EOF

###
# 調整檔案權限

for i in rules rules.d files_sd; do sudo chown -R prometheus:prometheus /etc/prometheus/${i}; done
for i in rules rules.d files_sd; do sudo chmod -R 775 /etc/prometheus/${i}; done
sudo chown -R prometheus:prometheus /var/lib/prometheus/

###
# 重新載入 啟動 daemon

sudo systemctl daemon-reload
sudo systemctl start prometheus
sudo systemctl enable prometheus

###
# 確認啟動服務狀態

$ systemctl status prometheus


```

### Step 6: Install node_exporter onDebian 11

```
cd ~\

curl -s https://api.github.com/repos/prometheus/node_exporter/releases/latest| grep browser_download_url|grep linux-amd64|cut -d '"' -f 4|wget -qi -

tar -xvf node_exporter*.tar.gz
cd  node_exporter*/
sudo cp node_exporter /usr/local/bin

###
# 確認設定

$ node_exporter --version

###
# 建立起動檔案

sudo tee /etc/systemd/system/node_exporter.service <<EOF
[Unit]
Description=Node Exporter
Wants=network-online.target
After=network-online.target

[Service]
User=prometheus
ExecStart=/usr/local/bin/node_exporter

[Install]
WantedBy=default.target
EOF


###
# 重新載入服務

sudo systemctl daemon-reload
sudo systemctl start node_exporter
sudo systemctl enable node_exporter

###
# 確認服務

$ systemctl status node_exporter.service

###
# 編輯設定檔將 node 加入服務

sudo vim /etc/prometheus/prometheus.yml

# 新增 node 服務
scrape_configs:
  # The job name is added as a label `job=<job_name>` to any timeseries scraped from this config.
  - job_name: "prometheus"
    static_configs:
      - targets: ["localhost:9090"]
  - job_name: 'node_exporter'
    static_configs:
      - targets: ['localhost:9100']



###
# 重啟 Prometheus

sudo systemctl restart prometheus

```
