<x-guest-layout>
    <div class="rounded-3xl bg-white p-8 shadow-xl ring-1 ring-slate-200 sm:p-10">
        <div class="mb-8">
            <h2 class="text-3xl font-bold tracking-tight text-slate-900">Password dimenticata</h2>
            <p class="mt-2 text-sm leading-6 text-slate-500">
                Nessun problema. Inserisci il tuo indirizzo email e ti invieremo un link per reimpostare la password.
            </p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="mb-2 block text-sm font-medium text-slate-700">Indirizzo email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                    class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100">
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-500" />
            </div>

            <button type="submit"
                class="inline-flex w-full items-center justify-center rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-slate-200">
                Invia link di reimpostazione
            </button>

            <p class="text-center text-sm text-slate-500">
                <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:text-indigo-500">
                    Torna al login
                </a>
            </p>
        </form>
    </div>
</x-guest-layout>