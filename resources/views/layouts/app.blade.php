<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <title>{{ config('app.name', 'SNS CRM') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 text-slate-800 antialiased">
    <div x-data="{ sidebarOpen: false }" class="min-h-screen lg:flex">
        <aside class="hidden w-72 shrink-0 bg-slate-900 text-white lg:flex lg:flex-col">
            @include('layouts.partials.sidebar-content')
        </aside>

        <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-30 bg-slate-900/50 lg:hidden" @click="sidebarOpen = false" style="display: none;"></div>

        <aside class="fixed inset-y-0 left-0 z-40 w-72 bg-slate-900 text-white transition-transform duration-300 lg:hidden -translate-x-full" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            @include('layouts.partials.sidebar-content')
        </aside>

        <div class="flex min-h-screen flex-1 flex-col">
            @include('layouts.partials.topbar')

            <main class="flex-1 p-6 lg:p-8">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>