<footer class="footer">
    <div class="w-50 gap-1 d-flex align-items-center">
        <a href="#" class="logo logo-light">
            <span class="logo-lg">
                <img src="{{ asset('assets/images/logo-pos.png') }}" alt="{{ config('settings.company_name') }}" height="25">
            </span>
        </a>
        <span class="vr mx-1" style="height: 30px; color: rgb(255 255 255 / 0.3);"></span>
        <h3 class="counter-name-info">{{ config('user_companies.branch_name') ? config('user_companies.branch_name') : config('general_settings.branch_name') }}</h3>
    </div>
    <div class="w-50 gap-1 d-flex align-items-center justify-content-end">
        <div class="btn-footer-group d-flex gap-2">
            <button type="button" class="btn btn-secondary" id="stock-toggle">Stock </button>
            <button type="button" class="btn btn-warning" data-shortcut="f8" id="histories-toggle"><span class="text-button">Histories</span><span class="shortcut-text-info">F8</span></button>
            <button type="button" class="btn btn-danger" data-shortcut="f9" id="void-toggle" disabled>Void <span class="shortcut-text-info">F9</span></button>
            <button type="button" class="btn btn-success" id="payment-step-toggle" data-shortcut="f10" disabled>Payment <span class="shortcut-text-info">F10</span></button>
        </div>
    </div>
</footer>