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

                <!-- Password Status Filter Pills -->
                <div class="flex items-center gap-1.5">
                    <button type="button" class="user-status-btn h-10 px-3.5 rounded-lg bg-[#16A34A] text-white text-xs font-semibold transition-colors" data-status="all">Semua Member</button>
                    <button type="button" class="user-status-btn h-10 px-3.5 rounded-lg bg-[#1F1F1F] text-[#A1A1AA] border border-[#27272A] text-xs font-semibold hover:text-white hover:bg-[#27272A] transition-colors" data-status="active">Password Aktif</button>
                    <button type="button" class="user-status-btn h-10 px-3.5 rounded-lg bg-[#1F1F1F] text-[#A1A1AA] border border-[#27272A] text-xs font-semibold hover:text-white hover:bg-[#27272A] transition-colors" data-status="need_change">Wajib Ganti Password</button>
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
                                data-status="{{ ($u['must_change_password'] ?? false) ? 'need_change' : 'active' }}">
                                
                                <!-- User ID -->
                                <td class="px-6 py-4 font-mono font-medium text-[#D4D4D8]">{{ $u['user_id_string'] ?? 'USR-' . $u['id'] }}</td>

                                <!-- Username -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-[#27272A] text-white flex items-center justify-center font-bold text-xs uppercase border border-[#3F3F46] shrink-0">
                                            {{ substr($u['username'], 0, 2) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-white font-semibold text-sm">{{ $u['username'] }}</span>
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
                                                onclick="handleResetPassword({{ $u['id'] }}, '{{ $u['username'] }}')" 
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
                                                    onclick="handleDeleteUser({{ $u['id'] }}, '{{ $u['username'] }}')" 
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

                <!-- Role Selection -->
                <div class="grid grid-cols-2 gap-3">
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[11px] font-semibold text-[#A1A1AA] uppercase tracking-wider" for="newRole">Role</label>
                        <select id="newRole" class="h-10 px-3 bg-[#1F1F1F] text-white border border-[#27272A] rounded-lg text-xs focus:outline-none focus:border-[#16A34A]">
                            <option value="user">User (Customer)</option>
                            <option value="admin">Administrator</option>
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
                    <button id="addUserSubmitBtn"
                            type="submit" 
                            class="h-10 px-5 bg-[#16A34A] hover:bg-[#15803D] text-white text-xs font-semibold rounded-lg transition-colors cursor-pointer shadow-md flex items-center justify-center gap-1.5">
                        <span>Simpan Akun</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

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
                const status = row.getAttribute('data-status');

                const matchStatus = (activeUserStatus === 'all') || (status === activeUserStatus);
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

        async function handleAddUserSubmit(event) {
            event.preventDefault();
            const usernameInput = document.getElementById('newUsername');
            const roleSelect = document.getElementById('newRole');
            const submitBtn = document.getElementById('addUserSubmitBtn');
            const originalBtnHtml = submitBtn.innerHTML;

            const username = usernameInput.value.trim();
            const role = roleSelect ? roleSelect.value : 'user';

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

                document.getElementById('addUserModal').classList.add('hidden');
                usernameInput.value = '';

                const tbody = document.getElementById('usersTableBody');
                const newRow = document.createElement('tr');
                newRow.className = 'user-row hover:bg-[#202020] transition-colors';
                newRow.id = `user-row-${data.data.id}`;
                newRow.setAttribute('data-id', data.data.id);
                newRow.setAttribute('data-username', data.data.username.toLowerCase());
                newRow.setAttribute('data-status', 'need_change');

                newRow.innerHTML = `
                    <td class="px-6 py-4 font-mono font-medium text-[#D4D4D8]">${data.data.user_id_string}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-[#27272A] text-white flex items-center justify-center font-bold text-xs uppercase border border-[#3F3F46] shrink-0">
                                ${data.data.username.substring(0, 2).toUpperCase()}
                            </div>
                            <div class="flex flex-col">
                                <span class="text-white font-semibold text-sm">${data.data.username}</span>
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
                    <td class="px-6 py-4 password-status-cell">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-[#F59E0B]"></span>
                            <span class="text-[#FBBF24] font-medium">Default (Wajib Ganti)</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-1.5">
                            <button type="button" 
                                    onclick="handleResetPassword(${data.data.id}, '${data.data.username}')" 
                                    class="p-2 rounded-lg text-[#FBBF24] hover:bg-[#F59E0B]/10 transition-colors cursor-pointer" 
                                    title="Reset Password ke Default">
                                <span class="material-symbols-outlined text-[19px]">key</span>
                            </button>
                            <button type="button" 
                                    onclick="handleDeleteUser(${data.data.id}, '${data.data.username}')" 
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

        async function handleResetPassword(userId, username) {
            if (!confirm(`Konfirmasi reset password untuk user "${username}"?\n\nKata sandi akan disetel ke default "socstreet123" dan user wajib mengganti kata sandi saat login berikutnya.`)) {
                return;
            }

            try {
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const response = await fetch(`/admin/users/${userId}/reset-password`, {
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

                const row = document.getElementById('user-row-' + userId);
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

                showAdminToast('Password Direset', data.message || `Password akun ${username} direset ke default (socstreet123).`, true);
            } catch (err) {
                showAdminToast('Gagal Reset', err.message || 'Terjadi kesalahan sistem.', false);
            }
        }

        async function handleDeleteUser(userId, username) {
            if (!confirm(`PERINGATAN: Apakah Anda yakin ingin menghapus akun "${username}"?\n\nData akun akan dihapus dari sistem.`)) {
                return;
            }

            try {
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const response = await fetch(`/admin/users/${userId}`, {
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

                const row = document.getElementById('user-row-' + userId);
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

                filterUsers();
                showAdminToast('Akun Dihapus', data.message || `User ${username} telah dihapus dari sistem.`, false);
            } catch (err) {
                showAdminToast('Gagal Hapus', err.message || 'Terjadi kesalahan sistem.', false);
            }
        }
    </script>
    @endpush
</x-layouts.admin>
