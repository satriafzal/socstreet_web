<x-layouts.app title="Socstreet Minisoccer - Booking Lapangan Minisoccer Premium">
    <x-navbar />

    <!-- Hero Section with Minisoccer Pitch Background -->
    <section class="relative pt-12 pb-20 md:pt-20 md:pb-28 overflow-hidden">
        <!-- Pitch Background Image Layer -->
        <div class="absolute inset-0 z-0">
            <img
                src="{{ asset('images/hero-pitch.jpg') }}"
                alt="Socstreet Minisoccer Pitch"
                class="w-full h-full object-cover object-center brightness-90 contrast-105"
                onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1574629810360-7efbbe195018?auto=format&fit=crop&w=2000&q=80';"
            />
            <!-- Gradient Overlay: keeps text readable while showing the field clearly -->
            <div class="absolute inset-0 bg-gradient-to-b from-[#121212]/75 via-[#121212]/80 to-[#121212]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_transparent_20%,_#121212_95%)]"></div>
            <!-- Green Accent Light Glow -->
            <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[350px] bg-[#16A34A]/20 blur-[140px] rounded-full pointer-events-none animate-pulse-slow"></div>
            <!-- Bottom smooth fade -->
            <div class="absolute inset-x-0 bottom-0 h-28 bg-gradient-to-t from-[#121212] to-transparent"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto space-y-6">
                <!-- Pill Badge (Staggered Animation 1) -->
                <div class="animate-fade-in-up [animation-delay:100ms] inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#181818]/90 backdrop-blur-md border border-[#27272A] shadow-lg hover:border-[#16A34A]/50 transition-colors">
                    <span class="w-2 h-2 rounded-full bg-[#16A34A] animate-pulse"></span>
                    <span class="text-xs font-semibold text-[#A1A1AA] uppercase tracking-wider">Single Arena Minisoccer Platform</span>
                </div>

                <!-- Display / Hero Headline (Staggered Animation 2) -->
                <h1 class="animate-fade-in-up [animation-delay:200ms] text-4xl sm:text-5xl lg:text-[48px] font-bold text-white tracking-[-0.02em] leading-[1.15] drop-shadow-md">
                    Sensasi Bermain Minisoccer <br class="hidden sm:inline" />
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-white via-white to-[#22C55E]">Kelas Kompetisi Resmi.</span>
                </h1>

                <!-- Body Regular (Staggered Animation 3) -->
                <p class="animate-fade-in-up [animation-delay:350ms] text-base sm:text-lg text-[#A1A1AA] leading-relaxed max-w-2xl mx-auto drop-shadow-sm">
                    Rasakan kualitas rumput sintetis monofilament berstandar WMF, pencahayaan 500 Lux anti-glare, dan kemudahan booking jadwal secara langsung tanpa antre.
                </p>

                <!-- CTA Actions (Staggered Animation 4) -->
                <div class="animate-fade-in-up [animation-delay:500ms] flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4 pt-4">
                    <x-button variant="primary" size="lg" href="{{ route('booking.schedule') }}" class="w-full sm:w-auto shadow-xl shadow-[#16A34A]/30 hover:scale-[1.03] active:scale-[0.98] transition-all group">
                        <span>Cek Jadwal Lapangan</span>
                        <x-icon name="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
                    </x-button>
                    <x-button variant="secondary" size="lg" href="#arena" class="w-full sm:w-auto bg-[#181818]/80 backdrop-blur-md hover:bg-[#242424] hover:border-[#3F3F46] hover:scale-[1.02] active:scale-[0.98] transition-all">
                        <span>Lihat Spesifikasi Arena</span>
                    </x-button>
                </div>

                <!-- Fast Stats Strip with Frosted Glass (Staggered Animation 5) -->
                <div class="animate-fade-in-up [animation-delay:650ms] grid grid-cols-2 md:grid-cols-4 gap-4 pt-8 max-w-4xl mx-auto text-left">
                    <div class="p-4 rounded-2xl bg-[#181818]/85 backdrop-blur-md border border-[#27272A] hover:-translate-y-1 hover:border-[#16A34A]/40 transition-all duration-300">
                        <p class="text-2xl font-bold text-white tracking-tight">1 Arena</p>
                        <p class="text-xs text-[#71717A] mt-0.5">Eksklusif & Terawat</p>
                    </div>
                    <div class="p-4 rounded-2xl bg-[#181818]/85 backdrop-blur-md border border-[#27272A] hover:-translate-y-1 hover:border-[#16A34A]/40 transition-all duration-300">
                        <p class="text-2xl font-bold text-[#22C55E] tracking-tight">500 Lux</p>
                        <p class="text-xs text-[#71717A] mt-0.5">Pencahayaan LED Match</p>
                    </div>
                    <div class="p-4 rounded-2xl bg-[#181818]/85 backdrop-blur-md border border-[#27272A] hover:-translate-y-1 hover:border-[#16A34A]/40 transition-all duration-300">
                        <p class="text-2xl font-bold text-white tracking-tight">FIFA Std</p>
                        <p class="text-xs text-[#71717A] mt-0.5">Rumput Monofilament</p>
                    </div>
                    <div class="p-4 rounded-2xl bg-[#181818]/85 backdrop-blur-md border border-[#27272A] hover:-translate-y-1 hover:border-[#16A34A]/40 transition-all duration-300">
                        <p class="text-2xl font-bold text-white tracking-tight">DP 50%</p>
                        <p class="text-xs text-[#71717A] mt-0.5">Fleksibel & Aman</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Arena Showcase Section (Desain 4.C.1) -->
    <section id="arena" class="py-16 md:py-24 border-t border-[#27272A]/70 bg-[#121212]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Heading (Reveal Animation) -->
            <div class="reveal-item flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="w-1.5 h-4 bg-[#16A34A] rounded-full"></span>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-[#16A34A]">Fokus Satu Arena Unggulan</h3>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">Socstreet Main Arena</h2>
                    <p class="text-sm text-[#A1A1AA] mt-1">Satu-satunya lapangan yang dikelola khusus untuk menjaga kualitas permukaan rumput dan kenyamanan pertandingan.</p>
                </div>
                <div class="hidden sm:flex items-center gap-2">
                    <x-badge variant="success" size="md">
                        <span class="w-2 h-2 rounded-full bg-[#22C55E] animate-pulse"></span>
                        Status: Buka Hari Ini
                    </x-badge>
                </div>
            </div>

            <!-- Arena Showcase Card (Reveal Animation + Hover Elevation) -->
            <div class="reveal-item rounded-2xl bg-[#1E1E1E] border border-[#27272A] overflow-hidden shadow-2xl hover:border-[#16A34A]/50 hover:shadow-[#16A34A]/10 transition-all duration-500">
                <div class="grid grid-cols-1 lg:grid-cols-12">
                    <!-- Visual / Image Area (16:9 on responsive) -->
                    <div class="lg:col-span-7 relative min-h-[300px] lg:min-h-[460px] bg-[#121212] flex items-center justify-center overflow-hidden border-b lg:border-b-0 lg:border-r border-[#27272A] group">
                        <!-- Synthetic Turf Photo Layer with Zoom effect -->
                        <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-105 opacity-40 mix-blend-luminosity"
                             style="background-image: url('https://images.unsplash.com/photo-1556056504-5c7696c4c28d?auto=format&fit=crop&w=1200&q=80');"></div>
                        <div class="absolute inset-0 bg-gradient-to-t from-[#1E1E1E] via-[#121212]/60 to-transparent"></div>
                        <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-[#16A34A]/25 via-transparent to-transparent"></div>
                        
                        <!-- Simulated Football Pitch Graphic -->
                        <div class="w-[85%] h-[80%] border-2 border-white/25 rounded-lg relative flex items-center justify-center p-4 backdrop-blur-[1px] group-hover:border-white/40 transition-colors">
                            <!-- Center line & circle -->
                            <div class="absolute inset-y-0 left-1/2 w-0.5 bg-white/20 -translate-x-1/2"></div>
                            <div class="w-24 h-24 rounded-full border-2 border-white/20 flex items-center justify-center">
                                <div class="w-2 h-2 rounded-full bg-[#22C55E]"></div>
                            </div>
                            <!-- Penalty boxes -->
                            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-16 h-36 border-2 border-l-0 border-white/20"></div>
                            <div class="absolute right-0 top-1/2 -translate-y-1/2 w-16 h-36 border-2 border-r-0 border-white/20"></div>
                            
                            <!-- Pitch Text Overlay -->
                            <div class="absolute bottom-4 left-4 z-10">
                                <span class="text-xs uppercase tracking-widest font-mono text-white/50">Synthetic Turf • FIFA Certified</span>
                            </div>
                        </div>

                        <!-- Top-Left Pill Badge: Available Today -->
                        <div class="absolute top-4 left-4 z-20">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-[#16A34A] text-white shadow-lg">
                                <span class="w-2 h-2 rounded-full bg-white animate-ping"></span>
                                Available Today
                            </span>
                        </div>

                        <!-- Top-Right Favorite Button (Heart) with Micro-interaction -->
                        <div class="absolute top-4 right-4 z-20">
                            <button type="button" class="w-10 h-10 rounded-xl bg-black/60 backdrop-blur-md border border-white/10 flex items-center justify-center text-white/80 hover:text-[#EF4444] hover:scale-110 active:scale-95 transition-all" title="Simpan Arena">
                                <x-icon name="heart" class="w-5 h-5" />
                            </button>
                        </div>
                    </div>

                    <!-- Body & Details Area -->
                    <div class="lg:col-span-5 p-6 sm:p-8 flex flex-col justify-between space-y-6">
                        <div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-semibold text-[#16A34A] tracking-wider uppercase">Spesifikasi Lapangan</span>
                                <span class="text-xs text-[#71717A]">Kode: SCTR-01</span>
                            </div>
                            <h3 class="text-2xl font-bold text-white mt-1">Socstreet Main Arena</h3>
                            <p class="text-xs text-[#A1A1AA] mt-1.5 leading-relaxed">
                                Arena utama berstandar nasional yang dirancang khusus untuk permainan 7 vs 7 atau 8 vs 8 dengan sirkulasi udara optimal dan pencahayaan merata.
                            </p>

                            <!-- Key Specs Grid -->
                            <div class="grid grid-cols-2 gap-3 mt-6">
                                <div class="p-3 rounded-xl bg-[#181818] border border-[#27272A] hover:border-[#3F3F46] transition-colors">
                                    <p class="text-[11px] text-[#71717A]">Dimensi Lapangan</p>
                                    <p class="text-xs font-bold text-white mt-0.5">42 × 25 Meter</p>
                                </div>
                                <div class="p-3 rounded-xl bg-[#181818] border border-[#27272A] hover:border-[#3F3F46] transition-colors">
                                    <p class="text-[11px] text-[#71717A]">Jenis Rumput</p>
                                    <p class="text-xs font-bold text-white mt-0.5">Monofilament 50mm</p>
                                </div>
                                <div class="p-3 rounded-xl bg-[#181818] border border-[#27272A] hover:border-[#3F3F46] transition-colors">
                                    <p class="text-[11px] text-[#71717A]">Pencahayaan</p>
                                    <p class="text-xs font-bold text-[#22C55E] mt-0.5">500 Lux Anti-Glare</p>
                                </div>
                                <div class="p-3 rounded-xl bg-[#181818] border border-[#27272A] hover:border-[#3F3F46] transition-colors">
                                    <p class="text-[11px] text-[#71717A]">Kapasitas Pemain</p>
                                    <p class="text-xs font-bold text-white mt-0.5">7 vs 7 (Hingga 16 org)</p>
                                </div>
                            </div>

                            <!-- Feature Checkmarks -->
                            <div class="mt-6 space-y-2">
                                <p class="text-xs font-semibold text-[#A1A1AA]">Termasuk Fasilitas Arena:</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs text-[#A1A1AA]">
                                    <div class="flex items-center gap-2">
                                        <x-icon name="check" class="w-4 h-4 text-[#16A34A] shrink-0" />
                                        <span>Bola Pertandingan (2x)</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <x-icon name="check" class="w-4 h-4 text-[#16A34A] shrink-0" />
                                        <span>Rompi Latihan (2 Warna)</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <x-icon name="check" class="w-4 h-4 text-[#16A34A] shrink-0" />
                                        <span>Wasit Berlisensi (Opsional)</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <x-icon name="check" class="w-4 h-4 text-[#16A34A] shrink-0" />
                                        <span>Papan Skor Digital</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card Footer / Bottom CTA Area (Price on left, Cek Jadwal on right) -->
                        <div class="pt-6 border-t border-[#27272A] flex items-center justify-between gap-4">
                            <div>
                                <p class="text-[11px] text-[#71717A] uppercase font-semibold">Mulai Dari</p>
                                <div class="flex items-baseline gap-1">
                                    <span class="text-2xl font-bold text-white">Rp 200.000</span>
                                    <span class="text-xs text-[#71717A]">/ jam</span>
                                </div>
                            </div>
                            <x-button variant="primary" size="lg" href="{{ route('booking.schedule') }}" class="shadow-lg shadow-[#16A34A]/25 hover:scale-[1.03] active:scale-[0.98] transition-all group">
                                <span>Cek Jadwal</span>
                                <x-icon name="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
                            </x-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Facilities Section (Desain 4.C.2) -->
    <section id="facilities" class="py-16 md:py-24 border-t border-[#27272A]/70 bg-[#121212]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="reveal-item text-center max-w-2xl mx-auto mb-12">
                <span class="text-xs font-bold uppercase tracking-wider text-[#16A34A]">Kenyamanan Maksimal</span>
                <h2 class="text-2xl sm:text-3xl font-bold text-white tracking-tight mt-1">Fasilitas Penunjang Lengkap</h2>
                <p class="text-sm text-[#A1A1AA] mt-2">Dukungan sarana modern untuk memastikan Anda dan rekan tim mendapatkan pengalaman bertanding terbaik.</p>
            </div>

            <!-- Facility Cards (Reveal Animation with Staggered Transition + Hover Lift) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($facilities as $index => $item)
                    <div 
                        class="reveal-item rounded-xl bg-[#181818] border border-[#27272A] p-6 hover:-translate-y-2 hover:bg-[#242424] hover:border-[#16A34A]/40 hover:shadow-xl hover:shadow-[#16A34A]/5 transition-all duration-300 group"
                        style="transition-delay: {{ $index * 80 }}ms;"
                    >
                        <div class="w-[44px] h-[44px] rounded-xl bg-[#242424] border border-[#27272A] flex items-center justify-center text-white group-hover:scale-110 group-hover:bg-[#16A34A]/20 group-hover:text-[#22C55E] group-hover:border-[#16A34A]/40 transition-all duration-300 mb-4">
                            <x-icon :name="$item['icon']" class="w-5 h-5" />
                        </div>
                        <h3 class="text-base font-semibold text-white tracking-tight group-hover:text-[#22C55E] transition-colors">{{ $item['title'] }}</h3>
                        <p class="text-xs text-[#A1A1AA] mt-2 leading-relaxed">{{ $item['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- How to Book ("Cara Booking") -->
    <section id="how-to-book" class="py-16 md:py-24 border-t border-[#27272A]/70 bg-[#0D0D0D]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="reveal-item text-center max-w-2xl mx-auto mb-12">
                <span class="text-xs font-bold uppercase tracking-wider text-[#16A34A]">Proses Pemesanan Mudah</span>
                <h2 class="text-2xl sm:text-3xl font-bold text-white tracking-tight mt-1">4 Langkah Mudah Booking</h2>
                <p class="text-sm text-[#A1A1AA] mt-2">Tanpa ribet konfirmasi chat manual, sistem kami menjamin slot waktu Anda terkunci secara otomatis.</p>
            </div>

            <!-- Steps Grid (Reveal Animation) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($steps as $index => $st)
                    <div 
                        class="reveal-item rounded-xl bg-[#181818] border border-[#27272A] p-6 relative hover:-translate-y-1.5 hover:border-[#3F3F46] transition-all duration-300 group"
                        style="transition-delay: {{ $index * 100 }}ms;"
                    >
                        <span class="text-3xl font-extrabold text-[#27272A] group-hover:text-[#16A34A] transition-colors block mb-3 font-mono">
                            {{ $st['step'] }}
                        </span>
                        <h4 class="text-base font-semibold text-white tracking-tight">{{ $st['title'] }}</h4>
                        <p class="text-xs text-[#71717A] mt-2 leading-relaxed">{{ $st['desc'] }}</p>
                    </div>
                @endforeach
            </div>

            <!-- Booking Now CTA Banner (Reveal Animation) -->
            <div class="reveal-item mt-14 p-8 sm:p-10 rounded-2xl bg-gradient-to-r from-[#181818] via-[#1E1E1E] to-[#16A34A]/15 border border-[#27272A] hover:border-[#16A34A]/40 transition-all duration-300 flex flex-col md:flex-row items-center justify-between gap-6 shadow-2xl">
                <div class="space-y-1 text-center md:text-left">
                    <h3 class="text-xl sm:text-2xl font-bold text-white">Siap Bertanding di Socstreet Main Arena?</h3>
                    <p class="text-xs sm:text-sm text-[#A1A1AA]">Pilih jam favorit tim Anda sebelum diambil pemain lain.</p>
                </div>
                <x-button variant="primary" size="lg" href="{{ route('booking.schedule') }}" class="shadow-xl shadow-[#16A34A]/30 hover:scale-[1.03] active:scale-[0.98] transition-all shrink-0 group">
                    <span>Pesan Jadwal Sekarang</span>
                    <x-icon name="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
                </x-button>
            </div>
        </div>
    </section>

    <!-- Scroll Reveal Observer Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-revealed');
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.12,
                rootMargin: '0px 0px -40px 0px'
            });

            document.querySelectorAll('.reveal-item').forEach(el => observer.observe(el));
        });
    </script>

    <x-footer />
</x-layouts.app>
