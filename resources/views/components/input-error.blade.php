@props(['messages'])

@if ($messages)
    <ul style="color:red;" {{ $attributes->merge(['class' => 'text-sm  space-y-1']) }}>
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif
