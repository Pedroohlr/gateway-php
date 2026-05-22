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
        ? preg_replace('/rgba\((\d+),\s*(\d+),\s*(\d+),\s*[\d.]+\)/', 'rgba($1, $2, $3, 0.5)', $color)
        : hexToRgba($color, 0.5);
@endphp

<!DOCTYPE html>
<html data-wf-domain="{{ 'www' . str_replace('https://', '', env('APP_URL')) }}" data-wf-page="65bc6a7ca6983c7153f2f401"
    data-wf-site="65bc6a7ca6983c7153f2f401" lang="pt-BR">

<head>
    <meta charset="utf-8" />
    <title>{{ $setting->gateway_name }} | Estrutura de Pagamentos para o seu negócio digital</title>
    <meta
        content="Cadastre seu infoproduto ou SaaS e receba pagamentos em minutos. Oferecemos Checkout 100% customizável e soluções nunca vistas no mercado, como o domínio de checkout customizável, e esse é o nosso objetivo. Trazer tudo que o mercado deixa a desejar."
        name="description" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta content="Webflow" name="generator" />
    <script src="{{ asset('LandingPage/js/jquery.js') }}" type="text/javascript"></script>
    <link href="{{ asset('LandingPage/css/thigasdev.css?v=bKmKpImyj9-7kqwksaEbgiKfz6sc5JX1Jw7lZ4B6gfE') }}"
        rel="stylesheet" type="text/css" />
    <script src="{{ asset('LandingPage/js/thigasdev.js?v=pYgLGVXrWXnegH3l0Cb3ABHajXeSHfDlF2ijzjvUUEY') }}"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin="anonymous" />
    <link rel="icon" type="image/x-icon" href="{{ asset($setting->gateway_favicon) }}">
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js" type="text/javascript"></script>
    <script type="text/javascript">
        WebFont.load({
            google: {
                families: ["Roboto:300,regular,500,700,900", "Manrope:200,300,regular,500,600,700,800:cyrillic,cyrillic-ext,greek,latin,latin-ext,vietnamese"]
            }
        });
    </script>
    <script type="text/javascript">
        !function (o, c) {
            var n = c.documentElement
                , t = " w-mod-";
            n.className += t + "js",
                ("ontouchstart" in o || o.DocumentTouch && c instanceof DocumentTouch) && (n.className += t + "touch")
        }(window, document);
    </script>
    <link href="{{ asset('LandingPage/img/MainIcon.svg') }}" rel="shortcut icon" type="image/x-icon" />
    <link href="{{ asset('LandingPage/img/MainIcon.svg') }}" rel="apple-touch-icon" rel="apple-touch-icon" />
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700&display=swap" rel="stylesheet" />
    <script>

        var source = "https://www.dropbox.com/scl/fi/fsy055uxt7zzlcw862z49/cash-register-kaching-sound-effect-125042.mp3?rlkey=d6vg92r861ykf4ptqxuiwzw8c&dl=1"
        var audio = document.createElement("audio");
        //
        audio.autoplay = true;
        //
        audio.load()
        audio.addEventListener("load", function () {
            audio.play();
        }, true);
        audio.src = source;
    </script>
    <style>
        :root {
            --color-gateway:
                {{ $setting->gateway_color }}
            ;
            --color-gateway-opacity:
                {{ $opacityColor }}
            ;
            --blue:
                {{ $setting->gateway_color }}
            ;
            --light-blue:
                {{ $opacityColor }}
            ;
            --black: black;
            --white: white;
            --blue-right:
                {{ $setting->gateway_color }}
            ;
        }

        /* Width */
        ::-webkit-scrollbar {
            width: 3px;
            height: 3px;
        }

        /* Track */
        ::-webkit-scrollbar-track {
            box-shadow: inset 0 0 5px 2F2E32;
            background: #555;
            border-radius: 10px;
        }

        /* Handle */
        ::-webkit-scrollbar-thumb {
            background: #817F86;
            border-radius: 1px;
        }

        /* Handle Hover */
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        .btn-gateway {
            background: var(--color-gateway) !important;
            background-color: var(--color-gateway) !important;
        }

        .btn-gateway:hover {
            background: var(--color-gateway-opacity) !important;
            background-color: var(--color-gateway-opacity) !important;
        }

        .section {
            background-color: #000;
            background-image: radial-gradient(circle at 60% 57%, var(--color-gateway-opacity), #0f0f0f 34%);
            border-bottom-right-radius: 0;
            height: 100svh;
            padding-top: 150px;
            padding-bottom: 0;
        }
    </style>
</head>

<body class="body-2">
    <input type="hidden" id="src" />
    <input type="hidden" id="utm" />
    <section id="inicio" class="section">
        <div class="wrapper">
            <div class="content-hero">
                <h1 class="heading-5">{{ $landing->section1_title }}</h1>
                <p class="paragraph-5">{{ $landing->section1_description }}</p>
                <a onclick="redirectCadastrar();" class="button-4 w-button btn-gateway">Comece agora</a>
            </div>
            <div class="hero_right-wrap">
                <div style="-webkit-transform:translate3d(0, 0, 0) scale3d(1, 1, 1) rotateX(16deg) rotateY(-25deg) rotateZ(7deg) skew(0, 0);-moz-transform:translate3d(0, 0, 0) scale3d(1, 1, 1) rotateX(16deg) rotateY(-25deg) rotateZ(7deg) skew(0, 0);-ms-transform:translate3d(0, 0, 0) scale3d(1, 1, 1) rotateX(16deg) rotateY(-25deg) rotateZ(7deg) skew(0, 0);transform:translate3d(0, 0, 0) scale3d(1, 1, 1) rotateX(16deg) rotateY(-25deg) rotateZ(7deg) skew(0, 0);transform-style:preserve-3d"
                    class="hero_ui-1">
                    <img src="{{ asset($landing->section1_image) }}" loading="eager" alt="" class="ui-img-1" />
                </div>
                <!-- <div style="-webkit-transform:translate3d(32%, -8%, 0) scale3d(1, 1, 1) rotateX(16deg) rotateY(-25deg) rotateZ(7deg) skew(0, 0);-moz-transform:translate3d(32%, -8%, 0) scale3d(1, 1, 1) rotateX(16deg) rotateY(-25deg) rotateZ(7deg) skew(0, 0);-ms-transform:translate3d(32%, -8%, 0) scale3d(1, 1, 1) rotateX(16deg) rotateY(-25deg) rotateZ(7deg) skew(0, 0);transform:translate3d(32%, -8%, 0) scale3d(1, 1, 1) rotateX(16deg) rotateY(-25deg) rotateZ(7deg) skew(0, 0);transform-style:preserve-3d" class="hero_ui-4">
                        <div  class="div-block">
                            <img src="{{ asset('LandingPage/img/AppIcon.svg') }}" loading="lazy" alt=""/>
                        </div>
                        <div  class="div-block">
                            <img src="{{ asset('LandingPage/img/AppIcon.svg') }}" loading="lazy" alt=""/>
                        </div>
                        <div  class="div-block">
                            <img src="{{ asset('LandingPage/img/AppIcon.svg') }}" loading="lazy" alt=""/>
                        </div>
                    </div> -->
            </div>
        </div>
    </section>
    <section id="funcionalidades" class="section-21">
        <div class="w-layout-blockcontainer container-19 w-container">
            <div class="text-block-16">{{ $landing->section2_title }}</div>
            <div class="text-block-17">{{ $landing->section2_description }}</div>
            <div class="w-layout-blockcontainer container-18 w-container">
                <img class="image-26" src="{{ asset($landing->section2_image1) }}" alt="" />
                <img class="image-25" src="{{ asset($landing->section2_image2) }}" alt="" />
                <img class="image-24" src="{{ asset($landing->section2_image3) }}" alt="" />
            </div>
        </div>
    </section>
    <section class="section-22">
        <div class="w-layout-blockcontainer container-20 w-container">
            <div class="text-block-16">{{ $landing->section3_title }}</div>
        </div>
        <div class="w-layout-blockcontainer container-22 w-container">
            <div id="w-node-_9daa5cb6-692e-4534-4009-936725698af0-53f2f442" class="div-block-12">
                <img class="image-27" src="{{ asset($landing->section3_item1_image) }}" alt="" loading="lazy" />
                <div>
                    <div class="text-block-18">{{ $landing->section3_item1_title }}</div>
                    <div class="text-block-19">{{ $landing->section3_item1_description }}</div>
                </div>
            </div>
            <div id="w-node-_74f92362-5a9b-80c1-2f0e-366911d0839e-53f2f442" class="div-block-12">
                <img class="image-28" src="{{ asset($landing->section3_item2_image) }}" alt="" loading="lazy" />
                <div>
                    <div class="text-block-18">{{ $landing->section3_item2_title }}</div>
                    <div class="text-block-20">{{ $landing->section3_item2_description }}</div>
                </div>
            </div>
            <div id="w-node-b54cda68-6016-8184-6f96-2c3acc269358-53f2f442" class="div-block-12">
                <img class="image-29" src="{{ asset($landing->section3_item3_image) }}" alt="" loading="lazy" />
                <div>
                    <div class="text-block-18">{{ $landing->section3_item3_title }}</div>
                    <div class="text-block-21">{{ $landing->section3_item3_description }}</div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-23">
        <div class="columns-2 w-row">
            <div class="column-5 w-col w-col-6">
                <img src="{{ asset($landing->section4_image) }}" loading="lazy" width="522" alt="" class="image-33" />
            </div>
            <div class="column-7 w-col w-col-6">
                <div class="text-block-22">{{ $landing->section4_title }}</div>
                <div class="text-block-23">{{ $landing->section4_description }}</div>
                <a href="{{ $landing->section4_link }}" class="nav-link-2-copy">Veja mais</a>
            </div>
        </div>
    </section>
    <section id="taxas" class="section-24">
        <div class="columns-3 w-row">
            <div class="column-10 w-col w-col-5">
                <div class="text-block-24">{{ $landing->section5_title }}</div>
                <div class="text-block-25">
                    <br />{{ $landing->section5_description }}

                </div>
                <div class="w-layout-blockcontainer container-25 w-container">
                    <div class="div-block-13">
                        <h3 class="text-size-medium text-weight-medium">Pix</h3>
                        <div class="text-block-10">D+0</div>
                    </div>
                </div>
                <div class="w-layout-blockcontainer container-25 w-container">
                    <div class="div-block-13">
                        <h3 class="text-size-medium text-weight-medium">Taxa por transação</h3>
                        <div class="text-block-10">
                            {{ $setting->taxa_cash_in_padrao }}%
                            @if($setting->taxa_fixa_padrao > 0)
                                + <strong class="bold-text-2">R$</strong>
                                {{ number_format($setting->taxa_fixa_padrao, '2', ',', '.') }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="column-9 w-col w-col-7">
                <img src="{{ asset($landing->section5_image) }}" loading="lazy" width="504" alt="" />
            </div>
        </div>
    </section>
    <section class="se-o-premia-es">
        <div class="w-layout-blockcontainer container-3 w-container">
            <div class="div-block-3">
                <h2 class="text-white" style="color:white!important">{{ $landing->section6_title }}</h2>
                <div class="text-block-3-copy">{{ $landing->section6_description }}</div>
            </div>
            <img src="{{ asset($landing->section6_image) }}" loading="lazy" width="618" alt="" />
        </div>
    </section>
    <section class="section-9">
        <div class="w-layout-blockcontainer container-2-copy w-container">
            <div class="text-block-copy">Nos últimos 3 meses de teste...</div>
        </div>
        <div class="w-layout-blockcontainer container-7 w-container">
            <div class="div-block-6">
                <div class="text-block-6">+11 milhões</div>
                <div class="text-block-3">Movimentados</div>
            </div>
            <div class="div-block-7">
                <div class="text-block-6">+55.000</div>
                <div class="text-block-3">Transações concluídas</div>
            </div>
            <div class="div-block-8">
                <div class="text-block-6">97,3%</div>
                <div class="text-block-3">Aprovação no crédito</div>
            </div>
        </div>
    </section>
    <section id="faq" class="section-12">
        <div class="w-layout-blockcontainer container-2 w-container">
            <div class="text-block">Tem uma dúvida?</div>
            <div class="text-block-4">Clique abaixo e fale com um gerente</div>
            <a href="https://api.whatsapp.com/send?phone=55{{ $setting->contato }}&amp;text=Ol%C3%A1!%20Vim%20pelo%20site%20da%{{ env('APP_NAME') }}."
                class="sfgesjsk w-button btn-gateway">Falar com um gerente de contas</a>
        </div>
    </section>
    <!-- <section class="section-11">
            <div class="w-layout-blockcontainer container-11 w-container">
                <div class="w-layout-blockcontainer container-10 w-container">
                    <img src="{{ asset($setting->gateway_logo) }}" loading="lazy" width="128" alt="" class="image-17"/>
                </div>
                <div  class="div-block">
                    <img src="{{ asset('LandingPage/img/AppIcon.svg') }}" loading="lazy" alt=""/>
                </div>
                <div  class="div-block">
                    <img src="{{ asset('LandingPage/img/AppIcon.svg') }}" loading="lazy" alt=""/>
                </div>
                <div  class="div-block">
                    <img src="{{ asset('LandingPage/img/AppIcon.svg') }}" loading="lazy" alt=""/>
                </div>
            </div>
        </section> -->
    <section class="footer-dark-2">
        <div class="container-30">
            <div class="footer-wrapper-2">
                <a href="#">
                    <img src="{{ asset($setting->gateway_logo) }}" style="height:50px!important;width:auto!important;"
                        loading="lazy" alt="" />
                </a>
                <div class="footer-content">
                    <div id="w-node-_39f4742f-4c88-f1ec-2f43-b90322354ebd-53f2f442" class="footer-block">
                        <div class="title-small">Plataforma</div>
                        <a href="#" class="footer-link">Taxas</a>
                        <a href="#" class="footer-link">FAQ</a>
                        <a href="#" class="footer-link">Ajuda</a>
                    </div>
                    <div id="w-node-_39f4742f-4c88-f1ec-2f43-b90322354ec6-53f2f442" class="footer-block">
                        <div class="title-small">Empresa</div>
                        <a href="#" class="footer-link">Contato</a>
                        <a href="#" class="footer-link">Denúncias</a>
                    </div>
                    <div id="w-node-_39f4742f-4c88-f1ec-2f43-b90322354ecd-53f2f442" class="footer-block">
                        <div class="title-small">Sobre</div>
                        <a href="{{ url('/doc/terms.pdf') }}" target="_blank" class="footer-link">Termos de Uso</a>
                        <a href="/" class="footer-link">Política de Privacidade</a>
                        <div class="footer-social-block">
                            <a href="#" class="footer-social-link w-inline-block">
                                <img src="{{ asset('LandingPage/img/TwitterSmallLogo.svg') }}" loading="lazy" alt="" />
                            </a>
                            <a href="#" class="footer-social-link w-inline-block">
                                <img src="{{ asset('LandingPage/img/LinkdinSmallLogo.svg') }}" loading="lazy" alt="" />
                            </a>
                            <a href="#" class="footer-social-link w-inline-block">
                                <img src="{{ asset('LandingPage/img/FacebookSmallLogo.svg') }}" loading="lazy" alt="" />
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-divider-2"></div>
        <div class="footer-copyright-center-2">Todos os Direitos Reservados © {{ date('Y') }}
            {{ $setting->gateway_name }} - {{ $setting->cnpj }}
        </div>
    </section>
    <div class="navbar-logo-left">
        <div data-animation="default" data-collapse="medium" data-duration="400" data-easing="ease" data-easing2="ease"
            role="banner" class="navbar-logo-left-container shadow-three w-nav">
            <div class="container-29">
                <div class="navbar-wrapper-3">
                    <a href="#">
                        <img src="{{ asset($setting->gateway_logo) }}" loading="lazy" width="40"
                            style="height:50px!important;width:auto!important;" alt="" />
                    </a>
                    <nav role="navigation" class="nav-menu-wrapper-3 w-nav-menu">
                        <ul role="list" class="nav-menu-two w-list-unstyled">
                            <li>
                                <a href="#funcionalidades" class="nav-link-3">Funcionalidades</a>
                            </li>
                            <li>
                                <a href="#taxas" class="nav-link-3">Taxas e prazos</a>
                            </li>
                            <li>
                                <a href="#faq" class="nav-link-3">FAQ</a>
                            </li>
                            <li class="list-item">
                                <div data-hover="true" data-delay="0" class="nav-dropdown-3 w-dropdown">
                                    <div class="nav-dropdown-toggle-3-copy w-dropdown-toggle">
                                        <div class="nav-dropdown-icon-3 w-icon-dropdown-toggle"></div>
                                        <div class="text-block-29">Ajuda</div>
                                    </div>
                                    <nav class="nav-dropdown-list-3 shadow-three mobile-shadow-hide w-dropdown-list">
                                        <a href="https://api.whatsapp.com/send?phone=55{{ $setting->contato }}&amp;text=Ol%C3%A1!%20Vim%20pelo%20site%20da%20{{ $setting->gateway_name }}."
                                            class="nav-dropdown-link-3 w-dropdown-link">Sou produtor</a>
                                        <a href="#" class="nav-dropdown-link-3 w-dropdown-link">Fiz uma compra</a>
                                    </nav>
                                </div>
                            </li>
                            <li class="list-item">
                                <a href="/login">
                                    <div class="nav-dropdown-toggle-33 w-dropdown-toggle">
                                        <div class="text-block-29">Acessar</div>
                                    </div>
                                </a>
                                <!-- <div data-hover="true" data-delay="0" class="nav-dropdown-3 w-dropdown">

                                        <nav class="nav-dropdown-list-3 shadow-three mobile-shadow-hide w-dropdown-list">
                                            <a href="/login" class="nav-dropdown-link-3 w-dropdown-link">Gerenciar meu negócio</a>
                                            <a href="/Login" id="memberAreaLink" class="nav-dropdown-link-3 w-dropdown-link">Acessar Área de Membros</a>
                                        </nav>
                                    </div> -->
                            </li>
                            <li class="mobile-margin-top-12">
                                <a onclick="redirectCadastrar();" class="button-primary-3 w-button btn-gateway">
                                    <strong class="bold-text ">Comece agora</strong>
                                </a>
                            </li>
                        </ul>
                    </nav>
                    <div class="menu-button-3 w-nav-button">
                        <div class="w-icon-nav-menu"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('LandingPage/js/webflow.js') }}" type="text/javascript"></script>
    <script>

        var source = "https://www.dropbox.com/scl/fi/fsy055uxt7zzlcw862z49/cash-register-kaching-sound-effect-125042.mp3?rlkey=d6vg92r861ykf4ptqxuiwzw8c&dl=1"
        var audio = document.createElement("audio");
        //
        audio.autoplay = true;
        //
        audio.load()
        audio.addEventListener("load", function () {
            audio.play();
        }, true);
        audio.src = source;
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const isPWA = window.matchMedia('(display-mode: standalone)').matches
                || window.navigator.standalone === true;

            if (isPWA) {
                window.location.href = '/login';
                console.log("Rodando como PWA instalada!");
            } else {
                console.log("Rodando no navegador (não instalado).");
            }
        })
    </script>
    <script disable-devtool-auto src='https://cdn.jsdelivr.net/npm/disable-devtool@latest'></script>
</body>

</html>