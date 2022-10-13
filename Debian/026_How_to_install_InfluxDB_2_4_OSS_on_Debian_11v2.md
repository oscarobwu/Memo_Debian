# 026_How_to_install_InfluxDB_2_4_OSS_on_Debian_11v2.md


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


180天 = 4320h

182.5 天 = 4380h

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

# 建立user  and Str0ngPassw0rd

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
# influx auth create -o f5monitor --user f5monitoruser --read-bucket <更換ID> --write-bucket <更換ID>
#
# influx auth create -o f5monitor --user f5monitoruser --read-bucket 2d4de2e557308518 --write-bucket 2d4de2e557308518
#
# 出現存取 Token
# influx auth create -o f5monitor --user f5monitoruser --read-bucket d436d5eee35eb719 --write-bucket d436d5eee35eb719
ID                      Description     Token                                                                                           User Name       User ID                 Permissions
0a1ec861b6731000                        QjYfqp7J5QXiVsOsaLH-aj8T-8ZB96zBNQ7Z8br8zytM5_-WaSn6lw8N3Td6m7cZZ3dSb5wXgjenPqxhhhQEMw==        f5monitoruser   0a1e8aa605731000        [read:orgs/b00f9b8db4dd334d/buckets/2d4de2e557308518 write:orgs/b00f9b8db4dd334d/buckets/2d4de2e557308518]


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

#### 安裝 telegraf 

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
   namepass= ["Linuxa_*"]
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


###
輸出時候排除資料名稱
[[outputs.influxdb_v2]]
   urls = ["http://127.0.0.1:8086"]
   token = ""
   organization = "demo
   bucket = "device_b"
   namepass= ["deviceb_*"]
   namedrop = ["F5_*"]

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
	
############# For Virtual Server ######################
  [[inputs.snmp.table]]
    name = "F5_virtual_server"
#    oid = "F5-BIGIP-LOCAL-MIB::ltmVirtualServStatTable"
    inherit_tags = [ "hostname" ]

    [[inputs.snmp.table.field]]
      name = "ltmVirtualServStatName"
      oid = "F5-BIGIP-LOCAL-MIB::ltmVirtualServStatName"
      is_tag = true

    [[inputs.snmp.table.field]]
      name = "ltmVirtualServStatClientCurConns"
      oid = "F5-BIGIP-LOCAL-MIB::ltmVirtualServStatClientCurConns"
    [[inputs.snmp.table.field]]
      name = "ltmVirtualServStatClientBytesIn"
      oid = "F5-BIGIP-LOCAL-MIB::ltmVirtualServStatClientBytesIn"
    [[inputs.snmp.table.field]]
      name = "ltmVirtualServStatClientBytesOut"
      oid = "F5-BIGIP-LOCAL-MIB::ltmVirtualServStatClientBytesOut"


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
  token = "QjYfqp7J5QXiVsOsaLH-aj8T-8ZB96zBNQ7Z8br8zytM5_-WaSn6lw8N3Td6m7cZZ3dSb5wXgjenPqxhhhQEMw=="
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


