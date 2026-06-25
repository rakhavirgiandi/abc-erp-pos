@extends('companies.v1.layouts.main')

@section('title', $title)

@section('style')
<style>
    #page-topbar {
        min-width: var(--pos-layout-min-width);
        position: absolute;
    }

    .product-image {
        width: 100%;
        max-height: 160px;
        object-fit: cover;
    }

    .page-content {
        height: 100vh;
        overflow: hidden;
    }

    #products-display[data-mode="table"] #product-cashier-table {
        display: table;
    }

    #products-display[data-mode="table"] #product-cashier-grid {
        display: none;
    }

    #products-display[data-mode="grid"] #product-cashier-grid {
        display: flex;
    }

    #products-display[data-mode="grid"] #product-cashier-table {
        display: none;
    }


    table#product-cashier-table thead th {
      position: sticky;
      top: 0;
      z-index: 1;
    }
    
    #products-display {
        flex: 1 1 auto;
        overflow-y: auto;
        padding: var(--bs-card-spacer-y) var(--bs-card-spacer-x);
        color: var(--bs-card-color);
    }

    #products-display[data-mode="table"] {
        padding: 0 !important;
    }

    #products-display[data-mode="grid"] {
        border-top: var(--bs-border-width) var(--bs-border-style) var(--bs-border-color) !important;
    }

    .order-item .qty-box {
        padding: 0 .5rem;
        width: auto;
        min-width: 32px;
        height: 32px;
        border-radius: 0.375rem;
        background: var(--bs-light);
        cursor: pointer;
        position: relative;
    }

    .order-item .qty-box .qty-value {
        -webkit-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .order-item {
        --bs-bg-opacity: 1;
        background-color: rgba(var(--bs-white-rgb), var(--bs-bg-opacity));
    }

    .order-item:hover {
        background-color: rgba(var(--bs-secondary-rgb), 0.15);
    }

    .order-item:hover .qty-box {
        background: var(--bs-secondary);
        color: #fff
    }

    .order-item[data-selected="true"] {
        background-color: rgba(var(--bs-secondary-rgb), 0.15);
        border-left: 4px solid var(--bs-secondary);
    }

    .order-item[data-selected="true"] .qty-box {
        background: var(--bs-secondary);
        color: #fff
    }

    .numpad-btn {
        border-radius: 0;
    }

    .key-pct {
        background: #FEF3C7;
        color: #D97706;
    }
    .key-pct:hover { background: #fde68a; }

    .key-nom {
        background: #DCFCE7;
        color: #059669;
    }
    .key-nom:hover { background: #bbf7d0; }

    .key-price {
        background: #F3E8FF;
        color: #7C3AED;
    }
    .key-price:hover { background: #e9d5ff; }

    .numpad-key {
        border-radius: 0;
        height: 51px;
    }
    .numpad-key:hover {
        background: #f8f9fa;
    }

    .rotate-icon {
        display: inline-block;
        transform: rotate(180deg);
        transition: transform 0.3s ease;
    }

    #payment-modal .modal-header {
        position: sticky;
        top: 0;
        z-index: 1055;
        background: white;
        box-shadow: 0 4px 6px -4px rgba(0, 0, 0, 0.2);
    }
    
    #histories-modal .modal-header {
        box-shadow: 0 4px 6px -4px rgba(0, 0, 0, 0.2);
    }

    #payment-modal .modal-body {
        background-color: var(--bs-body-bg);
        padding: var(--bs-modal-padding);
    }

    #payment-modal .modal-footer {
        box-shadow: 0 -4px 6px -4px rgba(0, 0, 0, 0.2);
        position: sticky;
        bottom: 0;
        z-index: 1055;
        background: white;
        padding: calc(var(--bs-modal-padding) - var(--bs-modal-footer-gap) * .5);
    }

    .reward-point-product-item {
        width: 100%;
        border-radius: var(--bs-border-radius) !important;
        padding: .75rem !important;
        border: var(--bs-border-width) var(--bs-border-style) var(--bs-border-color) !important;
        cursor: pointer;
    }

    .reward-point-product-item .name-placeholder {
        font-size: 14px;
    }

    .reward-point-product-item .total-point-placeholder {
        --bs-text-opacity: 1;
        color: rgba(var(--bs-warning-rgb), var(--bs-text-opacity)) !important;
        margin: 0;
    }

    .reward-point-product-item.disabled .name-placeholder {
        opacity: .5;
    }

    .reward-point-product-item.disabled .total-point-placeholder {
        color: var(--bs-body-color) !important;
        opacity: 0.3;
    }

    .reward-point-product-item.disabled {
        cursor: default;
        background-color: var(--bs-body-bg);
        color: var(--bs-secondary-color) !important;
    }

    .reward-point-product-item:hover {
        background: rgba(var(--bs-secondary-rgb), 0.15);
        border: var(--bs-border-width) var(--bs-border-style) rgba(var(--bs-secondary-rgb), 0.15) !important;
    }

    .reward-point-product-item.selected {
        background: rgba(var(--bs-secondary-rgb), 0.15);
        border: var(--bs-border-width) var(--bs-border-style) var(--bs-secondary) !important;
    }

    #payment-modal.modal.fade .modal-dialog {
        transform: translateY(100px) !important;
        transition: transform .25s ease-out !important;
    }

    #payment-modal.modal.show .modal-dialog {
        transform: translateY(0) !important;
    }

    .input-item-qty {
        background: none;
        border: 0px !important;
        text-align: center;
        color: var(--bs-body-color);
        font-weight: 600;
        outline: 0px !important;
        width: 1ch;
        padding: 0px;
        font-family: monospace;
    }

    .order-item[data-selected="true"] .input-item-qty, .order-item:hover .input-item-qty {
        color: #fff;
    }

    .input-discount-value {
        background: none;
        border: 0px !important;
        color: rgba(var(--bs-danger-rgb), var(--bs-text-opacity));
        font-weight: 600;
        outline: 0px !important;
        width: 1ch;
        padding: 0px;
        font-family: monospace;
    }

    .input-price {
        background: none;
        border: 0px !important;
        color: var(--bs-secondary-color) !important;
        outline: 0px !important;
        width: 1ch;
        font-size: 0.75rem;
        padding: 0px;
        font-family: monospace;
    }

    [data-shortcut-mode="discount-type-percentage"] [data-selected="true"] .discount-placeholder {
        display: block !important;
    }

    [data-shortcut-mode="discount-type-amount"] [data-selected="true"] .discount-placeholder {
        display: block !important;
    }

    #payment-method-tab.nav-pills .nav-link.active, .nav-pills .show>.nav-link {
        --bs-nav-pills-link-active-bg: var(--bs-secondary)
    }

    .edc-box {
        border: 1px solid var(--bs-border-color);
        cursor: pointer;
        height: 80px;
    }

    .edc-box.active {
        border: 2px solid var(--bs-secondary);
        background-color: rgba(var(--bs-secondary-rgb), 0.15); 
    }
    
    .apply-point-toggle.selected {
        border: 1px solid var(--bs-secondary);
        background-color: rgba(var(--bs-secondary-rgb), 0.15); 
    }

    #input-product-qty + .input-group-append button.bootstrap-touchspin-up {
        border-radius: var(--bs-btn-border-radius);
    }

    .input-group-prepend:has(+ #input-product-qty) button.bootstrap-touchspin-down {
        border-radius: var(--bs-btn-border-radius);
    }

    #input-product-qty {
        border: 0;
        font-size: 28px;
    }

    input[type="number"].no-spinners::-webkit-outer-spin-button,
    input[type="number"].no-spinners::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type="number"].no-spinners {
        -moz-appearance: textfield;
    }

    #input-product-unit + .select2-container .select2-selection--single {
        height: 45px;
    }

    #input-product-unit + .select2-container .select2-selection--single .select2-selection__rendered {
        line-height: 43px;
    }
    #input-product-unit + .select2-container .select2-selection--single .select2-selection__arrow {
        height: 45px;
        width: 45px;
        top: -2px !important;
    }

</style>
  <link href="{{ asset('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" type="text/css">

  <!-- Responsive datatable examples -->
  <link href="{{ asset('assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css')}}" rel="stylesheet" type="text/css">

  <!-- Datatable extensions -->
  <link href="{{ asset('assets/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css')}}" rel="stylesheet" type="text/css">
  <link href="{{ asset('assets/libs/datatables.net-select-bs5/css/select.bootstrap5.min.css')}}" rel="stylesheet" type="text/css">
  <link href="{{ asset('assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="d-flex gap-4" style="height: 100%">
        <div style="flex: 4">
            <div class="card rounded-3 mb-0" style="height: 100%">
                <div class="card-body p-3" style="flex: none">
                    <div class="d-flex mb-3">
                        <div class="search-product-group">
                            <input type="text" class="form-control form-control-lg py-3" id="input-search-product" placeholder="Masukan No PLU / Barcode / Cari [Space]">
                            <i class="mdi mdi-magnify position-absolute top-50 end-0 translate-middle-y me-3 text-muted fs-3"></i>
                        </div>
                        <button type="button" id="reset-input-search-product-toggle" class="btn btn-text-light d-none"><i class="mdi mdi-close text-muted fs-3"></i></button>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="gap-2 d-inline-flex align-items-center select-category-buttons" id="select-category-buttons">
                            {{-- <button type="button" class="btn rounded-3 px-4 btn-sm fw-bold fs-5 active">
                                All Product
                            </button> --}}
                        </div>
                        <div class="gap-1 d-flex align-items-center justify-content-end ms-auto" id="product-display-style">
                            <button type="button" class="btn btn-icon" id="change-product-display-style-action-grid" data-mode="grid"><i class="mdi mdi-view-grid-outline" disabled></i></button>
                            <button type="button" class="btn btn-icon active" id="change-product-display-style-action-table" data-mode="table"><i class="mdi mdi-format-list-bulleted"></i></button>
                        </div>
                    </div>
                </div>
                
                <div id="products-display" data-mode="table">
                    <table class="table" id="product-cashier-table">
                        <thead>
                            <tr>
                                <th>KODE</th>
                                <th>NAMA</th>
                                @if (config('general_settings.is_use_product_catalog') != 'true') 
                                <th>UNIT</th>
                                @endif
                                <th>KATEGORI</th>
                                <th class="text-end">HARGA</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <div class="row" id="product-cashier-grid" style="row-gap: calc(var(--bs-gutter-x) * 1);">
                    </div>
                </div>
            </div>
        </div>
        <div style="flex: 2.5">
            <div class="card rounded-3 h-100">
                <div class="p-2 border-bottom" style="height: max-content">
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-light w-25 rounded-3 text-primary fw-bold shortcut-toggle" id="customer-modal-toggle" style="font-size: 12px;" data-shortcut="f7">Pelanggan<br><span class="fw-normal">[F7]</span></button>
                        <button type="button" class="btn btn-label-warning w-25 rounded-3 fw-bold shortcut-toggle" style="font-size: 12px">Promo<br><span class="fw-normal">[F5]</span></button>
                        <button type="button" class="btn w-25 rounded-3 fw-bold btn-label-success shortcut-toggle" style="font-size: 12px" id="redeem-modal-toggle">Redeem<br><span class="fw-normal" data-shortcut="f12">[F12]</span></button>
                        <button type="button" class="btn btn-label-secondary w-25 rounded-3 fw-bold shortcut-toggle" style="font-size: 12px" id="product-note-modal-toggle" data-shortcut="f11">Catatan<br><span class="fw-normal">[F11]</span></button>
                    </div>
                </div>
                <div class="py-2 px-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex gap-2 align-items-center">
                            <span class="mdi mdi-cart-outline" style="font-size: 22px;"></span>
                            <h5 class="mb-0" style="font-size: 0.875rem;">Order List</h5>
                        </div>
                        <span class="badge badge-label-secondary rounded-pill fw-bold" style="font-size: 14px" id="order-item-total-label"></span>
                    </div>
                </div>
                <div class="flex-fill overflow-auto" id="order-list-container">
                </div>
                <div class="bg-white border-top">
                    <div class="row row-cols-5 g-0 border-bottom">

                        <div class="col">
                            <button class="btn btn-label-info numpad-btn w-100 py-2 d-flex flex-column align-items-center justify-content-center border-end shortcut-toggle" data-shortcut="f1" id="qty-shortcut-toggle">
                                <span class="fw-bold text-uppercase">Qty</span>
                                <span class="opacity-75">[F1]</span>
                            </button>
                        </div>
                    
                        <div class="col">
                            <button class="btn numpad-btn btn-label-warning w-100 py-2 d-flex flex-column align-items-center justify-content-center border-end shortcut-toggle" data-shortcut="f3" id="discount-percentage-shortcut-toggle">
                                <span class="fw-bold">Disc %</span>
                                <span class="opacity-75">[F3]</span>
                            </button>
                        </div>
                    
                        <div class="col">
                            <button class="btn numpad-btn btn-label-success w-100 py-2 d-flex flex-column align-items-center justify-content-center border-end shortcut-toggle" id="discount-type-amount-shortcut-toggle" data-shortcut="f6">
                                <span class="fw-bold">Disc Rp</span>
                                <span class="opacity-75">[F6]</span>
                            </button>
                        </div>
                    
                        <div class="col">
                            <button class="btn numpad-btn btn-label-secondary w-100 py-2 d-flex flex-column align-items-center justify-content-center shortcut-toggle" id="price-shortcut-toggle" data-shortcut="f4">
                                <span class="fw-bold">Harga</span>
                                <span class="opacity-75">[F4]</span>
                            </button>
                        </div>
                        <div class="col">
                            <button class="btn numpad-btn btn-label-danger w-100 py-2 d-flex flex-column align-items-center justify-content-center" id="unit-shortcut-toggle" data-shortcut="f2">
                                <span class="fw-bold">Satuan</span>
                                <span class="opacity-75">[F2]</span>
                            </button>
                        </div>
                    
                    </div>

                    <button id="btn-toggle-numpad"
                        class="btn w-100 py-1 bg-light border-bottom text-secondary d-flex justify-content-center align-items-center rounded-0"
                        title="Buka/Tutup Kalkulator">
                        <span id="btn-toggle-numpad" class="mdi mdi-chevron-up small"></span>
                    </button>
                
                    <div id="container-numpad" style="display: none">
                        <div class="row row-cols-3 g-0">
                            <div class="col"><button class="btn w-100 py-3 fs-4 border-end border-bottom numpad-key" data-key="1">1</button></div>
                            <div class="col"><button class="btn w-100 py-3 fs-4 border-end border-bottom numpad-key" data-key="2">2</button></div>
                            <div class="col"><button class="btn w-100 py-3 fs-4 border-bottom numpad-key" data-key="3">3</button></div>
                        
                            <div class="col"><button class="btn w-100 py-3 fs-4 border-end border-bottom numpad-key" data-key="4">4</button></div>
                            <div class="col"><button class="btn w-100 py-3 fs-4 border-end border-bottom numpad-key" data-key="5">5</button></div>
                            <div class="col"><button class="btn w-100 py-3 fs-4 border-bottom numpad-key" data-key="6">6</button></div>
                        
                            <div class="col"><button class="btn w-100 py-3 fs-4 border-end border-bottom numpad-key" data-key="7">7</button></div>
                            <div class="col"><button class="btn w-100 py-3 fs-4 border-end border-bottom numpad-key" data-key="8">8</button></div>
                            <div class="col"><button class="btn w-100 py-3 fs-4 border-bottom numpad-key" data-key="9">9</button></div>
                        
                            <div class="col"><button class="btn w-100 py-3 fs-4 border-bottom border-end numpad-key" data-key="0">0</button></div>
                            <div class="col"><button class="btn w-100 py-3 fs-5 border-bottom border-end numpad-key" data-key=",">,</button></div>
                            <div class="col"><button class="btn w-100 py-3 fs-4 border-bottom numpad-key" data-key="backspace"><span class="mdi mdi-backspace-outline"></span></button></div>
                        </div>
                    </div>
                </div>
                <div class="p-3">
                    <div class="d-flex flex-column">
                        <div class="d-flex justify-content-between">
                            <p class="mb-0" style="font-size: 16px">Subtotal</p>
                            <p class="mb-0" style="font-size: 16px" id="subtotal-info">0</p>
                        </div>
                        <div class="d-flex justify-content-between text-danger">
                            <p class="mb-0" style="font-size: 16px">Diskon</p>
                            <p class="mb-0" style="font-size: 16px" id="total-discount-info">- 0</p>
                        </div>
                        <div class="d-flex justify-content-between">
                            <p class="mb-0" style="font-size: 16px">Tax</p>
                            <p class="mb-0" style="font-size: 16px" id="total-tax-info">0</p>
                        </div>
                        <span class="border-bottom my-1"></span>
                        <div class="d-flex justify-content-between">
                            <p class="mb-0 fw-bold" style="font-size: 18px">Total</p>
                            <p class="mb-0 fw-bold" style="font-size: 18px" id="total-info">0</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input id="input-number" type="hidden">
    <input id="input-id" type="hidden">
@endsection

@section('modal')
    @include('companies.v1.pos.cashier.modal.catalog')
    @include('companies.v1.pos.cashier.modal.product_note')
    @include('companies.v1.pos.cashier.modal.customer')
    @include('companies.v1.pos.cashier.modal.unit')
    @include('companies.v1.pos.cashier.modal.payment')
    @include('companies.v1.pos.cashier.modal.redeem')
    @include('companies.v1.pos.cashier.modal.authenticate')
    @include('companies.v1.pos.cashier.modal.histories')
    @include('companies.v1.pos.cashier.modal.taxes')
    @include('companies.v1.pos.cashier.modal.stock')
    @include('companies.v1.pos.cashier.modal.sync')
    @include('companies.v1.pos.cashier.modal.access_denied')
@endsection

@section('script')
    <script src="{{ asset('assets/libs/datatables.net/js/dataTables.min.js')}}"></script>
    <script src="{{ asset('assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js')}}"></script>

    <!-- Responsive examples -->
    <script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{ asset('assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js')}}"></script>

    <!-- Datatable extensions -->
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js')}}"></script>
    <script src="{{ asset('assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js')}}"></script>

