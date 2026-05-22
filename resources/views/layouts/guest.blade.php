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
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  @if($route)
  <title>{{env('APP_NAME')}} - {{ $route }}</title>
  @else
  <title>{{env('APP_NAME')}}</title>
  @endif
  <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp" rel="stylesheet" />
  <!-- Roboto and Roboto Mono fonts from Google Fonts-->
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css?family=Roboto+Mono:400,500" rel="stylesheet" />
  <link href="{{asset('assets-v2/css/styles.css')}}" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css') }}" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <style>
            :root {
                --color-gateway: {{ $setting->gateway_color }};
                --color-gateway-opacity: {{ $opacityColor }};
                --color-gateway-opacity2: {{ $opacityColor2 }};
            }

            .form-control {
                background-color:transparent !important;
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

            i,
            i::before {
              color: var(--color-gateway) !important;
            }
            .avatar>svg {
                fill: white !important;
            }

            .svg-success>svg,
            .svg-primary>svg {
                fill: var(--color-gateway) !important;
            }

            .btn-primary,
            .main-card-icon .avatar .avatar,
            .badge.bg-primary,
            .btn-success,
            .page-link {
                background: var(--color-gateway) !important;
                background-color: var(--color-gateway) !important;
                border-color: var(--color-gateway) !important;
                shadow: 0 0 2 2 var(--color-gateway-opacity2) !important;
                color: white !important;

            }

            .text-decoration-none {
                color: var(--color-gateway) !important;
            }

            .text-decoration-none:hover {
                color: var(--color-gateway-opacity) !important;
            }

            #layoutAuthentication_footer .text-decoration-none {
                color: white !important;
            }

            #layoutAuthentication_footer  .text-decoration-none:hover {
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
            .btn-outline-primary {
                border: 1px solid var(--color-gateway) !important;
                color: var(--color-gateway) !important;
            }

            .btn-outline-primary:hover {
                border: 1px solid var(--color-gateway) !important;
                background: var(--color-gateway) !important;
                background-color: var(--color-gateway) !important;
                color: white !important;
            }

        </style>
</head>

<body style="background: var(--color-gateway);">
    <!-- Layout wrapper-->
    <div id="layoutAuthentication">
        <!-- Layout content-->
        <div id="layoutAuthentication_content">
            <!-- Main page content-->
            <main>
                <!-- Main content container-->
                <div class="container">
                    <div class="row justify-content-center">
                        {{ $slot }}
                    </div>
                </div>
            </main>
        </div>
        <!-- Layout footer-->
        <div id="layoutAuthentication_footer">
            <!-- Auth footer-->
            <footer class="p-4">
                <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between small">
                    <div class="mb-2 me-sm-3 mb-sm-0"><div class="text-white fw-500">&copy; Todos os direitos reservados a <a class="text-white" href="{{ env('APP_URL') }}" target="_blank">{{ $setting->gateway_name }}</a> {{ date('Y') }}</div></div>
                    <div class="ms-sm-3">
                        <a class="fw-500 text-decoration-none link-white" href="/">Inicio</a>
                        <a class="mx-4 fw-500 text-decoration-none link-white" href="{{ url('/doc/terms.pdf') }}">Termos</a>
                        <a class="fw-500 text-decoration-none link-white" href="#!">Suporte</a>
                    </div>
                </div>
            </footer>
        </div>
    </div>
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


  <!-- Load Bootstrap JS bundle-->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <!-- Load global scripts-->
   <script type="module" src="{{asset('assets-v2/js/material.js')}}"></script>
    <script src="{{asset('assets-v2/js/scripts.js')}}"></script>
  <script>
    function showToast(type, message) {
      Swal.fire({
        toast: true,
        icon: type,
        //imageUrl: "/assets/images/toast/success.png", // URL da imagem
        //imageWidth: 50, // Ajuste o tamanho
        //imageHeight: 50,
        title: message,
        animation: false,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
        //showCloseButton: true,
        didOpen: (toast) => {
          toast.addEventListener('mouseenter', Swal.stopTimer);
          toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
      });
    }
  </script>
<script disable-devtool-auto src='https://cdn.jsdelivr.net/npm/disable-devtool@latest'></script>
</body>

</html>
