<x-app-layout>
    <x-slot name="title">Conversazione</x-slot>
    <x-slot name="subtitle">Dettagli chat</x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h2 class="text-xl font-semibold">Chat con {{ $conversation->contact->name }}</h2>
            <p class="mt-2 text-sm text-slate-500">Messaggi scambiati</p>

            <div class="mt-6 space-y-4 max-h-96 overflow-y-auto">
                @forelse($conversation->messages as $msg)
                    <div class="flex {{ $msg->direction === 'outbound' ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-xs px-4 py-2 rounded-2xl {{ $msg->direction === 'outbound' ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-900' }}">
                            <p>{{ $msg->body }}</p>
                            <p class="text-xs mt-1 opacity-70">{{ $msg->created_at->format('H:i') }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-slate-500">Nessun messaggio</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>