@props([
    'variant' => 'success', // success, warning, danger, neutral, brand
    'size' => 'sm',
    'class' => '',
])

@php
    $baseClasses = 'inline-flex items-center font-semibold uppercase tracking-wider rounded-full transition-colors';

    $sizes = [
        'xs' => 'px-2 py-0.5 text-[10px] gap-1',
        'sm' => 'px-2.5 py-1 text-xs gap-1.5',
        'md' => 'px-3 py-1.5 text-xs gap-1.5',
    ];

    $variants = [
        'success' => 'bg-[#16A34A]/15 text-[#22C55E] border border-[#22C55E]/20',
        'warning' => 'bg-amber-500/15 text-amber-400 border border-amber-500/20',
        'danger' => 'bg-[#EF4444]/15 text-[#EF4444] border border-[#EF4444]/20',
        'neutral' => 'bg-[#27272A] text-[#A1A1AA] border border-[#3F3F46]/30',
        'brand' => 'bg-[#16A34A] text-white',
    ];

    $appliedClasses = "{$baseClasses} " . ($sizes[$size] ?? $sizes['sm']) . ' ' . ($variants[$variant] ?? $variants['success']) . " {$class}";
@endphp

<span {{ $attributes->merge(['class' => $appliedClasses]) }}>
    {{ $slot }}
</span>

