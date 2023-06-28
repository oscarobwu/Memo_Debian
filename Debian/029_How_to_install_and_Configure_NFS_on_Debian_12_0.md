# 029_How_to_install_and_Configure_NFS_on_Debian_12_0.md


### 1. Update your system packages

```
sudo apt update


```

### 2. 安裝 NFS Server

```

sudo apt install nfs-kernel-server -y

```

### 3. 建立分享目錄 

### Creating the Share Directories on the Host

```

sudo mkdir /var/nfs/general -p

ls -dl /var/nfs/general 

Output
drwxr-xr-x 2 root root 4096 Apr 17 23:51 /var/nfs/general

修改權限
sudo chown nobody:nogroup /var/nfs/general

Output
drwxr-xr-x 2 nobody nogroup 4096 Apr 17 23:51 /var/nfs/general


sudo mkdir /var/nfsshare -p

sudo chown nobody:nogroup /var/nfsshare

ls -dl /var/nfsshare


```

### 4. 設定本機 分享目錄
 Configuring the NFS Exports on the Host Server

```

vi  /etc/exports
# /etc/exports: the access control list for filesystems which may be exported
#               to NFS clients.  See exports(5).
#
# Example for NFSv2 and NFSv3:
# /srv/homes       hostname1(rw,sync,no_subtree_check) hostname2(ro,sync,no_subtree_check)
#
# Example for NFSv4:
# /srv/nfs4        gss/krb5i(rw,sync,fsid=0,crossmnt,no_subtree_check)
# /srv/nfs4/homes  gss/krb5i(rw,sync,no_subtree_check)
#
# 設定存取網段
/var/nfsshare    192.168.88.0/24(rw,sync,no_subtree_check)


```

### 5. 設定本機 防火牆


```

允許 port 111 

```


# 清除 防火牆規則

```
iptables -P INPUT ACCEPT
iptables -P FORWARD ACCEPT
iptables -P OUTPUT ACCEPT
iptables -F
iptables -X
iptables -t nat -F
iptables -t nat -X
iptables -t mangle -F
iptables -t mangle -X

```
