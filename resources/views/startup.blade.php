


@extends('companies.v1.layouts.guest.index')

@section('title', isset($title))

@section('style')
     <style>
        body {
            /* min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            user-select: none; */
        }

        .splash-card {
            width: 100%;
            max-width: 400px;
            border-radius: 16px;
            border: 1px solid #dee2e6;
            background: #fff;
        }

        .app-icon {
            width: 52px;
            height: 52px;
            background: #0d6efd;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .progress {
            height: 6px;
            border-radius: 99px;
            background: #e9ecef;
        }

        .progress-bar {
            transition: width 0.5s ease;
            border-radius: 99px;
        }
        .error-detail {
            font-family: monospace;
            font-size: 0.72rem;
            background: #fff3cd;
            border-radius: 6px;
            padding: 8px 10px;
            max-height: 90px;
            overflow-y: auto;
            white-space: pre-wrap;
            word-break: break-word;
            color: #664d03;
        }

        #main-content {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            user-select: none;
        }
    </style>
@endsection

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

  <div class="splash-card shadow-sm p-4">

        {{-- Header --}}
        <div class="d-flex align-items-center gap-3 mb-4">
            <div>
                <div class="fw-semibold text-dark lh-1 mb-1">{{ config('app.name') }}</div>
                <div class="text-muted" style="font-size:.72rem">v{{ config('app.version', '1.0.0') }}</div>
            </div>
        </div>

        {{-- Progress bar --}}
        <div class="progress mb-3">
            <div class="progress-bar bg-primary" id="progress-bar" style="width:8%"></div>
        </div>

        {{-- Satu baris keterangan --}}
        <div class="d-flex align-items-center gap-2 mb-4" style="min-height:24px">
            <span id="status-icon"></span>
            <span class="text-secondary" id="status-text" style="font-size:.82rem">Memeriksa service...</span>
        </div>

        {{-- Port changed notification --}}
        <div class="alert alert-success py-2 px-3 d-none mb-3" id="port-notif" style="font-size:.78rem">
            <i class="bi bi-arrow-left-right me-1"></i>
            <strong>Port berubah:</strong> <span id="port-notif-msg"></span>
        </div>

        {{-- Error panel --}}
        <div class="d-none" id="error-panel">
            <div class="alert alert-danger py-2 px-3 mb-2" style="font-size:.78rem">
                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                <strong>Gagal terhubung ke database</strong>
            </div>
            <div class="error-detail mb-3" id="error-msg"></div>
            <div class="d-flex gap-2">
                <button class="btn btn-primary btn-sm" onclick="retry()">
                    <i class="bi bi-arrow-clockwise me-1"></i>Coba Lagi
                </button>
                <button class="btn btn-outline-secondary btn-sm" onclick="openRepair()">
                    <i class="bi bi-tools me-1"></i>DB Repair
                </button>
            </div>
        </div>
</div>
@endsection

@section('script')

    <script>
        const statusUrl = "{{ route('startup.status') }}";
        const retryUrl  = "{{ route('startup.retry') }}";
        const csrf      = document.querySelector('meta[name="csrf-token"]').content;

        let pollTimer = null;

        // Urutan pesan yang berganti saat proses berjalan
        const steps = [
            { pct: 15, icon: '', text: 'Memeriksa service...' },
            { pct: 35, icon: '', text: 'Memulai database...' },
            { pct: 60, icon: '', text: 'Memeriksa port...' },
            { pct: 80, icon: '', text: 'Menghubungkan ke database...' },
        ];
        let stepIndex = 0;
        let stepTimer = null;

        function cycleStep() {
            stepIndex = (stepIndex + 1) % steps.length;
            const s = steps[stepIndex];
            setStatus(s.icon, s.text, s.pct);
        }

        function setStatus(iconType, text, pct) {
            const iconEl = document.getElementById('status-icon');
            const textEl = document.getElementById('status-text');
            const bar    = document.getElementById('progress-bar');

            if (iconType === 'check') {
                iconEl.innerHTML = '<i class="bi bi-check-circle-fill text-success" style="font-size:.9rem"></i>';
            } else if (iconType === 'error') {
                iconEl.innerHTML = '<i class="bi bi-x-circle-fill text-danger" style="font-size:.9rem"></i>';
            }

            textEl.textContent = text;

            if (pct !== undefined) {
                bar.style.width = pct + '%';
                bar.className   = 'progress-bar ' + (pct >= 100 ? 'bg-success' : pct < 0 ? 'bg-danger' : 'bg-primary');
            }
        }

        function stopCycling() { clearInterval(stepTimer); stepTimer = null; }
        function startCycling() {
            stopCycling();
            stepTimer = setInterval(cycleStep, 1800);
        }

        function startPolling() {
            stopPolling();
            checkStatus();
            pollTimer = setInterval(checkStatus, 1500);
        }

        function stopPolling() { clearInterval(pollTimer); pollTimer = null; }

        async function checkStatus() {
            try {
                const res  = await fetch(statusUrl);
                const data = await res.json();
                handle(data, res.ok);
            } catch {
                setStatus('', 'Menunggu server...');
            }
        }

        function handle(data, ok) {
            if (!ok || data.status === 'error') {
                stopPolling();
                stopCycling();
                setStatus('error', 'Gagal terhubung ke database.', -1);
                document.getElementById('progress-bar').style.width = '100%';
                document.getElementById('progress-bar').className = 'progress-bar bg-danger';
                document.getElementById('error-msg').textContent = data.message || 'Terjadi kesalahan.';
                document.getElementById('error-panel').classList.remove('d-none');
                return;
            }

            if (data.status === 'starting') {
                return; // Biarkan cycling berjalan
            }

            if (data.status === 'ready') {
                stopPolling();
                stopCycling();

                if (data.port_changed) {
                    const notif = document.getElementById('port-notif');
                    document.getElementById('port-notif-msg').textContent =
                        `Port ${data.old_port} sudah dipakai, dialihkan ke port ${data.port}.`;
                    notif.classList.remove('d-none');
                }

                setStatus('check', 'Database siap!', 100);

                setTimeout(() => {
                    window.location.href = data.redirect;
                }, data.port_changed ? 2500 : 700);
            }
        }

        async function retry() {
            document.getElementById('error-panel').classList.add('d-none');
            document.getElementById('port-notif').classList.add('d-none');
            setStatus('', 'Mencoba ulang...', 8);
            startCycling();

            try {
                const res  = await fetch(retryUrl, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                });
                const data = await res.json();
                handle(data, res.ok);
                if (data.status === 'starting') startPolling();
            } catch {
                setStatus('error', 'Tidak dapat terhubung ke server.');
            }
        }

        function openRepair() {
            alert('Silakan jalankan:\n\nABCPOS-DBRepair.exe\n\nJalankan sebagai Administrator, lalu klik "Coba Lagi".');
        }

        // Init
        startCycling();
        startPolling();
    </script>
@endsection