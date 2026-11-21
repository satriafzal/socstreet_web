@props([
    'title' => 'Admin Dashboard',
    'active' => null
])

<!DOCTYPE html>
<html lang="id" class="dark scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} | Socstreet Admin</title>

    <!-- Google Fonts: Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- Google Material Symbols Outlined -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

    <!-- Tailwind CSS Script CDN & Configuration (Exact tokens from md/desain.md & socstreet_prd_rules.md) -->
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
                        'primary': '#16A34A',
                        'primary-container': '#00873a',
                        'error': '#EF4444',
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'Inter', 'sans-serif'],
                        mono: ['ui-monospace', 'SFMono-Regular', 'Menlo', 'Monaco', 'Consolas', 'monospace'],
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
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        /* Material Symbols Outlined Icon defaults */
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
            user-select: none;
        }

        /* Custom scrollbar for pitch black dark mode */
        ::-webkit-scrollbar {
            width: 7px;
            height: 7px;
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
    </style>
</head>
<body class="bg-[#121212] text-white min-h-screen antialiased selection:bg-[#16A34A] selection:text-white">

    <!-- Separated Sidebar Component -->
    <x-admin.sidebar :active="$active" />

    <!-- Main Wrapper Offset by 64 (256px) on Desktop -->
    <div class="lg:pl-64 flex flex-col min-h-screen">
        
        <!-- Separated Header Component -->
        <x-admin.header :title="$title" />

        <!-- Main Content Area -->
        <main class="flex-1 pt-16 bg-[#121212] px-4 sm:px-6 lg:px-8 py-6 w-full max-w-7xl mx-auto">
            {{ $slot }}
        </main>

        <!-- Minimal Admin Footer -->
        <footer class="px-6 py-4 border-t border-[#1F1F1F] text-center text-xs text-[#71717A] bg-[#0D0D0D]">
            <span>Socstreet Minisoccer Management &bull; Admin Portal &bull; Single Arena Operational Control</span>
        </footer>
    </div>

    <!-- Global Floating Action Toast -->
    <div id="adminActionToast" 
         class="fixed bottom-6 right-6 z-50 flex items-center gap-3 px-4 py-3 bg-[#181818] border border-[#27272a] text-white rounded-xl shadow-2xl transition-all duration-300 transform translate-y-24 opacity-0 pointer-events-none">
        <div id="adminToastIconContainer" class="w-8 h-8 rounded-full bg-[#14291c] border border-[#16a34a] flex items-center justify-center text-[#4ade80]">
            <span id="adminToastIcon" class="material-symbols-outlined text-[18px]">check</span>
        </div>
        <div class="flex flex-col">
            <span id="adminToastTitle" class="text-xs font-bold text-white">Berhasil</span>
            <span id="adminToastMessage" class="text-[11px] text-[#a1a1aa]">Tindakan telah disimpan.</span>
        </div>
        <button type="button" onclick="hideAdminToast()" class="text-[#71717a] hover:text-white ml-2">
            <span class="material-symbols-outlined text-[16px]">close</span>
        </button>
    </div>

    <!-- Admin Helper Scripts -->
    <script>
        function toggleAdminSidebar() {
            const sidebar = document.getElementById('admin-sidebar');
            const backdrop = document.getElementById('admin-sidebar-backdrop');
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }
        }

        let toastTimeout;
        function showAdminToast(title, message, isSuccess = true) {
            const toast = document.getElementById('adminActionToast');
            const toastTitle = document.getElementById('adminToastTitle');
            const toastMessage = document.getElementById('adminToastMessage');
            const toastIcon = document.getElementById('adminToastIcon');
            const iconContainer = document.getElementById('adminToastIconContainer');

            if (!toast) return;

            toastTitle.innerText = title;
            toastMessage.innerText = message;

            if (isSuccess) {
                iconContainer.className = 'w-8 h-8 rounded-full bg-[#14291c] border border-[#16a34a] flex items-center justify-center text-[#4ade80]';
                toastIcon.innerText = 'check';
                toast.style.borderColor = 'rgba(22, 163, 74, 0.4)';
            } else {
                iconContainer.className = 'w-8 h-8 rounded-full bg-[#2d1214] border border-[#ef4444] flex items-center justify-center text-[#f87171]';
                toastIcon.innerText = 'close';
                toast.style.borderColor = 'rgba(239, 68, 68, 0.4)';
            }

            toast.classList.remove('translate-y-24', 'opacity-0', 'pointer-events-none');
            toast.classList.add('translate-y-0', 'opacity-100');

            clearTimeout(toastTimeout);
            toastTimeout = setTimeout(() => {
                hideAdminToast();
            }, 3500);
        }

        function hideAdminToast() {
            const toast = document.getElementById('adminActionToast');
            if (!toast) return;
            toast.classList.add('translate-y-24', 'opacity-0', 'pointer-events-none');
            toast.classList.remove('translate-y-0', 'opacity-100');
        }
    </script>

    @stack('scripts')
</body>
</html>
