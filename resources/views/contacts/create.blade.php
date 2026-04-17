<x-app-layout>
    <x-slot name="title">Nuovo contatto</x-slot>
    <x-slot name="subtitle">Aggiungi un nuovo cliente al tuo SNS CRM</x-slot>

    <div class="max-w-2xl mx-auto">
        <form id="createContactForm" enctype="multipart/form-data" class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-200 space-y-6">
            <div>
                <label for="name" class="mb-2 block text-sm font-medium text-slate-700">Nome completo *</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    required
                    class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"
                    placeholder="Inserisci il nome del contatto"
                >
                <p id="name-error" class="mt-1 text-sm text-red-500 hidden"></p>
            </div>

            <div>
                <label for="phone" class="mb-2 block text-sm font-medium text-slate-700">Numero di telefono *</label>
                <input
                    type="tel"
                    id="phone"
                    name="phone"
                    required
                    class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"
                    placeholder="+39 345 123 4567"
                >
                <p id="phone-error" class="mt-1 text-sm text-red-500 hidden"></p>
            </div>

            <div>
                <label for="email" class="mb-2 block text-sm font-medium text-slate-700">Indirizzo email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"
                    placeholder="contatto@email.it"
                >
                <p id="email-error" class="mt-1 text-sm text-red-500 hidden"></p>
            </div>

            <div>
                <label for="status" class="mb-2 block text-sm font-medium text-slate-700">Stato iniziale *</label>
                <select
                    id="status"
                    name="status"
                    required
                    class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100"
                >
                    <option value="">Seleziona uno stato</option>
                    @foreach(\App\Models\Contact::$statuses as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
                <p id="status-error" class="mt-1 text-sm text-red-500 hidden"></p>
            </div>

            <div>
                <label for="file" class="mb-2 block text-sm font-medium text-slate-700">File allegato (PDF/DOC, max 25MB)</label>
                <input
                    type="file"
                    id="file"
                    name="file"
                    accept=".pdf,.doc,.docx"
                    class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 file:mr-4 file:py-2 file:px-4 file:rounded-l-2xl file:border-0 file:text-sm file:font-semibold file:bg-slate-50 file:text-slate-700 hover:file:bg-slate-100"
                >
                <p id="file-error" class="mt-1 text-sm text-red-500 hidden"></p>
                <p class="mt-1 text-xs text-slate-500">Opzionale: carica un documento relativo al contatto.</p>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                <a href="{{ route('contacts.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                    Annulla
                </a>
                <button
                    type="submit"
                    id="submitBtn"
                    class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-slate-800 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <svg id="loadingIcon" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Crea contatto
                </button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('createContactForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitBtn = document.getElementById('submitBtn');
            const loadingIcon = document.getElementById('loadingIcon');

            // Clear previous errors
            document.querySelectorAll('[id$="-error"]').forEach(el => {
                el.classList.add('hidden');
                el.textContent = '';
            });

            // Show loading
            submitBtn.disabled = true;
            loadingIcon.classList.remove('hidden');

            fetch('/contacts', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    alert(data.message);
                    window.location.href = '/contacts';
                } else {
                    // Handle validation errors
                    if (data.errors) {
                        Object.keys(data.errors).forEach(field => {
                            const errorEl = document.getElementById(field + '-error');
                            if (errorEl) {
                                errorEl.textContent = data.errors[field][0];
                                errorEl.classList.remove('hidden');
                            }
                        });
                    } else {
                        alert('Errore durante la creazione del contatto');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Errore di rete. Riprova.');
            })
            .finally(() => {
                // Hide loading
                submitBtn.disabled = false;
                loadingIcon.classList.add('hidden');
            });
        });
    </script>
</x-app-layout>