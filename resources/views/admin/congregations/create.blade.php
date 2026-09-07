<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.congregacoes.index') }}" class="p-2 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition">
                ←
            </a>
            <div>
                <h2 class="font-bold text-2xl text-slate-850 font-display leading-tight">
                    Nova Congregação
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Cadastre uma nova filial da Escola Bíblica Dominical.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 sm:p-8">
                <form method="POST" action="{{ route('admin.congregacoes.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Nome da Congregação *
                        </label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name') }}" 
                            required 
                            placeholder="Ex: Congregação Canaã, Congregação Betel" 
                            class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 min-h-[48px] px-3.5 @error('name') border-rose-400 @enderror"
                        >
                        @error('name')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="pastor_dirigente" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Pastor / Dirigente Local
                            </label>
                            <input 
                                type="text" 
                                id="pastor_dirigente" 
                                name="pastor_dirigente" 
                                value="{{ old('pastor_dirigente') }}" 
                                placeholder="Ex: Ev. Marcos Antônio" 
                                class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 min-h-[48px] px-3.5"
                            >
                            @error('pastor_dirigente')
                                <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Telefone / WhatsApp de Contato
                            </label>
                            <input 
                                type="text" 
                                id="phone" 
                                name="phone" 
                                value="{{ old('phone') }}" 
                                placeholder="Ex: (82) 98888-7777" 
                                class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 min-h-[48px] px-3.5"
                            >
                            @error('phone')
                                <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="sm:col-span-2">
                            <label for="address" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Endereço / Bairro
                            </label>
                            <input 
                                type="text" 
                                id="address" 
                                name="address" 
                                value="{{ old('address') }}" 
                                placeholder="Ex: Rua das Flores, 120 - Tabuleiro" 
                                class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 min-h-[48px] px-3.5"
                            >
                            @error('address')
                                <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="city" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Cidade *
                            </label>
                            <input 
                                type="text" 
                                id="city" 
                                name="city" 
                                value="{{ old('city', 'Maceió') }}" 
                                required 
                                class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 min-h-[48px] px-3.5"
                            >
                            @error('city')
                                <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="space-y-3">
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input 
                                    type="checkbox" 
                                    name="is_headquarters" 
                                    value="1" 
                                    {{ old('is_headquarters') ? 'checked' : '' }}
                                    class="rounded border-slate-300 text-blue-600 shadow-xs focus:ring-blue-500 w-4 h-4"
                                >
                                <span class="text-xs font-bold text-slate-700">Definir como Templo Sede</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer block">
                                <input 
                                    type="checkbox" 
                                    name="is_active" 
                                    value="1" 
                                    {{ old('is_active', true) ? 'checked' : '' }}
                                    class="rounded border-slate-300 text-blue-600 shadow-xs focus:ring-blue-500 w-4 h-4"
                                >
                                <span class="text-xs font-medium text-slate-700">Congregação Ativa</span>
                            </label>
                        </div>

                        <div class="flex items-center gap-3 w-full sm:w-auto">
                            <a 
                                href="{{ route('admin.congregacoes.index') }}" 
                                class="flex-1 sm:flex-none px-5 py-3 rounded-xl border border-slate-200 bg-white text-slate-700 text-xs font-bold hover:bg-slate-50 min-h-[48px] flex items-center justify-center text-center"
                            >
                                Cancelar
                            </a>
                            <button 
                                type="submit" 
                                class="flex-1 sm:flex-none px-6 py-3 rounded-xl bg-blue-600 text-white text-xs font-bold hover:bg-blue-700 shadow-md shadow-blue-500/20 min-h-[48px] cursor-pointer"
                            >
                                Salvar Congregação
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
