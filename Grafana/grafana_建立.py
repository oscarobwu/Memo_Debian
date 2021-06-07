#!/usr/bin/env python3
# -*- coding: utf-8 -*-
import sys
from PyQt5.QtWidgets import QMainWindow, QApplication, QPushButton, QAction, QLineEdit, QMessageBox, QLabel, QPlainTextEdit, QApplication, QTextEdit
from PyQt5.QtGui import QIcon, QTextCursor
from PyQt5.QtCore import pyqtSlot, QObject, pyqtSignal, QThread
import time
#
#
class Worker(QThread):
    
    file_changed_signal = pyqtSignal(str) # 信号类型：str
    
    def __init__(self, sec=0, parent=None):
        super().__init__(parent)
        self.working = True
        self.sec = sec
        
    def __del__(self):
        self.working = False
        self.wait()
        
    def run(self):
        while self.working == True:
            self.file_changed_signal.emit('当前秒数：{}'.format(self.sec))
            self.sleep(1)
            self.sec += 1

class Stream(QObject):
    """Redirects console output to text widget."""
    newText = pyqtSignal(str)

    def write(self, text):
        self.newText.emit(str(text))

class App(QMainWindow):

    def __init__(self):
        super().__init__()
        self.title = '建立 Grafana 監控圖表 - 工具'
        self.left = 100
        self.top = 300
        self.width = 800
        self.height = 800
        self.initUI()

        # Custom output stream.
        sys.stdout = Stream(newText=self.onUpdateText)

    def onUpdateText(self, text):
        """Write console output to text widget."""
        cursor = self.process.textCursor()
        cursor.movePosition(QTextCursor.End)
        cursor.insertText(text)
        self.process.setTextCursor(cursor)
        self.process.ensureCursorVisible()

    def closeEvent(self, event):
        """Shuts down application on close."""
        # Return stdout to defaults.
        sys.stdout = sys.__stdout__
        super().closeEvent(event)
    
    def initUI(self):
        self.setWindowTitle(self.title)
        self.setGeometry(self.left, self.top, self.width, self.height)

        # 功能列
        mainMenu = self.menuBar()
        fileMenu = mainMenu.addMenu('File')
        editMenu = mainMenu.addMenu('Edit')
        viewMenu = mainMenu.addMenu('View')
        searchMenu = mainMenu.addMenu('Search')
        toolsMenu = mainMenu.addMenu('Tools')
        helpMenu = mainMenu.addMenu('Help')
        
        exitButton = QAction(QIcon('exit24.png'), 'Exit', self)
        exitButton.setShortcut('Ctrl+Q')
        exitButton.setStatusTip('Exit application')
        exitButton.triggered.connect(self.close)
        fileMenu.addAction(exitButton)
    
        # Create textbox Grafana
        label1 = QLabel('Grafana_IP ', self)
        label1.move(20,46)
        self.textbox1 = QLineEdit(self)
        self.textbox1.insert("192.168.88.13")
        self.textbox1.move(80, 50)
        self.textbox1.resize(180,20)
    
        # Create textbox Grafana Daah
        label1b = QLabel('Grafana daabarad ', self)
        label1b.move(300,46)
        self.textbox1b = QLineEdit(self)
        self.textbox1b.insert("DC_06_EXT_LTM")
        self.textbox1b.move(400, 50)
        self.textbox1b.resize(280,20)

        # Create textbox2
        label2 = QLabel('F5_MGMT ', self)
        label2.move(20,76)
        self.textbox2 = QLineEdit(self)
        self.textbox2.insert("192.168.88.166")
        self.textbox2.move(80, 80)
        self.textbox2.resize(180,20)

        # Create textbox3
        label3 = QLabel('UserName ', self)
        label3.move(20,106)
        self.textbox3 = QLineEdit(self)
        self.textbox3.insert("admin")
        self.textbox3.move(80, 110)
        self.textbox3.resize(180,20)
        
        # Password
        label4 = QLabel('密碼 ', self)
        label4.move(20,136)
        self.editor = QLineEdit(self)
        self.editor.move(80,140)
        self.editor.resize(180,20)
        self.editor.setEchoMode(QLineEdit.Password)

        # Add text field
        labellist = QLabel('需要新增VS名稱清單 ', self)
        labellist.move(80,170)
        labellist.resize(180,20)
        self.b = QPlainTextEdit(self)
        self.b.insertPlainText("")
        self.b.move(80,200)
        self.b.resize(400,200)

        # Create the text output widget.
        self.process = QTextEdit(self, readOnly=True)
        self.process.ensureCursorVisible()
        self.process.setLineWrapColumnOrWidth(500)
        self.process.setLineWrapMode(QTextEdit.FixedPixelWidth)
        self.process.setFixedWidth(400)
        self.process.setFixedHeight(150)
        self.process.move(80, 450)
        self.process.setStyleSheet(
            """QPlainTextEdit {background-color: #333;
                               color: #00FF00;
                               text-decoration: underline;
                               font-family: Courier;}""")

        # Create a button in the window
        self.button = QPushButton('執行 作業', self)
        self.button.move(600,500)
        
        # connect button to function on_click
        self.button.clicked.connect(self.on_click)
        self.show()

    @pyqtSlot()
    def on_click(self):
        grafana_host = self.textbox1.text()
        grafana_uid = self.textbox1b.text()
        F5_host = self.textbox2.text()
        grafana_user = self.textbox3.text()
        grafana_pw = self.editor.text()
        textboxValue5 = self.b.toPlainText()
        print("Start...")
        QMessageBox.question(self, 'Message - pythonspot.com', "You typed: " + grafana_host + ' = ' + grafana_uid, QMessageBox.Ok, QMessageBox.Ok)
        QMessageBox.question(self, 'Message - pythonspot.com', "You typed: " + F5_host , QMessageBox.Ok, QMessageBox.Ok)
        QMessageBox.question(self, 'Message - pythonspot.com', "You typed: " + grafana_user , QMessageBox.Ok, QMessageBox.Ok)
        #
        try:
            mgmt = ManagementRoot(F5_host, grafana_user, grafana_pw)
        except Exception as e:
            logging.error("Error in connecting to bigip.",e)
            print ("登入失敗 : {} ".format(e))
            #print ("登入失敗 :  ")
            exit(1)

        for grafana_title in textboxValue5.split('\n'):
            print(grafana_title)
            #print(grafana_title)
            #time.sleep(1)
            #self.thread = Worker()
            my_virtual = mgmt.tm.ltm.virtuals.virtual.load(name = grafana_title)
