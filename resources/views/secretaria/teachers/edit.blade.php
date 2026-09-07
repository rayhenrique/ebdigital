<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('professores.index') }}" class="p-2 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition">
                ←
            </a>
            <div>
                <h2 class="font-bold text-2xl text-slate-850 font-display leading-tight">
                    Editar Professor: {{ $teacher->name }}
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Atualize os dados e turmas atribuídas a este docente.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 sm:p-8">
                <form method="POST" action="{{ route('professores.update', $teacher) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Nome Completo *
                        </label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name', $teacher->name) }}" 
                            required 
                            class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 min-h-[48px] px-3.5 @error('name') border-rose-400 @enderror"
                        >
                        @error('name')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                E-mail de Acesso *
                            </label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                value="{{ old('email', $teacher->email) }}" 
                                required 
                                class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 min-h-[48px] px-3.5 @error('email') border-rose-400 @enderror"
                            >
                            @error('email')
                                <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Nova Senha (deixe em branco para manter)
                            </label>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                minlength="6"
                                placeholder="••••••••" 
                                class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 min-h-[48px] px-3.5 @error('password') border-rose-400 @enderror"
                            >
                            @error('password')
                                <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Telefone / WhatsApp (Opcional)
                        </label>
                        <input 
                            type="text" 
                            id="phone" 
                            name="phone" 
                            value="{{ old('phone', $teacher->phone) }}" 
                            placeholder="Ex: (82) 98888-7777" 
                            class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 min-h-[48px] px-3.5"
                        >
                        @error('phone')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Turmas que o professor lecionará -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Turmas Atribuídas
                        </label>
                        <p class="text-xs text-slate-400 mb-3">Selecione as turmas que este professor tem permissão para lançar a frequência:</p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-48 overflow-y-auto p-3 rounded-xl border border-slate-200 bg-slate-50/50">
                            @forelse($classes as $c)
                                <label class="flex items-center gap-2 p-2 rounded-lg hover:bg-white transition cursor-pointer border border-transparent hover:border-slate-200">
                                    <input 
                                        type="checkbox" 
                                        name="class_ids[]" 
                                        value="{{ $c->id }}"
                                        {{ in_array($c->id, old('class_ids', $selectedClassIds)) ? 'checked' : '' }}
                                        class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 w-4 h-4"
                                    >
                                    <span class="text-xs font-semibold text-slate-800">{{ $c->name }}</span>
                                </label>
                            @empty
                                <p class="text-xs text-slate-400 p-2 col-span-2">Nenhuma turma cadastrada nesta congregação.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input 
                                type="checkbox" 
                                name="is_active" 
                                value="1" 
                                {{ old('is_active', $teacher->is_active) ? 'checked' : '' }}
                                class="rounded border-slate-300 text-blue-600 shadow-xs focus:ring-blue-500 w-4 h-4"
                            >
                            <span class="text-xs font-medium text-slate-700">Professor Ativo</span>
                        </label>

                        <div class="flex items-center gap-3 w-full sm:w-auto">
                            <a 
                                href="{{ route('professores.index') }}" 
                                class="flex-1 sm:flex-none px-5 py-3 rounded-xl border border-slate-200 bg-white text-slate-700 text-xs font-bold hover:bg-slate-50 min-h-[48px] flex items-center justify-center text-center"
                            >
                                Cancelar
                            </a>
                            <button 
                                type="submit" 
                                class="flex-1 sm:flex-none px-6 py-3 rounded-xl bg-blue-600 text-white text-xs font-bold hover:bg-blue-700 shadow-md shadow-blue-500/20 min-h-[48px] cursor-pointer"
                            >
                                Atualizar Professor
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
