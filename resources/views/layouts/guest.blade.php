<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Caderneta EBD Digital') }}</title>

        <!-- Google Fonts: Inter & Plus Jakarta Sans -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

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
    </body>
</html>
