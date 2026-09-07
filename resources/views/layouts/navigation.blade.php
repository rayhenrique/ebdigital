<nav x-data="{ open: false }" class="backdrop-blur-md bg-white/80 border-b border-slate-100 sticky top-0 z-40 shadow-xs">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 gap-4">
            <!-- 1. Bloco Esquerda: Brand / Logo -->
            <div class="shrink-0 flex items-center">
                <a href="{{ route('dashboard') }}" wire:navigate.hover class="flex items-center gap-3 group">
                    <img 
                        src="{{ asset('images/logo-ad-transparent.png') }}" 
                        alt="Logo Assembleia de Deus" 
                        class="h-9 w-auto object-contain transition-transform duration-200 group-hover:scale-105"
                    />
                    <div class="flex flex-col">
                        <span class="font-extrabold text-base sm:text-lg text-slate-850 tracking-tight leading-tight">
                            Caderneta <span class="text-blue-600">EBD</span>
                        </span>
                        <span class="text-[10px] text-slate-400 font-medium hidden sm:inline leading-none">
                            Assembleia de Deus
                        </span>
                    </div>
                </a>
            </div>

            <!-- 2. Bloco Centro: Links de Navegação (Desktop) -->
            <div class="hidden md:flex items-center gap-2 lg:gap-2.5">
                @if(Auth::user()->isProfessor())
                    <x-nav-link :href="route('chamada.index')" :active="request()->routeIs('chamada.*')">
                        <!-- Lucide: clipboard-check -->
                        <svg class="w-4.5 h-4.5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="8" height="4" x="8" y="2" rx="1" ry="1"/>
                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                            <path d="m9 14 2 2 4-4"/>
                        </svg>
                        <span>Minhas Chamadas</span>
                    </x-nav-link>
                @endif

                @if(Auth::user()->isSecretario() || Auth::user()->isAdmin())
                    <x-nav-link :href="route('secretaria.dashboard')" :active="request()->routeIs('secretaria.dashboard')">
                        <!-- Lucide: layout-dashboard -->
                        <svg class="w-4.5 h-4.5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="7" height="9" x="3" y="3" rx="1"/>
                            <rect width="7" height="5" x="14" y="3" rx="1"/>
                            <rect width="7" height="9" x="14" y="12" rx="1"/>
                            <rect width="7" height="5" x="3" y="16" rx="1"/>
                        </svg>
                        <span>Dashboard</span>
                    </x-nav-link>

                    <x-nav-link :href="route('chamada.index')" :active="request()->routeIs('chamada.*')">
                        <!-- Lucide: clipboard-check -->
                        <svg class="w-4.5 h-4.5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="8" height="4" x="8" y="2" rx="1" ry="1"/>
                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                            <path d="m9 14 2 2 4-4"/>
                        </svg>
                        <span>Chamadas</span>
                    </x-nav-link>

                    <x-nav-link :href="route('classes.index')" :active="request()->routeIs('classes.*')">
                        <!-- Lucide: layers -->
                        <svg class="w-4.5 h-4.5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"/>
                            <path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65"/>
                            <path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65"/>
                        </svg>
                        <span>Classes</span>
                    </x-nav-link>

                    <x-nav-link :href="route('alunos.index')" :active="request()->routeIs('alunos.*')">
                        <!-- Lucide: users -->
                        <svg class="w-4.5 h-4.5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                        <span>Alunos</span>
                    </x-nav-link>
                @endif

                @if(Auth::user()->isAdmin())
                    <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                        <!-- Lucide: user-cog / shield-user -->
                        <svg class="w-4.5 h-4.5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="18" cy="15" r="3"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M10 15H6a4 4 0 0 0-4 4v2"/>
                            <path d="m21.7 16.4-.9-.3"/>
                            <path d="m15.2 13.9-.9-.3"/>
                            <path d="m16.6 18.7.3-.9"/>
                            <path d="m19.1 12.2.3-.9"/>
                            <path d="m19.6 18.7-.4-.8"/>
                            <path d="m16.8 12.3-.4-.8"/>
                            <path d="m14.3 16.6.8-.4"/>
                            <path d="m20.7 13.8.8-.4"/>
                        </svg>
                        <span>Usuários</span>
                    </x-nav-link>

                    <x-nav-link :href="route('admin.audit.index')" :active="request()->routeIs('admin.audit.*')">
                        <!-- Lucide: history / file-search -->
                        <svg class="w-4.5 h-4.5 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                            <path d="M3 3v5h5"/>
                            <path d="M12 7v5l4 2"/>
                        </svg>
                        <span>Auditoria</span>
                    </x-nav-link>
                @endif
            </div>

            <!-- 3. Bloco Direita: Perfil do Usuário (Desktop) -->
            <div class="hidden sm:flex sm:items-center gap-3">
                <!-- Badge de Papel Sutil -->
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold shrink-0 
                    {{ Auth::user()->isAdmin() ? 'bg-purple-50 text-purple-700 border border-purple-200/80' : (Auth::user()->isSecretario() ? 'bg-indigo-50 text-indigo-700 border border-indigo-200/80' : 'bg-blue-50 text-blue-700 border border-blue-200/80') }}">
                    {{ Auth::user()->role?->label() ?? 'Usuário' }}
                </span>

                <!-- Menu Dropdown do Perfil -->
                <x-dropdown align="right" width="52">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2.5 p-1.5 px-2.5 text-sm font-semibold rounded-xl text-slate-700 bg-transparent hover:bg-slate-50 focus:outline-none transition-colors border border-transparent hover:border-slate-200/70 cursor-pointer group">
                            <!-- Avatar circular com inicial -->
                            <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center text-xs font-bold shadow-xs shrink-0">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div class="max-w-[150px] truncate text-slate-850 font-semibold group-hover:text-blue-600 transition-colors">
                                {{ Auth::user()->name }}
                            </div>
                            <!-- Chevron Down -->
                            <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-600 transition-colors shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2.5 border-b border-slate-100">
                            <p class="text-[11px] text-slate-400 font-medium">Conectado como</p>
                            <p class="text-xs font-bold text-slate-800 truncate">{{ Auth::user()->email }}</p>
                        </div>

                        <x-dropdown-link :href="route('profile.edit')" wire:navigate class="flex items-center gap-2 text-slate-700 hover:text-blue-600">
                            <svg class="w-4 h-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                            <span>Meu Perfil</span>
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="text-rose-600 hover:text-rose-700 flex items-center gap-2">
                                <svg class="w-4 h-4 text-rose-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                                </svg>
                                <span>Sair do Sistema</span>
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- 4. Mobile Header Right (Badge de papel + Avatar + Drawer Toggle) -->
            <div class="flex items-center gap-2 sm:hidden">
                <!-- Badge de Papel Mobile -->
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold 
                    {{ Auth::user()->isAdmin() ? 'bg-purple-50 text-purple-700 border border-purple-100' : (Auth::user()->isSecretario() ? 'bg-indigo-50 text-indigo-700 border border-indigo-100' : 'bg-blue-50 text-blue-700 border border-blue-100') }}">
                    {{ Auth::user()->role?->label() ?? 'Usuário' }}
                </span>

                <!-- Avatar Circle Mobile -->
                <a href="{{ route('profile.edit') }}" wire:navigate class="w-8 h-8 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center text-xs font-bold shadow-xs">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </a>

                <!-- Hamburger Drawer Toggle Mobile -->
                <button 
                    @click="open = ! open" 
                    class="inline-flex items-center justify-center p-2 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 focus:outline-none min-h-[44px] min-w-[44px] transition"
                    aria-label="Abrir menu de navegação"
                >
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (Mobile Drawer) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white/95 backdrop-blur-md border-b border-slate-200">
        <div class="pt-2 pb-3 space-y-1 px-2">
            @if(Auth::user()->isProfessor())
                <x-responsive-nav-link :href="route('chamada.index')" :active="request()->routeIs('chamada.*')">
                    <span class="flex items-center gap-2">
                        <svg class="w-4.5 h-4.5 text-slate-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="8" height="4" x="8" y="2" rx="1" ry="1"/>
                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                            <path d="m9 14 2 2 4-4"/>
                        </svg>
                        <span>Minhas Chamadas</span>
                    </span>
                </x-responsive-nav-link>
            @endif

            @if(Auth::user()->isSecretario() || Auth::user()->isAdmin())
                <x-responsive-nav-link :href="route('secretaria.dashboard')" :active="request()->routeIs('secretaria.dashboard')">
                    <span class="flex items-center gap-2">
                        <svg class="w-4.5 h-4.5 text-slate-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="7" height="9" x="3" y="3" rx="1"/>
                            <rect width="7" height="5" x="14" y="3" rx="1"/>
                            <rect width="7" height="9" x="14" y="12" rx="1"/>
                            <rect width="7" height="5" x="3" y="16" rx="1"/>
                        </svg>
                        <span>Dashboard</span>
                    </span>
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('chamada.index')" :active="request()->routeIs('chamada.*')">
                    <span class="flex items-center gap-2">
                        <svg class="w-4.5 h-4.5 text-slate-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="8" height="4" x="8" y="2" rx="1" ry="1"/>
                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                            <path d="m9 14 2 2 4-4"/>
                        </svg>
                        <span>Chamadas</span>
                    </span>
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('classes.index')" :active="request()->routeIs('classes.*')">
                    <span class="flex items-center gap-2">
                        <svg class="w-4.5 h-4.5 text-slate-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"/>
                            <path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65"/>
                            <path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65"/>
                        </svg>
                        <span>Classes</span>
                    </span>
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('alunos.index')" :active="request()->routeIs('alunos.*')">
                    <span class="flex items-center gap-2">
                        <svg class="w-4.5 h-4.5 text-slate-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                        <span>Alunos</span>
                    </span>
                </x-responsive-nav-link>
            @endif

            @if(Auth::user()->isAdmin())
                <div class="border-t border-slate-100 my-2 pt-2">
                    <span class="px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Administração</span>
                    <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                        <span class="flex items-center gap-2">
                            <svg class="w-4.5 h-4.5 text-slate-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="18" cy="15" r="3"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="M10 15H6a4 4 0 0 0-4 4v2"/>
                            </svg>
                            <span>Gestão de Usuários</span>
                        </span>
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.audit.index')" :active="request()->routeIs('admin.audit.*')">
                        <span class="flex items-center gap-2">
                            <svg class="w-4.5 h-4.5 text-slate-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                                <path d="M3 3v5h5"/>
                                <path d="M12 7v5l4 2"/>
                            </svg>
                            <span>Logs de Auditoria</span>
                        </span>
                    </x-responsive-nav-link>
                </div>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-3 pb-3 border-t border-slate-100 px-4 bg-slate-50/50">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <div class="font-bold text-sm text-slate-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-xs text-slate-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-2 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    <span class="flex items-center gap-2">
                        <svg class="w-4.5 h-4.5 text-slate-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                        <span>Meu Perfil</span>
                    </span>
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();"
                            class="text-rose-600 font-semibold">
                        <span class="flex items-center gap-2">
                            <svg class="w-4.5 h-4.5 text-rose-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                            </svg>
                            <span>Sair do Sistema</span>
                        </span>
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

<!-- Mobile Bottom Navigation Bar (Bottom Nav Bar) -->
<div class="sm:hidden fixed bottom-0 inset-x-0 bg-white/95 backdrop-blur-md border-t border-slate-200/80 z-40 px-2 py-1 shadow-[0_-4px_20px_rgba(0,0,0,0.05)]">
    <div class="grid grid-cols-4 gap-1">
        <!-- 1. Painel -->
        @php
            $isDashboard = request()->routeIs('secretaria.dashboard') || request()->routeIs('dashboard');
        @endphp
        <a 
            href="{{ Auth::user()->isProfessor() ? route('chamada.index') : route('secretaria.dashboard') }}" 
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
            $alunosTarget = Auth::user()->isProfessor() ? route('chamada.index') : route('alunos.index');
        @endphp
        <a 
            href="{{ $alunosTarget }}" 
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
