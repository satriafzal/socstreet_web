<footer class="bg-[#0D0D0D] border-t border-[#27272A] mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 lg:gap-12">
            <!-- Brand & Tagline -->
            <div class="space-y-4 md:col-span-1">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-[#16A34A]/20 border border-[#16A34A]/30 flex items-center justify-center text-[#22C55E]">
                        <x-icon name="soccer" class="w-4 h-4" />
                    </div>
                    <span class="text-lg font-bold text-white tracking-tight">Socstreet<span class="text-[#16A34A]">.</span></span>
                </a>
                <p class="text-xs text-[#71717A] leading-relaxed">
                    Sistem pemesanan lapangan minisoccer standar kompetisi dengan rumput sintetis premium monofilament dan pencahayaan LED match standard.
                </p>
                <div class="pt-2 flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-[#16A34A]/10 text-[#22C55E] border border-[#16A34A]/20">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#22C55E] animate-ping"></span>
                        Socstreet Main Arena Aktif
                    </span>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="space-y-3">
                <h4 class="text-xs font-bold uppercase tracking-wider text-[#A1A1AA]">Navigasi Cepat</h4>
                <ul class="space-y-2 text-xs text-[#71717A]">
                    <li><a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a></li>
                    <li><a href="{{ route('home') }}#arena" class="hover:text-white transition-colors">Socstreet Main Arena</a></li>
                    <li><a href="{{ route('home') }}#facilities" class="hover:text-white transition-colors">Fasilitas Lengkap</a></li>
                    <li><a href="{{ route('booking.schedule') }}" class="hover:text-white transition-colors">Cek Jadwal & Slot</a></li>
                </ul>
            </div>

            <!-- Customer Portal -->
            <div class="space-y-3">
                <h4 class="text-xs font-bold uppercase tracking-wider text-[#A1A1AA]">Area Member</h4>
                <ul class="space-y-2 text-xs text-[#71717A]">
                    <li><a href="{{ route('booking.my') }}" class="hover:text-white transition-colors">Riwayat Booking Saya</a></li>
                    <li><a href="{{ route('profile.settings') }}" class="hover:text-white transition-colors">Pengaturan Akun</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Masuk Member</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-white transition-colors">Daftar Akun Baru</a></li>
                </ul>
            </div>

            <!-- Arena Info & Hours -->
            <div class="space-y-3">
                <h4 class="text-xs font-bold uppercase tracking-wider text-[#A1A1AA]">Lokasi & Operasional</h4>
                <div class="space-y-2 text-xs text-[#71717A]">
                    <div class="flex items-start gap-2">
                        <x-icon name="map-pin" class="w-4 h-4 text-[#16A34A] shrink-0 mt-0.5" />
                        <span>Komp. Socstreet Sports Center Kav. 1, Kota Jakarta</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <x-icon name="clock" class="w-4 h-4 text-[#16A34A] shrink-0" />
                        <span>Senin - Minggu: 08:00 - 24:00 WIB</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <x-icon name="shield" class="w-4 h-4 text-[#16A34A] shrink-0" />
                        <span>Pembayaran Aman: QRIS, Transfer Bank, E-Wallet</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-12 pt-8 border-t border-[#1F1F1F] flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-[#71717A]">
            <p>&copy; {{ date('Y') }} Socstreet Minisoccer. All rights reserved.</p>
            <div class="flex items-center gap-6">
                <a href="{{ route('profile.settings') }}#kebijakan-privasi" class="hover:text-white transition-colors">Kebijakan Privasi</a>
                <span class="text-[#27272A]">•</span>
                <a href="{{ route('profile.settings') }}#syarat-ketentuan" class="hover:text-white transition-colors">Syarat & Ketentuan</a>
            </div>
        </div>
    </div>
</footer>

