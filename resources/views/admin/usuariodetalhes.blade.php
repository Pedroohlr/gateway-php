<x-app-layout :route="'[ADMIN] Detalhes do usuário'">
    <div class="main-content app-content">
        <div class="container-fluid">

            <div class="d-flex align-items-center justify-content-between my-4 page-header-breadcrumb flex-wrap gap-2">
                <div>
                    <p class="fw-medium fs-20 mb-0">Olá, ADM {{explode(' ',auth()->user()->name)[0]}}</p>
                </div>
            </div>

            <!-- Exibição dos dados -->
            <div class="row">
                <div class="col-12">
                    <div class="card card-raised">
                        <div class="card-header justify-content-between">
                            <div class="card-title">
                                DADOS DO USUÁRIO
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Dados do Usuário -->
                            <div class="row gy-4">
                                <div class="col-12 col-sm-6 col-md-4">
                                    <label class="form-label">Usuario:</label>
                                    <p>{{ $usuario->user_id }}</p>
                                </div>

                                <div class="col-12 col-sm-6 col-md-4">
                                    <label class="form-label">Nome:</label>
                                    <p>{{ $usuario->name }}</p>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4">
                                    <label class="form-label">Email:</label>
                                    <p>{{ $usuario->email }}</p>
                                </div>
