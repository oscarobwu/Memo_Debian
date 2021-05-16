#!/usr/bin/env python3
# -*- coding: utf-8 -*-
# 匯入套件
#from f5_A_Create_VS_Pool_nodes import BIGIP_URL_BASE
import requests
import json
import time
from f5.bigip import ManagementRoot
import getpass
import logging

# ini option HTTPS F5 admin
import urllib3
urllib3.disable_warnings(urllib3.exceptions.InsecurePlatformWarning)

# read excel
import pandas as pd
#EXCEL = 'VS_POOL_NODES_LISTS.xlsx'
EXCEL = r'D:\LAB\ltmexcle\Delete_VS_POOL_NODES_LISTS_v1.xlsx'
SHEET = 'DC_02'
DATA = pd.read_excel(EXCEL,SHEET)

# read Variable loop
count = DATA['Virtual_Server'].count()

# F5 Host
try:
    #BIGIP_ADDRESS = input('F5_Host: ')
    BIGIP_ADDRESS = '192.168.84.167'
    USERNAME = input('Username: ')
    PASSWORD = getpass.getpass('Password: ')
    #  Connect to BIG-IP
    mgmt = ManagementRoot(BIGIP_ADDRESS, USERNAME, PASSWORD)
except Exception as e:
    logging.error("Error in connecting to bigip.",e)
    print ("登入失敗 : {} ".format(e))
    #print ("登入失敗 :  ")
    exit(1)

ADVANCED_DELETE = ManagementRoot(BIGIP_ADDRESS, USERNAME, PASSWORD)

# Function
def delete_vs(bigip, Virtual_Server):
    bigip.delete('%s/ltm/virtual/%s' % (BIGIP_URL_BASE, Virtual_Server))
    #print("def : bigip {} , Virtual_Server : {} ".format(bigip, Virtual_Server))

# Function
def delete_pool(bigip, Pool_Name):
    bigip.delete("%s/ltm/pool/%s" % (BIGIP_URL_BASE, Pool_Name))

# Function 
bigip = requests.session()
bigip.auth = (USERNAME, PASSWORD)
bigip.verify = False
bigip.headers.update({'Content-Type' : 'application/json'})
print("Created REST resource For BIP-IP at %s..." % BIGIP_ADDRESS)

# request
BIGIP_URL_BASE = "https://%s/mgmt/tm" % BIGIP_ADDRESS

# Delete Virtual Server
y=0
for y in range(0, count):
    virtual = ADVANCED_DELETE.tm.ltm.virtuals.virtual.load(name=(DATA['Virtual_Server'][y]))
    vsstats = virtual.stats.load()
    if virtual.name == DATA['Virtual_Server'][y]:
        delete_vs(bigip, DATA['Virtual_Server'][y])
        print("delete Virtual Server \"%s\"....." % (DATA['Virtual_Server'][y]))

#Delete Pool
x=0
for x in range(0, count):
    pool = ADVANCED_DELETE.tm.ltm.pools.pool.load(name=(DATA['Pool_Name'][x]))
    poolstats = pool.stats.load()
    if pool.name == DATA['Pool_Name'][x]:
        delete_pool(bigip, DATA['Pool_Name'][x])
        print("delete Pool \"%s\"....." % (DATA['Pool_Name'][x]))
