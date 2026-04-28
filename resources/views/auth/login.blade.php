<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <title>ABC ERP LOGIN</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="{{ asset('assets/images/logo-sm.png')}}">

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

        .login-overlay {
            /* background: rgba(0, 0, 0, 0.45); */
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            border-radius: 16px;
            padding: 32px;
            background: #fff;
            box-shadow: 0 20px 40px rgba(0,0,0,.15);
        }

        .login-logo {
            width: 200px;
            height: 70px;
        }

        .btn-primary {
            background: #1F2933;
            border-color: #1F2933;
        }

        .btn-primary:hover {
            background: #1F2933;
            border-color: #1F2933;
        }
    </style>

</head>
<script type="text/javascript">
    let BASE_URL = '{{ url('/') }}';
</script>
<body>

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="login-overlay">
    <div class="login-card">

        <div class="text-center mb-4">
            <img src="{{ asset('assets/images/logo2.png') }}" class="login-logo">
            <p class="text-muted mb-0">Silakan login untuk melanjutkan</p>
        </div>

        <form id="form-login">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="name@company.com" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="********" required>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="rememberMe">
                    <label class="form-check-label" for="rememberMe">
                        Remember me
                    </label>
                </div>
                <a href="#" class="text-decoration-none small">Lupa password?</a>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                Login
            </button>
        </form>

        <div class="text-center text-muted mt-4 small">
            © <script>document.write(new Date().getFullYear())</script> ABC ERP <br>
            by ABC Group Teknologi Indonesia
        </div>

    </div>
</div>

<script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js')}}"></script>
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('form#form-login').submit(function(e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            type: 'post',
            url: BASE_URL + '/api/login',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            beforeSend: function() {
                showLoading('Harap Menunggu!', 'Sedang memproses data');
            },
            success: function(res) {
                if (res.access_token) {
                    setTimeout(function() {
                        Swal.fire({
                            title: "Success",
                            text: "Login Success!",
                            icon: "success"
                        }).then((result) => {
                            res.data.access_token = res.access_token;
                            let data = res.data;

                            console.log(res.access_token);
                            

                            $.ajax({
                                type: 'post',
                                url: BASE_URL + '/authorizes',
                                data: JSON.stringify(data),
                                "headers": {
                                    'Content-Type': 'application/json'
                                },
                                cache: false,
                                contentType: false,
                                processData: false,
                                dataType: 'JSON',
                                success: function(res) {
                                    window.location.replace(BASE_URL + '/pos/cashier');
                                }
                            })
                        });
                    }, 500);
                } else {
                    Swal.fire({
                        title: "Gagal",
                        text: msg.message,
                        showConfirmButton: true,
                        confirmButtonColor: '#0760ef',
                        icon: "error"
                    });
                }
            },
            error: function(request, status, error) {
                Swal.fire({
                    title: "Gagal",
                    text: request.responseJSON.message,
                    showConfirmButton: true,
                    confirmButtonColor: '#0760ef',
                    icon: "error"
                });
            }
        })
    });

    function showLoading(title = i18n?.alert?.info?.processing?.title, message = i18n?.alert?.info?.processing?.text, timer = 0) {
        Swal.fire({
            title: title,
            html: message,
            didOpen: () => {
                Swal.showLoading();
            },
            timer: timer
        });
    }
</script>

</body>
</html>
