<x-guest-layout>
    <!-- 1. Identidade Visual -->
    <div class="text-center mb-6">
        <div class="flex justify-center mb-3.5">
            <img 
                src="{{ asset('images/logo-ad-transparent.png') }}" 
                alt="Igreja Evangélica Assembleia de Deus" 
                class="h-20 sm:h-22 w-auto object-contain drop-shadow-sm select-none"
                loading="eager"
            />
        </div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-850 font-display">
            Caderneta EBD Online
        </h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1 font-medium">
            Acesso para professores, secretaria e liderança
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- 2. Formulário Mobile-First -->
    <form method="POST" action="{{ route('login') }}" class="space-y-4" x-data="{ showPassword: false }">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">
                E-mail
            </label>
            <div class="relative rounded-xl shadow-sm">
                <!-- Ícone interno à esquerda -->
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
                    value="{{ old('email', 'admin@ebd.local') }}" 
                    required 
                    autofocus 
                    autocomplete="username" 
                    placeholder="seu.email@exemplo.com"
                />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5">
                Senha
            </label>
            <div class="relative rounded-xl shadow-sm">
                <!-- Ícone interno de cadeado à esquerda -->
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
                    value="senha123"
                    required 
                    autocomplete="current-password" 
                    placeholder="••••••••"
                />
                <!-- Botão para alternar visibilidade de senha -->
                <button 
                    type="button" 
                    @click="showPassword = !showPassword" 
                    class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer"
                    tabindex="-1"
                    aria-label="Alternar visibilidade da senha"
                >
                    <!-- Olho aberto -->
                    <svg x-show="!showPassword" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    <!-- Olho fechado / cortado -->
                    <svg x-show="showPassword" x-cloak class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer select-none">
                <input 
                    id="remember_me" 
                    type="checkbox" 
                    class="rounded-lg border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500 w-4 h-4 cursor-pointer" 
                    name="remember"
                >
                <span class="ms-2 text-xs sm:text-sm font-medium text-slate-600">Lembrar-me</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-xs sm:text-sm font-medium text-blue-600 hover:text-blue-700 hover:underline transition-colors" href="{{ route('password.request') }}">
                    Esqueceu a senha?
                </a>
            @endif
        </div>

        <!-- 3. Ações & Botões -->
        <div class="pt-2">
            <button 
                type="submit" 
                class="w-full flex items-center justify-center gap-2 px-6 py-3 min-h-[48px] bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 active:scale-[0.98] text-white font-semibold text-sm sm:text-base rounded-xl shadow-lg shadow-blue-500/20 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 transition-all duration-150 cursor-pointer"
            >
                <span>Entrar no Sistema</span>
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                </svg>
            </button>
        </div>
    </form>

    <!-- Divisor sutil no rodapé -->
    <div class="relative my-6">
        <div class="absolute inset-0 flex items-center" aria-hidden="true">
            <div class="w-full border-t border-slate-200"></div>
        </div>
        <div class="relative flex justify-center text-xs">
            <span class="bg-white px-3 text-slate-400 font-medium">Acesso Rápido de Teste</span>
        </div>
    </div>

    <!-- Chips/Pills horizontais para preenchimento rápido -->
    <div class="flex flex-wrap items-center justify-center gap-2" x-data>
        <button 
            type="button" 
            @click="document.getElementById('email').value='admin@ebd.local'; document.getElementById('password').value='senha123';"
            class="bg-slate-100 hover:bg-blue-50 hover:text-blue-700 hover:border-blue-200 text-slate-700 text-xs font-medium py-1.5 px-3 rounded-full border border-slate-200 active:scale-95 transition-all cursor-pointer min-h-[36px] flex items-center gap-1.5 shadow-sm"
        >
            <span>👑</span>
            <span>Pastor (Admin)</span>
        </button>
        <button 
            type="button" 
            @click="document.getElementById('email').value='secretario@ebd.local'; document.getElementById('password').value='senha123';"
            class="bg-slate-100 hover:bg-blue-50 hover:text-blue-700 hover:border-blue-200 text-slate-700 text-xs font-medium py-1.5 px-3 rounded-full border border-slate-200 active:scale-95 transition-all cursor-pointer min-h-[36px] flex items-center gap-1.5 shadow-sm"
        >
            <span>📋</span>
            <span>Secretário</span>
        </button>
        <button 
            type="button" 
            @click="document.getElementById('email').value='professor1@ebd.local'; document.getElementById('password').value='senha123';"
            class="bg-slate-100 hover:bg-blue-50 hover:text-blue-700 hover:border-blue-200 text-slate-700 text-xs font-medium py-1.5 px-3 rounded-full border border-slate-200 active:scale-95 transition-all cursor-pointer min-h-[36px] flex items-center gap-1.5 shadow-sm"
        >
            <span>📖</span>
            <span>Professor</span>
        </button>
    </div>
</x-guest-layout>
