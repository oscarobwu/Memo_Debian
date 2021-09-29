## f5管理IP
----

```
開放 特定主機範圍 存取管理IP 在第一筆之前
tmsh modify /security firewall management-ip-rules rules add { MGMT_Allow_List_1 { action accept destination { addresses add { 10.199.0.168 } ports add { 443 22 } } ip-protocol tcp log yes place-before first source { addresses add { 10.10.10.1-10.10.10.10 } ports none } status enabled uuid auto-generate } }

開放 特定主機 存取管理IP 在第一筆之後 
tmsh modify /security firewall management-ip-rules rules add { MGMT_Allow_List_2 { action accept destination { addresses add { 10.199.0.168 } ports add { 443 22 } } ip-protocol tcp log yes place-after first source { addresses add { 192.168.88.160 } ports none } status enabled uuid auto-generate } }

開放 特定主機 存取管理IP 在最後一筆筆之後 
tmsh modify /security firewall management-ip-rules rules add { MGMT_Allow_List_3 { action accept destination { addresses add { 10.199.0.168 } ports add { 443 22 } } ip-protocol tcp log yes place-before first source { addresses add { 192.168.88.161 } ports none } status enabled uuid auto-generate } }

Dena_any
tmsh modify /security firewall management-ip-rules rules add { MGMT_DenyAny_List { action drop destination { addresses add { 0.0.0.0/0 } ports add { 443 22 } } ip-protocol tcp log yes place-before last source { addresses add { 0.0.0.0/0 } ports none } status enabled uuid auto-generate } }

```

## 設定 packet-filter
----
```
# 要注意一下 order 順序 設定有 來源vlan
tmsh create net packet-filter Allow_SelfIP_list_2 { action accept order 6 rule "( ( ip proto TCP or ip6 proto TCP )  ) and ( src host 192.168.88.103 or src host 192.168.88.104 or src host 192.168.88.105) and ( dst host 192.168.88.166 ) and ( dst port 4353 or dst port 443 or dst port 22 )" vlan MGMT }

# Deny any selfip 
tmsh create net packet-filter Deny_MGMT_port_host_any { action discard logging enabled order 10 rule "( ( ip proto TCP or ip6 proto TCP ) ) and ( dst host 192.168.88.166 ) and ( dst port 22 or dst port 443 )" vlan MGMT }
```
## 限制 webui 和 ssh 登入

```
tmsh list /sys httpd allow
tmsh list /sys sshd allow


tmsh modify /sys sshd allow replace-all-with { 192.168.2.* 192.168.88.250 192.168.88.251}
tmsh modify /sys httpd allow replace-all-with { 192.168.2.* 192.168.88.250 192.168.88.251}
save /sys config
```
