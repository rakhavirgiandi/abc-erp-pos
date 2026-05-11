<div class="modal fade" id="histories-modal" tabindex="-1" aria-labelledby="productModalLabel">
    <div class="modal-dialog modal-fullscreen modal-dialog-centered">
        <div class="modal-content" style="max-height: 100vh">
            <div class="modal-header">
                <button type="button" class="btn btn-icon btn-label-light me-3" style="border: 0px !important" data-bs-dismiss="modal"><i class="fa fa-arrow-left"></i></button>
                <h5 class="modal-title fs-4" id="histories-modal-title">Riwayat</h5>
            </div>
            <div class="modal-body p-0" style="overflow: hidden; display: flex; flex-direction: column">
                <div>
                    <div class="nav nav-lines mb-0 w-100" id="histories-tab" role="tablist">
                        <a class="nav-item nav-link active flex-fill text-center fs-5" style="padding: 1.4rem 1rem" id="histories-pending-tab" data-bs-toggle="tab" href="#histories-pending-tab-content" aria-selected="true" role="tab" data-name="pending">Pending</a>
                        <a class="nav-item nav-link flex-fill text-center fs-5" style="padding: 1.4rem 1rem" id="histories-done-tab" data-bs-toggle="tab" href="#histories-done-tab-content" aria-selected="false" role="tab" tabindex="-1" data-name="done">Done</a>
                        <a class="nav-item nav-link flex-fill text-center fs-5" style="padding: 1.4rem 1rem" id="histories-hold-tab" data-bs-toggle="tab" href="#histories-hold-tab-content" aria-selected="false" role="tab" tabindex="-1" data-name="hold" {{ !!config('user_companies.details')->can('pos.hold-transaction') ? '' : 'disabled' }}>Hold</a>
                    </div>
                    <div style="padding: .75rem 1rem">
                        <div class="position-relative w-100">
                            <input type="text" class="form-control" id="input-search-histories" placeholder="Cari Transaksi">
                            <i class="mdi mdi-magnify position-absolute top-50 end-0 translate-middle-y me-3 text-muted fs-3"></i>
                        </div>
                    </div>
                </div>
                <div class="tab-content h-100 overflow-y-auto" id="histories-tabContent">
                    <div class="tab-pane fade active show w-100 h-100 overflow-y-auto histories-container" id="histories-pending-tab-content" role="tabpanel" aria-labelledby="#histories-pending-tab">
                    </div>
                    <div class="tab-pane fade h-100 w-100 overflow-y-auto histories-container" id="histories-done-tab-content" role="tabpanel" aria-labelledby="#histories-done-tab">
                    </div>
                    <div class="tab-pane fade h-100 w-100 overflow-y-auto histories-container" id="histories-hold-tab-content" role="tabpanel" aria-labelledby="#histories-hold-tab">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>