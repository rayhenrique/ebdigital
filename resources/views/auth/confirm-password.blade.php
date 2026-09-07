<x-guest-layout>
    <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-indigo-600 text-white text-2xl font-black shadow-md shadow-indigo-100 mb-3">
            🛡️
        </div>
        <h1 class="text-xl font-black text-gray-900">Confirmação de Segurança</h1>
        <p class="text-xs text-gray-500 mt-1 leading-relaxed">
            Esta é uma área protegida do sistema. Por favor, confirme sua senha antes de prosseguir.
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
        @csrf

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-semibold text-gray-700 mb-1">Senha Atual</label>
            <input 
                id="password" 
                class="w-full rounded-xl border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm min-h-[48px] px-3" 
                type="password" 
                name="password" 
                required 
                autocomplete="current-password" 
                placeholder="Digite sua senha"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <div class="pt-2">
            <button 
                type="submit" 
                class="w-full flex items-center justify-center px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-md shadow-indigo-100 transition min-h-[48px] text-sm"
            >
                Confirmar Senha
            </button>
        </div>
    </form>
</x-guest-layout>
