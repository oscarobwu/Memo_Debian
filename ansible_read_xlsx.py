#!/usr/bin/python
#-*- coding: utf-8 -*-
#===============================================================================
#
#         Filename:       ansible_read_xlsx.py
#
#        USAGE: ansible_read_xlsx.py
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
#      Created Time: 2021-02-09 16:59:45
#      Last modified: 2021-02-09 16:59
#     REVISION: ---
#===============================================================================
# -*- coding: utf-8 -*-
import argparse
import configparser
import json
import xlrd
import os
import yaml
def open_excel(file):
    try:
        data = xlrd.open_workbook(file)
        return data
    except Exception as e:
        print(str(e))
def inventory_group(ws, inventory={}, host_column=0, group_column=-1):
    # 功能——按指定列分组主机
    """
    实现——先初始化该列的hash，初始化时如该组名在group_vars_file_list里有，同时初始化vars
    再逐行将主机列的值append到list里
    :param ws: excel的worksheet，作为输入数据源
    :param inventory: 最终要输出的inventory字典，将数据添加到该字典
    :param host_column: inventory的Host或IP列号
    :param group_column: 如果须要按照某列分组，提供分组使用的列号
    :return: 添加excel数据后的Inventory
    """
    if group_column == -1:
        inventory['all'] = {'hosts': [], 'vars': {}}
        for i in range(ws.nrows - 1):
            inventory['all']['hosts'].append(ws.cell_value(i + 1, host_column))
    else:
        group_vars_file_list = next(os.walk(config['Excel Inventory']['group_vars_dir']))[2]
        for i in ws.col_values(group_column, 1):
            if i in group_vars_file_list:
                inventory[i] = {'hosts': [], 'vars': yaml.load(open(config['Excel Inventory']['group_vars_dir']+'/'+i))}
            else:
                inventory[i] = {'hosts': [], 'vars': {}}
        for i in range(ws.nrows - 1):
            inventory[ws.cell_value(i + 1, group_column)]['hosts'].append(ws.cell_value(i + 1, host_column))
    return inventory
def ExcelInventory():
    wb = xlrd.open_workbook(filename=config['Excel Inventory']['file_name'])
    ws = wb.sheet_by_name(config['Excel Inventory']['sheet_name'])
    column_hash = {}
    for i in range(ws.ncols):
        column_hash[ws.cell_value(0, i)] = i
    host_column_name = column_hash[config['Excel Inventory']['host_column_name']]
    # 初始化一个包括所有主机的all group
    # inventory_hash = {ws.cell_value(0, host_column_name): {'hosts': host_list, 'vars': {}}}
    inventory_hash = inventory_group(ws, host_column=host_column_name)
    # 按照Group Column例举的列分组
    for item in config['Group Column']:
        inventory_hash = inventory_group(ws, host_column=host_column_name, group_column=column_hash[item])
    # 功能——将IP对应的行的所有列作为_mata的hostvars值
    # 实现——外层循环将每一行的IP作为key内层循环的hash作为value，内层循环将该列的第一行作为key当前行作为value
    host_list = ws.col_values(host_column_name, start_rowx=1)
    hostvars = {}
    for i in range(ws.nrows - 1):
        varscollumn = {}
        for j in range(ws.ncols):
            varscollumn[ws.cell_value(0, j).replace(' ', '_')] = ws.cell_value(i + 1, j)
        hostvars[host_list[i]] = varscollumn
    inventory_hash['_meta'] = {'hostvars': hostvars}
    return inventory_hash
if __name__ == "__main__":
    parser = argparse.ArgumentParser()
    parser.add_argument('-l', '--list', help='hosts list', action='store_true')
    parser.add_argument('-H', '--host', help='hosts vars')
    args = vars(parser.parse_args())
    config = configparser.ConfigParser()
    config.optionxform = str
    config.read('./xl-inventory.ini')
    if args['list']:
        print(json.dumps(ExcelInventory(), indent=4))
    elif args['host']:
        print(json.dumps({'_meta': {'hostvars': {}}}))
    else:
        parser.print_help()
