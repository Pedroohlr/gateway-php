<x-app-layout :route="'[ADMIN] Gameficação'">
<div class="header mb-3">
    <h3 class="header-title">
        Gameficação
    </h3>
</div>

<div class="row mb-3">
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5 class="card-title">Niveis</h5>
            <button 
            class="btn btn-sm btn-primary"
            data-bs-toggle="modal" 
            data-bs-target="#modalAddNivel">
                <i class="fa-solid fa-plus"></i>
                &nbsp;Adicionar
            </button>
        </div>
        <div class="card-body">
                <table class="table " id="table-admin-niveis">
                <thead>
                    <tr>
                        <th>Icone</th>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th>Depósito (min)</th>
                        <th>Depósito (max)</th>
                        <th></th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($niveis->sortByDesc('created_at') as $nivel)
                    <tr>
                        <td>
                            <img src="{{ $nivel->image }}"
                            class=""
                            alt="Imagem"
                            style="width:25px;height:auto;object-fit: cover;">
                        </td>
                        <td>{{ $nivel->name }}</td>
                        <td>{{ $nivel->desc }}</td>
                        <td>R$ {{ number_format($nivel->min, 2, ',', '.') }}</td>
                        <td>R$ {{ number_format($nivel->max, 2, ',', '.') }}</td>
                        <td>
                            <button 
                            class="btn btn-sm btn-primary"
                            data-bs-toggle="modal" 
                            data-bs-target="#modalEditNivel{{ $nivel->id }}">
                                Editar
                            </button>
                            <button class="btn btn-sm btn-danger">Excluir</button>
                           
                        </td>
                    </tr>

                    <!-- Modal Edit -->
                    <div class="modal fade" id="modalEditNivel{{ $nivel->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalEditNivel{{ $nivel->id }}Label" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalEditNivel{{ $nivel->id }}Label">Editar nível</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="POST" action="{{ route('gamefication.edit', ['id'=> $nivel->id]) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="modal-body row">
                                <div class="col-12 form-floating mb-3">
                                    <input autofocus type="text" class="form-control" id="name{{ $nivel->id }}" name="name" value="{{ $nivel->name }}" placeholder="Ex.: Nível 1">
                                    <label for="name{{ $nivel->id }}">Nome</label>
                                </div>
                            
                                <div class="col-12 form-floating mb-3">
                                    <textarea  class="form-control h-6" id="desc{{ $nivel->id }}" name="desc">{{ $nivel->desc }}</textarea>
                                    <label for="desc{{ $nivel->id }}">Descrição</label>
                                </div>

                                <div class="col-6 form-floating mb-3">
                                    <input type="text" class="form-control" id="min{{ $nivel->id }}" name="min" value="{{ $nivel->id == 2 ? 0 : $nivel->min }}" {{ $nivel->id == 2 ? 'readonly' : '' }}>
                                    <label for="min{{ $nivel->id }}">Valor mínimo (R$)</label>
                                </div>

                                <div class="col-6 form-floating mb-3">
                                    <input type="text" class="form-control" id="max{{ $nivel->id }}" name="max" value="{{ $nivel->max }}">
                                    <label for="max{{ $nivel->id }}">Valor máximo (R$)</label>
                                </div>
                                <div class="col-12 mb-3">
                                    <x-image-upload id="image{{ $nivel->id }}" name="image" label="Icone (512x512)" :value="str_replace('/storage/','',$nivel->image)" />
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Alterar</button>
                            </div>
                        </form>
                        </div>
                    </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Modal Create-->
<div class="modal fade" id="modalAddNivel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalAddNivelLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalAddNivelLabel">Adicionar nível</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" action="{{ route('gamefication.add') }}" enctype="multipart/form-data">
        @csrf
        <div class="modal-body row">
            <div class="col-12 form-floating mb-3">
                <input autofocus type="text" class="form-control" id="name" name="name" placeholder="Ex.: Nível 1">
                <label for="name">Nome</label>
            </div>
        
            <div class="col-12 form-floating mb-3">
                <textarea  class="form-control h-6" id="desc" name="desc" placeholder=""></textarea>
                <label for="desc">Descrição</label>
            </div>

            <div class="col-6 form-floating mb-3">
                <input type="text" class="form-control" id="min" name="min" placeholder="">
                <label for="min">Valor mínimo (R$)</label>
            </div>

            <div class="col-6 form-floating mb-3">
                <input type="text" class="form-control" id="max" name="max" placeholder="">
                <label for="max">Valor máximo (R$)</label>
            </div>
            <div class="col-12 mb-3">
                <x-image-upload id="image" name="image" label="Icone (512x512)" :value="null" />
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Adicionar</button>
        </div>
    </form>
    </div>
  </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    var table = $("#table-admin-niveis").DataTable({
        responsive: true,
        ordering: false,
        lengthChange: false,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json',
            search: ''
        }
    });

    table.on('draw', function() {
        $('#table-admin-niveis tbody tr').each(function() {
            $(this).find('td').css('border-bottom', 'none');
        });
    });

    // Garante que o evento draw também seja executado na primeira renderização
    table.draw();
});

  </script>
  </x-app-layout>