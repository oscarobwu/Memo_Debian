```
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
