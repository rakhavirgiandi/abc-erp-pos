<div class="sidebar-left horizontal-sidebar">

    <div class="sidebar-slide h-100">

        <!--- Sidebar-menu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="left-menu list-unstyled" id="side-menu">
                <li>
                    <a href="{{ url('/home') }}">
                        <i class="ti ti-dashboard fs-6"></i>
                        <span>{{ __('language.dashboard') }}</span>
                    </a>
                </li>
                {{-- <li>
                    <a href="javascript:void(0);">
                        <i class="fas fa-users-cog"></i>
                        <span>{{ __('language.hrm') }}</span>
                    </a>
                </li>
                <li>
                    <a href="javascript:void(0);">
                        <i class="fas fa-poll-h"></i>
                        <span>{{ __('language.crm') }}</span>
                    </a>
                </li> --}}
                @if (
                    config('user_request')->can('contacts') ||
                    config('user_request')->can('products') ||
                    config('user_request')->can('product_categories') ||
                    config('user_request')->can('product_variants') ||
                    config('user_request')->can('production_phases') ||
                    config('user_request')->can('contact_segments') ||
                    config('user_request')->can('units') ||
                    config('user_request')->can('fixed_assets') ||
                    config('user_request')->can('fixed_asset_categories') ||
                    config('user_request')->can('branches') ||
                    config('user_request')->can('warehouses') ||
                    config('user_request')->can('projects') ||
                    config('user_request')->can('taxes') ||
                    config('user_request')->can('deposit_classifications') || 
                    config('user_request')->can('bank_accounts')||
                    config('user_request')->can('point_rules')||
                    config('user_request')->can('reward_points')||
                    config('user_request')->can('currencies')
                )
                <li>
                    <a href="javascript:void(0);" class="has-arrow">
                        <i class="ti ti-layout-grid fs-6"></i>
                        <span>{{ __('language.data_master') }}</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        @if (
                                config('user_request')->can('contacts') ||
                                config('user_request')->can('contact_segments') 
                            )
                        <li>
                            <a href="javascript: void(0);" class="has-arrow">                             
                                <i class="ti ti-address-book fs-5"></i><span>{{ __('language.contact_data') }}</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false" style="
                                position: absolute;
                                top: 0;
                                left: 100%;
                                margin-left: 20px;
                                min-width: 220px;
                                z-index: 9999;
                            ">
                            @if (config('user_request')->can('contacts'))
                                <li><a href="{{ url('/data-master/contacts')}}"><i class="ti ti-user-plus fs-5"></i><span>{{ __('language.contact_data') }}</a></li>
                            @endif
                            @if (config('user_request')->can('contact_segments'))
                                <li><a href="{{ url('/data-master/contact-segments')}}"><i class="ti ti-users-group fs-5"></i><span>{{ __('language.contact_segment') }}</span></a></li>
                            @endif
                            </ul>
                        </li>
                        @endif
                        @if (
                                config('user_request')->can('products') ||
                                config('user_request')->can('product_categories') ||
                                config('user_request')->can('product_variants') ||
                                config('user_request')->can('production_phases')
                            )
                        <li>
                            <a href="javascript: void(0);" class="has-arrow">                             
                                <i class="ti ti-package fs-5"></i><span>{{ __('language.product') }}</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false" style="
                                position: absolute;
                                top: 0;
                                left: 100%;
                                margin-left: 20px;
                                min-width: 220px;
                                z-index: 9999;
                            ">
                            @if (config('user_request')->can('products'))
                                <li><a href="{{ url('/data-master/products')}}"><i class="ti ti-box fs-5"></i><span>{{ __('language.product_data') }}</a></li>
                            @endif
                            @if (config('user_request')->can('product_categories'))
                                <li><a href="{{ url('/data-master/product-categories')}}"><i class="ti ti-category fs-5"></i><span>{{ __('language.product_category') }}</span></a></li>
                            @endif
                            @if (config('user_request')->can('product_variants'))
                                <li><a href="{{ url('/data-master/variants')}}"><i class="ti ti-stack fs-5"></i><span>{{ __('language.variant_product') }}</span></a></li>
                            @endif
                            </ul>
                        </li>
                        @endif
                        @if (config('user_request')->can('units'))
                        <li>
                            <a href="{{ url('/data-master/units')}}">
                                <i class="ti ti-ruler fs-5"></i>
                                <span>{{ __('language.unit') }}</span>
                            </a>
                        </li>
                        @endif
                        @if (config('user_request')->can('currencies'))
                        <li>
                            <a href="{{ url('data-master/currencies')}}">
                                <i class="ti ti-coin fs-5"></i>
                                <span>{{ __('language.currency') }}</span>
                            </a>
                        </li>
                        @endif
                        @if (config('user_request')->can('taxes'))
                        <li>
                            <a href="{{ url('data-master/taxes')}}">
                                <i class="ti ti-percentage fs-5"></i>
                                <span>{{ __('language.tax_data') }}</span>
                            </a>
                        </li>
                        @endif
                        @if (
                            config('user_request')->can('branches') ||
                            config('user_request')->can('warehouses')||
                            config('user_request')->can('projects')
                        )
                        <li>
                            <a href="javascript: void(0);" class="has-arrow">                             
                                <i class="ti ti-map-pin fs-5"></i><span>{{ __('language.branch_and_warehouse') }}</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false" style="
                                position: absolute;
                                top: 0;
                                left: 100%;
                                margin-left: 20px;
                                min-width: 220px;
                                z-index: 9999;
                            ">
                             @if (config('user_request')->can('branches'))
                                <li><a href="{{ url('/data-master/branches')}}"><i class="ti ti-building-store fs-5"></i><span>{{ __('language.branch_data') }}</a></li>
                             @endif
                             @if (config('user_request')->can('warehouses'))
                                <li><a href="{{ url('/data-master/warehouses')}}"><i class="ti ti-building-warehouse fs-5"></i><span>{{ __('language.warehouse_data') }}</span></a></li>
                             @endif
                             @if (config('user_request')->can('projects'))
                                <li><a href="{{ url('/data-master/projects')}}"><i class="ti ti-briefcase fs-5"></i><span>{{ __('language.project_data') }}</span></a></li>
                             @endif
                            </ul>
                        </li>
                        @endif
                        @if (
                            config('user_request')->can('fixed_assets') ||
                            config('user_request')->can('fixed_asset_categories')
                        )
                        <li>
                            <a href="javascript: void(0);" class="has-arrow">                             
                                <i class="ti ti-diamond fs-5"></i><span>{{ __('language.fixed_asset') }}</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false" style="
                                position: absolute;
                                top: 0;
                                left: 100%;
                                margin-left: 20px;
                                min-width: 220px;
                                z-index: 9999;
                            ">
                            @if (
                                config('user_request')->can('fixed_assets')
                            )
                                <li><a href="{{ url('/data-master/fixed-assets')}}"><i class="ti ti-diamond fs-5"></i><span>{{ __('language.fixed_asset_data') }}</a></li>
                            @endif
                            @if (
                                config('user_request')->can('fixed_asset_categories')
                            )
                                <li><a href="{{ url('/data-master/fixed-asset-categories')}}"><i class="ti ti-category fs-5"></i><span>{{ __('language.fixed_asset_category') }}</span></a></li>
                            @endif
                            </ul>
                        </li>
                        @endif
                        @if (config('user_request')->can('deposit_classifications'))
                        <li>
                            <a href="{{ url('data-master/deposit-classifications')}}">
                                <i class="ti ti-cash-banknote fs-5"></i>
                                <span>{{ __('language.deposit_classification') }}</span>
                            </a>
                        </li>
                        @endif
                        
                        @if (
                            config('user_request')->can('point_rules')||
                            config('user_request')->can('reward_points')
                        )
                        <li>
                            <a href="javascript: void(0);" class="has-arrow">                             
                                <i class="ti ti-shopping-cart-cog fs-5"></i><span>{{ __('language.merchandise') }}</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false" style="
                                position: absolute;
                                top: 0;
                                left: 100%;
                                margin-left: 20px;
                                min-width: 220px;
                                z-index: 9999;
                            ">
                            @if (config('user_request')->can('bank_accounts'))
                                <li><a href="{{ url('data-master/bank-accounts')}}"><i class="ti ti-credit-card fs-5"></i><span>{{ __('language.payment_method') }}</span></a></li>
                            @endif
                            @if (config('user_request')->can('point_rules'))
                                <li><a href="{{ url('/data-master/merchandises/point-rules')}}"><i class="ti ti-target fs-5"></i><span>{{ __('language.point_rule') }}</span></a></li>
                            @endif
                            @if (config('user_request')->can('reward_points'))
                                <li><a href="{{ url('/data-master/merchandises/reward-points')}}"><i class="ti ti-gift fs-5"></i><span>{{ __('language.reward_point') }}</a></li>
                            @endif
                            @if (config('user_request')->can('customer_points'))
                                <li><a href="{{ url('/data-master/customer-points')}}"><i class="ti ti-mood-dollar fs-5"></i><span>{{ __('language.customer_point') }}</a></li>
                            @endif
                            </ul>
                        </li>
                        @endif
                        @if (config('user_request')->can('production_phases'))
                        <li>
                            <a href="{{ url('data-master/production-phases')}}">
                                <i class="ti ti-git-branch fs-5"></i>
                                <span>{{ __('language.production_phase') }}</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>

                @endif
                @if (
                    config('user_request')->can('sales_quotations') ||
                    config('user_request')->can('sales_orders') ||
                    config('user_request')->can('sales_invoices') ||
                    config('user_request')->can('sales_returns') 
                    )
                <li>
                    <a href="javascript:void(0);" class="has-arrow">
                        <i class="ti ti-shopping-cart-plus fs-6"></i>
                        <span>{{ __('language.sales') }}</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        @if (config('user_request')->can('sales_quotations'))
                        <li>
                            <a href="{{ url('sales/sales-quotations')}}">
                                <i class="ti ti-receipt fs-5"></i>
                                <span>{{ __('language.sales_quotation') }}</span>
                            </a>
                        </li>
                        @endif
                        @if (config('user_request')->can('sales_orders'))
                        <li>
                            <a href="{{ url('sales/sales-orders')}}">
                                <i class="ti ti-receipt fs-5"></i>
                                <span>{{ __('language.sales_order') }}</span>
                            </a>
                        </li>
                        @endif
                        {{-- <li>
                            <a href="{{ url('hospitals/room')}}">
                                <i class="fas fa-file-invoice"></i>
                                <span>Faktur Proforma</span>
                            </a>
                        </li> --}}
                        @if (config('user_request')->can('sales_invoices'))
                        <li>
                            <a href="{{ url('sales/sales-invoices')}}">
                                <i class="ti ti-receipt-dollar fs-5"></i>
                                <span>{{ __('language.sales_invoice') }}</span>
                            </a>
                        </li>
                        @endif
                        @if (config('user_request')->can('sales_returns'))
                        <li>
                            <a href="{{ url('sales/sales-returns')}}">
                                <i class="ti ti-arrow-back-up fs-5"></i>
                                <span>{{ __('language.sales_return') }}</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif
                @if (
                   config('user_request')->can('purchase_quotations') ||
                   config('user_request')->can('purchase_orders') ||
                    config('user_request')->can('purchase_returns') ||
                    config('user_request')->can('purchase_invoices') 
                    )
                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i class="ti ti-shopping-bag-plus fs-6"></i>
                        <span>{{ __('language.purchase') }}</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        @if (config('user_request')->can('purchase_quotations'))
                        <li>
                            <a href="{{ url('purchase/purchase-quotations')}}">
                                <i class="ti ti-receipt fs-5"></i>
                                <span>{{ __('language.purchase_quotation') }}</span>
                            </a>
                        </li>
                        @endif
                        @if (config('user_request')->can('purchase_orders'))
                        <li>
                            <a href="{{ url('purchase/purchase-orders')}}">
                                <i class="ti ti-receipt fs-5"></i>
                                <span>{{ __('language.purchase_order') }}</span>
                            </a>
                        </li>
                        @endif
                        @if (config('user_request')->can('purchase_invoices'))
                        <li>
                            <a href="{{ url('purchase/purchase-invoices')}}">
                                <i class="ti ti-receipt-dollar fs-5"></i>
                                <span>{{ __('language.purchase_invoice') }}</span>
                            </a>
                        </li>
                        @endif
                        @if (config('user_request')->can('purchase_returns'))
                        <li>
                            <a href="{{ url('purchase/purchase-returns')}}">
                                <i class="ti ti-arrow-forward-up fs-5"></i>
                                <span>{{ __('language.purchase_return') }}</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif
                @if (
                    config('user_request')->can('coa') ||
                    config('user_request')->can('accounting_journals') ||
                    config('user_request')->can('cash_ins') ||
                    config('user_request')->can('cash_outs') ||
                    config('user_request')->can('giro_ins') ||
                    config('user_request')->can('giro_outs') ||
                    config('user_request')->can('sales_deposits') ||
                    config('user_request')->can('purchase_deposits') ||
                    config('user_request')->can('cash_transfers')||
                    config('user_request')->can('receivables') ||
                    config('user_request')->can('receivable_payments')||
                    config('user_request')->can('debts') || 
                    config('user_request')->can('debt_payments')
                    )
                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i class="ti ti-chart-bar fs-5"></i>
                        <span>{{ __('language.finance') }}</span>
                    </a>
                    <ul class="sub-menu">
                        @if (
                            config('user_request')->can('coa') ||
                            config('user_request')->can('accounting_journals') 
                            )
                        <li>
                            <a href="javascript: void(0);" class="has-arrow">                             
                                <i class="ti ti-graph fs-5"></i><span>{{ __('language.accounting') }}</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false" style="
                                position: absolute;
                                top: 0;
                                left: 100%;
                                margin-left: 20px;
                                min-width: 220px;
                                z-index: 9999;
                            ">
                             @if (config('user_request')->can('coa'))
                                <li><a href="{{ url('/accountings/accounting-master')}}"><i class="ti ti-notebook fs-5"></i><span>{{ __('language.accounting_master') }}</span></a></li>
                             @endif
                             @if (config('user_request')->can('accounting_journals'))
                                <li><a href="{{ url('/accountings/accounting-journals')}}"><i class="ti ti-scale fs-5"></i><span>{{ __('language.accounting_journal') }}</span></a></li>
                             @endif
                            </ul>
                        </li>
                        @endif
                        @if (
                            config('user_request')->can('cash_ins') ||
                            config('user_request')->can('cash_outs') ||
                            config('user_request')->can('giro_ins') ||
                            config('user_request')->can('giro_outs') ||
                            config('user_request')->can('cash_transfers')
                            )
                        <li>
                            <a href="javascript: void(0);" class="has-arrow">                             
                                <i class="ti ti-building-bank fs-5"></i><span>{{ __('language.cash_and_bank') }}</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false" style="
                                position: absolute;
                                top: 0;
                                left: 100%;
                                margin-left: 20px;
                                min-width: 220px;
                                z-index: 9999;
                            ">
                             @if (config('user_request')->can('cash_ins'))
                                <li><a href="{{ url('/accountings/cash-ins')}}"><i class="fas fa-arrow-down"></i><span>{{ __('language.cash_in') }}</span></a></li>
                             @endif
                             @if (config('user_request')->can('cash_outs'))
                                <li><a href="{{ url('/accountings/cash-outs')}}"><i class="fas fa-arrow-up"></i><span>{{ __('language.cash_out') }}</span></a></li>
                             @endif
                             @if (config('user_request')->can('cash_transfers'))
                                <li><a href="{{ url('/accountings/cash-transfers')}}"><i class="fas fa-exchange-alt"></i><span>{{ __('language.cash_transfer') }}</span></a></li>
                             @endif
                             @if (config('user_request')->can('giro_ins'))
                                <li><a href="{{ url('/accountings/giro-ins')}}"><i class="fas fa-arrow-circle-down"></i><span>{{ __('language.giro_in') }}</span></a></li>
                             @endif
                             @if (config('user_request')->can('giro_ins'))
                                <li> <a href="{{ url('/accountings/giro-outs')}}"><i class="fas fa-arrow-circle-up"></i><span>{{ __('language.giro_out') }}</span></a></li>
                             @endif
                            </ul>
                        </li>
                        @endif
                        @if (
                            config('user_request')->can('receivables') ||
                            config('user_request')->can('receivable_payments')
                            )
                        <li>
                            <a href="javascript: void(0);" class="has-arrow">                             
                                <i class="ti ti-file-invoice fs-5"></i><span>{{ __('language.receivable') }}</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false" style="
                                position: absolute;
                                top: 0;
                                left: 100%;
                                margin-left: 20px;
                                min-width: 220px;
                                z-index: 9999;
                            ">
                             @if (config('user_request')->can('receivables'))
                                <li><a href="{{ url('/accountings/receivables')}}"><i class="ti ti-list-details fs-5"></i><span>{{ __('language.receivable_list') }}</span></a></li>
                             @endif
                             @if (config('user_request')->can('receivable_payments'))
                                <li><a href="{{ url('/accountings/receivable-payments')}}"><i class="ti ti-credit-card-refund fs-5"></i><span>{{ __('language.receivable_payment_list') }}</span></a></li>
                             @endif
                            </ul>
                        </li>
                        @endif
                        @if (
                            config('user_request')->can('debts') || 
                            config('user_request')->can('debt_payments')
                            )
                        <li>
                            <a href="javascript: void(0);" class="has-arrow">                             
                                <i class="ti ti-file-minus fs-5"></i><span>{{ __('language.debt') }}</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false" style="
                                position: absolute;
                                top: 0;
                                left: 100%;
                                margin-left: 20px;
                                min-width: 220px;
                                z-index: 9999;
                            ">
                             @if (config('user_request')->can('debts'))
                                <li><a href="{{ url('/accountings/payables')}}"><i class="ti ti-list-details fs-5"></i><span>{{ __('language.payable_list') }}</span></a></li>
                             @endif
                             @if (config('user_request')->can('debt_payments'))
                                <li><a href="{{ url('/accountings/payable-payments')}}"><i class="ti ti-credit-card-pay fs-5"></i><span>{{ __('language.payable_payment_list') }}</span></a></li>
                             @endif
                            </ul>
                        </li>
                        @endif
                        @if (
                            config('user_request')->can('sales_deposits') ||
                            config('user_request')->can('purchase_deposits') 
                            )
                        <li>
                            <a href="javascript: void(0);" class="has-arrow">                             
                                <i class="ti ti-wallet fs-5"></i><span>{{ __('language.down_payment') }}</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false" style="
                                position: absolute;
                                top: 0;
                                left: 100%;
                                margin-left: 20px;
                                min-width: 220px;
                                z-index: 9999;
                            ">
                             @if (config('user_request')->can('purchase_deposits'))
                                <li><a href="{{ url('/accountings/purchase-deposits')}}"><i class="ti ti-moneybag-move fs-5"></i><span>{{ __('language.purchase_deposit') }}</span></a></li>
                             @endif
                             @if (config('user_request')->can('sales_deposits'))
                                <li><a href="{{ url('/accountings/sales-deposits')}}"><i class="ti ti-moneybag-move-back fs-5"></i><span>{{ __('language.sales_deposit') }}</span></a></li>
                             @endif
                            </ul>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif
                @if (
                    config('user_request')->can('purchase_requests') ||
                    config('user_request')->can('stock_adjustments') ||
                    config('user_request')->can('stock_opnames') ||
                    config('user_request')->can('warehouse_transfers') ||
                    config('user_request')->can('sales_deliveries')||
                    config('user_request')->can('purchase_receipts') 
                    )
                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i class="ti ti-forklift fs-5"></i>
                        <span>{{ __('language.warehouse') }}</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        @if (config('user_request')->can('purchase_requests'))
                        <li>
                            <a href="{{ url('warehouses/request-materials')}}">
                                <i class="ti ti-clipboard-list fs-5"></i> <span>{{ __('language.request_material') }}</span>
                            </a>
                        </li>
                        @endif
                        @if (config('user_request')->can('stock_opnames'))
                        <li>
                            <a href="{{ url('warehouses/stock-opnames')}}">
                                <i class="ti ti-clipboard-check fs-5"></i> <span>{{ __('language.stock_opname') }}</span>
                            </a>
                        </li>
                        @endif
                        @if (config('user_request')->can('stock_adjustments'))
                        <li>
                            <a href="{{ url('warehouses/stock-adjustments')}}">
                                <i class="ti ti-adjustments fs-5"></i> <span>{{ __('language.stock_adjustment') }}</span>
                            </a>
                        </li>
                        @endif
                        @if (config('user_request')->can('warehouse_transfers'))
                        <li>
                            <a href="{{ url('warehouses/warehouse-transfers')}}">
                                <i class="ti ti-file-isr fs-5"></i> <span>{{ __('language.warehouse_transfer') }}</span>
                            </a>
                        </li>
                        @endif
                        @if (config('user_request')->can('sales_deliveries'))
                        <li>
                            <a href="{{ url('warehouses/sales-deliveries')}}">
                                <i class="ti ti-truck-delivery fs-5"></i>
                                <span>{{ __('language.sales_delivery') }}</span>
                            </a>
                        </li>
                        @endif
                        @if (config('user_request')->can('purchase_receipts'))
                        <li>
                            <a href="{{ url('warehouses/purchase-receipts')}}">
                                <i class="ti ti-clipboard-search fs-5"></i>
                                <span>{{ __('language.purchase_receipt') }}</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif
                @if (
                    config('user_request')->can('bill_of_materials') 
                    // config('user_request')->can('assemblies') ||
                    // config('user_request')->can('deassemblies')
                    )
                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i class="ti ti-building-factory-2 fs-5"></i>
                        <span>{{ __('language.manufacture') }}</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        @if (config('user_request')->can('bill_of_materials'))
                        <li>
                            <a href="{{ url('manufactures/bill-of-materials')}}">
                                <i class="fas fa-sitemap"></i><span>{{ __('language.bill_of_material') }}</span>
                            </a>
                        </li>
                        @endif
                        <li>
                            <a href="{{ url('manufactures/productions')}}">
                                <i class="ti ti-building-factory fs-5"></i><span>{{ __('language.production') }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('manufactures/ppic')}}">
                                <i class="fas fa-project-diagram"></i><span>{{ __('language.ppic') }}</span>
                            </a>
                        </li>
                        {{-- <li>
                            <a href="{{ url('out-patients/out-patient')}}">
                                <span>Work Order</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('out-patients/out-patient')}}">
                                <span>Material Request</span>
                            </a>
                        </li> --}}
                    </ul>
                </li>
                @endif
                @if (   
                    config('user_request')->can('report_balance_sheets') ||
                    config('user_request')->can('report_profit_loss') ||
                    config('user_request')->can('report_cash_flows') ||
                    config('user_request')->can('report_ledgers') ||
                    config('user_request')->can('report_sales_quotations') ||
                    config('user_request')->can('report_sales_orders') ||
                    config('user_request')->can('report_sales_deliveries') ||
                    config('user_request')->can('report_sales_deliveries_outstanding') ||
                    config('user_request')->can('report_sales_invoices_summary') ||
                    config('user_request')->can('report_sales_invoices_detail') ||
                    config('user_request')->can('report_receivables') ||
                    config('user_request')->can('report_purchase_orders') ||
                    config('user_request')->can('report_purchase_receipts') ||
                    config('user_request')->can('report_purchase_receipts_outstanding') ||
                    config('user_request')->can('report_purchase_invoices_summary') ||
                    config('user_request')->can('report_purchase_invoices_detail') ||
                    config('user_request')->can('report_debts') ||
                    config('user_request')->can('report_warehouse_stock_request') ||
                    config('user_request')->can('report_warehouse_stock_adjustment') ||
                    config('user_request')->can('report_warehouse_stock_opname') ||
                    config('user_request')->can('report_warehouse_stock_transfers') ||
                    config('user_request')->can('report_product') ||
                    config('user_request')->can('report_product_by_warehouse') ||
                    config('user_request')->can('report_product_mutations') ||
                    config('user_request')->can('report_product_stock_cards') || 
                    config('user_request')->can('report_product_stock_card_serial_numbers') || 
                    config('user_request')->can('report_product_profitability') ||
                    config('user_request')->can('report_production_wip') ||
                    config('user_request')->can('report_production_wip_stock_card') ||
                    config('user_request')->can('report_production_request_materials') ||
                    config('user_request')->can('report_production_usage_notes') ||
                    config('user_request')->can('report_production_material_outs') ||
                    config('user_request')->can('report_production_stock_destructions') ||
                    config('user_request')->can('report_production_waste_destructions') ||
                    config('user_request')->can('report_production_finish_barcodes') ||
                    config('user_request')->can('report_production_assembly_disassembly')
                    )
                <li style="position: relative; z-index: 1000;">
                    <a href="{{ url('reports/main-menu')}}">
                        <i class="ti ti-report-analytics fs-6"></i>
                        <span>{{ __('language.report') }}</span>
                    </a>
                </li>
                @endif
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>