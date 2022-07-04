# 設定 powerline

### 安裝

```
apt install python-pip git -y

apt install powerline -y

apt install fonts-powerline -y

pip show powerline-status

pip install powerline-gitstatus

pip install psutil netifaces

pip install powerline-mem-segment
# for vim
pip install flake8 yapf

vi ~/.bashrc 
####
POWERLINE_SCRIPT=/usr/share/powerline/bindings/bash/powerline.sh
if [ -f $POWERLINE_SCRIPT ]; then
  powerline-daemon -q
  POWERLINE_BASH_CONTINUATION=1
  POWERLINE_BASH_SELECT=1
  source $POWERLINE_SCRIPT
fi

####
source ~/.bashrc

#### 重啟 powerline-daemon
powerline-daemon --replace

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

### 確認使用哪一個佈景

```
vi /usr/share/powerline/config_files/config.json

{
        "common": {
                "term_truecolor": false
        },
        "ext": {
                "ipython": {
                        "colorscheme": "default",
                        "theme": "in",
                        "local_themes": {
                                "rewrite": "rewrite",
                                "out": "out",
                                "in2": "in2"
                        }
                },
                "pdb": {
                        "colorscheme": "default",
                        "theme": "default"
                },
                "shell": {
                        "colorscheme": "default",
                        "theme": "default_good",
                        "local_themes": {
                                "continuation": "continuation",
                                "select": "select"
                        }
                },
                "tmux": {
                        "colorscheme": "default",
                        "theme": "default"
                },
                "vim": {
                        "colorscheme": "default",
                        "theme": "default",
                        "local_themes": {
                                "__tabline__": "tabline",

                                "cmdwin": "cmdwin",
                                "help": "help",
                                "quickfix": "quickfix",

                                "powerline.matchers.vim.plugin.nerdtree.nerdtree": "plugin_nerdtree",
                                "powerline.matchers.vim.plugin.commandt.commandt": "plugin_commandt",
                                "powerline.matchers.vim.plugin.gundo.gundo": "plugin_gundo",
                                "powerline.matchers.vim.plugin.gundo.gundo_preview": "plugin_gundo-preview"
                        }
                },
                "wm": {
                        "colorscheme": "default",
                        "theme": "default",
                        "update_interval": 2
                }
        }
}


```

### themes 的 config.json

## 新增一個佈景
## vi  /usr/share/powerline/config_files/themes/shell/default_good.json
## 將 "theme": "default_good", 修改
##
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

### 修改背景顏色
#### vi /usr/share/powerline/config_files/colorschemes/shell/default.json
###  新增 internal_ip 顏色

```
{
        "name": "Default color scheme for shell prompts",
        "groups": {
                "hostname":         { "fg": "brightyellow", "bg": "mediumorange", "attrs": [] },
                "environment":      { "fg": "white", "bg": "darkestgreen", "attrs": [] },
                "mode":             { "fg": "darkestgreen", "bg": "brightgreen", "attrs": ["bold"] },
                "attached_clients": { "fg": "white", "bg": "darkestgreen", "attrs": [] },
                "internal_ip":      { "fg": "darkestgreen", "bg": "brightgreen", "attrs": ["bold"] },
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
        },
        "mode_translations": {
                "vicmd": {
                        "groups": {
                                "mode": {"fg": "darkestcyan", "bg": "white", "attrs": ["bold"]}
                        }
                }
        }
}

