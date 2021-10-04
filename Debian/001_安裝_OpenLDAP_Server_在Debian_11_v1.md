# OpenLDAP Server 在Debian 上
----
OpenLDAP 套件

* slapd – stand-alone LDAP daemon (server)
* libraries implementing the LDAP protocol, and
* libraries implementing the LDAP protocol, and
utilities, tools, and sample clients.

```
確認版本

apt-cache policy slapd

slapd:
  Installed: (none)
  Candidate: 2.4.57+dfsg-3
  Version table:
     2.4.57+dfsg-3 500
        500 http://deb.debian.org/debian bullseye/main amd64 Packages

```
原本的apt是比較舊的版本 所以使用另外一種安裝方式

安裝 較新的版本 OpenLDAP 2.5.7

### Create OpenLDAP System Account

```language
useradd -r -M -d /var/lib/openldap -s /usr/sbin/nologin ldap

```
#### 安裝需要的套件

```
apt install libsasl2-dev make libtool build-essential openssl libevent-dev \
libargon2-dev sudo wget pkg-config wiredtiger libsystemd-dev libssl-dev
```

### 下載 OpenLDAP Source Code

```
VER=2.5.7

wget https://www.openldap.org/software/download/OpenLDAP/openldap-release/openldap-$VER.tgz

tar xzf openldap-$VER.tgz

cd openldap-$VER

./configure --prefix=/usr --sysconfdir=/etc --disable-static --enable-debug \
--with-tls=openssl --with-cyrus-sasl --enable-dynamic --enable-crypt --enable-spasswd \
--enable-slapd --enable-modules --enable-rlookups --enable-backends=mod --disable-ndb \
--disable-sql --enable-ppolicy=mod --enable-syslog --enable-overlays=mod --with-systemd --enable-wt=no

最後須呈現以下log

...
config.status: executing libtool commands
config.status: executing default commands
Making servers/slapd/backends.c
    Add config ...
    Add ldif ...
    Add monitor ...
Making servers/slapd/overlays/statover.c
    Add ppolicy ...
Please run "make depend" to build dependencies


make depend

make

make install


```

### 設定 OpenLDAP on Debian 11

```language

mkdir /var/lib/openldap /etc/openldap/slapd.d

設定檔案權限
chown -R ldap:ldap /var/lib/openldap

chown root:ldap /etc/openldap/slapd.conf

chmod 640 /etc/openldap/slapd.conf

```

### 更新 OpenLDAP Service

```language
備份檔案

mv /lib/systemd/system/slapd.service{,.old}

cat > /etc/systemd/system/slapd.service << 'EOL'
[Unit]
Description=OpenLDAP Server Daemon
After=syslog.target network-online.target
Documentation=man:slapd
Documentation=man:slapd-mdb

[Service]
Type=forking
PIDFile=/var/lib/openldap/slapd.pid
Environment="SLAPD_URLS=ldap:/// ldapi:/// ldaps:///"
Environment="SLAPD_OPTIONS=-F /etc/openldap/slapd.d"
ExecStart=/usr/libexec/slapd -u ldap -g ldap -h ${SLAPD_URLS} $SLAPD_OPTIONS

[Install]
WantedBy=multi-user.target
EOL



```

### 創建 OpenLDAP SUDO 架構

