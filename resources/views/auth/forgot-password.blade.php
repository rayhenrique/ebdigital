<x-guest-layout>
    <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-indigo-600 text-white text-2xl font-black shadow-md shadow-indigo-100 mb-3">
            🔐
        </div>
        <h1 class="text-xl font-black text-gray-900">Recuperar Senha</h1>
        <p class="text-xs text-gray-500 mt-1 leading-relaxed">
            Esqueceu sua senha? Sem problemas. Informe seu e-mail cadastrado e enviaremos um link para você criar uma nova senha.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-semibold text-gray-700 mb-1">E-mail Cadastrado</label>
            <input 
                id="email" 
                class="w-full rounded-xl border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm min-h-[48px] px-3" 
                type="email" 
                name="email" 
                value="{{ old('email') }}" 
                required 
                autofocus 
            />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <div>
            <button 
                type="submit" 
                class="w-full flex items-center justify-center px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-md shadow-indigo-100 transition min-h-[48px] text-sm"
            >
                Enviar Link de Redefinição
            </button>
        </div>

        <div class="text-center pt-2">
            <a class="text-xs font-medium text-indigo-600 hover:text-indigo-800" href="{{ route('login') }}">
                ← Voltar para o Login
            </a>
        </div>
    </form>
</x-guest-layout>
