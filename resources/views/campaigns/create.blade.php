<x-app-layout>
    <x-slot name="title">Nuova Campagna</x-slot>
    <x-slot name="subtitle">Invia messaggi in blocco</x-slot>

    <div class="max-w-2xl mx-auto" x-data="campaignForm()">
        <div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-200 space-y-6">
            <div>
                <label class="block text-sm font-medium mb-1">Nome Campagna</label>
                <input type="text" x-model="name" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" placeholder="Nome della campagna">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Messaggio</label>
                <textarea x-model="body" rows="4" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" placeholder="Scrivi il messaggio..."></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Seleziona Destinatari</label>
                <div class="max-h-48 overflow-y-auto space-y-2 border rounded-2xl p-4">
                    @foreach($contacts as $contact)
                    <label class="flex items-center gap-2">
                        <input type="checkbox" value="{{ $contact->id }}" x-model="selectedContacts" class="rounded">
                        <span>{{ $contact->name }} ({{ $contact->phone }})</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <button @click="submitCampaign" class="w-full rounded-2xl bg-indigo-600 px-6 py-3 text-white font-semibold hover:bg-indigo-700 transition">
                Invia Campagna
            </button>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('campaignForm', () => ({
                name: '',
                body: '',
                selectedContacts: [],
                async submitCampaign() {
                    const res = await fetch('/campaigns', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            name: this.name,
                            body: this.body,
                            contacts: this.selectedContacts
                        })
                    });
                    if (res.ok) {
                        window.location.href = '/campaigns';
                    }
                }
            }));
        });
    </script>
</x-app-layout>