<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>@yield('title', 'Terjadi Kesalahan') | {{ config('app.name', 'ABC POS') }}</title>
    <link href="{{ asset('assets/css/bootstrap.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css">
    <style>
        html, body {
            height: 100%;
        }
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            padding: 1.5rem;
        }
        .error-wrap {
            max-width: 420px;
            width: 100%;
            text-align: center;
        }
        .error-code {
            font-weight: 700;
            font-size: 2.75rem;
            color: #212529;
            line-height: 1;
            margin-bottom: 0.75rem;
        }
        .error-title {
            font-weight: 600;
            font-size: 1.1rem;
            color: #212529;
            margin-bottom: 0.5rem;
        }
        .error-message {
            color: #6c757d;
            font-size: 0.95rem;
            margin-bottom: 1.75rem;
        }
        .action-row {
            display: flex;
            gap: 0.5rem;
        }
        .action-row > * {
            flex: 1;
        }
        .btn {
            padding: 0.65rem 1rem;
            font-size: 1rem;
        }
        .countdown {
            font-weight: 600;
        }
    </style>
</head>
<body>
    <x-desktop-header />
    <div class="error-wrap">
        <div class="error-code">@yield('code')</div>
        <div class="error-title">@yield('title-text')</div>
        <p class="error-message">@yield('message')</p>

        <div class="action-row">
            <a href="{{ $back_url }}" class="btn btn-text-primary text-decoration-none">
                Kembali
            </a>
        </div>
    </div>
    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    <script>
        let BASE_URL = '{{ env('APP_URL') }}';
    </script>
    @stack('script')
</body>
</html>
