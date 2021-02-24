'''
#(root / default)
 
tmsh modify sys global-settings mgmt-dhcp disabled
tmsh create sys management-ip 10.199.0.224/255.255.255.0
tmsh create sys management-route default gateway 10.199.0.1
#(or you can use "config" command - to speed it up)
 
#DNS
tmsh modify sys dns name-servers add { 10.199.0.141 10.199.0.142 }
tmsh modify sys dns search add { cloud.example.com }
#Hostname
tmsh modify sys glob hostname lb01.cloud.example.com
#NTP
tmsh modify sys ntp servers add { 0.rhel.pool.ntp.org 1.rhel.pool.ntp.org }
tmsh modify sys ntp timezone "UTC"
#Seesion timeout
tmsh modify sys sshd inactivity-timeout 120000
tmsh modify sys http auth-pam-idle-timeout 120000
#SNMP allow from "all"
tmsh modify sys snmp allowed-addresses add { 10.199.0.0/8 }
#SNMP traps
tmsh modify /sys snmp traps add { my_trap_destination { host monitor.cloud.example.com community public version 2c } }
# Network configuration...
tmsh create net vlan External interfaces add { 1.2 }
tmsh create net vlan Internal interfaces add { 1.1 }
#SMTP
tmsh create sys smtp-server yum.cloud.example.com { from-address root@lb01.cloud.example.com local-host-name lb01.cloud.example.com smtp-server-host-name yum.cloud.example.com }
tmsh create net self 10.199.0.224/24 vlan Internal allow-service all
tmsh create net self 10.199.1.224/24 vlan External allow-service all
#https://support.f5.com/kb/en-us/solutions/public/13000/100/sol13180.html
tmsh modify /sys outbound-smtp mailhub yum.cloud.example.com:25
#Send email when there are some problems with monitoring nodes "up/down"
cat > /config/user_alert.conf << EOF
alert Monitor_Status "monitor status" {
        email toaddress="petr.ruzicka@example.com"
        fromaddress="root"
        body="Check the Server status: https://10.199.0.224"
}
EOF
 
echo 'ssh-dss AX.... ....UQ= admin' >> /root/.ssh/authorized_keys
  
cat > /root/.ssh/id_dsa << EOF
-----BEGIN DSA PRIVATE KEY-----
...
...
-----END DSA PRIVATE KEY-----
EOF
 
tmsh modify auth password admin # my_secret_password
tmsh modify auth user admin shell bash
mkdir /home/admin/.ssh && chmod 700 /home/admin/.ssh
cp -L /root/.ssh/authorized_keys /home/admin/.ssh/
tmsh modify auth password root  # my_secret_password2
  
tmsh install /sys license registration-key ZXXXX-XXXXX-XXXXX-XXXXX-XXXXXXL
 
curl http://10.199.0.141/Hotfix-BIGIP-11.6.0.1.0.403-HF1.iso > /shared/images/Hotfix-BIGIP-11.6.0.1.0.403-HF1.iso
scp 10.199.0.226:/var/tmp/BIGIP-11.6.0.0.0.401.iso /shared/images/
 
tmsh install sys software image BIGIP-11.6.0.0.0.401.iso volume HD1.2
 
tmsh install sys software hotfix Hotfix-BIGIP-11.6.0.1.0.403-HF1.iso volume HD1.2
tmsh show sys software status
tmsh reboot volume HD1.2
mount -o rw,remount /usr
rpm -Uvh --nodeps \
http://vault.centos.org/5.8/os/i386/CentOS/yum-3.2.22-39.el5.centos.noarch.rpm \
http://vault.centos.org/5.8/os/i386/CentOS/python-elementtree-1.2.6-5.i386.rpm \
http://vault.centos.org/5.8/os/i386/CentOS/python-iniparse-0.2.3-4.el5.noarch.rpm \
http://vault.centos.org/5.8/os/i386/CentOS/python-sqlite-1.1.7-1.2.1.i386.rpm \
http://vault.centos.org/5.8/updates/i386/RPMS/rpm-python-4.4.2.3-28.el5_8.i386.rpm \
http://vault.centos.org/5.8/os/i386/CentOS/python-urlgrabber-3.1.0-6.el5.noarch.rpm \
http://vault.centos.org/5.8/os/i386/CentOS/yum-fastestmirror-1.1.16-21.el5.centos.noarch.rpm \
http://vault.centos.org/5.8/os/i386/CentOS/yum-metadata-parser-1.1.2-3.el5.centos.i386.rpm
  
cat > /etc/yum.repos.d/CentOS-Base.repo << \EOF
[base]
name=CentOS-5 - Base
baseurl=http://mirror.centos.org/centos/5/os/i386/
gpgcheck=0
  
[updates]
name=CentOS-5 - Updates
baseurl=http://mirror.centos.org/centos/5/updates/i386/
gpgcheck=0
EOF

yum install -y mc screen

cat >> /etc/screenrc << EOF
defscrollback 10000
startup_message off
termcapinfo xterm ti@:te@
hardstatus alwayslastline '%{= kG}[ %{G}%H %{g}][%= %{= kw}%?%-Lw%?%{r}(%{W}%n*%f%t%?(%u)%?%{r})%{w}%?%+Lw%?%?%= %{g}][%{B} %d/%m %{W}%c %{g}]'
vbell off
EOF

mkdir -p /etc/skel/.mc/
chmod 700 /etc/skel/.mc

cat > /etc/skel/.mc/ini << EOF
[Midnight-Commander]
auto_save_setup=0
drop_menus=1
use_internal_edit=1
confirm_exit=0
[Layout]
menubar_visible=0
message_visible=0
EOF

cp -r /etc/skel/.mc /root/
sed -i.orig 's/mc-wrapper.sh/mc-wrapper.sh --nomouse/' /etc/profile.d/mc.sh

#Disable the GUI Wizzard
tmsh modify sys global-settings gui-setup disabled

#SSL certificate
SUBJ="
C=CZ
ST=Czech Republic
O=Example, Inc.
localityName=Brno
commonName=cloud.example.com Certificate Authority
"
openssl req -x509 -nodes -subj "$(echo -n "$SUBJ" | tr "\n" "/")" -newkey rsa:2048 -keyout /config/ssl/ssl.key/cloud.example.com_self-signed_2014.key -out /config/ssl/ssl.crt/cloud.example.com_self-signed_2014.crt -days 3650

tmsh install /sys crypto key cloud.example.com_self-signed_2014.key from-local-file /config/ssl/ssl.key/cloud.example.com_self-signed_2014.key
tmsh install /sys crypto cert cloud.example.com_self-signed_2014.crt from-local-file /config/ssl/ssl.crt/cloud.example.com_self-signed_2014.crt
'''
