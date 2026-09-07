<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <h2 class="font-bold text-lg sm:text-xl text-gray-800 leading-tight">
                Chamada: {{ $class->name }}
            </h2>
            <a href="{{ route('chamada.index') }}" class="inline-flex items-center gap-1 text-xs sm:text-sm font-semibold text-indigo-600 hover:text-indigo-800 py-1 self-start sm:self-auto min-h-[44px]">
                &larr; Voltar para Classes
            </a>
        </div>
    </x-slot>

    <div class="py-4">
        <livewire:take-attendance :class-id="$class->id" :date="$date" />
    </div>
</x-app-layout>
