#!/usr/bin/python
#-*- coding: utf-8 -*-
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
#ip = input()
ip = '192.168.84.167'
print("Connect: {}".format(ip))
# Create a device object with basic authentication
device = BIGIP(ip, username, password)

# Print virtual servers name
# 列出所有 Virtula server
virtuals = device.load("/mgmt/tm/ltm/virtual")
count = 0
print("List all virtual servers:")
for virtual in virtuals:
    count = count + 1
    vs_name = virtual.properties["name"]
    vsstats = device.show(f"/mgmt/tm/ltm/virtual/{rest_format(vs_name)}")
    #print(virtual.properties["name"])
    print(f'Maximum number of connections client side: {count}. {vsstats.properties["clientside.curConns"]["value"]}')

# Print Pool servers name
# 列出所有 Pool server
pools = device.load("/mgmt/tm/ltm/pool")
print("List all pool :")
for pool in pools:
    print(pool.properties["name"])

# Print node
#node = device.load(f"/mgmt/tm/ltm/node/{rest_format(node_name)}")
#print("Print node:")
#print(node)

#virtual = device.show(
#    f"/mgmt/tm/ltm/virtual/{rest_format(virtual_name)}")
#print(f'Maximum number of connections client side: {virtual.properties["clientside.maxConns"]["value"]}'
