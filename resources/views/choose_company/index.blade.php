<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-vertical-style="overlay" data-theme-mode="light" data-header-styles="light" data-menu-styles="light" data-toggled="close">

<head>

    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Daftar - {{env('APP_NAME')}} </title>
    <meta name="Description" content="Sakola Software Administrasi dan Keuangan Sekolah">
    <meta name="Author" content="ABC Group Teknologi Indonesia">
    <meta name="keywords" content="ABC, Aplikasi Sekolah, Software Sekolah, PT Alimrugi Bisnis Creative, ABC Group Teknologi Indonesia">
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/logo-sm-new.ico')}}">

    {{-- <script src="{{ asset('assets/js/authentication-main.js') }}"></script> --}}

    <!-- Bootstrap Css -->
    <link id="style" href="{{ asset('assets/libs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" >

    <!-- Style Css -->
    <link href="{{ asset('assets/css/app.css') }}" id="app-style" rel="stylesheet" type="text/css">

    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">


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

        .custom-card {
        box-shadow: 
            0 10px 25px rgba(0, 0, 0, 0.1),
            0 20px 60px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<script type="text/javascript">
	let BASE_URL = '{{ env('APP_URL') }}';
	let TOKEN = 'Bearer {{Session::get('_access_token')}}';
	let EMAIL = '{{Session::get('_email')}}';
	let NAME = '{{Session::get('_name')}}';
	let PHONE = '{{Session::get('_phone')}}';
</script>
<body class="authentication-background authenticationcover-background position-relative" id="particles-js">
	<meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    
        <div class="w-100" style="max-width: 1100px;">
            <div class="card custom-card border-0 rounded-4 p-4">
    
                <!-- LOGO -->
                <div class="text-center">
                    <img src="{{ asset('assets/images/logo2.png') }}" style="height:60px;">
                </div>
    
                <hr>
    
                <!-- HEADER -->
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="mb-1">
                            Halo <b>{{Session::get('_name')}} ({{Session::get('_email')}})</b>,
                        </h5>
                        <small class="text-muted fst-italic">
                            Silahkan Masuk ke Data Perusahaan Anda
                        </small>
                    </div>
    
                    <a href="{{url('/logout')}}" class="btn btn-danger btn-sm">
                        <i class="fa fa-power-off"></i> Logout
                    </a>
                </div>
    
                <hr>
    
                <!-- COMPANY LIST -->
                <div class="row justify-content-start">
                    <div class="row" id="company-content"></div>
                </div>
    
            </div>
        </div>
    
    </div>


    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>

    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('assets/libs/moment/dist/moment.min.js')}}"></script>

    <script type="text/javascript">
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

    	getUserCompanies();
		getCitySearch('#input-company_city');

		function getCitySearch(element, selectedValObject = []) {
            $(element).select2({
            	dropdownParent: $('#create_company-modal'),
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

	    function getUserCompanies() {
	        $.ajax({
	            url: BASE_URL+"/api/user_companies?all=true",
	            type: 'GET',
	            headers: { 'Authorization': TOKEN },
	            dataType: 'JSON',
	            success: function(res, textStatus, jqXHR){
                    window.userCompanies = res;
	                let html = '';
	                let badge = 'badge bg-warning';

			        $.each(res, function(key, val) {
			        	if (val.subscription.status == 'Subscribe') {
			        		badge = 'badge bg-success';
			        	} else if (val.subscription.status == 'Awaiting Payment') {
			        		badge = 'badge bg-secondary';
			        	} else if (val.subscription.status == 'Suspend') {
			        		badge = 'badge bg-danger';
			        	} else if (val.subscription.status == 'Not Active') {
			        		badge = 'badge bg-danger';
			        	}

                        html += '<div class="col-md-4">';
                        html += '    <div class="card border rounded-4 shadow-sm company-card">';
                        html += '        <div class="card-body">';
                        html += '            <div class="d-flex justify-content-between mb-3">';
                        html += '                <h6 class="fw-bold mb-0">'+val.company.name+'</h6>';
                        html += '                <span class="'+badge+'">'+val.subscription.status+'</span>';
                        html += '            </div>';
                        html += '            <hr>';
                        html += '            <div class="d-flex justify-content-between mb-3">';
                        html += '                <div>';
                        html += '                    <small class="text-muted">Berakhir Pada</small>';
                        html += '                    <div class="fw-semibold">'+moment(val.subscription.finish_at || '').format('DD MMM YYYY')+'</div>';
                        html += '                </div>';
                        html += '            </div>';
                        html += '            <hr>';
                        html += '            <button type="button" class="btn btn-light border w-100 text-warning fw-semibold" id="open-data" data-company_id="'+val.company_id+'" data-index="'+key+'">';
                        html += '                Masuk Perusahaan ▶';
                        html += '            </button>';
                        @if (isset($_GET['is_setup_data']) && $_GET['is_setup_data'] == 'true')
                        html += '            <button type="button" class="btn btn-danger border w-100 mt-2 text-light fw-semibold" id="delete-data" data-company_id="'+val.company_id+'" data-index="'+key+'">';
                        html += '               Hapus Perusahaan <i class="fas fa-trash-alt ms-2 fs-12 text-white"></i>';
                        html += '            </button>';
						@endif
                        html += '        </div>';
                        html += '    </div>';
                        html += '</div>';
			        });

	                $('#company-content').html(html);
	            },
                error: function(jqXHR, textStatus, errorThrown){
                    let html = '';

                    let res = JSON.parse(jqXHR.responseText);

                    html += '<div class="col-md-12">';
                    html += '    <div class="card border rounded-4 shadow-sm company-card">';
                    html += '        <div class="card-body bg-warning">';
                    html += '           <p class="fw-bold">'+res.message+'</p>'
                    html += '        </div>';
                    html += '    </div>';
                    html += '</div>';

                    $('#company-content').html(html);
                },
	        });
	    }

		$(document).on("click", "button#open-data",function(e) {
			e.preventDefault();
			Swal.fire({
			  title: 'Harap Menunggu',
			  html: 'Data anda sedang di sinkronkan',
			  didOpen: () => {
			      Swal.showLoading();
			  }
			});
			let companyId = $(this).data('company_id');
			let index = $(this).data('index');
            let data = window.userCompanies[index];

            let payload = {
                id: data.id,
                user_id: data.user_id,
                company_id: data.company_id,
                type: data.type,
                company: data.company,
                subscription: data.subscription,
                slug: data.slug
            };

            $.ajax({
                type: 'POST',
                url: BASE_URL + '/open-database',
                data: JSON.stringify(payload),
                contentType: 'application/json',
                processData: false,
                dataType: 'json',
                success: function(res) {
                	if (res.status == 'success') {
				        $.ajax({
				            url: BASE_URL+"/api/v1/migrations",
				            type: 'GET',
			                headers: {
			                    'Authorization': TOKEN,
								'company-id': companyId
			                },
				            dataType: 'JSON',
				            success: function(res, textStatus, jqXHR){
				           		window.location.replace(BASE_URL + '/pos/cashier');     
				            },
				            error: function(jqXHR, textStatus, errorThrown){
				            	let res = JSON.parse(jqXHR.responseText);
					            Swal.fire({
					                title: "Gagal",
					                text: res.message,
					                showConfirmButton: true,
					                confirmButtonColor: '#0760ef',
					                icon: "error"
					            });

					            return true;
				            },
				        });
                	} else {
			            Swal.fire({
			                title: "Gagal",
			                text: res.message,
			                showConfirmButton: true,
			                confirmButtonColor: '#0760ef',
			                icon: "error"
			            });

			            return true;
                	}
                },
                error: function(jqXHR, textStatus, errorThrown){
                    let res = JSON.parse(jqXHR.responseText);
                    Swal.fire({
                        title: "Gagal",
                        text: res.message,
                        showConfirmButton: true,
                        confirmButtonColor: '#0760ef',
                        icon: "error"
                    });

                    return true;
                },
            });
		});

		function showLoading(title, message) {
            Swal.fire({
                title: title,
                html: message,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }

        function showAlertOnSubmit(params, modal, table, reload, isBlank = false) {
            if (params.status == 'success') {
                setTimeout(function() {
                    Swal.fire({
                        title: "Sukses",
                        text: params.message,
                        icon: "success"
                    }).then((result) => {
                        if (modal) {
                            $(modal).modal('hide');
                        }
                        if (table) {
                            $(table).DataTable().ajax.reload(null, false);
                        }
                        if (reload) {
                            if (isBlank) {
                                window.open(reload, '_blank');
                            } else {
                                window.location.replace(reload);
                            }
                        }
                    });
                }, 200);
            } else {
                showFailedAlert(params.message);
            }
        }

	    $(document).on("click", "#new_company-btn",function() {
	        $('#create_company-modal').modal('show');
	    });

	    @if (isset($_GET['is_setup_data']) && $_GET['is_setup_data'] == 'true')
		    $('form#create_new_company-form').submit(function(e) {
		        e.preventDefault();
		        var formData = new FormData(this);
		        formData.append('email', EMAIL);
		        $.ajax({
		            type: 'post',
		            url: BASE_URL + '/api/companies',
		            "headers": {
		                'Authorization': TOKEN,
                        'company-id': COMPANY_ID
		            },
		            data: formData,
		            cache: false,
		            contentType: false,
		            processData: false,
		            dataType: 'json',
		            beforeSend: function() {
		                showLoading('Harap Menunggu!', 'Sedang menyimpan data');
		            },
		            success: function(res) {
		                Swal.close();
		                showAlertOnSubmit(res, '', '', '{{url()->current()}}');
		            }
		        });
		    });
	    @endif

		$(document).on("click", "button#delete-data",function(e) {
			e.preventDefault();
			let companyId = $(this).data('company_id');

			showDeletePopup(BASE_URL+'/api/companies/'+companyId, companyId, '', '', '{{url()->current()}}');
		});

        function showDeletePopup(url, companyId, modal, table, reload) {
            Swal.fire({
                title: 'Apakah Anda yakin menghapus data ini?',
                text: 'Data tidak dapat di kembalikan ketika dihapus!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Hapus!',
                cancelButtonText: 'TIDAK',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        "headers": {
                            'Authorization': TOKEN,
                        },
                        data: {
                            'reason': result.value
                        },
                        type: "DELETE"
                    })
                    .done(function(data) {
                        if (data.status == 'success') {
                            Swal.fire("Terhapus!", data.message, "success");
                            if (modal) {
                                $(modal).modal('hide');
                            }
                            if (table) {
                                $(table).DataTable().ajax.reload(null, false);
                            }
                            if (reload) {
                                window.location.replace(reload);
                            }
                        } else {
                            Swal.fire("Gagal", data.message, "error");
                        }
                    })
                    .fail(function(data) {
                        let errorRes = data.responseJSON;

                        showFailedAlert(errorRes.message);
                    });
                }
            })
        }

        function showFailedAlert(msg) {
            Swal.fire({
                title: "Gagal",
                html: msg,
                showConfirmButton: true,
                confirmButtonColor: '#0760ef',
                icon: "error"
            });
        }
    </script>
</body>

</html>