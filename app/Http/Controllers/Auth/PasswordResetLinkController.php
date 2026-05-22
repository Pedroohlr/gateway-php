<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }
 
    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Verifica se usuário existe
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'E-mail não encontrado'])->withInput();
        }

        // Gera nova senha
        $newPassword = Str::random(10);
        $user->password = Hash::make($newPassword);
        $user->password_temp = true;
        $user->save();

        // Envia e-mail
        Mail::to($user->email)->send(new \App\Mail\NewPasswordMail($user, $newPassword));

        return back()->with('success', 'Uma nova senha foi enviada para seu e-mail.');
    }
}
