# 設定jenkins pipeline設定 2021-11-11_11_00_32
----

```sh
設定 相關內容

1. F5_USERNAME

2. F5_SECRET_KEY

3. OT_CNA_All_FirstEnabled

設定 Groovy Script

def jobs = jenkins.model.Jenkins.instance.getJobNames()
//設定正規式過濾
def matchjobs = jobs.findAll{ name -> name =~ /^OT-CNA-.*First-1-Force-Offline$/ }
//返回
return matchjobs

4. 設定pipeline script

node {
    stage("001_檢查帳號登入"){
        wrap([$class: 'MaskPasswordsBuildWrapper'] ) {
            ansiColor('xterm') {
                echo 'Hello World 1'
                sh 'python /home/F5/poolmember3_v1.py 192.168.88.166 Teste pool_02 $F5_USERNAME ${F5_SECRET_KEY}'
            }
        }
    }
    //設定變數過濾
    stage("002_建立job建立 Loop"){
        // 設定 Jenkins 本機 API密碼
		// 要先在 credentialsId 設定好 Jenkins 的帳號密碼
        withCredentials([[$class: 'UsernamePasswordMultiBinding', credentialsId:'JENKINS_LOCAL_USERPASSWORD', usernameVariable: 'JENKINS_USERNAME', passwordVariable: 'JENKINS_PASSWORD']]) {
            OT_CNA_All_FirstEnabled.split(",").each { list_jobs ->
                wrap([$class: 'MaskPasswordsBuildWrapper'] ) {
                    ansiColor('xterm') {
                        sh 'sleep 3'
                        sh 'curl -X POST http://$JENKINS_USERNAME:$JENKINS_PASSWORD@127.0.0.1:8080/jenkins/job/' + list_jobs + '/build --data-urlencode json=\'{parameter:[{"name":"F5_USERNAME","value":"\'$F5_USERNAME\'"},{"name":"F5_SECRET_KEY","value":"\'${F5_SECRET_KEY}\'"}]}\''
                    }
                }
            }
        }
    }
    stage("003_完成登入"){
        wrap([$class: 'MaskPasswordsBuildWrapper'] ) {
            ansiColor('xterm') {
                echo 'Hello World 2'
                sh 'python /home/F5/poolmember3_v1.py 192.168.88.166 Teste pool_02 $F5_USERNAME ${F5_SECRET_KEY}'
            }
        }
    }
}


```

