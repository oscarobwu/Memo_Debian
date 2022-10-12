# 026_How_to_install_InfluxDB_2_4_OSS_on_Debian_11.md


### 
```

sudo apt -y update

wget -qO- https://repos.influxdata.com/influxdb.key | gpg --dearmor | sudo tee /etc/apt/trusted.gpg.d/influxdb.gpg > /dev/null

export DISTRIB_ID=$(lsb_release -si); export DISTRIB_CODENAME=$(lsb_release -sc)
echo "deb [signed-by=/etc/apt/trusted.gpg.d/influxdb.gpg] https://repos.influxdata.com/${DISTRIB_ID,,} ${DISTRIB_CODENAME} stable" | sudo tee /etc/apt/sources.list.d/influxdb.list > /dev/null


sudo apt update && sudo apt install influxdb2

# Confirm

$ apt-cache policy influxdb2

```


### 3. Starting and enabling the InfluxDB Service


```

sudo systemctl start influxdb

sudo systemctl enable influxdb

sudo systemctl status influxdb

```

### 4. Installing InfluxDB v2 cli

```

sudo apt install -y influxdb2-cli


確認

apt-cache policy influxdb2-cli


$ influx version

$ influx ping

```

### 5. Set up InfluxDB

```


$ influx setup

influx setup
> Welcome to InfluxDB 2.0!
? Please type your primary username administrator
? Please type your password ***********
? Please type your password again ***********
? Please type your primary organization name Oscarmon
? Please type your primary bucket name telegraf
? Please type your retention period in hours, or 0 for infinite 0
? Setup with these parameters?
  Username:          administrator
  Organization:      Oscarmon
  Bucket:            telegraf
  Retention Period:  infinite
 Yes
User            Organization    Bucket
administrator   Oscarmon        telegraf



$ influx config list
Active  Name    URL                     Org
*       default http://localhost:8086   Oscarmon



$ influx auth list


```


### 6. Common operations on InfluxDB

```
建立組織

$ influx org create -n citizix

$ influx org create -n f5monitor

建立 bucket 及資料庫
120天 = 2880小時

$ influx bucket create --org citizix --name telegraf --retention 4380h
# 建立不同資料bucket
$ influx bucket create --org f5monitor --name f5device --retention 2880h

$ influx bucket create --org f5monitor --name f5deviceltm --retention 2880h

# 
$ influx bucket delete --org f5monitor --name f5device 

# 查詢方式 (可以找出 ID 和名稱
$ influx bucket list -o f5monitor -name f5device

$ influx bucket find --org f5monitor

$ influx bucket list --org f5monitor

建立user 

$ influx user create --name telegraf --org citizix --password SecretP4ss!

$ influx user create --name f5monitoruser --org f5monitor --password SecretP4ss!

# 建立user 及給權限
# 
$ influx auth create --user telegraf --read-buckets --write-buckets

# 刪除使用者
$ influx auth delete --user f5monitoruser --read-buckets --write-buckets

# 不帶 bucket id
$ influx auth create -o f5monitor --user f5monitoruser --read-buckets --write-buckets 

# 帶有 bucket ID 
$ influx auth create -o f5monitor --user f5monitoruser --read-bucket 66bb98d01f575793 --write-bucket 66bb98d01f575793

$ influx auth create -o f5monitor --user f5monitoruser --read-bucket 58a35db4f682526e --write-bucket 58a35db4f682526e

# influx auth create -o f5monitor --user f5monitoruser --read-bucket 58a35db4f682526e --write-bucket 58a35db4f682526e
ID                      Description     Token                                                                                           User Name       User ID                 Permissions
0a1eb06be7331000                        wT4fi8XhWaeUQ1mwYhgr1ne_2wy8wmTOP9IgebGGr78nJrBCuXcWQD395J0bGsXRSvabFba8FhQ2C4TzITIeRA==        f5monitoruser   0a1e8aa605731000        [read:orgs/b00f9b8db4dd334d/buckets/58a35db4f682526e write:orgs/b00f9b8db4dd334d/buckets/58a35db4f682526e]

14b07b1486a82b9b
# influx auth create -o f5monitor --user f5monitoruser --read-bucket 14b07b1486a82b9b --write-bucket 14b07b1486a82b9b

# 查詢 user 
$ influx auth list

ID                      Description             Token                                                                                           User Name       User ID                 Permissions
0a1e8c1803331000                                YhR9aO7xvfa9EjCYgeXKHatY-rY9ZGxS1IFTo1YEn96Ylny8kH7YL-8uOaDiF18zY_NNyjylLyiBWO2wANxIbA==        f5monitoruser   0a1e8aa605731000        [read:orgs/b00f9b8db4dd334d/buckets/66bb98d01f575793 write:orgs/b00f9b8db4dd334d/buckets/66bb98d01f575793]

#############################
# 重建 bucket
# 刪除原本的 組織內的 bucket
$ influx bucket delete --org f5monitor --name f5device 
# 建立新的 同資料 bucket
$ influx bucket create --org f5monitor --name f5device --retention 2880h
# 查詢建立後 bucket 的 ID 
$ influx bucket list --org f5monitor
# 授權於 f5monitoruser 帶有 bucket ID 
# influx auth create -o f5monitor --user f5monitoruser --read-bucket 58a35db4f682526e --write-bucket 58a35db4f682526e

設定 
http://localhost:8086

關閉 Basic auth

# Syntax
influx auth create -o <org-name> [permission-flags]

# Example
influx auth create -o my-org </br>
  --read-buckets 03a2bbf46309a000 03ace3a87c269000 \
  --read-dashboards \
  --read-tasks \
  --read-telegrafs \
  --read-user

ID                      Name
aa085f39e8a84ff4        Oscarmon
b00f9b8db4dd334d        f5monitor


对特定bucket具有读写访问权限

对于bucket id为0000000000000001  0000000000000002具有读写bucket的权限。

influx auth create \

  --read-bucket 0000000000000001 \

  --read-bucket 0000000000000002 \

  --write-bucket 0000000000000001 \

  --write-bucket 0000000000000002

```

