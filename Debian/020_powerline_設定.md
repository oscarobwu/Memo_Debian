# 設定 powerline

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
