<!-- =========================================================================
     1. DESKTOP SIDEBAR (Fixo à Esquerda em telas md/lg)
     ========================================================================= -->
<aside 
    id="desktop-sidebar"
    class="hidden md:flex flex-col bg-white border-r border-slate-200/80 sticky top-0 h-screen z-30 shrink-0 select-none shadow-xs transition-all duration-300 ease-in-out overflow-x-hidden"
    :class="sidebarCollapsed ? 'w-20' : 'w-[268px] lg:w-72'"
>
    <!-- Brand / Logo EBD & Toggle Button -->
    <div 
        class="h-16 flex items-center border-b border-slate-100 shrink-0 transition-all duration-300"
        :class="sidebarCollapsed ? 'justify-center px-2' : 'justify-between px-4'"
    >
        <a 
            href="{{ route('dashboard') }}" 
            wire:navigate.hover 
            @click="if (sidebarCollapsed) { $event.preventDefault(); toggleSidebar(); }"
            class="flex items-center gap-2.5 group min-w-0" 
            :title="sidebarCollapsed ? 'Caderneta EBD (Clique para expandir)' : 'Caderneta EBD - Assembleia de Deus'"
        >
            <img 
                src="{{ asset('images/logo-ad-transparent.png') }}" 
                alt="Logo Assembleia de Deus" 
                class="h-9 w-auto object-contain transition-transform duration-200 group-hover:scale-105 shrink-0"
            />
            <div class="flex flex-col" x-show="!sidebarCollapsed" x-cloak>
                <span class="font-extrabold text-base lg:text-lg text-slate-850 tracking-tight leading-tight whitespace-nowrap">
                    Caderneta <span class="text-blue-600">EBD</span>
                </span>
                <span class="text-[10px] text-slate-400 font-medium leading-none whitespace-nowrap">
                    Assembleia de Deus
                </span>
            </div>
        </a>

        <!-- Botão de Recolher no Topo (Visível quando Expandido) -->
        <button 
            type="button"
            x-show="!sidebarCollapsed"
            @click="toggleSidebar()"
            title="Recolher menu lateral"
            class="p-1.5 rounded-xl text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition cursor-pointer flex items-center justify-center shrink-0"
        >
            <svg class="w-4.5 h-4.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect width="18" height="18" x="3" y="3" rx="2"/>
                <path d="M9 3v18"/>
                <path d="m14 9-3 3 3 3"/>
            </svg>
        </button>
    </div>

    <!-- Navegação com Scroll Vertical -->
    <div class="flex-1 px-3 py-4 space-y-5 overflow-y-auto overflow-x-hidden sidebar-scroll">
        <!-- Botão Expandir no Topo (Visível apenas quando Recolhido) -->
        <div x-show="sidebarCollapsed" class="flex justify-center mb-1" x-cloak>
            <button 
                type="button"
                @click="toggleSidebar()"
                title="Expandir menu lateral"
                class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-100 hover:text-blue-700 flex items-center justify-center transition shadow-2xs cursor-pointer"
            >
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="18" height="18" x="3" y="3" rx="2"/>
                    <path d="M9 3v18"/>
                    <path d="m11 9 3 3-3 3"/>
                </svg>
            </button>
        </div>

        <!-- Card Resumo do Usuário Logado -->
        <div 
            class="transition-all duration-300"
            :class="sidebarCollapsed ? 'flex justify-center p-0.5' : 'p-3 rounded-2xl bg-slate-50/90 border border-slate-100 flex items-center gap-3'"
        >
            <div 
                class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center text-sm font-bold shadow-xs shrink-0"
                :title="sidebarCollapsed ? '{{ Auth::user()->name }} ({{ Auth::user()->role?->label() ?? 'Usuário' }})' : ''"
            >
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="flex flex-col min-w-0 flex-1" x-show="!sidebarCollapsed" x-cloak>
                <span class="font-bold text-xs lg:text-sm text-slate-850 truncate" title="{{ Auth::user()->name }}">
                    {{ Auth::user()->name }}
                </span>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold shrink-0
                        {{ Auth::user()->isAdmin() ? 'bg-purple-50 text-purple-700 border border-purple-200/70' : (Auth::user()->isSecretario() ? 'bg-indigo-50 text-indigo-700 border border-indigo-200/70' : 'bg-blue-50 text-blue-700 border border-blue-200/70') }}">
                        {{ Auth::user()->role?->label() ?? 'Usuário' }}
                    </span>
                </div>
            </div>
        </div>

        @php
            $tenantService = app(\App\Services\TenantService::class);
            $availableCongregations = $tenantService->getAvailableCongregations();
            $isAllCongregations = $tenantService->isAllCongregations();
            $activeCongregationId = $tenantService->getCongregationId();
        @endphp

        <!-- Seletor / Indicador de Congregação -->
        @if(Auth::user()->isAdmin())
            <div class="px-1" x-show="!sidebarCollapsed" x-cloak>
                <form method="POST" action="{{ route('admin.congregacoes.switch') }}">
                    @csrf
                    <div class="p-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 shadow-2xs">
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">
                                Congregação Ativa
                            </span>
                            @if(!$isAllCongregations)
                                <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block animate-pulse"></span>
                            @endif
                        </div>
                        <select 
                            name="congregation_id" 
                            onchange="this.form.submit()" 
                            class="w-full text-xs font-bold rounded-xl border-slate-200 bg-white text-slate-800 py-1.5 pl-2 pr-7 focus:border-blue-500 focus:ring-blue-500 cursor-pointer shadow-2xs"
                        >
                            <option value="all" {{ $isAllCongregations ? 'selected' : '' }}>
                                🌐 Todas as Congregações
                            </option>
                            @foreach($availableCongregations as $c)
                                <option value="{{ $c->id }}" {{ $activeCongregationId === $c->id ? 'selected' : '' }}>
                                    {{ $c->is_headquarters ? '🏛️' : '⛪' }} {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <!-- Ícone compacto da congregação quando recolhido -->
            <div x-show="sidebarCollapsed" class="flex justify-center" x-cloak>
                <button 
                    type="button"
                    @click="toggleSidebar()"
                    title="Congregação: {{ $isAllCongregations ? 'Todas as Congregações' : ($availableCongregations->firstWhere('id', $activeCongregationId)?->name ?? 'Ativa') }} (Clique para alterar)"
                    class="w-11 h-11 rounded-xl bg-slate-50 hover:bg-blue-50 border border-slate-200/80 flex items-center justify-center text-base transition cursor-pointer relative shadow-2xs"
                >
                    {{ $isAllCongregations ? '🌐' : '⛪' }}
                    @if(!$isAllCongregations)
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    @endif
                </button>
            </div>
        @elseif(Auth::user()->congregation)
            <div class="px-1" x-show="!sidebarCollapsed" x-cloak>
                <div class="p-2.5 rounded-2xl bg-blue-50/70 border border-blue-100 flex items-center gap-2">
                    <span class="text-sm">⛪</span>
                    <div class="min-w-0 flex-1">
                        <span class="text-[10px] font-bold text-blue-500 uppercase tracking-wider block">Congregação</span>
                        <span class="text-xs font-bold text-blue-900 truncate block">{{ Auth::user()->congregation->name }}</span>
                    </div>
                </div>
            </div>
            <div x-show="sidebarCollapsed" class="flex justify-center" x-cloak>
                <div 
                    title="Congregação: {{ Auth::user()->congregation->name }}"
                    class="w-11 h-11 rounded-xl bg-blue-50/70 border border-blue-100 flex items-center justify-center text-sm"
                >
                    ⛪
                </div>
            </div>
        @endif

        <!-- Grupo 1: Menu Principal -->
        <div class="space-y-1">
            <div class="px-3 pb-1 text-[10px] font-extrabold text-slate-400 uppercase tracking-wider" x-show="!sidebarCollapsed" x-cloak>
                Principal
            </div>
            <div x-show="sidebarCollapsed" class="h-px bg-slate-100 my-2 mx-1" x-cloak></div>

            <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard') || request()->routeIs('secretaria.dashboard')" title="Dashboard Geral">
                <!-- Lucide: layout-dashboard -->
                <svg class="w-4.5 h-4.5 shrink-0 {{ (request()->routeIs('dashboard') || request()->routeIs('secretaria.dashboard')) ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-600' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="7" height="9" x="3" y="3" rx="1"/>
                    <rect width="7" height="5" x="14" y="3" rx="1"/>
                    <rect width="7" height="9" x="14" y="12" rx="1"/>
                    <rect width="7" height="5" x="3" y="16" rx="1"/>
                </svg>
                <span x-show="!sidebarCollapsed" x-cloak class="truncate">Dashboard Geral</span>
            </x-sidebar-link>

            <x-sidebar-link :href="route('chamada.index')" :active="request()->routeIs('chamada.*')" :title="Auth::user()->isProfessor() ? 'Minhas Chamadas' : 'Lançar Chamadas'">
                <!-- Lucide: clipboard-check -->
                <svg class="w-4.5 h-4.5 shrink-0 {{ request()->routeIs('chamada.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-600' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="8" height="4" x="8" y="2" rx="1" ry="1"/>
                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                    <path d="m9 14 2 2 4-4"/>
                </svg>
                <span x-show="!sidebarCollapsed" x-cloak class="truncate">{{ Auth::user()->isProfessor() ? 'Minhas Chamadas' : 'Lançar Chamadas' }}</span>
            </x-sidebar-link>
        </div>

        <!-- Grupo 2: Gestão da EBD (Professores, Secretaria & Admin) -->
        <div class="space-y-1">
            <div class="px-3 pb-1 text-[10px] font-extrabold text-slate-400 uppercase tracking-wider" x-show="!sidebarCollapsed" x-cloak>
                {{ Auth::user()->isProfessor() ? 'Minhas Turmas' : 'Gestão Escolar' }}
            </div>
            <div x-show="sidebarCollapsed" class="h-px bg-slate-100 my-2 mx-1" x-cloak></div>

            @if(Auth::user()->isSecretario() || Auth::user()->isAdmin())
                <x-sidebar-link :href="route('classes.index')" :active="request()->routeIs('classes.*')" title="Classes / Turmas">
                    <!-- Lucide: layers -->
                    <svg class="w-4.5 h-4.5 shrink-0 {{ request()->routeIs('classes.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-600' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"/>
                        <path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65"/>
                        <path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65"/>
                    </svg>
                    <span x-show="!sidebarCollapsed" x-cloak class="truncate">Classes / Turmas</span>
                </x-sidebar-link>
            @endif

            <x-sidebar-link :href="route('alunos.index')" :active="request()->routeIs('alunos.*')" :title="Auth::user()->isProfessor() ? 'Alunos da Turma' : 'Alunos Matriculados'">
                <!-- Lucide: users -->
                <svg class="w-4.5 h-4.5 shrink-0 {{ request()->routeIs('alunos.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-600' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
                <span x-show="!sidebarCollapsed" x-cloak class="truncate">{{ Auth::user()->isProfessor() ? 'Alunos da Turma' : 'Alunos Matriculados' }}</span>
            </x-sidebar-link>

            @if(Auth::user()->isSecretario() || Auth::user()->isAdmin())
                <x-sidebar-link :href="route('professores.index')" :active="request()->routeIs('professores.*')" title="Professores">
                    <!-- Lucide: graduation-cap -->
                    <svg class="w-4.5 h-4.5 shrink-0 {{ request()->routeIs('professores.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-600' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z"/>
                        <path d="M22 10v6"/>
                        <path d="M6 12.5V16a6 3 0 0 0 12 0v-3.5"/>
                    </svg>
                    <span x-show="!sidebarCollapsed" x-cloak class="truncate">Professores</span>
                </x-sidebar-link>
            @endif

            <x-sidebar-link :href="route('reports.index')" :active="request()->routeIs('reports.*')" :title="Auth::user()->isProfessor() ? 'Relatórios da Turma' : 'Relatórios da EBD'">
                <!-- Lucide: line-chart -->
                <svg class="w-4.5 h-4.5 shrink-0 {{ request()->routeIs('reports.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-600' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 3v18h18"/>
                    <path d="m19 9-5 5-4-4-3 3"/>
                </svg>
                <span x-show="!sidebarCollapsed" x-cloak class="truncate">{{ Auth::user()->isProfessor() ? 'Relatórios da Turma' : 'Relatórios da EBD' }}</span>
            </x-sidebar-link>

            <x-sidebar-link :href="route('manual.index')" :active="request()->routeIs('manual.*')" title="Manual de Uso">
                <!-- Lucide: book-open -->
                <svg class="w-4.5 h-4.5 shrink-0 {{ request()->routeIs('manual.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-600' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                </svg>
                <span x-show="!sidebarCollapsed" x-cloak class="truncate flex items-center justify-between w-full">
                    <span>Manual de Uso</span>
                    <span class="text-[10px] font-extrabold px-1.5 py-0.5 rounded-md bg-blue-100 text-blue-700 ml-1.5">Ajuda</span>
                </span>
            </x-sidebar-link>
        </div>

        <!-- Grupo 3: Administração Geral (Apenas Admin) -->
        @if(Auth::user()->isAdmin())
            <div class="space-y-1">
                <div class="px-3 pb-1 text-[10px] font-extrabold text-slate-400 uppercase tracking-wider" x-show="!sidebarCollapsed" x-cloak>
                    Administração
                </div>
                <div x-show="sidebarCollapsed" class="h-px bg-slate-100 my-2 mx-1" x-cloak></div>

                <x-sidebar-link :href="route('admin.congregacoes.index')" :active="request()->routeIs('admin.congregacoes.*')" title="Congregações">
                    <!-- Lucide: landmark / church -->
                    <svg class="w-4.5 h-4.5 shrink-0 {{ request()->routeIs('admin.congregacoes.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-600' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="2" x2="22" y1="22" y2="22"/>
                        <line x1="12" x2="12" y1="2" y2="8"/>
                        <path d="M4 10h16v12H4z"/>
                        <path d="M12 2v6"/>
                        <path d="m4 10 8-6 8 6"/>
                    </svg>
                    <span x-show="!sidebarCollapsed" x-cloak class="truncate">Congregações</span>
                </x-sidebar-link>

                <x-sidebar-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')" title="Gestão de Usuários">
                    <!-- Lucide: user-cog -->
                    <svg class="w-4.5 h-4.5 shrink-0 {{ request()->routeIs('admin.users.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-600' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="18" cy="15" r="3"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M10 15H6a4 4 0 0 0-4 4v2"/>
                        <path d="m21.7 16.4-.9-.3"/>
                        <path d="m15.2 13.9-.9-.3"/>
                        <path d="m16.6 18.7.3-.9"/>
                        <path d="m19.1 12.2.3-.9"/>
                    </svg>
                    <span x-show="!sidebarCollapsed" x-cloak class="truncate">Gestão de Usuários</span>
                </x-sidebar-link>

                <x-sidebar-link :href="route('admin.audit.index')" :active="request()->routeIs('admin.audit.*')" title="Logs de Auditoria">
                    <!-- Lucide: history -->
                    <svg class="w-4.5 h-4.5 shrink-0 {{ request()->routeIs('admin.audit.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-600' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                        <path d="M3 3v5h5"/>
                        <path d="M12 7v5l4 2"/>
                    </svg>
                    <span x-show="!sidebarCollapsed" x-cloak class="truncate">Logs de Auditoria</span>
                </x-sidebar-link>
            </div>
        @endif
    </div>

    <!-- Rodapé da Sidebar: Perfil, Sair & Toggle -->
    <div class="p-3 border-t border-slate-100 bg-slate-50/50 space-y-1 shrink-0">
        <x-sidebar-link :href="route('profile.edit')" :active="request()->routeIs('profile.*')" title="Meu Perfil">
            <!-- Lucide: user -->
            <svg class="w-4.5 h-4.5 shrink-0 {{ request()->routeIs('profile.*') ? 'text-blue-600' : 'text-slate-400 group-hover:text-blue-600' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
            </svg>
            <span x-show="!sidebarCollapsed" x-cloak class="truncate">Meu Perfil</span>
        </x-sidebar-link>

        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button 
                type="submit" 
                title="Sair do Sistema"
                class="flex items-center rounded-xl font-semibold text-rose-600 hover:text-rose-700 hover:bg-rose-50 transition-all cursor-pointer group"
                :class="sidebarCollapsed ? 'justify-center p-2.5 w-11 h-11 mx-auto gap-0' : 'gap-3 px-3.5 py-2.5 text-sm w-full'"
            >
                <!-- Lucide: log-out -->
                <svg class="w-4.5 h-4.5 shrink-0 text-rose-500 group-hover:text-rose-600 transition" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" x2="9" y1="12" y2="12"/>
                </svg>
                <span x-show="!sidebarCollapsed" x-cloak class="truncate">Sair do Sistema</span>
            </button>
        </form>

        <!-- Link de Política de Privacidade (Google Play Requirement) -->
        <a 
            href="{{ route('privacy.policy') }}" 
            target="_blank"
            title="Política de Privacidade"
            class="flex items-center rounded-xl text-slate-400 hover:text-blue-600 hover:bg-slate-100 transition-all"
            :class="sidebarCollapsed ? 'justify-center p-2.5 w-11 h-11 mx-auto' : 'gap-3 px-3.5 py-1.5 text-xs font-medium w-full'"
        >
            <svg class="w-4 h-4 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/>
                <path d="m9 12 2 2 4-4"/>
            </svg>
            <span x-show="!sidebarCollapsed" x-cloak class="truncate text-[11px]">Privacidade (LGPD)</span>
        </a>

        <!-- Botão de Alternar (Recolher / Expandir) no Rodapé -->
        <button 
            type="button" 
            @click="toggleSidebar()" 
            :title="sidebarCollapsed ? 'Expandir menu lateral' : 'Recolher menu lateral'"
            class="flex items-center rounded-xl text-slate-400 hover:text-blue-600 hover:bg-slate-100 transition-all cursor-pointer"
            :class="sidebarCollapsed ? 'justify-center p-2.5 w-11 h-11 mx-auto' : 'gap-3 px-3.5 py-2 text-xs font-semibold w-full'"
        >
            <svg x-show="!sidebarCollapsed" class="w-4.5 h-4.5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect width="18" height="18" x="3" y="3" rx="2"/>
                <path d="M9 3v18"/>
                <path d="m14 9-3 3 3 3"/>
            </svg>
            <svg x-show="sidebarCollapsed" x-cloak class="w-4.5 h-4.5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect width="18" height="18" x="3" y="3" rx="2"/>
                <path d="M9 3v18"/>
                <path d="m11 9 3 3-3 3"/>
            </svg>
            <span x-show="!sidebarCollapsed" x-cloak class="truncate">Recolher menu</span>
        </button>
    </div>
</aside>

<!-- =========================================================================
     2. MOBILE TOPBAR (Apenas visível em celulares < md)
     ========================================================================= -->
<header class="md:hidden sticky top-0 z-30 bg-white/95 backdrop-blur-md border-b border-slate-200/80 h-16 px-4 flex items-center justify-between shadow-xs">
    <div class="flex items-center gap-2.5">
        <!-- Botão Abrir Menu Hambúrguer -->
        <button 
            type="button"
            @click="mobileSidebarOpen = true" 
            class="inline-flex items-center justify-center p-2 rounded-xl text-slate-500 hover:text-slate-700 hover:bg-slate-100 focus:outline-none min-h-[44px] min-w-[44px] transition cursor-pointer"
            aria-label="Abrir menu lateral"
        >
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2">
            <img 
                src="{{ asset('images/logo-ad-transparent.png') }}" 
                alt="Logo Assembleia de Deus" 
                class="h-8 w-auto object-contain"
            />
            <span class="font-extrabold text-base text-slate-850 tracking-tight">
                Caderneta <span class="text-blue-600">EBD</span>
            </span>
        </a>
    </div>

    <div class="flex items-center gap-2">
        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold 
            {{ Auth::user()->isAdmin() ? 'bg-purple-50 text-purple-700 border border-purple-100' : (Auth::user()->isSecretario() ? 'bg-indigo-50 text-indigo-700 border border-indigo-100' : 'bg-blue-50 text-blue-700 border border-blue-100') }}">
            {{ Auth::user()->role?->label() ?? 'Usuário' }}
        </span>

        <a href="{{ route('profile.edit') }}" wire:navigate class="w-8 h-8 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center text-xs font-bold shadow-xs">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </a>
    </div>
</header>

<!-- =========================================================================
     3. MOBILE DRAWER (Gaveta Lateral Off-Canvas deslizante)
     ========================================================================= -->
<div 
    x-show="mobileSidebarOpen" 
    x-cloak
    class="md:hidden fixed inset-0 z-50 flex"
    role="dialog" 
    aria-modal="true"
>
    <!-- Fundo Escurecido com Blur (Backdrop) -->
    <div 
        x-show="mobileSidebarOpen" 
        x-transition:enter="transition-opacity ease-linear duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="mobileSidebarOpen = false"
        class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs"
    ></div>

    <!-- Painel Lateral Deslizante -->
    <div 
        x-show="mobileSidebarOpen" 
        x-transition:enter="transition ease-out duration-250 transform"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="relative flex-1 flex flex-col max-w-xs w-full bg-white shadow-2xl z-10"
    >
        <!-- Topo da Gaveta Mobile (Logo + Botão Fechar) -->
        <div class="h-16 px-4 flex items-center justify-between border-b border-slate-100 shrink-0">
            <div class="flex items-center gap-2.5">
                <img 
                    src="{{ asset('images/logo-ad-transparent.png') }}" 
                    alt="Logo Assembleia de Deus" 
                    class="h-8 w-auto object-contain"
                />
                <span class="font-extrabold text-base text-slate-850 tracking-tight">
                    Caderneta <span class="text-blue-600">EBD</span>
                </span>
            </div>
            <button 
                type="button" 
                @click="mobileSidebarOpen = false" 
                class="p-2 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 focus:outline-none min-h-[44px] min-w-[44px] flex items-center justify-center cursor-pointer"
                aria-label="Fechar menu lateral"
            >
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Links da Gaveta Mobile -->
        <div class="flex-1 px-3 py-4 space-y-5 overflow-y-auto">
            <!-- Usuário -->
            <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center text-xs font-bold shadow-xs shrink-0">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="flex flex-col min-w-0 flex-1">
                    <span class="font-bold text-xs text-slate-850 truncate">
                        {{ Auth::user()->name }}
                    </span>
                    <span class="text-[11px] text-slate-400 truncate">
                        {{ Auth::user()->email }}
                    </span>
                </div>
            </div>

            <!-- Seletor / Indicador de Congregação Mobile -->
            @if(Auth::user()->isAdmin())
                <div class="px-1">
                    <form method="POST" action="{{ route('admin.congregacoes.switch') }}">
                        @csrf
                        <div class="p-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 shadow-2xs">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">
                                    Congregação Ativa
                                </span>
                                @if(!$isAllCongregations)
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block animate-pulse"></span>
                                @endif
                            </div>
                            <select 
                                name="congregation_id" 
                                onchange="this.form.submit()" 
                                class="w-full text-xs font-bold rounded-xl border-slate-200 bg-white text-slate-800 py-1.5 pl-2 pr-7 focus:border-blue-500 focus:ring-blue-500 cursor-pointer shadow-2xs"
                            >
                                <option value="all" {{ $isAllCongregations ? 'selected' : '' }}>
                                    🌐 Todas as Congregações
                                </option>
                                @foreach($availableCongregations as $c)
                                    <option value="{{ $c->id }}" {{ $activeCongregationId === $c->id ? 'selected' : '' }}>
                                        {{ $c->is_headquarters ? '🏛️' : '⛪' }} {{ $c->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            @elseif(Auth::user()->congregation)
                <div class="px-1">
                    <div class="p-2.5 rounded-2xl bg-blue-50/70 border border-blue-100 flex items-center gap-2">
                        <span class="text-sm">⛪</span>
                        <div class="min-w-0 flex-1">
                            <span class="text-[10px] font-bold text-blue-500 uppercase tracking-wider block">Congregação</span>
                            <span class="text-xs font-bold text-blue-900 truncate block">{{ Auth::user()->congregation->name }}</span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Principal -->
            <div class="space-y-1">
                <div class="px-3 pb-1 text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">
                    Principal
                </div>

                <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard') || request()->routeIs('secretaria.dashboard')" @click="mobileSidebarOpen = false">
                    <svg class="w-4.5 h-4.5 shrink-0 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="7" height="9" x="3" y="3" rx="1"/>
                        <rect width="7" height="5" x="14" y="3" rx="1"/>
                        <rect width="7" height="9" x="14" y="12" rx="1"/>
                        <rect width="7" height="5" x="3" y="16" rx="1"/>
                    </svg>
                    <span>Dashboard Geral</span>
                </x-sidebar-link>

                <x-sidebar-link :href="route('chamada.index')" :active="request()->routeIs('chamada.*')" @click="mobileSidebarOpen = false">
                    <svg class="w-4.5 h-4.5 shrink-0 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="8" height="4" x="8" y="2" rx="1" ry="1"/>
                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                        <path d="m9 14 2 2 4-4"/>
                    </svg>
                    <span>{{ Auth::user()->isProfessor() ? 'Minhas Chamadas' : 'Lançar Chamadas' }}</span>
                </x-sidebar-link>
            </div>

            <!-- Gestão EBD -->
            <div class="space-y-1">
                <div class="px-3 pb-1 text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">
                    {{ Auth::user()->isProfessor() ? 'Minhas Turmas' : 'Gestão Escolar' }}
                </div>

                @if(Auth::user()->isSecretario() || Auth::user()->isAdmin())
                    <x-sidebar-link :href="route('classes.index')" :active="request()->routeIs('classes.*')" @click="mobileSidebarOpen = false">
                        <svg class="w-4.5 h-4.5 shrink-0 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"/>
                            <path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65"/>
                            <path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65"/>
                        </svg>
                        <span>Classes / Turmas</span>
                    </x-sidebar-link>
                @endif

                <x-sidebar-link :href="route('alunos.index')" :active="request()->routeIs('alunos.*')" @click="mobileSidebarOpen = false">
                    <svg class="w-4.5 h-4.5 shrink-0 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    <span>{{ Auth::user()->isProfessor() ? 'Alunos da Turma' : 'Alunos Matriculados' }}</span>
                </x-sidebar-link>

                @if(Auth::user()->isSecretario() || Auth::user()->isAdmin())
                    <x-sidebar-link :href="route('professores.index')" :active="request()->routeIs('professores.*')" @click="mobileSidebarOpen = false">
                        <svg class="w-4.5 h-4.5 shrink-0 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z"/>
                            <path d="M22 10v6"/>
                            <path d="M6 12.5V16a6 3 0 0 0 12 0v-3.5"/>
                        </svg>
                        <span>Professores</span>
                    </x-sidebar-link>
                @endif

                <x-sidebar-link :href="route('reports.index')" :active="request()->routeIs('reports.*')" @click="mobileSidebarOpen = false">
                    <svg class="w-4.5 h-4.5 shrink-0 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 3v18h18"/>
                        <path d="m19 9-5 5-4-4-3 3"/>
                    </svg>
                    <span>{{ Auth::user()->isProfessor() ? 'Relatórios da Turma' : 'Relatórios da EBD' }}</span>
                </x-sidebar-link>

                <x-sidebar-link :href="route('manual.index')" :active="request()->routeIs('manual.*')" @click="mobileSidebarOpen = false">
                    <svg class="w-4.5 h-4.5 shrink-0 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                    </svg>
                    <span class="flex items-center justify-between w-full">
                        <span>Manual de Uso</span>
                        <span class="text-[10px] font-extrabold px-1.5 py-0.5 rounded-md bg-blue-100 text-blue-700">Ajuda</span>
                    </span>
                </x-sidebar-link>
            </div>

            <!-- Admin -->
            @if(Auth::user()->isAdmin())
                <div class="space-y-1">
                    <div class="px-3 pb-1 text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">
                        Administração
                    </div>

                    <x-sidebar-link :href="route('admin.congregacoes.index')" :active="request()->routeIs('admin.congregacoes.*')" @click="mobileSidebarOpen = false">
                        <svg class="w-4.5 h-4.5 shrink-0 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="2" x2="22" y1="22" y2="22"/>
                            <line x1="12" x2="12" y1="2" y2="8"/>
                            <path d="M4 10h16v12H4z"/>
                            <path d="M12 2v6"/>
                            <path d="m4 10 8-6 8 6"/>
                        </svg>
                        <span>Congregações</span>
                    </x-sidebar-link>

                    <x-sidebar-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')" @click="mobileSidebarOpen = false">
                        <svg class="w-4.5 h-4.5 shrink-0 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="18" cy="15" r="3"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M10 15H6a4 4 0 0 0-4 4v2"/>
                            <path d="m21.7 16.4-.9-.3"/>
                            <path d="m15.2 13.9-.9-.3"/>
                        </svg>
                        <span>Gestão de Usuários</span>
                    </x-sidebar-link>

                    <x-sidebar-link :href="route('admin.audit.index')" :active="request()->routeIs('admin.audit.*')" @click="mobileSidebarOpen = false">
                        <svg class="w-4.5 h-4.5 shrink-0 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                            <path d="M3 3v5h5"/>
                            <path d="M12 7v5l4 2"/>
                        </svg>
                        <span>Logs de Auditoria</span>
                    </x-sidebar-link>
                </div>
            @endif
        </div>

        <!-- Rodapé da Gaveta Mobile -->
        <div class="p-3 border-t border-slate-100 bg-slate-50/70 space-y-1 shrink-0 pb-6">
            <x-sidebar-link :href="route('profile.edit')" :active="request()->routeIs('profile.*')" @click="mobileSidebarOpen = false">
                <svg class="w-4.5 h-4.5 shrink-0 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
                <span>Meu Perfil</span>
            </x-sidebar-link>

            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button 
                    type="submit" 
                    class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-rose-600 hover:text-rose-700 hover:bg-rose-50 transition-all cursor-pointer"
                >
                    <svg class="w-4.5 h-4.5 shrink-0 text-rose-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" x2="9" y1="12" y2="12"/>
                    </svg>
                    <span>Sair do Sistema</span>
                </button>
            </form>

            <!-- Link Política de Privacidade (Google Play Requirement) -->
            <a 
                href="{{ route('privacy.policy') }}" 
                target="_blank"
                class="flex items-center gap-2.5 px-3.5 py-2 rounded-xl text-xs font-medium text-slate-400 hover:text-blue-600 hover:bg-slate-50 transition mt-1"
            >
                <svg class="w-4 h-4 shrink-0 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/>
                    <path d="m9 12 2 2 4-4"/>
                </svg>
                <span>Política de Privacidade (LGPD)</span>
            </a>
        </div>
    </div>
</div>

<!-- =========================================================================
     4. MOBILE BOTTOM NAVIGATION BAR (Acesso Rápido com o Polegar)
     ========================================================================= -->
<div class="md:hidden fixed bottom-0 inset-x-0 bg-white/95 backdrop-blur-md border-t border-slate-200/80 z-40 px-2 py-1 shadow-[0_-4px_20px_rgba(0,0,0,0.05)]">
    <div class="grid grid-cols-4 gap-1">
        <!-- 1. Painel -->
        @php
            $isDashboard = request()->routeIs('dashboard') || request()->routeIs('secretaria.dashboard');
        @endphp
        <a 
            href="{{ route('dashboard') }}" 
            wire:navigate
            class="flex flex-col items-center justify-center py-1.5 px-1 rounded-xl text-[11px] font-medium transition-colors min-h-[48px] {{ $isDashboard ? 'text-blue-600 font-bold bg-blue-50/70' : 'text-slate-500 hover:text-slate-800' }}"
        >
            <svg class="w-5 h-5 mb-0.5 {{ $isDashboard ? 'text-blue-600 stroke-[2.2]' : 'text-slate-400' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
            <span>Painel</span>
        </a>

        <!-- 2. Chamadas -->
        @php
            $isChamada = request()->routeIs('chamada.*');
        @endphp
        <a 
            href="{{ route('chamada.index') }}" 
            wire:navigate
            class="flex flex-col items-center justify-center py-1.5 px-1 rounded-xl text-[11px] font-medium transition-colors min-h-[48px] {{ $isChamada ? 'text-blue-600 font-bold bg-blue-50/70' : 'text-slate-500 hover:text-slate-800' }}"
        >
            <svg class="w-5 h-5 mb-0.5 {{ $isChamada ? 'text-blue-600 stroke-[2.2]' : 'text-slate-400' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
            </svg>
            <span>Chamadas</span>
        </a>

        <!-- 3. Alunos -->
        @php
            $isAlunos = request()->routeIs('alunos.*') || request()->routeIs('classes.*');
        @endphp
        <a 
            href="{{ route('alunos.index') }}" 
            wire:navigate
            class="flex flex-col items-center justify-center py-1.5 px-1 rounded-xl text-[11px] font-medium transition-colors min-h-[48px] {{ $isAlunos ? 'text-blue-600 font-bold bg-blue-50/70' : 'text-slate-500 hover:text-slate-800' }}"
        >
            <svg class="w-5 h-5 mb-0.5 {{ $isAlunos ? 'text-blue-600 stroke-[2.2]' : 'text-slate-400' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
            </svg>
            <span>Alunos</span>
        </a>

        <!-- 4. Perfil -->
        @php
            $isPerfil = request()->routeIs('profile.*');
        @endphp
        <a 
            href="{{ route('profile.edit') }}" 
            wire:navigate
            class="flex flex-col items-center justify-center py-1.5 px-1 rounded-xl text-[11px] font-medium transition-colors min-h-[48px] {{ $isPerfil ? 'text-blue-600 font-bold bg-blue-50/70' : 'text-slate-500 hover:text-slate-800' }}"
        >
            <svg class="w-5 h-5 mb-0.5 {{ $isPerfil ? 'text-blue-600 stroke-[2.2]' : 'text-slate-400' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.964 0a9 9 0 1 0-11.963 0m11.964 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
            </svg>
            <span>Perfil</span>
        </a>
    </div>
</div>
