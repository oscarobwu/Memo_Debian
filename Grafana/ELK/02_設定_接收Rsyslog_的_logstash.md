# 02_設定_接收Rsyslog_的_logstash.md
----

##### 編輯設定檔
```
# vi /etc/logstash/conf.d/logstash.conf
```
##### 將內容新增到設定檔中

```
# This input block will listen on port 10514 for logs to come in.
# host should be an IP on the Logstash server.
# codec => "json" indicates that we expect the lines we're receiving to be in JSON format
# type => "rsyslog" is an optional identifier to help identify messaging streams in the pipeline.

input {
  udp {
    host => "logstash_private_ip"
    port => 10514
    codec => "json"
    type => "rsyslog"
  }
}

# This is an empty filter block.  You can later add other filters here to further process
# your log lines

filter { }

# This output block will send all events of type "rsyslog" to Elasticsearch at the configured
# host and port into daily indices of the pattern, "rsyslog-YYYY.MM.DD"

output {
  if [type] == "rsyslog" {
    elasticsearch {
      hosts => [ "elasticsearch_private_ip:9200" ]
      #user => "myusername"
      #password => "mypassword"
      index => "logstash-nginx-sysadmins-%{+YYYY.MM.dd}"
    }
  }
}
```

<style>
r { color: Red }
o { color: Orange }
g { color: Green }
</style>

# TODOs:

- <r>TODO:</r> Important thing to do
- <o>TODO:</o> Less important thing to do
- <g>DONE:</g> Breath deeply and improve karma

- ![#f03c15](https://via.placeholder.com/15/f03c15/000000?text=+) `#f03c15`
- ![#c5f015](https://via.placeholder.com/15/c5f015/000000?text=+) `#c5f015`
- ![#1589F0](https://via.placeholder.com/15/1589F0/000000?text=+) `#1589F0`
