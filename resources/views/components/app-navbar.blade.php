@php
    $apps = [
        [
            'label' => 'Dashboard',
            'icon' => 'house',
            'action' => '/dashboard',
        ],
        [
            'label' => 'Entradas',
            'icon' => 'download',
            'action' => '/relatorio/entradas',
        ],
        [
            'label' => 'Saídas',
            'icon' => 'upload',
            'action' => '/relatorio/saidas',
        ],
        [
            'label' => 'Financeiro',
            'icon' => 'chart-no-axes-combined',
            'action' => '/financeiro',
        ],
        [
            'label' => 'Produtos',
            'icon' => 'store',
            'action' => route('profile.checkout'),
        ],
        [
            'label' => 'Pedidos',
            'icon' => 'package',
            'action' => route('profile.orders'),
        ],
        [
            'label' => 'Documentação',
            'icon' => 'book-key',
            'action' => '/documentacao',
        ],
        [
            'label' => 'Chaves API',
            'icon' => 'key',
            'action' => '/chaves',
        ],
    ];

    $appsAdmin = [
        [
            'label' => 'Dashboard',
            'icon' => 'house',
            'action' => env('ADM_ROUTE').'/dashboard',
        ],
        [
            'label' => 'Clientes',
            'icon' => 'users',
            'action' => env('ADM_ROUTE').'/usuarios',
        ],
        [
            'label' => 'Aprovar saque',
            'icon' => 'list-checks',
            'action' => env('ADM_ROUTE').'/aprovar-saques',
        ],
        [
            'label' => 'Transações',
            'icon' => 'activity',
            'action' => env('ADM_ROUTE').'/financeiro/transacoes',
        ],
        [
            'label' => 'Carteiras',
            'icon' => 'wallet',
            'action' => env('ADM_ROUTE').'/financeiro/carteiras',
        ],
        [
            'label' => 'Entradas',
            'icon' => 'download',
            'action' => env('ADM_ROUTE').'/financeiro/entradas',
        ],
        [
            'label' => 'Saídas',
            'icon' => 'upload',
            'action' => env('ADM_ROUTE').'/financeiro/saidas',
        ],
        [
            'label' => 'Configurações',
            'icon' => 'settings',
            'action' => env('ADM_ROUTE').'/ajustes/gerais',
        ],
        [
            'label' => 'Adquirentes',
            'icon' => 'landmark',
            'action' => env('ADM_ROUTE').'/ajustes/adquirentes',
        ],
        [
            'label' => 'Criar entrada',
            'icon' => 'download',
            'action' => env('ADM_ROUTE').'/transacoes/entrada',
        ],
        [
            'label' => 'Criar saída',
            'icon' => 'upload',
            'action' => env('ADM_ROUTE').'/transacoes/saida',
        ],
        [
            'label' => 'Gameficação',
            'icon' => 'gamepad-2',
            'action' => route('gamefication.index'),
        ],
    ];

    $appsGerente = [
        [
            'label' => 'Clientes',
            'icon' => 'users',
            'action' => '/gerencia/clientes',
        ],
    ];
@endphp


<li class="nav-item dropdown dropdown-app">
    <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown" href="javascript:;"><i
            class="bx bx-grid-alt"></i></a>
    <div class="dropdown-menu dropdown-menu-end p-0">
        <div class="app-container p-2 my-2 ps">
            <div class="row gx-0 gy-2 row-cols-3 justify-content-center p-2">
                @if (auth()->user()->permission == 1)
                    @foreach ($apps as $app)
                        <div class="col">
                            <a href="{{ $app['action'] }}">
                                <div class="app-box text-center">
                                    <div class="app-icon text-center">
                                        <i data-lucide="{{ $app['icon'] }}"></i>
                                    </div>
                                    <div class="app-name text-center">
                                        <p class="mb-0 mt-1">{{ $app['label'] }}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                @elseif(auth()->user()->permission == 3)
                    @foreach ($appsAdmin as $app)
                        <div class="col">
                            <a href="{{ $app['action'] }}">
                                <div class="app-box text-center">
                                    <div class="app-icon text-center">
                                        <i data-lucide="{{ $app['icon'] }}"></i>
                                    </div>
                                    <div class="app-name text-center">
                                        <p class="mb-0 mt-1">{{ $app['label'] }}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                @elseif(auth()->user()->permission == 9)
                    @foreach ($appsGerente as $app)
                        <div class="col">
                            <a href="{{ $app['action'] }}">
                                <div class="app-box text-center">
                                    <div class="app-icon text-center">
                                        <i data-lucide="{{ $app['icon'] }}"></i>
                                    </div>
                                    <div class="app-name text-center">
                                        <p class="mb-0 mt-1">{{ $app['label'] }}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                @endif
                
            </div>

            <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
                <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
            </div>
            <div class="ps__rail-y" style="top: 0px; right: 0px;">
                <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div>
            </div>
        </div>
    </div>
</li>