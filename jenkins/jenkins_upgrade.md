# jenkins 升級
----

```
mkdir ~/backup

wget https://mirrors.tuna.tsinghua.edu.cn/jenkins/war-stable/2.303.3/jenkins.war -O ~/backup/jenkins.war_2.303.3

mv /usr/share/jenkins/jenkins.war ./backup/jenkins.war_2.303.1

mv ./backup/jenkins.war_2.303.3 /usr/share/jenkins/jenkins.war

systemctl restart jenkins.service
```
