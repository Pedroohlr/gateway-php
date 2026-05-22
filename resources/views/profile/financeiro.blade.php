
@php
use App\Helpers\Helper;
Helper::calculaSaldoLiquido(auth()->user()->user_id);
$setting = Helper::getSetting();
@endphp
<x-app-layout :route="'Financeiro'">
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="mb-3 row justify-content-between align-items-">
                <div style="display:flex;align-item:center;justify-content:flex-start;" class="mb-5 col-12 col-md-4 mb-md-0 justify-content-start align-items-center">
                    <h1 class="mb-0 display-5">Financeiro</h1>
                </div>
            </div>

            <!-- Start::page-header -->
            <div class="flex-wrap gap-2 my-4 d-flex align-items-center justify-content-between page-header-breadcrumb">
            </div>
            <!-- End::page-header -->

            <!-- Start:: row-1 -->
            <div class="mb-3 row">
                <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-white" id="dispPend">R$ {{ number_format($saldo + $saldo_bloqueado ?? 0, 2, ',', '.') }}</div>
                                    <div class="card-text">Disponível + Pendente</div>
                                </div>
                            </div>
                            <div class="card-text">
                                <div class="d-inline-flex align-items-center ">
                                    <i class="text-md fa-brands fa-pix icon-xs text-warning"></i>&nbsp;
                                    <div class="text-md caption text-warning fw-500 me-2">Total</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-white" id="dispSaque">R$ {{ number_format($saldo ?? 0, 2, ',', '.') }}</div>
                                    <div class="card-text">Disponível para saque</div>
                                </div>
                            </div>
                            <div class="card-text">
                                <div class="d-inline-flex align-items-center ">
                                    <i class="text-md fa-brands fa-pix icon-xs text-success"></i>&nbsp;
                                    <div class="text-md caption text-white fw-500 me-2">Liberado</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Start::row-1 -->
            <div class="row">
                <div class="col-xl-6">
                    <div class="card card-raised">
                        <div class="p-0 card-body">
                            <div class="p-3 d-grid border-bottom border-block-end-dashed">
                                <button class="btn btn-success d-flex align-items-center justify-content-center"
                                    data-bs-toggle="modal"
                                    data-bs-target="#addsaldo">
                                    <i class="align-middle ri-add-circle-line fs-16 me-1"></i> Adcionar Saldo
                                </button>

                                <!-- Modal -->
                                <div class="modal fade" id="addsaldo" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h6 class="modal-title" id="mail-ComposeLabel">Adcionar Saldo</h6>
                                                <button id="btnDepositar" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form id="depositForm" method="POST">
                                            @csrf
                                                <div class="px-4 modal-body">
                                                    <div class="row gy-2">

                                                        <!-- Campo de valor -->
                                                        <div class="col-xl-12">
                                                            <label for="valor" class="form-label">Valor</label>
                                                            <input type="number" step="0.01" class="form-control" id="valor_deposito" name="valor" placeholder="Valor" required>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                                                    <button id="btn-depositar" type="submit" class="btn btn-primary">Depositar</button>
                                                </div>
                                            </form>

                                            <div id="data-qrcode" class="mt-5 text-center" style="width:100%;display: none;">
                                                <img id="pix-qr-code" width="200" height="200" class="mb-3" />
                                                <input id="pix-copia-e-cola" style="background: transparent; width: 80%;" class="mb-3" readonly />
                                                <button class="mb-3 btn btn-primary" onclick="copiarTexto()">Copiar chave</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                           
                            <!-- Explicação sobre taxas padrão -->
                            <div class="m-3 alert alert-white">
                                <ul>
                                    <li><strong>Taxa de depósito:</strong> {{ auth()->user()->taxa_cash_in."%" }} {{ $setting->taxa_fixa_padrao > 0 ? '+ R$ '.number_format(auth()->user()->taxa_cash_in_fixa, '2', ',', '.') : '' }}</li>
                                    <li><strong>Limite Pessoa física:</strong> Sem limite</li>
                                    <li><strong>Limite Pessoa jurídica:</strong> Sem limite</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="card card-raised">
                        <div class="p-0 card-body">
                            <div class="p-3 d-grid border-bottom border-block-end-dashed">
                                <button class="btn btn-success d-flex align-items-center justify-content-center"
                                    data-bs-toggle="modal"
                                    data-bs-target="#addtask"
                                    data-saldo="{{ $saldoliquido }}">
                                    <i class="align-middle ri-add-circle-line fs-16 me-1"></i> Solicitar saque
                                </button>

                                <!-- Modal -->
                                <div class="modal fade" id="addtask" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h6 class="modal-title" id="mail-ComposeLabel">Novo Saque</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form id="saqueForm" method="POST">
                                            @csrf
                                                <div class="px-4 modal-body">
                                                    <div class="row gy-2">

                                                        <!-- Verificação de saldo baixo -->
                                                        @if($saldoBaixo)
                                                        <div class="mt-4 alert alert-white">
                                                            <strong>Saldo muito baixo para realizar um saque.</strong>
                                                        </div>
                                                        @endif

                                                        @if($retiradasPendentes)
                                                        <div class="mt-4 alert alert-white">
                                                            <strong>Já existe um saque em processamento. Aguarde a conclusão.</strong>
                                                        </div>
                                                        @endif

                                                        
                                                        <!-- Exibição do saldo disponível -->
                                                        <div class="mt-1 alert alert-info">
                                                            <ul>
                                                                <li><strong>DISPONÍVEL PARA SAQUE:</strong> R$: {{ number_format($saldo, 2, ',', '.') }}</li>
                                                            </ul>
                                                        </div>
                                                        @if($setting->hour_limit_withdraw && !is_null($setting->hour_limit_withdraw))
                                                            <div class="mt-4 alert alert-white">
                                                                <ul>
                                                                    @php 
                                                                        $hours = $setting->hour_limit_withdraw;
                                                                        if(strlen($hours) == 1) {
                                                                             $hours = '0'.$hours.":00";
                                                                        } else {
                                                                            $hours = $hours.":00";
                                                                        }
                                                                    @endphp
                                                                    <li><strong>Atenção</strong> Saques solicitados apartir das {{ $hours }} horas, serão enviados no dia seguinte, em horário comercial.</li>
                                                                </ul>
                                                            </div>
                                                        
                                                        @endif
                                                        <!-- Campo de valor -->
                                                        <div class="col-xl-12">
                                                            <label for="valor" class="form-label">Valor</label>
                                                            <input type="number" step="0.01" class="form-control"
                                                                id="valor"
                                                                max="{{ auth()->user()->saldo }}"
                                                                name="valor"
                                                                placeholder="Valor"
                                                                required>
                                                            <!-- <div id="valorLiquido" class="mt-2 text-success"></div> -->
                                                            <div id="containerValorLiquido" style="display: none;" class="mt-4 alert alert-success">
                                                                <ul>
                                                                    <li><strong id="valorLiquido"></strong></li>
                                                                </ul>
                                                            </div>
                                                            <div id="valorError" class="mt-2 text-danger" style="display: none;">Saldo insuficiente para o valor solicitado.</div>
                                                        </div>

                                                        <div class="col-xl-12">
                                                        <label class="form-label">Tipo de Chave</label>
                                                            <select id="tipo_chave" name="tipo_chave" type="text" class="form-control">
                                                                <option value="cpf">CPF</option>
                                                                <option value="cnpj">CNPJ</option>
                                                                <option value="email">EMAIL</option>
                                                                <option value="telefone">CELULAR</option>
                                                                <option value="aleatoria">ALEATÓRIA</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-xl-12">
                                                            <label for="chave" class="form-label">Chave PIX:</label>
                                                            <input type="text" class="form-control" id="chave" name="chave" placeholder="Chave" required>
                                                        </div>

                                                        <!-- Campo oculto para o ID do usuário -->
                                                        <input type="hidden" id="user_id" name="user_id" value="{{ $email }}">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                                                    <button
                                                      id="btnSolicitarSaque"
                                                      type="submit"
                                                      class="btn btn-primary"
                                                      {{ $retiradasPendentes >= 1 ? 'disabled' : '' }}>
                                                      Solicitar
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Explicação sobre taxas padrão -->
                            <div class="m-3 alert alert-white">
                                <ul>
                                    <li><strong>Taxa de saque:</strong> {{ auth()->user()->taxa_cash_out."%" }} {{ auth()->user()->taxa_cash_out_fixa > 0 ? '+ R$ '.number_format(auth()->user()->taxa_cash_out_fixa, '2', ',', '.') : '' }}</li>
                                    @if(isset($setting->limite_saque_mensal) && (float)$setting->limite_saque_mensal > 0)
                                        <li><strong>Limite Pessoa física:</strong> R$ {{ number_format($setting->limite_saque_mensal, '2', ',', '.') }} /mês</li>
                                    @else
                                        <li><strong>Limite Pessoa física:</strong> Sem limite</li>
                                    @endif
                                      <li><strong>Limite Pessoa jurídica:</strong> Sem limite</li>
                                </ul>
                            </div>
                             @if($setting->hour_limit_withdraw && !is_null($setting->hour_limit_withdraw))
                                <div class="m-3 alert alert-white">
                                    <ul>
                                        @php 
                                            $hours = $setting->hour_limit_withdraw;
                                            if(strlen($hours) == 1) {
                                                 $hours = '0'.$hours.":00";
                                            } else {
                                                $hours = $hours.":00";
                                            }
                                        @endphp
                                        <li><strong>Atenção</strong> Saques solicitados apartir das {{ $hours }} horas, serão enviados no dia seguinte, em horário comercial.</li>
                                    </ul>
                                </div>
                            
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- End::row-1 -->
        </div>
    </div>

    <script>
        function copiarTexto() {
            var input = document.getElementById("pix-copia-e-cola");
            input.select();
            document.execCommand("copy");
            showToast("success","Chave Pix copiada!");
        }
    </script>

    <script>
        document.getElementById('depositForm').addEventListener('submit', function(event) {
            event.preventDefault();
            let btnDepositar = document.getElementById('btn-depositar');
            btnDepositar.setAttribute('disabled', true);
            var paymentCode;
            var transactionId;
            generateQRCode();
            async function generateQRCode() {
                var name = "{{ auth()->user()->name }}";
                var cpf = "{{ auth()->user()->cpf_cnpj }}";
                var email = "{{ auth()->user()->email }}";
                var amount = document.getElementById('valor_deposito').value;
                var apiUrl = "{{ env('APP_URL') }}/api/transaction/deposit";
                var token = "{{ auth()->user()->chaves->token }}";
                var secret = "{{ auth()->user()->chaves->secret }}";
                var apikey = "{{ auth()->user()->chaves->apikey }}";
                var phone = "{{ auth()->user()->telefone }}";

                var payload = {
                    "amount": parseFloat(amount),
                    "debtor_name": name,
                    "email": email,
                    "debtor_document_number": cpf,
                    "phone": phone,
                    "method_pay": "pix",
                    "postback": "web"
                };
                
                console.log({token, secret, apikey})
                console.log({payload})
                try {
                    const response = await fetch(apiUrl, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "Authorization": 'Bearer '+btoa(token+":"+secret),
                            "X-API-KEY": apikey
                        },
                        body: JSON.stringify(payload)
                    });

                    const data = await response.json();
                    console.log('resposta', data);
                    if (data.qrcode) {
                        paymentCode = data.qrcode;
                        paymentCodeBase64 = data.qr_code_image_url;
                        transactionId = data.idTransaction; // Ajustado para pegar idTransaction



                        // Adiciona o paymentCode ao texto da div
                        document.getElementById('pix-qr-code').src = paymentCodeBase64;
                        document.getElementById('pix-copia-e-cola').value = paymentCode;

                        document.getElementById('depositForm').style.display = 'none';
                        let pixcontainer = document.getElementById('data-qrcode');
                        pixcontainer.style.display = 'flex';
                        pixcontainer.style.flexDirection = "column";
                        pixcontainer.style.alignItems = "center";
                        pixcontainer.style.justifyContent = "center";
                        pixcontainer.style.gap = 5;

                        // Inicia a verificação do pagamento a cada 2 segundos
                        setInterval(checkPaymentStatus, 5000);
                    } else {
                        btnDepositar.setAttribute('disabled', false);
                        console.error("Erro na solicitação:", data.message);
                    }
                } catch (error) {
                    btnDepositar.setAttribute('disabled', false);
                    console.error("Erro na solicitação:", error);
                }
            }

            async function checkPaymentStatus() {
                var token = "{{ auth()->user()->chaves->token }}";
                var secret = "{{ auth()->user()->chaves->secret }}";
                var apikey = "{{ auth()->user()->chaves->apikey }}";

                var apiUrl = "{{env('APP_URL')}}/api/status";
                var payload = {
                    "idTransaction": transactionId
                };

                try {
                    const response = await fetch(apiUrl, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "Authorization": btoa(token+":"+secret),
                            "X-API-KEY": apikey
                        },
                        body: JSON.stringify(payload)
                    });

                    const data = await response.json();

                    if (data.status === "PAID_OUT") {
                        clearInterval(checkPaymentStatus); // Para a verificação quando o pagamento for confirmado

                        showToast('success', "Saldo adcionado com sucesso!")
                        setTimeout(() => {
                            window.location.reload();
                        }, 3000)
                    } else if (data.status === "WAITING_FOR_APPROVAL") {
                        console.log("Aguardando aprovação...");
                    }
                } catch (error) {
                    console.error("Erro na verificação do pagamento:", error);
                }
            }
        })
    </script>

    <script>
    function atualizaSaldo (){
        let saldo = "{{ auth()->user()->fresh() }}"
    }
    
        document.addEventListener("DOMContentLoaded", function() {
            
            setTimeout(()=>atualizaSaldo,1000);
            
            let btnSolicitarSaque = document.getElementById("btnSolicitarSaque");
            let inputValor = document.getElementById("valor");
            let inputChave = document.getElementById("chave");
            let valorLiquidoInput = document.getElementById("valorLiquido");
            let containerValorLiquido = document.getElementById("containerValorLiquido");

            // Desabilita o botão inicialmente
            btnSolicitarSaque.setAttribute("disabled", true);

            function validarCampos() {
                let valorPreenchido = inputValor.value && parseFloat(inputValor.value) > 0;
                let chavePreenchida = inputChave.value.trim().length > 0;

                if (valorPreenchido && chavePreenchida) {
                    btnSolicitarSaque.removeAttribute("disabled");
                } else {
                    btnSolicitarSaque.setAttribute("disabled", true);
                }
            }

            function calcularValorLiquido() {
                let maxValue = parseFloat(inputValor.max) || 0;
                let currentValue = parseFloat(inputValor.value) || 0;

                if (currentValue > maxValue) {
                    inputValor.value = maxValue;
                    currentValue = maxValue;
                }

                if (currentValue <= 0 || isNaN(currentValue)) {
                    containerValorLiquido.style.display = "none";
                } else {
                    containerValorLiquido.style.display = "block";
                }

                let tx_cash_out = parseFloat("{{ auth()->user()->taxa_cash_out }}") || 0;
                let taxa_fixa_padrao = parseFloat("{{ auth()->user()->taxa_cash_out_fixa }}") || 0;
                let valorLiquido = currentValue - taxa_fixa_padrao - (currentValue * tx_cash_out / 100);

                valorLiquidoInput.innerText = "Valor líquido a receber: " +
                    valorLiquido.toLocaleString("pt-BR", {
                        style: "currency",
                        currency: "BRL"
                    });
            }

            inputValor.addEventListener("input", function() {
                calcularValorLiquido();
                validarCampos();
            });

            inputChave.addEventListener("input", validarCampos);
        });
    </script>

    <script>
        document.getElementById('saqueForm').addEventListener('submit', function(event) {
            event.preventDefault();
            document.getElementById('btnSolicitarSaque').setAttribute('disabled',true);
            var saldo = "{{ $saldoliquido }}"; // Corrigido para usar PHP para obter o saldo
            var valor = parseFloat(document.getElementById('valor').value);
            var valorError = document.getElementById('valorError');

            // Verifica se o saldo é zero ou se o valor solicitado é maior que o saldo
            if (saldo <= 0) {
                showToast('warning', "Saldo insuficiente!")
                event.preventDefault(); // Evita o envio do formulário
            } else if (valor > saldo) {
                showToast('success', "Saldo insuficiente!")
                event.preventDefault(); // Evita o envio do formulário
            }


            requestPayment();
            async function requestPayment() {
                var token = "{{ auth()->user()->chaves->token }}";
                var secret = "{{ auth()->user()->chaves->secret }}";
                var apikey = "{{ auth()->user()->chaves->apikey }}";
                var amount = document.getElementById('valor').value;
                var pixKey = document.getElementById('chave').value;
                var pixKeyType = document.getElementById('tipo_chave').value;
                var apiUrl = "{{env('APP_URL')}}/api/transaction/payment";

                if(parseFloat(valor) > parseFloat(saldo)){
                    valor = saldo;
                }

                var payload = {
                   token,
                   secret,
                   amount,
                   pixKey,
                   pixKeyType,
                   baasPostbackUrl: 'web'
                }

                var token = "{{ auth()->user()->chaves->token }}";
                var secret = "{{ auth()->user()->chaves->secret }}";
                var apikey = "{{ auth()->user()->chaves->apikey }}";
                var amount = document.getElementById('valor').value;
                var pixKey = document.getElementById('chave').value;
                var pixKeyType = document.getElementById('tipo_chave').value;
                var apiUrl = "{{env('APP_URL')}}/api/transaction/payment";
                let btnSolicitarSaque = document.getElementById('btnSolicitarSaque'); // Re-obtendo o botão

                var payload = {
                   token,
                   secret,
                   amount,
                   pixKey,
                   pixKeyType,
                   baasPostbackUrl: 'web'
                }

                fetch(apiUrl, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Authorization": 'Bearer ' + btoa(token + ":" + secret),
                        "X-API-KEY": apikey
                    },
                    body: JSON.stringify(payload)
                })
                .then(response => {
                    console.log(response)
                    // Trata o caso de Rate Limit (429) separadamente
                    if (response.status === 429) {
                        showToast('warning', "Aguarde 1 minuto para realizar um novo saque.");
                        setTimeout(() => {
                            window.location.href = '/financeiro';
                        }, 3000);
                        // Retorna um Promise que nunca resolve para interromper a cadeia .then()
                        return new Promise(() => {}); 
                    }
                    // Retorna o resultado como JSON para a próxima Promise
                    return response.json();
                })
                .then(data => {
                    console.log(data);
                    
                    if (!data) {
                        console.log("diferente de data");
                        showToast('warning', "Houve um erro. Tente novamente mais tarde.");
                        // Reativa o botão e agenda o refresh
                        btnSolicitarSaque.removeAttribute('disabled');
                        setTimeout(() => {
                            window.location.href = '/financeiro';
                        }, 3000);
                        return;
                    }
                    
                    if (data.id) {
                        console.log("Tudo certo");
                        showToast('success', "Saque solicitado com sucesso.");
                        // Agenda o refresh da página
                        setTimeout(() => {
                            window.location.href = '/financeiro';
                        }, 3000);
                    } else {
                        console.log("Ops");
                        // Exibe a mensagem de erro da API
                        const errorMessage = data.message ? data.message + "... Tente novamente mais tarde." : "Algo deu errado ao solicitar o saque.";
                        showToast('warning', errorMessage);
                        // Reativa o botão para permitir nova tentativa
                        btnSolicitarSaque.removeAttribute('disabled');
                    }
                })
                .catch(error => {
                    console.error("Erro na requisição ou processamento:", error);
                    console.log("Erro no catch");
                    showToast('warning', "Houve um erro na comunicação. Tente novamente mais tarde.");
                    // Reativa o botão em caso de falha total da requisição ou erro de parsing
                    btnSolicitarSaque.removeAttribute('disabled');
                });
               

            }



        });
    </script>
</x-app-layout>
