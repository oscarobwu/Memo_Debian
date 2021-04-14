#!/usr/bin/env python3
# Command : rlookup-node.py <hostname> <username> <nodename>
# filename : f5_輸入IP過濾所有pool_並開關機_v1.py
# node name
# 
__author__ = 'oscarwu'
__version__ = '1.0'

#  Standard Library
import sys
import re
import logging
import getpass
#  Local Application/Library Specific
from f5.bigip import ManagementRoot
from f5.utils.responses.handlers import Stats
import datetime
import getopt

#if len(sys.argv) < 1:
#    print( "\n\n\tUsage: %s host user node" % sys.argv[0])
#    sys.exit()

#  Get login password from CLI
try:
    F5_host = input('F5_Host: ')
    f5user = input('Username: ')
    f5pw = getpass.getpass('Password: ')
    #  Connect to BIG-IP
    mgmt = ManagementRoot(F5_host, f5user, f5pw)
except Exception as e:
    print ("登入失敗 : {} ".format(e))
    #print ("登入失敗 :  ")
    exit()

#  Get list of pools and pool members
pools = mgmt.tm.ltm.pools.get_collection()
FORMAT = '%(asctime)s %(levelname)s %(module)s %(message)s'
logging.basicConfig(format=FORMAT, level='INFO')
logger = logging.getLogger('set_pool_members_state')
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
#
fail = mgmt.tm.sys.failover.load()
failOverStat = fail.apiRawValues['apiAnonymous'].rstrip()
#
fields = failOverStat.strip().split()
Dev_status= fields[1]
print( Dev_status )
#
poolmemblist = []
all_pools = mgmt.tm.ltm.pools.get_collection()
for pool in all_pools:
    for member in pool.members_s.get_collection():
        for membadd in member_list:
            if member.address == membadd:
                print(pool.name + "," + member.name + ',' + member.address)
                poolmemblist.append(pool.name + "," + member.name + ',' + member.address)

pool_list = []
#
#
for poolnam in poolmemblist:
    poolna = poolnam.split(',')
    #print(poolna[0])
    my_pool = mgmt.tm.ltm.pools.pool.load(partition='Common', name=poolna[0])
    pool_stats = Stats(my_pool.stats.load())
    #print(pool_stats.stat.status_availabilityState)
    currm = pool_stats.stat.availableMemberCnt['value']
    if currm <= 1 and action != 'enabled':
        print("pool : {} = pool_member 少於1 : {}".format(poolna[0], currm))
        continue
    else:
        print( "\t"+poolna[0] + "\t" +poolna[1] )
        pooln = mgmt.tm.ltm.pools.pool.load(name=poolna[0], partition='Common')
        pm1 = pooln.members_s.members.load(partition='Common', name=poolna[1])
        if Dev_status in ["active"]:
            for member in [pm1]:
                if action == 'enabled':
                    # enables member
                    logger.info('enables member %s, previous state: %s' % (member.name, member.state))
                    member.state = 'user-up'
                    member.session = 'user-enabled'
                elif action == 'disabled':
                    # disables member
                    logger.info('disables member %s, previous state: %s' % (member.name, member.state))
                    member.session = 'user-disabled'
                elif action == 'forced_offline':
                    # forces online member
                    logger.info('forces online member %s, previous state: %s' % (member.name, member.state))
                    member.state = 'user-down'
                    member.session = 'user-disabled'
                elif action == 'checked':
                    # Checl online member
                    stt = member.session
                    #logger.info('checked online member %s, previous state: %s' %
                    #                        (member.name, member.state))
                    print('\tchecked online member %s, previous state: %s' % (member.name, member.state))
                    if "monitor-enabled" in stt:
                        print('\t')
                    else:
                        logger.info('另外一批有異常請檢查')
                    #print(False)
        
                if action is not None:
                    member.update()
                    print('\t檢查的 member %s, 目前執行後狀態 : %s' %(member.name, member.state))
                    pool_list.append(poolna[0])
                else:
                    logger.info('readonly mode, no changes applied')
        
                    logger.info('%s: %s %s' % (member.name, member.session, member.state))
        else:
            print("this will do Nothing 請修改 Active F5 的 IP ")
            exit()
#
#整理pool 清單
unique = []
for name in pool_list:       # 1st loop
    if name not in unique:   # 2nd loop
        unique.append(name)

#
#print("\n")
now = datetime.datetime.now()
fnames = "開關機"
for x in unique:
    my_pool = mgmt.tm.ltm.pools.pool.load(partition='Common', name=(x))
    my_pool_mbrs = my_pool.members_s.get_collection()
    Count = 0
    #print ( "\n" )
    print ("\033[0;37;44m\tCurrent Run date and time : \033[0m")
    print (now.strftime("\033[0;37;45m\t%Y-%m-%d %H:%M:%S\t\t\033[0m"))
    for pool_mbr in my_pool_mbrs:
        mbr_stats = Stats(pool_mbr.stats.load())
        dic_test = mbr_stats.stat.nodeName
        dic_test1 = dic_test['description']
        dic_test2 = dic_test1.replace('/Common/', '')
        dic_btest = mbr_stats.stat.status_availabilityState
        dic_btest1 = dic_btest['description']
        dic_ctest = mbr_stats.stat.serverside_curConns
        dic_ctest1 = dic_ctest['value']
        dic_dtest = mbr_stats.stat.status_enabledState
        dic_dtest1 = dic_dtest['description']
        Count = ((Count+1))
        #print ( "%s_%02d pool_member: [ %s ] 主機狀態 : %s  目前連線數 : \033[43m[ %s ]\033[0m" % (fnames, Count, dic_test2, dic_btest1,  dic_ctest1) )
        if (dic_btest1 == 'available' or dic_dtest1 != 'enabled' or dic_btest1 == 'offline'):
          if (dic_dtest1 == 'enabled' and dic_btest1 == 'available'):
              print("%s  pool_member: [ %s ] 主機狀態 : \033[0;37;42m[ %s ]\033[0m 目前連線數 : \033[43m[ %s ]\033[0m" %(x, dic_test2, dic_btest1, dic_ctest1))
          elif (dic_dtest1 != 'disabled' or dic_btest1 != 'offline'):
              print("%s  pool_member: [ %s ] 主機狀態 : \033[0;37;41m[ %s ]\033[0m 目前連線數 : \033[43m[ %s ]\033[0m" %(x, dic_test2, dic_dtest1, dic_ctest1))
          elif (dic_btest1 == 'offline'):
              print("%s  pool_member: [ %s ] 主機狀態 : \033[0;37;41m[ %s ]\033[0m 目前連線數 : \033[43m[ %s ]\033[0m" %(x, dic_test2, dic_btest1, dic_ctest1))
    
    print ( "\n" )
    # vim:set nu et ts=4 sw=4 cino=>4:

nodes = mgmt.tm.ltm.nodes.get_collection()
# Iterate
for node in nodes:
    #print(node)  # Notice what gets returned, not what you expect
    #print(type(node))  # Node is a 'class' type
    #print(node.raw)  # Use .raw to learn what objects you can call
    # Use this as an example of the power of using these classes
    #print("{} has an IP of {}".format(node.name, node.address))
    for nodeadd in member_list:
        if node.address == nodeadd:
            nodestat = mgmt.tm.ltm.nodes.node.load(name=node.name, partition='Common')
            noden_stat = Stats(nodestat.stats.load())
            noden_curr = noden_stat.stat.serverside_curConns['value']
            print("{} an IP of {} 目前_node_連線數 : \033[43m[ {} ]\033[0m ".format(node.name, node.address, noden_curr))

print ( "\n完成工作!!!\n" )
