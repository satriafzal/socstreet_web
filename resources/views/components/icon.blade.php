@props([
    'name' => 'info',
    'class' => 'w-5 h-5'
])

@switch($name)
    @case('soccer')
    @case('ball')
        <svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <circle cx="12" cy="12" r="10" />
            <polygon points="12 8, 15.5 10.5, 14 14.5, 10 14.5, 8.5 10.5" stroke-linejoin="round" />
            <line x1="12" y1="8" x2="12" y2="2" />
            <line x1="15.5" y1="10.5" x2="21.5" y2="8.5" />
            <line x1="14" y1="14.5" x2="18" y2="20.5" />
            <line x1="10" y1="14.5" x2="6" y2="20.5" />
            <line x1="8.5" y1="10.5" x2="2.5" y2="8.5" />
        </svg>
        @break

    @case('calendar')
        <svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
            <line x1="16" y1="2" x2="16" y2="6" />
            <line x1="8" y1="2" x2="8" y2="6" />
            <line x1="3" y1="10" x2="21" y2="10" />
        </svg>
        @break

    @case('clock')
        <svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <circle cx="12" cy="12" r="10" />
            <polyline points="12 6 12 12 16 14" />
        </svg>
        @break

    @case('map-pin')
        <svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
            <circle cx="12" cy="10" r="3" />
        </svg>
        @break

    @case('heart')
        <svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
        </svg>
        @break

    @case('check')
        <svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <polyline points="20 6 9 17 4 12" />
        </svg>
        @break

    @case('check-circle')
        <svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        @break

    @case('x')
        <svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <line x1="18" y1="6" x2="6" y2="18" />
            <line x1="6" y1="6" x2="18" y2="18" />
        </svg>
        @break

    @case('shield')
        <svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
        </svg>
        @break

    @case('user')
        <svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
            <circle cx="12" cy="7" r="4" />
        </svg>
        @break

    @case('lock')
        <svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
        </svg>
        @break

    @case('chevron-down')
        <svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <polyline points="6 9 12 15 18 9" />
        </svg>
        @break

    @case('chevron-right')
        <svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <polyline points="9 18 15 12 9 6" />
        </svg>
        @break

    @case('chevron-left')
        <svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <polyline points="15 18 9 12 15 6" />
        </svg>
        @break

    @case('arrow-right')
        <svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <line x1="5" y1="12" x2="19" y2="12" />
            <polyline points="12 5 19 12 12 19" />
        </svg>
        @break

    @case('arrow-left')
        <svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <line x1="19" y1="12" x2="5" y2="12" />
            <polyline points="12 19 5 12 12 5" />
        </svg>
        @break

    @case('logout')
        <svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
            <polyline points="16 17 21 12 16 7" />
            <line x1="21" y1="12" x2="9" y2="12" />
        </svg>
        @break

    @case('credit-card')
        <svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <rect x="1" y="4" width="22" height="16" rx="2" ry="2" />
            <line x1="1" y1="10" x2="23" y2="10" />
        </svg>
        @break

    @case('wallet')
        <svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path d="M20 7H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z" />
            <path d="M16 3H4a2 2 0 0 0-2 2v2" />
            <circle cx="16" cy="14" r="1.5" />
        </svg>
        @break

    @case('qr-code')
        <svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <rect x="3" y="3" width="7" height="7" />
            <rect x="14" y="3" width="7" height="7" />
            <rect x="3" y="14" width="7" height="7" />
            <path d="M14 14h3v3h-3z" />
            <path d="M20 14v3" />
            <path d="M14 20h6" />
        </svg>
        @break

    @case('lightbulb')
    @case('lighting')
        <svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <line x1="12" y1="1" x2="12" y2="3" />
            <line x1="12" y1="21" x2="12" y2="23" />
            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64" />
            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78" />
            <line x1="1" y1="12" x2="3" y2="12" />
            <line x1="21" y1="12" x2="23" y2="12" />
            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36" />
            <line x1="18.36" y1="5.64" x2="19.78" y2="4.22" />
            <circle cx="12" cy="12" r="5" />
        </svg>
        @break

    @case('shower')
        <svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path d="M4 4h7a4 4 0 0 1 4 4v12" />
            <circle cx="15" cy="5" r="1" />
            <line x1="12" y1="14" x2="12.01" y2="14" />
            <line x1="10" y1="17" x2="10.01" y2="17" />
            <line x1="14" y1="17" x2="14.01" y2="17" />
        </svg>
        @break

    @case('wifi')
        <svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path d="M5 12.55a11 11 0 0 1 14.08 0" />
            <path d="M1.42 9a16 16 0 0 1 21.16 0" />
            <path d="M8.53 16.11a6 6 0 0 1 6.95 0" />
            <line x1="12" y1="20" x2="12.01" y2="20" />
        </svg>
        @break

    @case('car')
        <svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path d="M14 16H9m10 0h3v-3.15a1 1 0 0 0-.84-.99L16 11l-2.7-3.6a1 1 0 0 0-.8-.4H7.5a1 1 0 0 0-.8.4L4 11l-5.16.86a1 1 0 0 0-.84.99V16h3" />
            <circle cx="6.5" cy="16.5" r="2.5" />
            <circle cx="16.5" cy="16.5" r="2.5" />
        </svg>
        @break

    @case('coffee')
        <svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path d="M18 8h1a4 4 0 0 1 0 8h-1" />
            <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z" />
            <line x1="6" y1="1" x2="6" y2="4" />
            <line x1="10" y1="1" x2="10" y2="4" />
            <line x1="14" y1="1" x2="14" y2="4" />
        </svg>
        @break

    @case('sparkles')
        <svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83" />
        </svg>
        @break

    @case('info')
    @default
        <svg class="{{ $class }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <circle cx="12" cy="12" r="10" />
            <line x1="12" y1="16" x2="12" y2="12" />
            <line x1="12" y1="8" x2="12.01" y2="8" />
        </svg>
@endswitch