<script>
    $(function() {

        const IS_CAN_CHANGE_ITEM_PRICE = '{{ config('user_companies.is_supervisor') || config('user_companies.details')->can('pos.change-price') || config('user_companies.details')->hasRole('SuperAdmin') }}';
        const IS_CAN_CHANGE_ITEM_DISCOUNT = '{{ config('user_companies.is_supervisor') || config('user_companies.details')->can('pos.change-item-discount') || config('user_companies.details')->hasRole('SuperAdmin') }}';
        const IS_CAN_DELETE_ITEM = '{{ config('user_companies.is_supervisor') || config('user_companies.details')->can('pos.delete-item-transaction') || config('user_companies.details')->hasRole('SuperAdmin') }}';
        const IS_CAN_UNIT_ITEM = '{{ config('user_companies.is_supervisor') || config('user_companies.details')->can('pos.change-unit') || config('user_companies.details')->hasRole('SuperAdmin') }}';
        const IS_CAN_HOLD_TRANSACTION = '{{ config('user_companies.is_supervisor') || config('user_companies.details')->can('pos.hold-transaction') || config('user_companies.details')->hasRole('SuperAdmin') }}';
        const IS_CAN_REPRINT_TRANSACTION = '{{ config('user_companies.is_supervisor') || config('user_companies.details')->can('pos.reprint') || config('user_companies.details')->hasRole('SuperAdmin') }}';
        const IS_CAN_REDEEM_TRANSACTION = '{{ config('user_companies.is_supervisor') || config('user_companies.details')->can('pos.redeem-transaction') || config('user_companies.details')->hasRole('SuperAdmin') }}';
        const IS_CAN_VOID_TRANSACTION = '{{ config('user_companies.is_supervisor') || config('user_companies.details')->can('pos.void-transaction') || config('user_companies.details')->hasRole('SuperAdmin') }}'        
        const IS_CAN_STOCK_WAREHOUSE = '{{ config('user_companies.is_supervisor') || config('user_companies.details')->can('pos.stock-warehouse') || config('user_companies.details')->hasRole('SuperAdmin') }}'
        const IS_CAN_EDIT_TRANSACTION = '{{ config('user_companies.is_supervisor') || config('user_companies.details')->can('pos.edit-transaction') || config('user_companies.details')->hasRole('SuperAdmin') }}'
        const IS_CAN_ACCESS_SETTINGS = '{{ config('user_companies.is_supervisor') || config('user_companies.details')->can('pos.settings') || config('user_companies.details')->hasRole('SuperAdmin') }}'
        
        const IS_DISPLAY_CATALOG_MODE = '{{ config('general_settings.is_use_product_catalog') }}' == 'true' ? true : false;

        $("#input-product-qty").TouchSpin({
            buttondown_class: "btn-sm btn-icon btn btn-secondary",
            buttonup_class: "btn-sm btn-icon btn btn-secondary",
            buttondown_txt: '<i class="ti ti-minus"></i>',
            buttonup_txt: '<i class="ti ti-plus"></i>',
            step: 1,
            decimals: true,
            forcestepdivisibility: 'none',
            decimalmark: '.'
        }).closest(".bootstrap-touchspin").addClass("align-items-center");

        function formatQty() {
            let value = parseFloat($("#input-product-qty").val());

            if (isNaN(value)) {
                return;
            }
        
            $("#input-product-qty").val(value);
        }

        $("#input-product-qty").on("touchspin.on.stopspin", formatQty);
        $("#input-product-qty").on("blur change", formatQty);

        let totalPointApplied = 0;
        let totalDiscountPoint = 0;
        let totalPointIsAppliedStatus = false;

        let productPage = 1;
        let loadProductIsLoading = false;
        let loadProductIsLast = false;
        let productRowsCount = 0;
        let selectedProductId = null;
        let selectedOrderProductId = null;
        let productOrderListMap = new Map();
        let productListMap = new Map();
        let productCatalogListMap = new Map();
        
        let customerPage = 1;
        let loadCustomerIsLoading = false;
        let loadCustomerIsLast = false;
        let customerRowsCount = 0;
        let customerListMap = new Map();
        let selectedCustomerId = null;
        let selectedCustomer = {};
        let customerModalHasBeenOpen = false;
        let defaultCustomer = {}
        
        let unitPage = 1;
        let loadUnitIsLoading = false;
        let loadUnitIsLast = false;
        let unitRowsCount = 0;
        let unitListMap = new Map();
        let selectedUnitId = null;
        let selectedUnit = {};
        let selectedProductIdLoadUnit = null;
        let unitModalHasBeenOpen = false;   

        let taxPage = 1;
        let loadTaxIsLoading = false;
        let loadTaxIsLast = false;
        let taxRowsCount = 0;
        let taxListMap = new Map();
        let selectedTaxId = null;
        let selectedTax = {};
        let taxModalHasBeenOpen = false;
        
        let rewardPointProductPage = 1;
        let loadRewardPointProductIsLoading = false;
        let loadRewardPointProductIsLast = false;
        let rewardPointProductRowsCount = 0;
        let rewardPointProductListMap = new Map();
        let selectedRewardPointProductId = null;
        let selectedRewardPointProduct = {};
        let rewardPointModalHasBeenOpen = false;

        let rewardPointPage = 1;
        let loadRewardPointIsLoading = false;
        let loadRewardPointIsLast = false;
        let rewardPointListMap = new Map();
        let selectedRewardPointId = null;
        let selectedRewardPoint = {};
        
        let paymentMethodPage = 1;
        let loadPaymentMethodIsLoading = false;
        let loadPaymentMethodIsLast = false;
        let paymentMethodRowsCount = 0;
        let paymentMethodListMap = new Map();
        let selectedPaymentMethodId = null;
        let selectedPaymentMethod = {};
        let EDCTabHasBeenOpen = false;

        let historiesPages = {
            pending: 1,
            done: 1,
            hold: 1
        };

        let loadHistoriesIsLoadings = {
            pending: false,
            done: false,
            hold: false
        };

        let loadHistoriesIsLasts = {
            pending: false,
            done: false,
            hold: false
        };

        let historiesModalHasBeenOpen = false;

        let typingTimer;
        let buffer = '';
        let startTime = 0;
        let lastTime = 0;

        let selectedCustomerIdRewardPoint = null;
        let rewardPointsAppliedMap = new Map();
        let deletedRewardPointIds = [];

        const SCAN_SPEED_THRESHOLD = 30;
        const MIN_BARCODE_LENGTH = 6;
        let IS_FIRST = parseFloat('{{config('user_companies.is_first')}}');

        let productStockDt;

        let authenticateSupervisorOnSuccess = null;

        $('#input-product-unit').select2({
            dropdownParent: $('#catalog-modal'),
        })

        getUserSearch({
            element: $('#input-supervisor-auth-user_id'),
            filter: 'is_supervisor=1',
            modal: $('#access-denied-modal'),
        })

        const shouldAuthenticateSupervisor = (onSuccess) => {
            $('#access-denied-modal').modal('show');
            if (onSuccess && typeof onSuccess === 'function') {
                authenticateSupervisorOnSuccess = onSuccess
            }
        } 

        const resetPointExchange = (resetInput = true) => {
            totalPointApplied = 0;
            totalDiscountPoint = 0;
            if (resetInput) {
                $('#input-point').numericInput('setValue', 0);
                $('#input-point').numericInput('destroy');
                $('#input-point').numericInput({
                    allowNegative: true
                });
            }
            $('#input-discount-point').val(0)
            $('#redeem-total-discount-placeholder').html('Rp 0');
        }

        const setCustomerDefaultValue = () => {
            $.ajax({
                type: 'get',
                url: BASE_URL + "/api/v1/contacts/"+'{{ config('general_settings.default_customer') }}'+'?is_pos_display=1',
                "headers": {
                    'Authorization': TOKEN,
                    'company-id': COMPANY_ID,
                },
                beforeSend: function () {
                    $('#customer-modal-toggle').prop('disabled', true)
                    $('#customer-modal-toggle').html(`
                                <div class="spinner-border spinner-border-sm" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>`)
                },
                success: function (res) {
                    selectedCustomer = res;
                    defaultCustomer = res
                    getSelectedCustomerInfo();
                },
                error: (jqXHR, textStatus, errorThrown) => {
                    $('#customer-modal-toggle').html('Pelanggan<br><span class="fw-normal">[F7]</span>');
                },
                complete: () => {
                    $('#customer-modal-toggle').prop('disabled', false);
                    setTotalInfo()
                }
            })
        } 

        function withShortcutSwal(options) {
            let handler;
                
            return Swal.fire({
                ...options,
                didOpen: () => {
                    handler = (e) => {
                        if (e.key === 'Enter') Swal.clickConfirm();
                        if (e.key.toLowerCase() === 'n') Swal.clickCancel();
                    };
                
                    document.addEventListener('keydown', handler);
                },
                willClose: () => {
                    document.removeEventListener('keydown', handler);
                },
                showCancelButton: true,
                confirmButtonColor: 'var(--bs-success)',
                cancelButtonColor: 'var(--bs-danger)',
                confirmButtonText: '{{ __('language.yes') }} <span style="font-family: monospace;">[Enter]<span>',
                cancelButtonText: '{{ __('language.cancel') }} <span style="font-family: monospace;">[Esc]<span>',
                reverseButtons: true
            });
        }

        const salesInvoiceSync = (props = {}) => {
            $.ajax({
                type: 'post',
                url: BASE_URL + "/api/v1/sync/sales_invoices",
                "headers": {
                    'Authorization': TOKEN,
                    'company-id': COMPANY_ID,
                },
                beforeSend: function () {
                    if (props?.beforeSend && typeof props?.beforeSend == 'function') {
                        props.beforeSend()
                    }
                },
                success: function (res) {
                    if (props?.success && typeof props?.success == 'function') {
                        props.success(res)
                    }
                },
                complete: function (res) {
                    if (props?.complete && typeof props?.complete == 'function') {
                        props.complete(res)
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    if (props?.error && typeof props?.error == 'function') {
                        props.error(jqXHR, textStatus, errorThrown)
                    }

                    if (props.isShowPopUpError) {
                        generalAjaxErrorHandler(jqXHR, textStatus, errorThrown)
                    }
                }
            })
        }

        const sync = (props = {}) => {
            $.ajax({
                type: 'POST',
                url: BASE_URL + "/api/v1/sync",
                "headers": {
                    'Authorization': TOKEN,
                    'company-id': COMPANY_ID,
                },
                beforeSend: function () {
                    if (props?.beforeSend && typeof props?.beforeSend == 'function') {
                        props.beforeSend()
                    }
                },
                success: function (res) {
                    if (props?.success && typeof props?.success == 'function') {
                        props.success(res)
                    }
                },
                complete: function (res) {
                    if (props?.complete && typeof props?.complete == 'function') {
                        props.complete(res)
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    if (props?.error && typeof props?.error == 'function') {
                        props.error(jqXHR, textStatus, errorThrown)
                    }

                    if (props.isShowPopUpError) {
                        generalAjaxErrorHandler(jqXHR, textStatus, errorThrown)
                    }
                }
            })
        }
        
        const elem = document.documentElement;

        const setInputAutoWidth = (e) => {
            const length = $(e).val()?.length;
            $(e).css('width', (length > 0 ? length : 1) + 'ch');
        }

        const generateRefNumber = (refNumber = null) => {
            $('#input-number').val('');
            $('#header-ref-number-info').html('');
            if (!refNumber) {
                $.ajax({
                    url: BASE_URL + '/api/v1/pos/generate_ref_numbers',
                    type: "GET",
                    dataType: "json",
                    headers: {
                        'Authorization': TOKEN,
                        'company-id': COMPANY_ID,
                    },
                    success: function(res) {
                        $('#header-ref-number-info').html(res?.ref_number)
                        $('#input-number').val(res?.ref_number)
                    },
                });
            } else {
                $('#input-number').val(refNumber);
                $('#header-ref-number-info').html(refNumber);
            }
        }

        $('#input-total_payment, #input-total_payment_edc').numericInput({
            separator: ',',
            allowNegative: false
        });

        setInterval(() => { 
            const now = new Date();
            const dateString = moment(now).format('DD-MM-YYYY');
            const timeString = moment(now).format('HH:mm:ss');
            $('#realtime-clock').text(timeString); 
            $('#realtime-date').text(dateString); 
        }, 1000);

        const resetShortcutMode = () => {
            document.documentElement.removeAttribute('data-shortcut-mode');
        }

        function openFullscreen() {
          if (elem.requestFullscreen) {
            elem.requestFullscreen();
          }
        }

        function closeFullscreen() {
          if (document.exitFullscreen) {
            document.exitFullscreen();
          }
        }

        const showPaymentStep = () => {
            $('#payment-modal').modal('show');
        }

        document.addEventListener('fullscreenchange', () => {
            if (document.fullscreenElement) {
                $('#fullscreen-toggler').html('<i class="mdi mdi-arrow-collapse"></i>')
            } else {
                $('#fullscreen-toggler').html('<i class="mdi mdi-arrow-expand"></i>')
            }
        });

        $(document).on('click', '#fullscreen-toggler', function () {
            if (!document.fullscreenElement) {
              document.documentElement.requestFullscreen();
            } else {
              document.exitFullscreen();
            }
        });

        const showNumpad = () => {
            const $numpad = $('#container-numpad');
            
            if (!$numpad.hasClass('is-open')) {
                $numpad.addClass('is-open').slideDown(200);
            }
        }
        
        const hideNumpad = () => {
            const $numpad = $('#container-numpad');
            
            if ($numpad.hasClass('is-open')) {
                $numpad.removeClass('is-open').slideDown(200);
            }
        }

        const hideAllModal = () => {
            $('.modal.show').modal('hide');
        }

        const clear = (props) => {

            const {
                setDefaultCustomer = false
            } = props || {}

            $('#input-id').val('');
            productOrderListMap.clear();
            selectedOrderProductId = null;
            selectedCustomer = {};
            selectedCustomerIdRewardPoint = null;
            rewardPointsAppliedMap.clear()
            deletedRewardPointIds = [];
            hideAllModal();
            getSelectedCustomerInfo(setDefaultCustomer);
            $('#order-list-container').html('');
            $('#input-total_payment').numericInput('setValue', 0);
            $('#input-variant-product_id').val('');
            resetPointExchange();
            setTotalInfo();
            selectedProductIdLoadUnit = null;
            $('table#customer-table tbody tr').removeAttr('data-selected');
            $('.apply-point-toggle').removeClass('selected');
            calculateTotalItemBadge();
            authenticateSupervisorOnSuccess = null;
            if ($('#input-search-product').val()?.length >  0) {
                $('#input-search-product').val('').trigger('input');
            }
        }

        const resetHistoriesState = () => {
            historiesPages.pending = 1;
            historiesPages.done = 1;
            historiesPages.hold = 1;

            loadHistoriesIsLoadings.pending = false;
            loadHistoriesIsLoadings.done = false;
            loadHistoriesIsLoadings.hold = false;

            loadHistoriesIsLasts.pending = false;
            loadHistoriesIsLasts.done = false;
            loadHistoriesIsLasts.hold = false;

            historiesModalHasBeenOpen = false;

            Object.entries(historiesPages).forEach(([key, value]) => {
                $('#histories-'+key+'-tab-content').html('')
            });
        }

        let activeInput = null;

        $(document).on('focus', 'input', function () {
            activeInput = this;
        });

        $(document).on('click', '.numpad-key', function () {
            if (!activeInput) return;
        
            const dataKey = $(this).data('key');
            let val = activeInput.value || '';
        
            let start = activeInput.selectionStart ?? val.length;
            let end = activeInput.selectionEnd ?? val.length;
        
            const hasSelection = start !== end;
        
            if (dataKey === 'backspace') {
                if (hasSelection) {
                    val = val.slice(0, start) + val.slice(end);
                } else {
                    val = val.slice(0, -1);
                    start = val.length;
                }
            } else {
                if (hasSelection) {
                    val = val.slice(0, start) + dataKey + val.slice(end);
                    start = start + dataKey.length;
                } else {
                    val += dataKey;
                    start = val.length;
                }
            }
        
            activeInput.value = val;
        
            activeInput.setSelectionRange(start, start);
        
            $(activeInput).trigger('input');
            activeInput.focus();
        });

        const setVariantPopUpProductInfo = (productId) => {
            const data = productListMap.get(productId);
            if (data) {
                $('#product-detail-code-info').html(data.code);
                $('#product-detail-price-info').html(parseFloat(data.sale_price)?.toLocaleString('en'));
            }
        }

        const calculateTotalItemBadge = () => {
            if (productOrderListMap.size > 0) {
                $('#order-item-total-label').html(productOrderListMap.size+' Item'+(productOrderListMap.size > 1 ? 's' : ''));
            } else {
                $('#order-item-total-label').html('');
            }
        }

        const focusToSelectedOrderItem = () => {
            const container = $('#order-list-container')[0];
            const selected = $('#order-list-container .order-item[data-selected="true"]')[0];

            if (container && selected) {
                const containerRect = container.getBoundingClientRect();
                const selectedRect = selected.getBoundingClientRect();
                const offset = 0;
            
                const scrollOffset =
                    selectedRect.top -
                    containerRect.top +
                    container.scrollTop -
                    offset;
            
                container.scrollTo({
                    top: scrollOffset,
                    behavior: 'smooth'
                });
            }
        }

        const selectProduct = (productId = null, props = {}) => {
            
            if (!props?.data) {
                $('table#product-cashier-table tbody tr').removeAttr('data-selected');
                $('#product-cashier-grid .product-col').removeAttr('data-selected');
            }

            if (productId) {
                const selectedRowProductInTable = $('table#product-cashier-table tbody tr[data-id='+productId+']');
                selectedRowProductInTable.attr('data-selected', "true");
                const selectedColProductInGrid = $('#product-cashier-grid .product-col[data-id='+productId+']');
                selectedColProductInGrid.attr('data-selected', "true");
                selectedProductId = productId;
                
            } else {
                if (!props.data) {
                    selectedProductId = null;
                }
            }

            // if (props?.isShowPopUp) {
                // console.log($('#catalog-modal'));
                
                // const data = props.data ? props.data : productListMap.get(selectedProductId);
                // if (data) {
                    // $('#input-variant-product_id').val(data.id);
    
                    // let chooseVariantsHtml = '';
                    
                    // if (grouped?.length > 0) {
                    //     grouped?.forEach((item, index) => {
                    //         chooseVariantsHtml += '<div class="mb-3">'
                    //         chooseVariantsHtml +=     '<h6>'+item?.variant_name+'</h6>'
                    //         chooseVariantsHtml +=      '<div class="row" style="--bs-gutter-x: 12px; --bs-gutter-y: 12px;">'
                    //             if (item?.data?.length > 0) {
                    //                 item?.data.forEach((variantOpt, variantOptIdx) => {
                    //                     chooseVariantsHtml += '<div class="col-4">'
                    //                     chooseVariantsHtml +=     '<button type="button" class="btn btn-lg w-100 select-variant-toggle" data-product_id="'+data.id+'" data-variant_id="'+item?.variant_id+'" data-variant_name="'+item?.variant_name+'" data-variant_option_id="'+variantOpt?.option_id+'" data-variant_option_value="'+variantOpt?.option_value+'">'+variantOpt?.option_value+'</button>'
                    //                     chooseVariantsHtml += '</div>'
                    //                 })
                    //             }
                    //         chooseVariantsHtml +=      '</div>'
                    //         chooseVariantsHtml +=  '</div>';
                    //     });
                    // }
                    
                    // $('#product-select-variants-container').html(chooseVariantsHtml);
                    
                    // $('#product-detail-code-info').html(data.code);
                    // $('#product-detail-name-info').html(data.name);
                    // $('#product-detail-price-info').html(parseFloat(data.sale_price)?.toLocaleString('en'));
    
                    // let thumbnail = `<div class="avatar avatar-label-primary" style="width: 6rem; height: 6rem; font-size: 35px;">
                    //         <span class="mdi mdi-file-image-outline"></span>
                    //     </div>`
    
                    // if (data?.media?.length > 0) {
                    //     const firstImage = data?.media?.[0];
                    //     if (firstImage) {
                    //         const imageURL = BASE_URL + firstImage.filepath+'/'+firstImage?.filename;
                    //         thumbnail = `<img src="${imageURL}" alt="" class="rounded-2" style="object-fit: cover; object-position: center; width: 6rem; height: 6rem;">`
                    //     }
                    // }
    
                    // $('#variant-product-thumbnail').html(thumbnail)
                    // $('#variant-submit-toggle').prop('disabled', true);

                    
                    // if ($('#input-product-unit').hasClass('select2-hidden-accessible')) {
                    //     $('#input-product-unit').select2('destroy').val("").select2({
                    //         dropdownParent: $('#catalog-modal')
                    //     });
                    // }      
                    
                    // getUnitSearch({
                    //     element: '#input-product-unit',
                    //     modal: $('#catalog-modal'),
                    //     filter: data?.unit_conversions?.length > 0 ? '&product_id='+data?.id : '',
                    //     selected_val_object: { id: data?.unit_id, text: data?.unit_name, dataset: {
                    //         conversions: data?.unit?.conversions
                    //     }}
                    // });

                // }
            // }
        }

        const selectOrderItem = (productId = null) => {

            $('#order-list-container .order-item').removeAttr('data-selected');

            if (productId) {
                
                const selectedOrderItem = $('#order-list-container .order-item[data-id='+productId+']');
                
                selectedOrderItem.attr('data-selected', "true");
                selectedOrderProductId = productId;

            } else {
                selectedOrderProductId = null;
            }

        }

        const generateOrderId = () => {
            return productOrderListMap.size === 0
                ? 1
                : Math.max(...productOrderListMap.keys()) + 1;
        };

        const getDuplicateOrderItemId = (id, params = {}) => {

            const {
                unitId = null,
                variantSign = null
            } = params || {}

            const seen = {};
              
            for (const [key, item] of productOrderListMap) {
                const productId = item?.detail?.id;
                const itemUnitId = item?.unit_id;
                const variantSignItem = item?.variant_sign;
                let fp = productId+'|'+itemUnitId;

                if (variantSignItem) {
                    fp += '|'+variantSignItem;
                }

                seen[fp] = key;
            }

            const fingerprint = `${id}|${unitId}${variantSign ? '|'+variantSign : ''}`;

            return seen[fingerprint] ? seen[fingerprint] : null;
        }

        const putProductToOrderList = (productId, params = {}) => {
            
            let id = productId;
        
            let getProductList = productListMap.get(id);
            let isExistInOrderList = false;
            
            if (!params?.select_item) {
                params.select_item = true
            }
            
            let callback = null

            if (params?.callback) {
                callback = params.callback;
                delete params.callback
            }

            let selectedVariantSign = null;

            if (params?.selectedVariants && Array.isArray(params.selectedVariants)) {
                params?.selectedVariants.forEach((item, idx) => {
                    if (idx < 1) {
                        selectedVariantSign = '';
                    } else {
                        selectedVariantSign += '|';
                    }

                    selectedVariantSign += item.variant_id+':'+item.variant_option_id;
                });
            
                params.variant_sign = selectedVariantSign
            }
            
            if (params?.is_new_item) {
                const duplicateOrderItemId = getDuplicateOrderItemId(id, {
                    unitId: params.unit_id ? params.unit_id : null,
                    variantSign: selectedVariantSign
                });

                if (duplicateOrderItemId) {
                    let getProductOrderList = productOrderListMap.get(duplicateOrderItemId);
                    id = duplicateOrderItemId
                    
                    isExistInOrderList = true;
                    
                    if (params.price) {
                        params.price = Number.isNaN(parseFloat(params.price)) ? 0 : parseFloat(params.price);
                    }

                    params.qty = (getProductOrderList?.qty ? parseFloat(getProductOrderList?.qty) : 0) + (params?.qty ? parseFloat(params.qty) : 1);

                    productOrderListMap.set(id, {
                        ...getProductOrderList,
                        ...params
                    });
                } else {
                    id = generateOrderId();

                    params.qty = params?.qty ? (isNaN(params?.qty) ? 1 : parseFloat(params?.qty)) : 1;

                    productOrderListMap.set(id, {
                        price: Number.isNaN(parseFloat(getProductList.sale_price)) ? 0 : parseFloat(getProductList.sale_price),
                        unit_id: params?.unit_id ? params.unit_id : getProductList?.unit_id,
                        unit_name: params?.unit_name && params?.unit_id ? params?.unit_name : getProductList?.unit_name,
                        tax: Number.isNaN(parseFloat(getProductList.sale_tax)) ? 0 : parseFloat(getProductList.sale_tax),
                        tax_id: getProductList?.sale_tax_id,
                        tax_code: getProductList?.sale_tax_code,
                        detail: getProductList,
                        ...params
                    });
                }
            } else {
                if (params?.is_other_item) {
                    if (params.price) {
                        params.price = Number.isNaN(parseFloat(params.price)) ? 0 : parseFloat(params.price);
                    }
                    if (params.qty) {
                        params.qty = Number.isNaN(parseFloat(params.qty)) ? 1 : parseFloat(params.qty);
                    }
                    productOrderListMap.set(id, params);
                } else {
                    let getProductOrderList = productOrderListMap.get(productId);
                    id = productId
                    
                    isExistInOrderList = true;

                    if (params.price) {
                        params.price = Number.isNaN(parseFloat(params.price)) ? 0 : parseFloat(params.price);
                    }

                    if (params.qty) {
                        params.qty = Number.isNaN(parseFloat(params.qty)) ? 1 : parseFloat(params.qty);
                    }

                    productOrderListMap.set(id, {
                        ...getProductOrderList,
                        ...params
                    });
                }
            }

            const data = productOrderListMap.get(id) ? productOrderListMap.get(id) : params;

            if (data) {
                const {
                    code,
                    name,
                    unit_name: productDetailUnitName,
                    unit_id: productDetailUnitId,
                } = data?.detail || {};
                
                const {
                    qty,
                    price,
                    discount_value = 0,
                    discount_type: discountType = null,
                    unit_id = productDetailUnitId,
                    unit_name: unitName = productDetailUnitName,
                    note,
                } = data;

                const subtotal = qty * price;

                let discountValue = isNaN(Number(discount_value)) ? 0 : parseFloat(discount_value)

                let discountAmount = 0;

                if (discountType === 'percentage') {
                    discountAmount = subtotal * (discountValue / 100);
                } else if (discountType === 'amount') {
                    discountAmount = Math.min(discountValue, subtotal);
                }

                const totalPrice = subtotal - discountAmount;
    
                if (isExistInOrderList) {
                    // $('#order-list-container .order-item[data-id="'+id+'"] #input-qty-'+id)?.val(qty);
                    $('#order-list-container .order-item[data-id="'+id+'"] .code-placeholder')?.html(code);
                    $('#order-list-container .order-item[data-id="'+id+'"] .unit_name-placeholder')?.html(`(${unitName})`);
                    $('#order-list-container .order-item[data-id="'+id+'"] #tax-item-toggle-'+id)?.html(`${data?.tax_code ? data?.tax_code : (data?.tax ? data?.tax+'%' : 'Tidak Ada Pajak')}`);
                    $('#order-list-container .order-item[data-id="'+id+'"] .total-price-placeholder')?.html(totalPrice?.toLocaleString('en'));
                    if (parseFloat(discountValue) > 0) {
                        $(`.discount-placeholder[data-id="${id}"]`).css({"display": "block"});
                        
                    } else {
                        $(`.discount-placeholder[data-id="${id}"]`).css({"display": "none"});
                    }

                    if (discountType) {
                        $(`#input-discount-type-${id}`).val(discountType)
                        $(`#discount-type-symbol-${id}`).html(`${(discountType === 'percentage' ? '%' : '')}`);
                    } else {
                        $(`#input-discount-type-${id}`).val('');
                        $(`#discount-type-symbol-${id}`).html('');
                    }

                    if (!params?.inputPrice && params.price) {
                        $('#input-price-'+id).numericInput('setValue', price);
                    }
                    
                    if (note) {
                        $('#order-list-container .order-item[data-id="'+id+'"] .note-placeholder')?.html(note);
                        $('#order-list-container .order-item[data-id="'+id+'"] .note-placeholder')?.removeClass('d-none');
                    }

                    $('#input-discount-value-'+id).numericInput('destroy');

                    if (params?.discount_type === 'percentage' ) {
                        $('#input-discount-value-'+id).numericInput({
                            allowNegative: false,
                            maxValue: 100
                        });
                    } else if (params.discount_type == 'amount' ) {
                        $('#input-discount-value-'+id).numericInput({
                            allowNegative: false,
                            separator: ','
                        });
                    }

                    if (!params?.inputDiscount && params?.discount_value) {
                        $('#input-discount-value-'+id).numericInput('setValue', discountValue);
                    }

                    if (!params?.inputQty && params?.qty) {
                        $('#input-qty-'+id).numericInput('setValue', qty);
                    }
                    
                    if (params?.select_item === true) {
                        selectOrderItem(id);
                    }
                } else {
                    
                    $('#order-list-container').append(`
                        <div class="order-item d-flex" data-id="${id}">
                            <div class="p-3 d-flex gap-3 flex-grow-1">
                                <div class="qty-box d-flex align-items-center justify-content-center fw-bold" data-id="${id}">
                                    <input type="text" id="input-qty-${id}" data-id="${id}" class="input-item-qty" value="${qty}" autocomplete="off" />
                                </div>
                                <div class="flex-fill">
                                    <div class="d-flex justify-content-between mb-1">
                                        <h6 class="fw-bold mb-0 text-primary">${name} <span class="fw-normal unit_name-placeholder">(${unitName})</span></h6>
                                        <h6 class="fw-bold mb-0 total-price-placeholder">${totalPrice?.toLocaleString('en')}</h6>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted small code-placeholder">${code}</span>
                                        <div class="dropdown">
                                            <a href="#!" id="tax-item-toggle-${id}" data-id="${id}" class="text-muted dropdown-toggle">
                                                ${data?.tax_code ? data?.tax_code : (data?.tax ? data?.tax+'%' : 'Tidak Ada Pajak')} 
                                            </a>
                                        </div>
                                        <input class="input-price" data-id="${id}" id="input-price-${id}" id="input-price-${id}" value="${parseFloat(price)?.toLocaleString('en') ?? 0}" autocomplete="off" ${!!IS_CAN_CHANGE_ITEM_PRICE ? '' : 'disabled'} />
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold small text-danger discount-placeholder" data-id="${id}" style="display: ${parseFloat(discountValue) > 0 ? 'block' : 'none'}">Disc <input id="input-discount-value-${id}" class="input-discount-value" data-id="${id}" autocomplete="off" ${!!IS_CAN_CHANGE_ITEM_DISCOUNT ? '' : 'disabled'} /><span id="discount-type-symbol-${id}">${(discountType === 'percentage' ? '%' : '')}</span></span>
                                            <select id="input-discount-type-${id}" data-id="${id}" autocomplete="off" style="position: absolute; opacity: 0; pointer-events: none;">
                                                <option value="" ${(discountType == '' ? 'selected' : '')}></option>
                                                <option value="percentage" ${(discountType === 'percentage' ? 'selected' : '')}>percentage</option>
                                                <option value="amount" ${(discountType === 'amount' ? 'selected' : '')}>amount</option>
                                            </select>
                                            <span class="fw-bold small text-muted note-placeholder ${note ? '': 'd-none'} ">${note}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <button type="button" class="btn btn-danger h-100 rounded-0 p-0 delete-item-toggle" data-id="${id}" style="width: 30px"><span class="mdi mdi-trash-can"></span></button>
                            <div>
                        </div>
                    `);

                    $('#input-price-'+id).numericInput({
                        allowNegative: false,
                        separator: ','
                    })

                    $('#input-qty-'+id).numericInput({
                        allowNegative: false
                    })

                    if (discountType === 'percentage' ) {
                        $('#input-discount-value-'+id).numericInput({
                            allowNegative: false,
                            minValue: 100
                        });
                    } else {
                        $('#input-discount-value-'+id).numericInput({
                            allowNegative: false,
                            separator: ','
                        });
                    }

                    if (params?.select_item === true) {
                        selectOrderItem(id);
                    }
                }

                setInputAutoWidth($('#input-price-'+id)[0]);
                setInputAutoWidth($('#input-discount-value-'+id)[0]);
                setInputAutoWidth($('#input-qty-'+id)[0]);
            }

            calculateTotalItemBadge()
            setTotalInfo()

            if (callback && typeof callback === 'function') {
                result = data;
                result._id = id;
                callback(result)
            }
        }

        const removeProductFromOrderList = (productId) => {

            const $orderItem = $('#order-list-container .order-item[data-id="'+productId+'"]')
            
            const $next = $orderItem.next();
            const $prev = $orderItem.prev();
            const $element = $prev?.length > 0 ? $prev : ($next.length > 0 ? $next : null);

            $orderItem.remove()
            productOrderListMap.delete(productId);

            if ($element) {
                const dataId = $element.data('id');
                selectOrderItem(dataId)
            } else {
                selectOrderItem(null);
            }

            calculateTotalItemBadge();
            setTotalInfo();
        }

        $(document).on('click', '.delete-item-toggle', function(e){
            e.preventDefault();
            const $this = $(this);
            const productId = $this.data('id');

            if (productId) {
                if (IS_CAN_DELETE_ITEM) {
                    removeProductFromOrderList(productId);
                } else {
                    shouldAuthenticateSupervisor(() => {
                        removeProductFromOrderList(productId);
                    })
                }
            }
        })

        const setLoadingTable = (selector, reset = false) => {
            const rowsLength = $(selector +' thead tr th').length;
            if (reset) {
                $(selector +' tbody').html(`<tr><td colspan="${rowsLength}" class="text-center">Loading...</td></tr>`);
            } else {
                $(selector +' tbody').append(`<tr><td colspan="${rowsLength}" class="text-center">Loading...</td></tr>`);
            }
        }

        const setEmptyTable = (selector) => {
            const rowsLength = $(selector +' thead tr th').length;
            $(selector +' tbody').html(`<tr><td colspan="${rowsLength}" class="text-center">Tidak ada data</td></tr>`);
        }

        const setLoadingGrid = (selector, reset = false) => {
            if (reset) {
                $(selector).html(`
                        <div class="col-12 spinner-col">
                            <div class="d-flex justify-content-center">
                                <div class="spinner-border text-dark" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </div>`);
            } else {
                $(selector).append(`
                        <div class="col-12 spinner-col">
                            <div class="d-flex justify-content-center">
                                <div class="spinner-border text-dark" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </div>`);
            }
        }

        const setEmptyGrid = (selector, reset = false) => {
            $(selector).append(`
                <div class="col-12 spinner-col">
                    <div class="d-flex justify-content-center">
                        <span class="text-center text-muted">Tidak ada data</span>
                    </div>
                </div>`);
        }

        function loadProducts(props = {}) {
            if (props?.refresh) {
                loadProductIsLast = false;
                productPage = 1;
                productRowsCount = 0;
                productListMap.clear();
            }

            if (loadProductIsLoading || loadProductIsLast) return;
            
            loadProductIsLoading = true;

            const inputSearchProduct = props?.search ? props?.search : $('#input-search-product').val();
            const selectedCategoryId = $('#select-category-buttons .button-item.active').data('id');
        
            let req = {
                'order[id]': 'desc',
                is_active: 1,
                is_pos_display: 1,
                page: productPage,
                ...props?.params
            }

            if (IS_DISPLAY_CATALOG_MODE) {
                req = {
                    'order[id]': 'desc',
                    is_active: 1,
                    page: productPage,
                    with_product_details: true,
                    ...props?.params
                }
            }

            if (selectedCategoryId) {
                req.product_category_id = selectedCategoryId;
            }

            if (inputSearchProduct) {
                req.or = {
                    name: inputSearchProduct,
                    code: inputSearchProduct
                };
            }

            const reqParams = $.param(req);

            let url = BASE_URL + '/api/v1/products?'+reqParams;

            if (IS_DISPLAY_CATALOG_MODE) {
                url = BASE_URL + '/api/v1/product_catalogs?'+reqParams;
            }

            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                headers: {
                    'Authorization': TOKEN,
                    'company-id': COMPANY_ID,
                },
                beforeSend: function() {
                    
                    setLoadingTable('table#product-cashier-table', props?.refresh ? true : false);
                    setLoadingGrid('#product-cashier-grid', props?.refresh ? true : false);
                },
                success: function(res) {

                    let rows = '';
                    let productAsGrid = '';
                    let firstId = null;

                    if (res?.data?.length > 0) {
                        
                        $(res?.data).each((i, item) => {
                            
                            if (productRowsCount == 0 && props?.refresh) {
                                firstId = item?.id
                            }

                            let name = item?.name;

                            let tableRowStart = '';
                            let unitTableData = '';
                            let priceTableData = '<td class="text-end fw-bold text-body">0</td>';
                            
                            if (IS_DISPLAY_CATALOG_MODE) {
                                tableRowStart = `<tr data-row="${productRowsCount}" data-id="${item?.id}">`;
                                if (item.products.length > 0) {
                                    const prices = item.products.map(product => product.sale_price ? product.sale_price : 0);
    
                                    const minPrice = Math.min(...prices);
                                    const maxPrice = Math.max(...prices);
    
                                    const price = minPrice === maxPrice
                                                ? `${minPrice.toLocaleString('en')}`
                                                : `${minPrice.toLocaleString('en')} - ${maxPrice.toLocaleString('en')}`;

                                    priceTableData = '<td class="text-end fw-bold text-body">'+price+'</td>';
                                }
                            } else {
                                tableRowStart = `<tr data-row="${productRowsCount}" data-id="${item?.id}" data-code="${item?.code}" data-name="${item?.name}" data-unit_id="${item?.unit_id}" data-unit_name="${item?.unit_name}" data-product_category_id="${item?.product_category_id}" data-product_category_name="${item?.product_category_name}" data-sale_price="${item?.sale_price}" >`;
                                unitTableData = `<td>${item?.unit_name}</td>`;
                                priceTableData = `<td class="text-end fw-bold text-body">${isNaN(parseFloat(item?.sale_price)) ? 0 : parseFloat(item?.sale_price)?.toLocaleString('en')}</td>`
                            }

                            rows += `${tableRowStart}
                                <td>${item?.code}</td>
                                <td><span class="fw-bold text-body">${name}</span></td>
                                ${unitTableData}
                                <td>${item?.category_name ? `<span class="badge badge-label-secondary fw-bold text-gray">${item?.category_name}</span>` : 'Tidak Ada'}</td>
                                ${priceTableData}
                                </tr>`;

                            let thumbnail = `<div class="avatar avatar-label-primary" style="height: 60%; width: 100%; font-size: 35px;">
                                                <span class="mdi mdi-file-image-outline"></span>
                                            </div>`
                            
                            if (item?.media?.length > 0) {
                                const firstImage = item?.media?.[0];
                                const imageURL = BASE_URL + firstImage.filepath+'/'+firstImage?.filename;
                                thumbnail = `<img src="${imageURL}" alt="" class="product-image rounded-2">`
                            }
                            
                            productAsGrid += `<div class="col-3 product-col" data-row="${productRowsCount}" data-id="${item?.id}">
                                <div class="product-card">
                                    ${thumbnail}
                                        <h6 class="my-2 text-muted fw-bold" style="">${item?.category_name}</h6>
                                        <h5 class="mb-2 fw-bold" style="  display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">${item?.name}</h5>
                                        <h6 class="mb-2 mt-auto fw-bold">${isNaN(parseFloat(item?.sale_price)) ? 0 : parseFloat(item?.sale_price)?.toLocaleString('en')}</h6>
                                    </div>
                                </div>`;

                            if (IS_DISPLAY_CATALOG_MODE) {
                                productCatalogListMap.set(item.id, item);

                                item?.products.forEach((productDetail, productDetailIdx) => {
                                    if (!productListMap.has(productDetail.id)) {
                                        productListMap.set(productDetail.id, productDetail);
                                    }
                                });

                            } else {
                                productListMap.set(item?.id, item);
                            }
                            productRowsCount++
                        });
                            
                        $("table#product-cashier-table tbody tr:last").remove();
                        if (!props?.refresh) {
                            $('table#product-cashier-table tbody').append(rows);
                        } else {
                            $('table#product-cashier-table tbody').html(rows);
                        }

                        $('#product-cashier-grid .spinner-col').remove();
                        $('#product-cashier-grid').append(productAsGrid);

                        if (firstId) {
                            selectProduct(firstId);
                        }
                        
                        productPage++;
                    } else {
                        if (props?.refresh) {
                            setEmptyTable('table#product-cashier-table')
                            $('#product-cashier-grid .spinner-col').remove();
                            setEmptyGrid('#product-cashier-grid');
                        } else {
                            loadProductIsLast = true
                            $("table#product-cashier-table tbody tr:last").remove();
                            $('#product-cashier-grid .spinner-col').remove();
                        }
                    }
                    
                    loadProductIsLoading = false;

                    if (props?.callback && typeof props?.callback === 'function') {
                        props.callback(res);
                    }
                },
                error: () => {
                    loadProductIsLoading = false;
                    loadProductIsLast = false;
                    productRowsCount = 0;
                }
            });
        }

        const showCatalogPopUp = () => {
            const dataId = $('#product-cashier-table tr[data-selected=true]').attr('data-id');
            if (!dataId) return;

            const variantGroupped = (productVariants) => {
                let grouped = {};

                productVariants.forEach(productVariant => {
                
                    if (!grouped[productVariant.variant_id]) {
                        grouped[productVariant.variant_id] = {
                            variant_id: productVariant.variant_id,
                            variant_name: productVariant.variant_name,
                            variant_options: []
                        };
                    }
                
                    const exists = grouped[productVariant.variant_id].variant_options.some(race =>
                        race.variant_option_id === productVariant.variant_option_id
                    );
                
                    if (!exists) {
                        grouped[productVariant.variant_id].variant_options.push({
                            variant_option_id: productVariant.variant_option_id,
                            option_value: productVariant.option_value
                        });
                    }
                });

                return Object.keys(grouped).length > 0 ? Object.values(grouped) : [];
            }

            productVariants = [];

            if (IS_DISPLAY_CATALOG_MODE) {
                const data = productCatalogListMap.get(Number(dataId));
                if (!data && data?.products.length == 0) return;
    
                const products = data.products;
    
                if (products.length < 1) return;
    
                $('#product-catalog-info-code').html(data.code);
                $('#product-catalog-info-name').html(data.name);
                
                const prices = products.map(product => product.sale_price ? product.sale_price : 0);
    
                const minPrice = Math.min(...prices);
                const maxPrice = Math.max(...prices);
    
                const price = minPrice === maxPrice
                    ? `${minPrice.toLocaleString('en')}`
                    : `${minPrice.toLocaleString('en')} - ${maxPrice.toLocaleString('en')}`;
    
                $('#product-catalog-info-price').html(price);

                let grouped = {};

                products.forEach((product, idx) => {
                    product.product_variants.forEach(productVariant => {
                    
                        if (!grouped[productVariant.variant_id]) {
                            grouped[productVariant.variant_id] = {
                                variant_id: productVariant.variant_id,
                                variant_name: productVariant.variant_name,
                                variant_options: []
                            };
                        }
                    
                        const exists = grouped[productVariant.variant_id].variant_options.some(race =>
                            race.variant_option_id === productVariant.variant_option_id
                        );
                    
                        if (!exists) {
                            grouped[productVariant.variant_id].variant_options.push({
                                variant_option_id: productVariant.variant_option_id,
                                option_value: productVariant.option_value
                            });
                        }
                    });
                });
                
                productVariants = Object.values(grouped);
            } else {
                const data = productListMap.get(Number(dataId));
                if (!data) return;

                $('#product-catalog-info-code').html(data.code);
                $('#product-catalog-info-name').html(data.name);
                $('#product-catalog-info-price').html(Number(data.sale_price).toLocaleString('en'));

                productVariants = variantGroupped(data.product_variants);
            }

            let productVariantsHTML = '';
            if (productVariants?.length > 0) {
                productVariants.forEach((item, idx) => {
                    if (item?.variant_options?.length > 0) {
                        productVariantsHTML += '<div class="mb-3">';
                        productVariantsHTML += '<h6>'+item.variant_name+'</h6>';
                        productVariantsHTML += '<div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3" style="--bs-gutter-x: 6px; --bs-gutter-y: 6px;">';
                        item?.variant_options.forEach((opt, optIdx) => {
                            productVariantsHTML += '<div class="col">'
                            productVariantsHTML += '    <button type="button" class="btn btn-lg w-100 select-variant-toggle" data-variant_id="'+item.variant_id+'" data-variant_option_id="'+opt.variant_option_id+'" data-option_value="'+opt.option_value+'">'+opt.option_value+'</button>'
                            productVariantsHTML += '</div>';
                        });
                        productVariantsHTML += '</div>';
                        productVariantsHTML += '</div>';
                    }
                });
            }

            $('#input-product-qty').val(parseFloat(1)).trigger('change');
            $('#catalog-select-products-container').html(productVariantsHTML);
            $('#catalog-modal').modal('show');
        }

        const getSelectedProductByVariant = () => {

            const dataId = $('#product-cashier-table tr[data-selected=true]').attr('data-id');
            if (!dataId) return null;

            let data = null

            if (IS_DISPLAY_CATALOG_MODE) {
                const getData = productCatalogListMap.get(Number(dataId));
                if (!getData && getData?.products.length == 0) return null;
                data = getData.products;
            } else {
                const getData = productListMap.get(Number(dataId));
                if (!getData) return;
                data = getData;
            }

            const selectedVariantToggle = $('.select-variant-toggle.active');

            let params = [];
            selectedVariantToggle.each((idx, e) => {
                const variantId = $(e).attr('data-variant_id');
                const variantOptionId = $(e).attr('data-variant_option_id');
                params.push({
                    variant_id: variantId ? Number(variantId) : null,
                    variant_option_id: variantOptionId ? Number(variantOptionId) : null 
                })
            });

            let result = false; 

            if (IS_DISPLAY_CATALOG_MODE) {
                result = data.filter(product => {
                
                    if (product.product_variants.length !== params.length) {
                        return false;
                    }
                
                    return params.every(p => {
                        return product.product_variants.some(variant => {
                            return variant.variant_id === p.variant_id &&
                                   variant.variant_option_id === p.variant_option_id;
                        });
                    });
                
                });
            } else {

                const uniqueVariantIds = [...new Set(data.product_variants.map(p => p.variant_id))];
                const selectedVariantIds = params.map(s => s.variant_id);

                const isValid = params.length === uniqueVariantIds.length
                    && new Set(selectedVariantIds).size === selectedVariantIds.length
                    && uniqueVariantIds.every(id => selectedVariantIds.includes(id))
                    && params.every(selected =>
                        data.product_variants.some(item =>
                            item.variant_id === selected.variant_id &&
                            item.variant_option_id === selected.variant_option_id
                        )
                    );
                
                if (isValid) {
                    result = [data];
                }
                
            }

            return result?.length > 0 ? result[0] : null;
        }

        const validateSelectProductVariant = () => {
            const selectedProduct = getSelectedProductByVariant();
            const qty = Number($('#input-product-qty').val() ? $('#input-product-qty').val() : 0);
            const unit = $('#input-product-unit').val();

            if (selectedProduct && qty > 0 && unit) {
                $('#variant-submit-toggle').prop('disabled', false);
            } else {
                $('#variant-submit-toggle').prop('disabled', true);
            }
        }

        $(document).on('click', '.select-variant-toggle', function () {
            const $this = $(this);
            const variantId = $this.attr('data-variant_id');

            const isUnselect = $this.hasClass('active');
            
            $('.select-variant-toggle[data-variant_id="'+variantId+'"]').removeClass('active');
            if (!isUnselect) {
                $this.addClass('active');
            }
            
            const selectedProduct = getSelectedProductByVariant();

            $('#input-product-unit').html('<option value="">Pilih Satuan</option>')
            
            if ($('#input-product-unit').hasClass('select2-hidden-accessible')) {
                $('#input-product-unit').select2('destroy').val("").select2({
                    dropdownParent: $('#catalog-modal')
                });
            } else {
                $('#input-product-unit').val("").trigger('change')
            }

            if (selectedProduct) {

                $('#product-catalog-info-code').html(selectedProduct.code);
                $('#product-catalog-info-name').html(selectedProduct.name);
                $('#product-catalog-info-price').html(Number(selectedProduct.sale_price)?.toLocaleString('en'));
                
                getUnitSearch({
                    element: '#input-product-unit',
                    modal: $('#catalog-modal'),
                    filter: '&product_id='+selectedProduct?.id,
                    selected_val_object: { id: selectedProduct?.unit_id, text: selectedProduct?.unit_name }
                });
            } else {
                if (IS_DISPLAY_CATALOG_MODE) {
                    
                    const dataId = $('#product-cashier-table tr[data-selected=true]').attr('data-id');
                    const data = productCatalogListMap.get(Number(dataId));

                    if (data) {
                        $('#product-catalog-info-code').html(data.code);
                        $('#product-catalog-info-name').html(data.name);
                        
                        const prices = products.map(product => product.sale_price ? product.sale_price : 0);
            
                        const minPrice = Math.min(...prices);
                        const maxPrice = Math.max(...prices);
            
                        const price = minPrice === maxPrice
                            ? `${minPrice.toLocaleString('en')}`
                            : `${minPrice.toLocaleString('en')} - ${maxPrice.toLocaleString('en')}`;
            
                        $('#product-catalog-info-price').html(price);
                    }

                }
            }

            validateSelectProductVariant()
        });

        $(document).on('hidden.bs.modal', '#catalog-modal', function () {
            $('#product-catalog-info-code').html("N/A");
            $('#product-catalog-info-name').html("N/A");
            $('#product-catalog-info-price').html(0);

            $('#input-product-unit').html('<option value="">Pilih Satuan</option>')
            
            if ($('#input-product-unit').hasClass('select2-hidden-accessible')) {
                $('#input-product-unit').select2('destroy').val("").select2({
                    dropdownParent: $('#catalog-modal')
                });
            } else {
                $('#input-product-unit').val("").trigger('change')
            }

            $('#input-product-qty').val(parseFloat(1)).trigger('change');
            $('#catalog-select-products-container').html("");
            validateSelectProductVariant()
        });

        $(document).on('input change', '#input-product-qty', function () {
            validateSelectProductVariant()
        });

        $(document).on('change.select2', '#input-product-unit', function () {
            validateSelectProductVariant()
        });

        $(document).on('click', '#variant-submit-toggle', function () {
            const selectedProduct = getSelectedProductByVariant();
            const qty = Number($('#input-product-qty').val() ? $('#input-product-qty').val() : 0);
            const unit_id = Number($('#input-product-unit').val());
            const unit_name = $('#input-product-unit option:selected').text();

            const selectedVariantToggle = $('.select-variant-toggle.active');

            let variantNotes = [];
            let selectedVariants = [];
            selectedVariantToggle.each((idx, e) => {
                const optionValue = $(e).attr('data-option_value');
                selectedVariants.push({
                    variant_id: $(e).attr('data-variant_id'),
                    variant_option_id: $(e).attr('data-variant_option_id')
                })
                variantNotes.push(optionValue);
            });

            putProductToOrderList(selectedProduct.id, {
                qty: qty,
                unit_id: unit_id,
                unit_name: unit_name,
                is_new_item: true,
                note: variantNotes?.length > 0 ? variantNotes.join(', ') : '',
                unit_convertion: unit_id != selectedProduct.unit_id ? { id: unit_id, name: unit_name } : null,
                selectedVariants: selectedVariants,
                callback: (data) => {
                    setMultiplePrice(data._id);
                    resetPointExchange(true)
                }
            });

            $('#catalog-modal').modal('hide')
        });

        let productInputBuffer = '';
        let productInputLastTime = 0;
        let productInputIsScanner = false;
        let productInputTimer = null;

        $(document).on('input change', '#input-search-product', function (e) {
            const $this = $(this);
            if ($this.val()) {
                $('#reset-input-search-product-toggle').removeClass('d-none')
            } else {
                $('#reset-input-search-product-toggle').addClass('d-none')
            }
        });

        $(document).on('click', '#reset-input-search-product-toggle', function (e) {
            e.preventDefault();
            $('#input-search-product').val('').trigger('input');
            $('#input-search-product').focus()
        });

        $(document).on('input', '#input-search-product', function (e) {

            const now = Date.now();
            const diff = now - productInputLastTime;
            productInputLastTime = now;

            const char = e.originalEvent?.data;
            
            if (!char) {
                clearTimeout(typingTimer);

                typingTimer = setTimeout(() => {
                    loadProducts({
                        refresh: true,
                        search: $(this).val()
                    });
                }, 500)
                return
            };

            productInputBuffer += char;

            if (diff < SCAN_SPEED_THRESHOLD) {
              productInputIsScanner = true;
            } else {
              productInputIsScanner = false;
              productInputBuffer = char;
            }
        
            clearTimeout(typingTimer);
        
            typingTimer = setTimeout(() => {
                if (productInputIsScanner && productInputBuffer.length >= MIN_BARCODE_LENGTH) {
                    return
                } else {
                    clearTimeout(typingTimer);

                    typingTimer = setTimeout(() => {
                        loadProducts({
                            refresh: true,
                            search: $(this).val()
                        });
                    }, 500);
                }
            
                productInputBuffer = '';
                productInputIsScanner = false;
            }, 50);
        });

        const loadProductCategories = () => {

            let req = {
                'order[id]': 'desc',
                is_active: 1,
                all: true,
            }

            const reqParams = $.param(req);

            $.ajax({
                url: BASE_URL + '/api/v1/product_categories?'+reqParams+'?is_pos_display=true',
                type: "GET",
                dataType: "json",
                headers: {
                    'Authorization': TOKEN,
                    'company-id': COMPANY_ID,
                },
                beforeSend: function() {
                    $('#select-category-buttons').addClass('placeholder-glow');
                    $('#select-category-buttons').html(`
                        <span class="btn rounded-3 px-4 placeholder button-item">
                            Loading
                        </span>
                        <span class="btn rounded-3 px-4 placeholder button-item">
                            Loading
                        </span>
                        <span class="btn rounded-3 px-4 placeholder button-item">
                            Loading
                        </span>
                    `);
                },
                success: function(res) {
                    $('#select-category-buttons').removeClass('placeholder-glow');
                    let html = `<button type="button" class="btn rounded-3 px-4 btn-sm fw-bold fs-5 button-item active">
                                All Product
                            </button>`;

                    if (res?.length > 0) {
                        $(res).each((i, val) => {
                            html += `<button type="button" data-id="${val.id}" class="btn rounded-3 px-4 btn-sm fw-bold fs-5 button-item">
                                    ${val?.name}
                                </button>`
                        })
                    }

                    $('#select-category-buttons').html(html);
                    
                },
                error: () => {
                }
            });
        }

        $(document).on('click', '#select-category-buttons .button-item', function (e) {
            $('#select-category-buttons .button-item').removeClass('active');
            $(this).addClass('active');

            const dataId = $(this).data('id');

            clearTimeout(typingTimer);

            typingTimer = setTimeout(() => {
                loadProducts({
                    refresh: true,
                });
            }, 800)
        })

        $(document).on('click', '[id^=change-product-display-style-action-]', function () {
            const dataMode = $(this).data('mode');
            const id = $(this).attr('id');
            $('#product-display-style button').each((i, e) => {
                if ($(e).attr('id') != id) {
                    $(e).removeClass('active');
                } else {
                    $(e).addClass('active');
                }
            });

            $('#products-display').attr('data-mode', dataMode)
        })

        $(document).on('click', 'table#product-cashier-table tbody tr, #product-cashier-grid .product-col', function () {
            const $this = $(this);
            const dataId = $this.data('id') ?? null;

            if ($this.attr('data-selected') == 'true') {
                if (IS_DISPLAY_CATALOG_MODE) {
                    enterShortcut();
                } else {
                    const getProductList = productListMap.get(dataId);
                    if (getProductList) {
                        enterShortcut();
                    }
                }
                return
            }

            selectProduct(dataId);
        });

        $(document).on('click', '#order-list-container .order-item', function (e) {
            const $this = $(this);
            const dataId = $this.data('id') ?? null;

            if (!$(e.target).hasClass('delete-item-toggle')) {
                selectOrderItem(dataId);
            }
        });

        $('#products-display').on('scroll', function () {
            const el = this;
        
            const scrollTop = el.scrollTop;
            const windowHeight = el.clientHeight;
            const docHeight = el.scrollHeight;
        
            if (scrollTop + windowHeight >= docHeight - 100) {
                loadProducts();
            }
        });

        const arrowDownShortcut = () => {

            const customerModalIsShow = $('#customer-modal').hasClass('show');
            const unitModalIsShow = $('#unit-modal').hasClass('show');
            const anyModalIsShow = $('.modal.show');

            const allowed = ['customer-modal', 'unit-modal'];
                    
            if (anyModalIsShow.length > 0) {
                if (!allowed.includes(anyModalIsShow.attr('id'))) {
                    return;
                }
            }

            let rowSelector = '#product-cashier-table tbody tr';
            
            if ($('#products-display[data-mode=grid]').length > 0) {
                rowSelector = '#product-cashier-grid .product-col';
            }

            let scrollContainerSelector = '#products-display';
            let selectFunc = selectProduct;
            
            if (customerModalIsShow) {
                rowSelector = '#customer-table tbody tr';
                scrollContainerSelector = '#customer-table_wrapper .dt-scroll-body';
                selectFunc = selectCustomer
            }
            
            if (unitModalIsShow) {
                rowSelector = '#unit-table tbody tr';
                scrollContainerSelector = '#unit-table_wrapper .dt-scroll-body';
                selectFunc = selectUnit
            }
            
            let selectedRowSelector = rowSelector+'[data-selected="true"]';
            let firstRowSelector = rowSelector+':first-of-type';
            let selectedNextRow = $(firstRowSelector);
            
            if ($(selectedRowSelector).length > 0) {
                selectedNextRow = $(selectedRowSelector).next();
                if ($('#products-display[data-mode=grid]').length > 0) {
                    if (!customerModalIsShow || !unitModalIsShow) {
                        selectedNextRow = $(selectedRowSelector).nextAll().eq(3);
                    }
                }
            }


            if (selectedNextRow?.length > 0) {
                let dataId = selectedNextRow.data('id');
                let dataRow = selectedNextRow.data('row');
                
                if (dataId && dataRow != 0) {
                    selectFunc(dataId);
                }

                const container = $(scrollContainerSelector)[0];
                const selected = selectedNextRow[0];
                
                if (container && selected) {
                    const containerRect = container.getBoundingClientRect();
                    const selectedRect = selected.getBoundingClientRect();
                    let offset = container.clientHeight * 0.8;
                    
                    if ($('#products-display[data-mode=grid]').length > 0) {
                        if (!customerModalIsShow || !unitModalIsShow) {
                            offset = container.clientHeight * 0.1;
                        }
                    }
                
                    const scrollOffset =
                        selectedRect.top -
                        containerRect.top +
                        container.scrollTop -
                        offset;
                
                    container.scrollTo({
                        top: scrollOffset,
                        behavior: 'smooth'
                    });
                }
                
            } 
        }

        const arrowUpShortcut = () => {

            const customerModalIsShow = $('#customer-modal').hasClass('show');
            const unitModalIsShow = $('#unit-modal').hasClass('show');
            const anyModalIsShow = $('.modal.show');

            const allowed = ['customer-modal', 'unit-modal'];

            if (anyModalIsShow.length > 0) {
                if (!allowed.includes(anyModalIsShow.attr('id'))) {
                    return;
                }
            }

            let selectedPrevRowSelector = '#product-cashier-table tbody tr[data-selected="true"]';
            if ($('#products-display[data-mode=grid]').length > 0) {
                selectedPrevRowSelector = '#product-cashier-grid .product-col[data-selected="true"]';
            }
            let scrollContainerSelector = '#products-display';
            let selectFunc = selectProduct
            
            if (customerModalIsShow) {
                selectedPrevRowSelector = '#customer-table tbody tr[data-selected="true"]';
                scrollContainerSelector = '#customer-table_wrapper .dt-scroll-body';
                selectFunc = selectCustomer
            }

            if (unitModalIsShow) {
                selectedPrevRowSelector = '#unit-table tbody tr[data-selected="true"]';
                scrollContainerSelector = '#unit-table_wrapper .dt-scroll-body';
                selectFunc = selectUnit
            }
        
            let selectedPrevRow = $(selectedPrevRowSelector).prev();

            if ($('#products-display[data-mode=grid]').length > 0) {
                if (!customerModalIsShow || !unitModalIsShow) {
                    selectedPrevRow = $(selectedPrevRowSelector).prevAll().eq(3);
                }
            }

            if (selectedPrevRow?.length > 0) {
                let dataId = selectedPrevRow.data('id');
                let dataRow = selectedPrevRow.data('row');
            
                if (dataId) {
                    selectFunc(dataId);
                }
            
                requestAnimationFrame(() => {
                
                    const container =
                        $(scrollContainerSelector)[0];
                
                    const selected =
                        $(selectedPrevRowSelector)[0];
                
                    if (!container || !selected) return;
                
                    const containerRect = container.getBoundingClientRect();
                    const selectedRect = selected.getBoundingClientRect();
                
                    const offset = container.clientHeight * 0.2;
                
                    const relativeTop =
                        selectedRect.top -
                        containerRect.top +
                        container.scrollTop;
                
                    if (selectedRect.top < containerRect.top + offset) {
                        container.scrollTo({
                            top: relativeTop - offset,
                            behavior: 'smooth'
                        });
                    }
                
                });
            }
        };

        const arrowRightShortcut = () => {
            const anyModalIsShow = $('.modal.show');

            const allowed = ['customer-modal', 'unit-modal'];

            if (anyModalIsShow?.length > 0) {
                return
            }

            rowSelector = '#product-cashier-grid .product-col';

            let scrollContainerSelector = '#products-display';
            let selectFunc = selectProduct;
            
            let selectedRowSelector = rowSelector+'[data-selected="true"]';
            let firstRowSelector = rowSelector+':first-of-type';
            let selectedNextRow = $(firstRowSelector);
            
            if ($(selectedRowSelector).length > 0) {
                selectedNextRow = $(selectedRowSelector).next();
            }


            if (selectedNextRow?.length > 0 && $('#products-display[data-mode=grid]').length > 0) {
                let dataId = selectedNextRow.data('id');
                let dataRow = selectedNextRow.data('row');
                
                if (dataId && dataRow != 0) {
                    selectFunc(dataId);
                }

                const container = $(scrollContainerSelector)[0];
                const selected = selectedNextRow[0];
                
                if (container && selected) {
                    const containerRect = container.getBoundingClientRect();
                    const selectedRect = selected.getBoundingClientRect();
                    offset = container.clientHeight * 0.1;
                    
                    const scrollOffset =
                        selectedRect.top -
                        containerRect.top +
                        container.scrollTop -
                        offset;
                
                    container.scrollTo({
                        top: scrollOffset,
                        behavior: 'smooth'
                    });
                }
                
            } 
        }

        const arrowLeftShortcut = () => {
            const anyModalIsShow = $('.modal.show');

            if (anyModalIsShow?.length > 0) {
                return;
            }
        
            const rowSelector = '#product-cashier-grid .product-col';
            const scrollContainerSelector = '#products-display';
            const selectFunc = selectProduct;
        
            const selectedRowSelector = rowSelector + '[data-selected="true"]';
            const lastRowSelector = rowSelector + ':last-of-type';
            let selectedPrevRow = $(lastRowSelector);
        
            if ($(selectedRowSelector).length > 0) {
                selectedPrevRow = $(selectedRowSelector).prev();
            }

            if (selectedPrevRow?.length > 0 && $('#products-display[data-mode=grid]').length > 0) {
                const dataId = selectedPrevRow.data('id');
                const dataRow = selectedPrevRow.data('row');
            
                selectFunc(dataId);
            
                const container = $(scrollContainerSelector)[0];
                const selected = selectedPrevRow[0];
            
                if (container && selected) {
                    const containerRect = container.getBoundingClientRect();
                    const selectedRect = selected.getBoundingClientRect();
                    const offset = container.clientHeight * 0.1;
                
                    const scrollOffset =
                        selectedRect.top -
                        containerRect.top +
                        container.scrollTop -
                        offset;
                
                    container.scrollTo({
                        top: scrollOffset,
                        behavior: 'smooth'
                    });
                }
            }
        };

        const enterShortcut = () => {

            if (Swal.isVisible()) {
                return
            }

            const modals = [
                { modal: '#customer-modal', submit: '#submit-customer-toggle' },
                { modal: '#sync-modal', submit: '#submit-sync-toggle' },
                { modal: '#histories-modal', submit: null },
                { modal: '#stock-modal', submit: null },
                { modal: '#unit-modal', submit: '#submit-unit-toggle' },
                { modal: '#product-note-modal', submit: '#submit-product-note-toggle' },
                { modal: '#catalog-modal', submit: '#variant-submit-toggle' },
                { modal: '#redeem-modal', submit: '#submit-redeem-toggle' },
                { modal: '#payment-modal', submit: '#submit-payment-toggle' },
                { modal: '#taxes-modal', submit: '#submit-taxes-toggle' },
                { modal: '#access-denied-modal', submit: '#authenticate-supervisor-submit-toggle' },
            ];

            for (const { modal, submit } of modals) {
                if ($(modal).hasClass('show')) {
                    if (!submit) {
                        return
                    }
                    $(submit).trigger('click');
                    return;
                }
            }

            if (IS_DISPLAY_CATALOG_MODE) {
                showCatalogPopUp();
            } else {
                const getProductList = productListMap.get(selectedProductId);
                
                if (getProductList) {

                    if (getProductList.is_variant_multi_select) {
                        showCatalogPopUp();
                    } else {
                        let note = getProductList.product_variants
                            ?.map(item => item.option_value)
                            .join(', ') || '';
                        
                        putProductToOrderList(selectedProductId, {
                            // qty: getProductOrderList ? getProductOrderList?.qty + 1 : 1,
                            is_new_item: true,
                            unit_id: getProductList.unit_id,
                            unit_name: getProductList.unit_name,
                            note: note,
                            callback: (data) => {
                                setMultiplePrice(data._id);
                                resetPointExchange(true)
                            }
                        });
                    }
    
                    // const sound = new Audio(BASE_URL+'/assets/audio/beep.mp3');
                    // sound.play();
        
                    focusToSelectedOrderItem()
                }
            }
        }

        const deleteShortcut = (e) => {
            const tag = document.activeElement.tagName;

            if (tag === 'INPUT' || tag === 'TEXTAREA') {
                return;
            }

            if (selectedOrderProductId && !!IS_CAN_DELETE_ITEM) {
                e.preventDefault();
                removeProductFromOrderList(selectedOrderProductId);
            }
        }

        const escShortcut = () => {
            if (Swal.isVisible()) {
                return
            }

            const modals = ['#customer-modal', '#unit-modal', '#product-note-modal', '#catalog-modal', '#redeem-modal', '#taxes-modal', '#histories-modal'];

            for (const modal of modals) {
                if ($(modal).hasClass('show')) {
                    $(modal).modal('hide')
                    return;
                }
            }
        }

        const spaceShortcut = (e) => {
            const tag = document.activeElement.tagName;

            if (tag === 'INPUT' || tag === 'TEXTAREA') {
                return;
            }

            e.preventDefault();
            $('#input-search-product').focus();

        }

        const shortcutClick = (sc) => {
            if (sc == 'f2' && $('#payment-modal').hasClass('show')) {
                $('.insert-payment-toggle[data-value="exact"]').trigger('click')
                return
            }
            $('[data-shortcut="'+sc+'"]').trigger('click');
        }

        const shortcuts = {
            'arrowdown': arrowDownShortcut,
            'arrowup': arrowUpShortcut,
            'arrowright': arrowRightShortcut,
            'arrowleft': arrowLeftShortcut,
            'enter': enterShortcut,
            'esc': escShortcut,
            'delete': deleteShortcut,
            'tab': (e) => e.preventDefault(),
            ' ': spaceShortcut,
            'f1': () => shortcutClick('f1'),
            'f2': () => shortcutClick('f2'),
            'f3': () => shortcutClick('f3'),
            'f4': () => shortcutClick('f4'),
            'f6': () => shortcutClick('f6'),
            'f7': () => shortcutClick('f7'),
            'f8': () => shortcutClick('f8'),
            'f9': () => shortcutClick('f9'),
            'f10': () => shortcutClick('f10'),
            'f11': () => shortcutClick('f11'),
            'f12': () => shortcutClick('f12'),
        };

        $(document).on('click', function (e) {
            const clickedNumpad = $(e.target).closest('.numpad-key').length > 0;
            const clickedShortcutToggle = $(e.target).closest('.shortcut-toggle').length > 0;

            if (!clickedNumpad && !clickedShortcutToggle) {
                if (!$('[id^=input-price-]').is(':focus') && !$('[id^=input-qty-]').is(':focus') && !$('[id^=input-discount-value-]').is(':focus')) {
                    resetShortcutMode();
                }
            }
        });

        const scanProduct = (productCode) => {

            let req = {
                'with[0]': 'media',
                'with[1]': 'product_variants',
                'with[2]': 'multi_prices',
                code: productCode,
                page: 1
            }

            const reqParams = $.param(req);

            $.ajax({
                url: BASE_URL + '/api/v1/products?'+reqParams,
                type: "GET",
                dataType: "json",
                headers: {
                    'Authorization': TOKEN,
                    'company-id': COMPANY_ID,
                },
                beforeSend: function() {
                },
                success: function(res) {
                    let data = res.data?.[0];
                    
                    if (data) {
                        const getDuplicateItemId = getDuplicateOrderItemId(data?.id);
                        if (getDuplicateItemId) {
                            putProductToOrderList(data.id, {
                                is_new_item: true,
                            })
                        } else {
                            putProductToOrderList(generateOrderId(), {
                                price: Number.isNaN(parseFloat(data.sale_price)) ? 0 : parseFloat(data.sale_price),
                                unit_id: data?.unit_id,
                                unit_name: data?.unit_name,
                                tax: Number.isNaN(parseFloat(data.sale_tax)) ? 0 : parseFloat(data.sale_tax),
                                tax_id: data?.sale_tax_id,
                                tax_code: data?.sale_tax_code,
                                qty: 1,
                                detail: data,
                                is_other_item: 1
                            });
                        }
                    }
                },
            });
        }

        $(document).on('keydown', function (e) {

            if ($('#authenticate-modal').hasClass('show')) {
                return
            }

            const keys = [];

            if (e.ctrlKey) keys.push('ctrl');
            if (e.shiftKey) keys.push('shift');
            if (e.altKey) keys.push('alt');

            const now = Date.now();

            if (!startTime) startTime = now;

            const timeDiff = now - lastTime;

            if (timeDiff > SCAN_SPEED_THRESHOLD) {
                buffer = '';
                startTime = now;
            }
        
            if (
                e.key?.length === 1 &&
                !e.ctrlKey && 
                !e.metaKey &&
                !e.altKey
            ) {
              buffer += e.key;
            }
        
            lastTime = now;
        
            if (e.key === 'Enter') {
                const totalTime = now - startTime;
            
                const isScanner =
                    buffer.length >= MIN_BARCODE_LENGTH &&
                    totalTime < buffer.length * SCAN_SPEED_THRESHOLD;
                
                if (isScanner && $('#input-search-product').is(":focus") && !$('html').attr('data-shortcut-mode')) {
                    scanProduct(buffer);
                    $('#input-search-product').val('')
                }
            
                buffer = '';
                startTime = 0;

                if (isScanner) {
                    return 
                }
            }
            
            keys.push(e.key?.toLowerCase());
            
            const combo = keys.join('+');
            
            const allowDefault = [' '];

            if (
                combo == 'arrowright' ||
                combo == 'arrowleft'
            ) {
                if ($('input:focus')) {
                    return
                }
            }

            
            if (shortcuts[combo]) {
                resetShortcutMode();
                if (!allowDefault.includes(combo)) {
                    e.preventDefault();
                }
                shortcuts[combo](e);
            }
        });

        $(document).on('input', '[id^=input-qty-]', function (e) {
            const $this = $(this);
            let val = $(this).val();
            
            const numberVal = parseFloat(val);
            
            const dataId = $(this).data('id');
            setInputAutoWidth(this);
            
            if ($('html').attr('data-shortcut-mode') === 'qty' && dataId && !isNaN(numberVal)) {
                
                const item = productOrderListMap.get(dataId);
                if (item) {
                    putProductToOrderList(dataId, {
                        qty: numberVal,
                        inputQty: true
                    });
                }
            }

            setMultiplePrice(dataId);
        });

        $(document).on('focus', '[id^=input-qty-]', function () {
            const $this = $(this)
            const el = $(this).get(0);
            const length = el.value.length;
            el.setSelectionRange(length, length);
            resetShortcutMode()
            $this.select()
            document.documentElement.setAttribute('data-shortcut-mode', 'qty');
        });

        $(document).on('focusout', '[id^=input-qty-]', function () {
            const $this = $(this);
            const val = $this.val();
            const dataId = $this.data('id');
            const numberVal = !isNaN(Number(val)) ? Number(val) : 0;

            if (numberVal <= 0) {
                $this.val(1);
                putProductToOrderList(dataId, {
                    qty: 1,
                });
            }
        })

        $(document).on('input', '[id^=input-discount-value-]', function (e) {
            let numberVal = $(this).numericInput('getRaw');
        
            if (numberVal != 0) {
                setInputAutoWidth(this)
            }
            
            
            if (($('html').attr('data-shortcut-mode') === 'discount-type-percentage' || $('html').attr('data-shortcut-mode') === 'discount-type-amount') && selectedOrderProductId) {
                const item = productOrderListMap.get(selectedOrderProductId);
                if (numberVal && !isNaN(numberVal)) {
                    putProductToOrderList(selectedOrderProductId, {
                        discount_value: numberVal,
                        discount_type: $('#input-discount-type-'+selectedOrderProductId).val(),
                        inputDiscount: true
                    });
                } else {
                    putProductToOrderList(selectedOrderProductId, {
                        discount_value: 0,
                        discount_type: $('#input-discount-type-'+selectedOrderProductId).val(),
                        inputDiscount: true
                    });
                }
            }
        });

        $(document).on('input', '[id^=input-price-]', function (e) {
            let val = this.value || '0';

            val = val.replace(/[^\d.]/g, '');
            val = val.replace(/(\..*)\./g, '$1');

            let numberVal = parseFloat(val);

            setInputAutoWidth(this)
            
            if ($('html').attr('data-shortcut-mode') === 'price' && selectedOrderProductId && !isNaN(numberVal)) {
                const item = productOrderListMap.get(selectedOrderProductId);
                if (item) {
                    if ($(this).val()) {
                        putProductToOrderList(selectedOrderProductId, {
                            price: numberVal,
                            inputPrice: true
                        });
                    } else {
                        putProductToOrderList(selectedOrderProductId, {
                            price: 0,
                            inputPrice: true
                        });
                    }
                }
            }
        });

        $(document).on('focusout', '[id^=input-price-]', function (e) {
            const $this = $(this)
            const dataId = $this.data('id');
            const val = $this.val();

            if (!val) {
                const item = productOrderListMap.get(dataId);
                if (item) {
                    removeProductFromOrderList(dataId)
                }
            }

            if (!IS_CAN_CHANGE_ITEM_PRICE) {
                $this.prop('disabled', true)
            }
        });
    
        $(document).on('click', '#btn-toggle-numpad', function () {
            const $numpad = $('#container-numpad');
            $(this).prop('disabled', true)
            
            if ($numpad.hasClass('is-open')) {
                $numpad.removeClass('is-open').slideUp(200);
            } else {
                $numpad.addClass('is-open').slideDown(200);
            }
            
            setTimeout(() => {
                $(this).prop('disabled', false)
                focusToSelectedOrderItem();
            }, 250)
        });

        $(document).on('click', '#qty-shortcut-toggle', (e) => {
            if (selectedOrderProductId) {
                const productOrderListSelected = productOrderListMap.get(selectedOrderProductId);
                if (productOrderListSelected) {
                    $('#input-qty-'+selectedOrderProductId).focus();
                }
            }
        });

        $(document).on('focus', '[id^=input-discount-value-]', function () {
            const el = $(this).get(0);
            const length = el.value.length;
            el.setSelectionRange(length, length);

            const $this = $(this);
            const dataId = $this.data('id');
            resetShortcutMode();

            const discountType = $('#input-discount-type-'+dataId).val();
            if (discountType === 'percentage') {
                document.documentElement.setAttribute('data-shortcut-mode', 'discount-type-percentage');
            } else {
                document.documentElement.setAttribute('data-shortcut-mode', 'discount-type-amount');

            }
            
        })
        
        $(document).on('click', '#discount-percentage-shortcut-toggle', (e) => {
            if (selectedOrderProductId) {
                const productOrderListSelected = productOrderListMap.get(selectedOrderProductId);
                if (productOrderListSelected) {
                    if (IS_CAN_CHANGE_ITEM_DISCOUNT) {
                        $('#input-discount-type-'+selectedOrderProductId).val('percentage').trigger('change');
                        $('#input-discount-value-'+selectedOrderProductId).focus();
    
                        if (productOrderListSelected?.discount_type == 'amount') {
                            putProductToOrderList(selectedOrderProductId, {
                                discount_value: 0,
                                discount_type: 'percentage'
                            })
                        }
                    } else {
                        shouldAuthenticateSupervisor(() => {
                            $('#input-discount-type-'+selectedOrderProductId).val('percentage').trigger('change');
                            $('#input-discount-value-'+selectedOrderProductId).prop('disabled', false);
                            $('#input-discount-value-'+selectedOrderProductId).focus();
                            
                            if (productOrderListSelected?.discount_type == 'amount') {
                                putProductToOrderList(selectedOrderProductId, {
                                    discount_value: 0,
                                    discount_type: 'percentage'
                                })
                            }
                        })
                    }
                }
            }
        });

        $(document).on('change', '[id^=input-discount-type-]', function () {
            const $this = $(this);
            const dataId = $this.data('id')
            const val = $this.val();
            if (val) {
                putProductToOrderList(dataId, {
                    discount_type: val
                });
            }
        })
        
        $(document).on('click', '#discount-type-amount-shortcut-toggle', (e) => {
            if (selectedOrderProductId) {
                const productOrderListSelected = productOrderListMap.get(selectedOrderProductId);
                if (productOrderListSelected) {
                    if (IS_CAN_CHANGE_ITEM_DISCOUNT) {
                        $('#input-discount-type-'+selectedOrderProductId).val('amount').trigger('change');
                        $('#input-discount-value-'+selectedOrderProductId).focus();
                        if (productOrderListSelected?.discount_type == 'percentage') {
                            putProductToOrderList(selectedOrderProductId, {
                                discount_value: 0,
                                discount_type: 'amount'
                            })
                        }
                    } else {
                        shouldAuthenticateSupervisor(() => {
                            $('#input-discount-type-'+selectedOrderProductId).val('amount').trigger('change');
                            $('#input-discount-value-'+selectedOrderProductId).prop('disabled', false);
                            $('#input-discount-value-'+selectedOrderProductId).focus();
                            if (productOrderListSelected?.discount_type == 'percentage') {
                                putProductToOrderList(selectedOrderProductId, {
                                    discount_value: 0,
                                    discount_type: 'amount'
                                })
                            }
                        })
                    }
                }
            }
        });

        $(document).on('focus', '[id^=input-price-]', function () {
            resetShortcutMode();
            const el = $(this).get(0);
            const length = el.value.length;
            el.setSelectionRange(length, length);
            
            if (selectedOrderProductId) {
                const productOrderListSelected = productOrderListMap.get(selectedOrderProductId);
                if (productOrderListSelected) {
                    document.documentElement.setAttribute('data-shortcut-mode', 'price');
                }
            }
        })
        
        $(document).on('click', '#price-shortcut-toggle', (e) => {
            if (selectedOrderProductId) {
                if (!!IS_CAN_CHANGE_ITEM_PRICE) {
                    $('#input-price-'+selectedOrderProductId).focus();
                } else {
                    shouldAuthenticateSupervisor(() => {
                        $('#input-price-'+selectedOrderProductId).prop('disabled', false);
                        $('#input-price-'+selectedOrderProductId).focus();
                    })
                }
            }
        });

        $(document).on('click', '#unit-shortcut-toggle', function () {
            resetShortcutMode();
            hideAllModal();
            if (selectedOrderProductId) {
                if (IS_CAN_UNIT_ITEM) {
                    $('#unit-modal').modal('show');
                } else {
                    shouldAuthenticateSupervisor(() => {
                        $('#unit-modal').modal('show');
                    })
                }
            }
        });

        $(document).on('click', '#customer-modal-toggle', (e) => {
            resetShortcutMode();
            hideAllModal();
            $('#customer-modal').modal('show')
        });

        $(document).on('click', '#submit-customer-toggle', (e) => {
            if (selectedCustomerId) {
                selectedCustomer = customerListMap.get(selectedCustomerId);
                $('#customer-modal').modal('hide');
                setMultiplePrice()
                rewardPointsAppliedMap.clear();
                $('.apply-point-toggle').removeClass('selected');
                resetPointExchange();
                for (const [key, item] of productOrderListMap) {
                    if (item?.is_reward_item == true) {
                        removeProductFromOrderList(key)
                    }
                }
                setTotalInfo();
            }
            
            getSelectedCustomerInfo();
        });

        $(document).on('click', '#product-note-modal-toggle', (e) => {
            $('#product-note-modal .selected-product-name-placeholder').html('');
            $('#input-product-note').val('');
            if (selectedOrderProductId) {
                const data = productOrderListMap.get(selectedOrderProductId);
                
                $('#product-note-modal .selected-product-name-placeholder').html(data?.detail?.name);
                $('#input-product-note').val(data?.note);
                $('#product-note-modal').modal('show');
            }
        });

        $(document).on('click', '#submit-product-note-toggle', (e) => {
            hideAllModal()
            resetShortcutMode()
            if (selectedOrderProductId) {
        
                putProductToOrderList(selectedOrderProductId, {
                    note: $('#input-product-note').val()
                });
            }

            $('#product-note-modal').modal('hide');
        });

        $(document).on('click', '#redeem-modal-toggle', (e) => {
            hideAllModal()
            resetShortcutMode()
            // deletedRewardPointIds = [];
            const mainFunc = () => {
                if (selectedCustomer?.id) {
                    $('#redeem-customer-name-placeholder').html('')
                    $('#redeem-contact-group-name-placeholder').html('')
                    $('#redeem-total-point-placeholder').html(0);
        
                    const pointBalance = Number(selectedCustomer?.point_balance) ?? 0;
                    
                    $('#redeem-customer-name-placeholder').html(selectedCustomer?.name)
                    $('#redeem-contact-group-name-placeholder').html(selectedCustomer?.contact_group_name)
                    $('#redeem-total-point-placeholder').html(pointBalance?.toLocaleString('en'));
        
                    if (rewardPointPage == 1) {
                        loadRewardPoints({
                            refresh: true
                        })
                    }
        
                    
                    if (selectedCustomer.id !== selectedCustomerIdRewardPoint) {
                        $('#input-point').numericInput('clear');
                        $('#input-point').numericInput('destroy');
                        $('#input-point').numericInput({
                            allowNegative: false,
                            maxValue: parseFloat(selectedCustomer.point_balance)
                        });
                        totalPointApplied = 0;
                        totalDiscountPoint = 0;
                        $('#input-point').numericInput('setValue', 0);
                        $('#input-discount-point').val(0)
                        $('#redeem-total-discount-placeholder').html('Rp 0');
                    } else {
                        
                        // $('#input-point').numericInput({
                        //     allowNegative: false,
                        //     maxValue: parseFloat(selectedCustomer.point_balance)
                        // });
                        $('#input-point').numericInput('setValue', totalPointApplied);
                        // $('#redeem-total-discount-placeholder').html('Rp '+(totalPointApplied * pointCalculate));
                    }
        
                    // if (selectedCustomer.id !== selectedCustomerIdRewardPoint) {
                        // loadRewardPointProducts({
                        //     refresh: true
                        // });
                    // } else {
                        // const rewardPointsApplied = [...rewardPointsAppliedMap.values()];
                        // if (rewardPointsApplied?.length > 0) {
                        //     rewardPointsApplied.forEach((item, idx) => {
                                
                        //         if (item?.is_applied == true) {
                                    
                        //             if (item?.benefit_type === 'discount') {
                        //             }
                        //             putRewardPoint(item?.id, item);
                                    
                        //         } else {
                        //             rewardPointsAppliedMap.delete(item?.id);
                        //             if (item?.benefit_type === 'discount') {
                        //                 clearRewardPointDiscount()
                        //             }
                        //         }
                        //     });
                        // }
                    // }
        
                    $('#redeem-modal').modal('show');
                    
                    selectedCustomerIdRewardPoint = selectedCustomer?.id;
                }
            }

            if (IS_CAN_REDEEM_TRANSACTION) {
                mainFunc()
            } else {
                shouldAuthenticateSupervisor(() => {
                    mainFunc()
                })
            }

        });

        $('#reward-point-product-item-wrapper').on('scroll', function () {
            const el = this;
        
            const scrollTop = el.scrollTop;
            const windowHeight = el.clientHeight;
            const docHeight = el.scrollHeight;

            if (scrollTop + windowHeight >= docHeight - 100) {
                loadRewardPointProducts();
            }
        });

        const loadRewardPointProducts = (props) => {
            if (props?.refresh) {
                loadRewardPointProductIsLast = false;
                rewardPointProductPage = 1;
                rewardPointProductRowsCount = 0;
                rewardPointProductListMap.clear();
            }
    
            if (loadRewardPointProductIsLoading || loadRewardPointProductIsLast) return;
    
            loadRewardPointProductIsLoading = true;
    
            let req = {
                'order[total_point]': 'asc',
                is_active: 1,
                page: rewardPointProductPage,
                benefit_type: 'product',
                with_product_detail: true,
                ...props?.params
            }
    
            const reqParams = $.param(req);
    
            $.ajax({
                url: BASE_URL + '/api/v1/reward_points?'+reqParams,
                type: "GET",
                dataType: "json",
                headers: {
                    'Authorization': TOKEN,
                    'company-id': COMPANY_ID
                },
                beforeSend: function() {
                    if (props?.refresh) {
                        $('#reward-point-product-item-container').html('')
                    }
                },
                success: function(res) {
                    if (res?.data?.length > 0) {
                        if (selectedCustomer?.id) {
                            const pointBalance = Number(selectedCustomer?.point_balance) ?? 0;
                            res?.data?.forEach((item, idx) => {
                                if (rewardPointsAppliedMap.get(item?.id)) {
                                    return;
                                }
                                let productItem = '';
                                let isDisabled = false;
                            
                                if (item?.total_point > pointBalance) {
                                    isDisabled = true;
                                }
    
                                let discountHtml = '';
                                
                                if (item?.discount_type === 'percentage') {
                                    discountHtml = '<span class="fw-normal text-body" style="font-size: 10px">('+item?.discount_percentage+'%)</span>';
                                } else {
                                    discountHtml = '<span class="fw-normal text-body" style="font-size: 10px">(-'+item?.discount_amount+')</span>';
                                }
                            
                                productItem += '<div class="col-6">'
                                productItem +=      '<div class="reward-point-product-item '+(isDisabled ? 'disabled' : '')+'" data-total_point="'+item?.total_point+'" data-id="'+item?.id+'">'
                                productItem +=          '<h6 class="name-placeholder">'+item?.product_name+'</h6>'
                                productItem +=          '<h6 class="total-point-placeholder">'+item?.total_point+' Poin '+discountHtml+'</h6>'
                                productItem +=      '</div>'
                                productItem +=  '</div>'

                                rewardPointProductListMap.set(item.id, item)

                                $('#reward-point-product-item-container').append(productItem);
                            });
                        
                            rewardPointProductPage++;
                        }
                    
                    } else {
                        loadRewardPointProductIsLast = true;
                    }
                },
                error: () => {
                    loadRewardPointProductIsLoading = false;
                    loadRewardPointProductIsLast = false;
                    rewardPointProductRowsCount = 0;
                },
                complete: function () {
                    loadRewardPointProductIsLoading = false;
                }
            });
        }

        const setRewardPointDiscountInfo = (rewardPointDiscountId) => {

            const data = rewardPointsAppliedMap.get(rewardPointDiscountId)

            if (data) {
                const calculateTotal = getCalculateTotal();
    
                let totalDiscount = 0;
                
                if (data?.discount_type == 'percentage') {
                    totalDiscount = calculateTotal?.total * (data?.discount_percentage / 100);
                } else if (data?.discount_type == 'amount') {
                    totalDiscount = data?.discount_amount;
                }
                
                if (totalDiscount > data?.maximum_discount_amount) {
                    totalDiscount = data?.maximum_discount_amount;
                }
    
                let calculateDiscount = calculateTotal?.total - totalDiscount;
    
                $('#redeem-total-discount-placeholder').html(totalDiscount?.toLocaleString('en'))
                $('#redeem-maximum-discount-amount-placeholder').html(data?.maximum_discount_amount?.toLocaleString('en'));
            }
        }

        const putRewardPoint = (id, data, params = {}) => {

            if (data?.benefit_type === 'product') {
                
                const el = $('.reward-point-product-item[data-id="'+id+'"]');
                const pointBalance = Number(selectedCustomer?.point_balance) ?? 0;

                if (el?.length < 1 && rewardPointsAppliedMap.get(id)) {

                    let productItem = '';
                    let isDisabled = false;
                            
                    if (data?.total_point > pointBalance) {
                        return
                    }
    
                    let discountHtml = '';
                    
                    if (data?.discount_type === 'percentage') {
                        discountHtml = '<span class="fw-normal text-body" style="font-size: 10px">('+data?.discount_percentage+'%)</span>';
                    } else {
                        discountHtml = '<span class="fw-normal text-body" style="font-size: 10px">(-'+item?.discount_amount+')</span>';
                    }
                            
                    productItem += '<div class="col-6">'
                    productItem +=      '<div class="reward-point-product-item selected '+(isDisabled ? 'disabled' : '')+'" data-total_point="'+data?.total_point+'" data-id="'+data?.id+'">'
                    productItem +=          '<h6 class="name-placeholder">'+data?.product_name+'</h6>'
                    productItem +=          '<h6 class="total-point-placeholder">'+data?.total_point+' Poin '+discountHtml+'</h6>'
                    productItem +=      '</div>'
                    productItem +=  '</div>'

                    rewardPointProductListMap.set(data.id, data)
                                
                    $('#reward-point-product-item-container').append(productItem);
                } else {
                    const elAlready = $('.reward-point-product-item[data-id="'+id+'"]:not(.disabled)');
                    
                    if (!elAlready.hasClass('selected')) {
                        elAlready.addClass('selected');
                        rewardPointsAppliedMap.set(id, data);
                        if (deletedRewardPointIds.includes(id)) {
                            deletedRewardPointIds = deletedRewardPointIds.filter(item => item !== id);
                        }
                    } else {
                        elAlready.removeClass('selected');        
                        rewardPointsAppliedMap.delete(id);
                        deletedRewardPointIds.push(id);
                    }
                }
            }

            validateTotalPointExchange();
            
        }

        $(document).on('click', '.reward-point-product-item:not(.disabled)', function () {

            const $this = $(this);
            const dataId = $this.data('id');

            const data = rewardPointProductListMap.get(dataId);

            if (data) {
                putRewardPoint(dataId, data);
            }
        });

        $(document).on('click', '#submit-redeem-toggle', function () {
            if (applyPointIsValid()) {
                totalPointApplied = $('#input-point').numericInput('getRaw');
            } else {
                totalPointApplied = 0;
            }

            totalDiscountPoint = getCalculateTotal().total - parseFloat($('#input-discount-point').val() ? $('#input-discount-point').val() : 0);
            
            for (const [key, val] of rewardPointsAppliedMap) {
                rewardPointsAppliedMap.set(key, { ...val, is_applied: true });
                // if (val?.benefit_type === 'product') {
                //     let productDetail = val?.product_detail;
                //     let params = {}
                    
                //     if (productDetail?.id) {
                //         params.discount_type = val?.discount_type;
                //         params.discount_value = val?.discount_type == 'percentage' ? val?.discount_percentage : val?.discount_amount;
                //         params.qty = val?.qty;
                //         params.detail = productDetail;
                //         params.price = productDetail?.sale_price;
                //         params.tax = productDetail?.sale_tax;
                //         params.is_reward_item = true;
                //         params.reward_point_id = key;
                //         params.is_other_item = true;
                //         putProductToOrderList(generateOrderId(), params);
                //     }
                // }
            }

            // deletedRewardPointIds.forEach((item) => {
            //     removeProductFromOrderList('redeem_id-'+item)
            // });
            
            // deletedRewardPointIds = []
            $('#redeem-modal').modal('hide')
            setTotalInfo()
            
        });

        const applyPointIsValid = () => {
            if (getCalculateTotal().total > 0 && $('#input-point').numericInput('getRaw') <= Number(selectedCustomer?.point_balance)) {
                return true
            }

                // if (($('#input-point').numericInput('getRaw') * pointCalculate) > getCalculateTotal().total) {
                //     return false
                // }

                // const data = [...rewardPointsAppliedMap.values()];

                // if (data?.length > 0) {
                //     data.forEach((val, index) => {
                //         let totalPoint = val.total_point;
                //         if (val?.benefit_type == 'discount') {
                //             totalPoint = val?.input_point ? val?.input_point : val?.total_point
                //         }
                //         totalPointExchange += Number(totalPoint);
                //     })
                    
                //     if (totalPointExchange > totalPointCutomer) {
                //         return false;
                //     }   
                // }

            return false;
        }

        const validateTotalPointExchange = () => {
            if (applyPointIsValid()) {
                $('#submit-redeem-toggle').prop('disabled', false)
            } else {
                $('#submit-redeem-toggle').prop('disabled', true)
            }
        }

        const getMatchedMultiPrice = (id) => {
            const productOrderList = productOrderListMap.get(id);

            // console.log(selectedCustomer?.contact_group_id, '{{ config('user_companies.branch_id') ? config('user_companies.branch_id') : config('general_settings.default_branch')  }}', productOrderList.qty, productOrderList.unit_id);
            
            if (productOrderList) {

                const candidates = productOrderList.detail?.multi_prices?.filter(d =>
                    (
                        d.contact_group_id === null ||
                        d.contact_group_id == selectedCustomer?.contact_group_id
                    ) &&
                    (
                        d.branch_id === null ||
                        d.branch_id == '{{ config('user_companies.branch_id') ? config('user_companies.branch_id') : config('general_settings.default_branch')  }}'
                    ) &&
                    productOrderList.qty >= parseFloat(d.from_qty) &&
                    productOrderList.qty <= parseFloat(d.to_qty) &&
                    productOrderList.unit_id == d.unit_id
                );

                const matchedPrice = candidates.sort((a, b) => {
                    const score = (d) => {
                        let s = 0;

                        if (d.branch_id != null && d.branch_id == '{{ config('user_companies.branch_id') ? config('user_companies.branch_id') : config('general_settings.default_branch') }}') {
                            s += 1;
                        }
                    
                        if (d.contact_group_id != null && d.contact_group_id == selectedCustomer?.contact_group_id) {
                            s += 1;
                        }
                    
                        return s;
                    };
                
                    return score(b) - score(a);
                })[0];

                return matchedPrice ? matchedPrice : null;
            }

            return null
        }

        const setMultiplePrice = (id = null) => {
            if (id) {
                const productOrderList = productOrderListMap.get(id);
                if (productOrderList) {
    
                    const matchedPrice = getMatchedMultiPrice(id);
                    
                    if (matchedPrice?.unit_price) {
                        putProductToOrderList(id, {
                            price: parseFloat(matchedPrice.unit_price),
                        })
                    } else {
                        let unitConvertion = productOrderList?.detail?.unit_conversions?.find((item) => item?.to_unit_id == productOrderList?.unit_convertion?.id && item?.from_unit_id == productOrderList?.detail?.unit_id);
                        let convertionValue = 1;
                        
                        let isProductUnitConvert = 0;
                        if (unitConvertion) {
                            convertionValue = parseFloat(unitConvertion.from_value) > 0 ? parseFloat(unitConvertion.from_value) : 1;
                            isProductUnitConvert = 1;
                        } else {
                            unitConvertion = productOrderList?.unit_convertion?.conversions?.find((item) => item?.to_unit_id == productOrderList?.detail?.unit_id);
                            if (unitConvertion) {
                                convertionValue = parseFloat(unitConvertion.to_value) > 0 ? parseFloat(unitConvertion.to_value) : 1;
                            }
                        }

                        let params = {}
                        
                        params.convertion_value = convertionValue;
                        params.price = productOrderList?.detail.sale_price * convertionValue;
                        params.is_product_unit_convert = isProductUnitConvert;

                        putProductToOrderList(id, params)
                    }
                }
            } else {
                if (selectedCustomer && productOrderListMap?.size > 0) {
                    for (const [key, item] of productOrderListMap) {
                        const getOrderItem = productOrderListMap.get(key);
                        if (getOrderItem) {
                            const matchedPrice = getMatchedMultiPrice(key);
                            
                            if (matchedPrice?.unit_price) {
                                putProductToOrderList(key, {
                                    price: parseFloat(matchedPrice.unit_price),
                                    select_item: false,
                                })
                            } else {
                                let unitConvertion = getOrderItem?.detail?.unit_conversions?.find((item) => item?.to_unit_id === getOrderItem?.unit_convertion?.id && item?.from_unit_id === getOrderItem?.detail?.unit_id);
                                let convertionValue = 1;
                                let isProductUnitConvert = 0;
                                if (unitConvertion) {
                                    convertionValue = unitConvertion.from_value > 0 ? unitConvertion.from_value : 1;
                                    isProductUnitConvert = 1;
                                } else {
                                    unitConvertion = getOrderItem?.unit_convertion?.conversions?.find((item) => item?.to_unit_id === getOrderItem?.detail?.unit_id);
                                    if (unitConvertion) {
                                        convertionValue = unitConvertion.to_value > 0 ? unitConvertion.to_value : 1;
                                    }
                                }
                            
                                let params = {}                    
                                params.convertion_value = convertionValue;
                                params.price = getOrderItem?.detail.sale_price * convertionValue;
                                params.is_product_unit_convert = isProductUnitConvert;
                            
                                putProductToOrderList(key, params)
                            }
                        }
                    }
                }
            }

        }

        let customerDt = $('#customer-table').DataTable({
            scrollY: 400,
            paging: false,
            searching: false,
            ordering: false,
            info: false,
            processing: true,
            rowId: 'id',
            columns: [
                { data: 'code' },
                { data: 'name' },
                { data: 'phone' },
                { data: 'point_balance',
                  render: (data, type, row) => {
                    return parseFloat(data)?.toLocaleString('en')
                  }
                }
            ],
            createdRow: function (row, data) {
                $(row).attr('data-id', data.id);
            }
        });

        const selectCustomer = (id = null, props = {}) => {

            $('table#customer-table tbody tr').removeAttr('data-selected');

            if (id) {
                const selectedRow = $('table#customer-table tbody tr[data-id='+id+']');
                
                selectedRow.attr('data-selected', "true");
                selectedCustomerId = id;

            } else {
                selectedCustomerId = null;
            }
        }

        const getSelectedCustomerInfo = (setDefault = false) => {
            if (selectedCustomer?.name) {
                $('#customer-modal-toggle').html(`<span class="fw-normal">[F7]</span><br>${selectedCustomer?.name}`);
            } else {
                if (setDefault && defaultCustomer?.name) {
                    selectedCustomer = defaultCustomer
                    $('#customer-modal-toggle').html(`<span class="fw-normal">[F7]</span><br>${defaultCustomer?.name}`);
                } else {
                    $('#customer-modal-toggle').html('Pelanggan<br><span class="fw-normal">[F7]</span>');
                }
            }
        }
    
        function loadCustomers(props = {}) {
            
            if (!customerDt) return

            if (props.refresh) {
                customerPage = 1;
                loadCustomerIsLast = false;
                customerListMap.clear();
                customerDt.clear().draw();
            }

            if (loadCustomerIsLoading || loadCustomerIsLast) return;

        
            loadCustomerIsLoading = true;
        
            let inputSearch = $('#input-search-customer').val();
        
            let req = {
                'order[id]': 'desc',
                is_active: 1,
                is_customer: 1,
                is_pos_display: 1,
                page: customerPage,
                ...props.params
            };
        
            if (inputSearch) {
                req.or = {
                    name: inputSearch,
                    phone: inputSearch
                };
            }
        
            $.ajax({
                url: BASE_URL + '/api/v1/contacts',
                type: "GET",
                dataType: "json",
                data: req,
                headers: {
                    'Authorization': TOKEN,
                    'company-id': COMPANY_ID
                },
                beforeSend: function () {
                    customerDt.processing(true);
                },
                success: function (res) {
                    if (res?.data?.length > 0) {
                    
                        res.data.forEach(item => {
                            customerListMap.set(item.id, item);
                        });
                    
                        customerDt.rows.add(res.data).draw(false);
                    
                        customerPage++;
                    
                    } else {
                        loadCustomerIsLast = true;
                    }
                    
                    if (props?.onSuccess && typeof props?.onSuccess === 'function') {
                        props.onSuccess()
                    }
                },
                error: function () {
                    loadCustomerIsLast = false;
                },
                complete: function () {
                    loadCustomerIsLoading = false;
                    customerDt.processing(false);
                }
            });
        }

        $(document).on('shown.bs.modal', '#customer-modal', function () {
            if (!customerModalHasBeenOpen) {

                loadCustomers({
                    refresh: true
                });
            }
            
            customerModalHasBeenOpen = true
        })


        $(document).on('click', 'table#customer-table tbody tr', function () {
            const $this = $(this);
            const dataId = $this.data('id') ?? null;
            
            if ($this.attr('data-selected') == 'true') {
                $('#submit-customer-toggle').trigger('click')
            } else {
                selectCustomer(dataId);
            }
        });

        $('#customer-table_wrapper .dt-scroll-body').on('scroll', function () {
            const el = this;
        
            const scrollTop = el.scrollTop;
            const windowHeight = el.clientHeight;
            const docHeight = el.scrollHeight;
        
            if (scrollTop + windowHeight >= docHeight - 100) {
                loadCustomers();
            }
        });

        $('#customer-modal').on('show.bs.modal', function () {
            resetShortcutMode()
            if (selectedCustomer?.id) {
                selectCustomer(selectedCustomer?.id);
            }
        });

        $('#customer-modal').on('shown.bs.modal', function () {
            $('#input-search-customer').focus()
        });

        $(document).on('input', '#input-search-customer', function () {
            setTimeout(() => {
              loadCustomers({refresh: true});
            }, 800);
        });

        const selectUnit = (id = null, props = {}) => {

            $('table#unit-table tbody tr').removeAttr('data-selected');

            if (id) {
                const selectedRow = $('table#unit-table tbody tr[data-id='+id+']');
                
                selectedRow.attr('data-selected', "true");
                selectedUnitId = id;

            } else {
                selectedUnitId = null;
            }
        }

        const unitDt = $('#unit-table').DataTable({
            scrollY: 400,
            scroller: true,
            paging: false,
            searching: false,
            ordering: false,
            info: false,
            processing: true,
            rowId: 'id',
            columns: [
                { data: 'code' },
                { data: 'name' },
                { data: 'symbol' }
            ],
            createdRow: function (row, data) {
                $(row).attr('data-id', data.id);
            }
        });

        function loadUnits(props = {}) {

            if (props?.refresh) {
                loadUnitIsLast = false;
                unitPage = 1;
                unitRowsCount = 0;
                unitListMap.clear();
                unitDt.clear().draw();
            }
    
            if (loadUnitIsLoading || loadUnitIsLast) return;
    
            loadUnitIsLoading = true;
    
            const inputSearchUnit = $('#input-search-unit').val();
    
            let req = {
                'order[id]': 'desc',
                is_active: 1,
                page: unitPage,
                ...props?.params
            }

            const selectedOrderItem = productOrderListMap.get(selectedOrderProductId)       

            if (selectedOrderItem?.detail?.id) {
                req.product_id = selectedOrderItem?.detail?.id;
            }
    
            if (inputSearchUnit) {
                req.name = inputSearchUnit;
            }
    
            const reqParams = $.param(req);
    
            $.ajax({
                url: BASE_URL + '/api/v1/units?'+reqParams,
                type: "GET",
                dataType: "json",
                headers: {
                    'Authorization': TOKEN,
                    'company-id': COMPANY_ID,
                },
                beforeSend: function() {
                    unitDt.processing(true);
                },
                success: function(res) {
                    if (res?.data?.length > 0) {
                    
                        res.data.forEach(item => {
                            unitListMap.set(item.id, item);
                        });
                    
                        unitDt.rows.add(res.data).draw(false);
                    
                        unitPage++;
                    
                    } else {
                        loadUnitIsLast = true;
                    }
                },
                error: () => {
                    loadUnitIsLoading = false;
                    loadUnitIsLast = false;
                    unitRowsCount = 0;
                },
                complete: function () {
                    loadUnitIsLoading = false;
                    unitDt.processing(false);
                }
            });
        }

        $(document).on('shown.bs.modal', '#unit-modal', function () {
            const selectedOrderItem = productOrderListMap.get(selectedOrderProductId);
            
            if (!unitModalHasBeenOpen || selectedProductIdLoadUnit != selectedOrderItem?.detail?.id) {
                loadUnits({
                    refresh: true
                });
            }

            selectedProductIdLoadUnit = selectedOrderItem?.detail?.id
            unitModalHasBeenOpen = true
        })

        $(document).on('click', 'table#unit-table tbody tr', function () {
            const $this = $(this);
            const dataId = $this.data('id') ?? null;
            if ($this.attr('data-selected') == 'true') {
                $('#submit-unit-toggle').trigger('click')
            } else {
                selectUnit(dataId);
            }
        });

        $('#unit-table_wrapper .dt-scroll-body').on('scroll', function () {
            const el = this;
        
            const scrollTop = el.scrollTop;
            const windowHeight = el.clientHeight;
            const docHeight = el.scrollHeight;
            
            if (docHeight > windowHeight && !loadUnitIsLoading) {
                if (scrollTop + windowHeight >= docHeight - 100) {
                    loadUnits();
                }
            }
        });

        $(document).on('input', '#input-search-unit', function () {
            setTimeout(() => {
              loadUnits({refresh: true});
            }, 800);
        });

        $(document).on('click', '#submit-unit-toggle', (e) => {
            if (selectedProductId && selectedUnitId) {
                const orderItem = productOrderListMap.get(selectedOrderProductId);
                const unit = unitListMap.get(selectedUnitId);

                let params = {
                    unit_id: selectedUnitId,
                    unit_name: unit?.name,
                    unit_convertion: unit
                }

                putProductToOrderList(selectedOrderProductId, params);
                setMultiplePrice(selectedOrderProductId);
                
                $('#unit-modal').modal('hide');
            }
        });

        $('#unit-modal').on('show.bs.modal', function () {
            resetShortcutMode()
            if (selectedOrderProductId) {
                const item = productOrderListMap.get(selectedOrderProductId);
                selectUnit(item?.unit_id ?? item?.detail?.unit_id);
            }
        });

        $(document).on('click', '#void-toggle', function() {

            const mainFunc = () => {
                withShortcutSwal({
                    title: "Dibutuhkan konfirmasi",
                    icon: 'warning',
                    html: `Apakah anda yakin ingin reset semua data?`,
                    showCancelButton: true,
                    confirmButtonColor: 'var(--bs-success)',
                    cancelButtonColor: 'var(--bs-danger)',
                    confirmButtonText: '{{ __('language.yes') }}',
                    cancelButtonText: '{{ __('language.cancel') }}',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        clear({
                            setDefaultCustomer: true
                        });
                        // $('#input-search-product').val('').trigger('input')
                    } else {
                        Swal.close();
                    }
                });
            }

            if (IS_CAN_VOID_TRANSACTION) {
                mainFunc()
            } else {
                shouldAuthenticateSupervisor(() => {
                    mainFunc()
                })
            }
        });

        $(document).on('shown.bs.modal', '#authenticate-modal', function () {
            $('#input-password').focus()
        })

        $(document).on('shown.bs.hidden', '#authenticate-modal', function () {
            $('#input-password').value('');
        })

        $(document).on('click', '#lockscreen-toggle', function() {
            $('#authenticate-modal').modal('show');
            $.ajax({
                type: 'post',
                url: BASE_URL + '/pos/logout',
                "headers": {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'JSON',
            })
        });

        $(document).on('submit', '#access-denied-form', function(e) {
            e.preventDefault();
            var formData = new FormData($('#access-denied-form')[0]);

            $.ajax({
                type: 'post',
                url: BASE_URL + "/api/v1/pos/verif_supervisor",
                "headers": {
                    'Authorization': TOKEN,
                    'company-id': COMPANY_ID
                },
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                beforeSend: function () {
                    $('#authenticate-supervisor-submit-toggle').prop('disabled', true);
                    $('#access-denied-modal .alert-container').html('');
                },
                success: function (res) {
                    $('#access-denied-modal').modal('hide');
                    if (authenticateSupervisorOnSuccess && typeof authenticateSupervisorOnSuccess === 'function') {
                        authenticateSupervisorOnSuccess(res);
                    }
                },
                error: (jqXHR, textStatus, errorThrown) => {

                    let err = jqXHR.responseJSON?.message;
                    
                    if (jqXHR?.status == 422) {
                        err = jqXHR.responseJSON?.errors;
                    }

                    let message = err;
                    
                    if (typeof err === 'object') {
                        message = '<ul style="padding: 0px; margin: 0px; list-style-type: none;">';
                        Object.keys(err).forEach(key => {
                            const value = err[key]?.[0];
                            message += '<li>'+value+'</li>'
                        });
                        message += '</ul>';
                    }

                    $('#access-denied-modal .alert-container').html(`
                        <div class="alert alert-danger alert-dismissible">
                            <div class="alert-content">
                                ${message}
                            </div>
                        </div>
                    `);
                },
                complete: function () {
                    $('#authenticate-supervisor-submit-toggle').prop('disabled', false);
                }
            })
        });

        $(document).on('click', '#authenticate-supervisor-submit-toggle', function () {
            $('#access-denied-form').trigger('submit')
        })

        $(document).on('submit', '#authenticate-form', function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                type: 'post',
                url: BASE_URL + "/api/v1/pos/login",
                "headers": {
                    'Authorization': TOKEN,
                    'company-id': COMPANY_ID
                },
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                beforeSend: function () {
                    $('#authenticate-submit-toggle').prop('disabled', true);
                    $('#authenticate-modal .alert-container').html('');
                },
                success: function (res) {
                    $.ajax({
                        type: 'post',
                        url: BASE_URL + '/pos/authorize',
                        "headers": {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType: 'JSON',
                        success: function(res) {
                            $('#authenticate-modal').modal('hide');
                            starting()
                        }
                    })
                    
                },
                error: (jqXHR, textStatus, errorThrown) => {

                    let err = jqXHR.responseJSON?.message;
                    
                    if (jqXHR?.status == 422) {
                        err = jqXHR.responseJSON?.errors;
                    }

                    let message = err;
                    
                    if (typeof err === 'object') {
                        message = '<ul style="padding: 0px; margin: 0px; list-style-type: none;">';
                        Object.keys(err).forEach(key => {
                            const value = err[key]?.[0];
                            message += '<li>'+value+'</li>'
                        });
                        message += '</ul>';
                    }

                    $('#authenticate-modal .alert-container').html(`
                        <div class="alert alert-danger alert-dismissible">
                            <div class="alert-content">
                                ${message}
                            </div>
                        </div>
                    `);
                },
                complete: function () {
                    $('#authenticate-submit-toggle').prop('disabled', false);
                }
            })
        });

        $(document).on('click', '#authenticate-submit-toggle', function () {
            $('#authenticate-form').trigger('submit');
        });

        let taxesDt = $('#taxes-table').DataTable({
            scrollY: 400,
            paging: false,
            searching: false,
            ordering: false,
            info: false,
            processing: true,
            rowId: 'id',
            columns: [
                { data: 'code' },
                { data: 'name' },
                { data: 'percentage',
                  render: (data, type, row) => {
                    return parseFloat(data)?.toLocaleString('en')
                  }
                }
            ],
            createdRow: function (row, data) {
                $(row).attr('data-id', data.id);
            }
        });

        $(document).on('click', '[id^=tax-item-toggle-]', function () {
            const $this = $(this);
            const dataId = $this.data('id');
            $('#input-order_item_id').val(dataId);
            $('#taxes-modal').modal('show')
        });

        function loadTaxes(props = {}) {
            
            if (!taxesDt) return

            if (props.refresh) {
                taxPage = 1;
                loadTaxIsLast = false;
                taxListMap.clear();
                taxesDt.clear().draw();
            }

            if (loadTaxIsLoading || loadTaxIsLast) return;

            loadTaxIsLoading = true;
        
            let inputSearch = $('#input-search-tax').val();
        
            let req = {
                'order[id]': 'desc',
                is_active: 1,
                page: taxPage,
                ...props.params
            };
        
            if (inputSearch) {
                req.or = {
                    name: inputSearch,
                    code: inputSearch,
                };
            }
        
            $.ajax({
                url: BASE_URL + '/api/v1/taxes',
                type: "GET",
                dataType: "json",
                data: req,
                headers: {
                    'Authorization': TOKEN,
                    'company-id': COMPANY_ID
                },
                beforeSend: function () {
                    taxesDt.processing(true);
                },
                success: function (res) {
                    if (res?.data?.length > 0) {
                    
                        res.data.forEach(item => {
                            customerListMap.set(item.id, item);
                        });
                    
                        taxesDt.rows.add(res.data).draw(false);
                    
                        taxPage++;
                    
                    } else {
                        loadTaxIsLast = true;
                    }
                    
                    if (props?.onSuccess && typeof props?.onSuccess === 'function') {
                        props.onSuccess()
                    }
                },
                error: function () {
                    loadTaxIsLast = false;
                },
                complete: function () {
                    loadTaxIsLoading = false;
                    taxesDt.processing(false);
                }
            });
        }

        $(document).on('shown.bs.modal', '#taxes-modal', function () {
            if (!taxModalHasBeenOpen) {
                loadTaxes({
                    refresh: true
                });
            }
            
            taxModalHasBeenOpen = true
        })
        
        $(document).on('hidden.bs.modal', '#taxes-modal', function () {
            $('#input-order_item_id').val('');
        })


        $(document).on('click', 'table#taxes-table tbody tr', function () {
            const $this = $(this);
            const dataId = $this.data('id') ?? null;
            
            if ($this.attr('data-selected') == 'true') {
                $('#submit-taxes-toggle').trigger('click')
            } else {
                selectTax(dataId);
            }
        });

        $('#taxes-table_wrapper .dt-scroll-body').on('scroll', function () {
            const el = this;
        
            const scrollTop = el.scrollTop;
            const windowHeight = el.clientHeight;
            const docHeight = el.scrollHeight;
        
            if (scrollTop + windowHeight >= docHeight - 100) {
                loadTaxes();
            }
        });

        const selectTax = (id = null, props = {}) => {

            $('table#taxes-table tbody tr').removeAttr('data-selected');

            if (id) {
                const selectedRow = $('table#taxes-table tbody tr[data-id='+id+']');
                selectedRow.attr('data-selected', "true");
                selectedTaxId = id;

            } else {
                selectedTaxId = null;
            }
        }

        $(document).on('click', '#submit-taxes-toggle', (e) => {
            if (selectedTaxId) {
                selectedTax = customerListMap.get(selectedTaxId);
                const orderId = $('#input-order_item_id').val();
                putProductToOrderList(Number(orderId), {
                    tax_id: selectedTax?.id,
                    tax: selectedTax?.percentage,
                    tax_code: selectedTax?.code,
                })
                setTotalInfo();
                $('#taxes-modal').modal('hide');
            }
        });

        $(document).on('click', '.insert-payment-toggle', function () {
            const $this = $(this);
            const dataValue = $this.data('value');

            if (dataValue) {
                if (dataValue != 'exact') {
                    $('#input-total_payment').numericInput('setValue', dataValue);
                } else {
                    const calculateTotal = getCalculateTotal();
                    $('#input-total_payment').numericInput('setValue', calculateTotal?.grandTotal);
                }
            }
        });

        const loadPaymentMethod = (props) => {
            if (props?.refresh) {
                loadPaymentMethodIsLast = false;
                paymentMethodPage = 1;
                paymentMethodRowsCount = 0;
                paymentMethodListMap.clear();
            }
    
            if (loadPaymentMethodIsLoading || loadPaymentMethodIsLast) return;
    
            loadPaymentMethodIsLoading = true;
    
            let req = {
                'order[id]': 'desc',
                is_active: 1,
                is_default_pos_payment: 1,
                'id[$not]': '{{ config('general_settings.default_cash') }}',
                page: paymentMethodPage,
                ...props?.params
            }
    
            const reqParams = $.param(req);
    
            $.ajax({
                url: BASE_URL + '/api/v1/bank_accounts?'+reqParams,
                type: "GET",
                dataType: "json",
                headers: {
                    'Authorization': TOKEN,
                    'company-id': COMPANY_ID
                },
                beforeSend: function() {
                    if (props?.refresh) {
                        $('#select-payment-method-container').html('')
                    }

                    setLoadingGrid('#select-payment-method-container', props?.refresh ? true : false)
                },
                success: function(res) {
                    if (res?.data?.length > 0) {
                        let html = '';
                        res?.data.forEach((item, idx) => {
                            html += '<div style="flex: 0 0 auto; width: 12%;">'
                            html +=     '<div class="w-100 rounded d-flex align-items-center justify-content-center edc-box p-2" data-id="'+item?.id+'">'
                            html +=         '<div class="text-center w-100"><span class="fw-bold" style="font-size: 12px; font-family: \'Lexend\', sans-serif;">'+(item?.bank_name ? item?.bank_name : '' )+'</span><br><span style="font-size: 10px;">'+(item?.account_holder_name ? item?.account_holder_name : '')+'</span></div>'
                            html +=     '</div>'
                            html += '</div>'
                        });
                        
                        $('#select-payment-method-container .spinner-col').remove();
                        $('#select-payment-method-container').append(html);

                        paymentMethodPage++
                    } else {
                        if (props?.refresh) {
                            $('#select-payment-method-container .spinner-col').remove();
                            setEmptyGrid('#select-payment-method-container');
                        }
                        loadPaymentMethodIsLast = true;
                    }
                },
                error: () => {
                    loadPaymentMethodIsLoading = false;
                    loadPaymentMethodIsLast = false;
                    paymentMethodRowsCount = 0;
                },
                complete: function () {
                    loadPaymentMethodIsLoading = false;
                }
            });
        }

        $(document).on('shown.bs.tab', '#payment-method-tab a[data-bs-toggle="tab"]', function (e) {
            const activeTabId = $(e.target).attr('id');

            if (activeTabId == 'payment-method-edc-tab-toggle') {
                $('#input-total_payment').numericInput('setValue', 0);
                $('#input-total_payment_edc').focus()
                if (!EDCTabHasBeenOpen) {
                    loadPaymentMethod({
                        refresh: true
                    })
                }
                $('#input-total_payment_edc').numericInput('setValue', getCalculateTotal()?.grandTotal)
                
                EDCTabHasBeenOpen = true
            } else {
                $('#input-total_payment_edc').numericInput('setValue', 0);
                $('#input-total_payment').focus()
                $('.edc-box').removeClass('active')
            }
        });

        $(document).on('click', '.edc-box', function () {
            const $this = $(this);
            
            $('.edc-box').removeClass('active')
            
            if (!$this.hasClass('active')) {
                $this.addClass('active');
            }
        })

        $(document).on('click', '#histories-toggle', function () {
            $('#histories-modal').modal('show');
            if (!historiesModalHasBeenOpen) {
                loadHistories({
                    refresh: true
                })
            }

            historiesModalHasBeenOpen = true;
        })

        $(document).on('click', '#hold-toggle', function () {
            const mainFunc = () => {
                if (productOrderListMap.size > 0 && selectedCustomer?.id) {
    
                    var formData = new FormData();
    
                    setSubmitFormValue(formData, { is_draft: true });
    
                    withShortcutSwal({
                        title: "Dibutuhkan konfirmasi",
                        icon: 'warning',
                        html: `Apakah anda yakin ingin menyimpan pembayaran sebagai draft?`,
                        showCancelButton: true,
                        confirmButtonColor: 'var(--bs-success)',
                        cancelButtonColor: 'var(--bs-danger)',
                        confirmButtonText: '{{ __('language.yes') }}',
                        cancelButtonText: '{{ __('language.cancel') }}',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: 'post',
                                url: BASE_URL + "/api/v1/pos/payments",
                                "headers": {
                                    'Authorization': TOKEN,
                                    'company-id': COMPANY_ID
                                },
                                data: formData,
                                cache: false,
                                contentType: false,
                                processData: false,
                                dataType: 'json',
                                beforeSend: function () {
                                    showLoading();
                                },
                                success: function (res) {
                                    Swal.close();
                                    clear({
                                        setDefaultCustomer: true
                                    });
                                    generateRefNumber();
                                    resetHistoriesState();
                                    $('#input-search-product').focus()
                                },
                                error: generalAjaxErrorHandler
                            })
                        } else {
                            Swal.close();
                        }
                    });
                }
            }

            if (IS_CAN_HOLD_TRANSACTION) {
                mainFunc();
            } else {
                shouldAuthenticateSupervisor(() => {
                    mainFunc();
                })
            }
        })

        const setRewardPointList = () => {
            const data = [...rewardPointsAppliedMap.values()]?.filter((item) => item?.is_applied == true);
            $('#reward-point-applied-list').html('');
            if (data?.length > 0) {
                let html = '';
                data.forEach((item, idx) => {
                    html += '<div class="w-100 p-3 bg-success-subtle border border-success rounded-2" style="--bs-border-opacity: 0.3;">'
                    html +=     '<h6 class="mb-1">Redeem Poin</h6>'
                    if (item?.benefit_type === 'discount') {
                        html +=     '<p class="mb-0 text-body-secondary" style="font-size: 11px">'+item?.total_point?.toString()?.replace(/\B(?=(\d{3})+(?!\d))/g, '.')+' Poin digunakan</p>'
                    } else {
                        html +=     '<p class="mb-0 text-body-secondary" style="font-size: 11px">'+item?.total_point?.toString()?.replace(/\B(?=(\d{3})+(?!\d))/g, '.')+' Poin digunakan untuk '+item?.qty+' '+item?.product_detail?.unit_name+' '+item?.product_detail?.name+'</p>'
                    }
                    html += '</div>'
                });

                $('#reward-point-applied-list').html(html);
            } else {
                $('#reward-point-applied-list').html(`
                    <div class="h-100 d-flex align-self-center">
                        <p class="mb-0 text-muted fs-6 align-self-center text-center">Tidak Ada Bonus Yang Digunakan</p>
                    </div>
                `)
            }
        }

        function getPaymentSuggestions(amount) {
            const denominations = [5000, 10000, 20000];
            const suggestions = new Set();
        
            for (let denom of denominations) {
                let base = Math.ceil(amount / denom) * denom;
                suggestions.add(base + denom);
            }
        
            return Array.from(suggestions).sort((a, b) => a - b);
        }

        const getCalculateItemFormula = (items) => {

            let subtotal = 0;
            let totalDiscount = 0;
            let totalTax = 0;
            
            for (const item of items) {
                const price = Number(item.price);
                const qty = Number(item.qty);
                const taxPercent = Number(item.tax ?? 0);                
                
                const subtotalBeforeTax = price * qty;
                const itemTax = subtotalBeforeTax * taxPercent / 100;
                const itemSubtotal = subtotalBeforeTax;
            
                let itemDiscount = 0;

                const discountValue = isNaN(Number(item?.discount_value)) ? 0 : parseFloat(item.discount_value);
            
                if (item.discount_type === 'percentage') {
                    itemDiscount = itemSubtotal * (discountValue / 100);
                }
            
                if (item.discount_type === 'amount') {
                    itemDiscount = discountValue;
                }
            
                itemDiscount = Math.min(itemDiscount, itemSubtotal);
            
                subtotal += itemSubtotal;
                totalTax += itemTax;
                totalDiscount += itemDiscount;
                
            }

            const total = subtotal - totalDiscount + totalTax;

            return {
                subtotal,
                totalDiscount,
                totalTax,
                total,
            };
        }

        const getCalculateRewardPoint = () => {
            let discountRewardPoint = [...rewardPointsAppliedMap.values()]?.find((item) => item?.benefit_type === 'discount' && item?.is_applied === true);
            let items = [...productOrderListMap.values()];

            const calculateItem = getCalculateItemFormula(items);
            const calculateRewardItem = getCalculateItemFormula(items?.filter((item) => item?.is_reward_item == true));
            let totalPointExchange = 0;

            [...rewardPointsAppliedMap.values()]?.forEach((item, idx) => {
                let totalPoint = item.total_point;
                if (item?.is_applied) {
                    if (item?.benefit_type == 'discount') {
                        totalPoint = item?.input_point ? parseFloat(item?.input_point) : parseFloat(item?.total_point)
                    }

                    totalPointExchange += parseFloat(totalPoint);
                }
            });
            
            let totalRewardPointDiscount = 0;

            if (discountRewardPoint?.discount_type == 'percentage') {
                totalRewardPointDiscount = calculateItem?.total * (discountRewardPoint?.discount_percentage / 100);
                
            } else if (discountRewardPoint?.discount_type == 'amount') {
                totalRewardPointDiscount = data?.discount_amount;
            }
            
            if (totalRewardPointDiscount > discountRewardPoint?.maximum_discount_amount) {
                totalRewardPointDiscount = discountRewardPoint?.maximum_discount_amount;
            }

            const totalDiscount = calculateRewardItem?.totalDiscount + totalRewardPointDiscount;

            return {
                totalPointExchange,
                totalDiscountPointExchange: totalRewardPointDiscount,
                totalDiscountItem: calculateRewardItem?.totalDiscount,
                totalDiscount
            }
        }

        const getCalculateTotal = (params = {}) => {

            let items = [...productOrderListMap.values()];

            const calculateItemFormula = getCalculateItemFormula(items);

            const calculateRewardPoint = getCalculateRewardPoint()

            calculateItemFormula.rewardPoint = totalDiscountPoint;
            // let rewardPointDiscount = 0;

            calculateItemFormula.grandTotal = calculateItemFormula.total - totalDiscountPoint;
            calculateItemFormula.grandTotalDiscount = calculateItemFormula.totalDiscount + totalDiscountPoint;

            let totalPayment = 0;

            if ($('#payment-method-tab-content .tab-pane.active').attr('id') === 'payment-method-cash-tab' && $('#input-total_payment').val()) {
                totalPayment = $('#input-total_payment').numericInput('getRaw');
            } else if ($('#payment-method-tab-content .tab-pane.active').attr('id') === 'payment-method-edc-tab' && $('#input-total_payment_edc').val()) {
                totalPayment = $('#input-total_payment_edc').numericInput('getRaw');
            }

            let change = 0;

            if (totalPayment > calculateItemFormula.grandTotal) {
                change = totalPayment - calculateItemFormula.grandTotal
            }
            
            calculateItemFormula.change = change;
            calculateItemFormula.totalPayment = totalPayment;

            return calculateItemFormula;

        };

        const setTotalInfo = () => {
            const calculateTotal = getCalculateTotal();
            
            $('#subtotal-info').html(calculateTotal?.subtotal?.toLocaleString('en'))
            $('#total-discount-info').html('- '+calculateTotal?.grandTotalDiscount?.toLocaleString('en'))
            $('#total-tax-info').html(calculateTotal?.totalTax?.toLocaleString('en'))
            $('#total-info').html(calculateTotal?.grandTotal?.toLocaleString('en'))
            
            $('#subtotal-payment-info').html(calculateTotal?.subtotal?.toLocaleString('en'))
            $('#total-tax-payment-info').html(calculateTotal?.totalTax?.toLocaleString('en'))
            $('#total-discount-payment-info').html(calculateTotal?.grandTotalDiscount?.toLocaleString('en'))
            $('#change-payment-info').html(calculateTotal?.change?.toLocaleString('en'))
            $('#total-payment-info').html(calculateTotal?.grandTotal?.toLocaleString('en'));
            
            $('#total-discount-reward-point-payment-info').html(calculateTotal?.rewardPoint?.totalDiscount?.toLocaleString('en'));
            
            if (productOrderListMap?.size > 0 && selectedCustomer?.id) {
                $('#payment-step-toggle').prop('disabled', false)
            } else {
                $('#payment-step-toggle').prop('disabled', true)
            }

            if (productOrderListMap.size > 0 && selectedCustomer?.id) {
                $('#histories-toggle .text-button').html('Hold');
                $('#histories-toggle').attr('id', 'hold-toggle')
            } else {
                $('#hold-toggle .text-button').html('Histories');
                $('#hold-toggle').attr('id', 'histories-toggle');
            }

            if (productOrderListMap.size > 0 && selectedCustomer?.id) {
                $('#void-toggle').prop('disabled', false);
            } else {
                $('#void-toggle').prop('disabled', true);
            }

            if (calculateTotal.totalPayment >= calculateTotal.grandTotal) {
                $('#submit-payment-toggle').prop('disabled', false);
            } else {
                $('#submit-payment-toggle').prop('disabled', true);
            }
        }

        $(document).on('click', '#payment-step-toggle', function () {
            setTotalInfo();
            setRewardPointList()
            showPaymentStep();
        });

        $(document).on('shown.bs.modal', '#payment-modal', function () {
            if (selectedCustomer?.id) {
                const calculateTotal = getCalculateTotal();
                let params = {
                    contact_group_id: selectedCustomer?.contact_group_id,
                    total_purchase: calculateTotal?.total,
                    chart_items: []
                }

                let chartItems = [...productOrderListMap.values()];

                chartItems.forEach((item, idx) => {
                    params.chart_items.push({
                        product_id: item.detail.id,
                        qty: item.qty,
                        unit_id: item?.unit_id ? item?.unit_id : item?.detail?.unit_id,
                        price: item?.price
                    })
                });

                if ($('#payment-method-tab-content .tab-pane.active').attr('id') === 'payment-method-cash-tab') {
                    $('#input-total_payment').focus()
                } else if ($('#payment-method-tab-content .tab-pane.active').attr('id') === 'payment-method-edc-tab') {
                    $('#input-total_payment_edc').focus()
                    $('#input-total_payment_edc').numericInput('setValue', getCalculateTotal()?.grandTotal)
                }

                $('#payment-rounded-container').html('');
                let paymentSuggestions = getPaymentSuggestions(calculateTotal?.total);

                let insertPaymentToggleHTML = '';
                let insertPaymentToggleCount = 0;
                paymentSuggestions.forEach((item, idx) => {
                    if (item != calculateTotal?.total) {
                        insertPaymentToggleHTML += '<button type="button" class="btn btn-light insert-payment-toggle" data-value="'+item+'">'+item?.toLocaleString('en')+'</button>';
                    }
                    insertPaymentToggleCount++
                    if (paymentSuggestions.length == insertPaymentToggleCount) {
                        insertPaymentToggleHTML += '<button type="button" class="btn btn-light insert-payment-toggle" data-value="exact">Uang Pas [F2]</button>';
                    }
                });

                $('#payment-rounded-container').html(insertPaymentToggleHTML);

                if (selectedCustomer?.contact_group_id) {
                    $.ajax({
                        url: BASE_URL + '/api/v1/contact_groups/'+selectedCustomer?.contact_group_id+'/generate_reward_points',
                        type: "POST",
                        dataType: "json",
                        data: params,
                        headers: {
                            'Authorization': TOKEN,
                            'company-id': COMPANY_ID,
                            'Accept': 'application/json'
                        },
                        beforeSend: function () {
                            $('#bonus-point-label').html(0)
                        },
                        success: function (res) {
                            $('#bonus-point-label').html('+'+res)
                            
                        },
                        error: function () {
                        },
                        complete: function () {
                        }
                    });
                }
            }
        })

        $(document).on('input', '#input-total_payment, #input-total_payment_edc', function () {
            setTotalInfo()
        });

        const setSubmitFormValue = (formData, props = {}) => {
            const calculateTotal = getCalculateTotal();
    
            if (props?.is_draft) {
                formData.set('total', calculateTotal?.total);
            } else {
                formData.set('total', calculateTotal?.grandTotal);
            }
            formData.set('subtotal', calculateTotal?.subtotal);
            formData.set('tax_amount', calculateTotal?.totalTax);
            formData.set('discount_amount', calculateTotal?.grandTotalDiscount);
            formData.set('total_payment', calculateTotal?.totalPayment);
            formData.set('total_change', calculateTotal?.change);
            formData.set('discount_type', 'amount');
            formData.set('customer_id', selectedCustomer?.id);
            formData.set('ref_number', $('#input-number').val());
            const id = $('#input-id').val();
            
            if (id) {
                formData.set('id', id);
            }

            let chartItems = [];

            for (const [key, item] of productOrderListMap) {
                const matchedMultiPrice = getMatchedMultiPrice(key);

                chartItems.push({
                    product_id: item?.product_id ? item?.product_id : item?.detail?.id,
                    product_code: item?.product_code ? item?.product_code : item?.detail?.code,
                    product_name: item?.product_name ? item?.product_name : item?.detail?.name,
                    price: item?.price,
                    qty: item?.qty,
                    unit_id: item?.unit_id ? item?.unit_id : item?.detail?.unit_id,
                    unit_name: item?.unit_name ? item?.unit_name : item?.detail?.unit_name,
                    discount_value: item?.discount_value ? item?.discount_value : 0,
                    discount_type: item?.discount_type ? item?.discount_type : '',
                    note: item?.note ? item?.note : '',
                    base_unit_id: item?.base_unit_id ? item?.base_unit_id : (item?.detail?.unit_id ? item?.detail?.unit_id : ''),
                    base_qty: item?.base_qty ? item.base_qty : (item?.qty * (item?.convertion_value ? item?.convertion_value : 1)),
                    is_product_unit_convert: item.is_product_unit_convert,
                    tax_id: item?.tax_id ? item?.tax_id : '',
                    tax_percentage: item?.tax ? item?.tax : 0,
                    base_unit_price: item?.base_unit_price ? item?.base_unit_price : (matchedMultiPrice ? parseFloat(matchedMultiPrice.unit_price) : parseFloat(item?.detail?.sale_price)),
                    sales_invoice_detail_id: item?.sales_invoice_detail_id ? item?.sales_invoice_detail_id : '',
                    product_catalog_id: item?.detail?.product_catalog_id ? item?.detail.product_catalog_id : '',
                })
            }

            chartItems?.forEach((item, idx) => {

                formData.set('sales_invoice_details['+idx+'][product_id]', item?.product_id);
                formData.set('sales_invoice_details['+idx+'][product_code]', item?.product_code);
                formData.set('sales_invoice_details['+idx+'][product_name]', item?.product_name);
                formData.set('sales_invoice_details['+idx+'][qty]', item?.qty);
                formData.set('sales_invoice_details['+idx+'][unit_id]', item?.unit_id);
                formData.set('sales_invoice_details['+idx+'][unit_name]', item?.unit_name);
                formData.set('sales_invoice_details['+idx+'][unit_price]', item?.price);
                formData.set('sales_invoice_details['+idx+'][tax_id]', item?.tax_id ? item?.tax_id : '');
                formData.set('sales_invoice_details['+idx+'][tax_percentage]', item?.tax_percentage ? item?.tax_percentage : 0);
                formData.set('sales_invoice_details['+idx+'][tax_amount]', item?.tax_amount ? item?.tax_amount : 0);
                formData.set('sales_invoice_details['+idx+'][base_unit_id]', item?.base_unit_id ? item?.base_unit_id : '');
                formData.set('sales_invoice_details['+idx+'][base_unit_price]', item?.base_unit_price ? item?.base_unit_price : 0);
                formData.set('sales_invoice_details['+idx+'][base_qty]', item?.base_qty ? item?.base_qty : '');
                formData.set('sales_invoice_details['+idx+'][is_product_unit_convert]', item?.is_product_unit_convert ? item?.is_product_unit_convert : 0);
                formData.set('sales_invoice_details['+idx+'][product_catalog_id]', item?.product_catalog_id ? item?.product_catalog_id : 0);
                
                if (item?.discount_value) {
                    formData.set('sales_invoice_details['+idx+'][discount_amount]', item?.discount_value);
                    formData.set('sales_invoice_details['+idx+'][discount_type]', item?.discount_type);
                } else {
                    formData.set('sales_invoice_details['+idx+'][discount_amount]', 0);
                    formData.set('sales_invoice_details['+idx+'][discount_type]', '');
                }
                
                if (id && item?.sales_invoice_detail_id) {
                    formData.set('sales_invoice_details['+idx+'][id]', item.sales_invoice_detail_id);
                }

                formData.set('sales_invoice_details['+idx+'][discount_percentage]', 0);
                formData.set('sales_invoice_details['+idx+'][note]', item?.note ? item?.note : '');
            })

            if (props?.is_draft) {
                formData.set('is_draft', 1);
            }
        }

        $(document).on('submit', '#main-form', function (e) {
            e.preventDefault();
            let formData = new FormData(this);
            
            setSubmitFormValue(formData);
            if ($('.edc-box.active')?.length > 0) {
                formData.set('bank_account_id', $('.edc-box.active').data('id'));
            }

            let rewardPointIds = [];

            [...rewardPointsAppliedMap.values()].forEach((item, idx) => {
                formData.set('reward_point_applied_ids['+idx+']', item?.id);
            });

            formData.set('total_point_applied', totalPointApplied);
            
            $.ajax({
                type: 'post',
                url: BASE_URL + "/api/v1/pos/payments",
                "headers": {
                    'Authorization': TOKEN,
                    'company-id': COMPANY_ID
                },
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                beforeSend: function () {
                    showLoading();
                },
                success: function (res) {
                    Swal.close();
                    withShortcutSwal({
                        title: "Transaksi Telah Dibayar!",
                        icon: 'success',
                        html: `Apakah anda ingin mencetak transaksi ini?`,
                        showCancelButton: true,
                        confirmButtonColor: 'var(--bs-success)',
                        cancelButtonColor: 'var(--bs-danger)',
                        confirmButtonText: '{{ __('language.yes') }}',
                        cancelButtonText: '{{ __('language.cancel') }}',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            printReceipt(res?.data?.ref_number);
                        }
                        if ('{{ config('services.is_onpremise') }}') {
                            salesInvoiceSync()
                        }
                        clear({
                            setDefaultCustomer: true
                        });
                        historiesModalHasBeenOpen = false
                        generateRefNumber();
                        resetHistoriesState();
                        $('#input-search-product').focus();
                    });
                },
                error: generalAjaxErrorHandler
            })
        });
    
        $(document).on('click', '#submit-payment-toggle', function (e) {
            withShortcutSwal({
                title: "Dibutuhkan konfirmasi",
                icon: 'warning',
                html: `Apakah anda yakin ingin melanjutkan pembayaran?`,
                showCancelButton: true,
                confirmButtonColor: 'var(--bs-success)',
                cancelButtonColor: 'var(--bs-danger)',
                confirmButtonText: '{{ __('language.yes') }}',
                cancelButtonText: '{{ __('language.cancel') }}',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#main-form').trigger('submit');
                } else {
                    Swal.close();
                }
            });
        });


        $(document).on('hidden.bs.modal', '.modal', function (e) {
            // console.log($(e));
            if ($(e.target).attr('id') != 'access-denied-modal') {
                $('#input-search-product').focus();
            }
        })

        $('#unit-table').on('processing.dt', function (e, settings, processing) {
            loadUnitIsLoading = processing;
        });

        $(document).on('shown.bs.modal', '#histories-modal', function () {
            $('#input-search-histories').focus()
        });

        $('.histories-container').on('scroll', function () {
            const el = this;
        
            const scrollTop = el.scrollTop;
            const windowHeight = el.clientHeight;
            const docHeight = el.scrollHeight;
        
            if (scrollTop + windowHeight >= docHeight - 100) {
                loadHistories();
            }
        });

        const setEmptyHistoriesList = (name) => {
            $('.histories-container#histories-'+name+'-tab-content').html(`
                <div class="w-100 h-100 d-flex align-items-center justify-content-center empty-display">
                    <p class="m-0 text-body-secondary fs-5">Tidak Ada Data</p>
                </div>
            `)
        }

        const getActiveHistoriesTab = () => {
            return $('#histories-tab .nav-link.active') ? $('#histories-tab .nav-link.active').data('name') : null;
        }

        $(document).on('shown.bs.tab', '#histories-tab a',function (e) {
            const activeTab = getActiveHistoriesTab();
            if (historiesPages[activeTab] == 1) {
                loadHistories();
            }

            $('#input-search-histories').focus()
        });

        $(document).on('input', '#input-search-histories', function () {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => {
                loadHistories({ refresh: true });
            }, 500);
        });

        function loadHistories(props = {}) {
            const activeTab = getActiveHistoriesTab();

            if (!activeTab) return

            if (props?.refresh) {
                historiesPages[activeTab] = 1;
                loadHistoriesIsLasts[activeTab] = false;
            }

            if (loadHistoriesIsLoadings[activeTab] || loadHistoriesIsLasts[activeTab]) return;
            
            loadHistoriesIsLoadings[activeTab] = true;

            const searchHistoriesValue = $('#input-search-histories').val()

            let req = {
                'order[date]': 'desc',
                page: historiesPages[activeTab],
                is_active: 1,
                is_from_pos: 1,
                created_by: '{{ config('user_companies.id') }}',
                ...props?.params
            }

            if (activeTab === 'pending') {
                req.number = 'null';
                req.status = 'paid';
            } else if (activeTab === 'done') {
                req['number[$not]'] = 'null';
                req.status = 'paid';
            } else if (activeTab === 'hold') {
                req.status = 'draft';
            }
            
            if (searchHistoriesValue) {
                req.or = {
                    customer_name: searchHistoriesValue,
                    ref_number: searchHistoriesValue
                };
            }

            const reqParams = $.param(req);

            $.ajax({
                url: BASE_URL + '/api/v1/sales_invoices?'+reqParams,
                type: "GET",
                dataType: "json",
                headers: {
                    'Authorization': TOKEN,
                    'company-id': COMPANY_ID
                },
                beforeSend: function() {
                    const spinner = `<div class="w-100 d-flex justify-content-center py-4 spinner-container">
                                <div class="spinner-border" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>`;

                    if (props?.refresh) {
                        $(`#histories-${activeTab}-tab-content.histories-container`).html(spinner);
                    } else {
                        $(`#histories-${activeTab}-tab-content.histories-container`).append(spinner);
                    }
                    
                },
                success: function(res) {
                    $(`#histories-${activeTab}-tab-content.histories-container .spinner-container`).remove();
                    if (historiesPages[activeTab] == res?.nav?.totalPage || res?.nav?.totalPage == 0) {
                        loadHistoriesIsLasts[activeTab] = true;
                    }

                    let html = '';

                    if (res?.data?.length > 0) {
                        
                        let labelDate = null;

                        $(res?.data).each((i, item) => {
                            if (labelDate != item.date) {
                                const dateMoment = moment(item.date);
                                html += '<div class="bg-body fw-bold text-body-secondary" style="width: 100%; padding: .5rem 1.5rem; font-family: \'Lexend\', sans-serif;">'
                                if (dateMoment.isSame(moment(), 'day')) {
                                    html += 'Today';
                                } else if (dateMoment.isSame(moment().subtract(1, 'day'), 'day')) {
                                    html += 'Yesterday';
                                } else {
                                    html += moment(item.date).format('DD MMM YYYY');
                                }
                                html += '</div>'
                            }

                            function toSentenceCase(str) {
                                if (!str) return "";
                                return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
                            }

                            let badgeClass = {
                                pending: 'badge-danger',
                                done: 'badge-success',
                                hold: 'badge-warning'
                            }

                            let dropdownButtonsHtml = '';

                            dropdownButtonsHtml += '<li><a href="#" id="histories-edit-transaction-toggle-'+i+'" data-ref_number="'+item?.ref_number+'" data-id="'+item.id+'" id="call-back-sales-invoice-'+item.id+'" class="dropdown-item">Ubah Transaksi</a></li>' 
                            dropdownButtonsHtml += '<li><a href="#" id="histories-print-toggle-'+i+'" data-ref_number="'+item?.ref_number+'" class="dropdown-item">Print</a></li>';

                            if (activeTab == 'hold') {
                                dropdownButtonsHtml += '<li><button class="dropdown-item" id="call-back-sales-invoice-'+item.id+'" data-id="'+item.id+'" type="button">Call Back</button></li>';
                            }

                            html += '<div class="d-flex border-bottom">'
                            html +=     '<div class="d-flex flex-fill justify-content-between h-100" style="padding: 1rem 0 1rem 1.25rem">'
                            html +=         '<div>'
                            html +=             '<h6 class="mb-1 text-secondary">'+item?.customer_name+'</h6>'
                            html +=             '<p class="mb-2 text-body-secondary small">'+item?.ref_number+'</p>'
                            html +=             '<p class="mb-0 text-body-secondary" style="font-size: 0.7rem;">'+moment(item?.date).format('DD MMM YYYY')+'</p>'
                            html +=         '</div>'
                            html +=         '<div class="text-end">'
                            html +=             '<h5 style="font-size: .95rem;">'+parseFloat(item?.total).toLocaleString('en')+'</h5>'
                            html +=             '<span class="badge '+(badgeClass[activeTab] ? badgeClass[activeTab] : '' )+'">'+toSentenceCase(activeTab)+'</span>'
                            html +=         '</div>'
                            html +=     '</div>'
                            html +=     '<div class="d-flex align-items-center" style="padding: 0 .5rem;">'
                                if ($(dropdownButtonsHtml).length > 0) {
                                    html +=         '<div class="dropdown">'
                                    html +=             '<button type="button" class="btn btn-text-light btn-icon align-self-center" data-bs-toggle="dropdown" aria-expanded="false"><span class="mdi mdi-dots-horizontal"></span></button>'
                                    html +=             '<ul class="dropdown-menu dropdown-menu-end">'
                                    html += dropdownButtonsHtml;
                                    html +=             '</ul>'
                                    html +=         '</div>'
                                }
                            html +=     '</div>'
                            html += '</div>'

                            labelDate = item.date;
                        });
                        
                        $(`#histories-${activeTab}-tab-content.histories-container`).append(html);
                        
                        historiesPages[activeTab]++;
                    }

                    if (res?.nav?.totalData == 0 && historiesPages[activeTab] == 1) {
                        setEmptyHistoriesList(activeTab)
                    }
                    
                    loadHistoriesIsLoadings[activeTab] = false;
                    

                    if (props?.callback && typeof props?.callback === 'function') {
                        props.callback(res);
                    }
                },
            });
        }

        $(document).on('click', '[id^=histories-edit-transaction-toggle-]', function (e) {
            e.preventDefault();
            const $this = $(this);
            const dataId = $this.attr('data-id');

            callbackTransaction(dataId, {
                popUpMessage: `Apakah anda yakin ingin mengubah isi transaksi?`,
                permissionAllowed: IS_CAN_EDIT_TRANSACTION
            });
        });

        $(document).on('click', '[id^=histories-print-toggle-]', function (e) {
            e.preventDefault()
            const $this = $(this);
            const dataRefNumber = $this.attr('data-ref_number');
            if (IS_CAN_REPRINT_TRANSACTION) {
                printReceipt(dataRefNumber)
            } else {
                $('#histories-modal').modal('hide');
                shouldAuthenticateSupervisor(() => {
                    printReceipt(dataRefNumber)
                })
            }
        });

        const printReceipt = (refNumber) => {
            if ('{{ config('services.is_onpremise') }}') {
                $.ajax({
                    type: 'post',
                    url: BASE_URL+'/api/v1/pos/print-receipts/'+refNumber,
                    "headers": {
                        'Authorization': TOKEN,
                        'company-id': COMPANY_ID
                    },
                    "data": {
                        "paper_size": '{{ config('local_user_settings.pos_printer_paper_size') }}'
                    },
                    error: generalAjaxErrorHandler,
                });
            } else {
                window.open(BASE_URL+'/pos/print-receipts/'+  refNumber, '_blank');
            }
        }

        $(document).on('click', '[id^=call-back-sales-invoice-]', function () {
            const $this = $(this);
            const dataId = $this.attr('data-id');

            callbackTransaction(dataId, {
                popUpMessage: `Apakah anda yakin ingin memanggil kembali transaksi?`,
                permissionAllowed: IS_CAN_HOLD_TRANSACTION
            });
        });

        const callbackTransaction = (dataId, props = {}) => {
            let req = {
                with_product_detail: true,
                with_customer_detail: true
            }

            const reqParams = $.param(req);

            const mainFunc = () => {
                $.ajax({
                    url: BASE_URL + '/api/v1/sales_invoices/'+dataId+'?'+reqParams,
                    type: "GET",
                    dataType: "json",
                    headers: {
                        'Authorization': TOKEN,
                        'company-id': COMPANY_ID
                    },
                    beforeSend: function() {
                        showLoading();
                    },
                    success: function(res) {
                        Swal.close();
                         $('#input-id').val(res.id);
                        selectedCustomer = res.customer_detail;
                        getSelectedCustomerInfo();
                        generateRefNumber(res.ref_number);

                        for (const [key, item] of productOrderListMap) {
                            removeProductFromOrderList(key)
                        }

                        if (res?.sales_invoice_details) {
                            res.sales_invoice_details.forEach((item, idx) => {
                                if (item?.product_detail) {
                                    putProductToOrderList(generateOrderId(), {
                                        sales_invoice_detail_id: item?.id,
                                        qty: parseFloat(item?.qty),
                                        price: parseFloat(item?.unit_price),
                                        unit_id: item?.unit_id,
                                        unit_name: item?.unit_name,
                                        detail: item?.product_detail,
                                        note: item?.note,
                                        is_product_unit_convert: item?.is_product_unit_convert,
                                        tax: parseFloat(item?.tax_percentage),
                                        tax_id: parseFloat(item?.tax_id),
                                        discount_type: item?.discount_type,
                                        discount_value: parseFloat(item?.discount_value),
                                        is_other_item: true,
                                        unit_convertion: item.unit_detail
                                    });

                                }
                            });
                        }

                        $('#histories-modal').modal('hide');
                    },
                });
            }
            
            if (props?.permissionAllowed) {
                withShortcutSwal({
                    title: "Dibutuhkan konfirmasi",
                    icon: 'warning',
                    html: props?.popUpMessage ? props?.popUpMessage : '',
                }).then((result) => {
                    if (result.isConfirmed) {
                        mainFunc()
                    } else {
                        Swal.close();
                    }
                });
            } else {
                $('#histories-modal').modal('hide');
                shouldAuthenticateSupervisor(() => {
                    mainFunc()
                })
            }
            
        }

        $(document).on('click', '#sync-toggle', function () {
            $('#sync-modal').modal('show');
        });

        $(document).on('shown.bs.modal', '#stock-modal', function () {
            $('#stock-product-code-info').html('N/A');
            $('#stock-product-name-info').html('N/A');
            $('#stock-product-price-info').html('0');
            let thumbnail = `<div class="avatar avatar-label-primary" style="width: 6rem; height: 6rem; font-size: 35px;">
                    <span class="mdi mdi-file-image-outline"></span>
                </div>`

            $('#stock-product-thumbnail').html(thumbnail);

            if (selectedProductId) {
                const data = productListMap.get(selectedProductId);
                $('#stock-product-code-info').html(data.code);
                $('#stock-product-name-info').html(data.name);
                $('#stock-product-price-info').html(parseFloat(data.sale_price)?.toLocaleString('en'));
    
                if (data?.media?.length > 0) {
                    const firstImage = data?.media?.[0];
                    if (firstImage) {
                        const imageURL = BASE_URL + firstImage.filepath+'/'+firstImage?.filename;
                        thumbnail = `<img src="${imageURL}" alt="" class="rounded-2" style="object-fit: cover; object-position: center; width: 6rem; height: 6rem;">`
                    }

                    $('#stock-product-thumbnail').html(thumbnail)
                }

    
                if (!productStockDt) {
                    productStockDt = $("#product-stock-table").addClass('nowrap').DataTable({
                        dom:
                        "<'row mb-3'<'col-md-6'B><'col-md-6'f>>" +
                        "<'row'<'col-12'tr>>" +
                        "<'row mt-3'<'col-md-5'i><'col-md-4'p><'col-md-3 text-end'l>>",
                        "destroy": true,
                        "pageLength": 10,
                        "processing": true,
                        "serverSide": true,
                        "responsive": true,
                        "scrollX": false,
                        "ajax": {
                            "url": BASE_URL + "/api/v1/sync/product_stock",
                            "headers": { 
                                'Authorization': TOKEN,
                                'company-id': COMPANY_ID
                            },
                            "dataType": "json",
                            "type": "get",
                            "data": function (d) {
                                d.product_id = selectedProductId;
                            },
                        },
                        "columns": [
                            {
                                data: 'warehouse_code',
                                name: 'warehouse_code',
                            },
                            {
                                data: 'warehouse_name',
                                name: 'warehouse_name',
                            },
                            {
                                data: 'warehouse_address',
                                name: 'warehouse_address',
                            },
                            {
                                data: 'stock',
                                name: 'stock',
                            },
                        ],
                        "order": [0, 'desc'],
                    });
                } else {
                    $("#product-stock-table").DataTable().ajax.reload(null, false); 
                }
            }
        })
    
        $(document).on('click', '#stock-toggle', function () {
            if (IS_CAN_STOCK_WAREHOUSE) {
                $('#stock-modal').modal('show')
            } else {
                shouldAuthenticateSupervisor(() => {
                    $('#stock-modal').modal('show')
                })
            }
        });

        $(document).on('hidden.bs.modal', '#sync-modal', function () {
            $('.sync-check').prop('checked', false);
            $('#sync-all-php a').prop('checked', false);
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
                        url: BASE_URL+'/api/v1/sync/products',
                        method: 'GET',
                        contentType: 'application/json',
                        headers: { 
                            'Authorization': TOKEN,
                            'company-id': COMPANY_ID
                        },
                    });
        }

        const productCategoriesSync = () => {
            return  $.ajax({
                        url: BASE_URL+'/api/v1/sync/product_categories',
                        method: 'GET',
                        contentType: 'application/json',
                        headers: { 
                            'Authorization': TOKEN,
                            'company-id': COMPANY_ID
                        },
                    });
        }

        const productMultiPricesSync = () => {
            return  $.ajax({
                        url: BASE_URL+'/api/v1/sync/product_multi_prices',
                        method: 'GET',
                        contentType: 'application/json',
                        headers: {
                            'Authorization': TOKEN,
                            'company-id': COMPANY_ID
                        },
                    });
        }

        const unitsSync = () => {
            return  $.ajax({
                        url: BASE_URL+'/api/v1/sync/units',
                        method: 'GET',
                        contentType: 'application/json',
                        headers: { 
                            'Authorization': TOKEN,
                            'company-id': COMPANY_ID
                        },
                    });
        }

        const productUnitConversionsSync = () => {
            return  $.ajax({
                        url: BASE_URL+'/api/v1/sync/product_unit_conversions',
                        method: 'GET',
                        contentType: 'application/json',
                        headers: { 
                            'Authorization': TOKEN,
                            'company-id': COMPANY_ID
                        },
                    });
        }

        const productVariantsSync = () => {
            return  $.ajax({
                        url: BASE_URL+'/api/v1/sync/product_variants',
                        method: 'GET',
                        contentType: 'application/json',
                        headers: { 
                            'Authorization': TOKEN,
                            'company-id': COMPANY_ID
                        },
                    })
        }

        const variantsSync = () => {
            return  $.ajax({
                        url: BASE_URL+'/api/v1/sync/variants',
                        method: 'GET',
                        contentType: 'application/json',
                        headers: { 
                            'Authorization': TOKEN,
                            'company-id': COMPANY_ID
                        },
                    });
        }

        const variantOptionsSync = () => {
            return  $.ajax({
                        url: BASE_URL+'/api/v1/sync/variant_options',
                        method: 'GET',
                        contentType: 'application/json',
                        headers: { 
                            'Authorization': TOKEN,
                            'company-id': COMPANY_ID
                        },
                    });
        }

        const taxesSync = () => {
            return  $.ajax({
                        url: BASE_URL+'/api/v1/sync/taxes',
                        method: 'GET',
                        contentType: 'application/json',
                        headers: { 
                            'Authorization': TOKEN,
                            'company-id': COMPANY_ID
                        },
                    });
        } 

        const baseUnitConversionsSync = () => {
            return  $.ajax({
                        url: BASE_URL+'/api/v1/sync/base_unit_conversions',
                        method: 'GET',
                        contentType: 'application/json',
                        headers: { 
                            'Authorization': TOKEN,
                            'company-id': COMPANY_ID
                        },
                    });
        }

        const mediaSync = () => {
            return  $.ajax({
                        url: BASE_URL+'/api/v1/sync/media',
                        method: 'GET',
                        contentType: 'application/json',
                        headers: { 
                            'Authorization': TOKEN,
                            'company-id': COMPANY_ID
                        },
                    });
        }

        const productStockSync = () => {
            return $.ajax({
                url: BASE_URL+'/api/v1/sync/product_stock',
                method: 'GET',
                contentType: 'application/json',
                headers: {
                    'Authorization': TOKEN,
                    'company-id': COMPANY_ID
                },
            });
        }

        const customerSync = () => {
            return  $.ajax({
                        url: BASE_URL+'/api/v1/sync/contacts',
                        method: 'GET',
                        contentType: 'application/json',
                        headers: { 
                            'Authorization': TOKEN,
                            'company-id': COMPANY_ID
                        },
                    });
        }

        const branchesSync = () => {
            return  $.ajax({
                        url: BASE_URL+'/api/v1/sync/branches',
                        method: 'GET',
                        contentType: 'application/json',
                        headers: { 
                            'Authorization': TOKEN,
                            'company-id': COMPANY_ID
                        },
                    });
        }

        const warehousesSync = () => {
            return  $.ajax({
                        url: BASE_URL+'/api/v1/sync/warehouses',
                        method: 'GET',
                        contentType: 'application/json',
                        headers: { 
                            'Authorization': TOKEN,
                            'company-id': COMPANY_ID
                        },
                    });
        }

        const rewardPointsSync = () => {
            return  $.ajax({
                        url: BASE_URL+'/api/v1/sync/reward_points',
                        method: 'GET',
                        contentType: 'application/json',
                        headers: { 
                            'Authorization': TOKEN,
                            'company-id': COMPANY_ID
                        },
                    });
        }

        const contactPointRulesSync = () => {
            return  $.ajax({
                        url: BASE_URL+'/api/v1/sync/contact_point_rules',
                        method: 'GET',
                        contentType: 'application/json',
                        headers: { 
                            'Authorization': TOKEN,
                            'company-id': COMPANY_ID
                        },
                    });
        }

        const generalSettingsSync = () => {
            return  $.ajax({
                        url: BASE_URL+'/api/v1/sync/general_settings',
                        method: 'GET',
                        contentType: 'application/json',
                        headers: { 
                            'Authorization': TOKEN,
                            'company-id': COMPANY_ID
                        },
                    });
        }
        
        const paymentMethodSync = () => {
            return  $.ajax({
                        url: BASE_URL+'/api/v1/sync/bank_accounts',
                        method: 'GET',
                        contentType: 'application/json',
                        headers: { 
                            'Authorization': TOKEN,
                            'company-id': COMPANY_ID
                        },
                    });
        }

        const salesInvoicesSync = () => {
            return  $.ajax({
                        url: BASE_URL+'/api/v1/sync/sales_invoices',
                        method: 'POST',
                        contentType: 'application/json',
                        headers: { 
                            'Authorization': TOKEN,
                            'company-id': COMPANY_ID
                        },
                    });
        }

        const accountingMastersSync = () => {
            return  $.ajax({
                        url: BASE_URL+'/api/v1/sync/accounting_master',
                        method: 'GET',
                        contentType: 'application/json',
                        headers: { 
                            'Authorization': TOKEN,
                            'company-id': COMPANY_ID
                        },
                    });
        }

        const contactGroupsSync = () => {
            return  $.ajax({
                        url: BASE_URL+'/api/v1/sync/contact_groups',
                        method: 'GET',
                        contentType: 'application/json',
                        headers: {
                            'Authorization': TOKEN,
                            'company-id': COMPANY_ID
                        },
                    });
        }

        const currenciesSync = () => {
            return  $.ajax({
                        url: BASE_URL+'/api/v1/sync/currencies',
                        method: 'GET',
                        contentType: 'application/json',
                        headers: { 
                            'company-id': COMPANY_ID,
                            'Authorization': TOKEN
                        },
                    });
        }

        const defaultAccountsSync = () => {
            return  $.ajax({
                        url: BASE_URL+'/api/v1/sync/default_accounts',
                        method: 'GET',
                        contentType: 'application/json',
                        headers: { 
                            'Authorization': TOKEN,
                            'company-id': COMPANY_ID
                        },
                    });
        }

        const usersSync = () => {
            return  $.ajax({
                        url: BASE_URL+'/api/v1/sync/users',
                        method: 'GET',
                        contentType: 'application/json',
                        headers: { 'Authorization': TOKEN, 'company-id': COMPANY_ID, },
                    });
        }

        const permissionsSync = () => {
            return  $.ajax({
                        url: BASE_URL+'/api/v1/sync/permissions',
                        method: 'GET',
                        contentType: 'application/json',
                        headers: { 'Authorization': TOKEN, 'company-id': COMPANY_ID, },
                    });
        }

        const rolesSync = () => {
            return  $.ajax({
                        url: BASE_URL+'/api/v1/sync/roles',
                        method: 'GET',
                        contentType: 'application/json',
                        headers: { 'Authorization': TOKEN, 'company-id': COMPANY_ID, },
                    });
        }

        const productCatalogsSync = () => {
            return  $.ajax({
                        url: BASE_URL+'/api/v1/sync/product_catalogs',
                        method: 'GET',
                        contentType: 'application/json',
                        headers: { 'Authorization': TOKEN, 'company-id': COMPANY_ID, },
                    });
        }

        const order = [
            'settings',
            'warehouse',
            'branch',
            'currencies',
            'customer',
            'accounting_master',
            'payment_method',
            'product',
            'reward_point_and_point_rule',
            'transaction',
            'users',
        ];

        const refreshAfterSyncResources = ['settings', 'users'];

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
                    taxesSync,
                    unitsSync,
                    variantsSync,
                    variantOptionsSync,
                    baseUnitConversionsSync,
                    productCategoriesSync,
                    productsSync,
                    productMultiPricesSync,
                    productUnitConversionsSync,
                    productVariantsSync,
                    productCatalogsSync,
                    mediaSync
                ],
                transaction: [
                    salesInvoicesSync
                ],
                stock_product: [
                    productStockSync
                ],
                customer: [
                    contactGroupsSync,
                    customerSync
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
                    generalSettingsSync,
                    defaultAccountsSync
                ],
                payment_method: [
                    paymentMethodSync
                ],
                currencies: [
                    currenciesSync
                ],
                users: [
                    usersSync,
                    rolesSync,
                    permissionsSync,
                ]
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

            options.sort((a, b) => order.indexOf(a) - order.indexOf(b));

            for (const item of options) {
                const fetchGroup = data[item];
                    
                if (fetchGroup) {
                  for (const fn of fetchGroup) {
                    try {
                        const result = await fn();
                    } catch (err) {
                        
                        if (err.status == 401) {
                            generalAjaxErrorHandler(err, err.status, err.responseText);
                            throw err;
                        }
                        
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
                                const isRefreshPage = refreshAfterSyncResources.some(item => {
                                    return data.includes(item);
                                });
                                if (isRefreshPage) {
                                    window.location.reload();
                                } else {
                                    loadProducts({
                                        refresh: 1
                                    });
    
                                    unitModalHasBeenOpen = false;
                                    unitPage = 1;
                                    
                                    customerModalHasBeenOpen = false;
                                    customerPage = 1;
    
                                    resetHistoriesState();
                                }
                            });
                        }, 1000)
                    }
                })
            }
        });

        const loadRewardPoints = (props) => {
            if (props?.refresh) {
                loadRewardPointIsLast = false;
                rewardPointPage = 1;
                rewardPointListMap.clear();
            }
    
            if (loadRewardPointIsLoading || loadRewardPointIsLast) return;
    
            loadRewardPointIsLoading = true;
    
            let req = {
                'order[total_point]': 'asc',
                is_active: 1,
                page: rewardPointPage,
                benefit_type: 'discount',
                ...props?.params
            }
    
            const reqParams = $.param(req);
    
            $.ajax({
                url: BASE_URL + '/api/v1/reward_points?'+reqParams,
                type: "GET",
                dataType: "json",
                headers: {
                    'Authorization': TOKEN,
                    'company-id': COMPANY_ID
                },
                beforeSend: function() {
                    if (props?.refresh) {
                        $('#point-toggle-container').html(`
                            <div class="col-12 d-flex justify-content-center">
                                <div class="spinner-border spinner-border-sm" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                            `)
                    } else {
                        $('#point-toggle-container').append(`<div class="col-12 d-flex justify-content-center spinner-col">
                                <div class="spinner-border spinner-border-sm" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>`);
                    }
                },
                success: function(res) {
                    $('#point-toggle-container .spinner-col').remove()
                    if (res?.data?.length > 0) {
                        let html = '';
                        res?.data?.forEach((item, index) => {
                            
                            html += '<div class="col-4">'
                            html += '    <button type="button" class="btn btn-outline-light w-100 apply-point-toggle btn-lg" data-id="'+item.id+'">'
                            html += item?.total_point
                            html += '    </button>'
                            html += '</div>'

                            rewardPointListMap.set(item.id, item);
                        });

                        if (props?.refresh) {
                            $('#point-toggle-container').html(html);
                        } else {
                            $('#point-toggle-container').append(html);
                        }

                        rewardPointPage++
                        
                    } else {
                        loadRewardPointIsLast = true
                    }
                },
                complete: () => {
                    loadRewardPointIsLoading = false;
                }
            });
        }

        $('#point-toggle-container').on('scroll', function () {
            const el = this;
        
            const scrollTop = el.scrollTop;
            const windowHeight = el.clientHeight;
            const docHeight = el.scrollHeight;
        
            if (scrollTop + windowHeight >= docHeight - 100) {
                loadRewardPoints();
            }
        });

        $(document).on('click', '.apply-point-toggle', function() {
            const $this = $(this);
            const dataId = $this.data('id');

            
            if ($this.hasClass('selected')) {
                $(this).removeClass('selected');
                clearRewardPointDiscount()
                return
            }
            
            $('.apply-point-toggle').removeClass('selected');
            $(this).addClass('selected');

            // const getPointReward = rewardPointListMap.get(dataId);
            // if (getPointReward) {
            //     putRewardPoint(dataId, getPointReward);
            // }
        });

        $('#input-point').numericInput({
            allowNegative: false
        });

        $(document).on('input', '#input-point', function () {
            
            clearTimeout(typingTimer);

            $('#redeem-total-discount-placeholder').html('Loading...');
            
            typingTimer = setTimeout(() => {
                const $this = $(this);
                const val = $this.numericInput('getRaw');
    
                const calculateTotal = getCalculateTotal();
                $('#submit-redeem-toggle').prop('disabled', true)
    
                let req = {
                    point: val,
                    total_payment: calculateTotal.total,
                }
    
                const params = $.param(req);

                $.ajax({
                    url: BASE_URL + '/api/v1/pos/discount_point_exchange',
                    type: "GET",
                    dataType: "json",
                    data: params,
                    headers: {
                        'Authorization': TOKEN,
                        'company-id': COMPANY_ID
                    },
                    beforeSend: function () {
                        $('#bonus-point-label').html(0)
                    },
                    success: function (res) {
                        $('#redeem-total-discount-placeholder').html('Rp '+parseFloat(res?.total_discount).toLocaleString('en'));
                        $('#input-discount-point').val(res?.total_discount);
                    },
                    error: function () {
                    },
                    complete: function () {
                        validateTotalPointExchange();
                    }
                });

            }, 500);
        });

        const starting = () => {
            if (IS_FIRST) {
                Swal.fire({
                    title: 'Mohon Tunggu',
                    html: `<div style="margin-bottom: .25rem;">Sedang menyinkronkan data</div> <br> <div class="progress">
                                <div class="progress-bar bg-secondary" id="sync-progress-bar" style="width: 0%"></div>
                            </div>`,
                    showConfirmButton: false
                });

                processSync({
                    options: [
                        'product',
                        'transaction',
                        'stock_product',
                        'customer',
                        'branch',
                        'warehouse',
                        'reward_point_and_point_rule',
                        'accounting_master',
                        'settings',
                        'payment_method',
                        'currencies',
                        'permissions'
                    ],
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

                        IS_FIRST = 0

                        setTimeout(() => {
                            Swal.fire({
                                icon: (errs?.length > 0) ? 'warning' : 'success',
                                title: 'Proses Selesai',
                                html: (errs?.length > 0) ? errMessage : 'Proses sinkron berhasil'
                            }).then((result) => {
                                generateRefNumber();
                                loadProducts({
                                    refresh: true
                                });
                                $('#input-search-product').focus();
                                loadProductCategories();
                                setCustomerDefaultValue();
                            });
                        }, 1000)
                    }
                })
            } else {
                generateRefNumber();
                loadProducts({
                    refresh: true
                });
                $('#input-search-product').focus();
                loadProductCategories();
                setCustomerDefaultValue();
            }

        }

        if (!IS_ACCESS_TO_POS) {
            $('#authenticate-modal').modal('show');
        } else {
            starting()
        }

        $(document).on('hidden.bs.modal', '#access-denied-modal', function () {
            $('#input-supervisor-auth-user_id').val('').trigger('change');
            $('#input-supervisor-auth-password').val('');
        })

        $(document).on('click', '#reload-toggler', function () {
            showLoading()
            window.location.reload();
        })
    });
</script>
@endsection