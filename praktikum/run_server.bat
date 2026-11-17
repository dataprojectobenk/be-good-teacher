@echo off
title LAB CLASSROOM PRAKTIKUM RPL - LAN SERVER
cls
echo ========================================================
echo    LAB CLASSROOM PRAKTIKUM RPL - SERVER JARINGAN LOKAL
echo ========================================================
echo.
echo Sedang mendeteksi alamat IP komputer server ini di jaringan LAN:
echo.

for /f "tokens=4" %%a in ('route print^|find " 0.0.0.0"') do (
    set LOCAL_IP=%%a
)

echo Alamat IP Server Anda: %LOCAL_IP%
echo.
echo Beritahukan siswa di lab untuk membuka browser dan mengakses:
echo.
echo     >> http://%LOCAL_IP%:8080 <<
echo.
echo Atau dari komputer ini sendiri:
echo     >> http://localhost:8080 <<
echo.
echo Akun Guru / Pengawas Lab:
echo     Username : admin
echo     Password : admin123
echo.
echo ========================================================
echo Tekan Ctrl + C untuk mematikan server.
echo ========================================================
echo.

cd /d "%~dp0"
php -S 0.0.0.0:8080
pause
