@props(['active' => false])

@php
$classes = $active
    ? 'flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold bg-blue-50 text-blue-700 border border-blue-100 shadow-xs transition-all group'
    : 'flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:text-blue-700 hover:bg-slate-50 transition-all group';
@endphp

<a wire:navigate.hover {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
