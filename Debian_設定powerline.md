```
設定powerline布景
/themes/shell/default.json
cat ~/.config/powerline/themes/shell/default.json

{
  "segments": {
      "above": [
                {
                                "left": [
                                                  {
                                                                      "function": "powerline.segments.shell.mode"
                                                                    },
                                                  {
                                                                      "function": "powerline.segments.common.net.hostname",
                                                                      "priority": 10
                                                                    },
                                                  {
                                                                      "function": "powerline.segments.common.net.internal_ip",
                                                                      "priority": 30
                                                                    },
                                                  {
                                                                      "function": "powerline.segments.common.env.user",
                                                                      "priority": 30
                                                                    },
                                                  {
                                                                      "function": "powerline.segments.common.env.virtualenv",
                                                                      "priority": 50
                                                                    },
                                                  {
                                                                      "function": "powerline.segments.shell.cwd",
                                                                      "priority": 10,
                                                                      "args": {
                                                                                                "use_path_separator": true
                                                                                              }
                                                                    },
                                                  {
                                                                      "function": "powerline.segments.shell.jobnum",
                                                                      "priority": 20
                                                                    },
                                                  {
                                                                      "function": "powerline.segments.shell.continuation",
                                                                                                          "draw_hard_divider": false
                                                                    }
                                                ],
                                "right": [
                                                  {
                                                                      "function": "powerline.segments.shell.last_status"
                                                                    },
                                                  {
                                                                      "function": "powerline.segments.common.time.date"
                                                                    },
                                                  {
                                                                      "function": "powerline.segments.common.time.date",
                                                                      "name": "time",
                                                                      "args": {
                                                                                                "format": "%H:%M:%S %A",
                                                                                                "istime": true
                                                                                              }
                                                                    },
                                                  {
                                                                      "function": "powerline.segments.common.vcs.branch",
                                                                      "priority": 40
                                                                    }
                                                ]
                              },
                {
                              }
              ],
      "left": [
                {
                                "function": "powerline.segments.shell.mode"
                              },
                {
                                "function": "powerline.segments.shell.last_pipe_status",
                                "priority": 10
                              },
                {
                                "function": "powerline.segments.common.time.date",
                                "args": {
                                                  "format": "#"
                                                }
                              }
              ],
      "right": [
              ]
    }
}
```
```
######
設定顏色

/usr/local/lib/python3.7/dist-packages/powerline/config_files/colorschemes/default.json
{
        "name": "Default",
        "groups": {
                "information:additional":     { "fg": "gray9", "bg": "gray4", "attrs": [] },
                "information:regular":        { "fg": "gray10", "bg": "gray4", "attrs": ["bold"] },
                "information:highlighted":    { "fg": "white", "bg": "gray4", "attrs": [] },
                "information:priority":       { "fg": "brightyellow", "bg": "mediumorange", "attrs": [] },
                "warning:regular":            { "fg": "white", "bg": "brightred", "attrs": ["bold"] },
                "critical:failure":           { "fg": "white", "bg": "darkestred", "attrs": [] },
                "critical:success":           { "fg": "white", "bg": "darkestgreen", "attrs": [] },
                "background":                 { "fg": "white", "bg": "gray0", "attrs": [] },
                "background:divider":         { "fg": "gray5", "bg": "gray0", "attrs": [] },
                "session":                    { "fg": "black", "bg": "gray10", "attrs": ["bold"] },
                "date":                       { "fg": "gray8", "bg": "gray2", "attrs": [] },
                "time":                       { "fg": "gray10", "bg": "gray2", "attrs": ["bold"] },
                "time:divider":               { "fg": "gray5", "bg": "gray2", "attrs": [] },
                "email_alert":                "warning:regular",
                "email_alert_gradient":       { "fg": "white", "bg": "yellow_orange_red", "attrs": ["bold"] },
                "hostname":                   { "fg": "black", "bg": "gray10", "attrs": ["bold"] },
                "weather":                    { "fg": "gray8", "bg": "gray0", "attrs": [] },
                "weather_temp_gradient":      { "fg": "blue_red", "bg": "gray0", "attrs": [] },
                "weather_condition_hot":      { "fg": "khaki1", "bg": "gray0", "attrs": [] },
                "weather_condition_snowy":    { "fg": "skyblue1", "bg": "gray0", "attrs": [] },
                "weather_condition_rainy":    { "fg": "skyblue1", "bg": "gray0", "attrs": [] },
                "uptime":                     { "fg": "gray8", "bg": "gray0", "attrs": [] },
                "external_ip":                { "fg": "gray8", "bg": "gray0", "attrs": [] },
                "internal_ip":                { "fg": "white", "bg": "green", "attrs": [] },
                "network_load":               { "fg": "gray8", "bg": "gray0", "attrs": [] },
                "network_load_gradient":      { "fg": "green_yellow_orange_red", "bg": "gray0", "attrs": [] },
                "network_load_sent_gradient": "network_load_gradient",
                "network_load_recv_gradient": "network_load_gradient",
                "network_load:divider":       "background:divider",
                "system_load":                { "fg": "gray8", "bg": "gray0", "attrs": [] },
                "system_load_gradient":       { "fg": "green_yellow_orange_red", "bg": "gray0", "attrs": [] },
                "environment":                { "fg": "gray8", "bg": "gray0", "attrs": [] },
                "cpu_load_percent":           { "fg": "gray8", "bg": "gray0", "attrs": [] },
                "cpu_load_percent_gradient":  { "fg": "green_yellow_orange_red", "bg": "gray0", "attrs": [] },
                "battery":                    { "fg": "gray8", "bg": "gray0", "attrs": [] },
                "battery_gradient":           { "fg": "white_red", "bg": "gray0", "attrs": [] },
                "battery_full":               { "fg": "red", "bg": "gray0", "attrs": [] },
                "battery_empty":              { "fg": "white", "bg": "gray0", "attrs": [] },
                "player":                     { "fg": "gray10", "bg": "black", "attrs": [] },
                "user":                       { "fg": "white", "bg": "darkblue", "attrs": ["bold"] },
                "branch":                     { "fg": "gray9", "bg": "gray2", "attrs": [] },
                "branch_dirty":               { "fg": "brightyellow", "bg": "gray2", "attrs": [] },
                "branch_clean":               { "fg": "gray9", "bg": "gray2", "attrs": [] },
                "branch:divider":             { "fg": "gray7", "bg": "gray2", "attrs": [] },
                "stash":                      "branch_dirty",
                "stash:divider":              "branch:divider",
                "cwd":                        "information:additional",
                "cwd:current_folder":         "information:regular",
                "cwd:divider":                { "fg": "gray7", "bg": "gray4", "attrs": [] },
                "virtualenv":                 { "fg": "white", "bg": "darkcyan", "attrs": [] },
                "attached_clients":           { "fg": "gray8", "bg": "gray0", "attrs": [] }
        }
}

```
