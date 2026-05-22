@php
$setting = \App\Helpers\Helper::getSetting();
@endphp
<footer class="py-4 mt-auto border-top" style="min-height: 74px">
    <div class="px-5 container-xl">
        <div class="d-flex flex-column flex-sm-row align-items-center justify-content-sm-between small">
            <div class="me-sm-2">&copy; Todos os direitos reservados a <a class="text-white" href="{{ env('APP_URL') }}" target="_blank">{{ $setting->gateway_name }}</a> {{ date('Y') }}</div>
            <div class="d-flex ms-sm-2">
                <a class="text-decoration-none" href="/dashboard">Inicio</a>
                <div class="mx-1">&middot;</div>
                <a class="text-decoration-none" href="{{ url('/doc/terms.pdf') }}">Termos &amp; Condições</a>
            </div>
        </div>
    </div>
</footer>
