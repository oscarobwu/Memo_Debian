# irule_time_base_v1
# 自動設定維護時間 星期一到日
when HTTP_REQUEST {
    array set static_timerange { 
	    1 {"05:30" "13:00"} 
		2 {"11:10" "13:10"} 
		3 {"05:30" "13:00"} 
		4 {"05:30" "13:00"} 
		5 {"00:00" "00:00"} 
		6 {"00:00" "00:00"} 
		7 {"05:30" "13:00"} 
	} 
    if { ([string tolower [HTTP::uri]] contains "/contractor/") } { 
	    set now [clock seconds] 
		set current_day [clock format $now -format {%u}] 
		set start [lindex $static_timerange($current_day) 0]
		set end [lindex $static_timerange($current_day) 1]
		set st_sec [clock scan $start]
		set end_sec [clock scan $end]
		log local0. "$now - S = $start : E = $end  |  $st_sec : $end_sec "
		if {($now >= [clock scan $start]) && ($now < [clock scan $end])} { 
		    set denied 1 
		    log local0. "time denied 1"
		} else {
		    set denied 0
		    log local0. "denied 0"
		} 
	} else { 
	    set denied 0
	    log local0. "not url denied 0"
	} 
	if { $denied } { 
	    # denied = 1 
        set registerbusinessMP "Not Authorised! Contact AdministratorNot Authorised! Contact Administrator...\r\n"
        append registerbusinessMP "\r\n"
        append registerbusinessMP "\r\n"
        append registerbusinessMP "\r\n"
        append registerbusinessMP "\r\n"
        append registerbusinessMP "\r\n"
        append registerbusinessMP "\r\n"
        append registerbusinessMP "\r\n"
        append registerbusinessMP "\r\n"
        append registerbusinessMP "\r\n"
        append registerbusinessMP "\r\n"
        append registerbusinessMP "\r\n"
        append registerbusinessMP "<br> [HTTP::host] <br>\r\n"
        append registerbusinessMP "$start To $end\r\n"
        append registerbusinessMP "\r\n"
        append registerbusinessMP "\r\n"
        append registerbusinessMP "\r\n"
        append registerbusinessMP "\r\n"
        HTTP::respond 200 -version 1.1 content $registerbusinessMP Content-Type "text/html; charset=utf-8" Strict-Transport-Security "16070400" Connection "close"   
	} else {
	    # denied = 0
	    #pool POOL_443
	    HTTP::redirect "https://www.hinet.net" 
	} 
}
