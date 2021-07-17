#!/bin/sh

# Writes an APR1-format password hash to the provided <htpasswd-file> for a provided <username>
# This is useful where an alternative web server (e.g. nginx) supports APR1 but no `htpasswd` is installed.
# The APR1 format provides signifcantly stronger password validation, and is described here: 
#	 http://httpd.apache.org/docs/current/misc/password_encryptions.html
# 設定 sudo chmod +x htpasswd-ssl.sh
#
# 使用 command 方式 : sudo ./htpasswd-ssl.sh /etc/nginx/pwdfile USERNAME
# 使用 command 方式 : sudo ./htpasswd-ssl.sh /etc/nginx/conf.d/.pwdfile username
#
help (){
cat <<EOF
  Usage: $0 <htpasswd-file> <username>
  Prompts for password (twice) via openssl.
EOF
}

[ $# -lt 2 ] && help;
[ $# -eq 2 ] && printf "${2}:`openssl passwd -apr1`\n" >> ${1}

#################################################
    #
    #目錄權限設定
    #
    location /phpMyAdmin/ {
        auth_basic              "Restricted Access!";
        auth_basic_user_file    /etc/nginx/conf.d/.pwdfile; 
    }
 #######################################################
 
 
 
 
