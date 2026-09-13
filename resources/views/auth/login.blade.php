


@extends('companies.v1.layouts.guest.index')

@section('title', $title)

@section('style')
    <style>
        body {
            background: url('{{ asset("assets/images/auth/cover_bg.png") }}') no-repeat center center;
            background-size: cover;
        }

        .login-overlay {
            min-height: calc(100vh - 38px);
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
            /* height: 70px; */
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
@endsection

@section('content')
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
                <a href="https://app.abcerp.id/forgot-password" class="text-decoration-none small" target="_blank">Lupa password?</a>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                Login
            </button>

            <div class="mt-2">
                {{-- <p class="mb-0">Belum Mempunyai Akun ? <a href="{{url('/register')}}" class="fw-medium text-primary"> Daftar </a> </p> --}}
            </div>
        </form>

        <div class="text-center text-muted mt-4 small">
            © <script>document.write(new Date().getFullYear())</script> ABC ERP <br>
            by <a href="https://alimrugicreative.biz/" target="_blank">ABC Group Teknologi Indonesia</a>
        </div>

    </div>
</div>
@endsection

@section('script')

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
                showLoading('Harap Menunggu!', 'Sedang memverifikasi kredensial Anda');
            },
            success: function(res) {
                if (res.access_token) {
                    setTimeout(function() {
                        Swal.fire({
                            title: "Success",
                            html: "Login Success!",
                            icon: "success"
                        }).then((result) => {
                            res.data.access_token = res.access_token;
                            let data = res.data;

                            $.ajax({
                                type: 'post',
                                url: BASE_URL + '/sessions',
                                data: JSON.stringify(data),
                                "headers": {
                                    'Content-Type': 'application/json'
                                },
                                cache: false,
                                contentType: false,
                                processData: false,
                                dataType: 'JSON',
                                success: function(res) {
                                    window.location.replace(BASE_URL + '/choose-company');
                                }
                            })
                        });
                    }, 500);
                } else {
                    Swal.fire({
                        title: "Gagal",
                        html: msg.message,
                        showConfirmButton: true,
                        confirmButtonColor: '#0760ef',
                        icon: "error"
                    });
                }
            },
            error: function(request, status, error) {
                Swal.fire({
                    title: "Gagal",
                    html: request.responseJSON.message,
                    showConfirmButton: true,
                    confirmButtonColor: '#0760ef',
                    icon: "error"
                });
            }
        })
    });

    $(document).on('click', '#menu-check-update', function () {
        Swal.fire({
            title: 'Memeriksa Update...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => Swal.showLoading(),
        });

        $.post(BASE_URL + '/native/updater/check')
            .then(() => pollUpdateStatus())
            .catch(() => {
                Swal.fire('Gagal', 'Tidak dapat memulai pengecekan update.', 'error');
            });
    });

    function pollUpdateStatus(maxAttempts = 30) {
        let attempts = 0;

        const interval = setInterval(function () {
            attempts++;

            $.get(BASE_URL + '/native/updater/status').then(function (status) {
                if (status.state === 'checking') {
                    if (attempts >= maxAttempts) {
                        clearInterval(interval);
                        Swal.fire('Timeout', 'Pengecekan update memakan waktu terlalu lama.', 'warning');
                    }
                    return;
                }

                clearInterval(interval);

                if (status.state === 'available') {
                    Swal.fire({
                        icon: 'info',
                        title: 'Update Tersedia',
                        html: `Versi <b>${status.version}</b> siap diunduh.` +
                              (status.releaseNotes ? `<br><br><small>${status.releaseNotes}</small>` : ''),
                        showCancelButton: true,
                        confirmButtonText: 'Update Sekarang',
                        cancelButtonText: 'Nanti',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            startDownload();
                        }
                    });
                } else if (status.state === 'not-available') {
                    Swal.fire('Sudah Terbaru', 'Aplikasi kamu sudah menggunakan versi terbaru.', 'success');
                } else if (status.state === 'error') {
                    Swal.fire('Gagal', status.message || 'Terjadi kesalahan saat memeriksa update.', 'error');
                }
            }).catch(function () {
                clearInterval(interval);
                Swal.fire('Gagal', 'Tidak dapat memeriksa status update.', 'error');
            });
        }, 1000);
    }

    function startDownload() {
        Swal.fire({
            title: 'Mengunduh Update...',
            html: 'Progress: <b>0%</b>',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => Swal.showLoading(),
        });

        $.post(BASE_URL + '/native/updater/download');

        const interval = setInterval(function () {
            $.get(BASE_URL + '/native/updater/status').then(function (status) {
                if (status.state === 'downloading') {
                    Swal.update({
                        html: `Progress: <b>${status.percent}%</b>`,
                    });
                } else if (status.state === 'downloaded') {
                    clearInterval(interval);
                    Swal.fire({
                        icon: 'success',
                        title: 'Update Siap Dipasang',
                        text: 'Restart aplikasi sekarang untuk memasang update?',
                        showCancelButton: true,
                        confirmButtonText: 'Restart Sekarang',
                        cancelButtonText: 'Nanti',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.post(BASE_URL + '/native/updater/install');
                        }
                    });
                } else if (status.state === 'error') {
                    clearInterval(interval);
                    Swal.fire('Gagal', status.message || 'Gagal mengunduh update.', 'error');
                }
            });
        }, 1000);
    }
</script>
@endsection