<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="horizontal" data-theme-mode="light" data-header-styles="light" data-menu-styles="dark" style="--primary-rgb: 160, 82, 45;">

<head>
    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="title" content="ABC ERP">
    <meta name="description" content="ABC ERP adalah solusi ERP terintegrasi untuk membantu bisnis mengelola operasional, keuangan, stok, dan laporan dalam satu platform.">
    <meta name="keywords" content="ERP, sistem ERP, manajemen bisnis, software akuntansi, stok, inventory">
    <meta name="author" content="ABC ERP">
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/logo-sm-new.ico')}}">
    

    <!-- Ikon perangkat seluler -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/logo-sm-new.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/logo-sm-new.png')}}">
    <link rel="manifest" href="{{ asset('assets/images/favicon/site.webmanifest') }}">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <!-- Ikon perangkat seluler (untuk Android) -->
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('assets/images/favicon/android-chrome-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('assets/images/favicon/android-chrome-512x512.png') }}">
 
     <!-- Main Theme Js -->
     <script src="{{ asset('assets/js/authentication-main.js') }}"></script>
 
     <!-- TITLE -->
     <title>Password Baru - {{env('APP_NAME')}} </title>
 
     <!-- BOOTSTRAP CSS -->
     <link id="style" href="{{ asset('assets/libs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
 
     <!-- STYLE CSS -->
     <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
 
     <!--- FONT-ICONS CSS -->
     <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">

     <link rel="stylesheet" href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}">

     <style>
        .login-img {
            background-image: url("{{ asset('assets/images/bg/bg.png') }}");
        }
     </style>
</head>
<script type="text/javascript">
    let BASE_URL = '{{ url('/') }}';
</script>
<body class="app sidebar-mini ltr login-img">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- BACKGROUND-IMAGE -->
    <div class="">

        <!-- PAGE -->
        <div class="page">
            <div class="container-lg">
                <div class="row justify-content-center mt-4 mx-0">
                    <div class="col-xl-4 col-lg-6">
                        <div class="card shadow-lg">
                            <div class="card-body p-sm-6">
                                <div class="text-center my-1">
                                    <a href="{{url('/')}}"><img src="{{ asset('assets/images/logo/mandep-horizontal.png') }}" class="header-brand-img" alt="" height="50px"></a>
                                </div>
                                <div class="text-center mb-4">
                                    <p>Silahkan masukkan email anda untuk mereset password</p>
                                </div>
                                <form id="main-form">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="mb-3">
                                                <label class="mb-2 fw-500">Password Baru<span class="text-danger ms-1">*</span></label>
                                                <input class="form-control ms-0" type="password" id="password" name="password" placeholder="Silahkan masukkan password Anda">
                                            </div>

                                            <div class="mb-3">
                                                <label class="mb-2 fw-500">Konfirmasi Password Baru<span class="text-danger ms-1">*</span></label>
                                                <input class="form-control ms-0" type="password" id="confirm_password" name="confirm_password" placeholder="Silahkan masukkan password Anda">
                                            </div>
                                        </div>
                                        <div class="col-xl-12">
                                            <div class="d-grid mb-3">
                                                <button class="btn btn-primary btn-block" type="submit">Rubah Password Saya</button>
                                            </div>
                                            <div class="text-center">
                                                <p class="mb-0 tx-14">Belum mempunyai akun?
                                                    <a href="{{url('/register')}}" class="tx-primary ms-1 text-decoration-underline">Daftar</a>
                                                </p>
                                            </div>
                                            <div class="text-center">
                                                <p class="mb-0 tx-14">Sudah Punya Akun?
                                                    <a href="{{url('/login')}}" class="tx-primary ms-1 text-decoration-underline">Login</a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- CONTAINER CLOSED -->
        </div>
        <!-- End PAGE -->
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

    <script type="text/javascript">
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		let code = '{{$code}}';

		$('form#main-form').submit(function(e) {
		    e.preventDefault();

		    var form_data = new FormData(this);

		    $.ajax({
		        type: 'post',
		        url: BASE_URL + '/api/new_passwords/'+code,
		        data: form_data,
		        cache: false,
		        contentType: false,
		        processData: false,
		        dataType: 'json',
		        beforeSend: function() {
		          Swal.fire({
		              title: 'Harap Menunggu',
		              html: 'Mengirim data anda',
		              didOpen: () => {
		                  Swal.showLoading();
		              }
		          });
		        },
		        success: function(res) {
		            if (res.status == 'success') {
	                    Swal.fire({
	                        title: "Sukses",
	                        text: "Password anda berhasil di reset, silahkan login",
	                        icon: "success"
	                    }).then((result) => {
	                    	window.location.replace(BASE_URL + '/login');
	                    });
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
    </script>
</body>

</html>