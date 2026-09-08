<!DOCTYPE html>
<html lang="pt-BR" class="notranslate" translate="no">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta http-equiv="content-language" content="pt-BR">
        <meta name="google" content="notranslate">

        <title>{{ config('app.name', 'Caderneta EBD Digital') }}</title>

        <!-- Google Fonts: Inter & Plus Jakarta Sans -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

        <!-- PWA Configs -->
        <link rel="manifest" href="/manifest.json">
        <link rel="apple-touch-icon" href="/icons/icon-192x192.png">
        <meta name="theme-color" content="#2563eb">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
        <meta name="apple-mobile-web-app-title" content="Caderneta EBD">
        <meta name="view-transition" content="same-origin">

        <script>
          if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
              navigator.serviceWorker.register('/sw.js');
            });
          }
        </script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body 
        x-data="{ 
            mobileSidebarOpen: false,
            sidebarCollapsed: localStorage.getItem('ebd_sidebar_collapsed') !== null 
                ? localStorage.getItem('ebd_sidebar_collapsed') === 'true' 
                : window.innerWidth < 1024,
            toggleSidebar() {
                this.sidebarCollapsed = !this.sidebarCollapsed;
                localStorage.setItem('ebd_sidebar_collapsed', this.sidebarCollapsed);
            }
        }" 
        class="font-sans antialiased bg-slate-50 text-slate-800 selection:bg-blue-600 selection:text-white"
    >
        <!-- Barra de Progresso Superior de Navegação (Feedback Instantâneo) -->
        <div id="ebd-progress-bar" class="fixed top-0 left-0 right-0 h-[3px] z-[99999] pointer-events-none transition-all duration-300 opacity-0 -translate-y-full bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-500 shadow-[0_0_12px_rgba(37,99,235,0.8)]" style="width: 0%;"></div>

        <!-- Estrutura Geral: Sidebar à Esquerda (Desktop) + Área de Conteúdo à Direita -->
        <div class="min-h-screen flex flex-col md:flex-row bg-slate-50">
            @include('layouts.navigation')

            <!-- Área de Conteúdo Principal (Direita no Desktop) -->
            <div class="flex-1 flex flex-col min-w-0 min-h-screen">
                <!-- Cabeçalho da Página (Page Heading) -->
                @isset($header)
                    <header class="bg-white/80 backdrop-blur-md border-b border-slate-100 shadow-xs">
                        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Mensagens Flash Globais -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full pt-4 empty:hidden">
                    @if(session('success'))
                        <div class="mb-4 p-4 rounded-2xl bg-emerald-50 border border-emerald-200/80 text-emerald-900 text-sm font-semibold flex items-center gap-3 shadow-xs">
                            <span class="text-base">✓</span>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('warning'))
                        <div class="mb-4 p-4 rounded-2xl bg-amber-50 border border-amber-200/80 text-amber-900 text-sm font-semibold flex items-center gap-3 shadow-xs">
                            <span class="text-base">⚠️</span>
                            <span>{{ session('warning') }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 p-4 rounded-2xl bg-rose-50 border border-rose-200/80 text-rose-900 text-sm font-semibold flex items-center gap-3 shadow-xs">
                            <span class="text-base">⛔</span>
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif

                    @if(session('info'))
                        <div class="mb-4 p-4 rounded-2xl bg-blue-50 border border-blue-200/80 text-blue-900 text-sm font-semibold flex items-center gap-3 shadow-xs">
                            <span class="text-base">ℹ️</span>
                            <span>{{ session('info') }}</span>
                        </div>
                    @endif
                </div>

                <!-- Conteúdo da Página (pb-24 no mobile evita sobreposição com o menu inferior) -->
                <main class="flex-1 pb-24 md:pb-12">
                    {{ $slot }}
                </main>
            </div>
        </div>
        @livewireScripts

        <!-- Script de Controle da Barra de Progresso em Navegações wire:navigate -->
        <script>
            document.addEventListener('livewire:navigating', () => {
                const bar = document.getElementById('ebd-progress-bar');
                if (bar) {
                    bar.style.transition = 'none';
                    bar.style.width = '0%';
                    bar.classList.remove('opacity-0', '-translate-y-full');
                    bar.style.opacity = '1';
                    setTimeout(() => {
                        bar.style.transition = 'width 300ms cubic-bezier(0.4, 0, 0.2, 1)';
                        bar.style.width = '75%';
                    }, 10);
                }
            });

            document.addEventListener('livewire:navigated', () => {
                const bar = document.getElementById('ebd-progress-bar');
                if (bar) {
                    bar.style.transition = 'width 120ms ease-in';
                    bar.style.width = '100%';
                    setTimeout(() => {
                        bar.style.opacity = '0';
                        setTimeout(() => {
                            bar.classList.add('-translate-y-full');
                            bar.style.width = '0%';
                        }, 200);
                    }, 150);
                }
            });
        </script>
    </body>
</html>
