@props([
    'active' => null
])

@php
    $currentRoute = request()->route() ? request()->route()->getName() : '';
    $activeItem = $active ?? $currentRoute;

    $menus = [
        [
            'name' => 'Dashboard',
            'route' => 'admin.dashboard',
            'icon' => 'grid_view',
            'match' => 'admin.dashboard',
        ],
        [
            'name' => 'Bookings',
            'route' => 'admin.bookings',
            'icon' => 'confirmation_number',
            'match' => 'admin.bookings',
        ],
        [
            'name' => 'Schedule & Pricing',
            'route' => 'admin.schedule',
            'icon' => 'calendar_month',
            'match' => 'admin.schedule',
        ],
        [
            'name' => 'Users',
            'route' => 'admin.users',
            'icon' => 'group',
            'match' => 'admin.users',
        ],
        [
            'name' => 'Settings',
            'route' => 'admin.settings',
            'icon' => 'settings',
            'match' => 'admin.settings',
        ],
    ];
@endphp

<!-- Mobile Sidebar Backdrop Overlay -->
<div id="admin-sidebar-backdrop" 
     class="fixed inset-0 z-40 bg-black/80 backdrop-blur-sm hidden lg:hidden transition-opacity" 
     onclick="toggleAdminSidebar()">
</div>

<!-- Admin Sidebar (Fixed 240px / 256px) -->
<aside id="admin-sidebar" 
       class="fixed inset-y-0 left-0 z-50 w-64 bg-[#0d0d0d] border-r border-[#27272a] flex flex-col justify-between transition-transform duration-300 -translate-x-full lg:translate-x-0 select-none">
    
    <div class="flex flex-col">
        <!-- Brand / Header -->
        <div class="h-16 px-6 flex items-center justify-between border-b border-[#27272a]">
            <a href="{{ Route::has('admin.dashboard') ? route('admin.dashboard') : '#' }}" class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-xl bg-[#16a34a]/20 border border-[#16a34a]/40 flex items-center justify-center text-[#22c55e]">
                    <span class="material-symbols-outlined text-[19px]">sports_soccer</span>
                </div>
                <div class="flex items-center gap-1.5 font-bold tracking-tight text-white text-base">
                    <span>Socstreet</span>
                    <span class="text-[#a1a1aa] font-medium text-sm">Admin</span>
                    <span class="w-2 h-2 rounded-full bg-[#16a34a] shadow-[0_0_8px_#16a34a] inline-block ml-0.5"></span>
                </div>
            </a>
            
            <!-- Close button for mobile -->
            <button type="button" 
                    class="lg:hidden p-1.5 rounded-lg text-[#71717a] hover:text-white hover:bg-[#1f1f23] transition-colors" 
                    onclick="toggleAdminSidebar()">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <!-- Single Arena Operational Pill -->
        <div class="mx-3.5 my-3.5 px-3 py-2.5 rounded-xl bg-[#141414] border border-[#27272a] flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-[#22c55e] animate-pulse"></span>
                <span class="text-[11px] font-semibold text-[#a1a1aa] uppercase tracking-wider">Main Arena</span>
            </div>
            <span class="text-[10px] px-2 py-0.5 rounded-full bg-[#16a34a]/15 text-[#4ade80] font-mono font-medium">Single Field</span>
        </div>

        <!-- Navigation Menu Links -->
        <nav class="flex flex-col gap-1 px-3">
            @foreach($menus as $menu)
                @php
                    $isActive = $activeItem === $menu['match'] || str_starts_with($currentRoute, $menu['match']);
                    $href = Route::has($menu['route']) ? route($menu['route']) : '#';
                @endphp
                <a href="{{ $href }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-xs font-semibold transition-all duration-150 {{ $isActive ? 'bg-[#16a34a] text-white shadow-md shadow-[#16a34a]/25' : 'text-[#a1a1aa] hover:bg-[#18181b] hover:text-white' }}">
                    <span class="material-symbols-outlined text-[20px] shrink-0">{{ $menu['icon'] }}</span>
                    <span class="tracking-wide">{{ $menu['name'] }}</span>
                </a>
            @endforeach
        </nav>
    </div>

    <!-- Bottom Sticky Area (Back to User App & Logout) -->
    <div class="p-3 border-t border-[#27272a] bg-[#0d0d0d] space-y-1.5">
        <!-- Link to Public / Member Arena -->
        <a href="{{ Route::has('home') ? route('home') : '/' }}" 
           class="flex items-center gap-3 px-3.5 py-2 rounded-lg text-xs font-medium text-[#71717a] hover:text-[#e4e4e7] hover:bg-[#18181b] transition-colors">
            <span class="material-symbols-outlined text-[18px]">open_in_new</span>
            <span>Lihat Lapangan Publik</span>
        </a>

        <!-- Admin Logout Trigger Button -->
        <button type="button" 
            onclick="toggleModal('admin-logout-modal')"
            class="w-full flex items-center gap-3 px-3.5 py-2 rounded-lg text-xs font-semibold text-[#ef4444] hover:bg-[#271010] hover:text-[#f87171] transition-colors">
            <span class="material-symbols-outlined text-[19px]">logout</span>
            <span>Logout</span>
        </button>
    </div>
</aside>
