<x-app-layout :route="'[ADMIN] Transações'">
    <div class="main-content app-content">
        <div class="container-fluid">

            <!-- Start::page-header -->
            <div class="mb-3 row justify-content-between align-items-">
                <div style="display:flex;align-item:center;justify-content:flex-start;" class="mb-5 col-12 col-md-4 mb-md-0 justify-content-start align-items-center">
                    <h1 class="mb-0 display-5">Transações</h1>
                </div>
            </div>

            <!-- Start:: row-1 -->
            <div class="row">
                <div class="mb-4 col-xxl-3 col-md-6">
                    <x-card-dash-admin 
                       :title="'Transações aprovadas'"
                       :subtitle="$transacoes_aprovadas"
                       :icon="'arrow-down-up'"
                       />
                </div>
                <div class="mb-4 col-xxl-3 col-md-6">
                    <x-card-dash-admin 
                       :title="'Lucro liquido (Hoje)'"
                       :subtitle="'R$ '.number_format($lucro_liquido_hoje ?? 0, 2, ',', '.')"
                       :icon="'dollar-sign'"
                       />
                </div>
                <div class="mb-4 col-xxl-3 col-md-6">
                    <x-card-dash-admin 
                       :title="'Lucro liquido (Mês)'"
                       :subtitle="'R$ '.number_format($lucro_liquido_mes ?? 0, 2, ',', '.')"
                       :icon="'dollar-sign'"
                       />
                </div>
                <div class="mb-4 col-xxl-3 col-md-6">
                    <x-card-dash-admin 
                       :title="'Lucro liquido (Total)'"
                       :subtitle="'R$ '.number_format($lucro_liquido_total ?? 0, 2, ',', '.')"
                       :icon="'dollar-sign'"
                       />
                </div>
            </div>
            <!-- End:: row-1 -->





         {{--    <!-- Start:: row-2 -->
            <div class="row">
                <div class="mb-4 col-xxl-3 col-md-6">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-success">{{ "R$ ".number_format($lucro_liquido_hoje ?? 0, 2, ',', '.') }}</div>
                                    <div class="card-text">Lucro liquido (Hoje)</div>
                                </div>
                                <div class="text-white icon-circle bg-warning card-color"><i class="fa-brands fa-pix"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="card card-raised">
                        <div class="p-4 card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <div>
                                        <span class="mb-2 d-block">Transações aprovadas</span>
                                        <h5 class="mb-4 fs-4">{{ $transacoes_aprovadas ?? 0 }}</h5>
                                    </div>
                                    <span class="text-success me-2 fw-medium d-inline-block"></span><span class="text-muted">Total </span>
                                </div>
                                <div>
                                    <div class="main-card-icon success">
                                        <div class="border avatar avatar-lg bg-success border-success border-opacity-10">
                                            <div class="avatar avatar-sm svg-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256">
                                                    <rect width="256" height="256" fill="none"></rect>
                                                    <path d="M40,192a16,16,0,0,0,16,16H216a8,8,0,0,0,8-8V88a8,8,0,0,0-8-8H56A16,16,0,0,1,40,64Z" opacity="0.2"></path>
                                                    <path d="M40,64V192a16,16,0,0,0,16,16H216a8,8,0,0,0,8-8V88a8,8,0,0,0-8-8H56A16,16,0,0,1,40,64h0A16,16,0,0,1,56,48H192" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></path>
                                                    <circle cx="180" cy="140" r="12"></circle>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="card custom-card main-card">
                        <div class="p-4 card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <div>
                                        <span class="mb-2 d-block">Valor aprovado</span>
                                        <h5 class="mb-4 fs-4">{{ number_format($valor_aprovado_hoje ?? 0, 2, ',', '.') }}</h5>
                                    </div>
                                    <span class="text-success me-2 fw-medium d-inline-block"></span><span class="text-muted">Hoje</span>
                                </div>
                                <div>
                                    <div class="main-card-icon success">
                                        <div class="border avatar avatar-lg bg-success border-success border-opacity-10">
                                            <div class="avatar avatar-sm svg-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#000000" viewBox="0 0 256 256">
                                                    <path d="M200,168a48.05,48.05,0,0,1-48,48H136v16a8,8,0,0,1-16,0V216H104a48.05,48.05,0,0,1-48-48,8,8,0,0,1,16,0,32,32,0,0,0,32,32h48a32,32,0,0,0,0-64H112a48,48,0,0,1,0-96h8V24a8,8,0,0,1,16,0V40h8a48.05,48.05,0,0,1,48,48,8,8,0,0,1-16,0,32,32,0,0,0-32-32H112a32,32,0,0,0,0,64h40A48.05,48.05,0,0,1,200,168Z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="card custom-card main-card">
                        <div class="p-4 card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <div>
                                        <span class="mb-2 d-block">Valor aprovado</span>
                                        <h5 class="mb-4 fs-4">{{ number_format($valor_aprovado_mes ?? 0, 2, ',', '.') }}</h5>
                                    </div>
                                    <span class="text-success me-2 fw-medium d-inline-block"></span><span class="text-muted">Mês</span>
                                </div>
                                <div>
                                    <div class="main-card-icon primary">
                                        <div class="border avatar avatar-lg bg-primary border-primary border-opacity-10">
                                            <div class="avatar avatar-sm svg-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#000000" viewBox="0 0 256 256">
                                                    <path d="M216,72H56a8,8,0,0,1,0-16H192a8,8,0,0,0,0-16H56A24,24,0,0,0,32,64V192a24,24,0,0,0,24,24H216a16,16,0,0,0,16-16V88A16,16,0,0,0,216,72Zm0,128H56a8,8,0,0,1-8-8V86.63A23.84,23.84,0,0,0,56,88H216Zm-48-60a12,12,0,1,1,12,12A12,12,0,0,1,168,140Z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="card custom-card main-card">
                        <div class="p-4 card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <div>
                                        <span class="mb-2 d-block">Valor aprovado</span>
                                        <h5 class="mb-4 fs-4">{{ number_format($valor_aprovado_total ?? 0, 2, ',', '.') }}</h5>
                                    </div>
                                    <span class="text-danger me-2 fw-medium d-inline-block"></span><span class="text-muted">TOTAL</span>
                                </div>
                                <div>
                                    <div class="main-card-icon orange">
                                        <div class="avatar avatar-lg avatar-rounded bg-primary svg-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#000000" viewBox="0 0 256 256">
                                                <path d="M224,200h-8V40a8,8,0,0,0-8-8H152a8,8,0,0,0-8,8V80H96a8,8,0,0,0-8,8v40H48a8,8,0,0,0-8,8v64H32a8,8,0,0,0,0,16H224a8,8,0,0,0,0-16ZM160,48h40V200H160ZM104,96h40V200H104ZM56,144H88v56H56Z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End:: row-1 -->
 --}}
            <!-- Start::row-2 -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card card-raised">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="table-transacoes" class="table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th scope="col">Cliente ID</th>
                                            <th scope="col">Transação ID</th>
                                            <th scope="col">Valor Total</th>
                                            <th scope="col">Valor Liquido</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Data</th>
                                            <!--  <th scope="col">Ações</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($deposits)
                                        @foreach($deposits as $row)
                                        @php
                                        // Ajustar a exibição do status
                                        switch ($row->status) {
                                        case 'PAID_OUT':
                                        $statusBadge = 'bg-success';
                                        $statusText = 'Aprovado';
                                        break;
                                        case 'WAITING_FOR_APPROVAL':
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
                                            <td>{{ $row->amount }}</td>
                                            <td>{{ $row->deposito_liquido }}</td>
                                            <td><span class="badge {{ $statusBadge }}">{{ $statusText }}</span></td>
                                            <td>{{ \Carbon\Carbon::parse($row->date)->format('d/m/Y \à\s H:i:s') }}</td>
                                            <!--  <td>
                                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal"
                                                    data-client-id="{{ $row->user_id }}"
                                                    data-externalreference="{{ $row->idTransaction }}"
                                                    data-valor="{{ $row->amount }}"
                                                    data-status="{{ $row->status }}"
                                                    data-data="{{ $row->date }}">Editar</button>
                                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" data-client-id="{{ $row->user_id }}">Excluir</button>
                                            </td> -->
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
            <!-- End::row-2 -->

            <!-- Modal Editar -->
            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Editar Confirmação de Depósito</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editForm">
                                <input type="hidden" id="editEmail" name="email">
                                <div class="mb-3">
                                    <label for="editExternalReference" class="form-label">Referência Externa</label>
                                    <input type="text" class="form-control" id="editExternalReference" name="externalreference">
                                </div>
                                <div class="mb-3">
                                    <label for="editValor" class="form-label">Valor</label>
                                    <input type="text" class="form-control" id="editValor" name="valor">
                                </div>
                                <div class="mb-3">
                                    <label for="editStatus" class="form-label">Status</label>
                                    <select class="form-select" id="editStatus" name="status">
                                        <option value="WAITING_FOR_APPROVAL">Pendente</option>
                                        <option value="PAID_OUT">Aprovado</option>
                                        <option value="CANCELLED">Cancelado</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="editData" class="form-label">Data</label>
                                    <input type="text" class="form-control" id="editData" name="data">
                                </div>
                                <button type="submit" class="btn btn-primary">Salvar alterações</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Confirmar Exclusão -->
            <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalLabel">Confirmar Exclusão</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Você tem certeza que deseja excluir este depósito?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Excluir</button>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener("DOMContentLoaded", function() {
                $("#table-transacoes").DataTable({
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

            <!-- JavaScript para Preencher o Modal e Enviar Alterações -->
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var editModal = document.getElementById('editModal');
                    var deleteModal = document.getElementById('deleteModal');
                    var emailToDelete = null;

                    // Preencher o modal de edição
                    editModal.addEventListener('show.bs.modal', function(event) {
                        var button = event.relatedTarget;
                        document.getElementById('editEmail').value = button.getAttribute('data-client-id');
                        document.getElementById('editExternalReference').value = button.getAttribute('data-externalreference');
                        document.getElementById('editValor').value = button.getAttribute('data-valor');
                        document.getElementById('editStatus').value = button.getAttribute('data-status');
                        document.getElementById('editData').value = button.getAttribute('data-data');
                    });

                    // Enviar o formulário de edição
                    document.getElementById('editForm').addEventListener('submit', function(event) {
                        event.preventDefault();
                        var formData = new FormData(this);
                        fetch('update_confirmar_deposito.php', {
                                method: 'POST',
                                body: formData
                            }).then(response => response.text())
                            .then(result => {
                                console.log(result); // Para depuração
                                if (result === 'success') {
                                    window.location.reload();
                                } else {
                                    alert('Erro ao atualizar depósito.');
                                }
                            });
                    });

                    // Definir o email do depósito para exclusão
                    deleteModal.addEventListener('show.bs.modal', function(event) {
                        var button = event.relatedTarget;
                        emailToDelete = button.getAttribute('data-client-id');
                    });

                    // Confirmar a exclusão
                    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
                        fetch('delete_confirmar_deposito.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: new URLSearchParams({
                                    'email': emailToDelete
                                })
                            }).then(response => response.text())
                            .then(result => {
                                console.log(result); // Para depuração
                                if (result === 'success') {
                                    window.location.reload();
                                } else {
                                    alert('Erro ao excluir depósito.');
                                }
                            });
                    });
                });
            </script>


        </div>
    </div>
</x-app-layout>