```

#####################################################
#
# Check on status of snmp
#
#####################################################

[[inputs.snmp]]
  #name_prefix = "execf5_"
  #agents = [ "192.168.88.60", "xxx.xxx.xxx.xx2", "xxx.xxx.xxx.xx3" ]
  agents = [ "192.168.88.198" ]
  version = 2
  community = "public"
  interval = "60s"
  timeout = "15s"
  retries = 3
  name = "F5_system"

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
    name = "F5_client_connections"
    oid = "1.3.6.1.4.1.3375.2.1.1.2.1.8.0"
  [[inputs.snmp.field]]
    name = "F5_client_bytes_in"
    oid = "1.3.6.1.4.1.3375.2.1.1.2.1.60.0"
  [[inputs.snmp.field]]
    name = "F5_Total_Connections"
    oid = "1.3.6.1.4.1.3375.2.1.1.2.1.8.0"
  [[inputs.snmp.field]]
    name = "F5_New_Connects"
    oid = "1.3.6.1.4.1.3375.2.1.1.2.12.8.0"
  [[inputs.snmp.field]]
    name = "F5_New_Accepts"
    oid = "1.3.6.1.4.1.3375.2.1.1.2.12.6.0"
  [[inputs.snmp.field]]
    name = "F5_Temperature"
    oid = "1.3.6.1.4.1.3375.2.1.3.2.3.2.1.2.1"
  [[inputs.snmp.field]]
    name = "F5_Global_HTTP_Responses_2xx"
    oid = "F5-BIGIP-SYSTEM-MIB::sysHttpStatResp2xxCnt.0"
  [[inputs.snmp.field]]
    name = "F5_Global_HTTP_Responses_3xx"
    oid = "F5-BIGIP-SYSTEM-MIB::sysHttpStatResp3xxCnt.0"
  [[inputs.snmp.field]]
    name = "F5_Global_HTTP_Responses_4xx"
    oid = "F5-BIGIP-SYSTEM-MIB::sysHttpStatResp4xxCnt.0"
  [[inputs.snmp.field]]
    name = "F5_Global_HTTP_Responses_5xx"
    oid = "F5-BIGIP-SYSTEM-MIB::sysHttpStatResp5xxCnt.0"

  [[inputs.snmp.field]]
    name = "F5_Device_status"
    oid = "F5-BIGIP-SYSTEM-MIB::sysCmFailoverStatusId.0"

 [[inputs.snmp.field]]
    name = "F5_Synchronization_status_color"
    oid = "F5-BIGIP-SYSTEM-MIB::sysCmSyncStatusColor.0"

 [[inputs.snmp.table]]
    name = "F5_CPU"
    oid = "F5-BIGIP-SYSTEM-MIB::sysMultiHostCpuTable"
    inherit_tags = [ "hostname" ]

 [[inputs.snmp.table]]
    name = "F5_Memory_Usage"
    oid = "F5-BIGIP-SYSTEM-MIB::sysMultiHostTable"
    inherit_tags = [ "hostname" ]

 [[inputs.snmp.table]]
    name = "F5_TMM_Memory_Usage"
    oid = "F5-BIGIP-SYSTEM-MIB::sysTmmPagesStatTable"
    inherit_tags = [ "hostname" ]

 [[inputs.snmp.table]]
    name = "F5_Certificate_Status"
    oid =  "F5-BIGIP-SYSTEM-MIB::sysCertificateFileObjectTable"
    inherit_tags = [ "hostname" ]

 [[inputs.snmp.table]]
   name = "F5_PoolStatus"
   oid = "F5-BIGIP-LOCAL-MIB::ltmPoolStatTable"
   inherit_tags = [ "hostname" ]

 [[inputs.snmp.table]]
   name = "F5_ClientSSLStatus"
   oid = "F5-BIGIP-LOCAL-MIB::ltmClientSslStatTable"
   inherit_tags = [ "hostname" ]

 [[inputs.snmp.table]]
   name = "F5_Fan"
   oid = "F5-BIGIP-SYSTEM-MIB::sysChassisFanTable"
   inherit_tags = [ "hostname" ]

 [[inputs.snmp.table]]
   name = "F5_Temperature"
   oid = "F5-BIGIP-SYSTEM-MIB::sysChassisTempTable"
   inherit_tags = [ "hostname" ]

 [[inputs.snmp.table]]
   name = "F5_VirtualStatus"
   oid = "F5-BIGIP-LOCAL-MIB::ltmVirtualServStatTable"
   inherit_tags = [ "hostname" ]

 [[inputs.snmp.table]]
    name = "F5_Nodes_Status"
    oid =  "F5-BIGIP-LOCAL-MIB::ltmNodeAddrStatTable"
    inherit_tags = [ "hostname" ]

 [[inputs.snmp.table]]
    name = "F5_Vlan_Status"
    oid =  "F5-BIGIP-SYSTEM-MIB::sysVlanStatTable"
    inherit_tags = [ "hostname" ]

 [[inputs.snmp.table]]
    name = "F5_DiskTable_Status"
    oid =  "F5-BIGIP-SYSTEM-MIB:sysHostDiskTable"
    inherit_tags = [ "hostname" ]

 [[inputs.snmp.table]]
    name = "F5_PoolMembers_Status"
    oid =  "F5-BIGIP-LOCAL-MIB::ltmPoolMemberStatTable"
    inherit_tags = [ "hostname" ]

 [[inputs.snmp.table]]
    name = "F5_PoolUpDowm_Status"
    oid =  "F5-BIGIP-LOCAL-MIB::ltmPoolMemberTable"
    inherit_tags = [ "hostname" ]
###############################################################################
# SSLVPN APM module #
###############################################################################

  [[inputs.snmp.table]]
    name = "F5_APM_IP_List"
    oid = "F5-BIGIP-APM-MIB::apmLeasepoolStatTable"
    inherit_tags = [ "hostname" ]
  
  [[inputs.snmp.table]]
    name = "F5_APM_Pauser_List"
    oid = "F5-BIGIP-APM-MIB::apmPaStatTable"
    inherit_tags = [ "hostname" ]
  
  [[inputs.snmp.table]]
    name = "F5_APM_ACL_List"
    oid = "F5-BIGIP-APM-MIB::apmAclStatTable"
    inherit_tags = [ "hostname" ]

###############################################################################
# SSLVPN #
###############################################################################
  #####################################################
  #
  # Gather Interface Statistics via SNMP Start
  #
  #####################################################

  # IF-MIB::ifTable contains counters on input and output traffic as well as errors and discards.
  [[inputs.snmp.table]]
    name = "F5_interface"
    inherit_tags = [ "hostname" ]
    oid = "IF-MIB::ifTable"

    # Interface tag - used to identify interface in metrics database
    [[inputs.snmp.table.field]]
      name = "ifDescr"
      oid = "IF-MIB::ifDescr"
      is_tag = true

  # IF-MIB::ifXTable contains newer High Capacity (HC) counters that do not overflow as fast for a few of the ifTable counters
  [[inputs.snmp.table]]
    name = "F5_interface"
    inherit_tags = [ "hostname" ]
    oid = "IF-MIB::ifXTable"

    # Interface tag - used to identify interface in metrics database
    [[inputs.snmp.table.field]]
      name = "ifDescr"
      oid = "IF-MIB::ifDescr"
      is_tag = true

  # EtherLike-MIB::dot3StatsTable contains detailed ethernet-level information about what kind of errors have been logged on an interface (such as FCS error, frame too long, etc)
  [[inputs.snmp.table]]
    name = "F5_interface"
    inherit_tags = [ "hostname" ]
    oid = "EtherLike-MIB::dot3StatsTable"

    # Interface tag - used to identify interface in metrics database
    [[inputs.snmp.table.field]]
      name = "ifDescr"
      oid = "IF-MIB::ifDescr"
      is_tag = true


#####################################################
#
# Export Information to Prometheus
#
#####################################################
[[outputs.prometheus_client]]
  listen = ":9301"
  #metric_version = 2

#####################################################
#
# Export Information to influxdb_v2
#
#####################################################

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


```



