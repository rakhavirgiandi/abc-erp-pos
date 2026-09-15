@extends('errors.layout')

@section('title', 'Halaman Tidak Ditemukan')

@section('icon')
<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
    <path stroke-linecap="round" d="M12 17h.008v.008H12V17Z" />
</svg>
@endsection

@section('code', '404')
@section('title-text', 'Halaman Tidak Ditemukan')
@section('message', 'Menu atau halaman yang Anda cari tidak tersedia. Mungkin tautan sudah berubah atau transaksi ini tidak ada.')
