@extends('errors.layout')

@section('title', 'Sesi Kedaluwarsa')

@section('icon')
<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
</svg>
@endsection

@section('code', '419')
@section('title-text', 'Sesi Anda Sudah Kedaluwarsa')
@section('message', 'Halaman ini sudah terlalu lama terbuka tanpa aktivitas. Silakan muat ulang untuk melanjutkan transaksi.')

@section('extra-action')
<button type="button" class="btn btn-pos-primary" onclick="window.location.reload()">
    Muat Ulang Sekarang
</button>
@endsection
