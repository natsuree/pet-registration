@props(['label', 'name', 'type' => 'text', 'placeholder' => '', 'value' => '', 'required' => false, 'id' => null, 'autocomplete' => null])

<div class="field-group">
    <label for="{{ $id ?? $name }}">{{ $label }}</label>
    <input
        id="{{ $id ?? $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ $value }}"
        placeholder="{{ $placeholder }}"
        @if($required) required @endif
        @if($autocomplete) autocomplete="{{ $autocomplete }}" @endif
    >
</div>
