@php
    $setting = \App\Helpers\Helper::getSetting();
    \App\Helpers\Helper::calculaSaldoLiquido(auth()->user()->user_id);
@endphp
<x-app-layout :route="'Dashboard'">
    <!-- Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">Atenção</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Você precisa concluir o cadastro para ativar sua conta.
                </div>
                <div class="modal-footer">
                    <a href="{{ url('/enviar-doc') }}" class="btn btn-success">Enviar Documentos</a>
                </div>
            </div>
        </div>
    </div>

    <div class="main-content app-content">
        <div class="container-fluid">


            @if($status == 0)
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card card-raised">
                            <div class="p-3 d-grid border-bottom border-block-end-dashed">
                                <h5 class="card-title">Ativação de Conta</h5>
                                <p class="card-text">Para ativar sua conta, é necessário o envio de documentos. Por favor,
                                    envie os documentos para análise.</p>
                                <a href="{{ url('/enviar-doc') }}" class="btn btn-success">Enviar Documentos</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($status == 5)
                <div class="p-5 container-xl">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card card-raised">
                                <div class="p-3 d-grid border-bottom border-block-end-dashed">
                                    <h5 class="card-title">Sua conta está em Análise</h5>
                                    <p class="card-text">Nossa equipe está analisando seus documentos e logo vai entrar em
                                        contato.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($status == 1 && $permission != 9)
                <div class="container-fluid mx-0 px-0 ">
                    @if(isset($setting->gateway_banner_home) && !is_null($setting->gateway_banner_home))
                        <div class="row">
                            <img src="{{ $setting->gateway_banner_home }}" width="1280" height="126">
                        </div>
                    @endif
                    <div class="mb-3 row justify-content-between align-items-">
                        <div style="display:flex;align-item:center;justify-content:flex-start;"
                            class="mb-0 col-12 col-md-4 mb-md-0 justify-content-start align-items-center">
                           <!--  <h1 class="mb-0 display-6">Dashboard</h1> -->
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-xxl-3 col-md-6">
                        <div class="card radius-10">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <p class="mb-0">Saldo disponível</p>
                                        <h4 class="my-1">R$ {{ number_format(auth()->user()->saldo ?? 0, 2, ',', '.') }}</h4>
                                    </div>
                                    <div class="ms-auto font-35 text-white"><i class="bx bx-wallet"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4 col-xxl-3 col-md-6">
                        <div class="card radius-10">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <p class="mb-0">Saldo bloqueado</p>
                                        <h4 class="my-1">R$ {{ number_format(auth()->user()->saldo_pendente ?? 0, 2, ',', '.') }}</h4>
                                    </div>
                                    <div class="ms-auto font-35 text-warning"><i class="bx bx-wallet"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4 col-xxl-3 col-md-6">
                        <div class="card radius-10">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <p class="mb-0">Pix pago</p>
                                        <h4 class="my-1">R$ {{ number_format($sumAmountPaidOut ?? 0, 2, ',', '.') }}</h4>
                                    </div>
                                    <div class="ms-auto font-35 text-white"><i class="bx bx-download"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4 col-xxl-3 col-md-6">
                        <div class="card radius-10">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <p class="mb-0">Ticket Médio</p>
                                        
                                        <h4 class="my-1">R$ {{ number_format($sumAmountPaidOut ?? 0, 2, ',', '.') }}</h4>
                                    </div>
                                    <div class="ms-auto font-35 text-white"><i class="bx bx-sync"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Start:: row-1 -->
                <div class="row ">
                    <!-- End:: row-1 -->
                    <div class="mb-3 col-lg-6">
                        <div class="card card-raised h-100">
                            <div class="p-3 card-body">
                                <div class="bg-white card-header text-slate-300">
                                <div class="card-title text-slate-300">Estatísticas de vendas</div>
                            </div>
                            <div class="p-0 card-body ">
                                <div id="areaChart"></div>
                            </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 col-lg-3">
                        <div class="card card-raised h-100">
                            <div class="p-4 card-body">
                                <!-- <div class="col-12">
                                        <div class="table-responsive">
                                            <table id="datatablesDash" class="table text-nowrap">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Meio</th>
                                                        <th scope="col">Aprovação</th>
                                                        <th scope="col">Valor</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <div class="gap-3">
                                                                <i class="text-lg fa-brands fa-pix color-gateway"></i>
                                                                <span class="text-lg"></span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            @php

                                                                $totalSolicitacoes = (clone $solicitacoes)->count();
                                                                $totalSolicitacoesPaidOut = (clone $solicitacoesCashOut)->where('status', 'PAID_OUT')->count();

                                                                $porcentagemSolicitacoes = $totalSolicitacoes > 0
                                                                    ? ($totalSolicitacoesPaidOut / $totalSolicitacoes) * 100
                                                                    : 0;

                                                                $totalCashOut = (clone $solicitacoesCashOut)->count();
                                                                $totalCashOutCompleted = (clone $solicitacoesCashOut)->where('status', 'COMPLETED')->count();

                                                                $porcentagemCashOut = $totalCashOut > 0
                                                                    ? ($totalCashOutCompleted / $totalCashOut) * 100
                                                                    : 0;
                                                            @endphp
                                                            <p>{{ number_format(($porcentagemSolicitacoes + $porcentagemCashOut / 2), 2) . "%" }}
                                                            </p>
                                                        </td>
                                                        <td>
                                                            <p>{{ "R$ " . number_format((clone $solicitacoes)->sum('deposito_liquido') + (clone $solicitacoesCashOut)->sum('amount'), '2', ',', '.') }}
                                                            </p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="gap-3">
                                                                <i class="text-lg fa-solid fa-barcode color-gateway"></i>
                                                                <span class="text-lg"></span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <p>0.00%</p>
                                                        </td>
                                                        <td>
                                                            <p>R$ 0,00</p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="gap-3">
                                                                <i
                                                                    class="text-lg fa-solid fa-credit-card color-gateway"></i>
                                                                <span class="text-lg"></span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <p>0.00%</p>
                                                        </td>
                                                        <td>
                                                            <p>R$ 0,00</p>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div> -->
                                <div class="col-12">
                                    <div class="bg-white card-header text-slate-300">
                                <div class="card-title text-slate-300">Ultimas vendas</div>
                            </div>
                            <div class="px-3 pb-7 card-body ">
                                <section class="py-1">
                                    <div class="timeline">
                                        @foreach($ultimasTransacoes->whereIn('status', ['PAID_OUT', 'COMPLETED']) as $row)
                                            @php
                                                $isPayment = isset($row->beneficiaryname);
                                                $data = isset($row->date) ? \Carbon\Carbon::parse($row->date) : \Carbon\Carbon::parse($row->date);
                                                $valor = isset($row->amount) ? $row->amount : $row->cash_out_liquido;
                                            @endphp

                                            <div class="timeline-item">
                                                <div class="timeline-date">{{ $data->format('d/m/Y \à\s H:i:s') }}</div>
                                                @if($isPayment)
                                                    <div class="fw-semibold text-warning">Pagamento realizado</div>
                                                    <div class="amount-credit text-warning">- R$
                                                        {{ number_format($valor, 2, ',', '.') }}</div>
                                                @else
                                                    <div class="fw-semibold text-success">Pagamento recebido</div>
                                                    <div class="amount-credit text-success">+ R$
                                                        {{ number_format($valor, 2, ',', '.') }}</div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </section>
                            </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 col-lg-3">
                        <div class="card card-raised h-100">
                            <div class="p-4 card-body d-flex align-items-center justify-content-center" style="position: relative;">
                                <img src="{{ auth()->user()->nivelAtual->image }}" 
                                    style="height: 100%; width: auto; object-fit: contain;" 
                                    alt="Nível atual">
                                     <small style="position: absolute;bottom:10px;text-align:center;">{{ auth()->user()->nivelAtual->desc }}</small>
                            </div>
                            
                                   
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="dateRangeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="border-0 modal-content rounded-4">
                <div class="justify-center p-4 pl-5 modal-body align-center">
                    <h5 class="mb-4 fw-semibold">Selecione o período</h5>

                    <div class="row">
                        <div class="mb-3 text-center col-md-6">
                            <strong class="mb-2 d-block">Data de Início</strong>
                            <div class="d-flex justify-content-center" id="calendarInicio"></div>
                        </div>
                        <div class="text-center col-md-6">
                            <strong class="mb-2 d-block">Data de Fim</strong>
                            <div class="d-flex justify-content-center" id="calendarFim"></div>
                        </div>
                    </div>
                </div>
                <div class="gap-2 mt-4 modal-footer d-flex justify-content-end">
                    <button class="btn btn-outline-dark" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-success" id="btnAplicarDatas">Aplicar</button>
                </div>
            </div>
        </div>
    </div>

    @if(auth()->check() && auth()->user()->password_temp)
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const modal = new bootstrap.Modal(document.getElementById('forcePasswordChangeModal'), {
                    backdrop: 'static',
                    keyboard: false
                });
                modal.show();
            });
        </script>
    @endif

    @if(auth()->check() && auth()->user()->password_temp)
        <div class="modal fade" id="forcePasswordChangeModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
            data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form method="POST" action="{{ route('password.force.update') }}">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Alterar Senha</h5>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="new_password" class="form-label">Nova Senha</label>
                                <input type="password" class="form-control" name="new_password" id="new_password" required>
                            </div>
                            <div class="mb-3">
                                <label for="new_password_confirmation" class="form-label">Confirmar Nova Senha</label>
                                <input type="password" class="form-control" name="new_password_confirmation"
                                    id="new_password_confirmation" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Alterar Senha</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
