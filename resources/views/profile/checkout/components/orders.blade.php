@props([
    'checkout' => []
])

<div class="card">
    <div class="flex justify-between align-middle card-header">
      Pedidos
    </div>
    <div class="card-body">
        <table id="table-checkout-pedidos" class="table">
            <thead>
              <tr>
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
               @foreach($checkout->orders as $order)
               <tr>
                   <td>{{ $order->name }}</td>
                   <td>{{ $order->cpf }}</td>
                   <td>{{ $order->telefone }}</td>
                   <td>{{ $order->email }}</td>
                   <td>
                    @if($checkout->produto_tipo == 'fisico')
                        {{ $order->endereco.', Nº'.$order->numero.' '.$order->bairro.', '.$order->cidade.'-'.$order->estado.'CEP: '.$order->cep }}</td>
                    @else
                        ---
                    @endif
                    <td>{{ "R$ ".number_format($order->valor_total, '2',',','.') }}</td>
                   <td>
                       @if($order->status == 'pago')
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
<script>
    document.addEventListener("DOMContentLoaded", function() {
       $("#table-checkout-pedidos").DataTable({
           responsive: true,
           language: {
               url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
           }
       });
    });

   </script>
