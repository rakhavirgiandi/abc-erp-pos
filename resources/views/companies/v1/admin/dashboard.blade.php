@extends('companies.v1.layouts.main')

@section('title', $title)

@section('style')
<link rel="stylesheet" href="{{ asset('assets/libs/simplebar/simplebar.min.css')}}">
@endsection

@section('content')
<div class="mb-3 d-flex justify-content-between">
    <div>
        <h4 class="mb-1">Hello, Admin</h4>
        <p class="mb-0 text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
    </div>
    <div class="d-flex">
        <select id="input-filter-period">
            <option value="daily">Hari Ini</option>
            <option value="monthly">Bulan Ini</option>
            <option value="third-of-month">3 Bulan</option>
            <option value="this-year">Tahun Ini</option>
        </select>
    </div>
</div>

<div class="row placeholder-glow">
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card placeholder w-100" id="total_sales-sales-metric-card">
            <div class="card-body">
                <div class="avatar avatar-sm avatar-label-secondary mb-6 sales-metric-card-icon">
                </div>
                <h6 class="mb-1 text-truncate">Total Penjualan</h6>
                <p class="mb-0 sales-metric-card-value">0</p>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card placeholder w-100" id="sales_count-sales-metric-card">
            <div class="card-body">
                <div class="avatar avatar-sm avatar-label-success mb-6 sales-metric-card-icon">
                </div>
                <h6 class="mb-1 text-truncate">Jumlah Penjualan</h6>
                <p class="mb-0 sales-metric-card-value">0</p>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card placeholder w-100" id="items_sold-sales-metric-card">
            <div class="card-body">
                <div class="avatar avatar-sm avatar-label-warning mb-6 sales-metric-card-icon">
                </div>
                <h6 class="mb-1 text-truncate">Item Terjual</h6>
                <p class="mb-0 sales-metric-card-value">0</p>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card placeholder w-100" id="average_sales-sales-metric-card">
            <div class="card-body">
                <div class="avatar avatar-sm avatar-label-pink mb-6 sales-metric-card-icon">
                </div>
                <h6 class="mb-1 text-truncate">Rata Rata Transaksi</h6>
                <p class="text-muted mb-0"></p>
                <p class="mb-0 sales-metric-card-value">0</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-8">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Grafik Penjualan</h5>
                    <div class="d-flex gap-2">
                        <select id="input-sales-overview-filter-month">
                            <option value="">Pilih Bulan</option>
                        </select>
                        <select id="input-sales-overview-filter-year">
                            <option value="">Pilih Tahun</option>
                        </select>
                    </div>
                </div>
                <div id="sales-overview-chart"></div>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="card">
            <div class="card-body">
                <h5 class="mb-3">Transaksi Terbaru</h5>
                <div class="rich-list overflow-y-auto" id="recent-transaction-list" style="height: 350px;">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <div class="card">
            <div class="card-body">
                <h5>Stok Produk Menipis</h5>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card">
            <div class="card-body">
                <h5>pelanggan Teratas</h5>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js')}}"></script>
