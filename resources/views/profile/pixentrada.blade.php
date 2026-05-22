<x-app-layout :route="'Relatório de entradas'">
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

                <div class="mb-4 col-xxl-3 col-md-6">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body" style="min-height: 114px">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5">{{ $totalaprovadasHoje }}</div>
                                    <div class="card-text">Transações aprovadas</div>
                                </div>
                                <div class="text-white icon-circle bg-info card-color"><i class="text-xl fa-brands fa-pix"></i></div>
                            </div>
                            <div class="card-text">
                                <div class="d-inline-flex align-items-center ">
                                    <i class="text-md fa-solid fa-calendar-days icon-xs text-success"></i>&nbsp;
                                    <div class="text-md caption text-sucess fw-500 me-2">Hoje</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4 col-xxl-3 col-md-6">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body" style="min-height: 114px">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5">{{ $totalaprovadasMes }}</div>
                                    <div class="card-text">Transações aprovadas</div>
                                </div>
                                <div class="text-white icon-circle bg-info card-color"><i class="text-xl fa-brands fa-pix"></i></div>
                            </div>
                            <div class="card-text">
                                <div class="d-inline-flex align-items-center ">
                                    <i class="text-md fa-solid fa-calendar-week icon-xs text-success"></i>&nbsp;
                                    <div class="text-md caption text-sucess fw-500 me-2">Mês</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4 col-xxl-3 col-md-6">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body" style="min-height: 114px">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5">{{ $totalaprovadas }}</div>
                                    <div class="card-text">Transações aprovadas</div>
                                </div>
                                <div class="text-white icon-circle bg-info card-color"><i class="text-xl fa-brands fa-pix"></i></div>
                            </div>
                            <div class="card-text">
                                <div class="d-inline-flex align-items-center ">
                                    <i class="text-md fa-brands fa-pix icon-xs text-success"></i>&nbsp;
                                    <div class="text-md caption text-sucess fw-500 me-2">Total</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4 col-xxl-3 col-md-6">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body" style="min-height: 114px">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5">{{ $totalsolicitacoes }}</div>
                                    <div class="card-text">Transações gerais</div>
                                </div>
                                <div class="text-white icon-circle bg-info card-color"><i class="text-xl fa-brands fa-pix"></i></div>
                            </div>
                            <div class="card-text">
                                <div class="d-inline-flex align-items-center ">
                                    <i class="text-md fa-brands fa-pix icon-xs text-success"></i>&nbsp;
                                    <div class="text-md caption text-sucess fw-500 me-2">Pendente + Aprovadas</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End:: row-1 -->

            <!-- Start:: row-2 -->
            <div class="mb-3 row">
                <div class="mb-4 col-xxl-4 col-md-6">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body" style="min-height: 114px">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5">{{ "R$ ".number_format($valorAprovadoHoje, 2, ',', '.') }}</div>
                                    <div class="card-text">Valor aprovado</div>
                                </div>
                                <div class="text-white icon-circle bg-info card-color"><i class="text-xl fa-brands fa-pix"></i></div>
                            </div>
                            <div class="card-text">
                                <div class="d-inline-flex align-items-center ">
                                    <i class="text-md fa-brands fa-pix icon-xs text-success"></i>&nbsp;
                                    <div class="text-md caption text-sucess fw-500 me-2">Hoje</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4 col-xxl-4 col-md-6">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body" style="min-height: 114px">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5">{{ "R$ ".number_format($valorAprovadoMes, 2, ',', '.') }}</div>
                                    <div class="card-text">Valor aprovado</div>
                                </div>
                                <div class="text-white icon-circle bg-info card-color"><i class="text-xl fa-brands fa-pix"></i></div>
                            </div>
                            <div class="card-text">
                                <div class="d-inline-flex align-items-center ">
                                    <i class="text-md fa-brands fa-pix icon-xs text-success"></i>&nbsp;
                                    <div class="text-md caption text-sucess fw-500 me-2">Mês</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4 col-xxl-4 col-md-6">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body" style="min-height: 114px">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5">{{ "R$ ".number_format($valorAprovadoTotal, 2, ',', '.') }}</div>
                                    <div class="card-text">Valor aprovado</div>
                                </div>
                                <div class="text-white icon-circle bg-info card-color"><i class="text-xl fa-brands fa-pix"></i></div>
                            </div>
                            <div class="card-text">
                                <div class="d-inline-flex align-items-center ">
                                    <i class="text-md fa-brands fa-pix icon-xs text-success"></i>&nbsp;
                                    <div class="text-md caption text-sucess fw-500 me-2">Total bruto</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End:: row-2 -->

            <div class="row">
                <div class="col-xl-12">
                    <div class="card card-raised">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="table-pix-entradas" class="table text-nowrap ">
                                    <thead>
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Transação ID</th>
                                            <th scope="col">Valor</th>
                                            <th scope="col">Valor Líquido</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Nome </th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Documento</th>
                                            <th scope="col">Data</th>
                                            <th scope="col">Taxa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($transactions as $transaction)
                                        <tr>
                                            <td>{{ $transaction->id }}</td>
                                            <td>{{ $transaction->idTransaction }}</td>
                                            <td>{{ "R$ ".number_format($transaction->amount, '2',',','.') }}</td>
                                            <td>{{ "R$ ".number_format($transaction->deposito_liquido, '2',',','.') }}</td>
                                            <td>
                                                @switch($transaction->status)
                                                @case('PAID_OUT')
                                                <span class="badge badge-sm bg-success">Aprovado</span>
                                                @break
                                                @case('WAITING_FOR_APPROVAL')
                                                <span class="badge badge-sm bg-info">Pendente</span>
                                                @break
                                                @case('MED')
                                                <span class="badge badge-sm bg-warning">MED</span>
                                                @break
                                                @case('CANCELLED')
                                                <span class="badge badge-sm bg-danger">Cancelado</span>
                                                @break
                                                @default
                                                <span class="badge">Desconhecido</span>
                                                @endswitch
                                            </td>
                                            <td>{{ $transaction->client_name }}</td>
                                            <td>{{ $transaction->client_email }}</td>
                                            <td>{{ $transaction->client_document }}</td>
                                            <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y \à\s H:i:s') }}</td>
                                            <td>
                                                R$ {{ number_format((float)$transaction->amount - (float)$transaction->deposito_liquido, '2', ',', '.') }}
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
                        <form method="GET" action="{{ route('profile.relatorio.pixentrada') }}">
                            <div class="modal-header">
                                <h5 class="modal-title" id="dateFilterModalLabel">Filtrar por Data</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="data_inicio">Data Início</label>
                                    <input type="date" class="form-control" name="data_inicio" id="data_inicio" value="{{ old('data_inicio', $dataInicio) }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="data_fim">Data Fim</label>
                                    <input type="date" class="form-control" name="data_fim" id="data_fim" value="{{ old('data_fim', $dataFim) }}" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Filtrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
        $("#table-pix-entradas").DataTable({
            responsive: true,
           
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
            },
            dom: '<"top"f>rt<"bottom"p><"clear">',
                order: [[0, 'desc']],
                columnDefs: [
                    { targets: 0, type: 'num' }, // se o ID está na 0
                ],
        });
    });
</script>
</x-app-layout>
