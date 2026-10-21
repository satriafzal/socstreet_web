@props([
    'variant' => 'card', // card (#1E1E1E), surface (#181818), raw
    'hoverable' => false,
    'padding' => 'p-6',
    'rounded' => 'rounded-2xl',
    'class' => '',
])

@php
    $bgClasses = match($variant) {
        'surface' => 'bg-[#181818]',
        'raw' => '',
        default => 'bg-[#1E1E1E]',
    };

    $hoverClasses = $hoverable ? 'hover:bg-[#242424] hover:border-[#3F3F46] transition-all duration-200' : '';
    $borderClasses = 'border border-[#27272A]';

    $appliedClasses = "{$bgClasses} {$borderClasses} {$rounded} {$padding} {$hoverClasses} {$class}";
@endphp

<div {{ $attributes->merge(['class' => $appliedClasses]) }}>
    {{ $slot }}
</div>

