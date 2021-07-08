#!/usr/bin/python3
#coding:utf8
import os
import time
curtime=time.strftime("%Y-%m-%d %H:%M:%S", time.localtime())

url="http://172.16.4.83:8080/"
username="admin"
passwd="passwd"
#jobs="zzz-job"
tag="v2.0.200601.09"

jobs = open("jobs","r")
for job in jobs:
    job=job.strip('\n')
    cmd = "java -jar jenkins-cli.jar -s %s -auth %s:%s build %s -p tag=%s" % (url,username,passwd,job,tag)
    os.system(cmd)
    print("%s开始构建------ tag版本:%s" % (jobs,tag))
    time.sleep(5)
jobs.close()
