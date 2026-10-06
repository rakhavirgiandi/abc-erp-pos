


@extends('companies.v1.layouts.guest.index')

@section('style')
@endsection

@section('content')
<div class="authentication-overlay">
    <div class="container">
        <div class="card w-100 shadow-lg">
            <div class="card-body">
                <form id="main-form">
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label">Port</label>
                                <input type="number" name="database[port]" class="form-control" placeholder="xxxx" value="{{ config('services.pgsql.port') }}">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label">Database</label>
                                <input type="input" name="database[database]" class="form-control" value="{{ config('services.pgsql.database') }}">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="input" name="database[username]" class="form-control" value="{{ config('services.pgsql.superuser') }}">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="database[password]" class="form-control" value="{{ config('services.pgsql.password') }}">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ url('config') }}" class="btn btn-danger">Back</a>
                                <button type="button" class="btn btn-success" id="check-db-connection">Test Connection</button>
                                <button type="button" class="btn btn-primary" id="submit-button">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).on('submit', '#main-form',  function (e) {
        e.preventDefault();
        let formData = new FormData(this);
        showConfirmAlert().then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'post',
                    url: BASE_URL + "/api/config/post",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    beforeSend: function () {
                        showLoadingAlert();
                    },
                    success: function (res) {

                        Swal.fire({
                            title: 'Info',
                            html: res.message+'<br>Aplikasi akan keluar otomatis.',
                            icon: 'info'
                        })

                        setTimeout(() => {
                            $.post(BASE_URL+'/native/window/close', { window_id: window.Native?.app?.id ?? 'main' });
                        }, 3000)
                    }
                })
            }
        });
    });

    $(document).on('click', '#submit-button', function () {
        $('#main-form').trigger('submit');
    });

    $(document).on('click', '#check-db-connection', function() {
        let formData = new FormData($('#main-form')[0]);

        $.ajax({
            type: 'post',
            url: BASE_URL + "/api/config/db-check",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            beforeSend: function () {
                showLoadingAlert();
            },
            success: function (res) {
                Swal.fire({
                    icon: "success",
                    title: "Success",
                    html: res.message,
                    confirmButtonColor: 'var(--bs-success)',
                    confirmButtonText: 'OK',
                });
            },
            error: generalAjaxErrorHandler
        })
    });



</script>
@endsection