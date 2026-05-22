<x-app-layout :route="'[ADMIN] Dashboard'">
    <div class="main-content app-content">
        <div class="container-fluid">

            <!-- Start::page-header -->
            <div class="mb-3 row justify-content-between align-items-">
                <div style="display:flex;align-item:center;justify-content:flex-start;" class="mb-5 col-12 col-md-4 mb-md-0 justify-content-start align-items-center">
                    <h1 class="mb-0 display-6">Dashboard admin</h1>
                </div>
            </div>

            <!-- Start:: row-1 -->
            <div class="row">
                <div class="mb-2 col-xxl-3 col-md-6">
                    <x-card-dash-admin 
                    :title="'Saldo em carteira'"
                    :subtitle="'R$ '.number_format($total_saldo_users ?? 0, 2, ',', '.')"
                    :icon="'wallet'"
                    />
                </div>
                 <div class="mb-2 col-xxl-3 col-md-6">
                    <x-card-dash-admin 
                    :title="'Lucro liquido (Hoje)'"
                    :subtitle="'R$ '.number_format($lucro_liquido_hoje ?? 0, 2, ',', '.')"
                    :icon="'dollar-sign'"
                    />
                </div>
                <div class="mb-2 col-xxl-3 col-md-6">
                    <x-card-dash-admin 
                    :title="'Lucro liquido (Mês)'"
                    :subtitle="'R$ '.number_format($lucro_liquido_mes ?? 0, 2, ',', '.')"
                    :icon="'dollar-sign'"
                    />
                </div>
                <div class="mb-2 col-xxl-3 col-md-6">
                    <x-card-dash-admin 
                    :title="'Lucro liquido (Total)'"
                    :subtitle="'R$ '.number_format($lucro_liquido_total ?? 0, 2, ',', '.')"
                    :icon="'dollar-sign'"
                    />
                </div>
            </div>
            <!-- End:: row-1 -->

            <!-- Start:: row-2 -->
            <div class="row">
                <div class="mb-2 col-xxl-3 col-md-6">
                    <x-card-dash-admin 
                    :title="'Transações aprovadas'"
                    :subtitle="$transacoes_aprovadas"
                    :icon="'arrow-down-up'"
                    />
                </div>
                <div class="mb-2 col-xxl-3 col-md-6">
                    <x-card-dash-admin 
                    :title="'Transações aprovadas (Hoje)'"
                    :subtitle="'R$ '.number_format($valor_aprovado_hoje ?? 0, 2, ',', '.')"
                    :icon="'list-checks'"
                    />
                </div>
                <div class="mb-2 col-xxl-3 col-md-6">
                    <x-card-dash-admin 
                    :title="'Transações aprovadas (Mês)'"
                    :subtitle="'R$ '.number_format($valor_aprovado_mes ?? 0, 2, ',', '.')"
                    :icon="'list-checks'"
                    />
                </div>
                <div class="mb-2 col-xxl-3 col-md-6">
                    <x-card-dash-admin 
                    :title="'Transações aprovadas (Total)'"
                    :subtitle="'R$ '.number_format($valor_aprovado_total ?? 0, 2, ',', '.')"
                    :icon="'list-checks'"
                    />
                </div>
                
            </div>
            <!-- End:: row-1 -->

            <!-- Start:: row-1 -->
            <div class="row">
                <div class="mb-2 col-xxl-3 col-md-6">
                    <x-card-dash-admin 
                    :title="'Clientes cadastrados (Hoje)'"
                    :subtitle="$cadastros_hoje"
                    :icon="'users-round'"
                    />
                </div>
                <div class="mb-2 col-xxl-3 col-md-6">
                    <x-card-dash-admin 
                    :title="'Clientes cadastrados (Total)'"
                    :subtitle="$cadastros_total"
                    :icon="'users-round'"
                    />
                </div>
                <div class="mb-2 col-xxl-3 col-md-6">
                    <x-card-dash-admin 
                    :title="'Clientes bloqueados'"
                    :subtitle="$cadastros_bloqueados"
                    :icon="'user-round-x'"
                    />
                </div>
                <div class="mb-2 col-xxl-3 col-md-6">
                    <x-card-dash-admin 
                    :title="'Clientes em análise'"
                    :subtitle="$cadastros_analise"
                    :icon="'user-round-cog'"
                    />
                </div>

            </div>

            <!-- End:: row-1 -->



            <!-- Start:: row-1 -->
            <div class="row">
                <div class="mb-2 col-xxl-3 col-md-6">
                    <x-card-dash-admin 
                    :title="'Saques (Total)'"
                    :subtitle="'R$ '.number_format($retiradas_hoje ?? 0, 2, ',', '.')"
                    :icon="'upload'"
                    />
                </div>
                <div class="mb-2 col-xxl-3 col-md-6">
                    <x-card-dash-admin 
                    :title="'Saques (Mês)'"
                    :subtitle="'R$ '.number_format($retiradas_mes ?? 0, 2, ',', '.')"
                    :icon="'upload'"
                    />
                </div>
                <div class="mb-2 col-xxl-3 col-md-6">
                    <x-card-dash-admin 
                    :title="'Saques (Total)'"
                    :subtitle="'R$ '.number_format($retiradas_total ?? 0, 2, ',', '.')"
                    :icon="'upload'"
                    />
                </div>
                <div class="mb-2 col-xxl-3 col-md-6">
                    <x-card-dash-admin 
                    :title="'Saques pendentes'"
                    :subtitle="'R$ '.number_format($retiradas_pendentes ?? 0, 2, ',', '.')"
                    :icon="'clock-arrow-up'"
                    />
                </div>
            </div>
            <!-- End:: row-1 -->

        </div>
    </div>
</x-app-layout>