```

name = "f5_system"
  [inputs.snmp.tags]
    cluster = "cluster_ASM"
  [[inputs.snmp.field]]
    name = "hostname"
    oid = "RFC1213-MIB::sysName.0"
    is_tag = true
  [[inputs.snmp.field]]
    name = "FailoverStatus"
    oid = ".1.3.6.1.4.1.3375.2.1.14.3.2.0"
    
  [[inputs.snmp.table]]
    name = "F5_virtual_server"
    inherit_tags = [ "hostname" ]
    oid = "F5-BIGIP-LOCAL-MIB::ltmVirtualServStatTable"
  
    [[inputs.snmp.table.field]]
      name = "F5_ltmVirtualServStatName"
      oid = "F5-BIGIP-LOCAL-MIB::ltmVirtualServStatName"
      is_tag = true
	  
    [[inputs.snmp.table.field]]
      name = "ltmVirtualServStatClientCurConns"
      oid = "F5-BIGIP-LOCAL-MIB::ltmVirtualServStatClientCurConns"
    [[inputs.snmp.table.field]]
      name = "ltmVirtualServStatClientBytesIn"
      oid = "F5-BIGIP-LOCAL-MIB::ltmVirtualServStatClientBytesIn"
    [[inputs.snmp.table.field]]
      name = "ltmVirtualServStatClientBytesOut"
      oid = "F5-BIGIP-LOCAL-MIB::ltmVirtualServStatClientBytesOut"


from(bucket: "f5device")
  |> range(start: v.timeRangeStart, stop: v.timeRangeStop)
  |> filter(fn: (r) => r["F5_ltmVirtualServStatName"] == "/Common/vs_0.0.0.0_any_out")
  |> filter(fn: (r) => r["_field"] == "ltmVirtualServStatClientBytesIn")
  |> filter(fn: (r) => r["hostname"] == "lab1.local.com")
  |> aggregateWindow(every: v.windowPeriod, fn: mean, createEmpty: false)
  |> yield(name: "mean")
  
  
  
 from(bucket: "f5device")
  |> range(start: v.timeRangeStart, stop: v.timeRangeStop)
  |> filter(fn: (r) => r["F5_ltmVirtualServStatName"] == "/Common/vs_0.0.0.0_any_out")
  |> filter(fn: (r) => r["hostname"] == "lab1.local.com")
  |> filter(fn: (r) => r["_field"] == "ltmVirtualServStatClientBytesIn")
  |> aggregateWindow(every: v.windowPeriod, fn: mean, createEmpty: false)
  |> yield(name: "mean")
  
########################


優化設定檔 減少 snmp query

# 利用 snmp.table 作為 measurement 
############# For Virtual Server Group ######################
## ------------------------------------------- ##
## For Virtual Server Group
## ------------------------------------------- ##
  [[inputs.snmp.table]]
    name = "F5_virtual_server"
#    oid = "F5-BIGIP-LOCAL-MIB::ltmVirtualServStatTable"
    inherit_tags = [ "hostname" ]

    [[inputs.snmp.table.field]]
      name = "ltmVirtualServStatName"
      oid = "F5-BIGIP-LOCAL-MIB::ltmVirtualServStatName"
      is_tag = true

    [[inputs.snmp.table.field]]
      name = "ltmVirtualServStatClientCurConns"
      oid = "F5-BIGIP-LOCAL-MIB::ltmVirtualServStatClientCurConns"
    [[inputs.snmp.table.field]]
      name = "ltmVirtualServStatClientBytesIn"
      oid = "F5-BIGIP-LOCAL-MIB::ltmVirtualServStatClientBytesIn"
    [[inputs.snmp.table.field]]
      name = "ltmVirtualServStatClientBytesOut"
      oid = "F5-BIGIP-LOCAL-MIB::ltmVirtualServStatClientBytesOut"


## ------------------------------------------- ##
## For Pool Group
## ------------------------------------------- ##
  [[inputs.snmp.table]]
    name = "F5_PoolStatus"
    # oid = "F5-BIGIP-LOCAL-MIB::ltmPoolStatTable"
    inherit_tags = [ "hostname" ]

    [[inputs.snmp.table.field]]
      name = "ltmPoolStatName"
      oid = "F5-BIGIP-LOCAL-MIB::ltmPoolStatName"
      is_tag = true

    [[inputs.snmp.table.field]]
      name = "ltmPoolStatServerCurConns"
      oid = "F5-BIGIP-LOCAL-MIB::ltmPoolStatServerCurConns"
    [[inputs.snmp.table.field]]
      name = "ltmPoolStatServerBytesIn"
      oid = "F5-BIGIP-LOCAL-MIB::ltmPoolStatServerBytesIn"
    [[inputs.snmp.table.field]]
      name = "ltmPoolStatServerBytesOut"
      oid = "F5-BIGIP-LOCAL-MIB::ltmPoolStatServerBytesOut"

## ------------------------------------------- ##
## For Pool member
## ------------------------------------------- ##
  [[inputs.snmp.table]]
    name = "F5_PoolMembers_Status"
    #oid =  "F5-BIGIP-LOCAL-MIB::ltmPoolMemberStatTable"
    inherit_tags = [ "hostname" ]
	
    [[inputs.snmp.table.field]]
      name = "ltmPoolMemberStatNodeName"
      oid = "F5-BIGIP-LOCAL-MIB::ltmPoolMemberStatNodeName"
      is_tag = true

    [[inputs.snmp.table.field]]
      name = "ltmPoolMemberStatServerCurConns"
      oid = "F5-BIGIP-LOCAL-MIB::ltmPoolMemberStatServerCurConns"
    [[inputs.snmp.table.field]]
      name = "ltmPoolMemberStatServerBytesIn"
      oid = "F5-BIGIP-LOCAL-MIB::ltmPoolMemberStatServerBytesIn"
    [[inputs.snmp.table.field]]
      name = "ltmPoolMemberStatServerBytesOut"
      oid = "F5-BIGIP-LOCAL-MIB::ltmPoolMemberStatServerBytesOut"

## ------------------------------------------- ##
## For Client SSL Profile
## ------------------------------------------- ##
  [[inputs.snmp.table]]
    name = "F5_ClientSSLStatus"
    #oid = "F5-BIGIP-LOCAL-MIB::ltmClientSslStatTable"
    inherit_tags = [ "hostname" ]

    [[inputs.snmp.table.field]]
      name = "ltmClientSslStatName"
      oid = "F5-BIGIP-LOCAL-MIB::ltmClientSslStatName"
      is_tag = true

    [[inputs.snmp.table.field]]
      name = "ltmClientSslStatName"
      oid = "F5-BIGIP-LOCAL-MIB::ltmClientSslStatName"
	  
	  
ltmClientSslStatName

ltmClientSslStatCurConns


execf5_F5_PoolMembers_Status_ltmPoolMemberStatServerBytesOut{agent_host="192.168.88.167", host="Debian11-3-TPG-01", hostname="OBS-LTM01-PRD-DC01.obs.com", instance="localhost:9301", job="IDC-01-F5-LTM-Group", ltmPoolMemberStatAddr="::", ltmPoolMemberStatNodeName="/Common/10.100.125.24", ltmPoolMemberStatPoolName="/Common/pool_1936_for_10.100.2.227", ltmPoolMemberStatPort="1936"}
ltmPoolMemberStatServerCurConns
execf5_F5_PoolMembers_Status_ltmPoolMemberStatServerCurConns{ltmPoolMemberStatNodeName="/Common/10.100.103.11"}


ltmPoolMemberStatNodeName
```



