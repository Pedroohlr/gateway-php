<x-app-layout :route="'[ADMIN] Ajustes de SMTP e Emails'">
    <div class="main-content app-content">
        <div class="container-fluid">
            <!-- Start::page-header -->
            <div class="mb-3 row justify-content-between align-items-center">
                <div class="mb-0 col-12 col-md-4 d-flex justify-content-start align-items-center">
                    <h1 class="mb-0 display-5">Ajustes SMTP/Emails</h1>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.ajustes.smtp') }}" enctype="multipart/form-data">
                @csrf
                @method('POST')

                <!-- Card: SMTP -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card card-raised">
                            <div class="card-header">
                                <h6>Configurações de SMTP</h6>
                            </div>
                            <div class="card-body">
                                <div class="row gy-3">
                                    <div class="col-md-6 col-lg-9">
                                        <label for="host" class="form-label">Hostname</label>
                                        <input type="text" class="form-control @error('host') is-invalid @enderror"
                                            name="host" value="{{ $smtp->host }}">
                                        @error('host')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 col-lg-3">
                                        <label for="port" class="form-label">Porta</label>
                                        <input type="number" class="form-control @error('port') is-invalid @enderror"
                                            name="port" value="{{ $smtp->port }}">
                                        @error('port')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-6">
                                        <label for="user" class="form-label">Username (Email)</label>
                                        <input type="email" class="form-control @error('user') is-invalid @enderror"
                                            name="user" value="{{ $smtp->user }}">
                                        @error('user')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-6">
                                        <label for="pass" class="form-label">Password</label>
                                        <input type="text" class="form-control @error('pass') is-invalid @enderror"
                                            name="pass" value="{{ $smtp->pass }}">
                                        @error('pass')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card: STATUS -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card card-raised">
                            <div class="card-header">
                                <h6>Configurações visuais Email (2FA)</h6>
                            </div>
                            <input type="hidden" name="auth_title" id="input-auth-title">
                            <input type="hidden" name="auth_message" id="input-auth-message">
                            <div class="row">
                                <div class="col-12 col-lg-6 px-4 mb-3">
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <label for="user" class="form-label">Cor</label>
                                            <input type="color"
                                                class="form-control @error('color') is-invalid @enderror" name="color"
                                                value="{{ $smtp->color }}">
                                            @error('color')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-12 mb-3">
                                            <x-image-upload :id="'image'" :name="'image'"
                                               :label="'Imagem'" :value="asset($smtp->image) ?? null" />
                                        </div>
                                    </div>

                                </div>
                                <div class="col-12 col-lg-6 px-4 mb-3">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="col-12 px-4 mb-3">
                                                <label for="auth_title" class="form-label">Titulo</label>
                                                <div id="auth-title"></div>
                                                @error('auth_title')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="col-12 px-4 mb-3">
                                                <label for="auth_message" class="form-label">Mensagem</label>
                                                <div id="auth-message"></div>
                                                <smal class="text-warning">{{ "{nome} Nome do cliente | " }}</smal>
                                                <smal class="text-warning">{{ "{gateway} Nome do gateway" }}</smal>
                                                @error('auth_message')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card card-raised">
                            <div class="card-body">
                                <div class="row gy-3">
                                    <div class="col-12 text-end mt-4">
                                        <button type="submit" class="btn btn-primary">Alterar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.ckeditor.com/ckeditor5/41.2.1/classic/ckeditor.js"></script>
    <script>
        let editorTitle;
        let editorMessage;

        /* ========== EDITOR 1 (Título) ========== */
        ClassicEditor
            .create(document.querySelector('#auth-title'), {
                toolbar: [
                    'undo', 'redo', '|',
                    'heading', '|',
                    'bold', 'italic', 'underline', 'strikethrough', '|',
                    'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|',
                    'alignment', '|',
                    'bulletedList', 'numberedList', '|',
                    'link', 'imageUpload', 'insertTable'
                ],
            })
            .then(editor => {
                editorTitle = editor;
                editorTitle.setData(`{!! $smtp->auth_title !!}`);

                // atualizar hidden quando editar
                editorTitle.model.document.on('change:data', () => {
                    document.getElementById('input-auth-title').value = editorTitle.getData();
                });
            });

        /* ========== EDITOR 2 (Mensagem) ========== */
        ClassicEditor
            .create(document.querySelector('#auth-message'), {
                toolbar: [
                    'undo', 'redo', '|',
                    'heading', '|',
                    'bold', 'italic', 'underline', 'strikethrough', '|',
                    'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|',
                    'alignment', '|',
                    'bulletedList', 'numberedList', '|',
                    'link', 'imageUpload', 'insertTable'
                ],
            })
            .then(editor => {
                editorMessage = editor;
                editorMessage.setData(`{!! $smtp->auth_message !!}`);

                editorMessage.model.document.on('change:data', () => {
                    document.getElementById('input-auth-message').value = editorMessage.getData();
                });
            });

        /* ========== Antes de enviar o form (garantia extra) ========== */
        document.querySelector("form").addEventListener("submit", () => {
            document.getElementById('input-auth-title').value = editorTitle.getData();
            document.getElementById('input-auth-message').value = editorMessage.getData();
        });

    </script>
    <style>
        /* Área de edição */
        .ck-editor__editable {
            background: transparent !important;
            color: #ffffff !important;
        }

        /* Toolbar */
        .ck.ck-toolbar {
            background: transparent !important;
            border-color: white !important;
        }

        /* Borda externa */
        .ck-editor__main,
        .ck-editor__top {
            border-color: white !important;
        }

        /* Dropdowns e menus */
        .ck.ck-button,
        .ck.ck-dropdown__panel {
            background: transparent !important;
            color: #fff !important;
        }

        .ck.ck-button:hover,
        .ck.ck-dropdown__panel:hover {
            background: transparent !important;
        }

        /* Ícones da toolbar */
        .ck.ck-icon {}
    </style>
</x-app-layout>