#!/usr/bin/env python3
#-*- coding: utf-8 -*-
# 
# 功能 : 使用API設定 LTM 啟動license 和 VPN tunnel
# 使用API設定_LTM_啟動license_和_VPN_tunnel.py
# File
#===============================================================================
import argparse,json,requests,time
from requests.packages.urllib3.exceptions import InsecureRequestWarning

requests.packages.urllib3.disable_warnings(InsecureRequestWarning)

def icontrol_get(host,username,password,path):
  apiCall = requests.session()
  apiCall.headers.update({'Content-type':'application/json'})
  apiCall.auth = (username,password)
  apiUri = 'https://' + host + path
   try:
  apiResponse = apiCall.get(apiUri,verify=False)
   except requests.exceptions.RequestException as e:
   print('{"responseStatus":"error","action":"get","host":"' + host + '","username":"' + username + '","path":"' + path + '","errorMsg":"' + str(e) + '"}')
   return(apiResponse.text)

def icontrol_post(host,username,password,path,api_payload):
  apiCall = requests.session()
  apiCall.headers.update({'Content-type':'application/json'})
  apiCall.auth = (username,password)
  apiUri = 'https://' + host + path
   try:
  apiResponse = apiCall.post(apiUri,verify=False,data=json.dumps(api_payload))
   except requests.exceptions.RequestException as e:
   print('{"responseStatus":"error","action":"post","host":"' + host + '","username":"' + username + '","path":"' + path + '"},"payload":"' + api_payload + '","errorMsg":"' + str(e) + '"}')
   return(apiResponse.text)

def icontrol_put(host,username,password,path,api_payload):
  apiCall = requests.session()
  apiCall.headers.update({'Content-type':'application/json'})
  apiCall.auth = (username,password)
  apiUri = 'https://' + host + path
   try:
  apiResponse = apiCall.put(apiUri,verify=False,data=json.dumps(api_payload))
   except requests.exceptions.RequestException as e:
   print('{"responseStatus":"error","action":"put","host":"' + host + '","username":"' + username + '","path":"' + path + '"},"payload":"' + api_payload + '","errorMsg":"' + str(e) + '"}')
   return(apiResponse.text)

def icontrol_patch(host,username,password,path,api_payload):
  apiCall = requests.session()
  apiCall.headers.update({'Content-type':'application/json'})
  apiCall.auth = (username,password)
  apiUri = 'https://' + host + path
   try:
  apiResponse = apiCall.patch(apiUri,verify=False,data=json.dumps(api_payload))
   except requests.exceptions.RequestException as e:
   print('{"responseStatus":"error","action":"patch","host":"' + host + '","username":"' + username + '","path":"' + path + '"},"payload":"' + api_payload + '","errorMsg":"' + str(e) + '"}')
   return(apiResponse.text)

def icontrol_delete(host,username,password,path):
  apiCall = requests.session()
  apiCall.headers.update({'Content-type':'application/json'})
  apiCall.auth = (username,password)
  apiUri = 'https://' + host + path
   try:
  apiResponse = apiCall.delete(apiUri,verify=False)
   except requests.exceptions.RequestException as e:
   print('{"responseStatus":"error","action":"delete","host":"' + host + '","username":"' + username + '","path":"' + path + '"},"errorMsg":"' + str(e) + '"}')
   return(apiResponse.text)

