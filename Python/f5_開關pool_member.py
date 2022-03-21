#!/usr/bin/env python
from f5.bigip import ManagementRoot
#
# choice Poolmemeber
def cpm():
    print("\nChoice Pool Memeber:\n")
    index = 1 #
    ListPoolm = {}
    for poolm in pool_1.members_s.get_collection():
        print(str(index) + ". " + poolm.name)
        ListPoolm[index] = poolm.name
        index += 1 
    choice = input("Which Poolmember do you want to action? ")
    return ListPoolm[int(choice)]

# Action For Poolmember
def act():
    member = pool_1.members_s.members.load(partition='Common', name=cpm())
    #
    action = input("enabled, disabled, forced_offline, Your input (press enter to skip): ")
    # 
    if action == 'enabled':
        # enables member
        member.state = 'user-up'
        member.session = 'user-enabled'
    elif action == 'disabled':
        # disables member
        member.session = 'user-disabled'
    elif action == 'forced_offline':
        # forces online member
        member.state = 'user-down'
        member.session = 'user-disabled'
    
    if action is not None:
        member.update()
    else:
        print('readonly mode, no changes applied')
    

if __name__ == "__main__":
    LB01 = ManagementRoot("testlb01", "weakuser", "weakpassword")
    pool_1 = LB01.tm.ltm.pools.pool.load (name="test-pool", partition="Common")
    #
    act()
    # via single for loop, I can extract the necessary info about pool members:
    for item in pool_1.members_s.raw["items"]:
        print("Node name: " + item["name"])
        print("Node IP: " + item["address"])
        print("Node full path: " + item["fullPath"])
        #
        if item["session"] == "monitor-enabled":
            print("Node state: enable")
        elif item["session"] == "user-disabled":
            print("Node state: disable")
        #
        print("Node reachability: " + item["state"])
        print("\n")
        
