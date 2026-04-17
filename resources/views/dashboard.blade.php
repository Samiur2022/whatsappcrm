<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>
    <x-slot name="subtitle">Panoramica generale del tuo SNS CRM</x-slot>

    <div class="space-y-6">
        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm text-slate-500">Conversazioni attive</p>
                <h3 class="mt-3 text-3xl font-bold text-slate-900">1,248</h3>
                <p class="mt-2 text-sm text-green-600">+12% rispetto alla scorsa settimana</p>
            </div>

            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm text-slate-500">Nuovi contatti</p>
                <h3 class="mt-3 text-3xl font-bold text-slate-900">326</h3>
                <p class="mt-2 text-sm text-green-600">+8% questo mese</p>
            </div>

            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm text-slate-500">Campagne attive</p>
                <h3 class="mt-3 text-3xl font-bold text-slate-900">18</h3>
                <p class="mt-2 text-sm text-sky-600">5 pianificate per oggi</p>
            </div>

            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm text-slate-500">Tasso di risposta</p>
                <h3 class="mt-3 text-3xl font-bold text-slate-900">94%</h3>
                <p class="mt-2 text-sm text-green-600">Prestazioni eccellenti</p>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-3">
            <div class="xl:col-span-2 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Attività recenti</h3>
                        <p class="text-sm text-slate-500">Ultimi aggiornamenti del sistema</p>
                    </div>
                </div>

                <div class="mt-6 space-y-4">
                    <div class="flex items-start justify-between rounded-2xl bg-slate-50 p-4">
                        <div>
                            <p class="font-medium text-slate-900">Nuovo lead assegnato</p>
                            <p class="text-sm text-slate-500">Marco Rossi è stato assegnato al team vendite</p>
                        </div>
                        <span class="text-xs text-slate-400">2 min fa</span>
                    </div>

                    <div class="flex items-start justify-between rounded-2xl bg-slate-50 p-4">
                        <div>
                            <p class="font-medium text-slate-900">Campagna inviata</p>
                            <p class="text-sm text-slate-500">La campagna “Promo Primavera” è stata inviata con successo</p>
                        </div>
                        <span class="text-xs text-slate-400">15 min fa</span>
                    </div>

                    <div class="flex items-start justify-between rounded-2xl bg-slate-50 p-4">
                        <div>
                            <p class="font-medium text-slate-900">Nuovo messaggio ricevuto</p>
                            <p class="text-sm text-slate-500">Hai ricevuto un nuovo messaggio da un cliente VIP</p>
                        </div>
                        <span class="text-xs text-slate-400">28 min fa</span>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h3 class="text-lg font-semibold text-slate-900">Riepilogo rapido</h3>
                <p class="mt-1 text-sm text-slate-500">Statistiche principali della giornata</p>

                <div class="mt-6 space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <span class="text-sm text-slate-600">Messaggi inviati</span>
                        <span class="text-sm font-semibold text-slate-900">2,340</span>
                    </div>
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <span class="text-sm text-slate-600">Messaggi ricevuti</span>
                        <span class="text-sm font-semibold text-slate-900">1,876</span>
                    </div>
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <span class="text-sm text-slate-600">Lead convertiti</span>
                        <span class="text-sm font-semibold text-slate-900">74</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600">Operatori online</span>
                        <span class="text-sm font-semibold text-slate-900">12</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>