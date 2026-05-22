<x-app-layout :route="'[ADMIN] Transações'">
    <div class="main-content app-content">
        <div class="container-fluid">

            <!-- Start::page-header -->
            <div class="mb-3 row justify-content-between align-items-">
                <div style="display:flex;align-item:center;justify-content:flex-start;" class="mb-5 col-12 col-md-4 mb-md-0 justify-content-start align-items-center">
                    <h1 class="mb-0 display-5">Saques pendentes</h1>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card card-raised">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="table-aprovar-saques" class="table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th scope="col">Cliente ID</th>
                                            <th scope="col">Transação ID</th>
                                            <th scope="col">Valor Total</th>
                                            <th scope="col">Taxas</th>
                                            <th scope="col">Valor Liquido</th>
                                            <th scope="col">Chave</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Data</th>
                                            <th scope="col">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($saques)
                                        @foreach($saques as $row)
                                        @php
                                        // Ajustar a exibição do status
                                        switch ($row->status) {
                                        case 'CONFIRMED':
                                        $statusBadge = 'bg-success';
                                        $statusText = 'Aprovado';
                                        break;
                                        case 'PENDING':
                                        $statusBadge = 'bg-warning';
                                        $statusText = 'Pendente';
                                        break;
                                        default:
                                        $statusBadge = 'bg-danger';
                                        $statusText = 'Cancelado';
                                        }
                                        @endphp
                                        <tr>
                                            <td>{{ $row->user_id }}</td>
                                            <td>{{ $row->idTransaction }}</td>
                                            <td>R$ {{ number_format($row->amount, '2', ',', '.') }}</td>
                                            <td>R$ {{ number_format($row->taxa_cash_out, '2', ',', '.') }}</td>
                                            <td>R$ {{ number_format($row->cash_out_liquido, '2', ',', '.') }}</td>
                                            <td><span class="badge bg-info">{{ $row->pix }}</span></td>
                                            <td><span class="badge {{ $statusBadge }}">{{ $statusText }}</span></td>
                                            <td>{{ \Carbon\Carbon::parse($row->date)->format('d/m/Y \à\s H:i:s') }}</td>
                                            <td>
                                                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#aprovarModal-{{ $row->id }}">Aprovar</button>
                                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejeitarModal-{{ $row->id }}">Rejeitar</button>
                                               
                                            </td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td colspan="6">Nenhum registro encontrado</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
     @if($saques)
        @foreach($saques as $row)
         <!-- Modal Confirmar Exclusão -->
                                                <form method="POST" action="{{ route('admin.saques.aprovar', ['id' => $row->id ]) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal fade" id="aprovarModal-{{ $row->id }}" tabindex="-1" aria-labelledby="-{{ $row->id }}" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="aprovarModalLabel-{{ $row->id }}">Aprovar Saque</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>Você tem certeza que deseja aprovar o saque?</p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Voltar</button>
                                                                    <button type="submit" class="btn btn-success" onclick="this.disabled = true; this.form.submit();">Aprovar</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>

                                                <!-- Modal Confirmar Exclusão -->
                                                 <form method="POST" action="{{ route('admin.saques.rejeitar', ['id' => $row->id ]) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal fade" id="rejeitarModal-{{ $row->id }}" tabindex="-1" aria-labelledby="-{{ $row->id }}" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="rejeitarModalLabel-{{ $row->id }}">Rejeitar Saque</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>Você tem certeza que deseja rejeitar o saque?</p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Voltar</button>
                                                                    <button type="submit" class="btn btn-danger" onclick="this.disabled = true; this.form.submit();">Rejeitar</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
        @endforeach
    @endif

    <script>
        document.addEventListener("DOMContentLoaded", function() {
        $("#table-aprovar-saques").DataTable({
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
                    $('#table-aprovar-saques_filter input[type="search"]').attr('placeholder', 'Pesquisar');
                }
        });
    });
    </script>
</x-app-layout>
