<x-app-layout>
    <x-slot name="title">Impostazioni</x-slot>
    <x-slot name="subtitle">Configura Twilio</x-slot>

    <div class="max-w-4xl mx-auto space-y-8" id="settingsApp">
        <!-- Toast Container -->
        <div id="toast-container" class="fixed top-4 right-4 z-50 flex flex-col gap-2"></div>

        <!-- Table Section -->
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h3 class="text-lg font-semibold mb-4">Configurazioni Twilio</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="p-4 text-left">Chiave</th>
                            <th class="p-4 text-left">Valore</th>
                            <th class="p-4 text-left">Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($settings as $setting)
                        <tr class="border-t border-slate-100">
                            <td class="p-4 font-medium">{{ strtoupper(str_replace('_', ' ', $setting->key)) }}</td>
                            <td class="p-4">
                                <code class="text-xs bg-slate-100 px-2 py-1 rounded">
                                    {{ \Illuminate\Support\Str::mask($setting->value, '*', 4, -4) }}
                                </code>
                            </td>
                            <td class="p-4">
                                <button onclick="deleteSetting('{{ $setting->key }}')"
                                        class="text-red-600 hover:text-red-800 text-xs font-semibold">
                                    Elimina
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="p-4 text-center text-slate-500">Nessuna impostazione salvata.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Form Section -->
        <div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
            <h3 class="text-lg font-semibold mb-6">Aggiungi / Aggiorna</h3>
            <form id="settingForm" class="space-y-6">
                @csrf
                @method('PATCH')
                <div>
                    <label class="block text-sm font-medium mb-2">Twilio Account SID</label>
                    <input type="text" id="twilio_sid" name="twilio_sid" 
                           class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Auth Token</label>
                    <input type="password" id="twilio_token" name="twilio_token" 
                           class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Mittente WhatsApp (whatsapp:+14155238886)</label>
                    <input type="text" id="twilio_from" name="twilio_from" 
                           class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm" required>
                </div>
                <div class="pt-4">
                    <button type="submit" 
                            class="w-full rounded-2xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white hover:bg-slate-800 transition">
                        Salva impostazioni
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // ========== TOAST SYSTEM ==========
        function showToast(message, type = 'info') {
            const container = document.getElementById('toast-container');
            if (!container) return;

            const bgColor = {
                success: 'bg-green-600',
                error: 'bg-red-600',
                warning: 'bg-orange-500',
                info: 'bg-blue-600'
            }[type] || 'bg-blue-600';

            const toast = document.createElement('div');
            toast.className = `flex items-center gap-2 px-4 py-3 rounded-lg text-white shadow-lg text-sm font-medium ${bgColor} transition-opacity duration-300`;
            toast.innerHTML = `
                <span>${message}</span>
                <button class="ml-2 font-bold text-lg leading-none" onclick="this.parentElement.remove()">&times;</button>
            `;
            container.appendChild(toast);
            setTimeout(() => { if (toast.parentNode) toast.remove(); }, 4000);
        }

        // ========== FORM SUBMISSION ==========
        document.getElementById('settingForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const payload = {
                twilio_sid: formData.get('twilio_sid'),
                twilio_token: formData.get('twilio_token'),
                twilio_from: formData.get('twilio_from'),
            };

            try {
                const res = await fetch('/settings', {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                if (res.ok) {
                    const data = await res.json();
                    showToast(data.message || 'Impostazioni aggiornate!', 'success');
                    // Clear form
                    document.getElementById('twilio_sid').value = '';
                    document.getElementById('twilio_token').value = '';
                    document.getElementById('twilio_from').value = '';
                    // Reload to refresh table after 1 sec
                    setTimeout(() => location.reload(), 800);
                } else {
                    const err = await res.json().catch(() => ({ message: 'Errore sconosciuto' }));
                    showToast(err.message || 'Errore durante il salvataggio', 'error');
                }
            } catch (error) {
                console.error(error);
                showToast('Errore di rete', 'error');
            }
        });

        // ========== DELETE ==========
        async function deleteSetting(key) {
            if (!confirm(`Eliminare la chiave "${key}"?`)) return;

            try {
                const res = await fetch(`/settings/${key}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                });

                if (res.ok) {
                    showToast('Impostazione eliminata!', 'success');
                    location.reload();
                } else {
                    const err = await res.json().catch(() => ({ message: 'Errore durante eliminazione' }));
                    showToast(err.message || 'Errore', 'error');
                }
            } catch (error) {
                console.error(error);
                showToast('Errore di rete', 'error');
            }
        }
    </script>
</x-app-layout>