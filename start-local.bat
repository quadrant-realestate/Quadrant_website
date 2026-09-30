@echo off
cd /d "%~dp0"
echo Starting Quadrant website at http://localhost:8000  (close this window to stop)
start "" http://localhost:8000
php -S localhost:8000 server.php
