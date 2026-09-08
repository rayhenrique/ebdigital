<!DOCTYPE html>
<html lang="pt-BR" class="h-full notranslate" translate="no">
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

        <script>
          if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
              navigator.serviceWorker.register('/sw.js');
            });
          }
        </script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased min-h-screen bg-slate-50 relative flex flex-col justify-center items-center py-8 px-4 sm:px-6 selection:bg-blue-600 selection:text-white">
        <!-- Subtle Ambient Gradient Glow -->
        <div class="fixed inset-0 pointer-events-none overflow-hidden -z-10" aria-hidden="true">
            <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-[650px] h-[350px] bg-gradient-to-tr from-blue-400/15 via-indigo-400/10 to-transparent blur-3xl rounded-full"></div>
            <div class="absolute -bottom-32 right-10 w-[550px] h-[320px] bg-gradient-to-br from-indigo-300/15 via-blue-500/10 to-transparent blur-3xl rounded-full"></div>
        </div>

        <!-- Fluid Responsive Card Container -->
        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl shadow-slate-200/70 border border-slate-100 p-6 sm:p-8 transition-all">
            {{ $slot }}
        </div>

        <!-- Rodapé Público com Link de Política de Privacidade & Desenvolvedor -->
        <footer class="mt-6 text-center text-xs text-slate-400 space-y-1.5">
            <div>
                <a href="{{ route('privacy.policy') }}" class="text-slate-500 hover:text-blue-600 font-medium underline underline-offset-2 transition-colors">
                    Política de Privacidade
                </a>
                <span class="mx-2 text-slate-300">•</span>
                <span class="text-slate-500">Assembleia de Deus — Teotônio Vilela/AL</span>
            </div>
            <div class="text-[11px] text-slate-400">
                Desenvolvido por 
                <a href="https://kltecnologia.com" target="_blank" rel="noopener noreferrer" class="font-semibold text-blue-600 hover:text-blue-700 hover:underline transition-colors">
                    KL Tecnologia
                </a>
            </div>
        </footer>
    </body>
</html>