```language

apt install sudo-ldap

sudo -V |  grep -i "ldap"

顯示以下

...
ldap.conf path: /etc/sudo-ldap.conf
ldap.secret path: /etc/ldap.secret

find /usr/share/doc/ -iname schema.openldap

找到 /usr/share/doc/sudo-ldap/schema.OpenLDAP

cp /usr/share/doc/sudo-ldap/schema.OpenLDAP  /etc/openldap/schema/sudo.schema

cat << 'EOL' > /etc/openldap/schema/sudo.ldif
dn: cn=sudo,cn=schema,cn=config
objectClass: olcSchemaConfig
cn: sudo
olcAttributeTypes: ( 1.3.6.1.4.1.15953.9.1.1 NAME 'sudoUser' DESC 'User(s) who may  run sudo' EQUALITY caseExactIA5Match SUBSTR caseExactIA5SubstringsMatch SYNTAX 1.3.6.1.4.1.1466.115.121.1.26 )
olcAttributeTypes: ( 1.3.6.1.4.1.15953.9.1.2 NAME 'sudoHost' DESC 'Host(s) who may run sudo' EQUALITY caseExactIA5Match SUBSTR caseExactIA5SubstringsMatch SYNTAX 1.3.6.1.4.1.1466.115.121.1.26 )
olcAttributeTypes: ( 1.3.6.1.4.1.15953.9.1.3 NAME 'sudoCommand' DESC 'Command(s) to be executed by sudo' EQUALITY caseExactIA5Match SYNTAX 1.3.6.1.4.1.1466.115.121.1.26 )
olcAttributeTypes: ( 1.3.6.1.4.1.15953.9.1.4 NAME 'sudoRunAs' DESC 'User(s) impersonated by sudo (deprecated)' EQUALITY caseExactIA5Match SYNTAX 1.3.6.1.4.1.1466.115.121.1.26 )
olcAttributeTypes: ( 1.3.6.1.4.1.15953.9.1.5 NAME 'sudoOption' DESC 'Options(s) followed by sudo' EQUALITY caseExactIA5Match SYNTAX 1.3.6.1.4.1.1466.115.121.1.26 )
olcAttributeTypes: ( 1.3.6.1.4.1.15953.9.1.6 NAME 'sudoRunAsUser' DESC 'User(s) impersonated by sudo' EQUALITY caseExactIA5Match SYNTAX 1.3.6.1.4.1.1466.115.121.1.26 )
olcAttributeTypes: ( 1.3.6.1.4.1.15953.9.1.7 NAME 'sudoRunAsGroup' DESC 'Group(s) impersonated by sudo' EQUALITY caseExactIA5Match SYNTAX 1.3.6.1.4.1.1466.115.121.1.26 )
olcObjectClasses: ( 1.3.6.1.4.1.15953.9.2.1 NAME 'sudoRole' SUP top STRUCTURAL DESC 'Sudoer Entries' MUST ( cn ) MAY ( sudoUser $ sudoHost $ sudoCommand $ sudoRunAs $ sudoRunAsUser $ sudoRunAsGroup $ sudoOption $ description ) )
EOL

```

### 更新 SLAPD Database

```language

mv /etc/openldap/slapd.ldif{,.bak}

cat > /etc/openldap/slapd.ldif << 'EOL'
dn: cn=config
objectClass: olcGlobal
cn: config
olcArgsFile: /var/lib/openldap/slapd.args
olcPidFile: /var/lib/openldap/slapd.pid

dn: cn=schema,cn=config
objectClass: olcSchemaConfig
cn: schema

dn: cn=module,cn=config
objectClass: olcModuleList
cn: module
olcModulepath: /usr/libexec/openldap
olcModuleload: back_mdb.la

include: file:///etc/openldap/schema/core.ldif
include: file:///etc/openldap/schema/cosine.ldif
include: file:///etc/openldap/schema/nis.ldif
include: file:///etc/openldap/schema/inetorgperson.ldif
include: file:///etc/openldap/schema/sudo.ldif
#include: file:///etc/openldap/schema/ppolicy.ldif
dn: olcDatabase=frontend,cn=config
objectClass: olcDatabaseConfig
objectClass: olcFrontendConfig
olcDatabase: frontend
olcAccess: to dn.base="cn=Subschema" by * read
olcAccess: to * 
  by dn.base="gidNumber=0+uidNumber=0,cn=peercred,cn=external,cn=auth" manage 
  by * none

dn: olcDatabase=config,cn=config
objectClass: olcDatabaseConfig
olcDatabase: config
olcRootDN: cn=config
olcAccess: to * 
  by dn.base="gidNumber=0+uidNumber=0,cn=peercred,cn=external,cn=auth" manage 
  by * none
EOL


```

