@echo off
echo Starting Tency Multi-Tenant Server...
echo.
echo Main Application: http://localhost:8000
echo Tenant Stores: http://[store-name].localhost:8000
echo.
echo Press Ctrl+C to stop the server
echo.
php -S 0.0.0.0:8000 server.php
