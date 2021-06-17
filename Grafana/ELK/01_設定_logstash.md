```
範例
input {
    tcp {
        port => 5000
    }
}

## Add your filters / logstash plugins configuration here

output {
    elasticsearch {
        hosts => "elasticsearch:9200"
    }

########################################
將log 分送redis 

input{
    beats{
       port => 5044
    }
}

output{
    if "system-log-5611" in [tags]{
        redis {
            host => "192.168.56.11"
            port => "6379"
            password => "123456"
            db => "3"
            data_type => "list"
            key => "system-log-5611"
        }

        stdout{
            codec => rubydebug
        }
    }
    if "nginx-log" in [tags]{
        redis {
            host => "192.168.56.11"
            port => "6379"
            password => "123456"
            db => "4"
            data_type => "list"
            key => "nginx-log"
        }

        stdout{
            codec => rubydebug
        }
    }
}
```
```
將log 從 Reids 讀出

input{
    redis {
            host => "192.168.56.11"
            port => "6379"
            password => "123456"
            db => "3"
            data_type => "list"
            key => "system-log-5611"
        }
    redis {
            host => "192.168.56.11"
            port => "6379"
            password => "123456"
            db => "4"
            data_type => "list"
            key => "nginx-log"
        }
}

filter{
    if "nginx-log" in [tags] {
        json{
 	    source => "message"
 	}
        if [user_ua] != "-" {
        useragent {
         target => "agent"   #agent將過來出的user agent的信息配置到了單獨的字段中
         source => "user_ua"   #這個表示對message裏面的哪個字段進行分析
       }
      }
    }
}

output{
    stdout{
        codec => rubydebug
    }
}
#####################################################################
input{
    redis {
            host => "192.168.56.11"
            port => "6379"
            password => "123456"
            db => "3"
            data_type => "list"
            key => "system-log-5611"
        }
    redis {
            host => "192.168.56.11"
            port => "6379"
            password => "123456"
            db => "4"
            data_type => "list"
            key => "nginx-log"
        }
}

filter{
    if "nginx-log" in [tags] {
        json{
 	    source => "message"
 	}
        if [user_ua] != "-" {
        useragent {
         target => "agent"   #agent將過來出的user agent的信息配置到了單獨的字段中
         source => "user_ua"   #這個表示對message裏面的哪個字段進行分析
       }
      }
    }
}

output{
    if "nginx-log" in [tags]{
        elasticsearch{ 
	    hosts => ["192.168.56.11:9200"]
  	    index => "nginx-log-%{+YYYY.MM}"
        } 
    }
    if "system-log-5611" in [tags]{
        elasticsearch{ 
	    hosts => ["192.168.56.11:9200"]
  	    index => "system-log-5611-%{+YYYY.MM}"
        } 
    }
}
```
```

input {
    # beats輸入外掛程式
    beats {
        #綁定主機
        host => "0.0.0.0"
        #綁定埠
        port => 5044
        #額外添加欄位，這裡是為了區分來自哪一個外掛程式
        add_field => {"[fields][class]" => "beats"}
    }

    syslog {
        #綁定埠
        port => 514
        #額外添加欄位，這裡是為了區分來自哪一個外掛程式
        add_field => {"[fields][class]" => "json"}
    }
}
 
filter { 
    # 處理來自beats外掛程式的日誌,beats這裡收集的是tomcat的日誌
    # 樣例：192.168.68.88 - - [16/Mar/2020:11:22:08 +0800] "GET /esws/testService/test?name=天道酬勤&size=50 HTTP/1.1" 200 15315
    if [fields][class] == "beats"{
        #grop過濾外掛程式，在編寫grop時，可以使用kibana，kibana上有編寫工具，無需自己搭建（官方grok速度太慢）
        grok {
            #解析Apache日誌，自動分割
            match => { "message" => "%{COMMONAPACHELOG}" }
        }
        #鍵值篩檢程式
        kv {
            #對request欄位操作
            source => "request"
            按照& ？ 分割
            field_split => "&?"
            value_split => "="
            #選取自己需要的分割後的欄位
            include_keys => ["op","reportlet","formlet"] 
        } 
        #解碼
        urldecode {
            #解碼全部欄位
            all_fields => true
        }
        #日期處理外掛程式
        date {
            #日期匹配，匹配格式可以有多個
            match => ["timestamp", "dd/MMM/yyyy:HH:mm:ss Z"]
            #匹配的日期存儲到欄位中
            target => "@timestamp"
        }
            
        # 資料修改
        mutate{
            #移除指定欄位
            remove_field => ["agent","beat","offset","tags","prospector","log","ident","[host][name]","[host][hostname]","[host][architecture]","[host][os]","[host][id]","auth","[input][type]"]
            #複製欄位
            copy => { "@timestamp" => "timestamp" }
            #copy => { "[fields][fields_type]" => "fields_type" }
            copy => { "formlet" => "reportlet" }
        }
        
        mutate{
            #替換
            gsub => ["reportlet", "%2F", "/"]
        }
        
        if ! [fields_type]  {
            mutate{
                copy => { "[fields][fields_type]" => "fields_type" }
            }
        }
 
        date{
            match => [ "timestamp", "yyyy-MM-dd-HH:mm:ss" ]
            locale => "cn"
        }
        
        # ip解析，分析IP的位置
        geoip{
            source => "clientip"
        }
    }
    # 處理來自syslog外掛程式的日誌
    if [fields][class] == "json"{
        json {
            source => "message"
        }
        if [host] == "192.168.68.100" {
            mutate{
                add_field => {"fields_type" => "firewall"}
            }
        }
    }
    
    if ! [fields_type] {
        mutate{
            add_field => {"fields_type" => "error-221"}
        }       
    } 
}
 
output {
    elasticsearch {
        action => "index"
        # 填寫ES集群
        hosts => ["http://node-01:9200","http://node-02:9200","http://node-03:9200"]
        # ES如果有登陸驗證，要配置用戶名和密碼
        #    user => "admin"
        #    password => "123456"
        # 按欄位值，存入不同的索引中
        index => "%{fields_type}-%{+YYYY-MM}"
    }
}

```
