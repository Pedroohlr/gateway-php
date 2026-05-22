document.addEventListener('DOMContentLoaded', function () {
    function applyInputMasks() {
        Inputmask({"mask": "(99) 99999-9999"}).mask(document.querySelectorAll('input[name="telefone"]'));
        Inputmask({"mask": "999.999.999-99"}).mask(document.querySelectorAll('input[name="cpf"]'));
        Inputmask({"mask": "99999-999"}).mask(document.querySelectorAll('input[name="cep"]'));
    }
    applyInputMasks();

    let currentStep = 1;
    const totalSteps = document.querySelectorAll('.step-content').length;

    function showStep(step) {
        document.querySelectorAll('.step-content').forEach(function (el) {
            el.classList.add('d-none');
        });
        document.querySelector('.step-content[data-step="' + step + '"]').classList.remove('d-none');

        document.querySelectorAll('.guide').forEach(g => g.classList.remove('ativo', 'current'));
        if (step === 1) document.getElementById('contact_data').classList.add('ativo', 'current');
        if (step === 2) document.getElementById('delivery_data').classList.add('ativo', 'current');
        if (step === 3) document.getElementById('payment_data').classList.add('ativo', 'current');
    }

    function validateStep(step) {
        let isValid = true;
        const stepContent = document.querySelector(`.step-content[data-step="${step}"]`);
        const requiredFields = stepContent.querySelectorAll('[required]');

        requiredFields.forEach(field => {
            const parent = field.closest('.mb-3');
            if (!parent) return;

            // Remove mensagens anteriores
            const existingError = parent.querySelector('.text-danger.dynamic-error');
            if (existingError) existingError.remove();

            // Validação simples
            if (!field.value.trim()) {
                isValid = false;

                // Estilização do input
                field.classList.add('is-invalid');

                // Mensagem de erro
                const error = document.createElement('span');
                error.classList.add('text-danger', 'dynamic-error');
                error.innerText = 'Campo obrigatório';
                parent.appendChild(error);
            } else {
                field.classList.remove('is-invalid');
            }
        });

        return isValid;
    }

    document.querySelectorAll('.next-step').forEach(btn => {
        btn.addEventListener('click', function () {
            if (validateStep(currentStep)) {
                if (currentStep < totalSteps) {
                    currentStep++;
                    showStep(currentStep);
                }
            }
        });
    });

    document.querySelectorAll('.prev-step').forEach(btn => {
        btn.addEventListener('click', function () {
            if (currentStep > 1) {
                currentStep--;
                showStep(currentStep);
            }
        })
    });

    const btnInicial = document.querySelector('#for_add button[type="button"]');
    btnInicial.addEventListener('click', function (e) {
        e.preventDefault();

        if (validateStep(currentStep)) {
            currentStep++;
            showStep(currentStep);
        }
    });

    showStep(currentStep);

    let tempo = window.tempo;
    const twoMinutes =  tempo * 60;
    const display = document.getElementById('countdown_text');
    startCountdown(twoMinutes, display);

    // 1. Pegue o valor vindo do backend Blade
    let hexColor = window.checkout_color_default; // Ex: "#FF5733"

    // 2. Função para converter HEX para RGBA
    function hexToRgba(hex, alpha = 0.4) {
        hex = hex.replace('#', '');

        if (hex.length === 3) {
            hex = hex.split('').map(h => h + h).join('');
        }

        const bigint = parseInt(hex, 16);
        const r = (bigint >> 16) & 255;
        const g = (bigint >> 8) & 255;
        const b = bigint & 255;

        return `rgba(${r}, ${g}, ${b}, ${alpha})`;
    }

    // 3. Converta e aplique no CSS root
    const rgbaColor = hexToRgba(hexColor, 0.1); // Define sua opacidade aqui
    const rgbaColor2 = hexToRgba(hexColor, 0.8);
    document.documentElement.style.setProperty('--color-default-opacity', rgbaColor);
    document.documentElement.style.setProperty('--color-default-opacity2', rgbaColor2);


});

function startCountdown(duration, display) {
    let timer = duration, minutes, seconds;

    const interval = setInterval(() => {
        minutes = String(Math.floor(timer / 60)).padStart(2, '0');
        seconds = String(timer % 60).padStart(2, '0');

        display.textContent = `${minutes}:${seconds}`;

        if (--timer < 0) {
            clearInterval(interval);
            display.textContent = "00:00";
            let containerCountdown = document.getElementById('texto-contador');
            containerCountdown.style.color = "white";
            containerCountdown.innerText = "Seu tempo acabou! Você precisa finalizar sua compra agora para ganhar o desconto extra."
        }
    }, 1000);
}

