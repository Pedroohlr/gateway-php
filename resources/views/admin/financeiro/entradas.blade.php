<x-app-layout :route="'[ADMIN] Saídas'">
    <div class="main-content app-content">
        <div class="container-fluid">

            <!-- Start::page-header -->
            <div class="mb-3 row justify-content-between align-items-">
                <div style="display:flex;align-item:center;justify-content:flex-start;" class="mb-5 col-12 col-md-4 mb-md-0 justify-content-start align-items-center">
                    <h1 class="mb-0 display-5">Entradas</h1>
                </div>
            </div>
            <!-- Start:: row-1 -->
            <div class="row">
                <div class="mb-2 col-xxl-3 col-md-6">
                    <x-card-dash-admin 
                       :title="'Aprovados (Total)'"
                       :subtitle="$totalaprovadas"
                       :icon="'square-check-big'"
                       />
                </div>
                <div class="mb-2 col-xxl-3 col-md-6">
                    <x-card-dash-admin 
                       :title="'Aprovados (Hoje)'"
                       :subtitle="$totalaprovadasHoje"
                       :icon="'square-check-big'"
                       />
                </div>
                <div class="mb-2 col-xxl-3 col-md-6">
                    <x-card-dash-admin 
                       :title="'Aprovados (Mês)'"
                       :subtitle="$totalaprovadasMes"
                       :icon="'square-check-big'"
                       />
                </div>
                <div class="mb-2 col-xxl-3 col-md-6">
                    <x-card-dash-admin 
                       :title="'Transações geral'"
                       :subtitle="$totalsolicitacoes"
                       :icon="'square-check-big'"
                       />
                </div>

            </div>
            <!-- End:: row-1 -->

            <!-- Start:: row-2 -->
            <div class="row">
                <div class="mb-2 col-xxl-4 col-md-6">
                     <x-card-dash-admin 
                       :title="'Aprovados (Bruto)'"
                       :subtitle="'R$ '.number_format($valorAprovadoTotal, 2, ',', '.')"
                       :icon="'square-check-big'"
                       />
                </div>
                <div class="mb-2 col-xxl-4 col-md-6">
                     <x-card-dash-admin 
                       :title="'Aprovados (Hoje)'"
                       :subtitle="'R$ '.number_format($valorAprovadoHoje, 2, ',', '.')"
                       :icon="'square-check-big'"
                       />
                </div>
                <div class="mb-2 col-xxl-4 col-md-6">
                     <x-card-dash-admin 
                       :title="'Aprovados (Mês)'"
                       :subtitle="'R$ '.number_format($valorAprovadoMes, 2, ',', '.')"
                       :icon="'square-check-big'"
                       />
                </div>
            </div>

            <!-- Start::row-2 -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card card-raised">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="table-financeiro-entradas" class="table">
                                    <thead >
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">User ID</th>
                                            <th scope="col">Transação ID</th>
                                            <th scope="col">Valor</th>
                                            <th scope="col">Taxas</th>
                                            <th scope="col">Líquido</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Data</th>
                                            <th scope="col">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cashOuts as $cashOut)
                                        <tr>
                                            <td>{{ $cashOut->id }}</td>
                                            <td>{{ $cashOut->user_id }}</td>
                                            <td>{{ $cashOut->idTransaction }}</td>
                                            <td>{{ "R$ ".number_format($cashOut->amount, 2, ',', '.') }}</td>
                                            <td>{{ "R$ ".number_format($cashOut->taxa_cash_in, 2, ',', '.') }}</td>
                                            <td>{{ "R$ ".number_format($cashOut->deposito_liquido, 2, ',', '.') }}</td>
                                            <td>
                                                @switch($cashOut->status)
                                                @case('PAID_OUT')
                                                <span class="badge bg-success">Aprovado</span>
                                                @break
                                                @case('WAITING_FOR_APROVAL')
                                                <span class="badge bg-warning">Pendente</span>
                                                @break
                                                @case('CANCELLED')
                                                <span class="badge bg-danger">Cancelado</span>
                                                @break
                                                @default
                                                <span class="badge">Desconhecido</span>
                                                @endswitch
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($cashOut->date)->format('d/m/Y \à\s H:i:s') }}</td>
                                            <td>
                                                @if($cashOut->status === 'PAID_OUT')
                                               <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#modalAddMed">Marcar como MED</button>
                                                @elseif($cashOut->status === 'MED')
                                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                data-bs-target="#modalRemoveMed">Remover MED</button>
                                                @else
                                                {{ '---' }}
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="dateFilterModal" tabindex="-1" role="dialog" aria-labelledby="dateFilterModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="shadow-lg modal-content">
                        <form method="GET" action="{{ route('admin.financeiro.entradas') }}">
                            <div class="modal-header">
                                <h5 class="modal-title" id="dateFilterModalLabel">Filtrar por Data</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="data_inicio">Data Início</label>
                                    <input type="date" class="form-control" name="data_inicio" id="data_inicio" value="{{ $dataInicio }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="data_fim">Data Fim</label>
                                    <input type="date" class="form-control" name="data_fim" id="data_fim" value="{{ $dataFim }}" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Filtrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


        </div>
    </div>

    @foreach ($cashOuts as $cashOut)

        <!-- Modal Apelo-->
        <div class="modal fade" id="modalAddMed" tabindex="-1" aria-labelledby="modalAddMedLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalApeloLabel">Marcar como med</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form method="POST" action="{{ route('admin.financeiro.marcar-med') }}">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" name="solicitacao_id" value="{{ $cashOut->id }}" />
                            <p>Deseja marcar como MED a transação: <span style="font-weight: bold;">{{ $cashOut->idTransaction }}</span>?</p>
                            <p>Criada apartir do Seller: <span style="font-weight: bold;">{{ $cashOut->user->name }}</span></p>
                            <p>Cliente: <span style="font-weight: bold;">{{ $cashOut->client_name }}</span></p>
                            <p>No valor de: <span style="font-weight: bold;">{{ 'R$ '.number_format($cashOut->amount, 2, ',', '.') }}</span></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Marcar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Apelo-->
        <div class="modal fade" id="modalRemoveMed" tabindex="-1" aria-labelledby="modalRemoveMedLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalRemoveLabel">Remover MED</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form method="POST" action="{{ route('admin.financeiro.remover-med') }}">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" name="solicitacao_id" value="{{ $cashOut->id }}" />
                            <p>Deseja remover MED da transação: <span style="font-weight: bold;">{{ $cashOut->idTransaction }}</span>?</p>
                            <p>Criada apartir do Seller: <span style="font-weight: bold;">{{ $cashOut->user->name }}</span></p>
                            <p>Cliente: <span style="font-weight: bold;">{{ $cashOut->client_name }}</span></p>
                            <p>No valor de: <span style="font-weight: bold;">{{ 'R$ '.number_format($cashOut->amount, 2, ',', '.') }}</span></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Remover</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    @endforeach


    <script>
        document.addEventListener("DOMContentLoaded", function() {
        $("#table-financeiro-entradas").DataTable({
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
                    $('#table-financeiro-entradas_filter input[type="search"]').attr('placeholder', 'Pesquisar');
                },
                order: [[0, 'desc']],
                columnDefs: [
                    { targets: 0, type: 'num' }, // se o ID está na 0
                ],
        });
    });
    </script>

</x-app-layout>
