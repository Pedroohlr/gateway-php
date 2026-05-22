@php
$setting = \App\Helpers\Helper::getSetting();
@endphp
<style>
#headerContainer {
    display:flex;justify-content:space-between;align-items:center;
    height: 150px;
}
.checkout-preview-content {
    @media screen and (min-width: 1400px) {
        & figure.main-image {
            width: calc(100% - 4rem) !important;
        }
    }
}
.main-image {
    width: 100%;
    max-width: 1040px;
    margin-left: 0px;
    margin-right: 0px;
}
figure {
    max-width: 1040px;
    margin: 0px 1.5rem;
    border-radius: 2px;
    overflow: hidden;
}
#topbar_background {
    height: 51.60px;
    width: 100%;
    display: flex;
    align-items:center;
    justify-content: center;
}
#topbar_text {
    color: white;
    font-size: 18px;
    font-weight: bold;
}
.guide.current .guide-text .step-number {
    background: {{$checkout->checkout_color_default ?? $setting->gateway_color}};
    color: #ffffff !important;
}

.guide-text .step-number {
    float: left;
    display: flex
;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 16px;
    position: static;
    width: 32px;
    height: 32px;
    left: 0;
    top: 0;
    background: #f4f6fb;
    border-radius: 32px;
    flex: none;
    order: 0;
    flex-grow: 0;
}
.bg-steps-form {
    background: rgb(255, 255, 255);
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
    margin-top: 20px;

}
.step-number {
    margin-right: 10px;
}

.body-container {
    background: rgb(255, 255, 255);
    border-radius: 5px;
    padding: 20px;
}
.cart {
    font-weight: 600;
    font-size: 16px;
    color: #313c52;
}
.qtde {
    width: 20px;
    height: 20px;
    border-radius: 50px;
    background: {{$checkout->checkout_color_default ?? $setting->gateway_color}};
    color: #ffffff !important;
}
.btn-security {
    color: #313c52 !important;
    cursor: pointer;
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
    padding: 8px 24px;
    position: static;
    width: 178px;
    height: 40px;
    left: 85px;
    top: 638px;
    background: #fff;
    border: 1px solid #f7f6f8;
    box-sizing: border-box;
    border-radius: 60px;
    font-style: normal;
    font-weight: 600;
    font-size: 12px;
    line-height: 14px;
    outline: 0;
}
.input-number {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #fff;
    width: 80px;
    height: 32px;
    border-radius: 20px;
}

.input-number button:first-child {
    border-radius: 30px 0 0 30px;
    border-top: solid 1px #e1e1e1;
    border-left: solid 1px #e1e1e1;
    border-bottom: solid 1px #e1e1e1;
}
.product-list .btn-add, .product-list .btn-sub {
    cursor: pointer;
}
.input-number button {
    display: flex;
    justify-content: center;
    align-items: center;
    border: none;
    outline: 0;
    width: 30px;
    height: 30px;
    background: #fff;
    transition: all .5s;
}
.input-number button:last-child {
    border-radius: 0 30px 30px 0;
    border-top: solid 1px #e1e1e1;
    border-right: solid 1px #e1e1e1;
    border-bottom: solid 1px #e1e1e1;
}
.input-number input {
    width: 30px;
    height: 30px;
    padding: 0;
    outline: 0;
    box-shadow: none;
    text-align: center;
    font-family: sans-serif;
    font-size: 14px;
    -webkit-appearance: textfield;
    -webkit-background-clip: padding-box;
    -moz-appearance: textfield;
    -moz-background-clip: padding-box;
    appearance: textfield;
    background-clip: padding-box;
    border: none;
    border-radius: 0;
    border-top: solid 1px #e1e1e1;
    border-bottom: solid 1px #e1e1e1;
}
.product-img {
    width: 50px;
    height:50px;
}
.product-grid {
    display: grid;
    grid-column-gap: 12px;
    grid-row-gap: 10px;
    grid-template-columns: 50px auto 80px;
    margin-bottom: 16px;
    color: #313c52;
}
@media screen and (max-width: 720px){
    .step-text {
        display: none;
    }
    #topbar_text {
        font-size: 12px;
    }
}

