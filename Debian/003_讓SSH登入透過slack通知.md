# SSH Login Notifications in Slack

----

003_讓SSH登入透過slack通知.md

## Step 1 申請Slack的API

```
https://YOUR_DOMAIN.slack.com/apps/manage/custom-integrations

```

## Step 2 . Add an SSH script to your server

Add and make executable (chmod+x) file to /etc/ssh/scripts/sshnotify.sh (note Make sure to replace <YOUR SLACKWEBHOOK> with the URL from step 1 and #channel with the channel you want notifications going to)

```language
mkdir -p /etc/ssh/scripts

vi /etc/ssh/scripts/sshnotify.sh 

```
#### 新增以下內容

```language
# 將 <YOUR SLACKWEBHOOK> 換成你的 Incoming WebHook url
# 將 #channe 換成你的通知 channe
if [ "$PAM_TYPE" != "close_session" ]; then
        url="<YOUR SLACK WEBHOOK>"
        channel="#channel"
        host="$(hostname)"
        content="\"attachments\": [ { \"mrkdwn_in\": [\"text\", \"fallback\"], \"fallback\": \"SSH login: $PAM_USER connected to \`$host\`\", \"text\": \"SSH login to \`$host\`\", \"fields\": [ { \"title\": \"User\", \"value\": \"$PAM_USER\", \"short\": true }, { \"title\": \"IP Address\", \"value\": \"$PAM_RHOST\", \"short\": true } ], \"color\": \"#F35A00\" } ]"
        curl -X POST --data-urlencode "payload={\"channel\": \"$channel\", \"mrkdwn\": true, \"username\": \"SSH Notifications\", $content, \"icon_emoji\": \":inbox-tray:\"}" "$url" &
fi
exit

```
#### 設定你的權限

```
chmod +x /etc/ssh/scripts/sshnotify.sh 
```

## Step 3. Add the script to your pam.d

```language
sudo echo "session optional pam_exec.so seteuid /etc/ssh/scripts/sshnotify.sh" >> /etc/pam.d/sshd

```

### 最後檢查一下你的SSH登入
