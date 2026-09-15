@extends('errors.layout')

@section('title', 'Terlalu Banyak Permintaan')

@section('icon')
<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008v.008H12v-.008ZM21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
</svg>
@endsection

@section('code', '429')
@section('title-text', 'Terlalu Banyak Permintaan')
@section('message', 'Sistem menerima terlalu banyak permintaan dalam waktu singkat. Tunggu sebentar sebelum mencoba transaksi berikutnya.')
