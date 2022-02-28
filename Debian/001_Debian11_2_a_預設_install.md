###001_Debian11_2_a_預設_install.md
# 001_Debian11_2_a_預設_install.md
#### 新增 rc.local
----
```
# ###001_Debian11_c預設_install.md
#更新安裝python 3.9.7
# 20211125 更新使用python 3.10.0
#
# Run all commands logged in as root or "sudo su - "
# Start from a base Debian 10 install and update it to current.
# Add backports repo so that we can install odbc-mariadb.
fname = $BASH_SOURCE
#安裝3.10.0

apt update && apt upgrade -y

# 舊版 For Debian10
#apt install wget build-essential libreadline-gplv2-dev libncursesw5-dev libssl-dev libsqlite3-dev tk-dev libgdbm-dev libc6-dev libbz2-dev libffi-dev zlib1g-dev -y

apt install wget build-essential lib32readline6-dev libncursesw5-dev libssl-dev libsqlite3-dev tk-dev libgdbm-dev libc6-dev libbz2-dev libffi-dev zlib1g-dev -y

wget https://www.python.org/ftp/python/3.10.0/Python-3.10.0.tgz 

tar xzf Python-3.10.0.tgz

cd Python-3.10.0
./configure --enable-optimizations 

make altinstall

#make
#make install

#update-alternatives --install /usr/bin/python python /usr/bin/python2.7 1
update-alternatives --install /usr/bin/python python /usr/bin/python3.10 1
#
update-alternatives --install /usr/bin/python python /usr/local/bin/python3.10 2

#apt install python3-pip -y

ln -s /usr/local/bin/pip3.10 /usr/bin/pip

#
apt update
apt upgrade -y
# Install all prerequisite packages

# apt install exuberant-ctags universal-ctags -y

apt install make gcc curl wget vim git-core git tree libsnmp-dev tcpdump htop iftop dnsutils libcurl4-gnutls-dev libxml2-dev libevent-dev linux-headers-`uname -r` build-essential -y
apt install exuberant-ctags universal-ctags -y
apt install net-tools sudo vim-gtk ack-grep -y
apt install expect -y
apt install nodejs -y
apt install yarn rdate -y
apt install chrony -y
apt install libssl-dev libghc-zlib-dev libexpat1-dev gettext unzip -y
apt install fping graphviz imagemagick mtr-tiny acl tmux -y
apt install fail2ban -y
apt install cmake -y
apt install python3.9-dev -y
# apt install python3.10-dev
# 稽核指令
apt install auditd -y

###apt install vim-gtk -y
apt install whois rrdtool librrds-perl python3-memcache python3-mysqldb -y

apt install libssl-dev libghc-zlib-dev libexpat1-dev -y
#
# 安裝 vim 所需套件
apt install silversearcher-ag fzf -y
#
apt install -y libclang-dev
#安裝 處理 json 分割使用
apt install -y jq bc

#
#安裝 iptable 本機防火牆-所需套件
apt install iptables-persistent -y
#
#
# 安裝 vim 和 power line
#
sudo apt install -y exuberant-ctags cscope vim-gtk git flake8 python3-rope pylint
git clone https://github.com/VundleVim/Vundle.vim.git ~/.vim/bundle/Vundle.vim
git clone https://github.com/powerline/fonts
cd fonts && ./install.sh

pip install powerline-status
pip install powerline-gitstatus
#
cd ~
git clone https://github.com/oscarobwu/vim-temp.git

#copy f5_snmp to mibs
cp -r vim-temp/snmp_f5/* /usr/share/snmp/mibs/
#
cd vim-temp
cp .vimrc ~/.vimrc
cp -R .vim/* /root/.vim


cd ~
git clone https://github.com/oscarobwu/oscar-pureline.git
cp oscar-pureline/configs/powerline_full_256col.conf ~/.pureline.conf
mv oscar-pureline pureline
#
cat <<'EOF' > /root/.bashrc
# ~/.bashrc: executed by bash(1) for non-login shells.
# Note: PS1 and umask are already set in /etc/profile. You should not
# need this unless you want different defaults for root.
# PS1='${debian_chroot:+($debian_chroot)}\h:\w\$ '
# umask 022
# You may uncomment the following lines if you want `ls' to be colorized:
# export LS_OPTIONS='--color=auto'
# eval "`dircolors`"
# alias ls='ls $LS_OPTIONS'
# alias ll='ls $LS_OPTIONS -l'
# alias l='ls $LS_OPTIONS -lA'
#
# Some more alias to avoid making mistakes:
# alias rm='rm -i'
# alias cp='cp -i'
# alias mv='mv -i'
export LS_OPTIONS='--color=auto'
eval "`dircolors`"
alias ls='ls $LS_OPTIONS'
#
if [ "$TERM" != "linux" ]; then
    source ~/pureline/pureline ~/.pureline.conf
