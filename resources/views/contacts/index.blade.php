<x-app-layout>
    <x-slot name="title">Contatti</x-slot>
    <x-slot name="subtitle">Gestisci tutti i contatti del tuo SNS CRM</x-slot>

    <div class="space-y-6">
        <!-- Header with Search, Filter, and Add Button -->
        <div class="flex flex-col gap-4 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">Rubrica contatti</h2>
                <p class="mt-1 text-sm text-slate-500">Visualizza, cerca e organizza i tuoi clienti.</p>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row">
                <input
                    type="text"
                    id="search"
                    placeholder="Cerca contatto..."
                    class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 sm:w-64"
                >
                <select id="status-filter" class="rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm">
                    <option value="">Tutti gli stati</option>
                    @foreach(\App\Models\Contact::$statuses as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
                <a href="{{ route('contacts.create') }}" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                    Nuovo contatto
                </a>
            </div>
        </div>

        <!-- Table -->
        <div class="rounded-3xl bg-white shadow-sm ring-1 ring-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 w-1/4">Nome</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 w-1/6 hidden sm:table-cell">Telefono</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 w-1/6 hidden md:table-cell">Email</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 w-1/6 hidden lg:table-cell">File</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 w-1/8">Stato</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 w-1/6 hidden xl:table-cell">Ultimo contatto</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-500 w-1/4">Azioni</th>
                        </tr>
                    </thead>
                    <tbody id="contacts-table">
                        @include('contacts.partials.table')
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-between">
            <div class="text-sm text-slate-500" id="total-count">
                {{ $contacts->total() }} contatti totali
            </div>
            <div class="flex items-center gap-2" id="pagination-links">
                {{ $contacts->links() }}
            </div>
        </div>
    </div>

    <!-- Status Change Modal -->
    <div id="statusModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4">
        <div class="w-full max-w-md rounded-3xl bg-white p-6">
            <h3 class="text-lg font-semibold text-slate-900">Cambia stato</h3>
            <p class="mt-2 text-sm text-slate-500">Seleziona il nuovo stato per questo contatto.</p>
            <div class="mt-4 space-y-2">
                @foreach(\App\Models\Contact::$statuses as $key => $label)
                    <button onclick="changeStatus('{{ $key }}')" class="w-full rounded-2xl border border-slate-300 py-3 text-sm hover:bg-slate-50">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
            <button onclick="closeStatusModal()" class="mt-4 w-full rounded-2xl bg-slate-900 py-3 text-white">Annulla</button>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4">
        <div class="w-full max-w-md rounded-3xl bg-white p-6">
            <h3 class="text-lg font-semibold text-slate-900">Conferma eliminazione</h3>
            <p class="mt-2 text-sm text-slate-500">Sei sicuro di voler eliminare questo contatto? Verrà spostato nel cestino.</p>
            <div class="mt-4 flex gap-3">
                <button onclick="closeDeleteModal()" class="flex-1 rounded-2xl border border-slate-300 py-3 text-slate-700">Annulla</button>
                <button id="confirmDelete" onclick="confirmDelete()" class="flex-1 rounded-2xl bg-red-600 py-3 text-white">Elimina</button>
            </div>
        </div>
    </div>

    <!-- View Details Modal -->
    <div id="viewModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4 overflow-y-auto">
        <div class="w-full max-w-2xl rounded-3xl bg-white p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-slate-900">Dettagli contatto</h3>
                <button onclick="closeViewModal()" class="text-slate-400 hover:text-slate-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div id="contactDetails"></div>
        </div>
    </div>

    <script>
        let currentContactId = null;

        function loadContacts() {
            const search = document.getElementById('search').value;
            const status = document.getElementById('status-filter').value;

            fetch(`/contacts?search=${encodeURIComponent(search)}&status=${status}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('contacts-table').innerHTML = data.table;
                document.getElementById('pagination-links').innerHTML = data.pagination;
                document.getElementById('total-count').textContent = data.total + ' contatti totali';
            })
            .catch(error => console.error('Error:', error));
        }

        function openStatusModal(contactId) {
            currentContactId = contactId;
            document.getElementById('statusModal').classList.remove('hidden');
            document.getElementById('statusModal').classList.add('flex');
        }

        function closeStatusModal() {
            document.getElementById('statusModal').classList.remove('flex');
            document.getElementById('statusModal').classList.add('hidden');
            currentContactId = null;
        }

        function changeStatus(status) {
            if (!currentContactId) return;

            fetch(`/contacts/${currentContactId}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({ status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadContacts();
                    closeStatusModal();
                } else {
                    alert('Errore nell\'aggiornamento dello stato');
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function openDeleteModal(contactId) {
            currentContactId = contactId;
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('flex');
            document.getElementById('deleteModal').classList.add('hidden');
            currentContactId = null;
        }

        function confirmDelete() {
            if (!currentContactId) return;

            fetch(`/contacts/${currentContactId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadContacts();
                    closeDeleteModal();
                } else {
                    alert('Errore nell\'eliminazione');
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function viewContact(contactId) {
            fetch(`/contacts/${contactId}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                document.getElementById('contactDetails').innerHTML = data.html;
                document.getElementById('viewModal').classList.remove('hidden');
                document.getElementById('viewModal').classList.add('flex');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Errore nel caricamento dei dettagli del contatto.');
            });
        }

        function closeViewModal() {
            document.getElementById('viewModal').classList.remove('flex');
            document.getElementById('viewModal').classList.add('hidden');
        }

        // Auto load on filter change
        document.getElementById('search').addEventListener('input', loadContacts);
        document.getElementById('status-filter').addEventListener('change', loadContacts);
    </script>
</x-app-layout>