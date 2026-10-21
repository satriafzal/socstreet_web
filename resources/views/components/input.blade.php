@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => null,
    'placeholder' => '',
    'required' => false,
    'error' => null,
    'help' => null,
    'class' => '',
    'icon' => null,
])

<div class="space-y-1.5 w-full">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-[#A1A1AA]">
            {{ $label }}
            @if($required)
                <span class="text-[#EF4444]">*</span>
            @endif
        </label>
    @endif

    <div class="relative rounded-lg">
        @if($icon)
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#71717A]">
                <x-icon :name="$icon" class="w-4 h-4" />
            </div>
        @endif

        <input
            id="{{ $name }}"
            name="{{ $name }}"
            type="{{ $type }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            @if($required) required @endif
            {{ $attributes->merge([
                'class' => 'w-full h-[46px] ' . ($icon ? 'pl-10 ' : 'px-4 ') . 'pr-4 bg-[#1F1F1F] text-white placeholder-[#71717A] text-sm rounded-lg border ' . 
                ($error ? 'border-[#EF4444] focus:border-[#EF4444] focus:ring-1 focus:ring-[#EF4444]' : 'border-[#27272A] focus:border-[#16A34A] focus:ring-1 focus:ring-[#16A34A]') . 
                ' outline-none transition-all duration-200 ' . $class
            ]) }}
        />
    </div>

    @if($error)
        <p class="text-xs text-[#EF4444] mt-1">{{ $error }}</p>
    @elseif($help)
        <p class="text-xs text-[#71717A] mt-1">{{ $help }}</p>
    @endif
</div>

