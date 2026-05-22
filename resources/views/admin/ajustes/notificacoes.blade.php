<x-app-layout :route="'[ADMIN] Ajustes FCM'">
    <div class="main-content app-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">APP (Notificações)</h5>
                            <small>Configurações de notificações Entradas/Saidas</small>
                        </div>
                        <div class="card-body">
                            <form class="my-3" method="POST" action="{{ route('admin.ajustes.notificacoes') }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('POST')
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <h6>Notificações de Pix IN</h6>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="title" name="title"
                                                value="{{ $fcm->title }}">
                                            <label for="title">Titulo da notificação</label>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 mb-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="body" name="body"
                                                value="{{ $fcm->body }}">
                                            <label for="body">Body da notificação</label>
                                        </div>
                                        <small class="text-warning">Atente-se em adcionar a string: {valor} onde será
                                            exibido o valor no body</small>
                                    </div>

                                    <div class="col-12 mb-2">
                                        <h6>Notificações de Pix OUT</h6>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="title_cashout"
                                                name="title_cashout" value="{{ $fcm->title_cashout }}">
                                            <label for="title_cashout">Titulo da notificação</label>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 mb-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="body_cashout"
                                                name="body_cashout" value="{{ $fcm->body_cashout }}">
                                            <label for="body_cashout">Body da notificação</label>
                                        </div>
                                        <small class="text-warning">Atente-se em adcionar a string: {valor} onde será
                                            exibido o valor no body</small>
                                    </div>
                                    <div class="col-12">
                                        <div class="col-12 text-end mt-4">
                                            <button type="submit" class="btn btn-primary">Alterar</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 my-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Enviar notificação</h5>
                        <small>Envie uma notificação para todos os clientes</small>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('notificacoes.send.all') }}">
                            @csrf
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <div>
                                        <label for="title">Titulo da notificação</label>
                                        <input type="text" class="form-control" id="title" name="title">
                                    </div>
                                </div>

                                <div class="col-12 mb-3">
                                    <div>
                                        <label for="body">Body da notificação (Mensagem)</label>
                                        <textarea type="text" class="form-control" id="body" name="body"
                                            style="min-height: 60px;background: transparent;border-color:white;"></textarea>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <div>
                                        <label for="title">Url</label>
                                        <input type="text" class="form-control" id="url" name="url">
                                        <small class="text-warning">Não obrigatório. Define para onde será redirecionado
                                            ao
                                            clicar na
                                            notificação.</small>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="col-12 text-end mt-4">
                                        <button type="submit" class="btn btn-primary">Enviar</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            window.MultiSelectDropdown.init();
        })
    </script>
</x-app-layout>