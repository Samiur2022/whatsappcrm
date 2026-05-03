<x-app-layout>
    <x-slot name="title">Promemoria</x-slot>
    <x-slot name="subtitle">Gestisci i tuoi promemoria</x-slot>

    <div class="max-w-4xl mx-auto space-y-8" x-data="{
        title: '',
        description: '',
        remindAt: '',
        toasts: [],
        toastId: 0,

        showToast(message, type = 'success') {
            const id = ++this.toastId;
            this.toasts.push({ id, message, type });
            setTimeout(() => {
                this.toasts = this.toasts.filter(t => t.id !== id);
            }, 4000);
        },

        async saveReminder() {
            if (!this.title || !this.remindAt) {
                this.showToast('Compila tutti i campi obbligatori', 'error');
                return;
            }

            try {
                const res = await fetch('/reminders', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    },
                    body: JSON.stringify({
                        title: this.title,
                        description: this.description,
                        remind_at: this.remindAt
                    })
                });
                const data = await res.json();
                if (data.success) {
                    this.showToast('✅ ' + data.message, 'success');
                    this.title = '';
                    this.description = '';
                    this.remindAt = '';
                    setTimeout(() => location.reload(), 1500);
                } else {
                    this.showToast('Errore durante il salvataggio', 'error');
                }
            } catch (e) {
                console.error(e);
                this.showToast('Errore di rete', 'error');
            }
        },

        async deleteReminder(id) {
            if (!confirm('Eliminare questo promemoria?')) return;
            try {
                const res = await fetch(`/reminders/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
                });
                if (res.ok) {
                    this.showToast('🗑️ Promemoria eliminato', 'success');
                    setTimeout(() => location.reload(), 1000);
                }
            } catch (e) {
                console.error(e);
                this.showToast('Errore di rete', 'error');
            }
        }
    }">
        <!-- Toast Container -->
        <div class="fixed top-4 right-4 z-50 flex flex-col gap-2">
            <template x-for="toast in toasts" :key="toast.id">
                <div x-transition
                     class="flex items-center gap-2 px-4 py-3 rounded-lg text-white shadow-lg text-sm font-medium animate-bounce"
                     :class="{
                         'bg-green-600': toast.type === 'success',
                         'bg-red-600': toast.type === 'error',
                         'bg-orange-500': toast.type === 'warning',
                         'bg-blue-600': toast.type === 'info'
                     }">
                    <span x-text="toast.message"></span>
                    <button @click="toasts = toasts.filter(t => t.id !== toast.id)"
                            class="ml-2 font-bold text-lg leading-none">&times;</button>
                </div>
            </template>
        </div>

        <!-- Form -->
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h3 class="text-lg font-semibold mb-4">📅 Nuovo Promemoria</h3>
            <form @submit.prevent="saveReminder" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Titolo *</label>
                    <input x-model="title" placeholder="Es: Chiamata cliente" 
                           class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Descrizione</label>
                    <textarea x-model="description" rows="2" placeholder="Dettagli opzionali..." 
                              class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Data e ora *</label>
                    <input type="datetime-local" x-model="remindAt" 
                           class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition" required>
                </div>
                <button type="submit" 
                        class="w-full rounded-2xl bg-indigo-600 py-3 text-white font-semibold hover:bg-indigo-700 transition transform hover:scale-[1.02] active:scale-95">
                    🔔 Salva Promemoria
                </button>
            </form>
        </div>

        <!-- Upcoming Reminders -->
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h3 class="text-lg font-semibold mb-4">⏰ Prossimi Promemoria</h3>
            <div class="space-y-3">
                @forelse($reminders as $reminder)
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 border-b border-slate-100 pb-3 last:border-0">
                        <div class="flex items-start gap-3">
                            <span class="text-2xl">📌</span>
                            <div>
                                <p class="font-medium">{{ $reminder->title }}</p>
                                @if($reminder->description)
                                    <p class="text-sm text-slate-500">{{ $reminder->description }}</p>
                                @endif
                                <p class="text-xs text-slate-400 mt-1">
                                    🕒 {{ $reminder->remind_at->format('d/m/Y H:i') }}
                                    @if($reminder->is_sent)
                                        <span class="ml-2 text-green-600 font-semibold">✅ Inviato</span>
                                    @else
                                        <span class="ml-2 text-orange-500 font-semibold">⏳ In attesa</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <button @click="deleteReminder({{ $reminder->id }})" 
                                class="self-end sm:self-center text-red-500 hover:text-red-700 text-sm font-medium transition">
                            🗑️ Elimina
                        </button>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <span class="text-4xl">🎉</span>
                        <p class="text-slate-500 mt-2">Nessun promemoria. Creane uno nuovo!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>