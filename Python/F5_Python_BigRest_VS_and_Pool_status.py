#Import needed libraries
from bigrest.bigip import BIGIP
import getpass

#Replace the host name as needed
host="xx.xx.xx.xx"
user=input('Username')
pw= getpass.getpass(prompt='Password:')

#Declare the Output filename
out="output.txt"

#Connect to device
device = BIGIP(host, user, pw)

#Open output file for writing
outf=open(out, 'w')


#Get the virtual server info
virtuals = device.load("/mgmt/tm/ltm/virtual")
for virtual in virtuals:

#Get the status of the VS
    vstat=device.load("/mgmt/tm/ltm/virtual/"+virtual.properties["fullPath"].replace("/","~")+"/stats")
    vss=list(vstat.properties['entries'].values())[0]['nestedStats']['entries']['status.availabilityState']['description']
    print("VS name is ", virtual.properties["fullPath"], vss)
    outf.write("VS name is " +  virtual.properties["fullPath"]+'\t'+vss+'\n')

#Get the pool name, if exists
    try:
        pool= virtual.properties["pool"]
    except:
        pool = None
        print ('Unassigned pool')
        outf.write("No pool assigned to this VS \n\n")
    if pool:

#Get the pool members info and their status
        pool= virtual.properties["pool"]
        pooldetail= device.load("/mgmt/tm/ltm/pool/"+pool.replace('/', '~')+"/members")
        pstate = 'Down'
        print ("Pool members are: ")
        outf.write("Pool members are: \n")
        for members in pooldetail:
            print (members.properties['fullPath'], members.properties['state'])
            outf.write(members.properties['fullPath']+'\t' +members.properties['state']+'\n')

#Mark the pool as up, if atleast one member is up
            if pstate=='Down':
                if members.properties['state'] == 'up':
                    pstate = 'up'
        print ("Pool is ", pool, pstate, '\n')
        outf.write("Pool is "+ pool + '\t' +pstate+'\n\n')
    outf.flush()
outf.close()
