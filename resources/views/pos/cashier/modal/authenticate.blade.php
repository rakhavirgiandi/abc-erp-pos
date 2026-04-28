<div class="modal fade" id="authenticate-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="authenticate-label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header justify-content-center">
        <h1 class="modal-title fs-5" id="authenticate-modal-label">Enter Login Password</h1>
      </div>
      <div class="modal-body">
        <div class="alert-container"></div>
        <form id="authenticate-form">
            <input type="hidden" name="email" value="{{Session::get('_email')}}">
            <input type="password" class="form-control form-control-lg" id="input-password" name="password">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-lg btn-primary w-100" style="padding: .75rem 1rem" id="authenticate-submit-toggle">Submit</button>
      </div>
    </div>
  </div>
</div>