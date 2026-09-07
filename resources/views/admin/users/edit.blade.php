<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <h2 class="font-bold text-lg sm:text-xl text-gray-900 leading-tight">
                Editar Usuário: {{ $user->name }}
            </h2>
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-1 text-xs sm:text-sm font-semibold text-indigo-600 hover:text-indigo-800 py-1 self-start sm:self-auto min-h-[44px]">
                &larr; Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center gap-2">
                    <span>✓</span> {{ session('success') }}
                </div>
            @endif

            <!-- Dados Gerais -->
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <h3 class="text-base font-bold text-gray-900 mb-4">Informações do Usuário</h3>
                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-xs font-semibold text-gray-700 mb-1">Nome Completo</label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                value="{{ old('name', $user->name) }}" 
                                required 
                                class="w-full rounded-xl border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 min-h-[48px] px-3"
                            >
                            <x-input-error :messages="$errors->get('name')" class="mt-1" />
                        </div>

                        <div>
                            <label for="email" class="block text-xs font-semibold text-gray-700 mb-1">E-mail de Acesso</label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                value="{{ old('email', $user->email) }}" 
                                required 
                                class="w-full rounded-xl border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 min-h-[48px] px-3"
                            >
                            <x-input-error :messages="$errors->get('email')" class="mt-1" />
                        </div>

                        <div>
                            <label for="role" class="block text-xs font-semibold text-gray-700 mb-1">Perfil / Papel de Acesso</label>
                            <select 
                                id="role" 
                                name="role" 
                                required 
                                class="w-full rounded-xl border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 min-h-[48px]"
                            >
                                @foreach($roles as $role)
                                    <option value="{{ $role->value }}" {{ old('role', $user->role->value) === $role->value ? 'selected' : '' }}>
                                        {{ $role->label() }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-1" />
                        </div>

                        <div>
                            <label for="congregation_id" class="block text-xs font-semibold text-gray-700 mb-1">Congregação Vinculada</label>
                            <select 
                                id="congregation_id" 
                                name="congregation_id" 
                                class="w-full rounded-xl border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 min-h-[48px]"
                            >
                                <option value="">Geral / Todas as Congregações (Apenas Admins / Liderança Geral)</option>
                                @foreach($congregations as $c)
                                    <option value="{{ $c->id }}" {{ old('congregation_id', $user->congregation_id) == $c->id ? 'selected' : '' }}>
                                        {{ $c->name }} {{ $c->is_headquarters ? '(Sede)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('congregation_id')" class="mt-1" />
                        </div>

                        <div class="flex items-center gap-2 pt-2">
                            <input 
                                type="checkbox" 
                                id="is_active" 
                                name="is_active" 
                                value="1" 
                                {{ old('is_active', $user->is_active) ? 'checked' : '' }} 
                                class="rounded text-indigo-600 focus:ring-indigo-500 w-5 h-5"
                            >
                            <label for="is_active" class="text-sm font-medium text-gray-700">Usuário Ativo no Sistema</label>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                        <a href="{{ route('admin.users.index') }}" class="w-full sm:w-auto px-5 py-3 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-50 min-h-[48px] flex items-center justify-center">
                            Cancelar
                        </a>
                        <button type="submit" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold shadow-md shadow-indigo-100 min-h-[48px] flex items-center justify-center">
                            Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>

            <!-- Redefinição Direta de Senha -->
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <h3 class="text-base font-bold text-gray-900 mb-1">Redefinir Senha</h3>
                <p class="text-xs text-gray-500 mb-4">Insira uma nova senha para este usuário. A ação será gravada na auditoria.</p>

                <form method="POST" action="{{ route('admin.users.reset-password', $user) }}">
                    @csrf
                    @method('PATCH')

                    <div class="flex flex-col sm:flex-row gap-3">
                        <input 
                            type="password" 
                            name="new_password" 
                            required 
                            placeholder="Nova senha (mínimo 6 caracteres)" 
                            class="w-full sm:flex-1 rounded-xl border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 min-h-[48px] px-3"
                        >
                        <button type="submit" class="w-full sm:w-auto px-5 py-3 bg-gray-900 hover:bg-gray-800 text-white text-xs font-bold rounded-xl min-h-[48px] flex items-center justify-center">
                            Atualizar Senha
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