.info-segura {
    border: 2px dashed #d6d6d6;
    padding: 5px;
    border-radius: 10px;
}
.produto {
    border: none;
}

.btn-form-checkout-prev {
    color: white;
    font-weight: bold;
}

.btn-form-checkout-prev:hover {
    color: white;
    font-weight: bold;
}
.step-number,
.step-text {
    font-weight: bold;
}
</style>

<div id="background_color" class="container h-full w-100" style="min-height: 100vh;background:{{$checkout->checkout_color ?? "rgb(245,242,242)"}}">
    <div id="countdown_background" style="width:100%;background-color: {{$checkout->checkout_timer_cor_fundo ?? $setting->gateway_color}}; display: {{ $checkout->checkout_timer_active ? 'block' : 'none' }};">
        <h5 class="text-center" style="padding: 12px; gap: 25px;display:flex;align-items:center;justify-content:center;gap:15px;">
            <span id="countdown_text" style="font-size: 20px !important; color: rgb(255, 255, 255);">{{ $checkout->checkout_timer_tempo ? $checkout->checkout_timer_tempo < 10 ? "0".$checkout->checkout_timer_tempo : $checkout->checkout_timer_tempo : "02" }}:00</span>
            <i id="countdown_icon" class="fa-solid fa-clock" style="font-size: 20px !important; color: rgb(255, 255, 255);"></i>
            <h8 style="font-size: 14px !important; color: rgb(255, 255, 255);" id="countdown_description">{{$checkout->checkout_timer_texto ?? "Garanta antes da oferta acabar" }}</h8>
        </h5>
    </div>
    <div class="py-2 logo" id="headerContainer">

        <figure style="align-content: center;">
            <img width="120px" height="60px" id="header_image1" src="{{ $checkout->checkout_header_logo ?? $setting->gateway_logo}}" alt="Logo" style="aspect-ratio: auto; display: {{ $checkout->checkout_header_logo_active ? 'block' : 'none' }};">
        </figure>

        <figure style="align-content: center;">
            <img  width="120px" height="60px" id="header_image2" src="{{ $checkout->checkout_header_image ?? $setting->gateway_logo}}" alt="Logo" style="aspect-ratio: auto; display: {{ $checkout->checkout_header_image_active ? 'block' : 'none' }};">
        </figure>

    </div>
    <div id="topbar_background" style="background:{{$checkout->checkout_topbar_color ?? $setting->gateway_color}};display:{{$checkout->checkout_topbar_active ? "flex" : "none" }};">
        <div id="topbar_text">
            APROVEITE O DESCONTO DE 5% VIA PIX!
        </div>
    </div>
    <div id="for_add" class="py-3 container-fluid">
        <div class="row gx-4">
            <!-- Lado A -->
            <div class="mb-4 col-lg-7 mb-lg-0">
                <!-- Steps -->
                <div class="p-3 mb-4 rounded d-flex justify-content-between bg-steps-form card-bg" style="background:{{$checkout->checkout_color_card ?? "#ffffff"}}">
                    <div id="contact_data" class="guide ativo current">
                        <div class="guide-text ativo current d-flex align-items-center">
                            <span class="step-number"><span class="number">1</span></span>
                            <div class="ms-2 step-text default-font-color ">Identificação</div>
                        </div>
                    </div>
                    <div id="delivery_data" class="guide">
                        <div class="guide-text d-flex align-items-center">
                            <span class="step-number"><span class="number">2</span></span>
                            <span class="ms-2 step-text default-font-color">Entrega</span>
                        </div>
                    </div>
                    <div id="payment_data" class="guide">
                        <div class="guide-text payment-data-text d-flex align-items-center">
                            <span class="step-number"><span class="number">3</span></span>
                            <span class="ms-2 step-text default-font-color">Pagamento</span>
                        </div>
                    </div>
                </div>

                <div class="body-container card-bg" style="background:{{$checkout->checkout_color_card ?? "#ffffff"}}">
                    <!-- Formulário step 1 -->
                    <div class="step-content" data-step="1">
                        <div class="row ">
                            <div class="mb-3 col-12 col-sm-6">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="text" style="height:42px;" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="nome@email.com" disabled>
                                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3 col-12 col-sm-6">
                                <label for="telefone" class="form-label">Telefone</label>
                                <input type="text" style="height:42px;" class="form-control @error('telefone') is-invalid @enderror" name="telefone" placeholder="(99) 99999-9999" maxlength="15" disabled>
                                @error('telefone') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3 col-12 col-sm-6">
                                <label for="name" class="form-label">Nome completo</label>
                                <input type="text" style="height:42px;" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Nome e Sobrenome" disabled>
                                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3 col-12 col-sm-6">
                                <label for="cpf" class="form-label">CPF</label>
                                <input type="text" style="height:42px;" class="form-control @error('cpf') is-invalid @enderror" name="cpf" placeholder="123.456.789-12" maxlength="14" disabled>
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
                            <button type="button" style="background:{{$checkout->checkout_color_default ?? $setting->gateway_color}}" class="btn btn-form-checkout-prev btn-lg btn-wave waves-effect waves-light btn-form-checkout">
                                IR PARA ENTREGA
                            </button>
                        </div>
                    </div>

                    <!-- Step 2: Entrega -->
                    <div class="step-content d-none" data-step="2">
                        <div class="row">
                            <div class="mb-3 col-12 col-sm-6">
                                <label for="cep" class="form-label">CEP</label>
                                <input type="text" class="form-control" name="cep" placeholder="00000-000" maxlength="9" disabled>
                            </div>
                            <div class="mb-3 col-12 col-sm-6">
                                <label for="endereco" class="form-label">Endereço</label>
                                <input type="text" class="form-control" name="endereco" placeholder="Rua Exemplo" disabled>
                            </div>
                            <div class="mb-3 col-6">
                                <label for="numero" class="form-label">Número</label>
                                <input type="text" class="form-control" name="numero" placeholder="123" disabled>
                            </div>
                            <div class="mb-3 col-6">
                                <label for="complemento" class="form-label">Complemento</label>
                                <input type="text" class="form-control" name="complemento" placeholder="Apto, bloco..." disabled>
                            </div>
                            <div class="mb-3 col-6">
                                <label for="bairro" class="form-label">Bairro</label>
                                <input type="text" class="form-control" name="bairro" placeholder="Centro" disabled>
                            </div>
                            <div class="mb-3 col-6">
                                <label for="cidade" class="form-label">Cidade</label>
                                <input type="text" class="form-control" name="cidade" placeholder="São Paulo" disabled>
                            </div>
                        </div>

                        <div class="mt-4 d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-dark prev-step">VOLTAR</button>
                            <button type="button" style="background:{{$checkout->checkout_color_default ?? $setting->gateway_color}}" class="btn btn-form-checkout-prev btn-lg next-step btn-form-checkout" disabled>IR PARA PAGAMENTO</button>
                        </div>
                    </div>

                    <!-- Step 3: Pagamento -->
                    <div class="step-content d-none" data-step="3">
                        <div class="mb-3">
                            <label class="form-label">Forma de pagamento</label>
                            <select class="form-select" name="forma_pagamento" disabled>
                                <option value="">Selecione</option>
                                <option value="pix">PIX</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Observações</label>
                            <textarea class="form-control" rows="5" style="height: 170px" name="obs" placeholder="Alguma observação sobre o pedido?" disabled></textarea>
                        </div>

                        <div class="mt-4 d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-dark prev-step">VOLTAR</button>
                            <button type="button" style="background:{{$checkout->checkout_color_default ?? $setting->gateway_color}}" class="btn btn-form-checkout-prev btn-form-checkout btn-lg">FINALIZAR COMPRA</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lado B -->
            <div class="p-0 col-lg-5">
                <div class="p-0 rounded w-100 h-100">
                    <div class="p-2 mb-4 rounded justify-content-between bg-steps-form card-bg" style="background:{{$checkout->checkout_color_card ?? "#ffffff"}}">
                        <div class="row ">
                            <div class="col-12">
                                <div class="p-0 mb-4 card produto card-bg" style="background:{{$checkout->checkout_color_card ?? "#ffffff"}}">
                                    <div class="pt-2 row justify-content-between sidetop">
                                        <div class="pl-2 col-6 cart">
                                            Seu carrinho
                                            <span class="pt-2 pr-2 small collapse collapse-toggle d-lg-none">
                                                Informações da sua compra
                                            </span>
                                        </div>

                                        <div class="col-6">
                                            <div class="d-flex align-items-center justify-content-end h-100">
                                                <span class="valor_total collapse collapse-toggle">R$ 207,00</span>
                                                <div class="text-center qtde">1</div>
                                                <i class="mb-1 ml-2 arrow_down__icon d-inline-block d-lg-none" onclick="togglePurchaseSummary()"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="purchase-summary__body" class="mt-2 d-lg-block">
                                        <div class="mb-3 product-list">
                                            <div class="product-grid">
                                                <div class="d-flex align-items-center">
                                                    <img class="product-img" src="{{ $checkout->produto_image ?? '/assets/images/product_default.png' }}" onerror="this.src='https://cloudfox-files.s3.amazonaws.com/produto.svg'">
                                                </div>
                                            <div>
                                            <p class="text-center ellipsis-h"> {{ $checkout->produto_name }}<p>
                                                <p class="text-center ellipsis-h" style="font-size:12px"> {{ $checkout->produto_descricao }}<p>
                                        </div>
                                        <div class="mt-3 d-flex align-items-center justify-content-end">
                                            <div class="input-number">
                                                <button class="btn-sub" disabled>
                                                    <img src="/assets/images/minus.svg">
                                                </button>
                                                <input type="number" value="1" min="1" max="99" step="1" name="valor-qtd" disabled>
                                                <button type="button" class="btn-add" disabled>
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
                                            <div class="text-left col-6">
                                                <span class="subtotal">Subtotal</span>
                                            </div>
                                                <div class="text-right col-6">
                                                    <span class="subtotal">
                                                        R$ <span class="subtotal-value">{{ number_format($checkout->produto_valor, '2',',','.') }}</span>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="p-0 mb-2 row justify-content-between">
                                                <div class="text-left col-6">
                                                    <span class="subtotal">Frete</span>
                                                </div>
                                                <div class="text-right col-6">
                                                    <span class="subtotal valor_frete" id="valor_frete"> - </span>
                                                </div>
                                            </div>
                                            <div id="div_progressive_discount" class="p-0 mb-2 row justify-content-between progressive-discount-class" style="display:none">
                                                <input type="hidden" id="progressive_discount">
                                                <div class="text-left col-7">
                                                    <span class="subtotal">Desconto progressivo</span>
                                                </div>
                                                <div class="text-right col-5">
                                                    <span class="subtotal discount-span progressive-discount-span-text"></span>
                                                </div>
                                            </div>
                                            <div class="p-0 mb-1 row justify-content-between d-none automatic-discount">
                                                <div class="text-left col-6">
                                                    <span class="text-automatic-discount subtotal">Desconto cartão</span>
                                                </div>
                                                <div class="text-right col-6">
                                                    <span class="subtotal value-automatic-discount discount-span"> R$ 0 </span>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="mt-0">
                                        <div class="cp-total" style="position: relative">
                                            <div class="row justify-content-between total_container">
                                                <div class="text-left col-6">Total</div>
                                                <div class="text-right col-6">
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
                    <div class="mb-4 security d-none d-lg-flex justify-content-center">
                        <button class="btn btn-security">
                            <img src="/assets/images/safe.svg" alt="Green Shield Icon">
                            Ambiente seguro
                        </button>
                    </div>
                    <div id="depoimento-visual-list">
                            <div class="d-none d-lg-block">
                                <div class="hidden p-3 mb-3 card card-bg depoimento-container" style="background:{{$checkout->checkout_color_card ?? "#ffffff"}}">
                                    <div class="card-body">
                                        <div class="row no-gutters">
                                            <div class="col-8 d-flex">
                                                <img class="rounded-circle preview-image" style="object-fit: cover;width:48px!important;height:48px!important;" src="/assets/images/avatar_default.png">
                                                <span class="pt-1 pl-2 text-ccblack d-inline-block preview-nome" style="width: 80%;">Hemiliana</span>
                                            </div>
                                            <div class="pt-1 text-right d-none d-md-flex col-4 align-items-center justify-content-end">
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
                                            <div class="mt-2 text-left review-desc col review-description preview-depoimento">A caixa veio recheada!!! Chegou em 4 dias aqui em casa sou de PE ! Apaixonada!</div>
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
                    </div>
                </div>
            </div>
        </div>
        <div id="footer" class="py-4 mt-5 text-center card card-bg d-flex flex-column align-items-center footer-cfx" style="padding-bottom: 74px !important;background:"{{$checkout->checkout_color_card ?? "#ffffff"}}">
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
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let currentStep = 1;
        const totalSteps = document.querySelectorAll('.step-content').length;

        function showStep(step) {
            document.querySelectorAll('.step-content').forEach(function (el) {
                el.classList.add('d-none');
            });
            document.querySelector('.step-content[data-step="' + step + '"]').classList.remove('d-none');

            // Atualizar barra superior
            document.querySelectorAll('.guide').forEach(g => g.classList.remove('ativo', 'current'));
            if (step === 1) document.getElementById('contact_data').classList.add('ativo', 'current');
            if (step === 2) document.getElementById('delivery_data').classList.add('ativo', 'current');
            if (step === 3) document.getElementById('payment_data').classList.add('ativo', 'current');
        }

        // Botão de próximo
        document.querySelectorAll('.next-step').forEach(btn => {
            btn.addEventListener('click', function () {
                if (currentStep < totalSteps) {
                    currentStep++;
                    showStep(currentStep);
                }
            });
        });

        // Botão de voltar
        document.querySelectorAll('.prev-step').forEach(btn => {
            btn.addEventListener('click', function () {
                if (currentStep > 1) {
                    currentStep--;
                    showStep(currentStep);
                }
            });
        });

        // Botão inicial (button do passo 1)
        const btnInicial = document.querySelector('#for_add button[type="button"]');
        btnInicial.addEventListener('click', function (e) {
            e.preventDefault();

            // Aqui você pode validar os campos do Step 1 se quiser

            currentStep++;
            showStep(currentStep);
        });

        showStep(currentStep); // Mostra passo inicial

        let qtde = document.querySelector('.qtde');
        let ivl = document.querySelector('[name="valor-qtd"]');
        let subtotal = document.querySelector('.subtotal-value');
        let total = document.querySelectorAll('.valor_total');

        // Função auxiliar para obter o valor numérico em reais
        function getUnitValue() {
            let txt = subtotal.textContent.trim();
            let valorStr = txt.replace(/[^\d,]/g, '').replace(',', '.'); // remove R$ e espaços, troca vírgula por ponto
            return parseFloat(valorStr) / Number(qtde.textContent); // valor unitário
        }

        // Função auxiliar para atualizar os valores exibidos
        function updateValues(newQtde) {
            let unit = getUnitValue();
            let totalFormatado = (newQtde * unit).toLocaleString('pt-br', {minimumFractionDigits: 2});

            qtde.innerText = newQtde;
            ivl.value = newQtde;
            subtotal.innerText = totalFormatado;

            total.forEach(el => {
                el.innerText = totalFormatado;
            });

            total.innerText = totalFormatado;
        }

        document.querySelector('.btn-add').addEventListener('click', function () {
            let qt = Number(qtde.textContent);
            updateValues(qt + 1);
        });

        document.querySelector('.btn-sub').addEventListener('click', function () {
            let qt = Number(qtde.textContent);
            if (qt === 1) return;
            updateValues(qt - 1);
        });

        ivl.addEventListener('input', function (e) {
            let newVal = Number(e.target.value);
            if (newVal < 1 || isNaN(newVal)) return;
            updateValues(newVal);
        });

    });
    </script>


