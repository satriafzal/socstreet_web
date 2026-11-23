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

    @if($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-red-950/40 border border-red-800 text-red-300 text-xs flex items-center gap-3">
            <x-icon name="info" class="w-5 h-5 text-red-400 shrink-0" />
            <div>
                @foreach($errors->all() as $error)
                    <p class="font-medium">{{ $error }}</p>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Cancellation Notification Banner (Shown when user cancels payment or timer expires) -->
    <div id="checkout-cancel-alert" class="hidden mb-6 p-4 rounded-xl bg-amber-950/40 border border-amber-800/80 text-amber-200 text-xs flex items-center justify-between gap-3 shadow-lg transition-all">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-amber-500/20 border border-amber-500/40 flex items-center justify-center text-amber-400 shrink-0">
                <x-icon name="alert-triangle" class="w-4 h-4" />
            </div>
            <div>
                <p class="font-bold text-white text-sm" id="cancel-alert-title">Pembayaran Dibatalkan</p>
                <p class="text-amber-200/80 text-xs mt-0.5" id="cancel-alert-desc">Sesi pembayaran telah dibatalkan. Anda dapat memilih kembali metode pembayaran lain atau menyesuaikan jadwal.</p>
            </div>
        </div>
        <button type="button" onclick="dismissCancelAlert()" class="p-1.5 rounded-lg text-amber-400 hover:text-white hover:bg-amber-900/40 transition-colors">
            <x-icon name="x" class="w-4 h-4" />
        </button>
    </div>

    <!-- Hidden Submission Form -->
    <form id="booking-submit-form" method="POST" action="{{ route('booking.store') }}" class="hidden">
        @csrf
        <input type="hidden" name="book_date" value="{{ $bookDate }}">
        <input type="hidden" name="time_slot" value="{{ $selectedSlot }}">
        <input type="hidden" name="payment_type" id="hidden_payment_type" value="DP">
        <input type="hidden" name="payment_method" id="hidden_payment_method" value="QRIS">
    </form>

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
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-md bg-[#181818] text-[#22C55E] border border-[#27272A]">
                        Min. 2 Jam / Sesi
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
                        <p class="text-[#71717A] mb-1">Sesi Jam Terpilih</p>
                        <p class="font-bold text-[#22C55E] text-sm" id="display-slot">
                            {{ $selectedSlot }}
                        </p>
                    </div>
                    <div class="p-3 rounded-xl bg-[#181818] border border-[#27272A]">
                        <p class="text-[#71717A] mb-1">Total Durasi Sewa</p>
                        <p class="font-bold text-white text-sm">
                            {{ $duration ?? 2 }} Jam ({{ ($duration ?? 2) * 60 }} Menit)
                        </p>
                        <span class="text-[10px] text-[#A1A1AA]">
                            ({{ round(($duration ?? 2) / 2) }} Sesi @ 2 Jam)
                        </span>
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
                            Kunci sesi bermain sekarang. Sisa pelunasan (50%) dibayar di kasir arena sebelum kickoff.
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
                            Bebas antrean kasir. Langsung datang ke arena bersama tim dan kickoff tepat waktu.
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
                                onchange="selectPaymentMethod(this, '{{ $pm['name'] }}', '{{ $pm['id'] }}')" 
                                class="sr-only" 
                            />
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <div class="h-9 px-3 py-1 bg-white rounded-lg flex items-center justify-center shadow-sm border border-gray-100">
                                        <img src="{{ asset($pm['logo'] ?? 'images/payments/' . strtolower($pm['id']) . '.svg') }}" alt="{{ $pm['name'] }}" class="h-5 max-w-[80px] w-auto object-contain">
                                    </div>
                                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded bg-[#27272A] text-[#22C55E]">
                                        {{ $pm['badge'] }}
                                    </span>
                                </div>
                                <span class="text-xs font-bold text-white block">{{ $pm['name'] }}</span>
                            </div>
                            <p class="text-[11px] text-[#71717A] leading-normal mt-2">{{ $pm['desc'] }}</p>
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
                        <span>Tarif Arena ({{ $duration ?? 2 }} Jam):</span>
                        <span class="font-semibold text-white">Rp {{ number_format($basePrice, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex items-center justify-between text-[#A1A1AA]">
                        <span>Durasi Pertandingan:</span>
                        <span class="font-semibold text-white">{{ $duration ?? 2 }} Jam ({{ round(($duration ?? 2) / 2) }} Sesi)</span>
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
                        <span>Sisa Bayar di Kasir Arena:</span>
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
                    <span>Sesi otomatis terkunci selama 10 menit untuk penyelesaian transaksi.</span>
                </div>

                <!-- Action Button Trigger Modal -->
                <x-button variant="primary" size="lg" type="button" onclick="openPaymentModal()" class="w-full shadow-xl shadow-[#16A34A]/30 font-bold">
                    <span>Konfirmasi & Bayar Sekarang</span>
                    <x-icon name="arrow-right" class="w-4 h-4" />
                </x-button>
            </div>
        </div>
    </div>

    <!-- Payment Instruction & Confirmation Modal -->
    <x-modal id="payment-modal" title="Konfirmasi & Instruksi Pembayaran" subtitle="Socstreet Main Arena - Single Arena Pitch" maxWidth="lg">
        <div class="space-y-4 text-left">
            <!-- Expired / Cancelled Banner inside Modal -->
            <div id="modal-expired-alert" class="hidden p-3.5 rounded-xl bg-red-950/60 border border-red-800 text-red-200 text-xs flex items-center gap-3">
                <x-icon name="alert-triangle" class="w-5 h-5 text-red-400 shrink-0" />
                <div class="flex-1">
                    <p class="font-bold text-white text-xs">Waktu Pembayaran Habis (Kedaluwarsa)</p>
                    <p class="text-red-300 text-[11px] mt-0.5">Batas waktu pembayaran 10 menit telah terlewati. Sesi transaksi ini otomatis dibatalkan.</p>
                </div>
            </div>

            <!-- Amount & Countdown Bar -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-left">
                <!-- Amount Pill -->
                <div class="p-3.5 rounded-xl bg-[#1E1E1E] border border-[#27272A]">
                    <p class="text-[10px] text-[#71717A] uppercase font-bold tracking-wider">Total Tagihan Yang Harus Dibayar</p>
                    <p class="text-2xl font-extrabold text-[#22C55E] mt-0.5" id="modal-pay-amount">
                        Rp {{ number_format($basePrice * 0.5, 0, ',', '.') }}
                    </p>
                    <p class="text-[10px] text-[#A1A1AA] mt-0.5" id="modal-pay-type-label">Tipe: Down Payment (DP 50%)</p>
                </div>

                <!-- Timer Bar -->
                <div class="p-3.5 rounded-xl bg-[#1E1E1E] border border-[#27272A] flex flex-col justify-between transition-all" id="modal-timer-card">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] text-[#71717A] uppercase font-bold tracking-wider">Batas Waktu Bayar</span>
                        <span class="flex h-2 w-2 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75" id="timer-ping"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                        </span>
                    </div>
                    <div class="flex items-baseline gap-2 mt-1">
                        <span class="text-2xl font-mono font-black text-amber-400 tracking-wider" id="countdown-timer">10:00</span>
                        <span class="text-[11px] text-[#71717A]">menit tersisa</span>
                    </div>
                    <p class="text-[10px] text-[#71717A]">Otomatis batal jika waktu habis</p>
                </div>
            </div>

            <!-- Summary details pill (Tanggal & Jam) -->
            <div class="flex items-center justify-between text-xs p-2.5 rounded-xl bg-[#141414] border border-[#27272A] text-[#A1A1AA]">
                <div class="flex items-center gap-1.5">
                    <x-icon name="calendar" class="w-3.5 h-3.5 text-[#22C55E]" />
                    <span class="font-medium text-white">{{ Carbon\Carbon::parse($bookDate)->isoFormat('dddd, D MMM Y') }}</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <x-icon name="clock" class="w-3.5 h-3.5 text-[#22C55E]" />
                    <span class="font-bold text-white">{{ $selectedSlot }}</span>
                    <span class="text-[10px] text-[#71717A]">({{ $duration ?? 2 }} Jam)</span>
                </div>
            </div>

            <!-- ============================================== -->
            <!-- VIEW 1: QRIS ONLY (Hanya Muncul Saat QRIS Dipilih) -->
            <!-- ============================================== -->
            <div id="view-qris" class="payment-method-view text-center space-y-3">
                <div class="relative p-5 rounded-2xl bg-white text-black inline-block mx-auto shadow-2xl max-w-[280px] w-full">
                    <!-- Overlay when Expired -->
                    <div id="qris-expired-overlay" class="hidden absolute inset-0 bg-black/90 backdrop-blur-sm rounded-2xl flex flex-col items-center justify-center p-4 text-white z-10">
                        <div class="w-12 h-12 rounded-full bg-red-500/20 text-red-400 flex items-center justify-center mb-2">
                            <x-icon name="x" class="w-7 h-7" />
                        </div>
                        <p class="font-bold text-xs text-red-400 uppercase tracking-wider">Kode QR Kedaluwarsa</p>
                        <p class="text-[10px] text-[#A1A1AA] text-center mt-1">Batas waktu pembayaran 10 menit telah habis.</p>
                    </div>

                    <!-- Header QRIS with real logo -->
                    <div class="flex items-center justify-between mb-3 border-b border-gray-200 pb-2">
                        <div class="flex items-center gap-2">
                            <img src="{{ asset('images/payments/qris.svg') }}" alt="Logo QRIS" class="h-6 w-auto object-contain">
                            <span class="text-[9px] font-semibold text-gray-500 uppercase tracking-widest hidden sm:inline">Standar Pembayaran</span>
                        </div>
                        <span class="text-[9px] font-mono font-bold text-gray-500">NMID: ID1020260019284</span>
                    </div>

                    <!-- Real styled QR Box -->
                    <div class="w-48 h-48 mx-auto bg-[#0F172A] rounded-xl flex flex-col items-center justify-center p-3 text-white shadow-inner relative">
                        <x-icon name="qr-code" class="w-28 h-28 text-white" />
                        <span class="text-[10px] font-mono tracking-widest mt-2 uppercase text-[#22C55E] font-bold">SOCSTREET PAY</span>
                    </div>

                    <p class="text-[11px] text-gray-800 font-bold mt-3">SOCSTREET MINISOCCER ARENA</p>
                    <p class="text-[10px] text-gray-500">Verifikasi Otomatis &bull; Semua Bank & E-Wallet</p>
                </div>

                <div class="text-left text-xs text-[#A1A1AA] space-y-1.5 p-3.5 rounded-xl bg-[#1E1E1E] border border-[#27272A]">
                    <div class="flex items-center gap-2 text-white font-semibold text-xs">
                        <x-icon name="smartphone" class="w-4 h-4 text-[#22C55E]" />
                        <span>Cara Pembayaran QRIS:</span>
                    </div>
                    <p class="text-[11px] text-[#71717A] leading-relaxed">
                        1. Buka aplikasi m-Banking (BCA, Mandiri, BRI, BNI) atau E-Wallet (GoPay, DANA, OVO, ShopeePay).<br>
                        2. Pilih menu <strong>Scan / QRIS</strong> lalu arahkan kamera ke kode QR di atas.<br>
                        3. Periksa nominal tagihan, masukkan PIN Anda, lalu klik tombol <strong>"Saya Sudah Membayar"</strong>.
                    </p>
                </div>
            </div>

            <!-- ==================================================== -->
            <!-- VIEW 2: VIRTUAL ACCOUNT (BCA, MANDIRI LIVIN, BRI BRIVA) -->
            <!-- ==================================================== -->
            <div id="view-va" class="payment-method-view hidden space-y-3.5 text-left">
                <!-- Bank Identity Header -->
                <div class="flex items-center justify-between p-3.5 rounded-xl bg-[#141414] border border-[#27272A]">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-24 bg-white rounded-xl flex items-center justify-center p-1.5 shadow-sm border border-gray-100 shrink-0">
                            <img id="va-bank-logo-img" src="{{ asset('images/payments/bca.svg') }}" alt="Bank Logo" class="max-h-full max-w-full object-contain">
                        </div>
                        <div>
                            <h4 id="va-bank-title" class="text-sm font-bold text-white">BCA Virtual Account</h4>
                            <p id="va-bank-subtitle" class="text-[11px] text-[#71717A]">Verifikasi Otomatis Tanpa Konfirmasi Manual</p>
                        </div>
                    </div>
                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded bg-[#1E1E1E] text-[#22C55E] border border-[#27272A]">
                        Virtual Account
                    </span>
                </div>

                <!-- VA Number Display Card with Copy Button -->
                <div class="p-4 rounded-xl bg-[#1E1E1E] border border-[#27272A] space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-[#71717A] font-medium">Nomor Virtual Account</span>
                        <span class="text-[11px] text-[#A1A1AA]">Atas Nama: <strong class="text-white">SOCSTREET - {{ strtoupper(auth()->user()->username ?? 'MEMBER') }}</strong></span>
                    </div>

                    <div class="flex items-center justify-between gap-3 p-3 rounded-lg bg-[#141414] border border-[#27272A]">
                        <span id="va-number-text" class="text-lg sm:text-2xl font-mono font-bold tracking-wider text-[#22C55E] select-all">
                            8077 7081 2938 4712
                        </span>
                        <button 
                            type="button" 
                            onclick="copyVaNumber()" 
                            id="btn-copy-va" 
                            class="shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#27272A] hover:bg-[#3F3F46] text-white text-xs font-semibold transition-all cursor-pointer"
                        >
                            <x-icon name="copy" class="w-3.5 h-3.5" />
                            <span id="copy-va-label">Salin No. VA</span>
                        </button>
                    </div>

                    <p class="text-[11px] text-[#71717A] leading-relaxed">
                        Nomor Virtual Account ini khusus dialokasikan untuk pemesanan ini dan berlaku selama 10 menit.
                    </p>
                </div>

                <!-- Petunjuk Transfer Multi-Tab (m-Banking vs ATM) -->
                <div class="rounded-xl bg-[#1E1E1E] border border-[#27272A] overflow-hidden text-xs">
                    <div class="flex border-b border-[#27272A] bg-[#141414]">
                        <button type="button" onclick="switchVaTab('mbanking')" id="va-tab-btn-mbanking" class="flex-1 py-2.5 text-center font-bold text-[#22C55E] border-b-2 border-[#22C55E] transition-all">
                            Mobile Banking
                        </button>
                        <button type="button" onclick="switchVaTab('atm')" id="va-tab-btn-atm" class="flex-1 py-2.5 text-center font-semibold text-[#71717A] hover:text-white transition-all">
                            Mesin ATM
                        </button>
                    </div>

                    <div class="p-4">
                        <div id="va-guide-mbanking" class="space-y-1.5 text-[11px] text-[#A1A1AA] leading-relaxed">
                            <!-- Injected by JS based on bank -->
                        </div>
                        <div id="va-guide-atm" class="hidden space-y-1.5 text-[11px] text-[#A1A1AA] leading-relaxed">
                            <!-- Injected by JS based on bank -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============================================== -->
            <!-- VIEW 3: E-WALLET (DANA & GOPAY - OPSI 1 MODAL) -->
            <!-- ============================================== -->
            <div id="view-ewallet" class="payment-method-view hidden space-y-3.5 text-left">
                <!-- E-Wallet Branding Header -->
                <div class="flex items-center justify-between p-3.5 rounded-xl bg-[#141414] border border-[#27272A]">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-24 bg-white rounded-xl flex items-center justify-center p-1.5 shadow-sm border border-gray-100 shrink-0">
                            <img id="ewallet-logo-img" src="{{ asset('images/payments/dana.svg') }}" alt="E-Wallet Logo" class="max-h-full max-w-full object-contain">
                        </div>
                        <div>
                            <h4 id="ewallet-title" class="text-sm font-bold text-white">DANA Wallet</h4>
                            <p id="ewallet-subtitle" class="text-[11px] text-[#71717A]">Pembayaran Instan 1-Klik Akun Terhubung</p>
                        </div>
                    </div>
                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded bg-[#1E1E1E] text-[#22C55E] border border-[#27272A]">
                        E-Wallet Instan
                    </span>
                </div>

                <!-- Account Connection Snippet & Action -->
                <div class="p-4 rounded-xl bg-[#1E1E1E] border border-[#27272A] space-y-3">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-[#71717A]">Akun Pengguna:</span>
                        <span class="font-bold text-white">{{ auth()->user()->username ?? 'Member' }}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-[#71717A]">Nomor Terhubung:</span>
                        <span class="font-mono text-white">0812-****-8829</span>
                    </div>

                    <!-- Interactive App Redirect Simulation Box -->
                    <div class="pt-2">
                        <button 
                            type="button" 
                            id="btn-simulate-ewallet" 
                            onclick="simulateEwalletFlow()" 
                            class="w-full py-3 px-4 rounded-xl font-bold text-xs text-white flex items-center justify-center gap-2 shadow-lg transition-all bg-[#118EEA] hover:bg-[#0C7AC9] cursor-pointer"
                        >
                            <x-icon name="smartphone" class="w-4 h-4" />
                            <span id="btn-simulate-ewallet-text">Buka Aplikasi DANA Sekarang</span>
                        </button>
                    </div>

                    <!-- Simulation Result Alert Banner -->
                    <div id="ewallet-status-banner" class="hidden p-3 rounded-lg border text-xs">
                        <!-- Injected by JS when clicked -->
                    </div>
                </div>

                <div class="p-3 rounded-xl bg-[#141414] border border-[#27272A] text-[11px] text-[#71717A] leading-relaxed">
                    <p class="font-semibold text-white mb-1">Panduan Pembayaran E-Wallet:</p>
                    <p>1. Tekan tombol di atas untuk mensimulasikan otorisasi pembayaran ke aplikasi e-wallet Anda.</p>
                    <p>2. Konfirmasi pembayaran dan pastikan saldo Anda mencukupi.</p>
                    <p>3. Klik tombol <strong>"Saya Sudah Membayar"</strong> di bawah untuk menyelesaikan booking.</p>
                </div>
            </div>
        </div>

        <x-slot:footer>
            <!-- Cancel Button -->
            <button 
                type="button" 
                id="btn-cancel-modal" 
                onclick="cancelPayment('manual')" 
                class="px-4 py-2.5 rounded-xl text-xs font-bold text-red-400 hover:text-red-300 hover:bg-red-950/40 border border-red-900/60 transition-all flex items-center gap-1.5 cursor-pointer"
            >
                <x-icon name="x" class="w-3.5 h-3.5" />
                <span>Batalkan Pembayaran</span>
            </button>

            <!-- Reset Timer Button (Shown when expired) -->
            <button 
                type="button" 
                id="btn-reset-timer" 
                onclick="resetPaymentTimer()" 
                class="hidden px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-amber-600 hover:bg-amber-500 shadow-lg shadow-amber-600/30 transition-all flex items-center gap-1.5 cursor-pointer"
            >
                <x-icon name="refresh" class="w-3.5 h-3.5" />
                <span>Ulangi Pembayaran (Reset 10 Menit)</span>
            </button>

            <!-- Submit Confirmation Button -->
            <button 
                type="submit" 
                id="btn-confirm-submit" 
                form="booking-submit-form" 
                class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl font-bold text-sm bg-[#16A34A] hover:bg-[#15803D] text-white shadow-lg shadow-[#16A34A]/25 transition-all cursor-pointer"
            >
                <x-icon name="check" class="w-4 h-4" />
                <span>Saya Sudah Membayar</span>
            </button>
        </x-slot:footer>
    </x-modal>

    <!-- Interactive Client Script -->
    <script>
        const basePrice = {{ $basePrice }};
        let currentType = 'DP';
        let currentMethodId = 'QRIS';
        let currentMethodName = 'QRIS';

        // Timer State (10 Minutes = 600 Seconds)
        const INITIAL_TIMER_SECONDS = 600;
        let timerSeconds = INITIAL_TIMER_SECONDS;
        let timerInterval = null;
        let isExpired = false;

        // Bank VA configurations
        const BANK_CONFIG = {
            'BCA': {
                name: 'BCA Virtual Account',
                code: 'BCA',
                logoUrl: "{{ asset('images/payments/bca.svg') }}",
                bgColor: '#00529C',
                subtitle: 'm-BCA, KlikBCA & ATM BCA',
                vaFormatted: '8077 7081 2938 4712',
                vaRaw: '8077708129384712',
                mbankingGuide: `
                    1. Buka aplikasi <strong>BCA Mobile (m-BCA)</strong> > pilih menu <strong>m-Transfer</strong>.<br>
                    2. Pilih <strong>BCA Virtual Account</strong>, lalu tempel nomor VA di atas.<br>
                    3. Pastikan nominal tagihan dan nama merchant (SOCSTREET) sesuai, lalu masukkan PIN m-BCA.
                `,
                atmGuide: `
                    1. Masukkan kartu ATM BCA & PIN Anda.<br>
                    2. Pilih menu <strong>Transaksi Lainnya</strong> > <strong>Transfer</strong> > <strong>ke Rekening BCA Virtual Account</strong>.<br>
                    3. Masukkan nomor Virtual Account di atas lalu tekan <strong>Benar</strong>.<br>
                    4. Simpan struk ATM sebagai bukti pembayaran.
                `
            },
            'Mandiri': {
                name: "Mandiri Livin' (Virtual Account)",
                code: 'LIVIN',
                logoUrl: "{{ asset('images/payments/mandiri.svg') }}",
                bgColor: '#002D62',
                subtitle: "Livin' by Mandiri & ATM Mandiri",
                vaFormatted: '8870 8082 9384 7120',
                vaRaw: '8870808293847120',
                mbankingGuide: `
                    1. Buka aplikasi <strong>Livin' by Mandiri</strong> (ikon kuning) di smartphone Anda.<br>
                    2. Pilih menu <strong>Bayar</strong> > pilih opsi <strong>Virtual Account</strong>.<br>
                    3. Masukkan nomor Virtual Account Mandiri di atas.<br>
                    4. Konfirmasi nama akun dan jumlah tagihan, lalu masukkan PIN Livin' Anda.
                `,
                atmGuide: `
                    1. Masukkan kartu ATM Mandiri & PIN.<br>
                    2. Pilih menu <strong>Bayar/Beli</strong> > <strong>Pendidikan / Lainnya</strong> > <strong>Virtual Account</strong>.<br>
                    3. Masukkan nomor Virtual Account Mandiri di atas lalu setujui transaksi.
                `
            },
            'BRI': {
                name: 'BRI BRIVA (Virtual Account)',
                code: 'BRIVA',
                logoUrl: "{{ asset('images/payments/bri.svg') }}",
                bgColor: '#00529C',
                subtitle: 'BRImo (BRI Mobile) & ATM BRI',
                vaFormatted: '1280 0083 8471 9280',
                vaRaw: '1280008384719280',
                mbankingGuide: `
                    1. Buka aplikasi <strong>BRImo</strong> lalu login dengan akun Anda.<br>
                    2. Pilih menu <strong>Tagihan</strong> > pilih <strong>BRIVA</strong>.<br>
                    3. Masukkan nomor BRIVA di atas, periksa rincian tagihan SOCSTREET.<br>
                    4. Masukkan PIN BRImo Anda untuk memverifikasi transaksi.
                `,
                atmGuide: `
                    1. Masukkan kartu ATM BRI & PIN.<br>
                    2. Pilih menu <strong>Transaksi Lain</strong> > <strong>Pembayaran</strong> > <strong>Lainnya</strong> > <strong>BRIVA</strong>.<br>
                    3. Masukkan nomor BRIVA di atas lalu konfirmasi pembayaran.
                `
            }
        };

        // E-Wallet configurations
        const EWALLET_CONFIG = {
            'DANA': {
                name: 'DANA Wallet',
                code: 'DANA',
                logoUrl: "{{ asset('images/payments/dana.svg') }}",
                bgColor: '#118EEA',
                hoverColor: '#0C7AC9',
                title: 'DANA Wallet',
                subtitle: 'Pembayaran Instan 1-Klik Akun Terhubung',
                btnText: 'Buka Aplikasi DANA Sekarang'
            },
            'GoPay': {
                name: 'GoPay',
                code: 'GOPAY',
                logoUrl: "{{ asset('images/payments/gopay.svg') }}",
                bgColor: '#00AA13',
                hoverColor: '#008A10',
                title: 'GoPay (Gojek)',
                subtitle: 'Pembayaran Instan via Akun Gojek',
                btnText: 'Buka Aplikasi GoPay / Gojek Sekarang'
            }
        };

        function updatePaymentType(type) {
            currentType = type;
            const hiddenType = document.getElementById('hidden_payment_type');
            if (hiddenType) hiddenType.value = type;

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

        function selectPaymentMethod(radio, name, id) {
            currentMethodName = name;
            currentMethodId = id;
            const hiddenMethod = document.getElementById('hidden_payment_method');
            if (hiddenMethod) hiddenMethod.value = radio.value;

            document.querySelectorAll('.payment-method-card').forEach(card => {
                card.classList.remove('border-[#16A34A]', 'bg-[#16A34A]/10', 'ring-1', 'ring-[#16A34A]');
                card.classList.add('border-[#27272A]', 'bg-[#1E1E1E]');
            });

            const parentCard = radio.closest('.payment-method-card');
            parentCard.classList.remove('border-[#27272A]', 'bg-[#1E1E1E]');
            parentCard.classList.add('border-[#16A34A]', 'bg-[#16A34A]/10', 'ring-1', 'ring-[#16A34A]');

            document.getElementById('summary-payment-method').textContent = name;
        }

        function openPaymentModal() {
            // Dismiss any old cancellation alert
            dismissCancelAlert();

            // Set views according to selected method
            configureModalView(currentMethodId);

            // Reset and start countdown timer
            resetPaymentTimer();

            // Open the modal
            toggleModal('payment-modal');
        }

        function configureModalView(methodId) {
            const viewQris = document.getElementById('view-qris');
            const viewVa = document.getElementById('view-va');
            const viewEwallet = document.getElementById('view-ewallet');

            // Hide all views first
            viewQris.classList.add('hidden');
            viewVa.classList.add('hidden');
            viewEwallet.classList.add('hidden');

            if (methodId === 'QRIS') {
                viewQris.classList.remove('hidden');
            } else if (['BCA', 'Mandiri', 'BRI'].includes(methodId)) {
                viewVa.classList.remove('hidden');
                renderVaDetails(methodId);
            } else if (['DANA', 'GoPay'].includes(methodId)) {
                viewEwallet.classList.remove('hidden');
                renderEwalletDetails(methodId);
            }
        }

        function renderVaDetails(bankKey) {
            const conf = BANK_CONFIG[bankKey];
            if (!conf) return;

            const logoImg = document.getElementById('va-bank-logo-img');
            if (logoImg) {
                logoImg.src = conf.logoUrl;
                logoImg.alt = conf.name;
            }

            document.getElementById('va-bank-title').textContent = conf.name;
            document.getElementById('va-bank-subtitle').textContent = conf.subtitle;
            document.getElementById('va-number-text').textContent = conf.vaFormatted;
            document.getElementById('va-guide-mbanking').innerHTML = conf.mbankingGuide;
            document.getElementById('va-guide-atm').innerHTML = conf.atmGuide;

            // Reset copy button state
            resetCopyButton();

            // Reset tab to m-Banking
            switchVaTab('mbanking');
        }

        function switchVaTab(tab) {
            const btnMb = document.getElementById('va-tab-btn-mbanking');
            const btnAtm = document.getElementById('va-tab-btn-atm');
            const guideMb = document.getElementById('va-guide-mbanking');
            const guideAtm = document.getElementById('va-guide-atm');

            if (tab === 'mbanking') {
                btnMb.classList.add('font-bold', 'text-[#22C55E]', 'border-b-2', 'border-[#22C55E]');
                btnMb.classList.remove('text-[#71717A]', 'font-semibold');
                btnAtm.classList.remove('font-bold', 'text-[#22C55E]', 'border-b-2', 'border-[#22C55E]');
                btnAtm.classList.add('text-[#71717A]', 'font-semibold');
                guideMb.classList.remove('hidden');
                guideAtm.classList.add('hidden');
            } else {
                btnAtm.classList.add('font-bold', 'text-[#22C55E]', 'border-b-2', 'border-[#22C55E]');
                btnAtm.classList.remove('text-[#71717A]', 'font-semibold');
                btnMb.classList.remove('font-bold', 'text-[#22C55E]', 'border-b-2', 'border-[#22C55E]');
                btnMb.classList.add('text-[#71717A]', 'font-semibold');
                guideAtm.classList.remove('hidden');
                guideMb.classList.add('hidden');
            }
        }

        function copyVaNumber() {
            const conf = BANK_CONFIG[currentMethodId];
            const rawNumber = conf ? conf.vaRaw : document.getElementById('va-number-text').textContent.replace(/\s+/g, '');

            navigator.clipboard.writeText(rawNumber).then(() => {
                const copyBtn = document.getElementById('btn-copy-va');
                const label = document.getElementById('copy-va-label');
                copyBtn.classList.remove('bg-[#27272A]', 'hover:bg-[#3F3F46]', 'text-white');
                copyBtn.classList.add('bg-[#16A34A]', 'text-white');
                label.textContent = 'Tersalin! ✓';

                setTimeout(() => {
                    resetCopyButton();
                }, 2000);
            }).catch(() => {
                alert('Nomor VA: ' + rawNumber);
            });
        }

        function resetCopyButton() {
            const copyBtn = document.getElementById('btn-copy-va');
            const label = document.getElementById('copy-va-label');
            if (copyBtn && label) {
                copyBtn.classList.add('bg-[#27272A]', 'hover:bg-[#3F3F46]', 'text-white');
                copyBtn.classList.remove('bg-[#16A34A]');
                label.textContent = 'Salin No. VA';
            }
        }

        function renderEwalletDetails(walletKey) {
            const conf = EWALLET_CONFIG[walletKey];
            if (!conf) return;

            const logoImg = document.getElementById('ewallet-logo-img');
            if (logoImg) {
                logoImg.src = conf.logoUrl;
                logoImg.alt = conf.name;
            }

            document.getElementById('ewallet-title').textContent = conf.title;
            document.getElementById('ewallet-subtitle').textContent = conf.subtitle;

            const simBtn = document.getElementById('btn-simulate-ewallet');
            simBtn.style.backgroundColor = conf.bgColor;
            document.getElementById('btn-simulate-ewallet-text').textContent = conf.btnText;
            simBtn.disabled = false;

            // Hide simulation status banner
            const banner = document.getElementById('ewallet-status-banner');
            banner.classList.add('hidden');
            banner.innerHTML = '';
        }

        function simulateEwalletFlow() {
            const conf = EWALLET_CONFIG[currentMethodId] || { name: 'E-Wallet' };
            const simBtn = document.getElementById('btn-simulate-ewallet');
            const btnText = document.getElementById('btn-simulate-ewallet-text');
            const banner = document.getElementById('ewallet-status-banner');

            simBtn.disabled = true;
            btnText.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                Menghubungkan ke ${conf.name}...
            `;

            setTimeout(() => {
                btnText.textContent = `Terhubung ke ${conf.name} ✓`;
                banner.className = 'p-3 rounded-lg border text-xs bg-emerald-950/40 border-emerald-800 text-emerald-300';
                banner.innerHTML = `
                    <div class="flex items-start gap-2">
                        <span class="text-base leading-none">✓</span>
                        <div>
                            <strong class="font-bold text-white">Otorisasi Berhasil Diterima!</strong>
                            <p class="text-[11px] text-emerald-200 mt-0.5">Sesi pembayaran telah disetujui di aplikasi ${conf.name}. Silakan tekan tombol <strong>"Saya Sudah Membayar"</strong> di bawah untuk menyelesaikan booking.</p>
                        </div>
                    </div>
                `;
                banner.classList.remove('hidden');
            }, 1500);
        }

        // ==========================================
        // COUNTDOWN TIMER & AUTO-CANCEL LOGIC
        // ==========================================
        function startCountdown() {
            clearInterval(timerInterval);
            isExpired = false;

            updateTimerDisplay();

            timerInterval = setInterval(() => {
                timerSeconds--;
                updateTimerDisplay();

                if (timerSeconds <= 0) {
                    clearInterval(timerInterval);
                    handleTimeExpired();
                }
            }, 1000);
        }

        function updateTimerDisplay() {
            const display = document.getElementById('countdown-timer');
            const ping = document.getElementById('timer-ping');
            const minutes = Math.floor(timerSeconds / 60);
            const seconds = timerSeconds % 60;

            const formatted = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            if (display) display.textContent = formatted;

            // Turn text red when under 1 minute
            if (timerSeconds <= 60 && display) {
                display.classList.remove('text-amber-400');
                display.classList.add('text-red-500', 'animate-pulse');
                if (ping) ping.className = 'animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75';
            } else if (display) {
                display.classList.add('text-amber-400');
                display.classList.remove('text-red-500', 'animate-pulse');
                if (ping) ping.className = 'animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75';
            }
        }

        function handleTimeExpired() {
            isExpired = true;

            // Show expired alert in modal
            const expiredAlert = document.getElementById('modal-expired-alert');
            if (expiredAlert) expiredAlert.classList.remove('hidden');

            // Show QRIS expired overlay if current view is QRIS
            const qrisOverlay = document.getElementById('qris-expired-overlay');
            if (qrisOverlay) qrisOverlay.classList.remove('hidden');

            // Toggle footer buttons
            const submitBtn = document.getElementById('btn-confirm-submit');
            const cancelBtn = document.getElementById('btn-cancel-modal');
            const resetBtn = document.getElementById('btn-reset-timer');

            if (submitBtn) submitBtn.classList.add('hidden');
            if (cancelBtn) cancelBtn.classList.add('hidden');
            if (resetBtn) resetBtn.classList.remove('hidden');

            // Highlight timer card border in red
            const timerCard = document.getElementById('modal-timer-card');
            if (timerCard) {
                timerCard.classList.remove('border-[#27272A]');
                timerCard.classList.add('border-red-800', 'bg-red-950/20');
            }
        }

        function resetPaymentTimer() {
            clearInterval(timerInterval);
            timerSeconds = INITIAL_TIMER_SECONDS;
            isExpired = false;

            // Hide expired alert in modal
            const expiredAlert = document.getElementById('modal-expired-alert');
            if (expiredAlert) expiredAlert.classList.add('hidden');

            // Hide QRIS expired overlay
            const qrisOverlay = document.getElementById('qris-expired-overlay');
            if (qrisOverlay) qrisOverlay.classList.add('hidden');

            // Restore footer buttons
            const submitBtn = document.getElementById('btn-confirm-submit');
            const cancelBtn = document.getElementById('btn-cancel-modal');
            const resetBtn = document.getElementById('btn-reset-timer');

            if (submitBtn) submitBtn.classList.remove('hidden');
            if (cancelBtn) cancelBtn.classList.remove('hidden');
            if (resetBtn) resetBtn.classList.add('hidden');

            // Reset timer card border
            const timerCard = document.getElementById('modal-timer-card');
            if (timerCard) {
                timerCard.classList.add('border-[#27272A]');
                timerCard.classList.remove('border-red-800', 'bg-red-950/20');
            }

            // Start fresh countdown
            startCountdown();
        }

        function cancelPayment(reason = 'manual') {
            clearInterval(timerInterval);

            // Close the modal
            toggleModal('payment-modal');

            // Display cancellation alert banner on page
            const alertBox = document.getElementById('checkout-cancel-alert');
            const alertTitle = document.getElementById('cancel-alert-title');
            const alertDesc = document.getElementById('cancel-alert-desc');

            if (alertBox) {
                if (reason === 'expired') {
                    alertTitle.textContent = 'Pembayaran Kedaluwarsa';
                    alertDesc.textContent = 'Batas waktu pembayaran 10 menit telah habis dan sesi otomatis dibatalkan. Silakan lakukan pemesanan ulang.';
                } else {
                    alertTitle.textContent = 'Pembayaran Dibatalkan';
                    alertDesc.textContent = 'Sesi konfirmasi pembayaran Anda telah dibatalkan. Anda dapat memilih kembali metode pembayaran lain atau menyesuaikan jadwal.';
                }
                alertBox.classList.remove('hidden');
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        }

        function dismissCancelAlert() {
            const alertBox = document.getElementById('checkout-cancel-alert');
            if (alertBox) alertBox.classList.add('hidden');
        }
    </script>
</x-layouts.dashboard>