## Parse the command line arguments#cmdargs = argparse.ArgumentParser()
cmdargs.add_argument('--f5restip',action='store',required=True,type=str,help='ip of BIG-IP REST interface, typically the mgmt ip')
cmdargs.add_argument('--username',action='store',required=True,type=str,help='username for REST authentication')
cmdargs.add_argument('--password',action='store',required=True,type=str,help='password for REST authentication')
cmdargs.add_argument('--licensekey',action='store',required=False,type=str,help='license key for licensing')
cmdargs.add_argument('--hostname',action='store',required=False,type=str,help='hostname to configure on the BIG-IP (must be FQDN)')
cmdargs.add_argument('--ntpserver',action='store',required=False,type=str,help='NTP server to add to the BIG-IP (IP or FQDN)')
cmdargs.add_argument('--dnsserver',action='store',required=False,type=str,help='DNS server to add to the BIG-IP (IP address)')
cmdargs.add_argument('--newlocalselfip',action='store',required=True,type=str,help='creates the private self-ip address of the VE where the public address is mapped (CIDR notation)')
cmdargs.add_argument('--newlocalselfipgateway',action='store',required=True,type=str,help='creates the private self-ip subnet gateway for server communications')
cmdargs.add_argument('--localserversubnet',action='store',required=True,type=str,help='the local VM subnet in CIDR notation')
cmdargs.add_argument('--localselfip',action='store',required=True,type=str,help='the private self-ip address of the VE where the public address is mapped')
cmdargs.add_argument('--localpublicip',action='store',required=True,type=str,help='the public IP address of the VNA')
cmdargs.add_argument('--remotepublicip',action='store',required=True,type=str,help='the public IP address of the remote IPSec peer')
cmdargs.add_argument('--presharedkey',action='store',required=True,type=str,help='the pre-shared key for encryption')
cmdargs.add_argument('--tunnelsourcenet',action='store',required=True,type=str,help='CIDR notated source network, can be 0.0.0.0/0 to tunnel all traffic')
cmdargs.add_argument('--tunneldestinationnet',action='store',required=True,type=str,help='CIDR notated destination network, the remote destination subnet to tunnel, can be 0.0.0.0/0 to let static routes control tunneling')
cmdargs.add_argument('--tunnelstaticroutenet',action='store',required=True,type=str,help='CIDR notated destination network to statically route across the tunnel (this makes the decision to tunnel or not)')
cmdargs.add_argument('--tunnelselfiplocal',action='store',required=True,type=str,help='CIDR notated tunnel self-IP address, a new address assigned to the tunnel (ie 192.168.1.1/30')
cmdargs.add_argument('--tunnelselfipremote',action='store',required=True,type=str,help='CIDR notated tunnel self-IP address on the remote end, a new address assigned to the tunnel (ie 192.168.1.1/30')
cmdargs.add_argument('--bgpas',action='store',required=True,type=str,help='AS number for BGP process and neighbor')
parsed_args = cmdargs.parse_args()

host = parsed_args.f5restip
username = parsed_args.username
password = parsed_args.password
license_key = parsed_args.licensekey
hostname = parsed_args.hostname
ntpserver = parsed_args.ntpserver
dnsserver = parsed_args.dnsserver
vpn = {}
vpn['local-self-ip'] = parsed_args.localselfip
vpn['local-public-ip'] = parsed_args.localpublicip
vpn['remote-public-ip'] = parsed_args.remotepublicip
vpn['pre-shared-key'] = parsed_args.presharedkey
vpn['tunnel-source-network'] = parsed_args.tunnelsourcenet
vpn['tunnel-destination-network'] = parsed_args.tunneldestinationnet
vpn['tunnel-static-route-network'] = parsed_args.tunnelstaticroutenet
vpn['tunnel-name'] = 'ipsec_peer-' + vpn['remote-public-ip']
vpn['tunnel-self-ip'] = parsed_args.tunnelselfiplocal
vpn['new-local-self-ip'] = parsed_args.newlocalselfip
vpn['new-local-self-ip-gateway'] = parsed_args.newlocalselfipgateway
vpn['local-server-subnet'] = parsed_args.localserversubnet
vpn['tunnel-self-ip-remote'] = parsed_args.tunnelselfipremote
vpn['bgp-as'] = parsed_args.bgpas

# License the VEif license_key is not None:
   print('Licensing ' + host + ' with key ' + license_key)
  apiCallResponse = icontrol_post(host,username,password,'/mgmt/tm/sys/license',{"command":"install","registrationKey":license_key})
   if 'New license installed' in apiCallResponse:
   print('License installed successfully. Waiting for service restart.')
  time.sleep(60)
   else:
   print('Error! ' + apiCallResponse)
   quit()
else:
   print("License key not specified - skipping licensing")

# Disable the GUI setup wizard since we're configuring via RESTprint('Disabling the GUI Setup Wizard')
apiCallResponse = icontrol_patch(host,username,password,'/mgmt/tm/sys/global-settings',{"guiSetup":"disabled"})
if '"kind":"tm:sys:global-settings:global-settingsstate"' and '"guiSetup":"disabled"' in apiCallResponse:
   print('Success!')
