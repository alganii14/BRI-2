@echo off
REM ========================================
REM Import All CSV Files from Folder
REM ========================================

echo.
echo ========================================
echo  IMPORT SEMUA FILE CSV DARI FOLDER MTH
echo ========================================
echo.

REM Navigate to project directory
cd /d "%~dp0"

REM Check if folder argument provided, otherwise use default MTH folder
set "folder=%~1"
if "%folder%"=="" (
    set "folder=MTH"
)

REM Check if folder exists
if not exist "%folder%" (
    echo ERROR: Folder tidak ditemukan: %folder%
    echo.
    echo Cara menggunakan:
    echo   1. Jalankan langsung: import-folder.bat
    echo      ^(akan membaca folder MTH secara default^)
    echo.
    echo   2. Dengan folder custom: import-folder.bat "C:\path\to\folder"
    echo.
    pause
    exit /b 1
)

REM Count CSV files in folder
set count=0
for %%f in ("%folder%\*.csv") do set /a count+=1

if %count%==0 (
    echo ERROR: Tidak ada file CSV di folder: %folder%
    echo.
    pause
    exit /b 1
)

echo Folder: %folder%
echo Total file CSV: %count%
echo.
echo ----------------------------------------
echo.

REM Run the import command
php artisan import:nasabah-folder "%folder%"

echo.
echo ----------------------------------------
echo.

if %errorlevel% equ 0 (
    echo.
    echo ✅ Import semua file berhasil!
    echo.
) else (
    echo.
    echo ❌ Ada file yang gagal diimport!
    echo.
    echo Cek detail di atas atau file log: storage\logs\laravel.log
    echo.
)

pause
