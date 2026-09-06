<div class="modal fade" id="catalog-modal" tabindex="-1" aria-labelledby="catalogModalLabel">
    <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <input type="hidden" id="input-catalog-product_id">
            <div class="modal-header">
                <h5 class="modal-title" id="product-modal-title">Pilih Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="input-product_catalog_id">
                <div class="d-flex gap-3 mb-5">
                    <div id="product-thumbnail">
                        <div class="avatar avatar-label-primary" style="width: 5rem; height: 5rem; font-size: 35px;">
                            <span class="mdi mdi-file-image-outline"></span>
                        </div>
                        {{-- <img src="https://picsum.photos/200/500" alt="" class="rounded-2" style="object-fit: cover; object-position: center; width: 6rem; height: 6rem;"> --}}
                    </div>
                    <div class="d-block">
                        <p class="mb-1 text-muted fw-bold" style="font-size: 12px;" id="product-catalog-info-code"></p>
                        <h5 class="mb-1" id="product-catalog-info-name"></h5>
                        <h6 id="product-catalog-info-price"></h6>
                    </div>
                </div>
                <div id="catalog-select-products-container" class="mb-3 d-grid">
                    {{-- <h6>Ukuran</h6>
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3" style="--bs-gutter-x: 6px">
                        <div class="col">
                            <button type="button" class="btn btn-lg w-100 select-variant-toggle active">Click me</button>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-lg w-100 select-variant-toggle">Click me</button>
                        </div>
                    </div> --}}
                </div>
                <div class="row">
                    <div class="col-6 d-flex justify-content-center align-items-center">
                        <input class="form-control no-spinners" id="input-product-qty" type="number" value="1">
                    </div>
                    <div class="col-6 d-flex justify-content-center align-items-center">
                        <select id="input-product-unit" class="form-select" style="width: 100%">
                            <option value="">Pilih Satuan</option>
                        </select>
                    </div>
                   {{-- <h6 class="mb-0">Jumlah Pesanan</h6> --}}
                </div>
            </div>
            <div class="modal-footer">
                <div class="d-flex gap-3 w-100">
                    <button type="button" class="btn btn-danger btn-shortcut w-50" style="justify-content: center" data-bs-dismiss="modal">Batal <span class="shortcut-text-info">Esc</span></button>
                    <button type="button" class="btn btn-success btn-shortcut w-50" style="justify-content: center" id="variant-submit-toggle" disabled>Simpan <span class="shortcut-text-info">Enter</span></button> 
                </div>
            </div>
        </div>
    </div>
</div>