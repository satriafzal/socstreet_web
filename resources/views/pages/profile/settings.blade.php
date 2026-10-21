<x-layouts.dashboard title="Pengaturan Akun - Socstreet Minisoccer">
    <!-- Header -->
    <div class="pb-6 mb-8 border-b border-[#27272A]">
        <div class="flex items-center gap-2 mb-1">
            <span class="w-2 h-2 rounded-full bg-[#16A34A]"></span>
            <span class="text-xs font-semibold text-[#16A34A] uppercase tracking-wider">Preferensi Pengguna</span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">Pengaturan Akun & Legal</h1>
        <p class="text-xs sm:text-sm text-[#A1A1AA] mt-1">Kelola data login akun Anda dan tinjau kebijakan operasional arena.</p>
    </div>

    <!-- Two Cards Grid (PRD 4.6: Edit Username & Change Password) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
        <!-- Card 1: Ubah Username -->
        <div class="rounded-2xl bg-[#181818] border border-[#27272A] p-6 sm:p-7 space-y-6">
            <div class="flex items-start justify-between pb-4 border-b border-[#27272A]">
                <div>
                    <h2 class="text-base font-bold text-white tracking-tight">Ubah Username</h2>
                    <p class="text-xs text-[#A1A1AA] mt-1">Username digunakan sebagai identitas akun dan tiket booking Anda.</p>
                </div>
                <div class="w-8 h-8 rounded-xl bg-[#16A34A]/15 border border-[#16A34A]/30 flex items-center justify-center text-[#22C55E]">
                    <x-icon name="user" class="w-4 h-4" />
                </div>
            </div>

            <form action="{{ route('profile.settings') }}" method="GET" class="space-y-4">
                <div class="space-y-1">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-[#71717A]">Username Saat Ini</label>
                    <div class="px-4 py-3 rounded-lg bg-[#141414] border border-[#27272A] text-white font-mono text-xs flex items-center justify-between">
                        <span>{{ $user['username'] }}</span>
                        <span class="text-[10px] text-[#22C55E] bg-[#16A34A]/10 px-2 py-0.5 rounded">Aktif</span>
                    </div>
                </div>

                <x-input
                    name="new_username"
                    label="Username Baru"
                    type="text"
                    placeholder="Masukkan username baru"
                    icon="user"
                    help="Minimal 4 karakter tanpa spasi."
                    required
                />

                <x-input
                    name="confirm_password"
                    label="Konfirmasi Kata Sandi"
                    type="password"
                    placeholder="Masukkan kata sandi untuk verifikasi"
                    icon="lock"
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

            <form action="{{ route('profile.settings') }}" method="GET" class="space-y-4">
                <x-input
                    name="old_password"
                    label="Kata Sandi Saat Ini"
                    type="password"
                    placeholder="••••••••"
                    icon="lock"
                    required
                />

                <x-input
                    name="new_password"
                    label="Kata Sandi Baru"
                    type="password"
                    placeholder="Minimal 6 karakter"
                    icon="lock"
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
                    <x-button variant="secondary" size="md" type="submit" class="w-full sm:w-auto">
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
                <div id="acc-privacy" class="p-6 pt-0 text-xs text-[#A1A1AA] leading-relaxed space-y-3 hidden">
                    <div class="p-4 rounded-xl bg-[#141414] border border-[#27272A] space-y-2">
                        <h4 class="font-bold text-white">1. Pengumpulan Data Minimalis</h4>
                        <p>
                            Socstreet menerapkan prinsip <em>data minimization</em> secara ketat. Kami hanya menyimpan <strong>username</strong> dan kata sandi yang telah di-hash secara aman. Kami <strong>tidak meminta</strong>, menyimpan, atau memperdagangkan nomor handphone, email pribadi, maupun data sensitif lainnya.
                        </p>
                    </div>
                    <div class="p-4 rounded-xl bg-[#141414] border border-[#27272A] space-y-2">
                        <h4 class="font-bold text-white">2. Keamanan Transaksi & Sesi</h4>
                        <p>
                            Sesi login dienkripsi dan disimpan menggunakan driver sesi database/sqlite lokal. Seluruh riwayat transaksi hanya dapat dilihat oleh pemilik akun yang bersangkutan melalui halaman Booking Saya.
                        </p>
                    </div>
                    <div class="p-4 rounded-xl bg-[#141414] border border-[#27272A] space-y-2">
                        <h4 class="font-bold text-white">3. Penghapusan Akun</h4>
                        <p>
                            Pengguna berhak mengajukan penghapusan riwayat data akun kapan saja melalui admin pengelola operasional Socstreet Main Arena.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Accordion Item 2: Syarat & Ketentuan Booking (Terms of Service) -->
            <div id="syarat-ketentuan" class="accordion-item">
                <button
                    type="button"
                    onclick="toggleAccordion('acc-terms')"
                    class="w-full flex items-center justify-between p-5 sm:p-6 text-left hover:bg-[#1E1E1E] transition-colors"
                >
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-lg bg-[#27272A] flex items-center justify-center text-[#22C55E]">
                            <x-icon name="info" class="w-4 h-4" />
                        </span>
                        <div>
                            <span class="text-sm font-bold text-white block">Syarat & Ketentuan Booking (Terms of Service)</span>
                            <span class="text-[11px] text-[#71717A]">Ketentuan uang muka (DP), pembatalan, tata tertib bermain, dan pelunasan.</span>
                        </div>
                    </div>
                    <span id="icon-acc-terms" class="text-[#71717A] transform transition-transform duration-200">
                        <x-icon name="chevron-down" class="w-5 h-5" />
                    </span>
                </button>
                <div id="acc-terms" class="p-6 pt-0 text-xs text-[#A1A1AA] leading-relaxed space-y-3 hidden">
                    <div class="p-4 rounded-xl bg-[#141414] border border-[#27272A] space-y-2">
                        <h4 class="font-bold text-white">1. Ketentuan Uang Muka (Down Payment 50%)</h4>
                        <p>
                            Pengguna dapat memilih opsi DP 50% saat checkout untuk mengamankan slot waktu. Sisa pelunasan 50% <strong>wajib dibayarkan di meja resepsionis</strong> arena paling lambat 15 menit sebelum waktu kickoff pertandingan.
                        </p>
                    </div>
                    <div class="p-4 rounded-xl bg-[#141414] border border-[#27272A] space-y-2">
                        <h4 class="font-bold text-white">2. Kebijakan Keterlambatan & Pembatalan</h4>
                        <p>
                            Slot waktu berjalan sesuai jadwal yang tertera pada Invoice Booking. Keterlambatan tim tidak akan menambah durasi waktu sewa lapangan. Pembatalan booking yang diajukan kurang dari 24 jam sebelum jadwal pertandingan mengakibatkan uang muka (DP) hangus.
                        </p>
                    </div>
                    <div class="p-4 rounded-xl bg-[#141414] border border-[#27272A] space-y-2">
                        <h4 class="font-bold text-white">3. Tata Tertib & Perlengkapan Lapangan</h4>
                        <p>
                            Pemain diwajibkan menggunakan sepatu minisoccer dengan sol karet bertapak gerigi (TF / AG turf). <strong>Dilarang keras</strong> menggunakan sepatu sepak bola dengan pul besi / SG (Soft Ground) demi menjaga ketahanan rumput sintetis monofilament standar FIFA.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Accordion Script -->
    <script>
        function toggleAccordion(id) {
            const content = document.getElementById(id);
            const icon = document.getElementById('icon-' + id);
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                icon.classList.add('rotate-180');
            } else {
                content.classList.add('hidden');
                icon.classList.remove('rotate-180');
            }
        }

        // Open hash if targeted from URL
        document.addEventListener('DOMContentLoaded', () => {
            if (window.location.hash === '#kebijakan-privasi') {
                toggleAccordion('acc-privacy');
            } else if (window.location.hash === '#syarat-ketentuan') {
                toggleAccordion('acc-terms');
            }
        });
    </script>
</x-layouts.dashboard>