```language
先做 verify
slapadd -n 0 -F /etc/openldap/slapd.d -l /etc/openldap/slapd.ldif -u

在執行
slapadd -n 0 -F /etc/openldap/slapd.d -l /etc/openldap/slapd.ldif

ls /etc/openldap/slapd.d

'cn=config'  'cn=config.ldif'

chown -R ldap:ldap /etc/openldap/slapd.d



```

### 執行 OpenLDAP Service

```language

systemctl daemon-reload

systemctl enable --now slapd

systemctl status slapd

```

### 設定使用 OpenLDAP 登入 on Debian 11

```language

cd /etc/openldap/

ldapmodify -Y EXTERNAL -H ldapi:/// -Q

dn: cn=config
changeType: modify
replace: olcLogLevel
olcLogLevel: stats


ldapsearch -Y EXTERNAL -H ldapi:/// -b cn=config "(objectClass=olcGlobal)" olcLogLevel -LLL -Q



echo "local4.* /var/log/slapd.log" >> /etc/rsyslog.d/51-slapd.conf

systemctl restart rsyslog slapd

You should now be able to read the LDAP logs on, /var/log/slapd.log.

cat > /etc/logrotate.d/slapd << EOL
/var/log/slapd.log
{ 
        rotate 7
        daily
        missingok
        notifempty
        delaycompress
        compress
        postrotate
                /usr/lib/rsyslog/rsyslog-rotate
        endscript
}
EOL

systemctl restart logrotate


```

### 建立 OpenLDAP Default Root DN

```language

cd /etc/openldap/

slappasswd

New password: ENTER PASSWORD
Re-enter new password: RE-ENTER PASSWORD
{SSHA}OH74PoJJKTsYIEg75iuwGk0OKbJ8y/BD

更新 Replace the domain components, dc=ldapmaster,dc=kifarunix-demo,dc=com with your appropriate names.

cat > rootdn.ldif << 'EOL'
dn: olcDatabase=mdb,cn=config
objectClass: olcDatabaseConfig
objectClass: olcMdbConfig
olcDatabase: mdb
olcDbMaxSize: 42949672960
olcDbDirectory: /var/lib/openldap
olcSuffix: dc=ldapmaster,dc=kifarunix-demo,dc=com
olcRootDN: cn=admin,dc=ldapmaster,dc=kifarunix-demo,dc=com
olcRootPW: {SSHA}OH74PoJJKTsYIEg75iuwGk0OKbJ8y/BD
olcDbIndex: uid pres,eq
olcDbIndex: cn,sn pres,eq,approx,sub
olcDbIndex: mail pres,eq,sub
olcDbIndex: objectClass pres,eq
olcDbIndex: loginShell pres,eq
olcDbIndex: sudoUser,sudoHost pres,eq
olcAccess: to attrs=userPassword,shadowLastChange,shadowExpire
  by self write
  by anonymous auth
  by dn.subtree="gidNumber=0+uidNumber=0,cn=peercred,cn=external,cn=auth" manage 
  by dn.subtree="ou=system,dc=ldapmaster,dc=kifarunix-demo,dc=com" read
  by * none
olcAccess: to dn.subtree="ou=system,dc=ldapmaster,dc=kifarunix-demo,dc=com" by dn.subtree="gidNumber=0+uidNumber=0,cn=peercred,cn=external,cn=auth" manage
  by * none
olcAccess: to dn.subtree="dc=ldapmaster,dc=kifarunix-demo,dc=com" by dn.subtree="gidNumber=0+uidNumber=0,cn=peercred,cn=external,cn=auth" manage
  by users read 
  by * none
EOL

ldapadd -Y EXTERNAL -H ldapi:/// -f /etc/openldap/rootdn.ldif

Sample command output;

SASL/EXTERNAL authentication started
SASL username: gidNumber=0+uidNumber=0,cn=peercred,cn=external,cn=auth
SASL SSF: 0
adding new entry "olcDatabase=mdb,cn=config"

```

### Configure OpenLDAP with SSL/TLS

