@php
    $layout_mode = 'default';
    
    if (Request::segment(1) === 'pos') {
        $layout_mode = 'pos';
        if (Request::segment(2) === 'settings') {
            $layout_mode = 'pos-settings';
        }
    }
@endphp

<!DOCTYPE html>
<html lang="en" data-layout="horizontal" data-content-width="boxed" data-bs-theme="light" data-sidebar-color="light" data-topbar-color="light" data-theme-colors="default" dir="ltr" data-mode="{{ $layout_mode }}">
<head>

    <meta charset="utf-8">
    <title>@yield('title') - {{ env('APP_NAME') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta content="SIMRS & SIMKLINIK" name="description">
    <meta content="ABC Grup Teknologi" name="author">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    
    <!-- layout setup -->
    <!-- <script type="module" src="assets/js/layout-setup.js"></script> -->
    
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/logo-sm-new.ico')}}">
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
    @if (Request::segment(1) == 'pos')
        <link href="{{ asset('assets/css/pos.css') }}" id="pos-style" rel="stylesheet" type="text/css">
    @endif

    <style>

        label.required:after {
          content:" *";
          color: red;
        }

        @media (min-width: 992px) {
            [data-layout=horizontal]
            .sidebar-left.horizontal-sidebar
            .sidebar-slide
            #sidebar-menu > ul li ul.sub-menu li ul.sub-menu:after {
                border-right-color: var(--bs-sidebar-bg-color); !important;
            }
        }

        @media (min-width: 992px) {
            [data-layout=horizontal]
            .sidebar-left.horizontal-sidebar
            .sidebar-slide
            #sidebar-menu > ul li ul.sub-menu li ul.sub-menu:after {
                border-right-color: var(--bs-sidebar-bg-color); !important;
            }
        }

        .table tbody tr td {
            padding: 15px 5px !important;
        }

        .table.table-summary tbody tr td {
            padding: 5px 5px !important;
        }

        td {
            vertical-align: middle;
        }

        .select2-container--default .select2-selection--single .select2-selection__clear {
            height: 0px !important;
            margin-right: 35px !important;
            padding-right: 0px !important;
            margin-top: -6px !important;
        }

        input[readonly] {
            background-color: #e9ecef !important;
            cursor: not-allowed;
        }
    </style>
    @yield('style')
</head>

<script type="text/javascript">
    let BASE_URL = '{{ env('APP_URL') }}';
    let TOKEN = 'Bearer {{Session::get('_access_token')}}';
    let USER_ID = '{{ Session::get('_id') }}';
    let ROLE_ID = '{{Session::get('_role_id')}}';
    let NAME = '{{Session::get('_name')}}';
    let USERNAME = '{{Session::get('_username')}}';
    let EMAIL = '{{Session::get('_email')}}';
    let PHONE = '{{Session::get('_phone')}}';
    let IS_ACCESS_TO_POS = '{{config('user_companies.is_access_to_pos')}}';
    let DEFAULT_BRANCH_ID = '{{Session::get('general_settings.default_branch')}}';
    let DEFAULT_BRANCH_NAME = '{{Session::get('general_settings.branch_name')}}';
    let DEFAULT_WAREHOUSE_ID = '{{Session::get('general_settings.default_warehouse')}}';
    let DEFAULT_WAREHOUSE_NAME = '{{Session::get('general_settings.warehouse_name')}}';
    let DEFAULT_PROJECT_ID = '{{Session::get('general_settings.default_project')}}';
    let DEFAULT_PROJECT_NAME = '{{Session::get('general_settings.project_name')}}';
    let DEFAULT_CURRENCY_ID = '{{Session::get('general_settings.default_currency')}}';
    let DEFAULT_CURRENCY_NAME = '{{Session::get('general_settings.currency_name')}}';
    let COMPANY_ID = '{{Session::get('_company_id')}}';
</script>