```
01. OT-CNA-001-LAB-API01_Site01_First-1-Force-Offline

設變建制化參數 (要與上面的驅動job相同)
F5_USERNAME
F5_SECRET_KEY

node {
    //設定變數過濾
    stage("建立job建立 關機"){
            wrap([$class: 'MaskPasswordsBuildWrapper'] ) {
                ansiColor('xterm') {
                sh 'sleep 3'
                sh 'python /home/F5/poolmember3_v1.py 192.168.88.166 API pool_01 $F5_USERNAME $F5_SECRET_KEY'
            }
        }
    }
}
node {
    //設定變數過濾
    stage("建立job建立 關機"){
            wrap([$class: 'MaskPasswordsBuildWrapper'] ) {
                ansiColor('xterm') {
                sh 'sleep 3'
                sh '''
                #
                python /home/F5/f5_set_pool_members.py -H 192.168.88.166 -U $F5_USERNAME -P $F5_SECRET_KEY -p pool_01 -n node_192.168.81.11:80 -s forced_offline
                python /home/F5/f5_set_pool_members.py -H 192.168.88.166 -U $F5_USERNAME -P $F5_SECRET_KEY -p pool_01 -n node_192.168.81.12:80 -s forced_offline
                python /home/F5/f5_set_pool_members.py -H 192.168.88.166 -U $F5_USERNAME -P $F5_SECRET_KEY -p pool_01 -n node_192.168.81.13:80 -s forced_offline
                #
                python /home/F5/poolmember3_v1.py 192.168.88.166 API pool_01 $F5_USERNAME $F5_SECRET_KEY | tee /home/F5/jenkins_log/$JOB_NAME-$BUILD_NUMBER-log-file
                #
                sleep 1
                Sname=`echo $JOB_NAME | tr [:upper:] [:lower:] | cut -c1-2`
                cat /home/F5/jenkins_log/$JOB_NAME-$BUILD_NUMBER-log-file | grep "offline" | awk '{ print $1 " " $4 " 連線數: " $14 }' | /home/F5/slackweb.sh $JOB_NAME-$BUILD_NUMBER $Sname
                '''
            }
        }
    }
}


02. OT-CNA-001-LAB-API01_Site02_First-1-Force-Offline

設變建制化參數 (要與上面的驅動job相同)
F5_USERNAME
F5_SECRET_KEY

node {
    //設定變數過濾
    stage("建立 $JOB_NAME-$BUILD_NUMBER 關機"){
            wrap([$class: 'MaskPasswordsBuildWrapper'] ) {
                ansiColor('xterm') {
                sh 'sleep 3'
                sh 'python /home/F5/poolmember3_v1.py 192.168.88.166 API pool_02 $F5_USERNAME $F5_SECRET_KEY'
            }
        }
    }
}

node {
    //設定變數過濾
    stage("建立 $JOB_NAME-$BUILD_NUMBER關機"){
            wrap([$class: 'MaskPasswordsBuildWrapper'] ) {
                ansiColor('xterm') {
                sh 'sleep 3'
                sh '''
                #
                python /home/F5/f5_set_pool_members.py -H 192.168.88.166 -U $F5_USERNAME -P $F5_SECRET_KEY -p pool_02 -n node_192.168.81.11:80 -s forced_offline
                python /home/F5/f5_set_pool_members.py -H 192.168.88.166 -U $F5_USERNAME -P $F5_SECRET_KEY -p pool_02 -n node_192.168.81.12:80 -s forced_offline
                python /home/F5/f5_set_pool_members.py -H 192.168.88.166 -U $F5_USERNAME -P $F5_SECRET_KEY -p pool_02 -n node_192.168.81.13:80 -s forced_offline
                #
                python /home/F5/poolmember3_v1.py 192.168.88.166 API_02 pool_02 $F5_USERNAME $F5_SECRET_KEY | tee /home/F5/jenkins_log/$JOB_NAME-$BUILD_NUMBER-log-file
                #
                sleep 1
                Sname=`echo $JOB_NAME | tr [:upper:] [:lower:] | cut -c1-2`
                cat /home/F5/jenkins_log/$JOB_NAME-$BUILD_NUMBER-log-file | grep "offline" | awk '{ print $1 " " $4 " 連線數: " $14 }' | /home/F5/slackweb.sh $JOB_NAME-$BUILD_NUMBER $Sname
                '''
            }
        }
    }
}

03. OT-CNA-001-LAB-API01_Site03_First-1-Force-Offline

設變建制化參數 (要與上面的驅動job相同)
F5_USERNAME
F5_SECRET_KEY

node {
    //設定變數過濾
    stage("建立job建立 關機"){
            wrap([$class: 'MaskPasswordsBuildWrapper'] ) {
                ansiColor('xterm') {
                sh 'sleep 3'
                sh 'python /home/F5/poolmember3_v1.py 192.168.88.166 API pool_03 $F5_USERNAME $F5_SECRET_KEY'
            }
        }
    }
}

修改新的
################################
node {
    //設定變數過濾
    stage("建立job建立 關機"){
            wrap([$class: 'MaskPasswordsBuildWrapper'] ) {
                ansiColor('xterm') {
                sh 'sleep 3'
                sh '''
                #
                python /home/F5/f5_set_pool_members.py -H 192.168.88.166 -U $F5_USERNAME -P $F5_SECRET_KEY -p pool_03 -n node_192.168.81.11:80 -s forced_offline
                python /home/F5/f5_set_pool_members.py -H 192.168.88.166 -U $F5_USERNAME -P $F5_SECRET_KEY -p pool_03 -n node_192.168.81.12:80 -s forced_offline
                #
                python /home/F5/poolmember3_v1.py 192.168.88.166 API pool_03 $F5_USERNAME $F5_SECRET_KEY | tee /home/F5/jenkins_log/$JOB_NAME-$BUILD_NUMBER-log-file
                #
                sleep 1
                Sname=`echo $JOB_NAME | tr [:upper:] [:lower:] | cut -c1-2`
                cat /home/F5/jenkins_log/$JOB_NAME-$BUILD_NUMBER-log-file | grep "offline" | awk '{ print $1 " " $4 " 連線數: " $14 }' | /home/F5/slackweb.sh $JOB_NAME-$BUILD_NUMBER $Sname
                '''
            }
        }
    }
}

```
