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
