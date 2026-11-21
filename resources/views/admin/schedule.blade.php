<x-layouts.admin title="Schedule & Pricing" active="admin.schedule">
    <div class="flex flex-col w-full gap-6">

        <!-- Page Heading & Status Bar -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-[#A1A1AA] text-xs font-semibold uppercase tracking-wider mb-1">
                    <span>Operational Control</span>
                    <span class="text-[#3F3F46]">•</span>
                    <span class="text-[#4ADE80]">Socstreet Main Arena</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Tarif & Ketersediaan Sesi</h1>
                <p class="text-sm text-[#A1A1AA] mt-1 max-w-2xl">
                    Atur konfigurasi tarif sesi bermain dan manajemen pemeliharaan slot arena secara terpusat dan real-time.
                </p>
            </div>

            <!-- Quick Status Metabar -->
            <div class="flex items-center gap-4 bg-[#18181B] border border-[#27272A] rounded-xl px-4 py-2 self-start md:self-auto">
                <div class="flex flex-col">
                    <span class="text-[10px] font-semibold text-[#71717A] uppercase tracking-wider">Status Sistem</span>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="w-2 h-2 rounded-full bg-[#16A34A] animate-pulse"></span>
                        <span class="text-xs text-white font-semibold">Live Booking Aktif</span>
                    </div>
                </div>
                <div class="h-7 w-px bg-[#27272A]"></div>
                <div class="flex flex-col">
                    <span class="text-[10px] font-semibold text-[#71717A] uppercase tracking-wider">Tersedia Hari Ini</span>
                    <span class="text-xs text-[#4ADE80] font-bold font-mono">5 / 7 Slot</span>
                </div>
            </div>
        </div>

        <!-- Two-Column Master Grid Layout (Left: Pricing 5 cols, Right: Block/Maintenance 7 cols) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <!-- LEFT COLUMN: Field Pricing Card (span 5 / ~40%) -->
            <section class="lg:col-span-5 bg-[#18181B] border border-[#27272A] rounded-xl p-6 shadow-xl relative overflow-hidden">
                <!-- Subtle Glow Accent -->
                <div class="absolute -right-16 -top-16 w-36 h-36 bg-[#16A34A]/10 rounded-full blur-3xl pointer-events-none"></div>

                <!-- Card Header -->
                <div class="flex items-start justify-between border-b border-[#27272A] pb-4 mb-5">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[#16A34A] text-[22px]">payments</span>
                            <h2 class="text-base font-bold text-white tracking-tight">Tarif Sewa Lapangan</h2>
                        </div>
                        <p class="text-xs text-[#A1A1AA] mt-0.5">Socstreet Main Arena (Durasi per Sesi: 2 Jam)</p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-[#142918] border border-[#1E4624] text-[#4ADE80] text-[11px] font-semibold">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#16A34A]"></span>
                        Aktif
                    </span>
                </div>

                <!-- Form Inputs Stack -->
                <form class="flex flex-col gap-4" onsubmit="event.preventDefault(); showAdminToast('Tarif Disimpan', 'Konfigurasi tarif sesi bermain berhasil diperbarui.');">
                    <!-- Price Input -->
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-[#A1A1AA] flex items-center justify-between" for="sessionPrice">
                            <span>Harga Sewa per Sesi / 2 Jam (Rp)</span>
                            <span class="text-[11px] text-[#4ADE80] font-mono font-semibold">IDR Normal</span>
                        </label>
                        <div class="relative flex items-center">
                            <span class="absolute left-3.5 text-sm text-[#71717A] font-mono select-none">Rp</span>
                            <input class="w-full bg-[#121212] border border-[#27272A] rounded-lg pl-11 pr-4 py-2.5 text-white text-lg font-bold tracking-tight focus:outline-none focus:border-[#16A34A] focus:ring-1 focus:ring-[#16A34A] transition-all font-mono" 
                                   id="sessionPrice" 
                                   type="text" 
                                   value="{{ $pricing['price_formatted'] ?? '300.000' }}"/>
                        </div>
                        <p class="text-[11px] text-[#71717A] flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">info</span>
                            Tarif berlaku otomatis untuk jadwal reservasi publik mendatang.
                        </p>
                    </div>

                    <!-- Operational Hours (Fixed PRD Rule) -->
                    <div class="flex flex-col gap-1.5">
                        <label class="text-xs font-semibold text-[#A1A1AA]" for="operatingHours">
                            Jam Operasional (Tetap Sesuai PRD)
                        </label>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-3.5 text-[#71717A] text-[18px]">schedule</span>
                            <input class="w-full bg-[#121212] border border-[#27272A] rounded-lg pl-10 pr-4 py-2.5 text-white text-xs font-semibold tracking-wide focus:outline-none focus:border-[#16A34A] cursor-not-allowed text-[#A1A1AA]" 
                                   id="operatingHours" 
                                   type="text" 
                                   value="{{ $pricing['operating_hours'] ?? '08:00 - 22:00' }}" 
                                   readonly/>
                        </div>
                    </div>

                    <!-- Operational Info Summary Box -->
                    <div class="bg-[#121212] border border-dashed border-[#27272A] rounded-lg p-3.5 flex flex-col gap-2 my-1 text-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-[#A1A1AA] flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[15px] text-[#71717A]">timer</span>
                                Durasi Standar
                            </span>
                            <span class="text-white font-mono font-semibold">120 Menit / Sesi</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-[#A1A1AA] flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[15px] text-[#71717A]">view_timeline</span>
                                Total Slot Harian
                            </span>
                            <span class="text-white font-mono font-semibold">7 Sesi Penuh</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-[#A1A1AA] flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[15px] text-[#71717A]">hourglass_top</span>
                                Toleransi Bayar
                            </span>
                            <span class="text-white font-mono font-semibold">15 Menit</span>
                        </div>
                    </div>

                    <!-- Hourly Breakdown Demand Spark Chart -->
                    <div class="bg-[#141414] border border-[#27272A] rounded-lg p-3.5">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[11px] text-[#71717A] uppercase tracking-wider font-semibold">Distribusi Permintaan Sesi</span>
                            <span class="text-[11px] text-[#16A34A] font-bold">Rata-rata 92% Weekend</span>
                        </div>
                        <div class="h-10 w-full flex items-end gap-1.5 pt-1">
                            <div class="flex-1 bg-[#27272A] hover:bg-[#16A34A] transition-colors h-[35%] rounded-t-sm" title="08:00 - 10:00 (35%)"></div>
                            <div class="flex-1 bg-[#27272A] hover:bg-[#16A34A] transition-colors h-[45%] rounded-t-sm" title="10:00 - 12:00 (45%)"></div>
                            <div class="flex-1 bg-[#27272A] hover:bg-[#16A34A] transition-colors h-[30%] rounded-t-sm" title="12:00 - 14:00 (30%)"></div>
                            <div class="flex-1 bg-[#27272A] hover:bg-[#16A34A] transition-colors h-[60%] rounded-t-sm" title="14:00 - 16:00 (60%)"></div>
                            <div class="flex-1 bg-[#3F3F46] hover:bg-[#16A34A] transition-colors h-[80%] rounded-t-sm" title="16:00 - 18:00 (80%)"></div>
                            <div class="flex-1 bg-[#16A34A] hover:bg-[#22C55E] transition-colors h-[100%] rounded-t-sm" title="18:00 - 20:00 (Peak 100%)"></div>
                            <div class="flex-1 bg-[#16A34A]/80 hover:bg-[#22C55E] transition-colors h-[90%] rounded-t-sm" title="20:00 - 22:00 (90%)"></div>
                        </div>
                        <div class="flex justify-between text-[10px] text-[#71717A] font-mono mt-1 px-0.5">
                            <span>08:00</span>
                            <span>14:00</span>
                            <span class="text-[#4ADE80] font-bold">18:00 (Peak)</span>
                            <span>22:00</span>
                        </div>
                    </div>

                    <!-- CTA Save Button -->
                    <button class="w-full mt-1 bg-[#16A34A] hover:bg-[#15803D] active:scale-[0.99] text-white text-xs font-bold py-3 px-4 rounded-lg shadow-lg shadow-[#16A34A]/20 flex items-center justify-center gap-2 transition-all cursor-pointer" type="submit">
                        <span class="material-symbols-outlined text-[18px]">save</span>
                        <span>Simpan Perubahan Harga</span>
                    </button>
                </form>
            </section>

            <!-- RIGHT COLUMN: Block Schedule / Maintenance (span 7 / ~60%) -->
            <section class="lg:col-span-7 bg-[#18181B] border border-[#27272A] rounded-xl p-6 shadow-xl flex flex-col gap-5">
                <!-- Card Header -->
                <div class="flex items-start justify-between border-b border-[#27272A] pb-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[#EF4444] text-[22px]">event_busy</span>
                            <h2 class="text-base font-bold text-white tracking-tight">Blokir Jadwal / Maintenance</h2>
                        </div>
                        <p class="text-xs text-[#A1A1AA] mt-0.5">Tutup slot waktu tertentu untuk pemeliharaan rutin atau acara internal.</p>
                    </div>
                    <div class="hidden sm:flex items-center gap-1 text-[#71717A] text-[11px] font-semibold">
                        <span class="material-symbols-outlined text-[16px]">lock_clock</span>
                        <span>Instant Lock</span>
                    </div>
                </div>

                <!-- Action Form / Controls Grid -->
                <form id="blockSlotForm" class="bg-[#121212] border border-[#27272A] rounded-lg p-4" onsubmit="event.preventDefault(); handleAddBlockSlot();">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <!-- Date Input -->
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[11px] font-semibold text-[#A1A1AA] uppercase tracking-wider" for="blockDate">Tanggal</label>
                            <div class="relative flex items-center">
                                <span class="material-symbols-outlined absolute left-3 text-[#71717A] text-[18px]">calendar_today</span>
                                <input class="w-full bg-[#18181B] border border-[#27272A] rounded-lg pl-10 pr-3 py-2 text-white text-xs font-semibold focus:outline-none focus:border-[#EF4444] focus:ring-1 focus:ring-[#EF4444] transition-all" 
                                       id="blockDate" 
                                       type="date" 
                                       value="{{ date('Y-m-d', strtotime('+3 days')) }}"
                                       required/>
                            </div>
                        </div>

                        <!-- Time Slot Selection (Strict 2-Hour Blocks) -->
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[11px] font-semibold text-[#A1A1AA] uppercase tracking-wider" for="blockSlot">Sesi (2 Jam)</label>
                            <div class="relative flex items-center">
                                <span class="material-symbols-outlined absolute left-3 text-[#71717A] text-[18px]">alarm</span>
                                <select class="w-full bg-[#18181B] border border-[#27272A] rounded-lg pl-10 pr-8 py-2 text-white text-xs font-semibold focus:outline-none focus:border-[#EF4444] focus:ring-1 focus:ring-[#EF4444] appearance-none cursor-pointer transition-all" 
                                        id="blockSlot">
                                    <option value="08:00 - 10:00">08:00 - 10:00</option>
                                    <option value="10:00 - 12:00">10:00 - 12:00</option>
                                    <option value="12:00 - 14:00">12:00 - 14:00</option>
                                    <option value="14:00 - 16:00">14:00 - 16:00</option>
                                    <option value="16:00 - 18:00">16:00 - 18:00</option>
                                    <option selected value="18:00 - 20:00">18:00 - 20:00 (Prime)</option>
                                    <option value="20:00 - 22:00">20:00 - 22:00</option>
                                </select>
                                <span class="material-symbols-outlined absolute right-2.5 text-[#71717A] text-[18px] pointer-events-none">expand_more</span>
                            </div>
                        </div>

                        <!-- Reason Input -->
                        <div class="flex flex-col gap-1.5 md:col-span-2">
                            <label class="text-[11px] font-semibold text-[#A1A1AA] uppercase tracking-wider" for="blockReason">Alasan Pemblokiran</label>
                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                                <div class="relative flex items-center flex-1">
                                    <span class="material-symbols-outlined absolute left-3 text-[#71717A] text-[18px]">edit_note</span>
                                    <input class="w-full bg-[#18181B] border border-[#27272A] rounded-lg pl-10 pr-3 py-2 text-white text-xs focus:outline-none focus:border-[#EF4444] focus:ring-1 focus:ring-[#EF4444] transition-all" 
                                           id="blockReason" 
                                           placeholder="Contoh: Perawatan Rumput / Turnamen Internal" 
                                           type="text" 
                                           value="Perawatan Rumput Sintetis"
                                           required/>
                                </div>
                                <button class="bg-[#EF4444] hover:bg-[#DC2626] active:scale-[0.99] text-white text-xs font-bold px-4 py-2 rounded-lg flex items-center justify-center gap-1.5 transition-all shadow-md shrink-0 cursor-pointer" 
                                        type="submit">
                                    <span class="material-symbols-outlined text-[17px]">block</span>
                                    <span>Blokir Slot</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Bottom Section: Daftar Jadwal Diblokir -->
                <div class="flex flex-col gap-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <h3 class="text-sm font-bold text-white tracking-tight">Daftar Jadwal Diblokir</h3>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-[#271010] border border-[#4D1616] text-[#FF8080] text-[10px] font-semibold" id="activeBlockCount">
                                {{ count($blockedSchedules ?? []) }} Slot Aktif
                            </span>
                        </div>
                        <span class="text-xs text-[#71717A]">Menampilkan sesi mendatang</span>
                    </div>

                    <!-- Dark Table Container -->
                    <div class="bg-[#121212] border border-[#27272A] rounded-lg overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse" id="blockedSlotsTable">
                                <thead>
                                    <tr class="border-b border-[#27272A] bg-[#141414] text-[11px] font-semibold uppercase tracking-wider text-[#71717A]">
                                        <th class="py-3 px-4">Tanggal</th>
                                        <th class="py-3 px-4">Sesi (2 Jam)</th>
                                        <th class="py-3 px-4">Alasan</th>
                                        <th class="py-3 px-4 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#27272A] text-xs" id="blockedTableBody">
                                    @forelse($blockedSchedules ?? [] as $slot)
                                        <tr class="hover:bg-[#18181B]/50 transition-colors" id="row-slot-{{ $slot['id'] }}">
                                            <td class="py-3.5 px-4">
                                                <div class="flex items-center gap-2">
                                                    <span class="material-symbols-outlined text-[16px] text-[#71717A]">calendar_month</span>
                                                    <span class="font-semibold text-white">{{ $slot['date'] }}</span>
                                                </div>
                                            </td>
                                            <td class="py-3.5 px-4">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded bg-[#1F1F23] text-[#E4E4E7] font-mono text-[11px]">
                                                    <span class="material-symbols-outlined text-[14px] text-[#EF4444]">lock</span>
                                                    {{ $slot['time_slot'] }}
                                                </span>
                                            </td>
                                            <td class="py-3.5 px-4">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-[#3D1A0E] text-[#FCA5A5] border border-[#5C2415] text-[11px]">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-[#F87171]"></span>
                                                    {{ $slot['reason'] }}
                                                </span>
                                            </td>
                                            <td class="py-3.5 px-4 text-right">
                                                <button type="button" 
                                                        onclick="removeBlockedSlot('row-slot-{{ $slot['id'] }}', '{{ $slot['time_slot'] }}')"
                                                        class="inline-flex items-center gap-1 border border-[#EF4444]/40 text-[#EF4444] hover:bg-[#271010] hover:border-[#EF4444] active:scale-95 text-xs font-semibold px-2.5 py-1 rounded-md transition-all cursor-pointer">
                                                    <span class="material-symbols-outlined text-[14px]">lock_open</span>
                                                    <span>Buka Blokir</span>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr id="emptyBlockedRow">
                                            <td colspan="4" class="py-6 text-center text-[#71717A]">Tidak ada jadwal yang sedang diblokir.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </section>
        </div>

    </div>

    @push('scripts')
    <script>
        function updateBlockCounter() {
            const rows = document.querySelectorAll('#blockedTableBody tr:not(#emptyBlockedRow)');
            const counter = document.getElementById('activeBlockCount');
            if (counter) {
                counter.innerText = `${rows.length} Slot Aktif`;
            }
        }

        function removeBlockedSlot(rowId, timeSlot) {
            const row = document.getElementById(rowId);
            if (row) {
                row.remove();
                updateBlockCounter();
                showAdminToast('Blokir Dibuka', `Slot ${timeSlot} telah dibuka kembali untuk pemesanan umum.`, true);

                const remaining = document.querySelectorAll('#blockedTableBody tr:not(#emptyBlockedRow)');
                if (remaining.length === 0) {
                    const tbody = document.getElementById('blockedTableBody');
                    tbody.innerHTML = `<tr id="emptyBlockedRow"><td colspan="4" class="py-6 text-center text-[#71717A]">Tidak ada jadwal yang sedang diblokir.</td></tr>`;
                }
            }
        }

        function handleAddBlockSlot() {
            const dateInput = document.getElementById('blockDate').value;
            const slotSelect = document.getElementById('blockSlot').value;
            const reasonInput = document.getElementById('blockReason').value.trim();

            if (!dateInput || !reasonInput) {
                showAdminToast('Form Belum Lengkap', 'Silakan pilih tanggal dan isi alasan pemblokiran.', false);
                return;
            }

            const emptyRow = document.getElementById('emptyBlockedRow');
            if (emptyRow) {
                emptyRow.remove();
            }

            const tbody = document.getElementById('blockedTableBody');
            const newId = 'row-slot-custom-' + Date.now();

            const newRow = document.createElement('tr');
            newRow.className = 'hover:bg-[#18181B]/50 transition-colors';
            newRow.id = newId;
            newRow.innerHTML = `
                <td class="py-3.5 px-4">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px] text-[#71717A]">calendar_month</span>
                        <span class="font-semibold text-white">${dateInput}</span>
                    </div>
                </td>
                <td class="py-3.5 px-4">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded bg-[#1F1F23] text-[#E4E4E7] font-mono text-[11px]">
                        <span class="material-symbols-outlined text-[14px] text-[#EF4444]">lock</span>
                        ${slotSelect}
                    </span>
                </td>
                <td class="py-3.5 px-4">
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-[#3D1A0E] text-[#FCA5A5] border border-[#5C2415] text-[11px]">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#F87171]"></span>
                        ${reasonInput}
                    </span>
                </td>
                <td class="py-3.5 px-4 text-right">
                    <button type="button" 
                            onclick="removeBlockedSlot('${newId}', '${slotSelect}')"
                            class="inline-flex items-center gap-1 border border-[#EF4444]/40 text-[#EF4444] hover:bg-[#271010] hover:border-[#EF4444] active:scale-95 text-xs font-semibold px-2.5 py-1 rounded-md transition-all cursor-pointer">
                        <span class="material-symbols-outlined text-[14px]">lock_open</span>
                        <span>Buka Blokir</span>
                    </button>
                </td>
            `;

            tbody.prepend(newRow);
            updateBlockCounter();
            showAdminToast('Slot Diblokir', `Sesi ${slotSelect} pada tanggal ${dateInput} telah diblokir.`, true);
        }
    </script>
    @endpush
</x-layouts.admin>
