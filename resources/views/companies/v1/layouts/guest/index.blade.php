<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <title>Login - {{env('APP_NAME')}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" content="ABC ERP">
    <meta name="description" content="ABC ERP adalah solusi ERP terintegrasi untuk membantu bisnis mengelola operasional, keuangan, stok, dan laporan dalam satu platform.">
    <meta name="keywords" content="ERP, sistem ERP, manajemen bisnis, software akuntansi, stok, inventory">
    <meta name="author" content="ABC ERP">

    <link rel="shortcut icon" href="{{ asset('assets/images/logo-sm-new.ico')}}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/logo-sm-new.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/logo-sm-new.png')}}">

    <link href="{{ asset('assets/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/css/icons.min.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/css/app.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css')}}">
    <style>
        body {
            background: url('{{ asset("assets/images/auth/cover_bg.png") }}') no-repeat center center;
            background-size: cover;
            min-height: 100vh;
        }
    </style>
    @yield('style')
    @stack('style')
</head>
<script type="text/javascript">
    let BASE_URL = '{{ url('/') }}';
</script>
<body>
<x-desktop-header />
<meta name="csrf-token" content="{{ csrf_token() }}">

<div id="main-content">
    @yield('content')
</div>

@yield('script')
@stack('script')
</body>
</html>