function applyViaCep() {
    const cepInput = document.querySelector('input[name="cep"]');
	if(cepInput){
      cepInput.addEventListener('input', function () {
          let cep = cepInput.value.replace(/\D/g, '');
          if (cep.length === 8) {
              fetchAddressByCEP(cep);
          }
      });

      cepInput.addEventListener('blur', function () {
          let cep = cepInput.value.replace(/\D/g, '');
          if (cep.length === 8) {
              fetchAddressByCEP(cep);
          }
      });
    }
}

function fetchAddressByCEP(cep) {
    fetch(`https://viacep.com.br/ws/${cep}/json/`)
        .then(res => res.json())
        .then(data => {
            console.log(data)
            if (!data.erro) {
                document.querySelector('input[name="endereco"]').value = data.logradouro || '';
                document.querySelector('input[name="bairro"]').value = data.bairro || '';
                document.querySelector('input[name="cidade"]').value = data.localidade || '';
                document.querySelector('input[name="estado"]').value = data.uf || '';
            } else {
                showCepError('CEP não encontrado.');
            }
        })
        .catch(() => {
            showCepError('Erro ao consultar CEP.');
        });
}

function showCepError(message) {
    const cepInput = document.querySelector('input[name="cep"]');
    let errorSpan = cepInput.parentElement.querySelector('.text-danger');
    if (!errorSpan) {
        errorSpan = document.createElement('span');
        errorSpan.className = 'text-danger';
        cepInput.parentElement.appendChild(errorSpan);
    }
    errorSpan.textContent = message;
}
if(window.endereco_active){
  applyViaCep();
}
function reorderCheckoutSteps() {
    const $product = $('.produto-reorder-item');
    const $steps = $('.steps-reorder-item');
    const $containerGrid1 = $('#container-grid1');

    if ($(window).width() < 992) {
        if (!$('.produto-reorder-item.mobile-inserted').length) {
            const $clone = $product.clone(true);
            $clone.addClass('mobile-inserted');
            $clone.insertBefore($steps); // Insere acima dos steps
            $product.hide(); // Oculta o original
        }
    } else {
        const $inserted = $('.produto-reorder-item.mobile-inserted');
        if ($inserted.length) {
            $inserted.remove();
            $product.show(); // Mostra novamente no lugar original
        }
    }
}


// Chamada inicial e no redimensionamento
window.addEventListener('load', reorderCheckoutSteps);
window.addEventListener('resize', reorderCheckoutSteps);


