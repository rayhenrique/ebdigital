<x-guest-layout>
    <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-indigo-600 text-white text-2xl font-black shadow-md shadow-indigo-100 mb-3">
            ✉️
        </div>
        <h1 class="text-xl font-black text-gray-900">Verificação de E-mail</h1>
        <p class="text-xs text-gray-500 mt-1 leading-relaxed">
            Obrigado por cadastrar-se! Antes de começar, confirme seu endereço de e-mail clicando no link que acabamos de enviar. Se você não recebeu o e-mail, teremos prazer em lhe enviar outro.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-semibold text-xs text-emerald-700 bg-emerald-50 p-3 rounded-xl border border-emerald-100 text-center">
            Um novo link de verificação foi enviado para o endereço de e-mail informado no cadastro.
        </div>
    @endif

    <div class="space-y-3 pt-2">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <button 
                    type="submit" 
                    class="w-full flex items-center justify-center px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-md shadow-indigo-100 transition min-h-[48px] text-sm"
                >
                    Reenviar E-mail de Verificação
                </button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="text-center">
            @csrf

            <button type="submit" class="text-xs font-semibold text-gray-500 hover:text-gray-800 p-2">
                Sair da Conta
            </button>
        </form>
    </div>
</x-guest-layout>
