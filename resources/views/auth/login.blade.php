


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
                Swal.fire({
                    title: 'Harap Menunggu!',
                    html: 'Sedang memverifikasi kredensial Anda',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => Swal.showLoading(),
                });
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

    $(function () {

        let progressSwalOpen = false;
        let updateAvailable = false;
        let updateVersion = null;

        if (!window.Native) {
            return;
        }

        // =========================
        // UPDATE AVAILABLE
        // =========================

        window.Native.on(
            'Native\\Desktop\\Events\\AutoUpdater\\UpdateAvailable',
            function (payload) {

                updateAvailable = true;
                updateVersion = payload.version ?? null;

                Swal.fire({
                    icon: 'info',
                    title: 'Update Tersedia',
                    text: 'Versi ' + (updateVersion ?? '') + ' tersedia.',
                    showCancelButton: true,
                    confirmButtonColor: 'var(--bs-success)',
                    cancelButtonColor: 'var(--bs-danger)',
                    confirmButtonText: 'YA',
                    cancelButtonText: 'NANTI',
                    allowOutsideClick: false
                }).then((result) => {

                    if (result.isConfirmed) {

                        $.post(
                            BASE_URL + '/native/updater/download'
                        ).fail(function (request) {

                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text:
                                    request.responseJSON?.message ??
                                    'Gagal memulai download update.'
                            });

                        });

                    } else {
                        console.log(
                            'Update tersedia:',
                            updateVersion
                        );
                    }
                });
            }
        );


        // =========================
        // DOWNLOAD PROGRESS
        // =========================

        window.Native.on(
            'Native\\Desktop\\Events\\AutoUpdater\\DownloadProgress',
            function (payload) {

                const percent = Math.round(
                    payload.percent ?? 0
                );

                if (!progressSwalOpen) {

                    progressSwalOpen = true;

                    Swal.fire({
                        title: 'Mengunduh Versi Terbaru...',
                        html:
                            'Progress: <b id="progress-percentage">0%</b>',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                }

                $('#progress-percentage')
                    .text(percent + '%');
            }
        );


        // =========================
        // UPDATE DOWNLOADED
        // =========================

        window.Native.on(
            'Native\\Desktop\\Events\\AutoUpdater\\UpdateDownloaded',
            function (payload) {

                progressSwalOpen = false;

                Swal.close();

                updateAvailable = false;

                Swal.fire({
                    icon: 'success',
                    title: 'Update Siap Dipasang',
                    text:
                        'Restart aplikasi sekarang untuk memasang ' +
                        'update versi ' +
                        (payload.version ?? '') +
                        '?',
                    showCancelButton: true,
                    confirmButtonText: 'Restart Sekarang',
                    cancelButtonText: 'Nanti',
                    allowOutsideClick: false
                }).then((result) => {

                    if (result.isConfirmed) {

                        $.post(
                            BASE_URL + '/native/update/install'
                        ).fail(function (request) {

                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal Memasang Update',
                                text:
                                    request.responseJSON?.message ??
                                    'Update gagal dipasang.'
                            });

                        });

                    } else {

                        console.log(
                            'Update sudah didownload dan menunggu install.'
                        );
                    }
                });
            }
        );


        // =========================
        // UPDATE ERROR
        // =========================

        window.Native.on(
            'Native\\Desktop\\Events\\AutoUpdater\\Error',
            function (payload) {

                progressSwalOpen = false;

                Swal.close();

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Update',
                    text:
                        payload.error ??
                        'Terjadi kesalahan saat memeriksa update.'
                });
            }
        );

    });
</script>
@endsection