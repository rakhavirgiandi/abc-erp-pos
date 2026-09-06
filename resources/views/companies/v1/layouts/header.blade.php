@php
    $logo_path = config('settings.company_logo');
    $custom_logo_url = $logo_path ? url('/storage/' . $logo_path) : asset('assets/images/logo2.png');
@endphp
<header id="page-topbar">
    <div class="navbar-header">

        <!-- Start Navbar-Brand -->
        <div class="navbar-logo-box">
            <a href="{{url('/')}}" class="logo logo-dark">
                {{-- <span class="logo-sm">
                    <img src="{{ $custom_logo_url }}" alt="{{ config('settings.company_name') }}" height="22">
                </span> --}}
                <span class="logo-lg">
                    <img src="{{ $custom_logo_url }}" alt="{{ config('settings.company_name') }}" height="40">
                </span>
            </a>
            <a href="{{url('/')}}" class="logo logo-light">
                {{-- <span class="logo-sm">
                    <img src="{{ $custom_logo_url }}" alt="{{ config('settings.company_name') }}" height="22">
                </span> --}}
                <span class="logo-lg">
                    <img src="{{ $custom_logo_url }}" alt="{{ config('settings.company_name') }}" height="40">
                </span>
            </a>
            <button type="button" class="btn btn-icon top-icon sidebar-btn" id="sidebar-btn" aria-label="Toggle navigation"><i class="mdi mdi-menu-open align-middle fs-17"></i></button>
            <button type="button" class="btn btn-icon top-icon sidebar-horizontal-btn d-none" aria-label="Toggle navigation"><i class="mdi mdi-menu align-middle fs-17"></i></button>
        </div>

        <!-- Start menu -->
        <div class="d-flex justify-content-between menu-sm px-4 ms-auto">
            <div class="d-flex align-items-center gap-2">
               
            </div>
            <div class="d-flex align-items-center gap-3">
                <button type="button" class="btn btn-icon top-icon d-none d-md-block" id="language-toggle" aria-label="Toggle Language">
                    @if(App::getLocale() == 'id')
                        <span class="flag-icon" style="font-size: 20px;">🇮🇩</span>
                    @else
                        <span class="flag-icon" style="font-size: 20px;">🇬🇧</span>
                    @endif
                </button>
                <div class="dropdown d-inline-block">
                    <button type="button" class="btn btn-icon top-icon" id="page-header-intergrations-dropdown" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Intergration">
                        <i class="ti ti-link fs-5"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-md dropdown-menu-end dropdown-menu-animated p-0 " aria-labelledby="page-header-intergrations-dropdown">
                        <div class="card">
                            <div class="card-body p-0">
                                <div class="grid-nav grid-nav-flush grid-nav-action grid-nav-no-rounded">
                                    <div class="grid-nav-row">
                                        <a href="{{ url('/hrm/dashboard') }}" class="grid-nav-item">
                                            <div class="grid-nav-icon"><i class="fas fa-users-cog"></i></div>
                                            <span class="grid-nav-content">HRM</span>
                                        </a>
                                        <a href="{{ url('/#') }}" class="grid-nav-item">
                                            <div class="grid-nav-icon"><i class="fas fa-poll-h"></i></div>
                                            <span class="grid-nav-content">CRM</span>
                                        </a>
                                        <a href="{{ url('/pos/cashier') }}" class="grid-nav-item">
                                            <div class="grid-nav-icon"><i class="fas fa-cash-register"></i></div>
                                            <span class="grid-nav-content">POS</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer p-0"></div>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-icon top-icon d-none d-md-block" id="light-dark-mode" aria-label="Toggle Light/Dark">
                    <i class="mdi mdi-brightness-7 align-middle"></i>
                    <i class="mdi mdi-white-balance-sunny align-middle"></i>
                </button>
                {{-- <button type="button" class="btn btn-icon d-none d-md-block" id="btn-sync-all">
                    <i class="mdi mdi-cloud-sync"></i>
                </button> --}}
                <!-- Start Notifications -->
                <div class="dropdown d-inline-block">
                    <button type="button" class="btn btn-icon top-icon" id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications">
                        <i class="mdi mdi-bell-ring-outline fs-17"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-md dropdown-menu-end dropdown-menu-animated p-0 " aria-labelledby="page-header-notifications-dropdown">
                        <div class="p-4 border-bottom">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="m-0"> <i class="mdi mdi-bell-ring-outline me-1 fs-15"></i> Notifications </h6>
                                </div>
                                <div class="col-auto">
                                    <a href="#!" class="badge bg-info-subtle text-info"> 8+</a>
                                </div>
                            </div>
                        </div>
                        <div data-simplebar style="max-height: 230px;">
                            <a href="#!" class="text-reset notification-item">
                                <div class="d-flex">
                                    <div class="avatar avatar-xs avatar-label-primary me-3">
                                        <span class="rounded fs-16">
                                            <i class="mdi mdi-file-document-outline"></i>
                                        </span>
                                    </div>
                                    <div class="flex-1">
                                        <h6 class="mb-1">New report has been recived</h6>
                                        <p class="mb-0 fs-12 text-muted"><i class="mdi mdi-clock-outline"></i> 3 min ago</p>
                                    </div>
                                    <i class="mdi mdi-chevron-right align-middle ms-2"></i>
                                </div>
                            </a>
                            <a href="#!" class="text-reset notification-item">
                                <div class="d-flex">
                                    <div class="avatar avatar-xs avatar-label-success me-3">
                                        <span class="rounded fs-16">
                                            <i class="mdi mdi-cart-variant"></i>
                                        </span>
                                    </div>
                                    <div class="flex-1">
                                        <h6 class="mb-1">Last order was completed</h6>
                                        <p class="mb-0 fs-12 text-muted"><i class="mdi mdi-clock-outline"></i> 1 hour ago</p>
                                    </div>
                                    <i class="mdi mdi-chevron-right align-middle ms-2"></i>
                                </div>
                            </a>
                            <a href="#!" class="text-reset notification-item">
                                <div class="d-flex">
                                    <div class="avatar avatar-xs avatar-label-danger me-3">
                                        <span class="rounded fs-16">
                                            <i class="mdi mdi-account-group"></i>
                                        </span>
                                    </div>
                                    <div class="flex-1">
                                        <h6 class="mb-1">Completed meeting canceled</h6>
                                        <p class="mb-0 fs-12 text-muted"><i class="mdi mdi-clock-outline"></i> 5 hour ago</p>
                                    </div>
                                    <i class="mdi mdi-chevron-right align-middle ms-2"></i>
                                </div>
                            </a>
                            <a href="#!" class="text-reset notification-item">
                                <div class="d-flex">
                                    <div class="avatar avatar-xs avatar-label-warning me-3">
                                        <span class="rounded fs-16">
                                            <i class="mdi mdi-send-outline"></i>
                                        </span>
                                    </div>
                                    <div class="flex-1">
                                        <h6 class="mb-1">New feedback received</h6>
                                        <p class="mb-0 fs-12 text-muted"><i class="mdi mdi-clock-outline"></i> 6 hour ago</p>
                                    </div>
                                    <i class="mdi mdi-chevron-right align-middle ms-2"></i>
                                </div>
                            </a>
                            <a href="#!" class="text-reset notification-item">
                                <div class="d-flex">
                                    <div class="avatar avatar-xs avatar-label-secondary me-3">
                                        <span class="rounded fs-16">
                                            <i class="mdi mdi-download-box"></i>
                                        </span>
                                    </div>
                                    <div class="flex-1">
                                        <h6 class="mb-1">New update was available</h6>
                                        <p class="mb-0 fs-12 text-muted"><i class="mdi mdi-clock-outline"></i> 1 day ago</p>
                                    </div>
                                    <i class="mdi mdi-chevron-right align-middle ms-2"></i>
                                </div>
                            </a>
                            <a href="#!" class="text-reset notification-item">
                                <div class="d-flex">
                                    <div class="avatar avatar-xs avatar-label-info me-3">
                                        <span class="rounded fs-16">
                                            <i class="mdi mdi-hexagram-outline"></i>
                                        </span>
                                    </div>
                                    <div class="flex-1">
                                        <h6 class="mb-1">Your password was changed</h6>
                                        <p class="mb-0 fs-12 text-muted"><i class="mdi mdi-clock-outline"></i> 2 day ago</p>
                                    </div>
                                    <i class="mdi mdi-chevron-right align-middle ms-2"></i>
                                </div>
                            </a>
                        </div>
                        <div class="p-2 border-top">
                            <div class="d-grid">
                                <a class="btn btn-sm btn-link font-size-14 text-center" href="javascript:void(0)">
                                    <i class="mdi mdi-arrow-right-circle me-1"></i> View More..
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Start Profile -->
                <div class="dropdown d-inline-block ps-3 ms-2 border-start admin-user-info">
                    <button type="button" aria-label="profile" class="btn btn-sm p-0" id="page-header-user-dropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false">
                        <span class="avatar avatar-xs p-1 d-inline-block">
                            <img src="{{ asset('assets/images/users/avatar-9.png')}}" alt="Header Avatar" class="img-fluid">
                        </span>
                        <span class="d-none d-xl-inline-block ms-1 fw-semibold fs-14 admin-name">{{Session::get('_name')}}</span>
                        <i class="mdi mdi-chevron-down align-middle fs-16 d-none d-xl-inline-block"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end dropdown-menu-animated overflow-hidden py-0">
                        <div class="card mb-0">
                            <div class="card-header">
                                <div class="rich-list-item w-100 p-0">
                                    <div class="rich-list-prepend">
                                        <span class="rounded avatar-sm p-1 bg-body d-flex">
                                            <img src="{{ asset('assets/images/users/avatar-9.png')}}" alt="Header Avatar" class="img-fluid">
                                        </span>
                                    </div>
                                    <div class="rich-list-content">
                                        <h3 class="rich-list-title fs-13 mb-1">{{Session::get('_name')}}</h3>
                                        <span class="rich-list-subtitle">{{Session::get('_email')}}</span>
                                    </div>
                                    <div class="rich-list-append"><span class="badge badge-label-primary fs-6">6+</span></div>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush">
                                    @if (
                                        config('user_request')->can('default_accounts') 
                                        )
                                    <a href="{{ url('/default-accounts') }}" class="list-group-item list-group-item-action d-flex align-items-center fw-semibold">
                                        <i class="mdi mdi-account-circle-outline me-2 fs-16"></i>
                                        Default Account
                                    </a>
                                    @endif
                                    @if (
                                        config('user_request')->can('general_settings') 
                                        )
                                    <a href="{{ url('general-settings') }}" class="list-group-item list-group-item-action d-flex align-items-center fw-semibold">
                                        <i class="mdi mdi-cog-outline me-2 fs-16"></i>
                                        {{ __('language.general_setting') }}
                                    </a>
                                    @endif
                                    @if (
                                            request()->query('is_setup_data') == true
                                        )
                                    <a href="#" id="reset-all-data-toggle" class="list-group-item list-group-item-action d-flex align-items-center fw-semibold">
                                        <i class="mdi mdi-database-refresh me-2 fs-16"></i>
                                        {{ __('language.reset_all_data') }}
                                    </a>
                                    @endif

                                    <li style="cursor: pointer;" class="list-group-item fw-semibold text-muted" data-bs-toggle="collapse" href="#periodClosing" onclick="event.stopPropagation()">
                                        <i class="mdi mdi-calendar-lock me-2 fs-16"></i>{{ __('language.periode_closing') }}
                                        <i class="mdi mdi-chevron-down float-end"></i>
                                    </li>

                                    <div class="collapse" id="periodClosing">
                                        <a href="{{ url('closings/product') }}" class="list-group-item list-group-item-action ps-4 d-flex align-items-center">
                                            <i class="mdi mdi-package-variant-closed me-2 fs-15"></i>
                                            {{ __('language.product_closing') }}
                                        </a>
                                    </div>

                                    <li style="cursor: pointer;" class="list-group-item fw-semibold text-muted" data-bs-toggle="collapse" href="#userManagement" onclick="event.stopPropagation()">
                                        <i class="mdi mdi-account-cog-outline me-2 fs-16"></i>User Management
                                        <i class="mdi mdi-chevron-down float-end"></i>
                                    </li>

                                    @if (
                                        config('user_request')->can('users') ||
                                        config('user_request')->can('roles') ||
                                        config('user_request')->can('permissions') 
                                        )

                                    
                                    <div class="collapse" id="userManagement">
                                        @if (
                                        config('user_request')->can('users')  
                                        )
                                        <a href="{{ url('/user-managements/users') }}" class="list-group-item list-group-item-action ps-4 d-flex align-items-center">
                                            <i class="mdi mdi-account-outline me-2 fs-15"></i>
                                            {{ __('language.users') }}
                                        </a>
                                        @endif
                                        @if (
                                            config('user_request')->can('roles')  
                                            )
                                        <a href="{{ url('/user-managements/roles') }}" class="list-group-item list-group-item-action ps-4 d-flex align-items-center">
                                            <i class="mdi mdi-shield-account-outline me-2 fs-15"></i>
                                            {{ __('language.roles') }}
                                        </a>
                                        @endif
                                        @if (
                                            config('user_request')->can('permissions')  
                                            )
                                        <a href="{{ url('/user-managements/permissions') }}" class="list-group-item list-group-item-action ps-4 d-flex align-items-center">
                                            <i class="mdi mdi-lock-outline me-2 fs-15"></i>
                                            {{ __('language.permissions') }}
                                        </a>
                                        @endif
                                    </div>
                                    @endif

                                    {{-- <a href="#" id="sync-modal-toggle" class="list-group-item list-group-item-action d-flex align-items-center fw-semibold">
                                        <i class="mdi mdi-cloud-sync me-2 fs-16"></i>
                                        {{ __('language.database_synchronize') }}
                                    </a> --}}
                                </ul>
                            </div>
                            <div class="card-footer card-footer-bordered rounded-0 border-top text-end"><a href="{{ url('/logout') }}" class="btn btn-label-danger">Log Out</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End menu -->
    </div>
</header>