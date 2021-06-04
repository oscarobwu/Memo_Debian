#!/usr/bin/env python
#coding=utf-8
import sys
import requests
import json
import getpass
from requests.api import delete
#
# 關閉SSL錯誤
import urllib3
urllib3.disable_warnings(urllib3.exceptions.InsecureRequestWarning)
import copy
#  Get login password from CLI
#grafana_host = input('Grafana_Host: ')
grafana_host = "192.168.88.13"
print(grafana_host)
grafana_user = input('Username: ')
grafana_pw = getpass.getpass('Password: ')
grafana_uid = input('Grafana_Dashboarde(F5-Load-Balance): ')
grafana_title = input('Grafana_title(A000001): ')
# Let's say you have uid from title
headers={"Content-Type": "application/json"}
uid_url='https://' + grafana_user + ':' + grafana_pw + '@' + grafana_host + '/grafana/api/search?query=%'
uid_r = requests.get(url = uid_url, headers = headers, verify=False)
for uid_item in uid_r.json():
    if uid_item['title'] == grafana_uid:
        #print(uid_item)
        uid = uid_item['uid']
        #print(uid)

# get the content of dashboard from the example above
# url = server + "/api/dashboards/uid/" + uid
# 要確認 url 是否正確
url='https://' + grafana_user + ':' + grafana_pw + '@' + grafana_host + "/grafana/api/dashboards/uid/" + uid
#r = requests.get(url=url, headers=headers, verify=False)
r = requests.get(url=url, headers=headers, verify=False)
dash_data = r.json()
#print(r.json())
#print(dash_data)
print(dash_data['dashboard']['id'])
print(dash_data['dashboard']['title'])
print(dash_data['meta']['folderId'])
#for item in r.json():
#    print(item)
da_data = r.json()['dashboard']
my_list = dash_data['dashboard']['panels'][-1]
plant_my_list = my_list['gridPos']
plant_my_list_h = my_list['gridPos']['h']
plant_my_list_w = my_list['gridPos']['w']
plant_my_list_x = my_list['gridPos']['x']
plant_my_list_y = my_list['gridPos']['y']
#
if plant_my_list_x == '0':
   plant_my_list_x = 12
   plant_my_list_y = plant_my_list_y
else:
   plant_my_list_x = 0
   plant_my_list_y = plant_my_list_y + 9

print("顯示最後座標 : %s " % plant_my_list)
print("顯示最後座標 x: %s " % plant_my_list_x)
print("顯示最後座標 y: %s " % plant_my_list_y)
#
# 新增 圖表
#dashboard_data = copy.deepcopy(dash_data)
dashboard_plant_data = copy.deepcopy(my_list)
if dashboard_plant_data['gridPos']['x'] == 0:
   dashboard_plant_data['gridPos']['x'] = 12
   dashboard_plant_data['gridPos']['y'] = dashboard_plant_data['gridPos']['y']
else:
   dashboard_plant_data['gridPos']['x'] = 0
   dashboard_plant_data['gridPos']['y'] = dashboard_plant_data['gridPos']['y'] + 9
#Panel_Title = grafana_title 
dashboard_plant_data['title'] = grafana_title
dashboard_plant_data['id'] = dashboard_plant_data['id'] + 1
#
#temp = dash_data['dashboard']['panels']
#print(temp)
# appending data to emp_details 
#print(dashboard_plant_data)
#temp.append(dashboard_plant_data)
#print(temp)

temp2 = dash_data
temp2['dashboard']['panels'].append(dashboard_plant_data)
#print(json.dumps(temp2, sort_keys=True, indent=4, separators=(',', ': ')))
# 檢查使用
print("顯示 uid : %s " % uid)
#dashboard_data = copy.deepcopy(dash_data)
dashboard_data = copy.deepcopy(temp2)
dashboard_data["dashboard"]['id'] = temp2['dashboard']['id']
dashboard_data["dashboard"]['uid'] = uid
dashboard_data["dashboard"]['version'] = temp2['dashboard']['version'] + 1
dashboard_data["folderId"] = 0
dashboard_data["overwrite"] = True
# 將dashboard 資料更新 路徑
dr_url='https://' + grafana_user + ':' + grafana_pw + '@' + grafana_host + "/grafana//api/dashboards/db"
#
#print(json.dumps(dashboard_data, sort_keys=True, indent=4, separators=(',', ': ')))
dr = requests.post(url=dr_url, headers=headers, data=json.dumps(dashboard_data), verify=False)
print(dr.json())
