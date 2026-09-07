<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-850 font-display leading-tight">
                    Gestão de Congregações
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Administração do Templo Sede e congregações filiais do campo da EBD.</p>
            </div>
            <a 
                href="{{ route('admin.congregacoes.create') }}" 
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-blue-600 text-white text-xs font-bold hover:bg-blue-700 shadow-md shadow-blue-500/20 min-h-[48px] cursor-pointer"
            >
                + Nova Congregação
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
            @if(session('error'))
                <div class="mb-4 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm font-medium flex items-center gap-2">
                    <span>⚠</span> {{ session('error') }}
                </div>
            @endif

            <!-- Filtros -->
            <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm mb-6">
                <form method="GET" action="{{ route('admin.congregacoes.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                    <div class="sm:col-span-2">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}" 
                            placeholder="Buscar congregação, dirigente ou cidade..." 
                            class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 min-h-[48px] px-3"
                        >
                    </div>
                    <div>
                        <select name="status" class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 min-h-[48px] px-3">
                            <option value="">Todos os Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Apenas Ativas</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Apenas Inativas</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 px-5 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-800 min-h-[48px] cursor-pointer">
                            Filtrar
                        </button>
                        @if(request()->hasAny(['search', 'status']))
                            <a href="{{ route('admin.congregacoes.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl text-xs font-semibold hover:bg-slate-200 flex items-center justify-center min-h-[48px]">
                                Limpar
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Listagem -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs sm:text-sm">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] font-extrabold tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="px-4 py-3">Congregação</th>
                                <th class="px-4 py-3">Pastor / Dirigente</th>
                                <th class="px-3 py-3 text-center">Turmas</th>
                                <th class="px-3 py-3 text-center">Alunos</th>
                                <th class="px-3 py-3 text-center">Professores</th>
                                <th class="px-3 py-3 text-center">Status</th>
                                <th class="px-4 py-3 text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                            @forelse($congregations as $congregation)
                                <tr class="hover:bg-slate-50/60 transition">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-slate-900">{{ $congregation->name }}</span>
                                            @if($congregation->is_headquarters)
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                                    🏛️ Templo Sede
                                                </span>
                                            @endif
                                        </div>
                                        <span class="text-xs text-slate-400 block">{{ $congregation->city }}{{ $congregation->address ? ' • ' . $congregation->address : '' }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-slate-700">
                                        {{ $congregation->pastor_dirigente ?? 'Não informado' }}
                                        @if($congregation->phone)
                                            <span class="text-[11px] text-slate-400 block">{{ $congregation->phone }}</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-3 text-center font-bold text-blue-700">
                                        {{ $congregation->classes_count }}
                                    </td>
                                    <td class="px-3 py-3 text-center font-bold text-emerald-700">
                                        {{ $congregation->students_count }}
                                    </td>
                                    <td class="px-3 py-3 text-center font-bold text-purple-700">
                                        {{ $congregation->teachers_count }}
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold {{ $congregation->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500' }}">
                                            {{ $congregation->is_active ? 'Ativa' : 'Inativa' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right space-x-2">
                                        <!-- Alternar Contexto -->
                                        <form method="POST" action="{{ route('admin.congregacoes.switch') }}" class="inline-block">
                                            @csrf
                                            <input type="hidden" name="congregation_id" value="{{ $congregation->id }}">
                                            <button 
                                                type="submit" 
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl border border-blue-200 bg-blue-50 text-blue-700 text-xs font-bold hover:bg-blue-100 transition cursor-pointer"
                                                title="Alternar contexto para visualizar esta congregação"
                                            >
                                                👁️ Visualizar
                                            </button>
                                        </form>

                                        <!-- Editar -->
                                        <a 
                                            href="{{ route('admin.congregacoes.edit', $congregation) }}" 
                                            class="inline-flex items-center px-2.5 py-1.5 rounded-xl border border-slate-200 bg-white text-slate-700 text-xs font-bold hover:bg-slate-50 transition"
                                        >
                                            Editar
                                        </a>

                                        @if(!$congregation->is_headquarters)
                                            <!-- Ativar/Desativar -->
                                            <form method="POST" action="{{ route('admin.congregacoes.toggle', $congregation) }}" class="inline-block">
                                                @csrf
                                                @method('PATCH')
                                                <button 
                                                    type="submit" 
                                                    class="inline-flex items-center px-2.5 py-1.5 rounded-xl border text-xs font-bold transition cursor-pointer {{ $congregation->is_active ? 'border-amber-200 bg-amber-50 text-amber-700 hover:bg-amber-100' : 'border-emerald-200 bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}"
                                                    onclick="return confirm('Deseja realmente alterar o status desta congregação?')"
                                                >
                                                    {{ $congregation->is_active ? 'Desativar' : 'Ativar' }}
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-slate-400 text-xs sm:text-sm">
                                        Nenhuma congregação encontrada com os filtros informados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($congregations->hasPages())
                    <div class="p-4 border-t border-slate-100">
                        {{ $congregations->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
