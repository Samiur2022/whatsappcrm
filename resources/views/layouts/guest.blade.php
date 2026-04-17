<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'CRM WhatsApp') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-800 antialiased">
    <div class="min-h-screen lg:grid lg:grid-cols-2">
        <div class="hidden lg:flex flex-col justify-between bg-slate-900 px-12 py-10 text-white">
            <div>
                <a href="/" class="inline-flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-indigo-500 text-lg font-bold">
                        W
                    </div>
                    <div>
                        <p class="text-xl font-semibold tracking-wide">SNS CRM</p>
                        <p class="text-sm text-slate-300">Sistema professionale di gestione</p>
                    </div>
                </a>
            </div>

            <div class="max-w-md">
                <h1 class="text-4xl font-bold leading-tight">
                    Gestisci conversazioni, contatti e campagne da un'unica dashboard moderna.
                </h1>
                <p class="mt-5 text-base leading-7 text-slate-300">
                    Un'esperienza veloce, moderna e scalabile per il tuo flusso di lavoro.
                </p>

                <div class="mt-10 grid grid-cols-2 gap-4">
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-5 backdrop-blur">
                        <p class="text-2xl font-semibold">10k+</p>
                        <p class="mt-1 text-sm text-slate-300">Messaggi gestiti</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-5 backdrop-blur">
                        <p class="text-2xl font-semibold">99.9%</p>
                        <p class="mt-1 text-sm text-slate-300">Focus sul sistema</p>
                    </div>
                </div>
            </div>

            <div class="text-sm text-slate-400">
                © {{ date('Y') }} {{ config('app.name', 'SMS CRM ') }}. Tutti i diritti riservati.
            </div>
        </div>

        <div class="flex min-h-screen items-center justify-center px-6 py-10 sm:px-10 lg:px-16">
            <div class="w-full max-w-md">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>