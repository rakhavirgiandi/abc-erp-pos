<div class="modal fade" id="redeem-modal" tabindex="-1" aria-labelledby="redeemModalLabel">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="redeem-modal-title">Penukaran Poin</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 d-flex flex-column">
                    <div class="p-0">
                        <div class="w-100 h-100 p-4 bg-info-subtle">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex gap-4">
                                    <div class="p-2 bg-white rounded-3 text-center" style="border: 1px solid var(--bs-info);">
                                        <h6 style="font-size: 8px" class="fw-bold text-info mb-0">TOTAL POINT</h6>
                                        <h3 class="fw-bold text-body mb-0" id="redeem-total-point-placeholder"></h3>
                                    </div>
                                    <div class="align-self-center">
                                        <h5 class="mb-0" id="redeem-customer-name-placeholder">John Doe</h5>
                                        <h6 class="mb-0 text-body-secondary" id="redeem-contact-group-name-placeholder">Gold Member</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                        <div class="row w-100 h-100 p-4">
                            <div class="col-6">
                                <h5 class="mb-3">Tukar Jadi Diskon</h5>
                                <label for="#" class="form-label">Jumlah Poin</label>
                                <input type="text" class="form-control form-control-lg mb-3" id="input-point">
                                <input type="hidden" id="input-discount-point">
                                <div class="w-100 p-3 bg-success-subtle text-success fw-bold fs-5 rounded-2 d-flex justify-content-between">
                                    <span>Total Diskon</span>
                                    <span id="redeem-total-discount-placeholder">0</span>
                                </div>
                            </div>
                            {{-- <div class="flex-fill row">
                                <div class="col-12">
                                    <div class="row mb-3 overflow-auto" id="point-toggle-container" style="--bs-gutter-x: 8px; --bs-gutter-y: 8px; max-height: 150px">
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                    {{-- <div class=" p-0">
                        <div class="w-100 p-4 h-100">
                            <h5 class="mb-3">Tukar Jadi Diskon</h5>
                            <div class="h-40 overflow-x-hidden overflow-y-auto" id="reward-point-product-item-wrapper">
                                <div class="row h-100" style="--bs-gutter-x: 16px; --bs-gutter-y: 16px" id="reward-point-product-item-container">
                                    <div class="col-6">
                                        <div class="reward-point-product-item">
                                            <h6 class="name-placeholder">Product 1</h6>
                                            <h6 class="total-point-placeholder">100 Poin</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
            </div>
            <div class="modal-footer">
                <div class="d-flex gap-3 w-100 justify-content-end">
                    <button type="button" class="btn btn-danger btn-shortcut" style="justify-content: center" data-bs-dismiss="modal">Batal <span class="shortcut-text-info" style="padding: 0.25rem 0.5rem !important">Esc</span></button>
                    <button type="button" class="btn btn-success btn-shortcut" style="justify-content: center" id="submit-redeem-toggle">Pilih <span class="shortcut-text-info" style="padding: 0.25rem 0.5rem !important">Enter</span></button> 
                </div>
            </div>
        </div>
    </div>
</div>