@php
    /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\EbdClass> $ebdClasses */
    $ebdClasses = $ebdClasses ?? $classes;
@endphp
<div>
    <!-- =========================================================================
         1. CABEÇALHO OFICIAL DE IMPRESSÃO (Visível apenas ao Imprimir em A4)
         ========================================================================= -->
    <div class="hidden print:block mb-8 pb-4 border-b-2 border-slate-800 text-center">
        <div class="flex items-center justify-center gap-3 mb-2">
            <img src="{{ asset('images/logo-ad-transparent.png') }}" alt="Logo" class="h-16 w-auto object-contain">
            <div class="text-left">
                <h1 class="text-lg font-black uppercase tracking-wider text-slate-900 leading-tight">
                    Igreja Evangélica Assembleia de Deus
                </h1>
                <p class="text-xs font-bold text-slate-700 tracking-wide">
                    Superintendência da Escola Bíblica Dominical
                </p>
                <p class="text-[11px] text-slate-500">
                    Sistema de Gestão Caderneta EBD Digital
                </p>
            </div>
        </div>

        <div class="mt-3 py-1.5 px-4 bg-slate-100 rounded-lg inline-block text-xs font-bold text-slate-850">
            @if($activeTab === 'consolidated')
                RELATÓRIO CONSOLIDADO DO PERÍODO: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} a {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
            @elseif($activeTab === 'students')
                FICHA NOMINAL DE FREQUÊNCIA: {{ $nominalData['selected_class']?->name ?? 'Classe' }} ({{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} a {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }})
            @elseif($activeTab === 'birthdays')
                RELATÓRIO DE ANIVERSARIANTES: MÊS DE {{ strtoupper($birthdayData['month_name']) }}
            @endif
        </div>
        <p class="text-[10px] text-slate-400 mt-1">
            Emitido em: {{ now()->format('d/m/Y \à\s H:i') }} | Por: {{ Auth::user()->name }} {{ Auth::user()->isProfessor() ? '(Professor)' : '' }}
        </p>
    </div>

    @if(Auth::user()->isProfessor() && $ebdClasses->isEmpty())
        <div class="print:hidden p-5 rounded-2xl bg-amber-50 border border-amber-200 text-center mb-6">
            <div class="w-12 h-12 mx-auto mb-2 rounded-full bg-amber-100 flex items-center justify-center text-2xl">
                ⚠️
            </div>
            <h3 class="text-sm font-bold text-amber-900">Nenhuma sala vinculada</h3>
            <p class="text-xs text-amber-700 mt-1 max-w-md mx-auto">
                Você ainda não possui nenhuma sala de EBD vinculada ao seu usuário. Solicite à secretaria da congregação a vinculação da sua turma para visualizar os relatórios analíticos.
            </p>
        </div>
    @endif

    <!-- =========================================================================
         2. NAVEGAÇÃO POR ABAS (TABS) - Oculto na Impressão
         ========================================================================= -->
    <div class="print:hidden flex flex-wrap gap-2 mb-6 border-b border-slate-200 pb-3">
        <button 
            type="button" 
            wire:click="setTab('consolidated')" 
            class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition min-h-[44px] cursor-pointer
                {{ $activeTab === 'consolidated' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}"
        >
            <svg class="w-4.5 h-4.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
            </svg>
            <span>Consolidado por Período</span>
        </button>

        <button 
            type="button" 
            wire:click="setTab('students')" 
            class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition min-h-[44px] cursor-pointer
                {{ $activeTab === 'students' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}"
        >
            <svg class="w-4.5 h-4.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
            </svg>
            <span>Frequência Nominal & Faltosos</span>
        </button>

        <button 
            type="button" 
            wire:click="setTab('birthdays')" 
            class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition min-h-[44px] cursor-pointer
                {{ $activeTab === 'birthdays' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}"
        >
            <span class="text-base">🎂</span>
            <span>Aniversariantes</span>
        </button>
    </div>

    <!-- =========================================================================
         3. BARRA DE FILTROS REATIVOS - Oculta na Impressão
         ========================================================================= -->
    <div class="print:hidden bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs mb-6 space-y-4">
        @if($activeTab === 'consolidated' || $activeTab === 'students')
            <!-- Atalhos de Período Rápido -->
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                    Atalhos Rápidos de Período:
                </label>
                <div class="flex flex-wrap gap-1.5">
                    <button 
                        type="button" 
                        wire:click="setShortcut('month')" 
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition cursor-pointer min-h-[36px]
                            {{ $currentShortcut === 'month' ? 'bg-blue-100 text-blue-700 border border-blue-200' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}"
                    >
                        Este Mês
                    </button>
                    <button 
                        type="button" 
                        wire:click="setShortcut('q1')" 
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition cursor-pointer min-h-[36px]
                            {{ $currentShortcut === 'q1' ? 'bg-blue-100 text-blue-700 border border-blue-200' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}"
                    >
                        1º Trimestre (Jan-Mar)
                    </button>
                    <button 
                        type="button" 
                        wire:click="setShortcut('q2')" 
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition cursor-pointer min-h-[36px]
                            {{ $currentShortcut === 'q2' ? 'bg-blue-100 text-blue-700 border border-blue-200' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}"
                    >
                        2º Trimestre (Abr-Jun)
                    </button>
                    <button 
                        type="button" 
                        wire:click="setShortcut('q3')" 
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition cursor-pointer min-h-[36px]
                            {{ $currentShortcut === 'q3' ? 'bg-blue-100 text-blue-700 border border-blue-200' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}"
                    >
                        3º Trimestre (Jul-Set)
                    </button>
                    <button 
                        type="button" 
                        wire:click="setShortcut('q4')" 
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition cursor-pointer min-h-[36px]
                            {{ $currentShortcut === 'q4' ? 'bg-blue-100 text-blue-700 border border-blue-200' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}"
                    >
                        4º Trimestre (Out-Dez)
                    </button>
                    <button 
                        type="button" 
                        wire:click="setShortcut('year')" 
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition cursor-pointer min-h-[36px]
                            {{ $currentShortcut === 'year' ? 'bg-blue-100 text-blue-700 border border-blue-200' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}"
                    >
                        Ano Vigente ({{ now()->format('Y') }})
                    </button>
                </div>
            </div>

            <!-- Seletores de Data Personalizada e Classe -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 pt-2 border-t border-slate-100">
                <div>
                    <label for="startDate" class="block text-xs font-bold text-slate-700 mb-1">Data Inicial</label>
                    <input 
                        id="startDate" 
                        type="date" 
                        wire:model.live="startDate" 
                        class="block w-full text-xs sm:text-sm rounded-xl border border-slate-200 py-2.5 px-3 bg-white focus:ring-2 focus:ring-blue-600 min-h-[44px]"
                    />
                </div>

                <div>
                    <label for="endDate" class="block text-xs font-bold text-slate-700 mb-1">Data Final</label>
                    <input 
                        id="endDate" 
                        type="date" 
                        wire:model.live="endDate" 
                        class="block w-full text-xs sm:text-sm rounded-xl border border-slate-200 py-2.5 px-3 bg-white focus:ring-2 focus:ring-blue-600 min-h-[44px]"
                    />
                </div>

                @if($activeTab === 'students')
                    <div>
                        <label for="selectedClassId" class="block text-xs font-bold text-slate-700 mb-1">Classe Selecionada</label>
                        <select 
                            id="selectedClassId" 
                            wire:model.live="selectedClassId" 
                            class="block w-full text-xs sm:text-sm rounded-xl border border-slate-200 py-2.5 px-3 bg-white focus:ring-2 focus:ring-blue-600 min-h-[44px]"
                        >
                            @forelse($ebdClasses as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @empty
                                <option value="">Nenhuma classe vinculada</option>
                            @endforelse
                        </select>
                    </div>
                @endif
            </div>
        @else
            <!-- Filtros da Aba de Aniversariantes -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="selectedMonth" class="block text-xs font-bold text-slate-700 mb-1">Mês de Aniversário</label>
                    <select 
                        id="selectedMonth" 
                        wire:model.live="selectedMonth" 
                        class="block w-full text-xs sm:text-sm rounded-xl border border-slate-200 py-2.5 px-3 bg-white focus:ring-2 focus:ring-blue-600 min-h-[44px]"
                    >
                        <option value="1">Janeiro</option>
                        <option value="2">Fevereiro</option>
                        <option value="3">Março</option>
                        <option value="4">Abril</option>
                        <option value="5">Maio</option>
                        <option value="6">Junho</option>
                        <option value="7">Julho</option>
                        <option value="8">Agosto</option>
                        <option value="9">Setembro</option>
                        <option value="10">Outubro</option>
                        <option value="11">Novembro</option>
                        <option value="12">Dezembro</option>
                    </select>
                </div>

                <div>
                    <label for="birthdayClassId" class="block text-xs font-bold text-slate-700 mb-1">Filtrar por Classe</label>
                    <select 
                        id="birthdayClassId" 
                        wire:model.live="birthdayClassId" 
                        class="block w-full text-xs sm:text-sm rounded-xl border border-slate-200 py-2.5 px-3 bg-white focus:ring-2 focus:ring-blue-600 min-h-[44px]"
                    >
                        <option value="">{{ Auth::user()->isProfessor() ? 'Todas as Minhas Classes' : 'Todas as Classes' }}</option>
                        @foreach($ebdClasses as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endif
    </div>

    <!-- =========================================================================
         4. CONTEÚDO DA ABA 1: CONSOLIDADO GERAL DO PERÍODO
         ========================================================================= -->
    @if($activeTab === 'consolidated')
        <!-- Cards de Métricas Acumuladas -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
            <!-- Aulas no Período -->
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-xs">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Aulas / Domingos</span>
                <span class="text-2xl font-black text-slate-850 mt-1 block">{{ $consolidatedData['unique_sundays'] }}</span>
                <span class="text-[11px] text-slate-500 font-medium">ministradas</span>
            </div>

            <!-- Média Presentes -->
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-xs">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Média Presentes</span>
                <span class="text-2xl font-black text-blue-600 mt-1 block">{{ $consolidatedData['avg_present_per_sunday'] }}</span>
                <span class="text-[11px] text-slate-500 font-medium">alunos / domingo</span>
            </div>

            <!-- Frequência Geral -->
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-xs">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Frequência Geral</span>
                <span class="text-2xl font-black text-emerald-600 mt-1 block">{{ $consolidatedData['overall_rate'] }}%</span>
                <span class="text-[11px] text-slate-500 font-medium">taxa média</span>
            </div>

            <!-- Total Visitantes -->
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-xs">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Visitantes</span>
                <span class="text-2xl font-black text-indigo-600 mt-1 block">{{ $consolidatedData['total_visitors'] }}</span>
                <span class="text-[11px] text-slate-500 font-medium">total acumulado</span>
            </div>

            <!-- Bíblias & Revistas -->
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-xs">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Bíblias / Revistas</span>
                <span class="text-2xl font-black text-amber-600 mt-1 block">{{ $consolidatedData['total_bibles'] }} / {{ $consolidatedData['total_magazines'] }}</span>
                <span class="text-[11px] text-slate-500 font-medium">total trazido</span>
            </div>

            <!-- Total Ofertas -->
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-xs">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Ofertas</span>
                <span class="text-2xl font-black text-emerald-700 mt-1 block">R$ {{ number_format($consolidatedData['total_offerings'], 2, ',', '.') }}</span>
                <span class="text-[11px] text-slate-500 font-medium">arrecadação</span>
            </div>
        </div>

        <!-- Tabela Comparativa de Classes -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-850">
                    {{ Auth::user()->isProfessor() ? 'Desempenho das Minhas Classes no Período' : 'Desempenho Consolidado por Classe no Período' }}
                </h3>
                <span class="text-xs font-medium text-slate-500">
                    {{ count($consolidatedData['classes_breakdown']) }} {{ Auth::user()->isProfessor() ? 'classe(s) vinculada(s)' : 'classes registradas' }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] font-extrabold tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="px-4 py-3">Classe</th>
                            <th class="px-3 py-3 text-center">Matriculados</th>
                            <th class="px-3 py-3 text-center">Aulas</th>
                            <th class="px-3 py-3 text-center">Média Presentes</th>
                            <th class="px-3 py-3 text-center">Taxa Assiduidade</th>
                            <th class="px-3 py-3 text-center">Visitantes</th>
                            <th class="px-4 py-3 text-right">Total Ofertas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        @forelse($consolidatedData['classes_breakdown'] as $row)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-4 py-3">
                                    <span class="font-bold text-slate-850 block">{{ $row['name'] }}</span>
                                    <span class="text-[11px] text-slate-400 block">{{ $row['teachers'] }}</span>
                                </td>
                                <td class="px-3 py-3 text-center font-bold text-slate-800">
                                    {{ $row['enrolled'] }}
                                </td>
                                <td class="px-3 py-3 text-center">
                                    {{ $row['lessons_count'] }}
                                </td>
                                <td class="px-3 py-3 text-center font-bold text-blue-700">
                                    {{ $row['avg_present'] }}
                                </td>
                                <td class="px-3 py-3 text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold
                                        {{ $row['rate'] >= 75 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($row['rate'] >= 50 ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-rose-50 text-rose-700 border border-rose-200') }}">
                                        {{ $row['rate'] }}%
                                    </span>
                                </td>
                                <td class="px-3 py-3 text-center font-semibold text-slate-700">
                                    {{ $row['visitors_sum'] }}
                                </td>
                                <td class="px-4 py-3 text-right font-bold text-emerald-700">
                                    R$ {{ number_format($row['offerings_sum'], 2, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-slate-400 text-xs sm:text-sm">
                                    Nenhum registro de aula lançado no período selecionado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- =========================================================================
         5. CONTEÚDO DA ABA 2: FREQUÊNCIA NOMINAL & ALERTA DE FALTOSOS
         ========================================================================= -->
    @if($activeTab === 'students')
        @if($nominalData['selected_class'])
            <!-- Alerta de Alunos Faltosos Crônicos (Visitação) -->
            @if($nominalData['chronic_absents_count'] > 0)
                <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 mb-6 flex items-start gap-3">
                    <span class="text-xl">⚠️</span>
                    <div>
                        <h4 class="font-bold text-sm text-amber-900 leading-tight">
                            Atenção da Liderança: {{ $nominalData['chronic_absents_count'] }} aluno(s) com 3 ou mais faltas seguidas!
                        </h4>
                        <p class="text-xs text-amber-800 mt-1">
                            Alunos destacados abaixo com alerta vermelho estão ausentes nas últimas aulas consecutivas. Recomendado acionar a equipe de visitação e acompanhamento pastoral.
                        </p>
                    </div>
                </div>
            @endif

            <!-- Tabela Nominal com Grade de Aulas -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="p-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <h3 class="text-sm font-bold text-slate-850">
                            Ficha Nominal: {{ $nominalData['selected_class']->name }}
                        </h3>
                        <p class="text-xs text-slate-500">
                            Total de {{ $nominalData['total_lessons'] }} aula(s) registrada(s) no período
                        </p>
                    </div>
                    <div class="flex items-center gap-3 text-xs font-semibold">
                        <span class="inline-flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-emerald-500 inline-block"></span> Presente (P)</span>
                        <span class="inline-flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-rose-400 inline-block"></span> Falta (F)</span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs sm:text-sm">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] font-extrabold tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="px-4 py-3">Aluno</th>
                                <th class="px-3 py-3 text-center">Presenças</th>
                                <th class="px-3 py-3 text-center">Faltas</th>
                                <th class="px-3 py-3 text-center">% Assiduidade</th>
                                <th class="px-3 py-3 text-center">Histórico no Período</th>
                                <th class="px-4 py-3 text-center">Status / Acolhimento</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                            @forelse($nominalData['students_rows'] as $studentRow)
                                <tr class="hover:bg-slate-50/60 transition {{ $studentRow['is_chronic_absent'] ? 'bg-rose-50/40' : '' }}">
                                    <td class="px-4 py-3">
                                        <span class="font-bold text-slate-900 block">{{ $studentRow['name'] }}</span>
                                        <span class="text-[11px] text-slate-400 block">{{ $studentRow['phone'] ?? 'Sem telefone' }}</span>
                                    </td>
                                    <td class="px-3 py-3 text-center font-bold text-emerald-700">
                                        {{ $studentRow['presents'] }}
                                    </td>
                                    <td class="px-3 py-3 text-center font-bold text-rose-600">
                                        {{ $studentRow['absents'] }}
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold
                                            {{ $studentRow['rate'] >= 75 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($studentRow['rate'] >= 50 ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-rose-50 text-rose-700 border border-rose-200') }}">
                                            {{ $studentRow['rate'] }}%
                                        </span>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        <!-- Histórico Aula a Aula (Bolinhas) -->
                                        <div class="inline-flex items-center gap-1 justify-center max-w-[200px] overflow-x-auto py-1">
                                            @foreach($studentRow['history'] as $status)
                                                <span 
                                                    class="w-5 h-5 rounded-full text-[10px] font-bold flex items-center justify-center text-white shrink-0
                                                        {{ $status === 'P' ? 'bg-emerald-500' : 'bg-rose-400' }}"
                                                    title="{{ $status === 'P' ? 'Presente' : 'Falta' }}"
                                                >
                                                    {{ $status }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if($studentRow['is_chronic_absent'])
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                                🚨 {{ $studentRow['consecutive_absents'] }} Faltas Seguidas
                                            </span>
                                        @elseif($studentRow['rate'] >= 75)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700">
                                                ⭐ Aluno Assíduo
                                            </span>
                                        @else
                                            <span class="text-xs text-slate-400">Regular</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-slate-400 text-xs sm:text-sm">
                                        Nenhum aluno encontrado nesta classe.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="bg-white p-8 rounded-2xl border border-slate-200 text-center text-slate-400">
                Nenhuma classe cadastrada para visualização de frequência.
            </div>
        @endif
    @endif

    <!-- =========================================================================
         6. CONTEÚDO DA ABA 3: ANIVERSARIANTES DO MÊS
         ========================================================================= -->
    @if($activeTab === 'birthdays')
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-850">
                        Aniversariantes de {{ $birthdayData['month_name'] }}
                    </h3>
                    <p class="text-xs text-slate-500">
                        Total de {{ $birthdayData['total'] }} aniversariante(s) localizado(s)
                    </p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] font-extrabold tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="px-4 py-3 text-center">Dia</th>
                            <th class="px-4 py-3">Nome</th>
                            <th class="px-4 py-3">Classe</th>
                            <th class="px-4 py-3 text-center">Idade</th>
                            <th class="px-4 py-3 text-right">Contato & Felicitações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        @forelse($birthdayData['birthdays'] as $b)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-xl bg-blue-50 text-blue-700 font-bold border border-blue-200/70 text-xs">
                                        {{ $b['formatted_day'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 font-bold text-slate-900">
                                    {{ $b['name'] }}
                                </td>
                                <td class="px-4 py-3 text-slate-600">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                                        {{ $b['class_name'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center font-bold text-slate-600">
                                    {{ $b['age'] ? $b['age'] . ' anos' : '—' }}
                                </td>
                                <td class="px-4 py-3 text-right space-x-2">
                                    @if($b['whatsapp_url'])
                                        <a 
                                            href="{{ $b['whatsapp_url'] }}" 
                                            target="_blank" 
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-700 text-xs font-bold hover:bg-emerald-100 transition"
                                            title="Enviar Mensagem de Parabéns no WhatsApp"
                                        >
                                            <span>💬</span>
                                            <span>Parabenizar (WhatsApp)</span>
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-400 italic">Sem WhatsApp</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-slate-400 text-xs sm:text-sm">
                                    Nenhum aniversariante cadastrado para o mês de {{ $birthdayData['month_name'] }}.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- =========================================================================
         7. RODAPÉ OFICIAL DE ASSINATURAS (Visível Apenas ao Imprimir)
         ========================================================================= -->
    <div class="hidden print:block mt-16 pt-8 text-center">
        <div class="grid grid-cols-2 gap-12 max-w-2xl mx-auto">
            @if(Auth::user()->isProfessor())
                <div class="border-t border-slate-900 pt-2">
                    <p class="text-xs font-bold text-slate-900">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] text-slate-500">Professor(a) Responsável</p>
                </div>
                <div class="border-t border-slate-900 pt-2">
                    <p class="text-xs font-bold text-slate-900">Superintendente da EBD</p>
                    <p class="text-[10px] text-slate-500">Secretaria da Escola Bíblica Dominical</p>
                </div>
            @else
                <div class="border-t border-slate-900 pt-2">
                    <p class="text-xs font-bold text-slate-900">Pastor Presidente / Dirigente</p>
                    <p class="text-[10px] text-slate-500">Igreja Evangélica Assembleia de Deus</p>
                </div>
                <div class="border-t border-slate-900 pt-2">
                    <p class="text-xs font-bold text-slate-900">Superintendente da EBD</p>
                    <p class="text-[10px] text-slate-500">Secretaria da Escola Bíblica Dominical</p>
                </div>
            @endif
        </div>
    </div>
</div>
