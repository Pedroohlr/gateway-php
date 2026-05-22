@php
$setting = \App\Helpers\Helper::getSetting();
@endphp
<nav class="top-app-bar navbar navbar-expand">
    <div class="px-4 container-fluid">
        <div class="text-uppercase font-monospace logo h-100">
            <div class="margin-logo" style="display:flex;align-items:center;justify-content:center;"></div>
            <img src="{{ $setting->gateway_logo }}" height="auto" width="60">
           {{--  {{ $setting->gateway_name }} --}}
        </div>
        <!-- Drawer toggle button-->
        <button class="order-1 bg-white icon-navbar btn btn-lg btn-icon order-lg-0" id="drawerToggle" href="javascript:void(0);">
            <i class="material-icons">chevron_left</i>
        </button>
        <!-- Navbar brand-->
        <a class="icon-navbar navbar-brand me-auto" href="/dashboard">
            <div class="text-uppercase font-monospace">

            </div>
        </a>
        <!-- Navbar items-->
        <div class="mx-3 d-flex align-items-center me-lg-0">
            <!-- Navbar-->
           {{--  <ul class="navbar-nav d-none d-lg-flex">
                <li class="nav-item"><a class="nav-link" href="index.html">Overview</a></li>
                <li class="nav-item"><a class="nav-link" href="https://docs.startbootstrap.com/material-admin-pro" target="_blank">Documentation</a></li>
            </ul> --}}
            <!-- Navbar buttons-->
            <div class="d-flex">
                <!-- Messages dropdown-->
                {{-- <div class="dropdown dropdown-notifications d-none d-sm-block">
                    <button class="icon-navbar btn btn-lg btn-icon dropdown-toggle me-3" id="dropdownMenuMessages" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">mail_outline</i></button>
                    <ul class="py-0 mt-3 overflow-hidden dropdown-menu dropdown-menu-end me-3" aria-labelledby="dropdownMenuMessages">
                        <li><h6 class="py-3 icon-navbar dropdown-header bg-primary fw-500">Notificações</h6></li>
                        <li><hr class="my-0 dropdown-divider" /></li>
                        <li>
                            <a class="dropdown-item unread" href="#!">
                                <div class="dropdown-item-content">
                                    <div class="dropdown-item-content-text"><div class="text-truncate d-inline-block" style="max-width: 18rem">{{ $setting->gateway_name }} Informa:</div></div>
                                    <div class="dropdown-item-content-subtext">Seja bem vindo Sr(a) {{ isset(explode(' ',auth()->user()->name)[0]) ? explode(' ',auth()->user()->name)[0] : auth()->user()->name }} a {{ $setting->gateway_name }}.</div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div> --}}
                <!-- Notifications and alerts dropdown-->
                <div class="dropdown dropdown-notifications d-none d-sm-block">
                    <button class="icon-navbar btn btn-lg btn-icon dropdown-toggle me-3" id="dropdownMenuNotifications" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="material-icons">notifications</i></button>
                    <ul class="py-0 mt-3 overflow-hidden dropdown-menu dropdown-menu-end me-3" aria-labelledby="dropdownMenuNotifications">
                        <li><h6 class="py-3 icon-navbar dropdown-header fw-500" style="color:white!important;background: {{ $setting->gateway_color }}">Notificações</h6></li>
                        <li><hr class="my-0 dropdown-divider" /></li>
                        <li>
                            <a class="dropdown-item unread" href="#!">
                                <div class="dropdown-item-content">
                                    <div class="dropdown-item-content-text"><div class="text-truncate d-inline-block" style="max-width: 18rem">{{ $setting->gateway_name }} Informa:</div></div>
                                    <div class="dropdown-item-content-subtext">Seja bem vindo Sr(a) {{ isset(explode(' ',auth()->user()->name)[0]) ? explode(' ',auth()->user()->name)[0] : auth()->user()->name }} a {{ $setting->gateway_name }}.</div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- User profile dropdown-->
                <div class="dropdown">
                   <button class="icon-navbar btn btn-lg btn-icon dropdown-toggle" id="dropdownMenuProfile" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="border-radius: 100px"><img src="{{auth()->user()->avatar}}" style="width:32px;height:32px;border-radius:100px"></button></button>
                    <ul class="mt-3 dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuProfile">
                        <li>
                            <a class="dropdown-item" href="{{route('profile.index')}}">
                                <i class="material-icons leading-icon color-gateway">person</i>
                                <div class="me-3">Perfil</div>
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item" target="_blank" href="https://wa.me/55{{auth()->user()->gerente->telefone ?? $setting->contato}}?text=Ol%C3%A1%20meu%20nome%20%C3%A9%20{{auth()->user()->name}},%20preciso%20de%20ajuda.">
                                <i class="material-icons leading-icon color-gateway">help</i>
                                <div class="me-3">Suporte</div>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider" /></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                            <button type="submit" class="dropdown-item btn-link">
                                <i class="material-icons leading-icon color-gateway">logout</i>
                                <div class="me-3">Sair</div>
                            </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
