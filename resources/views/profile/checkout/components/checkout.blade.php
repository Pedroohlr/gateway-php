
<style>
.custom-menu .nav-link {
    border-radius: 0.375rem;
    padding-left: 0.75rem;
    color: #333;
    transition: all 0.2s ease-in-out;
}

.custom-menu .nav-link:hover,
.custom-menu .nav-link.active {
    background-color: #f1f1f1;
    font-weight: 500;
    cursor: pointer;
}

.custom-menu .slide__category {
    padding-left: 0.75rem;
    letter-spacing: 0.5px;
}
</style>
@props([
    'checkout'
])

@endphp
<div class="row align-items-start">
    <div class="py-2 col-xxl-2 text-start" style="background: #F8F8F9;">
        <ul class="px-2 py-3 nav flex-column custom-menu">
            <li class="mb-2 slide__category nav-separador-mobile text-uppercase fw-bold small text-muted ">
                Cores
            </li>
            <li class="nav-item">
                <a class="gap-2 py-2 nav-link d-flex align-items-center" data-bs-toggle="tab" data-bs-target="#tema" role="tab">
                    <i class="fa-solid fa-swatchbook text-primary"></i>
                    <span>Tema</span>
                </a>
            </li>

            <li class="mt-4 mb-2 slide__category nav-separador-mobile text-uppercase fw-bold small text-muted">
                Padrão
            </li>
            <li class="nav-item">
                <a class="gap-2 py-2 nav-link d-flex align-items-center" data-bs-toggle="tab" data-bs-target="#time" role="tab">
                    <i class="fa-solid fa-clock text-primary"></i>
                    <span>Time</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="gap-2 py-2 nav-link d-flex align-items-center" data-bs-toggle="tab" data-bs-target="#headers" role="tab">
                    <i class="fa-regular fa-window-maximize text-primary"></i>
                    <span>Header</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="gap-2 py-2 nav-link d-flex align-items-center" data-bs-toggle="tab" data-bs-target="#banner" role="tab">
                    <i class="fa-solid fa-square text-primary"></i>
                    <span>Top Bar</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="gap-2 py-2 nav-link d-flex align-items-center" data-bs-toggle="tab" data-bs-target="#depoimentos" role="tab">
                    <i class="fa-solid fa-comments text-primary"></i>
                    <span>Depoimentos</span>
                </a>
            </li>
        </ul>

    </div>
    <div class="col-xxl-2 text-start">
        <div class="tab-content" id="myTabContent">
            {{-- TEMA --}}
            <div class="tab-pane fade" id="tema" role="tabpanel" aria-labelledby="tema-tab">
                <div class="container text-center">
                    <div class="col-xl-12">
                        <label for="checkout_color" class="form-label">Cor de fundo</label>
                        <input type="color" style="height:42px;" class="form-control @error('checkout_color') is-invalid @enderror" name="checkout_color" value="{{ $checkout->checkout_color ?? '#F5F2F2' }}">
                        @error('checkout_color') <span style="color: red;">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-xl-12">
                        <label for="checkout_color_default" class="form-label">Cor padrão</label>
                        <input type="color" style="height:42px;" class="form-control @error('checkout_color_default') is-invalid @enderror" name="checkout_color_default" value="{{ $checkout->checkout_color_default ?? '#00C26E' }}">
                        @error('checkout_color_default') <span style="color: red;">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-xl-12">
                        <label for="checkout_color_card" class="form-label">Cor Fundo Card e Footer</label>
                        <input type="color" style="height:42px;" class="form-control @error('checkout_color_card') is-invalid @enderror" name="checkout_color_card" value="{{ $checkout->checkout_color_card ?? '#00C26E' }}">
                        @error('checkout_color_card') <span style="color: red;">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            {{-- TIMER --}}
            <div class="tab-pane fade" id="time" role="tabpanel" aria-labelledby="time-tab">
                <div class="container text-center">
                    <div class="mb-3 col-xl-12" style="border-bottom:1px solid #bebebe; padding-bottom:10px;">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="checkout_timer_active" id="checkout_timer_active" {{ $checkout->checkout_timer_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="checkout_timer_active">Contador Ativo</label>
                        </div>
                    </div>

                    <div class="mb-3 timer-scope col-xl-12">
                        <label for="checkout_timer_tempo" class="form-label">Tempo Inicial</label>
                        <input type="number" style="height:42px;" class="form-control @error('checkout_timer_tempo') is-invalid @enderror" name="checkout_timer_tempo" value="{{ $checkout->checkout_timer_tempo ?? 2 }}">
                        @error('checkout_timer_tempo') <span style="color: red;">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3 timer-scope col-xl-12">
                        <label for="checkout_timer_cor_fundo" class="form-label">Cor do fundo</label>
                        <input type="color" style="height:42px;" class="form-control @error('checkout_timer_cor_fundo') is-invalid @enderror" name="checkout_timer_cor_fundo" value="{{ $checkout->checkout_timer_cor_fundo ?? '#00C26E' }}">
                        @error('checkout_timer_cor_fundo') <span style="color: red;">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3 timer-scope col-xl-12">
                        <label for="checkout_timer_cor_texto" class="form-label">Cor do texto e ícone</label>
                        <input type="color" style="height:42px;" class="form-control @error('checkout_timer_cor_texto') is-invalid @enderror" name="checkout_timer_cor_texto" value="{{ $checkout->checkout_timer_cor_texto ?? '#FFFFFF' }}">
                        @error('checkout_timer_cor_texto') <span style="color: red;">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3 timer-scope col-xl-12">
                        <label for="checkout_timer_texto" class="form-label">Texto da contagem</label>
                        <input type="text" style="height:42px;" class="form-control @error('checkout_timer_texto') is-invalid @enderror" name="checkout_timer_texto" value="{{ $checkout->checkout_timer_texto }}">
                        @error('checkout_timer_texto') <span style="color: red;">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            {{-- HEADERS --}}
            <div class="tab-pane fade" id="headers" role="tabpanel" aria-labelledby="headers-tab">
                <div class="container text-center">
                    <div class="mb-3 col-xl-12" style="border-bottom:1px solid #bebebe; padding-bottom:10px;">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="checkout_header_logo_active" id="checkout_header_logo_active" {{ $checkout->checkout_header_logo_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="checkout_header_logo_active">Logo Ativa</label>
                        </div>
                    </div>

                    <div class="mb-3 col-xl-12">
                        <x-image-upload id="checkout_header_logo" name="checkout_header_logo" label="Logo" :height="'150px'" :value="$checkout->checkout_header_logo" />
                        @error('checkout_header_logo') <span style="color: red;">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3 col-xl-12" style="border-bottom:1px solid #bebebe; padding-bottom:10px;">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="checkout_header_image_active" id="checkout_header_image_active" {{ $checkout->checkout_header_image_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="checkout_header_image_active">Imagem 2 Ativa (Opcional)</label>
                        </div>
                    </div>

                    <div class="mb-3 col-xl-12">
                        <x-image-upload id="checkout_header_image" name="checkout_header_image" label="Imagem 2 (opcional)" :height="'150px'" :value="$checkout->checkout_header_image" />
                        @error('checkout_header_image') <span style="color: red;">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3 col-xl-12" style="border-bottom:1px solid #bebebe; padding-bottom:10px;">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="checkout_banner_active" id="checkout_banner_active" {{ $checkout->checkout_banner_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="checkout_banner_active">Banner Ativo</label>
                        </div>
                    </div>
                    <div class="mb-3 col-xl-12">
                        <x-image-upload id="checkout_banner" name="checkout_banner" label="Imagem de fundo" :height="'150px'" :value="$checkout->checkout_banner" />
                        @error('checkout_banner') <span style="color: red;">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            {{-- BANNER --}}
            <div class="tab-pane fade" id="banner" role="tabpanel" aria-labelledby="banner-tab">
                <div class="container text-center">
                    <div class="mb-3 col-xl-12" style="border-bottom:1px solid #bebebe; padding-bottom:10px;">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="checkout_topbar_active" id="checkout_topbar_active" {{ $checkout->checkout_topbar_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="checkout_topbar_active">Top bar Ativo</label>
                        </div>
                    </div>

                    <div class="mb-3 topbar-scope col-xl-12">
                        <label for="checkout_topbar_text" class="form-label">Texto do top bar</label>
                        <input type="text" style="height:42px;" class="form-control @error('checkout_topbar_text') is-invalid @enderror" name="checkout_topbar_text" value="{{ $checkout->checkout_topbar_text }}">
                        @error('checkout_topbar_text') <span style="color: red;">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3 topbar-scope col-xl-12">
                        <label for="checkout_topbar_text_color" class="form-label">Cor do texto do top bar</label>
                        <input type="color" style="height:42px;" class="form-control @error('checkout_topbar_text_color') is-invalid @enderror" name="checkout_topbar_text_color" value="{{ $checkout->checkout_topbar_text_color ?? '#FFFFFF' }}">
                        @error('checkout_topbar_text_color') <span style="color: red;">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3 topbar-scope col-xl-12">
                        <label for="checkout_topbar_color" class="form-label">Cor de fundo do top bar</label>
                        <input type="color" style="height:42px;" class="form-control @error('checkout_topbar_color') is-invalid @enderror" name="checkout_topbar_color" value="{{ $checkout->checkout_topbar_color ?? '#00C26E' }}">
                        @error('checkout_topbar_color') <span style="color: red;">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            {{-- DEPOIMENTOS --}}
            <div class="tab-pane fade" id="depoimentos" role="tabpanel" aria-labelledby="depoimentos-tab">

                <div class="container hidden mb-3 text-center card depoimento-bloco">
                    <div class="mb-3 col-xl-12">
                        <label for="checkout_depoimentos_image" class="form-label">Imagem</label>
                        <input type="file" accept="image/*" class="image-input form-control @error('checkout_depoimentos_image') is-invalid @enderror" data-id="image_{{uniqid()}}"  name="checkout_depoimentos_image[]">
                        @error('checkout_depoimentos_image') <span style="color: red;">{{ $message }}</span> @enderror
                    </div>
                   {{--  <div class="mb-3 col-xl-12">
                        <div class="mb-3 col-xl-12">
                            <x-image-upload :exibir="false" :class="'image-input'" id="checkout_depoimentos_image"  name="checkout_depoimentos_image" label="Imagem de fundo" :height="'150px'" :value="null" />
                            @error('checkout_depoimentos_image') <span style="color: red;">{{ $message }}</span> @enderror
                    </div>
                    </div> --}}
                    <div class="mb-3 col-xl-12">
                        <label for="checkout_depoimentos_nome" class="form-label">Nome</label>
                        <input type="text" class="form-control @error('checkout_depoimentos_nome') is-invalid @enderror" data-id="{{uniqid()}}"  name="checkout_depoimentos_nome[]">
                        @error('checkout_depoimentos_nome') <span style="color: red;">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3 col-xl-12">
                        <label for="checkout_depoimentos_depoimento" class="form-label">Depoimento</label>
                        <textarea class="form-control @error('checkout_depoimentos_depoimento') is-invalid @enderror" data-id="{{uniqid()}}"  name="checkout_depoimentos_depoimento[]"></textarea>
                        @error('checkout_depoimentos_depoimento') <span style="color: red;">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="justify-center mt-3 text-center d-flex">
                    <button type="button" class="btn btn-sm btn-primary btn-add-depoimento"><i class="fa-solid fa-plus"></i>&nbsp;Adcionar </button>
                </div>
            </div>
        </div>

    </div>
    <div class="col-xxl-8 text-start">
        <div>
            @include('profile.checkout.components.template1')
        </div>
    </div>
</div>


<script>
  $(document).ready(function () {
    const $btnAdd = $('.btn-add-depoimento');

    function bindDepoimentoEvents($formBloco, $visualBloco) {
        const $inputNome = $formBloco.find('input[name="checkout_depoimentos_nome[]"]');
        const $inputTexto = $formBloco.find('textarea[name="checkout_depoimentos_depoimento[]"]');
        const $inputImg = $formBloco.find('input[name="checkout_depoimentos_image[]"]');

        const $nomePreview = $visualBloco.find('.preview-nome');
        const $textoPreview = $visualBloco.find('.preview-depoimento');
        const $imgPreview = $visualBloco.find('.preview-image');

        // Nome
        if ($inputNome.length && $nomePreview.length) {
            $inputNome.off('input.depoimento').on('input.depoimento', function () {
                $nomePreview.text($(this).val());
            }).trigger('input');
        }

        // Texto
        if ($inputTexto.length && $textoPreview.length) {
            $inputTexto.off('input.depoimento').on('input.depoimento', function () {
                $textoPreview.text($(this).val());
            }).trigger('input');
        }

        // Imagem
        if ($inputImg.length && $imgPreview.length) {
            $inputImg.off('change.depoimento').on('change.depoimento', function (e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (event) {
                        $imgPreview.attr('src', event.target.result);
                    };
                    reader.readAsDataURL(file);
                } else {
                    $imgPreview.attr('src', '/assets/images/avatar_default.png');
                }
            });
        }
    }

    function createDepoimentoBlock(id = '', nome = '', texto = '', imagem = '') {
        const $formOriginal = $('.depoimento-bloco').first();
        const $visualOriginal = $('.depoimento-container').first();

        const $formClone = $formOriginal.clone(true, true);
        const $visualClone = $visualOriginal.clone(true, true);

        // Preencher campos
        $formClone.find('input[name="checkout_depoimentos_nome[]"]').val(nome);
        $formClone.find('textarea[name="checkout_depoimentos_depoimento[]"]').val(texto);
        $formClone.find('input[name="checkout_depoimentos_image[]"]').val('');

        // Preview inicial
        $visualClone.find('.preview-nome').text(nome);
        $visualClone.find('.preview-depoimento').text(texto);
        $visualClone.find('.preview-image').attr('src', imagem || '/assets/images/avatar_default.png');

        // Adiciona campos ocultos, se necessário
        if (id) {
            const $inputId = $('<input>', {
                type: 'hidden',
                name: 'checkout_depoimentos_id[]',
                value: id
            });
            $formClone.append($inputId);
        }

        if (imagem) {
            const $inputAvatar = $('<input>', {
                type: 'hidden',
                name: 'checkout_depoimentos_avatar[]',
                value: imagem
            });
            $formClone.append($inputAvatar);
        }

        // Adiciona ao DOM
        $formClone.removeClass('hidden').addClass('d-flex');
        $visualClone.removeClass('hidden');

        const $formContainer = $formOriginal.parent();
        const $lastForm = $formContainer.find('.depoimento-bloco').last();
        $formClone.insertAfter($lastForm);

        $('#depoimento-visual-list').append($visualClone);

        const $divBtnRow = $('<div>', {
            class: 'row mb-3'
        });

        const $divBtnCol1 = $('<div>', {
            class: 'col-sm-6 d-flex align-items-center justify-content-end'
        });

        const $divBtnCol2 = $('<div>', {
            class: 'col-sm-6 d-flex align-items-center justify-content-start'
        });

        // Botão remover
        const $btnRemove = $('<button>', {
            type: 'button',
            class: 'btn btn-danger btn-sm ms-2',
            html: '<i class="fa-solid fa-trash"></i>&nbsp;Excluir'
        });

        $divBtnCol2.append($btnRemove);

        const $btnSalvar = $('<button>', {
        type: 'button',
        class: 'btn btn-success btn-sm ms-2',
        html: '<i class="fa-solid fa-floppy-disk"></i>&nbsp;Salvar'
        });

        $btnSalvar.on('click', function () {
            const formData = new FormData();

            const nome = $formClone.find('input[name="checkout_depoimentos_nome[]"]').val();
            const depoimento = $formClone.find('textarea[name="checkout_depoimentos_depoimento[]"]').val();
            const id = $formClone.find('input[name="checkout_depoimentos_id[]"]').val();
            const avatarAntigo = $formClone.find('input[name="checkout_depoimentos_avatar[]"]').val();
            const imagemInput = $formClone.find('input[name="checkout_depoimentos_image[]"]')[0];
            const imagemFile = imagemInput?.files?.[0];

            if (!nome || !depoimento) {
                showToast('warning','Preencha nome e depoimento.');
                return;
            }

            formData.append('id', id || '');
            formData.append('nome', nome);
            formData.append('depoimento', depoimento);
            formData.append('checkout_id', "{{$checkout->id}}")
            if (imagemFile) {
                formData.append('image', imagemFile);
            } else if (avatarAntigo) {
                formData.append('avatar', avatarAntigo);
            }

            $.ajax({
                url: '/produtos/depoimento/salvar', // endpoint a ser criado no backend
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (res) {
                    if (res.success) {
                        showToast('success','Depoimento salvo com sucesso!');
                        if (res.depoimento?.id) {
                            $formClone.find('input[name="checkout_depoimentos_id[]"]').val(res.depoimento.id);
                        }
                        if (res.depoimento?.avatar) {
                            $formClone.find('input[name="checkout_depoimentos_avatar[]"]').remove();
                            const $newHidden = $('<input>', {
                                type: 'hidden',
                                name: 'checkout_depoimentos_avatar[]',
                                value: res.depoimento.avatar
                            });
                            $formClone.append($newHidden);

                            $visualClone.find('.preview-image').attr('src', res.depoimento.avatar);
                        }
                    } else {
                        showToast('error','Erro ao salvar depoimento.');
                    }
                },
                error: function () {
                    showToast('error','Erro na requisição.');
                }
            });
        });


        $divBtnCol1.append($btnSalvar);

        $divBtnRow.append($divBtnCol1, $divBtnCol2);
        $formClone.append($divBtnRow);

        $btnRemove.on('click', function () {
            const id = $formClone.find('input[name="checkout_depoimentos_id[]"]').val();

            if (id) {
                // Envia requisição ao backend para remover do banco
                $.ajax({
                    url: '/produtos/depoimento/remover',
                    method: 'POST',
                    data: { id },
                    success: function (res) {
                        if (res.success) {
                            showToast('success', 'Depoimento removido com sucesso!');
                            $formClone.remove();
                            $visualClone.remove();
                        } else {
                            showToast('error', 'Erro ao remover depoimento.');
                        }
                    },
                    error: function () {
                        showToast('error', 'Erro na requisição de remoção.');
                    }
                });
            } else {
                // Apenas remove do DOM, pois ainda não foi salvo
                $formClone.remove();
                $visualClone.remove();
            }
        });

        bindDepoimentoEvents($formClone, $visualClone);
        initImageUpload($('#depoimentos .form-depoimento').last()[0]);
    }

    // Carrega depoimentos do backend
    let depoimentos = @json($checkout->depoimentos);
    if (Array.isArray(depoimentos) && depoimentos.length > 0) {
        depoimentos.forEach(dep => {
            createDepoimentoBlock(dep.id, dep.nome, dep.depoimento, dep.avatar ?? '');
        });
    }

    // Botão adicionar novo depoimento
    $btnAdd.on('click', function () {
        createDepoimentoBlock();
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});

    </script>




