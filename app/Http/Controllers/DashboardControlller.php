<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Helpers\Helper;
use App\Models\User;

class DashboardControlller extends Controller
{
    public function index(Request $request)
    {
        
        $nome = Auth::user()->name;
        $status = Auth::user()->status;
        $permission = Auth::user()->permission;
        if(auth()->user()->permission != 9){
            Helper::calculaSaldoLiquido(auth()->user()->user_id);
            $userId = Auth::user()->user_id;
    
            // Buscar as últimas 4 solicitações
            $solicitacoes = DB::table('solicitacoes')
                ->where('user_id', $userId)
                ->orderByDesc('id')
                ->limit(4)
                ->get();
    
            $solicitacoesPaid = DB::table('solicitacoes')
                ->where('user_id', $userId)
                ->where('status', 'PAID_OUT')
                ->orderByDesc('id')
                ->get();
    
            // Buscar as últimas 4 solicitações
            $solicitacoesCashOut = DB::table('solicitacoes_cash_out')
                ->where('user_id', $userId)
                ->orderByDesc('id')
                ->limit(4)
                ->get();
    
            // Combinar as duas coleções
            $ultimasTransacoes = $solicitacoes->merge($solicitacoesCashOut);
    
            // Ordenar a coleção combinada pela data (date ou date)
            $ultimasTransacoes = $ultimasTransacoes->sortByDesc(function ($row) {
                // Verificar se o campo 'date' existe e retornar como Carbon
                if (isset($row->date)) {
                    return Carbon::parse($row->date);
                }
                // Caso contrário, usar o campo 'date'
                return Carbon::parse($row->date);
            });
    
            // Limitar para as últimas 4 transações, após a ordenação
            $ultimasTransacoes = $ultimasTransacoes->take(4);
    
            // Consultar o número de linhas com status = 'PAID_OUT'
            $totalPaidOut = DB::table('solicitacoes')
                ->where('user_id', $userId)
                ->where('status', 'PAID_OUT')
                ->count();
    
            // Consultar o número total de solicitações
            $totalRequests = DB::table('solicitacoes')
                ->where('user_id', $userId)
                ->count();
    
            // Soma dos valores na coluna amount para 'PAID_OUT'
            $sumAmountPaidOut = DB::table('solicitacoes')
                ->where('user_id', $userId)
                ->where('status', 'PAID_OUT')
                ->sum('amount');
    
            // Soma dos depósitos líquidos
            $sumDepositoLiquido = DB::table('solicitacoes')
                ->where('user_id', $userId)
                ->where('status', 'PAID_OUT')
                ->sum('deposito_liquido');
    
            // Soma dos saques aprovados
            $sumSaquesAprovados = DB::table('solicitacoes_cash_out')
                ->where('user_id', $userId)
                ->where('status', 'COMPLETED')
                ->sum('cash_out_liquido');
    
            $saqueApvSom =  DB::table('solicitacoes_cash_out')
                ->where('user_id', $userId)
                ->where('status', 'COMPLETED')
                ->sum('cash_out_liquido');
    
            // Calcular o saldo líquido
            $saldoliquido = $sumDepositoLiquido - ($saqueApvSom ?: 0);
    
            // Data real mais recente
            $realDate = DB::table('solicitacoes')
                ->where('user_id', $userId)
                ->max('date');
    
            // Gráfico de valores diários
            $firstDayOfMonth = now()->startOfMonth()->toDateString();
            $lastDayOfMonth = now()->endOfMonth()->toDateString();
    
            $dailyValues = DB::table('solicitacoes')
                ->selectRaw('DATE(date) as date, SUM(amount) as total')
                ->where('user_id', $userId)
                ->where('status', 'PAID_OUT')
                ->whereBetween('date', [$firstDayOfMonth, $lastDayOfMonth])
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->pluck('total', 'date')
                ->toArray();
    
            $dates = [];
            $values = [];
            $currentDate = $firstDayOfMonth;
            while ($currentDate <= $lastDayOfMonth) {
                $formattedDate = date('d M Y', strtotime($currentDate));
                $dates[] = $formattedDate;
                $values[] = $dailyValues[$currentDate] ?? 0;
                $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
            }
    
            $nome = Auth::user()->name;
            $status = Auth::user()->status;
            $permission = Auth::user()->permission;
            $result_solicitacoes = [];
            $frase = $this->getFrase();
            return view(
                'dashboard',
                compact(
                    'nome',
                    'status',
                    'result_solicitacoes',
                    'permission',
                    'solicitacoes',
                    'solicitacoesPaid',
                    'solicitacoesCashOut',
                    'totalPaidOut',
                    'totalRequests',
                    'sumAmountPaidOut',
                    'sumDepositoLiquido',
                    'sumSaquesAprovados',
                    'ultimasTransacoes',
                    'saldoliquido',
                    'realDate',
                    'dates',
                    'values',
                    'frase'
                )
            );
        }else {
            return redirect()->route('gerencia.index');
        }
    }

