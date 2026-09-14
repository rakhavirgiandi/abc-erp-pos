


@extends('companies.v1.layouts.guest.index')

@section('title', $title)

@section('style')
<link href="{{ asset('assets/libs/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css">
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
            width: 180px;
            height: 40px;
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

        /* .select2-container--default .select2-selection--single .select2-selection__clear {
            height: 0px !important;
            margin-right: 35px !important;
            padding-right: 0px !important;
            margin-top: -6px !important;
        } */

        input[readonly] {
            background-color: #e9ecef !important;
            cursor: not-allowed;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #aaa;
        }

        /* Custom Modal Transition */
        .modal.fade {
            transition: opacity 0.15s linear;
        }
        .modal.fade .modal-dialog {
            transform: scale(0.9) translateY(20px);
            transition: transform 0.15s ease-out; /* Smooth transition for closing */
        }
        .modal.show .modal-dialog {
            transform: scale(1) translateY(0);
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); /* Bouncy transition for opening */
        }
    </style>
@endsection

@section('content')
<script type="text/javascript">
	// let BASE_URL = '{{ env('APP_URL') }}';
	let TOKEN = 'Bearer {{Session::get('_access_token')}}';
	let EMAIL = '{{Session::get('_email')}}';
	let NAME = '{{Session::get('_name')}}';
	let PHONE = '{{Session::get('_phone')}}';
