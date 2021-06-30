when HTTP_REQUEST {
   # sets the timer to return client to host URL
   set stime 10
   # Use the Host header value for the responses if it's set.  If not, use the VIP address.
   if {[string length [HTTP::host]]}{
      set host [HTTP::host]
   } else {
      set host [IP::local_addr]
   }
   # Check if the URI is /maintenance
   switch [HTTP::uri] {
      "/maintenance" {
         # Send an HTTP 200 response with a Javascript meta-refresh pointing to the host using a refresh time
         HTTP::respond 200 content \
"<html><head><title>Maintenance page</title></head><body><meta http-equiv='REFRESH' content=$stime;url=http://$host></HEAD>\
<p><h2>Sorry! This site is down for maintenance.</h2></p></body></html>" "Content-Type" "text/html" 
         return
      }
   }
   # If the pool_testLB is down, redirect to the maintenance page
   if { [active_members pool_testLB] < 1 } {
      HTTP::redirect "http://$host/maintenance"
      return
   }
}
