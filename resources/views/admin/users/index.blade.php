<x-app-layout>
    <x-slot name="title">User Role Management</x-slot>

    <div x-data="{
        modalOpen: false,
        activityUser: { name: '', email: '', is_blocked: false },
        activities: [],
        totalActiveHours: 0,
        toasts: [],
        toastId: 0,

        showToast(message, type = 'info') {
            const id = ++this.toastId;
            this.toasts.push({ id, message, type });
            setTimeout(() => {
                this.toasts = this.toasts.filter(t => t.id !== id);
            }, 3000);
        },

        async showActivity(userId) {
            try {
                const res = await fetch(`/admin/users/${userId}/activity`, {
                    headers: { 'Accept': 'application/json' }
                });
                if (!res.ok) throw new Error('Errore');
                const data = await res.json();
                this.activityUser = data.user;
                this.activities = data.activities;
                this.totalActiveHours = data.total_active_hours;
                this.modalOpen = true;
            } catch (e) {
                console.error(e);
                this.showToast('Impossibile caricare le attività', 'error');
            }
        }
    }" class="max-w-6xl mx-auto py-8">

        <!-- Toast Container -->
        <div class="fixed top-4 right-4 z-50 flex flex-col gap-2">
            <template x-for="toast in toasts" :key="toast.id">
                <div x-transition
                     class="flex items-center gap-2 px-4 py-3 rounded-lg text-white shadow-lg text-sm font-medium"
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

        <h2 class="text-xl font-bold mb-4">Manage Users &amp; Roles</h2>

        <div class="bg-white rounded-2xl shadow ring-1 ring-slate-200 overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="p-4 text-left">User</th>
                        <th class="p-4 text-left hidden sm:table-cell">Email</th>
                        <th class="p-4 text-left">Current Role</th>
                        <th class="p-4 text-left">Change Role</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-center">Activity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr class="border-t border-slate-100">
                        <td class="p-4 font-medium">{{ $user->name }}</td>
                        <td class="p-4 text-sm hidden sm:table-cell">{{ $user->email }}</td>
                        <td class="p-4">
                            <span class="inline-block bg-indigo-100 text-indigo-700 rounded-full px-3 py-1 text-xs font-semibold">
                                {{ $user->roles->first()->name ?? 'No Role' }}
                            </span>
                        </td>
                        <td class="p-4">
                            <select 
                                class="border border-slate-300 rounded-lg px-3 py-2 text-sm"
                                onchange="updateRole(this, {{ $user->id }})"
                                data-current-role="{{ $user->roles->first()->name ?? '' }}">
                                <option value="">-- Select --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}"
                                        {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td class="p-4 text-center">
                            <button 
                                onclick="toggleBlock({{ $user->id }})"
                                class="inline-flex items-center rounded-full px-4 py-1.5 text-xs font-semibold transition
                                    {{ $user->is_blocked ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }}">
                                {{ $user->is_blocked ? 'Sblocca' : 'Blocca' }}
                            </button>
                        </td>
                        <td class="p-4 text-center">
                            <button @click="showActivity({{ $user->id }})"
                                    class="inline-flex items-center rounded-full bg-slate-100 text-slate-700 px-4 py-1.5 text-xs font-semibold hover:bg-slate-200 transition">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                View
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Activity Modal -->
        <div x-show="modalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
            <div class="fixed inset-0 bg-slate-900/50" @click="modalOpen = false"></div>
            <div class="relative bg-white rounded-3xl shadow-xl ring-1 ring-slate-200 w-full max-w-2xl max-h-[85vh] overflow-y-auto z-10">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-lg font-semibold">
                            Attività di <span x-text="activityUser.name"></span>
                        </h3>
                        <button @click="modalOpen = false" class="text-slate-400 hover:text-slate-600 text-2xl leading-none">&times;</button>
                    </div>
                    <div class="flex flex-wrap gap-4 text-sm mb-4">
                        <span class="bg-slate-100 px-3 py-1 rounded-full" x-text="activityUser.email"></span>
                        <span class="px-3 py-1 rounded-full"
                              :class="activityUser.is_blocked ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'"
                              x-text="activityUser.is_blocked ? 'Blocked' : 'Active'"></span>
                    </div>
                    <div class="bg-indigo-50 rounded-2xl p-4 mb-4">
                        <p class="text-sm font-medium">
                            Totale ore attive: <span class="text-indigo-700 font-bold" x-text="totalActiveHours"></span>
                        </p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="p-2 text-left">IP</th>
                                    <th class="p-2 text-left">Device</th>
                                    <th class="p-2 text-left">Login</th>
                                    <th class="p-2 text-left">Logout</th>
                                    <th class="p-2 text-left">Durata</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="act in activities" :key="act.logged_in_at">
                                    <tr class="border-t border-slate-100">
                                        <td class="p-2" x-text="act.ip_address"></td>
                                        <td class="p-2" x-text="(act.device_type ?? '?') + ' / ' + (act.browser ?? '?')"></td>
                                        <td class="p-2" x-text="act.logged_in_at"></td>
                                        <td class="p-2" x-text="act.logged_out_at ?? 'In corso'"></td>
                                        <td class="p-2" x-text="act.logged_out_at ? act.duration_minutes + ' min' : '–'"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                        <p x-show="activities.length === 0" class="text-center text-slate-500 py-4">Nessuna attività trovata.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- External functions for role & block (keep them accessible) -->
    <script>
        function getToast() {
            const el = document.querySelector('[x-data]');
            return el?.__x?.$data;
        }

        async function updateRole(selectElement, userId) {
            const role = selectElement.value;
            if (!role) return;
            try {
                const res = await fetch(`/admin/users/${userId}/assign-role`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ role })
                });
                const toast = getToast();
                if (res.ok) {
                    if (toast) toast.showToast('Ruolo aggiornato!', 'success');
                } else {
                    const err = await res.json();
                    if (toast) toast.showToast('Errore: ' + (err.message || 'Aggiornamento fallito'), 'error');
                    selectElement.value = selectElement.dataset.currentRole;
                }
            } catch (e) {
                console.error(e);
                const toast = getToast();
                if (toast) toast.showToast('Errore di rete', 'error');
            }
        }

        async function toggleBlock(userId) {
            try {
                const res = await fetch(`/admin/users/${userId}/toggle-block`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                const toast = getToast();
                if (res.ok) {
                    const data = await res.json();
                    if (toast) toast.showToast(data.blocked ? 'Utente bloccato' : 'Utente sbloccato', 'success');
                    setTimeout(() => location.reload(), 700);
                } else {
                    const err = await res.json();
                    if (toast) toast.showToast('Errore: ' + (err.message || 'Operazione fallita'), 'error');
                }
            } catch (e) {
                console.error(e);
                const toast = getToast();
                if (toast) toast.showToast('Errore di rete', 'error');
            }
        }
    </script>
</x-app-layout>