@php
    $setting = \App\Helpers\Helper::getSetting();
    $color = $setting->gateway_color;
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
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="icon" href="{{  url('assets-front/images/favicon-32x32.png') }}" type="image/png" />
    <!--plugins-->
    <link href="{{  url('assets-front/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
    <link href="{{  url('assets-front/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
    <link href="{{  url('assets-front/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />
    <!-- loader-->
    <!-- <link href="{{  url('assets-front/css/pace.min.css') }}" rel="stylesheet" /> -->
    <!-- <script src="{{  url('assets-front/js/pace.min.js') }}"></script> -->
    <!-- Bootstrap CSS -->
    <link href="{{  url('assets-front/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{  url('assets-front/css/bootstrap-extended.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="{{  url('assets-front/css/app.css') }}" rel="stylesheet">
    <link href="{{  url('assets-front/css/icons.css') }}" rel="stylesheet">
    @if($route)
        <title>{{env('APP_NAME')}} - {{ $route }}</title>
    @else
        <title>{{env('APP_NAME')}}</title>
    @endif

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

        .btn-link {
            color: var(--color-gateway-opacity) !important;
        }

        .btn-primary {
            background-color: var(--color-gateway) !important;
            border-color: var(--color-gateway) !important;
            color: black;
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
    </style>
</head>

<body class="bg-theme {{ $setting->bg_theme }}">
    <!--wrapper-->
    {{ $slot }}

    <!--end wrapper-->
    <!--start switcher-->
    <!-- <div class="switcher-wrapper">
        <div class="switcher-btn"> <i class='bx bx-cog bx-spin'></i>
        </div>
        <div class="switcher-body">
            <div class="d-flex align-items-center">
                <h5 class="mb-0 text-uppercase">Theme Customizer</h5>
                <button type="button" class="btn-close ms-auto close-switcher" aria-label="Close"></button>
            </div>
            <hr />
            <p class="mb-0">Gaussian Texture</p>
            <hr>

            <ul class="switcher">
                <li id="theme1"></li>
                <li id="theme2"></li>
                <li id="theme3"></li>
                <li id="theme4"></li>
                <li id="theme5"></li>
                <li id="theme6"></li>
            </ul>
            <hr>
            <p class="mb-0">Gradient Background</p>
            <hr>

            <ul class="switcher">
                <li id="theme7"></li>
                <li id="theme8"></li>
                <li id="theme9"></li>
                <li id="theme10"></li>
                <li id="theme11"></li>
                <li id="theme12"></li>
                <li id="theme13"></li>
                <li id="theme14"></li>
                <li id="theme15"></li>
            </ul>
        </div>
    </div> -->
    <!--end switcher-->

    <!--plugins-->
    <script src="{{ url('assets-front/js/jquery.min.js') }} "></script>
    <!--Password show & hide js -->
    <script>
        $(document).ready(function () {
            $("#show_hide_password a").on('click', function (event) {
                event.preventDefault();
                if ($('#show_hide_password input').attr("type") == "text") {
                    $('#show_hide_password input').attr('type', 'password');
                    $('#show_hide_password i').addClass("bx-hide");
                    $('#show_hide_password i').removeClass("bx-show");
                } else if ($('#show_hide_password input').attr("type") == "password") {
                    $('#show_hide_password input').attr('type', 'text');
                    $('#show_hide_password i').removeClass("bx-hide");
                    $('#show_hide_password i').addClass("bx-show");
                }
            });

            $("#password_confirmation a").on('click', function (event) {
                event.preventDefault();
                if ($('#password_confirmation input').attr("type") == "text") {
                    $('#password_confirmation input').attr('type', 'password');
                    $('#password_confirmation i').addClass("bx-hide");
                    $('#password_confirmation i').removeClass("bx-show");
                } else if ($('#password_confirmation input').attr("type") == "password") {
                    $('#password_confirmation input').attr('type', 'text');
                    $('#password_confirmation i').removeClass("bx-hide");
                    $('#password_confirmation i').addClass("bx-show");
                }
            });
        });
    </script>

    <script>
        $(".switcher-btn").on("click", function () {
            $(".switcher-wrapper").toggleClass("switcher-toggled")
        }), $(".close-switcher").on("click", function () {
            $(".switcher-wrapper").removeClass("switcher-toggled")
        }),


            $('#theme1').click(theme1);
        $('#theme2').click(theme2);
        $('#theme3').click(theme3);
        $('#theme4').click(theme4);
        $('#theme5').click(theme5);
        $('#theme6').click(theme6);
        $('#theme7').click(theme7);
        $('#theme8').click(theme8);
        $('#theme9').click(theme9);
        $('#theme10').click(theme10);
        $('#theme11').click(theme11);
        $('#theme12').click(theme12);
        $('#theme13').click(theme13);
        $('#theme14').click(theme14);
        $('#theme15').click(theme15);

        function theme1() {
            $('body').attr('class', 'bg-theme bg-theme1');
        }

        function theme2() {
            $('body').attr('class', 'bg-theme bg-theme2');
        }

        function theme3() {
            $('body').attr('class', 'bg-theme bg-theme3');
        }

        function theme4() {
            $('body').attr('class', 'bg-theme bg-theme4');
        }

        function theme5() {
            $('body').attr('class', 'bg-theme bg-theme5');
        }

        function theme6() {
            $('body').attr('class', 'bg-theme bg-theme6');
        }

        function theme7() {
            $('body').attr('class', 'bg-theme bg-theme7');
        }

        function theme8() {
            $('body').attr('class', 'bg-theme bg-theme8');
        }

        function theme9() {
            $('body').attr('class', 'bg-theme bg-theme9');
        }

        function theme10() {
            $('body').attr('class', 'bg-theme bg-theme10');
        }

        function theme11() {
            $('body').attr('class', 'bg-theme bg-theme11');
        }

        function theme12() {
            $('body').attr('class', 'bg-theme bg-theme12');
        }

        function theme13() {
            $('body').attr('class', 'bg-theme bg-theme13');
        }

        function theme14() {
            $('body').attr('class', 'bg-theme bg-theme14');
        }

        function theme15() {
            $('body').attr('class', 'bg-theme bg-theme15');
        }

    </script>
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
</body>

</html>