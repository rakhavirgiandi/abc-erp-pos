; =============================================================================
; build/installer.nsh
; Custom NSIS script untuk NativePHP + PostgreSQL
; Di-include oleh Electron Builder ke dalam installer utama
; =============================================================================

; Macro ini dipanggil SETELAH semua file ter-copy ke destination
!macro customInstall
    DetailPrint "Menyiapkan database PostgreSQL..."

    ; --- Tentukan path ---
    ; $INSTDIR = folder instalasi (misal: C:\Program Files\ABCPOS)
    ; $APPDATA = C:\Users\<user>\AppData\Roaming

    StrCpy $0 "$INSTDIR\resources\pgsql\bin\pg_ctl.exe"
    StrCpy $1 "$APPDATA\ABCPOS\storage\pgsql\data"
    StrCpy $2 "ABCPOSPostgreSQL"

    ; --- Buat folder storage jika belum ada ---
    CreateDirectory "$APPDATA\ABCPOS"
    CreateDirectory "$APPDATA\ABCPOS\storage"
    CreateDirectory "$APPDATA\ABCPOS\storage\pgsql"

    ; --- Cek apakah service sudah terdaftar ---
    ; Query service, jika exit code 0 berarti sudah ada
    nsExec::ExecToLog 'sc query "$2"'
    Pop $R0  ; exit code
    ${If} $R0 == 0
        DetailPrint "Service $2 sudah terdaftar, skip registrasi."
        Goto done_register
    ${EndIf}

    ; --- Register PostgreSQL sebagai Windows Service ---
    ; Installer sudah berjalan sebagai Administrator (perMachine: true)
    ; jadi pg_ctl register langsung berhasil tanpa UAC tambahan
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
    DetailPrint "Setup database selesai."
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
    Sleep 3000  ; tunggu 3 detik

    ; Hapus service
    DetailPrint "Menghapus service $2..."
    nsExec::ExecToLog 'sc delete "$2"'
    Pop $R0
    ${If} $R0 == 0
        DetailPrint "Service $2 berhasil dihapus."
    ${Else}
        DetailPrint "Service $2 tidak ditemukan atau sudah dihapus."
    ${EndIf}

    ; Catatan: data di $APPDATA\ABCPOS\storage\pgsql\data TIDAK dihapus
    ; agar data user tetap aman saat uninstall
    DetailPrint "Catatan: Data database di $APPDATA\ABCPOS\storage tidak dihapus."
!macroend