#!/usr/bin/env python3
# -*- coding: utf-8 -*-
# 匯入套件
import requests
import json
import time
from f5.bigip import ManagementRoot
import getpass
import logging

# ini option HTTPS F5 
import urllib3
urllib3.disable_warnings(urllib3.exceptions.InsecurePlatformWarning)

# read excel
import pandas as pd
EXCEL = r'VS_POOL_NODES_LISTS.xlsx'
SHEET = 'DC_02'
#DATA = pd.read_excel(EXCEL, sheet_name=SHEET)
DATA = pd.read_excel(EXCEL,SHEET)
print(DATA)
# read Variable loop
count = DATA['Virtual_Server'].count()

# F5 Host
#  Get login password from CLI
try:
    BIGIP_ADDRESS = input('F5_Host: ')
    USERNAME = input('Username: ')
    PASSWORD = getpass.getpass('Password: ')
    #  Connect to BIG-IP
    mgmt = ManagementRoot(BIGIP_ADDRESS, USERNAME, PASSWORD)
except Exception as e:
    logging.error("Error in connecting to bigip.",e)
    print ("登入失敗 : {} ".format(e))
    #print ("登入失敗 :  ")
    exit(1)


# Function
def create_pool(bigip, Pool_Name, Pool_Description, Pool_Members, Pool_LB_Method, Health_Monitor):
    payload = {}

    # conver member format
    members = [ { 'kind' : 'ltm:pool:members', 'name' : member } for member in Pool_Members ]

    # define lab pool
    payload['kind'] = 'tm:ltm:pool:poolstate'
    payload['name'] = Pool_Name
    payload['description'] = Pool_Description
    payload['loadBalancingMode'] = Pool_LB_Method
    payload['monitor'] = Health_Monitor
    payload['members'] = Pool_Members

    bigip.post('%s/ltm/pool' % BIGIP_URL_BASE, data=json.dumps(payload))

# Funtion create Virtual
def create_http_virtual(bigip, name, description, VIP, Mask, VS_Port, Protocol, pool):
    payload = {}

    # defin LAB virtual
    payload['kind'] = 'tm:ltm:virtual:virtualstate'
    payload['name'] = name
    payload['description'] = description
    payload['destination'] = '%s:%s' % (VIP, VS_Port)
    payload['mask'] = Mask
    payload['ipProtocol'] = Protocol
    payload['sourceAddressTranslation'] = { 'type' : 'automap' }
    payload['profiles'] = [
        { 'kind' : 'ltm:virtual:profile', 'name' : 'http' },
        { 'kind' : 'ltm:virtual:profile', 'name' : 'tcp' }
    ]
    payload['pool'] = pool

    bigip.post('%s/ltm/virtual' % BIGIP_URL_BASE, data=json.dumps(payload))

# Function 
bigip = requests.session()
bigip.auth = (USERNAME, PASSWORD)
bigip.verify = False
bigip.headers.update({'Content-Type' : 'application/json'})
print("Created REST resource For BIP-IP at %s..." % BIGIP_ADDRESS)

# request
BIGIP_URL_BASE = 'https://%s/mgmt/tm' % BIGIP_ADDRESS
# Connect to the F5
ltm = mgmt.tm.ltm
# Create poo
x=0
for x in range(0, count):
    m1 = DATA['Members_1'][x].split('\n')
    processed = []
    for t in m1:
        n1 = t.split(':')[0]
        #print(n1)
        if mgmt.tm.ltm.nodes.node.exists(partition='Common', name='node_' + n1):
            print("存在")
        else:
            ltm.nodes.node.create(name='node_' + n1, address=n1)
            print("不存在建立")

        processed.append('node_' + t)

    print(processed)
    Pool_Members = processed
    create_pool(bigip, DATA['Pool_Name'][x], DATA['Pool_Description'][x], Pool_Members, DATA['LB_Method'][x], DATA['Health_Monitor'][x])
    print("Create pool \"%s\" with members %s..." % (DATA['Pool_Name'][x], ", ".join(Pool_Members)))

# Create Virtual Server
y=0
for y in range(0, count):
    create_http_virtual(bigip, DATA['Virtual_Server'][y], DATA['VS_Description'][x], DATA['Virtual_IP'][y], DATA['Mask'][y], DATA['VS_Port'][y], DATA['Protocol'][y], DATA['Pool_Name'][y])
    print("Created Virtual Server \"%s\" with destination %s:%s..." % (DATA['Virtual_Server'][y], DATA['Virtual_IP'][y], DATA['VS_Port'][y]))
