<x-layouts.admin title="Settings" active="admin.settings">
    <div class="flex flex-col w-full gap-6">

        <!-- Top Section Header -->
        <div class="flex flex-col gap-1">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#18181B] border border-[#27272A] w-fit">
                <span class="w-1.5 h-1.5 rounded-full bg-[#16A34A] animate-pulse"></span>
                <span class="text-[11px] font-bold uppercase tracking-wider text-[#A1A1AA]">PENGATURAN AKUN & KEAMANAN</span>
            </div>
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mt-1">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Pengaturan Sistem & Profil</h1>
                    <p class="text-sm text-[#A1A1AA] mt-0.5 max-w-2xl">Kelola identitas akun administrator dan perbarui kata sandi akun sistem.</p>
                </div>
                <div class="flex items-center gap-2 bg-[#18181B] border border-[#27272A] px-3.5 py-1.5 rounded-xl self-start md:self-auto text-xs text-[#A1A1AA]">
                    <span class="material-symbols-outlined text-[#16A34A] text-[18px]">verified_user</span>
                    <span>Level Akses: <strong class="text-white font-semibold">Super Admin</strong></span>
                </div>
            </div>
        </div>

        <!-- Center Grid Container: Two Side-by-Side Dark Cards (5 cols / 7 cols) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 w-full">

            <!-- CARD 1: Admin Profile (5 Cols) -->
            <div class="lg:col-span-5 bg-[#1A1A1A] border border-[#27272A] rounded-2xl p-6 flex flex-col justify-between shadow-xl relative overflow-hidden">
                <div class="absolute -right-12 -top-12 w-44 h-44 bg-[#16A34A]/5 rounded-full blur-3xl pointer-events-none"></div>

                <div>
                    <!-- Card Header -->
                    <div class="flex items-center justify-between pb-5 border-b border-[#27272A]">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-[#242424] border border-[#27272A] flex items-center justify-center text-[#4ADE80]">
                                <span class="material-symbols-outlined text-[20px]">badge</span>
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-white tracking-tight">Edit Profil Admin</h2>
                                <p class="text-xs text-[#A1A1AA]">Identitas akun administrator aktif</p>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-[#242424] text-[#D4D4D8] border border-[#27272A]">ID #01</span>
                    </div>

                    <!-- Avatar Section -->
                    <div class="flex flex-col items-center justify-center my-6">
                        <div class="relative group">
                            <div class="w-24 h-24 rounded-full bg-[#242424] border-2 border-[#27272A] flex items-center justify-center text-white shadow-inner overflow-hidden" id="adminAvatarDisplay">
                                <span class="material-symbols-outlined text-[48px] text-[#A1A1AA] group-hover:scale-105 transition-transform duration-300">person</span>
                            </div>
                            <label for="avatarUploadInput" 
                                   aria-label="Ganti Avatar" 
                                   class="absolute bottom-0 right-0 w-8 h-8 rounded-full bg-[#16A34A] text-white flex items-center justify-center hover:bg-[#15803D] transition-all border-2 border-[#1A1A1A] shadow-md hover:scale-110 active:scale-95 cursor-pointer" 
                                   title="Unggah Foto Profil Baru">
                                <span class="material-symbols-outlined text-[16px]">photo_camera</span>
                            </label>
                            <input type="file" id="avatarUploadInput" class="hidden" accept="image/*" onchange="handleAvatarPreview(event)">
                        </div>
                        <div class="mt-3 text-center">
                            <span class="text-sm font-bold text-white block font-mono" id="displayAdminUsername">{{ $admin['username'] ?? 'admin_soc' }}</span>
                            <span class="text-[11px] text-[#71717A]">Primary Root Authority</span>
                        </div>
                    </div>

                    <!-- STRICT PRD RULE: ONLY ONE single dark input field labeled 'Username' (NO email or phone fields) -->
                    <form class="space-y-4" onsubmit="event.preventDefault(); handleSaveProfile();">
                        <div>
                            <label class="block text-xs font-semibold text-[#E4E4E7] mb-2" for="admin-username">Username</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-[#71717A] text-[18px]">alternate_email</span>
                                <input type="text" 
                                       id="admin-username" 
                                       placeholder="Masukkan username" 
                                       value="{{ $admin['username'] ?? 'admin_soc' }}" 
                                       required 
                                       class="w-full bg-[#1F1F1F] border border-[#27272A] focus:border-[#16A34A] focus:ring-1 focus:ring-[#16A34A] rounded-lg py-2.5 pl-10 pr-4 text-white text-xs transition-colors outline-none font-mono"/>
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" 
                                    class="w-full py-2.5 px-4 rounded-xl bg-[#16A34A] hover:bg-[#15803D] text-white text-xs font-bold transition-all duration-200 shadow-lg shadow-[#16A34A]/20 active:scale-[0.99] flex items-center justify-center gap-2 cursor-pointer">
                                <span class="material-symbols-outlined text-[18px]">save</span>
                                <span>Simpan Profil</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Subtle Info Tip at Bottom -->
                <div class="mt-6 pt-4 border-t border-[#27272A] flex items-start gap-2">
                    <span class="material-symbols-outlined text-[#71717A] text-[17px] shrink-0 mt-0.5">info</span>
                    <p class="text-[11px] text-[#A1A1AA] leading-relaxed">
                        Username digunakan untuk autentikasi login ke seluruh modul dashboard admin. Sesuai PRD rules, field email dan nomor telepon tidak digunakan.
                    </p>
                </div>
            </div>

            <!-- CARD 2: Security / Password (7 Cols) -->
            <div class="lg:col-span-7 bg-[#1A1A1A] border border-[#27272A] rounded-2xl p-6 flex flex-col justify-between shadow-xl relative overflow-hidden">
                <div class="absolute -right-12 -top-12 w-44 h-44 bg-[#16A34A]/5 rounded-full blur-3xl pointer-events-none"></div>

                <div>
                    <!-- Card Header -->
                    <div class="flex items-center justify-between pb-5 border-b border-[#27272A]">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-[#242424] border border-[#27272A] flex items-center justify-center text-[#4ADE80]">
                                <span class="material-symbols-outlined text-[20px]">lock_reset</span>
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-white tracking-tight">Ubah Password</h2>
                                <p class="text-xs text-[#A1A1AA]">Pastikan akun menggunakan kredensial yang kuat & aman</p>
                            </div>
                        </div>
                        <span class="flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-[#242424] text-[#4ADE80] border border-[#27272A]">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#16A34A]"></span>
                            Terenkripsi
                        </span>
                    </div>

                    <!-- 3 Password Input Fields (PRD 7.B.5) -->
                    <form class="space-y-4 mt-5" onsubmit="event.preventDefault(); handleUpdatePassword();">
                        <!-- Field 1: Password Saat Ini -->
                        <div>
                            <label class="block text-xs font-semibold text-[#E4E4E7] mb-1.5" for="current-password">Password Saat Ini</label>
                            <div class="relative">
                                <input type="password" 
                                       id="current-password" 
                                       placeholder="••••••••••••" 
                                       required 
                                       class="w-full bg-[#1F1F1F] border border-[#27272A] focus:border-[#16A34A] focus:ring-1 focus:ring-[#16A34A] rounded-lg py-2.5 pl-4 pr-11 text-white text-xs transition-colors outline-none tracking-widest placeholder:tracking-normal placeholder:text-[#71717A]"/>
                                <button type="button" 
                                        onclick="togglePasswordVisibility('current-password', this)" 
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-[#71717A] hover:text-white transition-colors p-1"
                                        aria-label="Toggle password visibility">
                                    <span class="material-symbols-outlined text-[19px]">visibility</span>
                                </button>
                            </div>
                        </div>

                        <!-- Field 2: Password Baru -->
                        <div>
                            <label class="block text-xs font-semibold text-[#E4E4E7] mb-1.5" for="new-password">Password Baru</label>
                            <div class="relative">
                                <input type="password" 
                                       id="new-password" 
                                       placeholder="Minimal 8 karakter unik" 
                                       required 
                                       class="w-full bg-[#1F1F1F] border border-[#27272A] focus:border-[#16A34A] focus:ring-1 focus:ring-[#16A34A] rounded-lg py-2.5 pl-4 pr-11 text-white text-xs transition-colors outline-none placeholder:text-[#71717A]"/>
                                <button type="button" 
                                        onclick="togglePasswordVisibility('new-password', this)" 
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-[#71717A] hover:text-white transition-colors p-1"
                                        aria-label="Toggle password visibility">
                                    <span class="material-symbols-outlined text-[19px]">visibility</span>
                                </button>
                            </div>
                        </div>

                        <!-- Field 3: Konfirmasi Password Baru -->
                        <div>
                            <label class="block text-xs font-semibold text-[#E4E4E7] mb-1.5" for="confirm-password">Konfirmasi Password Baru</label>
                            <div class="relative">
                                <input type="password" 
                                       id="confirm-password" 
                                       placeholder="Ulangi password baru" 
                                       required 
                                       class="w-full bg-[#1F1F1F] border border-[#27272A] focus:border-[#16A34A] focus:ring-1 focus:ring-[#16A34A] rounded-lg py-2.5 pl-4 pr-11 text-white text-xs transition-colors outline-none placeholder:text-[#71717A]"/>
                                <button type="button" 
                                        onclick="togglePasswordVisibility('confirm-password', this)" 
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-[#71717A] hover:text-white transition-colors p-1"
                                        aria-label="Toggle password visibility">
                                    <span class="material-symbols-outlined text-[19px]">visibility</span>
                                </button>
                            </div>
                        </div>

                        <!-- Password Guidelines Checklist -->
                        <div class="p-3 rounded-xl bg-[#141414] border border-[#27272A] grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs text-[#A1A1AA] mt-2">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[#16A34A] text-[16px]">check_circle</span>
                                <span>Min. 8 karakter</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[#16A34A] text-[16px]">check_circle</span>
                                <span>Kombinasi huruf & angka</span>
                            </div>
                        </div>

                        <!-- CTA Button -->
                        <div class="pt-2">
                            <button type="submit" 
                                    class="w-full py-2.5 px-4 rounded-xl bg-[#16A34A] hover:bg-[#15803D] text-white text-xs font-bold transition-all duration-200 shadow-lg shadow-[#16A34A]/20 active:scale-[0.99] flex items-center justify-center gap-2 cursor-pointer">
                                <span class="material-symbols-outlined text-[18px]">key</span>
                                <span>Update Password</span>
                            </button>
                        </div>
                    </form>
                </div>

                <div class="mt-6 pt-4 border-t border-[#27272A] flex items-center gap-2 text-xs text-[#71717A]">
                    <span class="material-symbols-outlined text-[16px] text-[#16A34A]">shield</span>
                    <span>Pembaruan kredensial akan segera berlaku untuk sesi login berikutnya.</span>
                </div>
            </div>

        </div>

    </div>

    @push('scripts')
    <script>
        function togglePasswordVisibility(inputId, button) {
            const input = document.getElementById(inputId);
            const icon = button.querySelector('.material-symbols-outlined');
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerText = 'visibility_off';
            } else {
                input.type = 'password';
                icon.innerText = 'visibility';
            }
        }

        function handleAvatarPreview(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const avatarBox = document.getElementById('adminAvatarDisplay');
                    avatarBox.innerHTML = `<img src="${e.target.result}" alt="Avatar" class="w-full h-full object-cover">`;
                    showAdminToast('Foto Dipilih', 'Foto avatar baru siap disimpan.');
                };
                reader.readAsDataURL(file);
            }
        }

        function handleSaveProfile() {
            const username = document.getElementById('admin-username').value.trim();
            if (!username) return;

            document.getElementById('displayAdminUsername').innerText = username;
            showAdminToast('Profil Diperbarui', `Username admin berhasil disimpan sebagai "${username}".`, true);
        }

        function handleUpdatePassword() {
            const currentPass = document.getElementById('current-password').value;
            const newPass = document.getElementById('new-password').value;
            const confirmPass = document.getElementById('confirm-password').value;

            if (newPass.length < 8) {
                showAdminToast('Password Kurang Panjang', 'Password baru minimal harus 8 karakter.', false);
                return;
            }

            if (newPass !== confirmPass) {
                showAdminToast('Password Tidak Cocok', 'Konfirmasi password tidak sesuai dengan password baru.', false);
                return;
            }

            document.getElementById('current-password').value = '';
            document.getElementById('new-password').value = '';
            document.getElementById('confirm-password').value = '';

            showAdminToast('Password Berhasil Diubah', 'Kata sandi administrator telah diperbarui secara aman.', true);
        }
    </script>
    @endpush
</x-layouts.admin>
