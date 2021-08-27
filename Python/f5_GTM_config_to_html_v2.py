#!/usr/bin/env python3
# -*- coding: utf-8 -*-
# 將 F5設定檔整理成 html
# 使用 bigrest 套件來過濾 for GTM
# 降低 cpu 使用量 15xx個VS 
# 匯入套件
# 更新 member order 順訊 
#===============================================================================
"""
# 20210827 更新 member order 順序
"""
import time
import pandas as pd
import os
#
import re
import getpass
import logging
# ini option HTTPS F5 
import urllib3
urllib3.disable_warnings(urllib3.exceptions.InsecurePlatformWarning)
from bigrest.bigip import BIGIP

##
#
# F5 Host
#
try:
    BIGIP_ADDRESS = input('F5_GTM_Host: ')
    USERNAME = input('Username: ')
    PASSWORD = getpass.getpass('Password: ')
    #  Connect to BIG-IP
    device = BIGIP(BIGIP_ADDRESS, USERNAME, PASSWORD)
except Exception as e:
    logging.error("Error in connecting to bigip.",e)
    print ("登入失敗 : {} ".format(e))
    #print ("登入失敗 :  ")
    os.system("pause")
    exit(1)
#
time_start = time.time() 
devset = device.load(f"/mgmt/tm/sys/global-settings")
settings = devset.properties["hostname"]
print("\nshow hostname : {} ".format(settings))
print("\n###### 登入成功開始輸出設定 ######")
tf = pd.Timestamp("today").strftime("%Y-%m-%d_%H%M%S")
#
vs_new = BIGIP_ADDRESS + '_VS_Wideip_' + tf + '.txt'
wideip_name_only = device.load('/mgmt/tm/gtm/wideip/a?$select=name')
vs_file = open(vs_new, "w", encoding="utf-8")
for p in wideip_name_only:
    #print(p.properties["name"])
    vs_file.write("{}\n".format(p.properties["name"]))

vs_file.close()

#time.sleep(2)
#
file1 = open('{}'.format(vs_new) , 'r')
Lines = file1.readlines()
file1.close()
#
html_new = './' + BIGIP_ADDRESS + '_GTM_Wideip_LISTS_' + tf + '.html'
g_color = "bgcolor=Lime"
r_color = "bgcolor=red"
b_color = "bgcolor=LightSkyBlue"
d_color = "bgcolor=DeepSkyBlue"
t_color = "bgcolor=#EAF2D3"
w_color = "bgcolor=WhiteSmoke"
f_color = "bgcolor=GoldenRod"
l_color = "bgcolor=#48d1cc"
#
my_file = open(html_new, "w")
my_file.write("<table border=4 align=center width=95% bgcolor=Gray cellspacing=5 cellpadding=6>\n")
my_file.write("<thead>\n")
my_file.write("<caption> {} Virtual_Service_Table</caption>\n".format(tf))
my_file.write("<tr {}><th colspan=\"19\">{}</th></tr>\n".format(f_color, settings))
my_file.write("<tr {}>\n".format(f_color))
my_file.write("  <th rowspan=\"1\"><div style=\"width: 50px;\">編號</div></th>\n")
my_file.write("  <th rowspan=\"1\">Wideip_域名</th>\n")
my_file.write("  <th rowspan=\"1\">WIP-STATUS</th>\n")
my_file.write("  <th rowspan=\"1\">VS_描述說明</th>\n")
my_file.write("  <th rowspan=\"1\">Wideip-LB-MODE</th>\n")
my_file.write("  <th rowspan=\"1\">POOL-NAME</th>\n")
my_file.write("  <th rowspan=\"1\">POOL-STATUS</th>\n")
my_file.write("  <th rowspan=\"1\">POOL-LB-MODE</th>\n")
my_file.write("  <th rowspan=\"1\">POOL-MEMBERS</th>\n")
my_file.write("  <th rowspan=\"1\">備註</th>\n")
my_file.write("</tr>\n")
my_file.write("</thead>\n")
my_file.write("<tbody>\n")
#
count = 0
#
for wideip_namel in Lines:
    #count += 1
    wideip_a_n = wideip_namel.strip()
    #
    wideip_a = device.load("/mgmt/tm/gtm/wideip/a/{}".format(wideip_a_n))
    time_vs_start = time.time() 
    t1 = count + 1
    print(t1)
    count = count + 1
    tt= pd.Timestamp("today").strftime("%Y-%m-%d_%H:%M:%S")
    wideip_a_name = wideip_a.properties["name"]
    try:
        wideip_a_descript = wideip_a.properties["description"]
    except:
        wideip_a_descript = "None"

    wideip_a_lb = wideip_a.properties["poolLbMode"]
    #wideip_pool_s = wideip_a.properties["pools"]
    wideip_a_stats = device.load("/mgmt/tm/gtm/wideip/a/~Common~{}/stats".format(wideip_a_n))
    wideip_a_st =list(wideip_a_stats.properties['entries'].values())[0]['nestedStats']['entries']['status.availabilityState']['description']
    wideip_a_en =list(wideip_a_stats.properties['entries'].values())[0]['nestedStats']['entries']['status.enabledState']['description']
    t3 = wideip_a_stats.properties['entries'].values()
