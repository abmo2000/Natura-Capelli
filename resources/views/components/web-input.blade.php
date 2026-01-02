@props([
    'type' => 'text',
    'name',
    'label',
    'placeholder' => '',
    'required' => false,
    'value' => '',
    'autofocus' => false,
])

<div>
    <label for="{{ $name }}" class="block text-sm font-medium text-white mb-2">
        {{ $label }}
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    <input 
        type="{{ $type }}" 
        id="{{ $name }}" 
        name="{{ $name }}" 
        value="{{ old($name, $value) }}"
        @if($required) required @endif
        @if($autofocus) autofocus @endif
        class="auth-input {{ $attributes->get('class') }}"
        placeholder="{{ $placeholder }}"
        {{ $attributes->except(['class']) }}
    >
</div>