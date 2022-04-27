# Debian 技巧

```
在 jenkins 執行 下面指令
ssh-keygen -t rsa


將key複製到 nginx 上 免密碼登入

scp -p /root/.ssh/id_rsa.pub root@192.168.182.4:/root/.ssh/authorized_keys

# 同步 dist 資料夾 到遠端 html 下
scp -o "StrictHostKeyChecking no" -r dist/* root@192.168.182.4:/home/nginx/html

```

### Windows Server 2019 中文版 NPS RADIUS 驗證失敗的問題

```
Windows Server 2019 NPS RADIUS 驗證失敗的問題
Windows Server 2019 的 NPS 有 RADIUS Server 服務設定
但即使完成了正確的設定，RADIUS Client 仍然無法正確取得驗證
如果此時將 Firewall 關閉則可以正確驗證
顯然問題出在防火牆上

解決辦法下一行 PowerShell 指令即可

英文版
Get-NetFirewallRule -DisplayGroup "Network Policy Server" | where DisplayName -like "*RADIUS*" | Set-NetFirewallRule -Service Any

中文版 x64
Get-NetFirewallRule -DisplayGroup "網路原則伺服器" | where DisplayName -like "*RADIUS*" | Set-NetFirewallRule -Service Any

Reference: https://social.technet.microsoft.com/Forums/en-US/0bf054af-eebe-4a2b-a07b-ccab174b234f/server-2019-radius-blocked-by-defender-firewall
```
