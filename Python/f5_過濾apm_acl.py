#!/usr/bin/env python3
# -*- coding: utf-8 -*-
# 將 查詢F5 apm ACL 過濾 使用者 的acl  description 設定使用這名稱
# 使用 bigrest 套件來過濾 
# 降低 cpu 使用量 15xx個VS 
# 匯入套件
#===============================================================================
import time
import pandas as pd
import os
import sys
#
import getpass
import logging
# ini option HTTPS F5 
import urllib3
urllib3.disable_warnings(urllib3.exceptions.InsecurePlatformWarning)
from bigrest.bigip import BIGIP
from bigrest.utils.utils import rest_format
from datetime import datetime

###########################
# F5 Host
###########################
def login_per():
    try:
        BIGIP_ADDRESS = input('F5_Host: ')
        USERNAME = input('Username: ')
        PASSWORD = getpass.getpass('Password: ')
        #  Connect to BIG-IP
        device = BIGIP(BIGIP_ADDRESS, USERNAME, PASSWORD)
        print(f"登入成功 to connect to {BIGIP_ADDRESS}\n")
    except Exception as e:
        #logging.error("Error in connecting to bigip.",e)
        #print ("登入失敗 : {} ".format(e))
        print(f"登入失敗 Failed to connect to {BIGIP_ADDRESS} due to {type(e).__name__}:\n")
        print(f"{e}")
        #print ("登入失敗 :  ")
        sys.exit()
    #
    acl_username = input('ACL Username: ').lower()
    acl_ip_list = input('ACL IP Address: ').lower()
    #
    user_acl(device, acl_username, acl_ip_list)
    #return device, acl_username, acl_ip_list
# 練習使用 def 來做
# 利用 user_acl 來呼叫
def is_standby(device):
    status = device.load("/mgmt/tm/sys/failover")
    if "standby" in status.properties.get('apiRawValues').get('apiAnonymous'):
        return "standby"
    else:
        return "active"

def acl_parser_list(device, acl_name, acl_ip_list):
    acl_name_list = device.load(f"/mgmt/tm/apm/acl/{rest_format(acl_name)}")
    acl_count = 0
    for i in acl_name_list.properties["entries"]:
        #acl_count = 0
        dst_IP = i["dstSubnet"]
        #print(dst_IP)
        if acl_ip_list in dst_IP:
            acl_count = acl_count + 1
            print("\t{:04d}. {}".format(acl_count, i["dstSubnet"]))


def user_acl(device, acl_username, acl_ip_list):
    print(f'Task start at {datetime.now().strftime("%H:%M:%S")}')
    #b = instantiate_bigip(host, DEVICE_USER, DEVICE_PASSWORD)
    # 這會呼叫 上面的 is_standby 模組
    ha_status = is_standby(device)

    if ha_status == "ignore" or ha_status == "standby":
        print("Standby")
    else:
        print("active")
    #print(acl_username)
    #
    #
    apm_acl_name_only = device.load('/mgmt/tm/apm/acl/?$select=name,description,aclOrder')
    for acl in apm_acl_name_only:
        try:
            acl_user = acl.properties["description"].lower()
            #print(acl_user)
            #print("{}, {} ".format(acl.properties["name"], acl.properties["description"]))
            #vs_file.write("{}\n".format(p.properties["name"]))
            if acl_username in acl_user:
                print("\n{}, {}, {} ".format(acl.properties["name"], acl.properties["description"], acl.properties["aclOrder"]))
                acl_name = acl.properties["description"]
                #acl_name_list = device.load(f"/mgmt/tm/apm/acl/{rest_format(acl_name)}")
                #acl_count = 0
                #for i in acl_name_list.properties["entries"]:
                #    #acl_count = 0
                #    dst_IP = i["dstSubnet"]
                #    #print(dst_IP)
                #    if acl_ip_list in dst_IP:
                #        acl_count = acl_count + 1
                #        print("\t{:03d}. {}".format(acl_count, i["dstSubnet"]))
                acl_parser_list(device, acl_name, acl_ip_list)
                
        except:
            #print("{}, {} ".format(acl.properties["name"], "None"))
            continue
    #return device
    #
    #return device, acl_username
    #print(f'Task completed at {datetime.now().strftime("%H:%M:%S")}')

#def user_acl(device, acl)


if __name__ == "__main__":
    args = login_per()
    print(f'\nend Task completed at {datetime.now().strftime("%H:%M:%S")}')
