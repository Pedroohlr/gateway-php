
<x-app-layout :route="'Check-out'">

    <div class="main-content app-content">
        <div class="container-fluid">

            <form id="form-checkout-completo" method="POST" action="{{ route('profile.checkout.produto.editar', ['id' => $checkout->id ]) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="justify-between pr-0 mb-3 w-100 d-flex">
                        <button type="button" class="btn btn-light btn-wave waves-effect waves-light" onclick="window.location.href='/produtos'">
                            <i class="fa-solid fa-chevron-left"></i> Voltar
                        </button>
                        <button type="submit" class="btn btn-primary btn-wave waves-effect waves-light">
                            <i class="bi bi-save"></i> Salvar alterações
                        </button>
                    </div>
                    <div class="p-2 card card-raised">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" data-bs-target="#geral"
                                type="button" role="tab" aria-controls="geral" aria-selected="true">Geral</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" data-bs-target="#checkouts"
                                type="button" role="tab" aria-controls="checkouts" aria-selected="true">Checkouts</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" data-bs-target="#orderbumps"
                                type="button" role="tab" aria-controls="orderbumps" aria-selected="true">Order bump</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" data-bs-target="#links"
                                type="button" role="tab" aria-controls="links" aria-selected="true">Links</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" data-bs-target="#ads"
                                type="button" role="tab" aria-controls="ads" aria-selected="true">ADS</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" data-bs-target="#orders"
                                type="button" role="tab" aria-controls="orders" aria-selected="true">Pedidos</a>
                            </li>
                        </ul>


                        <!-- Tab panes -->
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="geral" role="tabpanel" aria-labelledby="geral-tab">
                                <div class="container text-center">
                                    <div class="row align-items-start">
                                        <div class="col text-start">
                                            <div class="mb-3 col-xl-12">
                                                <label for="produto_name" class="form-label">Nome do Produto</label>
                                                <input autofocus type="text" class="form-control @error('produto_name') is-invalid @enderror" name="produto_name" value="{{ $checkout->produto_name }}" >
                                                @error('produto_name')
                                                <span style="color: red;">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="mb-3 col-xl-12">
                                                <label for="produto_valor" class="form-label">Valor do Produto</label>
                                                <input autofocus type="text" class="form-control @error('produto_valor') is-invalid @enderror" name="produto_valor" value="{{ $checkout->produto_valor }}" >
                                                @error('produto_valor')
                                                <span style="color: red;">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="mb-3 col text-start">
                                                <div class="col-xl-12">
                                                    <label for="produto_descricao" class="form-label">Descrição do Produto</label>
                                                    <textarea  class="form-control @error('produto_descricao') is-invalid @enderror" name="produto_descricao" >{{ $checkout->produto_descricao }}</textarea>
                                                    @error('produto_descricao')
                                                    <span style="color: red;">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="mb-3 col text-start">
                                                <div class="form-group col-xl-12">
                                                    <label class="control-label choice" for="produto_categoria">Categoria Produto</label>
                                                    <select class="form-control choice input-validation-error" name="produto_categoria" id="produto_categoria">
                                                        <option value="0"  {{ $checkout->produto_categoria == "0" ? "selected" : "" }}class="dropdown-item border-radius-md" selected="selected">Selecione a Categoria</option>
                                                        <option value="1"> {{ $checkout->produto_categoria == "1" ? "selected" : "" }}Administração e Negócios</option>
                                                        <option value="2"> {{ $checkout->produto_categoria == "2" ? "selected" : "" }}Animais de Estimação</option>
                                                        <option value="3"> {{ $checkout->produto_categoria == "3" ? "selected" : "" }}Arquitetura e Engenharia</option>
                                                        <option value="4"> {{ $checkout->produto_categoria == "4" ? "selected" : "" }}Artes e Música</option>
                                                        <option value="5"> {{ $checkout->produto_categoria == "5" ? "selected" : "" }}Auto-ajuda e Desenvolvimento Pessoal</option>
                                                        <option value="6"> {{ $checkout->produto_categoria == "6" ? "selected" : "" }}Automóveis</option>
                                                        <option value="7"> {{ $checkout->produto_categoria == "7" ? "selected" : "" }}Blogs e Redes Sociais</option>
                                                        <option value="8"> {{ $checkout->produto_categoria == "8" ? "selected" : "" }}Casa e Jardinagem</option>
                                                        <option value="9"> {{ $checkout->produto_categoria == "9" ? "selected" : "" }}Culinária, Gastronomia, Receitas</option>
                                                        <option value="10" {{ $checkout->produto_categoria == "10" ? "selected" : "" }}>Design e Templates PSD, PPT ou HTML</option>
                                                        <option value="11" {{ $checkout->produto_categoria == "11" ? "selected" : "" }}>Edição de Áudio, Vídeo ou Imagens</option>
                                                        <option value="12" {{ $checkout->produto_categoria == "12" ? "selected" : "" }}>Educacional, Cursos Técnicos e Profissionalizantes</option>
                                                        <option value="13" {{ $checkout->produto_categoria == "13" ? "selected" : "" }}>Entretenimento, Lazer e Diversão</option>
                                                        <option value="14" {{ $checkout->produto_categoria == "14" ? "selected" : "" }}>Esportes e Fitness</option>
                                                        <option value="15" {{ $checkout->produto_categoria == "15" ? "selected" : "" }}>Filmes e Cinema</option>
                                                        <option value="16" {{ $checkout->produto_categoria == "16" ? "selected" : "" }}>Finanças</option>
                                                        <option value="17" {{ $checkout->produto_categoria == "17" ? "selected" : "" }}>Geral</option>
                                                        <option value="18" {{ $checkout->produto_categoria == "18" ? "selected" : "" }}>Histórias em Quadrinhos</option>
                                                        <option value="19" {{ $checkout->produto_categoria == "19" ? "selected" : "" }}>Idiomas</option>
                                                        <option value="20" {{ $checkout->produto_categoria == "20" ? "selected" : "" }}>Informática</option>
                                                        <option value="21" {{ $checkout->produto_categoria == "21" ? "selected" : "" }}>Internet Marketing</option>
                                                        <option value="22" {{ $checkout->produto_categoria == "22" ? "selected" : "" }}>Investimentos e Finanças</option>
                                                        <option value="23" {{ $checkout->produto_categoria == "23" ? "selected" : "" }}>Jogos de Cartas, Poker, Loterias</option>
                                                        <option value="24" {{ $checkout->produto_categoria == "24" ? "selected" : "" }}>Jogos de Computador, Jogos Online</option>
                                                        <option value="25" {{ $checkout->produto_categoria == "25" ? "selected" : "" }}>Jurídico</option>
                                                        <option value="26" {{ $checkout->produto_categoria == "26" ? "selected" : "" }}>Literatura e Poesia</option>
                                                        <option value="27" {{ $checkout->produto_categoria == "27" ? "selected" : "" }}>Marketing de Rede</option>
                                                        <option value="28" {{ $checkout->produto_categoria == "28" ? "selected" : "" }}>Marketing e Comunicação</option>
                                                        <option value="29" {{ $checkout->produto_categoria == "29" ? "selected" : "" }}>Plantas, Meio Ambiente</option>
                                                        <option value="30" {{ $checkout->produto_categoria == "30" ? "selected" : "" }}>Moda e vestuário</option>
                                                        <option value="31" {{ $checkout->produto_categoria == "31" ? "selected" : "" }}>Música, Bandas e Shows</option>
                                                        <option value="32" {{ $checkout->produto_categoria == "32" ? "selected" : "" }}>Paquera, Sedução e Relacionamentos</option>
                                                        <option value="33" {{ $checkout->produto_categoria == "33" ? "selected" : "" }}>Pessoas com deficiência</option>
                                                        <option value="34" {{ $checkout->produto_categoria == "34" ? "selected" : "" }}>Plugins, Widgets e Extensões</option>
                                                        <option value="35" {{ $checkout->produto_categoria == "35" ? "selected" : "" }}>Produtividade e Organização Pessoal</option>
                                                        <option value="36" {{ $checkout->produto_categoria == "36" ? "selected" : "" }}>Produtos infantis</option>
                                                        <option value="37" {{ $checkout->produto_categoria == "37" ? "selected" : "" }}>Relatórios, Artigos e Pesquisas</option>
                                                        <option value="38" {{ $checkout->produto_categoria == "38" ? "selected" : "" }}>Religião e Crenças</option>
                                                        <option value="39" {{ $checkout->produto_categoria == "39" ? "selected" : "" }}>Renda Extra</option>
                                                        <option value="40" {{ $checkout->produto_categoria == "40" ? "selected" : "" }}>Romances, Dramas, Estórias e Contos</option>
                                                        <option value="41" {{ $checkout->produto_categoria == "41" ? "selected" : "" }}>Saúde, Bem-estar e Beleza</option>
                                                        <option value="42" {{ $checkout->produto_categoria == "42" ? "selected" : "" }}>Scripts</option>
                                                        <option value="43" {{ $checkout->produto_categoria == "43" ? "selected" : "" }}>Segurança do Trabalho</option>
                                                        <option value="44" {{ $checkout->produto_categoria == "44" ? "selected" : "" }}>Sexologia e Sexualidade</option>
                                                        <option value="45" {{ $checkout->produto_categoria == "45" ? "selected" : "" }}>Snippets (Trechos de Vídeo)</option>
                                                        <option value="46" {{ $checkout->produto_categoria == "46" ? "selected" : "" }}>Turismo</option>
                                                    </select>
                                                    @error('produto_categoria')
                                                        <span style="color: red;">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="mb-3 col-xl-12">
                                                <label for="produto_tipo" class="form-label">Tipo do Produto</label>
                                                <select class="form-control @error('produto_tipo') is-invalid @enderror" name="produto_tipo" value="{{ $checkout->produto_tipo }}" readonly>
                                                    <option value="info" {{ $checkout->produto_tipo == 'info' ? 'selected' : '' }}>Info Produto</option>
                                                    <option value="fisico" {{ $checkout->produto_tipo == 'fisico' ? 'selected' : '' }}>Produto Físico</option>
                                                </select>
                                                @error('produto_tipo')
                                                <span style="color: red;">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="mb-3 col-xl-12">
                                                <label for="produto_tipo_cob" class="form-label">Tipo do Cobrança</label>
                                                <select class="form-control @error('produto_tipo_cob') is-invalid @enderror" name="produto_tipo_cob" value="{{ $checkout->produto_tipo_cob }}" readonly>
                                                    <option value="unico">Único</option>
                                                    <option value="recorrente">Recorrente</option>
                                                </select>
                                                @error('produto_tipo_cob')
                                                <span style="color: red;">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="mb-3 col-xl-12">
                                                <label for="url_pagina_vendas" class="form-label">Url da Página de Vendas</label>
                                                <input autofocus type="text" class="form-control @error('url_pagina_vendas') is-invalid @enderror" name="url_pagina_vendas" value="{{ $checkout->url_pagina_vendas }}">
                                                @error('url_pagina_vendas')
                                                <span style="color: red;">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col text-start">

                                            <div class="mb-1 col-xl-12">
                                                <x-image-upload id="produto_image" name="produto_image" label="Imagem do produto" :height="'150px'" :value="$checkout->produto_image" />
                                                @error('produto_image')
                                                <span style="color: red;">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="mb-3 col-xl-12">
                                                <label for="periodo_garantia" class="form-label">Período de Garantia (dias)</label>
                                                <input  type="number" value="7" class="form-control @error('periodo_garantia') is-invalid @enderror" name="periodo_garantia" value="{{ $checkout->periodo_garantia }}" >
                                                @error('periodo_garantia')
                                                <span style="color: red;">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="mb-3 col-xl-12">
                                                <label for="whatsapp_suporte" class="form-label">Whatsapp de Suporte</label>
                                                <input  type="text" class="form-control @error('whatsapp_suporte') is-invalid @enderror" name="whatsapp_suporte" value="{{ $checkout->whatsapp_suporte }}" >
                                                @error('whatsapp_suporte')
                                                <span style="color: red;">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="mb-3 col-xl-12">
                                                <label for="email_suporte" class="form-label">Email de Suporte</label>
                                                <input  type="text" class="form-control @error('email_suporte') is-invalid @enderror" name="email_suporte" value="{{ $checkout->email_suporte }}" >
                                                @error('email_suporte')
                                                <span style="color: red;">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="mb-3 col-xl-12">
                                                <label for="descricao_extra" class="form-label"> Qual o 'entregável' desse produto e como é feita essa entrega</label>
                                                <textarea  class="form-control @error('descricao_extra') is-invalid @enderror" name="descricao_extra" >{{ $checkout->descricao_extra }}</textarea>
                                                @error('descricao_extra')
                                                <span style="color: red;">{{ $message }}</span>
                                                @enderror
                                            </div>

                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div class="tab-pane fade" id="checkouts" role="tabpanel" aria-labelledby="checkouts-tab">
                            @include('profile.checkout.components.checkout', ['checkout' => $checkout])
                        </div>

                        <div class="tab-pane fade" id="orderbumps" role="tabpanel" aria-labelledby="orderbumps-tab">
                            <div class="card">
                                <div class="flex justify-between align-middle card-header">
                                  Order Bumps
                                  <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addOrderbump"><i class="fa-solid fa-plus"></i>&nbsp;Adcionar</button>
                                </div>
                                <div class="card-body">
                                    <table class="table">
                                        <thead>
                                          <tr>
                                            <th scope="col">Image</th>
                                            <th scope="col">Nome</th>
                                            <th scope="col">Descrição</th>
                                            <th scope="col">Valor de</th>
                                            <th scope="col">Valor por</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Ações</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                           {{--  {{dd($checkout->bumps)}} --}}
                                           @foreach($checkout->bumps as $bump)
                                           <tr>
                                               <td><img style="width:35px;height:35px;object-fit:cover;" src="{{ $bump->image }}"></td>
                                               <td>{{ $bump->nome }}</td>
                                               <td>{{ $bump->descricao }}</td>
                                               <td>{{ "R$ ".number_format($bump->valor_de, '2',',','.') }}</td>
                                               <td>{{ "R$ ".number_format($bump->valor_por, '2',',','.') }}</td>
                                               <td>
                                                   @if($bump->ativo == 1)
                                                       <span class="badge text-bg-success">Ativo</span>
                                                   @else
                                                       <span class="badge text-bg-dark">Inativo</span>
                                                   @endif
                                               </td>
                                               <td>
                                                   <button type="button" class="btn btn-sm btn-info btn-edit-orderbump"
                                                       data-id="{{ $bump->id }}"
                                                       data-nome="{{ $bump->nome }}"
                                                       data-descricao="{{ $bump->descricao }}"
                                                       data-valor-de="{{ $bump->valor_de }}"
                                                       data-valor-por="{{ $bump->valor_por }}"
                                                       data-ativo="{{ $bump->ativo }}"
                                                       data-image="{{ $bump->image }}"
                                                       data-bs-toggle="modal"
                                                       data-bs-target="#editOrderbump">
                                                       <i class="fa-solid fa-pencil"></i>
                                                   </button>

                                                   <button type="button" class="btn btn-sm btn-danger btn-remove-orderbump"
                                                       data-id="{{ $bump->id }}"
                                                       data-nome="{{ $bump->nome }}"
                                                       data-bs-toggle="modal"
                                                       data-bs-target="#removeOrderbump">
                                                       <i class="fa-solid fa-trash"></i>
                                                   </button>
                                               </td>
                                           </tr>
                                           @endforeach
                                        </tbody>
                                      </table>
                                    </div>
                                </div>
                        </div>
                        <div class="tab-pane fade" id="links" role="tabpanel" aria-labelledby="links-tab">
                                <table class="table">
                                    <thead>
                                      <tr>
                                        <th scope="col">Produto</th>
                                        <th scope="col">Tipo</th>
                                        <th scope="col">Cobrança</th>
                                        <th scope="col">Valor</th>
                                        <th scope="col">Link</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <tr>
                                        <th scope="row">{{ $checkout->produto_name }}</th>
                                        <td>
                                            <span class="badge text-bg-primary">
                                                {{ $checkout->produto_tipo }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge text-bg-primary">
                                                {{ $checkout->produto_tipo_cob }}
                                            </span>
                                        </td>
                                        <td>R$ {{ number_format($checkout->produto_valor, '2',',','.') }}</td>
                                        <td>
                                            <input id="link-checkout" value="{{ env('APP_URL')."/checkout/produto/v1/".$checkout->id_unico }}" hidden>
                                            <button type="button" class="btn btn-primary btn-sm" onclick="copiarUrl()"><i class="fa-solid fa-copy"></i></button>
                                            <a href="{{ env('APP_URL')."/checkout/produto/v1/".$checkout->id_unico }}" target="_blank">
                                                <button type="button" class="btn btn-primary btn-sm">Abrir</button>
                                            </a>
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                            </div>
                            <div class="tab-pane fade" id="ads" role="tabpanel" aria-labelledby="ads-tab">
                                <div class="mb-3 row">
                                    <div class="mb-3 text-center col-md-4">
                                        <div class="mb-3 d-flex justify-content-center align-items-center col-xl-12">
                                            <img class="text-center" src="/build/assets/images/meta_ads.png" width="145px" height="auto">
                                        </div>
                                        <label for="checkout_ads_meta" class="form-label">Pixel ID</label>
                                        <input  type="text" class="form-control @error('checkout_ads_meta') is-invalid @enderror" value="{{$checkout->checkout_ads_meta}}" name="checkout_ads_meta" >
                                        @error('checkout_ads_meta')
                                        <span style="color: red;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3 text-center col-md-4">
                                        <div class="mb-3 d-flex justify-content-center align-items-center col-xl-12">
                                            <img class="text-center" src="/build/assets/images/google_ads.png" width="65px" height="auto">
                                        </div>
                                        <label for="checkout_ads_google" class="form-label">Google ID</label>
                                        <input  type="text" class="form-control @error('checkout_ads_google') is-invalid @enderror" name="checkout_ads_google" value="{{$checkout->checkout_ads_google}}" >
                                        @error('checkout_ads_google')
                                        <span style="color: red;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3 text-center col-md-4">
                                        <div class="mb-3 d-flex justify-content-center align-items-center col-xl-12">
                                            <img class="text-center" src="/build/assets/images/tiktok_ads.png" width="80px" height="auto">
                                        </div>
                                        <label for="checkout_ads_tiktok" class="form-label">Tiktok ID</label>
                                        <input  type="text" class="form-control @error('checkout_ads_tiktok') is-invalid @enderror" name="checkout_ads_tiktok"value="{{$checkout->checkout_ads_tiktok}}" >
                                        @error('checkout_ads_tiktok')
                                        <span style="color: red;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="orders" role="tabpanel" aria-labelledby="orders-tab">
                            @include('profile.checkout.components.orders', ['checkout' => $checkout])
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <form id="form-orderbump" class="row" method="POST" action="{{ route('checkout.orderbumps.create', [ 'id' => $checkout->id ]) }}" enctype="multipart/form-data">
        @csrf
        <div class="modal fade" id="addOrderbump" tabindex="-1" data-bs-backdrop="static" aria-labelledby="addOrderbumpLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="addOrderbumpLabel">Adcionar Order bump</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body row">
                        <div class="mb-3 col-xl-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" name="active" id="active" checked>
                                <label class="form-check-label" for="active">Ativo</label>
                              </div>
                        </div>
                        <div class="col-xl-12">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" class="form-control @error('nome') is-invalid @enderror" name="nome" value="" required>
                            @error('nome')
                            <span style="color: red;">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-xl-12">
                            <label for="descricao" class="form-label">Descrição</label>
                            <textarea style="min-height: 80px" class="form-control @error('descricao') is-invalid @enderror" name="descricao" required></textarea>
                            @error('descricao')
                            <span style="color: red;">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-xl-6">
                            <label for="valor_de" class="form-label">Valor Normal</label>
                            <input type="text" class="form-control @error('valor_de') is-invalid @enderror" name="valor_de" value="" required>
                            @error('valor_de')
                            <span style="color: red;">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-xl-6">
                            <label for="valor_por" class="form-label">Valor Promoção</label>
                            <input type="text" class="form-control @error('valor_por') is-invalid @enderror" name="valor_por" value="" required>
                            @error('valor_por')
                            <span style="color: red;">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-12 col-md-12">
                        <x-image-upload id="image" name="image" label="Imagem" :height="'150px'" :value="null" />
                        </div>

                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>


<form id="form-orderbump" class="row" method="POST" action="{{ route('checkout.orderbumps.create', [ 'id' => $checkout->id ]) }}" enctype="multipart/form-data">
    @csrf
    <div class="modal fade" id="addOrderbump" tabindex="-1" data-bs-backdrop="static" aria-labelledby="addOrderbumpLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addOrderbumpLabel">Adcionar Order bump</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row">
                    <div class="mb-3 col-xl-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" name="active" id="active" checked>
                            <label class="form-check-label" for="active">Ativo</label>
                          </div>
                    </div>
                    <div class="col-xl-12">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" class="form-control @error('nome') is-invalid @enderror" name="nome" value="" required>
                        @error('nome')
                        <span style="color: red;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-xl-12">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea style="min-height: 80px" class="form-control @error('descricao') is-invalid @enderror" name="descricao" required></textarea>
                        @error('descricao')
                        <span style="color: red;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-xl-6">
                        <label for="valor_de" class="form-label">Valor Normal</label>
                        <input type="text" class="form-control @error('valor_de') is-invalid @enderror" name="valor_de" value="" required>
                        @error('valor_de')
                        <span style="color: red;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-xl-6">
                        <label for="valor_por" class="form-label">Valor Promoção</label>
                        <input type="text" class="form-control @error('valor_por') is-invalid @enderror" name="valor_por" value="" required>
                        @error('valor_por')
                        <span style="color: red;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-12 col-md-12">
                    <x-image-upload id="image" name="image" label="Imagem" :height="'150px'" :value="null" />
                    </div>

                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</form>


<form id="form-orderbump-edit" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="modal fade" id="editOrderbump" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Editar Order bump</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body row">
                    <input type="hidden" id="edit-id" name="id">
                    <div class="mb-3 col-xl-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="edit-active" name="active">
                            <label class="form-check-label" for="edit-active">Ativo</label>
                        </div>
                    </div>
                    <div class="col-xl-12">
                        <label class="form-label">Nome</label>
                        <input type="text" class="form-control" name="nome" id="edit-nome" required>
                    </div>
                    <div class="col-xl-12">
                        <label class="form-label">Descrição</label>
                        <textarea style="min-height: 80px" class="form-control" name="descricao" id="edit-descricao"></textarea>
                    </div>
                    <div class="col-xl-6">
                        <label class="form-label">Valor Normal</label>
                        <input type="text" class="form-control" name="valor_de" id="edit-valor_de" required>
                    </div>
                    <div class="col-xl-6">
                        <label class="form-label">Valor Promoção</label>
                        <input type="text" class="form-control" name="valor_por" id="edit-valor_por" required>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </div>
        </div>
    </div>
</form>

<form id="form-orderbump-remove" method="POST">
    @csrf
    @method('DELETE')
    <div class="modal fade" id="removeOrderbump" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Remover Order bump</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <h6>Deseja remover o Order bump <span class="text-danger" id="remove-nome"></span>?</h6>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Remover</button>
                </div>
            </div>
        </div>
    </div>
</form>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editButtons = document.querySelectorAll('.btn-edit-orderbump');
        const removeButtons = document.querySelectorAll('.btn-remove-orderbump');

        editButtons.forEach(button => {
            button.addEventListener('click', () => {
                const id = button.dataset.id;
                const nome = button.dataset.nome;
                const descricao = button.dataset.descricao;
                const valorDe = button.dataset.valorDe;
                const valorPor = button.dataset.valorPor;
                const ativo = button.dataset.ativo;
                const image = button.dataset.image;

                document.querySelector('#form-orderbump-edit').action = `/produtos/orderbumps/edit/${id}`;
                document.querySelector('#edit-id').value = id;
                document.querySelector('#edit-nome').value = nome;
                document.querySelector('#edit-descricao').value = descricao;
                document.querySelector('#edit-valor_de').value = valorDe;
                document.querySelector('#edit-valor_por').value = valorPor;
                document.querySelector('#edit-active').checked = ativo == "1";

                // Atualize preview da imagem se necessário
            });
        });

        removeButtons.forEach(button => {
            button.addEventListener('click', () => {
                const id = button.dataset.id;
                const nome = button.dataset.nome;

                document.querySelector('#form-orderbump-remove').action = `/produtos/orderbumps/remove/${id}`;
                document.querySelector('#remove-nome').textContent = nome;
            });
        });
    });
