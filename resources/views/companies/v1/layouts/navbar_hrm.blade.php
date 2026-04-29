<div class="sidebar-left horizontal-sidebar">

    <div class="sidebar-slide h-100">

        <!--- Sidebar-menu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="left-menu list-unstyled" id="side-menu">
                <li>
                    <a href="{{ url('/hrm/dashboard') }}">
                        <i class="ti ti-dashboard fs-6"></i>
                        <span>{{ __('language.dashboard') }}</span>
                    </a>
                </li>
                {{-- @if (
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
                ) --}}
                <li>
                    <a href="javascript:void(0);" class="has-arrow">
                        <i class="ti ti-layout-grid fs-6"></i>
                        <span>{{ __('language.data_master') }}</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li>
                            <a href="{{ url('/hrm/data-master/departments')}}">
                                <i class="ti ti-ruler fs-5"></i>
                                <span>{{ __('language.department') }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/hrm/data-master/job-positions')}}">
                                <i class="ti ti-ruler fs-5"></i>
                                <span>{{ __('language.job_position') }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/hrm/data-master/job-levels')}}">
                                <i class="ti ti-ruler fs-5"></i>
                                <span>{{ __('language.job_level') }}</span>
                            </a>
                        </li>
                        {{-- @if (
                                config('user_request')->can('contacts') ||
                                config('user_request')->can('contact_segments') 
                            ) --}}

                        <li>
                            <a href="javascript: void(0);" class="has-arrow">                             
                                <i class="ti ti-address-book fs-5"></i><span>{{ __('language.employee') }}</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false" style="
                                position: absolute;
                                top: 0;
                                left: 100%;
                                margin-left: 20px;
                                min-width: 220px;
                                z-index: 9999;
                            ">
                            {{-- @if (config('user_request')->can('contacts')) --}}
                                <li><a href="{{ url('/hrm/data-master/employees')}}"><i class="ti ti-user-plus fs-5"></i><span>{{ __('language.employee') }}</a></li>
                            {{-- @endif --}}
                            {{-- @if (config('user_request')->can('contact_segments')) --}}
                                <li><a href="{{ url('/hrm/data-master/employment-statuses')}}"><i class="ti ti-users-group fs-5"></i><span>{{ __('language.employment_status') }}</span></a></li>
                            {{-- @endif --}}
                            </ul>
                        </li>
                        {{-- @endif --}}
                        <li>
                            <a href="{{ url('/hrm/data-master/payroll-components')}}">
                                <i class="ti ti-ruler fs-5"></i>
                                <span>{{ __('language.payroll_component') }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/hrm/data-master/work-shifts')}}">
                                <i class="ti ti-ruler fs-5"></i>
                                <span>{{ __('language.work_shift') }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/hrm/data-master/work-schedules')}}">
                                <i class="ti ti-ruler fs-5"></i>
                                <span>{{ __('language.work_schedule') }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/hrm/data-master/holidays')}}">
                                <i class="ti ti-ruler fs-5"></i>
                                <span>{{ __('language.holiday') }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/hrm/data-master/leave-types')}}">
                                <i class="ti ti-ruler fs-5"></i>
                                <span>{{ __('language.leave_type') }}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/hrm/data-master/approvals')}}">
                                <i class="ti ti-ruler fs-5"></i>
                                <span>{{ __('language.approval') }}</span>
                            </a>
                        </li>
                        {{-- @if (
                                config('user_request')->can('products') ||
                                config('user_request')->can('product_categories') ||
                                config('user_request')->can('product_variants') ||
                                config('user_request')->can('production_phases')
                            ) --}}
                        <li>
                            <a href="javascript: void(0);" class="has-arrow">                             
                                <i class="ti ti-package fs-5"></i><span>{{ __('language.kpi_data') }}</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false" style="
                                position: absolute;
                                top: 0;
                                left: 100%;
                                margin-left: 20px;
                                min-width: 220px;
                                z-index: 9999;
                            ">
                            {{-- @if (config('user_request')->can('products')) --}}
                                <li><a href="{{ url('/hrm/data-master/teams')}}"><i class="ti ti-box fs-5"></i><span>{{ __('language.team') }}</a></li>
                            {{-- @endif --}}
                            {{-- @if (config('user_request')->can('product_categories')) --}}
                                <li><a href="{{ url('/hrm/data-master/kpi-masters')}}"><i class="ti ti-category fs-5"></i><span>{{ __('language.kpi_master') }}</span></a></li>
                            {{-- @endif --}}
                            {{-- @if (config('user_re/hrmquest')->can('product_variants')) --}}
                                <li><a href="{{ url('/hrm/data-master/kpi-targets')}}"><i class="ti ti-stack fs-5"></i><span>{{ __('language.kpi_target') }}</span></a></li>
                            {{-- @endif --}}
                            </ul>
                        </li>
                    </ul>
                </li>

                {{-- @endif --}}
                {{-- @if (
                    config('user_request')->can('sales_quotations') ||
                    config('user_request')->can('sales_orders') ||
                    config('user_request')->can('sales_invoices') ||
                    config('user_request')->can('sales_returns') 
                    ) --}}
                <li>
                    <a href="javascript:void(0);" class="has-arrow">
                        <i class="ti ti-shopping-cart-plus fs-6"></i>
                        <span>{{ __('language.kpi') }}</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        {{-- @if (config('user_request')->can('sales_quotations')) --}}
                        <li>
                            <a href="{{ url('hrm/kpi/assessment-data')}}">
                                <i class="ti ti-receipt fs-5"></i>
                                <span>{{ __('language.assesment_data') }}</span>
                            </a>
                        </li>
                        {{-- @endif --}}
                    </ul>
                </li>
                {{-- @endif --}}
                {{-- @if (
                   config('user_request')->can('purchase_quotations') ||
                   config('user_request')->can('purchase_orders') ||
                    config('user_request')->can('purchase_returns') ||
                    config('user_request')->can('purchase_invoices') 
                    ) --}}
                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i class="ti ti-shopping-bag-plus fs-6"></i>
                        <span>{{ __('language.attendance') }}</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        {{-- @if (config('user_request')->can('purchase_quotations')) --}}
                        <li>
                            <a href="{{ url('/hrm/attendances/attendance-data')}}">
                                <i class="ti ti-receipt fs-5"></i>
                                <span>{{ __('language.attendance') }}</span>
                            </a>
                        </li>
                        {{-- @endif --}}
                        {{-- @if (config('user_request')->can('purchase_orders')) --}}
                        <li>
                            <a href="{{ url('/hrm/attendances/leave-requests')}}">
                                <i class="ti ti-receipt fs-5"></i>
                                <span>{{ __('language.leave_request') }}</span>
                            </a>
                        </li>
                        {{-- @endif --}}
                        {{-- @if (config('user_request')->can('purchase_invoices')) --}}
                        <li>
                            <a href="{{ url('/hrm/attendances/permit-requests')}}">
                                <i class="ti ti-receipt-dollar fs-5"></i>
                                <span>{{ __('language.permit_request') }}</span>
                            </a>
                        </li>
                        {{-- @endif --}}
                        {{-- @if (config('user_request')->can('purchase_returns')) --}}
                        <li>
                            <a href="{{ url('/hrm/attendances/overtime-requests')}}">
                                <i class="ti ti-arrow-forward-up fs-5"></i>
                                <span>{{ __('language.overtime_request') }}</span>
                            </a>
                        </li>
                        {{-- @endif --}}
                    </ul>
                </li>
                {{-- @endif --}}
                {{-- @if (
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
                    ) --}}
                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i class="ti ti-chart-bar fs-5"></i>
                        <span>{{ __('language.payroll') }}</span>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="{{ url('/hrm/payrolls/payroll-data')}}">
                                <i class="ti ti-arrow-forward-up fs-5"></i>
                                <span>{{ __('language.payroll') }}</span>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- @endif --}}
                {{-- @if (
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
                    ) --}}
                    <li>
                        <a href="javascript: void(0);" class="has-arrow">
                            <i class="ti ti-chart-bar fs-5"></i>
                            <span>{{ __('language.other') }}</span>
                        </a>
                        <ul class="sub-menu">
                            <li>
                                <a href="{{ url('/hrm/other/announcements')}}">
                                    <i class="ti ti-arrow-forward-up fs-5"></i>
                                    <span>{{ __('language.announcement') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    {{-- @endif --}}
                {{-- @if (   
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
                    ) --}}
                <li style="position: relative; z-index: 1000;">
                    <a href="{{ url('reports/main-menu')}}">
                        <i class="ti ti-report-analytics fs-6"></i>
                        <span>{{ __('language.report') }}</span>
                    </a>
                </li>
                {{-- @endif --}}
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>