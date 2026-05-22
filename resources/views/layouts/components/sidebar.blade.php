 <div id="layoutDrawer_nav">

    <!-- Drawer navigation-->
    <nav class="bg-white drawer accordion drawer-light" id="drawerAccordion">
        <div class="drawer-menu">
            <div class="nav">
                <!-- Drawer section heading (Account)-->
                <div class="drawer-menu-heading d-sm-none">Account</div>
                <!-- Drawer link (Notifications)-->
                <a class="nav-link d-sm-none" href="#!">
                    <div class="nav-link-icon"><i class="material-icons">notifications</i></div>
                    Notifications
                </a>
                <!-- Drawer link (Messages)-->
                <a class="nav-link d-sm-none" href="#!">
                    <div class="nav-link-icon"><i class="material-icons">mail</i></div>
                    Messages
                </a>
               
                 @if($permission != 9)
                <!-- Divider-->
                <div class="drawer-menu-divider d-sm-none"></div>
                <!-- Drawer section heading (Interface)-->
                <div class="pb-1 drawer-menu-heading">MAIN</div>
                <!-- Drawer link (Overview)-->
               
                <a class="nav-link" href="/dashboard">
                    <div class="nav-link-icon"><i class="material-icons">dashboard</i></div>
                    Dashboard
                </a>
                @endif
                @if($status == 1 && $permission != 9)
                    <!-- Drawer link (Dashboards)-->
                    <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseDashboards" aria-expanded="false" aria-controls="collapseDashboards">
                        <div class="nav-link-icon"><i class="material-icons">receipt</i></div>
                        Relatórios
                        <div class="drawer-collapse-arrow"><i class="material-icons">expand_more</i></div>
                    </a>
                    <!-- Nested drawer nav (Dashboards)-->
                    <div class="collapse" id="collapseDashboards" aria-labelledby="headingOne" data-bs-parent="#drawerAccordion">
                        <nav class="drawer-menu-nested nav">
                            <a class="nav-link" href="/relatorio/entradas">Entradas</a>
                            <a class="nav-link" href="/relatorio/saidas">Saidas</a>
                        </nav>
                    </div>
                    <a class="nav-link" href="/financeiro">
                        <div class="nav-link-icon"><i class="material-icons">stacked_line_chart</i></div>
                        Financeiro
                    </a>
                    <a class="nav-link" href="{{route('profile.checkout')}}">
                        <div class="nav-link-icon"><i class="material-icons">shopping_bag</i></div>
                        Produtos
                    </a>
                    <a class="nav-link" href="{{route('profile.orders')}}">
                        <div class="nav-link-icon"><i class="material-icons">shopping_basket</i></div>
                        Pedidos
                    </a>
                    <div class="drawer-menu-divider d-sm-none"></div>
                    <a class="nav-link" href="/documentacao">
                        <div class="nav-link-icon"><i class="material-icons">menu_book</i></div>
                        Documentação
                    </a>
                    <a class="nav-link" href="/chaves">
                        <div class="nav-link-icon"><i class="material-icons">key</i></div>
                        Chave API
                    </a>
                    
                @endif

                @if($permission >= 3 && $permission < 9)
                <!-- Divider-->
                <div class="pb-0 my-0 drawer-menu-divider"></div>
                <!-- Drawer section heading (UI Toolkit)-->
                <div class="pt-2 pb-1 drawer-menu-heading">ADMINISTRAÇÃO</div>
                <a class="nav-link" href="/{{ env('ADM_ROUTE') }}/dashboard">
                    <div class="nav-link-icon"><i class="material-icons">dashboard</i></div>
                    Dashboard
                </a>
                <a class="nav-link" href="/{{ env('ADM_ROUTE') }}/usuarios">
                    <div class="nav-link-icon"><i class="material-icons">groups</i></div>
                    Usuários&nbsp;<span class="badge text-bg-warning">{{ \App\Helpers\Helper::getUsersPending() }}</span>
                </a>
                <a class="nav-link" href="/{{ env('ADM_ROUTE') }}/aprovar-saques">
                    <div class="nav-link-icon"><i class="material-icons">checklist</i></div>
                    Aprovar saques
                </a>

                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#financeiro" aria-expanded="false" aria-controls="financeiro">
                    <div class="nav-link-icon"><i class="material-icons">calculate</i></div>
                    Financeiro
                    <div class="drawer-collapse-arrow"><i class="material-icons">expand_more</i></div>
                </a>
                <!-- Nested drawer nav (Dashboards)-->
                <div class="collapse" id="financeiro" aria-labelledby="headingOne" data-bs-parent="#financeiro">
                    <nav class="drawer-menu-nested nav">
                        <a class="nav-link" href="/{{ env('ADM_ROUTE') }}/financeiro/transacoes">Transações</a>
                        <a class="nav-link" href="/{{ env('ADM_ROUTE') }}/financeiro/carteiras">Carteiras</a>
                        <a class="nav-link" href="/{{ env('ADM_ROUTE') }}/financeiro/entradas">Entradas</a>
                        <a class="nav-link" href="/{{ env('ADM_ROUTE') }}/financeiro/saidas">Saidas</a>
                    </nav>
                </div>

                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#transacoes" aria-expanded="false" aria-controls="transacoes">
                    <div class="nav-link-icon"><i class="material-icons">currency_exchange</i></div>
                    Criar Transações
                    <div class="drawer-collapse-arrow"><i class="material-icons">expand_more</i></div>
                </a>
                <!-- Nested drawer nav (Dashboards)-->
                <div class="collapse" id="transacoes" aria-labelledby="headingOne" data-bs-parent="#transacoes">
                    <nav class="drawer-menu-nested nav">
                        <a class="nav-link" href="/{{ env('ADM_ROUTE') }}/transacoes/entrada">Entrada</a>
                        <a class="nav-link" href="/{{ env('ADM_ROUTE') }}/transacoes/saida">Saída</a>
                    </nav>
                </div>

                <a class="nav-link collapsed" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#settings" aria-expanded="false" aria-controls="settings">
                    <div class="nav-link-icon"><i class="material-icons">settings</i></div>
                    Configurações
                    <div class="drawer-collapse-arrow"><i class="material-icons">expand_more</i></div>
                </a>
                <!-- Nested drawer nav (Dashboards)-->
                <div class="collapse" id="settings" aria-labelledby="headingOne" data-bs-parent="#settings">
                    <nav class="drawer-menu-nested nav">
                        <a class="nav-link" href="/{{ env('ADM_ROUTE') }}/ajustes/gerais">Gerais</a>
                        <a class="nav-link" href="/{{ env('ADM_ROUTE') }}/ajustes/adquirentes">Adquirentes</a>
                        <a class="nav-link" href="/{{ env('ADM_ROUTE') }}/ajustes/gerentes">Gerente de contas</a>
                        <a class="nav-link" href="/{{ env('ADM_ROUTE') }}/ajustes/landing-page">Landing Page</a>
                    </nav>
                </div>
                
               
                @endif
             @if($permission == 9)
              <div class="pb-0 my-0 drawer-menu-divider"></div>
                <!-- Drawer section heading (UI Toolkit)-->
                <div class="pt-2 pb-1 drawer-menu-heading">GERENCIAR</div>
                    <a class="nav-link" href="/gerencia/clientes">
                        <div class="nav-link-icon"><i class="material-icons">groups</i></div>
                        Meus clientes
                    </a>
              @endif  
            </div>
        </div>
        
        <!-- Drawer footer        -->
        <div class="drawer-footer border-top w-100">
            
             <div class="drawer-footer w-100">
            <div class="d-flex align-items-center w-100 justify-content-start">
                <img src="{{auth()->user()->avatar}}" style="width:32px;height:32px;border-radius:100px">
                <div class="ms-1">
                    <div class="caption">Autenticado como:</div>
                    <div class="small fw-500">{{ isset(explode(' ',auth()->user()->name)[0]) ? explode(' ',auth()->user()->name)[0] : auth()->user()->name }}</div>
                </div>
            </div>
        </div>
    </nav>
</div>
