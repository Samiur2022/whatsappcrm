@extends('errors.layout')

@section('title', 'Sessione scaduta')

@section('content')
    <div class="bg-white rounded-3xl shadow-lg ring-1 ring-slate-200 p-8 text-center space-y-6">
        <div class="text-8xl">⏳</div>
        <h1 class="text-4xl font-extrabold text-slate-900">419</h1>
        <p class="text-slate-600 text-lg">
            La tua sessione è scaduta per inattività. Per favore, effettua nuovamente l'accesso.
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('login') }}"
               class="inline-flex items-center justify-center rounded-2xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white hover:bg-indigo-700 transition">
                Accedi di nuovo
            </a>
            <a href="{{ url('/') }}"
               class="inline-flex items-center justify-center rounded-2xl border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                Home
            </a>
        </div>
    </div>
@endsection