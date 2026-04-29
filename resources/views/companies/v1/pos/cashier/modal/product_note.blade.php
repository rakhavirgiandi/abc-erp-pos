<div class="modal fade" id="product-note-modal" tabindex="-1" aria-labelledby="productNoteModalLabel">
    <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="product-note-modal-title">Catatan Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="p-3 rounded-2 mb-3" style="background-color: rgba(var(--bs-info-rgb), 0.15); border-color: var(--bs-btn-active-border-color);">
                    <h6 class="mb-1 fw-bold" style="color: var(--bs-info);">Produk Terpilih</h6>
                    <h5 class="mb-0 selected-product-name-placeholder">Indomie</h5>
                </div>
                <div class="mb-3">
                    <label for="input-product-note" class="form-label">Catatan</label>
                    <textarea id="input-product-note" class="form-control" rows="4"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <div class="d-flex gap-3 w-100 justify-content-end">
                    <button type="button" class="btn btn-danger btn-shortcut" style="justify-content: center" data-bs-dismiss="modal">Batal <span class="shortcut-text-info" style="padding: 0.25rem 0.5rem !important">Esc</span></button>
                    <button type="button" class="btn btn-success btn-shortcut" style="justify-content: center" id="submit-product-note-toggle">Simpan <span class="shortcut-text-info" style="padding: 0.25rem 0.5rem !important">Enter</span></button> 
                </div>
            </div>
        </div>
    </div>
</div>