@props([
    'title' => 'Admin Dashboard'
])

<header class="fixed top-0 left-0 lg:left-64 right-0 h-16 bg-[#121212]/95 backdrop-blur-md border-b border-[#27272a] z-40 flex items-center justify-between px-4 sm:px-6 lg:px-8 transition-all">
    <!-- Left: Mobile Toggle & Page Title -->
    <div class="flex items-center gap-3">
        <!-- Mobile Sidebar Toggle -->
        <button type="button" 
                class="lg:hidden p-2 rounded-lg bg-[#18181b] text-white border border-[#27272a] hover:bg-[#27272a] transition-colors"
                onclick="toggleAdminSidebar()" 
                aria-label="Toggle navigation menu">
            <span class="material-symbols-outlined text-[20px] block">menu</span>
        </button>

        <!-- Title -->
        <div class="flex items-center gap-2">
            <h1 class="text-base sm:text-lg font-bold text-white tracking-tight">{{ $title }}</h1>
        </div>
    </div>

    <!-- Right: Admin Status & Profile Badge -->
    <div class="flex items-center gap-3">
        <!-- Live Admin Status Badge -->
        <div class="hidden sm:flex items-center gap-2 px-3 py-1 rounded-full bg-[#1c1c1f] border border-[#27272a]">
            <span class="w-2 h-2 rounded-full bg-[#16a34a] inline-block shadow-[0_0_6px_#16a34a] animate-pulse"></span>
            <span class="text-xs font-semibold text-[#e4e4e7] font-mono">admin_soc</span>
            <span class="text-[10px] text-[#71717a] font-medium border-l border-[#27272a] pl-2 uppercase">Root</span>
        </div>

        <!-- Avatar Circle -->
        <a href="{{ Route::has('admin.settings') ? route('admin.settings') : '#' }}" 
           class="w-9 h-9 rounded-full bg-[#16a34a] flex items-center justify-center text-white hover:ring-2 hover:ring-[#22c55e] transition-all cursor-pointer shadow-md shadow-[#16a34a]/20" 
           title="Pengaturan Profil Admin">
            <span class="material-symbols-outlined text-[20px]">person</span>
        </a>
    </div>
</header>
