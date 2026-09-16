!include "LogicLib.nsh"

!define PG_PORT_START "1933"

!macro customInstall
    DetailPrint "Menyiapkan database PostgreSQL..."

    StrCpy $0 "$INSTDIR\resources\build\pgsql\bin\pg_ctl.exe"
    StrCpy $1 "$APPDATA\ABCPOS\storage\pgsql\data"
    StrCpy $2 "ABCPOSPostgreSQL"

    CreateDirectory "$APPDATA\ABCPOS"
    CreateDirectory "$APPDATA\ABCPOS\storage"
    CreateDirectory "$APPDATA\ABCPOS\storage\pgsql"

    ; Deteksi port kosong
    StrCpy $7 "${PG_PORT_START}"

    find_port:
        nsExec::ExecToStack 'cmd /c netstat -an | findstr /C:":$7 "'
        Pop $R0
        Pop $R1
        ${If} $R0 == 0
            IntOp $7 $7 + 1
            ${If} $7 > 65000
                MessageBox MB_ICONSTOP "Tidak ada port tersedia."
                Abort
            ${EndIf}
            Goto find_port
        ${EndIf}

    DetailPrint "Menggunakan port: $7"

    ; Simpan semua info ke registry
    ; Dibaca oleh PostgresWindowsService saat app pertama dibuka
    WriteRegStr HKLM "Software\ABCPOS" "InstallPath" "$INSTDIR"
    WriteRegStr HKLM "Software\ABCPOS" "PgBinPath"   "$INSTDIR\resources\build\pgsql\bin"
    WriteRegStr HKLM "Software\ABCPOS" "DataPath"    "$1"
    WriteRegStr HKLM "Software\ABCPOS" "ServiceName" "$2"
    WriteRegStr HKLM "Software\ABCPOS" "PgPort"      "$7"

    ; TIDAK register service di sini — data directory belum ada
    ; Service akan di-register otomatis saat app pertama dibuka
    ; via PostgresWindowsService::registerServiceIfNeeded() dengan UAC elevation

    DetailPrint "Info instalasi disimpan. Service akan dikonfigurasi saat app dibuka pertama kali."
!macroend


!macro customUnInstall
    DetailPrint "Menghapus service PostgreSQL..."

    StrCpy $2 "ABCPOSPostgreSQL"

    nsExec::ExecToLog 'sc stop "$2"'
    Sleep 3000
    nsExec::ExecToLog 'sc delete "$2"'
    Pop $R0
    ${If} $R0 == 0
        DetailPrint "Service $2 berhasil dihapus."
    ${Else}
        DetailPrint "Service $2 tidak ditemukan atau sudah dihapus."
    ${EndIf}

    DeleteRegKey HKLM "Software\ABCPOS"

    DetailPrint "Data di $APPDATA\ABCPOS\storage tidak dihapus."
!macroend
