@echo off
cd /d "%~dp0"
where php >nul 2>nul || set "PATH=%PATH%;%LOCALAPPDATA%\Microsoft\WinGet\Packages\PHP.PHP.8.3_Microsoft.Winget.Source_8wekyb3d8bbwe"
echo Starting Quadrant website at http://localhost:8000  (close this window to stop)
start "" http://localhost:8000
php -S localhost:8000 server.php
pause
