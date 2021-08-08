when CLIENT_ACCEPTED {
	set default_pool [LB::server pool]
    set srcip [IP::local_addr]
    set dnsquert [table keys -subtable "nonKUIP"]
    # whitelist
    if { [class match [IP::client_addr] equals DATAGROUP-OAIP-WHITELIST] }{
           return
    }
    if { [class match [IP::local_addr] equals DATAGROUP-KHIP-WHITELIST] }{
           return
    }
	if { [class match [IP::local_addr] equals $dnsquert] }  {
		if {[active_members GW_HK_Internet] < 1} {
			snat automap
			pool GW_HK_Internet
			log local0. "HTTP is allow_IP_HK $srcip is $dnsquert"
		} else {
			pool $default_pool
		}	
	} else {
		pool $default_pool
		log local0. "HTTP is allow_IP_OA-Client Source IP: [IP::client_addr]:[TCP::client_port] $srcip is $dnsquert"
	}
}
