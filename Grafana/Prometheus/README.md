# Install Prometheus on Debian 安裝

## install gosu
_just to not wrestle with sudo and exec_
```bash
$ sudo apt update
$ sudo apt install gosu
```

## Create prometheus user
```bash
$ sudo useradd -M prometheus
$ sudo usermod -L prometheus
```

## Make scaffolding
```bash
$ sudo sudo mkdir /etc/prometheus
$ sudo sudo mkdir /var/lib/prometheus
$ sudo chown prometheus:prometheus /etc/prometheus
$ sudo chown prometheus:prometheus /var/lib/prometheus
```

## Download prometheus
```bash
$ cd ~
$ wget https://github.com/prometheus/prometheus/releases/download/v2.0.0/prometheus-2.0.0.linux-amd64.tar.gz
$ sha256sum prometheus-2.0.0.linux-amd64.tar.gz 
e12917b25b32980daee0e9cf879d9ec197e2893924bd1574604eb0f550034d46  prometheus-2.0.0.linux-amd64.tar.gz
$ tar xvf prometheus-2.0.0.linux-amd64.tar.gz
安裝最新版 2.27.1 / 2021-05-18
# VER=2.27.1
# wget https://github.com/prometheus/prometheus/releases/download/v$VER/prometheus-$VER.linux-amd64.tar.gz
# tar xzf prometheus-$VER.linux-amd64.tar.gz
```

## Put bits of prometheus in places
```bash
$ sudo cp prometheus-2.0.0.linux-amd64/prometheus /usr/local/bin/
$ sudo cp prometheus-2.0.0.linux-amd64/promtool /usr/local/bin/

$ sudo cp prometheus-$VER.linux-amd64/prometheus /usr/local/bin/
$ sudo cp prometheus-$VER.linux-amd64/promtool /usr/local/bin/

$ sudo chown prometheus:prometheus /usr/local/bin/prometheus
$ sudo chown prometheus:prometheus /usr/local/bin/promtool
```

```bash
$ sudo cp -r prometheus-2.0.0.linux-amd64/prometheus.yml /etc/prometheus/
$ sudo cp -r prometheus-2.0.0.linux-amd64/consoles /etc/prometheus
$ sudo cp -r prometheus-2.0.0.linux-amd64/console_libraries /etc/prometheus

$ sudo cp -r prometheus-$VER.linux-amd64/prometheus.yml /etc/prometheus/
$ sudo cp -r prometheus-$VER.linux-amd64/consoles /etc/prometheus
$ sudo cp -r prometheus-$VER.linux-amd64/console_libraries /etc/prometheus

$ sudo chown -R prometheus:prometheus /etc/prometheus/prometheus.yml
$ sudo chown -R prometheus:prometheus /etc/prometheus/consoles
$ sudo chown -R prometheus:prometheus /etc/prometheus/console_libraries
```

## Configure prometheus
```bash
$ sudo vi /etc/prometheus/prometheus.yml
```

```yaml
global:
  scrape_interval: 15s

scrape_configs:
  - job_name: 'prometheus'
    scrape_interval: 5s
    static_configs:
      - targets: ['localhost:9090']
```

## Test if prometheus runs
```bash
$ sudo -u prometheus /usr/local/bin/prometheus --config.file=/etc/prometheus/prometheus.yml --storage.tsdb.path=/var/lib/prometheus/data --web.console.templates=/etc/prometheus/consoles --web.console.libraries=/etc/prometheus/consoles_libraries 
...
level=info ts=2018-01-17T17:34:29.810960616Z caller=main.go:371 msg="Server is ready to receive requests."
```
_Ctrl+C_

## Add it as sysvinit service
See *prometheus.init.d* below
```bash
$ sudo cp prometheus.init.d /etc/init.d/prometheus
$ sudo chmod +x /etc/init.d/prometheus
$ sudo update-rc.d prometheus defaults
$ sudo service prometheus status
● prometheus.service - LSB: monitoring system and time series database.
   Loaded: loaded (/etc/init.d/prometheus)
   Active: inactive (dead)
$ sudo vi /etc/default/prometheus
START=yes
```

