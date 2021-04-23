#!/usr/bin/env python3
#
from bigrest.bigip import BIGIP
import logging, ipaddress
import getpass
import re
import time
import datetime
#from datetime import datetime
#
f5_host = input('F5-IP: ')
f5user = input('Username: ')
f5pw = getpass.getpass('Password: ')
#subinput = input('Find_Username ( ex: 10.101.139.21 ) : ')
try:
    # Need to re-write this to store as an encrypted credential file
    #mgmt = ManagementRoot("192.168.1.11","admin","121278")
    device = BIGIP(f5_host, f5user, f5pw)
except Exception as e:
    #logging.error("Error in connecting to bigip.",e)
    print ("登入失敗 : {} ".format(e))
    #print(e)
    exit()
#
t1 = datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S')
print("開始時間 : {}".format(t1))
poolstats = device.show('/mgmt/tm/ltm/pool')
for p in poolstats:
    for k, v in p.properties.items():
        if v.get('description') != None:
            print(f'{k}: {v.get("description")}')
        elif v.get('value') != None:
            print(f'{k}: {v.get("value")}')

##
pools = device.load('/mgmt/tm/ltm/pool/?$select=name,loadBalancingMode')
for p in pools:
    print("show : {} - LB_mode: {}".format(p.properties['name'], p.properties['loadBalancingMode']))