```
建立憑證
openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout \
/etc/ssl/ldapserver.key -out /etc/ssl/ldapserver.crt

chown ldap:ldap /etc/ssl/{ldapserver.crt,ldapserver.key}

cat > /etc/openldap/tls.ldif << 'EOL'
dn: cn=config
changetype: modify
add: olcTLSCACertificateFile
olcTLSCACertificateFile: /etc/ssl/ldapserver.crt
-
add: olcTLSCertificateFile
olcTLSCertificateFile: /etc/ssl/ldapserver.crt
-
add: olcTLSCertificateKeyFile
olcTLSCertificateKeyFile: /etc/ssl/ldapserver.key
EOL


ldapadd -Y EXTERNAL -H ldapi:/// -f /etc/openldap/tls.ldif

應該出現 :

slapcat -b "cn=config" | grep olcTLS

olcTLSCACertificateFile: /etc/ssl/ldapserver.crt
olcTLSCertificateFile: /etc/ssl/ldapserver.crt
olcTLSCertificateKeyFile: /etc/ssl/ldapserver.key



```

#### 修改  the location of the CA certificate on /etc/ldap/ldap.conf.

```language
sed -i 's|/etc/ssl/certs/ca-certificates.crt|/etc/ssl/ldapserver.crt|' /etc/ldap/ldap.conf

```

#### Create OpenLDAP Base DN

```language

Replace the domain components and organization units accordingly.

cat > basedn.ldif << 'EOL'
dn: dc=ldapmaster,dc=kifarunix-demo,dc=com
objectClass: dcObject
objectClass: organization
objectClass: top
o: Kifarunix-demo
dc: ldapmaster

dn: ou=groups,dc=ldapmaster,dc=kifarunix-demo,dc=com
objectClass: organizationalUnit
objectClass: top
ou: groups

dn: ou=people,dc=ldapmaster,dc=kifarunix-demo,dc=com
objectClass: organizationalUnit
objectClass: top
ou: people
EOL


ldapadd -Y EXTERNAL -H ldapi:/// -f basedn.ldif

Sample output;

...
adding new entry "dc=ldapmaster,dc=kifarunix-demo,dc=com"

adding new entry "ou=groups,dc=ldapmaster,dc=kifarunix-demo,dc=com"

adding new entry "ou=people,dc=ldapmaster,dc=kifarunix-demo,dc=com"

Create OpenLDAP User Accounts

```

###

```language

cat > users.ldif << 'EOL'
dn: uid=johndoe,ou=people,dc=ldapmaster,dc=kifarunix-demo,dc=com
objectClass: inetOrgPerson
objectClass: posixAccount
objectClass: shadowAccount
uid: johndoe
cn: John
sn: Doe
loginShell: /bin/bash
uidNumber: 10000
gidNumber: 10000
homeDirectory: /home/johndoe
shadowMax: 60
shadowMin: 1
shadowWarning: 7
shadowInactive: 7
shadowLastChange: 0

dn: cn=johndoe,ou=groups,dc=ldapmaster,dc=kifarunix-demo,dc=com
objectClass: posixGroup
cn: johndoe
gidNumber: 10000
memberUid: johndoe
EOL

Add the user to the OpenLDAP database.

ldapadd -Y EXTERNAL -H ldapi:/// -f users.ldif

設定密碼

Setting password for LDAP User

ldappasswd -H ldapi:/// -Y EXTERNAL -S "uid=johndoe,ou=people,dc=ldapmaster,dc=kifarunix-demo,dc=com"

Create OpenLDAP Bind DN and Bind DN User

ldapsearch -Q -LLL -Y EXTERNAL -H ldapi:/// -b cn=config '(olcDatabase={1}mdb)' olcAccess


```

### 修改密碼

