@props([
    'id',
    'title' => null,
    'subtitle' => null,
    'maxWidth' => 'md', // sm, md, lg, xl
])

@php
    $widths = [
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        '2xl' => 'max-w-2xl',
    ];
    $appliedWidth = $widths[$maxWidth] ?? $widths['md'];
@endphp

<div
    id="{{ $id }}"
    class="fixed inset-0 z-50 hidden overflow-y-auto"
    aria-labelledby="{{ $id }}-title"
    role="dialog"
    aria-modal="true"
>
    <!-- Backdrop -->
    <div 
        class="fixed inset-0 bg-black/75 backdrop-blur-sm transition-opacity" 
        onclick="toggleModal('{{ $id }}')"
    ></div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-[#181818] border border-[#27272A] p-6 text-left shadow-2xl transition-all w-full {{ $appliedWidth }} my-8">
            <!-- Header -->
            <div class="flex items-center justify-between pb-4 border-b border-[#27272A]">
                <div>
                    @if($title)
                        <h3 class="text-lg font-bold text-white" id="{{ $id }}-title">{{ $title }}</h3>
                    @endif
                    @if($subtitle)
                        <p class="text-xs text-[#A1A1AA] mt-0.5">{{ $subtitle }}</p>
                    @endif
                </div>
                <button
                    type="button"
                    onclick="toggleModal('{{ $id }}')"
                    class="rounded-lg p-1 text-[#71717A] hover:text-white hover:bg-[#242424] transition-colors"
                >
                    <x-icon name="x" class="w-5 h-5" />
                </button>
            </div>

            <!-- Body -->
            <div class="py-4">
                {{ $slot }}
            </div>

            @if(isset($footer))
                <!-- Footer -->
                <div class="pt-4 border-t border-[#27272A] flex items-center justify-end gap-3">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    if (typeof window.toggleModal === 'undefined') {
        window.toggleModal = function(id) {
            const modal = document.getElementById(id);
            if (modal) {
                if (modal.classList.contains('hidden')) {
                    modal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                } else {
                    modal.classList.add('hidden');
                    document.body.style.overflow = '';
                }
            }
        };
    }
</script>