fi
#
function aa_prompt_defaults ()
{
   local colors=`tput colors 2>/dev/null||echo -n 1` C=;
   if [[ $colors -ge 256 ]]; then
      C="`tput setaf 33 2>/dev/null`";
      AA_P='mf=x mt=x n=0; while [[ $n < 1 ]];do read a mt a; read a mf a; (( n++ )); done</proc/meminfo; export AA_PP="\033[38;5;2m"$((mf/1024))/"\033[38;5;89m"$((mt/1024))MB; unset -v mf mt n a';
   else
      C="`tput setaf 4 2>/dev/null`";
      AA_P='mf=x mt=x n=0; while [[ $n < 1 ]];do read a mt a; read a mf a; (( n++ )); done</proc/meminfo; export AA_PP="\033[92m"$((mf/1024))/"\033[32m"$((mt/1024))MB; unset -v mf mt n a';
   fi;
   eval $AA_P;
   PROMPT_COMMAND='stty echo; history -a; echo -en "\e[34h\e[?25h"; (($SECONDS % 2==0 )) && eval $AA_P; echo -en "$AA_PP";';
   SSH_TTY=${SSH_TTY:-`tty 2>/dev/null||readlink /proc/$$/fd/0 2>/dev/null`}
   PS1="\[\e[m\n\e[1;30m\][\$\$:\$PPID \j:\!\[\e[1;30m\]]\[\e[0;36m\] \T \d \[\e[1;30m\][${C}\u@\H\[\e[1;30m\]:\[\e[0;37m\]${SSH_TTY/\/dev\/} \[\e[0;32m\]+${SHLVL}\[\e[1;30m\]] \[\e[1;37m\]\w\[\e[0;37m\]\n\\$ ";
   export PS1 AA_P PROMPT_COMMAND SSH_TTY
}
#RED='\033[0;31m'
# Set the prompt to include the IP address instead of hostname
function get_ip () {
  IFACE=$(ip -4 route | grep default | head -n1 | awk '{print $5}')
  if [ ! -z $IFACE ]; then
    echo -n "-"; ip -4 -o addr show scope global $IFACE | awk '{gsub(/\/.*/, "-",$4); print $4}' | paste -s -d ""
  else
    echo -n "||"
  fi
}
#if [ "$color_prompt" = yes ]; then
#    PS1='${debian_chroot:+($debian_chroot)}\[\033[01;32m\]\u\[\033[01;34m\]@\[\033[32m\]$(get_ip)\[\033[00m\]:\[\033[01;34m\]\w\[\033[00m\]\$ '
#else
#    PS1='\[\e[1;35m\][\[\[\e[1;36m\]\t \e[1;33m\]\u@\h \033[42;37m $(get_ip) \033[0m \[\e[1;31m\]\w\[\e[1;35m\]] : \n\[\e[1;36m\]\$ \[\e[0m\] '
#fi
#PY3_REPO_ROOT=/usr/local/lib/python3.7/dist-packages
#
#powerline-daemon -q
#POWERLINE_BASH_CONTINUATION=1
#POWERLINE_BASH_SELECT=1
#source $PY3_REPO_ROOT/powerline/bindings/bash/powerline.sh
#
# Powerline configuration
#eval "$(starship init bash)"
#Set variables for foreground colors
#
# Colorcodes
#
NORMAL=`echo -e '\033[0m'`
RED=`echo -e '\033[31m'`
GREEN=`echo -e '\033[0;32m'`
LGREEN=`echo -e '\033[1;32m'`
BLUE=`echo -e '\033[0;34m'`
LBLUE=`echo -e '\033[1;34m'`
YELLOW=`echo -e '\033[0;33m'`
#
# command: ip
# highlight ip addresses, default route and interface names
#
IP4=$RED
IP6=$LBLUE
IFACE=${YELLOW}
DEFAULT_ROUTE=$LBLUE
IP_CMD=$(which ip)
function colored_ip()
{
${IP_CMD} $@ | sed \
    -e "s/inet [^ ]\+ /${IP4}&${NORMAL}/g"\
    -e "s/inet6 [^ ]\+ /${IP6}&${NORMAL}/g"\
    -e "s/^default via .*$/${DEFAULT_ROUTE}&${NORMAL}/"\
    -e "s/^\([0-9]\+: \+\)\([^ \t]\+\)/\1${IFACE}\2${NORMAL}/"
}
alias ip='colored_ip'
EOF

