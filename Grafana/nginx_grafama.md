新增 nginx Dashbaord

```
https://grafana.com/grafana/dashboards/13437
可以使用

location /nginx_status {
    stub_status on;
    access_log off;
    allow 127.0.0.1;
    deny all;
}

```
