<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-850 font-display leading-tight">
                    Professores da EBD
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    @if($currentCongregation)
                        Visualizando corpo docente de: <strong class="text-slate-700">{{ $currentCongregation->name }}</strong>
                    @else
                        Visualizando professores de todas as congregações
                    @endif
                </p>
            </div>
            @if($isAllCongregations)
                <div class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-slate-100 text-slate-400 text-xs font-bold border border-slate-200 cursor-not-allowed select-none" title="Selecione uma congregação no menu para cadastrar um professor">
                    <span>🔒</span>
                    <span>+ Novo Professor</span>
                    <span class="text-[10px] bg-amber-100 text-amber-800 px-2 py-0.5 rounded-full font-bold">Selecione uma Congregação</span>
                </div>
            @else
                <a 
                    href="{{ route('professores.create') }}" 
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-blue-600 text-white text-xs font-bold hover:bg-blue-700 shadow-md shadow-blue-500/20 min-h-[48px] cursor-pointer"
                >
                    + Novo Professor
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-6" x-data="{
        resetModalOpen: false,
        teacherName: '',
        actionUrl: '',
        newPassword: '',
        openResetModal(name, url) {
            this.teacherName = name;
            this.actionUrl = url;
            this.newPassword = 'ebd' + Math.floor(1000 + Math.random() * 9000);
            this.resetModalOpen = true;
        }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($isAllCongregations)
                <div class="mb-6 p-4 rounded-2xl bg-amber-50/90 border border-amber-200/80 flex items-start sm:items-center justify-between gap-4 shadow-xs">
                    <div class="flex items-start sm:items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center text-xl shrink-0">
                            🌐
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-amber-950 uppercase tracking-wider">Modo Geral (Somente Leitura)</h4>
                            <p class="text-xs text-amber-800 mt-0.5">
                                Você está visualizando o corpo docente de <strong>Todas as Congregações</strong>. Para cadastrar, editar, alterar status ou redefinir senha, selecione uma congregação específica no seletor do menu lateral.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Filtros -->
            <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm mb-6">
                <form method="GET" action="{{ route('professores.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                    <div class="sm:col-span-2">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}" 
                            placeholder="Buscar professor por nome ou e-mail..." 
                            class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 min-h-[48px] px-3"
                        >
                    </div>
                    <div>
                        <select name="status" class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 min-h-[48px] px-3">
                            <option value="">Todos os Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Apenas Ativos</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Apenas Inativos</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 px-5 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-800 min-h-[48px] cursor-pointer">
                            Filtrar
                        </button>
                        @if(request()->hasAny(['search', 'status']))
                            <a href="{{ route('professores.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl text-xs font-semibold hover:bg-slate-200 flex items-center justify-center min-h-[48px]">
                                Limpar
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Listagem -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <!-- Visão Mobile (Cards) -->
                <div class="block md:hidden divide-y divide-slate-100">
                    @forelse($teachers as $teacher)
                        <div class="p-4 space-y-3">
                            <div class="flex items-start justify-between gap-2">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-700 font-bold flex items-center justify-center text-xs shrink-0">
                                        {{ strtoupper(substr($teacher->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <h3 class="font-bold text-slate-900 text-base leading-tight">{{ $teacher->name }}</h3>
                                            @if($isAllCongregations)
                                                <span class="text-[10px] font-bold text-blue-700 bg-blue-50 border border-blue-200/60 px-2 py-0.5 rounded-md">
                                                    ⛪ {{ $teacher->congregation?->name ?? 'Templo Sede' }}
                                                </span>
                                            @endif
                                        </div>
                                        <span class="text-xs text-slate-400 block mt-0.5">{{ $teacher->email }}</span>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold shrink-0 {{ $teacher->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $teacher->is_active ? 'Ativo' : 'Inativo' }}
                                </span>
                            </div>

                            <div class="bg-slate-50 p-3 rounded-xl text-xs space-y-1">
                                <span class="font-semibold text-slate-700 block mb-1">Turmas Atribuídas:</span>
                                <div class="flex flex-wrap gap-1">
                                    @forelse($teacher->teachingClasses as $class)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-blue-50 text-blue-700 border border-blue-200/60">
                                            {{ $class->name }}
                                        </span>
                                    @empty
                                        <span class="text-xs text-slate-400 italic">Nenhuma turma atribuída</span>
                                    @endforelse
                                </div>
                            </div>

                            @if($isAllCongregations)
                                <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200/60 text-center text-xs font-semibold text-slate-400 flex items-center justify-center gap-1.5">
                                    <span>🔒</span> Somente leitura no modo geral
                                </div>
                            @else
                                <div class="grid grid-cols-3 gap-2 pt-1">
                                    <button 
                                        type="button" 
                                        @click="openResetModal('{{ $teacher->name }}', '{{ route('professores.reset-password', $teacher) }}')"
                                        class="flex items-center justify-center px-2 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-700 text-xs font-bold hover:bg-slate-50 min-h-[44px] cursor-pointer"
                                    >
                                        🔑 Senha
                                    </button>
                                    <a 
                                        href="{{ route('professores.edit', $teacher) }}" 
                                        class="flex items-center justify-center px-2 py-2.5 rounded-xl border border-blue-200 bg-blue-50 text-blue-700 text-xs font-bold hover:bg-blue-100 min-h-[44px]"
                                    >
                                        Editar
                                    </a>
                                    <form method="POST" action="{{ route('professores.toggle', $teacher) }}" class="w-full">
                                        @csrf
                                        @method('PATCH')
                                        <button 
                                            type="submit" 
                                            class="w-full flex items-center justify-center px-2 py-2.5 rounded-xl text-xs font-bold min-h-[44px] cursor-pointer {{ $teacher->is_active ? 'border border-amber-200 bg-amber-50 text-amber-700 hover:bg-amber-100' : 'border border-emerald-200 bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}"
                                        >
                                            {{ $teacher->is_active ? 'Desativar' : 'Ativar' }}
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="p-8 text-center text-slate-400 text-sm">
                            Nenhum professor encontrado.
                        </div>
                    @endforelse
                </div>

                <!-- Visão Desktop (Tabela) -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left text-xs sm:text-sm">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] font-extrabold tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="px-4 py-3">Professor</th>
                                <th class="px-4 py-3">Turmas Atribuídas</th>
                                @if($isAllCongregations)
                                    <th class="px-4 py-3">Congregação</th>
                                @endif
                                <th class="px-3 py-3 text-center">Status</th>
                                <th class="px-4 py-3 text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                            @forelse($teachers as $teacher)
                                <tr class="hover:bg-slate-50/60 transition">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-700 font-bold flex items-center justify-center text-xs shrink-0">
                                                {{ strtoupper(substr($teacher->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <span class="font-bold text-slate-900 block">{{ $teacher->name }}</span>
                                                <span class="text-xs text-slate-400 block">{{ $teacher->email }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex flex-wrap gap-1">
                                            @forelse($teacher->teachingClasses as $class)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-blue-50 text-blue-700 border border-blue-200/60">
                                                    {{ $class->name }}
                                                </span>
                                            @empty
                                                <span class="text-xs text-slate-400 italic">Nenhuma turma atribuída</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    @if($isAllCongregations)
                                        <td class="px-4 py-3">
                                            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-800 bg-blue-50 border border-blue-100 px-2.5 py-1 rounded-lg">
                                                <span>⛪</span>
                                                <span>{{ $teacher->congregation?->name ?? 'Templo Sede' }}</span>
                                            </span>
                                        </td>
                                    @endif
                                    <td class="px-3 py-3 text-center">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold {{ $teacher->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500' }}">
                                            {{ $teacher->is_active ? 'Ativo' : 'Inativo' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        @if($isAllCongregations)
                                            <span class="inline-flex items-center gap-1 text-xs font-medium text-slate-400 italic">
                                                <span>🔒</span> Somente leitura
                                            </span>
                                        @else
                                            <div class="inline-flex items-center gap-2">
                                                <!-- Redefinir Senha -->
                                                <button 
                                                    type="button" 
                                                    @click="openResetModal('{{ $teacher->name }}', '{{ route('professores.reset-password', $teacher) }}')"
                                                    class="inline-flex items-center px-2.5 py-1.5 rounded-xl border border-slate-200 bg-white text-slate-700 text-xs font-bold hover:bg-slate-50 transition cursor-pointer"
                                                    title="Redefinir senha de acesso"
                                                >
                                                    🔑 Senha
                                                </button>

                                                <!-- Editar -->
                                                <a 
                                                    href="{{ route('professores.edit', $teacher) }}" 
                                                    class="inline-flex items-center px-2.5 py-1.5 rounded-xl border border-slate-200 bg-white text-slate-700 text-xs font-bold hover:bg-slate-50 transition"
                                                >
                                                    Editar
                                                </a>

                                                <!-- Ativar/Desativar -->
                                                <form method="POST" action="{{ route('professores.toggle', $teacher) }}" class="inline-block">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button 
                                                        type="submit" 
                                                        class="inline-flex items-center px-2.5 py-1.5 rounded-xl border text-xs font-bold transition cursor-pointer {{ $teacher->is_active ? 'border-amber-200 bg-amber-50 text-amber-700 hover:bg-amber-100' : 'border-emerald-200 bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}"
                                                    >
                                                        {{ $teacher->is_active ? 'Desativar' : 'Ativar' }}
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $isAllCongregations ? 5 : 4 }}" class="px-4 py-8 text-center text-slate-400 text-xs sm:text-sm">
                                        Nenhum professor encontrado.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($teachers->hasPages())
                    <div class="p-4 border-t border-slate-100">
                        {{ $teachers->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Modal de Redefinição de Senha -->
        <div 
            x-show="resetModalOpen" 
            x-cloak 
            class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
        >
            <div 
                @click.away="resetModalOpen = false" 
                class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-slate-100 relative"
            >
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-lg text-slate-900">
                        Redefinir Senha
                    </h3>
                    <button @click="resetModalOpen = false" class="text-slate-400 hover:text-slate-600 text-xl">
                        ✕
                    </button>
                </div>

                <p class="text-xs text-slate-500 mb-4">
                    Digite a nova senha para o professor <strong class="text-slate-800" x-text="teacherName"></strong>:
                </p>

                <form method="POST" :action="actionUrl" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <input 
                            type="text" 
                            name="password" 
                            x-model="newPassword" 
                            required 
                            minlength="6"
                            class="w-full rounded-xl border-slate-200 text-sm font-mono tracking-wider focus:border-blue-500 focus:ring-blue-500 min-h-[48px] px-3.5"
                        >
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button 
                            type="button" 
                            @click="resetModalOpen = false" 
                            class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-700 text-xs font-bold hover:bg-slate-50 min-h-[44px]"
                        >
                            Cancelar
                        </button>
                        <button 
                            type="submit" 
                            class="flex-1 px-4 py-2.5 rounded-xl bg-blue-600 text-white text-xs font-bold hover:bg-blue-700 shadow-md shadow-blue-500/20 min-h-[44px] cursor-pointer"
                        >
                            Confirmar Senha
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