else:
   print('Error! ' + apiCallResponse)
   quit()

# Set the hostname if specifiedif hostname is not None:
   print('Setting the hostname to ' + hostname + ' on ' + host)
  apiCallResponse = icontrol_patch(host, username, password, '/mgmt/tm/sys/global-settings',{"hostname":hostname})
   if '"kind":"tm:sys:global-settings:global-settingsstate"' and '"hostname":"' + hostname + '"' in apiCallResponse:
   print('Success!')
   else:
   print('Error! ' + apiCallResponse)
   quit()
else:
   print('Hostname not specified - skipping hostname configuration')

# Set the DNS server if specifiedif dnsserver is not None:
   print('Adding DNS server ' + dnsserver + ' to ' + host)
  apiCallResponse = icontrol_patch(host,username,password,'/mgmt/tm/sys/dns',{"nameServers":[dnsserver]})
   if '"kind":"tm:sys:dns:dnsstate"' in apiCallResponse:
   print('Success!')
   else:
   print('Error! ' + apiCallResponse)
   quit()
else:
   print('DNS server not specified - skipping DNS server configuration')

# Set the NTP server if specifiedif ntpserver is not None:
   print('Adding NTP server ' + ntpserver + ' to ' + host)
  apiCallResponse = icontrol_patch(host,username,password,'/mgmt/tm/sys/ntp',{"servers":[ntpserver]})
   if '"kind":"tm:sys:ntp:ntpstate"' in apiCallResponse:
   print('Success!')
   else:
   print('Error! ' + apiCallResponse)
   quit()
else:
   print('NTP server not specified - skipping NTP server configuration')

# Configure the local VLAN and self-IP if specifiedif vpn['new-local-self-ip'] is not None:
   # create external VLAN untagged   print('Creating the external VLAN')
  apiPayload = {}
  apiPayload['name'] = 'external'   apiPayload['interfaces'] = ['1.1']
  apiPayload['partition'] = 'Common'   apiPayload['mtu'] = 1500   apiCallResponse = icontrol_post(host,username,password,'/mgmt/tm/net/vlan',apiPayload)
   if '"kind":"tm:net:vlan:vlanstate"' in apiCallResponse :
   print('Success!')
   elif 'already exists' in apiCallResponse:
   print('Object exists; skipping item configuration')
   else:
   print('Error! ' + apiCallResponse)
   quit()
   # create the data interface self-IP   print('Creating the self-IP address ' + vpn['local-self-ip'])
  apiPayload = {}
  apiPayload['name'] = vpn['new-local-self-ip']
  apiPayload['address'] = vpn['new-local-self-ip']
  apiPayload['allowService'] = 'all'   apiPayload['interfaces'] = ['1.1']
  apiPayload['partition'] = 'Common'   apiPayload['trafficGroup'] = 'traffic-group-local-only'   apiPayload['vlan'] = 'external'   apiCallResponse = icontrol_post(host,username,password,'/mgmt/tm/net/self',apiPayload)
   if '"kind":"tm:net:self:selfstate"' in apiCallResponse :
   print('Success!')
   elif 'already exists' in apiCallResponse:
   print('Object exists; skipping item configuration')
   else:
   print('Error! ' + apiCallResponse)
   quit()

## Begin IPSec tunnel configuration steps## Create the IPSec policyprint('Creating the IPSec Policy (' + vpn['tunnel-name'] + ')')
apiPayload = {}
apiPayload['name'] = vpn['tunnel-name']
apiPayload['ikePhase2AuthAlgorithm'] = 'aes-gcm128'apiPayload['ikePhase2EncryptAlgorithm'] = 'aes-gcm128'apiPayload['ikePhase2PerfectForwardSecrecy'] = 'none'apiPayload['ikePhase2Lifetime'] = 1440apiPayload['ikePhase2LifetimeKilobytes'] = 0apiPayload['mode'] = 'interface'apiPayload['protocol'] = 'esp'apiPayload['ipcomp'] = 'none'apiPayload['partition'] = 'Common'apiCallResponse = icontrol_post(host,username,password,'/mgmt/tm/net/ipsec/ipsec-policy',apiPayload)
if '"kind":"tm:net:ipsec:ipsec-policy:ipsec-policystate"' in apiCallResponse :
   print('Success!')
