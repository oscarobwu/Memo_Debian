# 設定 powerline

### 安裝

```
apt install python-pip git

apt install powerline

apt install fonts-powerline

pip show powerline-status

pip install powerline-gitstatus

pip install psutil netifaces

vi ~/.bashrc 
####
POWERLINE_SCRIPT=/usr/share/powerline/bindings/bash/powerline.sh
if [ -f $POWERLINE_SCRIPT ]; then
source $POWERLINE_SCRIPT
fi

####
source ~/.bashrc

vi /usr/share/powerline/config_files/colorschemes/shell/default.json

{
        "groups": {
                "gitstatus":                 { "fg": "gray8",           "bg": "gray2", "attrs": [] },
                "gitstatus_branch":          { "fg": "gray8",           "bg": "gray2", "attrs": [] },
                "gitstatus_branch_clean":    { "fg": "green",           "bg": "gray2", "attrs": [] },
                "gitstatus_branch_dirty":    { "fg": "gray8",           "bg": "gray2", "attrs": [] },
                "gitstatus_branch_detached": { "fg": "mediumpurple",    "bg": "gray2", "attrs": [] },
                "gitstatus_tag":             { "fg": "darkcyan",        "bg": "gray2", "attrs": [] },
                "gitstatus_behind":          { "fg": "gray10",          "bg": "gray2", "attrs": [] },
                "gitstatus_ahead":           { "fg": "gray10",          "bg": "gray2", "attrs": [] },
                "gitstatus_staged":          { "fg": "green",           "bg": "gray2", "attrs": [] },
                "gitstatus_unmerged":        { "fg": "brightred",       "bg": "gray2", "attrs": [] },
                "gitstatus_changed":         { "fg": "mediumorange",    "bg": "gray2", "attrs": [] },
                "gitstatus_untracked":       { "fg": "brightestorange", "bg": "gray2", "attrs": [] },
                "gitstatus_stashed":         { "fg": "darkblue",        "bg": "gray2", "attrs": [] },
                "gitstatus:divider":         { "fg": "gray8",           "bg": "gray2", "attrs": [] }
        }
}
```

### themes 的 config.json


```
{
  "cursor_space": 50,
        "segments": {
            "above": [{
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
                                "after": " "
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
                                  "dir_shorten_len": 10,
                                  "dir_limit_depth": 8
                                }
                        },
                        {
                                "function": "powerline.segments.shell.last_status",
                                "priority": 20
                        },
                        {
                                "function": "powerline.segments.common.time.date",
                                "before": " "
                        },
                        {
                                "function": "powerline.segments.common.time.date",
                                "name": "time",
                                "args": {
                                  "format": "%H:%M",
                                  "istime": true
                                },
                                "after": " "
                        },
                        {
                                "function": "powerline.segments.shell.jobnum",
                                "priority": 20
                        }
                ],
                "right": [
                        {
                                "function": "powerline.segments.shell.last_pipe_status",
                                "priority": 10
                        },
                        {
                                "function": "powerline.segments.common.vcs.stash",
                                "priority": 50
                        },
                        {
                                "function": "powerline.segments.common.vcs.branch",
                                "priority": 40
                        }
                ]
            }],
            "left": [
                {
                    "type": "string",
                    "contents": "$ ",
                    "highlight_groups": ["continuation:current"]
                }
            ],
            "right": [
            ]
        }
}
```
