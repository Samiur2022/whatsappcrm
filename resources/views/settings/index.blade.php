<x-app-layout>
    <x-slot name="title">Impostazioni</x-slot>
    <x-slot name="subtitle">Configura Twilio e Email</x-slot>

    <div class="max-w-4xl mx-auto space-y-8" id="settingsApp">
        <!-- Toast Container -->
        <div id="toast-container" class="fixed top-4 right-4 z-50 flex flex-col gap-2"></div>

        <!-- Instructions -->
        <div class="rounded-3xl bg-gradient-to-r from-indigo-50 to-blue-50 p-6 shadow-sm ring-1 ring-indigo-100">
            <h3 class="text-lg font-semibold mb-3 flex items-center gap-2">
                <span>📖</span> Guida rapida alla configurazione
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-slate-600">
                <div class="space-y-2">
                    <p class="font-medium text-indigo-700">📱 Twilio (WhatsApp)</p>
                    <ul class="space-y-1 list-disc list-inside">
                        <li>Vai su <a href="https://console.twilio.com" target="_blank" class="text-indigo-600 underline">Twilio Console</a> → Account → API keys</li>
                        <li>Copia <strong>Account SID</strong> e <strong>Auth Token</strong></li>
                        <li>Per WhatsApp: usa <code class="bg-indigo-100 px-1 rounded">whatsapp:+numero</code></li>
                        <li>Incolla qui sotto e salva</li>
                    </ul>
                </div>
                <div class="space-y-2">
                    <p class="font-medium text-blue-700">✉️ Email (Gmail SMTP)</p>
                    <ul class="space-y-1 list-disc list-inside">
                        <li>Attiva la <strong>verifica in 2 passaggi</strong> su Google</li>
                        <li>Genera una <a href="https://myaccount.google.com/apppasswords" target="_blank" class="text-blue-600 underline">Password per le app</a></li>
                        <li>Inserisci i dati Gmail qui sotto</li>
                        <li><strong>Mailer:</strong> smtp | <strong>Host:</strong> smtp.gmail.com</li>
                        <li><strong>Porta:</strong> 587 | <strong>Crittografia:</strong> tls</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Twilio Table -->
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h3 class="text-lg font-semibold mb-4">📱 Configurazioni Twilio</h3>
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
                        @forelse($settings->whereIn('key', ['twilio_sid', 'twilio_token', 'twilio_from']) as $setting)
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
                            <td colspan="3" class="p-4 text-center text-slate-500">Nessuna impostazione Twilio.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Twilio Form -->
        <div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
            <h3 class="text-lg font-semibold mb-6">📱 Aggiorna Twilio</h3>
            <form id="twilioForm" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium mb-2">Twilio Account SID</label>
                    <input type="text" name="twilio_sid" value="{{ old('twilio_sid', $twilioSid) }}" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Auth Token</label>
                    <input type="password" name="twilio_token" value="{{ old('twilio_token', $twilioToken) }}" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Mittente WhatsApp</label>
                    <input type="text" name="twilio_from" value="{{ old('twilio_from', $twilioFrom) }}" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" required>
                </div>
                <button type="submit" class="w-full rounded-2xl bg-indigo-600 py-3 text-white font-semibold hover:bg-indigo-700 transition">Salva Twilio</button>
            </form>
        </div>

        <!-- Mail Table -->
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h3 class="text-lg font-semibold mb-4">✉️ Configurazioni Email</h3>
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
                        @forelse($settings->whereIn('key', ['mail_mailer', 'mail_host', 'mail_port', 'mail_username', 'mail_encryption', 'mail_from_address', 'mail_from_name']) as $setting)
                        <tr class="border-t border-slate-100">
                            <td class="p-4 font-medium">{{ strtoupper(str_replace('_', ' ', $setting->key)) }}</td>
                            <td class="p-4">
                                <code class="text-xs bg-slate-100 px-2 py-1 rounded">
                                    @if(in_array($setting->key, ['mail_password']))
                                        ********
                                    @else
                                        {{ $setting->value }}
                                    @endif
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
                            <td colspan="3" class="p-4 text-center text-slate-500">Nessuna impostazione Email.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mail Form -->
        <div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
            <h3 class="text-lg font-semibold mb-6">✉️ Aggiorna Email</h3>
            <form id="mailForm" class="space-y-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Mailer (smtp)</label>
                        <input type="text" name="mail_mailer" value="{{ old('mail_mailer', $mailMailer) }}" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Host</label>
                        <input type="text" name="mail_host" value="{{ old('mail_host', $mailHost) }}" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Porta</label>
                        <input type="text" name="mail_port" value="{{ old('mail_port', $mailPort) }}" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Username</label>
                        <input type="text" name="mail_username" value="{{ old('mail_username', $mailUsername) }}" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Password</label>
                        <input type="password" name="mail_password" placeholder="Lascia vuoto per non cambiare" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Crittografia (tls/ssl)</label>
                        <input type="text" name="mail_encryption" value="{{ old('mail_encryption', $mailEncryption) }}" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Indirizzo Mittente</label>
                        <input type="email" name="mail_from_address" value="{{ old('mail_from_address', $mailFromAddress) }}" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Nome Mittente</label>
                        <input type="text" name="mail_from_name" value="{{ old('mail_from_name', $mailFromName) }}" class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm" required>
                    </div>
                </div>

                <!-- Quick Reference -->
                <div class="bg-slate-50 rounded-2xl p-4 text-xs text-slate-600 space-y-1">
                    <p class="font-medium text-slate-700">🔍 Riferimento rapido:</p>
                    <p>➡️ <strong>Gmail:</strong> smtp.gmail.com / 587 / tls</p>
                    <p>➡️ <strong>Yahoo:</strong> smtp.mail.yahoo.com / 587 / tls</p>
                    <p>➡️ <strong>Outlook:</strong> smtp.office365.com / 587 / STARTTLS</p>
                    <p>➡️ <strong>Mailgun:</strong> smtp.mailgun.org / 587 / tls</p>
                </div>

                <button type="submit" class="w-full rounded-2xl bg-indigo-600 py-3 text-white font-semibold hover:bg-indigo-700 transition">Salva Email</button>
            </form>
        </div>
    </div>

   
     <script>
    // ========== TOAST ==========
    function showToast(message, type = 'info') {
        const container = document.getElementById('toast-container');
        if (!container) return;
        const bgColor = { success: 'bg-green-600', error: 'bg-red-600', warning: 'bg-orange-500', info: 'bg-blue-600' }[type] || 'bg-blue-600';
        const toast = document.createElement('div');
        toast.className = `flex items-center gap-2 px-4 py-3 rounded-lg text-white shadow-lg text-sm font-medium ${bgColor} transition-all duration-300`;
        toast.innerHTML = `<span>${message}</span><button class="ml-2 font-bold text-lg leading-none" onclick="this.parentElement.remove()">&times;</button>`;
        container.appendChild(toast);
        setTimeout(() => { if (toast.parentNode) toast.remove(); }, 4000);
    }

    // ========== DELETE SETTING ==========
    async function deleteSetting(key) {
        if (!confirm(`Eliminare "${key}"?`)) return;
        try {
            const res = await fetch(`/settings/${key}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content') }
            });
            if (res.ok) {
                showToast('Eliminato!', 'success');
                setTimeout(() => location.reload(), 800);
            }
        } catch (e) { console.error(e); }
    }

    // ========== TWILIO FORM ==========
    document.getElementById('twilioForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const payload = {};
        formData.forEach((v, k) => payload[k] = v);

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
            const data = await res.json();
            if (res.ok) {
                showToast(data.message || 'Twilio aggiornato!', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(data.message || 'Errore', 'error');
            }
        } catch (e) {
            console.error(e);
            showToast('Errore di rete', 'error');
        }
    });

    // ========== MAIL FORM ==========
    document.getElementById('mailForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const payload = {};
        formData.forEach((v, k) => payload[k] = v);

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
            const data = await res.json();
            if (res.ok) {
                showToast(data.message || 'Email aggiornata!', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(data.message || 'Errore', 'error');
            }
        } catch (e) {
            console.error(e);
            showToast('Errore di rete', 'error');
        }
    });
</script>

</x-app-layout>