elif 'already exists' in apiCallResponse:
   print('Object exists; updating with new configuration')
  apiCallResponse = icontrol_put(host, username, password, '/mgmt/tm/net/ipsec/ipsec-policy/' + vpn['tunnel-name'], apiPayload)
   if '"kind":"tm:net:ipsec:ipsec-policy:ipsec-policystate"' in apiCallResponse:
   print('Success!')
   else:
   print('Error! ' + apiCallResponse)
   quit()
else:
   print('Error! ' + apiCallResponse)
   quit()

# Create the IPSec traffic selectorprint('Creating the IPSec traffic selector (' + vpn['tunnel-name'] + ')')
apiPayload = {}
apiPayload['name'] = vpn['tunnel-name']
apiPayload['partition'] = 'Common'apiPayload['action'] = 'protect'apiPayload['sourceAddress'] = vpn['tunnel-source-network']
apiPayload['sourcePort'] = 0apiPayload['destinationAddress'] = vpn['tunnel-destination-network']
apiPayload['destinationPort'] = 0apiPayload['direction'] = 'both'apiPayload['ipProtocol'] = 255apiPayload['ipsecPolicy'] = vpn['tunnel-name']
apiPayload['order'] = 0apiCallResponse = icontrol_post(host,username,password,'/mgmt/tm/net/ipsec/traffic-selector',apiPayload)
if '"kind":"tm:net:ipsec:traffic-selector:traffic-selectorstate"' in apiCallResponse:
   print('Success!')
elif 'already exists' in apiCallResponse:
   print('Object exists; updating with new configuration')
  apiCallResponse = icontrol_put(host, username, password, '/mgmt/tm/net/ipsec/traffic-selector/' + vpn['tunnel-name'], apiPayload)
   if '"kind":"tm:net:ipsec:traffic-selector:traffic-selectorstate"' in apiCallResponse:
   print('Success!')
   else:
   print('Error! ' + apiCallResponse)
   quit()
else:
   print('Error! ' + apiCallResponse)
   quit()

# Set the default IPSec traffic selector to high orderprint('Setting the default IPSec traffic selector to order 100')
apiPayload = {}
apiPayload['order'] = 100apiCallResponse = icontrol_patch(host,username,password,'/mgmt/tm/net/ipsec/traffic-selector/default-traffic-selector-interface',apiPayload)
if '"kind":"tm:net:ipsec:traffic-selector:traffic-selectorstate"' in apiCallResponse:
   print('Success!')
else:
   print('Error! ' + apiCallResponse)
   quit()

# Create the IKE peerprint('Creating the IKE peer (' + vpn['tunnel-name'] + ')')
apiPayload = {}
apiPayload['name'] = vpn['tunnel-name']
apiPayload['myIdType'] = 'address'apiPayload['myIdValue'] = vpn['local-public-ip']
apiPayload['peersIdType'] = 'address'apiPayload['peersIdValue'] = vpn['remote-public-ip']
apiPayload['phase1AuthMethod'] = 'pre-shared-key'apiPayload['phase1EncryptAlgorithm'] = 'aes256'apiPayload['phase1HashAlgorithm'] = 'sha256'apiPayload['phase1PerfectForwardSecrecy'] = 'modp1024'apiPayload['prf'] = 'sha256'apiPayload['presharedKey'] = vpn['pre-shared-key']
apiPayload['remoteAddress'] = vpn['remote-public-ip']
apiPayload['version'] = ['v2']
apiPayload['dpdDelay'] = 30apiPayload['lifetime'] = 1440apiPayload['mode'] = 'main'apiPayload['natTraversal'] = 'off'apiPayload['passive'] = 'false'apiPayload['generatePolicy'] = 'off'apiPayload['trafficSelector'] = [vpn['tunnel-name']]
apiCallResponse = icontrol_post(host,username,password,'/mgmt/tm/net/ipsec/ike-peer',apiPayload)
if '"kind":"tm:net:ipsec:ike-peer:ike-peerstate"' in apiCallResponse:
   print('Success!')
