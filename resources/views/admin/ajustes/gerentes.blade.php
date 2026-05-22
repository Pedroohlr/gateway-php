<x-app-layout :route="'[ADMIN] Ajustes Gerentes'">
    <div class="main-content app-content">
        <div class="container-fluid">

            <!-- Start::page-header -->
            <div class="mb-3 row ">
                <div style="width:100%;display:flex;align-item:center;justify-content:space-between;" class="mb-5 col-12 col-md-4 mb-md-0 ">
                    <h1 class="mb-0 display-5">Gerentes de contas</h1>
                    <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#adcionarGerente" >Adcionar</button>
                </div>
            </div>

           <div class="row mb-3">
                <div class="col-xl-12">
                    <div class="card card-raised">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="table-listar-usuarios" class="table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th scope="col">Nome</th>
                                            <th scope="col">Telefone</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($gerentes as $gerente)
                                        <tr>
                                            <td>{{ $gerente->name }}</td>
                                            <td>{{ $gerente->telefone }}</td>
                                            <td>{{ $gerente->email }}</td>
                                            <td>
                                                @if($gerente->banido == 0)
                                                    <span class="badge text-bg-success text-white">Ativo</span>
                                                @else
                                                    <span class="badge text-bg-danger text-white">Inativo</span>
                                                @endif
                                            </td>
                                           <td>
                                                <button class="btn btn-warning btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editModal-{{ $gerente->id }}"
                                                    data-id="{{ $gerente->id }}"
                                                    data-name="{{ $gerente->name }}"
                                                    data-telefone="{{ $gerente->email }}">
                                                    Editar
                                                </button>
                                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $gerente->id }}" data-id="{{ $gerente->id }}">Excluir</button>
                                            </td>
                                        </tr>
                                        <!-- Modal Editar -->
                                        <div class="modal fade" id="editModal-{{ $gerente->id }}" tabindex="-1" aria-labelledby="editModalLabel-{{ $gerente->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editModalLabel-{{ $gerente->id }}">Editar Gerente</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="POST" action="{{ route('admin.ajustes.gerente.update', ['id' => $gerente->id]) }}">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" id="editUserId" name="id">
                                                            <div class="mb-3">
                                                                <label for="editNome-{{ $gerente->id }}" class="form-label">Nome</label>
                                                                <input type="text" value="{{ $gerente->name }}" class="form-control" id="editNome-{{ $gerente->id }}" name="name">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="editSenha-{{ $gerente->id }}" class="form-label">Nova senha</label>
                                                                <input type="text"  class="form-control" id="editSenha-{{ $gerente->id }}" name="password">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="editTel-{{ $gerente->id }}" class="form-label">Telefone</label>
                                                                <input type="telefone" value="{{ $gerente->telefone }}" class=" form-control" id="editTel-{{ $gerente->id }}" name="telefone">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="editEmail-{{ $gerente->id }}" class="form-label">Email</label>
                                                                <input type="email" value="{{ $gerente->email }}" class=" form-control" id="editEmail-{{ $gerente->id }}" name="email">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="editStatus-{{ $gerente->id }}" class="form-label">Status</label>
                                                                <select class=" form-control" id="editStatus-{{ $gerente->id }}" name="banido">
                                                                <option value="0" {{ $gerente->banido == 1 ? 'selected' : '' }}>Ativo</option>
                                                                <option value="1" {{ $gerente->banido == 0 ? 'selected' : '' }}>Inativo</option>
                                                                </select>
                                                            </div>
                                                            
                                                            <button type="submit" class="btn btn-primary">Salvar alterações</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Confirmar Exclusão -->
                                        <div class="modal fade" id="deleteModal-{{ $gerente->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $gerente->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel-{{ $gerente->id }}">Confirmar Exclusão</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Você tem certeza que deseja excluir este gerente?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                        <form method="POST" action="{{ route('admin.usuarios.delete', ['id'=> $gerente->id]) }}">
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
    
    <!-- Modal Confirmar Exclusão -->
                                        <div class="modal fade" id="adcionarGerente" tabindex="-1" aria-labelledby="adcionarGerenteLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                       <form method="POST" action="{{ route('admin.ajustes.gerente.add') }}">
                                                            @csrf
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="adcionarGerenteLabel">Adcionar gerente</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="name" class="form-label">Nome</label>
                                                                    <input type="text" class="form-control" id="name" name="name">
                                                                </div>
                                                                 <div class="mb-3">
                                                                    <label for="cpf_cnpj" class="form-label">CPF</label>
                                                                    <input type="text" class="form-control" id="cpf_cnpj" name="cpf_cnpj">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="email" class="form-label">Email</label>
                                                                    <input type="email" class="form-control" id="email" name="email">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="telefone" class="form-label">Telefone</label>
                                                                    <input type="text" class="form-control" id="telefone" name="telefone">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="password" class="form-label">Senha</label>
                                                                    <input type="text" class="form-control" id="password" name="password">
                                                                </div>
                                                            </div>
                                                            <input type="text" class="form-control" id="permission" name="permission" value="9" hidden>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Cancelar</button>
                                                                <button type="submit" class="btn btn-success">Adcionar</button>
                                                            </div>
                                                        </form>
                                                </div>
                                            </div>
                                        </div>
</x-app-layout>