</script>
<meta name="csrf-token" content="{{ csrf_token() }}">

	<meta name="csrf-token" content="{{ csrf_token() }}" />
    <div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    
        <div class="w-100" style="max-width: 1100px;">
            <div class="card custom-card border-0 rounded-4 p-4">
    
                <!-- LOGO -->
                <div class="text-center">
                    <img src="{{ asset('assets/images/logo2.png') }}" class="login-logo">
                </div>
    
                <hr>
    
                <!-- HEADER -->
                <div class="d-flex justify-content-between align-items-start">

                    <!-- KIRI -->
                    <div>
                        <h5 class="mb-0">
                            Halo, <b>{{Session::get('_name')}}</b>
                        </h5>
                        <div class="text-muted small mb-1">{{Session::get('_email')}}</div>
                        <small class="text-muted fst-italic">
                            Please Select Your Company
                        </small>
                    </div>
                
                    <!-- KANAN -->
                    <div class="d-flex gap-2">
                        
                        @if (isset($_GET['is_setup_data']) && $_GET['is_setup_data'] == 'true')
                            <button class="btn btn-success btn-sm" id="new_company-btn">
                                <i class="fa fa-plus"></i> Create New Company
                            </button>
                        @endif
                
                        <a href="{{url('/logout')}}" class="btn btn-danger btn-sm">
                            <i class="fa fa-power-off"></i> Logout
                        </a>
                
                    </div>
                
                </div>
    
                <hr>

                <!-- SEARCH & FILTER -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="text-muted fw-bold small" id="company-count">
                        <i class="fa fa-spinner fa-spin me-1"></i> Loading...
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <div class="input-group input-group-sm" style="width: 200px;">
                            <input type="text" class="form-control" id="search-company" placeholder="Search company...">
                            <span class="input-group-text"><i class="fa fa-search text-muted"></i></span>
                        </div>

                        <div style="width: 150px;">
                            <select class="form-select form-select-sm" id="filter-company" style="width: 100%;">
                                <option value="all">All</option>
                                <option value="active">Active</option>
                                <option value="trial">Trial</option>
                                <option value="awaiting payment">Awaiting Payment</option>
                                <option value="post subscribe">Post Subscribe</option>
                            </select>
                        </div>

                        <button class="btn btn-light btn-sm border" id="refresh-btn" onclick="getUserCompanies()"><i class="fa fa-sync-alt"></i></button>
                    </div>
                </div>

                <!-- COMPANY LIST -->
                <div class="pe-2 custom-scrollbar" style="max-height: 400px; min-height: 180px; overflow-y: auto;">
                    <table class="table table-hover table-borderless align-middle mb-0" style="position: relative; border-bottom: 1px solid #dee2e6;">
                        <thead style="position: sticky; top: 0; z-index: 2;">
                            <tr>
                                <th class="text-dark small fw-semibold py-2 ps-3 position-sticky top-0 bg-white" style="border-bottom: 2px solid #dee2e6; z-index: 1;">COMPANY</th>
                                <th class="text-dark small fw-semibold py-2 position-sticky top-0 bg-white" style="border-bottom: 2px solid #dee2e6; z-index: 1;">STATUS</th>
                                <th class="text-dark small fw-semibold py-2 position-sticky top-0 bg-white" style="border-bottom: 2px solid #dee2e6; z-index: 1;">ACTIVE PERIOD</th>
                                <th class="py-2 position-sticky top-0 bg-white" style="border-bottom: 2px solid #dee2e6; z-index: 1;"></th>
                            </tr>
                        </thead>
                        <tbody id="company-content">
                        </tbody>
                    </table>
                </div>
    
            </div>
        </div>
        <!-- Detail Modal -->
        <div class="modal fade" id="detail-company-modal" tabindex="-1" aria-labelledby="detailCompanyModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header border-bottom-0 pb-0">
                        <h5 class="modal-title fw-bold" id="detailCompanyModalLabel">Company Detail</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-3 pb-4 px-4">
                        <table class="table table-borderless table-sm mb-0">
                            <tr>
                                <td class="text-muted py-1" style="width: 130px; white-space: nowrap;">Name</td>
                                <td class="py-1">: <span id="detail-name" class="fw-semibold text-dark">Company Name</span></td>
                            </tr>
                            <tr>
                                <td class="text-muted py-1">Email</td>
                                <td class="py-1">: <span id="detail-email">email@example.com</span></td>
                            </tr>
                            <tr>
                                <td class="text-muted py-1 align-middle">Status</td>
                                <td class="py-1 align-middle">: <span class="badge px-2 py-1 fw-medium" id="detail-status">Status</span></td>
                            </tr>
                            <tr>
                                <td class="text-muted py-1">Active Period</td>
                                <td class="py-1">: <span id="detail-expired" class="fw-semibold text-dark">-</span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="create_company-modal" tabindex="-1" aria-labelledby="createCompanyModalLabel">
            <div class="modal-dialog modal-md">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header border-bottom-0 pb-0">
                        <h5 class="modal-title fw-bold" id="createCompanyModalTitle">Create New Company</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="create_new_company-form">
                        <div class="modal-body pt-3 pb-4 px-4">
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-medium mb-1" for="input-company_name">Company Name</label>
                                <input class="form-control" type="text" name="company_name" id="input-company_name" placeholder="Enter company name" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-medium mb-1" for="input-company_city">Company City</label>
                                <select class="form-control" id="input-company_city" name="company_city" required>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-medium mb-1" for="input-period_accounting">Accounting Period</label>
                                <input type="month" class="form-control" id="input-period_accounting" name="period_accounting" value="{{ date('Y-m') }}" required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label text-muted small fw-medium mb-1">Accounting Standard</label>
                                <select class="form-control" id="input-accounting_standard" name="accounting_standard">
                                    <option value="general_company">General Company</option>
                                    <option value="non_profit_organization">Non-Profit Organization</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer border-top-0 pt-0 px-4 pb-4">
                            <button class="btn btn-outline-danger px-3" type="button" data-bs-dismiss="modal">Cancel</button>
                            <button class="btn btn-primary px-4" type="submit">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

  <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

  <!-- Layouts main js -->
  <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>

  <!-- Metimenu js -->
  <script src="{{ asset('assets/libs/metismenu/metisMenu.min.js') }}"></script>

  <!-- simplebar js -->
  <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>

  <script src="{{ asset('assets/libs/eva-icons/eva.min.js') }}"></script>

  <!-- Scroll Top init -->
  <script src="{{ asset('assets/js/scroll-top.init.js') }}"></script>
  <!-- slick-carousel js -->
  <script src="{{ asset('assets/libs/slick-carousel/slick/slick.min.js') }}"></script>
  <!-- select2 -->
  <script src="{{ asset('assets/libs/select2/js/select2.min.js')}}"></script>
  <!-- Sweet Alerts js -->
  <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js')}}"></script>
  <!-- Bootstrap datepicker -->
  <script src="{{ asset('assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
  <script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js')}}"></script>
  <script src="{{ asset('assets/libs/moment/dist/moment.min.js')}}"></script>
  <script src="{{ asset('assets/libs/accounting.min.js') }}"></script>
  <script src="{{ asset('assets/js/numeric-input/numeric-input.js') }}"></script>

    <script type="text/javascript">
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

    	getUserCompanies();
		getCitySearch('#input-company_city');

        $('#input-accounting_standard').select2({
            width: '100%',
            dropdownParent: $('#create_company-modal'),
            placeholder: 'Select Accounting Standard',
        });

		function getCitySearch(element, selectedValObject = []) {
            $(element).select2({
            	dropdownParent: $('#create_company-modal'),
                width: '100%',
                minimumInputLength: 3,
                minimumResultsForSearch: '',
                placeholder: 'Select Domicile City',
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
	        $('#company-content').stop(true, true).html('<tr><td colspan="4" class="text-center py-5"><div class="spinner-border text-primary" role="status"></div><div class="mt-2 text-muted small">Loading data...</div></td></tr>').hide().fadeIn(200);
	        $('#refresh-btn i').addClass('fa-spin');
	        $.ajax({
	            url: BASE_URL+"/api/user_companies?all=true",
	            type: 'GET',
	            headers: { 'Authorization': TOKEN },
	            dataType: 'JSON',
	            success: function(res, textStatus, jqXHR){
                    window.userCompanies = res;
	                $('#refresh-btn i').removeClass('fa-spin');
	                let html = '';

			        $.each(res, function(key, val) {
			        	let disabled = '';
			        	if (val.is_active == 0) {
			        		disabled = 'disabled';
			        	}

                        let displayStatus = 'Status';
                        if (val.status && val.status.name) {
                            displayStatus = val.status.name;
                        } else if (val.subscription && val.subscription.status) {
                            displayStatus = val.subscription.status;
                        }

                        let expDate = '-';
                        if (val.subscription && val.subscription.expired_at) {
                            expDate = new Date(val.subscription.expired_at).toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'});
                        } else if (val.subscription && val.subscription.finish_at) {
                            expDate = moment(val.subscription.finish_at).format('DD MMM YYYY');
                        }

                        let badgeClass = 'badge-label-danger';
			        	if (displayStatus == 'Subscribe' || displayStatus == 'Active' || displayStatus == 'ACTIVE') {
                            displayStatus = 'Active';
			        		badgeClass = 'badge-label-success';
                        } else if (displayStatus == 'Trial' || displayStatus == 'TRIAL') {
                            displayStatus = 'Trial';
                            badgeClass = 'badge-label-warning';
			        	} else if (displayStatus == 'Awaiting Payment') {
			        		badgeClass = 'badge-label-secondary';
                        } else if (displayStatus == 'Post Subscribe') {
                            badgeClass = 'badge-label-danger';
                        } else {
                            badgeClass = 'badge-label-danger';
                        }

                        html += '<tr class="company-row border-bottom" data-name="'+val.company.name.toLowerCase()+'">';
                        html += '    <td class="py-3 px-3">';
                        html += '        <div class="fw-semibold mb-1">'+val.company.name+'</div>';
                        html += '    </td>';
                        html += '    <td class="py-3 align-middle">';
                        html += '        <span class="badge fw-bold text-capitalize ' + badgeClass + '">' + displayStatus.toLowerCase() + '</span>';
                        html += '    </td>';
                        html += '    <td class="py-3 align-middle text-muted fw-bold">';
                        html += '        '+expDate;
                        html += '    </td>';
                        html += '    <td class="py-3 align-middle text-end px-3">';
                        html += '        <div class="d-flex justify-content-end align-items-center gap-2">';
                        html += '            <button type="button" class="btn btn-sm px-3" id="open-data" data-company_id="'+val.company_id+'" data-index="'+key+'" style="background-color: #23C560; color: white; border-radius: 4px; font-weight: 500; border: none;">';
                        html += '                Open Company';
                        html += '            </button>';
                        html += '            <div class="dropdown">';
                        html += '                <button class="btn btn-sm btn-link text-muted p-1" type="button" data-bs-toggle="dropdown" data-bs-boundary="window" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>';
                        html += '                <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="border: 1px solid #ced4da !important;">';
                        html += '                    <li><a class="dropdown-item d-flex align-items-center" href="#" id="detail-data" data-name="'+val.company.name+'" data-status="'+displayStatus.toLowerCase()+'" data-badge="'+badgeClass+'" data-expired="'+expDate+'" data-email="'+(val.company.email || EMAIL)+'"><i class="fa fa-info-circle me-2 text-primary"></i> Detail</a></li>';
                        @if (isset($_GET['is_setup_data']) && $_GET['is_setup_data'] == 'true')
                        html += '                    <li><a class="dropdown-item text-danger d-flex align-items-center" href="#" id="delete-data" data-company_id="'+val.company_id+'"><i class="fa fa-trash-alt me-2"></i> Delete Data</a></li>';
                        @endif
                        html += '                </ul>';
                        html += '            </div>';
                        html += '        </div>';
                        html += '    </td>';
                        html += '</tr>';
			        });

                    html += '<tr id="no-data-row" style="display: none;">';
                    html += '    <td colspan="4" class="text-center py-5 text-muted">';
                    html += '        <i class="fa fa-folder-open mb-2 fs-3"></i><br>';
                    html += '        No data found';
                    html += '    </td>';
                    html += '</tr>';

	                $('#company-content').stop(true, true).html(html).hide().fadeIn(300);
                    $('#filter-company').trigger('change');
	            },
	            error: function(jqXHR, textStatus, errorThrown){
	                $('#refresh-btn i').removeClass('fa-spin');
	                $('#company-content').html('<tr><td colspan="4" class="text-center py-4 text-danger">Failed to load data.</td></tr>');
	            },
	        });
	    }

        $(document).ready(function() {
            $('#filter-company').select2({
                minimumResultsForSearch: Infinity,
                width: '100%'
            });

            function filterCompanies() {
                let search = $('#search-company').val().toLowerCase();
                let status = $('#filter-company').val().toLowerCase();
                let visibleCount = 0;

                $('.company-row').each(function() {
                    let name = $(this).data('name') || '';
                    let badgeStatus = $(this).find('.badge').text().toLowerCase();

                    let matchSearch = name.includes(search);
                    let matchStatus = (status === 'all') || (badgeStatus === status);

                    if (matchSearch && matchStatus) {
                        $(this).show();
                        visibleCount++;
                    } else {
                        $(this).hide();
                    }
                });

                if (visibleCount === 0) {
                    $('#no-data-row').show();
                } else {
                    $('#no-data-row').hide();
                }

                let totalCount = $('.company-row').length;
                if (visibleCount === totalCount) {
                    $('#company-count').text(totalCount + ' Companies');
                } else {
                    $('#company-count').html(visibleCount + ' <span class="fw-normal">of ' + totalCount + ' Companies</span>');
                }
            }

            $('#search-company').on('keyup', filterCompanies);
            $('#filter-company').on('change', filterCompanies);
        });

        $(document).on('click', '#detail-data', function(e) {
            e.preventDefault();
            let name = $(this).data('name');
            let status = $(this).data('status');
            let badgeClass = $(this).data('badge');
            let expired = $(this).data('expired');
            let email = $(this).data('email');

            $('#detail-name').text(name);
            $('#detail-avatar').text(name.charAt(0).toUpperCase());
            $('#detail-email').text(email);
            $('#detail-expired').text(expired);

            let statusEl = $('#detail-status');
            statusEl.removeClass().addClass('badge px-2 py-1 text-capitalize fw-bold ' + badgeClass);
            statusEl.text(status);

            statusEl.removeAttr('style');

            $('#detail-company-modal').modal('show');
        });

		$(document).on("click", "button#open-data",function(e) {
			e.preventDefault();
			Swal.fire({
			  title: 'Please Wait',
			  html: 'Your data is being synchronized',
			  didOpen: () => {
			      Swal.showLoading();
			  }
			});
			let companyId = $(this).data('company_id');
            let index = $(this).data('index');
			let company = window.userCompanies[index];
            let userCompanies = window.userCompanies[index];

            let data = {};
            data.company_id = companyId;
            data.user_id = userCompanies.user_id;
            data.company_id = userCompanies.company_id;
            data.company = userCompanies.company;
            data.subscription = userCompanies.subscription;
            data.slug = userCompanies.slug;

            $.ajax({
                type: 'post',
                url: BASE_URL + '/open-database',
                data: JSON.stringify(data),
                headers: {
                    'Content-Type': 'application/json'
                },
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'JSON',
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
					                title: "Failed",
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
			                title: "Failed",
			                text: res.message,
			                showConfirmButton: true,
			                confirmButtonColor: '#0760ef',
			                icon: "error"
			            });

			            return true;
                	}
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
                        title: "Success",
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
		            headers: {
		                'Authorization': TOKEN
		            },
		            data: formData,
		            cache: false,
		            contentType: false,
		            processData: false,
		            dataType: 'json',
		            beforeSend: function() {
		                showLoading('Please Wait!', 'Saving data...');
		            },
		            success: function(res) {
		                Swal.close();
		                showAlertOnSubmit(res, '', '', '{{url()->current()}}');
		            }
		        });
		    });
	    @endif

		$(document).on("click", "#delete-data",function(e) {
			e.preventDefault();
			let companyId = $(this).data('company_id');

			showDeletePopup(BASE_URL+'/api/companies/'+companyId, companyId, '', '', '');
		});

        function showDeletePopup(url, companyId, modal, table, reload) {
            Swal.fire({
                title: 'Are you sure you want to delete this data?',
                text: 'Data cannot be recovered once deleted!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Delete!',
                cancelButtonText: 'NO',
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
                            Swal.fire("Deleted!", data.message, "success").then(() => {
                                if (modal) {
                                    $(modal).modal('hide');
                                }
                                if (table) {
                                    $(table).DataTable().ajax.reload(null, false);
                                }
                                if (reload) {
                                    window.location.replace(reload);
                                } else {
                                    getUserCompanies();
                                }
                            });
                        } else {
                            Swal.fire("Failed", data.message, "error");
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
                title: "Failed",
                html: msg,
                showConfirmButton: true,
                confirmButtonColor: '#0760ef',
                icon: "error"
            });
        }
    </script>
@endsection