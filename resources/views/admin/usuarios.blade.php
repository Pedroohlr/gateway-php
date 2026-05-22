@php
use Carbon\Carbon;
$setting = \App\Models\App::first();
@endphp
<x-app-layout :route="'[ADMIN] Usuários'">
    <div class="main-content app-content">
        <div class="container-fluid">

            <div class="mb-3 row justify-content-between align-items-">
                <div style="display:flex;align-item:center;justify-content:flex-start;" class="mb-5 col-12 col-md-4 mb-md-0 justify-content-start align-items-center">
                    <h1 class="mb-0 display-5">Usuários</h1>
                </div>
            </div>
            <!-- Start:: row-1 -->
            <div class="row">
                <div class="mb-4 col-xxl-3 col-md-6">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body" style="min-height: 114px">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5">{{ $cadastrosHoje }}</div>
                                    <div class="card-text">Cadastrados hoje</div>
                                </div>
                                <div class="text-white icon-circle bg-info card-color"><i class="text-xl fa-solid fa-users"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4 col-xxl-3 col-md-6">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body" style="min-height: 114px">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5">{{ $cadastrosSemana }}</div>
                                    <div class="card-text">Cadastrados na semana</div>
                                </div>
                                <div class="text-white icon-circle bg-info card-color"><i class="text-xl fa-solid fa-users"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4 col-xxl-3 col-md-6">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body" style="min-height: 114px">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5">{{ $cadastrosMes }}</div>
                                    <div class="card-text">Cadastrados no mês</div>
                                </div>
                                <div class="text-white icon-circle bg-info card-color"><i class="text-xl fa-solid fa-users"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4 col-xxl-3 col-md-6">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body" style="min-height: 114px">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5">{{ $totalCadastros }}</div>
                                    <div class="card-text">Cadastrados total</div>
                                </div>
                                <div class="text-white icon-circle bg-info card-color"><i class="text-xl fa-solid fa-users"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- End:: row-1 --}}
            <div class="row mb-3">
                <div class="col-xl-12">
                    <div class="card card-raised">
                        <div class="card-header bg-white my-3">
                            <h6>Carteira de recebimento dos lucros</h6>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.usuarios.carteira-lucro') }}">
                                @csrf
                                <div class="d-flex align-items-center mb-3 gap-3">
                                    <div class="w-100">
                                        <select class="form-select" id="carteira_lucro" name="carteira_lucro">
                                            <option value="{{ null }}">Nenhuma</option>
                                        @foreach ($list_users as $user)    
                                            <option value="{{ $user->email }}" {{ $user->email === $setting->carteira_lucro ? "selected" : "" }}>{{ $user->email }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Alterar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            @if((clone $list_users)->where('status', 5)->count() > 0)

                <div class="row mb-3">
                <div class="col-xl-12">
                    <div class="card card-raised">
                        <div class="card-header bg-white">
                            <h6>Aguardando aprovação </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="table-listar-usuarios" class="table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th scope="col">Status</th>
                                            <th scope="col">Nome</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Saldo</th>
                                            <th scope="col">Data de Cadastro</th>
                                            <th scope="col">Permissão</th>
                                            <th scope="col">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ((clone $list_users)->where('status', 5) as $user)
                                        <tr>
                                            <td>
                                                @if($user->status == 0)
                                                    <span class="badge text-bg-dark text-white">Pendente de doc</span>
                                                @elseif($user->status == 1)
                                                    <span class="badge text-bg-success text-white">Aprovado</span>
                                                @elseif($user->status == 5)
                                                    <span class="badge text-bg-warning text-white">Aguardando aprovação</span>
                                                @elseif($user->status == 99)
                                                    <span class="badge text-bg-danger text-white">Reprovado</span>
                                                @endif
                                            </td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>R$ {{ number_format($user->saldo, 2, ',', '.') }}</td>
                                            <td>{{ Carbon::parse($user->created_at)->format('d/m/Y H:i') }}</td>
                                            <td>
                                                @switch($user->permission)
                                                @case(1)
                                                Usuário
                                                @break
                                                @case(2)
                                                Afiliado
                                                @break
                                                @case(3)
                                                Admin
                                                @break
                                                @default
                                                Desconhecido
                                                @endswitch
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.usuario.detalhes', $user->id) }}" class="btn btn-info btn-sm">Detalhes</a>
                                                <button class="btn btn-warning btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editModal-{{ $user->id }}"
                                                    data-id="{{ $user->id }}"
                                                    data-name="{{ $user->name }}"
                                                    data-email="{{ $user->email }}"
                                                    data-saldo="{{ $user->saldo }}"
                                                    data-permission="{{ $user->permission }}">
                                                    Editar
                                                </button>
                                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $user->id }}" data-id="{{ $user->id }}">Excluir</button>
                                            </td>
                                        </tr>
                                        <!-- Modal Editar -->
                                       <div class="modal fade"
                                            id="editModal-{{ $user->id }}"
                                            tabindex="-1">

                                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editModalLabel-{{ $user->id }}">Editar Usuário</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body" >
                                                        <form method="POST" action="{{ route('admin.usuarios.edit', ['id' => $user->id]) }}">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" id="editUserId" name="id">
                                                            <div class="mb-3">
                                                                <label for="editNome-{{ $user->id }}" class="form-label">Nome</label>
                                                                <input type="text" value="{{ $user->name }}" class="form-control" id="editNome-{{ $user->id }}" name="name">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="editSenha-{{ $user->id }}" class="form-label">Nova senha</label>
                                                                <input type="text"  class="form-control" id="editSenha-{{ $user->id }}" name="password">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="editEmail-{{ $user->id }}" class="form-label">Email</label>
                                                                <input type="email" value="{{ $user->email }}" class=" form-control" id="editEmail-{{ $user->id }}" name="email">
                                                            </div>
                                                            <div class="mb-3">
                                                                <p for="e-token-{{ $user->id }}" class="form-label">Token</p>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" value="{{ $user->chaves->token ?? '' }}" name="token" id="e-token-{{ $user->id }}" readonly>
                                                                    <button class="btn btn-primary" onclick="gerarChaveToken('{{ $user->id }}')" type="button">Gerar</button>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <p for="e-secret-{{ $user->id }}" class="form-label">Secret</p>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" value="{{ $user->chaves->secret ?? '' }}" name="secret" id="e-secret-{{ $user->id }}" readonly>
                                                                    <button class="btn btn-primary" onclick="gerarChaveSecret('{{ $user->id }}')" type="button">Gerar</button>
                                                                </div>
                                                            </div>
                                                             <div class="mb-3">
                                                                <label for="gerente-{{ $user->id }}" class="form-label">Gerente da conta</label>
                                                                <select class="form-select" value="{{ $user->gerente_id }}" id="gerente-{{ $user->id }}" name="gerente_id">
                                                                    @foreach($gerentes as $gerente)
                                                                    <option value="{{$gerente->id}}" {{ $user->gerente_id == $gerente->id ? "selected" : "" }}>{{$gerente->name }}</option>
                                                                    @endforeach
                                                                    <!-- Adicione outras permissões conforme necessário -->
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="editPermission-{{ $user->id }}" class="form-label">Permissão</label>
                                                                <select class="form-select" value="{{ $user->permission }}" id="editPermission-{{ $user->id }}" name="permission">
                                                                    <option value="1" {{ $user->permission == 1 ? "selected" : "" }}>usuario</option>
                                                                    <option value="2" {{ $user->permission == 2 ? "selected" : "" }}>afilaido</option>
                                                                    <option value="9" {{ $user->permission == 9 ? "selected" : "" }}>Gerente</option>
                                                                    <option value="3" {{ $user->permission == 3 ? "selected" : "" }}>Admin</option>
                                                                    <!-- Adicione outras permissões conforme necessário -->
                                                                </select>
                                                            </div>
                                                            <button type="submit" class="btn btn-primary">Salvar alterações</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Confirmar Exclusão -->
                                        <div class="modal fade" id="deleteModal-{{ $user->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $user->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel-{{ $user->id }}">Confirmar Exclusão</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Você tem certeza que deseja excluir este usuário?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                        <form method="POST" action="{{ route('admin.usuarios.delete', ['id'=> $user->id]) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Excluir</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            @endif

            <div class="row mb-3">
                <div class="col-xl-12">
                    <div class="card card-raised">
                        <div class="card-header bg-white">
                            <h6>Todos os usuários</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="table-listar-usuarios" class="table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th scope="col">Status</th>
                                            <th scope="col">Nome</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Saldo</th>
                                            <th scope="col">Data de Cadastro</th>
                                            <th scope="col">Gerente</th>
                                            <th scope="col">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($list_users as $user)
                                        <tr>
                                            <td>
                                                @if($user->status == 0)
                                                    <span class="badge text-bg-dark text-white">Pendente de doc</span>
                                                @elseif($user->status == 1)
                                                    <span class="badge text-bg-success text-white">Aprovado</span>
                                                @elseif($user->status == 5)
                                                    <span class="badge text-bg-warning text-white">Aguardando aprovação</span>
                                                @elseif($user->status == 99)
                                                    <span class="badge text-bg-danger text-white">Reprovado</span>
                                                @endif
                                            </td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>R$ {{ number_format($user->saldo, 2, ',', '.') }}</td>
                                            <td>{{ Carbon::parse($user->created_at)->format('d/m/Y H:i') }}</td>
                                            <td>
                                                @if($user->gerente_id)
                                                {{ $user->gerente->name }}
                                              @else
                                                -------
                                              @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.usuario.detalhes', $user->id) }}" class="btn btn-info btn-sm">Detalhes</a>
                                                <button class="btn btn-warning btn-sm text-black"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editModal-{{ $user->id }}"
                                                    data-id="{{ $user->id }}"
                                                    data-name="{{ $user->name }}"
                                                    data-email="{{ $user->email }}"
                                                    data-saldo="{{ $user->saldo }}"
                                                    data-permission="{{ $user->permission }}"
                                        data-bs-backdrop="false"
                                        data-bs-keyboard="false">
                                                    Editar
                                                </button>
                                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $user->id }}" data-id="{{ $user->id }}">Excluir</button>
                                            </td>
                                        </tr>
                                        <!-- Modal Editar -->
                                        <div class="modal fade" 
                                        id="editModal-{{ $user->id }}" 
                                        tabindex="-1" 
                                        aria-labelledby="editModalLabel-{{ $user->id }}" 
                                        aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editModalLabel-{{ $user->id }}">Editar Usuário</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="POST" action="{{ route('admin.usuarios.edit', ['id' => $user->id]) }}">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="row">
                                                                <input type="hidden" id="editUserId" name="id">
                                                            
                                                                <div class="col-12 mb-3">
                                                                    <label for="editNome-{{ $user->id }}" class="form-label">Nome</label>
                                                                    <input type="text" value="{{ $user->name }}" class="form-control" id="editNome-{{ $user->id }}" name="name">
                                                                </div>
                                                            
                                                                <div class="col-lg-6 mb-3">
                                                                    <label for="editEmail-{{ $user->id }}" class="form-label">Email</label>
                                                                    <input type="email" value="{{ $user->email }}" class="form-control" id="editEmail-{{ $user->id }}" name="email">
                                                                </div>
                                                            
                                                                <div class="col-lg-6 mb-3">
                                                                    <label for="editPermission-{{ $user->id }}" class="form-label">Permissão</label>
                                                                    <select class="form-select" id="editPermission-{{ $user->id }}" name="permission">
                                                                        <option value="1" {{ $user->permission == 1 ? "selected" : "" }}>Usuário</option>
                                                                        <option value="9" {{ $user->permission == 9 ? "selected" : "" }}>Gerente</option>
                                                                        <option value="3" {{ $user->permission == 3 ? "selected" : "" }}>Admin</option>
                                                                    </select>
                                                                </div>
                                                                
                                                                <div class="col-lg-6 mb-3">
                                                                    <label for="e-token-{{ $user->id }}" class="form-label">Token</label>
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" value="{{ $user->chaves->token ?? '' }}" name="token" id="e-token-{{ $user->id }}" readonly>
                                                                        <button class="btn btn-primary" onclick="gerarChaveToken('{{ $user->id }}')" type="button">Gerar</button>
                                                                    </div>
                                                                </div>
                                                            
                                                                <div class="col-lg-6 mb-3">
                                                                    <label for="e-secret-{{ $user->id }}" class="form-label">Secret</label>
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" value="{{ $user->chaves->secret ?? '' }}" name="secret" id="e-secret-{{ $user->id }}" readonly>
                                                                        <button class="btn btn-primary" onclick="gerarChaveSecret('{{ $user->id }}')" type="button">Gerar</button>
                                                                    </div>
                                                                </div>
                                                            
                                                                <div class="col-lg-6 mb-3">
                                                                    <label for="taxa_cash_in" class="form-label">Cash-in (%)</label>
                                                                    <input type="text" class="form-control" value="{{ $user->taxa_cash_in }}" name="taxa_cash_in" id="taxa_cash_in">
                                                                </div>
                                                            
                                                                <div class="col-lg-6 mb-3">
                                                                    <label for="taxa_cash_in_fixa" class="form-label">Cash-in (R$)</label>
                                                                    <input type="text" class="form-control" value="{{ $user->taxa_cash_in_fixa }}" name="taxa_cash_in_fixa" id="taxa_cash_in_fixa">
                                                                </div>
                                                            
                                                                <div class="col-lg-6 mb-3">
                                                                    <label for="taxa_cash_out" class="form-label">Cash-Out (%)</label>
                                                                    <input type="text" class="form-control" value="{{ $user->taxa_cash_out }}" name="taxa_cash_out" id="taxa_cash_out">
                                                                </div>
                                                            
                                                                <div class="col-lg-6 mb-3">
                                                                    <label for="taxa_cash_out_fixa" class="form-label">Cash-Out (R$)</label>
                                                                    <input type="text" class="form-control" value="{{ $user->taxa_cash_out_fixa }}" name="taxa_cash_out_fixa" id="taxa_cash_out_fixa">
                                                                </div>
                                                            
                                                                <div class="col-lg-6 mb-3">
                                                                    <label for="gerente-{{ $user->id }}" class="form-label">Gerente da conta</label>
                                                                    <select class="form-select" id="gerente-{{ $user->id }}" name="gerente_id">
                                                                        @foreach($gerentes as $gerente)
                                                                        <option value="{{ $gerente->id }}" {{ $user->gerente_id == $gerente->id ? "selected" : "" }}>{{ $gerente->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            
                                                               
                                                            
                                                                <div class="col-lg-6 mb-3">
                                                                    <label for="editSenha-{{ $user->id }}" class="form-label">Nova senha</label>
                                                                    <input type="text" class="form-control" id="editSenha-{{ $user->id }}" name="password">
                                                                <small class="text-warning">Digite somente se precisar alterar</small>
                                                                </div>
                                                            </div>

                                                            <button type="submit" class="btn btn-primary">Salvar alterações</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Confirmar Exclusão -->
                                        <div class="modal fade" id="deleteModal-{{ $user->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $user->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel-{{ $user->id }}">Confirmar Exclusão</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Você tem certeza que deseja excluir este usuário?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                        <form method="POST" action="{{ route('admin.usuarios.delete', ['id'=> $user->id]) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Excluir</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @endforeach
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
document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('touchstart', e => {
        e.stopPropagation();
    }, { passive: true });
});
</script>
   <script>
