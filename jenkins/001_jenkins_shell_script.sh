#!/bin/bash
#$CN_KU_All_First_ForceOffline
# 設定新增每天目錄
Jname=`echo $JOB_NAME | tr [:upper:] [:lower:] | cut -c1-2`
#echo $Jname
timedir=$(date +%F)
#echo $timedir
/usr/bin/mkdir -p /home/F5/jenkins_log/$Jname-$timedir
#
for i in $(echo $CN_KU_All_First_ForceOffline | sed "s/,/\n/g" ); 
do 
	echo $i
    curl -X POST http://127.0.0.1:8080/jenkins/job/$i/build?delay=0sec --user $USERNAME:$PASSWORD

    sleep 5
    
done
echo Build finished
/usr/bin/touch /home/F5/jenkins_log/$JOB_NAME-$BUILD_NUMBER-log-file
# 確認 job 是否執行完畢
for i in $(echo $CN_KU_All_First_ForceOffline | sed "s/,/\n/g" ); 
do 
#
    JOB_URL=http://127.0.0.1:8080/jenkins/job/$i
    JOB_STATUS_URL=${JOB_URL}/lastBuild/api/json
    
    GREP_RETURN_CODE=0
       
    # Poll every thirty seconds until the build is finished
    while [ $GREP_RETURN_CODE -eq 0 ]
    do
        sleep 5
        # Grep will return 0 while the build is running:
        curl $JOB_STATUS_URL --user $USERNAME:$PASSWORD | grep result\":null > /dev/null
        GREP_RETURN_CODE=$?
    done
    echo "Build Check finished"
    echo -e "#" | cat >> /home/F5/jenkins_log/$JOB_NAME-$BUILD_NUMBER-log-file
    /usr/bin/find /home/F5/jenkins_log/$i-* -type f -amin -10 -exec cat {} \;| grep "offline\|disabled" | awk '{ print $1 " " $4 " 連線數: " $14 }' >> /home/F5/jenkins_log/$JOB_NAME-$BUILD_NUMBER-log-file
    echo "$i-Out-put-log"
#
    mv /home/F5/jenkins_log/$i-* /home/F5/jenkins_log/$Jname-$timedir
    sleep 5
done
#
#
# 顯示執行清單
sleep 10
# 請設定 60 分秒後在撈取執行清單
#
Sname=`echo $JOB_NAME | tr [:upper:] [:lower:] | cut -c1-2`
#cat /home/F5/jenkins_log/$JOB_NAME-$BUILD_NUMBER-log-file | /home/F5/slackweb.sh $JOB_NAME-$BUILD_NUMBER
cat /home/F5/jenkins_log/$JOB_NAME-$BUILD_NUMBER-log-file | /home/F5/slackweb_group.sh $JOB_NAME-$BUILD_NUMBER $Sname
#
for i in $(echo $CN_KU_All_First_ForceOffline | sed "s/,/\n/g" ); 
do 
	echo $i
    curl -s http://127.0.0.1:8080/jenkins/job/$i/lastBuild/consoleText --user $USERNAME:$PASSWORD
    
    sleep 2
done
# 將工作產生的工作移到今天目錄當中
mv /home/F5/jenkins_log/$JOB_NAME-* /home/F5/jenkins_log/$Jname-$timedir
#
cat /home/F5/jenkins_log/$Jname-$timedir/$JOB_NAME-$BUILD_NUMBER-log-file