```
### 範例 2 /usr/share/powerline/config_files/themes/shell/default_good.json
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
                                "after": " "
                        },
                        {
                                 "function": "powerline.segments.common.time.date",
                                 "name": "time",
                                   "args": {
                                     "format": "%Y/%m/%d %H:%M:%S"
                              }
                        },
                        {
                                "function":"powerline.segments.common.sys.cpu_load_percent",
                                "before": "CPU: ",
                                "priority": 20
                        },
                        {
                                "function": "powerlinemem.mem_usage.mem_usage",
                                "priority": 50,
                                "args": {
                                    "mem_type": "active"
                                }
                        }
                ],
                "right": [
                        {
                                "function": "powerlinemem.mem_usage.mem_usage_percent",
                                "priority": 50,
                                "args": {
                                    "format": "Mem: %d%%"
                                }
                        },
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

### 設定 新增記憶體顏色

```
{
        "name": "Default color scheme for shell prompts",
        "groups": {
                "hostname":         { "fg": "brightyellow", "bg": "mediumorange", "attrs": [] },
                "environment":      { "fg": "white", "bg": "darkestgreen", "attrs": [] },
                "mode":             { "fg": "darkestgreen", "bg": "brightgreen", "attrs": ["bold"] },
                "attached_clients": { "fg": "white", "bg": "darkestgreen", "attrs": [] },
                "internal_ip":      { "fg": "darkestgreen", "bg": "brightgreen", "attrs": ["bold"] },
                "date":             { "fg": "white", "bg": "green", "attrs": ["bold"] },
                "mem_usage":                 { "fg": "gray8", "bg": "gray0", "attrs": [] },
                "mem_usage_gradient":        { "fg": "green_yellow_orange_red", "bg": "gray0", "attrs": [] }
        },
        "mode_translations": {
                "vicmd": {
                        "groups": {
                                "mode": {"fg": "darkestcyan", "bg": "white", "attrs": ["bold"]}
                        }
                }
        }
}

```

### 20220704 設定
### vi /usr/share/powerline/config_files/themes/shell/default_good.json

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
                                "after": " "
                        },
                        {
                                 "function": "powerline.segments.common.time.date",
                                 "name": "time",
                                   "args": {
                                     "format": "%Y/%m/%d %H:%M:%S"
                              }
                        },
                        {
                                "function":"powerline.segments.common.sys.cpu_load_percent",
                                "before": "CPU: ",
                                "priority": 20
                        },
                        {
                                "function": "powerlinemem.mem_usage.mem_usage",
                                "priority": 50,
                                "args": {
                                    "mem_type": "active"
                                }
                        }
                ],
                "right": [
                        {
                                "function": "powerlinemem.mem_usage.mem_usage_percent",
                                "priority": 50,
                                "args": {
                                    "format": "Mem: %d%%"
                                }
                        },
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

### color
### /usr/share/powerline/config_files/colorschemes/shell/default.json

```
{
        "name": "Default color scheme for shell prompts",
        "groups": {
                "hostname":         { "fg": "brightyellow", "bg": "mediumorange", "attrs": [] },
                "environment":      { "fg": "white", "bg": "darkestgreen", "attrs": [] },
                "mode":             { "fg": "darkestgreen", "bg": "brightgreen", "attrs": ["bold"] },
                "attached_clients": { "fg": "white", "bg": "darkestgreen", "attrs": [] },
                "internal_ip":      { "fg": "darkestgreen", "bg": "brightgreen", "attrs": ["bold"] },
                "date":             { "fg": "white", "bg": "green", "attrs": ["bold"] },
                "mem_usage":                 { "fg": "gray8", "bg": "gray0", "attrs": [] },
                "mem_usage_gradient":        { "fg": "green_yellow_orange_red", "bg": "gray0", "attrs": [] }
        },
        "mode_translations": {
                "vicmd": {
                        "groups": {
                                "mode": {"fg": "darkestcyan", "bg": "white", "attrs": ["bold"]}
                        }
                }
        }
}

```
### 成功範例

```
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
                                                        "use_path_separator": true
                                                }
                                        },
                                        {
                                                "function": "powerline.segments.shell.jobnum",
                                                "priority": 20
                                        },
                                        {
                                                "function": "powerline_gitstatus.gitstatus",
                                                "priority": 40
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
                                        "format": "$"
                                }
                        }
                ],
                "right": [
                ]
        }
}

```