## Start and check
```bash
$ sudo service prometheus start
$ ps auxww | grep prometheus
$ cat /var/log/prometheus/prometheus.log
$ sudo service prometheus stop
```

## Download and install node_exporter
```bash
$ wget https://github.com/prometheus/node_exporter/releases/download/v0.15.2/node_exporter-0.15.2.linux-amd64.tar.gz
$ sha256sum node_exporter-0.15.2.linux-amd64.tar.gz 
1ce667467e442d1f7fbfa7de29a8ffc3a7a0c84d24d7c695cc88b29e0752df37  node_exporter-0.15.2.linux-amd64.tar.gz
$ tar xvf node_exporter-0.15.1.linux-amd64.tar.gz

$ sudo cp node_exporter-0.15.2.linux-amd64/node_exporter /usr/local/bin/
$ sudo chown prometheus:prometheus /usr/local/bin/node_exporter

$ node_exporter 最新 1.1.2 / 2021-03-05
curl -s https://api.github.com/repos/prometheus/node_exporter/releases/latest \
| grep browser_download_url \
| grep linux-amd64 \
| cut -d '"' -f 4 \
| wget -qi -

設定執行
#
tar -xvf node_exporter*.tar.gz
cd  node_exporter*/
sudo cp node_exporter /usr/local/bin
#
$ node_exporter --version
node_exporter, version x.xx.x (branch: HEAD, revision: 3db77732e925c08f675d7404a8c46466b2ece83e)
  build user:       root@b50852a1acba
  build date:       20190604-16:41:18
  go version:       go1.12.5
#
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
#
sudo systemctl daemon-reload
sudo systemctl start node_exporter
sudo systemctl enable node_exporter
確認服務
$  systemctl status node_exporter.service 

```

## Test if node_exporter runs
```bash
$ sudo -u prometheus /usr/local/bin/node_exporter --collector.loadavg --collector.meminfo --collector.filesystem
..
INFO[0000] Listening on :9100                            source="node_exporter.go:76"
```

## Add node_exporter as sysvinit service
See *promethnode_exportereus.init.d* below
```bash
$ sudo cp node_exporter.init.d /etc/init.d/node_exporter
$ sudo chmod +x /etc/init.d/node_exporter
$ sudo update-rc.d node_exporter defaults
$ sudo service node_exporter status

$ sudo vi /etc/default/node_exporter
START=yes
```

## Start and check
```bash
$ sudo service node_exporter start
$ ps auxww | grep node_exporter
$ cat /var/log/prometheus/node_exporter.log
```

## Make prometheus to scrape node exporter
```bash
$ sudo vi /etc/prometheus/prometheus.yml
```
```yaml
global:
  scrape_interval: 15s

scrape_configs:
  - job_name: 'prometheus'
    scrape_interval: 5s
    static_configs:
      - targets: ['localhost:9090']
  - job_name: 'node_exporter'
    scrape_interval: 5s
    static_configs:
      - targets: ['localhost:9100']
```

```bash
$ sudo service prometheus start
```
## Download and install AlertManager
```bash
$ wget https://github.com/prometheus/node_exporter/releases/download/v0.15.2/node_exporter-0.15.2.linux-amd64.tar.gz
$ sha256sum node_exporter-0.15.2.linux-amd64.tar.gz 
1ce667467e442d1f7fbfa7de29a8ffc3a7a0c84d24d7c695cc88b29e0752df37  node_exporter-0.15.2.linux-amd64.tar.gz
$ tar xvf node_exporter-0.15.1.linux-amd64.tar.gz

$ sudo cp node_exporter-0.15.2.linux-amd64/node_exporter /usr/local/bin/
$ sudo chown prometheus:prometheus /usr/local/bin/node_exporter

$ 下載最新 0.22.2 / 2021-06-01
export VERSION=0.22.2
curl -LO https://github.com/prometheus/alertmanager/releases/download/v$VERSION/alertmanager-$VERSION.darwin-amd64.tar.gz
tar xvf alertmanager-$VERSION.darwin-amd64.tar.gz


```

## Further reading:

http://docs.grafana.org/installation/debian/
