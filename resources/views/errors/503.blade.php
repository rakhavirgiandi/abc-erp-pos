@extends('errors.layout')

@section('title', 'Sedang Pemeliharaan')

@section('icon')
<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" d="m11.42 15.17 2.496-3.03c.317-.384.74-.626 1.208-.766M6.75 8.25v-1.5a3 3 0 0 1 3-3h4.5a3 3 0 0 1 3 3v1.5M5.25 8.25h13.5A2.25 2.25 0 0 1 21 10.5v6.75A2.25 2.25 0 0 1 18.75 19.5H5.25A2.25 2.25 0 0 1 3 17.25V10.5a2.25 2.25 0 0 1 2.25-2.25Z" />
</svg>
@endsection

@section('code', '503')
@section('title-text', 'Sistem Sedang Pemeliharaan')
@section('message', 'Kami sedang melakukan pemeliharaan singkat untuk meningkatkan layanan. Halaman ini akan otomatis dimuat ulang.')

@push('extra-script')
<script>
    // Auto reload setiap 30 detik untuk cek apakah sistem sudah kembali normal
    let remaining = 30;
    const el = document.getElementById('pos-countdown');
    setInterval(() => {
        remaining -= 1;
        if (remaining <= 0) {
            window.location.reload();
        } else if (el) {
            el.textContent = remaining;
        }
    }, 1000);
</script>
@endpush

@section('message')
    Kami sedang melakukan pemeliharaan singkat untuk meningkatkan layanan.
    Halaman ini akan otomatis dimuat ulang dalam <span class="countdown" id="pos-countdown">30</span> detik.
@endsection