<body class="horizontal-layout">
    <!-- Begin page -->
    <div id="layout-wrapper">
        <!-- Start topbar -->
        @if (Request::segment(1) == 'pos' && Request::segment(2) == 'cashier')
            @include('companies.v1.layouts.pos.cashier.header')
        @elseif (Request::segment(1) == 'pos' && Request::segment(2) == 'settings')
            @include('companies.v1.layouts.pos.settings.header')
        @else
            @include('companies.v1.layouts.header')
        @endif
        <!-- End topbar -->
        <!-- ========== Left Sidebar Start ========== -->
        <!-- Left Sidebar End -->
        <div class="sidebar-backdrop" id="sidebar-backdrop"></div>
        <!-- ========== Left Sidebar Start ========== -->
       
        @if (Request::segment(1) == 'pos' && Request::segment(2) == 'cashier')
        @elseif (Request::segment(1) == 'pos' && Request::segment(2) == 'settings')
            @include('companies.v1.layouts.pos.settings.navbar')
        @elseif (Request::segment(1) == 'hrm')
            @include('companies.v1.layouts.navbar_hrm')
        @else
            @include('companies.v1.layouts.navbar')
        @endif
        <!-- Left Sidebar End -->
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                @if (Request::segment(1) == 'pos' && Request::segment(2) == 'cashier')
                    @yield('content')
                    @yield('modal')
                @elseif (Request::segment(1) == 'pos' && Request::segment(2) == 'settings')
                    @yield('content')
                    @yield('modal')
                @elseif(Request::segment(1) == 'hrm')
                    @include('companies.v1.layouts.navbar_hrm')
                    <div class="container-fluid">
                        @yield('content')
    
                        @yield('modal')
                    </div><!-- container-fluid -->
                @else
                    @include('companies.v1.layouts.navbar')
                    <div class="container-fluid">
                        @yield('content')
    
                        @yield('modal')
                    </div><!-- container-fluid -->
                @endif
            </div><!-- End Page-content -->

            <!-- Begin Footer -->
            @if (Request::segment(1) == 'pos' && Request::segment(2) == 'cashier')
            @include('companies.v1.layouts.pos.cashier.footer')
            @elseif (Request::segment(1) == 'pos' && Request::segment(2) == 'settings')
            @else
                @include('companies.v1.layouts.footer')
            @endif
            <!-- END Footer -->
            <!-- Begin scroll top -->
            <div class="progress-wrap" id="progress-scroll">
                <svg class="progress-circle" width="100%" height="100%" viewBox="-1 -1 102 102">
                    <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
                </svg>
            </div>
            <!-- END scroll top -->
        </div>
        <!-- end main content-->

    </div><!-- END layout-wrapper -->

    <!-- Bootstrap bundle js -->
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
    <!-- App js -->
    {{-- <script src="{{ asset('assets/js/app.js') }}"></script> --}}
    
    <script type="text/javascript">

        let Users = [];
        let Provinces = [];
        let Cities = [];
        let Districts = [];
        let Villages = [];
        let Countries = [];
        let Warehouses = [];
        let Units = [];
        let ContactGroups = [];
        let Taxes = [];
        let Products = [];
        let AccountingMasters = [];
        let SalesOrders = [];
        let PurchaseOrders = [];
        let PurchaseRequests = [];
        let Currencies = [];
        let Branches = [];
        let ProductionPhase = [];
        let Variants = [];
        let VariantOptions = [];
        let PurchaseQuotations = [];
        let PurchaseReceipts = [];
        let FixedAssetCategories = [];
        let FixedAssets = [];
        let fixedAssetSources = [];

        window.i18n = @json(__('language'));

        (function() {
            'use strict';
            
            // Fungsi toggle sidebar mobile
            function initMobileSidebar() {
                const sidebarBtn = document.getElementById('sidebar-btn');
                const body = document.body;
                
                // Klik tombol hamburger untuk buka/tutup sidebar
                if (sidebarBtn) {
                    sidebarBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        
                        // Cek apakah layar mobile (kurang dari 992px)
                        if (window.innerWidth < 992) {
                            body.classList.toggle('sidebar-enable');
                        }
                    });
                }
                
                // Auto close sidebar ketika klik di luar area sidebar
                document.addEventListener('click', function(e) {
                    const sidebar = document.querySelector('.sidebar-left');
                    
                    // Cek apakah yang diklik bukan tombol sidebar atau area sidebar
                    if (
                        window.innerWidth < 992 && 
                        !e.target.closest('#sidebar-btn') && 
                        !e.target.closest('.sidebar-left')
                    ) {
                        body.classList.remove('sidebar-enable');
                    }
                });
                
                // Auto close sidebar ketika window diresize ke desktop
                window.addEventListener('resize', function() {
                    if (window.innerWidth >= 992) {
                        body.classList.remove('sidebar-enable');
                    }
                });
            }
            
            // Jalankan fungsi setelah DOM ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initMobileSidebar);
            } else {
                initMobileSidebar();
            }
        })();

        $(document).ready(function() {
            $('#language-toggle').on('click', function() {
                var currentLang = '{{ App::getLocale() }}';
                var newLang = currentLang === 'id' ? 'en' : 'id';
                
                $.ajax({
                    url:BASE_URL + "/language/switch",
                    "headers": {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'Authorization': TOKEN,
                            'Content-Type': 'application/json'
                        },
                    type: 'POST',
                    data: JSON.stringify({
                        language: newLang
                    }),
                    contentType: 'application/json',
                    cache: false,
                    dataType: 'json',
                    success: function (res) {
                        Swal.close();
                        var currentUrl = window.location.href;
                        
                        showAlertOnSubmit(res, '', '',currentUrl);
                    },
                    error: function(xhr) {
                        console.error('Error switching language');
                    }
                });
            });
        });

        // const syncEndpoints = [
        //     '/api/sync/bank_accounts',
        //     '/api/sync/base_unit_conversions',
        //     '/api/sync/branches',
        //     '/api/sync/contacts',
        //     '/api/sync/contact_point_rules',
        //     '/api/sync/general_settings',
        //     '/api/sync/media',
        //     '/api/sync/products',
        //     '/api/sync/product_categories',
        //     '/api/sync/product_multi_prices',
        //     '/api/sync/product_skus',
        //     '/api/sync/product_sku_variants',
        //     '/api/sync/product_stock',
        //     '/api/sync/product_unit_conversions',
        //     '/api/sync/product_variants',
        //     '/api/sync/reward_points',
        //     '/api/sync/taxes',
        //     '/api/sync/units',
        //     '/api/sync/variants',
        //     '/api/sync/variant_options',
        //     '/api/sync/warehouses'
        // ];

        $('#btn-sync-all').on('click', function () {
            syncAll();
        });

        if ('{{ Request::segment(1) != 'pos' }}') {
                    $('#sync-modal-toggle').on('click', function (e) {
                        e.preventDefault();
                        $('#sync-modal').modal('show')
                    });
            
                    $(document).on('hidden.bs.modal', '#sync-modal', function () {
                        $('.sync-check').prop('checked', false);
                        $('#sync-all-check').prop('checked', false);
                    });
            
                    $(document).on('change', '.sync-check', function () {
                        const total = $('.sync-check').length;
                        const checked = $('.sync-check:checked').length;
            
                        $('#sync-all-check').prop('checked', total === checked);
                    });
            
                    $(document).on('change', '#sync-all-check', function () {
                        const $this = $(this);
                        $('.sync-check').each((idx, e) => {
                            $(e).prop('checked', $this.is(':checked'));
                        })
                    });
            
                    const productsSync = () => {
                        return $.ajax({
                                    url: BASE_URL+'/api/sync/products',
                                    method: 'GET',
                                    contentType: 'application/json',
                                    headers: { 'Authorization': TOKEN },
                                });
                    }
            
                    const productCategoriesSync = () => {
                        return  $.ajax({
                                    url: BASE_URL+'/api/sync/product_categories',
                                    method: 'GET',
                                    contentType: 'application/json',
                                    headers: { 'Authorization': TOKEN },
                                });
                    }
            
                    const productMultiPricesSync = () => {
                        return  $.ajax({
                                    url: BASE_URL+'/api/sync/product_multi_prices',
                                    method: 'GET',
                                    contentType: 'application/json',
                                    headers: { 'Authorization': TOKEN },
                                });
                    }
            
                    const productSkusSync = () => {
                        return  $.ajax({
                                    url: BASE_URL+'/api/sync/product_skus',
                                    method: 'GET',
                                    contentType: 'application/json',
                                    headers: { 'Authorization': TOKEN },
                                });
                    }
            
                    const productSkuVariantsSync = () => {
                        return  $.ajax({
                                    url: BASE_URL+'/api/sync/product_sku_variants',
                                    method: 'GET',
                                    contentType: 'application/json',
                                    headers: { 'Authorization': TOKEN },
                                });
                    }
            
                    const unitsSync = () => {
                        return  $.ajax({
                                    url: BASE_URL+'/api/sync/units',
                                    method: 'GET',
                                    contentType: 'application/json',
                                    headers: { 'Authorization': TOKEN },
                                });
                    }
            
                    const productUnitConversionsSync = () => {
                        return  $.ajax({
                                    url: BASE_URL+'/api/sync/product_unit_conversions',
                                    method: 'GET',
                                    contentType: 'application/json',
                                    headers: { 'Authorization': TOKEN },
                                });
                    }
            
                    const productVariantsSync = () => {
                        return  $.ajax({
                                    url: BASE_URL+'/api/sync/product_variants',
                                    method: 'GET',
                                    contentType: 'application/json',
                                    headers: { 'Authorization': TOKEN },
                                })
                    }
            
                    const variantsSync = () => {
                        return  $.ajax({
                                    url: BASE_URL+'/api/sync/variants',
                                    method: 'GET',
                                    contentType: 'application/json',
                                    headers: { 'Authorization': TOKEN },
                                });
                    }
            
                    const variantOptionsSync = () => {
                        return  $.ajax({
                                    url: BASE_URL+'/api/sync/variant_options',
                                    method: 'GET',
                                    contentType: 'application/json',
                                    headers: { 'Authorization': TOKEN },
                                });
                    }
            
                    const taxesSync = () => {
                        return  $.ajax({
                                    url: BASE_URL+'/api/sync/taxes',
                                    method: 'GET',
                                    contentType: 'application/json',
                                    headers: { 'Authorization': TOKEN },
                                });
                    } 
            
                    const baseUnitConversionsSync = () => {
                        return  $.ajax({
                                    url: BASE_URL+'/api/sync/base_unit_conversions',
                                    method: 'GET',
                                    contentType: 'application/json',
                                    headers: { 'Authorization': TOKEN },
                                });
                    }
            
                    const mediaSync = () => {
                        return  $.ajax({
                                    url: BASE_URL+'/api/sync/media',
                                    method: 'GET',
                                    contentType: 'application/json',
                                    headers: { 'Authorization': TOKEN },
                                });
                    }
            
                    const productStockSync = () => {
                        return $.ajax({
                            url: BASE_URL+'/api/sync/product_stock',
                            method: 'GET',
                            contentType: 'application/json',
                            headers: { 'Authorization': TOKEN },
                        });
                    }
            
                    const contactsSync = () => {
                        return  $.ajax({
                                    url: BASE_URL+'/api/sync/contacts',
                                    method: 'GET',
                                    contentType: 'application/json',
                                    headers: { 'Authorization': TOKEN },
                                });
                    }
            
                    const branchesSync = () => {
                        return  $.ajax({
                                    url: BASE_URL+'/api/sync/branches',
                                    method: 'GET',
                                    contentType: 'application/json',
                                    headers: { 'Authorization': TOKEN },
                                });
                    }
            
                    const warehousesSync = () => {
                        return  $.ajax({
                                    url: BASE_URL+'/api/sync/warehouses',
                                    method: 'GET',
                                    contentType: 'application/json',
                                    headers: { 'Authorization': TOKEN },
                                });
                    }
            
                    const rewardPointsSync = () => {
                        return  $.ajax({
                                    url: BASE_URL+'/api/sync/reward_points',
                                    method: 'GET',
                                    contentType: 'application/json',
                                    headers: { 'Authorization': TOKEN },
                                });
                    }
            
                    const contactPointRulesSync = () => {
                        return  $.ajax({
                                    url: BASE_URL+'/api/sync/contact_point_rules',
                                    method: 'GET',
                                    contentType: 'application/json',
                                    headers: { 'Authorization': TOKEN },
                                });
                    }
            
                    const generalSettingsSync = () => {
                        return  $.ajax({
                                    url: BASE_URL+'/api/sync/general_settings',
                                    method: 'GET',
                                    contentType: 'application/json',
                                    headers: { 'Authorization': TOKEN },
                                });
                    }
                    
                    const paymentMethodSync = () => {
                        return  $.ajax({
                                    url: BASE_URL+'/api/sync/bank_accounts',
                                    method: 'GET',
                                    contentType: 'application/json',
                                    headers: { 'Authorization': TOKEN },
                                });
                    }
            
                    const salesInvoicesSync = () => {
                        return  $.ajax({
                                    url: BASE_URL+'/api/sync/sales_invoices',
                                    method: 'POST',
                                    contentType: 'application/json',
                                    headers: { 'Authorization': TOKEN },
                                });
                    }
            
                    const accountingMastersSync = () => {
                        return  $.ajax({
                                    url: BASE_URL+'/api/sync/accounting_master',
                                    method: 'GET',
                                    contentType: 'application/json',
                                    headers: { 'Authorization': TOKEN },
                                });
                    }
            
                    const contactGroupsSync = () => {
                        return  $.ajax({
                                    url: BASE_URL+'/api/sync/contact_groups',
                                    method: 'GET',
                                    contentType: 'application/json',
                                    headers: { 'Authorization': TOKEN },
                                });
                    }
            
                    const processSync = async (params = {}) => {
            
                        function camelToSentence(str) {
                          return str
                            .replace(/([a-z])([A-Z])/g, '$1 $2')
                            .replace(/^./, s => s.toUpperCase());
                        }
            
                        const {
                            options = []
                        } = params || {}
            
                        const data = {
                            product: [
                                productsSync,
                                productCategoriesSync,
                                productMultiPricesSync,
                                productSkusSync,
                                productSkuVariantsSync,
                                unitsSync,
                                productUnitConversionsSync,
                                productVariantsSync,
                                variantsSync,
                                variantOptionsSync,
                                taxesSync,
                                baseUnitConversionsSync,
                                mediaSync
                            ],
                            transaction: [
                                salesInvoicesSync
                            ],
                            stock_product: [
                                productStockSync
                            ],
                            contact: [
                                contactsSync,
                                contactGroupsSync
                            ],
                            branch: [
                                branchesSync
                            ],
                            warehouse: [
                                warehousesSync
                            ],
                            reward_point_and_point_rule: [
                                rewardPointsSync,
                                contactPointRulesSync
                            ],
                            accounting_master: [
                                accountingMastersSync
                            ],
                            settings: [
                                generalSettingsSync
                            ],
                            payment_method: [
                                paymentMethodSync
                            ],
            
                        }
            
                        let allRequests = [];
            
                        let dataLength = 0;
                        let processLength = 0;
            
                        for (const item of options) {
                          const fetchGroup = data[item];
                          if (fetchGroup) {
                            dataLength += fetchGroup.length;
                          }
                        }
            
                        let errors = [];
            
                        for (const item of options) {
                            const fetchGroup = data[item];
                                
                            if (fetchGroup) {
                              for (const fn of fetchGroup) {
                                try {
                                    const result = await fn();
                                } catch (err) {
                                    errors.push(`Terjadi kesalahan ketika menjalankan ${camelToSentence(fn.name)}`);
                                } finally {
                                    processLength++;
                                    const progress = Math.round((processLength / dataLength) * 100);
                                    params?.processUpdated?.(progress);
                                }
                              }
                            }
                        }
            
                        if (params?.done || typeof params?.done === 'function') {
                            params.done(errors);
                        }
                    }
            
                    $(document).on('click', '#sync-submit-toggle', function () {
                         $('#sync-modal').modal('hide');
                        Swal.fire({
                            title: 'Mohon Tunggu',
                            html: `<div style="margin-bottom: .25rem;">Sedang memproses permintaan anda</div> <br> <div class="progress">
                                        <div class="progress-bar bg-secondary" id="sync-progress-bar" style="width: 0%"></div>
                                    </div>`,
                            showConfirmButton: false
                        });
                        
                        let data = [];
                        $('.sync-check').each((idx, e) => {
                            if ($(e).is(':checked')) {
                                data.push($(e).val());
                            }
                        });
            
                        if (data?.length > 0) {
                            processSync({
                                options: data,
                                processUpdated: (res) => {
                                    $('#sync-progress-bar').css('width', res+'%')
                                },
                                done: (errs) => {
                                    let errMessage = '';
            
                                    if (errs?.length > 0) {
                                        errs?.forEach((item, idx) => {
                                            errMessage += '<div style="margin-bottom: .25rem">'+item+'<div>';
                                        })
                                    }
            
                                    setTimeout(() => {
                                        Swal.fire({
                                            icon: (errs?.length > 0) ? 'warning' : 'success',
                                            title: 'Proses Selesai',
                                            html: (errs?.length > 0) ? errMessage : 'Proses sinkron berhasil'
                                        }).then((result) => {
                                            window.location.reload();
                                        });
                                    }, 1000)
                                }
                            })
                        }
                    });
        }
        

        async function syncAll() {
            const btn = $('#btn-sync-all');
            const icon = btn.find('i');

            btn.prop('disabled', true);
            icon.removeClass().addClass('mdi mdi-loading mdi-spin');

            try {
                await $.ajax({
                    url: '/api/sync',
                    method: 'POST',
                    headers: {
                        'Authorization': TOKEN,
                    },
                });

                showAlertOnSubmit({status:'success', message:'Successfully Sync!'}, '', '');

            } catch (error) {
                console.error('Sync error:', error);
                showAlertOnSubmit({status:'error', message:'Failed Sync!'}, '', '');
            }

            btn.prop('disabled', false);
            btn.html('<i class="mdi mdi-cloud-sync"></i>');
        }

        function resetAllInputOnForm(formId) {
            $(formId).find('input, textarea').val('');
            $(formId).find('input[type="checkbox"]').prop('checked', false);
            $(formId).find('input[type="radio"]').prop('checked', false);
            $(formId).find('select').each(function() {
                if ($(this).hasClass('select2-hidden-accessible')) {
                    $(this).select2('destroy').val("").select2({
                        // width: '100%',
                        dropdownParent: $(formId)
                    });
                } else {
                    $(formId).find('select').val("");
                }
            });
        }

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

        function findIndonesianMonthByNumber(numberMonth) {
            switch(parseInt(numberMonth)) {
                case 1:
                    return 'Januari';
                    break;
                case 2:
                    return 'Februari';
                    break;
                case 3:
                    return 'Maret';
                    break;
                case 4:
                    return 'April';
                    break;
                case 5:
                    return 'Mei';
                    break;
                case 6:
                    return 'Juni';
                    break;
                case 7:
                    return 'Juli';
                    break;
                case 8:
                    return 'Agustus';
                    break;
                case 9:
                    return 'September';
                    break;
                case 10:
                    return 'Oktober';
                    break;
                case 11:
                    return 'Nopember';
                    break;
                case 12:
                    return 'Desember';
                    break;
                default:
                    return '';
            }
        }

        function formatIndonesianDate(dateString) {
            if (!dateString) {
                return '';
            }

            var formattedDate = moment(dateString)
                .local()
                .format('DD-MM-YYYY');

            // Kapital huruf pertama
            return formattedDate.replace(/^./, function (char) {
                return char.toUpperCase();
            });
        }

        function showAlertOnSubmit(params, modal, table, reload, reloadBlank) {
            if (params.status == 'success') {
                setTimeout(function() {
                    Swal.fire({
                        title: '{{ __('language.alert.success.title') }}',
                        text: params.message,
                        icon: "success",
                        showConfirmButton: false,
                        timer: 1000
                    }).then((result) => {
                        if (modal) {
                            $(modal).modal('hide');
                        }
                        if (table) {
                            $(table).DataTable().ajax.reload(null, false);
                        }
                        if (reload) {
                            window.location.replace(reload);
                        }
                        if (reloadBlank) {
                            window.open(reloadBlank, '_blank');
                        }
                    });
                }, 200);
            } else {
                showAlertNotification(params.message, 'Gagal');
            }
        }

        function showFailedAlert(msg, selectedIcon = 'error', selectedTitle = 'Gagal') {
            Swal.fire({
                title: selectedTitle,
                html: msg,
                showConfirmButton: true,
                confirmButtonColor: 'var(--bs-success)',
                icon: selectedIcon
            });
        }

        function showAlertNotification(msg, title, icon = "error") {
            Swal.fire({
                title: title,
                showConfirmButton: true,
                confirmButtonColor: 'var(--bs-success)',
                icon: icon,
                html: msg
            });
        }

        function showPopupWithAction(title, subtitle, icon, method, data, url, modal, table, reload, callback = null) {
            Swal.fire({
                title: title,
                html: subtitle,
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: 'var(--bs-success)',
                cancelButtonColor: 'var(--bs-danger)',
                confirmButtonText: '{{ __('language.yes') }}',
                cancelButtonText: '{{ __('language.cancel') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        "headers": {
                            'Authorization': TOKEN,
                        },
                        data: data,
                        type: method,
                        beforeSend: function() {
                            showLoading(i18n?.alert?.info?.processing?.title, i18n?.alert?.info?.processing?.text);
                        },
                    })
                    .done(function(data) {
                        Swal.close();
                        if (data.status == 'success') {
                            Swal.fire({
                                title: '{{ __('language.ok') }}',
                                text: data.message,
                                icon: "success",
                                showConfirmButton: false,  
                                timer: 1500  
                            });
                            if (modal) {
                                $(modal).modal('hide');
                            }
                            if (table) {
                                $.each(table, function(index, item) {
                                    $(item).DataTable().ajax.reload(null, false);
                                })
                            }
                            if (typeof callback === 'function') {
                                callback(data);
                            }
                            if (reload) {
                                window.location.replace(reload);
                            }
                        } else {
                            Swal.fire(i18n?.errors?.title?.error, data.message, "error");
                        }
                    })
                    .fail(function(data) {
                        Swal.close();
                        let errorRes = data.responseJSON;
                        showAlertNotification(errorRes.message, i18n?.errors?.title?.error);
                    });
                }
            });
        }

        function generalAjaxErrorHandler(xhr, status, error) {
            Swal.close(); // Close any loading Swal
            let title = i18n?.errors?.title?.error;
            let message = i18n?.errors?.message?.general;
            let icon = 'error';

            if (status === 'timeout') {
                message = i18n?.errors?.message?.timeout;
            } else if (xhr.readyState === 0) {
                message = i18n?.errors?.message?.no_connection;
            } else {
                switch (xhr.status) {
                    case 0:
                        message = i18n?.errors?.message?.no_connection;
                        break;
                    case 400:
                        message = xhr.responseJSON?.message || i18n?.errors?.message?.bad_request;
                        break;
                    case 401:
                        title = i18n?.errors?.title?.unauthorized;
                        message = xhr.responseJSON?.message || i18n?.errors?.message?.unauthorized;
                        break;
                    case 403:
                        title = i18n?.errors?.title?.forbidden;
                        message = xhr.responseJSON?.message || i18n?.errors?.message?.forbidden;
                        break;
                    case 404:
                        title = i18n?.errors?.title?.not_found;
                        message = xhr.responseJSON?.message || i18n?.errors?.message?.not_found;
                        break;
                    case 419:
                        title = i18n?.errors?.title?.session_expired;
                        message = i18n?.errors?.message?.session_expired;
                        break;
                    case 422:
                        title = i18n?.errors?.title?.validation;
                        // Validation errors
                        const errors = xhr.responseJSON?.errors;
                        if (errors) {
                            message = Object.values(errors)
                                .flat()
                                .join('\n');
                        } else {
                            message = xhr.responseJSON?.message || i18n?.errors?.message?.validation;
                        }
                        break;
                    case 429:
                        message = i18n?.errors?.message?.too_many;
                        break;
                    case 500:
                        message = xhr.responseJSON?.message || i18n?.errors?.message?.server;
                        break;
                    case 503:
                        message = i18n?.errors?.message?.service_down;
                        break;
                    default:
                        message = xhr.responseJSON?.message || i18n?.errors?.message?.unexpected?.replace(':code', xhr.status);;
                }
            }

            if (xhr.status == 401) {
                Swal.fire({
                    title: title,
                    html: message,
                    icon: icon,
                    showCancelButton: false,
                    confirmButtonColor: 'var(--bs-info)',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = BASE_URL + '/logout'   
                    }
                });
            } else {
                showFailedAlert(message, icon, title);
            }

        }

        function getUsers(params, callback = null) {
            
            let element = params['element'],
                isAsync = params['is_async'] ?? true,
                selectedVal = params['selected_val'] ?? '',
                filter = params['filter'] ?? '';
            $.ajax({
                url: BASE_URL + "/api/users?all=true&order[id]=desc"+filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    Users = res;
                    if (element != '' && element != undefined && element != null) {
                        buildUsers(element, selectedVal);
                    }
                    callback && callback(Users);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                },
            });
        }

        function buildUsers(element, selectedVal = "") {
            let html = ''
            html += '<option value="">{{ __('language.placeholder.select', ["name" => __('language.user')]) }}</option>'
            $.each(Users, function(index, item) {
                if (selectedVal == item.id) {
                    html += '<option value="' + item.id + '" selected>' + item.name + '</option>'
                } else {
                    html += '<option value="' + item.id + '">' + item.name + '</option>'
                }
            })
            $(element).html(html)
        }

        function getUserSearch(params) {
            let element = params['element'],
                selectedValObject = params['selected_val_object'] ?? '',
                filter = params['filter'] ?? '',
                modal = params['modal'] ?? '',
                placeholderLabel = params['placeholder_label'] ?? '{{ __('language.placeholder.select', ["name" => __('language.user')]) }}',
                isTags = parseInt(params['is_tags'] ?? 0) === 1 ? true : false;

            $(element).select2({
                width: '100%',
                // minimumInputLength: 3,
                dropdownParent: modal,
                language: {
                    inputTooShort: function(val) {
                        return 'Ketikan minimal ' + val.minimum + ' karakter.';
                    }
                },
                tags: isTags,
                placeholder: placeholderLabel,
                selectionCssClass: "select2-sm",
                dropdownCssClass: "select2-sm",
                minimumResultsForSearch: '',
                ajax: {
                    url: BASE_URL + "/api/v1/users?per_page=10&order[id]=desc&" + filter,
                    headers: {
                        'Authorization': TOKEN,
                        'company-id': COMPANY_ID
                    },
                    dataType: "json",
                    type: "GET",
                    delay: 300,
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
                                    name: item.name,
                                    email: item.email,
                                    text: item.name,
                                    id: item.id
                                }
                            })
                        }
                    }
                },
            });

            if (selectedValObject) {
                $(element).empty();
                
                if (isTags && Array.isArray(selectedValObject)) {
                    selectedValObject.forEach(function(item) {
                        let option = new Option(item.text, item.id, true, true);
                        $(element).append(option);
                    });
                } else {
                    let preselectedValue = new Option(selectedValObject.text, selectedValObject.id, true, true);
                    $(element).append(preselectedValue);
                }
            }
        }

        function getCountries(params, callback = null) {
            
            let element = params['element'],
                isAsync = params['is_async'] ?? true,
                selectedVal = params['selected_val'] ?? '',
                filter = params['filter'] ?? '';
            $.ajax({
                url: BASE_URL + "/api/countries?all=true&order[id]=desc"+filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    Countries = res;
                    if (element != '' && element != undefined && element != null) {
                        buildCountries(element, selectedVal);
                    }
                    callback && callback(Countries);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                },
            });
        }

        function buildCountries(element, selectedVal = "") {
            let html = ''
            html += '<option value="">{{ __('language.placeholder.select', ["name" => __('language.country')]) }}</option>'
            $.each(Countries, function(index, item) {
                if (selectedVal == item.id) {
                    html += '<option value="' + item.id + '" selected>' + item.name + '</option>'
                } else {
                    html += '<option value="' + item.id + '">' + item.name + '</option>'
                }
            })
            $(element).html(html)
        }

        function getCountrySearch(params) {
            let element = params['element'],
                selectedValObject = params['selected_val_object'] ?? '',
                filter = params['filter'] ?? '',
                modal = params['modal'] ?? '',
                placeholderLabel = params['placeholder_label'] ?? '{{ __('language.placeholder.select', ["name" => __('language.country')]) }}',
                isTags = parseInt(params['is_tags'] ?? 0) === 1 ? true : false;

            $(element).select2({
                width: '100%',
                // minimumInputLength: 3,
                dropdownParent: modal,
                language: {
                    inputTooShort: function(val) {
                        return 'Ketikan minimal ' + val.minimum + ' karakter.';
                    }
                },
                tags: isTags,
                placeholder: placeholderLabel,
                selectionCssClass: "select2-sm",
                dropdownCssClass: "select2-sm",
                minimumResultsForSearch: '',
                ajax: {
                    url: BASE_URL + "/api/countries?per_page=10&order[id]=desc&" + filter,
                    headers: {
                        'Authorization': TOKEN,
                    },
                    dataType: "json",
                    type: "GET",
                    delay: 300,
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
                                    name: item.name,
                                    text: item.name,
                                    id: item.id
                                }
                            })
                        }
                    }
                },
            });

            if (selectedValObject) {
                $(element).empty();
                
                if (isTags && Array.isArray(selectedValObject)) {
                    selectedValObject.forEach(function(item) {
                        let option = new Option(item.text, item.id, true, true);
                        $(element).append(option);
                    });
                } else {
                    let preselectedValue = new Option(selectedValObject.text, selectedValObject.id, true, true);
                    $(element).append(preselectedValue);
                }
            }
        }

        function getAccountingMasters(params, callback = null) {
            
            let element = params['element'],
                isAsync = params['is_async'] ?? true,
                selectedVal = params['selected_val'] ?? '',
                filter = params['filter'] ?? '';
            $.ajax({
                url: BASE_URL + "/api/accounting_masters?all=true&order[coa]=asc&filter[is_active]=1"+filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    AccountingMasters = res;
                    if (element != '' && element != undefined && element != null) {
                        buildAccountingMasters(element, selectedVal);
                    }
                    callback && callback(AccountingMasters);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                },
            });
        }

        function buildAccountingMasters(element, selectedVal = "") {
            let html = ''
            html += '<option value="">{{ __('language.placeholder.select', ["name" => __('language.coa')]) }}</option>'
            $.each(AccountingMasters, function(index, item) {
                if (selectedVal == item.coa) {
                    html += '<option value="'+item.coa+'" data-id="'+item.id+'" data-name="'+item.name+'" selected>'+item.accounting_code+' | '+item.name+'</option>'
                } else {
                    html += '<option value="'+item.coa+'" data-id="'+item.id+'" data-name="'+item.name+'">'+item.accounting_code+' | '+item.name+'</option>'
                }
            })
            $(element).html(html)
        }

        function getAccountingMasterSearch(params) {
            let element = params['element'],
                selectedValObject = params['selected_val_object'] ?? '',
                filter = params['filter'] ?? '',
                modal = params['modal'] ?? '',
                placeholderLabel = params['placeholder_label'] ?? '{{ __('language.placeholder.select', ["name" => __('language.coa')]) }}',
                isTags = parseInt(params['is_tags'] ?? 0) === 1 ? true : false;

            $(element).select2({
                width: '100%',
                // minimumInputLength: 3,
                dropdownParent: modal,
                language: {
                    inputTooShort: function(val) {
                        return 'Ketikan minimal ' + val.minimum + ' karakter.';
                    }
                },
                tags: isTags,
                placeholder: placeholderLabel,
                selectionCssClass: "select2-sm",
                dropdownCssClass: "select2-sm",
                minimumResultsForSearch: '',
                ajax: {
                    url: BASE_URL + "/api/accounting_masters?per_page=10&order[coa]=asc&is_only_children=true&" + filter,
                    headers: {
                        'Authorization': TOKEN,
                    },
                    dataType: "json",
                    type: "GET",
                    delay: 300,
                    data: function(params) {
                        var queryParameters = {
                            or: {
                                coa: params.term,
                                name: params.term
                            }
                        }
                        return queryParameters
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data.data, function(item) {
                                return {
                                    accounting_code: item.accounting_code,
                                    name: item.name,
                                    text: item.accounting_code + ' | ' + item.name,
                                    id: item.coa
                                }
                            })
                        }
                    }
                },
            });

            if (selectedValObject) {
                $(element).empty();
                
                if (isTags && Array.isArray(selectedValObject)) {
                    selectedValObject.forEach(function(item) {
                        let option = new Option(item.text, item.id, true, true);
                        $(element).append(option);
                    });
                } else {
                    let preselectedValue = new Option(selectedValObject.text, selectedValObject.id, true, true);
                    $(element).append(preselectedValue);
                }
            }
        }

        function getProvinces(params, callback = null) {
            
            let element = params['element'],
                isAsync = params['is_async'] ?? true,
                selectedVal = params['selected_val'] ?? '',
                defaultKey = params['default_key'] ? params['default_key'] : 'id',
                filter = params['filter'] ?? '';
            $.ajax({
                url: BASE_URL + "/api/indonesia_provinces?all=true&order[id]=desc"+filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    Provinces = res;
                    if (element != '' && element != undefined && element != null) {
                        buildProvinces(element, selectedVal, defaultKey);
                    }
                    callback && callback(Provinces);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                },
            });
        }

        function buildProvinces(element, selectedVal = "", defaultKey) {
            let html = ''
            html += '<option value="">{{ __('language.placeholder.select', ["name" => __('language.province')]) }}</option>'
            $.each(Provinces, function(index, item) {
                if (selectedVal == item[defaultKey]) {
                    html += '<option value="' + item[defaultKey] + '" data-code="'+item.code+'" selected>' + item.name + '</option>'
                } else {
                    html += '<option value="' + item[defaultKey] + '" data-code="'+item.code+'">' + item.name + '</option>'
                }
            })
            $(element).html(html)
        }

        function getProvinceSearch(params) {
            let element = params['element'],
                selectedValObject = params['selected_val_object'] ?? '',
                filter = params['filter'] ?? '',
                modal = params['modal'] ?? '',
                placeholderLabel = params['placeholder_label'] ?? '{{ __('language.placeholder.select', ["name" => __('language.province')]) }}',
                isTags = parseInt(params['is_tags'] ?? 0) === 1 ? true : false;

            $(element).select2({
                width: '100%',
                // minimumInputLength: 3,
                dropdownParent: modal,
                language: {
                    inputTooShort: function(val) {
                        return 'Ketikan minimal ' + val.minimum + ' karakter.';
                    }
                },
                tags: isTags,
                placeholder: placeholderLabel,
                selectionCssClass: "select2-sm",
                dropdownCssClass: "select2-sm",
                minimumResultsForSearch: '',
                ajax: {
                    url: BASE_URL + "/api/indonesia_provinces?per_page=10&order[id]=desc&" + filter,
                    headers: {
                        'Authorization': TOKEN,
                    },
                    dataType: "json",
                    type: "GET",
                    delay: 300,
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
                                    name: item.name,
                                    code: item.code,
                                    text: item.name,
                                    id: item.id
                                }
                            })
                        }
                    }
                },
            });

            if (selectedValObject) {
                $(element).empty();
                
                if (isTags && Array.isArray(selectedValObject)) {
                    selectedValObject.forEach(function(item) {
                        let option = new Option(item.text, item.id, true, true);
                        $(element).append(option);
                    });
                } else {
                    let preselectedValue = new Option(selectedValObject.text, selectedValObject.id, true, true);
                    $(element).append(preselectedValue);
                }
            }
        }

        function getCities(params, callback = null) {
            let element = params['element'],
                isAsync = params['is_async'] ?? true,
                selectedVal = params['selected_val'] ?? '',
                defaultKey = params['default_key'] ? params['default_key'] : 'id',
                filter = params['filter'] ?? '';
            $.ajax({
                url: BASE_URL + "/api/indonesia_cities?all=true&order[id]=desc"+filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    Cities = res;
                    if (element != '' && element != undefined && element != null) {
                        buildCities(element, selectedVal, defaultKey);
                    }
                    callback && callback(Cities);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                },
            });
        }

        function buildCities(element, selectedVal = "", defaultKey) {
            let html = ''
            html += '<option value="">{{ __('language.placeholder.select', ["name" => __('language.city')]) }}</option>'
            $.each(Cities, function(index, item) {
                if (selectedVal == item[defaultKey]) {
                    html += '<option value="' + item[defaultKey] + '" data-code="'+item.code+'" data-province_id="'+item.province_id+'" selected>' + item.name + '</option>'
                } else {
                    html += '<option value="' + item[defaultKey] + '" data-code="'+item.code+'" data-province_id="'+item.province_id+'">' + item.name + '</option>'
                }
            })
            $(element).html(html)
        }

        function getCitySearch(params) {
            let element = params['element'],
                selectedValObject = params['selected_val_object'] ?? '',
                filter = params['filter'] ?? '',
                modal = params['modal'] ?? '',
                placeholderLabel = params['placeholder_label'] ?? '{{ __('language.placeholder.select', ["name" => __('language.city')]) }}',
                isTags = parseInt(params['is_tags'] ?? 0) === 1 ? true : false;

            $(element).select2({
                width: '100%',
                // minimumInputLength: 3,
                dropdownParent: modal,
                language: {
                    inputTooShort: function(val) {
                        return 'Ketikan minimal ' + val.minimum + ' karakter.';
                    }
                },
                tags: isTags,
                placeholder: placeholderLabel,
                selectionCssClass: "select2-sm",
                dropdownCssClass: "select2-sm",
                minimumResultsForSearch: '',
                ajax: {
                    url: BASE_URL + "/api/indonesia_cities?per_page=10&order[id]=desc&" + filter,
                    headers: {
                        'Authorization': TOKEN,
                    },
                    dataType: "json",
                    type: "GET",
                    delay: 300,
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
                                    province_id: item.province_id,
                                    name: item.name,
                                    code: item.code,
                                    text: item.name,
                                    id: item.id
                                }
                            })
                        }
                    }
                },
            });

            if (selectedValObject) {
                $(element).empty();
                
                if (isTags && Array.isArray(selectedValObject)) {
                    selectedValObject.forEach(function(item) {
                        let option = new Option(item.text, item.id, true, true);
                        $(element).append(option);
                    });
                } else {
                    let preselectedValue = new Option(selectedValObject.text, selectedValObject.id, true, true);
                    $(element).append(preselectedValue);
                }
            }
        }

        function getDistricts(params, callback = null) {
            
            let element = params['element'],
                isAsync = params['is_async'] ?? true,
                selectedVal = params['selected_val'] ?? '',
                defaultKey = params['default_key'] ? params['default_key'] : 'id',
                filter = params['filter'] ?? '';
            $.ajax({
                url: BASE_URL + "/api/indonesia_districts?all=true&order[id]=desc"+filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    Districts = res;
                    if (element != '' && element != undefined && element != null) {
                        buildDistricts(element, selectedVal, defaultKey);
                    }
                    callback && callback(Districts);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                },
            });
        }

        function buildDistricts(element, selectedVal = "", defaultKey) {
            let html = ''
            html += '<option value="">{{ __('language.placeholder.select', ["name" => __('language.district')]) }}</option>'
            $.each(Districts, function(index, item) {
                if (selectedVal == item[defaultKey]) {
                    html += '<option value="' + item[defaultKey] + '" data-code="'+item.code+'" data-city_code="'+item.city_code+'" selected>' + item.name + '</option>'
                } else {
                    html += '<option value="' + item[defaultKey] + '" data-code="'+item.code+'" data-city_code="'+item.city_code+'">' + item.name + '</option>'
                }
            })
            $(element).html(html)
        }

        function getDistrictSearch(params) {
            let element = params['element'],
                selectedValObject = params['selected_val_object'] ?? '',
                filter = params['filter'] ?? '',
                modal = params['modal'] ?? '',
                placeholderLabel = params['placeholder_label'] ?? '{{ __('language.placeholder.select', ["name" => __('language.district')]) }}',
                isTags = parseInt(params['is_tags'] ?? 0) === 1 ? true : false;

            $(element).select2({
                width: '100%',
                // minimumInputLength: 3,
                dropdownParent: modal,
                language: {
                    inputTooShort: function(val) {
                        return 'Ketikan minimal ' + val.minimum + ' karakter.';
                    }
                },
                tags: isTags,
                placeholder: placeholderLabel,
                selectionCssClass: "select2-sm",
                dropdownCssClass: "select2-sm",
                minimumResultsForSearch: '',
                ajax: {
                    url: BASE_URL + "/api/indonesia_districts?per_page=10&order[id]=desc&" + filter,
                    headers: {
                        'Authorization': TOKEN,
                    },
                    dataType: "json",
                    type: "GET",
                    delay: 300,
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
                                    city_code: item.city_code,
                                    name: item.name,
                                    code: item.code,
                                    text: item.name,
                                    id: item.id
                                }
                            })
                        }
                    }
                },
            });

            if (selectedValObject) {
                $(element).empty();
                
                if (isTags && Array.isArray(selectedValObject)) {
                    selectedValObject.forEach(function(item) {
                        let option = new Option(item.text, item.id, true, true);
                        $(element).append(option);
                    });
                } else {
                    let preselectedValue = new Option(selectedValObject.text, selectedValObject.id, true, true);
                    $(element).append(preselectedValue);
                }
            }
        }

        function getVillages(params, callback = null) {
            
            let element = params['element'],
                isAsync = params['is_async'] ?? true,
                selectedVal = params['selected_val'] ?? '',
                defaultKey = params['default_key'] ? params['default_key'] : 'id',
                filter = params['filter'] ?? '';
            $.ajax({
                url: BASE_URL + "/api/indonesia_villages?all=true&order[id]=desc"+filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    Villages = res;
                    if (element != '' && element != undefined && element != null) {
                        buildVillages(element, selectedVal, defaultKey);
                    }
                    callback && callback(Villages);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                },
            });
        }

        function buildVillages(element, selectedVal = "", defaultKey) {
            let html = ''
            html += '<option value="">{{ __('language.placeholder.select', ["name" => __('language.village')]) }}</option>'
            $.each(Villages, function(index, item) {
                if (selectedVal == item[defaultKey]) {
                    html += '<option value="' + item[defaultKey] + '" data-code="'+item.code+'" data-district_code="'+item.district_code+'" selected>' + item.name + '</option>'
                } else {
                    html += '<option value="' + item[defaultKey] + '" data-code="'+item.code+'" data-district_code="'+item.district_code+'">' + item.name + '</option>'
                }
            })
            $(element).html(html)
        }

        function getVillageSearch(params) {
            let element = params['element'],
                selectedValObject = params['selected_val_object'] ?? '',
                filter = params['filter'] ?? '',
                modal = params['modal'] ?? '',
                placeholderLabel = params['placeholder_label'] ?? '{{ __('language.placeholder.select', ["name" => __('language.village')]) }}',
                isTags = parseInt(params['is_tags'] ?? 0) === 1 ? true : false;

            $(element).select2({
                width: '100%',
                // minimumInputLength: 3,
                dropdownParent: modal,
                language: {
                    inputTooShort: function(val) {
                        return 'Ketikan minimal ' + val.minimum + ' karakter.';
                    }
                },
                tags: isTags,
                placeholder: placeholderLabel,
                selectionCssClass: "select2-sm",
                dropdownCssClass: "select2-sm",
                minimumResultsForSearch: '',
                ajax: {
                    url: BASE_URL + "/api/indonesia_villages?per_page=10&order[id]=desc&" + filter,
                    headers: {
                        'Authorization': TOKEN,
                    },
                    dataType: "json",
                    type: "GET",
                    delay: 300,
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
                                    district_code: item.district_code,
                                    name: item.name,
                                    code: item.code,
                                    text: item.name,
                                    id: item.id
                                }
                            })
                        }
                    }
                },
            });

            if (selectedValObject) {
                $(element).empty();
                
                if (isTags && Array.isArray(selectedValObject)) {
                    selectedValObject.forEach(function(item) {
                        let option = new Option(item.text, item.id, true, true);
                        $(element).append(option);
                    });
                } else {
                    let preselectedValue = new Option(selectedValObject.text, selectedValObject.id, true, true);
                    $(element).append(preselectedValue);
                }
            }
        }

        function getContacts(params, callback = null) {
            
            let element = params['element'],
                isAsync = params['is_async'] ?? true,
                selectedVal = params['selected_val'] ?? '',
                filter = params['filter'] ?? '';
            $.ajax({
                url: BASE_URL + "/api/contacts?all=true&order[id]=desc"+filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    Contacts = res;
                    if (element != '' && element != undefined && element != null) {
                        buildContacts(element, selectedVal);
                    }
                    callback && callback(Contacts);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                },
            });
        }

        function buildContacts(element, selectedVal = "") {
            let html = ''
            html += '<option value="">{{ __('language.placeholder.select', ["name" => __('language.contact')]) }}</option>'
            $.each(Contacts, function(index, item) {
                if (selectedVal == item.id) {
                    html += '<option value="' + item.id + '" data-name="'+item.name+'" selected>' + item.name + '</option>'
                } else {
                    html += '<option value="' + item.id + '" data-name="'+item.name+'">' + item.name + '</option>'
                }
            })
            $(element).html(html)
        }

        function getContactSearch(params) {
            let element = params['element'],
                selectedValObject = params['selected_val_object'] ?? '',
                filter = params['filter'] ?? '',
                modal = params['modal'] ?? '',
                isTags = parseInt(params['is_tags'] ?? 0) === 1 ? true : false;
                isNeedAll = parseInt(params['is_need_all'] ?? 0) === 1 ? true : false,
                placeholderLabel = params['placeholder_label'] ?? '{{ __('language.placeholder.select', ["name" => __('language.contact')]) }}';

            $(element).select2({
                width: '100%',
                // minimumInputLength: 3,
                dropdownParent: modal,
                language: {
                    inputTooShort: function(val) {
                        return 'Ketikan minimal ' + val.minimum + ' karakter.';
                    }
                },
                tags: isTags,
                placeholder: placeholderLabel,
                selectionCssClass: "select2-sm",
                dropdownCssClass: "select2-sm",
                minimumResultsForSearch: '',
                ajax: {
                    url: BASE_URL + "/api/contacts?per_page=10&order[id]=desc&" + filter,
                    headers: {
                        'Authorization': TOKEN,
                    },
                    dataType: "json",
                    type: "GET",
                    delay: 300,
                    data: function(params) {
                        var queryParameters = {
                            name: params.term
                        }
                        return queryParameters
                    },
                    processResults: function(data) {
                        let resultsContacts = [];
                        if (isNeedAll) {
                            resultsContacts.push({
                                id: 0,
                                text: 'Semua'
                            });
                        }

                        $.each(data.data, function(i, item) {
                            resultsContacts.push({
                                id: item.id,
                                text: item.name,
                                contact_group_id: item.contact_group_id,
                            });
                        });

                        return {
                            results: resultsContacts
                        };
                    }
                },
            });

            if (selectedValObject) {
                $(element).empty();
                
                if (isTags && Array.isArray(selectedValObject)) {
                    selectedValObject.forEach(function(item) {
                        let option = new Option(item.text, item.id, true, true);
                        $(element).append(option);
                    });
                } else {
                    let preselectedValue = new Option(selectedValObject.text, selectedValObject.id, true, true);
                    $(element).append(preselectedValue);
                }
            }
        }

        function getContactGroups(params, callback = null) {
            
            let element = params['element'],
                isAsync = params['is_async'] ?? true,
                selectedVal = params['selected_val'] ?? '',
                filter = params['filter'] ?? '';
            $.ajax({
                url: BASE_URL + "/api/contact_groups?all=true&order[id]=desc"+filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    ContactGroups = res;
                    if (element != '' && element != undefined && element != null) {
                        buildContactGroups(element, selectedVal);
                    }
                    callback && callback(ContactGroups);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                },
            });
        }

        function buildContactGroups(element, selectedVal = "") {
            let html = ''
            html += '<option value="">{{ __('language.placeholder.select', ["name" => __('language.contact_segment')]) }}</option>'
            $.each(ContactGroups, function(index, item) {
                if (selectedVal == item.id) {
                    html += '<option value="' + item.id + '" data-name="'+name+'" selected>' + item.name + '</option>'
                } else {
                    html += '<option value="' + item.id + '" data-name="'+name+'">' + item.name + '</option>'
                }
            })
            $(element).html(html)
        }

        function getContactGroupSearch(params) {
            let element = params['element'],
                selectedValObject = params['selected_val_object'] ?? '',
                filter = params['filter'] ?? '',
                modal = params['modal'] ?? '',
                placeholderLabel = params['placeholder_label'] ?? '{{ __('language.placeholder.select', ["name" => __('language.contact_segment')]) }}',
                isTags = parseInt(params['is_tags'] ?? 0) === 1 ? true : false;

            $(element).select2({
                width: '100%',
                // minimumInputLength: 3,
                dropdownParent: modal,
                language: {
                    inputTooShort: function(val) {
                        return 'Ketikan minimal ' + val.minimum + ' karakter.';
                    }
                },
                tags: isTags,
                placeholder: placeholderLabel,
                selectionCssClass: "select2-sm",
                dropdownCssClass: "select2-sm",
                minimumResultsForSearch: '',
                ajax: {
                    url: BASE_URL + "/api/contact_groups?per_page=10&order[id]=desc&" + filter,
                    headers: {
                        'Authorization': TOKEN,
                    },
                    dataType: "json",
                    type: "GET",
                    delay: 300,
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
                                    name: item.name,
                                    text: item.name,
                                    id: item.id
                                }
                            })
                        }
                    }
                },
            });

            if (selectedValObject) {
                $(element).empty();
                
                if (isTags && Array.isArray(selectedValObject)) {
                    selectedValObject.forEach(function(item) {
                        let option = new Option(item.text, item.id, true, true);
                        $(element).append(option);
                    });
                } else {
                    let preselectedValue = new Option(selectedValObject.text, selectedValObject.id, true, true);
                    $(element).append(preselectedValue);
                }
            }
        }

        function getCurrencies(params, callback = null) {
            
            let element = params['element'],
                isAsync = params['is_async'] ?? true,
                selectedVal = params['selected_val'] ?? '',
                filter = params['filter'] ?? '';
            $.ajax({
                url: BASE_URL + "/api/currencies?all=true&order[id]=desc"+filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    Currencies = res;
                    if (element != '' && element != undefined && element != null) {
                        buildCurrencies(element, selectedVal);
                    }
                    callback && callback(Currencies);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                },
            });
        }

        function buildCurrencies(element, selectedVal = "") {
            let html = ''
            html += '<option value="">{{ __('language.placeholder.select', ["name" => __('language.currency')]) }}</option>'
            $.each(Currencies, function(index, item) {
                if (selectedVal == item.id) {
                    html += '<option value="' + item.id + '" data-name="'+item.name+'" data-exchange_rate="'+item.exchange_rate+'" selected>' + item.name + '</option>'
                } else {
                    html += '<option value="' + item.id + '" data-name="'+item.name+'" data-exchange_rate="'+item.exchange_rate+'">' + item.name + '</option>'
                }
            })
            $(element).html(html)
        }

        function getCurrencySearch(params) {
            let element = params['element'],
                selectedValObject = params['selected_val_object'] ?? '',
                filter = params['filter'] ?? '',
                modal = params['modal'] ?? '',
                placeholderLabel = params['placeholder_label'] ?? '{{ __('language.placeholder.select', ["name" => __('language.currency')]) }}',
                isTags = parseInt(params['is_tags'] ?? 0) === 1 ? true : false;

            $(element).select2({
                width: '100%',
                // minimumInputLength: 3,
                dropdownParent: modal,
                language: {
                    inputTooShort: function(val) {
                        return 'Ketikan minimal ' + val.minimum + ' karakter.';
                    }
                },
                tags: isTags,
                placeholder: placeholderLabel,
                selectionCssClass: "select2-sm",
                dropdownCssClass: "select2-sm",
                minimumResultsForSearch: '',
                ajax: {
                    url: BASE_URL + "/api/currencies?per_page=10&order[id]=desc&" + filter,
                    headers: {
                        'Authorization': TOKEN,
                    },
                    dataType: "json",
                    type: "GET",
                    delay: 300,
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
                                    name: item.name,
                                    exchange_rate: item.exchange_rate,
                                    text: item.name,
                                    id: item.id
                                }
                            })
                        }
                    }
                },
            });

            if (selectedValObject) {
                $(element).empty();
                
                if (isTags && Array.isArray(selectedValObject)) {
                    selectedValObject.forEach(function(item) {
                        let option = new Option(item.text, item.id, true, true);
                        $(element).append(option);
                    });
                } else {
                    let preselectedValue = new Option(selectedValObject.text, selectedValObject.id, true, true);
                    $(element).append(preselectedValue);
                }
            }
        }
   
        function getWarehouses(params, callback = null) {
            
            let element = params['element'],
                isAsync = params['is_async'] ?? true,
                selectedVal = params['selected_val'] ?? '',
                filter = params['filter'] ?? '';
                all = params['all'] ?? false;
            $.ajax({
                url: BASE_URL + "/api/warehouses?all=true&order[id]=desc"+filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    Warehouses = res;
                    if (element != '' && element != undefined && element != null) {
                        buildWarehouses(element, selectedVal,all);
                    }
                    callback && callback(Warehouses);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                },
            });
        }

        function buildWarehouses(element, selectedVal = "",all) {
            let html = ''
            html += '<option value="">{{ __('language.placeholder.select', ["name" => __('language.warehouse')]) }}</option>'
            if (all == true) {
                 html += '<option value="all" selected>{{ __('language.all_warehouse') }}</option>'
            }
            $.each(Warehouses, function(index, item) {
                if (selectedVal == item.id) {
                    html += '<option value="' + item.id + '" data-name="'+item.name+'" selected>' + item.name + '</option>'
                } else {
                    html += '<option value="' + item.id + '" data-name="'+item.name+'">' + item.name + '</option>'
                }
            })
            $(element).html(html)
        }

        function getWarehouseSearch(params) {
            let element = params['element'],
                selectedValObject = params['selected_val_object'] ?? '',
                filter = params['filter'] ?? '',
                modal = params['modal'] ?? '',
                placeholderLabel = params['placeholder_label'] ?? '{{ __('language.placeholder.select', ["name" => __('language.warehouse')]) }}',
                isTags = parseInt(params['is_tags'] ?? 0) === 1 ? true : false;

            $(element).select2({
                width: '100%',
                // minimumInputLength: 3,
                dropdownParent: modal,
                language: {
                    inputTooShort: function(val) {
                        return 'Ketikan minimal ' + val.minimum + ' karakter.';
                    }
                },
                tags: isTags,
                placeholder: placeholderLabel,
                selectionCssClass: "select2-sm",
                dropdownCssClass: "select2-sm",
                minimumResultsForSearch: '',
                ajax: {
                    url: BASE_URL + "/api/warehouses?per_page=10&order[id]=desc&" + filter,
                    headers: {
                        'Authorization': TOKEN,
                    },
                    dataType: "json",
                    type: "GET",
                    delay: 300,
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
                                    name: item.name,
                                    text: item.name,
                                    id: item.id
                                }
                            })
                        }
                    }
                },
            });

            if (selectedValObject) {
                $(element).empty();
                
                if (isTags && Array.isArray(selectedValObject)) {
                    selectedValObject.forEach(function(item) {
                        let option = new Option(item.text, item.id, true, true);
                        $(element).append(option);
                    });
                } else {
                    let preselectedValue = new Option(selectedValObject.text, selectedValObject.id, true, true);
                    $(element).append(preselectedValue);
                }
            }
        }

        function getBranches(params, callback = null) {
            
            let element = params['element'],
                isAsync = params['is_async'] ?? true,
                selectedVal = params['selected_val'] ?? '',
                filter = params['filter'] ?? '';
                all = params['all'] ?? false;
            $.ajax({
                url: BASE_URL + "/api/branches?all=true&order[id]=desc"+filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    Branches = res;
                    if (element != '' && element != undefined && element != null) {
                        buildBranches(element, selectedVal,all);
                    }
                    callback && callback(Branches);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                },
            });
        }

        function buildBranches(element, selectedVal = "",all) {
            let html = ''
            html += '<option value="">{{ __('language.placeholder.select', ["name" => __('language.branch')]) }}</option>'
            if (all == true) {
                 html += '<option value="all" selected>{{ __('language.all_branch') }}</option>'
            }
            $.each(Branches, function(index, item) {
                if (selectedVal == item.id) {
                    html += '<option value="' + item.id + '" data-name="'+item.name+'" selected>' + item.name + '</option>'
                } else {
                    html += '<option value="' + item.id + '" data-name="'+item.name+'">' + item.name + '</option>'
                }
            })
            $(element).html(html)
        }

        function getBranchSearch(params) {
            let element = params['element'],
                selectedValObject = params['selected_val_object'] ?? '',
                filter = params['filter'] ?? '',
                modal = params['modal'] ?? '',
                isTags = parseInt(params['is_tags'] ?? 0) === 1 ? true : false;
                isNeedAll = parseInt(params['is_need_all'] ?? 0) === 1 ? true : false,
                placeholderLabel = params['placeholder_label'] ?? '{{ __('language.placeholder.select', ["name" => __('language.branch')]) }}';

            $(element).select2({
                width: '100%',
                // minimumInputLength: 3,
                dropdownParent: modal,
                language: {
                    inputTooShort: function(val) {
                        return 'Ketikan minimal ' + val.minimum + ' karakter.';
                    }
                },
                tags: isTags,
                placeholder: placeholderLabel,
                selectionCssClass: "select2-sm",
                dropdownCssClass: "select2-sm",
                minimumResultsForSearch: '',
                ajax: {
                    url: BASE_URL + "/api/branches?per_page=10&order[id]=desc&" + filter,
                    headers: {
                        'Authorization': TOKEN,
                    },
                    dataType: "json",
                    type: "GET",
                    delay: 300,
                    data: function(params) {
                        var queryParameters = {
                            name: params.term
                        }
                        return queryParameters
                    },
                    processResults: function(data) {
                        let resultsBranches = [];
                        if (isNeedAll) {
                            resultsBranches.push({
                                id: 0,
                                text: 'Semua',
                                name: 'Semua',
                                code: '0',
                            });
                        }

                        $.each(data.data, function(i, item) {
                            resultsBranches.push({
                                name: item.name,
                                code: item.code,
                                text: (item.code ? item.code + ' | ' : '') + item.name,
                                id: item.id
                            });
                        });

                        return {
                            results: resultsBranches
                        };
                    }
                },
            });

            if (selectedValObject) {
                $(element).empty();
                
                if (isTags && Array.isArray(selectedValObject)) {
                    selectedValObject.forEach(function(item) {
                        let option = new Option(item.text, item.id, true, true);
                        $(element).append(option);
                    });
                } else {
                    let preselectedValue = new Option(selectedValObject.text, selectedValObject.id, true, true);
                    $(element).append(preselectedValue);
                }
            }
        }

        function getProductionPhaseSearch(params) {
            let element = params['element'],
                selectedValObject = params['selected_val_object'] ?? '',
                filter = params['filter'] ?? '',
                modal = params['modal'] ?? '',
                placeholderLabel = params['placeholder_label'] ?? '{{ __('language.placeholder.select', ["name" => __('language.production_phase')]) }}',
                isTags = parseInt(params['is_tags'] ?? 0) === 1 ? true : false;

            $(element).select2({
                width: '100%',
                // minimumInputLength: 3,
                dropdownParent: modal,
                language: {
                    inputTooShort: function(val) {
                        return 'Ketikan minimal ' + val.minimum + ' karakter.';
                    }
                },
                tags: isTags,
                placeholder: placeholderLabel,
                selectionCssClass: "select2-sm",
                dropdownCssClass: "select2-sm",
                minimumResultsForSearch: '',
                ajax: {
                    url: BASE_URL + "/api/production_phases?per_page=10&order[id]=desc&" + filter,
                    headers: {
                        'Authorization': TOKEN,
                    },
                    dataType: "json",
                    type: "GET",
                    delay: 300,
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
                                    name: item.name,
                                    code: item.code,
                                    text: (item.code ? item.code + ' | ' : '') + item.name,
                                    id: item.id
                                }
                            })
                        }
                    }
                },
            });

            if (selectedValObject) {
                $(element).empty();
                
                if (isTags && Array.isArray(selectedValObject)) {
                    selectedValObject.forEach(function(item) {
                        let option = new Option(item.text, item.id, true, true);
                        $(element).append(option);
                    });
                } else {
                    let preselectedValue = new Option(selectedValObject.text, selectedValObject.id, true, true);
                    $(element).append(preselectedValue);
                }
            }
        }

        function getProjects(params, callback = null) {
            
            let element = params['element'],
                isAsync = params['is_async'] ?? true,
                selectedVal = params['selected_val'] ?? '',
                filter = params['filter'] ?? '';
                all = params['all'] ?? false;
            $.ajax({
                url: BASE_URL + "/api/projects?all=true&order[id]=desc"+filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    Projects = res;
                    if (element != '' && element != undefined && element != null) {
                        buildProjects(element, selectedVal,all);
                    }
                    callback && callback(Projects);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                },
            });
        }

        function buildProjects(element, selectedVal = "",all) {
            let html = ''
            html += '<option value="">{{ __('language.placeholder.select', ["name" => __('language.project')]) }}</option>'
            if (all == true) {
                 html += '<option value="all" selected>{{ __('language.all_project') }}</option>'
            }
            $.each(Projects, function(index, item) {
                if (selectedVal == item.id) {
                    html += '<option value="' + item.id + '" data-name="'+item.name+'" selected>' + item.name + '</option>'
                } else {
                    html += '<option value="' + item.id + '" data-name="'+item.name+'">' + item.name + '</option>'
                }
            })
            $(element).html(html)
        }

        function getProjectSearch(params) {
            let element = params['element'],
                selectedValObject = params['selected_val_object'] ?? '',
                filter = params['filter'] ?? '',
                modal = params['modal'] ?? '',
                isTags = parseInt(params['is_tags'] ?? 0) === 1 ? true : false;
                isNeedAll = parseInt(params['is_need_all'] ?? 0) === 1 ? true : false,
                placeholderLabel = params['placeholder_label'] ?? '{{ __('language.placeholder.select', ["name" => __('language.project')]) }}';
            $(element).select2({
                width: '100%',
                // minimumInputLength: 3,
                dropdownParent: modal,
                language: {
                    inputTooShort: function(val) {
                        return 'Ketikan minimal ' + val.minimum + ' karakter.';
                    }
                },
                tags: isTags,
                placeholder: placeholderLabel,
                selectionCssClass: "select2-sm",
                dropdownCssClass: "select2-sm",
                minimumResultsForSearch: '',
                ajax: {
                    url: BASE_URL + "/api/projects?per_page=10&order[id]=desc&" + filter,
                    headers: {
                        'Authorization': TOKEN,
                    },
                    dataType: "json",
                    type: "GET",
                    delay: 300,
                    data: function(params) {
                        var queryParameters = {
                            name: params.term
                        }
                        return queryParameters
                    },
                    processResults: function(data) {
                        
                        let resultsProjects = [];
                        if (isNeedAll) {
                            resultsProjects.push({
                                name: 'Semua',
                                text: 'Semua',
                                id: 0
                            });
                        }

                        $.each(data.data, function(i, item) {
                            resultsProjects.push({
                                name: item.name,
                                text: item.name,
                                id: item.id
                            });
                        });

                        return {
                            results: resultsProjects
                        };
                    }
                },
            });

            if (selectedValObject) {
                $(element).empty();
                
                if (isTags && Array.isArray(selectedValObject)) {
                    selectedValObject.forEach(function(item) {
                        let option = new Option(item.text, item.id, true, true);
                        $(element).append(option);
                    });
                } else {
                    let preselectedValue = new Option(selectedValObject.text, selectedValObject.id, true, true);
                    $(element).append(preselectedValue);
                }
            }
        }

        function getUnits(params, callback = null) {
            
            let element = params['element'],
                isAsync = params['is_async'] ?? true,
                selectedVal = params['selected_val'] ?? '',
                filter = params['filter'] ?? '';
            $.ajax({
                url: BASE_URL + "/api/units?all=true&order[id]=desc"+filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    Units = res;
                    if (element != '' && element != undefined && element != null) {
                        buildUnits(element, selectedVal);
                    }
                    callback && callback(Units);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                },
            });
        }

        function buildUnits(element, selectedVal = "") {
            let html = ''
            html += '<option value="">{{ __('language.placeholder.select', ["name" => __('language.unit')]) }}</option>'
            $.each(Units, function(index, item) {
                if (selectedVal == item.id) {
                    html += '<option value="' + item.id + '" data-name="'+item.name+'" selected>' + item.name + '</option>'
                } else {
                    html += '<option value="' + item.id + '" data-name="'+item.name+'">' + item.name + '</option>'
                }
            })
            $(element).html(html)
        }

        function getRoles(params, callback = null) {
            
            let element = params['element'],
                isAsync = params['is_async'] ?? true,
                selectedVal = params['selected_val'] ?? '',
                filter = params['filter'] ?? '';
            $.ajax({
                url: BASE_URL + "/api/roles?all=true&order[id]=desc"+filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    Units = res;
                    if (element != '' && element != undefined && element != null) {
                        buildRoles(element, selectedVal);
                    }
                    callback && callback(Units);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                },
            });
        }

        function buildRoles(element, selectedVal = "") {
            let html = ''
            html += '<option value="">{{ __('language.placeholder.select', ["name" => __('language.roles')]) }}</option>'
            $.each(Units, function(index, item) {
                if (selectedVal == item.id) {
                    html += '<option value="' + item.id + '" data-name="'+item.name+'" selected>' + item.name + '</option>'
                } else {
                    html += '<option value="' + item.id + '" data-name="'+item.name+'">' + item.name + '</option>'
                }
            })
            $(element).html(html)
        }

        function getUnitSearch(params) {
            let element = params['element'],
                selectedValObject = params['selected_val_object'] ?? '',
                filter = params['filter'] ?? '',
                modal = params['modal'] ?? '',
                placeholderLabel = params['placeholder_label'] ?? '{{ __('language.placeholder.select', ["name" => __('language.unit')]) }}',
                isTags = parseInt(params['is_tags'] ?? 0) === 1 ? true : false;

            $(element).select2({
                width: '100%',
                // minimumInputLength: 3,
                dropdownParent: modal,
                language: {
                    inputTooShort: function(val) {
                        return 'Ketikan minimal ' + val.minimum + ' karakter.';
                    }
                },
                tags: isTags,
                placeholder: placeholderLabel,
                selectionCssClass: "select2-sm",
                dropdownCssClass: "select2-sm",
                minimumResultsForSearch: '',
                ajax: {
                    url: BASE_URL + "/api/v1/units?per_page=10&order[id]=desc&is_active=1" + filter,
                    headers: {
                        'Authorization': TOKEN,
                        'company-id': COMPANY_ID
                    },
                    dataType: "json",
                    type: "GET",
                    delay: 300,
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
                                    name: item.name,
                                    text: item.name,
                                    id: item.id,
                                    conversions: item.conversions
                                }
                            })
                        }
                    }
                },
            });

            if (selectedValObject) {
                $(element).empty();
                
                if (isTags && Array.isArray(selectedValObject)) {
                    selectedValObject.forEach(function(item) {
                        let option = new Option(item.text, item.id, true, true);
                        $(element).append(option);
                    });
                } else {
                    let preselectedValue = new Option(selectedValObject.text, selectedValObject.id, true, true);

                    if (selectedValObject?.dataset && typeof selectedValObject.dataset === 'object') {
                        Object.entries(selectedValObject.dataset).forEach(([key, value]) => {
                            preselectedValue.dataset[key] = value;
                        });
                    }
                    
                    $(preselectedValue).data('data', {
                        id: selectedValObject.id,
                        text: selectedValObject.text,
                        ...selectedValObject,
                        ...selectedValObject?.dataset
                    });

                    $(element).append(preselectedValue);
                }
            }
        }

        function getProductUnitSearch(params) {
            let element = params['element'],
                selectedValObject = params['selected_val_object'] ?? '',
                filter = params['filter'] ?? '',
                modal = params['modal'] ?? '',
                placeholderLabel = params['placeholder_label'] ?? '{{ __('language.placeholder.select', ["name" => __('language.unit')]) }}',
                isTags = parseInt(params['is_tags'] ?? 0) === 1 ? true : false;

            $(element).select2({
                width: '100%',
                // minimumInputLength: 3,
                dropdownParent: modal,
                language: {
                    inputTooShort: function(val) {
                        return 'Ketikan minimal ' + val.minimum + ' karakter.';
                    }
                },
                tags: isTags,
                placeholder: placeholderLabel,
                selectionCssClass: "select2-sm",
                dropdownCssClass: "select2-sm",
                minimumResultsForSearch: '',
                ajax: {
                    url: BASE_URL + "/api/product_unit_conversions?per_page=10&order[id]=desc&" + filter,
                    headers: {
                        'Authorization': TOKEN,
                    },
                    dataType: "json",
                    type: "GET",
                    delay: 300,
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
                                    from_value: item.to_value,
                                    to_value: item.from_value,
                                    name: item.to_unit_name,
                                    text: item.to_unit_name,
                                    id: item.to_unit_id
                                }
                            })
                        }
                    }
                },
            });

            if (selectedValObject) {
                $(element).empty();
                
                if (isTags && Array.isArray(selectedValObject)) {
                    selectedValObject.forEach(function(item) {
                        let option = new Option(item.text, item.id, true, true);
                        $(element).append(option);
                    });
                } else {
                    let preselectedValue = new Option(selectedValObject.text, selectedValObject.id, true, true);
                    $(element).append(preselectedValue);
                }
            }
        }

        function getBaseUnitSearch(params) {
            let element = params['element'],
                selectedValObject = params['selected_val_object'] ?? '',
                filter = params['filter'] ?? '',
                modal = params['modal'] ?? '',
                placeholderLabel = params['placeholder_label'] ?? '{{ __('language.placeholder.select', ["name" => __('language.unit')]) }}',
                isTags = parseInt(params['is_tags'] ?? 0) === 1 ? true : false;

            $(element).select2({
                width: '100%',
                // minimumInputLength: 3,
                dropdownParent: modal,
                language: {
                    inputTooShort: function(val) {
                        return 'Ketikan minimal ' + val.minimum + ' karakter.';
                    }
                },
                tags: isTags,
                placeholder: placeholderLabel,
                selectionCssClass: "select2-sm",
                dropdownCssClass: "select2-sm",
                minimumResultsForSearch: '',
                ajax: {
                    url: BASE_URL + "/api/base_unit_conversions?per_page=10&order[id]=desc&" + filter,
                    headers: {
                        'Authorization': TOKEN,
                    },
                    dataType: "json",
                    type: "GET",
                    delay: 300,
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
                                    from_value: item.from_value,
                                    to_value: item.to_value,
                                    name: item.from_unit_name,
                                    text: item.from_unit_name,
                                    id: item.from_unit_id
                                }
                            })
                        }
                    }
                },
            });

            if (selectedValObject) {
                $(element).empty();
                
                if (isTags && Array.isArray(selectedValObject)) {
                    selectedValObject.forEach(function(item) {
                        let option = new Option(item.text, item.id, true, true);
                        $(element).append(option);
                    });
                } else {
                    let preselectedValue = new Option(selectedValObject.text, selectedValObject.id, true, true);
                    $(element).append(preselectedValue);
                }
            }
        }

        function getDepositClassifications(params, callback = null) {
            
            let element = params['element'],
                isAsync = params['is_async'] ?? true,
                selectedVal = params['selected_val'] ?? '',
                filter = params['filter'] ?? '';
            $.ajax({
                url: BASE_URL + "/api/deposit_classifications?all=true&order[id]=desc"+filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    DepositClassifications = res;
                    if (element != '' && element != undefined && element != null) {
                        buildDepositClassifications(element, selectedVal);
                    }
                    callback && callback(DepositClassifications);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                },
            });
        }

        function buildDepositClassifications(element, selectedVal = "") {
            let html = ''
            html += '<option value="">{{ __('language.placeholder.select', ["name" => __('language.deposit_classification')]) }}</option>'
            $.each(DepositClassifications, function(index, item) {
                if (selectedVal == item.id) {
                    html += '<option value="' + item.id + '" data-name="'+item.name+'" selected>' + item.name + '</option>'
                } else {
                    html += '<option value="' + item.id + '" data-name="'+item.name+'">' + item.name + '</option>'
                }
            })
            $(element).html(html)
        }

        function getDepositClassificationSearch(params) {
            let element = params['element'],
                selectedValObject = params['selected_val_object'] ?? '',
                filter = params['filter'] ?? '',
                modal = params['modal'] ?? '',
                placeholderLabel = params['placeholder_label'] ?? '{{ __('language.placeholder.select', ["name" => __('language.deposit_classification')]) }}',
                isTags = parseInt(params['is_tags'] ?? 0) === 1 ? true : false;

            $(element).select2({
                width: '100%',
                // minimumInputLength: 3,
                dropdownParent: modal,
                language: {
                    inputTooShort: function(val) {
                        return 'Ketikan minimal ' + val.minimum + ' karakter.';
                    }
                },
                tags: isTags,
                placeholder: placeholderLabel,
                selectionCssClass: "select2-sm",
                dropdownCssClass: "select2-sm",
                minimumResultsForSearch: '',
                ajax: {
                    url: BASE_URL + "/api/deposit_classifications?per_page=10&order[id]=desc&" + filter,
                    headers: {
                        'Authorization': TOKEN,
                    },
                    dataType: "json",
                    type: "GET",
                    delay: 300,
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
                                    name: item.name,
                                    text: item.name,
                                    id: item.id
                                }
                            })
                        }
                    }
                },
            });

            if (selectedValObject) {
                $(element).empty();
                
                if (isTags && Array.isArray(selectedValObject)) {
                    selectedValObject.forEach(function(item) {
                        let option = new Option(item.text, item.id, true, true);
                        $(element).append(option);
                    });
                } else {
                    let preselectedValue = new Option(selectedValObject.text, selectedValObject.id, true, true);
                    $(element).append(preselectedValue);
                }
            }
        }

        function getProductCategories(params, callback = null) {
            
            let element = params['element'],
                isAsync = params['is_async'] ?? true,
                selectedVal = params['selected_val'] ?? '',
                filter = params['filter'] ?? '';
            $.ajax({
                url: BASE_URL + "/api/product_categories?all=true&order[id]=desc"+filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    ProductCategories = res;
                    if (element != '' && element != undefined && element != null) {
                        buildProductCategories(element, selectedVal);
                    }
                    callback && callback(ProductCategories);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                },
            });
        }

        function buildProductCategories(element, selectedVal = "") {
            let html = ''
            html += '<option value="">{{ __('language.placeholder.select', ["name" => __('language.product_category')]) }}</option>'
            $.each(ProductCategories, function(index, item) {
                if (selectedVal == item.id) {
                    html += '<option value="' + item.id + '" data-name="'+item.name+'" selected>' + item.name + '</option>'
                } else {
                    html += '<option value="' + item.id + '" data-name="'+item.name+'">' + item.name + '</option>'
                }
            })
            $(element).html(html)
        }

        function getProductCategorySearch(params) {
            let element = params['element'],
                selectedValObject = params['selected_val_object'] ?? '',
                filter = params['filter'] ?? '',
                modal = params['modal'] ?? '',
                placeholderLabel = params['placeholder_label'] ?? '{{ __('language.placeholder.select', ["name" => __('language.product_category')]) }}',
                isTags = parseInt(params['is_tags'] ?? 0) === 1 ? true : false;

            $(element).select2({
                width: '100%',
                // minimumInputLength: 3,
                dropdownParent: modal,
                language: {
                    inputTooShort: function(val) {
                        return 'Ketikan minimal ' + val.minimum + ' karakter.';
                    }
                },
                tags: isTags,
                placeholder: placeholderLabel,
                selectionCssClass: "select2-sm",
                dropdownCssClass: "select2-sm",
                minimumResultsForSearch: '',
                ajax: {
                    url: BASE_URL + "/api/product_categories?per_page=10&order[id]=desc&" + filter,
                    headers: {
                        'Authorization': TOKEN,
                    },
                    dataType: "json",
                    type: "GET",
                    delay: 300,
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
                                    name: item.name,
                                    code: item.code,
                                    text: item.name,
                                    id: item.id
                                }
                            })
                        }
                    }
                },
            });

            if (selectedValObject) {
                $(element).empty();
                
                if (isTags && Array.isArray(selectedValObject)) {
                    selectedValObject.forEach(function(item) {
                        let option = new Option(item.text, item.id, true, true);
                        $(element).append(option);
                    });
                } else {
                    let preselectedValue = new Option(selectedValObject.text, selectedValObject.id, true, true);
                    $(element).append(preselectedValue);
                }
            }
        }

        function getProducts(params, callback = null) {
            
            let element = params['element'],
                isAsync = params['is_async'] ?? true,
                selectedVal = params['selected_val'] ?? '',
                filter = params['filter'] ?? '';
            $.ajax({
                url: BASE_URL + "/api/products?all=true&order[id]=desc"+filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    Products = res;
                    if (element != '' && element != undefined && element != null) {
                        buildProducts(element, selectedVal);
                    }
                    callback && callback(Products);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                },
            });
        }

        function buildProducts(element, selectedVal = "") {
            let html = ''
            html += '<option value="">{{ __('language.placeholder.select', ["name" => __('language.product')]) }}</option>'
            $.each(Products, function(index, item) {
                if (selectedVal == item.id) {
                    html += '<option value="' + item.id + '" data-name="'+item.name+'" data-code="'+item.code+'" data-qty="0" data-purchase_price="'+item.purchase_price+'" data-sale_price="'+item.sale_price+'" data-unit_id="'+item.unit_id+'" data-unit_name="'+item.unit_name+'" selected>' + item.name + '</option>'
                } else {
                    html += '<option value="' + item.id + '" data-name="'+item.name+'" data-code="'+item.code+'" data-qty="0" data-purchase_price="'+item.purchase_price+'" data-sale_price="'+item.sale_price+'" data-unit_id="'+item.unit_id+'" data-unit_name="'+item.unit_name+'">' + item.name + '</option>'
                }
            })
            $(element).html(html)
        }

        function getProductSearch(params) {
            let element = params['element'],
                selectedValObject = params['selected_val_object'] ?? '',
                filter = params['filter'] ?? '',
                modal = params['modal'] ?? '',
                placeholderLabel = params['placeholder_label'] ?? '{{ __('language.placeholder.select', ["name" => __('language.product')]) }}',
                isTags = parseInt(params['is_tags'] ?? 0) === 1 ? true : false;

            $(element).select2({
                width: '100%',
                // minimumInputLength: 3,
                dropdownParent: modal,
                language: {
                    inputTooShort: function(val) {
                        return 'Ketikan minimal ' + val.minimum + ' karakter.';
                    }
                },
                tags: isTags,
                placeholder: placeholderLabel,
                selectionCssClass: "select2-sm",
                dropdownCssClass: "select2-sm",
                minimumResultsForSearch: '',
                templateResult: params['templateResult'],
                templateSelection: params['templateSelection'],
                escapeMarkup: function(m) { return m; },
                ajax: {
                    url: BASE_URL + "/api/products?per_page=10&order[id]=desc&" + filter,
                    headers: {
                        'Authorization': TOKEN,
                    },
                    dataType: "json",
                    type: "GET",
                    delay: 300,
                    data: function(params) {
                        var queryParameters = {
                            or: {
                                code: params.term,
                                name: params.term
                            }
                        }
                        return queryParameters
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data.data, function(item) {
                                return {
                                    is_product_unit_convert: item.is_product_unit_convert,
                                    unit_name: item.unit_name,
                                    unit_id: item.unit_id,
                                    sale_price: item.sale_price,
                                    purchase_price: item.purchase_price,
                                    cogs_price: item.cogs_price,
                                    qty: item.qty_on_hand,
                                    code: item.code,
                                    name: item.name,
                                    text: item.name,
                                    id: item.id
                                }
                            })
                        }
                    }
                },
            });

            if (selectedValObject) {
                $(element).empty();
                
                if (isTags && Array.isArray(selectedValObject)) {
                    selectedValObject.forEach(function(item) {
                        let option = new Option(item.text, item.id, true, true);
                        $(element).append(option);
                    });
                } else {
                    let preselectedValue = new Option(selectedValObject.text, selectedValObject.id, true, true);
                    $(preselectedValue).data('data', selectedValObject);
                    $(element).append(preselectedValue).trigger('change');
                }
            }
        }

        function getTaxes(params, callback = null) {
            
            let element = params['element'],
                isAsync = params['is_async'] ?? true,
                selectedVal = params['selected_val'] ?? '',
                filter = params['filter'] ?? '';
            $.ajax({
                url: BASE_URL + "/api/taxes?all=true&order[id]=desc"+filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    Taxes = res;
                    if (element != '' && element != undefined && element != null) {
                        buildTaxes(element, selectedVal);
                    }
                    callback && callback(Taxes);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                },
            });
        }

        function buildTaxes(element, selectedVal = "") {
            let html = ''
            html += '<option value="">{{ __('language.placeholder.select', ["name" => __('language.tax')]) }}</option>'
            $.each(Taxes, function(index, item) {
                if (selectedVal == item.id) {
                    html += '<option value="' + item.id + '" data-name="'+item.name+'" data-percentage="'+item.percentage+'" data-purchase_coa="'+item.purchase_coa+'" data-sales_coa="'+item.sales_coa+'" selected>' + item.code + '</option>'
                } else {
                    html += '<option value="' + item.id + '" data-name="'+item.name+'" data-percentage="'+item.percentage+'" data-purchase_coa="'+item.purchase_coa+'" data-sales_coa="'+item.sales_coa+'">' + item.code + '</option>'
                }
            })
            $(element).html(html)
        }

        function getTaxSearch(params) {
            let element = params['element'],
                selectedValObject = params['selected_val_object'] ?? '',
                className = params['class_name'] ?? '',
                filter = params['filter'] ?? '',
                modal = params['modal'] ?? '',
                placeholderLabel = params['placeholder_label'] ?? '{{ __('language.placeholder.select', ["name" => __('language.tax')]) }}',
                isTags = parseInt(params['is_tags'] ?? 0) === 1 ? true : false;

            if ($(element).data('select2')) {
                $(element).select2('destroy');
            }

            $(element).empty();

            $(element).select2({
                width: '100%',
                // minimumInputLength: 3,
                dropdownParent: modal,
                language: {
                    inputTooShort: function(val) {
                        return 'Ketikan minimal ' + val.minimum + ' karakter.';
                    }
                },
                tags: isTags,
                placeholder: placeholderLabel,
                selectionCssClass: "select2-sm "+className,
                dropdownCssClass: "select2-sm",
                minimumResultsForSearch: '',
                allowClear: true,
                ajax: {
                    url: BASE_URL + "/api/taxes?per_page=10&order[id]=desc&" + filter,
                    headers: {
                        'Authorization': TOKEN,
                    },
                    dataType: "json",
                    type: "GET",
                    delay: 300,
                    data: function(params) {
                        var queryParameters = {
                            or: {
                                code: params.term,
                                name: params.term
                            }
                        }
                        return queryParameters
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data.data, function(item) {
                                return {
                                    sales_coa: item.sales_coa,
                                    purchase_coa: item.purchase_coa,
                                    percentage: item.percentage,
                                    code: item.code,
                                    name: item.name,
                                    text: item.code+' | '+item.name + ' ('+parseInt(item.percentage)+' %)',
                                    id: item.id
                                }
                            })
                        }
                    }
                },
            });

            if (selectedValObject) {
                $(element).empty();
                
                if (isTags && Array.isArray(selectedValObject)) {
                    selectedValObject.forEach(function(item) {
                        let option = new Option(item.text, item.id, true, true);
                        $(element).append(option);
                    });
                } else {
                    let preselectedValue = new Option(selectedValObject.text, selectedValObject.id, true, true);
                    $(preselectedValue).data('data', selectedValObject);
                    $(element).append(preselectedValue).trigger('change');
                }
            }
        }

        function getSalesOrders(params, callback = null) {
            
            let element = params['element'],
                isAsync = params['is_async'] ?? true,
                selectedVal = params['selected_val'] ?? '',
                filter = params['filter'] ?? '';
            $.ajax({
                url: BASE_URL + "/api/sales_orders?all=true&order[id]=desc"+filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    SalesOrders = res;
                    if (element != '' && element != undefined && element != null) {
                        buildSalesOrders(element, selectedVal);
                    }
                    callback && callback(SalesOrders);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                },
            });
        }

        function buildSalesOrders(element, selectedVal = "") {
            let html = ''
            html += '<option value="">{{ __('language.placeholder.select', ["name" => __('language.sales_order')]) }}</option>'
            $.each(SalesOrders, function(index, item) {
                if (selectedVal == item.id) {
                    html += '<option value="' + item.id + '" data-number="'+item.number+'" data-date="'+item.date+'" data-total="'+item.total+'" selected>' + item.number + ' | Rp ' + parseFloat(item.total).toLocaleString('en') + '</option>'
                } else {
                    html += '<option value="' + item.id + '" data-number="'+item.number+'" data-date="'+item.date+'" data-total="'+item.total+'">' + item.number + ' | Rp ' + parseFloat(item.total).toLocaleString('en') + '</option>'
                }
            })
            $(element).html(html)
        }

        function getSalesOrderSearch(params) {
            let element = params['element'],
                selectedValObject = params['selected_val_object'] ?? '',
                filter = params['filter'] ?? '',
                modal = params['modal'] ?? '',
                placeholderLabel = params['placeholder_label'] ?? '{{ __('language.placeholder.select', ["name" => __('language.sales_order')]) }}',
                isTags = parseInt(params['is_tags'] ?? 0) === 1 ? true : false;

            $(element).select2({
                width: '100%',
                // minimumInputLength: 3,
                dropdownParent: modal,
                language: {
                    inputTooShort: function(val) {
                        return 'Ketikan minimal ' + val.minimum + ' karakter.';
                    }
                },
                tags: isTags,
                placeholder: placeholderLabel,
                selectionCssClass: "select2-sm",
                dropdownCssClass: "select2-sm",
                minimumResultsForSearch: '',
                ajax: {
                    url: BASE_URL + "/api/sales_orders?per_page=10&order[id]=desc&" + filter,
                    headers: {
                        'Authorization': TOKEN,
                    },
                    dataType: "json",
                    type: "GET",
                    delay: 300,
                    data: function(params) {
                        var queryParameters = {
                            or: {
                                number: params.term,
                                total: params.term
                            }
                        }
                        return queryParameters
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data.data, function(item) {
                                return {
                                    total: item.total,
                                    date: item.date,
                                    number: item.number,
                                    text: item.number + ' | Rp ' + parseFloat(item.total).toLocaleString('en'),
                                    id: item.id
                                }
                            })
                        }
                    }
                },
            });

            if (selectedValObject) {
                $(element).empty();
                
                if (isTags && Array.isArray(selectedValObject)) {
                    selectedValObject.forEach(function(item) {
                        let option = new Option(item.text, item.id, true, true);
                        $(element).append(option);
                    });
                } else {
                    let preselectedValue = new Option(selectedValObject.text, selectedValObject.id, true, true);
                    $(element).append(preselectedValue);
                }
            }
        }

        function getPurchaseOrders(params, callback = null) {
            
            let element = params['element'],
                isAsync = params['is_async'] ?? true,
                selectedVal = params['selected_val'] ?? '',
                filter = params['filter'] ?? '';
            $.ajax({
                url: BASE_URL + "/api/purchase_orders?all=true&order[id]=desc"+filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    PurchaseOrders = res;
                    if (element != '' && element != undefined && element != null) {
                        buildPurchaseOrders(element, selectedVal);
                    }
                    callback && callback(PurchaseOrders);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                },
            });
        }

        function buildPurchaseOrders(element, selectedVal = "") {
            let html = ''
            html += '<option value="">{{ __('language.placeholder.select', ["name" => __('language.purchase_order')]) }}</option>'
            $.each(PurchaseOrders, function(index, item) {
                if (selectedVal == item.id) {
                    html += '<option value="' + item.id + '" data-number="'+item.number+'" data-date="'+item.date+'" data-total="'+item.total+'" selected>' + item.number + ' | Rp ' + parseFloat(item.total).toLocaleString('en') + '</option>'
                } else {
                    html += '<option value="' + item.id + '" data-number="'+item.number+'" data-date="'+item.date+'" data-total="'+item.total+'">' + item.number + ' | Rp ' + parseFloat(item.total).toLocaleString('en') + '</option>'
                }
            })
            $(element).html(html)
        }

        function getPurchaseOrderSearch(params) {
            let element = params['element'],
                selectedValObject = params['selected_val_object'] ?? '',
                filter = params['filter'] ?? '',
                modal = params['modal'] ?? '',
                placeholderLabel = params['placeholder_label'] ?? '{{ __('language.placeholder.select', ["name" => __('language.purchase_order')]) }}',
                isTags = parseInt(params['is_tags'] ?? 0) === 1 ? true : false;

            $(element).select2({
                width: '100%',
                // minimumInputLength: 3,
                dropdownParent: modal,
                language: {
                    inputTooShort: function(val) {
                        return 'Ketikan minimal ' + val.minimum + ' karakter.';
                    }
                },
                tags: isTags,
                placeholder: placeholderLabel,
                selectionCssClass: "select2-sm",
                dropdownCssClass: "select2-sm",
                minimumResultsForSearch: '',
                ajax: {
                    url: BASE_URL + "/api/purchase_orders?per_page=10&order[id]=desc&" + filter,
                    headers: {
                        'Authorization': TOKEN,
                    },
                    dataType: "json",
                    type: "GET",
                    delay: 300,
                    data: function(params) {
                        var queryParameters = {
                            or: {
                                number: params.term,
                                total: params.term
                            }
                        }
                        return queryParameters
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data.data, function(item) {
                                return {
                                    total: item.total,
                                    date: item.date,
                                    number: item.number,
                                    text: item.number + ' | Rp ' + parseFloat(item.total).toLocaleString('en'),
                                    id: item.id
                                }
                            })
                        }
                    }
                },
            });

            if (selectedValObject) {
                $(element).empty();
                
                if (isTags && Array.isArray(selectedValObject)) {
                    selectedValObject.forEach(function(item) {
                        let option = new Option(item.text, item.id, true, true);
                        $(element).append(option);
                    });
                } else {
                    let preselectedValue = new Option(selectedValObject.text, selectedValObject.id, true, true);
                    $(element).append(preselectedValue);
                }
            }
        }

        function getSalesQuotations(params, callback = null) {
            let element = params['element'],
                isAsync = params['is_async'] ?? true,
                selectedVal = params['selected_val'] ?? '',
                filter = params['filter'] ?? '';
            $.ajax({
                url: BASE_URL + "/api/sales_quotations?all=true&order[id]=desc"+filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    SalesQuotations = res;
                    if (element != '' && element != undefined && element != null) {
                        buildSalesQuotations(element, selectedVal);
                    }
                    callback && callback(SalesQuotations);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                },
            });
        }

        function buildSalesQuotations(element, selectedVal = "") {
            let html = ''
            html += '<option value="">{{ __('language.placeholder.select', ["name" => __('language.sales_quotation')]) }}</option>'
            $.each(SalesQuotations, function(index, item) {
                if (selectedVal == item.id) {
                    html += '<option value="' + item.id + '" data-number="'+item.number+'" data-date="'+item.date+'" data-total="'+item.total+'" selected>' + item.number + ' | Rp ' + parseFloat(item.total).toLocaleString('en') + '</option>'
                } else {
                    html += '<option value="' + item.id + '" data-number="'+item.number+'" data-date="'+item.date+'" data-total="'+item.total+'">' + item.number + ' | Rp ' + parseFloat(item.total).toLocaleString('en') + '</option>'
                }
            })
            $(element).html(html)
        }

        function getSalesQuotationSearch(params) {
            let element = params['element'],
                selectedValObject = params['selected_val_object'] ?? '',
                filter = params['filter'] ?? '',
                modal = params['modal'] ?? '',
                placeholderLabel = params['placeholder_label'] ?? '{{ __('language.placeholder.select', ["name" => __('language.sales_quotation')]) }}',
                isTags = parseInt(params['is_tags'] ?? 0) === 1 ? true : false;

            $(element).select2({
                width: '100%',
                // minimumInputLength: 3,
                dropdownParent: modal,
                language: {
                    inputTooShort: function(val) {
                        return 'Ketikan minimal ' + val.minimum + ' karakter.';
                    }
                },
                tags: isTags,
                placeholder: placeholderLabel,
                selectionCssClass: "select2-sm",
                dropdownCssClass: "select2-sm",
                minimumResultsForSearch: '',
                ajax: {
                    url: BASE_URL + "/api/sales_quotations?per_page=10&order[id]=desc&" + filter,
                    headers: {
                        'Authorization': TOKEN,
                    },
                    dataType: "json",
                    type: "GET",
                    delay: 300,
                    data: function(params) {
                        var queryParameters = {
                            or: {
                                number: params.term,
                                total: params.term
                            }
                        }
                        return queryParameters
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data.data, function(item) {
                                return {
                                    total: item.total,
                                    date: item.date,
                                    number: item.number,
                                    text: item.number + ' | Rp ' + parseFloat(item.total).toLocaleString('en'),
                                    id: item.id
                                }
                            })
                        }
                    }
                },
            });

            if (selectedValObject) {
                $(element).empty();
                
                if (isTags && Array.isArray(selectedValObject)) {
                    selectedValObject.forEach(function(item) {
                        let option = new Option(item.text, item.id, true, true);
                        $(element).append(option);
                    });
                } else {
                    let preselectedValue = new Option(selectedValObject.text, selectedValObject.id, true, true);
                    $(element).append(preselectedValue);
                }
            }
        }

        function getSalesDeliveries(params, callback = null) {
            
            let element = params['element'],
                isAsync = params['is_async'] ?? true,
                selectedVal = params['selected_val'] ?? '',
                filter = params['filter'] ?? '';
            $.ajax({
                url: BASE_URL + "/api/sales_deliveries?all=true&order[id]=desc"+filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    SalesDeliveries = res;
                    if (element != '' && element != undefined && element != null) {
                        buildSalesDeliveries(element, selectedVal);
                    }
                    callback && callback(SalesDeliveries);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                },
            });
        }

        function buildSalesDeliveries(element, selectedVal = "") {
            let html = ''
            html += '<option value="">{{ __('language.placeholder.select', ["name" => __('language.sales_delivery')]) }}</option>'
            $.each(SalesDeliveries, function(index, item) {
                if (selectedVal == item.id) {
                    html += '<option value="'+item.id+'" '+
                    'data-customer_name="'+(item.customer_name ?? '')+'" '+
                    'data-ref_number="'+(item.sales_order_number ?? '')+'" '+
                    'data-date="'+(item.date ?? '')+'" '+
                    'data-currency_name="'+(item.currency_name ?? '')+'" '+
                    'data-total="'+parseFloat(item.total ?? 0)+'" '+
                    'data-discount_amount="'+parseFloat(item.discount_amount ?? 0)+'" '+
                    'data-discount_type="'+(item.discount_type ?? '')+'" '+
                    'data-number="'+(item.number ?? '')+'" '+
                    'data-warehouse="'+(item.warehouse_name ?? '')+'" '+
                    'data-customer_id="'+(item.customer_id ?? '')+'" '+
                    'data-salesman_id="'+(item.salesman_id ?? '')+'" '+
                    'data-other_cost="'+parseFloat(item.other_cost ?? 0)+'" '+
                    'data-other_coa="'+(item.other_coa ?? '')+'" '+
                    'data-discount_coa="'+(item.discount_coa ?? '')+'" '+
                    'data-tax_amount="'+parseFloat(item.tax_amount ?? 0)+'" '+
                    'data-tax_percentage="'+parseFloat(item.tax_percentage ?? 0)+'" '+
                    'data-tax_coa="'+(item.tax_coa ?? '')+'" '+
                    'data-tax_id="'+(item.tax_id ?? 0)+'" '+
                    'data-discount_percentage="'+parseFloat(item.discount_percentage ?? 0)+'" selected>'+(item.number ?? '')+' | '+(moment(item.date).format('DD MMM YYYY') ?? '')+'</option>'
                } else {
                    html += '<option value="'+(item.id ?? '')+'" '+
                    'data-customer_name="'+(item.customer_name ?? '')+'" '+
                    'data-ref_number="'+(item.sales_order_number ?? '')+'" '+
                    'data-date="'+(item.date ?? '')+'" '+
                    'data-currency_name="'+(item.currency_name ?? '')+'" '+
                    'data-total="'+parseFloat(item.total ?? 0)+'" '+
                    'data-discount_amount="'+parseFloat(item.discount_amount ?? 0)+'" '+
                    'data-discount_type="'+(item.discount_type ?? '')+'" '+
                    'data-number="'+(item.number ?? '')+'" '+
                    'data-warehouse="'+(item.warehouse_name ?? '')+'" '+
                    'data-customer_id="'+(item.customer_id ?? '')+'" '+
                    'data-salesman_id="'+(item.salesman_id ?? '')+'" '+
                    'data-other_cost="'+parseFloat(item.other_cost ?? 0)+'" '+
                    'data-other_coa="'+(item.other_coa ?? '')+'" '+
                    'data-discount_coa="'+(item.discount_coa ?? '')+'" '+
                    'data-tax_amount="'+parseFloat(item.tax_amount ?? 0)+'" '+
                    'data-tax_percentage="'+parseFloat(item.tax_percentage ?? 0)+'" '+
                    'data-tax_coa="'+(item.tax_coa ?? '')+'" '+
                    'data-tax_id="'+(item.tax_id ?? 0)+'" '+
                    'data-discount_percentage="'+parseFloat(item.discount_percentage ?? 0)+'">'+(item.number ?? '')+' | '+(moment(item.date).format('DD MMM YYYY') ?? '')+'</option>'
                }
            });
            $(element).html(html)
        }

        function getSalesDeliverySearch(params) {
            let element = params['element'],
                selectedValObject = params['selected_val_object'] ?? '',
                filter = params['filter'] ?? '',
                modal = params['modal'] ?? '',
                placeholderLabel = params['placeholder_label'] ?? '{{ __('language.placeholder.select', ["name" => __('language.sales_delivery')]) }}',
                isTags = parseInt(params['is_tags'] ?? 0) === 1 ? true : false;

            $(element).select2({
                width: '100%',
                // minimumInputLength: 3,
                dropdownParent: modal,
                language: {
                    inputTooShort: function(val) {
                        return 'Ketikan minimal ' + val.minimum + ' karakter.';
                    }
                },
                tags: isTags,
                placeholder: placeholderLabel,
                selectionCssClass: "select2-sm",
                dropdownCssClass: "select2-sm",
                minimumResultsForSearch: '',
                ajax: {
                    url: BASE_URL + "/api/sales_deliveries?per_page=10&order[id]=desc&" + filter,
                    headers: {
                        'Authorization': TOKEN,
                    },
                    dataType: "json",
                    type: "GET",
                    delay: 300,
                    data: function(params) {
                        var queryParameters = {
                            or: {
                                number: params.term,
                                total: params.term
                            }
                        }
                        return queryParameters
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data.data, function(item) {
                                return {
                                    customer_name: item.customer_name,
                                    ref_number: item.sales_order_number,
                                    date: item.date,
                                    currency_name: item.currency_name,
                                    subtotal: item.subtotal,
                                    total: item.total,
                                    discount_amount: item.discount_amount,
                                    discount_type: item.discount_type,
                                    number: item.number,
                                    warehouse: item.warehouse_name,
                                    customer_id: item.customer_id,
                                    salesman_id: item.salesman_id,
                                    other_cost: item.other_cost,
                                    other_coa: item.other_coa,
                                    discount_coa: item.discount_coa,
                                    tax_amount: item.tax_amount,
                                    discount_percentage: item.discount_percentage,
                                    text: item.number  + ' | Rp ' + parseFloat(item.subtotal).toLocaleString('en'),
                                    id: item.id
                                }
                            })
                        }
                    }
                },
            });

            if (selectedValObject) {
                $(element).empty();
                
                if (isTags && Array.isArray(selectedValObject)) {
                    selectedValObject.forEach(function(item) {
                        let option = new Option(item.text, item.id, true, true);
                        $(element).append(option);
                    });
                } else {
                    let preselectedValue = new Option(selectedValObject.text, selectedValObject.id, true, true);
                    $(element).append(preselectedValue);
                }
            }
        }

        function getVariants(params, callback = null) {
            
            let element = params['element'],
                isAsync = params['is_async'] ?? true,
                selectedVal = params['selected_val'] ?? '',
                filter = params['filter'] ?? '';
            $.ajax({
                url: BASE_URL + "/api/variants?all=true&order[id]=desc"+filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    Variants = res;
                    if (element != '' && element != undefined && element != null) {
                        buildVariants(element, selectedVal);
                    }
                    callback && callback(Variants);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                },
            });
        }

        function buildVariants(element, selectedVal = "") {
            let html = ''
            html += '<option value="">{{ __('language.placeholder.select', ["name" => __('language.variant')]) }}</option>'
            $.each(Variants, function(index, item) {
                if (selectedVal == item.id) {
                    html += '<option value="' + item.id + '" selected>' + item.name + '</option>'
                } else {
                    html += '<option value="' + item.id + '">' + item.name + '</option>'
                }
            })
            $(element).html(html)
        }

        function getVariantSearch(params) {
            let element = params['element'],
                selectedValObject = params['selected_val_object'] ?? '',
                filter = params['filter'] ?? '',
                modal = params['modal'] ?? '',
                placeholderLabel = params['placeholder_label'] ?? '{{ __('language.placeholder.select', ["name" => __('language.variant')]) }}',
                isTags = parseInt(params['is_tags'] ?? 0) === 1 ? true : false;

            $(element).select2({
                width: '100%',
                // minimumInputLength: 3,
                dropdownParent: modal,
                language: {
                    inputTooShort: function(val) {
                        return 'Ketikan minimal ' + val.minimum + ' karakter.';
                    }
                },
                tags: isTags,
                placeholder: placeholderLabel,
                selectionCssClass: "select2-sm",
                dropdownCssClass: "select2-sm",
                minimumResultsForSearch: '',
                ajax: {
                    url: BASE_URL + "/api/variants?per_page=10&order[id]=desc&" + filter,
                    headers: {
                        'Authorization': TOKEN,
                    },
                    dataType: "json",
                    type: "GET",
                    delay: 300,
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
                                    name: item.name,
                                    text: item.name,
                                    id: item.id
                                }
                            })
                        }
                    }
                },
            });

            if (selectedValObject) {
                $(element).empty();
                
                if (isTags && Array.isArray(selectedValObject)) {
                    selectedValObject.forEach(function(item) {
                        let option = new Option(item.text, item.id, true, true);
                        $(element).append(option);
                    });
                } else {
                    let preselectedValue = new Option(selectedValObject.text, selectedValObject.id, true, true);
                    $(element).append(preselectedValue);
                }
            }
        }

        function getProductVariantSearch(params) {
            let element = params['element'],
                selectedValObject = params['selected_val_object'] ?? '',
                filter = params['filter'] ?? '',
                modal = params['modal'] ?? '',
                placeholderLabel = params['placeholder_label'] ?? '{{ __('language.placeholder.select', ["name" => __('language.variant')]) }}',
                isTags = parseInt(params['is_tags'] ?? 0) === 1 ? true : false;

            $(element).select2({
                width: '100%',
                // minimumInputLength: 3,
                dropdownParent: modal,
                language: {
                    inputTooShort: function(val) {
                        return 'Ketikan minimal ' + val.minimum + ' karakter.';
                    }
                },
                tags: isTags,
                placeholder: placeholderLabel,
                selectionCssClass: "select2-sm",
                dropdownCssClass: "select2-sm",
                minimumResultsForSearch: '',
                ajax: {
                    url: BASE_URL + "/api/product_variants?per_page=10&order[id]=desc&" + filter,
                    headers: {
                        'Authorization': TOKEN,
                    },
                    dataType: "json",
                    type: "GET",
                    delay: 300,
                    data: function(params) {
                        var queryParameters = {
                            name: params.term
                        }
                        return queryParameters
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    variant_option_id: item.variant_option_id,
                                    variant_id: item.variant_id,
                                    variant_option_name: item.variant_option_name,
                                    variant_name: item.variant_name,
                                    text: item.variant_name + ' ' + item.variant_option_name,
                                    id: item.variant_option_id
                                }
                            })
                            // results: $.map(data.data, function(item) {
                            //     return {
                            //         name: item.name,
                            //         text: item.name,
                            //         id: item.id
                            //     }
                            // })
                        }
                    }
                },
            });

            if (selectedValObject) {
                $(element).empty();
                
                if (isTags && Array.isArray(selectedValObject)) {
                    selectedValObject.forEach(function(item) {
                        let option = new Option(item.text, item.id, true, true);
                        $(element).append(option);
                    });
                } else {
                    let preselectedValue = new Option(selectedValObject.text, selectedValObject.id, true, true);
                    $(element).append(preselectedValue);
                }
            }
        }

        function getProductSkuSearch(params) {
            let element = params['element'],
                selectedValObject = params['selected_val_object'] ?? '',
                filter = params['filter'] ?? '',
                modal = params['modal'] ?? '',
                placeholderLabel = params['placeholder_label'] ?? '{{ __('language.placeholder.select', ["name" => __('language.variant')]) }}',
                isTags = parseInt(params['is_tags'] ?? 0) === 1 ? true : false;

            $(element).select2({
                width: '100%',
                // minimumInputLength: 3,
                dropdownParent: modal,
                language: {
                    inputTooShort: function(val) {
                        return 'Ketikan minimal ' + val.minimum + ' karakter.';
                    }
                },
                tags: isTags,
                placeholder: placeholderLabel,
                selectionCssClass: "select2-sm",
                dropdownCssClass: "select2-sm",
                minimumResultsForSearch: '',
                ajax: {
                    url: BASE_URL + "/api/product_skus?per_page=10&order[id]=desc&" + filter,
                    headers: {
                        'Authorization': TOKEN,
                    },
                    dataType: "json",
                    type: "GET",
                    delay: 300,
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
                                    code: item.sku_code,
                                    alias: item.alias,
                                    text: item.sku_code + ' | ' +item.alias,
                                    id: item.id
                                }
                            })
                        }
                    }
                },
            });

            if (selectedValObject) {
                $(element).empty();
                
                if (isTags && Array.isArray(selectedValObject)) {
                    selectedValObject.forEach(function(item) {
                        let option = new Option(item.text, item.id, true, true);
                        $(element).append(option);
                    });
                } else {
                    let preselectedValue = new Option(selectedValObject.text, selectedValObject.id, true, true);
                    $(element).append(preselectedValue);
                }
            }
        }

        function getPurchaseRequests(params, callback = null) {
            
            let element = params['element'],
                isAsync = params['is_async'] ?? true,
                selectedVal = params['selected_val'] ?? '',
                filter = params['filter'] ?? '';
            $.ajax({
                url: BASE_URL + "/api/purchase_requests?all=true&order[id]=desc"+filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    VariantOptions = res;
                    if (element != '' && element != undefined && element != null) {
                        buildPurchaseRequests(element, selectedVal);
                    }
                    callback && callback(VariantOptions);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                },
            });
        }

        function buildPurchaseRequests(element, selectedVal = "") {
            let html = ''
            html += '<option value="">{{ __('language.placeholder.select_with_number', ["name" => __('language.request_material')]) }}</option>'
            $.each(PurchaseRequests, function(index, item) {
                if (selectedVal == item.id) {
                    html += '<option value="' + item.id + '" data-number="'+item.number+'" data-date="'+item.date+'" data-total="'+item.total+'" selected>' + item.number + ' | Rp ' + parseFloat(item.total).toLocaleString('en') + '</option>'
                } else {
                    html += '<option value="' + item.id + '" data-number="'+item.number+'" data-date="'+item.date+'" data-total="'+item.total+'">' + item.number + ' | Rp ' + parseFloat(item.total).toLocaleString('en') + '</option>'
                }
            })
            $(element).html(html)
        }

        function getPurchaseRequestSearch(params) {
            let element = params['element'],
                selectedValObject = params['selected_val_object'] ?? '',
                filter = params['filter'] ?? '',
                modal = params['modal'] ?? '',
                placeholderLabel = params['placeholder_label'] ?? '{{ __('language.placeholder.select', ["name" => __('language.purchase_request')]) }}',
                isTags = parseInt(params['is_tags'] ?? 0) === 1 ? true : false;

            $(element).select2({
                width: '100%',
                // minimumInputLength: 3,
                dropdownParent: modal,
                language: {
                    inputTooShort: function(val) {
                        return 'Ketikan minimal ' + val.minimum + ' karakter.';
                    }
                },
                tags: isTags,
                placeholder: placeholderLabel,
                selectionCssClass: "select2-sm",
                dropdownCssClass: "select2-sm",
                minimumResultsForSearch: '',
                ajax: {
                    url: BASE_URL + "/api/purchase_requests?per_page=10&order[id]=desc&" + filter,
                    headers: {
                        'Authorization': TOKEN,
                    },
                    dataType: "json",
                    type: "GET",
                    delay: 300,
                    data: function(params) {
                        var queryParameters = {
                            or: {
                                number: params.term,
                                total: params.term
                            }
                        }
                        return queryParameters
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data.data, function(item) {
                                return {
                                    total: item.total,
                                    date: item.date,
                                    number: item.number,
                                    text: item.number + ' | ' + moment(item.date).format('DD MMM YYYY'),
                                    id: item.id
                                }
                            })
                        }
                    }
                },
            });

            if (selectedValObject) {
                $(element).empty();
                
                if (isTags && Array.isArray(selectedValObject)) {
                    selectedValObject.forEach(function(item) {
                        let option = new Option(item.text, item.id, true, true);
                        $(element).append(option);
                    });
                } else {
                    let preselectedValue = new Option(selectedValObject.text, selectedValObject.id, true, true);
                    $(element).append(preselectedValue);
                }
            }
        }

        function getVariantOptions(params, callback = null) {
            
            let element = params['element'],
                isAsync = params['is_async'] ?? true,
                selectedVal = params['selected_val'] ?? '',
                filter = params['filter'] ?? '';
            $.ajax({
                url: BASE_URL + "/api/variant_options?all=true&order[id]=desc"+filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    VariantOptions = res;
                    if (element != '' && element != undefined && element != null) {
                        buildVariantOptions(element, selectedVal);
                    }
                    callback && callback(VariantOptions);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                },
            });
        }

        function buildVariantOptions(element, selectedVal = "") {
            let html = ''
            html += '<option value="">{{ __('language.placeholder.select', ["name" => __('language.variant_product')]) }}</option>'
            $.each(VariantOptions, function(index, item) {
                if (selectedVal == item.id) {
                    html += '<option value="' + item.id + '" selected>' + item.value + '</option>'
                } else {
                    html += '<option value="' + item.id + '">' + item.value + '</option>'
                }
            })
            $(element).html(html)
        }

        function getVariantOptionSearch(params) {
            let element = params['element'],
                selectedValObject = params['selected_val_object'] ?? '',
                filter = params['filter'] ?? '',
                modal = params['modal'] ?? '',
                placeholderLabel = params['placeholder_label'] ?? '{{ __('language.placeholder.select', ["name" => __('language.variant_option')]) }}',
                isTags = parseInt(params['is_tags'] ?? 0) === 1 ? true : false;

            $(element).select2({
                width: '100%',
                // minimumInputLength: 3,
                dropdownParent: modal,
                language: {
                    inputTooShort: function(val) {
                        return 'Ketikan minimal ' + val.minimum + ' karakter.';
                    }
                },
                tags: isTags,
                placeholder: placeholderLabel,
                selectionCssClass: "select2-sm",
                dropdownCssClass: "select2-sm",
                minimumResultsForSearch: '',
                ajax: {
                    url: BASE_URL + "/api/variant_options?per_page=10&order[id]=desc&" + filter,
                    headers: {
                        'Authorization': TOKEN,
                    },
                    dataType: "json",
                    type: "GET",
                    delay: 300,
                    data: function(params) {
                        var queryParameters = {
                            value: params.term
                        }
                        return queryParameters
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data.data, function(item) {
                                return {
                                    value: item.value,
                                    text: item.value,
                                    id: item.id
                                }
                            })
                        }
                    }
                },
            });

            if (selectedValObject) {
                $(element).empty();
                
                // if (isTags && Array.isArray(selectedValObject)) {
                if (Array.isArray(selectedValObject)) {
                    selectedValObject.forEach(function(item) {
                        let option = new Option(item.text, item.id, true, true);
                        $(element).append(option);
                    });
                } else {
                    let preselectedValue = new Option(selectedValObject.text, selectedValObject.id, true, true);
                    $(element).append(preselectedValue);
                }
            }
        }

        function getPurchaseQuotations(params, callback = null) {
            let element = params['element'],
                isAsync = params['is_async'] ?? true,
                selectedVal = params['selected_val'] ?? '',
                filter = params['filter'] ?? '';
            $.ajax({
                url: BASE_URL + "/api/purchase_quotations?all=true&order[id]=desc"+filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    PurchaseQuotations = res;
                    if (element != '' && element != undefined && element != null) {
                        buildPurchaseQuotations(element, selectedVal);
                    }
                    callback && callback(PurchaseQuotations);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                },
            });
        }

        function buildPurchaseQuotations(element, selectedVal = "") {
            let html = ''
            html += '<option value="">{{ __('language.placeholder.select', ["name" => __('language.purchase_quotation')]) }}</option>'
            $.each(PurchaseQuotations, function(index, item) {
                if (selectedVal == item.id) {
                    html += '<option value="' + item.id + '" data-number="'+item.number+'" data-date="'+item.date+'" data-total="'+item.total+'" selected>' + item.number + ' | Rp ' + parseFloat(item.total).toLocaleString('en') + '</option>'
                } else {
                    html += '<option value="' + item.id + '" data-number="'+item.number+'" data-date="'+item.date+'" data-total="'+item.total+'">' + item.number + ' | Rp ' + parseFloat(item.total).toLocaleString('en') + '</option>'
                }
            })
            $(element).html(html)
        }

        function getPurchaseQuotationSearch(params) {
            let element = params['element'],
                selectedValObject = params['selected_val_object'] ?? '',
                filter = params['filter'] ?? '',
                modal = params['modal'] ?? '',
                placeholderLabel = params['placeholder_label'] ?? '{{ __('language.placeholder.select', ["name" => __('language.purchase_quotation')]) }}',
                isTags = parseInt(params['is_tags'] ?? 0) === 1 ? true : false;

            $(element).select2({
                width: '100%',
                // minimumInputLength: 3,
                dropdownParent: modal,
                language: {
                    inputTooShort: function(val) {
                        return 'Ketikan minimal ' + val.minimum + ' karakter.';
                    }
                },
                tags: isTags,
                placeholder: placeholderLabel,
                selectionCssClass: "select2-sm",
                dropdownCssClass: "select2-sm",
                minimumResultsForSearch: '',
                ajax: {
                    url: BASE_URL + "/api/purchase_quotations?per_page=10&order[id]=desc&&" + filter,
                    headers: {
                        'Authorization': TOKEN,
                    },
                    dataType: "json",
                    type: "GET",
                    delay: 300,
                    data: function(params) {
                        var queryParameters = {
                            or: {
                                number: params.term,
                                total: params.term
                            }
                        }
                        return queryParameters
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data.data, function(item) {
                                return {
                                    total: item.total,
                                    date: item.date,
                                    number: item.number,
                                    text: item.number + ' | Rp ' + parseFloat(item.total).toLocaleString('en'),
                                    id: item.id
                                }
                            })
                        }
                    }
                },
            });

            if (selectedValObject) {
                $(element).empty();
                
                if (isTags && Array.isArray(selectedValObject)) {
                    selectedValObject.forEach(function(item) {
                        let option = new Option(item.text, item.id, true, true);
                        $(element).append(option);
                    });
                } else {
                    let preselectedValue = new Option(selectedValObject.text, selectedValObject.id, true, true);
                    $(element).append(preselectedValue);
                }
            }
        }
        
        function getPurchaseReceipts(params, callback = null) {
            
            let element = params['element'],
                isAsync = params['is_async'] ?? true,
                selectedVal = params['selected_val'] ?? '',
                filter = params['filter'] ?? '';
            $.ajax({
                url: BASE_URL + "/api/purchase_receipts?all=true&order[id]=desc"+filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    PurchaseReceipts = res;
                    if (element != '' && element != undefined && element != null) {
                        buildPurchaseReceipts(element, selectedVal);
                    }
                    callback && callback(PurchaseReceipts);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                },
            });
        }

        function buildPurchaseReceipts(element, selectedVal = "") {
            let html = ''
            html += '<option value="">{{ __('language.placeholder.select_with_number', ["name" => __('language.purchase_receipt')]) }}</option></option>'
            $.each(PurchaseReceipts, function(index, item) {
                if (selectedVal == item.id) {
                    html += '<option value="' + item.id + '" data-number="'+item.number+'" data-date="'+item.date+'" selected>' + item.number +' | ' + moment(item.date).format('DD MMM YYYY') +'</option>'
                } else {
                    html += '<option value="' + item.id + '" data-number="'+item.number+'" data-date="'+item.date+'">' + item.number +' | ' + moment(item.date).format('DD MMM YYYY') +'</option>'
                }
            })
            $(element).html(html)
        }

        function getPurchaseReceiptSearch(params) {
            let element = params['element'],
                selectedValObject = params['selected_val_object'] ?? '',
                filter = params['filter'] ?? '',
                modal = params['modal'] ?? '',
                placeholderLabel = params['placeholder_label'] ?? '{{ __('language.placeholder.select', ["name" => __('language.purchase_receipt')]) }}',
                isTags = parseInt(params['is_tags'] ?? 0) === 1 ? true : false;

            $(element).select2({
                width: '100%',
                // minimumInputLength: 3,
                dropdownParent: modal,
                language: {
                    inputTooShort: function(val) {
                        return 'Ketikan minimal ' + val.minimum + ' karakter.';
                    }
                },
                tags: isTags,
                placeholder: placeholderLabel,
                selectionCssClass: "select2-sm",
                dropdownCssClass: "select2-sm",
                minimumResultsForSearch: '',
                ajax: {
                    url: BASE_URL + "/api/purchase_receipts?per_page=10&order[id]=desc&" + filter,
                    headers: {
                        'Authorization': TOKEN,
                    },
                    dataType: "json",
                    type: "GET",
                    delay: 300,
                    data: function(params) {
                        var queryParameters = {
                            or: {
                                number: params.term,
                                date: params.term
                            }
                        }
                        return queryParameters
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data.data, function(item) {
                                return {
                                    supplier_name: item.supplier_name,
                                    ref_number: item.purchase_order_number,
                                    date: item.date,
                                    currency_name: item.currency_name,
                                    subtotal: item.subtotal,
                                    total: item.total,
                                    discount_amount: item.discount_amount,
                                    discount_type: item.discount_type,
                                    number: item.number,
                                    warehouse: item.warehouse_name,
                                    supplier_id: item.supplier_id,
                                    other_cost: item.other_cost,
                                    other_coa: item.other_coa,
                                    discount_coa: item.discount_coa,
                                    tax_amount: item.tax_amount,
                                    discount_percentage: item.discount_percentage,
                                    text: item.number  + ' | Rp ' + parseFloat(item.subtotal).toLocaleString('en'),
                                    id: item.id
                                }
                            })
                        }
                    }
                },
            });

            if (selectedValObject) {
                $(element).empty();
                
                if (isTags && Array.isArray(selectedValObject)) {
                    selectedValObject.forEach(function(item) {
                        let option = new Option(item.text, item.id, true, true);
                        $(element).append(option);
                    });
                } else {
                    let preselectedValue = new Option(selectedValObject.text, selectedValObject.id, true, true);
                    $(element).append(preselectedValue);
                }
            }
        }

        function getFixedAssetCategories(params, callback = null) {
            
            let element = params['element'],
                isAsync = params['is_async'] ?? true,
                selectedVal = params['selected_val'] ?? '',
                filter = params['filter'] ?? '';
            $.ajax({
                url: BASE_URL + "/api/fixed_asset_categories?all=true&order[id]=desc"+filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    FixedAssetCategories = res;
                    if (element != '' && element != undefined && element != null) {
                        buildFixedAssetCategories(element, selectedVal);
                    }
                    callback && callback(FixedAssetCategories);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                },
            });
        }

        function buildFixedAssetCategories(element, selectedVal = "") {
            let html = ''
            html += '<option value="">{{ __('language.placeholder.select', ["name" => __('language.fixed_asset_category')]) }}</option>'
            $.each(FixedAssetCategories, function(index, item) {
                if (selectedVal == item.id) {
                    html += '<option value="' + item.id + '" selected>' + item.name + '</option>'
                } else {    
                    html += '<option value="' + item.id + '">' + item.name + '</option>'
                }
            })
            $(element).html(html)
        }

        function getFixedAssetCategorySearch(params) {
            let element = params['element'],
                selectedValObject = params['selected_val_object'] ?? '',
                filter = params['filter'] ?? '',
                modal = params['modal'] ?? '',
                placeholderLabel = params['placeholder_label'] ?? '{{ __('language.placeholder.select', ["name" => __('language.fixed_asset_category')]) }}',
                isTags = parseInt(params['is_tags'] ?? 0) === 1 ? true : false;

            $(element).select2({
                width: '100%',
                // minimumInputLength: 3,
                dropdownParent: modal,
                language: {
                    inputTooShort: function(val) {
                        return 'Ketikan minimal ' + val.minimum + ' karakter.';
                    }
                },
                tags: isTags,
                placeholder: placeholderLabel,
                selectionCssClass: "select2-sm",
                dropdownCssClass: "select2-sm",
                minimumResultsForSearch: '',
                ajax: {
                    url: BASE_URL + "/api/fixed_asset_categories?per_page=10&order[id]=desc&" + filter,
                    headers: {
                        'Authorization': TOKEN,
                    },
                    dataType: "json",
                    type: "GET",
                    delay: 300,
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
                                    name: item.name,
                                    text: item.name,
                                    id: item.id
                                }
                            })
                        }
                    }
                },
            });

            if (selectedValObject) {
                $(element).empty();
                
                if (isTags && Array.isArray(selectedValObject)) {
                    selectedValObject.forEach(function(item) {
                        let option = new Option(item.text, item.id, true, true);
                        $(element).append(option);
                    });
                } else {
                    let preselectedValue = new Option(selectedValObject.text, selectedValObject.id, true, true);
                    $(element).append(preselectedValue);
                }
            }
        }

        function getFixedAssetSources(params, callback = null) {
            let element = params['element'],
                isAsync = params['is_async'] ?? true,
                selectedVal = params['selected_val'] ?? '';

            $.ajax({
                url: BASE_URL + "/api/fixed_asset_sources",
                type: 'GET',
                headers: { Authorization: TOKEN },
                dataType: 'JSON',
                async: isAsync,
                success: function (res) {
                    fixedAssetSources = res;
                    if (element) {
                        buildFixedAssetSources(element, selectedVal);
                    }
                    callback && callback(fixedAssetSources);
                }
            });
        }

        function buildFixedAssetSources(element, selectedVal = '') {
            let html = '<option value="">{{ __('language.placeholder.select', ["name" => __('language.fixed_asset_source')]) }}</option>';

            $.each(fixedAssetSources, function (i, item) {
                let value = item.source_type + '|' + item.source_id;
                let label = item.number + ' (Rp. ' + parseFloat(item.total).toLocaleString('id-ID') + ')';

                let selected = selectedVal == item.source_id ? 'selected' : '';

                html += `<option value="${value}" data-model_id="${item.source_id}" data-type="${item.source_type}" data-total="${item.total}" ${selected}>${label}</option>`;
            });

            $(element).html(html);
        }

        function getFixedAssetSourceSearch(params) {
            let element = params['element'],
                selectedValObject = params['selected_val_object'] ?? '',
                filter = params['filter'] ?? '',
                modal = params['modal'] ?? '',
                placeholderLabel = params['placeholder_label'] ?? '{{ __('language.placeholder.select', ["name" => __('language.fixed_asset_source')]) }}',
                isTags = parseInt(params['is_tags'] ?? 0) === 1 ? true : false;

            $(element).select2({
                width: '100%',
                // minimumInputLength: 3,
                dropdownParent: modal,
                language: {
                    inputTooShort: function(val) {
                        return 'Ketikan minimal ' + val.minimum + ' karakter.';
                    }
                },
                tags: isTags,
                placeholder: placeholderLabel,
                selectionCssClass: "select2-sm",
                dropdownCssClass: "select2-sm",
                minimumResultsForSearch: '',
                ajax: {
                    url: BASE_URL + "/api/fixed_asset_sources?per_page=10&order[id]=desc&" + filter,
                    headers: {
                        'Authorization': TOKEN,
                    },
                    dataType: "json",
                    type: "GET",
                    delay: 300,
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
                                    name: item.name,
                                    text: item.name,
                                    id: item.id
                                }
                            })
                        }
                    }
                },
            });

            if (selectedValObject) {
                $(element).empty();
                
                if (isTags && Array.isArray(selectedValObject)) {
                    selectedValObject.forEach(function(item) {
                        let option = new Option(item.text, item.id, true, true);
                        $(element).append(option);
                    });
                } else {
                    let preselectedValue = new Option(selectedValObject.text, selectedValObject.id, true, true);
                    $(element).append(preselectedValue);
                }
            }
        }

        function getSalesInvoiceSearch(params) {
            let element = params['element'],
                selectedValObject = params['selected_val_object'] ?? '',
                filter = params['filter'] ?? '',
                modal = params['modal'] ?? '',
                placeholderLabel = params['placeholder_label'] ?? '{{ __('language.placeholder.select', ["name" => __('language.sales_invoice')]) }}',
                isTags = parseInt(params['is_tags'] ?? 0) === 1 ? true : false;

            $(element).select2({
                width: '100%',
                // minimumInputLength: 3,
                dropdownParent: modal,
                language: {
                    inputTooShort: function(val) {
                        return 'Ketikan minimal ' + val.minimum + ' karakter.';
                    }
                },
                tags: isTags,
                placeholder: placeholderLabel,
                selectionCssClass: "select2-sm",
                dropdownCssClass: "select2-sm",
                minimumResultsForSearch: '',
                ajax: {
                    url: BASE_URL + "/api/sales_invoices?per_page=10&order[id]=desc&" + filter,
                    headers: {
                        'Authorization': TOKEN,
                    },
                    dataType: "json",
                    type: "GET",
                    delay: 300,
                    data: function(params) {
                        var queryParameters = {
                            or: {
                                number: params.term,
                                total: params.term
                            }
                        }
                        return queryParameters
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data.data, function(item) {
                                return {
                                    total: item.total,
                                    date: item.date,
                                    number: item.number,
                                    text: item.number + ' | Rp ' + parseFloat(item.total).toLocaleString('en'),
                                    id: item.id
                                }
                            })
                        }
                    }
                },
            });

            if (selectedValObject) {
                $(element).empty();
                
                if (isTags && Array.isArray(selectedValObject)) {
                    selectedValObject.forEach(function(item) {
                        let option = new Option(item.text, item.id, true, true);
                        $(element).append(option);
                    });
                } else {
                    let preselectedValue = new Option(selectedValObject.text, selectedValObject.id, true, true);
                    $(element).append(preselectedValue);
                }
            }
        }

        function getPurchaseInvoiceSearch(params) {
            let element = params['element'],
                selectedValObject = params['selected_val_object'] ?? '',
                filter = params['filter'] ?? '',
                modal = params['modal'] ?? '',
                placeholderLabel = params['placeholder_label'] ?? '{{ __('language.placeholder.select', ["name" => __('language.purchase_invoice')]) }}',
                isTags = parseInt(params['is_tags'] ?? 0) === 1 ? true : false;

            $(element).select2({
                width: '100%',
                // minimumInputLength: 3,
                dropdownParent: modal,
                language: {
                    inputTooShort: function(val) {
                        return 'Ketikan minimal ' + val.minimum + ' karakter.';
                    }
                },
                tags: isTags,
                placeholder: placeholderLabel,
                selectionCssClass: "select2-sm",
                dropdownCssClass: "select2-sm",
                minimumResultsForSearch: '',
                ajax: {
                    url: BASE_URL + "/api/purchase_invoices?per_page=10&order[id]=desc&" + filter,
                    headers: {
                        'Authorization': TOKEN,
                    },
                    dataType: "json",
                    type: "GET",
                    delay: 300,
                    data: function(params) {
                        var queryParameters = {
                            or: {
                                number: params.term,
                                total: params.term
                            }
                        }
                        return queryParameters
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data.data, function(item) {
                                return {
                                    total: item.total,
                                    date: item.date,
                                    number: item.number,
                                    text: item.number + ' | Rp ' + parseFloat(item.total).toLocaleString('en'),
                                    id: item.id
                                }
                            })
                        }
                    }
                },
            });

            if (selectedValObject) {
                $(element).empty();
                
                if (isTags && Array.isArray(selectedValObject)) {
                    selectedValObject.forEach(function(item) {
                        let option = new Option(item.text, item.id, true, true);
                        $(element).append(option);
                    });
                } else {
                    let preselectedValue = new Option(selectedValObject.text, selectedValObject.id, true, true);
                    $(element).append(preselectedValue);
                }
            }
        }

        function getBankAccounts(params, callback = null) {
            
            let element = params['element'],
                isAsync = params['is_async'] ?? true,
                selectedVal = params['selected_val'] ?? '',
                filter = params['filter'] ?? '';
            $.ajax({
                url: BASE_URL + "/api/bank_accounts?all=true&order[id]=desc"+filter,
                type: 'GET',
                headers: {
                    'Authorization': TOKEN
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR) {
                    BankAccounts = res;
                    if (element != '' && element != undefined && element != null) {
                        buildBankAccounts(element, selectedVal);
                    }
                    callback && callback(BankAccounts);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                },
            });
        }

        function buildBankAccounts(element, selectedVal = "") {
            let html = ''
            html += '<option value="">{{ __('language.placeholder.select', ["name" => __('language.bank_account')]) }}</option>'
            $.each(BankAccounts, function(index, item) {
                if (selectedVal == item.id) {
                    html += '<option value="' + item.id + '" data-bank_name="'+item.bank_name+'" data-code="'+item.code+'" data-account_number="'+item.account_number+'" data-coa="'+item.coa+'" selected>' + item.bank_name + '</option>'
                } else {
                    html += '<option value="' + item.id + '" data-bank_name="'+item.bank_name+'" data-code="'+item.code+'" data-account_number="'+item.account_number+'" data-coa="'+item.coa+'">' + item.bank_name + '</option>'
                }
            })
            $(element).html(html)
        }

        async function urlToFile(url, fileName, mimeType) {
            const response = await fetch(url);
            const blob = await response.blob();
            return new File([blob], fileName, { type: mimeType });
        }

        $(document).on('change', '.input-single-file-group input[type="file"]:not([multiple])', function () {
            const _this = $(this);
            const file = this.files[0];
            const parent = _this.closest('.input-single-file-group');
            const filledElement = parent.find('[data-tag="filled"]');
            const clearButtonElement = parent.find('button[data-tag="clear-button"]');
            const imgPreview = parent.find('img[data-tag="image-preview"]');
            if (file) {
                const fileName = file.name;
                const fileURL = URL.createObjectURL(file);                
                
                filledElement.attr('data-url', fileURL);
                filledElement.html(fileName);
                _this.addClass('d-none');
                filledElement.removeClass('d-none');
                clearButtonElement.removeClass('d-none');
                
                if (imgPreview) {
                    imgPreview.removeClass('d-none');
                    imgPreview.attr('src', fileURL);
                }
            } else {
                _this.removeClass('d-none');
                filledElement.addClass('d-none');
                clearButtonElement.addClass('d-none');
                if (imgPreview) {
                    imgPreview.addClass('d-none');
                    imgPreview.attr('src', '');
                }
            }
        });

        $(document).on('click', '.input-single-file-group button[data-tag="clear-button"]', function () {
            const _this = $(this);
            const parent = _this.closest('.input-single-file-group');
            const filledElement = parent.find('[data-tag="filled"]');
            const inputElement = parent.find('input[type="file"][data-tag="input"]:not([multiple])');
            const imgPreview = parent.find('img[data-tag="image-preview"]');
            filledElement.removeAttr('data-url');
            filledElement.html('URL not found');
            _this.addClass('d-none');
            filledElement.addClass('d-none');
            inputElement.removeClass('d-none');
            inputElement.val(null)
            inputElement.trigger('change');
            if (imgPreview) {
                imgPreview.addClass('d-none');
                imgPreview.attr('src', '');
            }
        });

        $(document).on('click', '.input-single-file-group button[data-tag="filled"]', function () {
            const _this = $(this);
            const url = _this.attr('data-url');
            
            if (url) {
                window.open(url, '_blank');
            }
        });
        function initTimePicker(selector, defaultTime = '00:00') {
            $(selector).flatpickr({
                enableTime: true,
                noCalendar: true,       // ⬅️ hanya waktu, tanpa tanggal
                dateFormat: "H:i",      // Format 24 jam (contoh: 14:30)
                time_24hr: true,
                defaultDate: defaultTime ?? null, // bisa set jam default
                minuteIncrement: 1,     // langkah menit
            });
        }
        
        function dateReady(elm,mode = 'single'){
            $(elm).flatpickr({
                mode: mode,
                dateFormat: "Y-m-d",
                closeOnSelect: true,
                allowInput: false,
                nextArrow: '<i class="fa fa-angle-right"></i>',
                prevArrow: '<i class="fa fa-angle-left"></i>'
            });
        }

        function getNextCode(params, callback = null) {
            let element = params['element'],
                isAsync = params['is_async'] ?? true,
                selectedVal = params['selected_val'] ?? '',
                filter = params['filter'] ?? '';
                
            $.ajax({
                url: BASE_URL+"/api/generate_next_code?"+filter,
                type: 'GET',
                headers: { 
                    'Authorization': TOKEN,
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR){
                    if (element != '' && element != undefined && element != null) {
                        $(element).val(res.code);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown){
                },
            });
        }

        function getAutoNumber(params, callback = null) {
            let element = params['element'],
                isAsync = params['is_async'] ?? true,
                selectedVal = params['selected_val'] ?? '',
                filter = params['filter'] ?? '';
                
            $.ajax({
                url: BASE_URL+"/api/generate_numbers?"+filter,
                type: 'GET',
                headers: { 
                    'Authorization': TOKEN,
                },
                dataType: 'JSON',
                async: isAsync,
                success: function(res, textStatus, jqXHR){
                    if (element != '' && element != undefined && element != null) {
                        $(element).val(res.number);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown){
                },
            });
        }

        function addThousandSeparator() {
            $(".tseparator").each(function() {
                $(this).on("blur", function(e) {
                    var val = this.value;

                    $(this).val(addSeparator(val));
                });

                $(this).on("focus", function(e) {
                    var val = this.value;

                    $(this).val(detectFloat(val));
                    $(this).select();
                });
            });
        }

        // function addThousandSeparator() {
        //     $(document).on('keyup', '.tseparator', function (e) {
        //         // skip tombol navigasi
        //         if (
        //             e.key === 'ArrowLeft' ||
        //             e.key === 'ArrowRight' ||
        //             e.key === 'Backspace' ||
        //             e.key === 'Delete'
        //         ) {
        //             return;
        //         }

        //         let value = this.value.replace(/[^\d]/g, '');
        //         if (value === '') value = '0';

        //         this.value = addSeparator(value);
        //     });
        // }
       
        function formatNumber(value) {
            return new Intl.NumberFormat('id-ID').format(value);
        }

        function parseNumber(val = '0') {
            let valString = val;
            if (typeof val === 'number') {
                valString = val.toString();
            }
            return parseFloat(valString.replace(/,/g, '')) || 0;
        }

        function detectFloat(source) {
            let float = accounting.unformat(source);
            let posComma = source.indexOf('.');
            if (posComma > -1) {
                let posDot = source.indexOf(',');
                if (posDot > -1 && posComma > posDot) {
                    let germanFloat = accounting.unformat(source, '.');
                    if (Math.abs(germanFloat) > Math.abs(float)) {
                        float = germanFloat;
                    }
                } else {
                    // source = source.replace(/,/g, '.');
                    float = accounting.unformat(source, '.');
                }
            }
            return float;
        }

        function addSeparator(nStr, inD = '.', outD = '.', sep = ',') {
            nStr += '';
            var dpos = nStr.indexOf(inD);
            var nStrEnd = '';
            if (dpos != -1) {
                nStrEnd = outD + nStr.substring(dpos + 1, nStr.length);
                nStr = nStr.substring(0, dpos);
            }
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(nStr)) {
                nStr = nStr.replace(rgx, '$1' + sep + '$2');
            }
            return nStr + nStrEnd;
        }

        function initTooltip() {
            $('[data-bs-toggle="tooltip"]').tooltip();
        }

        function elementOnChange(element, callback) {
            $(element).on('change', function(e) {
                callback && callback($(this));
            });
        }

        function calculatePercentageChange(previousValue, currentValue) {
            previousValue = parseFloat(previousValue) || 0;
            currentValue = parseFloat(currentValue) || 0;

            if (previousValue === 0 && currentValue === 0) {
                return '0';
            }

            if (previousValue === 0) {
                return '100';
            }

            let change = currentValue - previousValue;
            let percentage = (change / previousValue) * 100;

            return Math.abs(percentage.toFixed(2)).toLocaleString('en');
        }

        function capitalize(text){
            if(!text) return '';
            return text.charAt(0).toUpperCase() + text.slice(1).toLowerCase();
        }

        $(document).on('click', '#reset-all-data-toggle', function (e) {
            e.preventDefault();

            Swal.fire({
                title: '{{ __('language.confirm.risk_action.title') }}',
                html: '{{ __('language.confirm.risk_action.text') }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: 'var(--bs-success)',
                cancelButtonColor: 'var(--bs-danger)',
                confirmButtonText: '{{ __('language.yes') }}<span>',
                cancelButtonText: '{{ __('language.cancel') }}<span>',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: BASE_URL + '/api/reset_data',
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType: 'JSON',
                        headers: {
                            'Authorization': TOKEN
                        },
                        beforeSend: function () {
                            showLoading()
                        },
                        success: function(res) {
                            Swal.close();
                            showAlertOnSubmit(res, null, null, '{{ url()->current() }}');
                        },
                        error: generalAjaxErrorHandler
                    })
                } else {
                    Swal.close();
                }
            });
        });

    </script>
 @yield('script')
</body>

</html>