let permission = "{{ $permission }}";
console.log(permission);

if (Number(permission) !== 9) {
    (function () {
        const solicitacoes = {!! json_encode($solicitacoesPaid ?? []) !!};

        const now = new Date();
        const year = now.getFullYear();
        const month = now.getMonth();
        const day = now.getDate();

        const labels = [];
        const depositMap = {};

        // Cria as 24 horas iniciais com valor 0
        for (let h = 0; h < 24; h++) {
            const hourLabel = `${h.toString().padStart(2, '0')}:00`;
            labels.push(hourLabel);
            depositMap[hourLabel] = 0;
        }

        // Preenche com valores caso existam solicitações
        if (Array.isArray(solicitacoes) && solicitacoes.length > 0) {
            solicitacoes.forEach(item => {
                if (!item.date || !item.amount) return;

                const date = new Date(item.date);

                if (
                    date.getFullYear() !== year ||
                    date.getMonth() !== month ||
                    date.getDate() !== day
                ) return;

                const hourLabel = `${date.getHours().toString().padStart(2, '0')}:00`;
                const amount = parseFloat(item.amount) || 0;

                if (!item.beneficiaryname) {
                    depositMap[hourLabel] += amount;
                }
            });
        } else {
            console.warn("⚠️ Nenhuma solicitação encontrada. Gráfico será renderizado vazio.");
        }

        const seriesDeposit = labels.map(h => depositMap[h]);

        const options = {
            series: [
                { name: 'Depósitos', data: seriesDeposit }
            ],
            chart: {
                height: 420,
                type: 'area',
                zoom: { enabled: false },
                toolbar: { show: false }
            },
            legend: { show: false },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth' },
            xaxis: {
                categories: labels,
                labels: { rotate: 0 }
            },
            tooltip: { x: { show: true }, theme: 'dark' },
            colors: ["{{ $setting->gateway_color }}"],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.4,
                    opacityTo: 0,
                    stops: [0, 100]
                }
            }
        };

        const chartElement = document.querySelector("#areaChart");
        if (!chartElement) {
            console.error("❌ Erro: elemento #areaChart não encontrado no DOM.");
            return;
        }

        new ApexCharts(chartElement, options).render();
    })();
}
</script>

  <!--   <script disable-devtool-auto src='https://cdn.jsdelivr.net/npm/disable-devtool@latest'></script> -->
</x-app-layout>