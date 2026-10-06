<div class="sidebar-left admin-sidebar" data-size="default">
    <div class="sidebar-slide h-100 simplebar-scrollable-y" style="margin: 0px;">
        <div class="sidebar-header">
            <a href="{{ url('/') }}">
                <span class="sidebar-logo-large">
                    <img src="{{ asset('assets/images/logo-dark.png') }}" alt="" height="33">
                </span>
                <span class="sidebar-logo">
                    <img src="{{ asset('assets/images/logo-box.png') }}" alt="" height="33">
                </span>
            </a>
        </div>
        <div class="sidebar-body">
            <ul class="sidebar-menu">
                <li>
                    <a class="sidebar-item" href="javascript: void(0);">
                        <i class="ti ti-layout-dashboard sidebar-item-icon"></i>
                        <span class="sidebar-item-label">Dasbor</span>
                    </a>
                </li>
                <li>
                    <a class="sidebar-item" href="{{ url('/pos/cashier') }}">
                        <i class="ti ti-cash-register sidebar-item-icon"></i>
                        <span class="sidebar-item-label">Kasir</span>
                    </a>
                </li>
                <li>
                    <a class="sidebar-item" href="{{ url('/admin/products') }}">
                        <i class="ti ti-triangle-square-circle sidebar-item-icon"></i>
                        <span class="sidebar-item-label">Produk</span>
                    </a>
                </li>
                <li>
                    <a class="sidebar-item" href="{{ url('/admin/sales-invoices') }}">
                        <i class="ti ti-report-money sidebar-item-icon"></i>
                        <span class="sidebar-item-label">Transaksi</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="sidebar-footer">
            <a href="#" class="sidebar-item">
                <i class="ti ti-logout-2 sidebar-item-icon"></i>
                <span class="sidebar-item-label">Keluar</span>
            </a>
        </div>
    </div>
</div>