<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    Minhas Classes (Chamada EBD)
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Selecione a classe para realizar a chamada ou conferir o relatório.
                </p>
            </div>
            <div class="w-full sm:w-auto">
                <form method="GET" action="{{ route('chamada.index') }}" class="flex items-center gap-2 w-full sm:w-auto">
                    <label for="date" class="text-xs font-semibold text-gray-600 shrink-0">Data da Aula:</label>
                    <input 
                        type="date" 
                        id="date" 
                        name="date" 
                        value="{{ $selectedDate }}" 
                        onchange="this.form.submit()" 
                        class="w-full sm:w-auto rounded-xl border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm min-h-[48px] px-3 font-semibold text-gray-700"
                    >
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @forelse($turmas as $class)
                    @php 
                        $record = $lessonRecords[$class->id] ?? null; 
                    @endphp
                    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $record ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                    {{ $record ? '✓ Chamada Entregue' : '⏱ Pendente' }}
                                </span>
                                <span class="text-xs text-gray-400 font-medium">
                                    {{ $class->active_students_count }} alunos
                                </span>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $class->name }}</h3>
                            <p class="text-xs text-gray-500 mb-4 line-clamp-2">{{ $class->description ?? 'Sem descrição' }}</p>

                            @if($record)
                                <div class="bg-gray-50 rounded-xl p-3 mb-4 text-xs space-y-1">
                                    <div class="flex justify-between text-gray-600">
                                        <span>Presentes:</span>
                                        <span class="font-bold text-gray-900">{{ $record->present_students_count }} / {{ $record->total_students_count }}</span>
                                    </div>
                                    <div class="flex justify-between text-gray-600">
                                        <span>Visitantes:</span>
                                        <span class="font-bold text-gray-900">{{ $record->visitors_count }}</span>
                                    </div>
                                    <div class="flex justify-between text-gray-600">
                                        <span>Ofertas:</span>
                                        <span class="font-bold text-emerald-600">R$ {{ number_format((float)$record->offerings_amount, 2, ',', '.') }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <a 
                            href="{{ route('chamada.take', ['class' => $class->id, 'date' => $selectedDate]) }}"
                            class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl font-bold text-sm min-h-[48px] transition {{ $record ? 'bg-indigo-50 text-indigo-700 hover:bg-indigo-100' : 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-md shadow-indigo-100' }}"
                        >
                            {{ $record ? 'Ver / Retificar Chamada' : 'Lançar Chamada Agora' }}
                        </a>
                    </div>
                @empty
                    <div class="col-span-full bg-white rounded-2xl p-12 text-center text-gray-500 border border-gray-100">
                        <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <h4 class="text-base font-bold text-gray-800">Nenhuma classe vinculada</h4>
                        <p class="text-xs text-gray-500 mt-1">Você não possui classes atribuídas ao seu usuário no momento.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