<!--<div class="col-12 col-sm-6 col-md-4">
                                    <label class="form-label">Razão Social:</label>
                                    <p>{{ $usuario->razao_social }}</p>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4">
                                    <label class="form-label">Nome Fantasia:</label>
                                    <p>{{ $usuario->razao_social }}</p>
                                </div> -->
                                <div class="col-12 col-sm-6 col-md-4">
                                    <label class="form-label">CPF/CNPJ:</label>
                                    <p>{{ $usuario->cpf_cnpj }}</p>
                                </div>
                               <!-- <div class="col-12 col-sm-6 col-md-4">
                                    <label class="form-label">CPF</label>
                                    <p>{{ $usuario->cpf }}</p>
                                </div> -->
                                <div class="col-12 col-sm-6 col-md-4">
                                    <label class="form-label">Data de Nascimento:</label>
                                    <p>{{ $usuario->data_nascimento }}</p>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4">
                                    <label class="form-label">Telefone:</label>
                                    <p>{{ $usuario->telefone }}</p>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4">
                                    <label class="form-label">Status:</label>
                                    @php
                                        $statusClasses = [
                                            0 => 'bg-warning-transparent text-warning',
                                            1 => 'bg-success-transparent text-success',
                                            3 => 'bg-danger-transparent text-danger',
                                            5 => 'bg-warning-transparent text-warning',
                                            99 => 'bg-danger-transparent text-danger',
                                        ];
                                    
                                        $statusLabels = [
                                            0 => 'Pendente',
                                            1 => 'Aprovado',
                                            3 => 'Banido',
                                            5 => 'Aguardando aprovação',
                                            99 => 'Reprovado',
                                        ];
                                    
                                        $statusClass = $statusClasses[$usuario->status] ?? 'bg-secondary text-dark';
                                        $statusLabel = $statusLabels[$usuario->status] ?? 'Status Desconhecido';
                                    @endphp
                                    
                                    <p class="{{ $statusClass }} p-2 rounded">
                                        {{ $statusLabel }}
                                    </p>
                                </div>

                                <div class="col-12 col-sm-6 col-md-4">
                                    <label class="form-label">Token:</label>
                                    <p >
                                        {{ $usuario->chaves->token ?? ''  }}
                                    </p>
                                </div>

                                <div class="col-12 col-sm-6 col-md-4">
                                    <label class="form-label">Secret:</label>
                                    <p >
                                        {{ $usuario->chaves->secret ?? ''  }}
                                    </p>
                                </div>

                                <!-- Botão de Aprovação -->
                                <div class="col-12 col-sm-6 col-md-4">
                                    <div class="row">
                                        {{-- Formulário 1 - Aprovar ou Em Análise --}}
                                        <div class="col-6">
                                            <form method="POST" action="{{ route('admin.usuarios.mudarstatus') }}">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $usuario->id }}" />
                                                <input type="hidden" name="tipo" value="status" />
                                    
                                                @if ($usuario->status == 1)
                                                    <button type="submit" name="reavaliar" value="true" class="btn btn-warning w-100">Em Análise</button>
                                                @else
                                                    <button type="submit" name="aprovar" value="true"  class="btn btn-success w-100">Aprovar</button>
                                                @endif
                                            </form>
                                        </div>
                                        {{-- Formulário 2 - Reprovar --}}
                                        <div class="col-6">
                                            <form method="POST" action="{{ route('admin.usuarios.mudarstatus') }}">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $usuario->id }}" />
                                                <input type="hidden" name="tipo" value="status" />
                                                <button type="submit" name="reprovar"  value="reprovado"  class="btn btn-danger w-100" {{ $usuario->status == 99 ? 'disabled' : '' }}>Reprovar</button>
                                            </form>
                                        </div>
                                    </div>
                                    </div>

                                <div class="col-12 col-sm-6 col-md-4">
                                    <form method="POST" action="{{ route('admin.usuarios.mudarstatus') }}">
                                        <input id="id" name="id" value="{{$usuario->id}}" hidden />
                                        <input id="tipo" name="tipo" value="banido" hidden />
                                        @csrf
                                        @if ($usuario->banido == 0)
                                        <button type="submit" name="banir" class="btn btn-warning w-100">Banir Usuário</button>
                                        @else
                                        <button type="submit" name="desbanir" class="btn btn-success w-100">Desbanir Usuário</button>
                                        @endif
                                    </form>
                                </div>

                                <div class="col-12 col-sm-6 col-md-4">
                                    <label class="form-label">Data de Cadastro:</label>
                                    <p>{{ \Carbon\Carbon::parse($usuario->data_cadastro)->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>

                            <!-- Contêiner para as fotos -->
                            <div class="row gy-4 mt-4">
                                <div class="col-12">
                                    <div class="card-title">
                                        FOTOS DE DOCUMENTAÇÃO
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 text-center">
                                    <img src="{{ asset($usuario->foto_rg_frente) }}" alt="Foto de Frente RG" class="img-thumbnail" width="150" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#fotoFrenteModal">
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 text-center">
                                    <img src="{{ asset($usuario->foto_rg_verso) }}" alt="Foto de Verso RG" class="img-thumbnail" width="150" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#fotoVersoModal">
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 text-center">
                                    <img src="{{ asset($usuario->selfie_rg) }}" alt="Selfie RG" class="img-thumbnail" width="150" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#selfieModal">
                                </div>
                            </div>

                            <!-- Modais para exibir as fotos maiores -->
                            <!-- Modal Foto Frente RG -->
                            <div class="modal fade" id="fotoFrenteModal" tabindex="-1" aria-labelledby="fotoFrenteLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="fotoFrenteLabel">Foto de Frente RG</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <img src="{{ asset($usuario->foto_rg_frente) }}" alt="Foto de Frente RG" class="img-fluid">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Foto Verso RG -->
                            <div class="modal fade" id="fotoVersoModal" tabindex="-1" aria-labelledby="fotoVersoLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="fotoVersoLabel">Foto de Verso RG</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <img src="{{ asset($usuario->foto_rg_verso) }}" alt="Foto de Verso RG" class="img-fluid">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Selfie RG -->
                            <div class="modal fade" id="selfieModal" tabindex="-1" aria-labelledby="selfieLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="selfieLabel">Selfie com RG</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <img src="{{ asset($usuario->selfie_rg) }}" alt="Selfie com RG" class="img-fluid">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</x-app-layout>
