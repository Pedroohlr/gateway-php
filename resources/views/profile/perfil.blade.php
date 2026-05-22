<x-app-layout :route="'Meu Perfil'">
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="mb-4 col-xxl-3 col-md-6">
                    <div class="flex justify-center border-4 card card-raised align-center">
                        <div class="flex justify-center p-6 card-body align-center">
                            <div style="display:flex;align-items:center;justify-content:center;width: 170px;height:170px;border-radius:80px;border: 2px dashed rgb(230, 230, 230); padding:20px;">
                                <form id="avatarForm" action="{{ route('profile.avatar.upload') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="file" id="avatarInput" name="avatar" accept="image/*" style="display: none;" onchange="submitAvatarForm()">

                                    <div onclick="document.getElementById('avatarInput').click()" style="cursor: pointer; display:flex; align-items:center; justify-content:center; width: 160px; height:160px; border-radius:80px; border: 1px dashed rgb(230, 230, 230); padding:0.1rem;">
                                        <img src="{{ auth()->user()->avatar }}" style="width: 160px; height:160px; border-radius:80px;" title="Clique para alterar">
                                    </div>
                                </form>
                            </div>
                        </div>
                        <p class="text-center text-gray-400" style="margin-top: -30px">Permitido *.jpeg, *.jpg, *.png, *.gif</p>
                            <p class="mb-6 text-center text-gray-400">Tamanho máximo de 3.1 MB</p>
                    </div>
                </div>

                <div class="mb-4 col-xxl-9 col-md-6">
                    <div class="border-4 card card-raised">
                        <div class="px-4 card-body">

                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label for="name" class="form-label">Nome</label>
                                        <input disabled type="text" value="{{ auth()->user()->name }}" class="form-control" id="name" name="name">
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="name" class="form-label">CPF/CNPJ</label>
                                        <input disabled type="text" value="{{ auth()->user()->cpf_cnpj }}" class="form-control" id="cpf_cnpj" name="cpf_cnpj">
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label for="data_nascimento" class="form-label">Data Nascimento</label>
                                        <input disabled type="text" value="{{ \Carbon\Carbon::parse(auth()->user()->data_nascimento)->format('d/m/Y') }}" class="form-control" id="data_nascimento" name="data_nascimento">
                                    </div>
                                    <div class="mb-3 col-md-4">
                                        <label for="telefone" class="form-label">Telefone</label>
                                        <input disabled type="text" value="{{ auth()->user()->telefone }}" class="form-control" id="telefone" name="telefone">
                                    </div>
                                    <div class="mb-3 col-md-5">
                                        <label for="email" class="form-label">Email</label>
                                        <input disabled type="text" value="{{ auth()->user()->email }}" class="form-control" id="email" name="email">
                                    </div>

                                    <div class="mb-3 col-md-3">
                                        <label for="cep" class="form-label">CEP</label>
                                        <input disabled type="text" value="{{ auth()->user()->cep }}" class="form-control" id="cep" name="cep">
                                    </div>
                                    <div class="mb-3 col-md-9">
                                        <label for="rua" class="form-label">Logradouro</label>
                                        <input disabled type="text" value="{{ auth()->user()->rua }}" class="form-control" id="rua" name="rua">
                                    </div>
                                    <div class="mb-3 col-md-3">
                                        <label for="numero_residencia" class="form-label">Número</label>
                                        <input disabled type="text" value="{{ auth()->user()->numero_residencia ?? "S/N" }}" class="form-control" id="numero_residencia" name="numero_residencia">
                                    </div>
                                    <div class="mb-3 col-md-9">
                                        <label for="complemento" class="form-label">Complemento</label>
                                        <input disabled type="text" value="{{ auth()->user()->complemento }}" class="form-control" id="complemento" name="complemento">
                                    </div>
                                    <div class="mb-3 col-md-4">
                                        <label for="bairro" class="form-label">Bairro</label>
                                        <input disabled type="text" value="{{ auth()->user()->bairro }}" class="form-control" id="bairro" name="bairro">
                                    </div>
                                    <div class="mb-3 col-md-4">
                                        <label for="cidade" class="form-label">Cidade</label>
                                        <input disabled type="text" value="{{ auth()->user()->cidade }}" class="form-control" id="cidade" name="cidade">
                                    </div>
                                    <div class="mb-3 col-md-4">
                                        <label for="estado" class="form-label">Estado</label>
                                        <input disabled type="text" value="{{ auth()->user()->estado }}" class="form-control" id="estado" name="estado">
                                    </div>
                                </div>

                        </div>
                    </div>
                </div>

        </div>
    </div>
    <script>
        function submitAvatarForm() {
            const form = document.getElementById('avatarForm');
            const input = document.getElementById('avatarInput');
            if (input.files.length > 0) {
                form.submit();
            }
        }
    </script>

</x-app-layout>
