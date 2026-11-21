<x-layouts.admin title="Admin Dashboard" active="admin.dashboard">
    <div class="flex flex-col w-full gap-6">

        <!-- Top Welcome & Status Banner -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="w-2 h-2 rounded-full bg-[#16A34A] shadow-[0_0_8px_#16A34A]"></span>
                    <span class="text-xs font-semibold uppercase tracking-wider text-[#A1A1AA]">Overview Operasional</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Admin Dashboard</h1>
                <p class="text-sm text-[#A1A1AA] mt-0.5">Monitoring transaksi real-time dan ringkasan aktivitas Socstreet Main Arena.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.bookings') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-[#18181B] border border-[#27272A] hover:border-[#3F3F46] text-xs font-semibold text-[#E4E4E7] transition-all">
                    <span class="material-symbols-outlined text-[18px]">receipt_long</span>
                    <span>Kelola Booking</span>
                </a>
                <a href="{{ route('admin.schedule') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-[#16A34A] hover:bg-[#15803D] text-xs font-semibold text-white shadow-md shadow-[#16A34A]/20 transition-all">
                    <span class="material-symbols-outlined text-[18px]">edit_calendar</span>
                    <span>Atur Tarif & Jadwal</span>
                </a>
            </div>
        </div>

        <!-- Summary Metrics (3 Cards - PRD 7.B.1) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Card 1: Pendapatan Bulan Ini -->
            <div class="bg-[#1A1A1A] border border-[#27272A] rounded-xl p-6 flex flex-col justify-between shadow-sm hover:border-[#3F3F46] transition-colors duration-150 relative overflow-hidden">
                <div class="absolute -right-8 -top-8 w-24 h-24 bg-[#16A34A]/10 rounded-full blur-2xl pointer-events-none"></div>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-[#A1A1AA]">Pendapatan Bulan Ini</span>
                    <span class="w-9 h-9 rounded-lg bg-[#27272A] flex items-center justify-center text-[#22C55E]">
                        <span class="material-symbols-outlined text-[20px]">payments</span>
                    </span>
                </div>
                <div class="mt-4 flex flex-col gap-1">
                    <span class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">{{ $metrics['revenue'] ?? 'Rp 12.450.000' }}</span>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="bg-[#16A34A]/20 text-[#22C55E] text-xs font-semibold px-2 py-0.5 rounded-full inline-flex items-center gap-0.5 border border-[#16A34A]/30">
                            <span class="material-symbols-outlined text-[14px]">arrow_upward</span>
                            +15%
                        </span>
                        <span class="text-xs text-[#A1A1AA]">vs bulan lalu</span>
                    </div>
                </div>
            </div>

            <!-- Card 2: Perlu Konfirmasi -->
            <div class="bg-[#1A1A1A] border border-[#27272A] rounded-xl p-6 flex flex-col justify-between shadow-sm hover:border-[#3F3F46] transition-colors duration-150">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-[#A1A1AA]">Perlu Konfirmasi</span>
                    <span class="w-9 h-9 rounded-lg bg-[#27272A] flex items-center justify-center text-[#F59E0B]">
                        <span class="material-symbols-outlined text-[20px]">pending_actions</span>
                    </span>
                </div>
                <div class="mt-4 flex flex-col">
                    <span class="text-2xl sm:text-3xl font-extrabold text-[#F59E0B] tracking-tight" id="pendingCounter">{{ $metrics['pending_count'] ?? '3' }} Booking</span>
                    <span class="text-xs text-[#A1A1AA] mt-1">Menunggu verifikasi pembayaran</span>
                </div>
            </div>

            <!-- Card 3: Jadwal Main Hari Ini -->
            <div class="bg-[#1A1A1A] border border-[#27272A] rounded-xl p-6 flex flex-col justify-between shadow-sm hover:border-[#3F3F46] transition-colors duration-150">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-[#A1A1AA]">Jadwal Main Hari Ini</span>
                    <span class="w-9 h-9 rounded-lg bg-[#27272A] flex items-center justify-center text-[#16A34A]">
                        <span class="material-symbols-outlined text-[20px]">stadium</span>
                    </span>
                </div>
                <div class="mt-4 flex flex-col">
                    <span class="text-2xl sm:text-3xl font-extrabold text-[#16A34A] tracking-tight">{{ $metrics['today_sessions'] ?? '6' }} Sesi</span>
                    <span class="text-xs text-[#A1A1AA] mt-1">Socstreet Main Arena (FIFA Sintetis)</span>
                </div>
            </div>
        </div>

        <!-- Section: Transaksi Masuk Terbaru -->
        <div class="flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2.5">
                    <span class="w-2.5 h-2.5 rounded-sm bg-[#16A34A]"></span>
                    <h2 class="text-lg font-bold text-white tracking-tight">Transaksi Masuk Terbaru</h2>
                </div>
                <a href="{{ route('admin.bookings') }}" class="text-xs font-semibold text-[#16A34A] hover:text-[#22C55E] flex items-center gap-1 transition-colors duration-150 group">
                    <span>Lihat Semua Booking</span>
                    <span class="material-symbols-outlined text-[16px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </a>
            </div>

            <!-- Table Container -->
            <div class="bg-[#1A1A1A] border border-[#27272A] rounded-xl overflow-hidden shadow-xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#141414] border-b border-[#27272A] text-[#A1A1AA] text-[11px] font-semibold uppercase tracking-wider">
                                <th class="py-3.5 px-6">Booking ID</th>
                                <th class="py-3.5 px-6">Username</th>
                                <th class="py-3.5 px-6">Jadwal Main</th>
                                <th class="py-3.5 px-6">Tipe & Metode</th>
                                <th class="py-3.5 px-6">Total</th>
                                <th class="py-3.5 px-6">Status</th>
                                <th class="py-3.5 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#27272A] text-sm" id="recentBookingsTable">
                            @forelse($recentBookings ?? [] as $item)
                                <tr class="hover:bg-[#202022] transition-colors duration-150" id="row-{{ $item['booking_id'] }}">
                                    <td class="py-4 px-6 font-mono font-semibold text-white">{{ $item['booking_id'] }}</td>
                                    <td class="py-4 px-6 text-[#E4E4E7] font-medium">{{ $item['username'] }}</td>
                                    <td class="py-4 px-6 text-[#A1A1AA]">{{ $item['schedule'] }}</td>
                                    <td class="py-4 px-6 text-[#E4E4E7]">{{ $item['payment_type'] }} ({{ $item['payment_method'] }})</td>
                                    <td class="py-4 px-6 font-semibold text-white">{{ $item['total'] }}</td>
                                    <td class="py-4 px-6 status-cell">
                                        @if($item['status'] === 'pending')
                                            <span class="inline-flex items-center gap-1.5 bg-[#F59E0B]/20 text-[#F59E0B] border border-[#F59E0B]/30 px-2.5 py-1 rounded-full text-xs font-semibold">
                                                <span class="w-1.5 h-1.5 rounded-full bg-[#F59E0B]"></span>
                                                Pending
                                            </span>
                                        @elseif($item['status'] === 'confirmed')
                                            <span class="inline-flex items-center gap-1.5 bg-[#16A34A]/20 text-[#22C55E] border border-[#16A34A]/30 px-2.5 py-1 rounded-full text-xs font-semibold">
                                                <span class="w-1.5 h-1.5 rounded-full bg-[#22C55E]"></span>
                                                Confirmed
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 bg-[#EF4444]/20 text-[#EF4444] border border-[#EF4444]/30 px-2.5 py-1 rounded-full text-xs font-semibold">
                                                <span class="w-1.5 h-1.5 rounded-full bg-[#EF4444]"></span>
                                                Cancelled
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-right action-cell">
                                        @if($item['status'] === 'pending')
                                            <div class="flex items-center justify-end gap-2">
                                                <button type="button" 
                                                        onclick="handleQuickAction('{{ $item['booking_id'] }}', 'confirm')"
                                                        class="bg-[#16A34A] hover:bg-[#15803D] active:scale-95 text-white text-xs font-semibold px-3 py-1.5 rounded-lg shadow-sm transition-all duration-150 cursor-pointer">
                                                    Confirm
                                                </button>
                                                <button type="button" 
                                                        onclick="handleQuickAction('{{ $item['booking_id'] }}', 'reject')"
                                                        class="border border-[#EF4444] text-[#EF4444] hover:bg-[#EF4444]/10 active:scale-95 text-xs font-semibold px-3 py-1.5 rounded-lg transition-all duration-150 cursor-pointer">
                                                    Reject
                                                </button>
                                            </div>
                                        @elseif($item['status'] === 'confirmed')
                                            <span class="inline-flex items-center gap-1 text-xs text-[#22C55E] font-medium">
                                                <span class="material-symbols-outlined text-[16px]">check_circle</span> Terverifikasi
                                            </span>
                                        @else
                                            <span class="text-xs text-[#71717A]">&mdash; Dibatalkan</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-8 text-center text-[#71717A]">Belum ada transaksi terbaru</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
    <script>
        function handleQuickAction(bookingId, action) {
            const row = document.getElementById('row-' + bookingId);
            if (!row) return;

            const statusCell = row.querySelector('.status-cell');
            const actionCell = row.querySelector('.action-cell');

            if (action === 'confirm') {
                statusCell.innerHTML = `
                    <span class="inline-flex items-center gap-1.5 bg-[#16A34A]/20 text-[#22C55E] border border-[#16A34A]/30 px-2.5 py-1 rounded-full text-xs font-semibold">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#22C55E]"></span>
                        Confirmed
                    </span>
                `;
                actionCell.innerHTML = `
                    <span class="inline-flex items-center gap-1 text-xs text-[#22C55E] font-medium">
                        <span class="material-symbols-outlined text-[16px]">check_circle</span> Terverifikasi
                    </span>
                `;
                showAdminToast('Booking Disetujui', `Booking ${bookingId} telah dikonfirmasi dan slot dikunci.`);
            } else {
                statusCell.innerHTML = `
                    <span class="inline-flex items-center gap-1.5 bg-[#EF4444]/20 text-[#EF4444] border border-[#EF4444]/30 px-2.5 py-1 rounded-full text-xs font-semibold">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#EF4444]"></span>
                        Cancelled
                    </span>
                `;
                actionCell.innerHTML = `
                    <span class="text-xs text-[#71717A]">&mdash; Dibatalkan</span>
                `;
                showAdminToast('Booking Ditolak', `Reservasi ${bookingId} ditolak dan slot dibebaskan.`, false);
            }
        }
    </script>
    @endpush
</x-layouts.admin>