apt install snmpd snmp smistrip -y

wget http://ftp.us.debian.org/debian/pool/non-free/s/snmp-mibs-downloader/snmp-mibs-downloader_1.5_all.deb

dpkg -i snmp-mibs-downloader_1.5_all.deb

apt-get install open-vm-tools open-vm-tools-desktop -y

rm snmp-mibs-downloader_1.5_all.deb

cat <<EOF > /etc/network/interfaces
# This file describes the network interfaces available on your system
# and how to activate them. For more information, see interfaces(5).
source /etc/network/interfaces.d/*
# The loopback network interface
auto lo
iface lo inet loopback
# The primary network interface
auto ens192
#allow-hotplug ens192
iface ens192 inet dhcp
#iface ens192 inet static
#address 192.168.96.211
#netmask 255.255.255.0
#gateway 192.168.96.100
#up route add -net xxx.xxx.x.0 netmask 255.255.255.0 gw 192.168.96.254
#down route del -net xxx.xxx.x.0 netmask 255.255.255.0 gw 192.168.96.254
#
#
#
EOF

# 關閉 beep 聲音
sed -i 's/# set bell-style none/set bell-style none/g' /etc/inputrc
# 關閉 ipv6
#sed -i 's/#net.ipv6.conf.all.disable_ipv6 = 1/net.ipv6.conf.all.disable_ipv6 = 1/g' /etc/sysctl.conf
#
/usr/local/bin/python3.10 -m pip install --upgrade pip
#
pip install Stats

pip install f5-sdk

pip install bigsuds

pip install netaddr

pip install numpy

pip install pandas

pip install common

pip install ciscoconfparse

pip install bigrest

pip install openpyxl xlsxwriter xlrd
# 顯示 excel 顏色
pip install Jinja2
#
pip install psutil netifaces
#
pip install jedi

pip install xpinyin
#
pip install PyNaCl
#
pip install netmiko

#pip install idlelib
pip install pyyaml

#pip install f5-sdk bigsuds netaddr deepdiff request objectpath openpyxl
pip install deepdiff objectpath
#
pip install jmespath
#
pip install sqlalchemy-utils

pip install influxdb-client

apt install -y idle idle3


cat <<'EOF' > /etc/bash.bashrc
# System-wide .bashrc file for interactive bash(1) shells.
# To enable the settings / commands in this file for login shells as well,
# this file has to be sourced in /etc/profile.
# If not running interactively, don't do anything
[ -z "$PS1" ] && return
# check the window size after each command and, if necessary,
# update the values of LINES and COLUMNS.
shopt -s checkwinsize
# set variable identifying the chroot you work in (used in the prompt below)
if [ -z "${debian_chroot:-}" ] && [ -r /etc/debian_chroot ]; then
    debian_chroot=$(cat /etc/debian_chroot)
fi
# set a fancy prompt (non-color, overwrite the one in /etc/profile)
# but only if not SUDOing and have SUDO_PS1 set; then assume smart user.
if ! [ -n "${SUDO_USER}" -a -n "${SUDO_PS1}" ]; then
  #PS1='${debian_chroot:+($debian_chroot)}\u@\h:\w\$ '
  PS1='\[\e[1;35m\][\[\[\e[1;36m\]\t \e[1;33m\]\u@\h \[\e[1;31m\]\w\[\e[1;35m\]]\[\e[1;36m\]\$ \[\e[0m\]'
fi
# Commented out, don't overwrite xterm -T "title" -n "icontitle" by default.
# If this is an xterm set the title to user@host:dir
#case "$TERM" in
#xterm*|rxvt*)
#    PROMPT_COMMAND='echo -ne "\033]0;${USER}@${HOSTNAME}: ${PWD}\007"'
#    ;;
#*)
#    ;;
#esac
# enable bash completion in interactive shells
#if ! shopt -oq posix; then
#  if [ -f /usr/share/bash-completion/bash_completion ]; then
#    . /usr/share/bash-completion/bash_completion
#  elif [ -f /etc/bash_completion ]; then
#    . /etc/bash_completion
#  fi
#fi
# if the command-not-found package is installed, use it
if [ -x /usr/lib/command-not-found -o -x /usr/share/command-not-found/command-not-found ]; then
        function command_not_found_handle {
                # check because c-n-f could've been removed in the meantime
                if [ -x /usr/lib/command-not-found ]; then
                   /usr/lib/command-not-found -- "$1"
                   return $?
                elif [ -x /usr/share/command-not-found/command-not-found ]; then
                   /usr/share/command-not-found/command-not-found -- "$1"
                   return $?
                else
                   printf "%s: command not found\n" "$1" >&2
                   return 127
                fi
        }
fi
EOF

#新增防火牆
cat <<'EOF' > /usr/local/bin/firewall.sh
#!/bin/sh
# ---------------------------------------------------------------------------------------
# 適用環境：單機防火牆－僅使用一張網卡，無NAT功能
# Command : /usr/local/bin/firewall.sh start
# ---------------------------------------------------------------------------------------
## -------------------------- 防火牆規則設定區段 -------------------------- ##
BADIPS=""

# 
IMPOSSIBLE_IPS="10.0.0.0/8 172.16.0.0/12"

# 允許對內連線的 TCP 通訊埠
IN_TCP_PORTALLOWED="
22,192.168.88.250
22,192.168.88.251
80
443
3306
8086"


#
IN_UDP_PORTALLOWED=""

#
IN_ICMP_ALLOWED="8"
# ---------------------------------------------------------------------------------------
#
#
echo -n "Initiating iptables..."
iptables -P INPUT ACCEPT
iptables -P OUTPUT ACCEPT
iptables -P FORWARD ACCEPT
iptables -t filter -F
iptables -t nat -F
iptables -t filter -X
iptables -t nat -X
echo "OK"
##
[ "$1" = "start" ] && skiptest="1"
## ------------------------ 設定核心的安全相關參數 ------------------------ ##
#
echo 1 > /proc/sys/net/ipv4/icmp_echo_ignore_broadcasts
#
echo 1 > /proc/sys/net/ipv4/icmp_ignore_bogus_error_responses
#
  for i in /proc/sys/net/ipv4/conf/*/accept_source_route; do
  echo "0" > $i
  done
