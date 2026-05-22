@props(['name', 'label', 'value' => ''])
<div class="mt-2 mb-2 col-md-12">
    <label class="form-label" for="{{ $name }}">{{ $label }}</label>
    <input type="text" name="{{ $name }}" id="{{ $name }}" class="form-control" value="{{ $value }}">
</div>
