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
                    <span>Total <strong class="text-[#4ADE80]" id="userTotalBadge">{{ count($users ?? []) }}</strong> Pengguna Terdaftar</span>
                </div>
            </div>
        </div>

        <!-- Quick Stats Metric Band -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-[#1A1A1A] border border-[#27272A] rounded-xl p-4 flex items-center justify-between shadow-sm">
                <div class="flex flex-col">
                    <span class="text-[11px] font-semibold text-[#A1A1AA] uppercase tracking-wider">Member Aktif</span>
                    <span class="text-2xl font-extrabold text-white mt-1">{{ $userStats['active_members'] ?? 3 }}</span>
                </div>
                <div class="w-10 h-10 rounded-lg bg-[#27272A] flex items-center justify-center text-white">
                    <span class="material-symbols-outlined text-[20px]">sports_soccer</span>
                </div>
            </div>

            <div class="bg-[#1A1A1A] border border-[#27272A] rounded-xl p-4 flex items-center justify-between shadow-sm">
                <div class="flex flex-col">
                    <span class="text-[11px] font-semibold text-[#A1A1AA] uppercase tracking-wider">Super Administrator</span>
                    <span class="text-2xl font-extrabold text-[#4ADE80] mt-1">{{ $userStats['admins'] ?? 1 }}</span>
                </div>
                <div class="w-10 h-10 rounded-lg bg-[#166534]/30 border border-[#16A34A]/30 flex items-center justify-center text-[#4ADE80]">
                    <span class="material-symbols-outlined text-[20px]">admin_panel_settings</span>
                </div>
            </div>

            <div class="bg-[#1A1A1A] border border-[#27272A] rounded-xl p-4 flex items-center justify-between shadow-sm">
                <div class="flex flex-col">
                    <span class="text-[11px] font-semibold text-[#A1A1AA] uppercase tracking-wider">Total Match Sessions</span>
                    <span class="text-2xl font-extrabold text-white mt-1">{{ $userStats['total_sessions'] ?? '9 Sesi' }}</span>
                </div>
                <div class="w-10 h-10 rounded-lg bg-[#27272A] flex items-center justify-center text-[#E4E4E7]">
                    <span class="material-symbols-outlined text-[20px]">event_available</span>
                </div>
            </div>

            <div class="bg-[#1A1A1A] border border-[#27272A] rounded-xl p-4 flex items-center justify-between shadow-sm">
                <div class="flex flex-col">
                    <span class="text-[11px] font-semibold text-[#A1A1AA] uppercase tracking-wider">Perlu Tindakan</span>
                    <span class="text-2xl font-extrabold text-[#FBBF24] mt-1">{{ $userStats['action_needed'] ?? '1 Akun' }}</span>
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
                           placeholder="Cari username..." 
                           class="w-full h-10 pl-9 pr-3 bg-[#1F1F1F] text-white placeholder-[#71717A] border border-[#27272A] rounded-lg text-xs focus:outline-none focus:border-[#16A34A] transition-colors"/>
                </div>

                <!-- Role Filter Pills -->
                <div class="flex items-center gap-1.5">
                    <button type="button" class="user-role-btn h-10 px-3.5 rounded-lg bg-[#16A34A] text-white text-xs font-semibold transition-colors" data-role="all">Semua Role</button>
                    <button type="button" class="user-role-btn h-10 px-3.5 rounded-lg bg-[#1F1F1F] text-[#A1A1AA] border border-[#27272A] text-xs font-semibold hover:text-white hover:bg-[#27272A] transition-colors" data-role="user">User</button>
                    <button type="button" class="user-role-btn h-10 px-3.5 rounded-lg bg-[#1F1F1F] text-[#A1A1AA] border border-[#27272A] text-xs font-semibold hover:text-white hover:bg-[#27272A] transition-colors" data-role="admin">Admin</button>
                </div>
            </div>

            <!-- Add User Button -->
            <div class="flex items-center self-end sm:self-auto">
                <button type="button" 
                        onclick="document.getElementById('addUserModal').classList.remove('hidden')" 
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
                            <th class="px-6 py-4 w-[220px]">Status Password</th>
                            <th class="px-6 py-4 text-right w-[140px]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#242424] text-xs" id="usersTableBody">
                        @forelse($users ?? [] as $u)
                            <tr class="user-row hover:bg-[#202020] transition-colors" 
                                id="user-row-{{ $u['id'] }}"
                                data-id="{{ $u['id'] }}"
                                data-username="{{ strtolower($u['username']) }}"
                                data-role="{{ strtolower($u['role']) }}">
                                
                                <!-- User ID -->
                                <td class="px-6 py-4 font-mono font-medium text-[#D4D4D8]">{{ $u['id'] }}</td>

                                <!-- Username -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-[#27272A] text-white flex items-center justify-center font-bold text-xs uppercase border border-[#3F3F46] shrink-0">
                                            {{ substr($u['username'], 0, 2) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-white font-semibold text-sm">{{ $u['username'] }}</span>
                                            <span class="text-[11px] text-[#71717A]">Terdaftar: {{ $u['created_at'] ?? '1 Nov 2026' }}</span>
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

                                <!-- Password Status -->
                                <td class="px-6 py-4 password-status-cell">
                                    @if($u['must_change_password'] ?? false)
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-[#F59E0B]"></span>
                                            <span class="text-[#FBBF24] font-medium">Default (Wajib Ganti)</span>
                                        </div>
                                    @else
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-[#16A34A] shadow-[0_0_6px_#16A34A]"></span>
                                            <span class="text-[#4ADE80] font-medium">Aktif</span>
                                        </div>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <!-- Reset Password -->
                                        <button type="button" 
                                                onclick="handleResetPassword('{{ $u['id'] }}', '{{ $u['username'] }}')" 
                                                class="p-2 rounded-lg text-[#FBBF24] hover:bg-[#F59E0B]/10 transition-colors" 
                                                title="Reset Password ke Default">
                                            <span class="material-symbols-outlined text-[19px]">key</span>
                                        </button>

                                        <!-- Delete User (Disabled for admin root) -->
                                        @if(strtolower($u['role']) === 'admin')
                                            <div class="p-2 text-[#52525B] cursor-not-allowed select-none" title="Akun admin terproteksi tidak dapat dihapus">
                                                <span class="material-symbols-outlined text-[19px]">lock</span>
                                            </div>
                                        @else
                                            <button type="button" 
                                                    onclick="handleDeleteUser('{{ $u['id'] }}', '{{ $u['username'] }}')" 
                                                    class="p-2 rounded-lg text-[#EF4444] hover:bg-[#EF4444]/10 transition-colors" 
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

    <!-- Modal: Tambah User Baru (Strict PRD: Username & Role only) -->
    <div id="addUserModal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center p-4">
        <div class="bg-[#181818] border border-[#27272A] rounded-xl max-w-md w-full p-6 shadow-2xl relative">
            <div class="flex items-center justify-between border-b border-[#27272A] pb-3 mb-4">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-[#16A34A]/20 border border-[#16A34A]/40 flex items-center justify-center text-[#4ADE80]">
                        <span class="material-symbols-outlined text-[18px]">person_add</span>
                    </div>
                    <span class="text-base font-bold text-white">Tambah Pengguna Baru</span>
                </div>
                <button type="button" 
                        onclick="document.getElementById('addUserModal').classList.add('hidden')"
                        class="text-[#A1A1AA] hover:text-white transition-colors">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            <!-- Form strictly adheres to PRD: ONLY username & role -->
            <form id="createAccountForm" class="flex flex-col gap-4" onsubmit="event.preventDefault(); handleAddUserSubmit();">
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

                <!-- Role Selection -->
                <div class="grid grid-cols-2 gap-3">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[11px] font-semibold text-[#A1A1AA] uppercase tracking-wider" for="newRole">Role</label>
                        <select id="newRole" class="h-10 px-3 bg-[#1F1F1F] text-white border border-[#27272A] rounded-lg text-xs focus:outline-none focus:border-[#16A34A]">
                            <option value="User">User (Customer)</option>
                            <option value="Admin">Administrator</option>
                        </select>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[11px] font-semibold text-[#A1A1AA] uppercase tracking-wider">Status Awal</label>
                        <input class="h-10 px-3 bg-[#242424] text-[#A1A1AA] border border-[#27272A] rounded-lg text-xs cursor-not-allowed" 
                               disabled 
                               type="text" 
                               value="Wajib Ganti Password"/>
                    </div>
                </div>

                <!-- Info Box about default password -->
                <div class="p-3 bg-[#121212] rounded-lg border border-[#27272A] flex items-start gap-2.5 text-[#A1A1AA] text-xs">
                    <span class="material-symbols-outlined text-[17px] text-[#FBBF24] shrink-0 mt-0.5">info</span>
                    <span>Kata sandi default <code>socstreet123</code> akan disetel dan pengguna wajib membuat kata sandi baru saat pertama kali login.</span>
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-[#27272A]">
                    <button type="button" 
                            onclick="document.getElementById('addUserModal').classList.add('hidden')"
                            class="h-10 px-4 text-[#A1A1AA] hover:text-white text-xs font-semibold rounded-lg hover:bg-[#27272A] transition-colors">
                        Batal
                    </button>
                    <button type="submit" 
                            class="h-10 px-5 bg-[#16A34A] hover:bg-[#15803D] text-white text-xs font-semibold rounded-lg transition-colors cursor-pointer shadow-md">
                        Simpan Akun
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        const roleBtns = document.querySelectorAll('.user-role-btn');
        const userSearchInput = document.getElementById('userSearchInput');
        const userRows = document.querySelectorAll('.user-row');
        const visibleUserCount = document.getElementById('visibleUserCount');

        let activeUserRole = 'all';

        function filterUsers() {
            const query = userSearchInput.value.toLowerCase().trim();
            let count = 0;

            userRows.forEach(row => {
                const username = row.getAttribute('data-username');
                const role = row.getAttribute('data-role');

                const matchRole = (activeUserRole === 'all') || (role === activeUserRole);
                const matchSearch = !query || username.includes(query);

                if (matchRole && matchSearch) {
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

        roleBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                roleBtns.forEach(b => {
                    b.className = 'user-role-btn h-10 px-3.5 rounded-lg bg-[#1F1F1F] text-[#A1A1AA] border border-[#27272A] text-xs font-semibold hover:text-white hover:bg-[#27272A] transition-colors';
                });
                btn.className = 'user-role-btn h-10 px-3.5 rounded-lg bg-[#16A34A] text-white text-xs font-semibold transition-colors';
                activeUserRole = btn.getAttribute('data-role');
                filterUsers();
            });
        });

        if (userSearchInput) {
            userSearchInput.addEventListener('input', filterUsers);
        }

        function handleResetPassword(userId, username) {
            if (confirm(`Konfirmasi reset password untuk user "${username}" (${userId})?\n\nKata sandi akan di-reset ke default "socstreet123" dan akun ditandai wajib mengganti password saat login berikutnya.`)) {
                const row = document.getElementById('user-row-' + userId);
                if (row) {
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
                showAdminToast('Password Direset', `Password akun ${username} direset ke default (socstreet123).`, true);
            }
        }

        function handleDeleteUser(userId, username) {
            if (confirm(`PERINGATAN: Apakah Anda yakin ingin menghapus akun "${username}" (${userId})?\n\nData historis booking akan tetap tersimpan demi integritas riwayat transaksi.`)) {
                const row = document.getElementById('user-row-' + userId);
                if (row) {
                    row.remove();
                    filterUsers();
                    showAdminToast('Akun Dihapus', `User ${username} telah dinonaktifkan dari sistem.`, false);
                }
            }
        }

        function handleAddUserSubmit() {
            const usernameInput = document.getElementById('newUsername');
            const roleSelect = document.getElementById('newRole');
            const username = usernameInput.value.trim();
            const role = roleSelect.value;

            if (!username) return;

            document.getElementById('addUserModal').classList.add('hidden');

            const tbody = document.getElementById('usersTableBody');
            const newId = 'USR-00' + (document.querySelectorAll('.user-row').length + 1);

            const newRow = document.createElement('tr');
            newRow.className = 'user-row hover:bg-[#202020] transition-colors';
            newRow.id = 'user-row-' + newId;
            newRow.setAttribute('data-id', newId);
            newRow.setAttribute('data-username', username.toLowerCase());
            newRow.setAttribute('data-role', role.toLowerCase());

            newRow.innerHTML = `
                <td class="px-6 py-4 font-mono font-medium text-[#D4D4D8]">${newId}</td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-[#27272A] text-white flex items-center justify-center font-bold text-xs uppercase border border-[#3F3F46] shrink-0">
                            ${username.substring(0, 2).toUpperCase()}
                        </div>
                        <div class="flex flex-col">
                            <span class="text-white font-semibold text-sm">${username}</span>
                            <span class="text-[11px] text-[#71717A]">Terdaftar: Hari ini</span>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold ${role === 'Admin' ? 'bg-[#166534]/30 text-[#4ADE80] border border-[#16A34A]/40' : 'bg-[#27272A] text-[#E4E4E7] border border-[#3F3F46]'}">
                        ${role}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <span class="text-[#E4E4E7] font-semibold font-mono">0 Sesi</span>
                </td>
                <td class="px-6 py-4 password-status-cell">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-[#F59E0B]"></span>
                        <span class="text-[#FBBF24] font-medium">Default (Wajib Ganti)</span>
                    </div>
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-1.5">
                        <button type="button" 
                                onclick="handleResetPassword('${newId}', '${username}')" 
                                class="p-2 rounded-lg text-[#FBBF24] hover:bg-[#F59E0B]/10 transition-colors" 
                                title="Reset Password">
                            <span class="material-symbols-outlined text-[19px]">key</span>
                        </button>
                        <button type="button" 
                                onclick="handleDeleteUser('${newId}', '${username}')" 
                                class="p-2 rounded-lg text-[#EF4444] hover:bg-[#EF4444]/10 transition-colors" 
                                title="Hapus Akun">
                            <span class="material-symbols-outlined text-[19px]">delete</span>
                        </button>
                    </div>
                </td>
            `;

            tbody.prepend(newRow);
            usernameInput.value = '';
            showAdminToast('User Berhasil Dibuat', `Akun ${username} dibuat dengan default password (socstreet123).`, true);
            filterUsers();
        }
    </script>
    @endpush
</x-layouts.admin>
