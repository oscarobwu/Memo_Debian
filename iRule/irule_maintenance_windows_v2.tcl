#irule_maintenance_windows_v2
when HTTP_REQUEST {
    set default_pool [LB::server pool] 
    array set check_timerange { 
		Mon {"05:30" "13:00"} 
		Tue {"05:30" "12:38"} 
		Wed {"05:30" "13:00"} 
		Thu {"05:30" "12:52"} 
		Fri {"00:00" "00:00"} 
		Sat {"00:00" "00:00"} 
		Sun {"05:30" "13:00"} 
	} 
    if { ([string tolower [HTTP::uri]] contains "/") } { 
	    set now [clock seconds] 
		set current_day [clock format $now -format {%a}]
		set start [lindex $check_timerange($current_day) 0] 
		set end [lindex $check_timerange($current_day) 1] 
		if {($now >= [clock scan $start]) && ($now < [clock scan $end])} { 
		    set denied 1
		} else {
		    set denied 0
			} 
	} else {
		set denied 0
	} 
	if { $denied } { 
	    HTTP::respond 200 content "Not Authorised! Contact AdministratorNot Authorised! Contact Administrator... $current_day - $denied-  $start >= $now < $end" 
	} else {
	    pool $default_pool
	} 
}
