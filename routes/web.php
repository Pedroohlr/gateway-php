<?php

use App\Http\Controllers\Admin\GameficationController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardControlller;
use App\Http\Controllers\EnviarDocControlller;
use App\Http\Controllers\DocumentacaoControlller;
use App\Http\Controllers\User\ChavesApiControlller;
use App\Http\Controllers\User\CheckoutControlller;
use App\Http\Controllers\User\FinanceiroControlller;
use App\Http\Controllers\User\RelatoriosControlller;
use App\Http\Controllers\Admin\Ajustes\LandingPageController;
use App\Http\Controllers\User\OrderbumpController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\PushController;

Route::get('/', [App\Http\Controllers\Admin\Ajustes\LandingPageController::class, 'welcome']);
Route::get('/login2', [AuthenticatedSessionController::class, 'login2'])->name('login2');
Route::post('/login2', [AuthenticatedSessionController::class, 'step1Login'])->name('auth.store2');
Route::post('/validar', [AuthenticatedSessionController::class, 'validateCode'])->name('validar.codigo');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardControlller::class, 'index'])->name('dashboard');
    Route::get('/documentacao', [DocumentacaoControlller::class, 'index'])->name('documentacao');
    Route::get('/enviar-doc', [EnviarDocControlller::class, 'index'])->name('profile.index');
    Route::post('/enviar-docs/{id}', [EnviarDocControlller::class, 'enviarDocs'])->where('id', ".*")->name('profile.enviardocs');


    Route::get('/my-profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/user/avatar-upload', [ProfileController::class, 'uploadAvatar'])->name('profile.avatar.upload');


    Route::group(['prefix' => 'relatorio'], function () {
        Route::get('/entradas', [RelatoriosControlller::class, 'pixentrada'])->name('profile.relatorio.pixentrada');
        Route::get('/saidas', [RelatoriosControlller::class, 'pixsaida'])->name('profile.relatorio.pixsaida');
        Route::get('/infracoes', [RelatoriosControlller::class, 'infracoes'])->name('profile.relatorio.infracoes');
        Route::post('/infracoes/defesa', [RelatoriosControlller::class, 'infracoesDefesa'])->name('profile.relatorio.infracoes.defesa');
        Route::get('/saidas/consulta', [RelatoriosControlller::class, 'consulta'])->name('profile.relatorio.consulta');
    });

    Route::group(['prefix' => 'app'], function () {
        Route::get('/install', [AppController::class, 'index'])->name('profile.app.index');
    });

    Route::get('/financeiro', [FinanceiroControlller::class, 'index'])->name('profile.financeiro');
    Route::get('/chaves', [ChavesApiControlller::class, 'index'])->name('profile.chavesapi');


    Route::group(['prefix' => 'produtos'], function () {
        Route::get('/', [CheckoutControlller::class, 'index'])->name('profile.checkout');
        Route::get('/visualizar/{id}', [CheckoutControlller::class, 'indexEdit'])->name('profile.checkout.produto');
        Route::put('/editar/{id}', [CheckoutControlller::class, 'edit'])->name('profile.checkout.produto.editar');

        Route::post('/', [CheckoutControlller::class, 'create'])->name('profile.checkout.create');


        Route::delete('checkout/{id}', [CheckoutControlller::class, 'destroy'])->name('profile.checkout.delete');

        Route::post('/depoimento/salvar', [CheckoutControlller::class, 'salvarDepoimento']);
        Route::post('/depoimento/remover', [CheckoutControlller::class, 'removerDepoimento']);
        Route::group(['prefix' => 'orderbumps'], function () {
            Route::post('create/{id}', [OrderbumpController::class, 'create'])->where('id', '.*')->name('checkout.orderbumps.create');
            Route::put('edit/{id}', [OrderbumpController::class, 'edit'])->where('id', '.*')->name('checkout.orderbumps.edit');
            Route::delete('remove/{id}', [OrderbumpController::class, 'removeBump'])->where('id', '.*')->name('checkout.orderbumps.remove');
        });

        Route::group(['prefix' => 'orders'], function () {
            Route::get('/', [OrderController::class, 'index'])->name('profile.orders');
        });
    });


    Route::group(['prefix' => 'gerencia'], function () {
        Route::get('clientes', [App\Http\Controllers\Gerencia\ClientesController::class, 'index'])->name('gerencia.index');
        Route::get('/cliente/detalhes/{id}', [App\Http\Controllers\Gerencia\ClientesController::class, 'detalhes'])->name('gerencia.detalhes');
        Route::post('/cliente/status', [App\Http\Controllers\Gerencia\ClientesController::class, 'usuarioStatus'])->name('gerencia.mudarstatus');
        Route::put('/cliente/edit/{id}', [App\Http\Controllers\Gerencia\ClientesController::class, 'edit'])->name('gerencia.edit');
        Route::post('/cliente/resetsenha/{id}', [App\Http\Controllers\Gerencia\ClientesController::class, 'resetsenha'])->name('gerencia.resetsenha');
    });

    Route::group(['prefix' => env("ADM_ROUTE"), 'middleware'=> 'auth_admin'], function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/usuarios', [App\Http\Controllers\Admin\UsuariosController::class, 'index'])->name('admin.usuarios');

        Route::get('/usuario/detalhes/{id}', [App\Http\Controllers\Admin\UsuariosController::class, 'detalhes'])->name('admin.usuario.detalhes');
        Route::post('/usuario/status', [App\Http\Controllers\Admin\UsuariosController::class, 'usuarioStatus'])->name('admin.usuarios.mudarstatus');
        Route::delete('/usuario/delete/{id}', [App\Http\Controllers\Admin\UsuariosController::class, 'destroy'])->name('admin.usuarios.delete');
        Route::put('/usuario/edit/{id}', [App\Http\Controllers\Admin\UsuariosController::class, 'edit'])->name('admin.usuarios.edit');
        Route::post('/usuario/carteira-lucro', [App\Http\Controllers\Admin\UsuariosController::class, 'definirCarteiraLucro'])->name('admin.usuarios.carteira-lucro');

        Route::group(['prefix' => 'financeiro'], function () {
            Route::get('/transacoes', [App\Http\Controllers\Admin\Financeiro\TransacoesController::class, 'index'])->name('admin.financeiro.transacoes');
            Route::get('/carteiras', [App\Http\Controllers\Admin\Financeiro\CarteirasController::class, 'index'])->name('admin.financeiro.carteiras');
            Route::get('/entradas', [App\Http\Controllers\Admin\Financeiro\EntradasController::class, 'index'])->name('admin.financeiro.entradas');
            Route::get('/saidas', [App\Http\Controllers\Admin\Financeiro\SaidasController::class, 'index'])->name('admin.financeiro.saidas');
            Route::get('/infracoes', [App\Http\Controllers\Admin\Financeiro\TransacoesController::class, 'infracoes'])->name('admin.financeiro.infracoes');
            Route::post('/med/marcar', [App\Http\Controllers\Admin\Financeiro\TransacoesController::class, 'marcarMed'])->name('admin.financeiro.marcar-med');
            Route::post('/med/remover', [App\Http\Controllers\Admin\Financeiro\TransacoesController::class, 'removerMed'])->name('admin.financeiro.remover-med');
        });

        Route::group(['prefix' => 'transacoes'], function () {
            Route::get('/procurar', [App\Http\Controllers\Admin\Transacoes\ProcurarController::class, 'index'])->name('admin.transacoes.procurar');
            Route::get('/entrada', [App\Http\Controllers\Admin\Transacoes\EntradaController::class, 'index'])->name('admin.transacoes.entradas');
            Route::post('/entrada', [App\Http\Controllers\Admin\Transacoes\EntradaController::class, 'addentrada'])->name('admin.transacoes.addentrada');
            Route::get('/saida', [App\Http\Controllers\Admin\Transacoes\SaidaController::class, 'index'])->name('admin.transacoes.saidas');
            Route::post('/saida', [App\Http\Controllers\Admin\Transacoes\SaidaController::class, 'addsaida'])->name('admin.transacoes.addsaida');
        });

        Route::get('/aprovar-saques', [App\Http\Controllers\Admin\SaquesController::class, 'index'])->name('admin.saques');
        Route::put('/saques/aprovar/{id}', [App\Http\Controllers\Admin\SaquesController::class, 'aprovar'])->where('id', '.*')->name('admin.saques.aprovar');
        Route::put('/saques/rejeitar/{id}', [App\Http\Controllers\Admin\SaquesController::class, 'rejeitar'])->where('id', '.*')->name('admin.saques.rejeitar');

        Route::group(['prefix' => 'ajustes'], function () {
            Route::get('/adquirentes', [App\Http\Controllers\Admin\Ajustes\AdquirentesController::class, 'index'])->name('admin.ajustes.adquirentes');
            Route::post('/cashtime', [App\Http\Controllers\Admin\Ajustes\AdquirentesController::class, 'update'])->name('admin.adquirentes.cashtime');
            Route::post('/cartwave', [App\Http\Controllers\Admin\Ajustes\AdquirentesController::class, 'updateCartwave'])->name('admin.adquirentes.cartwave');
            Route::post('/apithekey', [App\Http\Controllers\Admin\Ajustes\AdquirentesController::class, 'updateApithekey'])->name('admin.adquirentes.apithekey');
            Route::post('/simpay', [App\Http\Controllers\Admin\Ajustes\AdquirentesController::class, 'updateSimpay'])->name('admin.adquirentes.simpay');
            Route::post('/witetec', [App\Http\Controllers\Admin\Ajustes\AdquirentesController::class, 'updateWitetec'])->name('admin.adquirentes.witetec');
            Route::post('/zoompag', [App\Http\Controllers\Admin\Ajustes\AdquirentesController::class, 'updateZoompag'])->name('admin.adquirentes.zoompag');
            Route::post('/blupay', [App\Http\Controllers\Admin\Ajustes\AdquirentesController::class, 'updateBlupay'])->name('admin.adquirentes.blupay');
            Route::post('/mercadopago', [App\Http\Controllers\Admin\Ajustes\AdquirentesController::class, 'updateMercadopago'])->name('admin.adquirentes.mercadopago');
            Route::post('/default', [App\Http\Controllers\Admin\Ajustes\AdquirentesController::class, 'updateDefault'])->name('admin.adquirentes.default');
            Route::get('/landing-page', [App\Http\Controllers\Admin\Ajustes\LandingPageController::class, 'index'])->name('admin.landing.index');
            Route::post('/landing-page', [App\Http\Controllers\Admin\Ajustes\LandingPageController::class, 'update'])->name('admin.landing.update');
            Route::get('/gerais', [App\Http\Controllers\Admin\Ajustes\SegurancaController::class, 'index'])->name('admin.ajustes.seguranca');
            Route::post('/gerais', [App\Http\Controllers\Admin\Ajustes\SegurancaController::class, 'update'])->name('admin.ajustes.gerais');
            Route::get('/smtp', [App\Http\Controllers\Admin\Ajustes\SmtpController::class, 'index'])->name('admin.ajustes.smtp.index');
            Route::post('/smtp', [App\Http\Controllers\Admin\Ajustes\SmtpController::class, 'store'])->name('admin.ajustes.smtp');
            Route::get('/gerentes', [App\Http\Controllers\Admin\Ajustes\GerenteController::class, 'index'])->name('admin.ajustes.gerente');
            Route::post('/gerentes', [App\Http\Controllers\Admin\Ajustes\GerenteController::class, 'create'])->name('admin.ajustes.gerente.add');
            Route::put('/gerentes/{id}', [App\Http\Controllers\Admin\Ajustes\GerenteController::class, 'update'])->where('id', '.*')->name('admin.ajustes.gerente.update');

            Route::group(['prefix' => 'notificacoes'], function () {
                Route::get('/', [App\Http\Controllers\Admin\Ajustes\SegurancaController::class, 'notificacaoIndex'])->name('admin.ajustes.notificacoes.index');
                Route::post('/', [App\Http\Controllers\Admin\Ajustes\SegurancaController::class, 'notificacaoUpdate'])->name('admin.ajustes.notificacoes');

                Route::post('/send-push', [PushController::class, 'send'])->name('notificacoes.send.all');
                Route::post('/send-push-user', [PushController::class, 'sendToUser'])->name('notificacoes.send.one');
            });
        });

        Route::group(['prefix' => 'gamefication'], function () {
            Route::get('/', [GameficationController::class, 'index'])->name('gamefication.index');
            Route::post('/', [GameficationController::class, 'add'])->name('gamefication.add');
            Route::put('/edit/{id}', [GameficationController::class, 'edit'])->where('id', '.*')->name('gamefication.edit');
            Route::delete('/delete/{id}', [GameficationController::class, 'excluir'])->where('id', '.*')->name('gamefication.delete');
        });
    });


    Route::post('/save-subscription', [PushController::class, 'save']);
});

Route::post('/checkout/cliente/pedido/gerar', [CheckoutControlller::class, 'gerarPedido'])->name('profile.checkout.pedido.gerar');
Route::post('/checkout/cliente/pedido/status', [CheckoutControlller::class, 'statusPedido'])->name('profile.checkout.pedido.status');
Route::get('checkout/produto/v1/{id}', [CheckoutControlller::class, 'v1'])->where('id', '.*')->name('profile.checkout.v1');
//Route::get('checkout/produto/v2', [CheckoutControlller::class, 'v2'])->name('profile.checkout.v2');

Route::get('criando-email', function () {
    return view('emails.authenticate');
});
require __DIR__ . '/auth.php';
require __DIR__ . '/groups/adquirentes/cashtime.php';
require __DIR__ . '/groups/adquirentes/cartwave.php';
require __DIR__ . '/groups/adquirentes/apithekey.php';
require __DIR__ . '/groups/adquirentes/simpay.php';
require __DIR__ . '/groups/adquirentes/witetec.php';
require __DIR__ . '/groups/adquirentes/zoompag.php';
require __DIR__ . '/groups/adquirentes/mercadopago.php';
require __DIR__ . '/groups/adquirentes/blupay.php';
Route::get('/teste', function () {
    return view('test');
});

