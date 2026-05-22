<x-app-layout :route="'[ADMIN] Criar transação de saída'">
    <div class="main-content app-content">
        <div class="container-fluid">

            <!-- Start::row-1 -->
            <div class="row">
                <div class="mb-3 row justify-content-between align-items-">
                    <div style="display:flex;align-item:center;justify-content:flex-start;" class="mb-5 col-12 col-md-4 mb-md-0 justify-content-start align-items-center">
                        <h1 class="mb-0 display-5">Criar transações de saída</h1>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card card-raised">
                        <div class="p-0 card-body">
                            <div class="p-3 d-grid border-bottom border-block-end-dashed">
                                <button class="btn btn-primary d-flex align-items-center justify-content-center"
                                    data-bs-toggle="modal"
                                    data-bs-target="#addtask"
                                    data-saldo="{{ number_format($saldoliquido, 2, ',', '.') }}">
                                    <i class="align-middle ri-add-circle-line fs-16 me-1"></i> Criar Transação de saída
                                </button>

                                <!-- Modal -->
                                <div class="modal fade" id="addtask" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h6 class="modal-title" id="mail-ComposeLabel">Novo Saque</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form id="inserir-entrada" method="POST" action="{{ route('admin.transacoes.addsaida') }}" enctype="multipart/form-data">
                                                @csrf
                                                <div class="px-4 modal-body">
                                                    <div class="row gy-2">

                                                        <!-- Verificação de saldo baixo -->
                                                        @if (isset($saldoBaixo) && $saldoBaixo)
                                                        <div class="mt-4 alert alert-danger">
                                                            <strong>Saldo muito baixo para realizar um saque.</strong>
                                                        </div>
                                                        @endif

                                                        <!-- Campo de seleção de usuário -->
                                                        <div class="col-xl-12">
                                                            <label for="user_id" class="form-label">Selecionar Usuário</label>
                                                            <select class="form-select" id="user_id" name="user_id" required>
                                                                <option value="">Selecione um usuário</option>
                                                                @foreach ($users as $user)
                                                                <option value="{{ $user->user_id }}">{{ $user->username.' | '.$user->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <!-- Campo de valor -->
                                                        <div class="col-xl-12">
                                                            <label for="valor" class="form-label">Valor</label>
                                                            <input type="number" step="0.01" class="form-control" id="valor" name="valor" placeholder="Valor" required>
                                                            <div id="valorError" class="mt-2 text-danger" style="display: none;">
                                                                Saldo insuficiente para o valor solicitado.
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-primary"
                                                        @if (isset($count) && $count> 0) disabled @endif>Solicitar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Explicação sobre taxas padrão -->
                            <div class="m-3 alert alert-info">
                                <ul>
                                    <li><strong>Crie pagamentos de saídas</strong></li>
                                    <li><strong>Escolha o usuário e insira o valor do saldo que vai ser inserido</strong></li>
                                    <li><strong>A descrição vai ficar como {{ env('APP_NAME') }}</strong></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End::row-1 -->

        </div>
    </div>
</x-app-layout>
