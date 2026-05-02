@extends('errors.layout')

@section('title', 'Errore del server')

@section('content')
    <div class="bg-white rounded-3xl shadow-lg ring-1 ring-slate-200 p-8 text-center space-y-6">
        <div class="text-8xl">🔥</div>
        <h1 class="text-4xl font-extrabold text-slate-900">500</h1>
        <p class="text-slate-600 text-lg">
            Qualcosa è andato storto dal nostro lato. Stiamo lavorando per risolvere il problema.
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('dashboard') }}"
               class="inline-flex items-center justify-center rounded-2xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white hover:bg-indigo-700 transition">
                Vai alla Dashboard
            </a>
            <button onclick="location.reload()"
                    class="inline-flex items-center justify-center rounded-2xl border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                Riprova
            </button>
        </div>
    </div>
@endsection