```language

cd /etc/openldap/

slappasswd

New password: 
Re-enter new password: 
{SSHA}51i5ZSBTbCULaS8IwRrLDnrcsrM00czf

cat > bindDNuser.ldif << 'EOL'
dn: ou=system,dc=ldapmaster,dc=kifarunix-demo,dc=com
objectClass: organizationalUnit
objectClass: top
ou: system

dn: cn=readonly,ou=system,dc=ldapmaster,dc=kifarunix-demo,dc=com
objectClass: organizationalRole
objectClass: simpleSecurityObject
cn: readonly
userPassword: {SSHA}51i5ZSBTbCULaS8IwRrLDnrcsrM00czf
description: Bind DN user for LDAP Operations
EOL

ldapadd -Y EXTERNAL -H ldapi:/// -f /etc/openldap/bindDNuser.ldif



```

Enable OpenLDAP Password Policies

```language

cat > /etc/openldap/ppolicy.ldif << 'EOL'
dn: cn=ppolicy,cn=schema,cn=config
objectClass: olcSchemaConfig
cn: ppolicy
olcAttributeTypes: {0}( 1.3.6.1.4.1.42.2.27.8.1.1 NAME 'pwdAttribute' EQUALITY
  objectIdentifierMatch SYNTAX 1.3.6.1.4.1.1466.115.121.1.38 )
olcAttributeTypes: {1}( 1.3.6.1.4.1.42.2.27.8.1.2 NAME 'pwdMinAge' EQUALITY in
 tegerMatch ORDERING integerOrderingMatch SYNTAX 1.3.6.1.4.1.1466.115.121.1.27
  SINGLE-VALUE )
olcAttributeTypes: {2}( 1.3.6.1.4.1.42.2.27.8.1.3 NAME 'pwdMaxAge' EQUALITY in
 tegerMatch ORDERING integerOrderingMatch SYNTAX 1.3.6.1.4.1.1466.115.121.1.27
  SINGLE-VALUE )
olcAttributeTypes: {3}( 1.3.6.1.4.1.42.2.27.8.1.4 NAME 'pwdInHistory' EQUALITY
  integerMatch ORDERING integerOrderingMatch SYNTAX 1.3.6.1.4.1.1466.115.121.1
 .27 SINGLE-VALUE )
olcAttributeTypes: {4}( 1.3.6.1.4.1.42.2.27.8.1.5 NAME 'pwdCheckQuality' EQUAL
 ITY integerMatch ORDERING integerOrderingMatch SYNTAX 1.3.6.1.4.1.1466.115.12
 1.1.27 SINGLE-VALUE )
olcAttributeTypes: {5}( 1.3.6.1.4.1.42.2.27.8.1.6 NAME 'pwdMinLength' EQUALITY
  integerMatch ORDERING integerOrderingMatch  SYNTAX 1.3.6.1.4.1.1466.115.121.
 1.27 SINGLE-VALUE )
olcAttributeTypes: {6}( 1.3.6.1.4.1.42.2.27.8.1.7 NAME 'pwdExpireWarning' EQUA
 LITY integerMatch ORDERING integerOrderingMatch  SYNTAX 1.3.6.1.4.1.1466.115.
 121.1.27 SINGLE-VALUE )
olcAttributeTypes: {7}( 1.3.6.1.4.1.42.2.27.8.1.8 NAME 'pwdGraceAuthNLimit' EQ
 UALITY integerMatch ORDERING integerOrderingMatch  SYNTAX 1.3.6.1.4.1.1466.11
 5.121.1.27 SINGLE-VALUE )
olcAttributeTypes: {8}( 1.3.6.1.4.1.42.2.27.8.1.9 NAME 'pwdLockout' EQUALITY b
 ooleanMatch SYNTAX 1.3.6.1.4.1.1466.115.121.1.7 SINGLE-VALUE )
olcAttributeTypes: {9}( 1.3.6.1.4.1.42.2.27.8.1.10 NAME 'pwdLockoutDuration' E
 QUALITY integerMatch ORDERING integerOrderingMatch  SYNTAX 1.3.6.1.4.1.1466.1
 15.121.1.27 SINGLE-VALUE )
olcAttributeTypes: {10}( 1.3.6.1.4.1.42.2.27.8.1.11 NAME 'pwdMaxFailure' EQUAL
 ITY integerMatch ORDERING integerOrderingMatch  SYNTAX 1.3.6.1.4.1.1466.115.1
 21.1.27 SINGLE-VALUE )
olcAttributeTypes: {11}( 1.3.6.1.4.1.42.2.27.8.1.12 NAME 'pwdFailureCountInter
 val' EQUALITY integerMatch ORDERING integerOrderingMatch  SYNTAX 1.3.6.1.4.1.
 1466.115.121.1.27 SINGLE-VALUE )
olcAttributeTypes: {12}( 1.3.6.1.4.1.42.2.27.8.1.13 NAME 'pwdMustChange' EQUAL
 ITY booleanMatch SYNTAX 1.3.6.1.4.1.1466.115.121.1.7 SINGLE-VALUE )
olcAttributeTypes: {13}( 1.3.6.1.4.1.42.2.27.8.1.14 NAME 'pwdAllowUserChange' 
 EQUALITY booleanMatch SYNTAX 1.3.6.1.4.1.1466.115.121.1.7 SINGLE-VALUE )
olcAttributeTypes: {14}( 1.3.6.1.4.1.42.2.27.8.1.15 NAME 'pwdSafeModify' EQUAL
 ITY booleanMatch SYNTAX 1.3.6.1.4.1.1466.115.121.1.7 SINGLE-VALUE )
olcAttributeTypes: {15}( 1.3.6.1.4.1.4754.1.99.1 NAME 'pwdCheckModule' DESC 'L
 oadable module that instantiates "check_password() function' EQUALITY caseExa
 ctIA5Match SYNTAX 1.3.6.1.4.1.1466.115.121.1.26 SINGLE-VALUE )
olcAttributeTypes: {16}( 1.3.6.1.4.1.42.2.27.8.1.30 NAME 'pwdMaxRecordedFailur
 e' EQUALITY integerMatch ORDERING integerOrderingMatch  SYNTAX 1.3.6.1.4.1.
 1466.115.121.1.27 SINGLE-VALUE )
olcObjectClasses: {0}( 1.3.6.1.4.1.4754.2.99.1 NAME 'pwdPolicyChecker' SUP top
  AUXILIARY MAY pwdCheckModule )
olcObjectClasses: {1}( 1.3.6.1.4.1.42.2.27.8.2.1 NAME 'pwdPolicy' SUP top AUXI
 LIARY MUST pwdAttribute MAY ( pwdMinAge $ pwdMaxAge $ pwdInHistory $ pwdCheck
 Quality $ pwdMinLength $ pwdExpireWarning $ pwdGraceAuthNLimit $ pwdLockout $
  pwdLockoutDuration $ pwdMaxFailure $ pwdFailureCountInterval $ pwdMustChange
  $ pwdAllowUserChange $ pwdSafeModify $ pwdMaxRecordedFailure ) )
EOL

ldapadd -Y EXTERNAL -H ldapi:/// -f /etc/openldap/ppolicy.ldif

開通防火牆


iptables -A RH-Firewall-1-INPUT -s 192.168.1.0/24 -m state --state NEW -p tcp --dport 389 -j ACCEPT
iptables -A RH-Firewall-1-INPUT -s 192.168.1.0/24 -m state --state NEW -p udp --dport 389 -j ACCEPT
iptables -A RH-Firewall-1-INPUT -s 192.168.1.0/24 -m state --state NEW -p tcp --dport 636 -j ACCEPT
iptables -A RH-Firewall-1-INPUT -s 192.168.1.0/24 -m state --state NEW -p udp --dport 636 -j ACCEPT
或
iptables -A INPUT -m state --state NEW -m tcp -p tcp --dport 389 -j ACCEPT
iptables -A INPUT -m state --state NEW -m tcp -p tcp --dport 636 -j ACCEPT
iptables -A INPUT -m state --state NEW -m tcp -p tcp --dport 9830 -j ACCEPT


```

### 安裝 nginx 和 php8.0 和 phpLDAPadmin
