@php
    use \App\Helpers\Helper;
    $setting = Helper::getSetting();
    $color = $setting->gateway_color;

    Helper::gerarPwa();
    Helper::gerarServiceWorker();
@endphp

@php
    // Função para converter HEX para RGBA
    function hexToRgba($hex, $opacity = 0.5)
    {
        $hex = str_replace('#', '', $hex);

        if (strlen($hex) == 3) {
            $r = hexdec(str_repeat(substr($hex, 0, 1), 2));
            $g = hexdec(str_repeat(substr($hex, 1, 1), 2));
            $b = hexdec(str_repeat(substr($hex, 2, 1), 2));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }

        return "rgba($r, $g, $b, $opacity)";
    }

    $opacityColor = Str::contains($color, 'rgba')
        ? preg_replace('/rgba\((\d+),\s*(\d+),\s*(\d+),\s*[\d.]+\)/', 'rgba($1, $2, $3, 0.8)', $color)
        : hexToRgba($color, 0.8);

    $opacityColor2 = Str::contains($color, 'rgba')
        ? preg_replace('/rgba\((\d+),\s*(\d+),\s*(\d+),\s*[\d.]+\)/', 'rgba($1, $2, $3, 0.1)', $color)
        : hexToRgba($color, 0.1);
@endphp
@props(['route'])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme-mode="light" data-header-styles="transparent"
    style="" data-menu-styles="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="Description" content="{{env('APP_NAME')}}">
    <meta name="Author" content="{{env('APP_NAME')}}">
    <meta name="keywords" content="{{env('APP_NAME')}}">
    <link rel="icon" type="image/x-icon" href="{{ asset($setting->gateway_favicon) }}">
    <meta name="theme-color" content="#0B5ED7">
    <link rel="icon" href="{{ asset($setting->gateway_favicon) }}">
    <link rel="apple-touch-icon" href="{{ asset($setting->gateway_favicon) }}">
    <title>{{ env('APP_NAME') }} - {{ $route }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="manifest" href="/manifest.json">
    <link rel="icon" href="{{  url('assets-front/images/favicon-32x32.png') }}" type="image/png" />
    <!--plugins-->
    <link href="{{  url('assets-front/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
    <link href="{{  url('assets-front/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
    <link href="{{  url('assets-front/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />
    <!-- loader-->
    <!-- <link href="{{  url('assets-front/css/pace.min.css') }}" rel="stylesheet" />
    <script src="{{  url('assets-front/js/pace.min.js') }}"></script> -->
    <!-- Bootstrap CSS -->
    <link href="{{  url('assets-front/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{  url('assets-front/css/bootstrap-extended.css') }}" rel="stylesheet">
    <link href="{{  url('assets-front/css/app.css') }}" rel="stylesheet">
    <link href="{{  url('assets-front/css/icons.css') }}" rel="stylesheet">
    <!-- CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/5.3.5/apexcharts.min.js"
        integrity="sha512-dC9VWzoPczd9ppMRE/FJohD2fB7ByZ0VVLVCMlOrM2LHqoFFuVGcWch1riUcwKJuhWx8OhPjhJsAHrp4CP4gtw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/5.3.5/apexcharts.min.css"
        integrity="sha512-IqtQ7LKr3He47p7HjxynmqZfN07VljNkdGyGDdDJ//f1r6bT0IEKQf2CCtSgun/pvbFlNnPDMRrMSQhmSxmSSg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="
https://cdn.jsdelivr.net/npm/multi-select-dropdown@0.0.2/dist/styles/multi-select-dropdown.min.css
" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/ace-code-editor@1.2.3/lib/ace/ace.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ace-code-editor@1.2.3/lib/ace/theme/monokai.min.css">
    <style>
        :root {

            --color-gateway:
                {{ $setting->gateway_color }}
            ;
            --color-gateway-opacity:
                {{ $opacityColor }}
            ;
            --color-gateway-opacity2:
                {{ $opacityColor2 }}
            ;
        }

        .list-group,
        .list-group-item,
        .card-header {
            background-color: transparent !important;
        }

        .btn-primary {
            background-color: var(--color-gateway) !important;
            border-color: var(--color-gateway) !important;
        }

        .btn-primary:hover {
            background-color: var(--color-gateway-opacity) !important;
            border-color: var(--color-gateway) !important;
        }

        .btn-outline-primary {
            color: var(--color-gateway) !important;
            border-color: var(--color-gateway) !important;
        }

        .btn-outline-primary:hover {
            background-color: var(--color-gateway-opacity) !important;
            border-color: var(--color-gateway) !important;
        }

        .btn {
            text-align: center !important;
        }

        .btn .fa-solid {
            color: white !important;
            font-size: 14px !important;
        }

        .icon-circle {
            width: 48px !important;
            height: 48px !important;
            padding: 5px !important;
            border-radius: 50px;
            background-color: rgba(0, 0, 0, 0.28) !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        input,
        select,
        select.form-select {
            background-color: transparent !important;
            background: transparent !important;
            border-color: white !important;
        }

        .btn-primary,
        .btn-info {
            background: var(--color-gateway) !important;
            color: white !important;
        }

        .btn-primary:hover,
        .btn-info:hover {
            background: var(--color-gateway-opacity) !important;
            color: white !important;
        }

        table,
        .dataTables_wrapper {
            overflow-x: hidden !important;
        }

        code,
        .language-json {
            color: white !important;
        }

        .app-box {
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            /* Centraliza horizontalmente */
            justify-content: center !important;
            /* Centraliza verticalmente */
            height: 100% !important;
            /* Garante centralização mesmo se o item tiver altura variável */
            text-align: center !important;
        }

        .app-icon i {
            font-size: 24px !important;
            /* ajuste o tamanho do ícone conforme desejar */
            display: inline-block !important;
            margin-bottom: 10px !important;
        }

        .app-name p {
            margin: 0 !important;
            font-size: 10px !important;
            line-height: 1.2 !important;
        }

        select,
        option {
            background-color: #171717 !important;
        }

        .card-apk-windows,
        .card-apk-ios,
        .card-apk-android {
            margin: 5px;
            width: 130px;
            height: 130px;
            padding: 10px;
            border: 1px solid white;
            border-radius: 6px;
        }


        #android-svg,
        #ios-svg,
        #windows-svg {
            fill: white !important;
            transform: scale(0.35);
            transform-origin: top left;
            /* evitar deslocamento */
        }

        .card-apk-windows:hover #windows-svg {
            fill: #0099ffff !important;
            transform: scale(0.35);
            transform-origin: top left;
            cursor: pointer;
            /* evitar deslocamento */
        }

        .card-apk-windows:hover {
            border-color: #0099ffff !important;
            cursor: pointer;
        }

        .card-apk-android:hover #android-svg {
            fill: #26e200ff !important;
            transform: scale(0.35);
            transform-origin: top left;
            cursor: pointer;
            /* evitar deslocamento */
        }

        .card-apk-android:hover {
            border-color: #26e200ff !important;
            cursor: pointer;
        }

        .card-apk-ios:hover #ios-svg {
            fill: #e0e0e0ff !important;
            transform: scale(0.35);
            transform-origin: top left;
            cursor: pointer;
            /* evitar deslocamento */
        }

        .card-apk-ios:hover {
            border-ios: #e0e0e0ff !important;
            cursor: pointer;
        }

        @media screen and (max-width: 540px) {

            .card-apk-windows,
            .card-apk-ios,
            .card-apk-android {
                margin: 5px;
                width: 90px;
                height: 90px;
                padding: 10px;
                border: 1px solid white;
                border-radius: 6px;
            }


            #android-svg,
            #ios-svg,
            #windows-svg {
                fill: white !important;
                transform: scale(0.20);
                transform-origin: top left;
                /* evitar deslocamento */
            }

            .card-apk-windows:hover #windows-svg {
                fill: #0099ffff !important;
                transform: scale(0.20);
                transform-origin: top left;
                cursor: pointer;
                /* evitar deslocamento */
            }

            .card-apk-windows:hover {
                border-color: #0099ffff !important;
                cursor: pointer;
            }

            .card-apk-android:hover #android-svg {
                fill: #26e200ff !important;
                transform: scale(0.20);
                transform-origin: top left;
                cursor: pointer;
                /* evitar deslocamento */
            }

            .card-apk-android:hover {
                border-color: #26e200ff !important;
                cursor: pointer;
            }

            .card-apk-ios:hover #ios-svg {
                fill: #e0e0e0ff !important;
                transform: scale(0.20);
                transform-origin: top left;
                cursor: pointer;
                /* evitar deslocamento */
            }

            .card-apk-ios:hover {
                border-ios: #e0e0e0ff !important;
                cursor: pointer;
            }
        }

        .alert-info {
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .alert-info .btn-link {
            color: white;
            text-decoration: none;
        }

        .badge {
            color: black !important;
        }

        .badge.bg-success {
            color: white !important;
        }

        .btn-primary {
            color: black !important;
        }
    </style>
</head>

<body class="bg-theme {{ $setting->bg_theme }}  pace-done" cz-shortcut-listen="true">
    <!-- <div class="pace  pace-inactive">
        <div class="pace-progress" data-progress-text="100%" data-progress="99"
            style="transform: translate3d(100%, 0px, 0px);">
            <div class="pace-progress-inner"></div>
        </div>
        <div class="pace-activity"></div>
    </div> -->

    <div class="wrapper">
        @include('layouts.components.new-sidebar')
        @include('layouts.components.new-navbar')
        <div class="page-wrapper">
            <div class="page-content">
                <div class="accept-notify-ios d-none">
                    <a href="#!" id="notificar-ios" class="alert alert-info d-flex flex-column" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                            class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img"
                            aria-label="Warning:">
                            <path
                                d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                        </svg>
                        <div>
                            Deseja receber notificações?
                            <small>Clique aqui se aceita receber notificações.</small>
                        </div>
                    </a>
                </div>
            </div>
            {{ $slot }}
        </div>
        <!--  @include('layouts.components.footer') -->
    </div>
    </div>
    <script src="{{ asset('assets-front/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets-front/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets-front/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets-front/plugins/metismenu/js/metisMenu.min.js') }}"></script>
    <script src="{{ asset('assets-front/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets-front/plugins/apexcharts-bundle/js/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets-front/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets-front/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets-front/app.js') }}"></script>
    <!-- Load Simple DataTables Scripts-->
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function openHovered(action) {
            let side = document.querySelector('.wrapper');
            if (!side.classList.contains('toggled')) return;

            if (action === 'enter') {
                side.classList.add('sidebar-hovered');
            } else if (action === 'leave') {
                side.classList.remove('sidebar-hovered');
            }
        }

        function openSidebar() {
            let side = document.querySelector('.wrapper');
            if (side) {
                side.classList.toggle('toggled');
            }
        }
    </script>
    <script>
        function showToast(type, message) {
            Swal.fire({
                toast: true,
                icon: type,
                title: message,
                animation: false,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
                customClass: {
                    popup: 'custom-swal-theme'
                },
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });
        }
    </script>


    @if (session('success'))
        <script>
            showToast('success', "{{ session('success') }}");
        </script>
    @endif

    @if (session('error'))
        <script>
            showToast('danger', "{{ session('error') }}");
        </script>
    @endif

    @if (session('warning'))
        <script>
            showToast('warning', "{{ session('warning') }}");
        </script>
    @endif

    <script>
        const body = document.body;
        const toggleButton = document.getElementById('drawerToggle');

        const observer = new MutationObserver(() => {
            if (body.classList.contains('drawer-toggled')) {
                toggleButton.classList.add('rotated-right');
                toggleButton.classList.remove('rotated-left');
            } else {
                toggleButton.classList.add('rotated-left');
                toggleButton.classList.remove('rotated-right');
            }
        });

        observer.observe(body, { attributes: true, attributeFilter: ['class'] });
    </script>


    @livewireScripts
    {{--
    <script disable-devtool-auto src='https://cdn.jsdelivr.net/npm/disable-devtool@latest'></script> --}}
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Inicializa o menu lateral
            $('#menu').metisMenu();

            // Corrige ícones (Lucide)
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
    <script>
        const menuInstallApp = document.getElementById('app-install');
        const isPWA = window.matchMedia('(display-mode: standalone)').matches
            || window.navigator.standalone === true;

        if (isPWA) {
            menuInstallApp.classList.add('d-none');
            console.log("Rodando como PWA instalada!");
        } else {
            console.log("Rodando no navegador (não instalado).");
        }
    </script>

    <script>
        let DEVICE_ID = 'fcm_device_id';
        async function registerPush() {
            if (!("serviceWorker" in navigator)) {
                showToast('info', "Este navegador não suporta Service Workers.");
                return;
            }

            const reg = await navigator.serviceWorker.register("/service-worker.js");

            const vapidPublicKey = "{{ env('VAPID_PUBLIC_KEY') }}";

            try {
                const subscription = await reg.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: urlBase64ToUint8Array(vapidPublicKey),
                });

                let ua = navigator.userAgent;
                const match = ua.match(/\((.*?)\)/);

                if (!localStorage.getItem(DEVICE_ID)) {
                    // Enviar para o servidor salvar
                    await fetch("/save-subscription", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            subscription,
                            device_id: uuidv4(),
                            user_id: "{{ auth()->user()->id }}",
                            device_name: match[1] ?? 'Desconhecido'
                        })
                    });
                }

                localStorage.setItem(ACCEPT_NOTIFY_IOS, true);
            } catch (e) {
                console.error("Erro subscribe:", e);
            }
        }

        document.addEventListener('DOMContentLoaded', registerPush);

        function uuidv4() {
            let device_id;
            if (!localStorage.getItem(DEVICE_ID)) {
                device_id = ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, c =>
                    (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
                );
                localStorage.setItem(DEVICE_ID, device_id);
            }
            return device_id;
        }

        function urlBase64ToUint8Array(base64String) {
            const padding = '='.repeat((4 - base64String.length % 4) % 4);
            const base64 = (base64String + padding)
                .replace(/-/g, '+')
                .replace(/_/g, '/');

            const rawData = window.atob(base64);
            const outputArray = new Uint8Array(rawData.length);

            for (let i = 0; i < rawData.length; ++i) {
                outputArray[i] = rawData.charCodeAt(i);
            }
            return outputArray;
        }

        const isIOS = /iphone|ipad|ipod/i.test(navigator.userAgent);
        let containeracceptnotifyios = document.querySelector('.accept-notify-ios');
        let ACCEPT_NOTIFY_IOS = 'accept-notify-ios';
        let acceptnotify = localStorage.getItem(ACCEPT_NOTIFY_IOS);

        if (isIOS && containeracceptnotifyios && !acceptnotify) {
            containeracceptnotifyios.classList.remove('d-none');
        }

        document.getElementById("notificar-ios").addEventListener("click", async () => {
            await registerPush();
        });
    </script>
</body>

</html>