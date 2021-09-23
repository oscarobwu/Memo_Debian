# 設定F5 syslog
----
```language

git clone https://github.com/OutsideIT/logstash_filter_f5.git

cd logstash_filter_f5

cp f5.conf /etc/logstash/conf.d/01-f5.conf

vi /etc/logstash/conf.d/01-f5.conf

mkdir /etc/logstash/patterns

cp f5-patterns.yml /etc/logstash/patterns/f5-patterns.yml

mkdir /etc/logstash/dictionaries

cp syslogpri.yml /etc/logstash/dictionaries/syslogpri.yml

修改
:%s/search_from/replace_to/g

:%s/\/etc\/logstash\/patterns/\/etc\/logstash\/patterns\/f5-patterns.yml/g

將 port 改為 5514

```
```
http://192.168.88.245:9200/_cat/indices?v
```
