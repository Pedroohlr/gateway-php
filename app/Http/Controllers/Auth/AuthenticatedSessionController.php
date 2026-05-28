<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Jobs\SendEmailJob;
use App\Mail\AuthenticateMail;
use App\Models\User;
use App\Models\App;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function login2(Request $request)
    {

        return view('auth.login2');
    }

    public function validateCode(Request $request)
    {
        $email = $request->email;
        $password = $request->password;
        $code = $request->code;
        $new_code = $request->has('new_code');

        $user = User::where('email', $email)->first();

        if (is_null($user) || is_null($code) || (string) $user->mail_code !== (string) $code) {

            if ($new_code) {
                $new = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                $user->update(['mail_code' => $new]);
                dispatch(new SendEmailJob($user, $new));

                return back()->with('mail_code', true)
                    ->with('email', $email)
                    ->with('password', $password);
            }

            return back()->with('mail_code', true)
                ->with('email', $email)
                ->with('password', $password)
                ->with('codigo_error', 'Código inválido. Verifique e tente novamente.');
        }

        // Zera o código depois da autenticação
        $user->update(['mail_code' => null]);

        // Login manual (evita erro do LoginRequest)
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false))
            ->with('success', 'Bem vindo de volta!');
    }

    public function step1Login(LoginRequest $request): RedirectResponse
    {
        $email = $request->email;
        $password = $request->password;


        $gerente = App::value('contato') ?? '';

        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return back()->with('error', 'Usuário e/ou senha incorreto(s)');
        }

        if ($user->banido == 1 || $user->status == 99) {
            $contato = "https://wa.me/55{$gerente}?text=Ol%C3%A1%20Estou%20com%20problemas%20para%20acessar%20minha%20conta,%20preciso%20de%20ajuda.";

            return back()
                ->with('block', 'Usuário sem permissões. Entre em contato com seu gerente.')
                ->with('contato', $contato);
        }

        if (app()->environment('local')) {
            Auth::login($user);
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard', absolute: false))->with('success', 'Bem vindo de volta!');
        }

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user->update(['mail_code' => $code]);
        dispatch(new SendEmailJob($user, $code));

        return back()->with('mail_code', true)
            ->with('email', $email)
            ->with('password', $password);

    }


    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $email = $request->email;
        $password = $request->password;


        $gerente = App::value('contato') ?? '';

        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return back()->with('error', 'Usuário e/ou senha incorreto(s)');
            session()->keep(['error']);
        }

        if ($user->banido == 1 || $user->status == 99) {
            $contato = "https://wa.me/55{$gerente}?text=Ol%C3%A1%20Estou%20com%20problemas%20para%20acessar%20minha%20conta,%20preciso%20de%20ajuda.";

            return back()
                ->with('block', 'Usuário sem permissões. Entre em contato com seu gerente.')
                ->with('contato', $contato);
        }



        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false))->with('success', "Bem vindo de volta!");
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login')->with('success', "Até breve!");
    }

    public function forcePasswordUpdate(Request $request)
    {
        $request->validate([
            'new_password' => 'required|string|confirmed|min:6',
        ]);

        $user = auth()->user();
        $user->password = Hash::make($request->new_password);
        $user->password_temp = false; // Remove flag
        $user->save();

        return redirect()->back()->with('status', 'Senha alterada com sucesso.');
    }
}
