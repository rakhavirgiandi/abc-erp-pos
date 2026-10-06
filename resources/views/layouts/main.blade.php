@php

    $layout_mode = 'default';
    
    if (Request::segment(1) === 'pos') {
        $layout_mode = 'pos';
        if (Request::segment(2) === 'settings') {
            $layout_mode = 'pos-settings';
        }
    }
@endphp

<!DOCTYPE html>
<html lang="en" data-layout="vertical" data-content-width="default" data-bs-theme="light" data-sidebar-color="light" data-topbar-color="light" data-theme-colors="default" dir="ltr" data-mode="{{ $layout_mode }}" data-platform="desktop">
<head>
    <meta charset="utf-8">
    <title>@yield('title') - {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta content="SIMRS & SIMKLINIK" name="description">
    <meta content="ABC Grup Teknologi" name="author">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href="{{ asset('assets/libs/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/bootstrap.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/app.css') }}" id="app-style" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css')}}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />
    @yield('main-style')
</head>

<script type="text/javascript">
    let BASE_URL = '{{ config('app.url') }}';
    window.i18n = @json(__('language'));
</script>

<body class="horizontal-layout" data-platform="{{ config('nativephp-internal.running') ? 'desktop' : 'default' }}">
    <!-- Begin page -->
    @if (config('nativephp-internal.running'))
    <div id="app-header-win" class="app-header-win">
        <div class="app-header-win-drag">
            <img src="{{ asset('assets/images/logo-box.ico') }}" class="app-logo" alt="logo">
            <div class="app-header-win-menu">
                <div class="win-menu" data-menu="file">
                    <button type="button" class="btn-win-menu">File</button>
                    <div class="win-menu-dropdown">
                        <button type="button" class="win-menu-item" id="menu-print">Print</button>
                        <button type="button" class="win-menu-item" id="menu-reload">Reload</button>
                        @if (config('database.connection_mode') == 'service')
                        <button type="button" class="win-menu-item" id="menu-reload-db">Reload Database</button>
                        @endif
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
    <div id="main-content">
        @yield('main-content')
    </div>
    @else
        @yield('main-content')
    @endif
    @yield('main-modal')
    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js')}}"></script>
    <script>
        const CaseConverter = {

            _splitWords: function (str) {
                return str
                    .replace(/([a-z0-9])([A-Z])/g, '$1 $2')
                    .replace(/([A-Z]+)([A-Z][a-z])/g, '$1 $2')
                    .replace(/[_\-\.]+/g, ' ')
                    .replace(/\s+/g, ' ')
                    .trim()
                    .split(' ')
                    .filter(Boolean)
                    .map(w => w.toLowerCase());
            },
            toCamelCase: function (str) {
                const words = this._splitWords(str);
                return words
                    .map((w, i) => (i === 0 ? w : w.charAt(0).toUpperCase() + w.slice(1)))
                    .join('');
            },
            toPascalCase: function (str) {
                const words = this._splitWords(str);
                return words
                    .map(w => w.charAt(0).toUpperCase() + w.slice(1))
                    .join('');
            },
            toSnakeCase: function (str) {
                return this._splitWords(str).join('_');
            },
            toConstantCase: function (str) {
                return this._splitWords(str).join('_').toUpperCase();
            },
            toKebabCase: function (str) {
                return this._splitWords(str).join('-');
            },
            toTitleCase: function (str) {
                const words = this._splitWords(str);
                return words
                    .map(w => w.charAt(0).toUpperCase() + w.slice(1))
                    .join(' ');
            },
            toSentenceCase: function (str) {
                const words = this._splitWords(str);
                const sentence = words.join(' ');
                return sentence.charAt(0).toUpperCase() + sentence.slice(1);
            },
            toLowerCase: function (str) {
                return this._splitWords(str).join(' ');
            },
            toUpperCase: function (str) {
                return this._splitWords(str).join(' ').toUpperCase();
            },
            toDotCase: function (str) {
                return this._splitWords(str).join('.');
            },
            toToggleCase: function (str) {
                return str
                    .split('')
                    .map(c => (c === c.toUpperCase() ? c.toLowerCase() : c.toUpperCase()))
                    .join('');
            }
        };

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

        const setWIndowMinimize = () => {
            $.post(BASE_URL+'/native/window/minimize', { window_id: window.Native?.app?.id ?? 'main' });
        }

        const setWindowMaximize = () => {
            $.post(BASE_URL+'/native/window/maximize', { window_id: window.Native?.app?.id ?? 'main' });
        }

        const setWindowRestore = () => {
            $.post(BASE_URL+'/native/window/restore', { window_id: window.Native?.app?.id ?? 'main' });
        }

        const closeWindow = () => {
            $.post(BASE_URL+'/native/window/close', { window_id: window.Native?.app?.id ?? 'main' });
        }
        
        const openWindowDevTools = () => {
            $.post(BASE_URL+'/native/window/devtools/toggle', { window_id: window.Native?.app?.id ?? 'main' });
        }

        $(document).on('click', '#win-minimize', function () {
            setWIndowMinimize()
        });

        $(document).on('click', '#win-maximize', function () {
            setWindowMaximize()
        });

        $(document).on('click', '#win-restore', function () {
            setWindowRestore()
        });

        $(document).on('click', '#win-close', function () {
            closeWindow()
        });

        $(document).on('dblclick', '#app-header-win-drag', function () {
            setWindowMaximize()
        });

        $(document).on('click', '#menu-devtools', function () {
            openWindowDevTools()
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
            closeWindow();
        });

        $(document).on('click', '#menu-fullscreen', function () {
            setWindowMaximize();
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

        $(document).on('click', '#menu-reload-db', function () {
            $.post({
                url:  BASE_URL+'/api/startup/retry',
                type: 'POST',
                beforeSend: () => {
                    Swal.fire({
                        title: 'Harap Menunggu!',
                        html: 'Sedang reload database',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => Swal.showLoading(),
                    });
                }
            }).then(() => {
                Swal.close();
                setTimeout(() => {
                    window.location.href = BASE_URL+'/startup';
                }, 500);
            });
        })
        
        Object.defineProperty(window, 'HESOYAM', {
            configurable: true,
            get() {
                activateSecret()
            }
        });

        function activateSecret() {
            window.location.href = '{{ url('config') }}';
        }

        function showConfirmAlert(options = {}) {
            let swal = Swal.fire({
                icon: 'question',
                title: '{{ __('language.confirm.title') }}',
                html: '{{ __('language.confirm.text') }}',
                showCancelButton: true,
                confirmButtonColor: 'var(--bs-success)',
                cancelButtonColor: 'var(--bs-danger)',
                confirmButtonText: '{{ __('language.yes') }}',
                cancelButtonText: '{{ __('language.cancel') }}',
                reverseButtons: true,
                ...options,
            });

            return swal;
        }

        function showLoadingAlert(title = i18n?.alert?.info?.processing?.title, message = i18n?.alert?.info?.processing?.text, timer = 0) {
            Swal.fire({
                title: title,
                html: message,
                didOpen: () => {
                    Swal.showLoading();
                },
                timer: timer
            });
        }

        function generalAjaxErrorHandler(xhr, status, error) {
            Swal.close(); // Close any loading Swal
            let title = i18n?.errors?.title?.error;
            let message = i18n?.errors?.message?.general;
            let icon = 'error';

            console.log(xhr, status, error);

            if (status === 'timeout') {
                message = i18n?.errors?.message?.timeout;
            } else if (xhr.readyState === 0) {
                message = i18n?.errors?.message?.no_connection;
            } else {
                switch (xhr.status) {
                    case 0:
                        message = i18n?.errors?.message?.no_connection;
                        break;
                    case 400:
                        message = xhr.responseJSON?.message || i18n?.errors?.message?.bad_request;
                        break;
                    case 401:
                        title = i18n?.errors?.title?.unauthorized;
                        message = xhr.responseJSON?.message || i18n?.errors?.message?.unauthorized;
                        break;
                    case 403:
                        title = i18n?.errors?.title?.forbidden;
                        message = xhr.responseJSON?.message || i18n?.errors?.message?.forbidden;
                        break;
                    case 404:
                        title = i18n?.errors?.title?.not_found;
                        message = xhr.responseJSON?.message || i18n?.errors?.message?.not_found;
                        break;
                    case 419:
                        title = i18n?.errors?.title?.session_expired;
                        message = i18n?.errors?.message?.session_expired;
                        break;
                    case 422:
                        title = i18n?.errors?.title?.validation;
                        // Validation errors
                        const errors = xhr.responseJSON?.errors;
                        if (errors) {
                            message = Object.values(errors)
                                .flat()
                                .join('\n');
                        } else {
                            message = xhr.responseJSON?.message || i18n?.errors?.message?.validation;
                        }
                        break;
                    case 429:
                        message = i18n?.errors?.message?.too_many;
                        break;
                    case 500:
                        message = xhr.responseJSON?.message || i18n?.errors?.message?.server;
                        break;
                    case 503:
                        message = i18n?.errors?.message?.service_down;
                        break;
                    default:
                        message = xhr.responseJSON?.message || i18n?.errors?.message?.unexpected?.replace(':code', xhr.status);;
                }
            }

            if (xhr.status == 401) {
                Swal.fire({
                    title: title,
                    html: message,
                    icon: icon,
                    showCancelButton: false,
                    confirmButtonColor: 'var(--bs-info)',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = BASE_URL + '/logout'   
                    }
                });
            } else {
                Swal.fire({
                    title: title,
                    html: message,
                    showConfirmButton: true,
                    confirmButtonColor: 'var(--bs-success)',
                    icon: icon
                });
            }

        }
    </script>
 @yield('main-script')
</body>

</html>