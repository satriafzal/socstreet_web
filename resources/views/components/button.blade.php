@props([
    'variant' => 'primary', // primary, secondary, danger, ghost
    'size' => 'md', // sm, md, lg
    'type' => 'button',
    'href' => null,
    'class' => '',
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-semibold transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-[#121212] disabled:opacity-50 disabled:cursor-not-allowed select-none';

    $sizes = [
        'sm' => 'px-3 py-1.5 text-xs rounded-lg gap-1.5',
        'md' => 'px-4 py-2 text-sm rounded-xl gap-2',
        'lg' => 'px-6 py-3 text-base rounded-xl gap-2.5',
    ];

    $variants = [
        'primary' => 'bg-[#16A34A] hover:bg-[#15803D] active:bg-[#14532D] text-white shadow-sm shadow-[#16A34A]/20 focus:ring-[#16A34A]',
        'secondary' => 'bg-transparent border border-[#27272A] hover:border-[#3F3F46] hover:bg-[#242424] text-white focus:ring-[#27272A]',
        'danger' => 'bg-transparent text-[#EF4444] hover:bg-[#EF4444]/10 focus:ring-[#EF4444]',
        'ghost' => 'bg-transparent text-[#A1A1AA] hover:text-white hover:bg-[#242424] focus:ring-[#27272A]',
    ];

    $appliedClasses = "{$baseClasses} " . ($sizes[$size] ?? $sizes['md']) . ' ' . ($variants[$variant] ?? $variants['primary']) . " {$class}";
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $appliedClasses]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $appliedClasses]) }}>
        {{ $slot }}
    </button>
@endif

