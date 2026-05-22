@props([
    'title' => 'Clientes',
    'subtitle' => 'R$ 0,00',
    'icon' => 'user'
    ])

<div class="card radius-10">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <div>
                <p class="mb-0">{{ $title }}</p>
                <h4 class="my-1">{{ $subtitle }}</h4>
            </div>
            <div class="widgets-icons ms-auto"><i data-lucide="{{ $icon }}"></i>
            </div>
        </div>
    </div>
</div>