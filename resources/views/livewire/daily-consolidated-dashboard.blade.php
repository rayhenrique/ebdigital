<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 sm:py-7">
    <!-- Top Header Card: Identidade, Badges & Filtro de Data -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 sm:p-6 mb-6">
        <!-- Logotipo Compacto Institucional (Visível no topo no Mobile, exatamente como no mockup) -->
        <div class="flex flex-col items-center justify-center mb-4 sm:hidden">
            <img 
                src="{{ asset('images/logo-ad-transparent.png') }}" 
                alt="Logo Assembleia de Deus" 
                class="h-12 w-auto object-contain mb-1 drop-shadow-xs"
            />
            <span class="text-xs font-bold text-slate-850 font-display tracking-tight">Caderneta EBD Online</span>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <!-- Badges no topo -->
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100/80">
                        @php
                            $tenantService = app(\App\Services\TenantService::class);
                            $activeCongregation = $tenantService->getCongregation();
                        @endphp
                        @if($activeCongregation)
                            {{ $activeCongregation->is_headquarters ? '🏛️' : '⛪' }} {{ $activeCongregation->name }}
                        @elseif($tenantService->isAllCongregations())
                            🌐 Todas as Congregações
                        @else
                            Secretaria Geral da EBD
                        @endif
                    </span>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $deliveredClassesCount === $classesCount ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                        {{ $deliveredClassesCount }} de {{ $classesCount }} Classes Entregues
                    </span>
                </div>

                <!-- Título e Subtítulo -->
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-850 font-display mt-2">
                    Dashboard Consolidado
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1 font-medium">
                    Apuração em tempo real de presenças, frequência e valores arrecadados.
                </p>
            </div>

            <!-- Seletor de Data de Referência Moderno -->
            <div class="w-full sm:w-auto">
                <label for="dashboardDate" class="block text-xs font-semibold text-slate-600 mb-1.5">
                    Data de Referência
                </label>
                <div class="relative w-full sm:w-60">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 9v7.5" />
                        </svg>
                    </div>
                    <input 
                        type="date" 
                        id="dashboardDate" 
                        wire:model.live="selectedDate" 
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-800 shadow-sm focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-600 min-h-[48px] transition cursor-pointer"
                    >
                </div>
            </div>
        </div>
    </div>

    <!-- =========================================================================
         CENTRAL DE NOTIFICAÇÕES & LEMBRETES DA TURMA (Aniversariantes & Faltosos)
         ========================================================================= -->
    <div 
        id="lembretes-turma" 
        x-data="{ 
            isOpen: true, 
            activeTab: 'all',
            showMonthBirthdays: false 
        }" 
        class="mb-6"
    >
        @if($teacherNotifications['has_alerts'])
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/90 overflow-hidden transition-all">
                <!-- Cabeçalho da Central de Alertas -->
                <div class="p-4 sm:p-5 bg-gradient-to-r from-slate-900 via-blue-950 to-indigo-950 text-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-500/20 border border-blue-400/30 flex items-center justify-center text-xl shrink-0">
                            🔔
                        </div>
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h2 class="text-base sm:text-lg font-bold font-display tracking-tight text-white">
                                    Lembretes & Cuidado Pastoral
                                </h2>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-extrabold bg-rose-500/20 text-rose-300 border border-rose-400/30">
                                    {{ $teacherNotifications['total_alerts_count'] }} pendente(s)
                                </span>
                            </div>
                            <p class="text-xs text-slate-300 mt-0.5">
                                Acompanhe aniversariantes e alunos precisando de visita/contato na sua turma sem precisar abrir relatórios.
                            </p>
                        </div>
                    </div>

                    <!-- Controles: Filtros Rápidos & Recolher -->
                    <div class="flex items-center gap-1.5 self-end sm:self-auto shrink-0">
                        <div class="inline-flex rounded-xl bg-white/10 p-1 border border-white/10 text-xs">
                            <button 
                                type="button" 
                                @click="activeTab = 'all'" 
                                :class="activeTab === 'all' ? 'bg-white text-slate-900 font-bold shadow-xs' : 'text-slate-200 hover:text-white font-medium'"
                                class="px-2.5 py-1 rounded-lg transition min-h-[36px] cursor-pointer"
                            >
                                Todos ({{ $teacherNotifications['total_alerts_count'] }})
                            </button>
                            <button 
                                type="button" 
                                @click="activeTab = 'birthdays'" 
                                :class="activeTab === 'birthdays' ? 'bg-white text-slate-900 font-bold shadow-xs' : 'text-slate-200 hover:text-white font-medium'"
                                class="px-2.5 py-1 rounded-lg transition min-h-[36px] cursor-pointer flex items-center gap-1"
                            >
                                <span>🎂</span>
                                <span>{{ count($teacherNotifications['birthdays_week']) }}</span>
                            </button>
                            <button 
                                type="button" 
                                @click="activeTab = 'absents'" 
                                :class="activeTab === 'absents' ? 'bg-white text-slate-900 font-bold shadow-xs' : 'text-slate-200 hover:text-white font-medium'"
                                class="px-2.5 py-1 rounded-lg transition min-h-[36px] cursor-pointer flex items-center gap-1"
                            >
                                <span>⚠️</span>
                                <span>{{ count($teacherNotifications['chronic_absentees']) }}</span>
                            </button>
                        </div>

                        <!-- Botão Minimizar/Expandir -->
                        <button 
                            type="button" 
                            @click="isOpen = !isOpen" 
                            class="p-2 rounded-xl text-slate-300 hover:text-white hover:bg-white/10 transition min-h-[40px] min-w-[40px] flex items-center justify-center cursor-pointer"
                            :title="isOpen ? 'Recolher lembretes' : 'Expandir lembretes'"
                        >
                            <svg class="w-5 h-5 transition-transform duration-200" :class="isOpen ? 'rotate-180' : ''" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Conteúdo dos Lembretes (quando aberto) -->
                <div x-show="isOpen" x-collapse class="p-4 sm:p-5 space-y-5 bg-slate-50/50">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <!-- 1. BLOCO DE ANIVERSARIANTES -->
                        <div 
                            x-show="activeTab === 'all' || activeTab === 'birthdays'" 
                            class="bg-white rounded-2xl p-4 sm:p-5 border border-pink-100 shadow-2xs flex flex-col justify-between"
                        >
                            <div>
                                <div class="flex items-center justify-between pb-3 mb-3 border-b border-pink-50">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-lg bg-pink-50 text-pink-600 flex items-center justify-center text-base">
                                            🎂
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-bold text-slate-850">
                                                Aniversariantes da Turma
                                            </h3>
                                            <p class="text-[11px] text-slate-400">
                                                Hoje e próximos 7 dias
                                            </p>
                                        </div>
                                    </div>
                                    @if(count($teacherNotifications['birthdays_month']) > count($teacherNotifications['birthdays_week']))
                                        <button 
                                            type="button" 
                                            @click="showMonthBirthdays = !showMonthBirthdays"
                                            class="text-xs font-semibold text-pink-600 hover:text-pink-700 bg-pink-50 hover:bg-pink-100/80 px-2.5 py-1 rounded-lg transition min-h-[36px] cursor-pointer"
                                        >
                                            <span x-text="showMonthBirthdays ? 'Ver só semana' : 'Ver todo mês ({{ count($teacherNotifications['birthdays_month']) }})'"></span>
                                        </button>
                                    @endif
                                </div>

                                @php
                                    $birthdaysToShow = count($teacherNotifications['birthdays_week']) > 0 
                                        ? $teacherNotifications['birthdays_week'] 
                                        : $teacherNotifications['birthdays_month'];
                                @endphp

                                <div x-show="!showMonthBirthdays" class="space-y-2.5">
                                    @forelse($teacherNotifications['birthdays_week'] as $b)
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3 rounded-xl border {{ $b['is_today'] ? 'bg-pink-50/70 border-pink-200' : 'bg-slate-50/60 border-slate-100' }} gap-2.5">
                                            <div class="flex items-center gap-2.5 min-w-0">
                                                <div class="w-8 h-8 rounded-full {{ $b['is_today'] ? 'bg-pink-500 text-white' : 'bg-pink-100 text-pink-700' }} flex items-center justify-center text-xs font-bold shrink-0">
                                                    {{ $b['is_today'] ? '🎉' : strtoupper(substr($b['name'], 0, 1)) }}
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="flex items-center gap-1.5 flex-wrap">
                                                        <span class="text-xs font-bold text-slate-850 truncate max-w-[180px] sm:max-w-[220px]">
                                                            {{ $b['name'] }}
                                                        </span>
                                                        @if($b['is_today'])
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-pink-600 text-white animate-pulse">
                                                                Hoje! 🎉
                                                            </span>
                                                        @elseif($b['days_until'] === 1)
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                                                Amanhã
                                                            </span>
                                                        @else
                                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-medium bg-slate-200 text-slate-700">
                                                                Em {{ $b['days_until'] }} dias ({{ $b['formatted_day'] }})
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <p class="text-[11px] text-slate-400 truncate">
                                                        {{ $b['class_name'] }} • {{ $b['age'] }} anos
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- Ações Rápidas (WhatsApp de 1 clique) -->
                                            <div class="flex items-center gap-2 shrink-0 self-end sm:self-auto">
                                                @if($b['whatsapp_url'])
                                                    <a 
                                                        href="{{ $b['whatsapp_url'] }}" 
                                                        target="_blank" 
                                                        rel="noopener noreferrer"
                                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition min-h-[40px]"
                                                        title="Enviar felicitação de aniversário no WhatsApp"
                                                    >
                                                        <span>💬</span>
                                                        <span>Felicitar WhatsApp</span>
                                                    </a>
                                                @elseif($b['phone'])
                                                    <a 
                                                        href="tel:{{ preg_replace('/\D/', '', $b['phone']) }}" 
                                                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition min-h-[40px]"
                                                    >
                                                        <span>📞</span>
                                                        <span>Ligar</span>
                                                    </a>
                                                @else
                                                    <span class="text-[11px] text-slate-400 italic">
                                                        Sem telefone
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-4 text-xs text-slate-400">
                                            Nenhum aluno fazendo aniversário nos próximos 7 dias.
                                        </div>
                                    @endforelse
                                </div>

                                <!-- Exibição de todos do mês quando ativado -->
                                <div x-show="showMonthBirthdays" x-cloak class="space-y-2.5 max-h-64 overflow-y-auto pr-1">
                                    @forelse($teacherNotifications['birthdays_month'] as $b)
                                        <div class="flex items-center justify-between p-2.5 rounded-xl border {{ $b['is_today'] ? 'bg-pink-50 border-pink-200' : 'bg-slate-50/50 border-slate-100' }} gap-2 text-xs">
                                            <div class="min-w-0 flex items-center gap-2">
                                                <span class="font-bold text-slate-700 w-11 shrink-0 text-right">{{ $b['formatted_day'] }}</span>
                                                <div class="truncate">
                                                    <span class="font-bold text-slate-850 truncate block">{{ $b['name'] }}</span>
                                                    <span class="text-[10px] text-slate-400 truncate block">{{ $b['class_name'] }}</span>
                                                </div>
                                            </div>
                                            <div class="shrink-0">
                                                @if($b['whatsapp_url'])
                                                    <a 
                                                        href="{{ $b['whatsapp_url'] }}" 
                                                        target="_blank" 
                                                        rel="noopener noreferrer"
                                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-bold transition min-h-[36px]"
                                                    >
                                                        <span>💬</span>
                                                        <span>WhatsApp</span>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-4 text-xs text-slate-400">
                                            Nenhum aniversariante registrado neste mês.
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- 2. BLOCO DE FALTOSOS CRÔNICOS (3+ FALTAS SEGUIDAS) -->
                        <div 
                            x-show="activeTab === 'all' || activeTab === 'absents'" 
                            class="bg-white rounded-2xl p-4 sm:p-5 border border-amber-200/80 shadow-2xs flex flex-col justify-between"
                        >
                            <div>
                                <div class="flex items-center justify-between pb-3 mb-3 border-b border-amber-50">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-base">
                                            ⚠️
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-bold text-slate-850">
                                                Alunos com 3+ Faltas Consecutivas
                                            </h3>
                                            <p class="text-[11px] text-slate-400">
                                                Atenção pastoral e prevenção de evasão
                                            </p>
                                        </div>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-extrabold bg-amber-100 text-amber-800 border border-amber-200">
                                        {{ count($teacherNotifications['chronic_absentees']) }} aluno(s)
                                    </span>
                                </div>

                                <div class="space-y-2.5 max-h-72 overflow-y-auto pr-1">
                                    @forelse($teacherNotifications['chronic_absentees'] as $a)
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3 rounded-xl border bg-amber-50/40 border-amber-200/70 gap-2.5">
                                            <div class="flex items-center gap-2.5 min-w-0">
                                                <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center text-xs font-bold shrink-0">
                                                    {{ strtoupper(substr($a['name'], 0, 1)) }}
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="flex items-center gap-1.5 flex-wrap">
                                                        <span class="text-xs font-bold text-slate-850 truncate max-w-[180px] sm:max-w-[220px]">
                                                            {{ $a['name'] }}
                                                        </span>
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-100 text-rose-800 border border-rose-200">
                                                            {{ $a['consecutive_absents'] }} faltas seguidas
                                                        </span>
                                                    </div>
                                                    <p class="text-[11px] text-slate-500 truncate mt-0.5">
                                                        {{ $a['class_name'] }}
                                                        @if($a['last_missed_date'])
                                                            • Última aula: {{ $a['last_missed_date'] }}
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- Ações Rápidas (WhatsApp de 1 clique) -->
                                            <div class="flex items-center gap-2 shrink-0 self-end sm:self-auto">
                                                @if($a['whatsapp_url'])
                                                    <a 
                                                        href="{{ $a['whatsapp_url'] }}" 
                                                        target="_blank" 
                                                        rel="noopener noreferrer"
                                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition min-h-[40px]"
                                                        title="Enviar mensagem pastoral de acolhimento no WhatsApp"
                                                    >
                                                        <span>💬</span>
                                                        <span>Cuidado Pastoral</span>
                                                    </a>
                                                @elseif($a['phone'])
                                                    <a 
                                                        href="tel:{{ preg_replace('/\D/', '', $a['phone']) }}" 
                                                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition min-h-[40px]"
                                                    >
                                                        <span>📞</span>
                                                        <span>Ligar</span>
                                                    </a>
                                                @else
                                                    <span class="text-[11px] text-slate-400 italic">
                                                        Sem telefone
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-6 text-xs text-slate-400">
                                            <span class="text-lg block mb-1">🎉</span>
                                            Nenhum aluno com 3 faltas consecutivas. Frequência regular!
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Estado Positivo: Sem Alertas Ativos -->
            <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-2xs flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg shrink-0">
                        ✓
                    </div>
                    <div>
                        <h3 class="text-xs sm:text-sm font-bold text-slate-850">
                            Tudo em dia com a sua turma!
                        </h3>
                        <p class="text-[11px] text-slate-400">
                            Nenhum aniversariante nesta semana e nenhum aluno com 3 faltas consecutivas.
                        </p>
                    </div>
                </div>
                <a 
                    href="{{ route('reports.index') }}" 
                    wire:navigate 
                    class="text-xs font-semibold text-blue-600 hover:text-blue-700 bg-blue-50 hover:bg-blue-100/70 px-3 py-1.5 rounded-xl transition shrink-0 min-h-[36px] flex items-center"
                >
                    Ver Relatórios
                </a>
            </div>
        @endif
    </div>

    <!-- Grid de Métricas / KPI Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
        <!-- 1. Alunos Presentes -->
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm flex flex-col justify-between">
            <span class="text-xs font-semibold text-slate-500 block">Alunos Presentes</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-bold text-emerald-600">{{ $totalPresent }}</span>
                <span class="text-xs font-bold text-slate-400">/ {{ $totalEnrolled }}</span>
            </div>
            <span class="text-[11px] font-semibold text-emerald-600 mt-1 block">{{ $overallRate }}% de presença</span>
        </div>

        <!-- 2. Visitantes -->
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">Visitantes</span>
                <svg class="w-4 h-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.999-3.199a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                </svg>
            </div>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-bold text-indigo-600">{{ $totalVisitors }}</span>
                <span class="text-xs font-medium text-slate-400">pessoas</span>
            </div>
            <span class="text-[11px] text-slate-400 mt-1 block">não matriculados</span>
        </div>

        <!-- 3. Bíblias -->
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">Bíblias</span>
                <span class="text-sm">📖</span>
            </div>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-bold text-slate-850">{{ $totalBibles }}</span>
                <span class="text-xs font-medium text-slate-400">unid.</span>
            </div>
            <span class="text-[11px] text-slate-400 mt-1 block">trazidas à aula</span>
        </div>

        <!-- 4. Revistas -->
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">Revistas</span>
                <span class="text-sm">📚</span>
            </div>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-bold text-slate-850">{{ $totalMagazines }}</span>
                <span class="text-xs font-medium text-slate-400">unid.</span>
            </div>
            <span class="text-[11px] text-slate-400 mt-1 block">lição estudada</span>
        </div>

        <!-- 5. Total Geral (Destaque Azul Royal) -->
        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 text-white rounded-2xl p-4 shadow-md shadow-blue-500/20 flex flex-col justify-between">
            <span class="text-xs font-semibold text-blue-100 block">Total Geral</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-bold text-white">{{ $totalCongregation }}</span>
                <span class="text-xs text-blue-200">pessoas</span>
            </div>
            <span class="text-[11px] text-blue-100 mt-1 block font-medium">Alunos + Visitantes</span>
        </div>

        <!-- 6. Total Ofertas (Destaque Esmeralda Moderno) -->
        <div class="bg-emerald-500 text-white rounded-2xl p-4 shadow-md shadow-emerald-500/20 flex flex-col justify-between">
            <span class="text-xs font-semibold text-emerald-100 block">Total Ofertas</span>
            <div class="mt-2">
                <span class="text-xl sm:text-2xl font-bold text-white block truncate">
                    R$ {{ number_format($totalOfferings, 2, ',', '.') }}
                </span>
            </div>
            <span class="text-[11px] text-emerald-100 mt-1 block font-medium">todas as turmas</span>
        </div>
    </div>

    <!-- Quadro de Classes da EBD: Mobile Cards vs Desktop Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-5 sm:px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-base sm:text-lg font-bold text-slate-850 font-display">
                    Quadro de Classes da EBD
                </h2>
                <p class="text-xs text-slate-400">
                    Apuração por turma para {{ \Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }}
                </p>
            </div>
            @if(Auth::user()->isSecretario() || Auth::user()->isAdmin())
                <div class="flex items-center gap-2">
                    <a href="{{ route('classes.index') }}" class="text-xs font-semibold px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-600 hover:bg-slate-100 transition hidden sm:inline-flex items-center gap-1.5">
                        <span>🏫</span>
                        <span>Gerenciar Classes</span>
                    </a>
                    <a href="{{ route('alunos.index') }}" class="text-xs font-semibold px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-600 hover:bg-slate-100 transition hidden sm:inline-flex items-center gap-1.5">
                        <span>👥</span>
                        <span>Gerenciar Alunos</span>
                    </a>
                </div>
            @endif
        </div>

        <!-- A) No Mobile (block md:hidden): Lista de Cards de Classes com Accordion -->
        <div class="block md:hidden p-3 sm:p-4 space-y-3">
            @forelse($classReports as $report)
                <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-xs space-y-3.5" x-data="{ expanded: false }">
                    <!-- Topo do Card: Nome da Classe, Professores, Status e Accordion Chevron -->
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-slate-850 text-base leading-snug truncate">
                                {{ $report['class']->name }}
                            </h3>
                            <p class="text-xs text-slate-400 mt-0.5 truncate">
                                @if($report['class']->teachers->isNotEmpty())
                                    Prof: {{ $report['class']->teachers->pluck('name')->join(', ') }}
                                @else
                                    <span class="text-amber-600 italic">Sem professor</span>
                                @endif
                            </p>
                        </div>
                        <div class="flex items-center gap-1.5 shrink-0">
                            @if($report['is_delivered'])
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Entregue
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                    Pendente
                                </span>
                            @endif

                            <!-- Botão Chevron Toggle do Accordion -->
                            <button 
                                type="button" 
                                @click="expanded = !expanded" 
                                class="p-1 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-50 transition-colors cursor-pointer"
                                aria-label="Expandir detalhes da classe"
                            >
                                <svg 
                                    class="w-5 h-5 transition-transform duration-200" 
                                    :class="{ 'rotate-180': expanded }"
                                    xmlns="http://www.w3.org/2000/svg" 
                                    fill="none" 
                                    viewBox="0 0 24 24" 
                                    stroke-width="2" 
                                    stroke="currentColor"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Corpo do Card: Mini-resumo em Grid 4 colunas -->
                    <div class="grid grid-cols-4 gap-2 bg-slate-50/80 p-3 rounded-xl border border-slate-100 text-center">
                        <div>
                            <span class="block text-base font-bold text-slate-800">{{ $report['enrolled'] }}</span>
                            <span class="block text-[10px] text-slate-400 font-medium uppercase tracking-tight">Matrícula</span>
                        </div>
                        <div>
                            <span class="block text-base font-bold text-slate-800">{{ $report['present'] }}</span>
                            <span class="block text-[10px] text-slate-400 font-medium uppercase tracking-tight">Presentes</span>
                        </div>
                        <div>
                            <span class="block text-base font-bold {{ $report['rate'] >= 70 ? 'text-emerald-600' : 'text-amber-600' }}">
                                {{ $report['rate'] }}%
                            </span>
                            <span class="block text-[10px] text-slate-400 font-medium uppercase tracking-tight">Frequência</span>
                        </div>
                        <div>
                            <span class="block text-base font-bold text-emerald-600 truncate">
                                R$ {{ number_format((float)$report['offerings'], 2, ',', '.') }}
                            </span>
                            <span class="block text-[10px] text-slate-400 font-medium uppercase tracking-tight">Ofertas</span>
                        </div>
                    </div>

                    <!-- Seção Expansível (Accordion / Detalhes) -->
                    <div x-show="expanded" x-cloak class="pt-2 border-t border-slate-100 grid grid-cols-3 gap-2 text-center text-xs">
                        <div class="bg-indigo-50/50 p-2 rounded-xl border border-indigo-100/60">
                            <span class="font-bold text-indigo-600 text-sm block">{{ $report['visitors'] }}</span>
                            <span class="text-slate-500 text-[10px] block">Visitantes</span>
                        </div>
                        <div class="bg-slate-50 p-2 rounded-xl border border-slate-100">
                            <span class="font-bold text-slate-800 text-sm block">{{ $report['bibles'] }}</span>
                            <span class="text-slate-500 text-[10px] block">Bíblias</span>
                        </div>
                        <div class="bg-slate-50 p-2 rounded-xl border border-slate-100">
                            <span class="font-bold text-slate-800 text-sm block">{{ $report['magazines'] }}</span>
                            <span class="text-slate-500 text-[10px] block">Revistas</span>
                        </div>
                    </div>

                    <!-- Botão de Ação Full-Width na Base do Card -->
                    @php
                        $canTakeAttendance = Auth::user()->isAdmin() || Auth::user()->isSecretario() || (Auth::user()->isProfessor() && $report['class']->teachers->contains('id', Auth::id()));
                    @endphp
                    <div>
                        @if($canTakeAttendance)
                            @if($report['is_delivered'])
                                <a 
                                    href="{{ route('chamada.take', ['class' => $report['class']->id, 'date' => $selectedDate]) }}" 
                                    class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-200/60 active:scale-[0.98] transition-all min-h-[48px] cursor-pointer"
                                >
                                    <span>Retificar Chamada</span>
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/>
                                    </svg>
                                </a>
                            @else
                                <a 
                                    href="{{ route('chamada.take', ['class' => $report['class']->id, 'date' => $selectedDate]) }}" 
                                    class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl text-xs sm:text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 shadow-md shadow-blue-500/15 active:scale-[0.98] transition-all min-h-[48px] cursor-pointer"
                                >
                                    <span>Lançar Chamada</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                                    </svg>
                                </a>
                            @endif
                        @else
                            <div class="w-full flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl text-xs font-medium text-slate-400 bg-slate-50 border border-slate-100 min-h-[44px]">
                                <span>Apenas professor desta classe ou secretaria</span>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-slate-400 text-sm">
                    Nenhuma classe cadastrada no sistema.
                </div>
            @endforelse
        </div>

        <!-- B) No Desktop (hidden md:block): Tabela Moderna -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500 uppercase text-[11px] font-bold tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Classe</th>
                        <th class="px-4 py-4 text-center">Status</th>
                        <th class="px-4 py-4 text-center">Matrícula</th>
                        <th class="px-4 py-4 text-center">Presentes</th>
                        <th class="px-4 py-4 text-center">Frequência</th>
                        <th class="px-4 py-4 text-center">Visitantes</th>
                        <th class="px-4 py-4 text-center">Total</th>
                        <th class="px-4 py-4 text-center">Bíblias</th>
                        <th class="px-4 py-4 text-center">Revistas</th>
                        <th class="px-4 py-4 text-right">Ofertas</th>
                        <th class="px-6 py-4 text-right">Ação</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($classReports as $report)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-850">{{ $report['class']->name }}</div>
                                <div class="text-xs text-slate-400 mt-0.5">
                                    @if($report['class']->teachers->isNotEmpty())
                                        Prof: {{ $report['class']->teachers->pluck('name')->join(', ') }}
                                    @else
                                        <span class="italic text-amber-600">Nenhum professor vinculado</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-4 text-center">
                                @if($report['is_delivered'])
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Entregue
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                        Pendente
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center font-semibold text-slate-700">{{ $report['enrolled'] }}</td>
                            <td class="px-4 py-4 text-center font-bold text-emerald-600">{{ $report['present'] }}</td>
                            <td class="px-4 py-4 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold {{ $report['rate'] >= 70 ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                    {{ $report['rate'] }}%
                                </span>
                            </td>
                            <td class="px-4 py-4 text-center text-slate-700">{{ $report['visitors'] }}</td>
                            <td class="px-4 py-4 text-center font-bold text-indigo-600">{{ $report['total_attendance'] }}</td>
                            <td class="px-4 py-4 text-center text-slate-600">{{ $report['bibles'] }}</td>
                            <td class="px-4 py-4 text-center text-slate-600">{{ $report['magazines'] }}</td>
                            <td class="px-4 py-4 text-right font-bold text-emerald-600">
                                R$ {{ number_format((float)$report['offerings'], 2, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                @php
                                    $canTakeAttendance = Auth::user()->isAdmin() || Auth::user()->isSecretario() || (Auth::user()->isProfessor() && $report['class']->teachers->contains('id', Auth::id()));
                                @endphp
                                @if($canTakeAttendance)
                                    @if($report['is_delivered'])
                                        <a 
                                            href="{{ route('chamada.take', ['class' => $report['class']->id, 'date' => $selectedDate]) }}" 
                                            class="inline-flex items-center gap-1 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-200/60 transition min-h-[40px]"
                                        >
                                            Retificar Chamada
                                        </a>
                                    @else
                                        <a 
                                            href="{{ route('chamada.take', ['class' => $report['class']->id, 'date' => $selectedDate]) }}" 
                                            class="inline-flex items-center gap-1 px-3 py-2 rounded-xl text-xs font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 shadow-xs transition min-h-[40px]"
                                        >
                                            Lançar Chamada
                                        </a>
                                    @endif
                                @else
                                    <span class="text-xs text-slate-400 italic">Outro professor</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="px-6 py-10 text-center text-slate-400">
                                Nenhuma classe cadastrada no sistema.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