```

###
# Feel free to use if it helps.
# Forked from all over the place.
###

###############################################################################
#                            INPUT PLUGINS                                    #
###############################################################################

## ------------------------------------------- ##
## QNAP NAS
## ------------------------------------------- ##
[[inputs.snmp]]
# List of agents to poll
agents = [ ";XXX.XXX.XX.X" ]
interval = ";15s"
timeout = ";10s"
retries = 3
version = 2
community = ";community"
max_repetitions = 10
name = ";snmp.QNAP"

# CPU
	[[inputs.snmp.field]]
	name = ";name"
	oid  = ";NAS-MIB::enclosureName.1"

	[[inputs.snmp.table]]
	name = ";snmp.QNAP.cpuTable"
	oid  = ";NAS-MIB::cpuTable"

		[[inputs.snmp.table.field]]
		name = ";cpuIndex"
		oid  = ";NAS-MIB::cpuIndex"

		[[inputs.snmp.table.field]]
		name = ";cpuID"
		oid  = ";NAS-MIB::cpuID"

		[[inputs.snmp.table.field]]
		name = ";cpuUsage"
		oid  = ";NAS-MIB::cpuUsage"

	# Memory - Integers
	[[inputs.snmp.field]]
	name = ";systemTotalMemEX"
	oid  = ";NAS-MIB::systemTotalMemEX.0"

	[[inputs.snmp.field]]
	name = ";systemFreeMemEX"
	oid  = ";NAS-MIB::systemFreeMemEX.0"

	# Uptime
	[[inputs.snmp.field]]
	name = ";systemUptime"
	oid  = ";NAS-MIB::systemUptimeEX.0"

	# Temperature
	[[inputs.snmp.field]]
	name = ";systemTemperature"
	oid  = ";NAS-MIB::enclosureSystemTemp.1"

  #  Fan
  [[inputs.snmp.table]]
  name = ";snmp.QNAP.systemFanTableEx"
  oid  = ";NAS-MIB::systemFanTableEx"

	[[inputs.snmp.table.field]]
	name = ";sysFanIndexEX"
	oid  = ";NAS-MIB::sysFanIndexEX"

	[[inputs.snmp.table.field]]
	name = ";sysFanDescrEX"
	oid  = ";NAS-MIB::sysFanDescrEX"

	[[inputs.snmp.table.field]]
	name = ";sysFanSpeedEX"
	oid  = ";NAS-MIB::sysFanSpeedEX"

	#  Interfaces
[[inputs.snmp.table]]
name = ";snmp.QNAP.systemIfTableEx"
oid  = ";NAS-MIB::systemIfTableEx"

	[[inputs.snmp.table.field]]
	name = ";ifIndexEX"
	oid  = ";NAS-MIB::ifIndexEX"

	[[inputs.snmp.table.field]]
	name = ";ifDescrEX"
	oid  = ";NAS-MIB::ifDescrEX"

	[[inputs.snmp.table.field]]
	name = ";ifPacketsReceivedEX"
	oid  = ";NAS-MIB::ifPacketsReceivedEX"

	[[inputs.snmp.table.field]]
	name = ";ifPacketsSentEX"
	oid  = ";NAS-MIB::ifPacketsSentEX"

	[[inputs.snmp.table.field]]
	name = ";ifErrorPacketsEX"
	oid  = ";NAS-MIB::ifErrorPacketsEX"

#  Disk
[[inputs.snmp.table]]
name = ";snmp.QNAP.systemHdTableEX"
oid  = ";NAS-MIB::systemHdTableEX"

	[[inputs.snmp.table.field]]
	name = ";hdIndexEX"
	oid  = ";NAS-MIB::hdIndexEX"

	[[inputs.snmp.table.field]]
	name = ";hdDescrEX"
	oid  = ";NAS-MIB::hdDescrEX"

	[[inputs.snmp.table.field]]
	name = ";hdTemperatureEX"
	oid  = ";NAS-MIB::hdTemperatureEX"

	[[inputs.snmp.table.field]]
	name = ";hdStatusEX"
	oid  = ";NAS-MIB::hdStatusEX"

	[[inputs.snmp.table.field]]
	name = ";hdModelEX"
	oid  = ";NAS-MIB::hdModelEX"

	[[inputs.snmp.table.field]]
	name = ";hdSmartInfoEX"
	oid  = ";NAS-MIB::hdSmartInfoEX"

#  Volumes
[[inputs.snmp.table]]
name = ";snmp.QNAP.systemVolumeTable"
oid  = ";NAS-MIB::systemVolumeTable"

	[[inputs.snmp.table.field]]
	name = ";sysVolumeIndex"
	oid  = ";NAS-MIB::sysVolumeIndex"

	[[inputs.snmp.table.field]]
	name = ";sysVolumeDescr"
	oid  = ";NAS-MIB::sysVolumeDescr"

	[[inputs.snmp.table.field]]
	name = ";sysVolumeFS"
	oid  = ";NAS-MIB::sysVolumeFS"

	[[inputs.snmp.table.field]]
	name = ";sysVolumeTotalSize"
	oid  = ";NAS-MIB::sysVolumeTotalSize"

	[[inputs.snmp.table.field]]
	name = ";sysVolumeFreeSize"
	oid  = ";NAS-MIB::sysVolumeFreeSize"

	[[inputs.snmp.table.field]]
	name = ";sysVolumeStatus"
	oid  = ";NAS-MIB::sysVolumeStatus"

#  Disk Performance
[[inputs.snmp.table]]
name = ";snmp.QNAP.diskPerformanceTable"
oid  = ";NAS-MIB::diskPerformanceTable"

	[[inputs.snmp.table.field]]
	name = ";diskPerformanceIndex"
	oid  = ";NAS-MIB::diskPerformanceIndex"

	[[inputs.snmp.table.field]]
	name = ";blvID"
	oid  = ";NAS-MIB::blvID"

	[[inputs.snmp.table.field]]
	name = ";iops"
	oid  = ";NAS-MIB::iops"

	[[inputs.snmp.table.field]]
	name = ";latency"
	oid  = ";NAS-MIB::latency"



## ------------------------------------------- ##
## CISCO SMART SWITCH
## ------------------------------------------- ##
[[inputs.snmp]]
agents = [ ";XXX.XXX.XX.X" ]
interval = ";15s"
timeout = ";10s"
retries = 3
version = 2
community = ";community"
max_repetitions = 10
name = ";snmp.CISCO"
alias = ";cisco"


# hostname (main snmp.CISCO table)
[[inputs.snmp.field]]
name = ";hostname"
oid = ";SNMPv2-MIB::sysDescr.0"

# Instance Uptime (main snmp.CISCO table)
[[inputs.snmp.field]]
name = ";sysUpTime"
oid  = ";DISMAN-EVENT-MIB::sysUpTimeInstance"

# Port statuses
[[inputs.snmp.field]]
name = ";port1"
oid  = ";IF-MIB::ifOperStatus.49"

[[inputs.snmp.field]]
name = ";port2"
oid  = ";IF-MIB::ifOperStatus.50"

[[inputs.snmp.field]]
name = ";port3"
oid  = ";IF-MIB::ifOperStatus.51"

[[inputs.snmp.field]]
name = ";port4"
oid  = ";IF-MIB::ifOperStatus.52"

[[inputs.snmp.field]]
name = ";port5"
oid  = ";IF-MIB::ifOperStatus.53"

[[inputs.snmp.field]]
name = ";port6"
oid  = ";IF-MIB::ifOperStatus.54"

[[inputs.snmp.field]]
name = ";port7"
oid  = ";IF-MIB::ifOperStatus.55"

[[inputs.snmp.field]]
name = ";port8"
oid  = ";IF-MIB::ifOperStatus.56"

[[inputs.snmp.field]]
name = ";port9"
oid  = ";IF-MIB::ifOperStatus.57"

[[inputs.snmp.field]]
name = ";port10"
oid  = ";IF-MIB::ifOperStatus.58"

[[inputs.snmp.field]]
name = ";port11"
oid  = ";IF-MIB::ifOperStatus.59"

[[inputs.snmp.field]]
name = ";port12"
oid  = ";IF-MIB::ifOperStatus.60"

[[inputs.snmp.field]]
name = ";port13"
oid  = ";IF-MIB::ifOperStatus.61"

[[inputs.snmp.field]]
name = ";port14"
oid  = ";IF-MIB::ifOperStatus.62"

[[inputs.snmp.field]]
name = ";port15"
oid  = ";IF-MIB::ifOperStatus.63"

[[inputs.snmp.field]]
name = ";port16"
oid  = ";IF-MIB::ifOperStatus.64"

[[inputs.snmp.field]]
name = ";port17"
oid  = ";IF-MIB::ifOperStatus.65"

[[inputs.snmp.field]]
name = ";port18"
oid  = ";IF-MIB::ifOperStatus.66"

[[inputs.snmp.field]]
name = ";port19"
oid  = ";IF-MIB::ifOperStatus.67"

[[inputs.snmp.field]]
name = ";port20"
oid  = ";IF-MIB::ifOperStatus.68"

[[inputs.snmp.field]]
name = ";port21"
oid  = ";IF-MIB::ifOperStatus.69"

[[inputs.snmp.field]]
name = ";port22"
oid  = ";IF-MIB::ifOperStatus.70"

[[inputs.snmp.field]]
name = ";port23"
oid  = ";IF-MIB::ifOperStatus.71"

[[inputs.snmp.field]]
name = ";port24"
oid  = ";IF-MIB::ifOperStatus.72"


## ------------------------------------------- ##
## PFSENSE
## ------------------------------------------- ##
[[inputs.snmp]]
agents = [ ";XXX.XXX.XX.X" ]
interval = ";15s"
timeout = ";10s"
retries = 3
version = 2
community = ";community"
max_repetitions = 10
name = ";snmp.PFSENSE"
alias = ";pfsense"


# hostname (main snmp.CISCO table)
[[inputs.snmp.field]]
name = ";hostname"
oid = ";SNMPv2-MIB::sysName.0"
# is_tag = true

# Uptime (main snmp.CISCO table)
[[inputs.snmp.field]]
name = ";sysUpTime"
oid  = ";HOST-RESOURCES-MIB::hrSystemUptime.0"

# Ports (main snmp.CISCO table)
# 1 - up
# 5 - dormant (unplugged)
# 2 - down
[[inputs.snmp.field]]
name = ";port1"
oid  = ";IF-MIB::ifOperStatus.1"

[[inputs.snmp.field]]
name = ";port2"
oid  = ";IF-MIB::ifOperStatus.2"

[[inputs.snmp.field]]
name = ";port3"
oid  = ";IF-MIB::ifOperStatus.3"

[[inputs.snmp.field]]
name = ";port4"
oid  = ";IF-MIB::ifOperStatus.4"

[[inputs.snmp.field]]
name = ";port5"
oid  = ";IF-MIB::ifOperStatus.5"

[[inputs.snmp.field]]
name = ";port6"
oid  = ";IF-MIB::ifOperStatus.6"



## ------------------------------------------- ##
## LOCAL (rPI)
## ------------------------------------------- ##
[[inputs.cpu]]
percpu = true
totalcpu = true
collect_cpu_time = false
report_active = false

```
### influxdb_v2 設定檔
```

[[inputs.snmp]]
  agents = [ "192.168.88.198" ]
  version = 2
  community = "public"
  interval = "60s"
  timeout = "10s"
  retries = 3
  name = "F5_system"

  [[inputs.snmp.field]]
    name = "hostname"
    oid = "RFC1213-MIB::sysName.0"
    is_tag = true

  [[inputs.snmp.field]]
    name = "F5_uptime"
    oid = "F5-BIGIP-SYSTEM-MIB::sysSystemUptime.0"
  [[inputs.snmp.field]]
    name = "F5_httpRequests"
    oid = "F5-BIGIP-SYSTEM-MIB::sysStatHttpRequests.0"
  [[inputs.snmp.field]]
    name = "F5_version"
    oid = "F5-BIGIP-SYSTEM-MIB::sysProductVersion.0"
  [[inputs.snmp.field]]
    name = "F5_Platform"
    oid = "SNMPv2-MIB::sysDescr.0"


 [[inputs.snmp.table]]
    name = "F5_CPU"
    oid = "F5-BIGIP-SYSTEM-MIB::sysMultiHostCpuTable"
    inherit_tags = [ "hostname" ]



## ------------------------------------------- ##
## For Virtual Server Group
## ------------------------------------------- ##
  [[inputs.snmp.table]]
    name = "F5_virtual_server"
#    oid = "F5-BIGIP-LOCAL-MIB::ltmVirtualServStatTable"
    inherit_tags = [ "hostname" ]

    [[inputs.snmp.table.field]]
      name = "ltmVirtualServStatName"
      oid = "F5-BIGIP-LOCAL-MIB::ltmVirtualServStatName"
      is_tag = true

    [[inputs.snmp.table.field]]
      name = "ltmVirtualServStatClientCurConns"
      oid = "F5-BIGIP-LOCAL-MIB::ltmVirtualServStatClientCurConns"
    [[inputs.snmp.table.field]]
      name = "ltmVirtualServStatClientBytesIn"
      oid = "F5-BIGIP-LOCAL-MIB::ltmVirtualServStatClientBytesIn"
    [[inputs.snmp.table.field]]
      name = "ltmVirtualServStatClientBytesOut"
      oid = "F5-BIGIP-LOCAL-MIB::ltmVirtualServStatClientBytesOut"


## ------------------------------------------- ##
## For Pool Group
## ------------------------------------------- ##
  [[inputs.snmp.table]]
    name = "F5_PoolStatus"
    # oid = "F5-BIGIP-LOCAL-MIB::ltmPoolStatTable"
    inherit_tags = [ "hostname" ]

    [[inputs.snmp.table.field]]
      name = "ltmPoolStatName"
      oid = "F5-BIGIP-LOCAL-MIB::ltmPoolStatName"
      is_tag = true

    [[inputs.snmp.table.field]]
      name = "ltmPoolStatServerCurConns"
      oid = "F5-BIGIP-LOCAL-MIB::ltmPoolStatServerCurConns"
    [[inputs.snmp.table.field]]
      name = "ltmPoolStatServerBytesIn"
      oid = "F5-BIGIP-LOCAL-MIB::ltmPoolStatServerBytesIn"
    [[inputs.snmp.table.field]]
      name = "ltmPoolStatServerBytesOut"
      oid = "F5-BIGIP-LOCAL-MIB::ltmPoolStatServerBytesOut"

## ------------------------------------------- ##
## For Pool member
## ------------------------------------------- ##
  [[inputs.snmp.table]]
    name = "F5_PoolMembers_Status"
    #oid =  "F5-BIGIP-LOCAL-MIB::ltmPoolMemberStatTable"
    inherit_tags = [ "hostname" ]

    [[inputs.snmp.table.field]]
      name = "ltmPoolMemberStatNodeName"
      oid = "F5-BIGIP-LOCAL-MIB::ltmPoolMemberStatNodeName"
      is_tag = true

    [[inputs.snmp.table.field]]
      name = "ltmPoolMemberStatServerCurConns"
      oid = "F5-BIGIP-LOCAL-MIB::ltmPoolMemberStatServerCurConns"
    [[inputs.snmp.table.field]]
      name = "ltmPoolMemberStatServerBytesIn"
      oid = "F5-BIGIP-LOCAL-MIB::ltmPoolMemberStatServerBytesIn"
    [[inputs.snmp.table.field]]
      name = "ltmPoolMemberStatServerBytesOut"
      oid = "F5-BIGIP-LOCAL-MIB::ltmPoolMemberStatServerBytesOut"

## ------------------------------------------- ##
## For Client SSL Profile
## ------------------------------------------- ##
  [[inputs.snmp.table]]
    name = "F5_ClientSSLStatus"
    #oid = "F5-BIGIP-LOCAL-MIB::ltmClientSslStatTable"
    inherit_tags = [ "hostname" ]

    [[inputs.snmp.table.field]]
      name = "ltmClientSslStatName"
      oid = "F5-BIGIP-LOCAL-MIB::ltmClientSslStatName"
      is_tag = true

    [[inputs.snmp.table.field]]
      name = "ltmClientSslStatCurConns"
      oid = "F5-BIGIP-LOCAL-MIB::ltmClientSslStatCurConns"

#####################################################
#
# Export Information to influxdb_v2
#
#####################################################

[outputs.influxdb_v2]
   ## filter data
   namepass= ["F5_*"]
   urls = ["http://192.168.88.131:8086"]
   ## Token for authentication.
   token = "az6f4aA4YOYGjLYJuM9wuyKsiHyaqd-823NnH48DmuWvvDUMUtu-cksSftIP1vFUnUDaUNK6IKI2IxGa5HsLWA=="
   ## Organization is the name of the organization you wish to write to; must exist.
   organization = "f5monitor"
   ## Destination bucket to write into.
   bucket = "f5device"



iso.3.6.1.2.1.1.1.0

```


