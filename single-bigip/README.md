# single-bigip
<em>Using ansible and an xlsx spreadsheet to set up a single device</em><br><br>
Tested on BIG-IP Software version 12.1.2<br><br>
The default admin password of admin has been used<br><br>
This project uses the xls_to_facts.py module by Matt Mullen<br>
https://github.com/mamullen13316/ansible_xls_to_facts

Requirements:<br>

<b>BIG-IP Requirements</b><br>
The BIG-IP device will need to have its management IP, netmask, and management gateway configured<br>

It will also need to be licensed and provisionned with ltm (default). It is possible to both provision and license devices with ansible but it is not within the remit of this project.

For additional information on Ansible and F5 Ansible modules, please see:
http://clouddocs.f5.com/products/orchestration/ansible/devel/index.html


<b>Ansible Control Machine Requirements</b>
<br>I am using Centos, other OS are available<br><br>
<b>Note: It will be easiest to carry out the below as the root user</b><br><br>
You will need Python 2.7+<br>
$ yum install python<br>
<br>
You will need pip<br>
$ curl 'https://bootstrap.pypa.io/get-pip.py' > get-pip.py && sudo python get-pip.py
<br><br>
You will need ansible 2.5+ <br> 
$ pip install ansible <br>

If 2.5+ is not yet available, which it wasn't at the time of writing,  please download directly from git <br>
$ yum install git <br>
$ pip install --upgrade git+https://github.com/ansible/ansible.git<br>
<br>
You will need to add a few other modules
<br>
$ pip install f5-sdk bigsuds netaddr deepdiff request objectpath openpyxl
<br>


You will need to create and copy a <em>root</em> ssh-key to the bigip device<br>
$ ssh-keygen <br>
Accept the defaults<br>
$ ssh-copy-id -i /root/.ssh/id_rsa.pub root@\<bigip-<em><b>management-ip</b></em>\><br>
Example:<br>
$ ssh-copy-id -i /root/.ssh/id_rsa.pub root@192.168.1.203<br>

You will need to download the files using git - see above for git installation<br>
$ git clone https://github.com/bwearp/single-bigip/ <br>
$ cd single-bigip <br>

<b>Executing the playbook</b><br>

You will then need to edit the single-bigip.xlsx file to your preferences

Then execute the playbook

$ ansible-playbook single-bigip.yml

NOTES:

I have added only Standard Virtual Servers with http, client & server ssl profiles, but hopefully it is pretty obvious from the single-bigip.yml playbook how to add in others.

Trunks haven't been added. This is because you can't have trunks in VE and also there is no F5 ansible module to add trunks. It could be done relatively easily using the bigip_command module, and hopefully the bigip_command examples in the single-bigip.yml file will show that.

I haven't added in persistence settings, as this would require a dropdown list of some kind. Is simple enough to do.

Automation does not sit well with complication

To update if there are any changes, please cd to the same folder and run:<br>
$ git pull

You will notice there is also a reset.yml playbook to reset the device to factory defaults.<br>
To run the reset.yml playbook: <br>
$ ansible-playbook reset.yml

To set up an HA pair, please see https://github.com/bwearp/simple-ha-pair


