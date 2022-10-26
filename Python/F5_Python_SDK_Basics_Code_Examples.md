# F5_Python_SDK_Basics_Code_Examples.md


```
# Import the F5 module (Use pip to install> pip install f5-sdk)
from f5.bigip import ManagementRoot

# Define unique variables
user = 'f5_user'
password = 'my_f5_pass'
f5_ip = '192.168.10.5'
partition = 'Common'

# Connect to the F5
mgmt = ManagementRoot(f5_ip, user, password)
ltm = mgmt.tm.ltm

###################
## Gain information
###################

# What nodes are on this F5?
nodes = ltm.nodes.get_collection()

# Iterate
for node in nodes:
    print(node)  # Notice what gets returned, not what you expect
    print(type(node))  # Node is a 'class' type
    print(node.raw)  # Use .raw to learn what objects you can call

    # Use this as an example of the power of using these classes
    print("{} has an IP of {}".format(node.name, node.address))

# What pools are on this F5?
pools = ltm.pools.get_collection()

# We know this will be a class, so we know how to iterate through it now
for pool in pools:
    # print(pool.raw)  # If you want to learn about the objects
    print(pool.name)

# What virtual servers are on this F5?
virtuals = ltm.virtuals.get_collection()

# Getting much easier now
for virtual in virtuals:
    print("VS {} enabled? {}".format(virtual.name, virtual.enabled))


###################
## Checks
###################

# Does a node exist on the F5?
my_node = 'TESTNODE5'
test = ltm.nodes.node.exists(partition=partition, name=my_node)
print("Is {} on the F5? {}".format(my_node, test))

# Is my node in a pool?
for pool in ltm.pools.get_collection():
    # Take note of how this call works
    for member in pool.members_s.get_collection():  
        if my_node in member.name:
            print("{} is in the pool {}".format(my_node, pool.name))


###################
## Creating objects
###################

# Create a node
ltm.nodes.node.create(
    partition=partition, name=my_node, address='192.168.10.20')
# If a node of the same name exists, your progam will crash.
# Add logic you learned above to prevent this. 

# Create a pool
my_pool = 'TEST-POOL5'
ltm.pools.pool.create(name=my_pool)

# Create a member, i.e. add a node to a pool with a port
member_port = '80'
pool = ltm.pools.pool.load(name=my_pool)
pool.members_s.members.create(
    partition='Common', name=my_node + ":" + member_port)

# Create a L4 performance virtual server (most basic way)
vs_name = 'Test-Virtual-Server5'
# ltm.virtuals.virtual.create(name=vs_name, destination='192.168.100.10:80')

# We typically need a more complex virtual-server than that
# A good way is to define parameters, 
# and run them to the create function as needed
profiles = [
    {'name': 'f5-tcp-wan', "context": "clientside"},
    {'name': 'f5-tcp-lan', "context": "serverside"},
    {'name': 'http-profile-default'}
]

params = {'name': vs_name,
          'destination': '{}:{}'.format('192.168.100.10', str(80)),
          'mask': '255.255.255.255',
          'description': 'Created by Python',
          'pool': my_pool,
          'profiles': profiles,
          'partition': 'Common',
          'sourceAddressTranslation': {'type': 'automap'},
          'vlansEnabled': True,
          'vlans': ['/Common/internal']
          }

ltm.virtuals.virtual.create(**params)


###################
## Deleting objects
###################
# A few different ways to accomplish the same thing
# NOTE: Virtual servers need to be deleted before pools.
# and pools need to bedeleted before the node can.
# Use what you learned above to check membership 
# to prevent your program from crashing

# Delete a virtual-server
if ltm.virtuals.virtual.exists(partition=partition, name=vs_name):
    virtual = ltm.virtuals.virtual.load(
        partition=partition, name=vs_name)
    virtual.delete()
else:
    print("Virtual server does not exist")

# Delete a pool (we loaded it above so we can delete it this simply)
pool.delete()

# Delete a node
ltm.nodes.node.load(partition=partition, name=my_node).delete()

```

### 選單

