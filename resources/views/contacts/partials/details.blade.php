<div class="space-y-6">
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label class="text-sm font-medium text-slate-700">Nome</label>
            <p class="mt-1 text-slate-900">{{ $contact->name }}</p>
        </div>
        <div>
            <label class="text-sm font-medium text-slate-700">Telefono</label>
            <p class="mt-1 text-slate-900">{{ $contact->phone }}</p>
        </div>
        <div>
            <label class="text-sm font-medium text-slate-700">Email</label>
            <p class="mt-1 text-slate-900">{{ $contact->email ?? 'Non specificata' }}</p>
        </div>
        <div>
            <label class="text-sm font-medium text-slate-700">Stato</label>
            <p class="mt-1">
                @php
                    $statusLabels = [
                        'new' => 'Nuovo',
                        'active' => 'Attivo',
                        'pending' => 'In attesa',
                        'cancelled' => 'Annullato',
                        'success' => 'Successo',
                    ];
                @endphp
                <span class="inline-flex rounded-full px-2 py-1 text-xs font-medium {{ $statusColors[$contact->status] ?? 'bg-gray-100 text-gray-700' }}">
                    {{ $statusLabels[$contact->status] ?? 'Sconosciuto' }}
                </span>
            </p>
        </div>
        <div>
            <label class="text-sm font-medium text-slate-700">Assegnato a</label>
            <p class="mt-1 text-slate-900">{{ $contact->assignedUser->name ?? 'Non assegnato' }}</p>
        </div>
        <div>
            <label class="text-sm font-medium text-slate-700">Ultimo contatto</label>
            <p class="mt-1 text-slate-900">{{ $contact->last_contact_at?->format('d/m/Y H:i') ?? 'Mai' }}</p>
        </div>
    </div>
    @if($contact->file_path)
        <div>
            <label class="text-sm font-medium text-slate-700">File allegato</label>
            <div class="mt-1">
                <a href="{{ Storage::url($contact->file_path) }}" target="_blank" class="text-indigo-600 hover:text-indigo-500 underline">{{ basename($contact->file_path) }}</a>
            </div>
        </div>
    @endif
    <div>
        <label class="text-sm font-medium text-slate-700">Data creazione</label>
        <p class="mt-1 text-slate-900">{{ $contact->created_at->format('d/m/Y H:i') }}</p>
    </div>
</div>