```
# cat /etc/telegraf/telegraf.d/f5_192.168.88.198_v2.conf
[[inputs.snmp]]
  agents = [ "192.168.88.198" ]
  version = 2
  community = "public"
  interval = "60s"
  timeout = "10s"
  retries = 3
  name = "F5_system"

  [[inputs.snmp.field]]
    name = "hostname"
    oid = "RFC1213-MIB::sysName.0"
    is_tag = true

  [[inputs.snmp.field]]
    name = "F5_uptime"
    oid = "F5-BIGIP-SYSTEM-MIB::sysSystemUptime.0"
  [[inputs.snmp.field]]
    name = "F5_httpRequests"
    oid = "F5-BIGIP-SYSTEM-MIB::sysStatHttpRequests.0"
  [[inputs.snmp.field]]
    name = "F5_version"
    oid = "F5-BIGIP-SYSTEM-MIB::sysProductVersion.0"
  [[inputs.snmp.field]]
    name = "F5_Platform"
    oid = "SNMPv2-MIB::sysDescr.0"


 [[inputs.snmp.table]]
    name = "F5_CPU"
    oid = "F5-BIGIP-SYSTEM-MIB::sysMultiHostCpuTable"
    inherit_tags = [ "hostname" ]

## ------------------------------------------- ##
## For Virtual Server Group
## ------------------------------------------- ##
  [[inputs.snmp.table]]
    name = "F5_virtual_server"
    inherit_tags = [ "hostname" ]

    [[inputs.snmp.table.field]]
      name = "ltmVirtualServStatName"
      oid = "F5-BIGIP-LOCAL-MIB::ltmVirtualServStatName"
      is_tag = true

    [[inputs.snmp.table.field]]
      name = "ltmVirtualServStatClientCurConns"
      oid = "F5-BIGIP-LOCAL-MIB::ltmVirtualServStatClientCurConns"
    [[inputs.snmp.table.field]]
      name = "ltmVirtualServStatClientBytesIn"
      oid = "F5-BIGIP-LOCAL-MIB::ltmVirtualServStatClientBytesIn"
    [[inputs.snmp.table.field]]
      name = "ltmVirtualServStatClientBytesOut"
      oid = "F5-BIGIP-LOCAL-MIB::ltmVirtualServStatClientBytesOut"


## ------------------------------------------- ##
## For Pool Group
## ------------------------------------------- ##
  [[inputs.snmp.table]]
    name = "F5_PoolStatus"
    inherit_tags = [ "hostname" ]

    [[inputs.snmp.table.field]]
      name = "ltmPoolStatName"
      oid = "F5-BIGIP-LOCAL-MIB::ltmPoolStatName"
      is_tag = true

    [[inputs.snmp.table.field]]
      name = "ltmPoolStatServerCurConns"
      oid = "F5-BIGIP-LOCAL-MIB::ltmPoolStatServerCurConns"
    [[inputs.snmp.table.field]]
      name = "ltmPoolStatServerBytesIn"
      oid = "F5-BIGIP-LOCAL-MIB::ltmPoolStatServerBytesIn"
    [[inputs.snmp.table.field]]
      name = "ltmPoolStatServerBytesOut"
      oid = "F5-BIGIP-LOCAL-MIB::ltmPoolStatServerBytesOut"

## ------------------------------------------- ##
## For Pool member
## ------------------------------------------- ##
  [[inputs.snmp.table]]
    name = "F5_PoolMembers_Status"
    inherit_tags = [ "hostname" ]

    [[inputs.snmp.table.field]]
      name = "ltmPoolMemberStatNodeName"
      oid = "F5-BIGIP-LOCAL-MIB::ltmPoolMemberStatNodeName"
      is_tag = true

    [[inputs.snmp.table.field]]
      name = "ltmPoolMemberStatServerCurConns"
      oid = "F5-BIGIP-LOCAL-MIB::ltmPoolMemberStatServerCurConns"
    [[inputs.snmp.table.field]]
      name = "ltmPoolMemberStatServerBytesIn"
      oid = "F5-BIGIP-LOCAL-MIB::ltmPoolMemberStatServerBytesIn"
    [[inputs.snmp.table.field]]
      name = "ltmPoolMemberStatServerBytesOut"
      oid = "F5-BIGIP-LOCAL-MIB::ltmPoolMemberStatServerBytesOut"

## ------------------------------------------- ##
## For Client SSL Profile
## ------------------------------------------- ##
  [[inputs.snmp.table]]
    name = "F5_ClientSSLStatus"
    inherit_tags = [ "hostname" ]

    [[inputs.snmp.table.field]]
      name = "ltmClientSslStatName"
      oid = "F5-BIGIP-LOCAL-MIB::ltmClientSslStatName"
      is_tag = true

    [[inputs.snmp.table.field]]
      name = "ltmClientSslStatCurConns"
      oid = "F5-BIGIP-LOCAL-MIB::ltmClientSslStatCurConns"

#####################################################
#
# Export Information to influxdb_v2
#
#####################################################

[outputs.influxdb_v2]
   ## filter data
   namepass= ["F5_*"]
   urls = ["http://192.168.88.131:8086"]
   ## Token for authentication.
   token = "az6f4aA4YOYGjLYJuM9wuyKsiHyaqd-823NnH48DmuWvvDUMUtu-cksSftIP1vFUnUDaUNK6IKI2IxGa5HsLWA=="
   ## Organization is the name of the organization you wish to write to; must exist.
   organization = "f5monitor"
   ## Destination bucket to write into.
   bucket = "f5device"

```

