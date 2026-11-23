<x-layouts.admin title="Kelola Booking & Pembayaran" active="admin.bookings">
    <div class="flex flex-col w-full gap-6">

        <!-- Top Header & Search/Export -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex flex-col gap-1">
                <div class="flex items-center gap-2.5">
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Kelola Booking & Pembayaran</h1>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-[#18231C] text-[#4ADE80] text-[11px] font-semibold tracking-wider uppercase border border-[#166534]/50">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#22C55E] animate-pulse"></span>
                        Live Feed
                    </span>
                </div>
                <p class="text-sm text-[#A1A1AA]">Pantau dan konfirmasi status pembayaran reservasi lapangan secara real-time.</p>
            </div>

            <div class="flex items-center gap-2.5 self-start md:self-auto w-full md:w-auto">
                <div class="flex items-center bg-[#18181B] border border-[#27272A] rounded-lg px-3 py-1.5 focus-within:border-[#16A34A] transition-colors flex-1 md:flex-initial">
                    <span class="material-symbols-outlined text-[#71717A] text-[18px] mr-2">search</span>
                    <input type="text" 
                           id="tableSearchInput" 
                           placeholder="Cari ID Booking / User..." 
                           class="bg-transparent text-sm text-white placeholder-[#71717A] focus:outline-none w-full md:w-56 lg:w-64">
                </div>
                <a href="{{ route('admin.bookings.export') }}" 
                   class="flex items-center gap-1.5 bg-[#18181B] hover:bg-[#27272A] text-[#E4E4E7] border border-[#27272A] px-3.5 py-2 rounded-lg text-xs font-semibold transition-all shrink-0">
                    <span class="material-symbols-outlined text-[16px]">file_download</span>
                    <span>Ekspor CSV</span>
                </a>
            </div>
        </div>

        <!-- 4 Quick Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Card 1: Perlu Konfirmasi -->
            <div class="bg-[#18181B] border border-[#27272A] rounded-xl p-4 flex items-center justify-between shadow-sm">
                <div class="flex flex-col">
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-[#A1A1AA]">Perlu Konfirmasi</span>
                    <span class="text-2xl font-extrabold text-[#FBBF24] mt-1" id="statPendingCount">{{ $bookingStats['pending'] ?? 0 }}</span>
                    <span class="text-xs text-[#71717A] mt-0.5">Membutuhkan tindakan cepat</span>
                </div>
                <div class="w-10 h-10 rounded-lg bg-[#2E230D] border border-[#D97706]/30 flex items-center justify-center text-[#FBBF24]">
                    <span class="material-symbols-outlined text-[20px]">pending_actions</span>
                </div>
            </div>

            <!-- Card 2: Terkonfirmasi -->
            <div class="bg-[#18181B] border border-[#27272A] rounded-xl p-4 flex items-center justify-between shadow-sm">
                <div class="flex flex-col">
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-[#A1A1AA]">Terkonfirmasi</span>
                    <span class="text-2xl font-extrabold text-[#4ADE80] mt-1" id="statConfirmedCount">{{ $bookingStats['confirmed'] ?? 0 }}</span>
                    <span class="text-xs text-[#71717A] mt-0.5">Siap matchday</span>
                </div>
                <div class="w-10 h-10 rounded-lg bg-[#14291C] border border-[#16A34A]/30 flex items-center justify-center text-[#4ADE80]">
                    <span class="material-symbols-outlined text-[20px]">check_circle</span>
                </div>
            </div>

            <!-- Card 3: Total Booking Hari Ini -->
            <div class="bg-[#18181B] border border-[#27272A] rounded-xl p-4 flex items-center justify-between shadow-sm">
                <div class="flex flex-col">
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-[#A1A1AA]">Total Booking Hari Ini</span>
                    <span class="text-2xl font-extrabold text-white mt-1" id="statTodayCount">{{ $bookingStats['today_total'] ?? 0 }}</span>
                    <span class="text-xs text-[#71717A] mt-0.5">Sesi jadwal bermain hari ini</span>
                </div>
                <div class="w-10 h-10 rounded-lg bg-[#1F1F23] border border-[#3F3F46] flex items-center justify-center text-[#E4E4E7]">
                    <span class="material-symbols-outlined text-[20px]">sports_soccer</span>
                </div>
            </div>

            <!-- Card 4: Total Penerimaan -->
            <div class="bg-[#18181B] border border-[#27272A] rounded-xl p-4 flex items-center justify-between shadow-sm">
                <div class="flex flex-col">
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-[#A1A1AA]">Total Penerimaan</span>
                    <span class="text-2xl font-extrabold text-white mt-1" id="statTotalReceived">{{ $bookingStats['total_received'] ?? 'Rp 0' }}</span>
                    <span class="text-xs text-[#22C55E] mt-0.5 flex items-center gap-0.5">
                        <span class="material-symbols-outlined text-[14px]">payments</span> Akumulasi pembayaran
                    </span>
                </div>
                <div class="w-10 h-10 rounded-lg bg-[#1F1F23] border border-[#3F3F46] flex items-center justify-center text-[#E4E4E7]">
                    <span class="material-symbols-outlined text-[20px]">payments</span>
                </div>
            </div>
        </div>

        <!-- Filter & Control Toolbar -->
        <div class="bg-[#18181B] border border-[#27272A] rounded-xl p-4 flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-3">
                <!-- Status Filter Pills -->
                <div class="flex items-center gap-1 bg-[#121212] p-1 rounded-lg border border-[#27272A]">
                    <span class="text-[11px] font-semibold text-[#71717A] uppercase px-2 select-none">Status:</span>
                    <button type="button" class="status-btn px-3 py-1 rounded-md text-xs font-semibold bg-[#16A34A] text-white border border-[#22C55E]/50 transition-all shadow-sm" data-filter="all">Semua</button>
                    <button type="button" class="status-btn px-3 py-1 rounded-md text-xs font-semibold text-[#A1A1AA] hover:text-white hover:bg-[#27272A] border border-transparent transition-all" data-filter="pending">Pending</button>
                    <button type="button" class="status-btn px-3 py-1 rounded-md text-xs font-semibold text-[#A1A1AA] hover:text-white hover:bg-[#27272A] border border-transparent transition-all" data-filter="confirmed">Confirmed</button>
                    <button type="button" class="status-btn px-3 py-1 rounded-md text-xs font-semibold text-[#A1A1AA] hover:text-white hover:bg-[#27272A] border border-transparent transition-all" data-filter="cancelled">Cancelled</button>
                </div>

                <div class="hidden sm:block w-px h-6 bg-[#27272A]"></div>

                <!-- Payment Type Filter Pills -->
                <div class="flex items-center gap-1 bg-[#121212] p-1 rounded-lg border border-[#27272A]">
                    <span class="text-[11px] font-semibold text-[#71717A] uppercase px-2 select-none">Tipe:</span>
                    <button type="button" class="type-btn px-3 py-1 rounded-md text-xs font-semibold bg-[#16A34A] text-white border border-[#22C55E]/50 transition-all" data-type="all">Semua</button>
                    <button type="button" class="type-btn px-3 py-1 rounded-md text-xs font-semibold text-[#A1A1AA] hover:text-white hover:bg-[#27272A] border border-transparent transition-all" data-type="dp">DP 50%</button>
                    <button type="button" class="type-btn px-3 py-1 rounded-md text-xs font-semibold text-[#A1A1AA] hover:text-white hover:bg-[#27272A] border border-transparent transition-all" data-type="full">Lunas 100%</button>
                </div>
            </div>

            <!-- Sort Indicator -->
            <div class="flex items-center justify-end">
                <div class="flex items-center gap-2 bg-[#121212] border border-[#27272A] hover:border-[#3F3F46] text-[#E4E4E7] px-3.5 py-1.5 rounded-lg text-xs font-semibold select-none">
                    <span class="material-symbols-outlined text-[16px] text-[#A1A1AA]">sort</span>
                    <span>Urutan: Terbaru</span>
                </div>
            </div>
        </div>

        <!-- Bookings Master Data Table -->
        <div class="bg-[#18181B] border border-[#27272A] rounded-xl overflow-hidden shadow-2xl flex flex-col">
            <div class="overflow-x-auto w-full">
                <table class="w-full text-left border-collapse" id="bookingsTable">
                    <thead>
                        <tr class="bg-[#141414] border-b border-[#27272A] text-[#71717A] text-[11px] uppercase tracking-wider font-semibold">
                            <th class="py-3.5 px-4">Booking ID</th>
                            <th class="py-3.5 px-4">Username</th>
                            <th class="py-3.5 px-4">Jadwal Main (2 Jam)</th>
                            <th class="py-3.5 px-4">Tipe Bayar</th>
                            <th class="py-3.5 px-4">Metode</th>
                            <th class="py-3.5 px-4">Total Tagihan</th>
                            <th class="py-3.5 px-4">Sisa Bayar</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#27272A] text-xs font-medium" id="bookingsTableBody">
                        @forelse($bookings ?? [] as $b)
                            <tr class="booking-row hover:bg-[#202022] transition-colors"
                                id="booking-row-{{ $b['booking_id'] }}"
                                data-id="{{ $b['booking_id'] }}"
                                data-username="{{ strtolower($b['username']) }}"
                                data-status="{{ $b['status'] }}"
                                data-type="{{ $b['payment_type_raw'] }}">
                                
                                <!-- Booking ID -->
                                <td class="py-4 px-4 font-mono font-bold text-white">{{ $b['booking_id'] }}</td>

                                <!-- Username -->
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-[#27272A] border border-[#3F3F46] flex items-center justify-center text-[10px] text-white font-bold uppercase">
                                            {{ substr($b['username'], 0, 2) }}
                                        </div>
                                        <span class="text-[#E4E4E7] font-semibold">{{ $b['username'] }}</span>
                                    </div>
                                </td>

                                <!-- Schedule (Strict 2-Hour Slot) -->
                                <td class="py-4 px-4">
                                    <div class="flex flex-col">
                                        <span class="text-white font-semibold">{{ $b['date'] }}</span>
                                        <span class="text-[#71717A] text-[11px] font-mono">{{ $b['time_slot'] }}</span>
                                    </div>
                                </td>

                                <!-- Payment Type -->
                                <td class="py-4 px-4">
                                    @if($b['payment_type_raw'] === 'dp')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-[#27272A] text-[#FBBF24] border border-[#FBBF24]/30">
                                            DP 50%
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-[#27272A] text-[#4ADE80] border border-[#4ADE80]/30">
                                            Lunas 100%
                                        </span>
                                    @endif
                                </td>

                                <!-- Method -->
                                <td class="py-4 px-4">
                                    <span class="px-2 py-1 rounded bg-[#121212] border border-[#27272A] font-mono text-[11px] text-[#D4D4D8]">
                                        {{ $b['payment_method'] }}
                                    </span>
                                </td>

                                <!-- Total Tagihan -->
                                <td class="py-4 px-4 font-semibold text-white font-mono">{{ $b['total_price'] }}</td>

                                <!-- Sisa Bayar -->
                                <td class="py-4 px-4 font-mono remaining-cell" id="remaining-cell-{{ $b['booking_id'] }}">
                                    @if(($b['remaining_payment_raw'] ?? 0) > 0)
                                        <span class="text-[#FBBF24] font-semibold">{{ $b['remaining_payment'] }}</span>
                                    @else
                                        <span class="text-[#71717A]">&mdash; (Lunas)</span>
                                    @endif
                                </td>

                                <!-- Status Badge -->
                                <td class="py-4 px-4 status-cell">
                                    @if($b['status'] === 'pending')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-[#2E230D] border border-[#D97706]/40 text-[#FBBF24] text-[11px] font-semibold">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#F59E0B]"></span>
                                            Pending
                                        </span>
                                    @elseif($b['status'] === 'confirmed')
                                        @if(($b['remaining_payment_raw'] ?? 0) > 0)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-[#2E230D] border border-[#F59E0B]/40 text-[#FBBF24] text-[11px] font-semibold">
                                                <span class="w-1.5 h-1.5 rounded-full bg-[#F59E0B]"></span>
                                                Confirmed (DP 50%)
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-[#0D2818] border border-[#16A34A]/40 text-[#4ADE80] text-[11px] font-semibold">
                                                <span class="w-1.5 h-1.5 rounded-full bg-[#22C55E]"></span>
                                                Confirmed (Lunas)
                                            </span>
                                        @endif
                                    @else
                                        @if(!empty($b['is_dp_hangus']))
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-red-950/80 border border-red-500/60 text-red-300 text-[11px] font-semibold" title="{{ $b['cancellation_reason'] }}">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                                                Cancelled (DP Hangus)
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-[#2D1214] border border-[#EF4444]/40 text-[#F87171] text-[11px] font-semibold">
                                                <span class="w-1.5 h-1.5 rounded-full bg-[#EF4444]"></span>
                                                Cancelled
                                            </span>
                                        @endif
                                    @endif
                                </td>

                                <!-- Action Buttons -->
                                <td class="py-4 px-4 text-right action-cell">
                                    @if($b['status'] === 'pending')
                                        <div class="flex items-center justify-end gap-1.5">
                                            <button type="button" 
                                                    onclick="handleBookingAction('{{ $b['booking_id'] }}', 'confirm')"
                                                    class="bg-[#16A34A] hover:bg-[#15803D] active:scale-95 text-white text-xs font-semibold px-2.5 py-1.5 rounded-lg shadow-sm transition-all cursor-pointer">
                                                Confirm
                                            </button>
                                            <button type="button" 
                                                    onclick="handleBookingAction('{{ $b['booking_id'] }}', 'reject')"
                                                    class="border border-[#EF4444] text-[#EF4444] hover:bg-[#EF4444]/10 active:scale-95 text-xs font-semibold px-2.5 py-1.5 rounded-lg transition-all cursor-pointer">
                                                Reject
                                            </button>
                                        </div>
                                    @elseif($b['status'] === 'confirmed')
                                        @if(($b['remaining_payment_raw'] ?? 0) > 0)
                                            <div class="flex items-center justify-end gap-1.5">
                                                <button type="button"
                                                        onclick="handleBookingAction('{{ $b['booking_id'] }}', 'settle')"
                                                        class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-500 active:scale-95 text-white text-xs font-semibold px-2.5 py-1.5 rounded-lg shadow-sm transition-all cursor-pointer"
                                                        title="Pelunasan sisa tagihan DP di kasir arena">
                                                    <span class="material-symbols-outlined text-[15px]">point_of_sale</span>
                                                    <span>Lunasi di Kasir</span>
                                                </button>
                                            </div>
                                        @else
                                            <div class="inline-flex items-center gap-1.5 text-xs font-medium text-[#4ADE80] py-1.5">
                                                <span class="material-symbols-outlined text-[#22C55E] text-[16px]">check_circle</span>
                                                <span>Lunas 100%</span>
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-[#52525B] font-mono text-sm">&mdash;</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-8 text-center text-[#71717A]">Tidak ada data booking ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Table Footer -->
            <div class="px-6 py-4 bg-[#141414] border-t border-[#27272A] flex items-center justify-between text-xs text-[#A1A1AA]">
                <span>Menampilkan <strong class="text-white" id="visibleRowCount">{{ count($bookings ?? []) }}</strong> transaksi</span>
                <span class="text-[11px] text-[#71717A]">Sistem reservasi otomatis tersinkronisasi dengan grid jadwal member</span>
            </div>
        </div>

    </div>

    @push('scripts')
    <script>
        const statusBtns = document.querySelectorAll('.status-btn');
        const typeBtns = document.querySelectorAll('.type-btn');
        const searchInput = document.getElementById('tableSearchInput');
        const rows = document.querySelectorAll('.booking-row');
        const rowCountDisplay = document.getElementById('visibleRowCount');

        let activeStatus = 'all';
        let activeType = 'all';

        function applyFilters() {
            const query = searchInput.value.toLowerCase().trim();
            let visibleCount = 0;

            rows.forEach(row => {
                const rowStatus = row.getAttribute('data-status');
                const rowType = row.getAttribute('data-type');
                const rowId = row.getAttribute('data-id').toLowerCase();
                const rowUser = row.getAttribute('data-username');

                const matchesStatus = (activeStatus === 'all') || (rowStatus === activeStatus);
                const matchesType = (activeType === 'all') || (rowType === activeType);
                const matchesSearch = !query || rowId.includes(query) || rowUser.includes(query);

                if (matchesStatus && matchesType && matchesSearch) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            if (rowCountDisplay) {
                rowCountDisplay.innerText = visibleCount;
            }
        }

        statusBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                statusBtns.forEach(b => {
                    b.className = 'status-btn px-3 py-1 rounded-md text-xs font-semibold text-[#A1A1AA] hover:text-white hover:bg-[#27272A] border border-transparent transition-all';
                });
                btn.className = 'status-btn px-3 py-1 rounded-md text-xs font-semibold bg-[#16A34A] text-white border border-[#22C55E]/50 transition-all shadow-sm';
                activeStatus = btn.getAttribute('data-filter');
                applyFilters();
            });
        });

        typeBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                typeBtns.forEach(b => {
                    b.className = 'type-btn px-3 py-1 rounded-md text-xs font-semibold text-[#A1A1AA] hover:text-white hover:bg-[#27272A] border border-transparent transition-all';
                });
                btn.className = 'type-btn px-3 py-1 rounded-md text-xs font-semibold bg-[#16A34A] text-white border border-[#22C55E]/50 transition-all';
                activeType = btn.getAttribute('data-type');
                applyFilters();
            });
        });

        if (searchInput) {
            searchInput.addEventListener('input', applyFilters);
        }

        async function handleBookingAction(bookingId, action) {
            const targetRow = Array.from(rows).find(r => r.getAttribute('data-id') === bookingId);
            if (!targetRow) return;

            const statusCell = targetRow.querySelector('.status-cell');
            const actionCell = targetRow.querySelector('.action-cell');
            const originalActionHtml = actionCell.innerHTML;

            // Show loading spinner
            actionCell.innerHTML = `
                <span class="inline-flex items-center gap-1.5 text-xs text-[#A1A1AA]">
                    <span class="w-3 h-3 border-2 border-white/20 border-t-white rounded-full animate-spin"></span>
                    <span>Memproses...</span>
                </span>
            `;

            try {
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const response = await fetch(`/admin/bookings/${bookingId}/${action}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token || ''
                    }
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Gagal memproses aksi booking.');
                }

                const wasPending = targetRow.getAttribute('data-status') === 'pending';

                if (action === 'settle') {
                    targetRow.setAttribute('data-type', 'full');
                    const remCell = targetRow.querySelector('.remaining-cell') || document.getElementById(`remaining-cell-${bookingId}`);
                    if (remCell) {
                        remCell.innerHTML = `<span class="text-[#71717A]">&mdash; (Lunas)</span>`;
                    }
                    statusCell.innerHTML = `
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-[#0D2818] border border-[#16A34A]/40 text-[#4ADE80] text-[11px] font-semibold">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#22C55E]"></span>
                            Confirmed (Lunas)
                        </span>
                    `;
                    actionCell.innerHTML = `
                        <div class="inline-flex items-center gap-1.5 text-xs font-medium text-[#4ADE80] py-1.5">
                            <span class="material-symbols-outlined text-[#22C55E] text-[16px]">check_circle</span>
                            <span>Lunas 100%</span>
                        </div>
                    `;
                    showAdminToast('Pelunasan Kasir Berhasil', data.message || `Sisa tagihan untuk booking ${bookingId} telah dilunasi di kasir arena.`, true);
                } else if (action === 'confirm') {
                    targetRow.setAttribute('data-status', 'confirmed');
                    const isDp = targetRow.getAttribute('data-type') === 'dp';
                    statusCell.innerHTML = isDp ? `
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-[#2E230D] border border-[#F59E0B]/40 text-[#FBBF24] text-[11px] font-semibold">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#F59E0B]"></span>
                            Confirmed (DP 50%)
                        </span>
                    ` : `
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-[#0D2818] border border-[#16A34A]/40 text-[#4ADE80] text-[11px] font-semibold">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#22C55E]"></span>
                            Confirmed (Lunas)
                        </span>
                    `;
                    actionCell.innerHTML = isDp ? `
                        <div class="flex items-center justify-end gap-1.5">
                            <button type="button"
                                    onclick="handleBookingAction('${bookingId}', 'settle')"
                                    class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-500 active:scale-95 text-white text-xs font-semibold px-2.5 py-1.5 rounded-lg shadow-sm transition-all cursor-pointer"
                                    title="Pelunasan sisa tagihan DP di kasir arena">
                                <span class="material-symbols-outlined text-[15px]">point_of_sale</span>
                                <span>Lunasi di Kasir</span>
                            </button>
                        </div>
                    ` : `
                        <div class="inline-flex items-center gap-1.5 text-xs font-medium text-[#4ADE80] py-1.5">
                            <span class="material-symbols-outlined text-[#22C55E] text-[16px]">check_circle</span>
                            <span>Lunas 100%</span>
                        </div>
                    `;
                    showAdminToast('Booking Disetujui', data.message || `Booking ${bookingId} telah dikonfirmasi dan slot dikunci.`, true);

                    // Update stats counters
                    if (wasPending) {
                        const pendingEl = document.getElementById('statPendingCount');
                        if (pendingEl) {
                            const cur = parseInt(pendingEl.innerText) || 0;
                            if (cur > 0) pendingEl.innerText = cur - 1;
                        }
                        const confirmedEl = document.getElementById('statConfirmedCount');
                        if (confirmedEl) {
                            const cur = parseInt(confirmedEl.innerText) || 0;
                            confirmedEl.innerText = cur + 1;
                        }
                    }
                } else {
                    targetRow.setAttribute('data-status', 'cancelled');
                    statusCell.innerHTML = `
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-[#2D1214] border border-[#EF4444]/40 text-[#F87171] text-[11px] font-semibold">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#EF4444]"></span>
                            Cancelled
                        </span>
                    `;
                    actionCell.innerHTML = `<span class="text-[#52525B] font-mono text-sm">&mdash;</span>`;
                    showAdminToast('Booking Ditolak', data.message || `Reservasi ${bookingId} dibatalkan dan slot otomatis dibebaskan.`, false);

                    if (wasPending) {
                        const pendingEl = document.getElementById('statPendingCount');
                        if (pendingEl) {
                            const cur = parseInt(pendingEl.innerText) || 0;
                            if (cur > 0) pendingEl.innerText = cur - 1;
                        }
                    }
                }

                applyFilters();
            } catch (err) {
                actionCell.innerHTML = originalActionHtml;
                showAdminToast('Gagal', err.message || 'Terjadi kesalahan sistem.', false);
            }
        }
    </script>
    @endpush
</x-layouts.admin>
