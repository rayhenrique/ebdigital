@props(['active' => false, 'title' => ''])

@php
$classes = $active
    ? 'flex items-center rounded-xl font-bold bg-blue-50 text-blue-700 border border-blue-100 shadow-xs transition-all duration-200 group'
    : 'flex items-center rounded-xl font-semibold text-slate-600 hover:text-blue-700 hover:bg-slate-50 border border-transparent transition-all duration-200 group';
@endphp

<a 
    wire:navigate.hover 
    @if($title) title="{{ $title }}" @endif
    {{ $attributes->merge(['class' => $classes]) }}
    :class="(typeof sidebarCollapsed !== 'undefined' && sidebarCollapsed) ? 'gap-3 px-3.5 py-2.5 text-sm md:justify-center md:p-2.5 md:w-11 md:h-11 md:mx-auto md:gap-0' : 'gap-3 px-3.5 py-2.5 text-sm w-full'"
>
    {{ $slot }}
</a>
