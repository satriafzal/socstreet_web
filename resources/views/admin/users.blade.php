<x-layouts.admin title="Users Management" active="admin.users">
    <div class="flex flex-col w-full gap-6">

        <!-- Header & Context Banner -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div class="flex flex-col gap-1">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-[#16A34A] shadow-[0_0_6px_#16A34A]"></span>
                    <span class="text-xs font-semibold uppercase tracking-wider text-[#A1A1AA]">MANAJEMEN AKUN & HAK AKSES</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">Kelola Pengguna Sistem</h1>
                <p class="text-sm text-[#A1A1AA] max-w-2xl">Pantau aktivitas akun pengguna, atur peran hak akses, dan lakukan reset kredensial secara aman.</p>
            </div>

            <div class="flex items-center gap-2 self-start md:self-auto">
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-[#1A1A1A] border border-[#27272A] text-xs font-semibold text-white shadow-sm">
                    <span class="material-symbols-outlined text-[18px] text-[#16A34A]">group</span>
                    <span>Total <strong class="text-[#4ADE80]" id="userTotalBadge">{{ count($users ?? []) }}</strong> Member Terdaftar</span>
                </div>
            </div>
        </div>

        <!-- Quick Stats Metric Band -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-[#1A1A1A] border border-[#27272A] rounded-xl p-4 flex items-center justify-between shadow-sm">
                <div class="flex flex-col">
                    <span class="text-[11px] font-semibold text-[#A1A1AA] uppercase tracking-wider">Member Aktif</span>
                    <span class="text-2xl font-extrabold text-white mt-1" id="statActiveMembers">{{ $userStats['active_members'] ?? 0 }}</span>
                </div>
                <div class="w-10 h-10 rounded-lg bg-[#27272A] flex items-center justify-center text-white">
                    <span class="material-symbols-outlined text-[20px]">sports_soccer</span>
                </div>
            </div>

            <div class="bg-[#1A1A1A] border border-[#27272A] rounded-xl p-4 flex items-center justify-between shadow-sm">
                <div class="flex flex-col">
                    <span class="text-[11px] font-semibold text-[#A1A1AA] uppercase tracking-wider">Member Baru Bulan Ini</span>
                    <span class="text-2xl font-extrabold text-[#4ADE80] mt-1" id="statNewMembers">{{ $userStats['new_this_month'] ?? 0 }}</span>
                </div>
                <div class="w-10 h-10 rounded-lg bg-[#166534]/30 border border-[#16A34A]/30 flex items-center justify-center text-[#4ADE80]">
                    <span class="material-symbols-outlined text-[20px]">person_add</span>
                </div>
            </div>

            <div class="bg-[#1A1A1A] border border-[#27272A] rounded-xl p-4 flex items-center justify-between shadow-sm">
                <div class="flex flex-col">
                    <span class="text-[11px] font-semibold text-[#A1A1AA] uppercase tracking-wider">Total Match Sessions</span>
                    <span class="text-2xl font-extrabold text-white mt-1">{{ $userStats['total_sessions'] ?? '0 Sesi' }}</span>
                </div>
                <div class="w-10 h-10 rounded-lg bg-[#27272A] flex items-center justify-center text-[#E4E4E7]">
                    <span class="material-symbols-outlined text-[20px]">event_available</span>
                </div>
            </div>

            <div class="bg-[#1A1A1A] border border-[#27272A] rounded-xl p-4 flex items-center justify-between shadow-sm">
                <div class="flex flex-col">
                    <span class="text-[11px] font-semibold text-[#A1A1AA] uppercase tracking-wider">Perlu Tindakan</span>
                    <span class="text-2xl font-extrabold text-[#FBBF24] mt-1" id="statActionNeeded">{{ $userStats['action_needed'] ?? '0 Akun' }}</span>
                </div>
                <div class="w-10 h-10 rounded-lg bg-[#78350F]/30 border border-[#B45309]/40 flex items-center justify-center text-[#FBBF24]">
                    <span class="material-symbols-outlined text-[20px]">lock_reset</span>
                </div>
            </div>
        </div>

        <!-- Top Action & Filter Bar -->
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 bg-[#181818] p-4 rounded-xl border border-[#27272A]">
            <div class="flex flex-wrap items-center gap-3 flex-1">
                <!-- Search Input -->
                <div class="relative w-full sm:w-72">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[18px] text-[#A1A1AA]">search</span>
                    <input type="text" 
                           id="userSearchInput" 
                           placeholder="Cari username member..." 
                           class="w-full h-10 pl-9 pr-3 bg-[#1F1F1F] text-white placeholder-[#71717A] border border-[#27272A] rounded-lg text-xs focus:outline-none focus:border-[#16A34A] transition-colors"/>
                </div>

                <!-- User Status Filter Pills -->
                <div class="flex items-center gap-1.5">
                    <button type="button" class="user-status-btn h-10 px-3.5 rounded-lg bg-[#16A34A] text-white text-xs font-semibold transition-colors" data-status="all">Semua Member</button>
                    <button type="button" class="user-status-btn h-10 px-3.5 rounded-lg bg-[#1F1F1F] text-[#A1A1AA] border border-[#27272A] text-xs font-semibold hover:text-white hover:bg-[#27272A] transition-colors" data-status="active">User Aktif</button>
                    <button type="button" class="user-status-btn h-10 px-3.5 rounded-lg bg-[#1F1F1F] text-[#A1A1AA] border border-[#27272A] text-xs font-semibold hover:text-white hover:bg-[#27272A] transition-colors" data-status="inactive">User Nonaktif</button>
                </div>
            </div>

            <!-- Add User Button -->
            <div class="flex items-center self-end sm:self-auto">
                <button type="button" 
                        onclick="toggleModal('add-user-modal')" 
                        class="h-10 px-4 bg-[#16A34A] hover:bg-[#15803D] active:bg-[#166534] text-white text-xs font-semibold rounded-lg flex items-center justify-center gap-1.5 transition-colors shadow-sm cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    <span>Tambah User</span>
                </button>
            </div>
        </div>

        <!-- Main Data Table Container -->
        <div class="bg-[#1A1A1A] border border-[#27272A] rounded-xl overflow-hidden shadow-2xl">
            <div class="w-full overflow-x-auto">
                <table class="w-full text-left border-collapse" id="usersTable">
                    <thead>
                        <tr class="bg-[#161616] border-b border-[#27272A] text-[#A1A1AA] text-[11px] uppercase tracking-wider font-semibold">
                            <th class="px-6 py-4 w-[140px]">User ID</th>
                            <th class="px-6 py-4">Username</th>
                            <th class="px-6 py-4 w-[130px]">Role</th>
                            <th class="px-6 py-4 w-[160px]">Total Booking</th>
                            <th class="px-6 py-4 w-[160px]">Status User</th>
                            <th class="px-6 py-4 text-right w-[140px]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#242424] text-xs" id="usersTableBody">
                        @forelse($users ?? [] as $u)
                            <tr class="user-row hover:bg-[#202020] transition-colors" 
                                id="user-row-{{ $u['id'] }}"
                                data-id="{{ $u['id'] }}"
                                data-username="{{ strtolower($u['username']) }}"
                                data-status="{{ ($u['must_change_password'] ?? false) ? 'need_change' : 'active' }}"
                                data-is-active="{{ ($u['is_active'] ?? true) ? 'true' : 'false' }}">
                                
                                <!-- User ID -->
                                <td class="px-6 py-4 font-mono font-medium text-[#D4D4D8]">{{ $u['user_id_string'] ?? 'USR-' . $u['id'] }}</td>

                                <!-- Username -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-[#27272A] text-white flex items-center justify-center font-bold text-xs uppercase border border-[#3F3F46] shrink-0 user-avatar-initials">
                                            {{ substr($u['username'], 0, 2) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-white font-semibold text-sm username-display">{{ $u['username'] }}</span>
                                            <span class="text-[11px] text-[#71717A]">Terdaftar: {{ $u['created_at'] ?? 'Baru saja' }}</span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Role -->
                                <td class="px-6 py-4">
                                    @if(strtolower($u['role']) === 'admin')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-[#166534]/30 text-[#4ADE80] border border-[#16A34A]/40">
                                            <span class="material-symbols-outlined text-[13px]">shield</span>
                                            Admin
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#27272A] text-[#E4E4E7] border border-[#3F3F46]">
                                            User
                                        </span>
                                    @endif
                                </td>

                                <!-- Total Booking -->
                                <td class="px-6 py-4">
                                    <span class="text-[#E4E4E7] font-semibold font-mono">{{ $u['total_bookings'] }} Sesi</span>
                                </td>

                                <!-- Status User -->
                                <td class="px-6 py-4 user-status-cell">
                                    @if($u['is_active'] ?? true)
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-[#16A34A] shadow-[0_0_6px_#16A34A]"></span>
                                            <span class="text-[#4ADE80] font-medium">Aktif</span>
                                        </div>
                                    @else
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-[#EF4444] shadow-[0_0_6px_#EF4444]"></span>
                                            <span class="text-[#EF4444] font-medium">Nonaktif</span>
                                        </div>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <!-- Edit Username & Status -->
                                        <button type="button" 
                                                onclick="openEditUserModal({{ $u['id'] }}, '{{ $u['username'] }}', {{ ($u['is_active'] ?? true) ? 'true' : 'false' }})" 
                                                class="edit-user-btn p-2 rounded-lg text-[#38BDF8] hover:bg-[#38BDF8]/10 transition-colors cursor-pointer" 
                                                title="Edit Username & Status Akun">
                                            <span class="material-symbols-outlined text-[19px]">edit</span>
                                        </button>

                                        <!-- Reset Password Modal Trigger -->
                                        <button type="button" 
                                                onclick="openResetPasswordModal({{ $u['id'] }}, '{{ $u['username'] }}')" 
                                                class="p-2 rounded-lg text-[#FBBF24] hover:bg-[#F59E0B]/10 transition-colors cursor-pointer" 
                                                title="Reset Password ke Default">
                                            <span class="material-symbols-outlined text-[19px]">key</span>
                                        </button>

                                        <!-- Delete User (Disabled for current logged-in user) -->
                                        @if((int)$u['id'] === (int)auth()->id())
                                            <div class="p-2 text-[#52525B] cursor-not-allowed select-none" title="Akun Anda saat ini terproteksi">
                                                <span class="material-symbols-outlined text-[19px]">lock</span>
                                            </div>
                                        @else
                                            <button type="button" 
                                                    onclick="openDeleteUserModal({{ $u['id'] }}, '{{ $u['username'] }}')" 
                                                    class="p-2 rounded-lg text-[#EF4444] hover:bg-[#EF4444]/10 transition-colors cursor-pointer" 
                                                    title="Hapus Akun Pengguna">
                                                <span class="material-symbols-outlined text-[19px]">delete</span>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-[#71717A]">Tidak ada data pengguna.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Table Footer / Metadata Bar -->
            <div class="flex flex-col sm:flex-row items-center justify-between px-6 py-4 bg-[#161616] border-t border-[#27272A] gap-2 text-xs text-[#A1A1AA]">
                <span>Menampilkan <strong class="text-white" id="visibleUserCount">{{ count($users ?? []) }}</strong> pengguna sistem</span>
                <div class="flex items-center gap-1.5 text-[#71717A]">
                    <span class="material-symbols-outlined text-[15px] text-[#16A34A]">shield</span>
                    <span>Aktivitas reset kredensial dicatat secara otomatis pada sistem audit.</span>
                </div>
            </div>
        </div>

        <!-- Security & Audit Quick Log Box -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-1">
            <div class="p-4 bg-[#161616] border border-[#27272A] rounded-xl flex items-start gap-3">
                <div class="p-2 bg-[#27272A] rounded-lg text-[#4ADE80] shrink-0">
                    <span class="material-symbols-outlined text-[20px]">policy</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-xs font-bold text-white">Kebijakan Kata Sandi</span>
                    <p class="text-[11px] text-[#A1A1AA] mt-0.5">Password sementara akan memaksa user mengganti kata sandi pada halaman /change-password.</p>
                </div>
            </div>

            <div class="p-4 bg-[#161616] border border-[#27272A] rounded-xl flex items-start gap-3">
                <div class="p-2 bg-[#27272A] rounded-lg text-[#FBBF24] shrink-0">
                    <span class="material-symbols-outlined text-[20px]">lock_reset</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-xs font-bold text-white">Default Password</span>
                    <p class="text-[11px] text-[#A1A1AA] mt-0.5">Password default admin-created disetel ke <code>socstreet123</code> secara otomatis.</p>
                </div>
            </div>

            <div class="p-4 bg-[#161616] border border-[#27272A] rounded-xl flex items-start gap-3">
                <div class="p-2 bg-[#27272A] rounded-lg text-white shrink-0">
                    <span class="material-symbols-outlined text-[20px]">verified_user</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-xs font-bold text-white">Constraint Kredensial</span>
                    <p class="text-[11px] text-[#A1A1AA] mt-0.5">Sistem mematuhi aturan PRD: autentikasi hanya menggunakan <code>username</code> & <code>password</code> tanpa email.</p>
                </div>
            </div>
        </div>

    </div>

    <!-- Modal: Tambah User Baru (Strict: User only, admin is only 1) -->
    <x-modal id="add-user-modal" title="Tambah Pengguna Baru" subtitle="Sistem dibatasi hanya memiliki 1 Administrator (Root Authority)" maxWidth="md">
        <form id="createAccountForm" class="flex flex-col gap-4" onsubmit="handleAddUserSubmit(event)">
            <!-- Username -->
            <div class="flex flex-col gap-1.5">
                <label class="text-[11px] font-semibold text-[#A1A1AA] uppercase tracking-wider" for="newUsername">Username</label>
                <div class="relative flex items-center">
                    <span class="material-symbols-outlined absolute left-3 text-[#71717A] text-[18px]">alternate_email</span>
                    <input type="text" 
                           id="newUsername" 
                           placeholder="cth: garuda_united" 
                           class="w-full h-10 pl-9 pr-3 bg-[#1F1F1F] text-white border border-[#27272A] rounded-lg text-xs focus:outline-none focus:border-[#16A34A]" 
                           required/>
                </div>
            </div>

            <!-- Role Selection (Strict: User only) -->
            <div class="grid grid-cols-2 gap-3">
                <div class="flex flex-col gap-1.5">
                    <label class="text-[11px] font-semibold text-[#A1A1AA] uppercase tracking-wider">Role Akun</label>
                    <div class="h-10 px-3 bg-[#1F1F1F] border border-[#27272A] rounded-lg flex items-center gap-2 text-white text-xs select-none">
                        <span class="w-2 h-2 rounded-full bg-[#16A34A]"></span>
                        <span class="font-medium text-[#E4E4E7]">User (Member)</span>
                    </div>
                    <input type="hidden" id="newRole" value="user" />
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="text-[11px] font-semibold text-[#A1A1AA] uppercase tracking-wider">Status Awal</label>
                    <input class="h-10 px-3 bg-[#242424] text-[#A1A1AA] border border-[#27272A] rounded-lg text-xs cursor-not-allowed" 
                           disabled 
                           type="text" 
                           value="Wajib Ganti Password"/>
                </div>
            </div>

            <!-- Info Box about default password & single admin rule -->
            <div class="p-3 bg-[#121212] rounded-lg border border-[#27272A] flex items-start gap-2.5 text-[#A1A1AA] text-xs">
                <span class="material-symbols-outlined text-[17px] text-[#FBBF24] shrink-0 mt-0.5">info</span>
                <span>Kata sandi default <code>socstreet123</code> akan disetel. Akun Administrator dibatasi hanya 1 di sistem, sehingga pendaftaran baru otomatis berstatus User/Member.</span>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-[#27272A]">
                <x-button variant="secondary" size="md" type="button" onclick="toggleModal('add-user-modal')">
                    Batal
                </x-button>
                <button id="addUserSubmitBtn"
                        type="submit" 
                        class="h-10 px-5 bg-[#16A34A] hover:bg-[#15803D] text-white text-xs font-semibold rounded-xl transition-colors cursor-pointer shadow-md flex items-center justify-center gap-1.5">
                    <span class="material-symbols-outlined text-[18px]">person_add</span>
                    <span>Simpan Akun</span>
                </button>
            </div>
        </form>
    </x-modal>

    <!-- Modal: Edit Data Pengguna -->
    <x-modal id="edit-user-modal" title="Edit Data Pengguna" subtitle="Perbarui identitas username member" maxWidth="sm">
        <form id="editUserForm" class="space-y-4" onsubmit="handleEditUserSubmit(event)">
            <div class="text-center py-1 space-y-2">
                <div class="w-12 h-12 rounded-full bg-[#38BDF8]/15 border border-[#38BDF8]/30 text-[#38BDF8] flex items-center justify-center mx-auto">
                    <span class="material-symbols-outlined text-[24px]">manage_accounts</span>
                </div>
                <h4 class="text-sm font-bold text-white">Ubah Username Pengguna</h4>
                <p class="text-xs text-[#A1A1AA]">
                    Mengubah username akun <strong id="editModalOldUsername" class="text-white font-mono"></strong>
                </p>
            </div>

            <div class="flex flex-col gap-1.5 text-left">
                <label class="text-[11px] font-semibold text-[#A1A1AA] uppercase tracking-wider" for="editUsernameInput">Username</label>
                <div class="relative flex items-center">
                    <span class="material-symbols-outlined absolute left-3 text-[#71717A] text-[18px]">alternate_email</span>
                    <input type="text" 
                           id="editUsernameInput" 
                           class="w-full h-10 pl-9 pr-3 bg-[#1F1F1F] text-white border border-[#27272A] rounded-lg text-xs focus:outline-none focus:border-[#16A34A] font-mono"
                           placeholder="Username saat ini atau baru (opsional)"/>
                </div>
                <p class="text-[10px] text-[#71717A] mt-0.5">
                    Biarkan tetap atau kosongkan jika hanya ingin memperbarui status akun.
                </p>
            </div>

            <!-- Status Akun (Aktif / Nonaktifkan karena kecurangan/pelanggaran) -->
            <div class="flex flex-col gap-1.5 text-left">
                <label class="text-[11px] font-semibold text-[#A1A1AA] uppercase tracking-wider" for="editStatusSelect">Status Akun</label>
                <select id="editStatusSelect" class="h-10 px-3 bg-[#1F1F1F] text-white border border-[#27272A] rounded-lg text-xs focus:outline-none focus:border-[#16A34A]">
                    <option value="1">Aktif (Dapat Login & Booking)</option>
                    <option value="0">Nonaktifkan Akun (Kecurangan / Pelanggaran)</option>
                </select>
                <p class="text-[10px] text-[#71717A] mt-0.5 leading-relaxed">
                    Akun yang dinonaktifkan akan diblokir dari login dan tidak dapat melakukan reservasi lapangan.
                </p>
            </div>

            <div class="p-2.5 bg-[#141414] rounded-lg border border-[#27272A] flex items-center gap-2 text-xs text-[#A1A1AA]">
                <span class="material-symbols-outlined text-[16px] text-[#38BDF8]">info</span>
                <span>Username minimal 3 karakter. Anda dapat mengubah status akun saja tanpa mengganti username.</span>
            </div>

            <div class="pt-3 border-t border-[#27272A] flex items-center justify-end gap-2.5">
                <x-button variant="secondary" size="md" type="button" onclick="toggleModal('edit-user-modal')">
                    Batal
                </x-button>
                <button type="submit" 
                        id="editUserSubmitBtn" 
                        class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl text-xs font-bold bg-[#16A34A] hover:bg-[#15803D] text-white shadow-lg shadow-[#16A34A]/25 transition-all cursor-pointer">
                    <span class="material-symbols-outlined text-[16px]">save</span>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </x-modal>

    <!-- Modal: Konfirmasi Reset Password -->
    <x-modal id="reset-password-modal" title="Konfirmasi Reset Password" subtitle="Kredensial pengguna akan diatur ulang" maxWidth="sm">
        <div class="text-center py-2 space-y-3">
            <div class="w-12 h-12 rounded-full bg-[#F59E0B]/15 border border-[#F59E0B]/30 text-[#FBBF24] flex items-center justify-center mx-auto">
                <span class="material-symbols-outlined text-[24px]">lock_reset</span>
            </div>
            <h4 class="text-base font-bold text-white">Reset Kata Sandi Akun?</h4>
            <p class="text-xs text-[#A1A1AA] leading-relaxed">
                Kata sandi untuk pengguna <strong id="resetModalUsername" class="text-white font-mono"></strong> akan disetel kembali ke <code class="px-1.5 py-0.5 rounded bg-[#27272A] text-[#FBBF24] font-mono text-xs">socstreet123</code>. Pengguna wajib mengganti password saat login berikutnya.
            </p>
        </div>

        <x-slot:footer>
            <x-button variant="secondary" size="md" type="button" onclick="toggleModal('reset-password-modal')">
                Batal
            </x-button>
            <button type="button" 
                    id="confirmResetBtn"
                    onclick="executeResetPassword()" 
                    class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl text-xs font-bold bg-[#F59E0B] hover:bg-[#D97706] text-black shadow-lg shadow-[#F59E0B]/25 transition-all cursor-pointer">
                <span class="material-symbols-outlined text-[16px]">key</span>
                <span>Ya, Reset Password</span>
            </button>
        </x-slot:footer>
    </x-modal>

    <!-- Modal: Konfirmasi Hapus Pengguna -->
    <x-modal id="delete-user-modal" title="Konfirmasi Hapus Pengguna" subtitle="Tindakan penghapusan akun bersifat permanen" maxWidth="sm">
        <div class="text-center py-2 space-y-3">
            <div class="w-12 h-12 rounded-full bg-[#EF4444]/15 border border-[#EF4444]/30 text-[#EF4444] flex items-center justify-center mx-auto">
                <span class="material-symbols-outlined text-[24px]">person_remove</span>
            </div>
            <h4 class="text-base font-bold text-white">Hapus Akun Pengguna?</h4>
            <p class="text-xs text-[#A1A1AA] leading-relaxed">
                Apakah Anda yakin ingin menghapus akun <strong id="deleteModalUsername" class="text-white font-mono"></strong>? Seluruh data member akan dihapus permanen dari sistem.
            </p>
        </div>

        <x-slot:footer>
            <x-button variant="secondary" size="md" type="button" onclick="toggleModal('delete-user-modal')">
                Batal
            </x-button>
            <button type="button" 
                    id="confirmDeleteBtn"
                    onclick="executeDeleteUser()" 
                    class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl text-xs font-bold bg-[#EF4444] hover:bg-[#DC2626] text-white shadow-lg shadow-[#EF4444]/25 transition-all cursor-pointer">
                <span class="material-symbols-outlined text-[16px]">delete</span>
                <span>Ya, Hapus Sekarang</span>
            </button>
        </x-slot:footer>
    </x-modal>

    @push('scripts')
    <script>
        const statusBtns = document.querySelectorAll('.user-status-btn');
        const userSearchInput = document.getElementById('userSearchInput');
        const visibleUserCount = document.getElementById('visibleUserCount');

        let activeUserStatus = 'all';

        function filterUsers() {
            const query = userSearchInput ? userSearchInput.value.toLowerCase().trim() : '';
            const userRows = document.querySelectorAll('.user-row');
            let count = 0;

            userRows.forEach(row => {
                const username = row.getAttribute('data-username');
                const isActive = row.getAttribute('data-is-active') === 'true';

                let matchStatus = true;
                if (activeUserStatus === 'active') {
                    matchStatus = isActive === true;
                } else if (activeUserStatus === 'inactive') {
                    matchStatus = isActive === false;
                }

                const matchSearch = !query || username.includes(query);

                if (matchStatus && matchSearch) {
                    row.style.display = '';
                    count++;
                } else {
                    row.style.display = 'none';
                }
            });

            if (visibleUserCount) {
                visibleUserCount.innerText = count;
            }
        }

        statusBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                statusBtns.forEach(b => {
                    b.className = 'user-status-btn h-10 px-3.5 rounded-lg bg-[#1F1F1F] text-[#A1A1AA] border border-[#27272A] text-xs font-semibold hover:text-white hover:bg-[#27272A] transition-colors';
                });
                btn.className = 'user-status-btn h-10 px-3.5 rounded-lg bg-[#16A34A] text-white text-xs font-semibold transition-colors';
                activeUserStatus = btn.getAttribute('data-status');
                filterUsers();
            });
        });

        if (userSearchInput) {
            userSearchInput.addEventListener('input', filterUsers);
        }

        let currentActionUserId = null;
        let currentActionUsername = '';

        async function handleAddUserSubmit(event) {
            event.preventDefault();
            const usernameInput = document.getElementById('newUsername');
            const submitBtn = document.getElementById('addUserSubmitBtn');
            const originalBtnHtml = submitBtn.innerHTML;

            const username = usernameInput.value.trim();
            const role = 'user'; // System strictly allows only User role, Admin is only 1

            if (!username) {
                showAdminToast('Input Kosong', 'Silakan masukkan username pengguna.', false);
                return;
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <span class="w-3.5 h-3.5 border-2 border-white/20 border-t-white rounded-full animate-spin"></span>
                <span>Menyimpan...</span>
            `;

            try {
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const response = await fetch("{{ route('admin.users.create') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token || ''
                    },
                    body: JSON.stringify({
                        username: username,
                        role: role
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || (data.errors ? Object.values(data.errors)[0][0] : 'Gagal membuat pengguna baru.'));
                }

                toggleModal('add-user-modal');
                usernameInput.value = '';

                const tbody = document.getElementById('usersTableBody');
                const newRow = document.createElement('tr');
                newRow.className = 'user-row hover:bg-[#202020] transition-colors';
                newRow.id = `user-row-${data.data.id}`;
                newRow.setAttribute('data-id', data.data.id);
                newRow.setAttribute('data-username', data.data.username.toLowerCase());
                newRow.setAttribute('data-status', 'need_change');
                newRow.setAttribute('data-is-active', 'true');

                newRow.innerHTML = `
                    <td class="px-6 py-4 font-mono font-medium text-[#D4D4D8]">${data.data.user_id_string}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-[#27272A] text-white flex items-center justify-center font-bold text-xs uppercase border border-[#3F3F46] shrink-0 user-avatar-initials">
                                ${data.data.username.substring(0, 2).toUpperCase()}
                            </div>
                            <div class="flex flex-col">
                                <span class="text-white font-semibold text-sm username-display">${data.data.username}</span>
                                <span class="text-[11px] text-[#71717A]">Terdaftar: ${data.data.created_at}</span>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#27272A] text-[#E4E4E7] border border-[#3F3F46]">
                            User
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-[#E4E4E7] font-semibold font-mono">0 Sesi</span>
                    </td>
                    <td class="px-6 py-4 user-status-cell">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-[#16A34A] shadow-[0_0_6px_#16A34A]"></span>
                            <span class="text-[#4ADE80] font-medium">Aktif</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-1.5">
                            <button type="button" 
                                    onclick="openEditUserModal(${data.data.id}, '${data.data.username}', true)" 
                                    class="edit-user-btn p-2 rounded-lg text-[#38BDF8] hover:bg-[#38BDF8]/10 transition-colors cursor-pointer" 
                                    title="Edit Username & Status Akun">
                                <span class="material-symbols-outlined text-[19px]">edit</span>
                            </button>
                            <button type="button" 
                                    onclick="openResetPasswordModal(${data.data.id}, '${data.data.username}')" 
                                    class="p-2 rounded-lg text-[#FBBF24] hover:bg-[#F59E0B]/10 transition-colors cursor-pointer" 
                                    title="Reset Password ke Default">
                                <span class="material-symbols-outlined text-[19px]">key</span>
                            </button>
                            <button type="button" 
                                    onclick="openDeleteUserModal(${data.data.id}, '${data.data.username}')" 
                                    class="p-2 rounded-lg text-[#EF4444] hover:bg-[#EF4444]/10 transition-colors cursor-pointer" 
                                    title="Hapus Akun Pengguna">
                                <span class="material-symbols-outlined text-[19px]">delete</span>
                            </button>
                        </div>
                    </td>
                `;

                tbody.prepend(newRow);

                // Update badge and counts
                const totalBadge = document.getElementById('userTotalBadge');
                if (totalBadge) totalBadge.innerText = parseInt(totalBadge.innerText || 0) + 1;

                const memStat = document.getElementById('statActiveMembers');
                if (memStat) memStat.innerText = parseInt(memStat.innerText || 0) + 1;

                const newStat = document.getElementById('statNewMembers');
                if (newStat) newStat.innerText = parseInt(newStat.innerText || 0) + 1;

                const actionNeededStat = document.getElementById('statActionNeeded');
                if (actionNeededStat) {
                    const cur = parseInt(actionNeededStat.innerText) || 0;
                    actionNeededStat.innerText = (cur + 1) + ' Akun';
                }

                showAdminToast('User Berhasil Dibuat', data.message || `Akun ${username} dibuat dengan default password (socstreet123).`, true);
                filterUsers();
            } catch (err) {
                showAdminToast('Gagal Menambah Akun', err.message || 'Terjadi kesalahan sistem.', false);
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;
            }
        }

        // ================= EDIT USER MODAL LOGIC =================
        function openEditUserModal(userId, username, isActive) {
            currentActionUserId = userId;
            currentActionUsername = username;
            document.getElementById('editModalOldUsername').innerText = username;
            document.getElementById('editUsernameInput').value = username;
            document.getElementById('editStatusSelect').value = (isActive === false || isActive === 'false') ? '0' : '1';
            toggleModal('edit-user-modal');
        }

        async function handleEditUserSubmit(event) {
            event.preventDefault();
            if (!currentActionUserId) return;
            const newUsername = document.getElementById('editUsernameInput').value.trim();
            const isActive = document.getElementById('editStatusSelect').value === '1';

            const btn = document.getElementById('editUserSubmitBtn');
            const origHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="material-symbols-outlined text-[16px] animate-spin">progress_activity</span><span>Menyimpan...</span>';

            const payload = {
                _method: 'PUT',
                is_active: isActive
            };
            // Only send username if it has actually been changed and is not empty
            if (newUsername && newUsername !== currentActionUsername) {
                payload.username = newUsername;
            }

            try {
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const response = await fetch(`/admin/users/${currentActionUserId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token || ''
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();
                if (!response.ok) {
                    throw new Error(data.message || (data.errors ? Object.values(data.errors)[0][0] : 'Gagal memperbarui pengguna.'));
                }

                const row = document.getElementById('user-row-' + currentActionUserId);
                if (row) {
                    row.setAttribute('data-username', data.data.username.toLowerCase());
                    row.setAttribute('data-is-active', data.data.is_active ? 'true' : 'false');
                    const nameEl = row.querySelector('.username-display');
                    if (nameEl) nameEl.innerText = data.data.username;
                    const avatarEl = row.querySelector('.user-avatar-initials');
                    if (avatarEl) avatarEl.innerText = data.data.username.substring(0, 2).toUpperCase();

                    const statusCell = row.querySelector('.user-status-cell');
                    if (statusCell) {
                        if (data.data.is_active) {
                            statusCell.innerHTML = `
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-[#16A34A] shadow-[0_0_6px_#16A34A]"></span>
                                    <span class="text-[#4ADE80] font-medium">Aktif</span>
                                </div>
                            `;
                        } else {
                            statusCell.innerHTML = `
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-[#EF4444] shadow-[0_0_6px_#EF4444]"></span>
                                    <span class="text-[#EF4444] font-medium">Nonaktif</span>
                                </div>
                            `;
                        }
                    }

                    const editBtn = row.querySelector('.edit-user-btn');
                    if (editBtn) editBtn.setAttribute('onclick', `openEditUserModal(${data.data.id}, '${data.data.username}', ${data.data.is_active})`);
                }

                toggleModal('edit-user-modal');
                const statusNote = data.data.is_active ? '' : ' (Akun dinonaktifkan)';
                showAdminToast('Pengguna Diperbarui', (data.message || `Data pengguna ${data.data.username} berhasil disimpan.`) + statusNote, true);
                filterUsers();
            } catch (err) {
                showAdminToast('Gagal Update', err.message || 'Terjadi kesalahan sistem.', false);
            } finally {
                btn.disabled = false;
                btn.innerHTML = origHtml;
            }
        }

        // ================= RESET PASSWORD MODAL LOGIC =================
        function openResetPasswordModal(userId, username) {
            currentActionUserId = userId;
            currentActionUsername = username;
            document.getElementById('resetModalUsername').innerText = username;
            toggleModal('reset-password-modal');
        }

        async function executeResetPassword() {
            if (!currentActionUserId) return;
            const btn = document.getElementById('confirmResetBtn');
            const origHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="material-symbols-outlined text-[16px] animate-spin">progress_activity</span><span>Mereset...</span>';

            try {
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const response = await fetch(`/admin/users/${currentActionUserId}/reset-password`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token || ''
                    }
                });

                const data = await response.json();
                if (!response.ok) {
                    throw new Error(data.message || 'Gagal mereset password.');
                }

                const row = document.getElementById('user-row-' + currentActionUserId);
                if (row) {
                    row.setAttribute('data-status', 'need_change');
                    const statusCell = row.querySelector('.password-status-cell');
                    if (statusCell) {
                        statusCell.innerHTML = `
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-[#F59E0B]"></span>
                                <span class="text-[#FBBF24] font-medium">Default (Wajib Ganti)</span>
                            </div>
                        `;
                    }
                }

                toggleModal('reset-password-modal');
                showAdminToast('Password Direset', data.message || `Password akun ${currentActionUsername} direset ke default (socstreet123).`, true);
            } catch (err) {
                showAdminToast('Gagal Reset', err.message || 'Terjadi kesalahan sistem.', false);
            } finally {
                btn.disabled = false;
                btn.innerHTML = origHtml;
            }
        }

        // ================= DELETE USER MODAL LOGIC =================
        function openDeleteUserModal(userId, username) {
            currentActionUserId = userId;
            currentActionUsername = username;
            document.getElementById('deleteModalUsername').innerText = username;
            toggleModal('delete-user-modal');
        }

        async function executeDeleteUser() {
            if (!currentActionUserId) return;
            const btn = document.getElementById('confirmDeleteBtn');
            const origHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="material-symbols-outlined text-[16px] animate-spin">progress_activity</span><span>Menghapus...</span>';

            try {
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const response = await fetch(`/admin/users/${currentActionUserId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token || ''
                    }
                });

                const data = await response.json();
                if (!response.ok) {
                    throw new Error(data.message || 'Gagal menghapus pengguna.');
                }

                const row = document.getElementById('user-row-' + currentActionUserId);
                if (row) {
                    row.remove();
                }

                const totalBadge = document.getElementById('userTotalBadge');
                if (totalBadge) {
                    const cur = parseInt(totalBadge.innerText) || 0;
                    if (cur > 0) totalBadge.innerText = cur - 1;
                }

                const memStat = document.getElementById('statActiveMembers');
                if (memStat) {
                    const cur = parseInt(memStat.innerText) || 0;
                    if (cur > 0) memStat.innerText = cur - 1;
                }

                toggleModal('delete-user-modal');
                filterUsers();
                showAdminToast('Akun Dihapus', data.message || `User ${currentActionUsername} telah dihapus dari sistem.`, false);
            } catch (err) {
                showAdminToast('Gagal Hapus', err.message || 'Terjadi kesalahan sistem.', false);
            } finally {
                btn.disabled = false;
                btn.innerHTML = origHtml;
            }
        }
    </script>
    @endpush
</x-layouts.admin>