#### 

```
# influxdb.key GPG Fingerprint: 05CE15085FC09D18E99EFB22684A14CF2582E0C5
wget -q https://repos.influxdata.com/influxdb.key
echo '23a1c8836f0afc5ed24e0486339d7cc8f6790b83886c4c96995b88a061c5bb5d influxdb.key' | sha256sum -c && cat influxdb.key | gpg --dearmor | sudo tee /etc/apt/trusted.gpg.d/influxdb.gpg > /dev/null
echo 'deb [signed-by=/etc/apt/trusted.gpg.d/influxdb.gpg] https://repos.influxdata.com/debian stable main' | sudo tee /etc/apt/sources.list.d/influxdata.list
sudo apt-get update && sudo apt-get install telegraf

OR

curl -LO -C - https://dl.influxdata.com/telegraf/releases/telegraf_1.24.1-1_amd64.deb

sudo dpkg -i telegraf_1.24.1-1_amd64.deb


vi  /etc/telegraf/telegraf.conf

[outputs.influxdb_v2]
   urls = ["http://127.0.0.1:8086"]
   ## Token for authentication.
   token = "YhR9aO7xvfa9EjCYgeXKHatY-rY9ZGxS1IFTo1YEn96Ylny8kH7YL-8uOaDiF18zY_NNyjylLyiBWO2wANxIbA=="
   ## Organization is the name of the organization you wish to write to; must exist.
   organization = "f5monitor"
   ## Destination bucket to write into.
   bucket = "f5device"




[outputs.influxdb_v2]
   urls = ["http://192.168.88.131:8086"]
   ## Token for authentication.
   token = "YhR9aO7xvfa9EjCYgeXKHatY-rY9ZGxS1IFTo1YEn96Ylny8kH7YL-8uOaDiF18zY_NNyjylLyiBWO2wANxIbA=="
   ## Organization is the name of the organization you wish to write to; must exist.
   organization = "f5monitor"
   ## Destination bucket to write into.
   bucket = "f5device"





################################
# 過濾資料後拋到資料庫
[[outputs.influxdb_v2]]
   urls = ["http://127.0.0.1:8086"]
   token = ""
   organization = "demo"
   bucket = "device_a"
   namepass= ["devicea_*"]
[[outputs.influxdb_v2]]
   urls = ["http://127.0.0.1:8086"]
   token = ""
   organization = "demo
   bucket = "device_b"
   namepass= ["deviceb_*"]
[[inputs.modbus]]
  name_prefix = "devicea_"
  name = "Kompressor 1"
  slave_id = 1
  timeout = "1s"
  controller = "tcp://172.16.0.11:502"
  holding_registers = [
    { name = "Total Leistung", byte_order = "ABCD",   data_type = "FLOAT32-IEEE", scale=1.0,  address = [121,122]},
    { name = "L1 Leistung", byte_order = "ABCD",   data_type = "FLOAT32-IEEE", scale=1.0,  address = [35,36]},
    { name = "L2 Leistung", byte_order = "ABCD",   data_type = "FLOAT32-IEEE", scale=1.0,  address = [37,38]},
    { name = "L3 Leistung", byte_order = "ABCD",   data_type = "FLOAT32-IEEE", scale=1.0,  address = [39,40]},
    { name = "Temperatur", byte_order = "ABCD",   data_type = "FLOAT32-IEEE", scale=1.0,  address = [73,74]},
  ]
[[inputs.modbus]]
  name_prefix = "deviceb_"
  name = "Kompressor 2"
  slave_id = 1
  timeout = "1s"
  controller = "tcp://172.16.0.12:502"
  holding_registers = [
    { name = "Total Leistung", byte_order = "ABCD",   data_type = "FLOAT32-IEEE", scale=1.0,  address = [121,122]},
    { name = "L1 Leistung", byte_order = "ABCD",   data_type = "FLOAT32-IEEE", scale=1.0,  address = [35,36]},
    { name = "L2 Leistung", byte_order = "ABCD",   data_type = "FLOAT32-IEEE", scale=1.0,  address = [37,38]},
    { name = "L3 Leistung", byte_order = "ABCD",   data_type = "FLOAT32-IEEE", scale=1.0,  address = [39,40]},
    { name = "Temperatur", byte_order = "ABCD",   data_type = "FLOAT32-IEEE", scale=1.0,  address = [73,74]},
  ]


```


