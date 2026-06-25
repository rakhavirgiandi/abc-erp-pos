@php
@endphp
<header id="page-topbar">
    <div class="navbar-header" style="min-width: var(--pos-layout-min-width);">
        <div class="w-50">
            <h4 class="text-white mb-0">Penjualan Kasir</h4>
            <div class="counter-info">
                <div class="counter-info-item">No. Ref: <span id="header-ref-number-info"></span></div>
                <div class="counter-info-item">Tanggal: <span id="realtime-date">00-00-0000</span></div>
                <div class="counter-info-item">Waktu: <span id="realtime-clock">00:00:00</span></div>
            </div>
        </div>
        <div class="w-50 gap-4 d-flex justify-content-end align-items-center">
            <h5 class="mb-0 text-uppercase">Kasir : <span>{{Session::get('_name')}}</span></h5>
            <div class="d-flex gap-1 bg-white bg-opacity-10 p-1 rounded border border-white border-opacity-10 navbar-action-group">
                <a href="{{ url('/home') }}" class="btn btn-navbar-action btn-lg btn-icon">
                    <i class="mdi mdi-view-dashboard-outline"></i>
                </a>
                <button type="button" class="btn btn-navbar-action btn-lg btn-icon" id="fullscreen-toggler">
                    <i class="mdi mdi-arrow-expand"></i>
                </button>
                <button type="button" class="btn btn-navbar-action btn-lg btn-icon" id="reload-toggler">
                    <i class="mdi mdi-reload"></i>
                </button>
                @if (!!config('user_companies.details')->can('pos.settings') || config('user_companies.is_supervisor') || config('user_companies.details')->hasRole('SuperAdmin'))
                <a href="{{ url('/pos/settings') }}" class="btn btn-navbar-action btn-lg btn-icon">
                    <i class="mdi mdi-cog"></i>
                </a>
                @endif
                {{-- <button type="button" class="btn btn-navbar-action btn-lg btn-icon">
                </button> --}}
                <button type="button" class="btn btn-navbar-action btn-lg btn-icon" id="lockscreen-toggle">
                    <i class="mdi mdi-lock"></i>
                </button>
                <a href="{{ url('/logout') }}" class="btn btn-navbar-action btn-lg btn-icon" id="logout-toggle">
                    <i class="mdi mdi-logout"></i>
                </a>
                @php
                @endphp
                @if (config('app.is_onpremise'))
                    <span class="vr mx-1"></span>
                    <button type="button" class="btn btn-navbar-action btn-lg btn-icon" id="sync-toggle">
                        <span class="mdi mdi-cloud-sync"></span>
                    </button>
                @endif
            </div>
        </div>
    </div>
</header>