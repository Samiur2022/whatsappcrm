<x-app-layout>
    <x-slot name="title">Conversazioni</x-slot>
    <x-slot name="subtitle">Chat real-time WhatsApp</x-slot>

    <!-- Toast Notification Container -->
    <div x-data="chatApp()" class="flex h-[calc(100vh-12rem)] gap-4">
        <!-- Toast messages -->
        <div class="fixed top-4 right-4 z-50 flex flex-col gap-2" id="toast-container">
            <template x-for="toast in toasts" :key="toast.id">
                <div
                    x- transition
                    class="flex items-center gap-2 px-4 py-3 rounded-lg text-white shadow-lg text-sm font-medium"
                    :class="{
                        'bg-green-600': toast.type === 'success',
                        'bg-red-600': toast.type === 'error',
                        'bg-orange-500': toast.type === 'warning',
                        'bg-blue-600': toast.type === 'info'
                    }"
                    @click="removeToast(toast.id)"
                >
                    <span x-text="toast.message"></span>
                    <button @click.stop="removeToast(toast.id)" class="ml-2 font-bold text-lg leading-none">&times;</button>
                </div>
            </template>
        </div>

        <!-- Chat List (Left) -->
        <div class="w-80 rounded-3xl bg-white shadow-sm ring-1 ring-slate-200 overflow-hidden">
            <div class="p-4 border-b border-slate-200">
                <input x-model="search" @input="filterChats" placeholder="Cerca conversazione..." class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
            </div>

            <div class="overflow-y-auto h-full">
                <template x-for="chat in filteredChats" :key="chat.id">
                    <div
                        @click.prevent="selectChat(chat.id)"
                        class="p-4 border-b border-slate-100 hover:bg-slate-50 cursor-pointer transition-colors"
                        :class="selectedChatId === chat.id ? 'bg-slate-100' : ''"
                    >
                        <div class="flex items-start gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-900 text-white text-sm font-bold">
                                <span x-text="(chat.contact && chat.contact.name) ? chat.contact.name.charAt(0).toUpperCase() : '?'"></span>
                            </div>

                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-slate-900 truncate" x-text="(chat.contact && chat.contact.name) ? chat.contact.name : 'Unknown'"></p>
                                <p class="text-sm text-slate-500 truncate" x-text="chat.last_message ? chat.last_message : 'Nuova conversazione'"></p>
                                <p class="text-xs text-slate-400" x-text="formatTime(chat.last_message_at)"></p>
                            </div>

                            <span
                                x-show="(chat.unread_count ?? 0) > 0"
                                class="flex h-5 w-5 items-center justify-center rounded-full bg-indigo-600 text-xs text-white"
                                x-text="chat.unread_count"
                            ></span>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Chat Window (Right) -->
        <div class="flex-1 rounded-3xl bg-white shadow-sm ring-1 ring-slate-200 flex flex-col overflow-hidden">
            <template x-if="selectedChat">
                <div class="flex flex-col h-full">
                    <!-- Header -->
                    <div class="p-4 border-b border-slate-200 flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-900 text-white text-sm font-bold">
                            <span x-text="selectedChat?.contact?.name ? selectedChat.contact.name.charAt(0).toUpperCase() : '?'"></span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-900" x-text="selectedChat?.contact?.name ? selectedChat.contact.name : 'Unknown'"></h3>
                            <p class="text-sm text-slate-500" x-text="selectedChat?.contact?.phone ? selectedChat.contact.phone : ''"></p>
                            <!-- Assigned User -->
                            <p class="text-xs text-slate-400">
                                Assigned to:
                                <span x-text="selectedChat?.assigned_user?.name || 'Unassigned'"></span>
                            </p>
                        </div>
                        <div class="ml-auto">
                            <span class="inline-flex rounded-full px-2 py-1 text-xs font-medium bg-green-100 text-green-700">Online</span>
                        </div>
                    </div>

                    <!-- Messages -->
                    <div class="flex-1 overflow-y-auto p-4 space-y-4" id="messages">
                        <template x-for="msg in (selectedChat.messages ?? [])" :key="msg.id ?? (msg.created_at + '-' + msg.body)">
                            <div class="flex" :class="msg.direction === 'outbound' ? 'justify-end' : 'justify-start'">
                                <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-2xl" :class="msg.direction === 'outbound' ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-900'">
                                    <p x-text="msg.body"></p>
                                    <p class="text-xs mt-1 opacity-70" x-text="formatTime(msg.created_at)"></p>
                                    <p class="text-xs mt-0.5 opacity-60" x-text="msg.user ? 'By ' + msg.user : ''"></p>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Composer -->
                    <div class="p-4 border-t border-slate-200">
                        <form @submit.prevent="sendMessage" class="flex gap-3">
                            <input x-model="newMessage" type="text" placeholder="Scrivi un messaggio..." class="flex-1 rounded-2xl border border-slate-300 px-4 py-3 text-sm" required>
                            <button type="submit" class="rounded-2xl bg-indigo-600 px-6 py-3 text-white hover:bg-indigo-700 transition">Invia</button>
                        </form>
                    </div>
                </div>
            </template>

            <template x-if="!selectedChat">
                <div class="flex-1 flex items-center justify-center text-slate-500">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4-.8L3 20l1.2-3.2A7.73 7.73 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-slate-900">Seleziona una conversazione</h3>
                        <p class="mt-1 text-sm text-slate-500">Scegli dalla lista per iniziare a chattare.</p>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <script>
        function chatApp() {
            return {
                chats: @json($conversations),
                filteredChats: @json($conversations),
                selectedChatId: null,
                selectedChat: null,
                newMessage: '',
                search: '',
                toasts: [],
                toastId: 0,

                // Toast methods
                showToast(message, type = 'info') {
                    const id = ++this.toastId;
                    this.toasts.push({ id, message, type });
                    // Auto remove after 3 seconds
                    setTimeout(() => this.removeToast(id), 3000);
                },

                removeToast(id) {
                    this.toasts = this.toasts.filter(t => t.id !== id);
                },

                selectChat(id) {
                    this.selectedChatId = id;

                    fetch(`/conversations/${id}?ajax=1`, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(async (res) => {
                        if (!res.ok) {
                            if (res.status === 403) {
                                throw new Error('Non hai il permesso di visualizzare questa conversazione.');
                            }
                            throw new Error(`HTTP ${res.status}`);
                        }
                        return res.json();
                    })
                    .then((data) => {
                        this.selectedChat = data.conversation;
                        this.selectedChat.messages = data.messages ?? [];
                        this.selectedChat.unread_count = 0;

                        this.$nextTick(() => {
                            const el = document.getElementById('messages');
                            if (el) el.scrollTop = el.scrollHeight;
                        });
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                        this.showToast(error.message, 'error');
                    });
                },

                sendMessage() {
                    if (!this.selectedChatId) return;
                    if (!this.newMessage.trim()) return;

                    fetch(`/conversations/${this.selectedChatId}/send`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ body: this.newMessage })
                    })
                    .then(async (res) => {
                        if (!res.ok) {
                            const err = await res.json();
                            throw new Error(err.error || 'Unknown error');
                        }
                        return res.json();
                    })
                    .then((data) => {
                        if (data.success) {
                            this.selectedChat.messages = this.selectedChat.messages ?? [];
                            this.selectedChat.messages.push(data.message);
                            this.newMessage = '';

                            this.$nextTick(() => {
                                const el = document.getElementById('messages');
                                if (el) el.scrollTop = el.scrollHeight;
                            });
                            this.showToast('Messaggio inviato!', 'success');
                        } else {
                            this.showToast("Errore nell'invio", 'error');
                        }
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                        this.showToast('Failed to send: ' + error.message, 'error');
                    });
                },

                filterChats() {
                    const s = (this.search ?? '').toLowerCase();
                    this.filteredChats = (this.chats ?? []).filter(chat => {
                        const name = (chat.contact && chat.contact.name) ? chat.contact.name.toLowerCase() : '';
                        return name.includes(s);
                    });
                },

                formatTime(time) {
                    if (!time) return '';
                    const d = new Date(time);
                    if (Number.isNaN(d.getTime())) return time;
                    return d.toLocaleString('it-IT');
                }
            }
        }
    </script>
</x-app-layout>