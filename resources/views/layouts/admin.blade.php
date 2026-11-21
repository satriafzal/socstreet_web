@props([
    'title' => 'Admin Dashboard',
    'active' => null
])

<x-layouts.admin :title="$title" :active="$active">
    @yield('content')
</x-layouts.admin>
