@echo off
REM ========================================
REM Import CSV Nasabah - Helper Script
REM ========================================

echo.
echo ========================================
echo  IMPORT DATA NASABAH MTH
echo ========================================
echo.

REM Check if file path is provided
if "%~1"=="" (
    echo ERROR: File CSV tidak ditemukan!
    echo.
    echo Cara menggunakan:
    echo   1. Drag dan drop file CSV ke file .bat ini
    echo   2. Atau jalankan: import-nasabah.bat "C:\path\to\file.csv"
    echo.
    pause
    exit /b 1
)

REM Check if file exists
if not exist "%~1" (
    echo ERROR: File tidak ditemukan: %~1
    echo.
    pause
    exit /b 1
)

REM Get file info
for %%A in ("%~1") do (
    set "filename=%%~nxA"
    set "filesize=%%~zA"
)

REM Convert bytes to MB
set /a filesizeMB=%filesize% / 1048576

echo File: %filename%
echo Ukuran: %filesizeMB% MB
echo Path: %~1
echo.

REM Navigate to project directory
cd /d "%~dp0"

echo Memulai import...
echo.
echo ----------------------------------------

REM Run the import command
php artisan import:nasabah "%~1"

echo ----------------------------------------
echo.

if %errorlevel% equ 0 (
    echo.
    echo ✅ Import berhasil!
    echo.
) else (
    echo.
    echo ❌ Import gagal! Error code: %errorlevel%
    echo.
    echo Cek file log: storage\logs\laravel.log
    echo.
)

pause
