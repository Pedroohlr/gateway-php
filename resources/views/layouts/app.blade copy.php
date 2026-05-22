@php
$setting = \App\Helpers\Helper::getSetting();
$color = $setting->gateway_color;
@endphp

@php
    // Função para converter HEX para RGBA
    function hexToRgba($hex, $opacity = 0.5) {
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
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme-mode="light" data-header-styles="transparent" style="" data-menu-styles="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="Description" content="{{env('APP_NAME')}}">
    <meta name="Author" content="{{env('APP_NAME')}}">
    <meta name="keywords" content="{{env('APP_NAME')}}">
    <link rel="icon" type="image/x-icon" href="{{ asset($setting->gateway_favicon) }}">
    <title>{{ env('APP_NAME') }} - {{ $route }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('layouts.components.styles')
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet">
<!-- CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        body {
            font-family: 'Public Sans' !important;
        }
        :root {

            --color-gateway: {{ $setting->gateway_color }};
            --color-gateway-opacity: {{ $opacityColor }};
            --color-gateway-opacity2: {{ $opacityColor2 }};
        }

        .body-container {
            margin-left: 10%;margin-right: 10%;margin-bottom:25px;
        }

        @media screen and (max-width: 1250px){
            .body-container {
            margin-left: 3%;margin-right: 3%;margin-bottom:25px;
            }
        }

        @media screen and (max-width: 1024px){
            .body-container {
            margin-left: 2%;margin-right: 2%;margin-bottom:25px;
            }
        }

        div.dataTables_filter label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0; /* Oculta o texto */
        }

        /* Restaura a aparência do input */
        #table-produtos div.dataTables_filter label input[type="search"] {
            font-size: 1rem;         /* tamanho do texto normal */
            flex: 1;                 /* permite crescer */
            min-width: 150px;        /* largura mínima */
            border-radius: 10px;
            padding-left: 10px;
            margin-left: -10px;
            margin-bottom: 35px;
            z-index: 1;
        }

        div.dataTables_filter label input[type="search"] {
            border-radius: 10px !important;
        }

        [name="table-pedidos_length"] {
             width: 60px !important;
             padding-left: 10px !important;
        }

        #table-produtos,
        #table-produtos th,
        #table-produtos td,
        #table-produtos thead,
        #table-produtos tbody {
            position: relative;
            border: none !important;
        }

        table,
        th,
        td,
        thead,
        tbody {
            position: relative;
            border: none !important;
        }

        #table-pix-entradas thead tr,
        #table-pix-entradas tbody tr::before,
        #table-pix-entradas tbody tr::before td,
        #table-pix-entradas thead th,
        #table-pix-entradas tfoot tr,
        #table-pix-entradas tfoot td {
            border: none !important;
        }

        button {
            border-radius: 6px !important;
            text-transform: capitalize !important;
            font-weight: 300 !important;
        }

        #drawerToggle {
            border-radius: 50px !important;
        }

        #table-produtos thead {
            background: #F5F5F5 !important;
        }

        #table-produtos {
            padding-top: 45px !important;
        }

        #table-produtos [class="top"] {
            position: absolute;

        }

        /* Remove borda inferior do header (thead) */
        #table-produtos thead tr {
            border-bottom: none !important;
        }

        /* Remove borda inferior da tabela (após último tr) */
        #table-produtos tbody tr:last-child {
            border-bottom: none !important;
        }

        /* Opcional: remover linhas zebradas (se usadas) */
        #table-produtos tbody tr {
            background-color: transparent !important;
        }

        .text-bg-success {
            background: var(--color-gateway-opacity2) !important;
            color: var(--color-gateway) !important;
        }
        .color-gateway,
        .text-primary {
            color: var(--color-gateway) !important;
        }

        .bg-gateway {
            background: var(--color-gateway) !important;
        }

        .border-gateway-1 {
            border: 1px solid var(--color-gateway) !important;
        }

        .border-gateway-2 {
            border: 2px solid var(--color-gateway) !important;
        }

        #layoutDrawer_nav {
            max-width: 250px !important;
        }
        .bg-light, .footer-light {
            background-color: #f4f6f8 !important;
        }
        body.drawer-toggled {

        }

        #drawerToggle {
            width: 25px !important;
            height: 25px !important;
        }

        #drawerToggle {
            transition: transform 0.3s ease;
            }

            #drawerToggle.rotated-right {
            transform: rotate(180deg);
            }

            #drawerToggle.rotated-left {
            transform: rotate(0deg);
            }

            #drawerAccordion {
                border: none !important;
            }
        #drawerToggle {
            border: 1px dashed gray;
        }
        .top-app-bar {
            background: #F5F5F5 !important;
            color: #f4f6f8 !important;
            box-shadow: none !important;
        }
        .logo {
            width:225px;
            height: 100%;
            padding-left:1px;
            position: relative;
        }
        .margin-logo {
            position: absolute !important;
            height: 72px !important;
            top: -21px;
            right: -1px;
            border-right: 1px dashed rgb(194, 194, 194) !important;
            z-index: -1;

        }
        .drawer-menu-heading {
            color: rgb(185, 185, 185) !important;
            font-size: 14px !important;
        }

        @media screen and (max-width: 992px) {
            .margin-logo {
                display: none;
            position: absolute !important;
            height: 72px !important;
            top: -21px;
            right: -1px;
            /* border-right: 1px dashed rgb(194, 194, 194) !important; */
            z-index: -1;

        }
        }

        body.drawer-toggled .margin-logo {
            border: none !important;
        }

        .logo,
        .icon-navbar {
            color: #999999 !important;
        }
        .nav-link:hover {
            background: var(--color-gateway-opacity2) !important;
            background-color: var(--color-gateway-opacity2) !important;
        }

        .btn-outline-primary {
            border-color: var(--color-gateway) !important;
            color: var(--color-gateway) !important;
        }
        [data-bs-toggle="tab"],
        [data-bs-toggle="tab"]:before,
        [data-bs-toggle="tab"]:after,
        .nav-link:hover .material-icons {
        color: var(--color-gateway) !important;
        }

        .card-border-color {
            border-left: 4px solid var(--color-gateway) !important;
        }
        .card-color {
            background: var(--color-gateway) !important;
        }
        .card-raised {
            border-radius: 15px !important;
        }
        /* .nav-link-icon .material-icons {
            color: #F5F5F5 !important;
        } */

        #layoutDrawer_nav {
            background: #f4f6f8 !important;
            border: 1px dashed rgb(194, 194, 194) !important;
        }


        /* .drawer-menu-divider {
            border: 0.01rem dashed rgb(194, 194, 194) !important;
        } */
        #layoutDrawer_nav .drawer {
            background: #f4f6f8 !important;
        }
        .text-warning {
            color: rgb(255, 102, 0) !important;
        }
        .form-control {
            background-color:transparent !important;
            border-color: var(--input-border);
            height: 36.09px;
            border-radius: 0.25rem;
        }

        .btn-primary {
            color: black !important;
        }


        .swal2-popup {
            background: rgb(255, 255, 255) !important;
            height: 80px !important;
            display: flex !important;
            align-items: center !important;
        }

        .swal2-image {
            margin-left: 10px !important;
        }

        .swal2-title {
            color: var(--color-gateway) !important;
            width: 100% !important;
            font-size: 16px !important;
            text-align: start !important;
            margin-left: -5 !important;
        }
        [type=checkbox]:checked, [type=radio]:checked {
            color: var(--color-gateway) !important;
            border-color: var(--color-gateway) !important;
        }

        [type=checkbox]:focus, [type=radio]:focus {
            border-color: var(--color-gateway) !important;
        }

        .swal2-timer-progress-bar {
            background: var(--color-gateway-opacity) !important;
        }
        @media (max-width: 991.98px) {
            .app-header .horizontal-logo .header-logo img {
                height: 1.4rem;
                line-height: 1.75rem;
            }
        }
        svg  {
            fill: var(--color-gateway) !important;
        }

       /*  .avatar>svg {
            fill: white !important;
        } */

       /*  .svg-success>svg,
        .svg-primary>svg {
            fill: var(--color-gateway) !important;
        } */

         .btn-primary,
        .main-card-icon .avatar .avatar,
        .bg-primary,
        .text-bg-primary,
        .btn-success,
        .page-link {
            background: var(--color-gateway) !important;
            background-color: var(--color-gateway) !important;
            border-color: var(--color-gateway) !important;
            shadow: 0 0 2 2 var(--color-gateway-opacity2) !important;
            color: white !important;

        }
        .btn-success,
        .btn-success>span,
        .btn-success>i {
            color: white !important;
        }

        .btn-primary:hover,
        .btn-success:hover,
        .page-link:hover {
            background: var(--color-gateway-opacity) !important;
            background-color: var(--color-gateway-opacity) !important;
            border-color: var(--color-gateway) !important;
        }

        .text-primary,
        a.side-menu__item:hover {
            color: var(--color-gateway) !important;
        }
        .shadow-primary {
            box-shadow: 0 0px 8px var(--color-gateway) !important;
        }
        .main-card-icon .avatar,
        .side-menu__item:hover {
            background: var(--color-gateway-opacity2) !important;
            background-color: var(--color-gateway-opacity2) !important;
        }
        /* .btn-outline-primary {
            border: 1px solid var(--color-gateway) !important;
            color: var(--color-gateway) !important;
        }

        .btn-outline-primary:hover {
            border: 1px solid var(--color-gateway) !important;
            background: var(--color-gateway) !important;
            background-color: var(--color-gateway) !important;
            color: white !important;
        } */
        .desktop-logo {
            height: 30px !important;
            width: auto !important;
            text-align: center !important;
        }
        @media screen and (max-width: 1080px) {
            .nav-separador-mobile {
                display: none;
            }
            .flex-column {
                flex-direction: row !important; /* Muda de coluna para linha */
                flex-wrap: wrap; /* Permite quebra de linha, se necessário */
                justify-content: center; /* Centraliza os itens */
                padding: 0;
                margin: 0;
                list-style: none;
            }

            .nav-item-mobile {
                margin: 5px;
            }

            .nav-item-mobile button {
                display: inline-block;
                padding: 10px 15px;
                background-color: #0d6efd;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }

            .nav-item-mobile button:hover {
                background-color: #0b5ed7;
            }
        }


        .truncate {
            white-space: nowrap;        /* Não permite quebra de linha */
            overflow: hidden;           /* Esconde o que ultrapassa o limite */
            text-overflow: ellipsis;    /* Adiciona "..." no final do texto */
        }

        .timeline {
        position: relative;
        border-left: 3px solid #dee2e6;
        margin-left: 20px;
        }

        .timeline-item {
        position: relative;
        margin-bottom: 2rem;
        padding-left: 2rem;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -14px;
            top: 0;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background-color: var(--color-gateway);
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path fill="white" d="M242.4 292.5C247.8 287.1 257.1 287.1 262.5 292.5L339.5 369.5C353.7 383.7 372.6 391.5 392.6 391.5H407.7L310.6 488.6C280.3 518.1 231.1 518.1 200.8 488.6L103.3 391.2H112.6C132.6 391.2 151.5 383.4 165.7 369.2L242.4 292.5zM262.5 218.9C256.1 224.4 247.9 224.5 242.4 218.9L165.7 142.2C151.5 127.1 132.6 120.2 112.6 120.2H103.3L200.7 22.8C231.1-7.6 280.3-7.6 310.6 22.8L407.8 119.9H392.6C372.6 119.9 353.7 127.7 339.5 141.9L262.5 218.9zM112.6 142.7C126.4 142.7 139.1 148.3 149.7 158.1L226.4 234.8C233.6 241.1 243 245.6 252.5 245.6C261.9 245.6 271.3 241.1 278.5 234.8L355.5 157.8C365.3 148.1 378.8 142.5 392.6 142.5H430.3L488.6 200.8C518.9 231.1 518.9 280.3 488.6 310.6L430.3 368.9H392.6C378.8 368.9 365.3 363.3 355.5 353.5L278.5 276.5C264.6 262.6 240.3 262.6 226.4 276.6L149.7 353.2C139.1 363 126.4 368.6 112.6 368.6H80.8L22.8 310.6C-7.6 280.3-7.6 231.1 22.8 200.8L80.8 142.7H112.6z"/></svg>');
            background-repeat: no-repeat;
            background-position: center;
            background-size: 60%;
        }


        .amount-credit {
        font-weight: bold;
        font-size: 1rem;
        }

        /* CSS */
        .form-outlined {
        position: relative;
        margin-top: 1rem;
        }

        .form-outlined input.form-control {
        padding: 1.25rem 0.75rem 0.5rem;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        transition: border-color 0.2s;
        }

        .form-outlined label {
        position: absolute;
        top: 0.4rem;
        left: 0.75rem;
        background: #F4F6F8;
        padding: 0 0.25rem;
        color: #6c757d;
        transition: all 0.2s ease;
        pointer-events: none;
        font-size: 1rem;
        }

        select {
            color: #333; /* cor padrão */
        }

        select option {
            color: #000;
            background-color: #fff;
        }

        select option:focus {
            color: #000;
            background-color: #fff;
        }

        /* Estiliza o item selecionado (aplica no select, não no option) */
        select:focus {
            border-color: var(--color-gateway);
            box-shadow: 0 0 0 0.25rem var(--color-gateway-opacity2);
}

        .form-outlined input:focus {
        border-color: var(--color-gateway); /* cor do Material UI */
        outline: none;
        box-shadow: 0 0 0 0.2rem var(--color-gateway-opacity2);
        padding-bottom: 2px;
        }

        .form-outlined input:focus + label,
        .form-outlined input:not(:placeholder-shown) + label {
        top: -0.6rem;
        left: 0.5rem;
        font-size: 0.75rem;
        color: var(--color-gateway);
        }

        .form-outlined-select {
        position: relative;
        margin-top: 0rem;
        max-height: 40px;
        }

        .form-outlined-select select.form-select {
        padding: .25rem 0.75rem 0.5rem;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        appearance: none;
        background-color: transparent;
        transition: border-color 0.2s;
        }

        .form-outlined-select label {
        position: absolute;
        top: 1rem;
        left: 0.75rem;
        background: #F4F6F8;
        padding: 0 0.25rem;
        color: #6c757d;
        font-size: 0.8rem;
        transition: all 0.2s ease;
        pointer-events: none;
        }

        .form-outlined-select select:focus {
        border-color: var(--color-gateway);
        box-shadow: 0 0 0 0.2rem rgba(63, 81, 181, 0.2);
        }

        .form-outlined-select select:focus + label,
        .form-outlined-select select:not([value=""]):valid + label {
        top: -0.6rem;
        left: 0.5rem;
        font-size: 0.75rem;
        color: #6c757d;
        }

        body.drawer-toggled #layoutDrawer_nav  {
            left: 0 !important;
            margin: 0 !important;
            width: 250px !important
        }
        .display-5 {
            font-size: 1.5rem;
            font-weight: bold;
        }
        
        @media screen and (max-width: 540px){
            .margin-logo {
                border-color: transparent !important;
            }
        }

        <style>
  .flatpickr-calendar {
    width: 100% !important;
    max-width: none;
    box-shadow: none;
    border: none;
    position: static !important;
    display: flex;
    justify-content: center;
  }

  .flatpickr-calendar .flatpickr-innerContainer {
    display: flex;
    gap: 2rem;
  }

  .flatpickr-calendar .flatpickr-days {
    border: 1px dashed transparent;
    padding: 0.5rem;
  }

  .flatpickr-months {
    display: flex;
    justify-content: center;
  }

  .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange {
    background-color: #198754;
    color: white;
    border-color: transparent;
  }

  .flatpickr-day.inRange {
    background: #d1e7dd;
  }


