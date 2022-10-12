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

# 查詢方式 (可以找出 ID 和名稱
$ influx bucket list -o f5monitor -name f5device

$ influx bucket find --org f5monitor

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

# 查詢 user 
$ influx auth list

ID                      Description             Token                                                                                           User Name       User ID                 Permissions
0a1e8c1803331000                                YhR9aO7xvfa9EjCYgeXKHatY-rY9ZGxS1IFTo1YEn96Ylny8kH7YL-8uOaDiF18zY_NNyjylLyiBWO2wANxIbA==        f5monitoruser   0a1e8aa605731000        [read:orgs/b00f9b8db4dd334d/buckets/66bb98d01f575793 write:orgs/b00f9b8db4dd334d/buckets/66bb98d01f575793]



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

```
