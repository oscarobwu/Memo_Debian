sss
### 設定使用檔安清單

```
scrape_configs:
- job_name: 'blackbox'
  metrics_path: /probe
  params:
    module: [http_2xx]
  static_configs:
    - targets:
      - https://example.com
      - http://example.net:808
	  
	  
更新為檔案
scrape_configs:
- job_name: 'blackbox'
  metrics_path: /probe
  params:
    module: [http_2xx]
  file_sd_configs:
  - files: ['/etc/prometheus/monitor-config/web/*.yml']   # 监控web目錄配置放置目录
    refresh_interval: 5s
	
vi /etc/prometheus/monitor-config/web/check_cn_site.yml
################## 設定如下 : 
- targets: [ "https://example.com1" ]
  labels:
    group: "CN-site"
- targets: [ "http://example.net:808" ]
  labels:
    group: "CN-site"

```