document.addEventListener("DOMContentLoaded", function() {
    $("#table-listar-usuarios").DataTable({
        responsive: true,
        info: false,
        ordering: false,
        lengthChange: false,
        autoWidth: false,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
        },

        columnDefs: [
            { width: "80px",  targets: 0 }, // Status
            { width: "200px", targets: 1 }, // Nome
            { width: "220px", targets: 2 }, // Email
            { width: "100px", targets: 3 }, // Saldo
            { width: "140px", targets: 4 }, // Data
            { width: "140px", targets: 5 }, // Gerente
            { width: "160px", targets: 6, className: "text-nowrap" } // Ações
        ],

        dom: '<"top"f>rt<"bottom"p><"clear">', // <-- vírgula aqui!

        initComplete: function() {
            $('#table-listar-usuarios_filter input[type="search"]')
                .attr('placeholder', 'Pesquisar');
        }
    });
});
</script>


    <script>

        function gerarChaveSecret(id){
            let chave = generateUUIDv4();
            document.getElementById(`e-secret-${id}`).value = chave;
        }

        function gerarChaveToken(id){
            let chave = generateUUIDv4();
            document.getElementById(`e-token-${id}`).value = chave;
        }

        function generateUUIDv4() {
            return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
                (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
            );
        }

    </script>

</x-app-layout>
