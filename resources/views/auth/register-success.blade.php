<x-guest-layout>
    <!-- 1. Identidade & Ícone de Sucesso -->
    <div class="text-center mb-6">
        <div class="flex justify-center mb-3">
            <img 
                src="{{ asset('images/logo-ad-transparent.png') }}" 
                alt="Igreja Evangélica Assembleia de Deus" 
                class="h-16 w-auto object-contain drop-shadow-sm select-none"
                loading="eager"
            />
        </div>
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-emerald-100 text-emerald-600 mb-3 shadow-sm">
            <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
            </svg>
        </div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-850 font-display">
            Cadastro Realizado!
        </h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1.5 font-medium leading-relaxed">
            Sua solicitação foi enviada com sucesso e agora está <span class="text-amber-700 font-semibold">aguardando aprovação</span> pelo Administrador / Pastor da igreja.
        </p>
    </div>

    <!-- 2. Resumo dos Dados Cadastrados -->
    <div class="bg-slate-50/90 border border-slate-200/80 rounded-2xl p-4 mb-5 space-y-2.5 text-left">
        <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400 pb-1 border-b border-slate-200/60">
            Resumo dos Dados Cadastrados
        </div>

        <div class="flex items-center justify-between text-xs sm:text-sm">
            <span class="text-slate-500 font-medium">Nome:</span>
            <span class="text-slate-900 font-bold truncate max-w-[200px] sm:max-w-[240px]">
                {{ $registeredUser['name'] ?? 'Nome não informado' }}
            </span>
        </div>

        <div class="flex items-center justify-between text-xs sm:text-sm">
            <span class="text-slate-500 font-medium">E-mail:</span>
            <span class="text-slate-900 font-semibold truncate max-w-[200px] sm:max-w-[240px]">
                {{ $registeredUser['email'] ?? 'E-mail não informado' }}
            </span>
        </div>

        <div class="flex items-center justify-between text-xs sm:text-sm">
            <span class="text-slate-500 font-medium">Perfil:</span>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                {{ $registeredUser['role_label'] ?? 'Professor / Secretário' }}
            </span>
        </div>

        <div class="flex items-center justify-between text-xs sm:text-sm">
            <span class="text-slate-500 font-medium">Congregação:</span>
            <span class="text-slate-900 font-semibold truncate max-w-[200px] sm:max-w-[240px]">
                {{ $registeredUser['congregation_name'] ?? 'Congregação não informada' }}
            </span>
        </div>
    </div>

    <!-- 3. Chamada WhatsApp para Agilização da Aprovação -->
    <div class="bg-emerald-50/70 border border-emerald-200/80 rounded-2xl p-4 mb-5 text-center space-y-3">
        <div class="space-y-1">
            <h2 class="text-sm font-bold text-emerald-900">
                Deseja agilizar a sua liberação?
            </h2>
            <p class="text-xs text-emerald-700 leading-relaxed">
                Clique no botão abaixo para avisar diretamente no WhatsApp com seus dados pré-preenchidos:
            </p>
        </div>

        <!-- Botão Oficial WhatsApp -->
        <a 
            href="{{ $waLink }}" 
            target="_blank" 
            rel="noopener noreferrer" 
            class="w-full inline-flex items-center justify-center gap-2.5 px-5 py-3.5 min-h-[48px] bg-[#25D366] hover:bg-[#20bd5a] active:scale-[0.98] text-white font-bold text-sm sm:text-base rounded-xl shadow-lg shadow-emerald-600/25 transition-all cursor-pointer"
        >
            <!-- WhatsApp SVG Icon -->
            <svg class="w-5 h-5 fill-current shrink-0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M12.004 2C6.48 2 2 6.48 2 12.004c0 1.848.502 3.635 1.458 5.204L2 22l4.922-1.424a9.96 9.96 0 0 0 5.082 1.428h.004c5.52 0 10-4.48 10-10.004C22.008 6.48 17.528 2 12.004 2zm5.82 14.28c-.244.688-1.424 1.312-1.96 1.392-.496.072-1.12.104-3.272-.788-2.6-1.08-4.272-3.72-4.4-3.892-.128-.172-1.04-1.388-1.04-2.648 0-1.26.656-1.88.888-2.136.232-.256.508-.32.68-.32.172 0 .344 0 .496.008.16.008.376-.06.588.452.22.528.748 1.824.812 1.96.064.136.108.296.02.468-.088.172-.132.28-.264.436-.132.156-.276.348-.396.468-.132.132-.268.276-.116.536.152.26.676 1.116 1.452 1.808 1 .892 1.844 1.168 2.104 1.296.26.128.412.112.564-.064.152-.176.652-.76.828-1.02.176-.26.352-.216.592-.128.24.088 1.52.716 1.78.848.26.132.432.196.496.304.064.108.064.632-.18 1.32z"/>
            </svg>
            <span>Liberar pelo WhatsApp</span>
        </a>

        <div class="text-[11px] text-slate-500 pt-1">
            Qualquer dúvida entrar em contato: <span class="font-bold text-slate-700">{{ $phoneDisplay }}</span>
        </div>
    </div>

    <!-- 4. Botão Voltar ao Login -->
    <div class="pt-1">
        <a 
            href="{{ route('login') }}" 
            class="w-full flex items-center justify-center gap-2 px-5 py-3 min-h-[48px] bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs sm:text-sm rounded-xl border border-slate-200 shadow-sm transition active:scale-[0.98] cursor-pointer"
        >
            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            <span>Ir para a Página de Login</span>
        </a>
    </div>
</x-guest-layout>
