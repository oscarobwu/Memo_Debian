[root@xxxxxxxxcom grafana_http_api]# cat grafana_http_api.py  
#!/usr/bin/env python
#coding=utf-8

import json    # json库方便json数据结构和dict数据结构相互转化
import urllib2  
import sys
from urllib2 import Request, urlopen, URLError, HTTPError   # http库
from pprint import pprint     #  专门打印json数据格式 
from sqlalchemy import create_engine      # 
from sqlalchemy.orm import sessionmaker

#grafana_url="http://localhost:3000/api/dashboards/db/test"
#grafana_url="
 
grafana_server = "localhost"    # grafana主机地址
grafana_port = 3000             # grafana api默认端口
api_keys_name = 'salt-grafana'  # 调用grafana api需要事先在界面上创建一个API_KEYS

# http的报头
grafana_header = {
        "Content-Type":"application/json",
        "Accept": "application/json",
        "Authorization": "Bearer eyJrIjoiUWp5MGlrNk9nNEdGM0hpZ1BkMTdZSFJZODBiVmRPem8iLCJuIjoic2FsdC1ncmFmYW5hIiwiaWQiOjF9"  # 这个是必须的api_keys,格式"Authorization": "Bearer <Your API_keys>“
}

# 因为没有api可以导入API_KEY,而API_KEY默认是存储在sqlite数据库里面这里直接往数据库里面注入一条记录
def create_api_keys(path):
    engine = create_engine('sqlite:///' + path, echo=True)
    Session = sessionmaker(bind=engine)
    session = Session()
    # 事先查询下如果有就不导入了
    res = session.query("name").from_statement("SELECT name FROM api_key where name=:name").params(name=api_keys_name).all()
    if len(res) > 0:
        print "API Keys already exists"
    
    else:
        api_keys_sql = """insert into api_key (id, org_id, name, key, role, created, updated)
                        values(99,1, '%s',
                        '308730ed17b47e97ebd96a071791ba291e0848347195a6050c28662add04f50a74efd328540ecb15e5a3b294c0cd1feaa91c',
                        'Admin', '2015-12-10 09:19:40', '2015-12-10 09:19:40')""" % (api_keys_name)
        try:
            session.execute(api_keys_sql)
            session.commit()
            print  "API Keys import successfully"
        except:
            print "API Keys import failed"
            sys.exit(1)
        finally:
            session.close()
            
# 从外部文件读入json数据            
def get_auth_data(file):
    with open(file, 'r') as f:
        data = json.load(f)  # load方法把json数据结构转化为dict数据结构
        auth_data = json.dumps(data)  # dumps方法把dict数据结构转化为json数据结构因为官方api提供的http数据部分就是json结构
        return auth_data

# post请求    
def post_request(url, auth_data):
    request = urllib2.Request(url,auth_data)
    for key in grafana_header:
        request.add_header(key,grafana_header[key])
    try:
        result = urllib2.urlopen(request)
    except HTTPError, e:
        print 'The server couldn\'t fulfill the request, Error code: ', e.code
    except URLError, e:
        print 'We failed to reach a server.Reason: ', e.reason
    response=json.loads(result.read().decode('utf-8'))
    result.close()
    return response

# 创建grafana dashboard
def create_dashboard(grafana_server, grafana_port, file):
    grafana_url = 'http://{0}:{1}/api/dashboards/db'.format(grafana_server,
                                                            grafana_port)
    auth_data = get_auth_data(file)
    return post_request(grafana_url, auth_data)
    
# 创建数据源
def create_datasource(grafana_server, grafana_port, datasource_type, file):
    datasources = get_datasource(grafana_server, grafana_port)
    for datasource in datasources:
        if datasource_type + '-test' in datasource['name']:
            return "Datasource {0} already exists".format(datasource['name'])
    grafana_url = 'http://{0}:{1}/api/datasources'.format(grafana_server,
                                                          grafana_port)
    auth_data = get_auth_data(file)
    return post_request(grafana_url, auth_data)
    
# 列出所有的数据源
def get_datasource(grafana_server, grafana_port):
    grafana_url = 'http://{0}:{1}/api/datasources'.format(grafana_server,
                                                          grafana_port)
    return post_request(grafana_url, None)
    

if __name__ == '__main__':
    create_api_keys("/var/lib/grafana/grafana.db")
    # 创建zabbix数据源
    pprint(create_datasource(grafana_server, grafana_port, 'zabbix', 'zabbix_datasource.json'))
    # 创建elasticsearch数据源，这里有个bug，api无法指定Index的Pattern.
    pprint(create_datasource(grafana_server, grafana_port, 'elasticsearch', 'elasticsearch_datasource.json'))
    # 创建grafana dashboard
    pprint(create_dashboard(grafana_server, grafana_port, 'data.json'))
    pprint(get_datasource(grafana_server, grafana_port))


##
import requests
url='http://admin:admin@localhost:3000/api/dashboards/db'
data='''{
  "dashboard": {
    "id": null,
    "uid": "mahadev",
    "title": "scriptedDashboard",
    "tags": [ "templated" ],
    "timezone": "browser",
    "schemaVersion": 16,
    "version": 0
  },
  "fIolderd": 48,
  "overwrite": false
}'''
headers={"Content-Type": 'application/json'}
response = requests.post(url, data=data,headers=headers)
print (response.text)

無帳號即可檢視 DashBoard
#################################### Anonymous Auth ######################
[auth.anonymous]
# enable anonymous access
enabled = true

# specify organization name that should be used for unauthenticated users
;org_name = Main Org.

# specify role for unauthenticated users
org_role = Viewer

Grafana 允許被 iframe 遷入
# set to true if you want to allow browsers to render Grafana in a <frame>, <iframe>, <embed> or <object>. default is false.
allow_embedding = true


### 用 Node.js 搞出來的，所以要裝一下 npm 到你的機器之上。
git clone https://github.com/JamesOsgood/mongodb-grafana.git /usr/lib/grafana/plugins
cd /usr/lib/grafana/plugins
npm install
nohup npm run server > mongodb-grafana.log 2&>1 &
service grafana-server restart


import matplotlib.pyplot as plt
import json

from influxdb import InfluxDBClient
client = InfluxDBClient('54.227.227.143', 8086, 'root', '', 'Sensor')

# 將從資料庫select到的資料取內容(get_points())再將型態轉成list
result = list(client.query('select * from Temperature').get_points())
print(result)

time =[]
tem = []
for item in result:
    # 時間部分只取小分秒的地方
    time.append(item["time"][11:19])
    tem.append(item["Tem"])

# 畫圖
plt.xlabel('Time')
plt.ylabel('Temperature')
plt.plot(time, tem ,'b-o')
plt.show()
