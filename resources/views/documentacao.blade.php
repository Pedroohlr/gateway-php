<x-app-layout :route="'Documentação API PIX'">
  <link href="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/themes/prism.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/plugins/line-numbers/prism-line-numbers.css" rel="stylesheet" />

  <style>
    pre {
      overflow-x: auto;
      overflow-y: hidden;
      max-width: 100%;
      padding: 1rem;
      border-radius: 8px;
      background-color: #f8f9fa;
      white-space: pre;
    }

    code {
      display: block;
      font-size: 1.5rem;
      line-height: 1.5;
      color: #02971A;
    }

    .method,
    .card-method {
        background:rgb(1, 146, 25);
        border-radius: 8px;
        padding: 4px;
        color: white;
        width: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 15px;
    }

    .card-link {
        margin-top: 15px;
        margin-left: 10px;
        border: 1px solid rgb(190, 190, 190);
        border-radius: 10px;
        padding: 8px;
        padding-left: 10px;
        padding-right: 10px;
    }

    @media (max-width: 768px) {
      h1, h2, h4, p {
        font-size: 1rem;
      }

      .method {
        font-weight: bold;
      }
    }

    .divisoria {
        margin:6px;
        background: gray;
    }
  </style>

  <div class="main-content app-content">
    <div class="px-3 container-fluid px-md-5">

        <div class="mb-3 row justify-content-between align-items-">
            <div style="display:flex;align-item:center;justify-content:flex-start;" class="mb-5 col-12 col-md-4 mb-md-0 justify-content-start align-items-center">
                <h1 class="mb-0 display-5">Documentação API PIX</h1>
            </div>
        </div>

      
      <!-- Seção PIX IN -->
      <section id="deposito">
           <div class="mb-4">
          <h4>📡 Endpoint - Depósito (PIX IN)</h4>
          <p>Realiza um depósito gerando QrCode.</p>
          <p><strong>Método:</strong> <span class="method">POST</span></p>
          <code class="p-2 rounded d-block bg-light">{{ env('APP_URL') }}/api/transaction/deposit</code>
        </div>

        <div class="mb-4">
          <h4>📄 Descrição</h4>
          <p>Este endpoint permite gerar um pagamento via <strong>PIX</strong>.</p>
        </div>

        <div class="mb-4">
          <h4>🔐 Cabeçalhos (Headers)</h4>
          <div class="mb-3 w-100">
            <pre class="bg-light line-numbers"><code class="language-json">{
  "Content-Type": "application/json",
  "Accept": "application/json",
  "Authorization": "Bearer fadf465d4fas6d54f...", //Ex.: "Bearer ".base64_encode($token.':'.$secret)
  "X-API-KEY": "ffds464fd8898s4sa"
}</code></pre>
          </div>
        </div>

        <div class="mb-4">
          <h4>📤 Corpo da Requisição</h4>
          <div class="mb-3 w-100">
            <pre class="bg-light line-numbers"><code class="language-json">{
  "postback": "rota_callback",
  "amount": 100.00,
  "debtor_name": "Nome",
  "email": "email@dominio.com",
  "debtor_document_number": "CPF",
  "phone": "Telefone",
  "method_pay": "pix"
}</code></pre>
          </div>
        </div>

        <div class="mb-4">
          <h4>📥 Resposta</h4>
          <div class="mb-3 w-100">
            <pre class="bg-light line-numbers"><code class="language-json">{
  "idTransaction": "TX123",
  "qrcode": "código copia e cola",
  "qr_code_image_url": "url da imagem"
}</code></pre>
          </div>
        </div>
      </section>

      <!-- Seção Webhook -->
      <section id="webhook" class="mt-5">
        <h2>🔔 Webhook PIX IN</h2>
        <p>Notificações automáticas quando o status da transação for alterado. Na rota informada em "postback" enviado na requisição de PIX IN </p>
        <h4>📬 Exemplo de retorno:</h4>
        <div class="mb-3 w-100">
          <pre class="bg-light line-numbers"><code class="language-json">{
  "status": "paid",
  "idTransaction": "TX123",
  "typeTransaction": "PIX"
}</code></pre>
        </div>
      </section>

      <!-- Seção PIX OUT -->
      <section id="saque" class="mt-5">
        <h2>💸 Saque (PIX OUT)</h2>
        <p>Realiza um saque para uma chave PIX.</p>
        <p><strong>Método:</strong> <span class="method">POST</span></p>
        <code class="p-2 rounded d-block bg-light">{{ env('APP_URL') }}/api/transaction/payment</code>

        <div class="mb-4">
          <h4>🔐 Cabeçalhos (Headers)</h4>
          <div class="mb-3 w-100">
            <pre class="bg-light line-numbers"><code class="language-json">{
  "Content-Type": "application/json",
  "Accept": "application/json",
  "Authorization": "Bearer fadf465d4fas6d54f...", //Ex.: "Bearer ".base64_encode($token.':'.$secret)
  "X-API-KEY": "ffds464fd8898s4sa"
}</code></pre>
          </div>
        </div>

        <div class="mb-4">
          <h4>📤 Corpo da Requisição</h4>
          <p><strong>pixKeyType:</strong> 'cpf' | 'cnpj' | 'email' | 'phone' | 'random' → <span class="text-warning">De acordo com tipo definido em pixKey</span></p>
          <div class="mb-3 w-100">
            <pre class="bg-light line-numbers"><code class="language-json">{
  "baasPostbackUrl": "url_callback",
  "amount": 100.00,
  "pixKey": "chave_pix",
  "pixKeyType": "cpf"
}</code></pre>
          </div>
        </div>

        <div class="mb-4">
          <h4>📥 Resposta</h4>
          <div class="mb-3 w-100">
            <pre class="bg-light line-numbers"><code class="language-json">{
  "id": "b522a295-e404...",
  "amount": 100,
  "pixKey": "chave",
  "pixKeyType": "cpf",
  "withdrawStatusId": "PendingProcessing",
  "createdAt": "2025-04-19T20:04:53.166Z",
  "updatedAt": "2025-04-19T20:04:53.166Z"
}</code></pre>
          </div>
        </div>
      </section>


      <section id="webhook" class="mt-5">
        <h2>🔔 Webhook PIX OUT</h2>
        <p>Notificações automáticas quando o status da transação for alterado. Na rota informada em "baasPostbackUrl" enviado na requisição de PIX OUT </p>
        <h4>📬 Exemplo de retorno:</h4>
        <div class="mb-3 w-100">
          <pre class="bg-light line-numbers"><code class="language-json">{
  "status": "paid",
  "idTransaction": "TX123",
  "typeTransaction": "PAYMENT"
}</code></pre>
        </div>
      </section>
  </div>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/prism.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/plugins/line-numbers/prism-line-numbers.js"></script>
</x-app-layout>
