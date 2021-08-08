when RULE_INIT {  
	# 1个源ip1秒钟最多建立15次TCP连接
	set static::maxRateConn 15 
	set static::windowSecsConn 1
	set static::lifeSecond 30
	set static::maxSecond 300
}
when DNS_RESPONSE {
	if { ([DNS::question type] eq "A") or ([DNS::question type] eq "AAAA") }  {
		if {[class match [DNS::question name] contains class_foreign] } {
			set DomainIP [table keys -subtable "nonCNIP"] 
			#log local2. "AAAA = $DomainIP"
			if { [ table lookup -subtable "nonCNIP" $DomainIP ] !="" } {
			    set rrs [DNS::answer]
			    foreach rr $rrs {
			    log local2. "[DNS::rdata $rr] = [DNS::question name]"
			    table add -subtable "nonCNIP" [DNS::rdata $rr] [DNS::question name] $static::maxSecond $static::lifeSecond
				}
				} else {
			    set rrs [DNS::answer]
			    foreach rr $rrs {
			    log local2. "[DNS::rdata $rr] = [DNS::question name] AABB = $DomainIP"
			    table set -subtable "nonCNIP" [DNS::rdata $rr] [DNS::question name] indef $static::lifeSecond
			    }
			}
		}
    }
}
