# pipline


```
node {
    //設定變數過濾
    CN_KU_All_FirstEnabled.split(",").each {list_jobs ->
        stage("建立job建立 $list_jobs"){
            wrap([$class: 'MaskPasswordsBuildWrapper'] ) {
                    script {
                        //設定command 可以設定curl
                        conn = "@//dbservername:1521/" + list_jobs + ".myservice.com" + "$F5USERNAME"
                    }
                    ansiColor('xterm') {
                    sh 'sleep 3'
                    sh 'python /home/F5/poolmember3_v1.py 192.168.88.166 ' + list_jobs + ' pool_01 $F5USERNAME ${F5_SECRET_KEY}'
                }
            }
        }
    }
}

```
```
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
