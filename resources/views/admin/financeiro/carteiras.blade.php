<x-app-layout :route="'[ADMIN] Carteiras'">
    <div class="main-content app-content">
        <div class="container-fluid">


             <!-- Start::page-header -->
             <div class="mb-3 row justify-content-between align-items-">
                <div style="display:flex;align-item:center;justify-content:flex-start;" class="mb-5 col-12 col-md-4 mb-md-0 justify-content-start align-items-center">
                    <h1 class="mb-0 display-5">Carteiras</h1>
                </div>
            </div>

            <!-- Start:: row-2 -->
            <div class="row">
                <div class="mb-4 col-6">
                     <x-card-dash-admin 
                       :title="'Total em carteiras'"
                       :subtitle="'R$ '.number_format($total_em_carteiras, 2, ',', '.')"
                       :icon="'wallet'"
                       />
                </div>
                <div class="mb-4 col-6">
                     <x-card-dash-admin 
                       :title="'Total no gateway'"
                       :subtitle="'R$ '.number_format($totalBrutoGateway, 2, ',', '.')"
                       :icon="'wallet'"
                       />
                </div>
            </div>
            <!-- End:: row-2 -->

            <!-- Start::row-2 -->
            <div class="mb-3 row">
                <div class="col-xl-12">
                    <div class="card card-raised">
                        <div class="bg-transparent card-header justify-content-between d-flex align-items-center">
                            <div class="card-title">
                                Usuários com Mais saldo em carteira
                            </div>
                        </div>
                        <div class="card-body">
                                        <div class="alert alert-info" style="border-radius: 10px">
                                            <div class="row">
                                                @foreach ($topUsuarios as $topUser)
                                                <div class="col-4">
                                                    <div style="border: 1px solid white;padding:10px;border-radius:10px;">
                                                        <p>{{ $topUser->user_id }}</p>
                                                        <p class="font-bold">R$ {{ number_format($topUser->saldo, 2, ',', '.') }}</p>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-3 row">
                <div class="col-xl-12">
                    <div class="card card-raised">
                        <div class="bg-transparent card-header justify-content-between d-flex align-items-center">
                            <div class="card-title">
                                Relatório de Usuários
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="table-carteiras" class="table text-nowrap">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col">User ID</th>
                                            <th scope="col">Carteira</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Telefone</th>
                                            <th scope="col">Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($usuarios as $usuario)
                                            <tr>
                                                <td>{{ $usuario->user_id }}</td>
                                                <td>R$ {{ number_format($usuario->saldo, 2, ',', '.') }}</td>
                                                <td>{{ $usuario->email }}</td>
                                                <td>{{ $usuario->telefone }}</td>
                                                <td>
                                                    <a href="https://wa.me/55{{ preg_replace('/[^0-9]/', '', $usuario->telefone) }}"
                                                    target="_blank"
                                                    class="btn btn-sm btn-info">WhatsApp</a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5">Nenhum registro encontrado</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
        $("#table-carteiras").DataTable({
            responsive: true,
            info:false,
            ordering: false,
            lengthChange: false,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
            },
            dom: '<"top"f>rt<"bottom"p><"clear">',
                initComplete: function() {
                    // Muda o placeholder do input de busca
                    $('#table-carteiras_filter input[type="search"]').attr('placeholder', 'Pesquisar');
                }
        });
    });
    </script>
</x-app-layout>