```
# 過濾資料後拋到資料庫
[[inputs.snmp]]
agents = [ "192.168.88.198" ]
version = 2
community = "public"
interval = "60s"
timeout = "10s"
retries = 3
name = "F5_system"
name_prefix = "devicefa_"

[[inputs.snmp.field]]
name = "hostname"
oid = "RFC1213-MIB::sysName.0"
is_tag = true

  [[inputs.snmp.field]]
    name = "F5_uptime"
    oid = "1.3.6.1.4.1.3375.2.1.6.6.0"
  [[inputs.snmp.field]]
    name = "F5_httpRequests"
    oid = "1.3.6.1.4.1.3375.2.1.1.2.1.56.0"
  [[inputs.snmp.field]]
    name = "F5_version"
    oid = "F5-BIGIP-SYSTEM-MIB::sysProductVersion.0"
  [[inputs.snmp.field]]
    name = "F5_Platform"
    oid = "1.3.6.1.2.1.1.1.0"


[[inputs.snmp.table]]
name = "F5_PoolStatus"
oid = "F5-BIGIP-LOCAL-MIB::ltmPoolStatTable"
inherit_tags = [ "hostname" ]

[[inputs.snmp.table]]
name = "F5_ClientSSLStatus"
oid = "F5-BIGIP-LOCAL-MIB::ltmClientSslStatTable"
inherit_tags = [ "hostname" ]

[[inputs.snmp.table]]
name = "F5_CPU"
oid = "F5-BIGIP-SYSTEM-MIB::sysCpuTable"
inherit_tags = [ "hostname" ]

[[inputs.snmp.table]]
name = "F5_Fan"
oid = "F5-BIGIP-SYSTEM-MIB::sysChassisFanTable"
inherit_tags = [ "hostname" ]

[[inputs.snmp.table]]
name = "F5_Temperature"
oid = "F5-BIGIP-SYSTEM-MIB::sysChassisTempTable"
inherit_tags = [ "hostname" ]

# Output Plugin InfluxDB
[[outputs.influxdb]]
    urls = [ "http://127.0.0.1:8086" ]
    skip_database_creation = true
    database = "telegraf_db"
    username = "telegraf_user"
    password = "Str0ng_P@ssw0rd"
    retention_policy = ""

[outputs.influxdb_v2]
   ## filter data
   namepass= ["F5_*"]
   urls = ["http://192.168.88.131:8086"]
   ## Token for authentication.
   token = "_wNxrsBmknZ-bur-XawJaEWsOCQbpM-7S3J9_Hda1FWxmVZvhRc2wm8qtIg5r6ayVvM-GUj7_HCD_pHXlpUL6Q=="
   ## Organization is the name of the organization you wish to write to; must exist.
   organization = "f5monitor"
   ## Destination bucket to write into.
   bucket = "f5device"




from(bucket: "f5device")
  |> range(start: v.timeRangeStart, stop: v.timeRangeStop)
  |> filter(fn: (r) => r["_measurement"] == "F5_system")
  |> filter(fn: (r) => r["_field"] == "F5_version")
  |> filter(fn: (r) => r["hostname"] == "lab1.local.com")
  |> aggregateWindow(every: v.windowPeriod, fn: last, createEmpty: false)
  |> yield(name: "last")

```