elif 'already exists' in apiCallResponse:
   print('Object exists; updating with new configuration')
  apiCallResponse = icontrol_put(host, username, password, '/mgmt/tm/net/ipsec/ike-peer/' + vpn['tunnel-name'], apiPayload)
   if '"kind":"tm:net:ipsec:ike-peer:ike-peerstate"' in apiCallResponse:
   print('Success!')
   else:
   print('Error! ' + apiCallResponse)
   quit()
else:
   print('Error! ' + apiCallResponse)
   quit()

# Create the tunnel profileprint('Creating the tunnel profile (' + vpn['tunnel-name'] + ')')
apiPayload = {}
apiPayload['name'] = vpn['tunnel-name']
apiPayload['defaultsFrom'] = '/Common/ipsec'apiPayload['trafficSelector'] = vpn['tunnel-name']
apiCallResponse = icontrol_post(host,username,password,'/mgmt/tm/net/tunnels/ipsec',apiPayload)
if '"kind":"tm:net:tunnels:ipsec:ipsecstate"' in apiCallResponse:
   print('Success!')
elif 'already exists' in apiCallResponse:
   print('Object exists; updating with new configuration')
  apiCallResponse = icontrol_put(host, username, password, '/mgmt/tm/net/tunnels/ipsec/' + vpn['tunnel-name'], apiPayload)
   if '"kind":"tm:net:tunnels:ipsec:ipsecstate"' in apiCallResponse:
   print('Success!')
   else:
   print('Error! ' + apiCallResponse)
   quit()
else:
   print('Error! ' + apiCallResponse)
   quit()

# Create the tunnel objectprint('Creating the tunnel object (' + vpn['tunnel-name'] + ')')
apiPayload = {}
apiPayload['name'] = vpn['tunnel-name']
apiPayload['autoLasthop'] = 'default'apiPayload['idleTimeout'] = 300apiPayload['key'] = 0apiPayload['localAddress'] = vpn['local-self-ip']
apiPayload['mode'] = 'bidirectional'apiPayload['mtu'] = 0apiPayload['profile'] = vpn['tunnel-name']
apiPayload['remoteAddress'] = vpn['remote-public-ip']
apiPayload['tos'] = 'preserve'apiPayload['transparent'] = 'disabled'apiPayload['usePmtu'] = 'enabled'apiCallResponse = icontrol_post(host,username,password,'/mgmt/tm/net/tunnels/tunnel',apiPayload)
if '"kind":"tm:net:tunnels:tunnel:tunnelstate"' in apiCallResponse:
   print('Success!')
elif 'already exists' in apiCallResponse:
   print('Object exists; updating with new configuration')
  apiCallResponse = icontrol_put(host, username, password, '/mgmt/tm/net/tunnels/tunnel/' + vpn['tunnel-name'], apiPayload)
   if '"kind":"tm:net:tunnels:tunnel:tunnelstate"' in apiCallResponse:
   print('Success!')
   else:
   print('Error! ' + apiCallResponse)
   quit()
else:
   print('Error! ' + apiCallResponse)
   quit()

# Create the self-IP for the tunnel-routeprint('Creating the local tunnel interface self-IP (' + vpn['tunnel-name'] + ')')
apiPayload = {}
apiPayload['name'] = vpn['tunnel-name']
apiPayload['address'] = vpn['tunnel-self-ip']
apiPayload['vlan'] = vpn['tunnel-name']
apiPayload['traffic-group'] = 'traffic-group-local-only'apiPayload['allowService'] = 'all'apiCallResponse = icontrol_post(host,username,password,'/mgmt/tm/net/self',apiPayload)
if '"kind":"tm:net:self:selfstate"' in apiCallResponse:
   print('Success!')
elif 'already exists' in apiCallResponse:
   print('Object exists; updating with new configuration')
  apiCallResponse = icontrol_put(host, username, password, '/mgmt/tm/net/self/' + vpn['tunnel-name'], apiPayload)
   if '"kind":"tm:net:self:selfstate"' in apiCallResponse:
   print('Success!')
   else:
   print('Error! ' + apiCallResponse)
   quit()
