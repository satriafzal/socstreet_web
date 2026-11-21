<x-layouts.dashboard title="Pengaturan Akun - Socstreet Minisoccer">
    <!-- Header -->
    <div class="pb-6 mb-8 border-b border-[#27272A]">
        <div class="flex items-center gap-2 mb-1">
            <span class="w-2 h-2 rounded-full bg-[#16A34A]"></span>
            <span class="text-xs font-semibold text-[#16A34A] uppercase tracking-wider">Preferensi Pengguna</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">Pengaturan Akun & Legal</h1>
        <p class="text-xs sm:text-sm text-[#A1A1AA] mt-1">Kelola data profil, email, dan kata sandi akun Anda di Socstreet Main Arena.</p>
    </div>

    <!-- User Profile Overview Card -->
    <div class="mb-8 p-6 rounded-2xl bg-[#181818] border border-[#27272A] flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-[#16A34A]/15 border border-[#16A34A]/30 flex items-center justify-center text-[#22C55E] font-bold text-xl">
                {{ strtoupper(substr($user->username ?? 'U', 0, 1)) }}
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-lg font-bold text-white">{{ $user->username }}</h2>
                    <span class="text-[11px] font-semibold px-2 py-0.5 rounded bg-[#16A34A]/20 text-[#22C55E] border border-[#16A34A]/30">
                        {{ ucfirst($user->role ?? 'User') }}
                    </span>
                </div>
                <p class="text-xs text-[#A1A1AA] mt-0.5 flex items-center gap-1.5">
                    <x-icon name="mail" class="w-3.5 h-3.5 text-[#71717A]" />
                    <span>{{ $user->email }}</span>
                </p>
            </div>
        </div>

        <div class="flex items-center gap-6 text-xs text-[#A1A1AA] border-t md:border-t-0 md:border-l border-[#27272A] pt-3 md:pt-0 md:pl-6 w-full md:w-auto">
            <div>
                <p class="text-[11px] text-[#71717A]">Terdaftar Sejak</p>
                <p class="font-semibold text-white mt-0.5">
                    {{ $user->created_at ? $user->created_at->isoFormat('D MMMM Y') : 'Baru' }}
                </p>
            </div>
            <div>
                <p class="text-[11px] text-[#71717A]">Total Transaksi</p>
                <p class="font-semibold text-[#22C55E] mt-0.5">
                    {{ $user->bookings()->count() }} Booking
                </p>
            </div>
        </div>
    </div>

    <!-- Two Cards Grid (Edit Username & Change Password) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12 items-start">

        <!-- Card 1: Ubah Username -->
        <div class="rounded-2xl bg-[#181818] border border-[#27272A] p-6 sm:p-7 space-y-6">
            <div class="flex items-start justify-between pb-4 border-b border-[#27272A]">
                <div>
                    <h2 class="text-base font-bold text-white tracking-tight">Ubah Username</h2>
                    <p class="text-xs text-[#A1A1AA] mt-1">Username digunakan sebagai tanda pengenal pada tiket reservasi Anda.</p>
                </div>
                <div class="w-8 h-8 rounded-xl bg-[#16A34A]/15 border border-[#16A34A]/30 flex items-center justify-center text-[#22C55E]">
                    <x-icon name="user" class="w-4 h-4" />
                </div>
            </div>

            <!-- Flash Success / Error for Username -->
            @if(session('success_username'))
                <div class="p-3 rounded-xl bg-[#16A34A]/15 border border-[#16A34A]/40 text-[#22C55E] text-xs flex items-center gap-2">
                    <x-icon name="check-circle" class="w-4 h-4 shrink-0" />
                    <span>{{ session('success_username') }}</span>
                </div>
            @endif

            @if($errors->updateUsername->any())
                <div class="p-3 rounded-xl bg-[#EF4444]/15 border border-[#EF4444]/40 text-[#EF4444] text-xs space-y-1">
                    @foreach($errors->updateUsername->all() as $err)
                        <div class="flex items-center gap-2">
                            <x-icon name="x" class="w-3.5 h-3.5 shrink-0" />
                            <span>{{ $err }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('profile.update-username') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="space-y-1">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-[#71717A]">Username Saat Ini</label>
                    <div class="px-4 py-3 rounded-lg bg-[#141414] border border-[#27272A] text-white font-mono text-xs flex items-center justify-between">
                        <span>{{ $user->username }}</span>
                        <span class="text-[10px] text-[#22C55E] bg-[#16A34A]/10 px-2 py-0.5 rounded font-semibold">Aktif</span>
                    </div>
                </div>

                <x-input
                    name="new_username"
                    label="Username Baru"
                    type="text"
                    placeholder="Masukkan username baru"
                    icon="user"
                    :value="old('new_username')"
                    :error="$errors->updateUsername->first('new_username')"
                    help="Minimal 3 karakter tanpa spasi."
                    required
                />

                <x-input
                    name="confirm_password"
                    label="Konfirmasi Kata Sandi Akun"
                    type="password"
                    placeholder="Masukkan kata sandi untuk verifikasi"
                    icon="lock"
                    :error="$errors->updateUsername->first('confirm_password')"
                    help="Dibutuhkan untuk memastikan keamanan perubahan identitas akun."
                    required
                />

                <div class="pt-2">
                    <x-button variant="primary" size="md" type="submit" class="w-full sm:w-auto shadow-lg shadow-[#16A34A]/25">
                        <span>Simpan Username</span>
                    </x-button>
                </div>
            </form>
        </div>

        <!-- Card 2: Ganti Kata Sandi -->
        <div class="rounded-2xl bg-[#181818] border border-[#27272A] p-6 sm:p-7 space-y-6">
            <div class="flex items-start justify-between pb-4 border-b border-[#27272A]">
                <div>
                    <h2 class="text-base font-bold text-white tracking-tight">Ganti Kata Sandi</h2>
                    <p class="text-xs text-[#A1A1AA] mt-1">Perbarui kata sandi secara berkala untuk menjaga keamanan akun Anda.</p>
                </div>
                <div class="w-8 h-8 rounded-xl bg-[#16A34A]/15 border border-[#16A34A]/30 flex items-center justify-center text-[#22C55E]">
                    <x-icon name="lock" class="w-4 h-4" />
                </div>
            </div>

            <!-- Flash Success / Error for Password -->
            @if(session('success_password'))
                <div class="p-3 rounded-xl bg-[#16A34A]/15 border border-[#16A34A]/40 text-[#22C55E] text-xs flex items-center gap-2">
                    <x-icon name="check-circle" class="w-4 h-4 shrink-0" />
                    <span>{{ session('success_password') }}</span>
                </div>
            @endif

            @if($errors->updatePassword->any())
                <div class="p-3 rounded-xl bg-[#EF4444]/15 border border-[#EF4444]/40 text-[#EF4444] text-xs space-y-1">
                    @foreach($errors->updatePassword->all() as $err)
                        <div class="flex items-center gap-2">
                            <x-icon name="x" class="w-3.5 h-3.5 shrink-0" />
                            <span>{{ $err }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('profile.update-password') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <x-input
                    name="old_password"
                    label="Kata Sandi Saat Ini"
                    type="password"
                    placeholder="••••••••"
                    icon="lock"
                    :error="$errors->updatePassword->first('old_password')"
                    required
                />

                <x-input
                    name="new_password"
                    label="Kata Sandi Baru"
                    type="password"
                    placeholder="Minimal 6 karakter"
                    icon="lock"
                    :error="$errors->updatePassword->first('new_password')"
                    required
                />

                <x-input
                    name="new_password_confirmation"
                    label="Ulangi Kata Sandi Baru"
                    type="password"
                    placeholder="Ketik ulang kata sandi baru"
                    icon="lock"
                    required
                />

                <div class="pt-2">
                    <x-button variant="secondary" size="md" type="submit" class="w-full sm:w-auto hover:border-[#16A34A]">
                        <span>Perbarui Kata Sandi</span>
                    </x-button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bottom Accordion for Legal & Policies (PRD 4.6: Privacy Policy & Terms of Service) -->
    <div class="space-y-4">
        <div class="pb-2">
            <h2 class="text-lg font-bold text-white tracking-tight flex items-center gap-2">
                <x-icon name="shield" class="w-5 h-5 text-[#16A34A]" />
                <span>Legal & Kebijakan Arena</span>
            </h2>
            <p class="text-xs text-[#A1A1AA] mt-0.5">Pelajari syarat penggunaan layanan dan komitmen keamanan data di Socstreet.</p>
        </div>

        <!-- Accordion Container -->
        <div class="rounded-2xl bg-[#181818] border border-[#27272A] divide-y divide-[#27272A] overflow-hidden">
            <!-- Accordion Item 1: Kebijakan Privasi (Privacy Policy) -->
            <div id="kebijakan-privasi" class="accordion-item">
                <button
                    type="button"
                    onclick="toggleAccordion('acc-privacy')"
                    class="w-full flex items-center justify-between p-5 sm:p-6 text-left hover:bg-[#1E1E1E] transition-colors"
                >
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-lg bg-[#27272A] flex items-center justify-center text-[#22C55E]">
                            <x-icon name="shield" class="w-4 h-4" />
                        </span>
                        <div>
                            <span class="text-sm font-bold text-white block">Kebijakan Privasi (Privacy Policy)</span>
                            <span class="text-[11px] text-[#71717A]">Pengelolaan data autentikasi dan privasi tanpa tracking pihak ketiga.</span>
                        </div>
                    </div>
                    <span id="icon-acc-privacy" class="text-[#71717A] transform transition-transform duration-200">
                        <x-icon name="chevron-down" class="w-5 h-5" />
                    </span>
                </button>
                <div id="acc-privacy" class="hidden px-6 pb-6 pt-2 text-xs text-[#A1A1AA] space-y-3 leading-relaxed border-t border-[#27272A]/50 bg-[#141414]">
                    <p>
                        Socstreet berkomitmen melindungi privasi pengunjung dan member arena. Sistem kami beroperasi dengan prinsip minimasi data:
                    </p>
                    <ul class="list-disc pl-5 space-y-1.5 text-[#71717A]">
                        <li>Akun hanya menyimpan Username, Email, dan Hash Kata Sandi terenkripsi (Bcrypt standard).</li>
                        <li>Kami tidak melacak riwayat penjelajahan di luar sistem booking Socstreet.</li>
                        <li>Data pemesanan dan jadwal tidak pernah diperjualbelikan kepada pihak ketiga manapun.</li>
                    </ul>
                </div>
            </div>

            <!-- Accordion Item 2: Syarat & Ketentuan Layanan (Terms of Service) -->
            <div id="syarat-ketentuan" class="accordion-item">
                <button
                    type="button"
                    onclick="toggleAccordion('acc-terms')"
                    class="w-full flex items-center justify-between p-5 sm:p-6 text-left hover:bg-[#1E1E1E] transition-colors"
                >
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-lg bg-[#27272A] flex items-center justify-center text-[#22C55E]">
                            <x-icon name="check-circle" class="w-4 h-4" />
                        </span>
                        <div>
                            <span class="text-sm font-bold text-white block">Syarat & Ketentuan Booking Arena</span>
                            <span class="text-[11px] text-[#71717A]">Aturan durasi sesi 2 jam, batas pembayaran DP, dan tata tertib lapangan.</span>
                        </div>
                    </div>
                    <span id="icon-acc-terms" class="text-[#71717A] transform transition-transform duration-200">
                        <x-icon name="chevron-down" class="w-5 h-5" />
                    </span>
                </button>
                <div id="acc-terms" class="hidden px-6 pb-6 pt-2 text-xs text-[#A1A1AA] space-y-3 leading-relaxed border-t border-[#27272A]/50 bg-[#141414]">
                    <ul class="list-disc pl-5 space-y-1.5 text-[#71717A]">
                        <li>Durasi pemesanan beroperasi dalam blok 2 jam (minimal 1 sesi 2 jam, penambahan kelipatan 2 jam).</li>
                        <li>Pembayaran Down Payment (DP 50%) mengunci jadwal selama 15 menit untuk penyelesaian transfer.</li>
                        <li>Sisa pelunasan 50% wajib dilunasi di kasir arena sebelum tim memasuki lapangan.</li>
                        <li>Pemain wajib mengenakan sepatu minisoccer/turf standar untuk menjaga kualitas rumput sintetis.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Client-side Accordion Script -->
    <script>
        function toggleAccordion(id) {
            const content = document.getElementById(id);
            const icon = document.getElementById('icon-' + id);

            if (!content) return;

            const isHidden = content.classList.contains('hidden');

            // Close all
            document.querySelectorAll('[id^="acc-"]').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('[id^="icon-acc-"]').forEach(el => el.classList.remove('rotate-180'));

            // Toggle target
            if (isHidden) {
                content.classList.remove('hidden');
                if (icon) icon.classList.add('rotate-180');
            }
        }
    </script>
</x-layouts.dashboard>
