


@extends('companies.v1.layouts.guest.index')

@section('style')
    <link href="{{ asset('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/libs/flatpickr/flatpickr.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/jquery.json-viewer@1.5.0/json-viewer/jquery.json-viewer.min.css" rel="stylesheet">
    <style>
      .datatable-footer {
          display: grid;
          grid-template-columns: 1fr auto 1fr;
          align-items: center;
          gap: 20px;
          margin-top: 15px;
      }

      .datatable-info {
          justify-self: start;
      }

      .datatable-length {
          justify-self: center;
      }

      .datatable-pagination {
          justify-self: end;
      }

      .datatable-length select {
          margin: 0 5px;
      }
    </style>
@endsection

@section('content')
<div class="authentication-overlay">
    <div class="container-xxl">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <a href="{{ url('config') }}" class="btn btn-primary btn-sm btn-icon">
                                <i class="ti ti-arrow-left"></i>
                            </a>
                            <h5 class="mb-0">Activity Log</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card w-100 shadow-lg">
                    <div class="card-body">
                        <form id="filter-form">
                            <h5>Filter</h5>
                            <div class="mb-3">
                                <label for="input-level" class="form-label">Level</label>
                                <select name="level" id="input-level" class="form-select" multiple>
                                    <option value="error">Error</option>
                                    <option value="info">Info</option>
                                    <option value="warning">Warning</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="input-datetime_from" class="form-label">From</label>
                                <input type="date" id="input-datetime_from" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="input-datetime_to" class="form-label">To</label>
                                <input type="date" id="input-datetime_to" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="input-name" class="form-label">Name</label>
                                <select name="name" id="input-name" class="form-select" multiple>
                                </select>
                            </div>
                            <div class="d-flex justify-content-end w-100">
                                <button class="btn btn-primary" id="submit-filter-button">Filter</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-9">
                <div class="card w-100 shadow-lg">
                    <div class="card-body">
                        <table id="main-table" class="table table-borderless">
                            <thead>
                                <tr>
                                    <th>Level</th>
                                    <th>Time</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('main-modal')
<div class="modal fade" id="detail-modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detail</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <pre id="activity-log-json"></pre>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
    <script src="{{ asset('assets/libs/datatables.net/js/dataTables.min.js')}}"></script>
    <script src="{{ asset('assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js')}}"></script>
    <script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{ asset('assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js')}}"></script>
    <script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js')}}"></script>
    <script src="{{ asset('assets/libs/moment/dist/moment.min.js')}}"></script>
    <script src="{{ asset('assets/libs/select2/js/select2.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery.json-viewer@1.5.0/json-viewer/jquery.json-viewer.min.js"></script>

    <script>

        $('#input-level').select2({
            placeholder: 'Select Level'
        });
        $('#input-name').select2({
            placeholder: 'Select Name'
        });

        flatpickr('#input-datetime_from', {
            enableTime: true,
            altInput: true,
            altFormat: "d-m-Y H:i",
            dateFormat: 'Y-m-d H:i',
            defaultDate: new Date().setHours(0, 0, 0, 0)
        });
        
        flatpickr('#input-datetime_to', {
            enableTime: true,
            altInput: true,
            altFormat: "d-m-Y H:i",
            dateFormat: 'Y-m-d H:i',
            defaultDate: new Date().setHours(23, 59, 0, 0)
        });

        let isShouldSetOptionNames = false;

        let dt;

        const initDatatable = () => {
            dt = $('#main-table').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                searching: false,
                scrollY: '50vh',
                dom:
                '<"table-wrapper"t>' +
                '<"datatable-footer"' +
                    '<"datatable-info"i>' +
                    '<"datatable-length"l>' +
                    '<"datatable-pagination"p>' +
                '>',
                ajax: function (data, callback) {
                    const page = Math.floor(data.start / data.length) + 1;

                    let req = {};

                    if (Array.isArray($('#input-level').val()) && $('#input-level').val()?.length > 0) {
                        req.level = $('#input-level').val().toString();
                    }
                    
                    if ($('#input-datetime_from').val()) {
                        req.datetime_from = $('#input-datetime_from').val();
                    }

                    if ($('#input-datetime_to').val()) {
                        req.datetime_to = $('#input-datetime_to').val();
                    }

                    
                    if (Array.isArray($('#input-name').val()) && $('#input-name').val()?.length > 0) {
                        req.name_log = $('#input-name').val().toString();
                    }
                
                    $.ajax({
                        url: BASE_URL+'/api/activity_logs',
                        type: 'GET',
                        data: {
                            page: page,
                            per_page: data.length,
                            ...req
                        },
                        success: function (response) {

                            if (!isShouldSetOptionNames) {
                                
                                if (response.log_names?.length > 0) {
                                    response.log_names.forEach((item, idx) => {
                                        const option = new Option(CaseConverter.toTitleCase(item), item);
                                        $('#input-name').append(option);
                                    });

                                    isShouldSetOptionNames = true
                                }

                            }

                            callback({
                                draw: data.draw,
                            
                                recordsTotal: response.meta.total,
                                recordsFiltered: response.meta.total,
                            
                                data: response.data
                            });
                        },
                    
                        error: function (xhr) {
                            callback({
                                draw: data.draw,
                                recordsTotal: 0,
                                recordsFiltered: 0,
                                data: []
                            });
                        }
                    });
                },
            
                columns: [
                    {
                        data: 'level',
                        name: 'level',
                        width: '10%',
                        render: (data, type, row) => {
                            if (data) {
                                if (data == 'error') {
                                    return `<span class="text-danger fw-bold"><i class="ti ti-alert-circle"></i>&ensp;${CaseConverter.toTitleCase(data)}</span>`
                                } else if (data == 'info') {
                                    return `<span class="text-info fw-bold"><i class="ti ti-info-circle"></i>&ensp;${CaseConverter.toTitleCase(data)}</span>`
                                } else if (data == 'warning') {
                                    return `<span class="text-warning fw-bold"><i class="ti ti-alert-triangle"></i>&ensp;${CaseConverter.toTitleCase(data)}</span>`
                                } else {
                                    return 'N/A'
                                }
                            }

                            return '';
                        }
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: (data, type, row) => {
                            return moment(data).format('D-MM-YYYY HH:mm:ss');
                        }
                    },
                    {
                        data: 'log_name',
                        name: 'log_name',
                        render: (data, type, row) => {
                            return CaseConverter.toTitleCase(data)
                        }
                    },
                    {
                        data: 'description',
                        name: 'description',
                        width: '30%'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        render: (data, type, row) => {
                            return '<button type="button" class="btn btn-info btn-icon btn-sm" id="button-detail-'+(row?.id)+'" data-id="'+(row?.id)+'"><i class="ti ti-info-circle"></i></button>'
                        },
                        width: '5%'
                    },
                ]
            });
        }

        initDatatable();

        $(document).on('submit', '#filter-form', function (e) {
            e.preventDefault();
            initDatatable();
        });

        $(document).on('click', '#submit-filter-button', function () {
            $('#filter-form').trigger('submit');
        });

        $(document).on('click', '[id^=button-detail]', function (e) {
            const $this = $(this);
            const dataId = $this.attr('data-id');

            $.ajax({
                type: 'GET',
                url: BASE_URL + "/api/activity_logs/"+ dataId,
                beforeSend: function () {
                    showLoadingAlert();
                },
                success: function (res) {
                    Swal.close();
                    $('#activity-log-json').jsonViewer(res);
                    $('#detail-modal').modal('show');
                    
                },
            });
            
        })

    </script>
@endsection