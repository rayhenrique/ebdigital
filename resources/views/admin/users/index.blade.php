<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-850 font-display leading-tight">
                    Gestão de Usuários
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Controle de acessos, papéis (admin, secretário, professor) e redefinição de senhas.</p>
            </div>
            <a 
                href="{{ route('admin.users.create') }}" 
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-blue-600 text-white text-xs font-bold hover:bg-blue-700 shadow-md shadow-blue-500/20 min-h-[48px] cursor-pointer"
            >
                + Novo Usuário
            </a>
        </div>
    </x-slot>

    <div class="py-6" x-data="{
        resetModalOpen: false,
        userName: '',
        actionUrl: '',
        newPassword: '',
        showPassword: true,
        openResetModal(name, url) {
            this.userName = name;
            this.actionUrl = url;
            this.newPassword = 'ebd' + Math.floor(1000 + Math.random() * 9000);
            this.resetModalOpen = true;
        }
    }">
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
                <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                    <div class="sm:col-span-2">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}" 
                            placeholder="Buscar por nome ou e-mail..." 
                            class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 min-h-[48px] px-3"
                        >
                    </div>
                    <div>
                        <select name="role" class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 min-h-[48px] px-3">
                            <option value="">Todos os Perfis</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->value }}" {{ request('role') === $role->value ? 'selected' : '' }}>
                                    {{ $role->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 px-5 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-800 min-h-[48px] cursor-pointer">
                            Filtrar
                        </button>
                        @if(request()->hasAny(['search', 'role']))
                            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl text-xs font-bold hover:bg-slate-200 flex items-center justify-center min-h-[48px]">
                                Limpar
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Tabela e Lista de Usuários -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <!-- Visão Mobile (Cards) -->
                <div class="block md:hidden divide-y divide-slate-100">
                    @forelse($users as $u)
                        <div class="p-4 space-y-3">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <h3 class="font-bold text-slate-850 text-base leading-tight">{{ $u->name }}</h3>
                                    <p class="text-xs text-slate-500 mt-0.5">{{ $u->email }}</p>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold shrink-0 {{ $u->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500 border border-slate-200' }}">
                                    {{ $u->is_active ? 'Ativo' : 'Inativo' }}
                                </span>
                            </div>

                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold 
                                    {{ $u->role->value === 'admin' ? 'bg-purple-50 text-purple-700 border border-purple-200/80' : ($u->role->value === 'secretario' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200/80' : 'bg-blue-50 text-blue-700 border border-blue-200/80') }}">
                                    {{ $u->role->label() }}
                                </span>
                            </div>

                            <!-- Ações Mobile (3 Colunas: Editar, Redefinir Senha, Ativar/Desativar) -->
                            <div class="grid grid-cols-3 gap-2 pt-1">
                                <a 
                                    href="{{ route('admin.users.edit', $u) }}" 
                                    class="flex items-center justify-center px-2 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-700 text-xs font-semibold hover:bg-slate-50 min-h-[44px]"
                                >
                                    Editar
                                </a>
                                <button 
                                    type="button" 
                                    @click="openResetModal('{{ addslashes($u->name) }}', '{{ route('admin.users.reset-password', $u) }}')"
                                    class="flex items-center justify-center gap-1 px-2 py-2.5 rounded-xl border border-blue-200 bg-blue-50 text-blue-700 text-xs font-bold hover:bg-blue-100 min-h-[44px] cursor-pointer"
                                    title="Redefinir Senha"
                                >
                                    <span>🔑</span>
                                    <span>Senha</span>
                                </button>
                                @if($u->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.users.toggle', $u) }}" class="w-full">
                                        @csrf
                                        @method('PATCH')
                                        <button 
                                            type="submit" 
                                            class="w-full flex items-center justify-center px-2 py-2.5 rounded-xl text-xs font-bold min-h-[44px] {{ $u->is_active ? 'border border-amber-200 bg-amber-50 text-amber-700 hover:bg-amber-100' : 'border border-emerald-200 bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}"
                                        >
                                            {{ $u->is_active ? 'Desativar' : 'Ativar' }}
                                        </button>
                                    </form>
                                @else
                                    <div class="flex items-center justify-center text-[11px] text-slate-400 font-semibold bg-slate-50 rounded-xl min-h-[44px]">
                                        Você
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-slate-400 text-sm">
                            Nenhum usuário encontrado.
                        </div>
                    @endforelse
                </div>

                <!-- Visão Desktop (Tabela) -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-[11px] font-bold tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4">Nome / E-mail</th>
                                <th class="px-4 py-4 text-center">Perfil</th>
                                <th class="px-4 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($users as $u)
                                <tr class="hover:bg-slate-50/70 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-850">{{ $u->name }}</div>
                                        <div class="text-xs text-slate-400">{{ $u->email }}</div>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold 
                                            {{ $u->role->value === 'admin' ? 'bg-purple-50 text-purple-700 border border-purple-200/80' : ($u->role->value === 'secretario' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200/80' : 'bg-blue-50 text-blue-700 border border-blue-200/80') }}">
                                            {{ $u->role->label() }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $u->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500 border border-slate-200' }}">
                                            {{ $u->is_active ? 'Ativo' : 'Inativo' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-1">
                                        <!-- Botão Direto de Redefinição de Senha -->
                                        <button 
                                            type="button" 
                                            @click="openResetModal('{{ addslashes($u->name) }}', '{{ route('admin.users.reset-password', $u) }}')"
                                            class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-xl border border-blue-100 transition cursor-pointer"
                                            title="Redefinir Senha do Usuário"
                                        >
                                            <span>🔑</span>
                                            <span>Redefinir Senha</span>
                                        </button>

                                        <a href="{{ route('admin.users.edit', $u) }}" class="inline-flex items-center text-xs font-semibold text-slate-700 hover:text-blue-600 bg-slate-50 hover:bg-slate-100 px-3 py-1.5 rounded-xl border border-slate-200 transition">
                                            Editar
                                        </a>

                                        @if($u->id !== auth()->id())
                                            <form method="POST" action="{{ route('admin.users.toggle', $u) }}" class="inline-block">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-xs font-semibold {{ $u->is_active ? 'text-amber-700 hover:text-amber-800 bg-amber-50 hover:bg-amber-100 border-amber-200' : 'text-emerald-700 hover:text-emerald-800 bg-emerald-50 hover:bg-emerald-100 border-emerald-200' }} px-3 py-1.5 rounded-xl border transition">
                                                    {{ $u->is_active ? 'Desativar' : 'Ativar' }}
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-slate-400">
                                        Nenhum usuário encontrado com os filtros selecionados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($users->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Modal de Redefinição Rápida de Senha -->
        <div 
            x-show="resetModalOpen" 
            x-cloak 
            class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title" 
            role="dialog" 
            aria-modal="true"
        >
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
                <!-- Backdrop com blur suave -->
                <div 
                    x-show="resetModalOpen" 
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    @click="resetModalOpen = false" 
                    class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs transition-opacity" 
                    aria-hidden="true"
                ></div>

                <!-- Modal Dialog -->
                <div 
                    x-show="resetModalOpen" 
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-white rounded-2xl p-6 text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-slate-100 relative z-10"
                >
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2.5">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg shadow-xs">
                                🔑
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-850" id="modal-title">
                                    Redefinir Senha do Usuário
                                </h3>
                                <p class="text-xs text-slate-500 font-medium">
                                    Usuário: <span class="font-bold text-slate-800" x-text="userName"></span>
                                </p>
                            </div>
                        </div>
                        <button 
                            type="button" 
                            @click="resetModalOpen = false" 
                            class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-50 text-lg leading-none cursor-pointer"
                        >
                            &times;
                        </button>
                    </div>

                    <form :action="actionUrl" method="POST" class="mt-4 space-y-4">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                                Nova Senha de Acesso
                            </label>
                            <div class="relative rounded-xl shadow-xs">
                                <input 
                                    :type="showPassword ? 'text' : 'password'" 
                                    name="new_password" 
                                    x-model="newPassword" 
                                    required 
                                    minlength="6"
                                    placeholder="Mínimo 6 caracteres" 
                                    class="block w-full pl-4 pr-24 py-3 rounded-xl border border-slate-200 text-sm font-semibold text-slate-800 focus:border-transparent focus:ring-2 focus:ring-blue-600 min-h-[48px]"
                                >
                                <div class="absolute inset-y-0 right-0 flex items-center pr-2 gap-1">
                                    <button 
                                        type="button" 
                                        @click="newPassword = 'ebd' + Math.floor(1000 + Math.random() * 9000)"
                                        class="text-[11px] px-2 py-1 rounded-lg bg-slate-100 hover:bg-blue-50 hover:text-blue-700 text-slate-600 font-semibold transition cursor-pointer"
                                        title="Gerar nova sugestão de senha"
                                    >
                                        🎲 Gerar
                                    </button>
                                    <button 
                                        type="button" 
                                        @click="showPassword = !showPassword" 
                                        class="p-1.5 text-slate-400 hover:text-slate-600 cursor-pointer"
                                        aria-label="Ver senha"
                                    >
                                        <span x-show="!showPassword">👁️</span>
                                        <span x-show="showPassword" x-cloak>🙈</span>
                                    </button>
                                </div>
                            </div>
                            <p class="text-[11px] text-slate-500 mt-1.5">
                                Dica: O sistema já sugeriu uma senha simples e segura acima. Você pode usá-la ou digitar uma nova. Copie e envie ao usuário.
                            </p>
                        </div>

                        <div class="p-3 bg-amber-50 rounded-xl border border-amber-200/60 text-[11px] text-amber-800 flex items-start gap-2">
                            <span class="text-sm shrink-0">🛡️</span>
                            <span>Esta alteração tem efeito imediato e será gravada nos registros de <strong>auditoria</strong> com data, hora e IP do administrador.</span>
                        </div>

                        <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2.5 pt-2">
                            <button 
                                type="button" 
                                @click="resetModalOpen = false" 
                                class="w-full sm:w-auto px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 min-h-[44px]"
                            >
                                Cancelar
                            </button>
                            <button 
                                type="submit" 
                                class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white text-xs font-bold shadow-md shadow-blue-500/20 min-h-[44px] flex items-center justify-center gap-1.5 cursor-pointer"
                            >
                                <span>✓</span>
                                <span>Confirmar e Gravar Nova Senha</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
