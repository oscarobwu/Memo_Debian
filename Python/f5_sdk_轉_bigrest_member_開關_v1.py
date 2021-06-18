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
import hashlib
import argparse,json,requests  

# Internal imports
# Import only with "from x import y", to simplify the code.
from bigrest.bigip import BIGIP
from bigrest.utils.utils import rest_format
from bigrest.utils.utils import token

cmdargs = argparse.ArgumentParser()
#cmdargs.add_argument('--host',action='store',required=True,type=str,help='ip of BIG-IP REST interface, typically the mgmt ip')  
#cmdargs.add_argument('--username',action='store',required=True,type=str,help='username for REST authentication')  
#cmdargs.add_argument('--password',action='store',required=True,type=str,help='password for REST authentication')  
cmdargs.add_argument('--pool',action='store',required=True,type=str,help='user pool name')
cmdargs.add_argument('--member',action='store',required=True,type=str,help='user pool memeber')
cmdargs.add_argument('--action',action='store',required=True,type=str,help='the Member action (enabled, Disable, forced_offline)')
#
parsed_args = cmdargs.parse_args()  
#
host = parsed_args.host  
username = parsed_args.username  
password = parsed_args.password  
pool_name = parsed_args.pool
member = parsed_args.member
action = str(parsed_args.action).lower()  
#
# Get username, password, and ip
print("Username: ", end="")
#username = input()
#password = getpass.getpass()
print("Device IP or name: ", end="")
#host = input()
#host = '192.168.88.166'
print("Connect: {}".format(host))
# Create a device object with basic authentication
device = BIGIP(host, username, password)


if action == 'enabled':
    #enable
    actiont = {}
    actiont["state"] = "user-up"
    actiont["session"] = "user-enabled"
    pool_updated = device.modify(f"/mgmt/tm/ltm/pool/{pool_name}/members/~Common~{member}", actiont)
    pool_detail= device.load(f"/mgmt/tm/ltm/pool/{pool_name}/members/~Common~{member}")
    mem_status = pool_detail.properties['state']
    pool_ststs = device.show(f"/mgmt/tm/ltm/pool/{pool_name}/members/~Common~{member}")
    mem_curr_conns = pool_ststs.properties["curSessions"]["value"]
    print("Member 目前狀態 : {} ".format(mem_status))
    print("Member 顯示連線數 : {} ".format(mem_curr_conns))
elif action == 'disabled':
    #disable
    actiont = {}
    actiont["state"] = "user-up"
    actiont["session"] = "user-enabled"
    pool_updated = device.modify(f"/mgmt/tm/ltm/pool/{pool_name}/members/~Common~{member}", actiont)
    pool_detail= device.load(f"/mgmt/tm/ltm/pool/{pool_name}/members/~Common~{member}")
    mem_status = pool_detail.properties['state']
    pool_ststs = device.show(f"/mgmt/tm/ltm/pool/{pool_name}/members/~Common~{member}")
    mem_curr_conns = pool_ststs.properties["curSessions"]["value"]
    print("Member 目前狀態 : {} ".format(mem_status))
    print("Member 顯示連線數 : {} ".format(mem_curr_conns))
elif action == 'forced_offline':    
    #forced_offline
    actiont = {}
    actiont["state"] = "user-down"
    actiont["session"] = "user-disabled"
    pool_updated = device.modify(f"/mgmt/tm/ltm/pool/{pool_name}/members/~Common~{member}", actiont)
    pool_detail= device.load(f"/mgmt/tm/ltm/pool/{pool_name}/members/~Common~{member}")
    mem_status = pool_detail.properties['state']
    pool_ststs = device.show(f"/mgmt/tm/ltm/pool/{pool_name}/members/~Common~{member}")
    mem_curr_conns = pool_ststs.properties["curSessions"]["value"]
    print("Member 目前狀態 : {} ".format(mem_status))
    print("Member 顯示連線數 : {} ".format(mem_curr_conns))
elif action == 'checked':
    #forced_offline
    pool_detail= device.load(f"/mgmt/tm/ltm/pool/{pool_name}/members/~Common~{member}")
    mem_status = pool_detail.properties['state']
    pool_ststs = device.show(f"/mgmt/tm/ltm/pool/{pool_name}/members/~Common~{member}")
    mem_curr_conns = pool_ststs.properties["curSessions"]["value"]
    print("Member 目前狀態 : {} ".format(mem_status))
    print("Member 顯示連線數 : {} ".format(mem_curr_conns))


#顯示 Poolmember 連線數
pool = device.show(f"/mgmt/tm/ltm/pool/{rest_format(pool_name)}")
pool_member_list = device.load(f"/mgmt/tm/ltm/pool/~Common~{pool_name}/members")
curr_conns = pool.properties["curSessions"]["value"]
print("\npool 名稱: {} ".format(pool_name))
print("顯示連線數 : {} ".format(curr_conns))
for member_s in pool_member_list:
    member_ss = member_s.properties['name']
    pool_detail= device.load(f"/mgmt/tm/ltm/pool/{pool_name}/members/~Common~{member_ss}")
    mem_status = pool_detail.properties['state']
    pool_ststss = device.show(f"/mgmt/tm/ltm/pool/{pool_name}/members/~Common~{member_ss}")
    mem_curr_connss = pool_ststss.properties["curSessions"]["value"]
    print("\t{:<25} 連線數 : {:<6} 狀態 : {:<10} ".format(member_ss, mem_curr_connss, mem_status))
