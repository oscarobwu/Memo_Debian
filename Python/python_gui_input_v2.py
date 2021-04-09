#!/usr/bin/env python
from tkinter import *
from tkinter.ttk import Combobox
import tkinter as tk
window=Tk()
var = StringVar()
var.set("192.168.88.166")
data=("192.168.88.166", "192.168.88.167", "192.168.88.168", "192.168.88.169")
cb=Combobox(window, text = "IPaddr", values=data).place(x=80, y=20)
cb = tk.Label(window, text = "F5-MGMT").place(x=5, y=20)
#cb.place(x=80, y=20)
#
txtfld = tk.Label(window, text = "Username").place(x=5, y=50)
txtfld = tk.Entry(window).place(x=80, y=50)
#txtfld.place(x=60, y=50)
txtpw = tk.Label(window, text = "Password").place(x=5, y=80)
txtpw = tk.Entry(window).place(x=80, y=80)
#
textlog = tk.Label(window, text = "Run-Log").place(x=5, y=280)
textlog = tk.Text(window,height=5).place(x=5, y=300)
#
button = tk.Button(window,text="OK").place(x=5, y=390)
#textlog.pack()
#lb=Listbox(window, height=5, selectmode='multiple')
#for num in data:
#    lb.insert(END,num)
#lb.place(x=250, y=150)

v0=IntVar()
v0.set(1)
r1=Radiobutton(window, text="Enable", variable=v0,value=1)
r2=Radiobutton(window, text="disable", variable=v0,value=2)
r3=Radiobutton(window, text="FourceOffline", variable=v0,value=3)
r4=Radiobutton(window, text="Check", variable=v0,value=4)
r1.place(x=20,y=150)
r2.place(x=100, y=150)
r3.place(x=180, y=150)
r4.place(x=240, y=150)
                
v1 = IntVar()
v2 = IntVar()
C1 = Checkbutton(window, text = "Cricket", variable = v1)
C2 = Checkbutton(window, text = "Tennis", variable = v2)
C1.place(x=20, y=200)
C2.place(x=100, y=200)

window.title('F5-開關機')
window.geometry("800x600+300+300")
window.mainloop()
