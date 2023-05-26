@echo off
setlocal enabledelayedexpansion

for /f "tokens=3 delims=: " %%a in ('ping -n 1 %computername% ^| findstr "TTL="') do (
  set "ip=%%a"
)

php websocket.php %ip%

endlocal