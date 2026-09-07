<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <h2 class="font-bold text-lg sm:text-xl text-gray-900 leading-tight">
                Novo Aluno
            </h2>
            <a href="{{ route('alunos.index') }}" class="inline-flex items-center gap-1 text-xs sm:text-sm font-semibold text-indigo-600 hover:text-indigo-800 py-1 self-start sm:self-auto min-h-[44px]">
                &larr; Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <form method="POST" action="{{ route('alunos.store') }}">
                    @csrf

                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-xs font-semibold text-gray-700 mb-1">Nome Completo do Aluno</label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                value="{{ old('name') }}" 
                                required 
                                placeholder="Ex: João da Silva Santos" 
                                class="w-full rounded-xl border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 min-h-[48px] px-3"
                            >
                            <x-input-error :messages="$errors->get('name')" class="mt-1" />
                        </div>

                        <div>
                            <label for="class_id" class="block text-xs font-semibold text-gray-700 mb-1">Classe Vinculada</label>
                            <select 
                                id="class_id" 
                                name="class_id" 
                                required 
                                class="w-full rounded-xl border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 min-h-[48px]"
                            >
                                <option value="">Selecione a Classe...</option>
                                @foreach($turmas as $c)
                                    <option value="{{ $c->id }}" {{ old('class_id') == $c->id ? 'selected' : '' }}>
                                        {{ $c->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('class_id')" class="mt-1" />
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="phone" class="block text-xs font-semibold text-gray-700 mb-1">Telefone / WhatsApp</label>
                                <input 
                                    type="text" 
                                    id="phone" 
                                    name="phone" 
                                    value="{{ old('phone') }}" 
                                    placeholder="(82) 99999-9999" 
                                    class="w-full rounded-xl border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 min-h-[48px] px-3"
                                >
                                <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                            </div>

                            <div>
                                <label for="birth_date" class="block text-xs font-semibold text-gray-700 mb-1">Data de Nascimento</label>
                                <input 
                                    type="date" 
                                    id="birth_date" 
                                    name="birth_date" 
                                    value="{{ old('birth_date') }}" 
                                    class="w-full rounded-xl border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 min-h-[48px] px-3"
                                >
                                <x-input-error :messages="$errors->get('birth_date')" class="mt-1" />
                            </div>
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
                            <label for="is_active" class="text-sm font-medium text-gray-700">Aluno Ativo na Classe</label>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                        <a href="{{ route('alunos.index') }}" class="w-full sm:w-auto px-5 py-3 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-50 min-h-[48px] flex items-center justify-center">
                            Cancelar
                        </a>
                        <button type="submit" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold shadow-md shadow-indigo-100 min-h-[48px] flex items-center justify-center">
                            Salvar Aluno
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
