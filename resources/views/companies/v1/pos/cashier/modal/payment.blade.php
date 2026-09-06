<div class="modal fade overflow-y-hidden" id="payment-modal" tabindex="-1" aria-labelledby="productModalLabel">
    <div class="modal-dialog modal-fullscreen modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon btn-label-light me-3" style="border: 0px !important" data-bs-dismiss="modal"><i class="fa fa-arrow-left"></i></button>
                <h5 class="modal-title fs-4" id="payment-modal-title">Pembayaran</h5>
            </div>
            <div class="modal-body overflow-y-hidden">
                <form id="main-form"></form>
                <div class="row h-100" style="--bs-gutter-y: 16px;">
                    <div class="col-12" style="height: calc(20% - .5rem)">
                        <div class="card bg-secondary mb-0 h-100">
                            <div class="card-body p-4">
                                <div class="w-100 text-center">
                                    <h6 class="text-uppercase text-white">Total Tagihan</h6>
                                    <h1 class="text-white m-0" id="total-payment-info">0</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12" style="height: calc(80% - .5rem)">
                        <div class="d-flex gap-4 overflow-hidden h-100">
                            <div style="flex: 2">
                                <div class="card mb-0 h-100">
                                    <div class="p-4">
                                        <h5 class="mb-0">Bonus yang Digunakan</h5>
                                    </div>
                                    <div class="px-4 h-100 overflow-y-auto">
                                        <div class="d-flex flex-column gap-3 h-100" id="reward-point-applied-list">
                                        </div>
                                    </div>
                                    <div class="p-4 pt-0">
                                        <hr>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-warning fw-bold text-uppercase"><i class="fas fa-coins"></i> Poin yang Didapat</span>
                                            <span class="fw-bold" id="bonus-point-label">0</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div style="flex: 4" class="overflow-x-hidden overflow-y-auto">
                                <div class="card mb-0" style="min-height: 100%;">
                                    <div class="card-body d-flex flex-column">
                                        <div class="flex-fill">
                                            <h6 class="text-body-secondary mb-2">Metode Pembayaran</h6>
                                            <div class="mb-3">
                                                <div class="mb-3">
                                                    <div class="nav nav-pills" id="payment-method-tab" role="tablist">
                                                        <a class="nav-item nav-link active" id="payment-method-cash-tab-toggle" data-bs-toggle="tab" href="#payment-method-cash-tab" aria-selected="true" role="tab">Tunai</a>
                                                        <a class="nav-item nav-link" id="payment-method-edc-tab-toggle" data-bs-toggle="tab" href="#payment-method-edc-tab" aria-selected="false" tabindex="-1" role="tab">Metode Lainnya</a>
                                                    </div>
                                                </div>
                                                <div class="tab-content flex-fill d-flex flex-column" id="payment-method-tab-content">
                                                    <div class="tab-pane fade show active" id="payment-method-cash-tab" role="tabpanel" aria-labelledby="#payment-method-cash-tab">
                                                        <div class="w-100 position-relative mb-3">
                                                            <input type="text" class="form-control fw-bold fs-3" id="input-total_payment" style="height: 50px; padding: .5625rem 1rem .5625rem 3.125rem;letter-spacing: 1px">
                                                            <h3 class="position-absolute top-50 start-0 translate-middle-y ms-3 text-body-secondary fw-bold fs-3">Rp</h3>
                                                        </div>
                                                        <div class="d-flex flex-wrap gap-2" id="payment-rounded-container">
                                                            {{-- <button type="button" class="btn btn-light insert-payment-toggle" data-value="50000">50,000</button>
                                                            <button type="button" class="btn btn-light insert-payment-toggle" data-value="100000">100,000</button>
                                                            <button type="button" class="btn btn-light insert-payment-toggle" data-value="200000">200,000</button>
                                                            <button type="button" class="btn btn-light insert-payment-toggle" data-value="exact">Uang Pas</button> --}}
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade flex-fill" id="payment-method-edc-tab" role="tabpanel" aria-labelledby="#payment-method-edc-tab-toggle">
                                                        <div class="d-flex flex-column flex-fill">
                                                            <div class="h-100 position-relative mb-3">
                                                                <input type="text" class="form-control fw-bold fs-3" id="input-total_payment_edc" style="height: 50px; padding: .5625rem 1rem .5625rem 3.125rem;letter-spacing: 1px">
                                                                <h3 class="position-absolute top-50 start-0 translate-middle-y ms-3 text-body-secondary fw-bold fs-3">Rp</h3>
                                                            </div>
                                                            <div class="d-flex flex-column flex-fill overflow-x-hidden overflow-y-auto" style="max-height: 170px">
                                                                <div class="row flex-fill" style="row-gap: calc(var(--bs-gutter-x) * 1); column-gap: row-gap: calc(var(--bs-gutter-x) * 1);" id="select-payment-method-container">

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-100 p-3 bg-info-subtle rounded-2 mb-0">
                                            <div class="w-100 d-flex justify-content-between">
                                                <span style="font-size: 12px">Subtotal</span>
                                                <span style="font-size: 12px" class="fw-bold" id="subtotal-payment-info">0</span>
                                            </div>
                                            <div class="w-100 d-flex justify-content-between">
                                                <span style="font-size: 12px">Tax</span>
                                                <span style="font-size: 12px" class="fw-bold" id="total-tax-payment-info">0</span>
                                            </div>
                                            <div class="w-100 d-flex justify-content-between">
                                                <span style="font-size: 12px" class="text-success">Diskon</span>
                                                <span style="font-size: 12px" class="fw-bold text-success" id="total-discount-payment-info">0</span>
                                            </div>
                                            <div class="w-100 d-flex justify-content-between">
                                                <span style="font-size: 12px" class="text-success">Potongan Dari Poin</span>
                                                <span style="font-size: 12px" class="fw-bold text-success" id="total-discount-reward-point-payment-info">0</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="d-flex gap-3 w-100">
                    <div class="p-2 bg-danger-subtle border border-danger rounded-3" style="height: 60px; width: 50%;">
                        <p class="mb-0 text-danger fw-bold" style="font-size: 10px">KEMBALIAN</p>
                        <div class="w-100 d-flex align-items-center">
                            <h3 class="text-body mb-0" id="change-payment-info">0</h3>
                        </div>
                    </div>
                    <div class="d-flex gap-3 ms-auto align-self-center" style="height: 50px;">
                        <button type="button" class="btn btn-outline-light btn-shortcut" style="justify-content: center" data-bs-dismiss="modal">Batal <span class="shortcut-text-info" style="padding: 0.25rem 0.5rem !important">Esc</span></button>
                        <button type="button" class="btn btn-success btn-shortcut" style="justify-content: center" id="submit-payment-toggle">Bayar dan Cetak <span class="shortcut-text-info" style="padding: 0.25rem 0.5rem !important">Enter</span></button> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>