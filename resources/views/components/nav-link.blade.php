@props(['active'])

@php
$classes = ($active ?? false)
            ? 'bg-blue-50 text-blue-700 font-semibold px-3 py-2 rounded-xl inline-flex items-center gap-2 text-sm transition-all shadow-xs'
            : 'text-slate-600 hover:text-blue-600 hover:bg-slate-50 font-medium px-3 py-2 rounded-xl inline-flex items-center gap-2 text-sm transition-all';
@endphp

<a wire:navigate.hover {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
