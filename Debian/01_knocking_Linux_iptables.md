# 使用 knocking Iptable

```
#!/bin/bash

set -o errexit
set -o nounset

# Set the ports to tknocking
port1="xxx"
port2="xxx"
port3="xxx"

# Set ips to withelist
withelist1="0.0.0.0"
withelist2="0.0.0.0"

# Create the CHAINS on iptalbes
iptables -N KNOCKING
iptables -N GATE1
iptables -N GATE2
iptables -N GATE3
iptables -N PASSED

# Add rules to each CHAIN and specify the knock doors
iptables -A GATE1 -p tcp --dport $port1 -m recent --name AUTH1 --set -j DROP
iptables -A GATE1 -j RETURN
iptables -A GATE2 -m recent --name AUTH1 --remove
iptables -A GATE2 -p tcp --dport $port2 -m recent --name AUTH2 --set -j DROP
iptables -A GATE2 -j GATE1
iptables -A GATE3 -m recent --name AUTH2 --remove
iptables -A GATE3 -p tcp --dport $port3 -m recent --name AUTH3 --set -j DROP
iptables -A GATE3 -j GATE1
iptables -A PASSED -m recent --name AUTH3 --remove
iptables -A PASSED -p tcp --dport 22 -j ACCEPT
iptables -A PASSED -j GATE1

# Add rules to jump between the CHAINS
iptables -I INPUT -j KNOCKING
iptables -A KNOCKING -p tcp --dport 22 -s $withelist1  -j ACCEPT
iptables -A KNOCKING -p tcp --dport 22 -s $withelist2  -j ACCEPT
iptables -A KNOCKING -p tcp --dport 22 -m conntrack --ctstate ESTABLISHED,RELATED -j ACCEPT
iptables -A KNOCKING -m recent --rcheck --seconds 30 --name AUTH3 -j PASSED
iptables -A KNOCKING -m recent --rcheck --seconds 10 --name AUTH2 -j GATE3
iptables -A KNOCKING -m recent --rcheck --seconds 10 --name AUTH1 -j GATE2
iptables -A KNOCKING -j GATE1
iptables -A KNOCKING -p tcp --dport 22 -m limit --limit 3/min -j LOG --log-level 4 --log-prefix '[KNOCKING BLOCK] '
iptables -A KNOCKING -p tcp --dport 22 -j DROP
```
### 使用 udp

```
#!/bin/sh

iptables -F
iptables -X
iptables -Z
## 開始使用portnick 第一階段 使用udp不會有upd連線保存問題
iptables -N STATE0
iptables -A STATE0 -p udp --dport 12345 -m recent --name KNOCK1 --set -j DROP
iptables -A STATE0 -j DROP

iptables -N STATE1
iptables -A STATE1 -m recent --name KNOCK1 --remove
iptables -A STATE1 -p udp --dport 23456 -m recent --name KNOCK2 --set -j DROP
iptables -A STATE1 -j STATE0

iptables -N STATE2
iptables -A STATE2 -m recent --name KNOCK2 --remove
iptables -A STATE2 -p udp --dport 34567 -m recent --name KNOCK3 --set -j DROP
iptables -A STATE2 -j STATE0

iptables -N STATE3
iptables -A STATE3 -m recent --name KNOCK3 --remove
iptables -A STATE3 -p tcp --dport 22 -j ACCEPT
iptables -A STATE3 -j STATE0

iptables -A INPUT -m state --state ESTABLISHED,RELATED -j ACCEPT
iptables -A INPUT -s 127.0.0.0/8 -j ACCEPT
iptables -A INPUT -p icmp -j ACCEPT
iptables -A INPUT -p tcp --dport 80 -j ACCEPT

iptables -A INPUT -m recent --name KNOCK3 --rcheck -j STATE3
iptables -A INPUT -m recent --name KNOCK2 --rcheck -j STATE2
iptables -A INPUT -m recent --name KNOCK1 --rcheck -j STATE1
iptables -A INPUT -j STATE0
```
```
#! /bin/bash
 
####### 初始化
# 清除腳本
iptables -X
iptables -F
# 預設 名稱
iptables -X INTO-P2
iptables -X INTO-P3
iptables -X INTO-P4
 
 
####### 新增白單#######
#iptables -A INPUT -p tcp -s xxx.xxx.xxx.xxx --dport 22 -j ACCEPT
#iptables -A INPUT -p tcp -s xxx.xxx.xxx.xxx --dport 22 -j ACCEPT
#iptables -A INPUT -p tcp -s 192.168.88.250 --dport 22 -j ACCEPT
iptables -A INPUT -p tcp -s 192.168.88.250 --dport 22 -j ACCEPT
 
###### 接受當前已經建立好的連接 #######
iptables -A INPUT -p tcp --dport 22 -m state --state ESTABLISHED -j ACCEPT
iptables -A INPUT -p tcp --dport 22 -m state --state RELATED -j ACCEPT
 
####### Etapes du code secret #######
####### Creation des chaines puis ajout des regles
# 創建鏈然後添加規則
#####################
iptables -N INTO-P2
iptables -A INTO-P2 -m recent --name P1 --remove
iptables -A INTO-P2 -m recent --name P2 --set
iptables -A INTO-P2 -j LOG --log-prefix "KNOCKING-INTO P2: "
 
iptables -N INTO-P3
iptables -A INTO-P3 -m recent --name P2 --remove
iptables -A INTO-P3 -m recent --name P3 --set
iptables -A INTO-P3 -j LOG --log-prefix "KNOCKING-INTO P3: "
 
iptables -N INTO-P4
iptables -A INTO-P4 -m recent --name P3 --remove
iptables -A INTO-P4 -m recent --name P4 --set
iptables -A INTO-P4 -j LOG --log-prefix "KNOCKING-INTO P4: "
 
iptables -A INPUT -m recent --update --name P1 -j LOG --log-prefix "KNOCKING-INTO P1: "
 
####### 密碼的定義與到期前每個階段的時間限制。
#######
# 建立 敲門port 使用
# 等 10秒敲門
iptables -A INPUT -p tcp --dport 45678 -m recent --name P1 --set
iptables -A INPUT -p tcp --dport 34567 -m recent --rcheck --seconds 10 --name P1 -j INTO-P2
iptables -A INPUT -p tcp --dport 23456 -m recent --rcheck --seconds 10 --name P2 -j INTO-P3
iptables -A INPUT -p tcp --dport 12345 -m recent --rcheck --seconds 10 --name P3 -j INTO-P4
 
####### 到達 P4 後，SSH 端口連接可用。
 
iptables -A INPUT -p tcp --dport 22 -m recent --rcheck --seconds 10 --name P4 -j ACCEPT
 
####### 如果不遵守上述所有內容，則默認規則：端口 22 關閉  ########
 
iptables -A INPUT -p tcp --dport 22 -m state --state NEW -j DROP
```
