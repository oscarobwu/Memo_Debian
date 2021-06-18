#!/usr/bin/python
#-*- coding: utf-8 -*-
# 
# 功能 : 提供給 jenkins 自動化開關機使用
# File
#===============================================================================
"""
BIGREST SDK tests
Perform test on a BIG-IP device
"""

# External Imports
# Import only with "import package",
# it will make explicity in the code where it came from.
import getpass
import os
import time
import hashlib
import argparse,json,requests  

# Internal imports
# Import only with "from x import y", to simplify the code.
from bigrest.bigip import BIGIP
from bigrest.utils.utils import rest_format
from bigrest.utils.utils import token

# Get username, password, and ip
print("Username: ", end="")
username = input()
password = getpass.getpass()
print("Device IP or name: ", end="")
#host = input()
host = '192.168.88.166'
print("Connect: {}".format(host))
# Create a device object with basic authentication
device = BIGIP(host, username, password)
print("select IP: ", end="")
#s_n_ip = input()
#  Node to search for
print('Node_清單使用換行分隔\n10.99.0.11\n10.99.0.12\n10.99.0.13\n.....\nIPaddress: ')
try:
    member_list = []
    while True:
        sn = input().strip()
        if sn == '':
            break
        member_list.append(sn)
except:
    pass

#action = input('[enabled, disabled, forced_offline, checked] : ')
print("選擇開關機的方式?\n")
print("[1] enabled\n")
print("[2] disabled\n")
print("[3] forced_offline\n")
print("[4] checked\n")
Sele = input("Your choice (press enter to skip): ")
if Sele == '1':
    action = 'enabled' # 開啟
elif Sele == '2':
    action = 'disabled' # 關閉
elif Sele == '3':
    action = 'forced_offline' # 強制關閉
elif Sele == '4':
    action = 'checked' # 檢查
else:
    action = "" # default value is none.

poolmemblist = []
all_pools = device.load("/mgmt/tm/ltm/pool")
for pool in all_pools:
    pool_name = pool.properties["name"]
    pooldetail = device.load(f"/mgmt/tm/ltm/pool/{pool_name}/members")
    pool_stats = device.show(f"/mgmt/tm/ltm/pool/~Common~{pool_name}")
    currm = pool_stats.properties["availableMemberCnt"]["value"]
    for member in pooldetail:
        member_add = member.properties["address"]
        member_name = member.properties["name"]
        #print(member_add)
        for membadd in member_list:
            if member_add == membadd:
                #print(pool_name + "," + member_name + ',' + membadd)
                poolmemblist.append(pool_name + "," + member_name + ',' + membadd)
                #

#
pool_list = []
#
#
for poolnam in poolmemblist:
    poolna = poolnam.split(',')
    #time.sleep(1)
    pool_stats = device.show(f"/mgmt/tm/ltm/pool/~Common~{poolna[0]}")
    currm = pool_stats.properties["activeMemberCnt"]["value"]
    if currm <= 1 and action != 'enabled':
        print("pool : {} = pool_member 少於1 : {}".format(poolna[0], currm))
        continue
    else:
        #
        #time.sleep(2)
        print("pool : {} = pool_member 大於 1 : {}".format(poolna[0], currm))
        if action == 'enabled':
            #enable
            actiont = {}
            actiont["state"] = "user-up"
            actiont["session"] = "user-enabled"
            pool_updated = device.modify(f"/mgmt/tm/ltm/pool/{poolna[0]}/members/~Common~{poolna[1]}", actiont)
            pool_detail= device.load(f"/mgmt/tm/ltm/pool/{poolna[0]}/members/~Common~{poolna[1]}")
            mem_status = pool_detail.properties['state']
            pool_ststs = device.show(f"/mgmt/tm/ltm/pool/{poolna[0]}/members/~Common~{poolna[1]}")
            mem_curr_conns = pool_ststs.properties["curSessions"]["value"]
            print("Member 目前狀態 : {} ".format(mem_status))
            print("Member 顯示連線數 : {} ".format(mem_curr_conns))
        elif action == 'disabled':
            #disable
            actiont = {}
            actiont["state"] = "user-up"
            actiont["session"] = "user-enabled"
            pool_updated = device.modify(f"/mgmt/tm/ltm/pool/{poolna[0]}/members/~Common~{poolna[1]}", actiont)
            pool_detail= device.load(f"/mgmt/tm/ltm/pool/{poolna[0]}/members/~Common~{poolna[1]}")
            mem_status = pool_detail.properties['state']
            pool_ststs = device.show(f"/mgmt/tm/ltm/pool/{poolna[0]}/members/~Common~{poolna[1]}")
            mem_curr_conns = pool_ststs.properties["curSessions"]["value"]
            print("Member 目前狀態 : {} ".format(mem_status))
            print("Member 顯示連線數 : {} ".format(mem_curr_conns))
        elif action == 'forced_offline':    
            #forced_offline
            actiont = {}
            actiont["state"] = "user-down"
            actiont["session"] = "user-disabled"
            pool_updated = device.modify(f"/mgmt/tm/ltm/pool/{poolna[0]}/members/~Common~{poolna[1]}", actiont)
            pool_detail= device.load(f"/mgmt/tm/ltm/pool/{poolna[0]}/members/~Common~{poolna[1]}")
            mem_status = pool_detail.properties['state']
            pool_ststs = device.show(f"/mgmt/tm/ltm/pool/{poolna[0]}/members/~Common~{poolna[1]}")
            mem_curr_conns = pool_ststs.properties["curSessions"]["value"]
            print("Member 目前狀態 : {} ".format(mem_status))
            print("Member 顯示連線數 : {} ".format(mem_curr_conns))
        elif action == 'checked':
            #forced_offline
            pool_detail= device.load(f"/mgmt/tm/ltm/pool/{poolna[0]}/members/~Common~{poolna[1]}")
            mem_status = pool_detail.properties['state']
            pool_ststs = device.show(f"/mgmt/tm/ltm/pool/{poolna[0]}/members/~Common~{poolna[1]}")
            mem_curr_conns = pool_ststs.properties["curSessions"]["value"]
            print("Member 目前狀態 : {} ".format(mem_status))
            print("Member 顯示連線數 : {} ".format(mem_curr_conns))

