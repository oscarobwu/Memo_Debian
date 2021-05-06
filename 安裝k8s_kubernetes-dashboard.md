```

【K8S】 K8S 1.21.0 安装dashboard（基于kubernetes-dashboard 2.2.0版本）

安装部署dashboard
1.查看pod运行情况

# kubectl get pods -A  -o wide

2.下载recommended.yaml文件

wget -O kubernetes-dashboard.yaml https://raw.githubusercontent.com/kubernetes/dashboard/v2.2.0/aio/deploy/recommended.yaml

3.修改 kubernetes-dashboard.yaml 文件

# vi kubernetes-dashboard.yaml

修改內容如下:

---
kind: Service
apiVersion: v1
metadata:
  labels:
    k8s-app: kubernetes-dashboard
  name: kubernetes-dashboard
  namespace: kubernetes-dashboard
spec:
  type: NodePort #增加
  ports:
    - port: 443
      targetPort: 8443
      nodePort: 30000 #增加
  selector:
    k8s-app: kubernetes-dashboard
---
#因为自动生成的证书很多浏览器无法使用，所以我们自己创建，注释掉kubernetes-dashboard-certs对象声明
#apiVersion: v1
#kind: Secret
#metadata:
#  labels:
#    k8s-app: kubernetes-dashboard
#  name: kubernetes-dashboard-certs
#  namespace: kubernetes-dashboard
#type: Opaque
---

4.创建 kubernetes-dashboard 使用证书

mkdir dashboard-certs

cd dashboard-certs/

#创建命名空间
kubectl create namespace kubernetes-dashboard

# 创建key文件
openssl genrsa -out dashboard.key 2048

#证书请求
openssl req -days 36000 -new -out dashboard.csr -key dashboard.key -subj '/CN=dashboard-cert'

#自签证书
openssl x509 -req -in dashboard.csr -signkey dashboard.key -out dashboard.crt

#创建kubernetes-dashboard-certs对象
kubectl create secret generic kubernetes-dashboard-certs --from-file=dashboard.key --from-file=dashboard.crt -n kubernetes-dashboard

5.安装dashboard 

kubectl create -f ~/kubernetes-dashboard.yaml

注意：这里可能会报如下所示。

Error from server (AlreadyExists): error when creating "/root/kubernetes-dashboard.yaml": namespaces "kubernetes-dashboard" already exists
Error from server (AlreadyExists): error when creating "/root/kubernetes-dashboard.yaml": secrets "kubernetes-dashboard-certs" already exists

这是因为我们在创建证书时，已经创建了kubernetes-dashboard命名空间，所以，直接忽略此错误信息即可。

6.查看安装结果

# kubectl get pods -A  -o wide

# kubectl get service -n kubernetes-dashboard  -o wide

7.创建dashboard管理员

# vi dashboard-admin.yaml

新增內容如下:

apiVersion: v1
kind: ServiceAccount
metadata:
  labels:
    k8s-app: kubernetes-dashboard
  name: dashboard-admin
  namespace: kubernetes-dashboard


保存退出后执行如下命令创建管理员。

kubectl create -f ./dashboard-admin.yaml

8.为用户分配权限

创建dashboard-admin-bind-cluster-role.yaml文件。

vi dashboard-admin-bind-cluster-role.yaml

文件内容如下所示。

apiVersion: rbac.authorization.k8s.io/v1
kind: ClusterRoleBinding
metadata:
  name: dashboard-admin-bind-cluster-role
  labels:
    k8s-app: kubernetes-dashboard
roleRef:
  apiGroup: rbac.authorization.k8s.io
  kind: ClusterRole
  name: cluster-admin
subjects:
- kind: ServiceAccount
  name: dashboard-admin
  namespace: kubernetes-dashboard

保存退出后执行如下命令为用户分配权限。

kubectl create -f ./dashboard-admin-bind-cluster-role.yaml


9.查看并复制用户Token

kubectl -n kubernetes-dashboard describe secret $(kubectl -n kubernetes-dashboard get secret | grep dashboard-admin | awk '{print $1}')

# kubectl -n kubernetes-dashboard describe secret $(kubectl -n kubernetes-dashboard get secret | grep dashboard-admin | awk '{print $1}')
Name:         dashboard-admin-token-p8tng
Namespace:    kubernetes-dashboard
Labels:       <none>
Annotations:  kubernetes.io/service-account.name: dashboard-admin
              kubernetes.io/service-account.uid: c3640b5f-cd92-468c-ba01-c886290c41ca

Type:  kubernetes.io/service-account-token

Data
====
ca.crt:     1025 bytes
namespace:  20 bytes
token:     .........



可以看到，此时的Token值为：


查看dashboard界面
在浏览器中打开链接 https://xxx.xxx.xxx.xxx:30000 
```
