@props(['title' => 'Socstreet Minisoccer Booking'])

<!DOCTYPE html>
<html lang="id" class="dark scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} | Socstreet</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS Script CDN & Config (Guarantees zero unstyled flicker even before npm build) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'bg-base': '#121212',
                        'bg-surface': '#181818',
                        'bg-card': '#1E1E1E',
                        'bg-card-hover': '#242424',
                        'bg-input': '#1F1F1F',
                        'footer-bg': '#0D0D0D',
                        'brand-primary': '#16A34A',
                        'brand-hover': '#15803D',
                        'brand-active': '#22C55E',
                        'brand-subtle': 'rgba(22, 163, 74, 0.15)',
                        'status-danger': '#EF4444',
                        'text-primary': '#FFFFFF',
                        'text-secondary': '#A1A1AA',
                        'text-muted': '#71717A',
                        'border-subtle': '#27272A',
                        'border-focus': '#16A34A',
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'Inter', 'sans-serif'],
                    },
                    keyframes: {
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(24px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        pulseSlow: {
                            '0%, 100%': { opacity: '0.15', transform: 'scale(1)' },
                            '50%': { opacity: '0.3', transform: 'scale(1.06)' },
                        },
                    },
                    animation: {
                        'fade-in-up': 'fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                        'pulse-slow': 'pulseSlow 6s ease-in-out infinite',
                    }
                }
            }
        }
    </script>

    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        body {
            background-color: #121212;
            color: #FFFFFF;
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        /* Custom scrollbars */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #121212;
        }
        ::-webkit-scrollbar-thumb {
            background: #27272A;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #3F3F46;
        }

        /* Scroll reveal animations */
        .reveal-item {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.75s cubic-bezier(0.16, 1, 0.3, 1), transform 0.75s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform;
        }
        .reveal-item.is-revealed {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body class="bg-[#121212] text-white flex flex-col min-h-screen selection:bg-[#16A34A] selection:text-white">

    {{ $slot }}

    <!-- Notification Toast if any -->
    @if(session('success'))
        <div id="flash-toast" class="fixed bottom-6 right-6 z-50 flex items-center gap-3 px-4 py-3 bg-[#181818] border border-[#16A34A]/40 text-white rounded-xl shadow-xl shadow-[#16A34A]/10 animate-fade-in">
            <div class="w-7 h-7 rounded-lg bg-[#16A34A]/20 flex items-center justify-center text-[#22C55E]">
                <x-icon name="check-circle" class="w-4 h-4" />
            </div>
            <p class="text-xs font-medium">{{ session('success') }}</p>
            <button onclick="document.getElementById('flash-toast').remove()" class="text-[#71717A] hover:text-white ml-2">
                <x-icon name="x" class="w-4 h-4" />
            </button>
        </div>
    @endif

</body>
</html>

