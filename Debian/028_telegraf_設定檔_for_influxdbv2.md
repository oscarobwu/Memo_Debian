# telegraf.conf

```
[global_tags]

# Grafana Telegraf Metrics dashboard for InfluxDB 2.0 (Flux) ID : 15650
#
# Configuration for telegraf agent
[agent]
    interval = "10s"
    debug = false
#    hostname = "server-hostname"
    hostname = ""
    round_interval = true
    flush_interval = "10s"
    flush_jitter = "0s"
    collection_jitter = "0s"
    metric_batch_size = 1000
    metric_buffer_limit = 10000
    quiet = false
    logfile = ""
    omit_hostname = false
    precision = ""
    name_prefix = "linuxa_"

###############################################################################
# OUTPUT PLUGINS
###############################################################################

# Configuration for sending metrics to InfluxDB_v2
[outputs.influxdb_v2]
   #namepass= ["linuxa_*"]
   urls = ["http://127.0.0.1:8086"]
   ## Token for authentication.
   token = "W6twk0qOXPkKJ8XZHBbNUVDS5gGaUiRHZIFrsaWRR-izSz29HPtmHqbg6Ss1xkfYg7HgedGj54Z3srUCDOskTw=="
   ## Organization is the name of the organization you wish to write to; must exist.
   organization = "f5mon"
   ## Destination bucket to write into.
   bucket = "f5monitor"


###############################################################################
# PROCESSOR PLUGINS
###############################################################################


###############################################################################
# AGGREGATOR PLUGINS
###############################################################################


###############################################################################
# INPUT PLUGINS
###############################################################################

# Read metrics about cpu usage
[[inputs.cpu]]
        ## Whether to report per-cpu stats or not
        percpu = true
        ## Whether to report total system cpu stats or not
        totalcpu = true
        ## If true, collect raw CPU time metrics
        collect_cpu_time = false
        ## If true, compute and report the sum of all non-idle CPU states
        report_active = false

# Read metrics about disk usage by mount point
[[inputs.disk]]
        ## By default stats will be gathered for all mount points.
        ## Set mount_points will restrict the stats to only the specified mount points.
        # mount_points = ["/"]

        ## Ignore mount points by filesystem type.
        ignore_fs = ["tmpfs", "devtmpfs", "devfs"]

[[inputs.diskio]]
# Get kernel statistics from /proc/stat
[[inputs.kernel]]

# Read metrics about memory usage
[[inputs.mem]]

# Get the number of processes and group them by status
[[inputs.processes]]

# Read metrics about swap memory usage
[[inputs.swap]]

# Read metrics about system load & uptime
[[inputs.system]]
        ## Uncomment to remove deprecated metrics.
        # fielddrop = ["uptime_format"]

# Read metrics about network interface usage
[[inputs.net]]
        ## By default, telegraf gathers stats from any up interface (excluding loopback)
        ## Setting interfaces will tell it to gather these explicit interfaces,
        ## regardless of status.
        ##
        # interfaces = ["eth0"]
        ##
        ## On linux systems telegraf also collects protocol stats.
        ## Setting ignore_protocol_stats to true will skip reporting of protocol metrics.
        ##
        # ignore_protocol_stats = false

# Read TCP metrics such as established, time wait and sockets counts.
[[inputs.netstat]]

# Read metrics about IO
[[inputs.io]]


###############################################################################
# SERVICE INPUT PLUGINS Eed
###############################################################################

```
