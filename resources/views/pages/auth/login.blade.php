<x-layouts.app title="Masuk Akun - Socstreet Minisoccer">
    <div class="min-h-screen flex flex-col justify-center items-center px-4 py-12 relative overflow-hidden">
        <!-- Minisoccer Pitch Background Image Layer -->
        <div class="absolute inset-0 z-0">
            <img
                src="{{ asset('images/auth-pitch.jpg') }}"
                alt="Minisoccer Stadium Atmosphere"
                class="w-full h-full object-cover object-center brightness-90 contrast-105"
                onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1529900748604-07564a03e7a6?auto=format&fit=crop&w=2000&q=80';"
            />
            <!-- Dark Gradient & Vignette Overlays -->
            <div class="absolute inset-0 bg-gradient-to-t from-[#121212] via-[#121212]/80 to-[#121212]/85"></div>
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_transparent_15%,_#121212_90%)]"></div>
            <!-- Green Ambient Glow -->
            <div class="absolute top-1/3 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[550px] h-[350px] bg-[#16A34A]/20 blur-[140px] rounded-full pointer-events-none"></div>
        </div>

        <!-- Back to Home -->
        <div class="w-full max-w-md mb-6 relative z-10">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-[#A1A1AA] hover:text-white transition-colors bg-[#181818]/80 backdrop-blur-md px-3 py-1.5 rounded-lg border border-[#27272A] w-fit">
                <x-icon name="arrow-left" class="w-4 h-4" />
                <span>Kembali ke Beranda</span>
            </a>
        </div>

        <!-- Centered Auth Card with Frosted Glass (Desain 3 & 4.B: Surface #181818, Border #27272A, Rounded-2xl) -->
        <div class="w-full max-w-md rounded-2xl bg-[#181818]/90 backdrop-blur-xl border border-[#27272A] p-7 sm:p-9 shadow-2xl shadow-black/90 relative z-10">
            <!-- Brand & Header -->
            <div class="text-center space-y-2 mb-8">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 justify-center group mb-2">
                    <div class="w-10 h-10 rounded-xl bg-[#16A34A]/15 border border-[#16A34A]/30 flex items-center justify-center text-[#22C55E] group-hover:scale-105 transition-transform">
                        <x-icon name="soccer" class="w-5 h-5" />
                    </div>
                    <span class="text-2xl font-bold tracking-tight text-white">Socstreet<span class="text-[#16A34A]">.</span></span>
                </a>
                <h1 class="text-xl font-bold text-white tracking-tight">Masuk ke Akun Member</h1>
                <p class="text-xs text-[#A1A1AA]">Masukkan username dan kata sandi untuk mengelola jadwal Anda.</p>
            </div>

            <!-- Form: STRICTLY Username & Password ONLY (PRD Rule 1.3) -->
            <form action="{{ route('booking.schedule') }}" method="GET" class="space-y-4">
                <x-input
                    name="username"
                    label="Username"
                    type="text"
                    placeholder="Contoh: player_one"
                    icon="user"
                    value="Player_One"
                    required
                />

                <div class="space-y-1.5">
                    <x-input
                        name="password"
                        label="Kata Sandi"
                        type="password"
                        placeholder="••••••••"
                        icon="lock"
                        value="secret123"
                        required
                    />
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 text-[#A1A1AA] cursor-pointer select-none">
                        <input type="checkbox" checked class="w-4 h-4 rounded bg-[#1F1F1F] border-[#27272A] text-[#16A34A] focus:ring-[#16A34A] focus:ring-offset-0" />
                        <span>Ingat saya</span>
                    </label>
                    <span class="text-[#71717A] text-[11px] font-mono">Arena: Socstreet Main</span>
                </div>

                <div class="pt-2">
                    <x-button variant="primary" size="lg" type="submit" class="w-full shadow-lg shadow-[#16A34A]/25">
                        <span>Masuk Sekarang</span>
                        <x-icon name="arrow-right" class="w-4 h-4" />
                    </x-button>
                </div>
            </form>

            <!-- Bottom Switcher -->
            <div class="mt-8 pt-6 border-t border-[#27272A] text-center">
                <p class="text-xs text-[#71717A]">
                    Belum memiliki akun?
                    <a href="{{ route('register') }}" class="font-semibold text-[#22C55E] hover:underline ml-1">
                        Daftar Akun Baru
                    </a>
                </p>
            </div>
        </div>

        <!-- Single Arena Notice -->
        <p class="text-[11px] text-[#71717A] mt-6 text-center">
            Socstreet Minisoccer &bull; Akses Eksklusif Socstreet Main Arena
        </p>
    </div>
</x-layouts.app>

