<x-app-layout :route="'[ADMIN] Ajustes de adquirentes'">
    <div class="main-content app-content">
        <div class="container-fluid">

            <!-- Start::page-header -->
            <div class="mb-3 row justify-content-between align-items-">
                <div style="display:flex;align-item:center;justify-content:flex-start;"
                    class="mb-5 col-12 col-md-4 mb-md-0 justify-content-start align-items-center">
                    <h1 class="mb-0 display-5">Ajuste de adquirentes</h1>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12 mb-3">
                    <div class="card card-raised">
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.adquirentes.default') }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('POST')
                                <div class="row gy-2">
                                    <div class="col-12">
                                        <label for="adquirente" class="form-label">Adquirente padrão</label>
                                        <select class="form-control @error('secret') is-invalid @enderror"
                                            name="adquirente" value="{{ $adquirente }}" required>
                                            <option value="apithekey" {{ $adquirente == 'apithekey' ? 'selected' : '' }}>
                                                The Key</option>
                                            <option value="blupay" {{ $adquirente == 'blupay' ? 'selected' : '' }}>
                                                BluPay</option>
                                            <option value="cashtime" {{ $adquirente == 'cashtime' ? 'selected' : '' }}>
                                                Cashtime</option>
                                            <option value="cartwave" {{ $adquirente == 'cartwave' ? 'selected' : '' }}>
                                                Cartwave</option>
                                            <option value="mercadopago" {{ $adquirente == 'mercadopago' ? 'selected' : '' }}>Mercado Pago</option>
                                            <option value="simpay" {{ $adquirente == 'simpay' ? 'selected' : '' }}>Simpay
                                            </option>
                                            <option value="witetec" {{ $adquirente == 'witetec' ? 'selected' : '' }}>
                                                Witetec</option>
                                            <option value="zoompag" {{ $adquirente == 'zoompag' ? 'selected' : '' }}>
                                                Zoompag</option>
                                        </select>
                                        @error('secret')
                                            <span style="color: red;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-xl-12 text-end">
                                        <button type="submit" class="btn btn-primary">Alterar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Start::row-2 -->
            <div class="row">
                <div class="col-xl-12 mb-3">
                    <div class="card card-raised">
                        <div class="bg-transparent card-header justify-content-between">
                            <div class="card-title">
                                SimPAY
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.adquirentes.simpay') }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('POST')
                                <div class="row gy-2">
                                    <div class="col-xl-4">
                                        <label for="x_api_key" class="form-label">API KEY</label>
                                        <input type="text" class="form-control @error('x_api_key') is-invalid @enderror"
                                            name="x_api_key" value="{{ $simpay->x_api_key }}" required>
                                        @error('x_api_key')
                                            <span style="color: red;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-xl-4">
                                        <label for="taxa_pix_cash_in" class="form-label">Taxa (PIX-IN)</label>
                                        <input type="number" step="0.01"
                                            class="form-control @error('taxa_pix_cash_in') is-invalid @enderror"
                                            name="taxa_pix_cash_in" value="{{ $simpay->taxa_pix_cash_in }}" required>
                                        @error('taxa_pix_cash_in')
                                            <span style="color: red;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-xl-4">
                                        <label for="taxa_pix_cash_out" class="form-label">Taxa (PIX-OUT)</label>
                                        <input type="number" step="0.01"
                                            class="form-control @error('taxa_pix_cash_out') is-invalid @enderror"
                                            name="taxa_pix_cash_out" value="{{ $simpay->taxa_pix_cash_out }}" required>
                                        @error('taxa_pix_cash_in')
                                            <span style="color: red;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-xl-12 text-end">
                                        <button type="submit" class="btn btn-primary">Alterar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Start::row-2 -->
            <div class="row">
                <div class="col-xl-12 mb-3">
                    <div class="card card-raised">
                        <div class="bg-transparent card-header justify-content-between">
                            <div class="card-title">
                                Cashtime
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.adquirentes.cashtime') }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('POST')
                                <div class="row gy-2">
                                    <div class="col-xl-4">
                                        <label for="secret" class="form-label">Chave Secreta</label>
                                        <input type="text" class="form-control @error('secret') is-invalid @enderror"
                                            name="secret" value="{{ $cashtime->secret }}" required>
                                        @error('secret')
                                            <span style="color: red;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-xl-4">
                                        <label for="taxa_pix_cash_in" class="form-label">Taxa (PIX-IN)</label>
                                        <input type="number" step="0.01"
                                            class="form-control @error('taxa_pix_cash_in') is-invalid @enderror"
                                            name="taxa_pix_cash_in" value="{{ $cashtime->taxa_pix_cash_in }}" required>
                                        @error('taxa_pix_cash_in')
                                            <span style="color: red;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-xl-4">
                                        <label for="taxa_pix_cash_out" class="form-label">Taxa (PIX-OUT)</label>
                                        <input type="number" step="0.01"
                                            class="form-control @error('taxa_pix_cash_out') is-invalid @enderror"
                                            name="taxa_pix_cash_out" value="{{ $cashtime->taxa_pix_cash_out }}"
                                            required>
                                        @error('taxa_pix_cash_in')
                                            <span style="color: red;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-xl-12 text-end">
                                        <button type="submit" class="btn btn-primary">Alterar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                    <div class="col-xl-12 mb-3">
                        <div class="card card-raised">
                            <div class="bg-transparent card-header justify-content-between">
                                <div class="card-title">
                                    Cartwave
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('admin.adquirentes.cartwave') }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('POST')
                                    <div class="row gy-2">
                                        <div class="col-xl-4">
                                            <label for="secret" class="form-label">Chave Secreta</label>
                                            <input type="text"
                                                class="form-control @error('secret') is-invalid @enderror" name="secret"
                                                value="{{ $cartwave->secret }}" required>
                                            @error('secret')
                                                <span style="color: red;">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-xl-4">
                                            <label for="taxa_pix_cash_in" class="form-label">Taxa (PIX-IN)</label>
                                            <input type="number" step="0.01"
                                                class="form-control @error('taxa_pix_cash_in') is-invalid @enderror"
                                                name="taxa_pix_cash_in" value="{{ $cartwave->taxa_pix_cash_in }}"
                                                required>
                                            @error('taxa_pix_cash_in')
                                                <span style="color: red;">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-xl-4">
                                            <label for="taxa_pix_cash_out" class="form-label">Taxa (PIX-OUT)</label>
                                            <input type="number" step="0.01"
                                                class="form-control @error('taxa_pix_cash_out') is-invalid @enderror"
                                                name="taxa_pix_cash_out" value="{{ $cartwave->taxa_pix_cash_out }}"
                                                required>
                                            @error('taxa_pix_cash_in')
                                                <span style="color: red;">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-xl-12 text-end">
                                            <button type="submit" class="btn btn-primary">Alterar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-12 mb-3">
                        <div class="card card-raised">
                            <div class="bg-transparent card-header justify-content-between">
                                <div class="card-title">
                                    The Key
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('admin.adquirentes.apithekey') }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('POST')
                                    <div class="row gy-2">
                                        <div class="col-xl-4">
                                            <label for="client_id" class="form-label">Client ID</label>
                                            <input type="text"
                                                class="form-control @error('client_id') is-invalid @enderror"
                                                name="client_id" value="{{ $apithekey->client_id }}" required>
                                            @error('client_id')
                                                <span style="color: red;">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-xl-4">
                                            <label for="client_secret" class="form-label">Client Secret</label>
                                            <input type="text"
                                                class="form-control @error('client_secret') is-invalid @enderror"
                                                name="client_secret" value="{{ $apithekey->client_secret }}" required>
                                            @error('client_secret')
                                                <span style="color: red;">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-xl-2">
                                            <label for="taxa_pix_cash_in" class="form-label">Taxa (PIX-IN)</label>
                                            <input type="number" step="0.01"
                                                class="form-control @error('taxa_pix_cash_in') is-invalid @enderror"
                                                name="taxa_pix_cash_in" value="{{ $apithekey->taxa_pix_cash_in }}"
                                                required>
                                            @error('taxa_pix_cash_in')
                                                <span style="color: red;">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-xl-2">
                                            <label for="taxa_pix_cash_out" class="form-label">Taxa (PIX-OUT)</label>
                                            <input type="number" step="0.01"
                                                class="form-control @error('taxa_pix_cash_out') is-invalid @enderror"
                                                name="taxa_pix_cash_out" value="{{ $apithekey->taxa_pix_cash_out }}"
                                                required>
                                            @error('taxa_pix_cash_in')
                                                <span style="color: red;">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-xl-12 text-end">
                                            <button type="submit" class="btn btn-primary">Alterar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
            </div>

                <div class="row mb-3">
                    <div class="col-xl-12">
                        <div class="card card-raised">
                            <div class="bg-transparent card-header justify-content-between">
                                <div class="card-title d-flex align-items-center justify-content-between">
                                    <span>Witetec</span>
                                    <div>
                                        <form method="POST" action="{{ route('witetec.webhooks') }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('POST')
                                            <button type="submit" class="btn btn-outline-primary">Registrar
                                                Webhooks</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('admin.adquirentes.witetec') }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('POST')
                                    <div class="row gy-2">
                                        <div class="col-12 col-xl-6">
                                            <label for="api_token" class="form-label">API Key</label>
                                            <input type="text"
                                                class="form-control @error('api_token') is-invalid @enderror"
                                                name="api_token" value="{{ $witetec->api_token ?? null }}" required>
                                            @error('api_token')
                                                <span style="color: red;">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-xl-3">
                                            <label for="taxa_pix_cash_in" class="form-label">Taxa (PIX-IN)</label>
                                            <input type="number" step="0.01"
                                                class="form-control @error('taxa_pix_cash_in') is-invalid @enderror"
                                                name="taxa_pix_cash_in" value="{{ $witetec->taxa_pix_cash_in }}"
                                                required>
                                            @error('taxa_pix_cash_in')
                                                <span style="color: red;">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-xl-3">
                                            <label for="taxa_pix_cash_out" class="form-label">Taxa (PIX-OUT)</label>
                                            <input type="number" step="0.01"
                                                class="form-control @error('taxa_pix_cash_out') is-invalid @enderror"
                                                name="taxa_pix_cash_out" value="{{ $witetec->taxa_pix_cash_out }}"
                                                required>
                                            @error('taxa_pix_cash_in')
                                                <span style="color: red;">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-xl-12 text-end">
                                            <button type="submit" class="btn btn-primary">Alterar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-xl-12">
                        <div class="card card-raised">
                            <div class="bg-transparent card-header justify-content-between">
                                <div class="card-title d-flex align-items-center justify-content-between">
                                    <span>Zoompag</span>
                                    <div>
                                        <form method="POST" action="{{ route('zoompag.webhooks') }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('POST')
                                            <button type="submit" class="btn btn-outline-primary">Registrar
                                                Webhooks</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('admin.adquirentes.zoompag') }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('POST')
                                    <div class="row gy-2">
                                        <div class="col-12 col-xl-6">
                                            <label for="api_token" class="form-label">API Key</label>
                                            <input type="text"
                                                class="form-control @error('api_token') is-invalid @enderror"
                                                name="api_token" value="{{ $zoompag->api_token ?? null }}" required>
                                            @error('api_token')
                                                <span style="color: red;">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-xl-3">
                                            <label for="taxa_pix_cash_in" class="form-label">Taxa (PIX-IN)</label>
                                            <input type="number" step="0.01"
                                                class="form-control @error('taxa_pix_cash_in') is-invalid @enderror"
                                                name="taxa_pix_cash_in" value="{{ $zoompag->taxa_pix_cash_in }}"
                                                required>
                                            @error('taxa_pix_cash_in')
                                                <span style="color: red;">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-xl-3">
                                            <label for="taxa_pix_cash_out" class="form-label">Taxa (PIX-OUT)</label>
                                            <input type="number" step="0.01"
                                                class="form-control @error('taxa_pix_cash_out') is-invalid @enderror"
                                                name="taxa_pix_cash_out" value="{{ $zoompag->taxa_pix_cash_out }}"
                                                required>
                                            @error('taxa_pix_cash_in')
                                                <span style="color: red;">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-xl-12 text-end">
                                            <button type="submit" class="btn btn-primary">Alterar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-xl-12">
                        <div class="card card-raised">
                            <div class="bg-transparent card-header justify-content-between">
                                <div class="card-title d-flex align-items-center justify-content-between">
                                    <span>BluPAY</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('admin.adquirentes.blupay') }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('POST')
                                    <div class="row gy-2">
                                        <div class="col-12 col-xl-4">
                                            <label for="username" class="form-label">Secret Key</label>
                                            <input type="text"
                                                class="form-control @error('username') is-invalid @enderror"
                                                name="username" value="{{ $blupay->username ?? null }}" required>
                                            @error('username')
                                                <span style="color: red;">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-xl-4">
                                            <label for="password" class="form-label">Public Key</label>
                                            <input type="text"
                                                class="form-control @error('password') is-invalid @enderror"
                                                name="password" value="{{ $blupay->password ?? null }}" required>
                                            @error('password')
                                                <span style="color: red;">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-xl-4">
                                            <label for="idempotency_key" class="form-label">Idempotency Key</label>
                                            <input type="text"
                                                class="form-control @error('idempotency_key') is-invalid @enderror"
                                                name="idempotency_key" value="{{ $blupay->idempotency_key ?? null }}" required>
                                            @error('idempotency_key')
                                                <span style="color: red;">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-xl-6">
                                            <label for="taxa_pix_cash_in" class="form-label">Taxa (PIX-IN)</label>
                                            <input type="number" step="0.01"
                                                class="form-control @error('taxa_pix_cash_in') is-invalid @enderror"
                                                name="taxa_pix_cash_in" value="{{ $blupay->taxa_pix_cash_in }}"
                                                required>
                                            @error('taxa_pix_cash_in')
                                                <span style="color: red;">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-xl-6">
                                            <label for="taxa_pix_cash_out" class="form-label">Taxa (PIX-OUT)</label>
                                            <input type="number" step="0.01"
                                                class="form-control @error('taxa_pix_cash_out') is-invalid @enderror"
                                                name="taxa_pix_cash_out" value="{{ $blupay->taxa_pix_cash_out }}"
                                                required>
                                            @error('taxa_pix_cash_in')
                                                <span style="color: red;">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-xl-12 text-end">
                                            <button type="submit" class="btn btn-primary">Alterar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Start::row-2 -->
                <div class="row">
                    <div class="col-xl-12 mb-3">
                        <div class="card card-raised">
                            <div class="bg-transparent card-header justify-content-between">
                                <div class="card-title">
                                    Mercado Pago
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('admin.adquirentes.mercadopago') }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('POST')
                                    <div class="row gy-2">
                                        <div class="col-xl-8">
                                            <label for="access_token" class="form-label">Access Token</label>
                                            <input type="text"
                                                class="form-control @error('access_token') is-invalid @enderror"
                                                name="access_token" value="{{ $mercadopago->access_token }}" required>
                                            @error('access_token')
                                                <span style="color: red;">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-xl-4">
                                            <label for="taxa_pix_cash_in" class="form-label">Taxa (PIX-IN)</label>
                                            <input type="number" step="0.01"
                                                class="form-control @error('taxa_pix_cash_in') is-invalid @enderror"
                                                name="taxa_pix_cash_in" value="{{ $mercadopago->taxa_pix_cash_in }}"
                                                required>
                                            @error('taxa_pix_cash_in')
                                                <span style="color: red;">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-xl-12 text-end">
                                            <button type="submit" class="btn btn-primary">Alterar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
</x-app-layout>