<x-layouts.dashboard title="Konfirmasi Pelunasan - Socstreet Minisoccer">
    <!-- Header -->
    <div class="flex items-center gap-3 pb-6 mb-6 border-b border-[#27272A]">
        <a href="{{ route('booking.my') }}" class="p-2 rounded-xl bg-[#1E1E1E] border border-[#27272A] text-[#A1A1AA] hover:text-white hover:bg-[#242424] transition-colors" title="Kembali ke Booking Saya">
            <x-icon name="arrow-left" class="w-4 h-4" />
        </a>
        <div>
            <div class="flex items-center gap-2 mb-0.5">
                <span class="w-2 h-2 rounded-full bg-[#16A34A]"></span>
                <span class="text-xs font-semibold text-[#16A34A] uppercase tracking-wider">Pelunasan Tagihan Online</span>
            </div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Konfirmasi Pelunasan Sisa Pembayaran DP</h1>
        </div>
    </div>

    @if(isset($errors) && $errors->any())
        <div class="mb-6 p-4 rounded-xl bg-red-950/40 border border-red-800 text-red-300 text-xs flex items-center gap-3">
            <x-icon name="info" class="w-5 h-5 text-red-400 shrink-0" />
            <div>
                @foreach($errors->all() as $error)
                    <p class="font-medium">{{ $error }}</p>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Cancellation Notification Banner -->
    <div id="checkout-cancel-alert" class="hidden mb-6 p-4 rounded-xl bg-amber-950/40 border border-amber-800/80 text-amber-200 text-xs flex items-center justify-between gap-3 shadow-lg transition-all">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-amber-500/20 border border-amber-500/40 flex items-center justify-center text-amber-400 shrink-0">
                <x-icon name="alert-triangle" class="w-4 h-4" />
            </div>
            <div>
                <p class="font-bold text-white text-sm" id="cancel-alert-title">Pembayaran Pelunasan Dibatalkan</p>
                <p class="text-amber-200/80 text-xs mt-0.5" id="cancel-alert-desc">Sesi pembayaran pelunasan telah dibatalkan. Anda dapat memilih kembali metode pembayaran lain kapan saja sebelum kickoff.</p>
            </div>
        </div>
        <button type="button" onclick="dismissCancelAlert()" class="p-1.5 rounded-lg text-amber-400 hover:text-white hover:bg-amber-900/40 transition-colors">
            <x-icon name="x" class="w-4 h-4" />
        </button>
    </div>

    <!-- Hidden Native Submission Form -->
    <form id="settlement-submit-form" method="POST" action="{{ route('booking.settle', $booking->booking_id_string) }}" class="hidden">
        @csrf
        <input type="hidden" name="payment_method" id="form-payment-method" value="QRIS">
    </form>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Main Content Column (Left / 8 cols) -->
        <div class="lg:col-span-8 space-y-6">

            <!-- Card 1: Detail Lapangan & Jadwal -->
            <div class="rounded-2xl bg-[#1E1E1E] border border-[#27272A] p-6 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-[#27272A]">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-[#16A34A]/15 border border-[#16A34A]/30 flex items-center justify-center text-[#22C55E]">
                            <x-icon name="soccer" class="w-4 h-4" />
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-white">Socstreet Main Arena</h2>
                            <p class="text-xs text-[#71717A]">FIFA Standard Monofilament Turf &bull; 42x25m</p>
                        </div>
                    </div>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-md bg-[#181818] text-[#22C55E] border border-[#27272A] font-mono">
                        #{{ $booking->booking_id_string }}
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                    <div class="p-3 rounded-xl bg-[#181818] border border-[#27272A]">
                        <p class="text-[#71717A] mb-1">Tanggal Pertandingan</p>
                        <p class="font-bold text-white text-sm">
                            {{ Carbon\Carbon::parse($booking->book_date)->isoFormat('dddd, D MMM Y') }}
                        </p>
                    </div>
                    <div class="p-3 rounded-xl bg-[#181818] border border-[#27272A]">
                        <p class="text-[#71717A] mb-1">Sesi Jam Booking</p>
                        <p class="font-bold text-[#22C55E] text-sm">
                            {{ $booking->start_time }} - {{ $booking->end_time }}
                        </p>
                    </div>
                    <div class="p-3 rounded-xl bg-[#181818] border border-[#27272A]">
                        <p class="text-[#71717A] mb-1">Durasi Sewa</p>
                        <p class="font-bold text-white text-sm">
                            {{ $booking->duration ?? 2 }} Jam
                        </p>
                        <span class="text-[10px] text-[#A1A1AA]">Sesi Standar Arena</span>
                    </div>
                </div>
            </div>

            <!-- Card 2: Status Down Payment & Rincian Sisa -->
            <div class="rounded-2xl bg-[#181818] border border-[#27272A] p-6 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-[#27272A]">
                    <div>
                        <h2 class="text-base font-bold text-white">Rincian Pembayaran Down Payment (DP)</h2>
                        <p class="text-xs text-[#A1A1AA] mt-0.5">Sisa tagihan wajib dilunasi sebelum kickoff pertandingan.</p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-950/70 border border-amber-500/40 text-amber-300 text-[11px] font-semibold">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                        DP 50% Aktif
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                    <div class="p-3.5 rounded-xl bg-[#1E1E1E] border border-[#27272A]">
                        <span class="text-[#71717A] text-[11px] uppercase font-bold tracking-wider">Total Biaya Sewa</span>
                        <p class="text-base font-bold text-white mt-1">Rp {{ number_format($totalPrice, 0, ',', '.') }}</p>
                        <span class="text-[10px] text-[#71717A]">Tarif Lapangan 2 Jam</span>
                    </div>
                    <div class="p-3.5 rounded-xl bg-[#1E1E1E] border border-[#27272A]">
                        <span class="text-[#71717A] text-[11px] uppercase font-bold tracking-wider">DP 50% Terbayar</span>
                        <p class="text-base font-bold text-emerald-400 mt-1">Rp {{ number_format($paidAmount, 0, ',', '.') }}</p>
                        <span class="text-[10px] text-emerald-400 flex items-center gap-1 mt-0.5">
                            <x-icon name="check" class="w-3 h-3" />
                            <span>Telah Dikonfirmasi</span>
                        </span>
                    </div>
                    <div class="p-3.5 rounded-xl bg-amber-950/20 border border-amber-500/40">
                        <span class="text-amber-400 text-[11px] uppercase font-bold tracking-wider">Sisa Yang Harus Dibayar</span>
                        <p class="text-xl font-black text-amber-300 mt-0.5">Rp {{ number_format($remainingAmount, 0, ',', '.') }}</p>
                        <span class="text-[10px] text-amber-300/80">Tagihan Pelunasan</span>
                    </div>
                </div>
            </div>

            <!-- Card 3: Pilihan Metode Pembayaran Pelunasan -->
            <div class="rounded-2xl bg-[#181818] border border-[#27272A] p-6 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-[#27272A]">
                    <div>
                        <h2 class="text-base font-bold text-white">Pilih Metode Pembayaran Pelunasan</h2>
                        <p class="text-xs text-[#A1A1AA] mt-0.5">Klik salah satu metode pembayaran di bawah ini:</p>
                    </div>
                    <span class="text-xs text-[#16A34A] font-semibold">Tersedia 6 Kanal</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" id="payment-methods-grid">
                    @foreach($paymentMethods as $index => $method)
                        <div onclick="selectPaymentMethod('{{ $method['id'] }}', '{{ $method['name'] }}')"
                             class="payment-method-card relative cursor-pointer p-4 rounded-xl border {{ $index === 0 ? 'border-[#16A34A] bg-[#16A34A]/10 ring-1 ring-[#16A34A]' : 'border-[#27272A] bg-[#1E1E1E]' }} hover:border-[#3F3F46] hover:bg-[#242424] transition-all flex items-start gap-3.5 select-none"
                             data-method-id="{{ $method['id'] }}"
                             id="method-card-{{ $method['id'] }}">

                            <!-- Vector Logo White Badge -->
                            <div class="h-10 w-16 bg-white rounded-lg flex items-center justify-center p-1.5 shadow-sm border border-gray-100 shrink-0">
                                <img src="{{ asset($method['logo']) }}" alt="{{ $method['name'] }}" class="max-h-full max-w-full object-contain">
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-1">
                                    <span class="text-xs font-bold text-white truncate">{{ $method['name'] }}</span>
                                    <span class="text-[9px] font-semibold px-1.5 py-0.5 rounded bg-[#27272A] text-[#22C55E] shrink-0">
                                        {{ $method['badge'] }}
                                    </span>
                                </div>
                                <p class="text-[10px] text-[#71717A] mt-0.5 leading-snug">{{ $method['desc'] }}</p>
                                <span class="text-[9px] text-[#A1A1AA] font-mono mt-1 block uppercase tracking-wider">{{ $method['category'] }}</span>
                            </div>

                            <!-- Radio indicator ring -->
                            <div class="radio-ring w-4 h-4 rounded-full border {{ $index === 0 ? 'border-[#16A34A] bg-[#16A34A]' : 'border-[#3F3F46]' }} flex items-center justify-center shrink-0 mt-0.5">
                                <span class="radio-dot w-1.5 h-1.5 rounded-full bg-white {{ $index === 0 ? '' : 'hidden' }}"></span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        <!-- Sidebar Summary Column (Right / 4 cols) -->
        <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-24">
            <div class="rounded-2xl bg-[#181818] border border-[#27272A] p-6 space-y-5 shadow-2xl">
                <div>
                    <h2 class="text-base font-bold text-white">Ringkasan Pelunasan</h2>
                    <p class="text-xs text-[#A1A1AA] mt-0.5">Konfirmasi sisa tagihan booking Anda</p>
                </div>

                <div class="space-y-3 text-xs border-b border-[#27272A] pb-4">
                    <div class="flex justify-between items-center text-[#A1A1AA]">
                        <span>ID Booking</span>
                        <span class="font-mono text-white font-bold">#{{ $booking->booking_id_string }}</span>
                    </div>
                    <div class="flex justify-between items-center text-[#A1A1AA]">
                        <span>Sesi Sewa (2 Jam)</span>
                        <span class="text-white font-medium">{{ $booking->start_time }} - {{ $booking->end_time }}</span>
                    </div>
                    <div class="flex justify-between items-center text-[#A1A1AA]">
                        <span>Metode Terpilih</span>
                        <span id="summary-payment-method" class="text-white font-bold text-[#22C55E]">QRIS</span>
                    </div>
                    <div class="flex justify-between items-center text-[#A1A1AA]">
                        <span>Total Biaya Sewa</span>
                        <span class="text-white font-mono">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-emerald-400">
                        <span>DP 50% (Sudah Dibayar)</span>
                        <span class="font-mono font-medium">- Rp {{ number_format($paidAmount, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Final Amount To Pay -->
                <div class="flex items-baseline justify-between pt-1">
                    <div>
                        <p class="text-xs text-[#71717A] uppercase font-bold tracking-wider">Sisa Tagihan Pelunasan</p>
                        <p class="text-[11px] text-[#A1A1AA] mt-0.5">Status setelah bayar: Lunas 100%</p>
                    </div>
                    <span class="text-2xl font-black text-[#22C55E]">
                        Rp {{ number_format($remainingAmount, 0, ',', '.') }}
                    </span>
                </div>

                <!-- Security / Tolerance rule notice -->
                <div class="flex items-start gap-2.5 p-3 rounded-xl bg-[#1E1E1E] border border-[#27272A] text-[11px] text-[#A1A1AA] leading-relaxed">
                    <x-icon name="shield" class="w-4 h-4 text-[#16A34A] shrink-0 mt-0.5" />
                    <span>Sesi pembayaran pelunasan online memiliki batas waktu verifikasi 10 menit.</span>
                </div>

                <!-- Action Button Trigger Modal -->
                <x-button variant="primary" size="lg" type="button" onclick="openPaymentModal()" class="w-full shadow-xl shadow-[#16A34A]/30 font-bold">
                    <span>Lanjut Konfirmasi Pelunasan</span>
                    <x-icon name="arrow-right" class="w-4 h-4" />
                </x-button>

                <div class="text-center">
                    <a href="{{ route('booking.my') }}" class="text-xs text-[#71717A] hover:text-white transition-colors">
                        Kembali ke Riwayat Booking
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Instruction & Confirmation Modal -->
    <x-modal id="payment-modal" title="Konfirmasi & Pelunasan Pembayaran" subtitle="Socstreet Main Arena - Pembayaran Online" maxWidth="lg">
        <div class="space-y-4 text-left">
            <!-- Expired / Cancelled Banner inside Modal -->
            <div id="modal-expired-alert" class="hidden p-3.5 rounded-xl bg-red-950/60 border border-red-800 text-red-200 text-xs flex items-center gap-3">
                <x-icon name="alert-triangle" class="w-5 h-5 text-red-400 shrink-0" />
                <div class="flex-1">
                    <p class="font-bold text-white text-xs">Waktu Pembayaran Habis (Kedaluwarsa)</p>
                    <p class="text-red-300 text-[11px] mt-0.5">Batas waktu pembayaran pelunasan 10 menit telah terlewati. Sesi transaksi ini otomatis dibatalkan.</p>
                </div>
            </div>

            <!-- Amount & Countdown Bar -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-left">
                <!-- Amount Pill -->
                <div class="p-3.5 rounded-xl bg-[#1E1E1E] border border-[#27272A]">
                    <p class="text-[10px] text-[#71717A] uppercase font-bold tracking-wider">Sisa Tagihan Pelunasan</p>
                    <p class="text-2xl font-black text-[#22C55E] mt-0.5">
                        Rp {{ number_format($remainingAmount, 0, ',', '.') }}
                    </p>
                    <p class="text-[10px] text-[#A1A1AA] mt-0.5" id="modal-method-summary-label">Metode: QRIS</p>
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
                    <span class="font-medium text-white">{{ Carbon\Carbon::parse($booking->book_date)->isoFormat('dddd, D MMM Y') }}</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <x-icon name="clock" class="w-3.5 h-3.5 text-[#22C55E]" />
                    <span class="font-bold text-white">{{ $booking->start_time }} - {{ $booking->end_time }}</span>
                    <span class="text-[10px] text-[#71717A]">({{ $booking->duration ?? 2 }} Jam)</span>
                </div>
            </div>

            <!-- VIEW 1: QRIS ONLY -->
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
                        <span>Cara Pelunasan via QRIS:</span>
                    </div>
                    <p class="text-[11px] text-[#71717A] leading-relaxed">
                        1. Buka aplikasi m-Banking (BCA, Mandiri, BRI, BNI) atau E-Wallet (GoPay, DANA, OVO, ShopeePay).<br>
                        2. Pilih menu <strong>Scan / QRIS</strong> lalu arahkan kamera ke kode QR di atas.<br>
                        3. Periksa nominal tagihan sisa, masukkan PIN Anda, lalu klik tombol <strong>"Saya Sudah Membayar Pelunasan"</strong> di bawah.
                    </p>
                </div>
            </div>

            <!-- VIEW 2: VIRTUAL ACCOUNT (BCA, MANDIRI LIVIN, BRI BRIVA) -->
            <div id="view-va" class="payment-method-view hidden space-y-3.5 text-left">
                <!-- Bank Identity Header -->
                <div class="flex items-center justify-between p-3.5 rounded-xl bg-[#141414] border border-[#27272A]">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-24 bg-white rounded-xl flex items-center justify-center p-1.5 shadow-sm border border-gray-100 shrink-0">
                            <img id="va-bank-logo-img" src="{{ asset('images/payments/bca.svg') }}" alt="Bank Logo" class="max-h-full max-w-full object-contain">
                        </div>
                        <div>
                            <p class="text-xs font-bold text-white" id="va-bank-name-display">BCA Virtual Account</p>
                            <p class="text-[10px] text-[#71717A]" id="va-bank-subtitle">m-BCA, KlikBCA & ATM BCA</p>
                        </div>
                    </div>
                    <span class="text-[10px] font-semibold text-[#22C55E] bg-[#16A34A]/10 border border-[#16A34A]/30 px-2 py-0.5 rounded">
                        Online 24 Jam
                    </span>
                </div>

                <!-- VA Number Display with Copy Button -->
                <div class="p-4 rounded-xl bg-[#1E1E1E] border border-[#27272A] space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-semibold text-[#A1A1AA] uppercase tracking-wider">Nomor Virtual Account</span>
                        <span class="text-[10px] text-[#71717A]">Socstreet Arena / {{ auth()->user()->username ?? 'Member' }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-3 bg-[#141414] p-3 rounded-lg border border-[#27272A]">
                        <span class="text-lg sm:text-xl font-mono font-extrabold text-white tracking-widest" id="va-number-display">
                            8077 7081 2938 4712
                        </span>
                        <button type="button" 
                                onclick="copyVaNumber()" 
                                id="btn-copy-va"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md bg-[#27272A] hover:bg-[#3F3F46] active:scale-95 text-[#22C55E] text-xs font-semibold transition-all cursor-pointer shrink-0">
                            <x-icon name="copy" class="w-3.5 h-3.5" />
                            <span id="copy-text-label">Salin</span>
                        </button>
                    </div>
                    <div id="copy-success-toast" class="hidden text-[11px] text-[#22C55E] flex items-center gap-1.5 pt-1">
                        <x-icon name="check" class="w-3.5 h-3.5" />
                        <span>Nomor Virtual Account berhasil disalin ke clipboard!</span>
                    </div>
                </div>

                <!-- Accordion Petunjuk Transfer m-Banking & ATM -->
                <div class="space-y-2 text-xs">
                    <p class="text-white font-semibold text-xs flex items-center gap-1.5">
                        <x-icon name="smartphone" class="w-3.5 h-3.5 text-[#22C55E]" />
                        <span>Panduan Pembayaran Virtual Account:</span>
                    </p>

                    <!-- Tab m-Banking -->
                    <div class="rounded-xl bg-[#1E1E1E] border border-[#27272A] overflow-hidden">
                        <button type="button" onclick="toggleVaGuide('mbanking')" class="w-full p-3 flex items-center justify-between text-left text-xs font-semibold text-white hover:bg-[#242424] transition-colors">
                            <span id="guide-mbanking-title">Pembayaran via m-Banking</span>
                            <x-icon name="chevron-down" class="w-4 h-4 text-[#71717A] transform transition-transform" id="icon-arrow-mbanking" />
                        </button>
                        <div id="guide-mbanking-content" class="p-3 pt-0 text-[11px] text-[#A1A1AA] space-y-1.5 border-t border-[#27272A]/50 hidden">
                            <div id="va-guide-mbanking-text"></div>
                        </div>
                    </div>

                    <!-- Tab ATM -->
                    <div class="rounded-xl bg-[#1E1E1E] border border-[#27272A] overflow-hidden">
                        <button type="button" onclick="toggleVaGuide('atm')" class="w-full p-3 flex items-center justify-between text-left text-xs font-semibold text-white hover:bg-[#242424] transition-colors">
                            <span id="guide-atm-title">Pembayaran via Mesin ATM</span>
                            <x-icon name="chevron-down" class="w-4 h-4 text-[#71717A] transform transition-transform" id="icon-arrow-atm" />
                        </button>
                        <div id="guide-atm-content" class="p-3 pt-0 text-[11px] text-[#A1A1AA] space-y-1.5 border-t border-[#27272A]/50 hidden">
                            <div id="va-guide-atm-text"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- VIEW 3: E-WALLET (DANA & GOPAY INTERACTIVE SIMULATION MODAL) -->
            <div id="view-ewallet" class="payment-method-view hidden space-y-3.5 text-left">
                <!-- E-Wallet Header Banner -->
                <div class="flex items-center justify-between p-3.5 rounded-xl bg-[#141414] border border-[#27272A]">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-24 bg-white rounded-xl flex items-center justify-center p-1.5 shadow-sm border border-gray-100 shrink-0">
                            <img id="ewallet-logo-img" src="{{ asset('images/payments/dana.svg') }}" alt="E-Wallet Logo" class="max-h-full max-w-full object-contain">
                        </div>
                        <div>
                            <p class="text-xs font-bold text-white" id="ewallet-name-display">DANA Wallet</p>
                            <p class="text-[10px] text-[#71717A]">Instan &bull; Pembayaran Terkoneksi</p>
                        </div>
                    </div>
                    <span class="text-[10px] font-semibold text-[#22C55E] bg-[#16A34A]/10 border border-[#16A34A]/30 px-2 py-0.5 rounded">
                        Koneksi Instan
                    </span>
                </div>

                <!-- Simulation Info Box -->
                <div class="p-4 rounded-xl bg-[#1E1E1E] border border-[#27272A] space-y-3">
                    <div class="flex items-start gap-2.5">
                        <div class="w-7 h-7 rounded-lg bg-[#16A34A]/20 border border-[#16A34A]/40 flex items-center justify-center text-[#22C55E] shrink-0 mt-0.5">
                            <x-icon name="smartphone" class="w-3.5 h-3.5" />
                        </div>
                        <div>
                            <p class="text-xs font-bold text-white">Simulasi Otorisasi Aplikasi Dompet Digital</p>
                            <p class="text-[11px] text-[#A1A1AA] mt-0.5 leading-relaxed">
                                Untuk kemudahan presentasi tugas, Anda dapat mengklik tombol simulasi berikut untuk mengotorisasi pelunasan e-wallet tanpa perlu membuka aplikasi ponsel eksternal.
                            </p>
                        </div>
                    </div>

                    <!-- Interactive Simulation Button -->
                    <div class="p-3 bg-[#141414] rounded-lg border border-[#27272A] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#22C55E] animate-pulse"></span>
                            <span class="text-xs font-mono font-semibold text-white" id="ewallet-status-label">Siap Diotorisasi</span>
                        </div>
                        <button type="button" 
                                onclick="simulateEwalletAuth()"
                                id="btn-simulate-ewallet"
                                class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#16A34A]/20 hover:bg-[#16A34A]/30 border border-[#16A34A]/40 text-[#22C55E] text-xs font-semibold transition-all cursor-pointer">
                            <x-icon name="external-link" class="w-3.5 h-3.5" />
                            <span id="ewallet-btn-label">Buka Aplikasi & Otorisasi Pelunasan</span>
                        </button>
                    </div>

                    <div id="ewallet-auth-toast" class="hidden p-2.5 rounded-lg bg-emerald-950/60 border border-emerald-800 text-emerald-300 text-[11px] flex items-center gap-2">
                        <x-icon name="check-circle" class="w-4 h-4 text-emerald-400 shrink-0" />
                        <span>Otorisasi berhasil! Silakan klik tombol <strong>"Saya Sudah Membayar Pelunasan"</strong> di bawah.</span>
                    </div>
                </div>

                <div class="text-left text-[11px] text-[#71717A] p-3 rounded-xl bg-[#141414] border border-[#27272A] leading-relaxed">
                    Saldo dompet digital Anda akan dipotong otomatis sejumlah sisa tagihan pelunasan di atas.
                </div>
            </div>

            <!-- Manual Cancel Section -->
            <div class="pt-2">
                <button type="button" 
                        onclick="cancelPaymentFromModal('manual')"
                        class="w-full py-2 rounded-xl text-xs font-semibold text-[#A1A1AA] hover:text-red-400 hover:bg-red-950/20 border border-transparent hover:border-red-900/40 transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                    <x-icon name="x" class="w-3.5 h-3.5" />
                    <span>Batalkan Sesi Pembayaran Ini</span>
                </button>
            </div>
        </div>

        <x-slot:footer>
            <button 
                type="button" 
                onclick="cancelPaymentFromModal('manual')"
                class="px-4 py-2.5 rounded-xl text-xs font-semibold text-[#A1A1AA] hover:text-white hover:bg-[#242424] transition-colors cursor-pointer"
            >
                Tutup
            </button>
            <!-- Native Form Submission Button -->
            <button 
                type="button" 
                id="btn-confirm-payment"
                onclick="handlePaymentSubmit(this)"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#16A34A] hover:bg-[#15803D] active:scale-95 text-white text-xs font-bold shadow-lg shadow-[#16A34A]/25 transition-all cursor-pointer"
            >
                <x-icon name="check" class="w-4 h-4" />
                <span id="btn-confirm-label">Saya Sudah Membayar Pelunasan</span>
            </button>
        </x-slot:footer>
    </x-modal>

    <script>
        // State
        let selectedMethod = 'QRIS';
        let countdownSeconds = 600; // 10 minutes
        let countdownInterval = null;
        let isExpired = false;

        const vaBankData = {
            'BCA': {
                name: 'BCA Virtual Account',
                number: '8077 7081 2938 4712',
                logo: "{{ asset('images/payments/bca.svg') }}",
                subtitle: 'm-BCA, KlikBCA & ATM BCA',
                app: 'm-BCA / BCA Mobile',
                mbankingGuide: `
                    1. Buka aplikasi <strong>BCA Mobile (m-BCA)</strong> > pilih menu <strong>m-Transfer</strong>.<br>
                    2. Pilih <strong>BCA Virtual Account</strong>, lalu masukkan nomor VA di atas.<br>
                    3. Periksa nominal sisa tagihan pelunasan dan nama (SOCSTREET), lalu masukkan PIN m-BCA Anda.
                `,
                atmGuide: `
                    1. Masukkan kartu ATM BCA & PIN Anda.<br>
                    2. Pilih menu <strong>Transaksi Lainnya</strong> > <strong>Transfer</strong> > <strong>ke Rekening BCA Virtual Account</strong>.<br>
                    3. Masukkan nomor Virtual Account di atas lalu tekan <strong>Benar</strong>.
                `
            },
            'Mandiri': {
                name: "Mandiri Livin' (Virtual Account)",
                number: '8870 8082 9384 7120',
                logo: "{{ asset('images/payments/mandiri.svg') }}",
                subtitle: "Livin' by Mandiri & ATM Mandiri",
                app: "Livin' by Mandiri",
                mbankingGuide: `
                    1. Buka aplikasi <strong>Livin' by Mandiri</strong> di smartphone Anda.<br>
                    2. Pilih menu <strong>Bayar</strong> > pilih opsi <strong>Virtual Account</strong>.<br>
                    3. Masukkan nomor Virtual Account Mandiri di atas.<br>
                    4. Konfirmasi rincian tagihan pelunasan lalu masukkan PIN Livin' Anda.
                `,
                atmGuide: `
                    1. Masukkan kartu ATM Mandiri & PIN.<br>
                    2. Pilih menu <strong>Bayar/Beli</strong> > <strong>Virtual Account</strong>.<br>
                    3. Masukkan nomor Virtual Account Mandiri di atas lalu setujui transaksi.
                `
            },
            'BRI': {
                name: 'BRI BRIVA (Virtual Account)',
                number: '1280 0083 8471 9280',
                logo: "{{ asset('images/payments/bri.svg') }}",
                subtitle: 'BRImo (BRI Mobile) & ATM BRI',
                app: 'BRImo',
                mbankingGuide: `
                    1. Buka aplikasi <strong>BRImo</strong> lalu login akun Anda.<br>
                    2. Pilih menu <strong>Tagihan</strong> > pilih <strong>BRIVA</strong>.<br>
                    3. Masukkan nomor BRIVA di atas, periksa rincian pelunasan.<br>
                    4. Masukkan PIN BRImo Anda untuk memverifikasi transaksi.
                `,
                atmGuide: `
                    1. Masukkan kartu ATM BRI & PIN.<br>
                    2. Pilih menu <strong>Transaksi Lain</strong> > <strong>Pembayaran</strong> > <strong>BRIVA</strong>.<br>
                    3. Masukkan nomor BRIVA di atas lalu konfirmasi pembayaran.
                `
            }
        };

        const ewalletData = {
            'DANA': {
                name: 'DANA Wallet',
                logo: "{{ asset('images/payments/dana.svg') }}"
            },
            'GoPay': {
                name: 'GoPay (Gojek)',
                logo: "{{ asset('images/payments/gopay.svg') }}"
            }
        };

        function selectPaymentMethod(methodId, methodName) {
            selectedMethod = methodId;

            // Update hidden form input
            const hiddenInput = document.getElementById('form-payment-method');
            if (hiddenInput) {
                hiddenInput.value = methodId;
            }

            // Update sidebar summary
            const summaryMethod = document.getElementById('summary-payment-method');
            if (summaryMethod) {
                summaryMethod.innerText = methodName || methodId;
            }

            // Update cards styling
            document.querySelectorAll('.payment-method-card').forEach(card => {
                const ring = card.querySelector('.radio-ring');
                const dot = card.querySelector('.radio-dot');
                const cardId = card.getAttribute('data-method-id');

                if (cardId === methodId) {
                    card.classList.remove('border-[#27272A]', 'bg-[#1E1E1E]');
                    card.classList.add('border-[#16A34A]', 'bg-[#16A34A]/10', 'ring-1', 'ring-[#16A34A]');
                    if (ring) { ring.classList.remove('border-[#3F3F46]'); ring.classList.add('border-[#16A34A]', 'bg-[#16A34A]'); }
                    if (dot) dot.classList.remove('hidden');
                } else {
                    card.classList.remove('border-[#16A34A]', 'bg-[#16A34A]/10', 'ring-1', 'ring-[#16A34A]');
                    card.classList.add('border-[#27272A]', 'bg-[#1E1E1E]');
                    if (ring) { ring.classList.remove('border-[#16A34A]', 'bg-[#16A34A]'); ring.classList.add('border-[#3F3F46]'); }
                    if (dot) dot.classList.add('hidden');
                }
            });

            // Update modal views accordingly
            updateModalViewForMethod(methodId);
        }

        function updateModalViewForMethod(method) {
            const viewQris = document.getElementById('view-qris');
            const viewVa = document.getElementById('view-va');
            const viewEwallet = document.getElementById('view-ewallet');
            const modalSummary = document.getElementById('modal-method-summary-label');

            if (modalSummary) {
                modalSummary.innerText = `Metode: ${method}`;
            }

            viewQris.classList.add('hidden');
            viewVa.classList.add('hidden');
            viewEwallet.classList.add('hidden');

            if (method === 'QRIS') {
                viewQris.classList.remove('hidden');
            } else if (['BCA', 'Mandiri', 'BRI'].includes(method)) {
                viewVa.classList.remove('hidden');
                const data = vaBankData[method];
                if (data) {
                    document.getElementById('va-bank-name-display').innerText = data.name;
                    document.getElementById('va-bank-subtitle').innerText = data.subtitle;
                    document.getElementById('va-number-display').innerText = data.number;
                    document.getElementById('va-bank-logo-img').src = data.logo;
                    document.getElementById('guide-mbanking-title').innerText = `Pembayaran via ${data.app}`;
                    document.getElementById('va-guide-mbanking-text').innerHTML = data.mbankingGuide;
                    document.getElementById('va-guide-atm-text').innerHTML = data.atmGuide;
                }
            } else if (['DANA', 'GoPay'].includes(method)) {
                viewEwallet.classList.remove('hidden');
                const data = ewalletData[method];
                if (data) {
                    document.getElementById('ewallet-name-display').innerText = data.name;
                    document.getElementById('ewallet-logo-img').src = data.logo;
                }
            }
        }

        function startCountdown() {
            clearInterval(countdownInterval);
            countdownSeconds = 600;
            isExpired = false;
            updateTimerDisplay();

            const timerCard = document.getElementById('modal-timer-card');
            const timerPing = document.getElementById('timer-ping');
            const expiredAlert = document.getElementById('modal-expired-alert');
            const qrisOverlay = document.getElementById('qris-expired-overlay');
            const btnConfirm = document.getElementById('btn-confirm-payment');

            timerCard.classList.remove('border-red-600', 'bg-red-950/30');
            timerPing.classList.remove('hidden');
            expiredAlert.classList.add('hidden');
            qrisOverlay.classList.add('hidden');
            btnConfirm.disabled = false;
            btnConfirm.classList.remove('opacity-50', 'cursor-not-allowed');

            countdownInterval = setInterval(() => {
                countdownSeconds--;
                updateTimerDisplay();

                if (countdownSeconds <= 0) {
                    clearInterval(countdownInterval);
                    handleTimerExpired();
                }
            }, 1000);
        }

        function updateTimerDisplay() {
            const minutes = Math.floor(countdownSeconds / 60);
            const seconds = countdownSeconds % 60;
            const formatted = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            const timerEl = document.getElementById('countdown-timer');
            if (timerEl) {
                timerEl.innerText = formatted;
            }
        }

        function handleTimerExpired() {
            isExpired = true;
            document.getElementById('modal-expired-alert').classList.remove('hidden');
            document.getElementById('qris-expired-overlay').classList.remove('hidden');
            document.getElementById('timer-ping').classList.add('hidden');

            const btnConfirm = document.getElementById('btn-confirm-payment');
            btnConfirm.disabled = true;
            btnConfirm.classList.add('opacity-50', 'cursor-not-allowed');

            setTimeout(() => {
                cancelPaymentFromModal('expired');
            }, 4000);
        }

        function cancelPaymentFromModal(reason = 'manual') {
            clearInterval(countdownInterval);
            toggleModal('payment-modal');

            const alertBox = document.getElementById('checkout-cancel-alert');
            const titleEl = document.getElementById('cancel-alert-title');
            const descEl = document.getElementById('cancel-alert-desc');

            if (reason === 'expired') {
                titleEl.innerText = 'Pembayaran Kedaluwarsa (Waktu Habis)';
                descEl.innerText = 'Batas waktu pembayaran 10 menit telah habis. Silakan pilih kembali metode pembayaran dan ulangi konfirmasi.';
            } else {
                titleEl.innerText = 'Pembayaran Dibatalkan';
                descEl.innerText = 'Sesi pembayaran pelunasan telah dibatalkan oleh pengguna. Anda dapat memilih kembali metode lain kapan saja.';
            }

            alertBox.classList.remove('hidden');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function dismissCancelAlert() {
            document.getElementById('checkout-cancel-alert').classList.add('hidden');
        }

        function copyVaNumber() {
            const vaNum = document.getElementById('va-number-display').innerText.replace(/\s+/g, '');
            navigator.clipboard.writeText(vaNum).then(() => {
                const toast = document.getElementById('copy-success-toast');
                const label = document.getElementById('copy-text-label');
                label.innerText = 'Tersalin!';
                toast.classList.remove('hidden');
                setTimeout(() => {
                    label.innerText = 'Salin';
                    toast.classList.add('hidden');
                }, 2500);
            });
        }

        function toggleVaGuide(type) {
            const content = document.getElementById(`guide-${type}-content`);
            const icon = document.getElementById(`icon-arrow-${type}`);
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                icon.classList.add('rotate-180');
            } else {
                content.classList.add('hidden');
                icon.classList.remove('rotate-180');
            }
        }

        function simulateEwalletAuth() {
            const btn = document.getElementById('btn-simulate-ewallet');
            const label = document.getElementById('ewallet-btn-label');
            const statusLabel = document.getElementById('ewallet-status-label');
            const toast = document.getElementById('ewallet-auth-toast');

            btn.disabled = true;
            label.innerText = 'Menghubungkan ke Aplikasi...';

            setTimeout(() => {
                label.innerText = 'Otorisasi Berhasil';
                statusLabel.innerText = 'Terhubung & Terverifikasi';
                toast.classList.remove('hidden');
            }, 1200);
        }

        function openPaymentModal() {
            dismissCancelAlert();
            updateModalViewForMethod(selectedMethod);
            startCountdown();
            toggleModal('payment-modal');
        }

        function handlePaymentSubmit(btn) {
            if (isExpired) {
                alert('Batas waktu pembayaran telah habis. Silakan ulangi transaksi.');
                return false;
            }

            btn.disabled = true;
            btn.classList.add('opacity-75', 'cursor-not-allowed');
            btn.innerHTML = `
                <span class="w-4 h-4 border-2 border-white/20 border-t-white rounded-full animate-spin inline-block"></span>
                <span>Memproses Pelunasan...</span>
            `;

            // Submit the native HTML form
            const form = document.getElementById('settlement-submit-form');
            if (form) {
                form.submit();
            }
        }
    </script>
</x-layouts.dashboard>
