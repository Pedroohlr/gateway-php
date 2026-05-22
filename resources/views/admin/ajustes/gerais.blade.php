<x-app-layout :route="'[ADMIN] Ajustes Gerais'">
    <div class="main-content app-content">
        <div class="container-fluid">

            <!-- Start::page-header -->
            <div class="mb-3 row justify-content-between align-items-">
                <div style="display:flex;align-item:center;justify-content:flex-start;"
                    class="mb-5 col-12 col-md-4 mb-md-0 justify-content-start align-items-center">
                    <h1 class="mb-0 display-5">Ajustes gerais</h1>
                </div>
            </div>

            <!-- Start::row-2 -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card card-raised">
                        <div class="card-body">
                            <form id="form-geral" method="POST" action="{{ route('admin.ajustes.gerais') }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('POST')
                                <div class="row gy-2">
                                    <div class="col-xl-4">
                                        <label for="taxa_cash_in_padrao" class="form-label">Taxa PIX IN (%)</label>
                                        <input type="text"
                                            class="form-control @error('taxa_cash_in_padrao') is-invalid @enderror"
                                            name="taxa_cash_in_padrao" value="{{ $setting->taxa_cash_in_padrao }}"
                                            required>
                                        @error('taxa_cash_in_padrao')
                                            <span style="color: red;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-xl-4">
                                        <label for="taxa_fixa_padrao" class="form-label">Taxa Fixa PIX IN (R$)</label>
                                        <input type="text"
                                            class="form-control @error('taxa_fixa_padrao') is-invalid @enderror"
                                            name="taxa_fixa_padrao" value="{{ $setting->taxa_fixa_padrao }}" required>
                                        @error('taxa_fixa_padrao')
                                            <span style="color: red;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-xl-4">
                                        <label for="baseline" class="form-label">Taxa Baseline (R$)</label>
                                        <input type="text" class="form-control @error('baseline') is-invalid @enderror"
                                            name="baseline" value="{{ $setting->baseline }}" required>
                                        @error('baseline')
                                            <span style="color: red;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-xl-6">
                                        <label for="taxa_cash_out_padrao" class="form-label">Taxa PIX OUT (%)</label>
                                        <input type="text"
                                            class="form-control @error('taxa_cash_out_padrao') is-invalid @enderror"
                                            name="taxa_cash_out_padrao" value="{{ $setting->taxa_cash_out_padrao }}"
                                            required>
                                        @error('taxa_cash_out_padrao')
                                            <span style="color: red;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-xl-6">
                                        <label for="taxa_fixa_padrao_cash_out" class="form-label">Taxa Fixa PIX OUT
                                            (R$)</label>
                                        <input type="text"
                                            class="form-control @error('taxa_fixa_padrao_cash_out') is-invalid @enderror"
                                            name="taxa_fixa_padrao_cash_out"
                                            value="{{ $setting->taxa_fixa_padrao_cash_out }}" required>
                                        @error('taxa_fixa_padrao')
                                            <span style="color: red;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3">
                                        <label for="deposito_minimo" class="form-label">Depósito Mínimo</label>
                                        <input type="text"
                                            class="form-control @error('deposito_minimo') is-invalid @enderror"
                                            name="deposito_minimo" value="{{ $setting->deposito_minimo }}" required>
                                        @error('deposito_minimo')
                                            <span style="color: red;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3">
                                        <label for="saque_minimo" class="form-label">Saque Mínimo</label>
                                        <input type="text"
                                            class="form-control @error('saque_minimo') is-invalid @enderror"
                                            name="saque_minimo" value="{{ $setting->saque_minimo }}" required>
                                        @error('saque_minimo')
                                            <span style="color: red;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-xl-3">
                                        <label for="limite_saque_mensal" class="form-label">Limite Mensal (P.F)</label>
                                        <input type="text"
                                            class="form-control @error('limite_saque_mensal') is-invalid @enderror"
                                            name="limite_saque_mensal" value="{{ $setting->limite_saque_mensal }}"
                                            required>
                                        @error('limite_saque_mensal')
                                            <span style="color: red;">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-xl-3">
                                        <label for="limite_saque_automatico" class="form-label">Limite Saque
                                            (automatico)</label>
                                        <input type="text"
                                            class="form-control @error('limite_saque_automatico') is-invalid @enderror"
                                            name="limite_saque_automatico"
                                            value="{{ $setting->limite_saque_automatico }}" required>
                                        @error('limite_saque_automatico')
                                            <span style="color: red;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-xl-12">
                                        <label for="hour_limit_withdraw" class="form-label">Limitar Saque até
                                            (Horas)</label></label>
                                        <select class="form-control @error('hour_limit_withdraw') is-invalid @enderror"
                                            name="hour_limit_withdraw" value="{{ $setting->hour_limit_withdraw }}"
                                            required>
                                            <option value="none" {{ $setting->hour_limit_withdraw == null ? 'selected' : '' }}>Não limitar</option>
                                            <option value="0" {{ $setting->hour_limit_withdraw == '0' ? 'selected' : '' }}>00:00</option>
                                            <option value="1" {{ $setting->hour_limit_withdraw == '1' ? 'selected' : '' }}>01:00</option>
                                            <option value="2" {{ $setting->hour_limit_withdraw == '2' ? 'selected' : '' }}>02:00</option>
                                            <option value="3" {{ $setting->hour_limit_withdraw == '3' ? 'selected' : '' }}>03:00</option>
                                            <option value="4" {{ $setting->hour_limit_withdraw == '4' ? 'selected' : '' }}>04:00</option>
                                            <option value="5" {{ $setting->hour_limit_withdraw == '5' ? 'selected' : '' }}>05:00</option>
                                            <option value="6" {{ $setting->hour_limit_withdraw == '6' ? 'selected' : '' }}>06:00</option>
                                            <option value="7" {{ $setting->hour_limit_withdraw == '7' ? 'selected' : '' }}>07:00</option>
                                            <option value="8" {{ $setting->hour_limit_withdraw == '8' ? 'selected' : '' }}>08:00</option>
                                            <option value="9" {{ $setting->hour_limit_withdraw == '9' ? 'selected' : '' }}>09:00</option>
                                            <option value="10" {{ $setting->hour_limit_withdraw == '10' ? 'selected' : '' }}>10:00</option>
                                            <option value="11" {{ $setting->hour_limit_withdraw == '11' ? 'selected' : '' }}>11:00</option>
                                            <option value="12" {{ $setting->hour_limit_withdraw == '12' ? 'selected' : '' }}>12:00</option>
                                            <option value="13" {{ $setting->hour_limit_withdraw == '13' ? 'selected' : '' }}>13:00</option>
                                            <option value="14" {{ $setting->hour_limit_withdraw == '14' ? 'selected' : '' }}>14:00</option>
                                            <option value="15" {{ $setting->hour_limit_withdraw == '15' ? 'selected' : '' }}>15:00</option>
                                            <option value="16" {{ $setting->hour_limit_withdraw == '16' ? 'selected' : '' }}>16:00</option>
                                            <option value="17" {{ $setting->hour_limit_withdraw == '17' ? 'selected' : '' }}>17:00</option>
                                            <option value="18" {{ $setting->hour_limit_withdraw == '18' ? 'selected' : '' }}>18:00</option>
                                            <option value="19" {{ $setting->hour_limit_withdraw == '19' ? 'selected' : '' }}>19:00</option>
                                            <option value="20" {{ $setting->hour_limit_withdraw == '20' ? 'selected' : '' }}>20:00</option>
                                            <option value="21" {{ $setting->hour_limit_withdraw == '21' ? 'selected' : '' }}>21:00</option>
                                            <option value="22" {{ $setting->hour_limit_withdraw == '22' ? 'selected' : '' }}>22:00</option>
                                            <option value="23" {{ $setting->hour_limit_withdraw == '23' ? 'selected' : '' }}>23:00</option>
                                        </select>
                                        @error('hour_limit_withdraw')
                                            <span style="color: red;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-xl-6">
                                        <label for="gateway_name" class="form-label">Gateway Name</label>
                                        <input type="text"
                                            class="form-control @error('gateway_name') is-invalid @enderror"
                                            name="gateway_name" value="{{ $setting->gateway_name }}" required>
                                        @error('gateway_name')
                                            <span style="color: red;">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-xl-6">
                                        <label for="gateway_color" class="form-label">Cor padrão</label>
                                        <input type="color" style="height:42px;"
                                            class="form-control @error('gateway_color') is-invalid @enderror"
                                            name="gateway_color" value="{{ $setting->gateway_color }}" required>
                                        @error('gateway_color')
                                            <span style="color: red;">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        @php
                                            $bgs = [
                                                'bg-theme1',
                                                'bg-theme2',
                                                'bg-theme3',
                                                'bg-theme4',
                                                'bg-theme5',
                                                'bg-theme6',
                                                'bg-theme7',
                                                'bg-theme8',
                                                'bg-theme9',
                                                'bg-theme10',
                                                'bg-theme11',
                                                'bg-theme12',
                                                'bg-theme13',
                                                'bg-theme14',
                                                'bg-theme15'
                                            ];
                                        @endphp
                                        <p class="mb-1">Selecione o tema</p>
                                        <ul class="switcher">
                                            @foreach ($bgs as $bg)
                                                <li id="{{ str_replace('bg-', '', $bg) }}" 
                                                    style="position:relative;cursor:pointer;border: 2px solid {{ $setting->bg_theme == $bg ? 'white' : 'transparent' }};"
                                                    onclick="selTheme('{{ $bg }}')">
                                                    @if($setting->bg_theme == $bg)
                                                        <small style="position: absolute;bottom: 3px;left:22%;right:0;font-weight:bold;">Atual</small>
                                                    @endif
                                                    </li>
                                            @endforeach
                                        </ul>
                                    </div>

                                    <div class="col-xl-6">
                                        <label for="cnpj" class="form-label">CNPJ</label>
                                        <input type="text" class="form-control @error('cnpj') is-invalid @enderror"
                                            name="cnpj" value="{{ $setting->cnpj }}" required>
                                        @error('cnpj')
                                            <span style="color: red;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-xl-6">
                                        <label for="contato" class="form-label">Contato (Gerente)</label>
                                        <input type="text" class="form-control @error('contato') is-invalid @enderror"
                                            name="contato" value="{{ $setting->contato }}" required>
                                        @error('contato')
                                            <span style="color: red;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <x-image-upload id="gateway_logo" name="gateway_logo" label="Logo"
                                            :value="asset($setting->gateway_logo)" />
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <x-image-upload id="gateway_favicon" name="gateway_favicon" label="Icone"
                                            :value="asset($setting->gateway_favicon)" />
                                    </div>
                                    <div class="col-12">
                                        <x-image-upload id="gateway_banner_home" name="gateway_banner_home"
                                            label="Banner Dashboard" :value="asset($setting->gateway_banner_home)" />
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
    </div>
    <script>
        function selTheme(theme) {
            const body = document.body;

            // Remove todas as classes que começam com "bg-theme" (1 a 15)
            for (let i = 1; i <= 15; i++) {
                body.classList.remove(`bg-theme${i}`);
            }

            // Adiciona a nova classe de tema
            body.classList.add(theme);

            // Adiciona campo hidden ao formulário
            const form = document.getElementById('form-geral');
            if (form) {
                // Remove o input anterior, se já existir
                const existing = form.querySelector('input[name="bg_theme"]');
                if (existing) existing.remove();

                const bgtheme = document.createElement('input');
                bgtheme.type = 'hidden';
                bgtheme.name = 'bg_theme';
                bgtheme.value = theme;
                form.appendChild(bgtheme);
            }
        }

    </script>
</x-app-layout>