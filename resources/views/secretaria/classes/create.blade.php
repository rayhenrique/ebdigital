<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <h2 class="font-bold text-xl text-gray-900 leading-tight">
                Nova Classe / Turma
            </h2>
            <a href="{{ route('classes.index') }}" class="inline-flex items-center gap-1 text-xs sm:text-sm font-semibold text-indigo-600 hover:text-indigo-800 py-1 self-start sm:self-auto min-h-[44px]">
                &larr; Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <form method="POST" action="{{ route('classes.store') }}">
                    @csrf

                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-xs font-semibold text-gray-700 mb-1">Nome da Classe</label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                value="{{ old('name') }}" 
                                required 
                                placeholder="Ex: Classe Jovens (Geração Eleita)" 
                                class="w-full rounded-xl border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 min-h-[48px] px-3"
                            >
                            <x-input-error :messages="$errors->get('name')" class="mt-1" />
                        </div>

                        <div>
                            <label for="description" class="block text-xs font-semibold text-gray-700 mb-1">Descrição / Faixa Etária</label>
                            <textarea 
                                id="description" 
                                name="description" 
                                rows="2" 
                                placeholder="Descrição dos objetivos ou idade dos alunos da classe" 
                                class="w-full rounded-xl border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 p-3"
                            >{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-1" />
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-2">Professores Vinculados</label>
                            <div class="space-y-2 max-h-48 overflow-y-auto border border-gray-200 rounded-xl p-3">
                                @forelse($teachers as $teacher)
                                    <label class="flex items-center gap-2 text-sm text-gray-700 p-1 hover:bg-gray-50 rounded-lg cursor-pointer">
                                        <input 
                                            type="checkbox" 
                                            name="teacher_ids[]" 
                                            value="{{ $teacher->id }}" 
                                            {{ in_array($teacher->id, old('teacher_ids', [])) ? 'checked' : '' }}
                                            class="rounded text-indigo-600 focus:ring-indigo-500 w-4 h-4"
                                        >
                                        <span>{{ $teacher->name }} ({{ $teacher->email }})</span>
                                    </label>
                                @empty
                                    <p class="text-xs text-gray-400">Nenhum usuário com perfil de professor cadastrado.</p>
                                @endforelse
                            </div>
                            <x-input-error :messages="$errors->get('teacher_ids')" class="mt-1" />
                        </div>

                        <div class="flex items-center gap-2 pt-2">
                            <input 
                                type="checkbox" 
                                id="is_active" 
                                name="is_active" 
                                value="1" 
                                {{ old('is_active', true) ? 'checked' : '' }} 
                                class="rounded text-indigo-600 focus:ring-indigo-500 w-5 h-5"
                            >
                            <label for="is_active" class="text-sm font-medium text-gray-700">Classe Ativa no Calendário</label>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                        <a href="{{ route('classes.index') }}" class="w-full sm:w-auto px-5 py-3 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-50 min-h-[48px] flex items-center justify-center">
                            Cancelar
                        </a>
                        <button type="submit" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold shadow-md shadow-indigo-100 min-h-[48px] flex items-center justify-center">
                            Salvar Classe
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
