<x-layouts.dashboard title="Jadwal Lapangan - Socstreet Main Arena">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between pb-6 mb-6 border-b border-[#27272A] gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-2 h-2 rounded-full bg-[#16A34A] animate-pulse"></span>
                <span class="text-xs font-semibold text-[#16A34A] uppercase tracking-wider">Single Arena Booking</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">Socstreet Main Arena</h1>
            <p class="text-xs sm:text-sm text-[#A1A1AA] mt-1">Pilih tanggal dan sesi bermain (minimal 2 jam per booking, penambahan kelipatan 2 jam).</p>
        </div>

        <!-- Legend Pills -->
        <div class="flex items-center flex-wrap gap-2 text-xs">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#181818] border border-[#27272A] text-[#A1A1AA]">
                <span class="w-2 h-2 rounded-full bg-[#16A34A]"></span>
                Tersedia
            </span>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#181818] border border-[#27272A] text-[#71717A]">
                <span class="w-2 h-2 rounded-full bg-[#3F3F46]"></span>
                Terisi / Booked
            </span>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#16A34A]/20 border border-[#16A34A] text-white">
                <span class="w-2 h-2 rounded-full bg-[#22C55E]"></span>
                Pilihan Anda
            </span>
        </div>
    </div>

    <!-- Rule Notification Banner (Min 2 Hours per Booking) -->
    <div class="mb-6 p-4 rounded-xl bg-[#16A34A]/10 border border-[#16A34A]/30 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-[#16A34A]/20 border border-[#16A34A]/40 flex items-center justify-center text-[#22C55E] shrink-0">
                <x-icon name="info" class="w-4 h-4" />
            </div>
            <div>
                <p class="text-xs sm:text-sm font-bold text-white">Aturan Durasi Booking Lapangan</p>
                <p class="text-[11px] sm:text-xs text-[#A1A1AA] mt-0.5">
                    Reservasi tidak melayani durasi 1 jam. Minimum booking adalah <strong class="text-white">2 Jam (1 Sesi)</strong>. Jika ingin menambah waktu bertanding, wajib menambah <strong class="text-[#22C55E]">2 Jam lagi</strong> (kelipatan 2 jam).
                </p>
            </div>
        </div>
        <span class="px-2.5 py-1 rounded-md bg-[#181818] border border-[#27272A] text-[11px] font-mono text-[#22C55E] font-semibold shrink-0">
            Sesi: 2 Jam / Blok
        </span>
    </div>

    <!-- Quick Date Selector Strip (7 Days) -->
    <div class="mb-8">
        <label class="block text-xs font-bold uppercase tracking-wider text-[#A1A1AA] mb-3">Pilih Tanggal Pertandingan</label>
        <div class="flex items-center gap-3 overflow-x-auto pb-2 scrollbar-thin">
            @foreach($availableDates as $d)
                <a 
                    href="{{ route('booking.schedule', ['date' => $d['full_date']]) }}" 
                    class="shrink-0 flex flex-col items-center justify-center min-w-[105px] py-3 px-4 rounded-xl border transition-all text-center {{ $d['is_selected'] ? 'bg-[#16A34A] border-[#16A34A] text-white shadow-lg shadow-[#16A34A]/25 ring-2 ring-[#16A34A]/30' : 'bg-[#181818] border-[#27272A] text-[#A1A1AA] hover:border-[#3F3F46] hover:bg-[#242424]' }}"
                >
                    <span class="text-[11px] font-semibold uppercase tracking-wider {{ $d['is_selected'] ? 'text-white/90' : 'text-[#71717A]' }}">
                        {{ $d['day_name'] }}
                    </span>
                    <span class="text-sm font-bold mt-0.5 {{ $d['is_selected'] ? 'text-white' : 'text-white' }}">
                        {{ $d['date_short'] }}
                    </span>
                </a>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Time Slots Grid (Left / 8 cols) -->
        <div class="lg:col-span-8 space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-white tracking-tight flex items-center gap-2">
                        <x-icon name="clock" class="w-4 h-4 text-[#16A34A]" />
                        <span>Sesi Lapangan 2 Jam (08:00 - 22:00)</span>
                    </h2>
                    <p class="text-xs text-[#71717A] mt-0.5">Klik slot untuk memilih. Klik slot lain untuk menambah durasi (+2 jam per sesi).</p>
                </div>
                <span class="text-xs text-[#71717A]">{{ count($allSlots) }} Sesi Harian</span>
            </div>

            <!-- Slots Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5" id="slots-container">
                @foreach($allSlots as $index => $slot)
                    <button
                        type="button"
                        data-slot="{{ $slot['time'] }}"
                        data-duration="{{ $slot['duration'] ?? 2 }}"
                        data-price="{{ $slot['price'] }}"
                        data-booked="{{ $slot['is_booked'] ? 'true' : 'false' }}"
                        @if($slot['is_booked']) disabled @endif
                        onclick="toggleSlot(this)"
                        class="slot-card relative text-left p-4 rounded-xl border transition-all flex items-center justify-between {{ $slot['is_booked'] ? 'bg-[#141414] border-[#1F1F1F] opacity-45 cursor-not-allowed select-none' : 'bg-[#1E1E1E] border-[#27272A] hover:border-[#16A34A]/60 hover:bg-[#242424] cursor-pointer' }}"
                    >
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-bold {{ $slot['is_booked'] ? 'line-through text-[#71717A]' : 'text-white' }}">
                                    {{ $slot['time'] }}
                                </span>
                                <span class="text-[10px] px-1.5 py-0.5 rounded font-mono {{ $slot['is_booked'] ? 'bg-[#27272A] text-[#71717A]' : 'bg-[#27272A] text-[#A1A1AA]' }}">
                                    {{ $slot['category'] }}
                                </span>
                            </div>
                            <div class="flex items-center gap-2 mt-1.5">
                                <span class="text-[11px] px-2 py-0.5 rounded bg-[#181818] border border-[#27272A] text-[#A1A1AA] font-semibold">
                                    2 Jam (120 Menit)
                                </span>
                                <p class="text-xs font-semibold {{ $slot['is_booked'] ? 'text-[#71717A]' : 'text-[#22C55E]' }}">
                                    Rp {{ number_format($slot['price'], 0, ',', '.') }}
                                </p>
                            </div>
                        </div>

                        <div>
                            @if($slot['is_booked'])
                                <x-badge variant="neutral" size="xs">Terisi</x-badge>
                            @else
                                <div class="slot-checkbox w-6 h-6 rounded-lg border border-[#27272A] bg-[#181818] flex items-center justify-center text-transparent transition-all">
                                    <x-icon name="check" class="w-3.5 h-3.5" />
                                </div>
                            @endif
                        </div>
                    </button>
                @endforeach
            </div>

            <!-- Helpful notice for adding hours -->
            <div class="p-3.5 rounded-xl bg-[#181818] border border-[#27272A] flex items-center gap-2 text-xs text-[#A1A1AA]">
                <x-icon name="info" class="w-4 h-4 text-[#16A34A] shrink-0" />
                <span>
                    Ingin bertanding lebih lama? Anda dapat memilih lebih dari satu sesi 2 jam (misal 4 jam atau 6 jam). Penambahan durasi selalu dalam kelipatan 2 jam.
                </span>
            </div>
        </div>

        <!-- Sticky Booking Summary Drawer / Panel (Right / 4 cols) -->
        <div class="lg:col-span-4 lg:sticky lg:top-8 space-y-4">
            <div class="rounded-2xl bg-[#181818] border border-[#27272A] p-6 shadow-xl space-y-5">
                <div class="flex items-center justify-between pb-4 border-b border-[#27272A]">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-white">Ringkasan Jadwal</h3>
                    <span class="text-xs text-[#16A34A] font-semibold">Socstreet Main</span>
                </div>

                <!-- Arena Details Info -->
                <div class="space-y-3 text-xs">
                    <div class="flex items-center justify-between text-[#A1A1AA]">
                        <span>Lapangan:</span>
                        <span class="font-bold text-white">Socstreet Main Arena</span>
                    </div>
                    <div class="flex items-center justify-between text-[#A1A1AA]">
                        <span>Tanggal Main:</span>
                        <span class="font-bold text-white" id="summary-date">{{ Carbon\Carbon::parse($selectedDate)->isoFormat('dddd, D MMMM Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between text-[#A1A1AA] gap-2">
                        <span class="shrink-0">Sesi Terpilih:</span>
                        <span class="font-bold text-[#22C55E] text-right truncate max-w-[200px]" id="summary-slot">Belum Dipilih</span>
                    </div>
                    <div class="flex items-center justify-between text-[#A1A1AA]">
                        <span>Total Durasi:</span>
                        <span class="font-bold text-white" id="summary-duration">2 Jam (1 Sesi)</span>
                    </div>
                    <div class="flex items-center justify-between text-[#A1A1AA]">
                        <span>Aturan Durasi:</span>
                        <span class="font-semibold text-amber-400">Min. 2 Jam / Kelipatan 2 Jam</span>
                    </div>
                </div>

                <!-- Price Box -->
                <div class="p-4 rounded-xl bg-[#1E1E1E] border border-[#27272A] space-y-2">
                    <div class="flex items-center justify-between text-xs text-[#A1A1AA]">
                        <span>Tarif Sewa:</span>
                        <span class="font-semibold text-white" id="summary-price">Rp 0</span>
                    </div>
                    <div class="flex items-center justify-between text-xs text-[#A1A1AA]">
                        <span>Biaya Layanan:</span>
                        <span class="font-semibold text-[#22C55E]">GRATIS</span>
                    </div>
                    <div class="pt-2 border-t border-[#27272A] flex items-center justify-between">
                        <span class="text-xs font-bold text-white">Total Tagihan:</span>
                        <span class="text-lg font-bold text-[#22C55E]" id="summary-total">Rp 0</span>
                    </div>
                </div>

                <!-- Notice -->
                <p class="text-[11px] text-[#71717A] leading-relaxed">
                    * Pembayaran dapat dipilih saat checkout: <strong>Down Payment (DP 50%)</strong> atau <strong>Lunas 100%</strong>.
                </p>

                <!-- Action Button -->
                <a
                    id="btn-checkout"
                    href="#"
                    class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-[#27272A] text-[#71717A] font-bold text-sm pointer-events-none transition-all"
                >
                    <span id="btn-checkout-label">Lanjut ke Pembayaran</span>
                    <x-icon name="arrow-right" class="w-4 h-4" />
                </a>
            </div>
        </div>
    </div>

    <!-- Client-side 2-Hour Slot Selection Script -->
    <script>
        const selectedDateParam = '{{ $selectedDate }}';
        let selectedSlots = []; // Array of { slot: string, price: number, duration: number, element: HTMLElement }

        function toggleSlot(button) {
            if (button.getAttribute('data-booked') === 'true') return;

            const slotTime = button.getAttribute('data-slot');
            const slotPrice = parseInt(button.getAttribute('data-price'), 10);
            const slotDuration = parseInt(button.getAttribute('data-duration') || '2', 10);

            const existingIndex = selectedSlots.findIndex(s => s.slot === slotTime);

            if (existingIndex > -1) {
                // If it's already selected and it's the ONLY slot, keep it (must have at least 1 session / 2 hours)
                if (selectedSlots.length === 1) {
                    return;
                }
                // Deselect this slot
                selectedSlots.splice(existingIndex, 1);
                applyCardSelectionState(button, false);
            } else {
                // Select this slot (adds 2 hours)
                selectedSlots.push({
                    slot: slotTime,
                    price: slotPrice,
                    duration: slotDuration,
                    element: button
                });
                applyCardSelectionState(button, true);
            }

            renderSummary();
        }

        function applyCardSelectionState(card, isSelected) {
            const checkbox = card.querySelector('.slot-checkbox');
            if (isSelected) {
                card.classList.remove('bg-[#1E1E1E]', 'border-[#27272A]');
                card.classList.add('bg-[#16A34A]/10', 'border-[#16A34A]', 'ring-1', 'ring-[#16A34A]');
                if (checkbox) {
                    checkbox.classList.remove('bg-[#181818]', 'border-[#27272A]', 'text-transparent');
                    checkbox.classList.add('bg-[#16A34A]', 'border-[#16A34A]', 'text-white');
                }
            } else {
                card.classList.remove('bg-[#16A34A]/10', 'border-[#16A34A]', 'ring-1', 'ring-[#16A34A]');
                card.classList.add('bg-[#1E1E1E]', 'border-[#27272A]');
                if (checkbox) {
                    checkbox.classList.remove('bg-[#16A34A]', 'border-[#16A34A]', 'text-white');
                    checkbox.classList.add('bg-[#181818]', 'border-[#27272A]', 'text-transparent');
                }
            }
        }

        function renderSummary() {
            const btn = document.getElementById('btn-checkout');
            const summarySlot = document.getElementById('summary-slot');
            const summaryDuration = document.getElementById('summary-duration');
            const summaryPrice = document.getElementById('summary-price');
            const summaryTotal = document.getElementById('summary-total');
            const btnLabel = document.getElementById('btn-checkout-label');

            if (selectedSlots.length === 0) {
                summarySlot.textContent = 'Belum Dipilih';
                summaryDuration.textContent = '0 Jam';
                summaryPrice.textContent = 'Rp 0';
                summaryTotal.textContent = 'Rp 0';
                btnLabel.textContent = 'Pilih Sesi Terlebih Dahulu';

                btn.classList.remove('bg-[#16A34A]', 'hover:bg-[#15803D]', 'text-white', 'shadow-lg', 'shadow-[#16A34A]/25');
                btn.classList.add('bg-[#27272A]', 'text-[#71717A]', 'pointer-events-none');
                btn.href = '#';
                return;
            }

            // Calculate total price and total hours
            const totalHours = selectedSlots.reduce((acc, s) => acc + s.duration, 0);
            const totalPrice = selectedSlots.reduce((acc, s) => acc + s.price, 0);
            const slotNames = selectedSlots.map(s => s.slot).join(', ');
            const sessionCount = selectedSlots.length;

            summarySlot.textContent = slotNames;
            summarySlot.title = slotNames;
            summaryDuration.textContent = totalHours + ' Jam (' + sessionCount + ' Sesi @ 2 Jam)';

            const formattedPrice = 'Rp ' + totalPrice.toLocaleString('id-ID');
            summaryPrice.textContent = formattedPrice;
            summaryTotal.textContent = formattedPrice;

            btnLabel.textContent = 'Lanjut ke Pembayaran (' + totalHours + ' Jam)';
            btn.classList.remove('bg-[#27272A]', 'text-[#71717A]', 'pointer-events-none');
            btn.classList.add('bg-[#16A34A]', 'hover:bg-[#15803D]', 'text-white', 'shadow-lg', 'shadow-[#16A34A]/25');

            btn.href = '{{ route('booking.checkout') }}?date=' + encodeURIComponent(selectedDateParam) + 
                       '&slot=' + encodeURIComponent(slotNames) + 
                       '&duration=' + totalHours + 
                       '&price=' + totalPrice;
        }

        // Auto-select first available 2-hour slot on load
        document.addEventListener('DOMContentLoaded', () => {
            const firstAvailable = document.querySelector('.slot-card[data-booked="false"]');
            if (firstAvailable) {
                toggleSlot(firstAvailable);
            }
        });
    </script>
</x-layouts.dashboard>
