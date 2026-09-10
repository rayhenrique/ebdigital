<div class="max-w-4xl mx-auto px-2 sm:px-6 lg:px-8 py-4">
    <!-- Header com Informações da Turma e Seleção de Data -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700">
                        Classe
                    </span>
                    @if($existingRecordId)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700">
                            Chamada Salva
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700">
                            Pendente de Envio
                        </span>
                    @endif
                </div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 mt-1">{{ $ebdClass->name }}</h1>
                <p class="text-sm text-gray-500">{{ $ebdClass->description ?? 'Escola Bíblica Dominical' }}</p>
            </div>

            <div class="w-full sm:w-auto">
                <label for="lessonDate" class="block text-xs font-semibold text-gray-600 mb-1">Data da Aula</label>
                <input 
                    type="date" 
                    id="lessonDate" 
                    wire:model.live="lessonDate" 
                    class="w-full sm:w-auto rounded-xl border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm min-h-[48px] px-3 font-semibold text-gray-700"
                >
            </div>
        </div>

        @if($isReadOnly)
            <div class="mt-4 p-3.5 bg-amber-50 border border-amber-200 rounded-xl flex items-start gap-3">
                <svg class="w-5 h-5 text-amber-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div class="text-xs sm:text-sm text-amber-800">
                    <span class="font-semibold">Modo Somente Leitura:</span> Professores têm permissão apenas para lançar chamadas da data de hoje. Para retificações de aulas passadas, solicite o ajuste à Secretaria.
                </div>
            </div>
        @endif

        @if (session('status'))
            <div class="mt-4 p-3.5 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3 text-emerald-800 text-sm font-medium">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ session('status') }}
            </div>
        @endif
    </div>

    <!-- Cards de Métricas em Tempo Real (Cabeçalho Reativo) -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between">
            <span class="text-xs font-medium text-gray-500">Matriculados</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-black text-gray-900">{{ $this->enrolledCount }}</span>
                <span class="text-xs text-gray-400">alunos</span>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between">
            <span class="text-xs font-medium text-emerald-600">Presentes</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-black text-emerald-600">{{ $this->presentCount }}</span>
                <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">{{ $this->attendanceRate }}%</span>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-between">
            <span class="text-xs font-medium text-rose-500">Ausentes</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-black text-rose-600">{{ $this->absentCount }}</span>
                <span class="text-xs text-gray-400">faltas</span>
            </div>
        </div>

        <div class="bg-indigo-600 p-4 rounded-2xl shadow-sm text-white flex flex-col justify-between">
            <span class="text-xs font-medium text-indigo-100">Total na Sala</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-black text-white">{{ $this->totalCongregation }}</span>
                <span class="text-xs text-indigo-200">+{{ (int) ($visitorsCount ?: 0) }} visit.</span>
            </div>
        </div>
    </div>

    <!-- Barra de Ações Rápidas -->
    @if(!$isReadOnly)
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
            <span class="text-sm font-semibold text-gray-700">Chamada dos Alunos ({{ count($students) }} matriculados)</span>
            <div class="flex flex-wrap items-center gap-2">
                <button 
                    type="button" 
                    wire:click="openQuickEnroll" 
                    class="flex-1 sm:flex-none text-xs font-bold px-3.5 py-2.5 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 shadow-sm shadow-indigo-200 active:scale-[0.98] transition min-h-[48px] flex items-center justify-center gap-1.5 cursor-pointer"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                    </svg>
                    <span>+ Matricular Aluno</span>
                </button>
                <button 
                    type="button" 
                    wire:click="markAllPresent" 
                    class="text-xs font-semibold px-3 py-2.5 rounded-xl bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition min-h-[48px] flex items-center justify-center text-center cursor-pointer"
                >
                    ✓ Todos Presentes
                </button>
                <button 
                    type="button" 
                    wire:click="markAllAbsent" 
                    class="text-xs font-semibold px-3 py-2.5 rounded-xl bg-gray-100 text-gray-700 hover:bg-gray-200 transition min-h-[48px] flex items-center justify-center text-center cursor-pointer"
                >
                    Desmarcar Todos
                </button>
            </div>
        </div>
    @endif

    <!-- Lista de Alunos (Mobile-First / Touch Targets >= 48px) -->
    <div class="space-y-2 mb-6 pb-20 sm:pb-0">
        @forelse($students as $student)
            @php $isPresent = $attendances[$student->id] ?? false; @endphp
            <div 
                wire:click="toggleAttendance({{ $student->id }})"
                class="flex items-center justify-between p-3.5 sm:p-4 rounded-2xl border transition select-none cursor-pointer min-h-[56px] {{ $isPresent ? 'bg-emerald-50/70 border-emerald-200 shadow-sm' : 'bg-white border-gray-100 hover:border-gray-200' }} {{ $isReadOnly ? 'cursor-not-allowed opacity-90' : '' }}"
            >
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm {{ $isPresent ? 'bg-emerald-500 text-white' : 'bg-gray-100 text-gray-500' }}">
                        {{ strtoupper(substr($student->name, 0, 2)) }}
                    </div>
                    <div>
                        <p class="text-sm sm:text-base font-semibold text-gray-900 leading-tight">{{ $student->name }}</p>
                        @if($student->phone)
                            <p class="text-xs text-gray-400 mt-0.5">{{ $student->phone }}</p>
                        @endif
                    </div>
                </div>

                <!-- Botão Toggle com Touch Target >= 48px -->
                <button 
                    type="button" 
                    wire:click.stop="toggleAttendance({{ $student->id }})"
                    @if($isReadOnly) disabled @endif
                    class="w-12 h-12 flex items-center justify-center rounded-xl transition {{ $isPresent ? 'bg-emerald-600 text-white shadow-sm' : 'bg-gray-100 text-gray-400 hover:bg-gray-200' }}"
                >
                    @if($isPresent)
                        <svg class="w-6 h-6 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    @else
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    @endif
                </button>
            </div>
        @empty
            <div class="bg-white rounded-2xl p-8 text-center text-gray-500 border border-gray-100">
                <p>Nenhum aluno ativo matriculado nesta classe.</p>
            </div>
        @endforelse
    </div>

    <!-- Formulário Consolidado de Rodapé (Estatísticas Agregadas) -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 mb-6">
        <h2 class="text-base font-bold text-gray-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            Estatísticas da Lição & Rodapé
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="lessonNumber" class="block text-xs font-semibold text-gray-700 mb-1">Número da Lição</label>
                <input 
                    type="text" 
                    id="lessonNumber" 
                    wire:model="lessonNumber" 
                    @if($isReadOnly) disabled @endif
                    placeholder="Ex: Lição 01" 
                    class="w-full rounded-xl border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm min-h-[48px] px-3"
                >
            </div>

            <div>
                <label for="lessonTitle" class="block text-xs font-semibold text-gray-700 mb-1">Tema / Título da Lição</label>
                <input 
                    type="text" 
                    id="lessonTitle" 
                    wire:model="lessonTitle" 
                    @if($isReadOnly) disabled @endif
                    placeholder="Ex: A Graça Manifestada" 
                    class="w-full rounded-xl border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm min-h-[48px] px-3"
                >
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
            <div>
                <label for="visitorsCount" class="block text-xs font-semibold text-gray-700 mb-1">Visitantes</label>
                <input 
                    type="number" 
                    id="visitorsCount" 
                    wire:model.live.debounce.300ms="visitorsCount" 
                    min="0"
                    @if($isReadOnly) disabled @endif
                    class="w-full rounded-xl border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm min-h-[48px] px-3 text-center font-bold"
                >
            </div>

            <div>
                <label for="biblesCount" class="block text-xs font-semibold text-gray-700 mb-1">Bíblias</label>
                <input 
                    type="number" 
                    id="biblesCount" 
                    wire:model.blur="biblesCount" 
                    min="0"
                    @if($isReadOnly) disabled @endif
                    class="w-full rounded-xl border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm min-h-[48px] px-3 text-center font-bold"
                >
            </div>

            <div>
                <label for="magazinesCount" class="block text-xs font-semibold text-gray-700 mb-1">Revistas</label>
                <input 
                    type="number" 
                    id="magazinesCount" 
                    wire:model.blur="magazinesCount" 
                    min="0"
                    @if($isReadOnly) disabled @endif
                    class="w-full rounded-xl border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm min-h-[48px] px-3 text-center font-bold"
                >
            </div>

            <div>
                <label for="offeringsAmount" class="block text-xs font-semibold text-gray-700 mb-1">Ofertas (R$)</label>
                <input 
                    type="number" 
                    step="0.01" 
                    id="offeringsAmount" 
                    wire:model.blur="offeringsAmount" 
                    min="0"
                    @if($isReadOnly) disabled @endif
                    class="w-full rounded-xl border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm min-h-[48px] px-3 text-center font-bold text-emerald-700"
                >
            </div>
        </div>

        <div class="mb-4">
            <label for="observations" class="block text-xs font-semibold text-gray-700 mb-1">Observações da Aula</label>
            <textarea 
                id="observations" 
                wire:model="observations" 
                rows="2" 
                @if($isReadOnly) disabled @endif
                placeholder="Anotações sobre a aula, pedidos de oração ou avisos..."
                class="w-full rounded-xl border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm p-3"
            ></textarea>
        </div>

        <!-- Botão de Salvar Mobile-First (Mínimo 48px de altura, feedback de loading) -->
        @if(!$isReadOnly)
            <button 
                type="button" 
                wire:click="requestSave" 
                wire:loading.attr="disabled"
                class="w-full flex items-center justify-center gap-2 px-6 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 transition min-h-[52px] text-base cursor-pointer"
            >
                <span wire:loading.remove wire:target="requestSave">
                    💾 Salvar Chamada da Aula
                </span>
                <span wire:loading wire:target="requestSave" class="flex items-center gap-2">
                    <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    Validando...
                </span>
            </button>
        @endif
    </div>

    <!-- Barra Flutuante Mobile de Ações Rápidas (Fixa na parte inferior no celular) -->
    @if(!$isReadOnly)
        <div class="sm:hidden fixed bottom-3 inset-x-3 z-40 bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl border border-gray-200 p-3 flex items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black text-sm">
                    ✓
                </div>
                <div>
                    <div class="flex items-baseline gap-1">
                        <span class="text-sm font-black text-emerald-600">{{ $this->presentCount }}</span>
                        <span class="text-xs text-gray-400">/ {{ $this->enrolledCount }}</span>
                        <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-1 py-0.2 rounded">{{ $this->attendanceRate }}%</span>
                    </div>
                    <span class="text-[10px] text-gray-400 block leading-none">Presentes</span>
                </div>
            </div>

            <button 
                type="button" 
                wire:click="requestSave" 
                wire:loading.attr="disabled"
                class="flex items-center justify-center gap-1.5 px-4 py-2.5 bg-indigo-600 active:bg-indigo-700 text-white font-bold rounded-xl text-xs shadow-md shadow-indigo-200 min-h-[44px] transition cursor-pointer"
            >
                <span wire:loading.remove wire:target="requestSave">💾 Gravar Aula</span>
                <span wire:loading wire:target="requestSave" class="flex items-center gap-1">Validando...</span>
            </button>
        </div>
    @endif

    <!-- Modal de Matrícula Rápida de Aluno -->
    @if($showQuickEnrollModal)
        <div 
            class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-end sm:items-center justify-center p-0 sm:p-4 transition-all"
        >
            <div 
                class="w-full sm:max-w-md bg-white rounded-t-3xl sm:rounded-2xl p-5 sm:p-6 shadow-2xl border border-slate-100 space-y-4 max-h-[90vh] overflow-y-auto"
                @click.away="$wire.closeQuickEnroll()"
            >
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-base font-bold">
                            🎓
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-850 font-display leading-tight">Matricular Novo Aluno</h3>
                            <p class="text-xs text-slate-500 font-medium">Turma: {{ $ebdClass->name }}</p>
                        </div>
                    </div>
                    <button 
                        type="button" 
                        wire:click="closeQuickEnroll" 
                        class="p-2 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition cursor-pointer"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit="quickEnrollStudent" class="space-y-3.5">
                    <div>
                        <label for="quick_name" class="block text-xs font-semibold text-slate-700 mb-1">
                            Nome Completo do Aluno <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="quick_name" 
                            wire:model="newStudentName" 
                            placeholder="Ex: Pedro Henrique dos Santos"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-medium text-slate-800 px-3 py-2.5 focus:bg-white focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 min-h-[48px]"
                            autofocus
                        >
                        @error('newStudentName')
                            <p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label for="quick_phone" class="block text-xs font-semibold text-slate-700 mb-1">
                                WhatsApp / Telefone
                            </label>
                            <input 
                                type="text" 
                                id="quick_phone" 
                                wire:model="newStudentPhone" 
                                placeholder="(82) 99999-9999"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-medium text-slate-800 px-3 py-2.5 focus:bg-white focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 min-h-[48px]"
                            >
                            @error('newStudentPhone')
                                <p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="quick_birth" class="block text-xs font-semibold text-slate-700 mb-1">
                                Data de Nascimento
                            </label>
                            <input 
                                type="date" 
                                id="quick_birth" 
                                wire:model="newStudentBirthDate" 
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-medium text-slate-800 px-3 py-2.5 focus:bg-white focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 min-h-[48px]"
                            >
                            @error('newStudentBirthDate')
                                <p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="p-3 bg-emerald-50/70 border border-emerald-100 rounded-xl text-[11px] text-emerald-800 flex items-center gap-2">
                        <span class="text-sm">✓</span>
                        <span>O aluno será matriculado nesta turma e marcado automaticamente como <strong>Presente</strong>.</span>
                    </div>

                    <div class="pt-2 flex flex-col sm:flex-row gap-2">
                        <button 
                            type="button" 
                            wire:click="closeQuickEnroll" 
                            class="w-full sm:w-auto flex-1 py-3 px-4 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 min-h-[48px] flex items-center justify-center cursor-pointer"
                        >
                            Cancelar
                        </button>
                        <button 
                            type="submit" 
                            class="w-full sm:w-auto flex-1 py-3 px-4 rounded-xl bg-indigo-600 text-white text-xs font-bold hover:bg-indigo-700 shadow-md shadow-indigo-200 min-h-[48px] flex items-center justify-center gap-1.5 cursor-pointer"
                        >
                            <span>Salvar e Marcar Presença</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Modal de Confirmação antes de Salvar -->
    @if($showConfirmModal)
        <div 
            class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-end sm:items-center justify-center p-0 sm:p-4 transition-all"
        >
            <div 
                class="w-full sm:max-w-lg bg-white rounded-t-3xl sm:rounded-2xl p-5 sm:p-6 shadow-2xl border border-slate-100 max-h-[90vh] overflow-y-auto"
                @click.away="$wire.cancelSave()"
            >
                <!-- Header -->
                <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg font-bold">
                            📋
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900 leading-tight">Confirmar Chamada</h3>
                            <p class="text-xs text-slate-500 font-medium">Revise os dados antes de salvar</p>
                        </div>
                    </div>
                    <button 
                        type="button" 
                        wire:click="cancelSave" 
                        class="p-2 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition cursor-pointer"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Resumo da Chamada -->
                <div class="space-y-3">
                    <!-- Turma e Data -->
                    <div class="bg-slate-50 rounded-xl p-3.5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold text-slate-500">Turma</p>
                                <p class="text-sm font-bold text-slate-900">{{ $ebdClass->name }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-semibold text-slate-500">Data</p>
                                <p class="text-sm font-bold text-slate-900">{{ \Carbon\Carbon::parse($lessonDate)->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Lição -->
                    @if($lessonNumber || $lessonTitle)
                        <div class="bg-indigo-50/60 rounded-xl p-3.5">
                            <p class="text-xs font-semibold text-indigo-600 mb-0.5">Lição</p>
                            <p class="text-sm font-bold text-slate-900">
                                {{ $lessonNumber }}{{ $lessonNumber && $lessonTitle ? ' — ' : '' }}{{ $lessonTitle }}
                            </p>
                        </div>
                    @endif

                    <!-- Presença -->
                    <div class="grid grid-cols-3 gap-2">
                        <div class="bg-emerald-50 rounded-xl p-3 text-center">
                            <p class="text-2xl font-black text-emerald-600">{{ $this->presentCount }}</p>
                            <p class="text-[10px] font-semibold text-emerald-700">Presentes</p>
                        </div>
                        <div class="bg-rose-50 rounded-xl p-3 text-center">
                            <p class="text-2xl font-black text-rose-600">{{ $this->absentCount }}</p>
                            <p class="text-[10px] font-semibold text-rose-700">Ausentes</p>
                        </div>
                        <div class="bg-indigo-50 rounded-xl p-3 text-center">
                            <p class="text-2xl font-black text-indigo-600">{{ $this->totalCongregation }}</p>
                            <p class="text-[10px] font-semibold text-indigo-700">Total na Sala</p>
                        </div>
                    </div>

                    <!-- Estatísticas -->
                    <div class="bg-slate-50 rounded-xl p-3.5">
                        <div class="grid grid-cols-2 gap-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-slate-500">Visitantes</span>
                                <span class="text-sm font-bold text-slate-800">{{ (int) ($visitorsCount ?: 0) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-slate-500">Bíblias</span>
                                <span class="text-sm font-bold text-slate-800">{{ (int) ($biblesCount ?: 0) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-slate-500">Revistas</span>
                                <span class="text-sm font-bold text-slate-800">{{ (int) ($magazinesCount ?: 0) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-slate-500">Ofertas</span>
                                <span class="text-sm font-bold text-emerald-700">R$ {{ number_format((float) ($offeringsAmount ?: 0), 2, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    @if($observations)
                        <div class="bg-amber-50/60 rounded-xl p-3.5">
                            <p class="text-xs font-semibold text-amber-700 mb-0.5">Observações</p>
                            <p class="text-xs text-slate-700">{{ $observations }}</p>
                        </div>
                    @endif
                </div>

                <!-- Botões de Ação -->
                <div class="pt-4 mt-4 border-t border-slate-100 flex flex-col sm:flex-row gap-2">
                    <button 
                        type="button" 
                        wire:click="cancelSave" 
                        class="w-full sm:w-auto flex-1 py-3 px-4 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 min-h-[48px] flex items-center justify-center cursor-pointer"
                    >
                        ← Voltar e Revisar
                    </button>
                    <button 
                        type="button" 
                        wire:click="confirmSave" 
                        wire:loading.attr="disabled"
                        class="w-full sm:w-auto flex-1 py-3 px-4 rounded-xl bg-emerald-600 text-white text-sm font-bold hover:bg-emerald-700 shadow-md shadow-emerald-200 min-h-[48px] flex items-center justify-center gap-2 cursor-pointer"
                    >
                        <span wire:loading.remove wire:target="confirmSave">✓ Confirmar e Salvar</span>
                        <span wire:loading wire:target="confirmSave" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            Gravando...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
