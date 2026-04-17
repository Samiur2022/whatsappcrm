<x-guest-layout>
    <div class="rounded-3xl bg-white p-8 shadow-xl ring-1 ring-slate-200 sm:p-10">
        <div class="mb-8">
            <h2 class="text-3xl font-bold tracking-tight text-slate-900">Verifica il tuo indirizzo email</h2>
            <p class="mt-2 text-sm leading-6 text-slate-500">
                Grazie per esserti registrato. Prima di iniziare, verifica il tuo indirizzo email cliccando sul link che ti abbiamo inviato.
                Se non hai ricevuto l'email, possiamo inviartene un'altra.
            </p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 rounded-2xl bg-green-50 px-4 py-3 text-sm font-medium text-green-700 ring-1 ring-green-200">
                Un nuovo link di verifica è stato inviato all'indirizzo email associato al tuo account.
            </div>
        @endif

        <div class="space-y-4">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <button type="submit"
                    class="inline-flex w-full items-center justify-center rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-slate-200">
                    Invia nuovamente l'email di verifica
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit"
                    class="inline-flex w-full items-center justify-center rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 focus:outline-none focus:ring-4 focus:ring-slate-200">
                    Esci
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>