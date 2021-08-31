#安裝
----
如何在 Debian 10 (Buster) 上安裝 Prometheus 和 node_exporter

如何在 Debian 10 (Buster) Linux 上安裝 Prometheus？。Prometheus 是一個免費的開源監控系統，

可讓您從任何目標系統收集時間序列數據指標。其 Web 界面使您能夠執行強大的查詢、可視化收集的數據以及配置警報。

在本指南中，我們將介紹在 Debian 10 (Buster) 上安裝 Prometheus 和 node_exporter。由於這是一種二進制安裝方法，因此不需要依賴項來繼續安裝。


第一步：創建 Prometheus 系統用戶/組
我們將創建一個專用的 Prometheus 系統用戶和組。該  -r 或 -系統 選項用於此目的。

```
sudo groupadd --system prometheus
sudo useradd -s /sbin/nologin --system -g prometheus prometheus
```

這會創建一個不需要 /bin/bash shell 的系統用戶，這就是我們使用 -s /sbin/nologin 的原因

步驟 2：創建配置和數據目錄

Prometheus 需要目錄來存儲數據和配置文件。使用以下命令創建所有必需的目錄。

```
sudo mkdir /var/lib/prometheus
for i in rules rules.d files_sd; do sudo mkdir -p /etc/prometheus/${i}; done
```

第 3 步：在 Debian 10 (Buster) 上下載並安裝 Prometheus
讓我們下載最新版本的 Prometheus 存檔並將其解壓縮以獲取二進製文件。您可以從 Prometheus 發布 Github 頁面查看發布。

```
sudo apt-get -y install wget
mkdir -p /tmp/prometheus && cd /tmp/prometheus
curl -s https://api.github.com/repos/prometheus/prometheus/releases/latest \
  | grep browser_download_url \
  | grep linux-amd64 \
  | cut -d '"' -f 4 \
  | wget -qi -

```

```
tar xvf prometheus*.tar.gz
cd prometheus*/


sudo mv prometheus promtool /usr/local/bin/

sudo mv prometheus.yml  /etc/prometheus/prometheus.yml

sudo mv consoles/ console_libraries/ /etc/prometheus/
cd ~/
rm -rf /tmp/prometheus

sudo vim /etc/prometheus/prometheus.yml

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

```

將 prometheus 二進製文件移動到 /usr/local/bin/
由於 /usr/local/bin/ 在您的 PATH 中，讓我們將二進製文件複製到其中。


Step 5: Create a Prometheus systemd Service unit file

```
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
```

Change directory permissions.

```
for i in rules rules.d files_sd; do sudo chown -R prometheus:prometheus /etc/prometheus/${i}; done
for i in rules rules.d files_sd; do sudo chmod -R 775 /etc/prometheus/${i}; done
sudo chown -R prometheus:prometheus /var/lib/prometheus/
```

Reload systemd daemon and start the service.

```
sudo systemctl daemon-reload
sudo systemctl start prometheus
sudo systemctl enable prometheus

#Confirm that the service is running.

systemctl status prometheus

```

第 6 步：在 Debian 10 Buster 上安裝 node_exporter

```
curl -s https://api.github.com/repos/prometheus/node_exporter/releases/latest \
| grep browser_download_url \
| grep linux-amd64 \
| cut -d '"' -f 4 \
| wget -qi -

tar -xvf node_exporter*.tar.gz
cd  node_exporter*/
sudo cp node_exporter /usr/local/bin

$ node_exporter --version 
node_exporter, version 0.18.1 (branch: HEAD, revision: 3db77732e925c08f675d7404a8c46466b2ece83e) 
  build user:        root@b50852a1acba 
  build date: 2019.62.15
  版本：4:1906:15


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

sudo systemctl daemon-reload
sudo systemctl start node_exporter
sudo systemctl enable node_exporter

$  systemctl status node_exporter.service 
● node_exporter.service - Node Exporter
   Loaded: loaded (/etc/systemd/system/node_exporter.service; enabled; vendor preset: enabled)
   Active: active (running) since Wed 2019-08-21 23:41:11 CEST; 8s ago
 Main PID: 22879 (node_exporter)
    Tasks: 6 (limit: 4585)
   Memory: 6.6M
   CGroup: /system.slice/node_exporter.service
           └─22879 /usr/local/bin/node_exporter
.................................................



```

一旦我們確認服務正在運行，讓我們將 node_exporter 添加到 Prometheus 服務器。

在scrape_config部分下添加新工作。

```
- job_name: 'node_exporter'
    static_configs:
      - targets: ['localhost:9100']
```


sudo systemctl restart prometheus

