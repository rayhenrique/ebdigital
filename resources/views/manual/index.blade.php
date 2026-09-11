<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200/80">
                        📖 Central de Ajuda & Treinamento
                    </span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-600">
                        Versão 2.0 (Multi-Tenant)
                    </span>
                </div>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight font-display">
                    Manual de Uso Didático da EBD Digital
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                    Guia prático e ilustrado para Pastores, Superintendentes, Secretários e Professores da Escola Bíblica.
                </p>
            </div>

            <!-- Botão de Impressão da Cartilha / Salvar PDF -->
            <button 
                type="button" 
                onclick="window.print()" 
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-700 text-xs sm:text-sm font-semibold hover:bg-slate-50 shadow-xs transition min-h-[44px] cursor-pointer shrink-0"
                title="Imprimir cartilha ou salvar em PDF"
            >
                <svg class="w-4 h-4 text-slate-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
                </svg>
                <span>Imprimir / Salvar PDF</span>
            </button>
        </div>
    </x-slot>

    <!-- Cabeçalho Apenas para Impressão -->
    <div class="hidden print:block mb-8 pb-4 border-b-2 border-slate-900 text-center">
        <div class="flex items-center justify-center gap-3 mb-2">
            <img src="{{ asset('images/logo-ad-transparent.png') }}" alt="Logo" class="h-16 w-auto object-contain">
            <div class="text-left">
                <h1 class="text-lg font-black uppercase tracking-wider text-slate-900 leading-tight">
                    Igreja Evangélica Assembleia de Deus
                </h1>
                <p class="text-xs font-bold text-slate-700">
                    Superintendência Geral da Escola Bíblica Dominical
                </p>
                <p class="text-[11px] text-slate-500">
                    Manual Oficial de Treinamento e Operação do Sistema EBD Digital
                </p>
            </div>
        </div>
        <p class="text-[10px] text-slate-400 mt-2">
            Documento emitido em {{ now()->format('d/m/Y \à\s H:i') }} para capacitação de obreiros e professores.
        </p>
    </div>

    <div 
        class="py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-6" 
        x-data="{
            activeRole: '{{ Auth::user()->isAdmin() ? 'admin' : (Auth::user()->isSecretario() ? 'secretario' : 'professor') }}',
            search: '',
            openFaq: null,
            matches(terms) {
                if (!this.search || !this.search.trim()) return true;
                const normalize = (str) => str.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
                const q = normalize(this.search);
                return normalize(terms).includes(q);
            },
            toggleFaq(id) {
                this.openFaq = this.openFaq === id ? null : id;
            }
        }"
    >
        <!-- =========================================================================
             1. BARRA DE BUSCA EM TEMPO REAL E SELETOR DE PERFIL INTERATIVO
             ========================================================================= -->
        <div class="print:hidden bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
            <!-- Campo de Busca em Tempo Real -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </div>
                <input 
                    type="text" 
                    x-model="search" 
                    placeholder="Buscar no manual (ex: como fazer chamada, matricular aluno, celular, relatórios, faltas, ofertas)..." 
                    class="block w-full pl-11 pr-10 py-3 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-blue-600 focus:border-transparent min-h-[48px] transition"
                />
                <button 
                    type="button" 
                    x-show="search.length > 0" 
                    x-cloak 
                    @click="search = ''" 
                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer"
                    title="Limpar busca"
                >
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Abas Interativas de Perfil -->
            <div>
                <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">
                    Visualizar Conteúdo por Perfil / Módulo:
                </span>
                <div class="flex flex-wrap gap-2">
                    <button 
                        type="button" 
                        @click="activeRole = 'all'" 
                        class="px-3.5 py-2 rounded-xl text-xs sm:text-sm font-bold transition min-h-[40px] cursor-pointer flex items-center gap-1.5"
                        :class="activeRole === 'all' ? 'bg-slate-900 text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                        :style="activeRole === 'all' ? 'background-color: #0f172a; color: #ffffff;' : 'background-color: #f1f5f9; color: #334155;'"
                    >
                        <span>🌟</span>
                        <span>Visão Completa</span>
                    </button>

                    <button 
                        type="button" 
                        @click="activeRole = 'professor'" 
                        class="px-3.5 py-2 rounded-xl text-xs sm:text-sm font-bold transition min-h-[40px] cursor-pointer flex items-center gap-1.5"
                        :class="activeRole === 'professor' ? 'bg-emerald-600 text-white shadow-sm shadow-emerald-600/20' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                        :style="activeRole === 'professor' ? 'background-color: #059669; color: #ffffff;' : 'background-color: #f1f5f9; color: #334155;'"
                    >
                        <span>📖</span>
                        <span>Guia do Professor</span>
                        @if(Auth::user()->isProfessor())
                            <span 
                                class="text-[10px] px-1.5 py-0.5 rounded-full font-bold ml-1 transition"
                                :class="activeRole === 'professor' ? 'bg-white/25 text-white' : 'bg-emerald-100 text-emerald-800 border border-emerald-300/60'"
                                :style="activeRole === 'professor' ? 'background-color: rgba(255,255,255,0.25); color: #ffffff;' : 'background-color: #d1fae5; color: #065f46;'"
                            >
                                Seu Perfil
                            </span>
                        @endif
                    </button>

                    <button 
                        type="button" 
                        @click="activeRole = 'secretario'" 
                        class="px-3.5 py-2 rounded-xl text-xs sm:text-sm font-bold transition min-h-[40px] cursor-pointer flex items-center gap-1.5"
                        :class="activeRole === 'secretario' ? 'bg-blue-600 text-white shadow-sm shadow-blue-600/20' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                        :style="activeRole === 'secretario' ? 'background-color: #2563eb; color: #ffffff;' : 'background-color: #f1f5f9; color: #334155;'"
                    >
                        <span>📋</span>
                        <span>Superintendente / Secretaria</span>
                        @if(Auth::user()->isSecretario())
                            <span 
                                class="text-[10px] px-1.5 py-0.5 rounded-full font-bold ml-1 transition"
                                :class="activeRole === 'secretario' ? 'bg-white/25 text-white' : 'bg-blue-100 text-blue-800 border border-blue-300/60'"
                                :style="activeRole === 'secretario' ? 'background-color: rgba(255,255,255,0.25); color: #ffffff;' : 'background-color: #dbeafe; color: #1e40af;'"
                            >
                                Seu Perfil
                            </span>
                        @endif
                    </button>

                    <button 
                        type="button" 
                        @click="activeRole = 'admin'" 
                        class="px-3.5 py-2 rounded-xl text-xs sm:text-sm font-bold transition min-h-[40px] cursor-pointer flex items-center gap-1.5"
                        :class="activeRole === 'admin' ? 'bg-purple-700 text-white shadow-sm shadow-purple-700/20' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                        :style="activeRole === 'admin' ? 'background-color: #7e22ce; color: #ffffff;' : 'background-color: #f1f5f9; color: #334155;'"
                    >
                        <span>👑</span>
                        <span>Pastor / Admin Geral</span>
                        @if(Auth::user()->isAdmin())
                            <span 
                                class="text-[10px] px-1.5 py-0.5 rounded-full font-bold ml-1 transition"
                                :class="activeRole === 'admin' ? 'bg-white/25 text-white' : 'bg-purple-100 text-purple-800 border border-purple-300/60'"
                                :style="activeRole === 'admin' ? 'background-color: rgba(255,255,255,0.25); color: #ffffff;' : 'background-color: #f3e8ff; color: #6b21a8;'"
                            >
                                Seu Perfil
                            </span>
                        @endif
                    </button>

                    <button 
                        type="button" 
                        @click="activeRole = 'pwa'" 
                        class="px-3.5 py-2 rounded-xl text-xs sm:text-sm font-bold transition min-h-[40px] cursor-pointer flex items-center gap-1.5"
                        :class="activeRole === 'pwa' ? 'bg-amber-600 text-white shadow-sm shadow-amber-600/20' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                        :style="activeRole === 'pwa' ? 'background-color: #d97706; color: #ffffff;' : 'background-color: #f1f5f9; color: #334155;'"
                    >
                        <span>📱</span>
                        <span>Instalação no Celular</span>
                    </button>

                    <button 
                        type="button" 
                        @click="activeRole = 'faq'" 
                        class="px-3.5 py-2 rounded-xl text-xs sm:text-sm font-bold transition min-h-[40px] cursor-pointer flex items-center gap-1.5"
                        :class="activeRole === 'faq' ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-600/20' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                        :style="activeRole === 'faq' ? 'background-color: #4f46e5; color: #ffffff;' : 'background-color: #f1f5f9; color: #334155;'"
                    >
                        <span>❓</span>
                        <span>Perguntas Frequentes (FAQ)</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- =========================================================================
             2. SEÇÃO: GUIA DO PROFESSOR (SALA DE AULA & MOBILE)
             ========================================================================= -->
        <div 
            x-show="(activeRole === 'all' || activeRole === 'professor') && matches('professor chamada presenca matricula rapida biblia revista oferta relatorio aniversariantes')" 
            x-cloak
            class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden"
        >
            <div class="p-5 sm:p-6 bg-gradient-to-r from-emerald-500/10 via-emerald-500/5 to-transparent border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500 text-white flex items-center justify-center text-2xl shadow-sm shrink-0">
                        📖
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-900 tracking-tight">
                            Módulo do Professor: O Dia a Dia na Sala de Aula
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-500">
                            Como realizar a chamada dominical, matricular alunos na hora e acompanhar a assiduidade.
                        </p>
                    </div>
                </div>

                <a 
                    href="{{ route('chamada.index') }}" 
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-xs self-start sm:self-auto min-h-[40px]"
                >
                    <span>Ir para Chamadas</span>
                    <span>→</span>
                </a>
            </div>

            <div class="p-5 sm:p-6 space-y-6">
                <!-- Passo 1: Como Iniciar a Chamada -->
                <div class="flex items-start gap-4">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-800 font-black text-sm flex items-center justify-center shrink-0 mt-0.5">
                        1
                    </div>
                    <div class="space-y-2 flex-1">
                        <h4 class="font-bold text-slate-900 text-sm sm:text-base">
                            Acessando a Lista de Turmas no Domingo de Manhã
                        </h4>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            Ao entrar no sistema pelo celular ou tablet, toque em <strong>"Chamadas"</strong> na barra inferior ou no menu lateral. Você verá a lista de classes em que você leciona. Se a aula de hoje ainda não foi registrada, ela estará destacada com uma badge amarela <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">Pendente</span>. Toque no botão <strong>"Lançar Chamada"</strong>.
                        </p>
                    </div>
                </div>

                <!-- Passo 2: O Checklist de Presença -->
                <div class="flex items-start gap-4">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-800 font-black text-sm flex items-center justify-center shrink-0 mt-0.5">
                        2
                    </div>
                    <div class="space-y-2 flex-1">
                        <h4 class="font-bold text-slate-900 text-sm sm:text-base">
                            Marcando Presença e Ausência com 1 Toque
                        </h4>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            Todos os alunos matriculados na sua sala aparecem em uma lista vertical desenhada especialmente para telas touch. Basta tocar no card ou no nome do aluno:
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                            <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center gap-3">
                                <span class="w-7 h-7 rounded-full bg-emerald-500 text-white flex items-center justify-center text-sm font-bold">✓</span>
                                <div>
                                    <span class="text-xs font-bold text-emerald-900 block">Aluno Presente (Verde)</span>
                                    <span class="text-[11px] text-emerald-700">Contabilizado na matrícula e soma da frequência.</span>
                                </div>
                            </div>
                            <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 flex items-center gap-3">
                                <span class="w-7 h-7 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-sm font-bold">✕</span>
                                <div>
                                    <span class="text-xs font-bold text-slate-800 block">Aluno Ausente (Cinza / Branco)</span>
                                    <span class="text-[11px] text-slate-500">Computa falta automática no histórico do aluno.</span>
                                </div>
                            </div>
                        </div>
                        <p class="text-xs text-slate-500 italic">
                            💡 Dica: Você também pode usar os botões rápidos <strong>"Marcar Todos"</strong> ou <strong>"Desmarcar Todos"</strong> no topo da lista para agilizar a chamada.
                        </p>
                    </div>
                </div>

                <!-- Passo 3: Matrícula Rápida em Sala de Aula -->
                <div class="flex items-start gap-4">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-800 font-black text-sm flex items-center justify-center shrink-0 mt-0.5">
                        3
                    </div>
                    <div class="space-y-2 flex-1">
                        <div class="flex items-center gap-2">
                            <h4 class="font-bold text-slate-900 text-sm sm:text-base">
                                Matrícula Rápida: Aluno Novo Chegou na Sala?
                            </h4>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-blue-100 text-blue-800 uppercase">Recurso Especial</span>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            Se um visitante decidiu se matricular ou uma família nova chegou, você <strong>não precisa mandar ninguém para a secretaria</strong>. Dentro da própria tela de chamada, toque no botão azul <strong>"+ Matricular Aluno"</strong>:
                        </p>
                        <ol class="list-decimal list-inside text-xs sm:text-sm text-slate-600 space-y-1 pl-2">
                            <li>Digite o <strong>Nome Completo</strong> do novo aluno.</li>
                            <li>(Opcional) Informe o WhatsApp e a Data de Nascimento (para alertas de aniversário).</li>
                            <li>Toque em <strong>"Matricular e Marcar Presença"</strong>.</li>
                            <li>O aluno entra <strong>instantaneamente</strong> na lista da sua sala e já fica com presença verde confirmada para hoje!</li>
                        </ol>
                    </div>
                </div>

                <!-- Passo 4: Coleta de Bíblias, Revistas, Visitantes e Oferta -->
                <div class="flex items-start gap-4">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-800 font-black text-sm flex items-center justify-center shrink-0 mt-0.5">
                        4
                    </div>
                    <div class="space-y-2 flex-1">
                        <h4 class="font-bold text-slate-900 text-sm sm:text-base">
                            Registrando os Indicadores Dominicais da Turma
                        </h4>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            Logo abaixo da lista de alunos, preencha os campos estatísticos da aula:
                        </p>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-center text-xs pt-1">
                            <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                                <span class="text-lg block">📖</span>
                                <span class="font-bold text-slate-800 block">Bíblias</span>
                                <span class="text-[10px] text-slate-400">Trazidas na aula</span>
                            </div>
                            <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                                <span class="text-lg block">📚</span>
                                <span class="font-bold text-slate-800 block">Revistas</span>
                                <span class="text-[10px] text-slate-400">Lições em mãos</span>
                            </div>
                            <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                                <span class="text-lg block">🤝</span>
                                <span class="font-bold text-slate-800 block">Visitantes</span>
                                <span class="text-[10px] text-slate-400">Pessoas não matriculadas</span>
                            </div>
                            <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                                <span class="text-lg block">🪙</span>
                                <span class="font-bold text-slate-800 block">Ofertas (R$)</span>
                                <span class="text-[10px] text-slate-400">Valor recolhido</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Passo 5: Modal de Confirmação & Bloqueio Retroativo -->
                <div class="flex items-start gap-4">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-800 font-black text-sm flex items-center justify-center shrink-0 mt-0.5">
                        5
                    </div>
                    <div class="space-y-2 flex-1">
                        <h4 class="font-bold text-slate-900 text-sm sm:text-base">
                            Modal de Confirmação e Redirecionamento Automático
                        </h4>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            Ao clicar em <strong>"Salvar Chamada"</strong>, o sistema abre uma janela de confirmação resumindo:
                            <strong>Total de Presentes</strong>, <strong>Ausentes</strong>, <strong>Visitantes</strong> e <strong>Oferta Apurada</strong>.
                            Ao clicar em <strong>"✓ Confirmar e Salvar"</strong>, os dados são salvos em transação segura e você retorna automaticamente para a tela principal de chamadas.
                        </p>
                        <div class="p-3 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-900">
                            <strong>⚠️ Regra de Segurança Importante:</strong> Professores só podem salvar ou editar a chamada <strong>no dia da aula (hoje)</strong>. Se for necessária qualquer correção em domingos passados, solicite ao Superintendente ou à Secretaria.
                        </div>
                    </div>
                </div>

                <!-- Passo 6: Relatórios da Turma e Aniversariantes -->
                <div class="flex items-start gap-4">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-800 font-black text-sm flex items-center justify-center shrink-0 mt-0.5">
                        6
                    </div>
                    <div class="space-y-2 flex-1">
                        <h4 class="font-bold text-slate-900 text-sm sm:text-base">
                            Acompanhando os Alunos e Aniversariantes da Sua Turma
                        </h4>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            No menu lateral, acesse <strong>"Relatórios da Turma"</strong> para:
                        </p>
                        <ul class="list-disc list-inside text-xs sm:text-sm text-slate-600 space-y-1 pl-2">
                            <li><strong>Consolidado da Turma:</strong> média de frequência e assiduidade percentual do trimestre.</li>
                            <li><strong>Frequência Nominal & Alerta de Faltosos:</strong> veja o histórico bolinha a bolinha (<span class="text-emerald-600 font-bold">P</span> ou <span class="text-rose-500 font-bold">F</span>) de cada aluno. Alunos com <strong>3 ou mais faltas consecutivas</strong> recebem destaque com alerta vermelho para ação pastoral e visitação.</li>
                            <li><strong>Aniversariantes do Mês:</strong> lista de todos os alunos da sua classe aniversariando no mês, com idade calculada e o botão <strong>"Parabenizar (WhatsApp)"</strong> que abre a mensagem pronta com 1 toque!</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- =========================================================================
             3. SEÇÃO: GUIA DO SUPERINTENDENTE & SECRETARIA
             ========================================================================= -->
        <div 
            x-show="(activeRole === 'all' || activeRole === 'secretario') && matches('secretaria superintendente classes turmas professores fechamento consolidado auditoria relatorio oficial')" 
            x-cloak
            class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden"
        >
            <div class="p-5 sm:p-6 bg-gradient-to-r from-blue-500/10 via-blue-500/5 to-transparent border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center text-2xl shadow-sm shrink-0">
                        📋
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-900 tracking-tight">
                            Módulo da Secretaria e Superintendência
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-500">
                            Gestão de turmas, atribuição de professores, alunos e fechamento dominical geral.
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a 
                        href="{{ route('classes.index') }}" 
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition min-h-[38px]"
                    >
                        Classes
                    </a>
                    <a 
                        href="{{ route('professores.index') }}" 
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition min-h-[38px]"
                    >
                        Professores
                    </a>
                    <a 
                        href="{{ route('reports.index') }}" 
                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition shadow-xs min-h-[38px]"
                    >
                        Relatórios Gerais
                    </a>
                </div>
            </div>

            <div class="p-5 sm:p-6 space-y-6">
                <!-- 1. Gestão de Classes e Professores -->
                <div class="flex items-start gap-4">
                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-800 font-black text-sm flex items-center justify-center shrink-0 mt-0.5">
                        1
                    </div>
                    <div class="space-y-2 flex-1">
                        <h4 class="font-bold text-slate-900 text-sm sm:text-base">
                            Organização das Classes e Professores da Congregação
                        </h4>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            No menu <strong>"Classes"</strong>, a secretaria cadastra as turmas da congregação (ex: Crianças, Juniores, Adolescentes, Jovens, Novos Convertidos, Adultos). Ao criar ou editar uma classe, você seleciona quais professores lecionam nela. Uma turma pode ter múltiplos professores (ex: titular e auxiliar).
                        </p>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            No menu <strong>"Professores"</strong>, você cadastra o corpo docente com nome e e-mail. Se algum professor esquecer a senha de acesso, a secretaria pode usar o botão <strong>"Resetar Senha"</strong> para restabelecer a senha padrão com 1 toque.
                        </p>
                    </div>
                </div>

                <!-- 2. Gestão Geral de Matrículas -->
                <div class="flex items-start gap-4">
                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-800 font-black text-sm flex items-center justify-center shrink-0 mt-0.5">
                        2
                    </div>
                    <div class="space-y-2 flex-1">
                        <h4 class="font-bold text-slate-900 text-sm sm:text-base">
                            Matrículas, Transferências e Inativação de Alunos
                        </h4>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            No menu <strong>"Alunos"</strong>, você tem a listagem completa da congregação com busca por nome e filtro por sala:
                        </p>
                        <ul class="list-disc list-inside text-xs sm:text-sm text-slate-600 space-y-1 pl-2">
                            <li><strong>Transferência de Turma:</strong> basta editar o aluno e trocar a classe (ex: aluno completou 18 anos e foi transferido de Jovens para Adultos). O histórico anterior de presenças é preservado intacto.</li>
                            <li><strong>Inativação Segura:</strong> se o aluno se mudou ou afastou, use o botão de status para torná-lo <em>Inativo</em>. Ele deixará de ocupar espaço na chamada semanal, mas o histórico permanece para relatórios e estatísticas.</li>
                        </ul>
                    </div>
                </div>

                <!-- 3. Fechamento Dominical em Tempo Real -->
                <div class="flex items-start gap-4">
                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-800 font-black text-sm flex items-center justify-center shrink-0 mt-0.5">
                        3
                    </div>
                    <div class="space-y-2 flex-1">
                        <h4 class="font-bold text-slate-900 text-sm sm:text-base">
                            Fechamento Dominical no Painel Central (Dashboard)
                        </h4>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            Enquanto as aulas acontecem no domingo de manhã, o Superintendente acompanha o <strong>Painel Consolidado</strong>:
                        </p>
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs sm:text-sm text-slate-700 space-y-2">
                            <div class="flex items-center justify-between border-b border-slate-200 pb-2">
                                <span class="font-bold">Indicador de Entrega:</span>
                                <span class="font-semibold text-blue-600">Mostra exatamente quantas turmas já salvaram a chamada (ex: 4 de 5 entregues).</span>
                            </div>
                            <div class="flex items-center justify-between border-b border-slate-200 pb-2">
                                <span class="font-bold">Conferência de Ofertas:</span>
                                <span class="font-semibold text-emerald-700">Somatório automático de todas as ofertas arrecadadas para conferir com o envelope físico.</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="font-bold">Total Geral na EBD:</span>
                                <span class="font-semibold text-slate-900">Soma de presentes matriculados + visitantes para leitura pública no encerramento no templo.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Lançamento e Retificação Retroativa -->
                <div class="flex items-start gap-4">
                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-800 font-black text-sm flex items-center justify-center shrink-0 mt-0.5">
                        4
                    </div>
                    <div class="space-y-2 flex-1">
                        <h4 class="font-bold text-slate-900 text-sm sm:text-base">
                            Retificações e Correção de Chamadas Retroativas
                        </h4>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            Apenas a Secretaria e o Pastor têm autorização para editar chamadas de datas passadas. Se um professor cometeu um engano no domingo anterior, o Secretário pode selecionar a data desejada no seletor de data, abrir a chamada da classe e fazer a retificação. Toda correção gera um log de auditoria automática com data e hora.
                        </p>
                    </div>
                </div>

                <!-- 5. Relatórios Oficiais e Impressão Timbrada -->
                <div class="flex items-start gap-4">
                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-800 font-black text-sm flex items-center justify-center shrink-0 mt-0.5">
                        5
                    </div>
                    <div class="space-y-2 flex-1">
                        <h4 class="font-bold text-slate-900 text-sm sm:text-base">
                            Relatórios Oficiais da EBD e Impressão Timbrada A4
                        </h4>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            No menu <strong>"Relatórios da EBD"</strong>, use os atalhos rápidos (Este Mês, 1º Trimestre, 2º Trimestre, etc.) para apuração contábil e de membros. Ao clicar em <strong>"Imprimir / Salvar PDF"</strong>, o sistema esconde menus e aplica o cabeçalho timbrado oficial da Assembleia de Deus com linhas de assinatura do Pastor e Superintendente.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- =========================================================================
             4. SEÇÃO: GUIA DO PASTOR & ADMINISTRADOR GERAL
             ========================================================================= -->
        <div 
            x-show="(activeRole === 'all' || activeRole === 'admin') && matches('pastor administrador admin geral congregacao tenant switcher auditoria logs seguranca usuarios')" 
            x-cloak
            class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden"
        >
            <div class="p-5 sm:p-6 bg-gradient-to-r from-purple-600/10 via-purple-600/5 to-transparent border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-purple-700 text-white flex items-center justify-center text-2xl shadow-sm shrink-0">
                        👑
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-900 tracking-tight">
                            Módulo do Pastor e Administrador Geral
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-500">
                            Governança do campo, alternância entre congregações, gestão de contas e auditoria.
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a 
                        href="{{ route('admin.congregacoes.index') }}" 
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition min-h-[38px]"
                    >
                        Congregações
                    </a>
                    <a 
                        href="{{ route('admin.users.index') }}" 
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition min-h-[38px]"
                    >
                        Usuários
                    </a>
                    <a 
                        href="{{ route('admin.audit.index') }}" 
                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-purple-700 hover:bg-purple-800 text-white text-xs font-bold transition shadow-xs min-h-[38px]"
                    >
                        Auditoria
                    </a>
                </div>
            </div>

            <div class="p-5 sm:p-6 space-y-6">
                <!-- 1. Tenant Switcher -->
                <div class="flex items-start gap-4">
                    <div class="w-8 h-8 rounded-full bg-purple-100 text-purple-800 font-black text-sm flex items-center justify-center shrink-0 mt-0.5">
                        1
                    </div>
                    <div class="space-y-2 flex-1">
                        <h4 class="font-bold text-slate-900 text-sm sm:text-base">
                            O Alternador de Congregações (Tenant Switcher)
                        </h4>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            No topo da barra lateral esquerda, o Pastor / Admin tem um dropdown seletor:
                        </p>
                        <ul class="list-disc list-inside text-xs sm:text-sm text-slate-600 space-y-1 pl-2">
                            <li><strong>🌐 Todas as Congregações:</strong> visão global somada de todo o campo eclesiástico para o pastor presidente acompanhar a totalidade de membros e ofertas.</li>
                            <li><strong>🏛️ / ⛪ Congregação Específica (ex: Templo Sede, Canaã, Betel):</strong> filtra instantaneamente todo o sistema (dashboard, chamadas, alunos e relatórios) para atuar como dirigente daquela congregação.</li>
                        </ul>
                    </div>
                </div>

                <!-- 2. Cadastro de Congregações -->
                <div class="flex items-start gap-4">
                    <div class="w-8 h-8 rounded-full bg-purple-100 text-purple-800 font-black text-sm flex items-center justify-center shrink-0 mt-0.5">
                        2
                    </div>
                    <div class="space-y-2 flex-1">
                        <h4 class="font-bold text-slate-900 text-sm sm:text-base">
                            Cadastro de Novas Filiais e Congregações
                        </h4>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            No menu <strong>"Congregações"</strong>, o Pastor cadastra novos templos e congregações pertencentes ao campo, definindo o nome, o Pastor Dirigente e se ela é a Sede ou congregação filial. Cada congregação criada ganha isolamento automático dos seus dados.
                        </p>
                    </div>
                </div>

                <!-- 3. Gestão de Contas e Usuários -->
                <div class="flex items-start gap-4">
                    <div class="w-8 h-8 rounded-full bg-purple-100 text-purple-800 font-black text-sm flex items-center justify-center shrink-0 mt-0.5">
                        3
                    </div>
                    <div class="space-y-2 flex-1">
                        <h4 class="font-bold text-slate-900 text-sm sm:text-base">
                            Gestão de Usuários e Níveis de Permissão
                        </h4>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            No menu <strong>"Gestão de Usuários"</strong>, o Admin cria e gerencia os acessos de liderança:
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 pt-1">
                            <div class="p-3 rounded-xl bg-purple-50 border border-purple-200">
                                <span class="font-bold text-xs text-purple-900 block">👑 Administrador</span>
                                <span class="text-[11px] text-purple-700">Acesso ilimitado, todas as congregações e auditoria.</span>
                            </div>
                            <div class="p-3 rounded-xl bg-blue-50 border border-blue-200">
                                <span class="font-bold text-xs text-blue-900 block">📋 Secretário</span>
                                <span class="text-[11px] text-blue-700">Gestão completa da sua própria congregação e turmas.</span>
                            </div>
                            <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-200">
                                <span class="font-bold text-xs text-emerald-900 block">📖 Professor</span>
                                <span class="text-[11px] text-emerald-700">Chamadas e alunos exclusivos das suas classes lecionadas.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Auditoria e Governança -->
                <div class="flex items-start gap-4">
                    <div class="w-8 h-8 rounded-full bg-purple-100 text-purple-800 font-black text-sm flex items-center justify-center shrink-0 mt-0.5">
                        4
                    </div>
                    <div class="space-y-2 flex-1">
                        <h4 class="font-bold text-slate-900 text-sm sm:text-base">
                            Trilha de Auditoria & Governança Eclesiástica
                        </h4>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            No menu <strong>"Logs de Auditoria"</strong>, a liderança dispõe de transparência total:
                            toda inserção ou alteração de chamada, oferta ou exclusão registra o autor, data, hora, IP de origem e os valores antes e depois da alteração. O sistema possui expurgo automático programado para retenção de segurança de 30 dias.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- =========================================================================
             5. SEÇÃO: INSTALAÇÃO NO CELULAR (PWA)
             ========================================================================= -->
        <div 
            x-show="(activeRole === 'all' || activeRole === 'pwa') && matches('pwa celular android iphone tela inicial aplicativo instalar')" 
            x-cloak
            class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden"
        >
            <div class="p-5 sm:p-6 bg-gradient-to-r from-amber-500/10 via-amber-500/5 to-transparent border-b border-slate-100 flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-amber-500 text-white flex items-center justify-center text-2xl shadow-sm shrink-0">
                    📱
                </div>
                <div>
                    <h3 class="text-lg font-black text-slate-900 tracking-tight">
                        Como Instalar a Caderneta no Celular (Android e iPhone)
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-500">
                        Tenha o aplicativo na palma da mão sem precisar baixar nada pesado da Play Store.
                    </p>
                </div>
            </div>

            <div class="p-5 sm:p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Android -->
                <div class="p-4 sm:p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="text-xl">🤖</span>
                        <h4 class="font-bold text-slate-900 text-sm sm:text-base">Como Instalar no Android (Chrome)</h4>
                    </div>
                    <ol class="list-decimal list-inside text-xs sm:text-sm text-slate-600 space-y-2 pl-1">
                        <li>Abra o navegador <strong>Google Chrome</strong> no celular e acesse o endereço do sistema.</li>
                        <li>Toque no ícone dos <strong>três pontinhos (⋮)</strong> no canto superior direito do navegador.</li>
                        <li>Toque na opção <strong>"Instalar aplicativo"</strong> ou <strong>"Adicionar à tela inicial"</strong>.</li>
                        <li>Confirme clicando em <strong>"Instalar"</strong>.</li>
                        <li>Pronto! O ícone da <strong>Caderneta EBD</strong> aparecerá junto aos seus outros aplicativos no celular.</li>
                    </ol>
                </div>

                <!-- iPhone iOS -->
                <div class="p-4 sm:p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="text-xl">🍎</span>
                        <h4 class="font-bold text-slate-900 text-sm sm:text-base">Como Instalar no iPhone (Safari)</h4>
                    </div>
                    <ol class="list-decimal list-inside text-xs sm:text-sm text-slate-600 space-y-2 pl-1">
                        <li>Abra o navegador <strong>Safari</strong> no seu iPhone e acesse o link do sistema.</li>
                        <li>Toque no botão de <strong>Compartilhar</strong> (quadrado com uma seta apontando para cima, na barra inferior).</li>
                        <li>Role para baixo e toque em <strong>"Adicionar à Tela de Início"</strong>.</li>
                        <li>No canto superior direito, toque em <strong>"Adicionar"</strong>.</li>
                        <li>O app da EBD abrirá em tela cheia como um aplicativo nativo, sem barras de navegador!</li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- =========================================================================
             6. SEÇÃO: PERGUNTAS FREQUENTES (FAQ INTERATIVO)
             ========================================================================= -->
        <div 
            x-show="(activeRole === 'all' || activeRole === 'faq') && matches('duvidas perguntas frequentes faq erro falta retroativa esqueci senha')" 
            x-cloak
            class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden"
        >
            <div class="p-5 sm:p-6 bg-gradient-to-r from-indigo-500/10 via-indigo-500/5 to-transparent border-b border-slate-100 flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white flex items-center justify-center text-2xl shadow-sm shrink-0">
                    ❓
                </div>
                <div>
                    <h3 class="text-lg font-black text-slate-900 tracking-tight">
                        Perguntas Frequentes (FAQ & Resolução de Dúvidas)
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-500">
                        Respostas diretas para as situações mais comuns no dia a dia da congregação.
                    </p>
                </div>
            </div>

            <div class="p-5 sm:p-6 divide-y divide-slate-100 space-y-1">
                <!-- FAQ 1 -->
                <div class="py-3" x-show="matches('professor errou esqueci domingo passado retroativa')">
                    <button 
                        type="button" 
                        @click="toggleFaq(1)" 
                        class="w-full flex items-center justify-between text-left font-bold text-sm sm:text-base text-slate-850 hover:text-blue-600 transition cursor-pointer"
                    >
                        <span>1. O professor esqueceu de lançar a chamada no domingo. Como lançar na segunda-feira?</span>
                        <span class="text-slate-400 font-normal text-lg" x-text="openFaq === 1 ? '−' : '+'"></span>
                    </button>
                    <div x-show="openFaq === 1" x-cloak class="mt-2 text-xs sm:text-sm text-slate-600 leading-relaxed pl-2 border-l-2 border-blue-500">
                        Por regra de segurança, professores só lançam na data vigente (hoje). Caso passe do domingo, o <strong>Superintendente ou a Secretaria</strong> deve acessar a tela de chamada, selecionar a data do domingo passado no topo e realizar o lançamento ou retificação com permissão administrativa.
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="py-3" x-show="matches('visitante novo aluno sala aula matricular na hora')">
                    <button 
                        type="button" 
                        @click="toggleFaq(2)" 
                        class="w-full flex items-center justify-between text-left font-bold text-sm sm:text-base text-slate-850 hover:text-blue-600 transition cursor-pointer"
                    >
                        <span>2. Como matricular um visitante que decidiu ficar na classe durante a aula?</span>
                        <span class="text-slate-400 font-normal text-lg" x-text="openFaq === 2 ? '−' : '+'"></span>
                    </button>
                    <div x-show="openFaq === 2" x-cloak class="mt-2 text-xs sm:text-sm text-slate-600 leading-relaxed pl-2 border-l-2 border-blue-500">
                        Na tela de chamada da sua turma, toque no botão azul <strong>"+ Matricular Aluno"</strong>. Digite o nome completo e toque em salvar. O sistema cadastra o aluno na hora no banco de dados e já marca presença verde para ele no domingo de hoje, sem burocracia!
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div class="py-3" x-show="matches('aluno mudou sala transferir jovens adultos')">
                    <button 
                        type="button" 
                        @click="toggleFaq(3)" 
                        class="w-full flex items-center justify-between text-left font-bold text-sm sm:text-base text-slate-850 hover:text-blue-600 transition cursor-pointer"
                    >
                        <span>3. O aluno completou a idade e deve mudar de turma. Como transferir?</span>
                        <span class="text-slate-400 font-normal text-lg" x-text="openFaq === 3 ? '−' : '+'"></span>
                    </button>
                    <div x-show="openFaq === 3" x-cloak class="mt-2 text-xs sm:text-sm text-slate-600 leading-relaxed pl-2 border-l-2 border-blue-500">
                        A Secretaria (ou o professor na gestão de alunos) clica em <strong>"Editar Aluno"</strong> e simplesmente altera o campo <strong>"Classe"</strong> (ex: de Juvenis para Jovens). O aluno passa a figurar na nova sala e todo o histórico anterior de presenças é mantido.
                    </div>
                </div>

                <!-- FAQ 4 -->
                <div class="py-3" x-show="matches('faltas consecutivas 3 faltas visitacao pastoral')">
                    <button 
                        type="button" 
                        @click="toggleFaq(4)" 
                        class="w-full flex items-center justify-between text-left font-bold text-sm sm:text-base text-slate-850 hover:text-blue-600 transition cursor-pointer"
                    >
                        <span>4. Como identificar alunos que precisam de visitação pastoral por faltas?</span>
                        <span class="text-slate-400 font-normal text-lg" x-text="openFaq === 4 ? '−' : '+'"></span>
                    </button>
                    <div x-show="openFaq === 4" x-cloak class="mt-2 text-xs sm:text-sm text-slate-600 leading-relaxed pl-2 border-l-2 border-blue-500">
                        No menu <strong>"Relatórios da EBD"</strong> (ou Relatórios da Turma para professores), clique na aba <strong>"Frequência Nominal & Faltosos"</strong>. O sistema calcula automaticamente os alunos com <strong>3 ou mais ausências consecutivas</strong> e exibe um alerta amarelo no topo com a lista destacada em vermelho, pronta para acionar os visitadores.
                    </div>
                </div>

                <!-- FAQ 5 -->
                <div class="py-3" x-show="matches('esqueci senha resetar recuperar professor')">
                    <button 
                        type="button" 
                        @click="toggleFaq(5)" 
                        class="w-full flex items-center justify-between text-left font-bold text-sm sm:text-base text-slate-850 hover:text-blue-600 transition cursor-pointer"
                    >
                        <span>5. Um professor esqueceu a senha dele. Como resolver rapidamente?</span>
                        <span class="text-slate-400 font-normal text-lg" x-text="openFaq === 5 ? '−' : '+'"></span>
                    </button>
                    <div x-show="openFaq === 5" x-cloak class="mt-2 text-xs sm:text-sm text-slate-600 leading-relaxed pl-2 border-l-2 border-blue-500">
                        O Secretário acessa o menu <strong>"Professores"</strong>, localiza o professor na lista e clica no botão <strong>"Resetar Senha"</strong>. A senha volta imediatamente para o padrão <code>senha123</code> e o professor já consegue logar no celular no mesmo instante.
                    </div>
                </div>

                <!-- FAQ 6 -->
                <div class="py-3" x-show="matches('whatsapp parabens felicitar aniversariante')">
                    <button 
                        type="button" 
                        @click="toggleFaq(6)" 
                        class="w-full flex items-center justify-between text-left font-bold text-sm sm:text-base text-slate-850 hover:text-blue-600 transition cursor-pointer"
                    >
                        <span>6. Como parabenizar os aniversariantes no WhatsApp?</span>
                        <span class="text-slate-400 font-normal text-lg" x-text="openFaq === 6 ? '−' : '+'"></span>
                    </button>
                    <div x-show="openFaq === 6" x-cloak class="mt-2 text-xs sm:text-sm text-slate-600 leading-relaxed pl-2 border-l-2 border-blue-500">
                        Na aba <strong>"Aniversariantes"</strong> dos Relatórios, selecione o mês atual. Ao lado de cada aluno com telefone cadastrado, existe um botão verde <strong>"Parabenizar (WhatsApp)"</strong>. Clicar nele abre o WhatsApp no celular com uma mensagem bíblica de parabéns já pronta, precisando apenas tocar em enviar!
                    </div>
                </div>
            </div>
        </div>

        <!-- =========================================================================
             7. RODAPÉ DE SUPORTE E CONTATO
             ========================================================================= -->
        <div class="print:hidden p-5 sm:p-6 rounded-2xl bg-slate-900 text-white flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3 text-center sm:text-left">
                <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center text-xl shrink-0">
                    💡
                </div>
                <div>
                    <h4 class="font-bold text-sm sm:text-base">Ainda tem alguma dúvida operacional?</h4>
                    <p class="text-xs text-slate-400">
                        Procure a Secretaria da sua congregação ou a Superintendência Geral da Escola Bíblica.
                    </p>
                </div>
            </div>

            <a 
                href="{{ route('dashboard') }}" 
                class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs sm:text-sm font-bold transition shadow-sm min-h-[44px] flex items-center justify-center shrink-0"
            >
                Voltar ao Painel Principal
            </a>
        </div>
    </div>
</x-app-layout>
