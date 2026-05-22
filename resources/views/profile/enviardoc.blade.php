<x-app-layout :route="'Validar documentos'">
    <div class="main-content app-content">
        <div class="container-fluid">

            <!-- Start::page-header -->
            <div class="flex-wrap gap-2 my-4 d-flex align-items-center justify-content-between page-header-breadcrumb">
                <!-- HTML do formulário -->
                <form method="POST" enctype="multipart/form-data" action="{{ route('profile.enviardocs', ['id' => auth()->id() ]) }}">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-raised">
                                <div class="card-header justify-content-between">
                                    <div class="card-title">
                                        DADOS CADASTRAIS
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row gy-4">
                                        <div class="col-12 col-md-6">
                                            <label for="cpf-cnpj" class="form-label">CPF/CNPJ</label>
                                            <input type="text" class="form-control" id="cpf-cnpj" name="cpf_cnpj" value="{{ old('cpf_cnpj') }}" required placeholder="">
                                      		@error('cpf_cnpj')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                      </div>
                                        <div class="col-12 col-md-6">
                                            <label for="cpf-cnpj" class="form-label">Data de nascimento</label>
                                            <input type="date" class="form-control" id="data-nascimento" name="data_nascimento" value="{{ old('data_nascimento') }}" required placeholder="">
                                      		@error('data_nascimento')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label for="CEP" class="form-label">CEP</label>
                                            <input type="text" class="form-control" id="CEP" name="cep" value="{{ old('cep') }}" required placeholder="">
                                      		@error('cep')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label for="rua" class="form-label">Rua</label>
                                            <input type="text" class="form-control" id="rua" name="rua" value="{{ old('cep') }}" required placeholder="">
                                      		@error('rua')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label for="numero_residencia" class="form-label">Número</label>
                                            <input type="text" class="form-control" id="numero_residencia" name="numero_residencia" value="{{ old('numero_residencia') }}" required placeholder="">
                                      		@error('numero_residencia')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label for="complemento" class="form-label">Complemento:</label>
                                            <input type="text" class="form-control" id="complemento" name="complemento" value="{{ old('complemento') }}" required placeholder="">
                                      		@error('complemento')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label for="bairro" class="form-label">Bairro:</label>
                                            <input type="text" class="form-control" id="bairro" name="bairro" value="{{ old('bairro') }}" required placeholder="">
                                      		@error('bairro')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label for="cidade" class="form-label">Cidade:</label>
                                            <input type="text" class="form-control" id="cidade" name="cidade" value="{{ old('cidade') }}" required placeholder="">
                                      		@error('cidade')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label for="estado" class="form-label">Estado:</label>
                                            <input type="text" class="form-control" id="estado" name="estado" value="{{ old('estado') }}" required placeholder="">
                                      		@error('estado')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label">Média de Faturamento mensal:</label>
                                            <select class="form-control" name="media_faturamento" value="{{ old('media_faturamento') }}" required>
                                                <option value="">Selecione uma opção</option>
                                                <option value="10000-30000">Entre R$ 10.000 - 30.000</option>
                                                <option value="30000-100000">Entre R$ 30.000 - 100.000</option>
                                                <option value="100000-400000">Entre R$ 100.000 - 400.000</option>
                                                <option value="500000+">Acima de R$ 500.000</option>
                                            </select>
                                      		@error('media_faturamento')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label for="foto_rg_frente" class="form-label">Foto de frente RG ou Habilitação:</label>
                                            <input class="form-control" type="file" accept="image/*" id="foto_rg_frente" name="foto_rg_frente" value="{{ old('foto_rg_frente') }}" required>
                                      		@error('foto_rg_frente')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label for="foto_rg_verso" class="form-label">Foto do verso RG ou Habilitação:</label>
                                            <input class="form-control" type="file" accept="image/*" id="foto_rg_verso" name="foto_rg_verso" value="{{ old('foto_rg_verso') }}" required>
                                      		@error('foto_rg_verso')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label for="selfie_rg" class="form-label">Selfie segurando o RG:</label>
                                            <input class="form-control" type="file" accept="image/*" id="selfie_rg" name="selfie_rg" value="{{ old('selfie_rg') }}" required>
                                      		@error('selfie_rg')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-end">
                                    <button type="submit" class="btn btn-primary btn-wave waves-effect waves-light">
                                        <i class="bi bi-plus-circle"></i> enviar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- Inclua o Bootstrap JS -->
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("CEP").addEventListener("blur", function() {
                let cep = this.value.replace(/\D/g, ""); // Remove caracteres não numéricos

                if (cep.length === 8) { // Verifica se tem 8 dígitos
                    fetch(`https://viacep.com.br/ws/${cep}/json/`)
                        .then(response => response.json())
                        .then(data => {
                            if (!data.erro) {
                                document.getElementById("rua").value = data.logradouro || "";
                                document.getElementById("bairro").value = data.bairro || "";
                                document.getElementById("cidade").value = data.localidade || "";
                                document.getElementById("estado").value = data.uf || "";
                            } else {
                                alert("CEP não encontrado!");
                                limparCampos();
                            }
                        })
                        .catch(error => {
                            console.error("Erro ao buscar o CEP:", error);
                            limparCampos();
                        });
                }
            });

            function limparCampos() {
                document.getElementById("rua").value = "";
                document.getElementById("bairro").value = "";
                document.getElementById("cidade").value = "";
                document.getElementById("estado").value = "";
            }
        });
    </script>

    <!-- Modal de sucesso -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Dados Enviados</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Seus dados foram enviados com sucesso e estão em análise. Você receberá um retorno em até 24 horas.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="window.location.href='../home';">Continuar</button>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>