</script>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let backgroundColor = document.getElementById('background_color');
        let contadorBG = document.getElementById("countdown_background");
        let contadorDescription = document.getElementById('countdown_description');
        let contadorText = document.getElementById('countdown_text');
        let contadorIcon = document.getElementById('countdown_icon');
        let headerContainer = document.getElementById('headerContainer');
        let headerImage1 = document.getElementById("header_image1");
        let headerImage2 = document.getElementById("header_image2");
        let topbarBackground = document.getElementById('topbar_background');
        let topbarText = document.getElementById('topbar_text');

        let guideCurrent = document.querySelector('.guide.ativo .step-number');

        document.querySelector('[name="checkout_color"]').addEventListener('input', (e) => {
            backgroundColor.style.background = e.target.value;
        });

        document.querySelector('[name="checkout_color_card"]').addEventListener('input', (e) => {
            document.querySelectorAll('.card-bg').forEach(el => {
                el.style.background = e.target.value;
            });
        });


        document.querySelector('[name="checkout_timer_tempo"]').addEventListener('input', (e) => {
            let time = e.target.value;
            if(Number(time) < 10){
                time = '0'+time;
            }
            contadorText.innerText = time+':00';
        });

        document.querySelector('[name="checkout_timer_cor_fundo"]').addEventListener('input', (e) => {
            contadorBG.style.background = e.target.value;
        });

        document.querySelector('[name="checkout_timer_cor_texto"]').addEventListener('input', (e) => {
            contadorText.style.color = e.target.value;
            contadorIcon.style.color = e.target.value;
            contadorDescription.style.color = e.target.value;
        });

        document.querySelector('[name="checkout_timer_texto"]').addEventListener('input', (e) => {
            contadorDescription.innerText = e.target.value;
        });

        document.querySelector('[name="checkout_header_logo"]').addEventListener('input', (e) => {
            const file = e.target.files[0];
            headerImage1.src = URL.createObjectURL(file);
        });

        document.querySelector('[name="checkout_header_logo_active"]').addEventListener('input', (e) => {
            const isChecked = e.target.checked;
            headerImage1.style.display = isChecked ? 'block' : 'none';
            document.querySelector('[name="checkout_header_logo"]').style.display = isChecked ? 'block' : 'none';
        });

        document.querySelector('[name="checkout_header_image"]').addEventListener('input', (e) => {
            const file = e.target.files[0];
            headerImage2.src = URL.createObjectURL(file);

        });

        document.querySelector('[name="checkout_header_image_active"]').addEventListener('input', (e) => {
            const isChecked = e.target.checked;
            headerImage2.style.display = isChecked ? 'block' : 'none';
            document.querySelector('[name="checkout_header_image"]').style.display = isChecked ? 'block' : 'none';
        });

        document.querySelector('[name="checkout_banner_active"]').addEventListener('input', (e) => {
            const isChecked = e.target.checked;
            console.log('isChecked', e.target.checked)
            /* if(isChecked){
                document.getElementById('headerContainer').style.background = "url('{{ $checkout->checkout_banner }}')"
            } */
            headerContainer.style.background = isChecked ? "url('{{ $checkout->checkout_banner }}')" : 'transparent';
        });


        document.querySelector('[name="checkout_banner"]').addEventListener('input', (e) => {
            const file = e.target.files[0];
            headerContainer.style.background = `url(${URL.createObjectURL(file)})`;
        });

        document.querySelector('[name="checkout_banner"]').addEventListener('input', (e) => {
            const file = e.target.files[0];
            headerContainer.style.background = `url(${URL.createObjectURL(file)})`;
        });

        document.querySelector('[name="checkout_topbar_text"]').addEventListener('input', (e) => {
            topbarText.innerText = e.target.value;
        });

        document.querySelector('[name="checkout_topbar_text_color"]').addEventListener('input', (e) => {
            topbarText.style.color = e.target.value;
        });

        document.querySelector('[name="checkout_topbar_color"]').addEventListener('input', (e) => {
            topbarBackground.style.background = e.target.value;
        });

        document.querySelector('[name="checkout_color_default"]').addEventListener('input', (e) => {
            guideCurrent.style.background = e.target.value;
        });

        document.querySelector('[name="checkout_color_default"]').addEventListener('input', (e) => {
            guideCurrent.style.background = e.target.value;
            document.querySelector('.qtde').style.background = e.target.value;

            document.querySelectorAll('.btn-form-checkout').forEach(el => {
                el.style.background = e.target.value;
                el.style.backgroundColor = e.target.value;
            });
        });

        document.querySelector('[name="checkout_timer_active"]').addEventListener('change', (e) => {
            const isChecked = e.target.checked;
            contadorBG.style.display = isChecked ? 'block' : 'none';
            document.querySelectorAll('.timer-scope').forEach(el => {
                el.style.display = isChecked ? 'block' : 'none';
            });
        });

        document.querySelector('[name="checkout_topbar_active"]').addEventListener('change', (e) => {
            const isChecked = e.target.checked;
            topbarBackground.style.display = isChecked ? 'flex' : 'none';
            document.querySelectorAll('.topbar-scope').forEach(el => {
                el.style.display = isChecked ? 'block' : 'none';
            });
        });


    </script>