</style>

    </style>
</head>

<body class="nav-fixed bg-light" style="position: relative;">
        @include('layouts.components.navbar')
        <div id="layoutDrawer">
            @include('layouts.components.sidebar')
            <div id="layoutDrawer_content" >
                <main class="body-container">
                    {{ $slot }}
                </main>
                @include('layouts.components.footer')
            </div>
        </div>
    <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <!-- Load Bootstrap JS bundle-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <!-- Load global scripts-->
    <script type="module" src="{{asset('assets-v2/js/material.js')}}"></script>
    <script src="{{asset('assets-v2/js/scripts.js')}}"></script>
    <!--  Load Chart.js via CDN-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.0.2/chart.min.js" crossorigin="anonymous"></script>
    <!--  Load Chart.js customized defaults-->
    <script src="{{asset('assets-v2/js/charts/chart-defaults.js')}}"></script>
    <!--  Load chart demos for this page-->
    <script src="{{asset('assets-v2/js/charts/demos/chart-pie-demo.js')}}"></script>
    <script src="{{asset('assets-v2/js/charts/demos/dashboard-chart-bar-grouped-demo.js')}}"></script>
    <!-- Load Simple DataTables Scripts-->
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>
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
<!--
<script disable-devtool-auto src='https://cdn.jsdelivr.net/npm/disable-devtool@latest'></script>
-->
</body>

</html>
