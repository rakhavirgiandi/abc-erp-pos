@extends('companies.v1.layouts.main')

@section('title', $title)

@section('style')
@endsection

@section('content')
<div id="layout-wrapper">
    <div class="card shadow-none rounded-0 mb-0" style="min-height: calc(100vh - var(--bs-app-header-height));">
        <div class="card-body h-100">
            <form id="main-form" class="d-flex flex-column h-100">
                <div class="row flex-fill">
                    <div class="col-3">
                        <div class="mb-3">
                            <label class="form-label">Printer</label>
                            @if (config('services.is_onpremise'))
                                <div class="d-flex gap-3">
                                    <div class="flex-fill">
                                        <select name="pos_printer_selected_printer" class="form-select form-select-lg form-select2" id="input-selected_printer"></select>
                                    </div>
                                    <button type="button" id="get-printer-toggle" class="btn btn-icon btn-secondary"><i class="fas fa-sync-alt"></i></button>
                                </div>
                                <div class="form-text" id="printer-text-status">Try to get all printer devices...</div>
                            @else
                                <input type="text" name="pos_printer_selected_printer" class="form-control" value="{{ config('local_user_settings.pos_printer_selected_printer') }}">
                            @endif
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ukuran Kertas Printer</label>
                            <select name="pos_printer_paper_size" class="form-select form-select-lg form-select2" id="input-printer_size">
                                <option value="">Pilih Ukuran</option>
                                <option value="58" {{ config('local_user_settings.pos_printer_paper_size') == '58' ? 'selected' : '' }}>58mm</option>
                                <option value="75" {{ config('local_user_settings.pos_printer_paper_size') == '75' ? 'selected' : '' }}>75mm</option>
                                <option value="80" {{ config('local_user_settings.pos_printer_paper_size') == '80' ? 'selected' : '' }}>80mm</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                </div>
            </form>
        </div>
        <div class="card-footer border-0">
            <div class="w-100 d-flex justify-content-end">
                <button type="button" id="submit-toggle" class="btn btn-lg btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>

    $('.form-select2').select2();

    let  selectedPrinter = '{{ config('local_user_settings.pos_printer_selected_printer') }}';

    console.log('{{ config('database.connections.pgsql_companies.database') }}');
    

    const getAllPrinterDevices = () => {

        $.ajax({
            url: BASE_URL + '/api/v1/pos/printer_connected',
            type: 'GET',
            headers: {
                'Authorization': TOKEN,
                'company-id': COMPANY_ID
            },
            dataType: 'JSON',
            async: false,
            beforeSend: function() {
                $('#printer-text-status').html('Try to get all connected printer devices...');
                $('#printer-text-status').removeClass('text-success')
                $('#printer-text-status').removeClass('text-danger');
            },
            success: function(res) {
                let html = '';

                let isConnected = false;

                $.each(res, function(index, item) {
                    if (item.name === selectedPrinter) {
                        isConnected = true;
                        html += '<option value="' + item.name + '" selected>' + item.displayName + '</option>'
                    } else {
                        html += '<option value="' + item.name + '">' + item.displayName + '</option>'
                    }

                })
                
                if (!isConnected) {
                    $('#printer-text-status').html('Device not found');
                    $('#printer-text-status').addClass('text-danger');

                    if (!selectedPrinter) {
                        html = '<option value="" selected>Pilih Printer</option>' + html;
                    } else if (selectedPrinter) {
                        html = '<option value="'+selectedPrinter+'" selected>'+selectedPrinter+'</option>' + html;
                    }


                } else {
                    $('#printer-text-status').html('Device '+selectedPrinter+' is ready to be connected');
                    $('#printer-text-status').addClass('text-success');
                }

                $('#input-selected_printer').html(html)
            },
        });
    }

    if ('{{ !!config('services.is_onpremise') }}') {   
        getAllPrinterDevices();
    }
        
    $(document).on('click', '#get-printer-toggle', function () {
        selectedPrinter = $('#input-selected_printer').val()
        getAllPrinterDevices()
    });
        
    $(document).on('change', '#input-selected_printer', function () {
        $('#printer-text-status').html('');
    });

    $(document).on('submit', '#main-form', function (e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            type: 'POST',
            url: BASE_URL + '/api/v1/local_user_settings',
            headers: { 
                'Authorization': TOKEN,
                'company-id': COMPANY_ID
            },
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            beforeSend: function() {
                showLoading('Harap Menunggu!', 'Sedang mengirim data');
            },
            success: function(res) {
                Swal.close();
                showAlertOnSubmit(res, '', '', '');
            },
            error: function() {
                showFailedAlert('Error');
            }
        });
    });

    $(document).on('click', '#submit-toggle', function () {
        $('#main-form').trigger('submit');
    }); 

</script>
@endsection