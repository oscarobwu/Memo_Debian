# 安裝 Prometheus 中的 SNMP_exporter 監控

----

```
20220524

wget https://github.com/prometheus/snmp_exporter/releases/download/v0.20.0/snmp_exporter-0.20.0.linux-amd64.tar.gz

tar xzf snmp_exporter-0.20.0.linux-amd64.tar.gz

cd snmp_exporter-0.20.0.linux-amd64
ls -lh
cp ./snmp_exporter /usr/local/bin/snmp_exporter
cp ./snmp.yml /usr/local/bin/snmp.yml
cd /usr/local/bin/


./snmp_exporter -h

sudo useradd --system prometheus

sudo vi /etc/systemd/system/snmp-exporter.service

# 加入下面文字
# 預設 9116 port 
# 可以使用 --web.listen-address=:9116 改變port 號
# ExecStart=/usr/local/bin/snmp_exporter --config.file="/usr/local/bin/snmp.yml" --web.listen-address=:9116
[Unit]
Description=Prometheus SNMP Exporter Service
After=network.target

[Service]
Type=simple
User=prometheus
ExecStart=/usr/local/bin/snmp_exporter --config.file="/usr/local/bin/snmp.yml" --web.listen-address=:9116

[Install]
WantedBy=multi-user.target
###########################

systemctl daemon-reload
sudo service snmp-exporter start
sudo service snmp-exporter status

sudo systemctl enable snmp-exporter.service


## 修改 prometheus 主機的 prometheus.yml


sudo vi /etc/prometheus/prometheus.yml

...
  - job_name: snmp
    metrics_path: /snmp
    params:
      module: [if_mib]
    static_configs:
      - targets:
        - 127.0.0.1
    relabel_configs:
      - source_labels: [__address__]
        target_label: __param_target
      - source_labels: [__param_target]
        target_label: instance
      - target_label: __address__
        replacement: 127.0.0.1:9116  # URL as shown on the UI
        
##############################
  - job_name: NET-MikroTik
    static_configs:
      - targets: 
          - 192.168.88.1  # 思科交换机的 IP 地址
    metrics_path: /snmp
    params:
      module: 
        - if_mib  # 如果是其他设备，请更换其他模块。
      community:
        - xxxxxx  #  指定 community，当 snmp_exporter snmp.yml 配置文件没有指定 community，此处定义的 community 生效。
    relabel_configs:
      - source_labels: [__address__]
        target_label: __param_target
      - source_labels: [__param_target]
        target_label: instance
      - target_label: __address__
        replacement: 192.168.88.106:9116  # SNMP Exporter  的地址和端口

  - job_name: 'Mikrotik'
    static_configs:
      - targets:
        - 192.168.88.1  # SNMP device IP.
    metrics_path: /snmp
    params:
      module: [mikrotik]
    relabel_configs:
      - source_labels: [__address__]
        target_label: __param_target
      - source_labels: [__param_target]
        target_label: instance
      - target_label: __address__
        replacement: 192.168.88.106:9116  # SNMP Exporter  的地址和端口


################################
        
promtool check config /etc/prometheus/prometheus.yml


sudo service prometheus restart
sudo service prometheus status

```

# 安裝 SNMP Exporter Config Generator
```
#############################
# On the new server, install the dependencies that the SNMP Exporter Config Generator will need.

sudo apt update

sudo apt install unzip build-essential libsnmp-dev p7zip-full 

# Install the go compiler

apt install golang-go 

#Test that go is installed.

go version
# Download and build the exporter


go get github.com/prometheus/snmp_exporter/generator


#CD into the folder where the downloaded repository was saved.
cd ${GOPATH-$HOME/go}/src/github.com/prometheus/snmp_exporter/generator


#View the contents of the folder
ls -lh
# Build the project using go
go build

#There should be a new file named generator with execute permissions.

...
-rwxr-xr-x 1 root root 7.3M Nov  5 06:53 generator
...


#Now run
make mibs

#It will download many MIBs files and place them into a folder named mibs

...
drwxr-xr-x 3 root root 4.0K Nov  5 07:20 mibs
...
#We now need to create a variable that bash can use that points to the new mibs folder


export MIBDIRS=mibs
#Now we can test that generating a new snmp.yml works.
# 要修改 generate.yml 建立新的snmp exp
./generator generate
#In the ./generator folder there will be a new file called snmp.yml. We can now replace the existing snmp.yml on the server running SNMP Exporter with this new snmp.yml file if we wanted to.
```