else:
   print('Error! ' + apiCallResponse)
   quit()

# Point the peer route over the tunnelprint('Creating the peer static route (' + vpn['tunnel-name'] + ')')
apiPayload = {}
apiPayload['name'] = vpn['tunnel-name']
apiPayload['tmInterface'] = vpn['tunnel-name']
apiPayload['network'] = vpn['tunnel-self-ip-remote'] + '/32'apiPayload['mtu'] = 0apiCallResponse = icontrol_post(host,username,password,'/mgmt/tm/net/route',apiPayload)
if '"kind":"tm:net:route:routestate"' in apiCallResponse:
   print('Success!')
elif 'already exists' in apiCallResponse:
   print('Object exists; updating with new configuration')
  apiCallResponse = icontrol_put(host, username, password, '/mgmt/tm/net/route/~Common~'+vpn['tunnel-name'], apiPayload)
   if '"kind":"tm:net:route:routestate"' in apiCallResponse:
   print('Success!')
   else:
   print('Error! ' + apiCallResponse)
   quit()
else:
   print('Error! ' + apiCallResponse)
   quit()

# Create the local server routeprint('Creating the default static route to ' + vpn['new-local-self-ip-gateway'])
apiPayload = {}
apiPayload['name'] = 'local-servers'apiPayload['gw'] = vpn['new-local-self-ip-gateway']
apiPayload['network'] = vpn['local-server-subnet']
apiCallResponse = icontrol_post(host, username, password, '/mgmt/tm/net/route', apiPayload)
if '"kind":"tm:net:route:routestate"' in apiCallResponse:
   print('Success!')
elif 'already exists' in apiCallResponse:
   print('Object exists; updating with new configuration')
  apiCallResponse = icontrol_put(host, username, password, '/mgmt/tm/net/route/~Common~local-servers', apiPayload)
   if '"kind":"tm:net:route:routestate"' in apiCallResponse:
   print('Success!')
   else:
   print('Error! ' + apiCallResponse)
   quit()
else:
   print('Error! ' + apiCallResponse)
   quit()

# Create the remote peer routerprint('Creating the IPSec peer route to ' + vpn['remote-public-ip'])
apiPayload = {}
apiPayload['name'] = 'remote-peer'apiPayload['gw'] = vpn['new-local-self-ip-gateway']
apiPayload['network'] = vpn['remote-public-ip']
apiCallResponse = icontrol_post(host, username, password, '/mgmt/tm/net/route', apiPayload)
if '"kind":"tm:net:route:routestate"' in apiCallResponse:
   print('Success!')
elif 'already exists' in apiCallResponse:
   print('Object exists; updating with new configuration')
  apiCallResponse = icontrol_put(host, username, password, '/mgmt/tm/net/route/~Common~remote-peer', apiPayload)
   if '"kind":"tm:net:route:routestate"' in apiCallResponse:
   print('Success!')
   else:
   print('Error! ' + apiCallResponse)
   quit()
else:
   print('Error! ' + apiCallResponse)
   quit()


# Create the default IP forwarding virtual serverprint('Creating the default IP forwarding virtual server')
apiPayload = {}
apiPayload['name'] = 'ipsec_forwarding_vs'apiPayload['destination'] = '/Common/0.0.0.0:0'apiPayload['ipProtocol'] = 'any'apiPayload['mask'] = '0.0.0.0'apiPayload['source'] = '0.0.0.0/0'apiPayload['ipForward'] = TrueapiPayload['sourceAddressTranslation'] = {'type':'none'}
apiPayload['sourcePort'] = 'preserve-strict'apiPayload['profiles'] = ['/Common/fastL4']
apiPayload['enabled'] = TrueapiPayload['translateAddress'] = 'disabled'apiPayload['translatePort'] = 'disabled'apiCallResponse = icontrol_post(host,username,password,'/mgmt/tm/ltm/virtual',apiPayload)
if '"kind":"tm:ltm:virtual:virtualstate"' in apiCallResponse:
   print('Success!')
