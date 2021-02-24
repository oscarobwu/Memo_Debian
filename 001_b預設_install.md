```
# Run all commands logged in as root or "sudo su - "
# Start from a base Debian 10 install and update it to current.
# Add backports repo so that we can install odbc-mariadb.
fname = $BASH_SOURCE

update-alternatives --install /usr/bin/python python /usr/bin/python2.7 1
#
update-alternatives --install /usr/bin/python python /usr/bin/python3.7 2

apt install python3-pip -y
#
ln -s /usr/bin/pip3 /usr/bin/pip
#
apt update
apt upgrade -y
# Install all prerequisite packages

apt install make gcc curl wget vim git-core git tree libsnmp-dev tcpdump htop iftop dnsutils libcurl4-gnutls-dev libxml2-dev libevent-dev linux-headers-`uname -r` build-essential -y
apt install net-tools sudo vim-gtk ack-grep ctags  -y
apt install expect -y
apt install nodejs -y
apt install yarn rdate -y
apt install libssl-dev libghc-zlib-dev libexpat1-dev gettext unzip -y
apt install fping graphviz imagemagick mtr-tiny acl tmux -y

###apt install vim-gtk -y
apt install whois rrdtool librrds-perl python-memcache python-mysqldb -y

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
sudo apt install -y exuberant-ctags cscope vim-gtk git flake8 python-rope pylint
git clone https://github.com/VundleVim/Vundle.vim.git ~/.vim/bundle/Vundle.vim
git clone https://github.com/powerline/fonts
cd fonts && ./install.sh

pip install powerline-status
pip install powerline-gitstatus
#
git clone https://github.com/oscarobwu/vim-temp.git

#copy f5_snmp to mibs
cp -r vim-temp/snmp_f5/* /usr/share/snmp/mibs/
#
cd vim-temp
cp .vimrc ~/.vimrc
cp -R .vim/* ../.vim


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
allow-hotplug ens192
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
