<x-layouts.dashboard title="Booking Saya - Socstreet Minisoccer">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 mb-6 border-b border-[#27272A] gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-2 h-2 rounded-full bg-[#16A34A]"></span>
                <span class="text-xs font-semibold text-[#16A34A] uppercase tracking-wider">Histori Transaksi</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">Booking Saya</h1>
            <p class="text-xs sm:text-sm text-[#A1A1AA] mt-1">Daftar jadwal dan status pemesanan Anda di Socstreet Main Arena.</p>
        </div>

        <x-button variant="primary" size="md" href="{{ route('booking.schedule') }}" class="shadow-lg shadow-[#16A34A]/25 self-start sm:self-auto">
            <x-icon name="calendar" class="w-4 h-4" />
            <span>Pesan Jadwal Baru</span>
        </x-button>
    </div>

    <!-- Filter Tabs -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2 mb-6 scrollbar-thin">
        <a 
            href="{{ route('booking.my', ['status' => 'all']) }}" 
            class="px-4 py-2 rounded-xl text-xs font-semibold transition-all shrink-0 {{ $statusFilter === 'all' ? 'bg-[#16A34A] text-white shadow-md shadow-[#16A34A]/25' : 'bg-[#181818] border border-[#27272A] text-[#A1A1AA] hover:text-white hover:bg-[#242424]' }}"
        >
            Semua Transaksi
        </a>
        <a 
            href="{{ route('booking.my', ['status' => 'confirmed']) }}" 
            class="px-4 py-2 rounded-xl text-xs font-semibold transition-all shrink-0 {{ $statusFilter === 'confirmed' ? 'bg-[#16A34A] text-white shadow-md shadow-[#16A34A]/25' : 'bg-[#181818] border border-[#27272A] text-[#A1A1AA] hover:text-white hover:bg-[#242424]' }}"
        >
            Dikonfirmasi
        </a>
        <a 
            href="{{ route('booking.my', ['status' => 'pending_payment']) }}" 
            class="px-4 py-2 rounded-xl text-xs font-semibold transition-all shrink-0 {{ $statusFilter === 'pending_payment' ? 'bg-[#16A34A] text-white shadow-md shadow-[#16A34A]/25' : 'bg-[#181818] border border-[#27272A] text-[#A1A1AA] hover:text-white hover:bg-[#242424]' }}"
        >
            Menunggu Pembayaran
        </a>
        <a 
            href="{{ route('booking.my', ['status' => 'cancelled']) }}" 
            class="px-4 py-2 rounded-xl text-xs font-semibold transition-all shrink-0 {{ $statusFilter === 'cancelled' ? 'bg-[#16A34A] text-white shadow-md shadow-[#16A34A]/25' : 'bg-[#181818] border border-[#27272A] text-[#A1A1AA] hover:text-white hover:bg-[#242424]' }}"
        >
            Dibatalkan
        </a>
    </div>

    <!-- Clean Data Table Container (PRD Rule 4.5: Clean data table, NO action buttons on rows) -->
    <div class="rounded-2xl bg-[#181818] border border-[#27272A] overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-[#27272A] bg-[#141414] text-[11px] font-bold uppercase tracking-wider text-[#71717A]">
                        <th class="py-4 px-6">Booking ID</th>
                        <th class="py-4 px-6">Tanggal Main</th>
                        <th class="py-4 px-6">Jam Main</th>
                        <th class="py-4 px-6">Tipe & Metode</th>
                        <th class="py-4 px-6 text-right">Total Harga</th>
                        <th class="py-4 px-6 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#27272A] text-xs">
                    @forelse($bookings as $booking)
                        <tr class="hover:bg-[#1E1E1E]/80 transition-colors">
                            <!-- Booking ID -->
                            <td class="py-4 px-6 font-mono font-bold text-white">
                                <span class="text-[#22C55E]">#</span>{{ $booking['booking_id_string'] }}
                            </td>

                            <!-- Tanggal Main -->
                            <td class="py-4 px-6 text-white font-medium">
                                {{ $booking['book_date'] }}
                            </td>

                            <!-- Jam Main -->
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-[#1F1F1F] border border-[#27272A] text-white font-mono text-xs">
                                    <x-icon name="clock" class="w-3.5 h-3.5 text-[#16A34A]" />
                                    {{ $booking['start_time'] }} - {{ $booking['end_time'] }}
                                </span>
                            </td>

                            <!-- Tipe Pembayaran & Metode -->
                            <td class="py-4 px-6">
                                <p class="text-white font-semibold">{{ $booking['payment_type'] }}</p>
                                <p class="text-[11px] text-[#71717A]">{{ $booking['payment_method'] }}</p>
                            </td>

                            <!-- Total Harga -->
                            <td class="py-4 px-6 text-right font-bold text-white font-mono">
                                Rp {{ number_format($booking['total_price'], 0, ',', '.') }}
                            </td>

                            <!-- Status Badge (No row action buttons per PRD 4.5) -->
                            <td class="py-4 px-6 text-center">
                                @if($booking['status'] === 'confirmed')
                                    <x-badge variant="success" size="sm">
                                        <x-icon name="check" class="w-3 h-3" />
                                        <span>{{ $booking['status_label'] }}</span>
                                    </x-badge>
                                @elseif($booking['status'] === 'pending_payment')
                                    <x-badge variant="warning" size="sm">
                                        <x-icon name="clock" class="w-3 h-3" />
                                        <span>{{ $booking['status_label'] }}</span>
                                    </x-badge>
                                @else
                                    <x-badge variant="danger" size="sm">
                                        <x-icon name="x" class="w-3 h-3" />
                                        <span>{{ $booking['status_label'] }}</span>
                                    </x-badge>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-[#71717A]">
                                <x-icon name="info" class="w-8 h-8 mx-auto text-[#3F3F46] mb-3" />
                                <p class="text-sm font-semibold text-white">Tidak ada data booking</p>
                                <p class="text-xs text-[#71717A] mt-1">Belum ada transaksi pemesanan dengan filter ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Table Footer Metadata -->
        <div class="p-4 bg-[#141414] border-t border-[#27272A] flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-[#71717A]">
            <span>Menampilkan {{ count($bookings) }} transaksi &bull; Socstreet Main Arena</span>
            <span class="text-[11px]">Tunjukkan ID Booking pada resepsionis saat memasuki arena</span>
        </div>
    </div>
</x-layouts.dashboard>

