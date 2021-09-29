## 01_嚴謹_Linux_iptables.md

```
#!/bin/bash
# 使用說明：
# (1)將本檔放置於： /usr/local/bin
# (2)變更檔案權限： chmod +x /usr/local/bin/firewall.sh
# (3)設定開機啟動：於 /etc/rc.local 新增 /usr/local/bin/firewall.sh start
# (4)只開放特定主機存取 SSH
# (5)封鎖所有對外和對內的連線
ip4=$(/sbin/ip -o -4 addr list ens192 | awk '{print $4}' | cut -d/ -f1)
ip6=$(/sbin/ip -o -6 addr list ens192 | awk '{print $4}' | cut -d/ -f1)

iptables -t filter -F
iptables -t filter -X
#Now we will block all traffic:

iptables -t filter -P INPUT DROP
iptables -t filter -P FORWARD DROP
iptables -t filter -P OUTPUT DROP
#We will keep established connections (you can skip it but we recommend to put these rules)
# Allow loopback
iptables -I INPUT 1 -i lo -j ACCEPT
#
iptables -A INPUT -m state --state RELATED,ESTABLISHED -j ACCEPT
iptables -A OUTPUT -m state --state RELATED,ESTABLISHED -j ACCEPT
#Allow loopback connections (necessary in some cases . we recommend to add this rule to exclude possible applications issues)

iptables -t filter -A INPUT -i lo -j ACCEPT
iptables -t filter -A OUTPUT -o lo -j ACCEPT
#And now we are ready to add “allowed rules” For example, we will allow http traffic:

iptables -t filter -A INPUT -p tcp -d $ip4 --dport 80 -j ACCEPT
iptables -t filter -A OUTPUT -p tcp -d $ip4 --dport 80 -j ACCEPT
#iptables -t filter -A INPUT -p tcp -s $ip4 --dport 80 -j ACCEPT
#And also do not forget about SSH (in case you use differ ssh port -change it)
#iptables -t filter -A OUTPUT -p tcp --dport 443 -j ACCEPT
#
iptables -t filter -A INPUT -p tcp -d $ip4 --dport 443 -j ACCEPT
iptables -t filter -A OUTPUT -p tcp -d $ip4 --dport 443 -j ACCEPT
#iptables -t filter -A INPUT -p tcp -s $ip4 --dport 443 -j ACCEPT

#iptables -t filter -A INPUT -p tcp --dport 22 -j ACCEPT
#iptables -t filter -A OUTPUT -p tcp --dport 22 -j ACCEPT
#You also can open ssh port for specific IP

iptables -I INPUT -p tcp -m tcp -s 192.168.88.250 --dport 22 -j ACCEPT
#iptables -I INPUT -p tcp -m tcp -s 0.0.0.0/0 --dport 22 -j DROP
#In case you need to allow some port range use the next example:

#iptables -t filter -A OUTPUT -p tcp --dport 1024:2000 -j ACCEPT
#iptables -t filter -A INPUT -p tcp --dport 1024:2000 -j ACCEPT
iptables -A OUTPUT -p tcp --dport 443 -d 192.168.88.13 -j ACCEPT
#iptables -A OUTPUT -p tcp --dport 5601 -d 192.168.88.245 -j ACCEPT
#Block all UDP except port 53 (DNS):

#allow dns requests
#iptables -A OUTPUT -p udp --dport 53 -j ACCEPT
#iptables -A OUTPUT -p udp --dport 53 -j ACCEPT
iptables -A OUTPUT -p udp --dport 53 -d 192.168.88.1 -j ACCEPT
#
# 開放FQDN 需要再 開通DNS服務後面
# Now, allow connection to website www.github.com on port 22,80,443
iptables -A OUTPUT -p tcp -m multiport -d www.hinet.net --dports 22,80,443 -j ACCEPT
iptables -A INPUT -m conntrack --ctstate ESTABLISHED,RELATED -j ACCEPT
#block all other udp
iptables -A OUTPUT -p udp -j DROP
ip6tables -A OUTPUT -p udp -j DROP
#You can add allowed nameservers with the “-d” parameter:

iptables -A OUTPUT -p tcp -j DROP
ip6tables -A OUTPUT -p tcp -j DROP

#iptables -A OUTPUT -p udp --dport 53 -d 192.168.88.1 -j ACCEPT
#iptables -A OUTPUT -p udp --dport 53 -d 8.8.4.4 -j ACCEPT
#Disable outgoing ping echo request:
iptables -A OUTPUT -p icmp -s 192.168.88.166 --icmp-type echo-request -j ACCEPT
iptables -A INPUT -p icmp -s 192.168.88.166 --icmp-type echo-request -j ACCEPT
#
iptables -A OUTPUT -p icmp --icmp-type echo-request -j DROP
#Disable incoming pings:

iptables -A INPUT -p icmp --icmp-type echo-request -j REJECT

```
