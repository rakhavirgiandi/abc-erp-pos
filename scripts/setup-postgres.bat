@echo off
:: ============================================================================
:: setup-postgres.bat
:: Script untuk download dan setup PostgreSQL portable ke dalam project
:: Jalankan sekali saat setup project baru
:: ============================================================================

setlocal EnableDelayedExpansion

set "SCRIPT_DIR=%~dp0"
set "PROJECT_ROOT=%SCRIPT_DIR%.."
set "PGSQL_DIR=%PROJECT_ROOT%\pgsql"

echo.
echo ============================================================
echo  NativePHP + PostgreSQL Portable Setup
echo ============================================================
echo.

:: Cek apakah pgsql\bin\postgres.exe sudah ada
if exist "%PGSQL_DIR%\bin\postgres.exe" (
    echo [OK] PostgreSQL binary sudah ada di pgsql\bin\
    echo      Tidak perlu setup ulang.
    goto :check_env
)

echo [INFO] PostgreSQL belum ada di folder pgsql\
echo.
echo Download PostgreSQL portable dari EDB (EnterpriseDB):
echo   https://www.enterprisedb.com/download-postgresql-binaries
echo.
echo Pilih versi untuk Windows x86-64, lalu:
echo.
echo   1. Ekstrak ZIP yang didownload
echo   2. Dari hasil ekstrak, copy folder-folder berikut ke pgsql\ :
echo        pgsql\bin\    ^<-- semua .exe dan .dll
echo        pgsql\lib\    ^<-- library files
echo        pgsql\share\  ^<-- locale, timezone data
echo.
echo   Struktur yang diperlukan:
echo   pgsql\
echo   ├── bin\
echo   │   ├── postgres.exe
echo   │   ├── initdb.exe
echo   │   ├── pg_ctl.exe
echo   │   ├── createdb.exe
echo   │   ├── psql.exe
echo   │   └── ... (dan DLL-nya)
echo   ├── lib\
echo   └── share\
echo.

set /p CONTINUE="Apakah folder pgsql\ sudah diisi? (y/n): "
if /i "!CONTINUE!" neq "y" (
    echo Silakan download dan setup dulu, lalu jalankan script ini lagi.
    pause
    exit /b 1
)

:check_env
echo.
echo [CHECK] Memeriksa file .env ...
if not exist "%PROJECT_ROOT%\.env" (
    echo [INFO] .env belum ada, membuat dari .env.example ...
    copy "%PROJECT_ROOT%\.env.example" "%PROJECT_ROOT%\.env"
    echo [OK] .env dibuat. Silakan sesuaikan konfigurasi jika perlu.
) else (
    echo [OK] .env sudah ada.
)

echo.
echo [CHECK] Memeriksa storage\pgsql\data ...
if not exist "%PROJECT_ROOT%\storage\pgsql\data" (
    mkdir "%PROJECT_ROOT%\storage\pgsql\data"
    echo [OK] Folder storage\pgsql\data dibuat.
) else (
    echo [OK] Folder storage\pgsql\data sudah ada.
)

echo.
echo [INFO] Menginstall Composer dependencies ...
cd /d "%PROJECT_ROOT%"
call composer install --no-dev --optimize-autoloader

echo.
echo [INFO] Generate app key ...
php artisan key:generate

echo.
echo ============================================================
echo  Setup selesai!
echo ============================================================
echo.
echo Untuk menjalankan app pertama kali (perlu Administrator):
echo   php artisan pgsql:service install
echo.
echo Atau langsung jalankan NativePHP:
echo   php artisan native:serve
echo.
echo Catatan: Pertama kali, jalankan sebagai Administrator
echo agar bisa mendaftar Windows Service.
echo ============================================================
echo.
pause
