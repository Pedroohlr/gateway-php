
<x-app-layout :route="'Pedidos'">

    <div class="container">
        <!-- Start::page-header -->
        <div class="flex-wrap my-4 d-flex align-items-center justify-content-between page-header-breadcrumb">
            <div>
                <p class="mb-0 display-5">Pedidos</p>
            </div>
        </div>


        <div class="row">

            <div class="m-0 mb-4 col-xxl-3 col-md-6">
                 <x-card-dash-admin 
                    :title="'Pedidos pagos'"
                    :subtitle="(clone $orders)->where('status', 'pago')->count()"
                    :icon="'shopping-cart'"
                    />
            </div>
            <div class="m-0 mb-4 col-xxl-3 col-md-6">
                 <x-card-dash-admin 
                    :title="'Pedidos pendentes'"
                    :subtitle="(clone $orders)->where('status', 'gerado')->count()"
                    :icon="'shopping-cart'"
                    />
            </div>
            <div class="m-0 mb-4 col-xxl-3 col-md-6">
                 <x-card-dash-admin 
                    :title="'Valor pago'"
                    :subtitle="'R$ '.number_format((clone $orders)->where('status', 'pago')->sum('valor_total'), '2', ',', '.')"
                    :icon="'shopping-cart'"
                    />
            </div>
            <div class="m-0 mb-4 col-xxl-3 col-md-6">
                 <x-card-dash-admin 
                    :title="'Valor gerado'"
                    :subtitle="'R$ '.number_format((clone $orders)->where('status', 'gerado')->sum('valor_total'), '2', ',', '.')"
                    :icon="'shopping-cart'"
                    />
            </div>
        </div>

        <!-- Start:: row-1 -->
        <div class="row">
               <div class="card card-raised">
            <div class="card-body">
                <table class="table text-nowrap" id="table-pedidos">
                        <thead>
                            <tr>
                                <th scope="col">Produto</th>
                                <th scope="col">Nome do cliente</th>
                                <th scope="col">CPF do cliente</th>
                                <th scope="col">Telefone do cliente</th>
                                <th scope="col">Email do cliente</th>
                                <th scope="col">Endereço</th>
                                <th scope="col">Valor da compra</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{--  {{dd($checkout->bumps)}} --}}
                            @foreach ($orders as $order)
                                <tr>
                                    <td>{{ $order->checkout->produto_name }}</td>
                                    <td>{{ $order->name }}</td>
                                    <td>{{ $order->cpf }}</td>
                                    <td>{{ $order->telefone }}</td>
                                    <td>{{ $order->email }}</td>
                                    <td>
                                        @if ($order->checkout->produto_tipo == 'fisico')
                                            {{ $order->endereco . ', Nº' . $order->numero . ' ' . $order->bairro . ', ' . $order->cidade . '-' . $order->estado . 'CEP: ' . $order->cep }}
                                    </td>
                                @else
                                    ---
                            @endif
                            <td>{{ "R$ " . number_format($order->valor_total, '2', ',', '.') }}</td>
                            <td>
                                @if ($order->status == 'pago')
                                    <span class="badge text-bg-success">Pago</span>
                                @else
                                    <span class="badge text-bg-warning">Pendente</span>
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


    <script>
        document.addEventListener("DOMContentLoaded", function() {
           $("#table-pedidos").DataTable({
               responsive: true,
               ordering: false,
               language: {
                   url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
               },
                initComplete: function() {
                    // Muda o placeholder do input de busca
                    $('#table-produtos_filter input[type="search"]').attr('placeholder', 'Pesquisar');
                }
           });
        });

       </script>
</x-app-layout>
