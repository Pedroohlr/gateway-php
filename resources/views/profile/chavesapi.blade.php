@php

if(is_null(auth()->user()->apikey)){
   $apikey = uniqid();
  \App\Models\UsersKey::where('user_id', auth()->user()->user_id)->update(['apikey'=> $apikey]);
  auth()->user()->chaves->apikey = $apikey;
}

@endphp
<x-app-layout :route="'Token API PIX'">
  <div class="main-content app-content">
    <div class="container-fluid">
      <div class="row">

        <div class="mb-3 row justify-content-between align-items-">
            <div style="display:flex;align-item:center;justify-content:flex-start;" class="mb-5 col-12 col-md-4 mb-md-0 justify-content-start align-items-center">
                <h1 class="mb-0 display-5">Chaves API</h1>
            </div>
        </div>

        <div class="col-md-7 grid-margin stretch-card">
          <div class="card card-raised">
            <div class="card-body">


              <h4 class="mb-4 d-block">Recursos do Gateway {{ env('APP_NAME') }}:</h4>
              <ul style="list-style-type: none;padding-left: 0;">
                <li>
                  <i class="mb-4 fa-solid fa-circle-info fs-6" style="color: #007bff ;"></i> Tecnologia avançada que processa transações com eficiência e segurança.
                </li>
                <li>
                  <i class="mb-4 fa-solid fa-circle-info fs-6" style="color: #28a745;"></i> Painel de controle personalizado para análise de vendas e gerenciamento financeiro.
                </li>
                <li>
                  <i class="mb-4 fa-solid fa-circle-info fs-6" style="color: #dc3545;"></i> Segurança robusta contra fraudes e proteção dos dados dos clientes.
                </li>
                <li>
                  <i class="mb-4 fa-solid fa-circle-info fs-6" style=" color: #ffc107;"></i> Integração perfeita com as principais plataformas de e-commerce.
                </li>
                <li>
                  <i class="mb-4 fa-solid fa-circle-info fs-6" style="color: #17a2b8;"></i> Conexão direta com a adquirente, simplificando o processo de pagamento.
                </li>
              </ul>

            </div>
          </div>
        </div>



        <script>
          function mostrarCodigo() {
            var token = document.getElementById("token");

            if (token.innerText === "*********") {
              token.innerText = "{{ $token }}";
            } else {
              token.innerText = "*********";
            }
          }
        </script>

        <div class="col-md-5 grid-margin" style="min-height: 272.8px">
          <div class="card card-raised" style="height: 100%;">
            <div class="card-body" style="height: 100%;">
              <h8 class="d-block">Integração com o Gateway</h8>
              <div class="flex-row py-3 rounded bg-gray-dark d-flex d-md-block d-xl-flex px-md-3 ">
                <div class="text-md-center text-xl-left">
                </div>
                <div class="flex-grow text-sm align-self-center text-start text-md-center text-sm-start py-md-2 py-xl-0">
                  <div>

                    <p class="mb-3 font-weight-bold text-start">
                        <button id="btn-show-key-token" class="btn btn-success btn-sm"  onclick="mostrarToken()"><i class="fa-solid fa-eye"></i></button>
                        <button class="rounded rounded-full btn btn-success btn-sm" style="color: black !important;max-width: 150px;" onclick="copiarToken()"><i class="fa-solid fa-copy"></i></button>&nbsp;
                        Token: <span id="token">***********************</span></p>
                  </div>
                  <div>
                    <p class="mb-3 font-weight-bold text-start">
                        <button id="btn-show-key-secret" class="btn btn-success btn-sm"  onclick="mostrarSecret()"><i class="fa-solid fa-eye"></i></button>
                        <button class="rounded rounded-full btn btn-success btn-sm" style="color: black !important;max-width: 150px;" onclick="copiarSecret()"><i class="fa-solid fa-copy"></i></button>&nbsp;
                        Secret: <span id="secret">***********************</span></p>
                  </div>
                  <div>
                    <p class="mb-3 font-weight-bold text-start">
                        <button id="btn-show-key-api" class="btn btn-success btn-sm"  onclick="mostrarApiKey()"><i class="fa-solid fa-eye"></i></button>
                        <button class="rounded rounded-full btn btn-success btn-sm" style="color: black !important;max-width: 150px;" onclick="copiarApiKey()"><i class="fa-solid fa-copy"></i></button>&nbsp;
                        API KEY: <span id="apikey">***********************</span></p>
                  </div>
                </div>
              </div>
              <input id="chave-secret" value="{{ $secret }}" style="display: none;" />
              <input id="chave-token" value="{{ $token }}" style="display: none;" />
              <input id="chave-apikey" value="{{ auth()->user()->chaves->apikey }}" style="display: none;" />

            </div>

            <div class="p-3 col-12 d-block">
              <p for="endpoint" class="form-label">API Endpoint</p>
              <div class=" input-group">
                <input type="text" id="endpoint" name="endpoint" value="{{ env('APP_URL').'/api/' }}"  class="form-control" style="background:transparent!important;" readonly>
                <button class="mb-0 btn btn-outline-primary" type="button" onclick="copyToClipboard()"><i class="fa-solid fa-copy"></i></button>
              </div>
             </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    function copyToClipboard() {
      var copyText = document.getElementById("endpoint");
      copyText.select();
      copyText.setSelectionRange(0, 99999); // Para compatibilidade com dispositivos móveis
      document.execCommand("copy");

      // Exibir um alerta ou feedback ao usuário
      showToast('success', 'Endpoint copiado com sucesso.')
    }
  </script>
  <script>
    function copiarSecret() {
      var input = document.getElementById("chave-secret");

      // Garante que o valor do input será copiado
      navigator.clipboard.writeText(input.value)
        .then(() => {
          showToast('success', "Chave 'Secret' copiada!")
          //alert("Chave Pix copiada!");
        })
        .catch(err => {
          console.error("Erro ao copiar", err);
        });
    }
    

    function copiarToken() {
      var input = document.getElementById("chave-token");

      // Garante que o valor do input será copiado
      navigator.clipboard.writeText(input.value)
        .then(() => {
          showToast('success', "Chave 'Token' copiada!")
          //alert("Chave Pix copiada!");
        })
        .catch(err => {
          console.error("Erro ao copiar", err);
        });
    }

    function copiarApiKey() {
      var input = document.getElementById("chave-apikey");

      // Garante que o valor do input será copiado
      navigator.clipboard.writeText(input.value)
        .then(() => {
          showToast('success', "Chave 'API KEY' copiada!")
          //alert("Chave Pix copiada!");
        })
        .catch(err => {
          console.error("Erro ao copiar", err);
        });
    }
  </script>


  <script>
    function mostrarToken() {
      var token = document.getElementById("token");
      var btnCode = document.getElementById('btn-show-key-token');

      if (token.innerText === "***********************") {
        token.innerText = '{{ $token }}';
        btnCode.innerHTML = `<i class="fa-solid fa-eye-slash"></i>`;
      } else {
        token.innerText = '***********************';
        btnCode.innerHTML = ` <i class="fa-solid fa-eye"></i>`;
      }
    }

    function mostrarSecret() {
      var token = document.getElementById("secret");
      var btnCode = document.getElementById('btn-show-key-secret');

      if (token.innerText === "***********************") {
        token.innerText = '{{ $secret }}';
        btnCode.innerHTML = `<i class="fa-solid fa-eye-slash"></i>`;
      } else {
        token.innerText = '***********************';
        btnCode.innerHTML = ` <i class="fa-solid fa-eye"></i>`;
      }
    }

    function mostrarApiKey() {
      var token = document.getElementById("apikey");
      var btnCode = document.getElementById('btn-show-key-api');

      if (token.innerText === "***********************") {
        token.innerText = '{{ auth()->user()->chaves->apikey }}';
        btnCode.innerHTML = `<i class="fa-solid fa-eye-slash"></i>`;
      } else {
        token.innerText = '***********************';
        btnCode.innerHTML = ` <i class="fa-solid fa-eye"></i>`;
      }
    }

   /*  function mostrarCodigo() {
      var token = document.getElementById("token");
      var secret = document.getElementById("secret");
      var btnCode = document.getElementById('btn-show-key');

      if (token.innerText === "***********************") {
        token.innerText = '{{ $token }}';
        secret.innerText = '{{ $secret }}';
        btnCode.innerHTML = `<i class="fa-solid fa-eye-slash"></i> Ocultar Chaves`;
      } else {
        token.innerText = '***********************';
        secret.innerText = '**********************';
        btnCode.innerHTML = ` <i class="fa-solid fa-eye"></i> Mostrar Chaves`;
      }
    } */
  </script>
</x-app-layout>