elif 'already exists' in apiCallResponse:
   print('Object exists; updating with new configuration')
  apiCallResponse = icontrol_put(host, username, password, '/mgmt/tm/ltm/virtual/~Common~ipsec_forwarding_vs', apiPayload)
   if '"kind":"tm:ltm:virtual:virtualstate"' in apiCallResponse:
   print('Success!')
   else:
   print('Error! ' + apiCallResponse)
   quit()
else:
   print('Error! ' + apiCallResponse)
   quit()

# Disable the ipsec.if.checkpolicy db variableprint('Disabling the db variable ipsec.if.checkpolicy to allow BGP routing')
apiPayload = {}
apiPayload['value'] = 'disable'apiCallResponse = icontrol_put(host,username,password,'/mgmt/tm/sys/db/ipsec.if.checkpolicy',apiPayload)
if '"kind":"tm:sys:db:dbstate"' in apiCallResponse:
   print('Success!')
else:
   print('Error! ' + apiCallResponse)
   quit()

# Enable BGP and BFD on route domain 0print('Enabling routing protocols on default route domain 0')
apiPayload['routingProtocol'] = ['BFD','BGP']
apiCallResponse = icontrol_patch(host,username,password,'/mgmt/tm/net/route-domain/~Common~0',apiPayload)
if '"kind":"tm:net:route-domain:route-domainstate"' in apiCallResponse:
   print('Success!')
else:
   print('Error!' + apiCallResponse)
   quit()

# Sleep to let BGP start upprint('Waiting for BGP to start')
time.sleep(15)

# Create the BGP configuration CLI scriptprint('Creating the BGP configuration CLI script on the BIG-IP')
apiPayload = {}
apiPayload['kind'] = 'kind":"tm:cli:script:scriptstate'apiPayload['name'] = 'bgp_configuration'apiPayload['partition'] = 'Common'apiPayload['apiAnonymous'] = 'proc script::init {} {\n}\n\nproc script::run {} {\n  set neighboraddr [lindex $tmsh::argv 1]\n  set asnumber [lindex $tmsh::argv 2]\n  set imishscript [open "/var/tmp/imish_script.tmp" "w"]\n  puts $imishscript "enable"\n  puts $imishscript "configure terminal"\n  puts $imishscript "router bgp $asnumber"\n  puts $imishscript "neighbor $neighboraddr remote-as $asnumber"\n  puts $imishscript "neighbor $neighboraddr activate"\n  puts $imishscript "neighbor $neighboraddr soft-reconfiguration inbound"\n  puts $imishscript "neighbor $neighboraddr timers 10 30"\n  puts $imishscript "neighbor $neighboraddr timers connect 10"\n  puts $imishscript "end"\n  puts $imishscript "write mem"\n  close $imishscript\n  set rc [ catch { exec imish -f /var/tmp/imish_script.tmp } result ]\n  puts $rc\n  puts $result\n}\n\nproc script::help {} {\n}\n\nproc script::tabc {} {\n}\n'apiCallResponse = icontrol_post(host,username,password,'/mgmt/tm/cli/script',apiPayload)
if '"kind":"tm:cli:script:scriptstate"' in apiCallResponse:
   print('Success!')
elif 'already exists' in apiCallResponse:
   print('Script already exists! Skipping creation')
else:
   print('Error! ' + apiCallResponse)

# Create the BGP configurationprint('Creating BGP AS ' + vpn['bgp-as'] + ' with neighbor ' + vpn['tunnel-self-ip-remote'])
apiPayload = {}
apiPayload['command'] = 'run'apiPayload['name'] = 'bgp_configuration'apiPayload['utilCmdArgs'] = vpn['tunnel-self-ip-remote'] + ' ' + vpn['bgp-as']
apiCallResponse = icontrol_post(host,username,password,'/mgmt/tm/cli/script',apiPayload)
print('Script executed but BGP configuration should be manually confirmed!')

# Save the configurationprint('Saving the configuration')
apiPayload = {}
apiPayload['command'] = 'save'apiCallResponse = icontrol_post(host,username,password,'/mgmt/tm/sys/config',apiPayload)
if '"kind":"tm:sys:config:savestate","command":"save"' in apiCallResponse:
   print('Success!')
else:
   print('Error! ' + apiCallResponse)
   quit()