#
  for i in /proc/sys/net/ipv4/conf/*/accept_redirects; do
  echo "0" > $i
  done
#
  for i in /proc/sys/net/ipv4/conf/*/send_redirects; do
  echo "0" > $i
  done
#
  for i in /proc/sys/net/ipv4/conf/*/rp_filter; do
  echo "1" > $i
  done
#
echo 1 > /proc/sys/net/ipv4/tcp_syncookies
#
echo 3 > /proc/sys/net/ipv4/tcp_retries1
echo 30 > /proc/sys/net/ipv4/tcp_fin_timeout
echo 1400 > /proc/sys/net/ipv4/tcp_keepalive_time
echo 0 > /proc/sys/net/ipv4/tcp_window_scaling
echo 0 > /proc/sys/net/ipv4/tcp_sack
echo 0 > /proc/sys/net/ipv4/tcp_timestamps
# ---------------------------------------------------------------------------------------
## ---------------------- 預設阻擋所有連線的基本原則 ---------------------- ##
# 
echo -n "Setting firewall rules......" 
# 
iptables -P INPUT DROP
# 
iptables -P OUTPUT ACCEPT
#
iptables -P FORWARD DROP
## ------------------ 設定本機內部 lookback 連線相關規則 ------------------ ##
# 
iptables -A INPUT -i lo -j ACCEPT
## -------------------------- 阻擋可疑狀態的封包 -------------------------- ##
# 
iptables -N BADPKT
# 
iptables -A BADPKT -j DROP
# 
iptables -A INPUT -m state --state INVALID -j BADPKT
iptables -A INPUT -p tcp ! --syn -m state --state NEW -j BADPKT
iptables -A INPUT -p tcp --tcp-flags ALL NONE -j BADPKT
iptables -A INPUT -p tcp --tcp-flags SYN,FIN SYN,FIN -j BADPKT
iptables -A INPUT -p tcp --tcp-flags SYN,RST SYN,RST -j BADPKT
iptables -A INPUT -p tcp --tcp-flags FIN,RST FIN,RST -j BADPKT
iptables -A INPUT -p tcp --tcp-flags ACK,FIN FIN -j BADPKT
iptables -A INPUT -p tcp --tcp-flags ACK,URG URG -j BADPKT
iptables -A INPUT -p tcp --tcp-flags ACK,PSH PSH -j BADPKT
iptables -A INPUT -p tcp --tcp-flags ALL FIN,URG,PSH -j BADPKT
iptables -A INPUT -p tcp --tcp-flags ALL SYN,RST,ACK,FIN,URG -j BADPKT
iptables -A INPUT -p tcp --tcp-flags ALL ALL -j BADPKT
iptables -A INPUT -p tcp --tcp-flags ALL FIN -j BADPKT

# 
iptables -A INPUT -m state --state ESTABLISHED,RELATED -j ACCEPT
## -------------------------- 阻擋特定 IP 的連線 -------------------------- ##
# 
iptables -N BADIP

# 
iptables -A BADIP -j DROP

#
for ip in $BADIPS $IMPOSSIBLE_IPS ; do
   iptables -A INPUT -s $ip -j BADIP
done
## -------------------------- 允許特定 IP 的連線 -------------------------- ##
# 
for i in $IN_TCP_PORTALLOWED ; do
   IFS=','
   set $i
   unset IFS ipt_option

   port="$1"
   [ -n "$2" ] && ipt_option="-s `echo $2 | sed 's/^!/! /'`"

  iptables -A INPUT -p tcp $ipt_option --dport $port --syn -m state --state NEW -j ACCEPT
done

# 
for i in $IN_UDP_PORTALLOWED ; do
   IFS=','
   set $i
   unset IFS ipt_option

   port="$1"
   [ -n "$2" ] && ipt_option="-s `echo $2 | sed 's/^!/! /'`"

  iptables -A INPUT -p udp $ipt_option --dport $port -m state --state NEW -j ACCEPT
done

# 
for i in $IN_ICMP_ALLOWED ; do
   IFS=','
   set $i
   unset IFS ipt_option

   type="$1"
   [ -n "$2" ] && ipt_option="-s `echo $2 | sed 's/^!/! /'`"
   
  iptables -A INPUT -p icmp $ipt_option --icmp-type $type -m state --state NEW -j ACCEPT
done

# 
iptables -A OUTPUT -m state --state NEW -j ACCEPT
## ------------------------------- 結束訊息 ------------------------------- ##
echo "OK"
# -----------------------------------------------------------------------------
if [ "$skiptest" = "1" ]; then exit ;fi

echo -e "\n     TEST MODE"
echo -n "All chains will be cleaned after 7 sec."

i=1; while [ "$i" -le "7" ]; do
   echo -n "."
   i=`expr $i + 1`
   sleep 1
done

echo -en "\nFlushing ruleset..."
iptables -P INPUT ACCEPT
iptables -P OUTPUT ACCEPT
iptables -P FORWARD ACCEPT
iptables -t filter -F
iptables -t nat -F
iptables -t filter -X
iptables -t nat -X
echo "OK"
# -----------------------------------------------------------------------------
# ---------------------------------------------------------------------------------------
EOF
#
chmod +x /usr/local/bin/firewall.sh
# 新增 rc.local
cat <<'EOF' > /etc/rc.local
#!/bin/sh -e
#
# rc.local
#
# This script is executed at the end of each multiuser runlevel.
# Make sure that the script will "exit 0" on success or any other
# value on error.
#
# In order to enable or disable this script just change the execution
# bits.
#
# By default this script does nothing.
/usr/local/bin/firewall.sh start

exit 0
EOF

chmod +x /etc/rc.local
#
systemctl start rc-local
#
cd ~
rm Python-3.10.0.tgz
rm -R Python-3.10.0
#
rm -R vim-temp
#
#新增 檔案系統優化
cat <<'EOF' > /etc/security/limits.conf
# /etc/security/limits.conf
#
#Each line describes a limit for a user in the form:
#
#<domain>        <type>  <item>  <value>
#
#Where:
#<domain> can be:
#        - a user name
#        - a group name, with @group syntax
#        - the wildcard *, for default entry
#        - the wildcard %, can be also used with %group syntax,
#                 for maxlogin limit
#        - NOTE: group and wildcard limits are not applied to root.
#          To apply a limit to the root user, <domain> must be
#          the literal username root.
#
#<type> can have the two values:
#        - "soft" for enforcing the soft limits
#        - "hard" for enforcing hard limits
#
#<item> can be one of the following:
#        - core - limits the core file size (KB)
#        - data - max data size (KB)
#        - fsize - maximum filesize (KB)
#        - memlock - max locked-in-memory address space (KB)
#        - nofile - max number of open file descriptors
#        - rss - max resident set size (KB)
#        - stack - max stack size (KB)
#        - cpu - max CPU time (MIN)
#        - nproc - max number of processes
#        - as - address space limit (KB)
#        - maxlogins - max number of logins for this user
#        - maxsyslogins - max number of logins on the system
#        - priority - the priority to run user process with
#        - locks - max number of file locks the user can hold
#        - sigpending - max number of pending signals
#        - msgqueue - max memory used by POSIX message queues (bytes)
#        - nice - max nice priority allowed to raise to values: [-20, 19]
#        - rtprio - max realtime priority
#        - chroot - change root to directory (Debian-specific)
#
#<domain>      <type>  <item>         <value>
#

#*               soft    core            0
#root            hard    core            100000
#*               hard    rss             10000
#@student        hard    nproc           20
#@faculty        soft    nproc           20
#@faculty        hard    nproc           50
#ftp             hard    nproc           0
#ftp             -       chroot          /ftp
#@student        -       maxlogins       4
root soft nofile 65536
root hard nofile 65536
* soft nofile 65536
* hard nofile 65536
# End of file

EOF

cat << 'EOF' > /etc/issue
Debian GNU/Linux 11 \n \l

My IP address: \4 \l

\d \t

EOF

#
# 網路優化 來自netflex 設定檔
cat <<'EOF' > /etc/security/limits.conf
# /etc/sysctl.conf - Configuration file for setting system variables
# See /etc/sysctl.d/ for additional system variables.
# See sysctl.conf (5) for information.
#

#kernel.domainname = example.com

# Uncomment the following to stop low-level messages on console
#kernel.printk = 3 4 1 3

###################################################################
# Functions previously found in netbase
#

# Uncomment the next two lines to enable Spoof protection (reverse-path filter)
# Turn on Source Address Verification in all interfaces to
# prevent some spoofing attacks
#net.ipv4.conf.default.rp_filter=1
#net.ipv4.conf.all.rp_filter=1

# Uncomment the next line to enable TCP/IP SYN cookies
# See http://lwn.net/Articles/277146/
# Note: This may impact IPv6 TCP sessions too
#net.ipv4.tcp_syncookies=1

# Uncomment the next line to enable packet forwarding for IPv4
#net.ipv4.ip_forward=1

# Uncomment the next line to enable packet forwarding for IPv6
#  Enabling this option disables Stateless Address Autoconfiguration
#  based on Router Advertisements for this host
#net.ipv6.conf.all.forwarding=1


###################################################################
# Additional settings - these settings can improve the network
# security of the host and prevent against some network attacks
# including spoofing attacks and man in the middle attacks through
# redirection. Some network environments, however, require that these
# settings are disabled so review and enable them as needed.
#
# Do not accept ICMP redirects (prevent MITM attacks)
#net.ipv4.conf.all.accept_redirects = 0
#net.ipv6.conf.all.accept_redirects = 0
# _or_
# Accept ICMP redirects only for gateways listed in our default
# gateway list (enabled by default)
# net.ipv4.conf.all.secure_redirects = 1
#
# Do not send ICMP redirects (we are not a router)
#net.ipv4.conf.all.send_redirects = 0
#
# Do not accept IP source route packets (we are not a router)
#net.ipv4.conf.all.accept_source_route = 0
#net.ipv6.conf.all.accept_source_route = 0
#
# Log Martian Packets
#net.ipv4.conf.all.log_martians = 1
#
root soft nofile 65536
root hard nofile 65536
* soft nofile 65536
* hard nofile 65536
###################################################################
# Magic system request Key
# 0=disable, 1=enable all, >1 bitmask of sysrq functions
# See https://www.kernel.org/doc/html/latest/admin-guide/sysrq.html
# for what other values do
#kernel.sysrq=438

net.core.somaxconn = 1024
net.core.netdev_max_backlog = 5000
net.core.rmem_max = 16777216
net.core.wmem_max = 16777216
net.ipv4.tcp_wmem = 4096 12582912 16777216
net.ipv4.tcp_rmem = 4096 12582912 16777216
net.ipv4.tcp_max_syn_backlog = 8096
net.ipv4.tcp_slow_start_after_idle = 0
net.ipv4.tcp_tw_reuse = 1
net.ipv4.ip_local_port_range = 10240 65535
EOF
#
###
currentscript="$0"

# Function that is called when the script exits:
function finish {
    echo "Securely shredding ${currentscript}"; shred -u ${currentscript};
}

# Do your bashing here...

# When your script is finished, exit with a call to the function, "finish":
trap finish EXIT
```

```
vi install plug
:PlugInstall
#####################################
重起啟動  YouCompleteme

~/.vim/plugged/YouCompleteMe/install.py --clang-completer

如果遇到失敗 先更新git
cd /root/.vim/plugged/YouCompleteMe/third_party/ycmd/third_party

git submodule update --init --recursive


#####################################



cat /etc/issue

Debian GNU/Linux 10 \n \l

My IP address: \4 \l

\d \t
```

### Debian 11 把 rc.local 加回來

```
cat <<EOF >/etc/rc.local
#!/bin/sh -e
#
# rc.local
#
# This script is executed at the end of each multiuser runlevel.
# Make sure that the script will "exit 0" on success or any other
# value on error.
#
# In order to enable or disable this script just change the execution
# bits.
#
# By default this script does nothing.

exit 0
EOF

```
#### 接著我們賦予 rc.local 可執行的權限

```
chmod +x /etc/rc.local

```
#### 再來就可以啟動 rc-local 服務了

```language
systemctl start rc-local

```

觀察看看是否有正確運作
```language
systemctl status rc-local

```
### 新增 fail2ban 條件規則

```
內容如下 :
##########
[sshd]

# To use more aggressive sshd modes set filter parameter "mode" in jail.local:
# normal (default), ddos, extra or aggressive (combines all).
# See "tests/files/logs/sshd" or "filter.d/sshd.conf" for usage example and details.
#mode   = normal
port    = ssh
logpath = %(sshd_log)s
backend = %(sshd_backend)s
enabled = true
maxretry = 3
findtime  = 1d
bantime   = 4w
ignoreip  = 127.0.0.1/8 192.168.88.250

#########################
# 封鎖 IP
sudo fail2ban-client set sshd banip <ip address>
# 解鎖 IP
sudo fail2ban-client set sshd unbanip <ip address>

```
### 設定 ssh 登入


```bash
vi /etc/ssh/sshd_config

請不允許root登入，現在系統預設都不是允許，所以將這行註解

# PermitRootLogin yes
允許 oscar 任何地方登入，只需列出用戶名：

AllowUsers oscar
上面的AllowUsers 一旦有設定，未在表列中的用戶就再也無法登入

允許root從192.168.1.32登入：

AllowUsers root@192.168.88.250
這項設定必需允許 PermitRootLogin yes

允許整個網段登入

AllowUsers root@10.200.*
可以在同一行指定多個帳戶登入用，注意IPv6 的寫法

AllowUsers oscar@10.* oscar@172.109.* oscar@2401:e180:8991:*
使用群組限制，只有允許群組 user 登入

AllowGroups user

設定完記得重啟 ssh
systemctl restart sshd
```
### 增加文件最大數量

```
# 查看当前系统的 Liunx 文件描述符最大数量
$ ulimit -n
65535

如果是 1024，需要設置文件/etc/security/limits.conf，文件數量。改完需要重啟。

root soft nofile 65536
root hard nofile 65536
* soft nofile 65536
* hard nofile 65536
```
### 優化內核網絡參數
##### 配置文件/etc/sysctl.conf 如下，提高了網絡性能。
```
net.core.somaxconn = 1024
net.core.netdev_max_backlog = 5000
net.core.rmem_max = 16777216
net.core.wmem_max = 16777216
net.ipv4.tcp_wmem = 4096 12582912 16777216
net.ipv4.tcp_rmem = 4096 12582912 16777216
net.ipv4.tcp_max_syn_backlog = 8096
net.ipv4.tcp_slow_start_after_idle = 0
net.ipv4.tcp_tw_reuse = 1
net.ipv4.ip_local_port_range = 10240 65535

性能調優參考 Netflix 如何調優 EC2 實例的性能。

```

```
清除 防火牆規則
iptables -P INPUT ACCEPT
iptables -P FORWARD ACCEPT
iptables -P OUTPUT ACCEPT
iptables -t nat -F
iptables -t mangle -F
iptables -F
iptables -X

ip6tables -P INPUT ACCEPT
ip6tables -P FORWARD ACCEPT
ip6tables -P OUTPUT ACCEPT
ip6tables -t nat -F
ip6tables -t mangle -F
ip6tables -F
ip6tables -X

```

## influxdb 資料保存
```
# 修改儲存資料時間
ALTER RETENTION POLICY "autogen" ON "telegraf" DURATION 40d REPLICATION 1
```

## 如果遇到 jenkins 還原失敗

```language


[ INFO] Restore started at [08/28/15 05:58:06]
[ INFO] Working into /var/lib/jenkins_restore directory
[ INFO] A old restore working dir exists, cleaning ...
[ERROR] Unable to delete /var/lib/jenkins_restore

```

```language
sudo chmod 777 /var/lib
... perform restore ...
sudo chmod 755 /var/lib

```

```language
sudo mkdir /var/lib/jenkins_restore
sudo chown jenkins /var/lib/jenkins_restore

```

```
<!DOCTYPE html>
<html>
<head>
   <!-- HTML meta refresh URL redirection -->
   <meta http-equiv="refresh"
   content="5; url=/jenkins">
</head>
<body>
   <p>The page has moved to:<br>
     <a href="/jenkins">jenkins this page</a></p><br>
     <a href="/gitlab">GitLab this page</a></p>
     <a href="/guacamole">Guacamole Remote</a></p>
     <a href="/grafana">Grafan Monitor Dashboard</a></p>
</body>
</html>
```
