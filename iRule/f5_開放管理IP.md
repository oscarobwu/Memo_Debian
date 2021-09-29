## f5管理IP


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
