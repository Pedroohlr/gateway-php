<x-app-layout :route="'Check-out'">

    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-3">
                    <div class="card card-raised">
                        <div class="card-body p-0">
                            <div class="p-3 d-grid border-bottom border-block-end-dashed">
                                <button class="btn btn-primary d-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#addtask">
                                    <i class="ri-add-circle-line fs-16 align-middle me-1"></i>Criar novo Checkout
                                </button>
                                <div class="modal fade" id="addtask" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h6 class="modal-title">Novo Checkout</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form method="POST" action="{{ route('profile.checkout.create') }}" enctype="multipart/form-data">
                                                @csrf
                                                @method('POST')
                                                <div class="modal-body px-4">
                                                    <div class="row gy-2">
                                                        <div class="col-xl-12">
                                                            <label for="produto_name" class="form-label">Nome do Produto</label>
                                                            <input type="text" class="form-control @error('produto_name') is-invalid @enderror" name="produto_name" required>
                                                            @error('produto_name')
                                                            <span style="color: red;">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-xl-12">
                                                            <label for="valor_checkout" class="form-label">Valor</label>
                                                            <input type="text" class="form-control @error('valor_checkout') is-invalid @enderror" name="valor_checkout" required>
                                                            @error('valor_checkout')
                                                            <span style="color: red;">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-xl-12">
                                                            <label for="obrigado_page" class="form-label">Página de Obrigado</label>
                                                            <input type="text" class="form-control @error('obrigado_page') is-invalid @enderror" name="obrigado_page" required>
                                                            @error('obrigado_page')
                                                            <span style="color: red;">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-xl-12">
                                                            <x-image-upload id="formFile" name="formFile" label="Imagem do produto" :value="null" />
                                                            @error('formFile')
                                                            <span style="color: red;">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-xl-12">
                                                          <x-image-upload id="bannerFile" name="bannerFile" label="Banner do produto" :value="null" />
                                                            @error('bannerFile')
                                                            <span style="color: red;">{{ $message }}</span>
                                                            @enderror
                                                            <div class="alert alert-info mt-4">
                                                                <p>Por favor, faça o upload de um banner nas dimensões recomendadas: 1200x400 pixels.</p>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-4">
                                                            <label for="status" class="form-label">Status</label>
                                                            <select class="form-control @error('status') is-invalid @enderror" name="status" required>
                                                                <option value="1">Ativo</option>
                                                                <option value="0">Inativo</option>
                                                            </select>
                                                            @error('status')
                                                            <span style="color: red;">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-primary">Criar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-9">
                    <div class="card card-raised">
                        <div class="card-header justify-content-between">
                            <div class="card-title">Produtos</div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table text-nowrap table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Produto</th>
                                            <th>Status</th>
                                            <th>Valor</th>
                                            <th>Pedidos</th>
                                            <th>Link Checkout</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($produtos as $produto)
                                        @if(isset($produto))
                                        <tr>
                                            <th>
                                                <div class='d-flex align-items-center'>
                                                    <span class='avatar avatar-xs me-2 online avatar-rounded'>
                                                        <img src='{{ $produto->logo_produto }}' alt='img'>
                                                    </span>{{ $produto->name_produto }}
                                                </div>
                                            </th>
                                            <td><span class='badge {{ $produto->ativo ? "bg-success" : "bg-light text-dark" }}'>{{ $produto->ativo ? 'Ativo' : 'Inativo' }}</span></td>
                                            <td>R$ {{ number_format((float)$produto->valor ?? 0, '2', ',', '.') }}</td>
                                            <td>
                                                @if($produto->vendas->count() == 0)
                                                0
                                                @else
                                                <a href="#" onclick="{{ $pedidos = $produto->vendas }}">
                                                    {{$produto->vendas->count()}}
                                                </a>
                                                @endif
                                            </td>
                                            <td>
                                                <a href='{{ $produto->url_checkout }}' class='btn btn-primary' target='_blank'>DIGITAL</a>
                                                <a href='{{ str_replace("v1", "v2", $produto->url_checkout) }}' class='btn btn-primary' target='_blank'>FÍSICO</a>
                                            </td>
                                            <td>
                                                <div class='hstack gap-2 flex-wrap'>
                                                    <a href='#' class='btn btn-info btn-edit' data-bs-toggle='modal' data-bs-target='#editModal' data-id='{{ $produto->id }}' data-name='{{ $produto->name_produto }}' data-value='{{ $produto->valor }}' data-thank-you-page='{{ $produto->obrigado_page }}' data-status='{{ $produto->ativo }}'><i class='ri-edit-line'></i></a>
                                                    <form action="{{ route('profile.checkout.delete', $produto->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Você tem certeza que deseja excluir este produto?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">
                                                            <i class="ri-delete-bin-5-line"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Modal para Editar Checkout -->
                                        <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h6 class="modal-title" id="editModalLabel">Editar Checkout</h6>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form id="editForm" method="POST" action="{{ route('profile.checkout.edit', $produto->id) }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body px-4">
                                                            <input type="hidden" id="edit_id" name="id">
                                                            <div class="row gy-2">
                                                                <div class="col-xl-12">
                                                                    <label for="edit_valor_checkout" class="form-label">Valor</label>
                                                                    <input type="text" class="form-control" id="edit_valor_checkout" name="valor_checkout" placeholder="Valor">
                                                                </div>
                                                                <div class="col-xl-12">
                                                                    <label for="edit_obrigado_page" class="form-label">Página de Obrigado</label>
                                                                    <input type="text" class="form-control" id="edit_obrigado_page" name="obrigado_page" placeholder="URL da Página de Obrigado">
                                                                </div>
                                                                <div class="col-xl-12">
                                                                    <label for="status" class="form-label">Status</label>
                                                                    <select class="form-control" id="edit_status" name="status" required>
                                                                        <option value="1">Ativo</option>
                                                                        <option value="0">Inativo</option>
                                                                    </select>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                                                            <button type="submit" class="btn btn-primary">Salvar alterações</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        @else
                                        <tr>
                                            <td colspan='5'>Nenhum produto encontrado</td>
                                        </tr>
                                        @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!--
                @if($pedidos)
                <div class="col-xl-12">
                    <div class="card card-raised">
                        <div class="card-header justify-content-between">
                            <div class="card-title">Pedidos</div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table text-nowrap table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>Email</th>
                                            <th>Telefone</th>
                                            <th>Status</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pedidos as $pedido)
                                        @if(isset($pedido))
                                        <tr>
                                            <th>
                                                <div class='d-flex align-items-center'>
                                                    <span class='avatar avatar-xs me-2 online avatar-rounded'>
                                                        <img src='{{ $produto->logo_produto }}' alt='img'>
                                                    </span>{{ $pedido->nome }}
                                                </div>
                                            </th>
                                            <td><span class='badge {{ $produto->ativo ? "bg-success" : "bg-light text-dark" }}'>{{ $produto->ativo ? 'Ativo' : 'Inativo' }}</span></td>
                                            <td>{{ $pedido->email }}</td>
                                            <td>{{ $pedido->phone }}</td>
                                            <td>
                                                @if($pedido->status == "QRCODE_GERADO")
                                                <span class='badge bg-warning-transparent }}'>{{ $pedido->status }}</span>
                                                @elseif($pedido->status == "PAGO")
                                                <span class='badge bg-success-transparent }}'>{{ $pedido->status }}</span>
                                                @else
                                                <span class='badge bg-danger-transparent }}'>{{ $pedido->status }}</span>
                                                @endif
                                            </td>
                                            <td>Ações</td>
                                        </tr>


                                        <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h6 class="modal-title" id="editModalLabel">Editar Checkout</h6>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form id="editForm" method="POST" action="{{ route('profile.checkout.edit', $produto->id) }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body px-4">
                                                            <input type="hidden" id="edit_id" name="id">
                                                            <div class="row gy-2">
                                                                <div class="col-xl-12">
                                                                    <label for="edit_valor_checkout" class="form-label">Valor</label>
                                                                    <input type="text" class="form-control" id="edit_valor_checkout" name="valor_checkout" placeholder="Valor">
                                                                </div>
                                                                <div class="col-xl-12">
                                                                    <label for="edit_obrigado_page" class="form-label">Página de Obrigado</label>
                                                                    <input type="text" class="form-control" id="edit_obrigado_page" name="obrigado_page" placeholder="URL da Página de Obrigado">
                                                                </div>
                                                                <div class="col-xl-12">
                                                                    <label for="status" class="form-label">Status</label>
                                                                    <select class="form-control" id="edit_status" name="status" required>
                                                                        <option value="1">Ativo</option>
                                                                        <option value="0">Inativo</option>
                                                                    </select>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                                                            <button type="submit" class="btn btn-primary">Salvar alterações</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        @else
                                        <tr>
                                            <td colspan='5'>Nenhum produto encontrado</td>
                                        </tr>
                                        @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            -->
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#checkoutForm').on('submit', function(e) {
                e.preventDefault(); // Evita o refresh

                var formData = new FormData(this);
                $('.text-danger').remove(); // Remove mensagens de erro antigas

                $.ajax({
                    url: $(this).attr('action'),
                    type: $(this).attr('method'),
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        alert('Checkout criado com sucesso!');
                        location.reload(); // Recarrega a página após sucesso
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            for (var field in errors) {
                                $('[name="' + field + '"]').after('<span class="text-danger">' + errors[field][0] + '</span>');
                            }
                        }
                    }
                });
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Seleciona todos os botões de edição
            document.querySelectorAll(".btn-edit").forEach(button => {
                button.addEventListener("click", function() {
                    // Obtém os atributos data-* do botão clicado
                    let id = this.getAttribute("data-id");
                    let name = this.getAttribute("data-name");
                    let value = this.getAttribute("data-value");
                    let thankYouPage = this.getAttribute("data-thank-you-page");
                    let status = this.getAttribute("data-status");

                    // Preenche os campos da modal
                    document.getElementById("edit_id").value = id;
                    document.getElementById("edit_valor_checkout").value = value;
                    document.getElementById("edit_obrigado_page").value = thankYouPage;
                    document.getElementById("edit_status").value = status;

                    // Atualiza a action do formulário com o ID correto
                    let form = document.getElementById("editForm");
                    form.action = form.action.replace(/\/\d+$/, '/' + id);
                });
            });
        });
    </script>

    <script>
        function getVendas(pedidos) {
            console.log(pedidos);
        }
    </script>
</x-app-layout>
