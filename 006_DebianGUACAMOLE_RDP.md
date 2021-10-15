# 新增bat檔案到桌面

```
[HKEY_LOCAL_MACHINE\SYSTEM\CurrentControlSet\Control\Terminal Server\WinStations\RDP-Tcp]
Change “SecurityLayer” value to dword:00000001
Verify “UserAuthentication” value is dword:0x00000000
```
