@props(['title' => 'Dashboard Member'])

<x-layouts.app :title="$title">
    <div class="flex min-h-screen bg-[#121212]">
        <!-- Mobile Sidebar Overlay -->
        <div id="mobile-sidebar-backdrop" class="fixed inset-0 z-40 bg-black/70 backdrop-blur-sm hidden md:hidden" onclick="toggleSidebar()"></div>

        <!-- Dashboard Sidebar (240px Fixed) -->
        <aside id="dashboard-sidebar" class="fixed inset-y-0 left-0 z-50 w-[240px] bg-[#121212] border-r border-[#27272A] flex flex-col justify-between transition-transform duration-300 -translate-x-full md:translate-x-0">
            <!-- Sidebar Header & Links -->
            <div>
                <!-- Brand / Logo -->
                <div class="h-[72px] px-6 border-b border-[#27272A] flex items-center justify-between">
                    <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-[#16A34A]/15 border border-[#16A34A]/30 flex items-center justify-center text-[#22C55E]">
                            <x-icon name="soccer" class="w-4 h-4" />
                        </div>
                        <div class="flex items-baseline">
                            <span class="text-lg font-bold tracking-tight text-white">Socstreet</span>
                            <span class="w-1.5 h-1.5 rounded-full bg-[#16A34A] ml-0.5"></span>
                        </div>
                    </a>
                    <!-- Close button for mobile -->
                    <button class="md:hidden text-[#71717A] hover:text-white" onclick="toggleSidebar()">
                        <x-icon name="x" class="w-5 h-5" />
                    </button>
                </div>

                <!-- Single Arena Indicator -->
                <div class="p-4 mx-3 my-4 rounded-xl bg-[#181818] border border-[#27272A]">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-[#22C55E] animate-pulse"></span>
                        <span class="text-[11px] font-semibold text-[#A1A1AA] uppercase tracking-wider">Single Arena Field</span>
                    </div>
                    <p class="text-xs font-bold text-white mt-1">Socstreet Main Arena</p>
                    <p class="text-[11px] text-[#71717A] mt-0.5">FIFA Sintetis • 42x25m</p>
                </div>

                <!-- Navigation List -->
                <nav class="px-3 space-y-1.5">
                    @php
                        $currentRoute = request()->route() ? request()->route()->getName() : '';
                    @endphp

                    <!-- Jadwal Lapangan -->
                    <a href="{{ route('booking.schedule') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ str_starts_with($currentRoute, 'booking.schedule') || str_starts_with($currentRoute, 'booking.checkout') ? 'bg-[#16A34A] text-white shadow-md shadow-[#16A34A]/25' : 'text-[#A1A1AA] hover:text-white hover:bg-[#1E1E1E]' }}">
                        <x-icon name="calendar" class="w-4 h-4 shrink-0" />
                        <span>Jadwal Lapangan</span>
                    </a>

                    <!-- Booking Saya -->
                    <a href="{{ route('booking.my') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ str_starts_with($currentRoute, 'booking.my') ? 'bg-[#16A34A] text-white shadow-md shadow-[#16A34A]/25' : 'text-[#A1A1AA] hover:text-white hover:bg-[#1E1E1E]' }}">
                        <x-icon name="check-circle" class="w-4 h-4 shrink-0" />
                        <span>Booking Saya</span>
                    </a>

                    <!-- Pengaturan Akun -->
                    <a href="{{ route('profile.settings') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ str_starts_with($currentRoute, 'profile.settings') ? 'bg-[#16A34A] text-white shadow-md shadow-[#16A34A]/25' : 'text-[#A1A1AA] hover:text-white hover:bg-[#1E1E1E]' }}">
                        <x-icon name="user" class="w-4 h-4 shrink-0" />
                        <span>Pengaturan Akun</span>
                    </a>

                    <!-- Back to Home -->
                    <a href="{{ route('home') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-[#71717A] hover:text-white hover:bg-[#1E1E1E] transition-all">
                        <x-icon name="arrow-left" class="w-4 h-4 shrink-0" />
                        <span>Halaman Utama</span>
                    </a>
                </nav>
            </div>

            <!-- Sticky Bottom Area (User snippet & Logout) -->
            <div class="p-4 border-t border-[#27272A] bg-[#121212] space-y-3">
                <!-- User Profile Snippet -->
                <div class="flex items-center gap-3 px-2 py-1">
                    <div class="w-8 h-8 rounded-full bg-[#1E1E1E] border border-[#27272A] flex items-center justify-center text-[#22C55E] font-bold text-xs">
                        U
                    </div>
                    <div class="truncate flex-1">
                        <p class="text-xs font-semibold text-white truncate">Player_One</p>
                        <p class="text-[10px] text-[#71717A] truncate">Member Socstreet</p>
                    </div>
                </div>

                <!-- Logout Button (Design 4.D.2) -->
                <a href="{{ route('home') }}" class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-lg text-xs font-semibold text-[#EF4444] hover:bg-[#EF4444]/10 transition-colors">
                    <x-icon name="logout" class="w-4 h-4" />
                    <span>Keluar Akun</span>
                </a>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 md:pl-[240px] flex flex-col min-w-0">
            <!-- Mobile Header Topbar -->
            <header class="md:hidden sticky top-0 z-30 h-16 bg-[#121212]/95 backdrop-blur-md border-b border-[#27272A] px-4 flex items-center justify-between">
                <button onclick="toggleSidebar()" class="p-2 rounded-lg bg-[#1E1E1E] text-white border border-[#27272A]">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <line x1="4" y1="6" x2="20" y2="6" stroke-width="2" stroke-linecap="round"/>
                        <line x1="4" y1="12" x2="20" y2="12" stroke-width="2" stroke-linecap="round"/>
                        <line x1="4" y1="18" x2="20" y2="18" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </button>
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <span class="text-base font-bold text-white">Socstreet</span>
                    <span class="w-2 h-2 rounded-full bg-[#16A34A]"></span>
                </a>
                <a href="{{ route('profile.settings') }}" class="w-8 h-8 rounded-full bg-[#1E1E1E] border border-[#27272A] flex items-center justify-center text-[#22C55E] text-xs font-bold">
                    U
                </a>
            </header>

            <!-- Page Body -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto">
                {{ $slot }}
            </main>

            <!-- Minimal Dashboard Footer -->
            <footer class="px-6 py-4 border-t border-[#1F1F1F] text-center text-xs text-[#71717A]">
                Socstreet Minisoccer Booking System &bull; Single Arena Flow
            </footer>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('dashboard-sidebar');
            const backdrop = document.getElementById('mobile-sidebar-backdrop');
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }
        }
    </script>
</x-layouts.app>

