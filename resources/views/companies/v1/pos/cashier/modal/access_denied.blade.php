<div class="modal fade" id="access-denied-modal" tabindex="-1" aria-labelledby="authenticate-label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header justify-content-center">
        <h1 class="modal-title fs-5" id="access-denied-modal-label">Masukan Autentikasi Kepala Toko</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert-container"></div>
        <form id="access-denied-form">
            <input type="hidden" name="branch_id" value="{{ config('user_companies.branch_id') ? config('user_companies.branch_id') : config('general_settings.default_branch') }}">
            <div class="mb-3">
                <label for="input-supervisor-auth-user_id" class="form-label">Pilih Akun</label>
                <select name="user_id" class="form-select form-select-lg" id="input-supervisor-auth-user_id"></select>
            </div>
            <div class="mb-3">
                <label for="input-supervisor-auth-password" class="form-label">Password</label>
                <input type="password" class="form-control" id="input-supervisor-auth-password" name="password">
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary w-100" style="padding: .75rem 1rem" id="authenticate-supervisor-submit-toggle">Submit</button>
      </div>
    </div>
  </div>
</div>