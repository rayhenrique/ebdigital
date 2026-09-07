<section>
    <header>
        <h2 class="text-base font-bold text-gray-900">
            Informações do Perfil
        </h2>

        <p class="mt-1 text-xs text-gray-500">
            Atualize os dados da sua conta e seu endereço de e-mail.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-4">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="block text-xs font-semibold text-gray-700 mb-1">Nome Completo</label>
            <input id="name" name="name" type="text" class="w-full rounded-xl border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 min-h-[48px] px-3" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            <x-input-error class="mt-1" :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="email" class="block text-xs font-semibold text-gray-700 mb-1">Endereço de E-mail</label>
            <input id="email" name="email" type="email" class="w-full rounded-xl border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 min-h-[48px] px-3" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            <x-input-error class="mt-1" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-xs mt-2 text-gray-800">
                        Seu e-mail ainda não foi verificado.

                        <button form="send-verification" class="underline text-xs text-indigo-600 hover:text-indigo-800">
                            Clique aqui para reenviar o e-mail de confirmação.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-xs text-emerald-600">
                            Um novo link de verificação foi enviado para o seu endereço de e-mail.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center gap-3 pt-2">
            <button type="submit" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold shadow-md shadow-indigo-100 min-h-[48px] flex items-center justify-center">
                Salvar Alterações
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    class="text-xs font-semibold text-emerald-600"
                >✓ Dados salvos com sucesso!</p>
            @endif
        </div>
    </form>
</section>