<script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
<script>

    let salesOverviewChart = null;

    $('#input-filter-period').select2({
        placeholder: "Pilih Period",
        width: '150px'
    });

    $('#input-sales-overview-filter-month').select2({
        placeholder: "Pilih Bulan",
        allowClear: true,
        width: '133px'
    });

    $('#input-sales-overview-filter-year').select2({
        placeholder: "Pilih Tahun",
        allowClear: true,
        width: '100px'
    });

    generateMonthOptions('#input-sales-overview-filter-month',  {
        value: 'current'
    });

    generateYearOptions('#input-sales-overview-filter-year', {
        value: 'current'
    })

    function getPeriodDate(period) {
        const today = new Date();

        const year = today.getFullYear();
        const month = today.getMonth();

        const formatDate = (date) => {
            const y = date.getFullYear();
            const m = String(date.getMonth() + 1).padStart(2, '0');
            const d = String(date.getDate()).padStart(2, '0');

            return `${y}-${m}-${d}`;
        };

        let from;
        let to;

        switch (period) {
            case 'daily':
                from = new Date(year, month, today.getDate());
                to = new Date(year, month, today.getDate());
                break;

            case 'monthly':
                from = new Date(year, month, 1);
                to = new Date(year, month + 1, 0);
                break;

            case 'third-of-month':
                from = new Date(year, month - 2, 1);
                to = new Date(year, month + 1, 0);
                break;

            case 'this-year':
                from = new Date(year, 0, 1);
                to = new Date(year, 11, 31);
                break;

            default:
                throw new Error(`Period "${period}" tidak dikenal.`);
        }

        return {
            from_date: formatDate(from), 
            to_date: formatDate(to)
        };
    }

    const setSalesMetricCardLoading = () => {
        $('#total_sales-sales-metric-card').addClass('placeholder w-100')
        $('#sales_count-sales-metric-card').addClass('placeholder w-100')
        $('#items_sold-sales-metric-card').addClass('placeholder w-100')
        $('#average_sales-sales-metric-card').addClass('placeholder w-100')

        $('#total_sales-sales-metric-card .sales-metric-card-icon').html('')
        $('#sales_count-sales-metric-card .sales-metric-card-icon').html('')
        $('#items_sold-sales-metric-card .sales-metric-card-icon').html('')
        $('#average_sales-sales-metric-card .sales-metric-card-icon').html('')

        $('#total_sales-sales-metric-card .sales-metric-card-value').html('0')
        $('#sales_count-sales-metric-card .sales-metric-card-value').html('0')
        $('#items_sold-sales-metric-card .sales-metric-card-value').html('0')
        $('#average_sales-sales-metric-card .sales-metric-card-value').html('0')
    }

    const getSalesMetric = () =>  {
        const inputPeriod = $('#input-filter-period');

        if (inputPeriod.val()) {
            const period = getPeriodDate(inputPeriod.val());
            
            const params = new URLSearchParams(period);

            $.ajax({
                url: BASE_URL + '/api/v1/dashboard/sales_metric?'+params?.toString(),
                type: "GET",
                dataType: "json",
                headers: {
                    'Authorization': TOKEN,
                    'company-id': COMPANY_ID,
                },
                beforeSend: () => {
                    setSalesMetricCardLoading();
                },
                success: function(res) {
                    const getDiffLabel = (diff, type) => {

                        value = parseFloat(diff)
                        
                        if (value == 0) return ''; 

                        let icon = '';

                        if (value > 0) {
                            icon = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="eva eva-trending-up size-3"><g data-name="Layer 2"><g data-name="trending-up"><rect width="24" height="24" transform="rotate(-90 12 12)" opacity="0"></rect><path d="M21 7a.78.78 0 0 0 0-.21.64.64 0 0 0-.05-.17 1.1 1.1 0 0 0-.09-.14.75.75 0 0 0-.14-.17l-.12-.07a.69.69 0 0 0-.19-.1h-.2A.7.7 0 0 0 20 6h-5a1 1 0 0 0 0 2h2.83l-4 4.71-4.32-2.57a1 1 0 0 0-1.28.22l-5 6a1 1 0 0 0 .13 1.41A1 1 0 0 0 4 18a1 1 0 0 0 .77-.36l4.45-5.34 4.27 2.56a1 1 0 0 0 1.27-.21L19 9.7V12a1 1 0 0 0 2 0V7z"></path></g></g></svg>`;
                        } else if (value < 0) {
                            icon = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" class="eva eva-trending-down size-3"><g data-name="Layer 2"><g data-name="trending-down"><rect width="24" height="24" transform="rotate(-90 12 12)" opacity="0"></rect><path d="M21 12a1 1 0 0 0-2 0v2.3l-4.24-5a1 1 0 0 0-1.27-.21L9.22 11.7 4.77 6.36a1 1 0 1 0-1.54 1.28l5 6a1 1 0 0 0 1.28.22l4.28-2.57 4 4.71H15a1 1 0 0 0 0 2h5a1.1 1.1 0 0 0 .36-.07l.14-.08a1.19 1.19 0 0 0 .15-.09.75.75 0 0 0 .14-.17 1.1 1.1 0 0 0 .09-.14.64.64 0 0 0 .05-.17A.78.78 0 0 0 21 17z"></path></g></g></svg>`;
                        }

                        let plus = '+';

                        if (value < 0) {
                            plus = '-';
                        }

                        let percentage = '';

                        if (type == 'percentage') {
                            percentage = '%';
                        }

                        return `<span class="text-${value < 0 ? 'danger': 'success'}" style="font-size: 10px;">`+plus+value?.toLocaleString('en')+percentage+' '+icon+'</span>';
                    }

                    $('#total_sales-sales-metric-card .sales-metric-card-value').html(`${parseFloat(res.total_sales.value)?.toLocaleString('en')} ${getDiffLabel(res.total_sales.difference, res.total_sales.difference_type)}`)
                    $('#sales_count-sales-metric-card .sales-metric-card-value').html(`${parseFloat(res.sales_count.value)?.toLocaleString('en')} ${getDiffLabel(res.sales_count.difference, res.sales_count.difference_type)}`)
                    $('#items_sold-sales-metric-card .sales-metric-card-value').html(`${parseFloat(res.items_sold.value)?.toLocaleString('en')} ${getDiffLabel(res.items_sold.difference, res.items_sold.difference_type)}`)
                    $('#average_sales-sales-metric-card .sales-metric-card-value').html(`${parseFloat(res.average_sales.value)?.toLocaleString('en')} ${getDiffLabel(res.average_sales.difference, res.average_sales.difference_type)}`)

                    $('#total_sales-sales-metric-card').removeClass('placeholder w-100')
                    $('#sales_count-sales-metric-card').removeClass('placeholder w-100')
                    $('#items_sold-sales-metric-card').removeClass('placeholder w-100')
                    $('#average_sales-sales-metric-card').removeClass('placeholder w-100')

                    $('#total_sales-sales-metric-card .sales-metric-card-icon').html('<i class="ti ti-coin"></i>')
                    $('#sales_count-sales-metric-card .sales-metric-card-icon').html('<i class="ti ti-shopping-cart"></i>')
                    $('#items_sold-sales-metric-card .sales-metric-card-icon').html('<i class="ti ti-list-numbers"></i>')
                    $('#average_sales-sales-metric-card .sales-metric-card-icon').html('<i class="ti ti-chart-area-line"></i>')
                },
            });
        }
    }

    const getSalesOverview = () => {
        const inputSalesOverviewFilterMonth = $('#input-sales-overview-filter-month');
        const inputSalesOverviewFilterYear = $('#input-sales-overview-filter-year');

        var salesOverviewChartOpt = {
            chart: {
                type: 'area',
                height: 350,
                toolbar: {
                    show: false
                }
            },
            dataLabels: {
                enabled: false,
            },
            stroke: {
                curve: 'smooth',
            },
            legend: {
                horizontalAlign: 'left',
            },
            yaxis: {
                labels: {
                    formatter: function (val) {
                      return parseFloat(val)?.toLocaleString('en')
                    },
                },
            },
            xaxis: {
                labels: {
                    formatter: function (val) {
                      return moment(val).format('DD MMM');
                    },
                },
            },
        }

        if (inputSalesOverviewFilterYear.val()) {

            let params = {
                year: inputSalesOverviewFilterYear.val()
            }

            if (inputSalesOverviewFilterMonth.val()) {
                params.month = inputSalesOverviewFilterMonth.val();
            }
            
            params = new URLSearchParams(params);

            $.ajax({
                url: BASE_URL + '/api/v1/dashboard/sales_overview_chart?'+params?.toString(),
                type: "GET",
                dataType: "json",
                headers: {
                    'Authorization': TOKEN,
                    'company-id': COMPANY_ID,
                },
                beforeSend: () => {
                },
                success: function(res) {
                    salesOverviewChartOpt.series = res.series;
                    salesOverviewChartOpt.labels = res.categories;

                    if (salesOverviewChart) {
                        salesOverviewChart.destroy()
                    }

                    salesOverviewChart = new ApexCharts(document.querySelector('#sales-overview-chart'), salesOverviewChartOpt)
                    salesOverviewChart.render();
                },
            });
        }
    }

    const getRecentTransaction = () => {
        $.ajax({
            url: BASE_URL + '/api/v1/dashboard/recent_transactions',
            type: "GET",
            dataType: "json",
            headers: {
                'Authorization': TOKEN,
                'company-id': COMPANY_ID,
            },
            beforeSend: () => {
            },
            success: function(res) {
                let html = ''
                
                if (res?.data?.length > 0) {
                    res?.data?.forEach((item, idx) => {
                        html += `<div class="rich-list-item px-0 py-2">
                            <div class="rich-list-content">
                                <a href="#!" class="rich-list-title text-body fs-14 fw-semibold">${item.number}</a>
                                <span class="rich-list-subtitle" style="font-size: 12px">Pembeli: ${item.customer_name}</span>
                                <span class="rich-list-subtitle" style="font-size: 12px">Kasir: ${item.cashier_name}</span>
                            </div>
                            <div class="rich-list-append text-end flex-column align-items-end">
                                <span class="fw-semibold text-success text-end">${parseFloat(item.total ?? 0)?.toLocaleString('en')}</span>
                                <span class="rich-list-subtitle" style="font-size: 12px">${moment(item.updated_at).format('DD MMM YYYY HH:mm:ss')}</span>
                            </div>
                        </div>`
                    });
                    
                    $('#recent-transaction-list').html(html);
                    
                } else {
                    html += `<div class="d-flex align-items-center justify-content-center h-100">
                        <span class="text-muted">Belum ada transaksi</span>
                    </div>`
                        
                    $('#recent-transaction-list').html(html)
                }

            },
        });
    }

    $(document).on('select2:select',  '#input-filter-period', function() {
        getSalesMetric();
    });
    
    $(document).on('select2:select select2:clear', '#input-sales-overview-filter-month, #input-sales-overview-filter-year', function () {
        getSalesOverview();
    })

    getSalesMetric();
    getSalesOverview();
    getRecentTransaction();

</script>
@endsection