# 安裝設定_OpenLDAP_Server_在Debian 11 (Bullseye)

##### 002_安裝設定_OpenLDAP_Server_在Debian_11_v1.md
----

1. 設定主機名稱正確FQDN

```
echo "192.168.10.10 debian11-0-ldap-01.localdomain.com" | sudo tee -a /etc/hosts
sudo hostnamectl set-hostname debian11-0-ldap-01.localdomain.com --static

```

### 更新設定

```
sudo apt -y update
sudo apt -y upgrade
sudo reboot
```

Step 2: 安裝 OpenLDAP on Debian 11 Bullseye (Bullseye)

```
sudo apt -y install slapd ldap-utils
```

### 確認資訊

```
需先確認 dn資訊

$ slapcat
dn: dc=localdomain,dc=com
objectClass: top
objectClass: dcObject
objectClass: organization
o: localdomain.com
dc: localdomain
structuralObjectClass: organization
entryUUID: 3380a11a-587c-1039-8fb1-a76b7240a677
creatorsName: cn=admin,dc=localdomain,dc=com
createTimestamp: 20190821162641Z
entryCSN: 20190821162641.076360Z#000000#000#000000
modifiersName: cn=admin,dc=localdomain,dc=com
modifyTimestamp: 20190821162641Z

dn: cn=admin,dc=localdomain,dc=com
objectClass: simpleSecurityObject
objectClass: organizationalRole
cn: admin
description: LDAP administrator
userPassword:: e1NTSEF9eDN2SUVtUnRZMUFjeHZuREtMaDlwdjU5c3dMZkFaWmM=
structuralObjectClass: organizationalRole
entryUUID: 3380e3fa-587c-1039-8fb2-a76b7240a677
creatorsName: cn=admin,dc=localdomain,dc=com
createTimestamp: 20190821162641Z
entryCSN: 20190821162641.078129Z#000000#000#000000
modifiersName: cn=admin,dc=localdomain,dc=com
modifyTimestamp: 20190821162641Z

```

Step 3: 新增 base dn for Users and Groups

```
vi /etc/openldap/basedn.ldif

dn: ou=people,dc=localdomain,dc=com
objectClass: organizationalUnit
ou: people

dn: ou=groups,dc=localdomain,dc=com
objectClass: organizationalUnit
ou: groups

```

#### 套用設定

```

sudo ldapadd -x -D cn=admin,dc=localdomain,dc=com -W -f /etc/openldap/basedn.ldif



```


Step 4: Add User Accounts and Groups

```
設定密碼

$ slappasswd
New password: 
Re-enter new password: 
{SSHA}5D94oKzVyJYzkCq21LhXDZFNZpPQD9uE

```

```
$ nano ldapusers.ldif
dn: uid=oscartai,ou=people,dc=localdomain,dc=com
objectClass: inetOrgPerson
objectClass: posixAccount
objectClass: shadowAccount
cn: Josphat
sn: Mutai
userPassword: {SSHA}5D94oKzVyJYzkCq21LhXDZFNZpPQD9uE
loginShell: /bin/bash
homeDirectory: /home/testuser
uidNumber: 3000
gidNumber: 3000


```

#### 套用設定

```language

$ ldapadd -x -D cn=admin,dc=localdomain,dc=com -W -f ldapusers.ldif 
Enter LDAP Password: 
adding new entry "uid=oscartai,ou=people,dc=localdomain,dc=com"



```

```language
$ cat ldapgroups.ldif
dn: cn=oscartai,ou=groups,dc=localdomain,dc=com
objectClass: posixGroup
cn: jmutai
gidNumber: 3000
memberUid: jmutai

$ ldapadd -x -D cn=admin,dc=localdomain,dc=com -W -f ldapgroups.ldif
Enter LDAP Password: 
 adding new entry "cn=oscartai,ou=groups,dc=localdomain,dc=com"

```

Step 5: Install LDAP Account Manager on Debian 11 (Bullseye)

```language
https://master.dl.sourceforge.net/project/lam/LAM/7.7/ldap-account-manager-lamdaemon_7.7-1_all.deb?viasf=1

wget http://prdownloads.sourceforge.net/lam/ldap-account-manager_7.7-1_all.deb
sudo dpkg -i ldap-account-manager_7.7-1_all.deb

sudo apt -f install
sudo dpkg -i ldap-account-manager_7.7-1_all.deb

```
