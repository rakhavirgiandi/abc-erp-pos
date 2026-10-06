; =============================================================================
; build/installer.nsh
; Custom NSIS script untuk NativePHP + PostgreSQL
; Di-include oleh Electron Builder ke dalam installer utama
; =============================================================================

!include "LogicLib.nsh"

!define PG_PORT_START "5433"   ; Port awal pencarian

; Macro ini dipanggil SETELAH semua file ter-copy ke destination
!macro customInstall
    DetailPrint "Menyiapkan database PostgreSQL..."

    ; --- Tentukan path ---
    StrCpy $0 "$INSTDIR\resources\pgsql\bin\pg_ctl.exe"
    StrCpy $1 "$APPDATA\ABCPOS\storage\pgsql\data"
    StrCpy $2 "ABCPOSPostgreSQL"

    ; --- Buat folder storage jika belum ada ---
    CreateDirectory "$APPDATA\ABCPOS"
    CreateDirectory "$APPDATA\ABCPOS\storage"
    CreateDirectory "$APPDATA\ABCPOS\storage\pgsql"

    ; =========================================================
    ; Deteksi port kosong mulai dari PG_PORT_START
    ; =========================================================
    StrCpy $7 "${PG_PORT_START}"

    find_port:
        ; netstat findstr: exit 0 = port dipakai, exit 1 = port kosong
        nsExec::ExecToStack 'cmd /c netstat -an | findstr /C:":$7 "'
        Pop $R0
        Pop $R1

        ${If} $R0 == 0
            DetailPrint "Port $7 sudah dipakai, mencoba berikutnya..."
            IntOp $7 $7 + 1
            ${If} $7 > 65000
                MessageBox MB_ICONSTOP "Tidak ada port yang tersedia untuk PostgreSQL."
                Abort
            ${EndIf}
            Goto find_port
        ${EndIf}

    DetailPrint "Menggunakan port: $7"

    ; Simpan port ke registry — dibaca oleh PostgresWindowsService saat app buka
    WriteRegStr HKLM "Software\ABCPOS" "PgPort" "$7"

    ; =========================================================
    ; Cek apakah service sudah terdaftar
    ; =========================================================
    nsExec::ExecToLog 'sc query "$2"'
    Pop $R0
    ${If} $R0 == 0
        DetailPrint "Service $2 sudah terdaftar, skip registrasi."
        Goto done_register
    ${EndIf}

    ; --- Register PostgreSQL sebagai Windows Service ---
    DetailPrint "Mendaftarkan service $2..."
    nsExec::ExecToLog '"$0" register -N "$2" -D "$1" -S auto -w'
    Pop $R0
    ${If} $R0 != 0
        DetailPrint "PERINGATAN: Gagal mendaftarkan service. App tetap bisa digunakan."
        DetailPrint "Coba jalankan app sekali sebagai Administrator."
    ${Else}
        DetailPrint "Service $2 berhasil didaftarkan."
    ${EndIf}

    done_register:
    DetailPrint "Setup database selesai. Port: $7"
!macroend


; Macro ini dipanggil SEBELUM file dihapus saat uninstall
!macro customUnInstall
    DetailPrint "Menghapus service PostgreSQL..."

    StrCpy $2 "ABCPOSPostgreSQL"
    StrCpy $0 "$INSTDIR\resources\pgsql\bin\pg_ctl.exe"
    StrCpy $1 "$APPDATA\ABCPOS\storage\pgsql\data"

    ; Stop service dulu
    DetailPrint "Menghentikan service $2..."
    nsExec::ExecToLog 'sc stop "$2"'
    Sleep 3000

    ; Hapus service
    DetailPrint "Menghapus service $2..."
    nsExec::ExecToLog 'sc delete "$2"'
    Pop $R0
    ${If} $R0 == 0
        DetailPrint "Service $2 berhasil dihapus."
    ${Else}
        DetailPrint "Service $2 tidak ditemukan atau sudah dihapus."
    ${EndIf}

    ; Hapus registry key
    DeleteRegKey HKLM "Software\ABCPOS"

    DetailPrint "Catatan: Data database di $APPDATA\ABCPOS\storage tidak dihapus."
!macroend