#
    try:
        wideip_poolg = []
        wideip_poolm = []
        wideip_poolg2 = []
        wideip_poolm2 = []
        wideip_pool = wideip_a.properties["pools"]
        w_count = 0
        t = 0
        w_t = 0
        for wipn in wideip_pool:
            w_count = w_count + 1
            wipnn = wipn["name"]
            #
            gtmw_poolp = device.load("/mgmt/tm/gtm/pool/a/{}".format(wipnn))
            wipnnw_lb = gtmw_poolp.properties["loadBalancingMode"]
            w_a_pool_stats = device.load("/mgmt/tm/gtm/pool/a/~Common~{}/stats".format(wipnn))
            w_a_pool_st =list(w_a_pool_stats.properties['entries'].values())[0]['nestedStats']['entries']['status.availabilityState']['description']
            w_a_pool_en =list(w_a_pool_stats.properties['entries'].values())[0]['nestedStats']['entries']['status.enabledState']['description']
            #print(wipn)
            if w_count == 1:
                w_a_gtm_pools_member = device.load('/mgmt/tm/gtm/pool/a/~Common~{}/members'.format(wipnn))
                w_a_gtm_member_list = []
                for w_gtm_members in w_a_gtm_pools_member:
                    w_t = w_t + 1
                    w_gtm_member = w_gtm_members.properties['name']
                    w_a_gtm_memb_order = w_gtm_members.properties["memberOrder"]
                    w_gtm_dc = w_gtm_member.split(':')[0]
                    w_gtm_vs = w_gtm_member.split(':')[1]
                    w_a_gtm_memb_dest = device.load('/mgmt/tm/gtm/server/{}/virtual-servers/{}'.format(w_gtm_dc, w_gtm_vs))
                    w_a_gtm_dest = w_a_gtm_memb_dest.properties["destination"]
                    w_a_gtm_member_list.append("{}. {} dest-----> {} <br>".format(w_a_gtm_memb_order, w_gtm_member, w_a_gtm_dest))
                w_a_gtm_member_list.sort()
                wideip_poolg.append("<td {}>{}. {}</td><td {}>{}-{}</td><td {}>{}</td><td {}>{}</td>".format(l_color, w_count, wipn["name"], l_color, w_a_pool_st, w_a_pool_en, l_color, wipnnw_lb, l_color, "".join(w_a_gtm_member_list)))
                wideip_poolm.append("{}".format(wipn["name"]))
            else:
                w_a_gtm_pools_member = device.load('/mgmt/tm/gtm/pool/a/~Common~{}/members'.format(wipnn))
                w_a_gtm_member_list = []
                for w_gtm_members in w_a_gtm_pools_member:
                    w_t = w_t + 1
                    w_gtm_member = w_gtm_members.properties['name']
                    w_a_gtm_memb_order = w_gtm_members.properties["memberOrder"]
                    w_gtm_dc = w_gtm_member.split(':')[0]
                    w_gtm_vs = w_gtm_member.split(':')[1]
                    w_a_gtm_memb_dest = device.load('/mgmt/tm/gtm/server/{}/virtual-servers/{}'.format(w_gtm_dc, w_gtm_vs))
                    w_a_gtm_dest = w_a_gtm_memb_dest.properties["destination"]
                    w_a_gtm_member_list.append("{}. {} dest-----> {} <br>".format(w_a_gtm_memb_order, w_gtm_member, w_a_gtm_dest))
                w_a_gtm_member_list.sort()
                wideip_poolg2.append("<tr><td {}>{}. {}</td><td {}>{}-{}</td><td {}>{}</td><td {}>{}</td></tr>".format(l_color, w_count, wipn["name"], l_color, w_a_pool_st, w_a_pool_en, l_color, wipnnw_lb, l_color, "".join(w_a_gtm_member_list)))
                wideip_poolm2.append("{}".format(wipn["name"]))

        wideip_pools = "".join(wideip_poolg)
        wideip_pool2s = "".join(wideip_poolg2)
        gtm_poolm2 = []
        #所以pool 的處理需要在這裡了
        for gtm_pool in wideip_poolm:
            gtm_poolp = device.load("/mgmt/tm/gtm/pool/a/{}".format(gtm_pool))
            wipnn_lb = gtm_poolp.properties["loadBalancingMode"]
            gtm_pools_member = device.load("/mgmt/tm/gtm/pool/a/{}/members".format(gtm_pool))
            for gtm_member in gtm_pools_member:
                t = t + 1
                gtm_mb = gtm_member.properties['name']
                gtm_poolm2.append("{}<br>".format(gtm_mb))
                gtm_dc = gtm_mb.split(':')[0]
                gtm_vs = gtm_mb.split(':')[1]

    except:
        w_count = 1
        t = 1
        w_t = 1
        wideip_pools = "None"
        wideip_pool2s = ""


    my_file.write("<tr>\n")
    my_file.write("<td {} rowspan=\"{}\" align=center>{}</td>\n".format(l_color, w_count, count))
    my_file.write("<td {} rowspan=\"{}\">{}</td>\n".format(l_color, w_count, wideip_a_name)) # Wideip_域名
    my_file.write("<td {} rowspan=\"{}\">{}-{}</td>\n".format(l_color, w_count, wideip_a_st, wideip_a_en)) #WIP-STATUS
    my_file.write("<td {} rowspan=\"{}\">{}</td>\n".format(l_color, w_count, wideip_a_descript)) #VS_描述說明
    my_file.write("<td {} rowspan=\"{}\">{}</td>\n".format(l_color, w_count, wideip_a_lb)) #Wideip-LB-MODE
    my_file.write("{}\n".format(wideip_pools))
    my_file.write("<td {} rowspan=\"{}\">{}</td>\n".format(l_color, w_count, ""))
    my_file.write("</tr>{}\n".format(wideip_pool2s))

my_file.close()
###
if os.path.exists(vs_new):
  os.remove(vs_new)
  #print("The file {} does remove".format(vs_new))
else:
  print("The file does not exist") 


while(True):
    print("設定檔產生結束。離開請按Y")
    in_content = input("請輸入：")
    if in_content == "Y" or in_content == "y":
        print("你已退出了該程式！")
        exit(0)
    else:
        print("你輸入的內容有誤，請重輸入！")