$(document).ready(function () {
    const produtoValor = window.produto_valor;

    // Inicializa carrinho se não existir
    if (!localStorage.getItem('cart')) {
        const cart = {
            items: {
                produto: {
                    id: 'produto',
                    qtd: 1,
                    valor: produtoValor
                }
            },
            bumps: []
        };
        localStorage.setItem('cart', JSON.stringify(cart));
    }

    function getCart() {
        const cart = localStorage.getItem('cart');
        return cart ? JSON.parse(cart) : { items: { produto: { qtd: 1, valor: 0 } }, bumps: [] };
    }

    function saveCart(cart) {
        localStorage.setItem('cart', JSON.stringify(cart));
    }

    function atualizarDisplay() {
        const cart = getCart();
        const produtoQtd = cart.items.produto.qtd;
        const produtoTotal = produtoQtd * cart.items.produto.valor;

        let bumpsTotal = 0;
        const bumpsIdsUnicos = new Set();

        cart.bumps.forEach(b => {
            if (!bumpsIdsUnicos.has(b.id)) {
                bumpsTotal += parseFloat(b.valor_por);
                bumpsIdsUnicos.add(b.id);
            }
        });

        const total = produtoTotal + bumpsTotal;

        $('#checkout-total').text(total.toLocaleString('pt-br', { minimumFractionDigits: 2 }));
        $('.subtotal-value').text(total.toLocaleString('pt-br', { minimumFractionDigits: 2 }));
        $('.valor_total').text(total.toLocaleString('pt-br', { minimumFractionDigits: 2 }));
        $('.number-qtd').text(produtoQtd);
        $('.qtde').text(produtoQtd);
    }

    function restaurarBumps() {
        const cart = getCart();
        $('.ob-preview-content').empty();

        cart.bumps.forEach(bump => {
            $('.ob-preview-content').append(bump.html);

            // Aguarda o DOM estar completamente carregado
            setTimeout(() => {
                const originalContainer = $(`.container-item[data-id="${bump.id}"]`);
                if (originalContainer.length > 0) {
                    originalContainer.addClass('container-item-in-cart');
                    originalContainer.find('.btn-add-bump').addClass('d-none');
                    originalContainer.find('.ob-purchased').addClass('d-flex');
                }
            }, 50);
        });
    }

    // Botão de adicionar produto
    $(document).on('click', '.btn-add', function () {
        const cart = getCart();
        cart.items.produto.qtd += 1;
        saveCart(cart);
        atualizarDisplay();
    });

    $(document).on('click', '.btn-sub', function () {
        const cart = getCart();
        if (cart.items.produto.qtd > 1) {
            cart.items.produto.qtd -= 1;
            saveCart(cart);
            atualizarDisplay();
        }
    });

    // Adicionar bump
    $(document).on('click', '.btn-add-bump', function () {
        const container = $(this).closest('.container-item');
        const bumpId = container.data('id');
        const cart = getCart();

        // 🚫 Impede duplicação: Verifica se o bump com esse ID já está no carrinho
        const bumpExistente = cart.bumps.find(b => b.id === bumpId);
        if (bumpExistente) {
            return; // Já existe, não adiciona novamente
        }

        const image = container.find('img').attr('src');
        const nome = container.find('h6').first().text();
        const descricao = container.find('p').eq(0).text();
        const precoPor = container.find('.text-success').text();
        const precoDe = container.find('.text-danger').text();

        const valorPor = parseFloat(precoPor.replace(/[^\d,]/g, '').replace(',', '.'));
        const valorDe = parseFloat(precoDe.replace(/[^\d,]/g, '').replace(',', '.'));

        const desconto = valorDe - valorPor;
        const descontoFormatado = desconto.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });

        const obHtml = `
            <div class="ob-info container-item-in-cart" id="ob-${bumpId}" data-id="${bumpId}" data-valor-de="${valorDe}" data-valor-por="${valorPor}">
                <img class="ob-photo" src="${image}" alt="Product Photo">
                <div class="ob-text">
                    <div class="ob-title">${nome}</div>
                    <div class="ob-description">${descricao}</div>
                    <div class="ob-price-container">
                        <span>1x de</span>
                        <span class="ob-price">${precoPor}</span>
                        <div class="ob-saved">${descontoFormatado} OFF</div>
                    </div>
                </div>
                <a class="ob-trash" style="cursor: pointer;"></a>
            </div>
        `;

        $('.ob-preview-content').append(obHtml);

        // Marca visualmente o bump como adicionado
        container.addClass('container-item-in-cart');
        container.find('.btn-add-bump').addClass('d-none');
        container.find('.ob-purchased').addClass('d-flex');

        // Adiciona o bump ao carrinho
        cart.bumps.push({
            id: bumpId,
            valor_de: valorDe,
            valor_por: valorPor,
            html: obHtml
        });

        saveCart(cart);
        atualizarDisplay();
        atualizaDesconto();
    });


    // Remover bump
    $(document).on('click', '.ob-trash', function () {
        const bumpItem = $(this).closest('.ob-info');
        const bumpId = bumpItem.data('id');

        bumpItem.remove();

        // Reexibe botão de adicionar e remove classe do item original
        const originalContainer = $(`.container-item[data-id="${bumpId}"]`);
        originalContainer.removeClass('container-item-in-cart');
        originalContainer.find('.btn-add-bump').removeClass('d-none');
        originalContainer.find('.ob-purchased').removeClass('d-flex');

        // Atualiza o carrinho removendo o bump do array
        const cart = getCart();
        cart.bumps = cart.bumps.filter(b => b.id !== bumpId);
        saveCart(cart);
        // Atualiza o total
        atualizarDisplay();
        atualizaDesconto();
    });


    function salvarBumpsLocalStorage() {
        const cart = getCart();
        cart.bumps = [];

        $('.ob-info').each(function () {
            cart.bumps.push({
                id: $(this).data('id'),
                valor_de: $(this).data('valor-de'),  // Salvando valor original
                valor_por: $(this).data('valor-por'), // Salvando valor com desconto
                html: $(this).prop('outerHTML')
            });
        });

        saveCart(cart); // Atualiza no localStorage
    }

    function atualizaDesconto(){
        let bumps = JSON.parse(localStorage.getItem('cart'))['bumps'];
        let per = 0;
        let total_disc = 0;
        bumps.map((item)=>{
            let de = parseFloat(item?.valor_de);
            let por = parseFloat(item?.valor_por);
            console.log(((de - por) / de) * 100)
            if (de > 0 && por < de) {
                per = per + ((de - por) / de) * 100;
                total_disc += (de - por);
            }
        })
        $('#discount_pix_span').text(total_disc.toLocaleString('pt-br', { minimumFractionDigits: 2 }));
    }
    atualizaDesconto();

    let bumps = window.bumps || [];
        let per = 0;
        let total_disc = 0;
        bumps.map((item)=>{
            let de = parseFloat(item?.valor_de);
            let por = parseFloat(item?.valor_por);
            console.log(((de - por) / de) * 100)
            if (de > 0 && por < de) {
                per = per + ((de - por) / de) * 100;
                total_disc += (de - por);
            }
        })

        $('.chk-flag-option-discount').text("ATÉ "+per.toFixed(0)+'% OFF');
        $('.percent-discount').text("ATÉ "+per.toFixed(0)+'% OFF');
    // Inicializa a interface
    restaurarBumps();
    atualizarDisplay();


    $('#form-paid').on('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        const dados = {};

        formData.forEach((value, key) => {
            dados[key] = value;
        });

        let checkout_id = window.checkout_id;
        let carrinho = JSON.parse(localStorage.getItem('cart'));
        let produto = carrinho?.items?.produto;
        let orderbumps = carrinho?.bumps;

        let qtd_produtos = produto?.qtd;
        let valor_produto = window.produto_valor;
        let total = valor_produto * qtd_produtos;

        let order_bumps = [];
        orderbumps?.forEach((i)=>{
            total += i?.valor_por;
            order_bumps.push(i?.id);
        })

        dados['quantidade'] = qtd_produtos;
        dados['valor_total'] = total;
        dados['checkout_id'] = checkout_id;
        dados['order_bumps'] = JSON.stringify(order_bumps);

        fetch("/checkout/cliente/pedido/gerar", {
            method: "POST",
            headers: {
                'Content-Type': "application/json",
                'Accept': "application/json",
                'Authorization': 'Bearer '+btoa(dados['token']+':'+dados['secret']),
                'X-CSRF-TOKEN': dados['_token']
            },
            body: JSON.stringify(dados)
        })
        .then((res)=>res.json())
        .then((res)=>{
            console.log(res);
            if(res.status === 'success'){
                Swal.fire({
                    title: `<h6>Escaneie o QrCode ou copie o código abaixo para realizar o pagamento<h6>`,
                    html: `

                      <img src="${res?.data?.qr_code_image_url}" width="250px" height="250px"></br>
                      <h5 style="font-weight:bold;" class="text-success mb-3 text-bold">Valor: ${res.valor_text}</h5>
                      <input id="pix-copia-e-cola" class="form-control" readonly id="input-copia-e-cola" value="${res?.data?.qrcode}">
                          <div id="container-alert"> </div>
                      <button onclick="copiarChavePix()" class="btn btn-info btn-sm my-3"><i class="fa-solid fa-copy"></i>&nbsp;Copiar</button>

                          `,
                    showCloseButton: true,
                    showCancelButton: false,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false
                });

                let intervalId;

                const checkStatus = () => {
                    let idTransaction = res?.data?.idTransaction;

                    fetch("/checkout/cliente/pedido/status", {
                        method: "POST",
                        headers: {
                            'Content-Type': "application/json",
                            'Accept': "application/json",
                            'X-CSRF-TOKEN': dados['_token']
                        },
                        body: JSON.stringify({ idTransaction })
                    })
                    .then((res) => res.json())
                    .then(res => {
                        if (res?.status === 'pago') {
                            Swal.close();

                            Swal.fire({
                                title: `<h4>Seu pagamento foi confirmado</h4>`,
                                html: `
                                    <img src="/assets-checkout/img/order_confirmed.png" width="200px" height="auto"></br>
                                    <h5 style="font-weight:bold;" class="text-success mb-3 text-bold">Obrigado pela sua compra.</h5>
                    `,
                                showCloseButton: true,
                                showCancelButton: false,
                                showConfirmButton: false,
                                didClose: () => {
                                   window.location.reload();
                                }

                            });
                            clearInterval(intervalId);

                            let produto = JSON.parse(localStorage.getItem('cart'));
                            fbq('track', 'Purchase', {value: produto?.items?.produto?.valor, currency: 'BRL'});

                            const produtoValor = window.produto_valor;

                            const cart = {
                                items: {
                                    produto: {
                                        id: 'produto',
                                        qtd: 1,
                                        valor: produtoValor
                                    }
                                },
                                bumps: []
                            };
                            localStorage.setItem('cart', JSON.stringify(cart));
                            atualizarDisplay();
                            atualizaDesconto();
                        }
                    });
                };

                intervalId = setInterval(checkStatus, 5000);
            }

        })
        .catch(console.error)
    });
});

function copiarChavePix() {
   var input = document.getElementById("pix-copia-e-cola");

   // Garante que o valor do input será copiado
   navigator.clipboard.writeText(input.value)
   .then(() => {
     let message = `
<div class="alert alert-success my-3" role="alert" style="font-weight:bold;">
  <i class="fa-brands fa-pix"></i>&nbsp;Chave PIX copiada com sucesso!
</div>`;
     document.getElementById('container-alert').innerHTML = message;
   })
   .catch(err => {
      console.error("Erro ao copiar", err);
   });
}

