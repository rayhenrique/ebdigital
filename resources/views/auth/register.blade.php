<x-guest-layout>
    <!-- 1. Identidade Visual -->
    <div class="text-center mb-6">
        <div class="flex justify-center mb-3.5">
            <img 
                src="{{ asset('images/logo-ad-transparent.png') }}" 
                alt="Igreja Evangélica Assembleia de Deus" 
                class="h-18 sm:h-20 w-auto object-contain drop-shadow-sm select-none"
                loading="eager"
            />
        </div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-850 font-display">
            Solicitar Cadastro
        </h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1 font-medium">
            Acesso para professores e secretários da EBD
        </p>
    </div>

    <!-- Aviso sobre Aprovação Obrigatória -->
    <div class="mb-5 p-3.5 bg-amber-50/80 border border-amber-200/80 rounded-xl flex items-start gap-2.5">
        <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
        </svg>
        <p class="text-xs text-amber-800 leading-relaxed font-medium">
            Seu cadastro passará por <strong>aprovação prévia do Administrador / Pastor</strong> antes da liberação do acesso ao sistema.
        </p>
    </div>

    <!-- 2. Formulário Mobile-First -->
    <form method="POST" action="{{ route('register') }}" class="space-y-4" x-data="{ showPassword: false, showConfirmPassword: false }">
        @csrf

        <!-- Nome Completo -->
        <div>
            <label for="name" class="block text-xs font-semibold text-slate-700 mb-1.5">
                Nome Completo <span class="text-rose-500">*</span>
            </label>
            <div class="relative rounded-xl shadow-sm">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                </div>
                <input 
                    id="name" 
                    class="block w-full pl-11 pr-4 py-3 min-h-[48px] text-sm text-slate-900 placeholder:text-slate-400 rounded-xl border border-slate-200 bg-white shadow-sm focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-600 transition" 
                    type="text" 
                    name="name" 
                    value="{{ old('name') }}" 
                    required 
                    autofocus 
                    autocomplete="name" 
                    placeholder="Ex: João da Silva Santos"
                />
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
        </div>

        <!-- E-mail -->
        <div>
            <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">
                E-mail <span class="text-rose-500">*</span>
            </label>
            <div class="relative rounded-xl shadow-sm">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                    </svg>
                </div>
                <input 
                    id="email" 
                    class="block w-full pl-11 pr-4 py-3 min-h-[48px] text-sm text-slate-900 placeholder:text-slate-400 rounded-xl border border-slate-200 bg-white shadow-sm focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-600 transition" 
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required 
                    autocomplete="username" 
                    placeholder="seu.email@exemplo.com"
                />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <!-- Perfil: Secretário / Professor -->
        <div>
            <label for="role" class="block text-xs font-semibold text-slate-700 mb-1.5">
                Perfil de Acesso <span class="text-rose-500">*</span>
            </label>
            <div class="relative rounded-xl shadow-sm">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.199l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                    </svg>
                </div>
                <select 
                    id="role" 
                    name="role" 
                    required 
                    class="block w-full pl-11 pr-4 py-3 min-h-[48px] text-sm text-slate-900 rounded-xl border border-slate-200 bg-white shadow-sm focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-600 transition"
                >
                    <option value="" disabled {{ old('role') ? '' : 'selected' }}>Selecione o seu perfil na EBD...</option>
                    <option value="secretario" {{ old('role') === 'secretario' ? 'selected' : '' }}>
                        Secretário / Superintendente
                    </option>
                    <option value="professor" {{ old('role') === 'professor' ? 'selected' : '' }}>
                        Professor
                    </option>
                </select>
            </div>
            <x-input-error :messages="$errors->get('role')" class="mt-1.5" />
        </div>

        <!-- Congregação Vinculada -->
        <div>
            <label for="congregation_id" class="block text-xs font-semibold text-slate-700 mb-1.5">
                Congregação Vinculada <span class="text-rose-500">*</span>
            </label>
            <div class="relative rounded-xl shadow-sm">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205 3 1m1.5.5-1.5-.5M6.75 7.364V3h-3v18m3-13.636 10.5-3.818" />
                    </svg>
                </div>
                <select 
                    id="congregation_id" 
                    name="congregation_id" 
                    required 
                    class="block w-full pl-11 pr-4 py-3 min-h-[48px] text-sm text-slate-900 rounded-xl border border-slate-200 bg-white shadow-sm focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-600 transition"
                >
                    <option value="" disabled {{ old('congregation_id') ? '' : 'selected' }}>Selecione a sua congregação...</option>
                    @foreach($congregations as $congregation)
                        <option value="{{ $congregation->id }}" {{ old('congregation_id') == $congregation->id ? 'selected' : '' }}>
                            {{ $congregation->name }} {{ $congregation->city ? '— ' . $congregation->city : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <x-input-error :messages="$errors->get('congregation_id')" class="mt-1.5" />
        </div>

        <!-- Senha -->
        <div>
            <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5">
                Senha <span class="text-rose-500">*</span>
            </label>
            <div class="relative rounded-xl shadow-sm">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                </div>
                <input 
                    id="password" 
                    class="block w-full pl-11 pr-11 py-3 min-h-[48px] text-sm text-slate-900 placeholder:text-slate-400 rounded-xl border border-slate-200 bg-white shadow-sm focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-600 transition"
                    :type="showPassword ? 'text' : 'password'"
                    name="password"
                    required 
                    autocomplete="new-password" 
                    placeholder="Mínimo 8 caracteres"
                />
                <button 
                    type="button" 
                    @click="showPassword = !showPassword" 
                    class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer"
                    tabindex="-1"
                    aria-label="Alternar visibilidade da senha"
                >
                    <svg x-show="!showPassword" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    <svg x-show="showPassword" x-cloak class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <!-- Confirmar Senha -->
        <div>
            <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 mb-1.5">
                Confirmar Senha <span class="text-rose-500">*</span>
            </label>
            <div class="relative rounded-xl shadow-sm">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                </div>
                <input 
                    id="password_confirmation" 
                    class="block w-full pl-11 pr-11 py-3 min-h-[48px] text-sm text-slate-900 placeholder:text-slate-400 rounded-xl border border-slate-200 bg-white shadow-sm focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-600 transition"
                    :type="showConfirmPassword ? 'text' : 'password'"
                    name="password_confirmation" 
                    required 
                    autocomplete="new-password" 
                    placeholder="Repita a senha digitada"
                />
                <button 
                    type="button" 
                    @click="showConfirmPassword = !showConfirmPassword" 
                    class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer"
                    tabindex="-1"
                    aria-label="Alternar visibilidade da confirmação de senha"
                >
                    <svg x-show="!showConfirmPassword" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    <svg x-show="showConfirmPassword" x-cloak class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5" />
        </div>

        <!-- 3. Ações & Botões -->
        <div class="pt-2">
            <button 
                type="submit" 
                class="w-full flex items-center justify-center gap-2 px-6 py-3 min-h-[48px] bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 active:scale-[0.98] text-white font-semibold text-sm sm:text-base rounded-xl shadow-lg shadow-blue-500/20 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 transition-all duration-150 cursor-pointer"
            >
                <span>Enviar Solicitação de Cadastro</span>
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                </svg>
            </button>
        </div>

        <!-- Voltar para o Login -->
        <div class="text-center pt-2">
            <a 
                href="{{ route('login') }}" 
                class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-medium text-blue-600 hover:text-blue-700 hover:underline transition-colors py-2 min-h-[44px]"
            >
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                <span>Já possui uma conta? Entrar</span>
            </a>
        </div>
    </form>
</x-guest-layout>
