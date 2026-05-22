<x-app-layout :route="'Consulta Saidas'">
<div class="main-content app-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card card-raised">
                    <div class="card-header justify-content-between d-flex align-items-center">
                        <div class="card-title">
                            Relatório de Transações de Cash Out
                        </div>
                        <!-- Botão que abre o modal para escolher as datas -->
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#dateFilterModal">
                            Filtrar por Data
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <strong>Total Cash Out Bruto:</strong> R$ {{ number_format($total_cash_out_bruto_filtrada, 2, ',', '.') }}<br>
                            <strong>Total Cash Out Líquido:</strong> R$ {{ number_format($total_cash_out_liquido_filtrado, 2, ',', '.') }}<br>
                            <strong>Total Bruto de Lucro para a Plataforma:</strong> R$ {{ number_format($lucro_plataforma_filtrada, 2, ',', '.') }}
                        </div>

                        <div class="table-responsive">
                            <table class="table text-nowrap table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">User ID</th>
                                        <th scope="col">Referência Externa</th>
                                        <th scope="col">Valor</th>
                                        <th scope="col">Valor Líquido</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Nome do Beneficiário</th>
                                        <th scope="col">Documento do Beneficiário</th>
                                        <th scope="col">Tipo</th>
                                        <th scope="col">Chave PIX</th>
                                        <th scope="col">Data</th>
                                        <th scope="col">Taxa Cash Out</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($transactions as $transaction)
                                        <tr>
                                            <td>{{ $transaction->user_id }}</td>
                                            <td>{{ $transaction->externalreference }}</td>
                                            <td>{{ $transaction->amount }}</td>
                                            <td>{{ $transaction->cash_out_liquido }}</td>
                                            <td>
                                                @switch($transaction->status)
                                                    @case('COMPLETED')
                                                        <span class="badge bg-success">Aprovado</span>
                                                        @break
                                                    @case('PENDING')
                                                        <span class="badge bg-warning">Pendente</span>
                                                        @break
                                                    @case('CANCELLED')
                                                        <span class="badge bg-danger-transparent">Cancelado</span>
                                                        @break
                                                    @default
                                                        <span class="badge">Desconhecido</span>
                                                @endswitch
                                            </td>
                                            <td>{{ $transaction->beneficiaryname }}</td>
                                            <td>{{ $transaction->beneficiarydocument }}</td>
                                            <td>{{ $transaction->type }}</td>
                                            <td>{{ $transaction->pixkey }}</td>
                                            <td>{{ $transaction->date }}</td>
                                            <td>{{ $transaction->taxa_cash_out }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="13">Nenhum registro encontrado</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginação -->
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                <li class="page-item {{ ($page <= 1) ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ route('profile.relatorio.consulta', ['page' => $page - 1, 'data_inicio' => $dataInicio, 'data_fim' => $dataFim]) }}" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                @for ($i = 1; $i <= $totalPages; $i++)
                                    <li class="page-item {{ ($i == $page) ? 'active' : '' }}">
                                        <a class="page-link" href="{{ route('profile.relatorio.consulta', ['page' => $i, 'data_inicio' => $dataInicio, 'data_fim' => $dataFim]) }}">{{ $i }}</a>
                                    </li>
                                @endfor
                                <li class="page-item {{ ($page >= $totalPages) ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ route('profile.relatorio.consulta', ['page' => $page + 1, 'data_inicio' => $dataInicio, 'data_fim' => $dataFim]) }}" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="dateFilterModal" tabindex="-1" role="dialog" aria-labelledby="dateFilterModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content shadow-lg">
                    <form method="GET" action="{{ route('profile.relatorio.consulta') }}">
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
</x-app-layout>
