<div class="modal fade" id="taxes-modal" tabindex="-1" aria-labelledby="taxesModalLabel">
    <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="taxes-modal-title">Pajak</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="input-order_item_id">
                <div style="width: 100%; position: relative;">
                    <input type="text" class="form-control" id="input-search-taxes" style="padding-left: 2rem;" placeholder="Cari Nama Pajak">
                    <i class="mdi mdi-magnify position-absolute top-50 start-0 translate-middle-y ms-3" style="font-size: 18px; color: var(--bs-form-control-placeholder-color);"></i>
                </div>
                <div id="taxes-table-container">
                    <table class="table table-master m-0" id="taxes-table">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Persentase Pajak</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <div class="d-flex gap-3 w-100 justify-content-end">
                    <button type="button" class="btn btn-danger btn-shortcut" style="justify-content: center" data-bs-dismiss="modal">Batal <span class="shortcut-text-info" style="padding: 0.25rem 0.5rem !important">Esc</span></button>
                    <button type="button" class="btn btn-success btn-shortcut" style="justify-content: center" id="submit-taxes-toggle">Simpan <span class="shortcut-text-info" style="padding: 0.25rem 0.5rem !important">Enter</span></button> 
                </div>
            </div>
        </div>
    </div>
</div>