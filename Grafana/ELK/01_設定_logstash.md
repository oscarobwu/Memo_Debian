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