<script>
    function copiarUrl() {
      var input = document.getElementById("link-checkout");

      // Garante que o valor do input será copiado
      navigator.clipboard.writeText(input.value)
        .then(() => {
          showToast('success', "Link copiado.")
          //alert("Chave Pix copiada!");
        })
        .catch(err => {
          console.error("Erro ao copiar", err);
        });
    }
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const mainTabs = document.querySelectorAll('a[data-bs-toggle="tab"]');

        // Ativa a aba correta com base no hash da URL
        function activateTabsFromHash() {
            const fullHash = window.location.hash;
            const [mainHash, subHash] = fullHash.split('#').filter(Boolean); // remove vazios

            // Ativa aba principal
            const mainTab = document.querySelector('a[data-bs-target="#' + mainHash + '"]');
            if (mainTab) {
                new bootstrap.Tab(mainTab).show();
            }

            // Se aba principal for checkouts
            if (mainHash === 'checkouts') {
                const subId = subHash || 'tema'; // default é "tema" se não houver sub aba
                const subTab = document.querySelector('#checkouts a[data-bs-target="#' + subId + '"]');
                if (subTab) {
                    new bootstrap.Tab(subTab).show();
                }
            }
        }

        // Atualiza a URL ao mudar a aba principal
        mainTabs.forEach(tab => {
            tab.addEventListener('shown.bs.tab', function (e) {
                const target = e.target.getAttribute('data-bs-target').replace('#', '');
                if (target === 'checkouts') {
                    // Se for checkouts, mantemos o sub aba atual ou padrão 'tema'
                    const currentSub = document.querySelector('#checkouts .nav-link.active')?.getAttribute('data-bs-target')?.replace('#', '') || 'tema';
                    history.replaceState(null, null, '#' + target + '#' + currentSub);
                } else {
                    history.replaceState(null, null, '#' + target);
                }
            });
        });

        // Atualiza a URL ao mudar aba interna de checkouts
        const checkoutSubTabs = document.querySelectorAll('#checkouts a[data-bs-toggle="tab"]');
        checkoutSubTabs.forEach(tab => {
            tab.addEventListener('shown.bs.tab', function (e) {
                const subTarget = e.target.getAttribute('data-bs-target').replace('#', '');
                history.replaceState(null, null, '#checkouts#' + subTarget);
            });
        });

        activateTabsFromHash();
    });
    </script>

</x-app-layout>
