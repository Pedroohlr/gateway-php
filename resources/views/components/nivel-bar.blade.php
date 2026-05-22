@php
    $setting = \App\Helpers\Helper::getSetting();

    $nivel_atual = auth()->user()->nivelAtual ?? 0;
    $min = $nivel_atual->min ?? 0;
    $max = $nivel_atual->max ?? 1; // evita divisão por zero
    $atual = auth()->user()->depositos()->where('status', 'PAID_OUT')->sum('amount');

    // Cálculo da porcentagem entre min e max
    $progresso = max(0, min(100, (($atual - $min) / ($max - $min)) * 100));


    function formatK($value)
    {
        if ($value >= 1000000 && fmod($value, 1000000) === 0.0) {
            return 'R$ ' . number_format($value / 1000000, 0, ',', '.') . 'M';
        } elseif ($value >= 1000 && fmod($value, 1000) === 0.0) {
            return number_format($value / 1000, 0, ',', '.') . 'k';
        } else {
            return 'R$ ' . number_format($value, 2, ',', '.');
        }
    }
@endphp
<div class="nv-topbar px-2 py-1" style="max-width: 240px;">
    <div class="d-flex align-items-bottom justify-content-center">
        <div style="width: 300px" class="mx-2">
            <div class="d-flex align-items-center justify-content-between">
                <span class="fw-semibold mb-1" style="font-size: 12px;color:var(--color-gateway)!important;">
                    <i class="fa-solid fa-medal me-2 fs-5"></i>
                    {{ $nivel_atual->name }}
                    
                </span>
             
                <div class="d-flex align-items-center">
                    <span style="color:var(--color-gateway)!important;" class="fw-semibold me-1">{{ formatK($atual) }}</span>
                    <span style="color:var(--color-gateway)!important;" class="fw-semibold me-1">| {{ formatK($max) }}</span>
                </div>
            </div>
            <div class="progress " style="width: 100%;height: 10px;">
                <div class="progress-bar progress-bar-striped bg-success" role="progressbar"
                    style="width: {{ $progresso }}%;" aria-valuenow="{{ $progresso }}" aria-valuemin="0"
                    aria-valuemax="100">
                </div>
            </div>
        </div>

    </div>
</div>