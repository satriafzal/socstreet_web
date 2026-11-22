<header id="main-navbar" class="fixed top-0 inset-x-0 z-50 w-full h-[72px] bg-[#121212]/90 backdrop-blur-md border-b border-[#27272A] transition-all duration-300 ease-in-out will-change-transform">
    <div class="max-w-7xl mx-auto h-full px-4 sm:px-6 lg:px-8 flex items-center justify-between">
        <!-- Brand Logo -->
        <a href="{{ route('home') }}" class="flex items-center gap-2 group">
            <div class="w-9 h-9 rounded-xl bg-[#16A34A]/15 border border-[#16A34A]/30 flex items-center justify-center text-[#22C55E] group-hover:scale-105 group-hover:bg-[#16A34A]/25 transition-all">
                <x-icon name="soccer" class="w-5 h-5 group-hover:rotate-12 transition-transform duration-300" />
            </div>
            <div class="flex items-baseline">
                <span class="text-xl font-bold tracking-tight text-white group-hover:text-white/90 transition-colors">Socstreet</span>
                <span class="w-2 h-2 rounded-full bg-[#16A34A] ml-0.5 animate-pulse"></span>
            </div>
        </a>

        <!-- Center Nav Links -->
        <nav class="hidden md:flex items-center gap-8">
            <a href="{{ route('home') }}#arena" class="text-sm font-medium text-[#A1A1AA] hover:text-white transition-colors relative py-1 after:absolute after:bottom-0 after:left-0 after:w-0 hover:after:w-full after:h-0.5 after:bg-[#16A34A] after:transition-all after:duration-200">Lapangan</a>
            <a href="{{ route('home') }}#facilities" class="text-sm font-medium text-[#A1A1AA] hover:text-white transition-colors relative py-1 after:absolute after:bottom-0 after:left-0 after:w-0 hover:after:w-full after:h-0.5 after:bg-[#16A34A] after:transition-all after:duration-200">Fasilitas</a>
            <a href="{{ route('home') }}#how-to-book" class="text-sm font-medium text-[#A1A1AA] hover:text-white transition-colors relative py-1 after:absolute after:bottom-0 after:left-0 after:w-0 hover:after:w-full after:h-0.5 after:bg-[#16A34A] after:transition-all after:duration-200">Cara Booking</a>
            <a href="{{ auth()->check() ? route('booking.schedule') : route('login') }}" class="text-sm font-medium text-[#22C55E] hover:text-white flex items-center gap-1.5 transition-colors group">
                <span class="w-1.5 h-1.5 rounded-full bg-[#22C55E] group-hover:scale-150 transition-transform"></span>
                <span>Cek Jadwal</span>
            </a>
        </nav>

        <!-- Right Action Buttons -->
        <div class="flex items-center gap-3">
            <!-- Booking Saya Link (Directs to Login if unauthenticated) -->
            <a href="{{ auth()->check() ? route('booking.my') : route('login') }}" class="hidden sm:inline-flex text-xs font-semibold px-3 py-2 text-[#A1A1AA] hover:text-white hover:bg-[#1E1E1E] rounded-lg transition-colors border border-transparent hover:border-[#27272A]">
                Booking Saya
            </a>

            @guest
                <x-button variant="primary" size="md" href="{{ route('login') }}" class="rounded-xl font-semibold shadow-md shadow-[#16A34A]/25 hover:shadow-[#16A34A]/40 hover:scale-[1.02] active:scale-[0.98] transition-all">
                    Masuk
                </x-button>
            @else
                <div class="flex items-center gap-2">
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('booking.schedule') }}" class="text-xs font-semibold px-3 py-2 rounded-xl bg-[#1E1E1E] border border-[#27272A] text-white hover:border-[#16A34A] transition-colors">
                        Dashboard
                    </a>
                    <button type="button" onclick="toggleModal('navbar-logout-modal')" class="text-xs font-semibold px-3 py-2 rounded-xl bg-[#27272A] hover:bg-[#EF4444]/20 hover:text-[#EF4444] text-[#A1A1AA] transition-colors">
                        Keluar
                    </button>
                </div>

                <!-- Navbar Logout Confirmation Modal -->
                <x-modal id="navbar-logout-modal" title="Konfirmasi Keluar" subtitle="Sesi Anda akan diakhiri" maxWidth="sm">
                    <div class="text-center py-2 space-y-3">
                        <div class="w-12 h-12 rounded-full bg-[#EF4444]/15 border border-[#EF4444]/30 text-[#EF4444] flex items-center justify-center mx-auto">
                            <x-icon name="logout" class="w-6 h-6" />
                        </div>
                        <h4 class="text-base font-bold text-white">Keluar dari Akun?</h4>
                        <p class="text-xs text-[#A1A1AA] leading-relaxed">
                            Apakah Anda yakin ingin keluar dari akun Socstreet Anda?
                        </p>
                    </div>

                    <x-slot:footer>
                        <x-button variant="secondary" size="md" type="button" onclick="toggleModal('navbar-logout-modal')">
                            Batal
                        </x-button>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl text-xs font-bold bg-[#EF4444] hover:bg-[#DC2626] text-white shadow-lg shadow-[#EF4444]/25 transition-all">
                                <x-icon name="logout" class="w-3.5 h-3.5" />
                                <span>Ya, Keluar Akun</span>
                            </button>
                        </form>
                    </x-slot:footer>
                </x-modal>
            @endguest
        </div>
    </div>
</header>

<!-- Spacer so top content isn't covered by fixed navbar -->
<div class="h-[72px] w-full"></div>

<!-- Auto-hide on scroll and reappear when scroll stops -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const navbar = document.getElementById('main-navbar');
        if (!navbar) return;

        let isScrollingTimeout;

        window.addEventListener('scroll', () => {
            const currentScrollY = window.scrollY;

            if (currentScrollY > 50) {
                navbar.classList.add('-translate-y-full');
                navbar.classList.add('shadow-xl', 'shadow-black/70');
            } else {
                navbar.classList.remove('-translate-y-full');
                navbar.classList.remove('shadow-xl', 'shadow-black/70');
            }

            clearTimeout(isScrollingTimeout);

            isScrollingTimeout = setTimeout(() => {
                navbar.classList.remove('-translate-y-full');
            }, 200);
        }, { passive: true });
    });
</script>
