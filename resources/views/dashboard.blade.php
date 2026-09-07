<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 leading-tight">
            Painel Geral
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-6 border border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold">
                        ✓
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 text-sm">Acesso Concedido</h3>
                        <p class="text-xs text-gray-500">Você está conectado com sucesso ao sistema Caderneta EBD.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

