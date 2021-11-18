# jenkins_Freesta_pipeline_設定.md

```
node {
    stage("001_檢查帳號登入"){
        wrap([$class: 'MaskPasswordsBuildWrapper'] ) {
            ansiColor('xterm') {
                echo 'Hello World 001_檢查帳號登入'
                sh 'python /home/F5/poolmember3_v1.py 192.168.88.166 Teste pool_02 $F5_USERNAME ${F5_SECRET_KEY}'
            }
        }
    }
    //設定變數過濾
    stage("002_建立job建立 Loop"){
        // 設定 Jenkins 本機 API密碼
        withCredentials([[$class: 'UsernamePasswordMultiBinding', credentialsId:'JENKINS_LOCAL_USERPASSWORD', usernameVariable: 'JENKINS_USERNAME', passwordVariable: 'JENKINS_PASSWORD']]) {
            OT_CNA_All_FirstEnabled.split(",").each { list_jobs ->
                wrap([$class: 'MaskPasswordsBuildWrapper'] ) {
                    ansiColor('xterm') {
					    echo 'Hello World 002_建立job建立 Loop'
                        sh 'sleep 3'
                        sh 'curl -X POST http://$JENKINS_USERNAME:$JENKINS_PASSWORD@127.0.0.1:8080/jenkins/job/' + list_jobs + '/build?delay=0sec --data-urlencode json=\'{parameter:[{"name":"F5_USERNAME","value":"\'$F5_USERNAME\'"},{"name":"F5_SECRET_KEY","value":"\'${F5_SECRET_KEY}\'"}]}\''
                    }
                }
            }
        }
    }
    stage("003_檢查job建立 是否完成 Loop"){
        // 設定 Jenkins 本機 API密碼
        withCredentials([[$class: 'UsernamePasswordMultiBinding', credentialsId:'JENKINS_LOCAL_USERPASSWORD', usernameVariable: 'JENKINS_USERNAME', passwordVariable: 'JENKINS_PASSWORD']]) {
            OT_CNA_All_FirstEnabled.split(",").each { list_jobs ->
                wrap([$class: 'MaskPasswordsBuildWrapper'] ) {
                    ansiColor('xterm') {
					    echo 'Hello World 003_檢查job建立 是否完成 Loop'
                        sh 'sleep 3'
                        sh '''#!/bin/bash
						    Jname=`echo $JOB_NAME | tr [:upper:] [:lower:] | cut -c1-2`
							#
							ls_job=''' + list_jobs  + '''
							timedir=$(date +%F)
							echo $Jname
							#
                            directory_name="/home/F5/jenkins_log/$Jname-$timedir"
                            #
                            if [ -d $directory_name ]
                            then
                                echo "Directory already exists"
                            else
                                /usr/bin/mkdir -p $directory_name
                            fi
							#/usr/bin/mkdir -p /home/F5/jenkins_log/$Jname-$timedir
							#echo ''' + list_jobs  + '''
							#
							#/usr/bin/touch /home/F5/jenkins_log/$JOB_NAME-$BUILD_NUMBER-log-file
                            JOB_URL=http://$JENKINS_USERNAME:$JENKINS_PASSWORD@127.0.0.1:8080/jenkins/job/''' + list_jobs  + '''
                            JOB_STATUS_URL=$JOB_URL/lastBuild/api/json
                            
                            GREP_RETURN_CODE=0
                               
                            # Poll every thirty seconds until the build is finished
                            while [ $GREP_RETURN_CODE -eq 0 ]
                            do
                                sleep 5
                                # Grep will return 0 while the build is running:
                                curl $JOB_STATUS_URL | grep 'result":null' > /dev/null
                                GREP_RETURN_CODE=$?
                            done
                            echo "Build Check finished"
                            echo -e "#" | cat >> /home/F5/jenkins_log/$JOB_NAME-$BUILD_NUMBER-log-file
                            /usr/bin/find /home/F5/jenkins_log/${ls_job}-* -type f -amin -10 -exec cat {} \\;| grep "offline\\|disabled" | awk '{ print $1 " " $4 " 連線數: " $14 }' >> /home/F5/jenkins_log/$JOB_NAME-$BUILD_NUMBER-log-file
                            echo "''' + list_jobs  + '''-Out-put-log"
                            #                   
                            mv /home/F5/jenkins_log/''' + list_jobs  + '''-* /home/F5/jenkins_log/$Jname-$timedir
                            sleep 2
                        '''
                    }
                }
            }
        }
    }
    //將清單過濾發出slack
    stage("004_顯示每個執行job_log"){
        // 設定 Jenkins 本機 API密碼
        withCredentials([[$class: 'UsernamePasswordMultiBinding', credentialsId:'JENKINS_LOCAL_USERPASSWORD', usernameVariable: 'JENKINS_USERNAME', passwordVariable: 'JENKINS_PASSWORD']]) {
            OT_CNA_All_FirstEnabled.split(",").each { list_jobs ->
                wrap([$class: 'MaskPasswordsBuildWrapper'] ) {
                    ansiColor('xterm') {
					    echo 'Hello World 004_過濾連線數發Slack'
                        sh 'sleep 3'
                        sh '''#!/bin/bash
						curl -X POST http://$JENKINS_USERNAME:$JENKINS_PASSWORD@127.0.0.1:8080/jenkins/job/''' + list_jobs + '''/lastBuild/consoleText
						'''
                    }
                }
            }
        }
    }
    //將清單過濾發出slack 整合後發出
    stage("005_過濾連線數發Slack"){
        // 設定 Jenkins 本機 API密碼
        withCredentials([[$class: 'UsernamePasswordMultiBinding', credentialsId:'JENKINS_LOCAL_USERPASSWORD', usernameVariable: 'JENKINS_USERNAME', passwordVariable: 'JENKINS_PASSWORD']]) {
            wrap([$class: 'MaskPasswordsBuildWrapper'] ) {
                ansiColor('xterm') {
                    echo 'Hello World 005_過濾連線數發Slack'
                    sh 'sleep 3'
                    sh '''#!/bin/bash
                    Sname=`echo $JOB_NAME | tr [:upper:] [:lower:] | cut -c1-2`
                    cat /home/F5/jenkins_log/$JOB_NAME-$BUILD_NUMBER-log-file | /home/F5/slackweb_group.sh $JOB_NAME-$BUILD_NUMBER $Sname
					sleep 3
					cat /home/F5/jenkins_log/$JOB_NAME-$BUILD_NUMBER-log-file
                    '''
                }
            }
        }
    }
    stage("006_完成登入"){
        wrap([$class: 'MaskPasswordsBuildWrapper'] ) {
            ansiColor('xterm') {
                echo 'Hello World 5'
				echo 'Hello World 006_完成登入'
                sh 'python /home/F5/poolmember3_v1.py 192.168.88.166 Teste pool_02 $F5_USERNAME ${F5_SECRET_KEY}'
				sh '''#!/bin/bash
				Jname=`echo $JOB_NAME | tr [:upper:] [:lower:] | cut -c1-2`
				#
				timedir=$(date +%F)
				#
				mv /home/F5/jenkins_log/$JOB_NAME-* /home/F5/jenkins_log/$Jname-$timedir
				cat /home/F5/jenkins_log/$Jname-$timedir/$JOB_NAME-$BUILD_NUMBER-log-file
				'''
            }
        }
    }
}
```
