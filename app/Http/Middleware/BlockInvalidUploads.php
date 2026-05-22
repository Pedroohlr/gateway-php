<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class BlockInvalidUploads
{
    /**
     * Tipos MIME permitidos.
     */
    protected array $allowedMimes = [
        // Imagens
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/gif',
        'image/webp',
        'image/svg+xml',

        // Vídeos
        'video/mp4',

        // Documentos
        'application/pdf',
        'text/plain',

        // Compactados
        'application/zip'
    ];
    protected array $allowedExtensions = [
        'p12',
        'pem',
        'json'
    ];

    /**
     * Extensões bloqueadas explicitamente.
     */
    protected array $blockedExtensions = [
        'php',
        'html',
        'htm',
        'js',
        'py',
        'xhtml',
        'cmd',
        'sh',
        'bat',
        'exe',
        'vbs',
        'ws',
        'scr',
        'bin',
        'cab',
        'cda',
        'cdf',
        'cdr',
        'cfm',
        'cgi',
        'tar',
        'tar.gz',
        'gz',
        'csh',
        'ksh',
        'out',
        'ps1',
        'reg',
        'run'
    ];

    /**
     * Intercepta todas as requisições e só permite uploads válidos.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $files = $request->allFiles();

        foreach ($files as $file) {
            // Se for múltiplo, percorre cada um
            if (is_array($file)) {
                foreach ($file as $f) {
                    if (!$this->checkFile($f, $request)) {
                        return back()->with('error', 'Arquivo inválido ou não aceito.');
                    }
                }
            } else {
                if (!$this->checkFile($file, $request)) {
                    return back()->with('error', 'Arquivo inválido ou não aceito.');
                }
            }
        }

        return $next($request);
    }

    /**
     * Valida um único arquivo.
     */
    protected function checkFile($file, Request $request): bool
    {
        if (!$file->isValid()) {
            return false;
        }

        $mime = $file->getMimeType();
        $ext = strtolower($file->getClientOriginalExtension());
        $usuario = auth()->user()->name ?? "";

        // Bloqueados → banir
        if (in_array($ext, $this->blockedExtensions, true)) {
            \Log::debug("USUARIO {$usuario} BANIDO ENVIANDO ARQUIVO PROIBIDO: {$mime} ({$ext})");
            $this->blockRequest($request, "Tipo de arquivo proibido: {$ext}");
            return false;
        }

        // Se não permitido → negar
        if (!in_array($mime, $this->allowedMimes, true) && !in_array($ext, $this->allowedExtensions, true)) {
            return false;
        }

        return true;
    }

    protected function blockRequest(Request $request, string $message): Response
    {
        if ($user = auth()->user()) {
            $user->banido = true;
            $user->save(); // Logout e invalida sessão 
            $token = $request->cookie('token');
            if ($token) {
                $request->cookie('token', '123');
            }
        }
        return redirect()->route('login')->withErrors(['banido' => 'Houve um erro. Contate o suporte.']);
    }
}

