<x-layouts.dashboard title="Jadwal Lapangan - Socstreet Main Arena">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between pb-6 mb-6 border-b border-[#27272A] gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-2 h-2 rounded-full bg-[#16A34A] animate-pulse"></span>
                <span class="text-xs font-semibold text-[#16A34A] uppercase tracking-wider">Single Arena Booking</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">Socstreet Main Arena</h1>
            <p class="text-xs sm:text-sm text-[#A1A1AA] mt-1">Pilih tanggal dan slot waktu bertanding untuk tim Anda.</p>
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
                <h2 class="text-base font-bold text-white tracking-tight flex items-center gap-2">
                    <x-icon name="clock" class="w-4 h-4 text-[#16A34A]" />
                    <span>Pilihan Jam Main (08:00 - 23:00)</span>
                </h2>
                <span class="text-xs text-[#71717A]">{{ count($allSlots) }} Total Slot Harian</span>
            </div>

            <!-- Slots Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5" id="slots-container">
                @foreach($allSlots as $index => $slot)
                    <button
                        type="button"
                        data-slot="{{ $slot['time'] }}"
                        data-price="{{ $slot['price'] }}"
                        data-booked="{{ $slot['is_booked'] ? 'true' : 'false' }}"
                        @if($slot['is_booked']) disabled @endif
                        onclick="selectSlot(this)"
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
                            <p class="text-xs font-semibold mt-1.5 {{ $slot['is_booked'] ? 'text-[#71717A]' : 'text-[#22C55E]' }}">
                                Rp {{ number_format($slot['price'], 0, ',', '.') }} <span class="text-[10px] text-[#71717A] font-normal">/ jam</span>
                            </p>
                        </div>

                        <div>
                            @if($slot['is_booked'])
                                <x-badge variant="neutral" size="xs">Terisi</x-badge>
                            @else
                                <div class="slot-radio w-6 h-6 rounded-full border border-[#27272A] bg-[#181818] flex items-center justify-center text-transparent transition-all">
                                    <x-icon name="check" class="w-3.5 h-3.5" />
                                </div>
                            @endif
                        </div>
                    </button>
                @endforeach
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
                    <div class="flex items-center justify-between text-[#A1A1AA]">
                        <span>Jam Pilihan:</span>
                        <span class="font-bold text-[#22C55E]" id="summary-slot">Belum Dipilih</span>
                    </div>
                    <div class="flex items-center justify-between text-[#A1A1AA]">
                        <span>Durasi:</span>
                        <span class="font-bold text-white">1 Jam (60 Menit)</span>
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
                        <span class="text-xs font-bold text-white">Total Estimasi:</span>
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
                    <span>Lanjut ke Pembayaran</span>
                    <x-icon name="arrow-right" class="w-4 h-4" />
                </a>
            </div>
        </div>
    </div>

    <!-- Client-side Slot Selection Script -->
    <script>
        let currentSelectedSlot = null;
        let currentPrice = 0;
        const selectedDateParam = '{{ $selectedDate }}';

        function selectSlot(button) {
            if (button.getAttribute('data-booked') === 'true') return;

            // Reset previous selected slot
            document.querySelectorAll('.slot-card').forEach(card => {
                if (card.getAttribute('data-booked') !== 'true') {
                    card.classList.remove('bg-[#16A34A]/10', 'border-[#16A34A]', 'ring-1', 'ring-[#16A34A]');
                    card.classList.add('bg-[#1E1E1E]', 'border-[#27272A]');
                    const radio = card.querySelector('.slot-radio');
                    if (radio) {
                        radio.classList.remove('bg-[#16A34A]', 'border-[#16A34A]', 'text-white');
                        radio.classList.add('bg-[#181818]', 'border-[#27272A]', 'text-transparent');
                    }
                }
            });

            // Highlight current
            button.classList.remove('bg-[#1E1E1E]', 'border-[#27272A]');
            button.classList.add('bg-[#16A34A]/10', 'border-[#16A34A]', 'ring-1', 'ring-[#16A34A]');
            const radio = button.querySelector('.slot-radio');
            if (radio) {
                radio.classList.remove('bg-[#181818]', 'border-[#27272A]', 'text-transparent');
                radio.classList.add('bg-[#16A34A]', 'border-[#16A34A]', 'text-white');
            }

            currentSelectedSlot = button.getAttribute('data-slot');
            currentPrice = parseInt(button.getAttribute('data-price'), 10);

            // Update summary UI
            document.getElementById('summary-slot').textContent = currentSelectedSlot;
            const formattedPrice = 'Rp ' + currentPrice.toLocaleString('id-ID');
            document.getElementById('summary-price').textContent = formattedPrice;
            document.getElementById('summary-total').textContent = formattedPrice;

            // Enable checkout button
            const btn = document.getElementById('btn-checkout');
            btn.classList.remove('bg-[#27272A]', 'text-[#71717A]', 'pointer-events-none');
            btn.classList.add('bg-[#16A34A]', 'hover:bg-[#15803D]', 'text-white', 'shadow-lg', 'shadow-[#16A34A]/25');
            btn.href = '{{ route('booking.checkout') }}?date=' + encodeURIComponent(selectedDateParam) + '&slot=' + encodeURIComponent(currentSelectedSlot) + '&price=' + currentPrice;
        }

        // Auto-select first available slot for convenience
        document.addEventListener('DOMContentLoaded', () => {
            const firstAvailable = document.querySelector('.slot-card[data-booked="false"]');
            if (firstAvailable) {
                selectSlot(firstAvailable);
            }
        });
    </script>
</x-layouts.dashboard>

