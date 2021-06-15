```

安裝 設定 Prometheus and node_exporter

Step 1: 建立 Prometheus system user / group

sudo groupadd --system prometheus
sudo useradd -s /sbin/nologin --system -g prometheus prometheus

Step 2: 建立 configuration and data directories


sudo mkdir /var/lib/prometheus
for i in rules rules.d files_sd; do sudo mkdir -p /etc/prometheus/${i}; done

Step 3: 下載和安裝 Prometheus on Debian 10 (Buster)

確認是否已經安裝 wget
sudo apt-get -y install wget

建立暫時目錄
mkdir -p /tmp/prometheus && cd /tmp/prometheus

下在最新版本 prometheus
curl -s https://api.github.com/repos/prometheus/prometheus/releases/latest \
  | grep browser_download_url \
  | grep linux-amd64 \
  | cut -d '"' -f 4 \
  | wget -qi -

解壓縮檔案
tar xvf prometheus*.tar.gz
cd prometheus*/

移動 prometheus binary 檔案到 /usr/local/bin/


sudo mv prometheus promtool /usr/local/bin/

移動設定檔到
sudo mv prometheus.yml  /etc/prometheus/prometheus.yml

sudo mv consoles/ console_libraries/ /etc/prometheus/
cd ~/
rm -rf /tmp/prometheus


Step 4: Create/Edit a Prometheus configuration file.


sudo vim /etc/prometheus/prometheus.yml

設定檔內容如下 :

##########################################################
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

##############################

Step 5: Create a Prometheus systemd Service unit file

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
  --web.console.templates=/etc/prometheus/consoles \
  --web.console.libraries=/etc/prometheus/console_libraries \
  --web.listen-address=0.0.0.0:9090 \
  --web.external-url=

SyslogIdentifier=prometheus
Restart=always

[Install]
WantedBy=multi-user.target
EOF

設定權限

for i in rules rules.d files_sd; do sudo chown -R prometheus:prometheus /etc/prometheus/${i}; done
for i in rules rules.d files_sd; do sudo chmod -R 775 /etc/prometheus/${i}; done
sudo chown -R prometheus:prometheus /var/lib/prometheus/

啟動檔重新載入

sudo systemctl daemon-reload
sudo systemctl start prometheus
sudo systemctl enable prometheus

確認服務是否啟動
systemctl status prometheus

檢查 http://ip:9090 是否可以開啟

Step 6: Install node_exporter on Debian 10 Buster

下載

curl -s https://api.github.com/repos/prometheus/node_exporter/releases/latest \
| grep browser_download_url \
| grep linux-amd64 \
| cut -d '"' -f 4 \
| wget -qi -


解壓縮

tar -xvf node_exporter*.tar.gz
cd  node_exporter*/
sudo cp node_exporter /usr/local/bin

確認安裝

$ node_exporter --version
node_exporter, version 0.18.1 (branch: HEAD, revision: 3db77732e925c08f675d7404a8c46466b2ece83e)
  build user:       root@b50852a1acba
  build date:       20190604-16:41:18
  go version:       go1.12.5

建立起動服務檔

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

Reload systemd and start the service.

sudo systemctl daemon-reload
sudo systemctl start node_exporter
sudo systemctl enable node_exporter

# 確認狀態:
$  systemctl status node_exporter.service 

將node 新增到 prometheus

編輯 promethrus 設定檔

sudo vim /etc/prometheus.yml

Add new job under scrape_config section.
新增 jos 到 scrape_config 部分

- job_name: 'node_exporter'
    static_configs:
      - targets: ['localhost:9100']

重啟服務

sudo systemctl restart prometheus

```
