@extends('errors.layout')

@section('title', 'Terjadi Kesalahan Server')

@section('icon')
<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9.303 3.376c.866 1.5-.217 3.374-1.948 3.374H4.645c-1.73 0-2.813-1.874-1.948-3.374L10.652 4.5c.866-1.5 3.032-1.5 3.898 0l7.753 12.626ZM12 15.75h.007v.008H12v-.008Z" />
</svg>
@endsection

@section('code', '500')
@section('title-text', 'Terjadi Kesalahan Server')
@section('message', 'Sistem kasir mengalami gangguan teknis. Transaksi yang sedang berjalan mungkin belum tersimpan. Silakan hubungi admin atau coba lagi.')
