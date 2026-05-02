@extends('errors.layout')

@section('title', 'Pagina non trovata')

@section('content')
    <div class="bg-white rounded-3xl shadow-lg ring-1 ring-slate-200 p-8 text-center space-y-6">
        <div class="text-8xl">🔍</div>
        <h1 class="text-4xl font-extrabold text-slate-900">404</h1>
        <p class="text-slate-600 text-lg">
            La pagina che stai cercando non esiste o è stata spostata.
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ url('/') }}"
               class="inline-flex items-center justify-center rounded-2xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white hover:bg-indigo-700 transition">
                Torna alla Home
            </a>
            <button onclick="history.back()"
                    class="inline-flex items-center justify-center rounded-2xl border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                Indietro
            </button>
        </div>
    </div>
@endsection