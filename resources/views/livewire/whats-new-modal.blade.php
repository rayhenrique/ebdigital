<div>
    @if($showModal)
        <div 
            class="fixed inset-0 z-[9999] overflow-y-auto"
            aria-labelledby="modal-title" 
            role="dialog" 
            aria-modal="true"
            x-data="{ activeTab: @entangle('selectedVersion') }"
        >
            <!-- Fundo Escurecido com Blur Suave -->
            <div 
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
                wire:click="closeModalOnly"
            ></div>

            <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-6">
                <!-- Card Principal do Modal -->
                <div 
                    class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all w-full max-w-lg sm:max-w-xl border border-slate-100 flex flex-col max-h-[90vh] animate-in fade-in zoom-in-95 duration-200"
                    @click.stop
                >
                    <!-- Cabeçalho com Gradiente Suave & Ícone de Destaque -->
                    <div class="relative px-6 pt-6 pb-5 bg-gradient-to-br from-blue-600 via-indigo-600 to-blue-700 text-white shrink-0 overflow-hidden">
                        <!-- Círculos Decorativos de Fundo -->
                        <div class="absolute -right-8 -top-8 w-32 h-32 rounded-full bg-white/10 blur-xl pointer-events-none"></div>
                        <div class="absolute -left-6 -bottom-6 w-24 h-24 rounded-full bg-indigo-400/20 blur-lg pointer-events-none"></div>

                        <div class="relative flex items-start justify-between gap-4">
                            <div class="flex items-center gap-3.5">
                                <div class="w-12 h-12 rounded-2xl bg-white/15 backdrop-blur-md border border-white/20 flex items-center justify-center text-white shadow-inner shrink-0">
                                    <!-- Ícone Foguete / Novidades -->
                                    <svg class="w-6 h-6 animate-pulse" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"/>
                                        <path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"/>
                                        <path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0"/>
                                        <path d="M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5"/>
                                    </svg>
                                </div>

                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-black tracking-wider uppercase bg-white/20 text-white backdrop-blur-xs border border-white/25">
                                            v{{ $release['version'] ?? $currentVersion }}
                                        </span>
                                        @if(($release['version'] ?? '') === $currentVersion)
                                            <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                                Mais recente
                                            </span>
                                        @endif
                                    </div>
                                    <h3 class="text-xl sm:text-2xl font-black text-white tracking-tight mt-1 leading-snug">
                                        O Que Há de Novo?
                                    </h3>
                                </div>
                            </div>

                            <!-- Botão Fechar no Topo -->
                            <button 
                                type="button" 
                                wire:click="closeModalOnly" 
                                title="Fechar modal"
                                class="p-2 -mr-1 -mt-1 rounded-full text-white/80 hover:text-white hover:bg-white/10 transition-colors focus:outline-none min-w-[44px] min-h-[44px] flex items-center justify-center cursor-pointer"
                            >
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="6" x2="6" y2="18"/>
                                    <line x1="6" y1="6" x2="18" y2="18"/>
                                </svg>
                            </button>
                        </div>

                        <!-- Barra de Seleção de Versões (Abas Horizontais com Scroll) -->
                        @if(count($allReleases) > 1)
                            <div class="mt-4 pt-3 border-t border-white/15 flex items-center gap-2 overflow-x-auto pb-1 no-scrollbar">
                                <span class="text-[11px] font-bold text-white/70 shrink-0 uppercase tracking-wider">
                                    Versões:
                                </span>
                                @foreach(array_keys($allReleases) as $ver)
                                    <button 
                                        type="button" 
                                        wire:click="selectVersion('{{ $ver }}')"
                                        class="px-2.5 py-1 rounded-lg text-xs font-semibold whitespace-nowrap transition-all cursor-pointer {{ $selectedVersion === $ver ? 'bg-white text-blue-700 shadow-xs font-bold' : 'text-white/80 hover:text-white hover:bg-white/10' }}"
                                    >
                                        v{{ $ver }}
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Conteúdo com Scroll Vertical Suave -->
                    <div class="px-6 py-5 overflow-y-auto flex-1 space-y-4 text-slate-700 text-sm">
                        <!-- Card do Resumo da Versão -->
                        <div class="p-4 rounded-2xl bg-blue-50/70 border border-blue-100/80">
                            <div class="flex items-center justify-between gap-2 mb-1.5">
                                <h4 class="font-bold text-base text-blue-950">
                                    {{ $release['title'] ?? 'Atualização da EBD Digital' }}
                                </h4>
                                @if(isset($release['date']))
                                    <span class="text-xs font-medium text-blue-600 shrink-0">
                                        {{ $release['date'] }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-blue-900/80 leading-relaxed">
                                {{ $release['description'] ?? 'Confira as melhorias e novidades implementadas para aperfeiçoar sua experiência na Escola Bíblica.' }}
                            </p>
                        </div>

                        <!-- Lista de Destaques (Highlights) -->
                        <div class="space-y-3 pt-1">
                            <h5 class="text-xs font-extrabold uppercase tracking-wider text-slate-400">
                                Destaques Desta Atualização
                            </h5>

                            @forelse($release['highlights'] ?? [] as $item)
                                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 hover:border-slate-200 transition-colors flex items-start gap-3">
                                    <!-- Badge / Ícone por Tipo -->
                                    @if(($item['type'] ?? '') === 'feature')
                                        <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 shadow-2xs text-xs font-bold" title="Nova Funcionalidade">
                                            ✨
                                        </div>
                                    @elseif(($item['type'] ?? '') === 'improvement')
                                        <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center shrink-0 shadow-2xs text-xs font-bold" title="Melhoria">
                                            ⚡
                                        </div>
                                    @else
                                        <div class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center shrink-0 shadow-2xs text-xs font-bold" title="Ajuste / Correção">
                                            🛡️
                                        </div>
                                    @endif

                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap mb-0.5">
                                            <span class="font-bold text-slate-900 text-sm">
                                                {{ $item['title'] ?? 'Melhoria' }}
                                            </span>
                                            @if(($item['type'] ?? '') === 'feature')
                                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    Novo
                                                </span>
                                            @elseif(($item['type'] ?? '') === 'improvement')
                                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                                                    Melhoria
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-slate-600 leading-relaxed">
                                            {{ $item['description'] ?? '' }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <div class="p-4 text-center text-xs text-slate-400 italic">
                                    Nenhum destaque específico listado para esta versão.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Rodapé do Modal com Botão de Toque Grande (Min 48px) -->
                    <div class="p-5 border-t border-slate-100 bg-slate-50/80 shrink-0 flex flex-col sm:flex-row items-center justify-between gap-3">
                        <div class="text-[11px] text-slate-400 text-center sm:text-left flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-blue-500 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="16" x2="12" y2="12"/>
                                <line x1="12" y1="8" x2="12.01" y2="8"/>
                            </svg>
                            <span>Caderneta EBD Digital • Assembleia de Deus</span>
                        </div>

                        <button 
                            type="button" 
                            wire:click="dismiss" 
                            class="w-full sm:w-auto min-h-[48px] px-6 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 active:scale-[0.98] text-white text-sm font-bold shadow-md shadow-blue-500/20 transition-all flex items-center justify-center gap-2 cursor-pointer"
                        >
                            <span>Entendi, vamos começar!</span>
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m9 18 6-6-6-6"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
