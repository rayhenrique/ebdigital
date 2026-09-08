<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    Logs de Auditoria & Governança
                </h2>
                <p class="text-xs text-gray-500 mt-0.5">Rastreabilidade completa de acessos, alterações e retificações.</p>
            </div>
            <form method="POST" action="{{ route('admin.audit.purge') }}" onsubmit="return confirm('ATENÇÃO: Deseja realmente expurgar os registros de auditoria com mais de 30 dias? Esta ação não pode ser desfeita.');" class="w-full sm:w-auto">
                @csrf
                <button 
                    type="submit" 
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 hover:bg-rose-100 text-xs font-bold transition min-h-[48px]"
                >
                    🗑 Expurgar Logs (> 30 dias)
                </button>
            </form>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Filtros -->
            <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm mb-6">
                <form method="GET" action="{{ route('admin.audit.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <input 
                            type="text" 
                            name="action" 
                            value="{{ request('action') }}" 
                            placeholder="Filtrar por ação (ex: ATTENDANCE, LOGIN)..." 
                            class="w-full rounded-xl border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 min-h-[48px] px-3"
                        >
                    </div>
                    <div>
                        <input 
                            type="date" 
                            name="date" 
                            value="{{ request('date') }}" 
                            class="w-full rounded-xl border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 min-h-[48px] px-3"
                        >
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 px-5 py-2 bg-gray-900 text-white rounded-xl text-xs font-bold hover:bg-gray-800 min-h-[48px]">
                            Filtrar
                        </button>
                        @if(request()->hasAny(['action', 'date']))
                            <a href="{{ route('admin.audit.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl text-xs font-bold hover:bg-gray-200 flex items-center justify-center min-h-[48px]">
                                Limpar
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Tabela e Lista de Auditoria -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <!-- Visão Mobile (Cards) -->
                <div class="block md:hidden divide-y divide-gray-100">
                    @forelse($logs as $log)
                        <div class="p-4 space-y-2">
                            <div class="flex items-center justify-between gap-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-800">
                                    {{ $log->action }}
                                </span>
                                <span class="text-xs text-gray-400">
                                    {{ $log->created_at->format('d/m/Y H:i') }}
                                </span>
                            </div>

                            <div class="text-xs">
                                <span class="font-bold text-gray-900">{{ $log->user ? $log->user->name : 'Sistema / Excluído' }}</span>
                                @if($log->user)
                                    <span class="text-gray-400">({{ $log->user->email }})</span>
                                @endif
                            </div>

                            <div class="bg-gray-50 p-2.5 rounded-xl text-xs space-y-1">
                                <div class="font-semibold text-gray-700">
                                    {{ class_basename($log->auditable_type) }} #{{ $log->auditable_id }}
                                </div>
                                @if($log->payload_after)
                                    <pre class="bg-white p-2 rounded-lg text-[10px] text-gray-600 overflow-x-auto max-h-32 border border-gray-100">{{ json_encode($log->payload_after, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                @endif
                                <div class="text-[10px] text-gray-400 font-mono pt-1 text-right">
                                    IP: {{ $log->ip_address ?? '127.0.0.1' }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500 text-sm">
                            Nenhum registro de auditoria encontrado.
                        </div>
                    @endforelse
                </div>

                <!-- Visão Desktop (Tabela) -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-gray-500 uppercase text-[11px] font-bold tracking-wider">
                            <tr>
                                <th class="px-6 py-3.5">Data / Hora</th>
                                <th class="px-4 py-3.5">Usuário</th>
                                <th class="px-4 py-3.5 text-center">Ação</th>
                                <th class="px-6 py-3.5">Detalhes / Payloads</th>
                                <th class="px-4 py-3.5 text-right">IP</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($logs as $log)
                                <tr class="hover:bg-gray-50/70 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-600">
                                        {{ $log->created_at->format('d/m/Y H:i:s') }}
                                    </td>
                                    <td class="px-4 py-4">
                                        @if($log->user)
                                            <div class="font-bold text-gray-900">{{ $log->user->name }}</div>
                                            <div class="text-xs text-gray-400">{{ $log->user->email }}</div>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Sistema / Excluído</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-gray-100 text-gray-800">
                                            {{ $log->action }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-xs">
                                        <div class="font-semibold text-gray-700 mb-1">
                                            {{ class_basename($log->auditable_type) }} #{{ $log->auditable_id }}
                                        </div>
                                        @if($log->payload_after)
                                            <pre class="bg-gray-50 p-2 rounded-lg text-[11px] text-gray-600 overflow-x-auto max-w-md">{{ json_encode($log->payload_after, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-right text-xs text-gray-400 font-mono">
                                        {{ $log->ip_address ?? '127.0.0.1' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                        Nenhum registro de auditoria encontrado.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($logs->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $logs->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
