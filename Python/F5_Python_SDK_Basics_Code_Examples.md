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
