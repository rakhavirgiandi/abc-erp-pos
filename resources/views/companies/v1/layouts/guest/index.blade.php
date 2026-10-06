@extends('layouts.main')

@section('main-style')

<style>
    body {
        background: url('{{ asset("assets/images/authentication_bg.png") }}') no-repeat center center;
        background-size: cover;
    }

    .authentication-overlay {
        min-height: calc(100vh - 38px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .authentication-card {
        width: 100%;
        max-width: 420px;
        border-radius: 16px;
        padding: 32px;
        background: #fff;
        box-shadow: 0 20px 40px rgba(0,0,0,.15);
    }

    .authentication-logo {
        width: 200px;
    }
</style>

@yield('style')

@endsection
@section('main-content')

@yield('content')

@endsection
@section('main-script')

@yield('script')

@endsection