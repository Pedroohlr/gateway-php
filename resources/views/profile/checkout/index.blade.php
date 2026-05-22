<x-app-layout :route="'Check-out'">

    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="row">

                <div class="mb-3 bg-transparent"
                        style="display:flex;align-items:center;justify-content:space-between;">
                        <h5 class="display-5 card-title">Produtos</h5>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#addproduto">
                            <button type="button" class="btn btn-primary ">
                                <i class="bi bi-plus-circle"></i> Adcionar produto
                            </button>
                        </a>
                    </div>

                <div class="card card-raised">
                    <div class="card-body">
                        <table class="table " id="table-produtos">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Preço</th>
                                    <th>Status</th>
                                    <th></th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($checkouts as $checkout)
                                    <tr>
                                        <td >{{ $checkout->produto_name }}</td>
                                        <td class="w-12">R$ {{ number_format($checkout->produto_valor, '2', ',', '.') }}</td>
                                        <td class="w-6">
                                            @if ($checkout->status)
                                                <span class="badge text-bg-success">Ativo</span>
                                            @else
                                                <span class="badge text-bg-warning">Inativo</span>
                                            @endif
                                        </td>
                                        <td class="w-6">

                                            <div class="dropdown">
                                                <button class="icon-navbar btn btn-sm btn-icon dropdown-toggle "
                                                    id="dropdownMenuProduto-{{ $checkout->id }}" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false"><i
                                                        class="text-sm fa-solid fa-ellipsis-vertical"></i></button>
                                                <ul class="mt-3 dropdown-menu dropdown-menu-end"
                                                    aria-labelledby="dropdownMenuProduto-{{ $checkout->id }}">
                                                    <li>
                                                        <a class="text-sm dropdown-item"
                                                            href="/produtos/visualizar/{{ $checkout->id_unico }}#links">
                                                            <i class="fa-solid fa-link color-gateway"></i>&nbsp;
                                                            <div class="me-3 ">Ver links</div>
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a class="text-sm dropdown-item"
                                                            href="/produtos/visualizar/{{ $checkout->id_unico }}#orders">
                                                            <i
                                                                class="fa-solid fa-cart-arrow-down color-gateway"></i>&nbsp;
                                                            <div class="me-3">Pedidos
                                                                <span class="badge badge-light">
                                                                    {{ $checkout->orders->count() }}
                                                                </span>
                                                            </div>

                                                        </a>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider" />
                                                    </li>
                                                    <li>
                                                        <a class="text-sm dropdown-item"
                                                            href="{{ route('profile.checkout.produto', ['id' => $checkout->id_unico]) }}">
                                                            <i class="fa-solid fa-pencil color-gateway"></i>&nbsp;
                                                            <div class="me-3">Editar</div>
                                                        </a>
                                                    </li>
                                                    <li>

                                                        <a class="text-sm dropdown-item" href="#!"  data-bs-toggle="modal" data-bs-target="#editModal-{{$checkout->id}}">
                                                            <i class="fa-solid fa-trash color-gateway"></i>&nbsp;
                                                            <div class="me-3">Excluir</div>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>

                                            <!-- Modal -->
                                            <div class="modal fade" id="editModal-{{$checkout->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editModal-{{$checkout->id}}Label" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="editModal-{{$checkout->id}}Label">Excluir produto</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                    <h6>
                                                        Você tem certeza que deseja excluir o produto:
                                                        <p class="font-bold text-danger">{{ $checkout->produto_name }}?</p>
                                                    </h6>
                                                    </div>
                                                    <div class="gap-2 modal-footer">
                                                    <button type="button" class="btn btn-sm btn-dark" data-bs-dismiss="modal">Cancelar</button>
                                                    <form method="POST" action="{{ route('profile.checkout.delete', ['id'=> $checkout->id]) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        
                                                    <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                                                    </form>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addproduto" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Novo Produto</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('profile.checkout.create') }}" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="px-4 modal-body">
                        <div class="row gy-2">
                            <div class="col-xl-12">
                                <label for="produto_name" class="form-label">Nome do Produto</label>
                                <input type="text" class="form-control @error('produto_name') is-invalid @enderror"
                                    name="produto_name" value="{{ old('produto_name') }}" required>
                                @error('produto_name')
                                    <span style="color: red;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-xl-12">
                                <label for="produto_valor" class="form-label">Preço do Produto</label>
                                <input type="text" role="0,01" class="form-control @error('produto_valor') is-invalid @enderror"
                                    name="produto_valor" value="{{ old('produto_valor') }}" required>
                                @error('valor_checkout')
                                    <span style="color: red;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-xl-12">
                                <label for="produto_descricao" class="form-label">Descriçao</label>
                                <textarea class="form-control @error('produto_descricao') is-invalid @enderror" name="produto_descricao"
                                    value="{{ old('produto_descricao', 'unico') }}" required></textarea>
                                @error('produto_descricao')
                                    <span style="color: red;">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-xl-12">
                                <label for="produto_tipo" class="form-label">Tipo do Produto</label>
                                <select class="form-control @error('produto_tipo') is-invalid @enderror"
                                    name="produto_tipo" value="{{ old('produto_tipo', 'info') }}" required>
                                    <option value="info" selected>Info Produto</option>
                                    <option value="fisico">Produto Físico</option>
                                </select>
                                @error('status')
                                    <span style="color: red;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-xl-12">
                                <label for="produto_tipo_cob" class="form-label">Tipo do Cobrança</label>
                                <select class="form-control @error('produto_tipo_cob') is-invalid @enderror"
                                    name="produto_tipo_cob" value="{{ old('produto_tipo_cob', 'unico') }}" required>
                                    <option value="unico" selected>Único</option>
                                    <option value="recorrente">Recorrente</option>
                                </select>
                                @error('produto_tipo_cob')
                                    <span style="color: red;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-xl-12">
                                <div class="mb-0 form-group">
                                    <div class="gap-2 d-flex">
                                        <input type="button" value="Cancelar" data-bs-dismiss="modal"
                                            class="mb-0 btn btn-outline-dark w-100">
                                        <input type="submit" value="Cadastrar" class="mb-0 btn btn-primary w-100">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            $("#table-produtos").DataTable({
                responsive: true,
                info:false,
                lengthChange: false,
                ordering: false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
                },
                dom: '<"top"f>rt<"bottom"p><"clear">',
                initComplete: function() {
                    // Muda o placeholder do input de busca
                    $('#table-produtos_filter input[type="search"]').attr('placeholder', 'Pesquisar');
                }
            });
        });
    </script>
</x-app-layout>
