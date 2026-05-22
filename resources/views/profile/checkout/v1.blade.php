@php
$setting = \App\Helpers\Helper::getSetting();

$produto_tipo = $checkout->produto_tipo;
$meta_active = !empty($checkout->checkout_ads_meta);
$google_active = !empty($checkout->checkout_ads_google);
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme-mode="light" data-header-styles="transparent" style="" data-menu-styles="light">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="Description" content="{{env('APP_NAME')}}">
<meta name="Author" content="{{env('APP_NAME')}}">
<meta name="keywords" content="{{env('APP_NAME')}}">
<link rel="icon" type="image/x-icon" href="{{ asset('assets/images/site_logo/logo_white.png') }}">
<title>{{ env('APP_NAME') }} - {{ $checkout->produto_name }}</title>
<link rel="icon" href="../img/logo.png" type="image/x-icon">
<link id="style" href="{{ asset("/assets/libs/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet">
<link href="{{ asset("/assets/css/styles.css") }}" rel="stylesheet">
<link href="{{ asset("/assets/icon-fonts/icons.css") }}" rel="stylesheet">
<link href="{{ asset("/assets/libs/node-waves/waves.min.css") }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset("/assets/libs/simplebar/simplebar.min.css") }}">
<link rel="stylesheet" href="{{ asset("/assets/libs/flatpickr/flatpickr.min.css") }}">
<link rel="stylesheet" href="{{ asset("/assets/libs/@simonwep/pickr/themes/nano.min.css") }}">
<link rel="stylesheet" href="{{ asset("/assets/libs/@tarekraafat/autocomplete.js/css/autoComplete.css") }}">
<link rel="stylesheet" href="{{ asset("/assets/libs/choices.js/public/assets/styles/choices.min.css") }}">
<script src="{{ asset("/assets/libs/choices.js/public/assets/scripts/choices.min.js") }}"></script>
<script src="{{ asset("/assets/js/main.js") }}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.9/jquery.inputmask.min.js" integrity="sha512-F5Ul1uuyFlGnIT1dk2c4kB4DBdi5wnBJjVhL7gQlGh46Xn0VhvD8kgxLtjdZ5YN83gybk/aASUAlpdoWUjRR3g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="/assets-checkout/css/style-checkout.css">
<style>
    :root {
        --gateway-color: {{$setting->gateway_color}};
        --checkout-color: {{$checkout->checkout_color_default ?? $setting->gateway_color}};
        --color-default: {{$checkout->checkout_color_default }};
    }
    .guide.current .guide-text .step-number,
    .qtde {
    background: var(--checkout-color) !important;
    color: #ffffff !important;
}
</style>

