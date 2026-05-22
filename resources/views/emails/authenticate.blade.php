@php
    $setting = \App\Helpers\Helper::getSetting();
    $smtp = \App\Models\Smtp::first();
@endphp

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $titulo }}</title>
</head>

<body style="margin:0; padding:0; background:#f5f5f5; font-family:Arial, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f5f5f5; padding:20px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" border="0"
                    style="background:#ffffff; border-radius:10px; overflow:hidden;">

                    <!-- HEADER -->
                    <tr>
                        <td align="center" style="background:{{ $smtp->color }}; padding:40px;">
                            <img src="{{ url($smtp->image) }}" width="64" alt="Logo" style="display:block;">
                        </td>
                    </tr>

                    <!-- TÍTULO -->
                    <tr>
                        <td align="center" style="padding:20px 30px;">
                            <h1 style="margin:0; font-size:24px; color:#333;">
                                {!! $titulo !!}
                            </h1>
                        </td>
                    </tr>

                    <!-- MENSAGEM -->
                    <tr>
                        <td style="padding:0 30px 20px 30px; color:#555; font-size:15px; line-height:22px;">
                            {!! $mensagem !!}
                        </td>
                    </tr>

                    <!-- CÓDIGO -->
                    <tr>
                        <td align="center" style="background:#ebebeb; padding:30px 0;">
                            <h1 style="margin:0; letter-spacing:6px; font-size:32px; color:#333;">
                                {{ $code }}
                            </h1>
                        </td>
                    </tr>

                    <!-- AVISO -->
                    <tr>
                        <td style="padding:30px; font-size:14px; color:#777;">
                            Caso não tenha solicitado este código, por favor ignore este e-mail.
                        </td>
                    </tr>

                    <!-- RODAPÉ -->
                    <tr>
                        <td align="center" style="background:#ebebeb; padding:20px; font-size:13px; color:#777;">
                            © {{ date('Y') }} {{ $setting->gateway_name }}. Todos os direitos reservados.
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>

</html>