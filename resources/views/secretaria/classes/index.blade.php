<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    Classes e Turmas da EBD
                </h2>
                <p class="text-xs text-gray-500 mt-0.5">Gerenciamento de turmas e atribuição de professores.</p>
            </div>
            <a 
                href="{{ route('classes.create') }}" 
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-indigo-600 text-white text-xs font-bold hover:bg-indigo-700 shadow-md shadow-indigo-100 min-h-[48px]"
            >
                + Nova Classe
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center gap-2">
                    <span>✓</span> {{ session('success') }}
                </div>
            @endif

            <!-- Filtros -->
            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm mb-6">
                <form method="GET" action="{{ route('classes.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="sm:col-span-2">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}" 
                            placeholder="Buscar classe por nome..." 
                            class="w-full rounded-xl border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 min-h-[48px] px-3"
                        >
                    </div>
                    <div class="flex gap-2">
                        <select name="status" class="flex-1 rounded-xl border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 min-h-[48px] px-3">
                            <option value="">Todos os Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Apenas Ativas</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inativas</option>
                        </select>
                        <button type="submit" class="px-5 py-2 bg-gray-900 text-white rounded-xl text-xs font-bold hover:bg-gray-800 min-h-[48px]">
                            Filtrar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabela e Lista de Classes -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <!-- Visão Mobile (Cards) -->
                <div class="block md:hidden divide-y divide-gray-100">
                    @forelse($turmas as $c)
                        <div class="p-4 space-y-3">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <h3 class="font-bold text-gray-900 text-base leading-tight">{{ $c->name }}</h3>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $c->description ?? 'Sem descrição' }}</p>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold shrink-0 {{ $c->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $c->is_active ? 'Ativa' : 'Inativa' }}
                                </span>
                            </div>

                            <div class="bg-gray-50 p-3 rounded-xl text-xs space-y-1">
                                <div class="text-gray-600">
                                    <span class="font-semibold text-gray-700">Professores:</span>
                                    @if($c->teachers->isNotEmpty())
                                        {{ $c->teachers->pluck('name')->join(', ') }}
                                    @else
                                        <span class="italic text-amber-600">Nenhum professor</span>
                                    @endif
                                </div>
                                <div class="text-gray-600">
                                    <span class="font-semibold text-gray-700">Alunos Ativos:</span>
                                    <span class="font-bold text-gray-900">{{ $c->students_count }}</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-2 pt-1">
                                <a 
                                    href="{{ route('classes.edit', $c) }}" 
                                    class="flex items-center justify-center px-4 py-2.5 rounded-xl border border-indigo-200 bg-indigo-50 text-indigo-700 text-xs font-bold hover:bg-indigo-100 min-h-[44px]"
                                >
                                    Editar
                                </a>
                                <form method="POST" action="{{ route('classes.toggle', $c) }}" class="w-full">
                                    @csrf
                                    @method('PATCH')
                                    <button 
                                        type="submit" 
                                        class="w-full flex items-center justify-center px-4 py-2.5 rounded-xl text-xs font-bold min-h-[44px] {{ $c->is_active ? 'border border-amber-200 bg-amber-50 text-amber-700 hover:bg-amber-100' : 'border border-emerald-200 bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}"
                                    >
                                        {{ $c->is_active ? 'Desativar' : 'Ativar' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500 text-sm">
                            Nenhuma classe encontrada.
                        </div>
                    @endforelse
                </div>

                <!-- Visão Desktop (Tabela) -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-gray-500 uppercase text-[11px] font-bold tracking-wider">
                            <tr>
                                <th class="px-6 py-3.5">Nome / Descrição</th>
                                <th class="px-4 py-3.5">Professores Vinculados</th>
                                <th class="px-4 py-3.5 text-center">Alunos Ativos</th>
                                <th class="px-4 py-3.5 text-center">Status</th>
                                <th class="px-6 py-3.5 text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($turmas as $c)
                                <tr class="hover:bg-gray-50/70 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900">{{ $c->name }}</div>
                                        <div class="text-xs text-gray-400">{{ $c->description ?? 'Sem descrição' }}</div>
                                    </td>
                                    <td class="px-4 py-4">
                                        @if($c->teachers->isNotEmpty())
                                            <div class="text-xs font-medium text-gray-700">
                                                {{ $c->teachers->pluck('name')->join(', ') }}
                                            </div>
                                        @else
                                            <span class="text-xs text-amber-600 italic">Nenhum professor</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-center font-bold text-gray-700">
                                        {{ $c->students_count }}
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $c->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                                            {{ $c->is_active ? 'Ativa' : 'Inativa' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <a href="{{ route('classes.edit', $c) }}" class="inline-block text-xs font-bold text-indigo-600 hover:text-indigo-800 p-2">
                                            Editar
                                        </a>
                                        <form method="POST" action="{{ route('classes.toggle', $c) }}" class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-xs font-semibold {{ $c->is_active ? 'text-amber-600 hover:text-amber-800' : 'text-emerald-600 hover:text-emerald-800' }} p-2">
                                                {{ $c->is_active ? 'Desativar' : 'Ativar' }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                        Nenhuma classe encontrada.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($turmas->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $turmas->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
