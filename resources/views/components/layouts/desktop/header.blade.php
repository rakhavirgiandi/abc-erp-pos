<div id="app-header-win" class="app-header-win">
    <div class="app-header-win-drag">
        <img src="{{ asset('assets/images/logo-box.ico') }}" class="app-logo" alt="logo">
        <div class="app-header-win-menu">
            <div class="win-menu" data-menu="file">
                <button type="button" class="btn-win-menu">File</button>
                <div class="win-menu-dropdown">
                    <button type="button" class="win-menu-item" id="menu-print">Print</button>
                    <button type="button" class="win-menu-item" id="menu-reload">Reload</button>
                    <div class="win-menu-divider"></div>
                    <button type="button" class="win-menu-item" id="menu-exit">Exit</button>
                </div>
            </div>
            <div class="win-menu" data-menu="view">
                <button type="button" class="btn-win-menu">View</button>
                <div class="win-menu-dropdown">
                    <button type="button" class="win-menu-item" id="menu-fullscreen">Toggle Fullscreen</button>
                    <button type="button" class="win-menu-item" id="menu-zoom-in">Zoom In</button>
                    <button type="button" class="win-menu-item" id="menu-zoom-out">Zoom Out</button>
                    <button type="button" class="win-menu-item" id="menu-zoom-reset">Reset Zoom</button>
                    <div class="win-menu-divider"></div>
                    <button type="button" class="win-menu-item" id="menu-devtools">Toggle DevTools</button>
                </div>
            </div>
            <div class="win-menu" data-menu="help">
                <button type="button" class="btn-win-menu">Help</button>
                <div class="win-menu-dropdown">
                    <button type="button" class="win-menu-item" id="menu-about">About</button>
                    @if (request()->routeIs('web.login'))
                        <button type="button" class="win-menu-item" id="menu-check-update">Check For Update</button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="app-header-win-controls">
        <button type="button" class="win-btn" title="Minimize" id="win-minimize">
            <svg width="10" height="10" viewBox="0 0 10 10"><rect width="10" height="1" y="5" fill="currentColor"/></svg>
        </button>
        <div id="screen-size-button">
        </div>
        <button type="button" class="win-btn" title="Close" id="win-close">
            <svg width="10" height="10" viewBox="0 0 10 10"><path d="M0,0 L10,10 M10,0 L0,10" stroke="currentColor" stroke-width="1.2"/></svg>
        </button>
    </div>
</div>

@once
    @push('script')
        <script>
            function isWindowMaximized() {
                return window.outerWidth >= screen.availWidth && window.outerHeight >= screen.availHeight;
            }

            function updateMaximizeIcon() {
                const maximized = isWindowMaximized();
                if (maximized) {
                    $('#screen-size-button').html(
                        `<button type="button" class="win-btn" title="Restore" id="win-restore">
                            <svg width="15" height="15" viewBox="0 0 10 10">
                            <path
                                d="M3 3.5V1.5H8.5V7H6.5M6.5 3.5H1.5V8.5H6.5V3.5Z"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1"
                            />
                            </svg>
                        </button>`
                    )
                } else {
                    $('#screen-size-button').html(
                        `
                            <button type="button" class="win-btn" title="Maximize" id="win-maximize">
                                <svg width="10" height="10" viewBox="0 0 10 10"><rect width="9" height="9" x="0.5" y="0.5" fill="none" stroke="currentColor"/></svg>
                            </button>
                        `
                    )
                }
            }

            $(window).on('resize', updateMaximizeIcon);
            $(document).ready(updateMaximizeIcon);

            $(document).on('click', '#win-minimize', function () {
                $.post(BASE_URL+'/native/window/minimize', { window_id: window.Native?.app?.id ?? 'main' });
            });

            $(document).on('click', '#win-maximize', function () {
                $.post(BASE_URL+'/native/window/maximize', { window_id: window.Native?.app?.id ?? 'main' });
            });

            $(document).on('click', '#win-restore', function () {
                $.post(BASE_URL+'/native/window/restore', { window_id: window.Native?.app?.id ?? 'main' });
            });

            $(document).on('click', '#win-close', function () {
                $.post(BASE_URL+'/native/window/close', { window_id: window.Native?.app?.id ?? 'main' });
            });

            $(document).on('dblclick', '#app-header-win-drag', function () {
                $.post(BASE_URL+'/native/window/maximize', { window_id: window.Native?.app?.id ?? 'main' });
            });

            $(document).on('click', '#menu-devtools', function () {
                $.post(BASE_URL+'/native/window/devtools/toggle', { window_id: window.Native?.app?.id ?? 'main' });
            })

            $(document).on('click', '.btn-win-menu', function (e) {
                e.stopPropagation();
                const $menu = $(this).closest('.win-menu');
                const isOpen = $menu.hasClass('open');

                $('.win-menu').removeClass('open');

                if (!isOpen) {
                    $menu.addClass('open');
                }
            });

            $(document).on('mouseenter', '.win-menu', function () {
                if ($('.win-menu.open').length && !$(this).hasClass('open')) {
                    $('.win-menu').removeClass('open');
                    $(this).addClass('open');
                }
            });

            $(document).on('click', function () {
                $('.win-menu').removeClass('open');
            });

            // Tutup dropdown setelah item menu diklik
            $(document).on('click', '.win-menu-item', function () {
                $('.win-menu').removeClass('open');
            });

            // ----- Actions -----

            $(document).on('click', '#menu-print', function () {
                window.print();
            });

            $(document).on('click', '#menu-reload', function () {
                location.reload();
            });

            $(document).on('click', '#menu-exit', function () {
                $.post(BASE_URL+'/native/window/close', { window_id: window.Native?.app?.id ?? 'main' });
            });

            $(document).on('click', '#menu-fullscreen', function () {
                $.post(BASE_URL+'/native/window/maximize', { window_id: window.Native?.app?.id ?? 'main' });
            });

            let zoomLevel = 100;
            $(document).on('click', '#menu-zoom-in', function () {
                zoomLevel = Math.min(zoomLevel + 10, 200);
                document.body.style.zoom = zoomLevel + '%';
            });
            $(document).on('click', '#menu-zoom-out', function () {
                zoomLevel = Math.max(zoomLevel - 10, 50);
                document.body.style.zoom = zoomLevel + '%';
            });
            $(document).on('click', '#menu-zoom-reset', function () {
                zoomLevel = 100;
                document.body.style.zoom = '100%';
            });

            $(document).on('click', '#menu-about', function () {
                alert('{{ config('app.name') }}\nVersion {{ config('nativephp.version', '1.0.0') }}');
            });
        </script>
    @endpush
@endonce