@if($meta_active)
<script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;
    n.push=n;
    n.loaded=!0;
    n.version='2.0';
    n.queue=[];
    t=b.createElement(e);
    t.async=!0;
    t.src=v;
    s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}
    (window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');

    fbq('init', "{{ $checkout->checkout_ads_meta }}");
    fbq('track', 'PageView');
</script>

<noscript>
    <img height="1" width="1" style="display:none"
      src="https://www.facebook.com/tr?id={{ $checkout->checkout_ads_meta }}&ev=PageView&noscript=1"
    />
</noscript>
@endif

@if($google_active)
<script async src="https://www.googletagmanager.com/gtag/js?id={{$checkout->checkout_ads_google}}"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', "{{$checkout->checkout_ads_google}}");
</script>
@endif

<body style="overflow-x:hidden;position: relative;padding:0;margin:0;width:100vw;height:100vh;background:{{$checkout->checkout_color ?? "rgb(245,242,242)"}}">
    <div id="countdown_background" style="width:100%;background-color: {{$checkout->checkout_timer_cor_fundo ?? $setting->gateway_color}}; display: {{ $checkout->checkout_timer_active ? 'block' : 'none' }};">
        <h5 class="text-center" id="texto-contador" style="padding: 12px; gap: 25px;display:flex;align-items:center;justify-content:center;gap:15px;">
            <span id="countdown_text" style="font-size: 20px !important; color: rgb(255, 255, 255);">{{ $checkout->checkout_timer_tempo ? $checkout->checkout_timer_tempo < 10 ? "0".$checkout->checkout_timer_tempo : $checkout->checkout_timer_tempo : "02" }}:00</span>
            <i id="countdown_icon" class="fa-solid fa-clock" style="font-size: 20px !important; color: rgb(255, 255, 255);"></i>
            <h8 style="font-size: 14px !important; color: rgb(255, 255, 255);" id="countdown_description">{{$checkout->checkout_timer_texto ?? "Garanta antes da oferta acabar" }}</h8>
        </h5>
    </div>
<div id="background_color" >

    <div class="container px-4">
        <div id="headerContainer" style=";background:url('{{$checkout->checkout_banner_active ? $checkout->checkout_banner : 'transparent'}}')">
                <figure style="align-content: center;">
                    <img  id="header_image1" src="{{ $checkout->checkout_header_logo ?? $setting->gateway_logo}}" alt="Logo" style="aspect-ratio: auto; display: {{ $checkout->checkout_header_logo_active ? 'block' : 'none' }};">
                </figure>

                <figure style="align-content: center;">
                    <img  id="header_image2" src="{{ $checkout->checkout_header_image ?? $setting->gateway_logo}}" alt="Logo" style="aspect-ratio: auto; display: {{ $checkout->checkout_header_image_active ? 'block' : 'none' }};">
                </figure>
        </div>
    </div>
</div>
<div id="topbar_background" style="min-height:51.60px;margin-left:0;margin-right:0;background:{{$checkout->checkout_topbar_color ?? $setting->gateway_color}};display:{{$checkout->checkout_topbar_active ? "flex" : "none" }};">
    <div id="topbar_text">
       {{ $checkout->checkout_topbar_text }}
    </div>
</div>
<div class="container">
    <div id="for_add" class="py-3 container-fluid">
        <div class="row gx-4">
            <!-- Lado A -->
            <div id="container-grid1" class="mb-4 col-lg-7 mb-lg-0">

                <!-- Steps -->
                <div class="p-3 mb-4 rounded steps-reorder-item d-flex justify-content-between bg-steps-form card-bg" style="background:{{$checkout->checkout_color_card ?? "#ffffff"}}">
                    <div id="contact_data" class="guide ativo current">
                        <div class="text-center guide-text ativo current d-flex flex-column flex-lg-row align-items-center justify-content-center">
                            <span class="step-number"><span class="number">1</span></span>
                            <div class="mt-2 mt-lg-0 ml-lg-2 step-text default-font-color">Identificação</div>
                        </div>
                    </div>
                    @if($produto_tipo == 'fisico')
                        <div id="delivery_data" class="guide">
                            <div class="text-center guide-text d-flex flex-column flex-lg-row align-items-center justify-content-center">
                                <span class="step-number"><span class="number">2</span></span>
                                <span class="mt-2 mt-lg-0 ml-lg-2 step-text default-font-color">Entrega</span>
                            </div>
                        </div>
                    @endif
                    <div id="payment_data" class="guide">
                        <div class="text-center guide-text payment-data-text d-flex flex-column flex-lg-row align-items-center justify-content-center">
                            <span class="step-number"><span class="number">{{ $produto_tipo == 'fisico' ? '3' : '2' }}</span></span>
                            <span class="mt-2 mt-lg-0 ml-lg-2 step-text default-font-color">Pagamento</span>
                        </div>
                    </div>
                </div>

                <form id="form-paid" method="POST" action="">
                    @csrf
                    <div class="body-container card-bg" style="background:{{$checkout->checkout_color_card ?? "#ffffff"}}">
                        <!-- Formulário step 1 -->
                        <div class="step-content" data-step="1">
                            <div class="row ">
                                <div class="mb-3 col-12 col-sm-6">
                                    <label for="email" class="form-label">E-mail</label>
                                    <input type="text" style="height:42px;" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="nome@email.com" required>
                                    @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                <div class="mb-3 col-12 col-sm-6">
                                    <label for="telefone" class="form-label">Telefone</label>
                                    <input type="text" style="height:42px;" class="form-control @error('telefone') is-invalid @enderror" name="telefone" placeholder="(99) 99999-9999" maxlength="15" required>
                                    @error('telefone') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                <div class="mb-3 col-12 col-sm-6">
                                    <label for="name" class="form-label">Nome completo</label>
                                    <input type="text" style="height:42px;" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Nome e Sobrenome" required>
                                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                <div class="mb-3 col-12 col-sm-6">
                                    <label for="cpf" class="form-label">CPF</label>
                                    <input type="text" style="height:42px;" class="form-control @error('cpf') is-invalid @enderror" name="cpf" placeholder="123.456.789-12" maxlength="14" required>
                                    @error('cpf') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Info segura -->
                            <div class="p-3 mt-4 info-segura">
                                <div class="mb-3 about_purchase" style="font-weight: bold;">Usamos seus dados de forma 100% segura para garantir a sua satisfação:</div>

                                <div class="mb-2 d-flex align-items-start">
                                    <img src="/assets/images/checkmarkSecurity.svg" class="me-2" alt="">
                                    <span class="sub">Enviar o seu comprovante de compra e pagamento;</span>
                                </div>

                                <div class="mb-2 d-flex align-items-start">
                                    <img src="/assets/images/checkmarkSecurity.svg" class="me-2" alt="">
                                    <span class="sub">Ativar a sua garantia de devolução caso não fique satisfeito;</span>
                                </div>

                                <div class="d-flex align-items-start">
                                    <img src="/assets/images/checkmarkSecurity.svg" class="me-2" alt="">
                                    <span class="sub">Acompanhar o andamento do seu pedido;</span>
                                </div>
                            </div>

                            <!-- Botão -->
                            <div class="mt-4 text-end">
                                <button onclick="metaAddToCart()" type="button" style="background:{{$checkout->checkout_color_default ?? $setting->gateway_color}}" class="btn btn-form-checkout-prev btn-lg btn-wave waves-effect waves-light btn-form-checkout">
                                    {{ $produto_tipo == 'fisico' ? 'IR PARA ENTREGA' : 'IR PARA PAGAMENTO' }}
                                </button>
                            </div>
                        </div>
                        @if($produto_tipo == 'fisico')
                            <!-- Step 2: Entrega -->
                            <div class="step-content d-none" data-step="2">
                                <div class="row">
                                    <div class="mb-3 col-12 col-sm-3">
                                        <label for="cep" class="form-label">CEP</label>
                                        <input type="text" class="form-control" name="cep" placeholder="00000-000" maxlength="9" required>
                                    </div>
                                    <div class="mb-3 col-12 col-sm-9">
                                        <label for="endereco" class="form-label">Endereço</label>
                                        <input type="text" class="form-control" name="endereco" placeholder="Rua Exemplo" required>
                                    </div>
                                    <div class="mb-3 col-3">
                                        <label for="numero" class="form-label">Número</label>
                                        <input type="text" class="form-control" name="numero" placeholder="123" required>
                                    </div>
                                    <div class="mb-3 col-9">
                                        <label for="complemento" class="form-label">Complemento</label>
                                        <input type="text" class="form-control" name="complemento" placeholder="Apto, bloco..." required>
                                    </div>
                                    <div class="mb-3 col-4">
                                        <label for="bairro" class="form-label">Bairro</label>
                                        <input type="text" class="form-control" name="bairro" placeholder="Centro" required>
                                    </div>
                                    <div class="mb-3 col-4">
                                        <label for="cidade" class="form-label">Cidade</label>
                                        <input type="text" class="form-control" name="cidade" placeholder="São Paulo" required>
                                    </div>
                                    <div class="mb-3 col-4">
                                        <label for="cidade" class="form-label">Estado (UF)</label>
                                        <input type="text" class="form-control" name="estado" placeholder="São Paulo" required>
                                    </div>
                                </div>

                                <div class="mt-4 d-flex justify-content-between">
                                    <button type="button" class="btn btn-outline-dark prev-step">VOLTAR</button>
                                    <button type="button" style="background:{{$checkout->checkout_color_default ?? $setting->gateway_color}}" class="btn btn-form-checkout-prev btn-lg next-step btn-form-checkout" required>IR PARA PAGAMENTO</button>
                                </div>
                            </div>
                        @endif
                        <!-- Step 3: Pagamento -->
                        <div class="step-content d-none" data-step="{{ $produto_tipo == 'fisico' ? '3' : '2' }}">
                            <div class="row">
                            <div class="mb-4 col-12 md:justify-center chk-payment-flags justify-content-sm-start selected">
                                <div id="pix-payment-0" class="chk-flag-option pixPayment selected">
                                    <div class="container-pix">
                                        <svg class="pix-icon" width="56" height="21" viewBox="0 0 56 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <g clip-path="url(#clip0_1193_2161)">
                                                <path d="M23.1182 19.1341V7.7101C23.1182 5.6045 24.8206 3.9021 26.9262 3.9021H30.2974C32.3918 3.9021 34.083 5.6045 34.083 7.6989V10.1293C34.083 12.2349 32.3806 13.9373 30.275 13.9373H25.515" stroke="#939598" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M35.0234 3.91333H36.4906C37.353 3.91333 38.0474 4.60773 38.0474 5.47013V14.0045" stroke="#939598" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M37.7341 2.59174L37.0733 1.93094C36.9053 1.76294 36.9053 1.49414 37.0733 1.33734L37.7341 0.676537C37.9021 0.508537 38.1709 0.508537 38.3277 0.676537L38.9885 1.33734C39.1565 1.50534 39.1565 1.77414 38.9885 1.93094L38.3277 2.59174C38.1597 2.74854 37.8909 2.74854 37.7341 2.59174Z" fill="#32BCAD"></path>
                                                <path d="M40.8477 3.9021H42.2925C43.0429 3.9021 43.7485 4.1933 44.2861 4.7309L47.6797 8.1245C48.1165 8.5613 48.8333 8.5613 49.2701 8.1245L52.6525 4.7421C53.1789 4.2157 53.8957 3.9133 54.6461 3.9133H55.8221" stroke="#939598" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M40.8477 13.9262H42.2925C43.0429 13.9262 43.7485 13.635 44.2861 13.0974L47.6797 9.70382C48.1165 9.26702 48.8333 9.26702 49.2701 9.70382L52.6525 13.0862C53.1789 13.6126 53.8957 13.915 54.6461 13.915H55.8221" stroke="#939598" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M14.4714 16.0877C13.7434 16.0877 13.0602 15.8077 12.545 15.2925L9.76739 12.5149C9.57699 12.3245 9.22979 12.3245 9.03939 12.5149L6.2506 15.3037C5.7354 15.8189 5.05219 16.0989 4.32419 16.0989H3.77539L7.30339 19.6269C8.40099 20.7245 10.193 20.7245 11.2906 19.6269L14.8298 16.0877H14.4714Z" fill="#32BCAD"></path>
                                                <path d="M4.31293 6.25403C5.04093 6.25403 5.72413 6.53403 6.23933 7.04923L9.02813 9.83803C9.22973 10.0396 9.55453 10.0396 9.75613 9.83803L12.5449 7.06043C13.0601 6.54523 13.7433 6.26523 14.4713 6.26523H14.8073L11.2681 2.72603C10.1705 1.62843 8.37853 1.62843 7.28093 2.72603L3.75293 6.25403H4.31293Z" fill="#32BCAD"></path>
                                                <path d="M17.7308 9.18849L15.5916 7.04929C15.5468 7.07169 15.4908 7.08289 15.4348 7.08289H14.4604C13.9564 7.08289 13.4636 7.28449 13.1164 7.64289L10.3388 10.4205C10.0812 10.6781 9.73404 10.8125 9.39804 10.8125C9.05084 10.8125 8.71483 10.6781 8.45723 10.4205L5.66844 7.63169C5.31004 7.27329 4.81724 7.07169 4.32444 7.07169H3.12604C3.07004 7.07169 3.02524 7.06049 2.98044 7.03809L0.830036 9.18849C-0.267564 10.2861 -0.267564 12.0781 0.830036 13.1757L2.96923 15.3149C3.01403 15.2925 3.05884 15.2813 3.11484 15.2813H4.31323C4.81723 15.2813 5.31003 15.0797 5.65723 14.7213L8.44604 11.9325C8.95004 11.4285 9.83484 11.4285 10.3388 11.9325L13.1164 14.7101C13.4748 15.0685 13.9676 15.2701 14.4604 15.2701H15.4348C15.4908 15.2701 15.5356 15.2813 15.5916 15.3037L17.7308 13.1645C18.8284 12.0669 18.8284 10.2861 17.7308 9.18849Z" fill="#32BCAD"></path>
                                                <path d="M26.0075 18.1599C25.8507 18.1599 25.6715 18.1934 25.4811 18.2382V18.9326C25.6043 18.9774 25.7499 18.9998 25.8843 18.9998C26.2315 18.9998 26.3995 18.8766 26.3995 18.5742C26.4107 18.2942 26.2763 18.1599 26.0075 18.1599ZM25.3691 19.459V18.0814H25.4699L25.4811 18.1374C25.6379 18.1038 25.8619 18.0479 26.0299 18.0479C26.1643 18.0479 26.2875 18.0703 26.3883 18.1486C26.5115 18.2494 26.5451 18.4062 26.5451 18.5742C26.5451 18.7534 26.4891 18.9214 26.3211 19.0222C26.2091 19.0894 26.0523 19.1118 25.9179 19.1118C25.7723 19.1118 25.6379 19.0894 25.5035 19.0446V19.4478H25.3691V19.459Z" fill="#939598"></path>
                                                <path d="M27.4182 18.1599C27.071 18.1599 26.9142 18.2718 26.9142 18.5742C26.9142 18.8766 27.071 18.9998 27.4182 18.9998C27.7654 18.9998 27.9222 18.8878 27.9222 18.5854C27.911 18.2942 27.7654 18.1599 27.4182 18.1599ZM27.8662 19.011C27.7542 19.0894 27.5974 19.123 27.4182 19.123C27.239 19.123 27.0822 19.1006 26.9702 19.011C26.847 18.9214 26.791 18.7758 26.791 18.5854C26.791 18.4062 26.847 18.2494 26.9702 18.1599C27.0822 18.0815 27.239 18.0479 27.4182 18.0479C27.5974 18.0479 27.7542 18.0703 27.8662 18.1599C28.0006 18.2494 28.0454 18.4062 28.0454 18.5854C28.0454 18.7646 27.9894 18.9214 27.8662 19.011Z" fill="#939598"></path>
                                                <path d="M29.5131 19.0893L29.1211 18.2493H29.1099L28.7291 19.0893H28.6283L28.2139 18.0813H28.3483L28.6955 18.9213H28.7067L29.0763 18.0813H29.1883L29.5691 18.9213H29.5803L29.9163 18.0813H30.0395L29.6251 19.0893H29.5131Z" fill="#939598"></path>
                                                <path d="M30.7904 18.1599C30.4656 18.1599 30.3536 18.3055 30.3424 18.5071H31.2496C31.2272 18.2831 31.1152 18.1599 30.7904 18.1599ZM30.7792 19.1119C30.5888 19.1119 30.4656 19.0895 30.3648 18.9999C30.2416 18.8991 30.208 18.7535 30.208 18.5855C30.208 18.4287 30.264 18.2495 30.3984 18.1599C30.5104 18.0815 30.6448 18.0591 30.7904 18.0591C30.9248 18.0591 31.0704 18.0703 31.1936 18.1599C31.3392 18.2607 31.3728 18.4287 31.3728 18.6191H30.3424C30.3424 18.8319 30.4096 19.0111 30.8016 19.0111C30.992 19.0111 31.16 18.9775 31.3168 18.9551V19.0559C31.1488 19.0783 30.9584 19.1119 30.7792 19.1119Z" fill="#939598"></path>
                                                <path d="M31.6973 19.0894V18.0815H31.7981L31.8093 18.1374C32.0221 18.0815 32.1229 18.0479 32.3133 18.0479H32.3245V18.1599H32.2909C32.1341 18.1599 32.0333 18.1823 31.8205 18.2383V19.0782L31.6973 19.0894Z" fill="#939598"></path>
                                                <path d="M33.0072 18.1599C32.6824 18.1599 32.5704 18.3055 32.5592 18.5071H33.4664C33.444 18.2831 33.332 18.1599 33.0072 18.1599ZM32.996 19.1119C32.8056 19.1119 32.6824 19.0895 32.5816 18.9999C32.4584 18.8991 32.4248 18.7535 32.4248 18.5855C32.4248 18.4287 32.4808 18.2495 32.6152 18.1599C32.7272 18.0815 32.8616 18.0591 33.0072 18.0591C33.1416 18.0591 33.2872 18.0703 33.4104 18.1599C33.556 18.2607 33.5896 18.4287 33.5896 18.6191H32.5592C32.5592 18.8319 32.6264 19.0111 33.0184 19.0111C33.2088 19.0111 33.3768 18.9775 33.5336 18.9551V19.0559C33.3656 19.0783 33.1752 19.1119 32.996 19.1119Z" fill="#939598"></path>
                                                <path d="M34.8673 18.227C34.7441 18.1822 34.5984 18.1598 34.464 18.1598C34.1169 18.1598 33.9489 18.283 33.9489 18.5854C33.9489 18.8766 34.0833 18.9998 34.352 18.9998C34.5088 18.9998 34.6881 18.9662 34.8784 18.9214V18.227H34.8673ZM34.8896 19.0894L34.8784 19.0334C34.7217 19.067 34.4976 19.123 34.3296 19.123C34.1952 19.123 34.0721 19.1006 33.9713 19.0222C33.8481 18.9214 33.8145 18.7646 33.8145 18.5966C33.8145 18.4174 33.8705 18.2494 34.0385 18.1598C34.1505 18.0926 34.3072 18.0702 34.4416 18.0702C34.576 18.0702 34.7216 18.0926 34.856 18.1374V17.6782H34.9793V19.1118L34.8896 19.0894Z" fill="#939598"></path>
                                                <path d="M36.7146 18.1597C36.5578 18.1597 36.3786 18.1933 36.1882 18.2381V18.9325C36.3114 18.9773 36.457 18.9997 36.5914 18.9997C36.9386 18.9997 37.1066 18.8765 37.1066 18.5741C37.1066 18.2941 36.9722 18.1597 36.7146 18.1597ZM37.017 19.0221C36.905 19.0893 36.7482 19.1117 36.6138 19.1117C36.4682 19.1117 36.3114 19.0893 36.1658 19.0333L36.1546 19.0781H36.0762V17.6445H36.1994V18.1261C36.3562 18.0925 36.5802 18.0477 36.737 18.0477C36.8714 18.0477 36.9946 18.0701 37.0954 18.1485C37.2186 18.2493 37.2522 18.4061 37.2522 18.5741C37.241 18.7645 37.1738 18.9325 37.017 19.0221Z" fill="#939598"></path>
                                                <path d="M37.3871 19.4703V19.3583C37.4431 19.3695 37.4991 19.3695 37.5327 19.3695C37.6783 19.3695 37.7679 19.3247 37.8463 19.1567L37.8799 19.0783L37.3535 18.0703H37.4879L37.9359 18.9439H37.9471L38.3727 18.0703H38.5071L37.9359 19.2015C37.8351 19.4031 37.7231 19.4703 37.5103 19.4703C37.4879 19.4815 37.4431 19.4815 37.3871 19.4703Z" fill="#939598"></path>
                                                <path d="M40.1426 18.5069H39.7506V18.8653H40.1426C40.4114 18.8653 40.5122 18.8317 40.5122 18.6861C40.5234 18.5293 40.3778 18.5069 40.1426 18.5069ZM40.0754 17.9357H39.7618V18.2941H40.0866C40.3554 18.2941 40.4562 18.2605 40.4562 18.1149C40.445 17.9581 40.3106 17.9357 40.0754 17.9357ZM40.6802 18.9885C40.5346 19.0781 40.3666 19.0893 40.0418 19.0893H39.4482V17.7229H40.0306C40.2994 17.7229 40.4674 17.7229 40.613 17.8125C40.7138 17.8685 40.7474 17.9693 40.7474 18.0813C40.7474 18.2269 40.6914 18.3165 40.5346 18.3837V18.3949C40.7138 18.4397 40.8258 18.5293 40.8258 18.7197C40.8258 18.8429 40.781 18.9325 40.6802 18.9885Z" fill="#939598"></path>
                                                <path d="M42.0239 18.6638C41.9007 18.6526 41.7887 18.6526 41.6655 18.6526C41.4639 18.6526 41.3855 18.6974 41.3855 18.787C41.3855 18.8766 41.4415 18.9214 41.5983 18.9214C41.7327 18.9214 41.8895 18.8878 42.0239 18.8654V18.6638ZM42.0799 19.0894L42.0687 19.0334C41.9007 19.0782 41.6991 19.123 41.5199 19.123C41.4079 19.123 41.2959 19.1118 41.2175 19.0446C41.1391 18.9886 41.1055 18.899 41.1055 18.7982C41.1055 18.6862 41.1503 18.5742 41.2735 18.5294C41.3743 18.4846 41.5199 18.4734 41.6543 18.4734C41.7551 18.4734 41.9007 18.4846 42.0239 18.4846V18.4622C42.0239 18.3054 41.9231 18.2494 41.6319 18.2494C41.5199 18.2494 41.3855 18.2606 41.2623 18.2718V18.0703C41.4079 18.0591 41.5647 18.0479 41.6991 18.0479C41.8783 18.0479 42.0575 18.0591 42.1695 18.1374C42.2815 18.2158 42.3039 18.3278 42.3039 18.4846V19.0782L42.0799 19.0894Z" fill="#939598"></path>
                                                <path d="M43.6141 19.0895V18.5295C43.6141 18.3503 43.5245 18.2831 43.3565 18.2831C43.2333 18.2831 43.0765 18.3167 42.9421 18.3503V19.0895H42.6621V18.0815H42.8861L42.8973 18.1487C43.0765 18.1039 43.2669 18.0591 43.4349 18.0591C43.5581 18.0591 43.6813 18.0815 43.7821 18.1599C43.8605 18.2271 43.8941 18.3279 43.8941 18.4735V19.0895H43.6141Z" fill="#939598"></path>
                                                <path d="M44.6895 19.1119C44.5551 19.1119 44.4207 19.0895 44.3199 19.0111C44.1967 18.9103 44.1631 18.7535 44.1631 18.5855C44.1631 18.4287 44.2191 18.2495 44.3647 18.1599C44.4879 18.0815 44.6447 18.0591 44.8127 18.0591C44.9247 18.0591 45.0367 18.0703 45.1711 18.0815V18.2943C45.0703 18.2831 44.9471 18.2719 44.8463 18.2719C44.5775 18.2719 44.4543 18.3503 44.4543 18.5855C44.4543 18.7983 44.5439 18.8991 44.7679 18.8991C44.8911 18.8991 45.0479 18.8767 45.1935 18.8431V19.0559C45.0255 19.0783 44.8463 19.1119 44.6895 19.1119Z" fill="#939598"></path>
                                                <path d="M46.0109 18.2607C45.7421 18.2607 45.6301 18.3391 45.6301 18.5743C45.6301 18.7983 45.7421 18.8991 46.0109 18.8991C46.2797 18.8991 46.3917 18.8207 46.3917 18.5855C46.3917 18.3615 46.2797 18.2607 46.0109 18.2607ZM46.4925 19.0111C46.3693 19.0895 46.2125 19.1119 46.0109 19.1119C45.8093 19.1119 45.6525 19.0895 45.5293 19.0111C45.3949 18.9215 45.3389 18.7647 45.3389 18.5855C45.3389 18.4063 45.3837 18.2495 45.5293 18.1599C45.6525 18.0815 45.8093 18.0591 46.0109 18.0591C46.2125 18.0591 46.3693 18.0815 46.4925 18.1599C46.6269 18.2495 46.6829 18.4063 46.6829 18.5855C46.6829 18.7647 46.6269 18.9215 46.4925 19.0111Z" fill="#939598"></path>
                                                <path d="M48.3062 19.1118C48.1382 19.1118 47.9478 19.0894 47.8134 18.9662C47.6454 18.8318 47.6006 18.619 47.6006 18.395C47.6006 18.1934 47.6678 17.9582 47.8806 17.8126C48.0486 17.7006 48.2502 17.6782 48.463 17.6782C48.6198 17.6782 48.7654 17.6894 48.9446 17.7006V17.947C48.799 17.9358 48.6198 17.9246 48.4854 17.9246C48.0934 17.9246 47.9366 18.0702 47.9366 18.3838C47.9366 18.7086 48.0934 18.8542 48.3734 18.8542C48.5638 18.8542 48.7654 18.8206 48.9782 18.7758V19.0222C48.7542 19.067 48.5302 19.1118 48.3062 19.1118Z" fill="#939598"></path>
                                                <path d="M49.7844 18.227C49.5492 18.227 49.4596 18.3054 49.4484 18.4622H50.1316C50.1204 18.3054 50.0196 18.227 49.7844 18.227ZM49.7396 19.1118C49.5716 19.1118 49.426 19.0894 49.314 18.9998C49.1908 18.899 49.1572 18.7534 49.1572 18.5742C49.1572 18.4174 49.202 18.2494 49.3476 18.1486C49.4708 18.0591 49.6276 18.0479 49.7844 18.0479C49.93 18.0479 50.098 18.0591 50.2212 18.1486C50.378 18.2606 50.4004 18.4398 50.4004 18.6414H49.4484C49.4596 18.7982 49.538 18.899 49.818 18.899C49.9972 18.899 50.1876 18.8766 50.3556 18.843V19.0446C50.154 19.0782 49.9412 19.1118 49.7396 19.1118Z" fill="#939598"></path>
                                                <path d="M51.6561 19.0895V18.5295C51.6561 18.3503 51.5665 18.2831 51.3985 18.2831C51.2753 18.2831 51.1185 18.3167 50.9841 18.3503V19.0895H50.7041V18.0815H50.9281L50.9393 18.1487C51.1185 18.1039 51.3089 18.0591 51.4769 18.0591C51.6001 18.0591 51.7233 18.0815 51.8241 18.1599C51.9025 18.2271 51.9361 18.3279 51.9361 18.4735V19.0895H51.6561Z" fill="#939598"></path>
                                                <path d="M52.7308 19.1118C52.5964 19.1118 52.4732 19.0782 52.406 18.9662C52.3612 18.899 52.3276 18.7982 52.3276 18.6638V18.283H52.126V18.0702H52.3276L52.3612 17.7678H52.6076V18.0702H52.9996V18.283H52.6076V18.6078C52.6076 18.6862 52.6188 18.7534 52.63 18.7982C52.6636 18.8654 52.7308 18.8878 52.8092 18.8878C52.8764 18.8878 52.9548 18.8766 53.0108 18.8654V19.067C52.9324 19.1006 52.8204 19.1118 52.7308 19.1118Z" fill="#939598"></path>
                                                <path d="M53.2803 19.0895V18.0815H53.5043L53.5155 18.1487C53.7059 18.0927 53.8403 18.0591 54.0195 18.0591H54.0531V18.2943H53.9523C53.8179 18.2943 53.7059 18.3055 53.5603 18.3503V19.0895H53.2803Z" fill="#939598"></path>
                                                <path d="M55.0713 18.6638C54.9481 18.6526 54.8361 18.6526 54.7129 18.6526C54.5113 18.6526 54.4329 18.6974 54.4329 18.787C54.4329 18.8766 54.4889 18.9214 54.6457 18.9214C54.7801 18.9214 54.9369 18.8878 55.0713 18.8654V18.6638ZM55.1385 19.0894L55.1273 19.0334C54.9593 19.0782 54.7577 19.123 54.5785 19.123C54.4665 19.123 54.3545 19.1118 54.2761 19.0446C54.1977 18.9886 54.1641 18.899 54.1641 18.7982C54.1641 18.6862 54.2089 18.5742 54.3321 18.5294C54.4329 18.4846 54.5785 18.4734 54.7129 18.4734C54.8137 18.4734 54.9593 18.4846 55.0825 18.4846V18.4622C55.0825 18.3054 54.9817 18.2494 54.6905 18.2494C54.5785 18.2494 54.4441 18.2606 54.3209 18.2718V18.0703C54.4665 18.0591 54.6233 18.0479 54.7577 18.0479C54.9369 18.0479 55.1161 18.0591 55.2281 18.1374C55.3401 18.2158 55.3625 18.3278 55.3625 18.4846V19.0782L55.1385 19.0894Z" fill="#939598"></path>
                                                <path d="M55.7207 17.6558H56.0007V19.0894H55.7207V17.6558Z" fill="#939598"></path>
                                            </g>
                                            <defs>
                                                <clipPath id="clip0_1193_2161">
                                                    <rect width="56" height="19.9136" fill="white" transform="translate(0 0.543213)"></rect>
                                                </clipPath>
                                            </defs>
                                        </svg>
                                        @if(count($checkout->bumps) > 0)
                                            <span class="chk-flag-option-discount ">
                                                5% OFF
                                            </span>
                                        @endif
                                    </div>
                                    <div id="pix_data_payment" class="p-4 tab-pane fade method_data_payment pix show active" role="tabpanel" aria-labelledby="pix_data_payment-tab">
                                        <div class="mb-2 row no-gutters " style="display: flex">
                                            <div class="col-5 col-sm-3">
                                                <div class="p-1 mt-1 d-flex justify-content-center percent-discount mt-md-0">
                                                    5% OFF
                                                </div>
                                            </div>
                                            <div class="col-7 col-sm-9 d-flex align-items-center">
                                                <div class="row no-gutters discount-text">
                                                    <div class="col-12">
                                                        Garanta&nbsp;<span class="economize-value"> R$ </span>
                                                        <span id="discount_pix_span" class="mr-1 economize-value">10,35</span>
                                                        &nbsp;de desconto pagando via Pix

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row d-flex justify-content-end">
                                            <div class="col-12">
                                                <p class="obs">
                                                    Ao selecionar o Pix, você será encaminhado para um ambiente seguro para finalizar
                                                    seu pagamento.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3 col-12">
                                        @foreach($checkout->bumps as $key => $bump)
                                            <div class="p-3 mb-4 position-relative container-item" data-id="{{ $bump->id }}">
                                                <div class="row no-gutters align-items-start">
                                                    <!-- Imagem -->
                                                    <div class="text-center col-4 col-sm-3 col-md-2">
                                                        <img
                                                            src="{{ $bump->image }}"
                                                            class="rounded img-fluid"
                                                            style="max-height: 100px; object-fit: cover;"
                                                            alt="{{ $bump->nome }}"
                                                        >
                                                    </div>

                                                    <!-- Conteúdo -->
                                                    <div class="pl-3 col-8 col-sm-9 col-md-10">
                                                        <h6 class="mb-1 font-weight-bold" style="font-size: 1.1rem;">
                                                            {{ $bump->nome }}
                                                        </h6>
                                                        <p class="mb-2 text-muted">
                                                            {{ $bump->descricao }}
                                                        </p>

                                                        <p class="mb-1 text-danger" style="text-decoration: line-through;">
                                                            {{ "R$ " . number_format($bump->valor_de, 2, ',', '.') }}
                                                        </p>
                                                        <p class="text-success h5 font-weight-bold">
                                                            {{ "R$ " . number_format($bump->valor_por, 2, ',', '.') }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="position-absolute" style="bottom: -20px; right: 10px;">
                                                    <button type="button"
                                                        class="btn-add-bump btn btn-warning btn-lg btn-block font-weight-bold d-flex align-items-center justify-content-center toggle-bump"
                                                        style="font-size: 1.2rem;"
                                                        data-id="{{ $bump->id }}"> <!-- Pode ser o ID real se preferir -->
                                                        <i class="fa-solid fa-plus"></i>&nbsp;
                                                        <span>PEGAR OFERTA</span>
                                                    </button>
                                                </div>
                                                <span class="ob-purchased">
                                                    <span>OFERTA ADQUIRIDA</span>
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-dark prev-step">VOLTAR</button>
                                <button type="submit" style="background:{{$checkout->checkout_color_default ?? $setting->gateway_color}}" class="btn btn-form-checkout-prev btn-form-checkout btn-lg">FINALIZAR COMPRA</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Lado B -->
            <div id="container-grid2" class="p-2 pt-0 col-lg-5">
                <div class="p-0 rounded w-100 h-100">
                    <div class="p-2 pt-0 mb-4 rounded item produto-reorder-item justify-content-between bg-steps-form card-bg" style="background:{{$checkout->checkout_color_card ?? "#ffffff"}}">
                        <div class="row ">
                            <div class="col-12">
                                <div class="p-2 mb-4 card produto card-bg" style="background:{{$checkout->checkout_color_card ?? "#ffffff"}}">
                                    <div class="row justify-content-between sidetop">
                                        <div class="pl-2 col-6 cart">
                                            Seu carrinho
                                            <span class="pt-2 pr-2 small collapse collapse-toggle d-lg-none">
                                                Informações da sua compra
                                            </span>
                                        </div>

                                        <div class="col-6">
                                            <div class="d-flex align-items-center justify-content-end h-100" style="position: relative;">
                                                <span class="valor_total collapse collapse-toggle" style="z-index: 2">R$ 207,00</span>
                                                {{-- <i class="fa-solid fa-cart-shopping" style="color:{{$checkout->checkout_color_default }};position: absolute;font-size:24px;bottom:-16.5px;right:-2px;z-index:1;"></i> --}}
                                                <div class="text-center qtde">1</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="purchase-summary__body" class="mt-2 d-lg-block">
                                        <div class="mb-3 product-list text-start">
                                            <div class="product-grid">
                                                <div class="d-flex ">
                                                    <img class="product-img" src="{{ $checkout->produto_image ?? '/assets/images/product_default.png' }}" onerror="this.src='https://cloudfox-files.s3.amazonaws.com/produto.svg'">
                                                </div>
                                            <div>
                                            <p class="text-lg text-start ellipsis-h" style="color:black;font-size: 18px;font-weight:bold;" > {{ $checkout->produto_name }}<p>
                                            <p class="text-start ellipsis-h" style="font-size:14px;margin-top:-15px;"> {{ $checkout->produto_descricao }}<p>
                                        </div>
                                        <div class="mt-3 d-flex align-items-center justify-content-end">
                                            <div class="input-number">
                                                <button class="btn-sub" required>
                                                    <img src="/assets/images/minus.svg">
                                                </button>
                                                <span class="number-qtd" readonly>1</span>
                                                <button type="button" class="btn-add" required>
                                                    <img src="/assets/images/plus.svg">
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                 <div class="ob-preview">
                                    <div class="ob-purchased-info"></div>
                                    <div class="ob-preview-content"></div>
                                </div>

                                <div>
                                    <hr>
                                </div>
                                    <div class="mb-1 cp-subtotal">
                                        <div class="p-0 mb-2 row justify-content-between">
                                            <div class="text-start col-6">
                                                <span class="subtotal">Subtotal</span>
                                            </div>
                                                <div class="text-end col-6">
                                                    <span class="text-end subtotal">
                                                        R$ <span class="subtotal-value">{{ number_format($checkout->produto_valor, '2',',','.') }}</span>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="p-0 mb-2 row justify-content-between">
                                                <div class="text-start col-6">
                                                    <span class="subtotal">Frete</span>
                                                </div>
                                                <div class="text-end col-6">
                                                    <span class="text-end subtotal valor_frete" id="valor_frete"> - </span>
                                                </div>
                                            </div>
                                            <div id="div_progressive_discount" class="p-0 mb-2 row justify-content-between progressive-discount-class" style="display:none">
                                                <input type="hidden" id="progressive_discount">
                                                <div class="text-start col-7">
                                                    <span class="subtotal">Desconto progressivo</span>
                                                </div>
                                                <div class="text-end col-5">
                                                    <span class="subtotal discount-span progressive-discount-span-text"></span>
                                                </div>
                                            </div>
                                            <div class="p-0 mb-1 row justify-content-between d-none automatic-discount">
                                                <div class="text-start col-6">
                                                    <span class="text-automatic-discount subtotal">Desconto cartão</span>
                                                </div>
                                                <div class="text-end col-6">
                                                    <span class="subtotal value-automatic-discount discount-span"> R$ 0 </span>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="mt-0">
                                        <div class="cp-total" style="position: relative">
                                            <div class="row justify-content-between total_container">
                                                <div class="text-start col-6">Total</div>
                                                <div class="text-end col-6">
                                                    R$&nbsp;
                                                    <span class="valor_total">{{ number_format($checkout->produto_valor, '2',',','.') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4 security d-lg-flex align-items-center justify-content-lg-center justify-content-center">
                        <button class="text-center btn btn-security">
                            <img src="/assets/images/safe.svg" alt="Green Shield Icon">
                            &nbsp;Ambiente seguro
                        </button>
                    </div>
                    <div id="depoimento-visual-list" class="py-2">
                            @foreach($checkout->depoimentos as $depoimento)
                                <div class="d-lg-block" style="">
                                    <div class="mb-0 card card-bg depoimento-container" style="border-bottom: 1px solid rgb(231, 231, 231)background:{{$checkout->checkout_color_card ?? "#ffffff"}}">
                                        <div class="card-body ">
                                            <div class="row no-gutters">
                                                <div class="col-8 d-flex">
                                                    <img class="rounded-circle preview-image" style="object-fit: cover;width:48px!important;height:48px!important;" src="{{ $depoimento->avatar }}">
                                                    <span class="pt-1 pl-2 text-ccblack d-inline-block preview-nome" style="width: 80%;">{{ $depoimento->nome }}</span>
                                                </div>
                                                <div class="pt-1 text-end d-none d-md-flex col-4 align-items-center justify-content-end">
                                                    <div class="stars d-flex" style="color: #f8ce1c">
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="mt-2 text-start review-desc col review-description preview-depoimento">{{ $depoimento->depoimento }}</div>
                                            </div>
                                            <div class="mt-4 d-flex d-md-none align-items-center justify-content-start">
                                                <div class="stars d-flex" style="color: #f8ce1c">
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <div id="footer" class="w-full p-2 pt-4 mb-0 text-center card card-bg d-flex flex-column align-items-center footer-cfx" style="background:{{$checkout->checkout_color_card ?? '#ffffff'}}">
        <p class="mb-2">Formas de pagamento</p>
        <div class="d-flex" style="gap: 0.5rem;">
            <img src="https://pay.ment-deveuperdeu.shop/assets/img/card-pix.svg" width="44">
            </div>
            <p class="mt-4">© {{ date('Y') }} All rights reserved.</p>
            <div class="mt-4 security d-none sm-flex">
                <button class="btn btn-security">
                <img src="/assets/images/safe.svg" alt="Green Shield Icon"> Ambiente seguro </button>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/5.0.8/inputmask.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        window.tempo = Number("{{ $checkout->checkout_timer_tempo }}");
        window.produto_valor = parseFloat("{{ $checkout->produto_valor }}");
        window.checkout_color_default = "{{ $checkout->checkout_color_default }}";
        window.bumps = @Json($checkout->bumps);
        window.checkout_id = Number("{{ $checkout->id }}");
      	window.endereco_active = "{{ $checkout->produto_tipo }}" === 'fisico';
        window.meta_active = Boolean("{{ $meta_active }}");
        console.log(window.meta_active)
      //console.log(window.endereco_active);
    </script>
    <script>
    function metaAddToCart(){
        fbq('track', 'AddToCart');
    }
    </script>
  <script type="text/javascript" src="{{ asset('assets-checkout/js/checkout.js'.'?'.uniqid()) }}"></script>
    </body>
</html>