### LTM telegraf 設定


```

#############################################################
# cat /etc/telegraf/telegraf.d/f5_XXX.XXX.XXX.XXX_v2.conf
[[inputs.snmp]]
  agents = [ "XXX.XXX.XXX.XXX" ]
  version = 2
  community = "public"
  interval = "60s"
  timeout = "10s"
  retries = 3
  name = "F5_system"

  [[inputs.snmp.field]]
    name = "hostname"
    oid = "RFC1213-MIB::sysName.0"
    is_tag = true

  [[inputs.snmp.field]]
    name = "F5_uptime"
    oid = "F5-BIGIP-SYSTEM-MIB::sysSystemUptime.0"
  [[inputs.snmp.field]]
    name = "F5_httpRequests"
    oid = "F5-BIGIP-SYSTEM-MIB::sysStatHttpRequests.0"
  [[inputs.snmp.field]]
    name = "F5_version"
    oid = "F5-BIGIP-SYSTEM-MIB::sysProductVersion.0"
  [[inputs.snmp.field]]
    name = "F5_Platform"
    oid = "SNMPv2-MIB::sysDescr.0"
  [[inputs.snmp.field]]
    name = "F5_Temperature"
    oid = "F5-BIGIP-SYSTEM-MIB::sysChassisTempTemperature.1"
  [[inputs.snmp.field]]
    name = "F5_Device_status"
    oid = "F5-BIGIP-SYSTEM-MIB::sysCmFailoverStatusId.0"
 [[inputs.snmp.field]]
    name = "F5_Synchronization_status_color"
    oid = "F5-BIGIP-SYSTEM-MIB::sysCmSyncStatusColor.0"


 [[inputs.snmp.table]]
    name = "F5_CPU"
    oid = "F5-BIGIP-SYSTEM-MIB::sysMultiHostCpuTable"
    inherit_tags = [ "hostname" ]

 [[inputs.snmp.table]]
    name = "F5_Memory_Usage"
    oid = "F5-BIGIP-SYSTEM-MIB::sysMultiHostTable"
    inherit_tags = [ "hostname" ]

## ------------------------------------------- ##
## For Virtual Server Group
## ------------------------------------------- ##
  [[inputs.snmp.table]]
    name = "F5_virtual_server"
    inherit_tags = [ "hostname" ]

    [[inputs.snmp.table.field]]
      name = "ltmVirtualServStatName"
      oid = "F5-BIGIP-LOCAL-MIB::ltmVirtualServStatName"
      is_tag = true

    [[inputs.snmp.table.field]]
      name = "ltmVirtualServStatClientCurConns"
      oid = "F5-BIGIP-LOCAL-MIB::ltmVirtualServStatClientCurConns"
    [[inputs.snmp.table.field]]
      name = "ltmVirtualServStatClientBytesIn"
      oid = "F5-BIGIP-LOCAL-MIB::ltmVirtualServStatClientBytesIn"
    [[inputs.snmp.table.field]]
      name = "ltmVirtualServStatClientBytesOut"
      oid = "F5-BIGIP-LOCAL-MIB::ltmVirtualServStatClientBytesOut"

## ------------------------------------------- ##
## For Pool Group
## ------------------------------------------- ##
  [[inputs.snmp.table]]
    name = "F5_PoolStatus"
    inherit_tags = [ "hostname" ]

    [[inputs.snmp.table.field]]
      name = "ltmPoolStatName"
      oid = "F5-BIGIP-LOCAL-MIB::ltmPoolStatName"
      is_tag = true

    [[inputs.snmp.table.field]]
      name = "ltmPoolStatServerCurConns"
      oid = "F5-BIGIP-LOCAL-MIB::ltmPoolStatServerCurConns"
    [[inputs.snmp.table.field]]
      name = "ltmPoolStatServerBytesIn"
      oid = "F5-BIGIP-LOCAL-MIB::ltmPoolStatServerBytesIn"
    [[inputs.snmp.table.field]]
      name = "ltmPoolStatServerBytesOut"
      oid = "F5-BIGIP-LOCAL-MIB::ltmPoolStatServerBytesOut"

## ------------------------------------------- ##
## For Pool member
## ------------------------------------------- ##
  [[inputs.snmp.table]]
    name = "F5_PoolMembers_Status"
    inherit_tags = [ "hostname" ]

    [[inputs.snmp.table.field]]
      name = "ltmPoolMemberStatNodeName"
      oid = "F5-BIGIP-LOCAL-MIB::ltmPoolMemberStatNodeName"
      is_tag = true

    [[inputs.snmp.table.field]]
      name = "ltmPoolMemberStatServerCurConns"
      oid = "F5-BIGIP-LOCAL-MIB::ltmPoolMemberStatServerCurConns"
    [[inputs.snmp.table.field]]
      name = "ltmPoolMemberStatServerBytesIn"
      oid = "F5-BIGIP-LOCAL-MIB::ltmPoolMemberStatServerBytesIn"
    [[inputs.snmp.table.field]]
      name = "ltmPoolMemberStatServerBytesOut"
      oid = "F5-BIGIP-LOCAL-MIB::ltmPoolMemberStatServerBytesOut"

## ------------------------------------------- ##
## For Client SSL Profile
## ------------------------------------------- ##
  [[inputs.snmp.table]]
    name = "F5_ClientSSLStatus"
    inherit_tags = [ "hostname" ]

    [[inputs.snmp.table.field]]
      name = "ltmClientSslStatName"
      oid = "F5-BIGIP-LOCAL-MIB::ltmClientSslStatName"
      is_tag = true

    [[inputs.snmp.table.field]]
      name = "ltmClientSslStatCurConns"
      oid = "F5-BIGIP-LOCAL-MIB::ltmClientSslStatCurConns"

#####################################################
#
# Export Information to influxdb_v2
#
#####################################################

[outputs.influxdb_v2]
   ## filter data
   namepass= ["F5_*"]
   urls = ["http://127.0.0.1:8086"]
   ## Token for authentication.
   token = "az6f4aA4YOYGjLYJuM9wuyKsiHyaqd-823NnH48DmuWvvDUMUtu-cksSftIP1vFUnUDaUNK6IKI2IxGa5HsLWA=="
   ## Organization is the name of the organization you wish to write to; must exist.
   organization = "f5monitor"
   ## Destination bucket to write into.
   bucket = "f5device"


_pa$$w0rd_


Str0ngPassw0rd

```
