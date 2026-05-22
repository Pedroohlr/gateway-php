<?php
namespace App\Mail;

use App\Models\App;
use App\Models\Smtp;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AuthenticateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $code;

    public $titulo;
    public $mensagem;

    public function __construct($user, $code)
    {
        $smtp = Smtp::first();
        $setting = App::first();

        $this->user = $user;
        $this->code = $code;
        $this->titulo = $smtp->auth_title;
        $mensagem = str_replace(['{nome}', '{gateway}'], [$user->name, $setting->gateway_name], $smtp->auth_message);

        $this->mensagem = $mensagem;
    }

    public function build()
    {
        return $this->subject('Autenticação em duas etapas (2fa)')
            ->view('emails.authenticate');
    }
}
