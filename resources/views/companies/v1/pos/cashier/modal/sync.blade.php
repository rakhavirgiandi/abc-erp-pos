<div class="modal fade" id="sync-modal" tabindex="-1" aria-labelledby="syncModalLabel">
    <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sync-modal-title">Pilih data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-4">
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input sync-check" value="product" type="checkbox" id="sync-product-check"><label class="form-check-label" for="sync-product-check">Produk</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input sync-check" type="checkbox" value="product_stock" id="sync-stock-product-check"><label class="form-check-label" for="sync-stock-product-check">Stok Produk</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input sync-check" value="transaction" type="checkbox" id="sync-transaction-check"><label class="form-check-label" for="sync-transaction-check">Transaksi</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input sync-check" value="customer" type="checkbox" id="sync-customer-check"><label class="form-check-label" for="sync-customer-check">Customer</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input sync-check" value="currencies" type="checkbox" id="sync-currencies-check"><label class="form-check-label" for="sync-currencies-check">Mata Uang</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input sync-check" value="branch" type="checkbox" id="sync-branch-check"><label class="form-check-label" for="sync-branch-check">Cabang</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input sync-check" value="warehouse" type="checkbox" id="sync-warehouse-check"><label class="form-check-label" for="sync-warehouse-check">Gudang</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input sync-check" value="reward_point_and_point_rule" type="checkbox" id="sync-reward_point_and_point_rule-check"><label class="form-check-label" for="sync-reward_point_and_point_rule-check">Hadiah Point dan Peraturan Poin</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input sync-check" value="payment_method" type="checkbox" id="sync-payment_method-check"><label class="form-check-label" for="sync-payment_method-check">Metode Pembayaran</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input sync-check" value="settings" type="checkbox" id="sync-settings-check"><label class="form-check-label" for="sync-settings-check">Pengaturan</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input sync-check" value="permissions" type="checkbox" id="sync-permissions-check"><label class="form-check-label" for="sync-permissions-check">Hak Akses</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input sync-check" value="accounting_master" type="checkbox" id="sync-accounting_master-check"><label class="form-check-label" for="sync-accounting_master-check">COA</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="sync-all-check"><label class="form-check-label" for="sync-all-check">Semua</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="d-flex gap-3 w-100">
                    <button type="button" class="btn btn-danger btn-shortcut w-50" style="justify-content: center" data-bs-dismiss="modal">Batal <span class="shortcut-text-info">Esc</span></button>
                    <button type="button" class="btn btn-success btn-shortcut w-50" style="justify-content: center" id="sync-submit-toggle">Mulai <span class="shortcut-text-info">Enter</span></button> 
                </div>
            </div>
        </div>
    </div>
</div>