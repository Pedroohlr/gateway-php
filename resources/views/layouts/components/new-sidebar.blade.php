@php
    $setting = \App\Helpers\Helper::getSetting();
@endphp

<div class="sidebar-wrapper" data-simplebar="init" onmouseenter="openHovered('enter')"
    onmouseleave="openHovered('leave')">
    <div class="simplebar-wrapper" style="margin: 0px;">
        <div class="simplebar-height-auto-observer-wrapper">
            <div class="simplebar-height-auto-observer"></div>
        </div>
        <div class="simplebar-mask">
            <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                <div class="simplebar-content-wrapper" style="height: 100%; overflow: hidden scroll;">
                    <div class="simplebar-content mm-active" style="padding: 0px;">
                        <div class="sidebar-header">
                            <div>
                                <img src="{{ asset($setting->gateway_logo) }}" class="logo-icon" alt="logo icon">
                            </div>
                            <div>
                                <h4 class="logo-text">{{ $setting->gateway_name }}</h4>
                            </div>
                            <div class="toggle-icon ms-auto" onclick="openSidebar()"><i data-lucide="menu"
                                    class="me-2"></i>
                            </div>
                        </div>
                        <!--navigation-->
                        <ul class="metismenu mm-show" id="menu">
                            <li>
                                <a href="/dashboard" aria-expanded="false">
                                    <div class="parent-icon"><i data-lucide="house" class="me-2"></i>
                                    </div>
                                    <div class="menu-title">Dashboard</div>
                                </a>
                            </li>

                            @if($status == 1 && $permission != 9)
                                <li>
                                    <a href="/relatorio/entradas" aria-expanded="false">
                                        <div class="parent-icon"><i data-lucide="download" class="me-2"></i>
                                        </div>
                                        <div class="menu-title">Entradas</div>
                                    </a>
                                </li>
                                <li>
                                    <a href="/relatorio/saidas" aria-expanded="false">
                                        <div class="parent-icon"><i data-lucide="upload" class="me-2"></i>
                                        </div>
                                        <div class="menu-title">Saídas</div>
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('profile.relatorio.infracoes') }}" aria-expanded="false">
                                        <div class="parent-icon"><i data-lucide="triangle-alert" class="me-2"></i>
                                        </div>
                                        <div class="menu-title">Infrações</div>
                                    </a>
                                </li>
                                <li>
                                    <a href="/financeiro" aria-expanded="false">
                                        <div class="parent-icon"><i data-lucide="chart-no-axes-combined" class="me-2"></i>
                                        </div>
                                        <div class="menu-title">Financeiro</div>
                                    </a>
                                </li>

                                <li>
                                    <a href="{{route('profile.checkout')}}" aria-expanded="false">
                                        <div class="parent-icon"><i data-lucide="store" class="me-2"></i>
                                        </div>
                                        <div class="menu-title">Produtos</div>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{route('profile.orders')}}" aria-expanded="false">
                                        <div class="parent-icon"><i data-lucide="package" class="me-2"></i>
                                        </div>
                                        <div class="menu-title">Pedidos</div>
                                    </a>
                                </li>
                                <li>
                                    <a href="/documentacao" aria-expanded="false">
                                        <div class="parent-icon"><i data-lucide="book-key" class="me-2"></i>
                                        </div>
                                        <div class="menu-title">Documentação</div>
                                    </a>
                                </li>
                                <li>
                                    <a href="/chaves" aria-expanded="false">
                                        <div class="parent-icon"><i data-lucide="key" class="me-2"></i>
                                        </div>
                                        <div class="menu-title">Chaves API</div>
                                    </a>
                                </li>
                                <li>
                                    <a href="/app/install" aria-expanded="false" class="" id="app-install">
                                        <div class="parent-icon"><i data-lucide="smartphone" class="me-2"></i>
                                        </div>
                                        <div class="menu-title">Aplicativo</div>
                                    </a>
                                </li>

                                @if($permission >= 3 && $permission < 9)
                                    <li class="menu-label">Administrativo</li>
                                    <li>
                                        <a href="/{{ env('ADM_ROUTE') }}/dashboard" aria-expanded="false">
                                            <div class="parent-icon"><i data-lucide="home" class="me-2"></i>
                                            </div>
                                            <div class="menu-title">Dashboard</div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/{{ env('ADM_ROUTE') }}/usuarios" aria-expanded="false">
                                            <div class="parent-icon"><i data-lucide="users" class="me-2"></i>
                                            </div>
                                            <div class="menu-title">Clientes</div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/{{ env('ADM_ROUTE') }}/aprovar-saques" aria-expanded="false">
                                            <div class="parent-icon"><i data-lucide="list-checks" class="me-2"></i>
                                            </div>
                                            <div class="menu-title">Aprovar saques</div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/{{ env('ADM_ROUTE') }}/financeiro/infracoes">
                                            <div class="parent-icon"><i data-lucide="triangle-alert" class="me-2"></i></div>
                                            <div class="menu-title">Infrações</div>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:;" class="has-arrow" aria-expanded="false">
                                            <div class="parent-icon"><i data-lucide="activity"></i>
                                            </div>
                                            <div class="menu-title">Financeiro</div>
                                        </a>
                                        <ul class="mm-collapse">
                                            <li>
                                                <a href="/{{ env('ADM_ROUTE') }}/financeiro/transacoes">
                                                    <i data-lucide="refresh-ccw" class="me-2"></i>Transações
                                                </a>
                                            </li>
                                            <li>
                                                <a href="/{{ env('ADM_ROUTE') }}/financeiro/carteiras">
                                                    <i data-lucide="wallet" class="me-2"></i>Carteiras
                                                </a>
                                            </li>
                                            <li>
                                                <a href="/{{ env('ADM_ROUTE') }}/financeiro/entradas">
                                                    <i data-lucide="download" class="me-2"></i>Entradas
                                                </a>
                                            </li>
                                            <li>
                                                <a href="/{{ env('ADM_ROUTE') }}/financeiro/saidas">
                                                    <i data-lucide="upload" class="me-2"></i>Saídas
                                                </a>
                                            </li>

                                        </ul>
                                    </li>

                                    <li>
                                        <a href="javascript:;" class="has-arrow" aria-expanded="false">
                                            <div class="parent-icon"><i data-lucide="arrow-down-up"></i>
                                            </div>
                                            <div class="menu-title">Transações</div>
                                        </a>
                                        <ul class="mm-collapse">
                                            <li>
                                                <a href="/{{ env('ADM_ROUTE') }}/transacoes/entrada">
                                                    <i data-lucide="download" class="me-2"></i>Entrada
                                                </a>
                                            </li>
                                            <li>
                                                <a href="/{{ env('ADM_ROUTE') }}/transacoes/saida">
                                                    <i data-lucide="upload" class="me-2"></i>Saída
                                                </a>
                                            </li>
                                        </ul>
                                    </li>

                                    <li>
                                        <a href="javascript:;" class="has-arrow" aria-expanded="false">
                                            <div class="parent-icon"><i data-lucide="settings"></i>
                                            </div>
                                            <div class="menu-title">Configurações</div>
                                        </a>
                                        <ul class="mm-collapse">
                                            <li>
                                                <a href="/{{ env('ADM_ROUTE') }}/ajustes/gerais">
                                                    <i data-lucide="settings" class="me-2"></i>Gerais
                                                </a>
                                            </li>
                                            <li>
                                                <a href="/{{ env('ADM_ROUTE') }}/ajustes/adquirentes">
                                                    <i data-lucide="landmark" class="me-2"></i>Adquirentes
                                                </a>
                                            </li>
                                            <li>
                                                <a href="/{{ env('ADM_ROUTE') }}/ajustes/notificacoes">
                                                    <i data-lucide="tablet-smartphone" class="me-2"></i>Notificações
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.ajustes.smtp.index') }}">
                                                    <i data-lucide="at-sign" class="me-2"></i>SMTP
                                                </a>
                                            </li>
                                            <li>
                                                <a href="/{{ env('ADM_ROUTE') }}/ajustes/gerentes">
                                                    <i data-lucide="user-star" class="me-2"></i>Gerentes
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('gamefication.index') }}">
                                                    <i data-lucide="gamepad-2" class="me-2"></i>Gameficação
                                                </a>
                                            </li>
                                            <li>
                                                <a href="/{{ env('ADM_ROUTE') }}/ajustes/landing-page">
                                                    <i data-lucide="panel-top" class="me-2"></i>Landing Page
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                @endif
                                @if($permission == 9)
                                    <li class="menu-label">Gerente</li>
                                    <li>
                                        <a href="/gerencia/clientes" aria-expanded="false">
                                            <div class="parent-icon"><i data-lucide="users" class="me-2"></i>
                                            </div>
                                            <div class="menu-title">Meus clientes</div>
                                        </a>
                                    </li>
                                @endif
                            @endif
                        </ul>
                        <!--end navigation-->
                    </div>
                </div>
            </div>
        </div>
        <div class="simplebar-placeholder" style="width: auto; height: 1449px;"></div>
    </div>
    <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
        <div class="simplebar-scrollbar" style="width: 0px; display: none; transform: translate3d(0px, 0px, 0px);">
        </div>
    </div>
    <div class="simplebar-track simplebar-vertical" style="visibility: visible;">
        <div class="simplebar-scrollbar" style="height: 515px; transform: translate3d(0px, 0px, 0px); display: block;">
        </div>
    </div>
</div>