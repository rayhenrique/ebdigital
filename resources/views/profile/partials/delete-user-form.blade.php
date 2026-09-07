<section class="space-y-6">
    <header>
        <h2 class="text-base font-bold text-gray-900">
            Excluir Conta
        </h2>

        <p class="mt-1 text-xs text-gray-500">
            Depois que sua conta for excluída, todos os recursos e dados vinculados a ela serão apagados permanentemente. Antes de excluir sua conta, faça o download de quaisquer dados ou informações que deseje reter.
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="w-full sm:w-auto min-h-[48px] px-5 py-3 text-xs font-bold rounded-xl flex items-center justify-center"
    >
        Excluir Minha Conta
    </x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-base font-bold text-gray-900">
                Tem certeza de que deseja excluir sua conta?
            </h2>

            <p class="mt-2 text-xs text-gray-600">
                Esta ação é irreversível. Todos os seus dados de acesso serão removidos. Digite sua senha para confirmar a exclusão definitiva da sua conta.
            </p>

            <div class="mt-4">
                <label for="password" class="sr-only">Senha</label>

                <input
                    id="password"
                    name="password"
                    type="password"
                    class="w-full rounded-xl border-gray-300 text-sm focus:border-red-500 focus:ring-red-500 min-h-[48px] px-3 shadow-sm"
                    placeholder="Digite sua senha para confirmar"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                <button 
                    type="button" 
                    x-on:click="$dispatch('close')"
                    class="w-full sm:w-auto px-5 py-3 rounded-xl border border-gray-300 bg-white text-xs font-semibold text-gray-700 hover:bg-gray-50 min-h-[48px] flex items-center justify-center"
                >
                    Cancelar
                </button>

                <button 
                    type="submit"
                    class="w-full sm:w-auto px-5 py-3 rounded-xl bg-red-600 text-xs font-bold text-white hover:bg-red-700 shadow-sm min-h-[48px] flex items-center justify-center"
                >
                    Sim, Excluir Minha Conta
                </button>
            </div>
        </form>
    </x-modal>
</section>
