<x-layouts.dashboard title="Checkout & Pembayaran - Socstreet Minisoccer">
    <!-- Header -->
    <div class="flex items-center gap-3 pb-6 mb-6 border-b border-[#27272A]">
        <a href="{{ route('booking.schedule') }}" class="p-2 rounded-xl bg-[#1E1E1E] border border-[#27272A] text-[#A1A1AA] hover:text-white hover:bg-[#242424] transition-colors">
            <x-icon name="arrow-left" class="w-4 h-4" />
        </a>
        <div>
            <div class="flex items-center gap-2 mb-0.5">
                <span class="w-2 h-2 rounded-full bg-[#16A34A]"></span>
                <span class="text-xs font-semibold text-[#16A34A] uppercase tracking-wider">Langkah Terakhir</span>
            </div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Checkout & Pembayaran</h1>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Main Form Column (Left / 8 cols) -->
        <div class="lg:col-span-8 space-y-6">

            <!-- Card 1: Detail Lapangan (Socstreet Main Arena) -->
            <div class="rounded-2xl bg-[#1E1E1E] border border-[#27272A] p-6 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-[#27272A]">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-[#16A34A]/15 border border-[#16A34A]/30 flex items-center justify-center text-[#22C55E]">
                            <x-icon name="soccer" class="w-4 h-4" />
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-white">Socstreet Main Arena</h2>
                            <p class="text-xs text-[#71717A]">FIFA Standard Monofilament Turf • 42x25m</p>
                        </div>
                    </div>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-md bg-[#181818] text-[#A1A1AA] border border-[#27272A]">
                        Single Arena
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                    <div class="p-3 rounded-xl bg-[#181818] border border-[#27272A]">
                        <p class="text-[#71717A] mb-1">Tanggal Pertandingan</p>
                        <p class="font-bold text-white text-sm" id="display-date">
                            {{ Carbon\Carbon::parse($bookDate)->isoFormat('dddd, D MMM Y') }}
                        </p>
                    </div>
                    <div class="p-3 rounded-xl bg-[#181818] border border-[#27272A]">
                        <p class="text-[#71717A] mb-1">Slot Jam Terpilih</p>
                        <p class="font-bold text-[#22C55E] text-sm" id="display-slot">
                            {{ $selectedSlot }}
                        </p>
                    </div>
                    <div class="p-3 rounded-xl bg-[#181818] border border-[#27272A]">
                        <p class="text-[#71717A] mb-1">Durasi Sewa</p>
                        <p class="font-bold text-white text-sm">1 Jam (60 Menit)</p>
                    </div>
                </div>
            </div>

            <!-- Card 2: Pilihan Tipe Pembayaran (PRD 4.4: DP 50% vs FULL 100%) -->
            <div class="rounded-2xl bg-[#181818] border border-[#27272A] p-6 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-[#27272A]">
                    <div>
                        <h2 class="text-base font-bold text-white">1. Pilih Tipe Pembayaran</h2>
                        <p class="text-xs text-[#A1A1AA] mt-0.5">Tentukan apakah ingin membayar uang muka (DP 50%) atau bayar lunas (100%).</p>
                    </div>
                    <span class="text-xs text-[#16A34A] font-semibold">Wajib Dipilih</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Option DP 50% -->
                    <label class="payment-type-option relative cursor-pointer p-4 rounded-xl border border-[#16A34A] bg-[#16A34A]/10 transition-all block select-none">
                        <input type="radio" name="payment_type" value="DP" checked onchange="updatePaymentType('DP')" class="sr-only" />
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-[#22C55E]">Down Payment (DP 50%)</span>
                            <span class="w-5 h-5 rounded-full bg-[#16A34A] text-white flex items-center justify-center text-xs">
                                <x-icon name="check" class="w-3 h-3" />
                            </span>
                        </div>
                        <p class="text-lg font-bold text-white" id="dp-amount-label">
                            Rp {{ number_format($basePrice * 0.5, 0, ',', '.') }}
                        </p>
                        <p class="text-xs text-[#A1A1AA] mt-1.5 leading-relaxed">
                            Kunci jadwal sekarang. Sisa pelunasan dibayar di kasir arena sebelum bertanding.
                        </p>
                    </label>

                    <!-- Option FULL 100% -->
                    <label class="payment-type-option relative cursor-pointer p-4 rounded-xl border border-[#27272A] bg-[#1E1E1E] hover:border-[#3F3F46] transition-all block select-none">
                        <input type="radio" name="payment_type" value="FULL" onchange="updatePaymentType('FULL')" class="sr-only" />
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold uppercase tracking-wider text-white">Lunas Penuh (100%)</span>
                            <span class="w-5 h-5 rounded-full border border-[#27272A] bg-[#181818] flex items-center justify-center text-transparent">
                                <x-icon name="check" class="w-3 h-3" />
                            </span>
                        </div>
                        <p class="text-lg font-bold text-white">
                            Rp {{ number_format($basePrice, 0, ',', '.') }}
                        </p>
                        <p class="text-xs text-[#A1A1AA] mt-1.5 leading-relaxed">
                            Bebas antrean kasir. Langsung datang ke lapangan dan langsung kickoff saat jam tiba.
                        </p>
                    </label>
                </div>
            </div>

            <!-- Card 3: Pilihan Metode Pembayaran (PRD 4.4: QRIS, BCA, Mandiri, BRI, DANA, GoPay) -->
            <div class="rounded-2xl bg-[#181818] border border-[#27272A] p-6 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-[#27272A]">
                    <div>
                        <h2 class="text-base font-bold text-white">2. Pilih Metode Pembayaran</h2>
                        <p class="text-xs text-[#A1A1AA] mt-0.5">Semua kanal pembayaran diverifikasi otomatis 24 jam nonstop.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach($paymentMethods as $index => $pm)
                        <label class="payment-method-card relative cursor-pointer p-4 rounded-xl border transition-all flex flex-col justify-between {{ $index === 0 ? 'border-[#16A34A] bg-[#16A34A]/10 ring-1 ring-[#16A34A]' : 'border-[#27272A] bg-[#1E1E1E] hover:border-[#3F3F46] hover:bg-[#242424]' }}">
                            <input 
                                type="radio" 
                                name="payment_method" 
                                value="{{ $pm['id'] }}" 
                                {{ $index === 0 ? 'checked' : '' }} 
                                onchange="selectPaymentMethod(this, '{{ $pm['name'] }}')" 
                                class="sr-only" 
                            />
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-xs font-bold text-white">{{ $pm['name'] }}</span>
                                <span class="text-[10px] font-semibold px-2 py-0.5 rounded bg-[#27272A] text-[#22C55E]">
                                    {{ $pm['badge'] }}
                                </span>
                            </div>
                            <p class="text-[11px] text-[#71717A] leading-normal">{{ $pm['desc'] }}</p>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Order Summary & Checkout Action Column (Right / 4 cols) -->
        <div class="lg:col-span-4 lg:sticky lg:top-8 space-y-4">
            <div class="rounded-2xl bg-[#181818] border border-[#27272A] p-6 shadow-2xl space-y-5">
                <div class="flex items-center justify-between pb-4 border-b border-[#27272A]">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-white">Rincian Tagihan</h3>
                    <span class="text-xs text-[#22C55E] font-mono">Invoice Preview</span>
                </div>

                <!-- Price Breakdown Details -->
                <div class="space-y-3 text-xs">
                    <div class="flex items-center justify-between text-[#A1A1AA]">
                        <span>Tarif Lapangan (1 Jam):</span>
                        <span class="font-semibold text-white">Rp {{ number_format($basePrice, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex items-center justify-between text-[#A1A1AA]">
                        <span>Tipe Pembayaran:</span>
                        <span class="font-bold text-[#22C55E]" id="summary-payment-type">Down Payment (50%)</span>
                    </div>

                    <div class="flex items-center justify-between text-[#A1A1AA]">
                        <span>Metode Bayar:</span>
                        <span class="font-semibold text-white" id="summary-payment-method">QRIS (Instant QR)</span>
                    </div>

                    <div class="flex items-center justify-between text-[#A1A1AA]">
                        <span>Biaya Transaksi:</span>
                        <span class="font-semibold text-[#22C55E]">Rp 0 (Promo)</span>
                    </div>

                    <!-- Remaining DP settlement row -->
                    <div class="flex items-center justify-between p-2.5 rounded-lg bg-[#1E1E1E] border border-[#27272A] text-[#A1A1AA]" id="remaining-row">
                        <span>Sisa Bayar di Lokasi:</span>
                        <span class="font-bold text-amber-400" id="summary-remaining">
                            Rp {{ number_format($basePrice * 0.5, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <!-- Grand Total Box -->
                <div class="pt-4 border-t border-[#27272A] flex items-baseline justify-between">
                    <div>
                        <p class="text-xs text-[#71717A] uppercase font-bold tracking-wider">Bayar Sekarang</p>
                        <p class="text-[11px] text-[#A1A1AA] mt-0.5">Termasuk PPN</p>
                    </div>
                    <span class="text-2xl font-bold text-[#22C55E]" id="summary-pay-now">
                        Rp {{ number_format($basePrice * 0.5, 0, ',', '.') }}
                    </span>
                </div>

                <!-- Security Assurance -->
                <div class="flex items-center gap-2 p-3 rounded-xl bg-[#1E1E1E] border border-[#27272A] text-[11px] text-[#71717A]">
                    <x-icon name="shield" class="w-4 h-4 text-[#16A34A] shrink-0" />
                    <span>Slot otomatis terkunci selama 15 menit untuk penyelesaian transaksi.</span>
                </div>

                <!-- Action Button Trigger Modal -->
                <x-button variant="primary" size="lg" type="button" onclick="toggleModal('payment-modal')" class="w-full shadow-xl shadow-[#16A34A]/30 font-bold">
                    <span>Konfirmasi & Bayar Sekarang</span>
                    <x-icon name="arrow-right" class="w-4 h-4" />
                </x-button>
            </div>
        </div>
    </div>

    <!-- Payment Instruction & Confirmation Modal -->
    <x-modal id="payment-modal" title="Instruksi Pembayaran" subtitle="Socstreet Main Arena - Kode Booking: INV-20261021-NEW" maxWidth="lg">
        <div class="space-y-5 text-center">
            <!-- Amount Pill -->
            <div class="p-4 rounded-xl bg-[#1E1E1E] border border-[#27272A]">
                <p class="text-xs text-[#71717A] uppercase font-semibold">Total Tagihan Yang Harus Dibayar</p>
                <p class="text-3xl font-extrabold text-[#22C55E] mt-1" id="modal-pay-amount">
                    Rp {{ number_format($basePrice * 0.5, 0, ',', '.') }}
                </p>
                <p class="text-xs text-[#A1A1AA] mt-1" id="modal-pay-type-label">Tipe: Down Payment (DP 50%)</p>
            </div>

            <!-- Simulated QRIS / Code Box -->
            <div class="p-6 rounded-2xl bg-white text-black inline-block mx-auto shadow-lg">
                <div class="w-48 h-48 bg-[#0F172A] rounded-xl flex flex-col items-center justify-center p-3 text-white">
                    <x-icon name="qr-code" class="w-28 h-28 text-white" />
                    <span class="text-[10px] font-mono tracking-widest mt-2 uppercase text-[#22C55E] font-bold">SOCSTREET QRIS CODE</span>
                </div>
                <p class="text-[11px] text-gray-600 font-semibold mt-3">NMID: ID1020260019284</p>
            </div>

            <div class="text-left text-xs text-[#A1A1AA] space-y-2 p-3.5 rounded-xl bg-[#1F1F1F] border border-[#27272A]">
                <div class="flex items-center justify-between text-white font-semibold">
                    <span>Metode:</span>
                    <span id="modal-method-label">QRIS Instant</span>
                </div>
                <div class="flex items-center justify-between">
                    <span>Batas Waktu:</span>
                    <span class="text-amber-400 font-mono font-bold">14 Menit 59 Detik</span>
                </div>
                <p class="text-[11px] text-[#71717A] pt-1">
                    Buka aplikasi m-Banking atau E-Wallet Anda (BCA, Mandiri, BRI, GoPay, DANA), lalu scan kode QR di atas untuk menyelesaikan transaksi.
                </p>
            </div>
        </div>

        <x-slot:footer>
            <x-button variant="secondary" size="md" type="button" onclick="toggleModal('payment-modal')">
                Tutup
            </x-button>
            <x-button variant="primary" size="md" href="{{ route('booking.my') }}" class="shadow-lg shadow-[#16A34A]/25">
                <x-icon name="check" class="w-4 h-4" />
                <span>Saya Sudah Membayar</span>
            </x-button>
        </x-slot:footer>
    </x-modal>

    <!-- Interactive Client Script -->
    <script>
        const basePrice = {{ $basePrice }};
        let currentType = 'DP';
        let currentMethodName = 'QRIS';

        function updatePaymentType(type) {
            currentType = type;
            const options = document.querySelectorAll('.payment-type-option');
            options.forEach(opt => {
                const input = opt.querySelector('input');
                const checkIcon = opt.querySelector('span:last-child');
                if (input.value === type) {
                    opt.classList.add('border-[#16A34A]', 'bg-[#16A34A]/10');
                    opt.classList.remove('border-[#27272A]', 'bg-[#1E1E1E]');
                    checkIcon.classList.add('bg-[#16A34A]', 'text-white');
                    checkIcon.classList.remove('border', 'border-[#27272A]', 'bg-[#181818]', 'text-transparent');
                } else {
                    opt.classList.remove('border-[#16A34A]', 'bg-[#16A34A]/10');
                    opt.classList.add('border-[#27272A]', 'bg-[#1E1E1E]');
                    checkIcon.classList.remove('bg-[#16A34A]', 'text-white');
                    checkIcon.classList.add('border', 'border-[#27272A]', 'bg-[#181818]', 'text-transparent');
                }
            });

            // Update calculation
            const payNow = type === 'DP' ? (basePrice * 0.5) : basePrice;
            const remaining = type === 'DP' ? (basePrice * 0.5) : 0;

            document.getElementById('summary-payment-type').textContent = type === 'DP' ? 'Down Payment (50%)' : 'Lunas Penuh (100%)';
            document.getElementById('summary-pay-now').textContent = 'Rp ' + payNow.toLocaleString('id-ID');
            document.getElementById('modal-pay-amount').textContent = 'Rp ' + payNow.toLocaleString('id-ID');
            document.getElementById('modal-pay-type-label').textContent = type === 'DP' ? 'Tipe: Down Payment (DP 50%)' : 'Tipe: Pembayaran Lunas (100%)';

            const remainingRow = document.getElementById('remaining-row');
            if (type === 'DP') {
                remainingRow.classList.remove('hidden');
                document.getElementById('summary-remaining').textContent = 'Rp ' + remaining.toLocaleString('id-ID');
            } else {
                remainingRow.classList.add('hidden');
            }
        }

        function selectPaymentMethod(radio, name) {
            currentMethodName = name;
            document.querySelectorAll('.payment-method-card').forEach(card => {
                card.classList.remove('border-[#16A34A]', 'bg-[#16A34A]/10', 'ring-1', 'ring-[#16A34A]');
                card.classList.add('border-[#27272A]', 'bg-[#1E1E1E]');
            });

            const parentCard = radio.closest('.payment-method-card');
            parentCard.classList.remove('border-[#27272A]', 'bg-[#1E1E1E]');
            parentCard.classList.add('border-[#16A34A]', 'bg-[#16A34A]/10', 'ring-1', 'ring-[#16A34A]');

            document.getElementById('summary-payment-method').textContent = name;
            document.getElementById('modal-method-label').textContent = name;
        }
    </script>
</x-layouts.dashboard>