```
from f5.bigip import ManagementRoot
import logging, ipaddress
 
# enable logging with timestamp
logging.basicConfig(filename="bigip_script.log",
                    format="%(asctime)s %(levelname)s:%(message)s",
                    level=logging.INFO)
 
try:
    # Need to re-write this to store as an encrypted credential file
    mgmt = ManagementRoot("192.168.1.11","admin","121278")
except Exception as e:
    logging.error("Error in connecting to bigip.",e)
    print(e)
    exit(1)
 
ltm = mgmt.tm.ltm # LTM
vs = ltm.virtuals.virtual # Virtual Server
 
 
def choosePool():
    index = 1 # index starts from 1.
    dictPool = {} # dictionary to store the pool.name
    for pool in ltm.pools.get_collection():
        print(str(index) + ". " + pool.name)
        dictPool[index] = pool.name
        index += 1 # this index maps to the pool.name stored.
    choice = input("Which pool do you want to update? ")
    return dictPool[int(choice)] # user enters a digit, need to type cast choice to integer.
 
 
def vsMenu():
    # Simple menu to take in user input for creating vs.
    name = input("Enter virtual server name ")
    destination = input("Enter ip address of virtual server ")
    try:
        ipaddress.ip_address(destination) # check for valid ipv4 address.
    except Exception as e:
        logging.error(e)
        print("You have entered an invalid ipv4 address ", e)
        exit(1)
    port = input("Enter the service port number of {} ".format(name))
    if not port:# if user did not enter a value
        port = '0' # default port is any
    ipProtocol = input("Enter protocol of {} ".format(name))
    if not ipProtocol: # if user did not enter a value
        ipProtocol = 'tcp' # default is tcp
    source = input("Enter the source address (your expected visitor, if none just press enter) ")
    if not source: # if user did not enter a value
        source = '0.0.0.0/0' # default is any address
    pool = choosePool()
    print("Which persistence do you prefer?\n")
    print("[1] source address\n")
    print("[2] destination address\n")
    persistChoice = input("Your choice (press enter to skip): ")
    if persistChoice == '1':
        persist = 'source_addr' # session persistence based on source address
    elif persistChoice == '2':
        persist = 'dest_addr' # session persistence based on destination address
    else:
        persist = "" # default value is none.
    destination = destination + ":" + port # persist parameter accepts ip_address:port eg. 192.168.1.1:80 only.
    #print(name,destination,source,ipProtocol,pool,persist)
    createVS(name,destination,source,ipProtocol,pool,persist)
 
 
def createVS(name,destination,source,ipProtocol,pool,persist):
    if vs.exists(partition="Common", name=name):
        print("{} exists in bigip!".format(name))
    try:
        logging.info("Creating the Virtual Server {}".format(name))
        # Calling the iControl API using http-post
        vs.create(partition="Common",
                name=name,
                destination=destination,
                source=source,
                ipProtocol=ipProtocol,
                pool=pool,
                persist = persist)
    except Exception as e:
        logging.error(e)
        exit(1)
 
 
def addMember():
    poolobj = ltm.pools.pool.load(partition="Common", name=choosePool())
    createMembers(poolobj)
 
 
def createMembers(poolObj):
    members = []
 
    while True:
        nodeMember = input("Enter member ip address: ")
        if not nodeMember: # Quit asking if user does not put in value
            break
        try:
            ipaddress.ip_address(nodeMember) # validate if user has entered a valid ip address
            logging.debug("Collecting {}...".format(nodeMember))
        except Exception as e:
            logging.error("{} is an invalid ipv4 address: ".format(nodeMember),e)
            print("You have entered an invalid ip address: ",e)
            exit(1)
        nodeMemberPort = input("Service port of this member") # Collect server port
        if not nodeMemberPort: # Quit asking when no port is provided.
            break
        # make sure the port number is between 0 and 65535
        elif int(nodeMemberPort) >= 0 and int(nodeMemberPort) <= 65535:
            # collect the ip_address:port eg. 192.168.1.1:80 to members array.
            members.append(nodeMember + ":" + nodeMemberPort)
            logging.info("Collecting {}:{}".format(nodeMember,nodeMemberPort))
        else:
            logging.info("Unknown service port, assume quitting sub-menu...")
            break
    for member in members:
        poolObj.members_s.members.create(partition="Common", name=member)
 
 
def createPool():
    poolName = input("Enter pool name eg. pool-Gwen: ")
    # check if pool exists in bigip
    while ltm.pools.pool.exists(partition="Common",name=poolName):
        print("This pool {} is already exist! Choose another name".format(poolName))
        poolName = input("Enter pool name eg. pool-Gwen: ")
    if poolName: # Ask further for inputs if user has enter Pool Name
        print("Select load balancing method:\n")
        print("[1] Round Robin (default)\n")
        print("[2] Least Connections (Member)\n")
        print("[3] Least Connections (Node)\n")
        print("[4] Least sessions\n")
        choice = input("Enter a load balancing method: ")
 
        if not choice or choice is '1':
            lbMode = "round-robin" # default load balancing method, if user did not enter any value.
        elif choice is '2':
            lbMode = "least-connections-member"
        elif choice is '3':
            lbMode = "least-connections-node"
        else:
            lbMode = "least-sessions"
        try:
            poolObj = ltm.pools.pool.create(partition="Common", name=poolName, loadBalancingMode=lbMode)
            logging.info("Creating pool {} with load balancing method as {}".format(poolName,lbMode))
        except Exception as e:
            logging.error("Error in creating pool:",e)
            print(e)
            exit(1)
        choice = input("Proceed to create members? ")
        if choice.lower() == 'y':
            logging.info("User has selected to create pool members.")
            createMembers(poolObj)
        elif choice.lower() == 'n':
            logging.info("User does not want to create pool members.")
        else:
            print("You have entered an invalid choice, only y or n.\n")
 
 
# Ugly menu in CLI
def cliMenu():
    choice = 0
    while choice is not '9':
        print("Menu\n")
        print("====\n")
        print("[1]Create Pool\n")
        print("[2]Add members to existing Pool\n")
        print("[3]Create Virtual Server\n")
        print("[9]Quit\n")
        choice = input("Enter a choice: ")
        if choice == '1':
            createPool()
        elif choice == '2':
            addMember()
        elif choice == '3':
            vsMenu()
        elif choice == '9':
            print("Bye.")
        else:
            print("You have entered an invalid choice.")
            logging.error("User has entered an invalid choice in main menu.")
 
 
if __name__ == "__main__":
    cliMenu()
```
