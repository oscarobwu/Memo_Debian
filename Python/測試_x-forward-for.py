#!/usr/bin/python
#-*- coding: utf-8 -*-
#===============================================================================
#
#         Filename:       test4.py
#
#        USAGE: test4.py
#
#  DESCRIPTION:
#
#      OPTIONS: ---
# REQUIREMENTS: ---
#         BUGS: ---
#        NOTES: ---
#       AUTHOR: Oscarob Wu(oscarobwu@gmail.com),
# ORGANIZATION:
#      VERSION: 1.0
#      Created Time: 2021-07-07 14:03:11
#      Last modified: 2021-07-07 14:14
#     REVISION: ---
#===============================================================================
import requests
import socket
import random
import struct
import time

index = 0
while True:
    index += 1
    #time.sleep(1)
    if(index == 1000000):
        print ("end : %s".format(index))
        break
    else:
        url = 'http://192.168.88.153/index.php'
        ip = socket.inet_ntoa(struct.pack('>I', random.randint(1, 0xffffffff)))
        headers = {'X-Forwarded-For': ip, 'content-type':'application/json'}
        print(headers)
        req = requests.get(url,headers=headers)
        print(index)
        print (ip)
        print(req.content.decode('utf-8'))