    public function getFrase()
    {
        $frases = [
            "Invista no seu futuro hoje.",
            "Dinheiro é um servo, não um mestre.",
            "Cada centavo conta.",
            "Poupar é ganhar duas vezes.",
            "Dinheiro bem investido multiplica.",
            "Riqueza vem da disciplina.",
            "Seu futuro financeiro depende de você.",
            "Ganhe, economize, invista, repita.",
            "O segredo é a constância.",
            "Controle seu dinheiro ou ele controlará você.",
            "Riqueza começa com uma mentalidade próspera.",
            "Trabalhe duro, invista com inteligência.",
            "Evite dívidas, conquiste liberdade.",
            "Paciência gera riqueza.",
            "O tempo é seu maior aliado nos investimentos.",
            "Gaste menos do que ganha.",
            "Aprenda sobre dinheiro todos os dias.",
            "A liberdade financeira é um objetivo real.",
            "Multiplique suas fontes de renda.",
            "O conhecimento financeiro é poder.",
            "Grandes fortunas começam pequenas.",
            "Seja estratégico com suas finanças.",
            "Faça seu dinheiro trabalhar para você.",
            "Dinheiro bem administrado gera paz.",
            "O sucesso financeiro começa com um plano.",
            "Cada escolha financeira importa.",
            "Evite gastos impulsivos.",
            "Seja dono do seu destino financeiro.",
            "O poder da riqueza está na educação financeira.",
            "Pequenos hábitos criam grandes riquezas.",
            "Dinheiro é uma ferramenta, use com sabedoria.",
            "Compre ativos, não passivos.",
            "Sonhe grande, economize sabiamente.",
            "Riqueza é construída com paciência.",
            "O primeiro passo é começar.",
            "Trabalhe para aprender, não apenas para ganhar.",
            "Suas ações definem seu futuro financeiro.",
            "Não espere, comece agora.",
            "Seja disciplinado com seu dinheiro.",
            "Invista antes de gastar.",
            "A riqueza começa com uma decisão.",
            "Seja consistente na sua jornada financeira.",
            "Dívidas são correntes invisíveis.",
            "A mentalidade rica supera desafios.",
            "O orçamento é seu melhor amigo.",
            "O tempo recompensa investidores disciplinados.",
            "Não se trata de quanto ganha, mas de como usa.",
            "Sucesso financeiro exige planejamento.",
            "A independência financeira é um estilo de vida.",
            "Siga o dinheiro inteligente, não o rápido.",
            "Grandes sonhos exigem controle financeiro.",
            "Dinheiro não compra felicidade, mas compra liberdade.",
            "Cada real investido é um passo para a liberdade.",
            "Gaste com propósito, não por impulso.",
            "A riqueza começa com pequenos passos.",
            "Investir é plantar para colher no futuro.",
            "A liberdade financeira é construída, não ganha.",
            "Controle seus gastos antes que eles controlem você.",
            "Pense no longo prazo, não no imediato.",
            "A paciência é a maior aliada do investidor.",
            "Dinheiro cresce onde há disciplina.",
            "Renda passiva é a chave para a liberdade.",
            "Não trabalhe por dinheiro, faça o dinheiro trabalhar por você.",
            "Gastar menos do que ganha é um superpoder.",
            "Todo milionário já foi um poupador.",
            "O tempo no mercado é melhor que tentar prever o mercado.",
            "Pobreza não é falta de dinheiro, é falta de educação financeira.",
            "Dinheiro bem investido traz segurança.",
            "Não busque atalhos, busque conhecimento.",
            "Invista mais em ativos, menos em passivos.",
            "Ganhar dinheiro é uma habilidade que pode ser aprendida.",
            "Nunca gaste antes de ganhar.",
            "Disciplina hoje, riqueza amanhã.",
            "O sucesso financeiro exige sacrifício temporário.",
            "O mercado recompensa os pacientes.",
            "Cada compra deve ter um propósito.",
            "Não gaste para impressionar, gaste para prosperar.",
            "Aposentadoria tranquila começa agora.",
            "Fique rico devagar, não pobre rápido.",
            "O dinheiro ama quem sabe usá-lo.",
            "Gaste menos tempo consumindo e mais tempo criando.",
            "Economize como um pessimista, invista como um otimista.",
            "Dinheiro parado é dinheiro perdido.",
            "Aprender a investir é ganhar tempo de vida.",
            "O dinheiro não some, ele muda de dono.",
            "Grandes conquistas exigem planejamento.",
            "Seu bolso reflete seus hábitos.",
            "A melhor época para investir foi ontem, a segunda melhor é hoje.",
            "Invista no que entende, entenda no que investe.",
            "A riqueza é construída no dia a dia.",
            "Renda extra é sempre uma boa ideia.",
            "O medo impede, a educação financeira liberta.",
            "Multiplique seu dinheiro, não suas dívidas.",
            "Trabalhe para criar, não apenas para consumir.",
            "Evite comparações, foque no seu crescimento.",
            "Investir sem conhecimento é apostar.",
            "Toda pequena economia faz diferença.",
            "A preguiça financeira custa caro.",
            "A liberdade financeira é um direito, mas exige esforço.",
            "Não dependa de uma única fonte de renda.",
            "Cada escolha financeira define seu amanhã.",
            "Menos status, mais patrimônio.",
            "A inteligência financeira vale mais que um salário alto.",
            "A tranquilidade vale mais que bens materiais.",
            "Corte gastos desnecessários sem cortar felicidade.",
            "O dinheiro é um reflexo do seu conhecimento.",
            "Ser rico é questão de hábito, não de sorte.",
            "A independência financeira é conquistada, não dada.",
            "O segredo do sucesso financeiro é a persistência.",
            "A riqueza silenciosa é a verdadeira riqueza.",
            "Gaste hoje pensando no amanhã.",
            "Saber ganhar é importante, saber manter é essencial.",
            "A paciência e o tempo fazem milionários.",
            "O grande erro financeiro é não planejar.",
            "Toda riqueza começa com uma mentalidade forte.",
            "O futuro rico começa no presente consciente.",
            "Pessoas ricas acumulam ativos, não passivos.",
            "A educação financeira é um investimento, não um custo.",
            "O primeiro passo para a riqueza é sair das dívidas.",
            "O dinheiro cresce na mão de quem sabe usá-lo.",
            "Tenha uma reserva antes de ter luxos.",
            "Investir não é para os ricos, é para quem quer ser rico.",
            "A independência financeira é construída com escolhas diárias.",
            "Sua conta bancária reflete suas decisões.",
            "Fuja das dívidas, corra para os investimentos.",
            "A renda extra pode mudar sua vida.",
            "Quem planta conhecimento colhe riqueza.",
            "Viva abaixo do seu padrão e invista a diferença.",
            "O sucesso financeiro começa com pequenos passos diários."
        ];


        return $frases[array_rand($frases)];
    }
}