#
            if hasattr(my_virtual, 'pool'):
                pool_name_from_vs = my_virtual.pool
                #print("Create : {} ".format(pool_name_from_vs))
            else:
                pool_name_from_vs = 'none'
                #print("none")
            #
            headers={"Content-Type": "application/json"}
            uid_url='https://' + grafana_user + ':' + grafana_pw + '@' + grafana_host + '/grafana/api/search?query=%'
            uid_r = requests.get(url = uid_url, headers = headers, verify=False)
            for uid_item in uid_r.json():
                if uid_item['title'] == grafana_uid:
                    #print(uid_item)
                    uid = uid_item['uid']
                    print(uid)
            
            # 要確認 url 是否正確
            url='https://' + grafana_user + ':' + grafana_pw + '@' + grafana_host + "/grafana/api/dashboards/uid/" + uid
            #r = requests.get(url=url, headers=headers, verify=False)
            r = requests.get(url = url, headers = headers, verify=False)
            dash_data = r.json()
            #print("比對vs_{}".format(dash_data[1]))
            my_list = dash_data['dashboard']['panels']
            #print(my_list.title)
            #print("Grafana_建立")
            #for k in my_list:
            vs_list = []
            for k in my_list:
                vs_list.append(k['title'])

            #if vs_list == grafana_title:
            if  grafana_title in vs_list:
                #print(k['title'])
                print("監控以建立")
            else:
                #print(k['title'])
                print("尚未建立")
                #dash_data = r.json()
                go_to_folder = dash_data['meta']['folderId']
                my_list = dash_data['dashboard']['panels'][-1]
                plant_my_list_x = my_list['gridPos']['x']
                plant_my_list_y = my_list['gridPos']['y']
                #
                if plant_my_list_x == '0':
                   plant_my_list_x = 12
                   plant_my_list_y = plant_my_list_y
                else:
                   plant_my_list_x = 0
                   plant_my_list_y = plant_my_list_y + 9
                
                # 新增 圖表
                #dashboard_data = copy.deepcopy(dash_data)
                dashboard_plant_data = copy.deepcopy(my_list)
                if dashboard_plant_data['gridPos']['x'] == 0:
                   dashboard_plant_data['gridPos']['x'] = 12
                   dashboard_plant_data['gridPos']['y'] = dashboard_plant_data['gridPos']['y']
                else:
                   dashboard_plant_data['gridPos']['x'] = 0
                   dashboard_plant_data['gridPos']['y'] = dashboard_plant_data['gridPos']['y'] + 9
                
                #
                dashboard_plant_data['title'] = grafana_title
                dashboard_plant_data['id'] = dashboard_plant_data['id'] + 1
                dashboard_plant_data['targets'][0]['tags'][1]['value'] = pool_name_from_vs
                dashboard_plant_data['targets'][1]['tags'][1]['value'] = '/Common/' + grafana_title
                #
                dashboard_plant_data['alert']['message'] = '初次設定_' + grafana_title
                dashboard_plant_data['alert']['name'] = '連線告警_' + grafana_title
                dashboard_plant_data['alert']['conditions'][0]['evaluator']['params'][0] = 250
                dashboard_plant_data['alert']['conditions'][1]['evaluator']['params'][0] = 550
                #
                temp2 = dash_data
                temp2['dashboard']['panels'].append(dashboard_plant_data)
                #
                dashboard_data = copy.deepcopy(temp2)
                dashboard_data["dashboard"]['id'] = temp2['dashboard']['id']
                dashboard_data["dashboard"]['uid'] = uid
                dashboard_data["dashboard"]['version'] = temp2['dashboard']['version'] + 1
                #
                dashboard_data["folderId"] = go_to_folder
                dashboard_data["overwrite"] = True
                #
                dr_url='https://' + grafana_user + ':' + grafana_pw + '@' + grafana_host + "/grafana//api/dashboards/db"
                #
                dr = requests.post(url=dr_url, headers=headers, data=json.dumps(dashboard_data), verify=False)
                #print(dr.json())
                print("VS_{}_建立完成".format(grafana_title))
#
        print("列印完畢")
        time.sleep(1)
        self.textbox1.setText("")
        self.textbox2.setText("")
        self.textbox3.setText("")
        self.editor.setText("")
        self.b.clear()
        print('Done.')

if __name__ == '__main__':
    import sys
    import requests
    import json
    import getpass
    import logging
    from requests.api import delete
    #
    from f5.bigip import ManagementRoot
    # 關閉SSL錯誤
    import urllib3
    urllib3.disable_warnings(urllib3.exceptions.InsecureRequestWarning)
    import copy
    app = QApplication(sys.argv)
    ex = App()
    sys.exit(app.exec_())
