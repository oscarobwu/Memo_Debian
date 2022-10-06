# 維護irule



```

#irule_maintenance_windows_v3
when HTTP_REQUEST {
    # Start of maintenance window in YYYY-mm-dd HH:MM format
    set check_start_date "2022-10-06 14:30"
    # End of maintenance window in YYYY-mm-dd HH:MM format
    set check_end_date "2022-10-06 15:00"
    # Convert start/end times to seconds from the epoch for easier date comparisons
    set check_start [clock scan $check_start_date]
    set check_end [clock scan $check_end_date]
    # Get the current time in seconds since the Unix epoch of 0-0-1970
    set now [clock seconds]
    # Check if the current time is between the start and end times
    if {$now > $check_start and $now < $check_end}{
        HTTP::respond 200 content "Not Authorised! Contact AdministratorNot Authorised! Contact Administrator...   $check_start_date >= $now < $check_end_date" 
    }
    # Default action is to use the virtual server default pool
}

```
