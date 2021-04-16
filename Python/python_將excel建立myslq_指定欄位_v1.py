#!/usr/bin/python3
# -*- coding: utf-8 -*-
import pymysql

# 1.0链接数据库（注意把账户和密码改成自己的）
conn = pymysql.connect(
      host='192.168.88.13',
      port=3306,
      user='testuser',
      passwd='test123',
      db='TESTDB',
      charset='utf8')
def connect_mysql(conn):
    #判断链接是否正常
    conn.ping(True)
    #建立操作游标
    cursor=conn.cursor()
    #设置数据输入输出编码格式
    cursor.execute('set names utf8')
    return cursor
# 建立链接游标
cur=connect_mysql(conn)
print ('正在新建数据表~')

# 2.0添加数据表
# 开始时间,来访ID,二级分类,三级分类,四级分类,五级分类,备注,服务方式,TIME_LOAD
# cursor.execute("DROP TABLE IF EXISTS DATA")
# cur.execute("DROP TABLE IF EXISTS HOSTABLE2") 如果要清空資料表的話
cur.execute('''CREATE TABLE IF NOT EXISTS HOSTABLE2 (
        ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        Hostname CHAR(30),
        Function CHAR(30),
        VLAN CHAR(30),
        Private_IP CHAR(60)
        )''')

# 3.0提交并关闭连接
conn.commit()
conn.close()
print ('Done!')
