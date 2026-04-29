<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <title>Registrasi Akun - {{env('APP_NAME')}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" content="ABC ERP">
    <meta name="description" content="ABC ERP adalah solusi ERP terintegrasi untuk membantu bisnis mengelola operasional, keuangan, stok, dan laporan dalam satu platform.">
    <meta name="keywords" content="ERP, sistem ERP, manajemen bisnis, software akuntansi, stok, inventory">
    <meta name="author" content="ABC ERP">

    <link rel="shortcut icon" href="{{ asset('assets/images/logo-sm-new.png')}}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/logo-sm-new.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/logo-sm-new.png')}}">
    <!-- slick-carousel css -->
    <link rel="stylesheet" href="{{ asset('assets/libs/slick-carousel/slick/slick.css') }}">
    <!-- Bootstrap Datepicker -->
    <link href="{{ asset('assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <!-- Sweet Alert-->
    <link rel="stylesheet" href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css')}}">
    <!-- select2 -->
    <link href="{{ asset('assets/libs/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css">
    <!-- Simplebar Css -->
    <link rel="stylesheet" href="{{ asset('assets/libs/simplebar/simplebar.min.css') }}">
    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/css/bootstrap.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css">
    <!--icons css-->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css">
    <!-- App Css-->
    <link href="{{ asset('assets/css/app.css') }}" id="app-style" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/libs/flatpickr/flatpickr.min.css') }}">
    <style>
        body {
            background: url('{{ asset("assets/images/auth/cover_bg.png") }}') no-repeat center center;
            background-size: cover;
            min-height: 100vh;
        }

        .register-overlay {
            /* background: rgba(0, 0, 0, 0.45); */
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .register-card {
            width: 100%;
            border-radius: 16px;
            padding: 32px;
            background: #fff;
            box-shadow: 0 20px 40px rgba(0,0,0,.15);
        }

        .register-logo {
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

<div class="register-overlay">
    <div class="col-md-7">
        <div class="register-card">
            <div class="text-center mb-4">
                <img src="{{ asset('assets/images/logo2.png') }}" class="register-logo">
                <p class="text-muted mb-0">Silahkan <b>Daftar</b> Untuk Bisa Masuk ke ABC ERP</p>
            </div>
            <form id="main-form">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="form-label">Nama Anda</label>
                            <input type="text" class="form-control" id="input-name" placeholder="Nama Anda..." name="name" tabindex="1">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Perusahaan</label>
                            <input type="text" class="form-control" id="input-company_name" name="company[name]" placeholder="Nama Perusahaan..." tabindex="2">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" id="input-email" placeholder="Email Anda..." name="email" tabindex="3">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" id="input-password" placeholder="Masukkan Password Anda..." name="password" tabindex="5">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Domisili Perusahaan</label>
                            <select class="form-control" id="input-company_city" name="company[city]" tabindex="7" required>
                                <option value=""> Pilih </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">No. HP</label>
                            <input type="text" class="form-control" id="input-phone" placeholder="Masukkan No. HP Anda..." name="phone" tabindex="4">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password_confirm" id="input-password_confirm" placeholder="Masukkan Password Anda..." tabindex="6">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jumlah Cabang</label>
                            <input class="form-control" type="text" id="input-number_of_branches" name="company[number_of_branches]" placeholder="Silahkan isikan jumlah Cabang" required tabindex="8">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="input-is_referral_code" tabindex="9">
                            <label class="form-check-label" for="input-is_referral_code">
                                Saya mempunyai Kode Referral.
                            </label>
                        </div>

                        <div class="mb-3" id="referral_code-row" style="display: none;">
                            <input class="form-control" type="text" id="input-referral_code" name="referral_code" placeholder="ABC001">
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="agreement" required tabindex="9">
                        <label class="form-check-label" for="agreement">
                            Saya menyetujui <a href="javascript:void(0);" class="fw-bold">Syarat dan Ketentuan</a> Aplikasi ABC ERP.</a>
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                    Daftar
                </button>

                <div class="mt-2">
                    <p class="mb-0">Sudah Mempunyai Akun ? <a href="{{url('/login')}}" class="fw-medium text-primary"> Login </a> </p>
                </div>
            </form>
            <div class="text-center text-muted mt-4 small">
                © <script>document.write(new Date().getFullYear())</script> ABC ERP <br>
                by <a href="https://alimrugicreative.biz/" target="_blank">ABC Group Teknologi Indonesia</a>
            </div>
        </div>
        
    </div>
</div>

<script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js')}}"></script>
<script src="{{ asset('assets/libs/select2/js/select2.min.js')}}"></script>
<script type="text/javascript">
    let refCode = '';
    let c = '{{isset($_GET['c']) ? $_GET['c'] : 0}}';

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    getCitySearch('#input-company_city');

    $(document).on('select2:open', () => {
        document.querySelector('.select2-search__field').focus();
    });

    function getCitySearch(element, selectedValObject = []) {
        $(element).select2({
            width: '100%',
            minimumInputLength: 3,
            minimumResultsForSearch: '',
            placeholder: 'Pilih Kota Domisili',
            delay: 300,
            ajax: {
                url: BASE_URL + "/api/reg_regencies?per_page=20",
                headers: {
                    
                },
                dataType: "json",
                type: "GET",
                data: function(params) {
                    var queryParameters = {
                        name: params.term
                    }
                    return queryParameters
                },
                processResults: function(data) {
                    return {
                        results: $.map(data.data, function(item) {
                            return {
                                text: item.name,
                                id: item.id
                            }
                        })
                    }
                }
            },
        });

        if (selectedValObject) {
            var preselectedValue = new Option(selectedValObject.text, selectedValObject.id, false, false);
            $(element).append(preselectedValue);
        }
    }

    $('form#main-form').submit(function(e) {
        e.preventDefault();

        refCode = $('#input-referral_code').val();
        
        var formData = new FormData(this);

        if (!refCode && c) {
            formData.append('referral_code', c);
        }

        $.ajax({
            type: 'post',
            url: BASE_URL + '/api/registrations',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            beforeSend: function() {
                showLoading('Harap Menunggu!', 'Sistem kami sedang menyiapkan database perusahaan anda');
            },
            success: function(res) {
                if (res.status == 'success') {
                    Swal.fire({
                        title: "Sukses",
                        text: "Silahkan Klik Ok Untuk Login!",
                        icon: "success"
                    }).then((result) => {
                        window.location.replace(BASE_URL + '/login');
                    });
                } else {
                    Swal.fire({
                        title: "Gagal",
                        html: res.message_html,
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

    $("#input-is_referral_code").on('change', function() {
        if($("#input-is_referral_code").is(':checked')) {
            $("#referral_code-row").show();
        } else {
            $("#referral_code-row").hide();
            $('#input-referral_code').val('');
        }
    });
</script>

</body>
</html>
