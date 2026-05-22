<x-app-layout :route="'[ADMIN] Saídas'">
    <div class="main-content app-content">
        <div class="container-fluid">

            <!-- Start::page-header -->
            <div class="mb-3 row justify-content-between align-items-center">
                <div style="display:flex; align-items:center; justify-content:flex-start;" 
                     class="mb-5 col-12 col-md-4 mb-md-0">
                    <h1 class="mb-0 display-5">Saídas</h1>
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
            <!-- End:: row-2 -->

            <!-- Start:: table section -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card card-raised">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="table-admin-financeiro-saidas" class="table text-nowrap">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>User ID</th>
                                            <th>Transação ID</th>
                                            <th>Valor</th>
                                            <th>Taxas</th>
                                            <th>Líquido</th>
                                            <th>Status</th>
                                            <th>Nome</th>
                                            <th>Chave</th>
                                            <th>Tipo</th>
                                            <th>Data</th>
                                            <th>Lucro</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($cashOuts as $cashOut)
                                        <tr>
                                            <td>{{ $cashOut->id }}</td>
                                            <td>{{ $cashOut->user_id }}</td>
                                            <td>{{ $cashOut->externalreference }}</td>
                                            <td>R$ {{ number_format($cashOut->amount, 2, ',', '.') }}</td>
                                            <td>R$ {{ number_format($cashOut->taxa_cash_out, 2, ',', '.') }}</td>
                                            <td>R$ {{ number_format($cashOut->cash_out_liquido, 2, ',', '.') }}</td>

                                            <td>
                                                @switch($cashOut->status)
                                                    @case('COMPLETED')
                                                        <span class="badge bg-success">Aprovado</span>
                                                        @break

                                                    @case('PENDING')
                                                        <span class="badge bg-warning">Pendente</span>
                                                        @break

                                                    @case('CANCELLED')
                                                        <span class="badge bg-danger">Cancelado</span>
                                                        @break

                                                    @default
                                                        <span class="badge">Desconhecido</span>
                                                @endswitch
                                            </td>

                                            <td>{{ $cashOut->beneficiaryname }}</td>
                                            <td>{{ $cashOut->beneficiarydocument }}</td>
                                            <td>{{ $cashOut->pixkey }}</td>
                                            <td>{{ \Carbon\Carbon::parse($cashOut->date)->format('d/m/Y \à\s H:i:s') }}</td>

                                            <td>
                                                R$ {{ number_format((float)$cashOut->cash_out_liquido - (float)$cashOut->amount, 2, ',', '.') }}
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
            <!-- End:: table section -->

            <!-- Modal Filtro -->
            <div class="modal fade" id="dateFilterModal" tabindex="-1" role="dialog" aria-labelledby="dateFilterModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content shadow-lg">
                        <form method="GET" action="{{ route('admin.financeiro.saidas') }}">
                            <div class="modal-header">
                                <h5 class="modal-title" id="dateFilterModalLabel">Filtrar por Data</h5>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span>&times;</span>
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
            <!-- End:: modal -->

        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            $("#table-admin-financeiro-saidas").DataTable({
                responsive: true,
                info: false,
                ordering: false,
                lengthChange: false,
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json"
                },
                dom: '<"top"f>rt<"bottom"p><"clear">',
                initComplete: function() {
                    $('#table-admin-financeiro-saidas_filter input[type="search"]')
                        .attr('placeholder', 'Pesquisar');
                },
                order: [[0, 'desc']],
                columnDefs: [
                    { targets: 0, type: 'num' },
                ],
            });
        });
    </script>
</x-app-layout>
