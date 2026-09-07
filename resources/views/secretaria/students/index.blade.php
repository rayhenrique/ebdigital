<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    Cadastro de Alunos
                </h2>
                <p class="text-xs text-gray-500 mt-0.5">Gestão de matrículas e vinculação de alunos às classes.</p>
            </div>
            <a 
                href="{{ route('alunos.create') }}" 
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-indigo-600 text-white text-xs font-bold hover:bg-indigo-700 shadow-md shadow-indigo-100 min-h-[48px]"
            >
                + Novo Aluno
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
                <form method="GET" action="{{ route('alunos.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                    <div class="sm:col-span-2">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}" 
                            placeholder="Buscar por nome ou telefone..." 
                            class="w-full rounded-xl border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 min-h-[48px] px-3"
                        >
                    </div>
                    <div>
                        <select name="class_id" class="w-full rounded-xl border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 min-h-[48px] px-3">
                            <option value="">Todas as Classes</option>
                            @foreach($turmas as $c)
                                <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <select name="status" class="flex-1 rounded-xl border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 min-h-[48px] px-3">
                            <option value="">Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Ativos</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inativos</option>
                        </select>
                        <button type="submit" class="px-5 py-2 bg-gray-900 text-white rounded-xl text-xs font-bold hover:bg-gray-800 min-h-[48px]">
                            Filtrar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabela e Lista de Alunos -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <!-- Visão Mobile (Cards) -->
                <div class="block md:hidden divide-y divide-gray-100">
                    @forelse($students as $s)
                        <div class="p-4 space-y-3">
                            <div class="flex items-start justify-between gap-2">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-700 font-bold text-xs flex items-center justify-center shrink-0">
                                        {{ strtoupper(substr($s->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-900 text-base leading-tight">{{ $s->name }}</h3>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-indigo-50 text-indigo-700 mt-1">
                                            {{ $s->ebdClass?->name ?? 'Sem classe' }}
                                        </span>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold shrink-0 {{ $s->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $s->is_active ? 'Ativo' : 'Inativo' }}
                                </span>
                            </div>

                            <div class="bg-gray-50 p-3 rounded-xl text-xs flex items-center justify-between text-gray-600">
                                <div>
                                    <span class="text-gray-400 block text-[10px] uppercase font-bold">Telefone</span>
                                    <span>{{ $s->phone ?? 'Não informado' }}</span>
                                </div>
                                <div class="text-right">
                                    <span class="text-gray-400 block text-[10px] uppercase font-bold">Nascimento</span>
                                    <span>{{ $s->birth_date ? $s->birth_date->format('d/m/Y') : '—' }}</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-2 pt-1">
                                <a 
                                    href="{{ route('alunos.edit', $s) }}" 
                                    class="flex items-center justify-center px-4 py-2.5 rounded-xl border border-indigo-200 bg-indigo-50 text-indigo-700 text-xs font-bold hover:bg-indigo-100 min-h-[44px]"
                                >
                                    Editar
                                </a>
                                <form method="POST" action="{{ route('alunos.toggle', $s) }}" class="w-full">
                                    @csrf
                                    @method('PATCH')
                                    <button 
                                        type="submit" 
                                        class="w-full flex items-center justify-center px-4 py-2.5 rounded-xl text-xs font-bold min-h-[44px] {{ $s->is_active ? 'border border-amber-200 bg-amber-50 text-amber-700 hover:bg-amber-100' : 'border border-emerald-200 bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}"
                                    >
                                        {{ $s->is_active ? 'Desativar' : 'Ativar' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500 text-sm">
                            Nenhum aluno encontrado.
                        </div>
                    @endforelse
                </div>

                <!-- Visão Desktop (Tabela) -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-gray-500 uppercase text-[11px] font-bold tracking-wider">
                            <tr>
                                <th class="px-6 py-3.5">Nome do Aluno</th>
                                <th class="px-4 py-3.5">Classe</th>
                                <th class="px-4 py-3.5">Telefone</th>
                                <th class="px-4 py-3.5 text-center">Nascimento</th>
                                <th class="px-4 py-3.5 text-center">Status</th>
                                <th class="px-6 py-3.5 text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($students as $s)
                                <tr class="hover:bg-gray-50/70 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900">{{ $s->name }}</div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700">
                                            {{ $s->ebdClass?->name ?? 'Sem classe' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-xs text-gray-600">
                                        {{ $s->phone ?? '—' }}
                                    </td>
                                    <td class="px-4 py-4 text-center text-xs text-gray-600">
                                        {{ $s->birth_date ? $s->birth_date->format('d/m/Y') : '—' }}
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $s->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                                            {{ $s->is_active ? 'Ativo' : 'Inativo' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <a href="{{ route('alunos.edit', $s) }}" class="inline-block text-xs font-bold text-indigo-600 hover:text-indigo-800 p-2">
                                            Editar
                                        </a>
                                        <form method="POST" action="{{ route('alunos.toggle', $s) }}" class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-xs font-semibold {{ $s->is_active ? 'text-amber-600 hover:text-amber-800' : 'text-emerald-600 hover:text-emerald-800' }} p-2">
                                                {{ $s->is_active ? 'Desativar' : 'Ativar' }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                        Nenhum aluno encontrado.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($students->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $students->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
