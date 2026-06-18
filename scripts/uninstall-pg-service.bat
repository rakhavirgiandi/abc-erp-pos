@echo off
:: ============================================================================
:: uninstall-pg-service.bat
:: Hentikan dan hapus Windows Service PostgreSQL
:: Jalankan sebagai Administrator
:: ============================================================================

set "SERVICE_NAME=NativePHPPostgres"

echo.
echo ============================================================
echo  Uninstall PostgreSQL Windows Service
echo ============================================================
echo.
echo Service: %SERVICE_NAME%
echo.
echo PERINGATAN: Ini hanya menghapus Windows Service.
echo Data di storage\pgsql\data TIDAK akan dihapus.
echo.
set /p CONFIRM="Lanjutkan? (y/n): "
if /i "%CONFIRM%" neq "y" (
    echo Dibatalkan.
    pause
    exit /b 0
)

echo.
echo [1/2] Menghentikan service ...
sc stop %SERVICE_NAME% >nul 2>&1
timeout /t 3 /nobreak >nul

echo [2/2] Menghapus service ...
sc delete %SERVICE_NAME%

if %errorlevel% == 0 (
    echo.
    echo [OK] Service %SERVICE_NAME% berhasil dihapus.
) else (
    echo.
    echo [WARN] Gagal menghapus service, mungkin sudah tidak ada.
)

echo.
pause
