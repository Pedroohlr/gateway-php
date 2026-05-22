<x-app-layout :route="'Infrações'">

    <div class="container">
        <!-- Start::page-header -->
        <div class="flex-wrap my-4 d-flex align-items-center justify-content-between page-header-breadcrumb">
            <div>
                <p class="mb-0 display-5">Infrações</p>
            </div>
        </div>


        <div class="row">

            <div class="m-0 mb-4 col-xxl-3 col-md-6">
                <x-card-dash-admin :title="'Novas'" :subtitle="(clone $infracoes)->where('status', 'OPEN')->count()"
                    :icon="'triangle-alert'" />
            </div>
            <div class="m-0 mb-4 col-xxl-3 col-md-6">
                <x-card-dash-admin :title="'Em disputa'" :subtitle="(clone $infracoes)->where('status', 'UNDER_REVIEW')->count()" :icon="'triangle-alert'" />
            </div>
            <div class="m-0 mb-4 col-xxl-3 col-md-6">
                <x-card-dash-admin :title="'Resolvidas'" :subtitle="(clone $infracoes)->where('status', 'RESOLVED')->count()" :icon="'triangle-alert'" />
            </div>
            <div class="m-0 mb-4 col-xxl-3 col-md-6">
                <x-card-dash-admin :title="'Rejeitadas'" :subtitle="(clone $infracoes)->where('status', 'REJECTED')->count()" :icon="'triangle-alert'" />
            </div>
        </div>

        <!-- Start:: row-1 -->
        <div class="row">
            <div class="card card-raised">
                <div class="card-body">
                    <table class="table" id="table-disputas">
                        <thead>
                            <tr>
                                <th scope="col">Transação</th>
                                <th scope="col">Valor</th>
                                <th scope="col">Seller</th>
                                <th scope="col">Cliente</th>
                                <th scope="col">Status</th>
                                <th scope="col">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- {{dd($checkout->bumps)}} --}}
                            @foreach ($infracoes as $infracao)
                                <tr>
                                    <td>{{ $infracao->idTransaction }}</td>
                                    <td>{{ 'R$ ' . number_format($infracao->amount, 2, ',', '.') }}</td>
                                    <td>{{ $infracao->user->name }}</td>
                                    <td>{{ $infracao->solicitacao->client_name }}</td>
                                    <td>
                                        @if ($infracao->status == 'OPEN')
                                            <span class="badge text-bg-info">Aberto</span>
                                        @elseif($order->status == 'UNDER_REVIEW')
                                            <span class="badge text-bg-warning">Em disputa</span>
                                        @elseif($order->status == 'REJECTED')
                                            <span class="badge text-bg-error">Rejeitada</span>
                                        @elseif($order->status == 'RESOLVED')
                                            <span class="badge text-bg-error">Resolvida</span>
                                        @else
                                            <span class="badge text-bg-dark">---</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($infracao->status == 'OPEN')
                                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#modalApelo">Defender</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @foreach ($infracoes as $infracao)

        <!-- Modal Apelo-->
        <div class="modal fade" id="modalApelo" tabindex="-1" aria-labelledby="modalApeloLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalApeloLabel">Defender</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form method="POST" action="{{ route('profile.relatorio.infracoes.defesa') }}">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" name="infracao_id" value="{{ $infracao->id }}" />
                            <div class="mb-3">
                                <label for="appeal" class="form-label">Apresente a defesa</label>
                                <textarea class="form-control" id="appeal" name="appeal"
                                    rows="3">Nao foi feita cobranca indevida</textarea>
                                <small class="text-white">Utilize poucas palavras apresentando a defesa.</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Enviar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    @endforeach

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            $("#table-disputas").DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
                }
            });
        });

    </script>
</x-app-layout>