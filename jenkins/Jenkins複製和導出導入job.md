# Jenkins複製和導出導入job

----

## 方法一：同一個Jenkins中複製job

```
如果是同一個Jenkins複製job，只需要在創建Job時，選擇Copy from一個已有的job即可。
```

##  方法二：直接複製jobs或指定的job目錄

### 這種方法適合跨Jenkins複製。

#### 複製全部job：
```
cd /var/lib/jenkins
# 在源Jenkins上壓縮jobs目錄
tar -czvf jobs.tar.gz jobs
# 在目標Jenkins上解壓jobs目錄
tar -zxvf jobs.tar.gz

```

#### 複製某個job：

```
cd /var/lib/jenkins/jobs
# 在源Jenkins上壓縮指定的job目錄
tar -czvf myjob.tar.gz myjob
# 在目標Jenkins上解壓指定的job目錄
tar -zxvf myjob.tar.gz
```

#### 然後在目標Jenkins上，打開Manage Jenkins，選擇Reload Configuration from Disk。

#### 不需要重啓目標Jenkins。


## 方法三：用Jenkins-CLI來導出和導入

#### 在Jenkins上，打開Manage Jenkins，打開Jenkins-CLI。

#### 下載jenkins-cli.jar，按照Jenkins-CLI頁面的指引來操作：

```
# 導出一個job
java -jar jenkins-cli.jar -s http://192.168.37.131:8080/ get-job myjob > myjob.xml
# 導入一個jobs
java -jar jenkins-cli.jar -s http://192.168.37.131:8080/ get-job myjob < myjob.xml

```

![jenkins_auth_access_1](https://github.com/oscarobwu/Memo_Debian/raw/main/jenkins/images/jenkins_auth_access_1.png)

#####

![jenkins_auth_access_2](https://github.com/oscarobwu/Memo_Debian/raw/main/jenkins/images/jenkins_auth_access_2.png)

#####

![jenkins_auth_access_3](https://github.com/oscarobwu/Memo_Debian/raw/main/jenkins/images/jenkins_auth_access_3.png)

#####

![jenkins_auth_access_4](https://github.com/oscarobwu/Memo_Debian/raw/main/jenkins/images/jenkins_auth_access_4.png)

#####

![jenkins_auth_access_5](https://github.com/oscarobwu/Memo_Debian/raw/main/jenkins/images/jenkins_auth_access_5.png)
