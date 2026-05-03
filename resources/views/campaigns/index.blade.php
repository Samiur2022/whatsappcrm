<x-app-layout>
    <x-slot name="title">Campagne</x-slot>
    <x-slot name="subtitle">Invia messaggi WhatsApp in blocco</x-slot>

    <div class="max-w-6xl mx-auto space-y-8" id="campaignApp">
        <!-- ROI Button Section -->
        <div class="flex justify-center">
            <button id="roiButton" onclick="celebrateAndGo()"
                class="relative inline-flex items-center justify-center px-10 py-5 overflow-hidden font-bold text-white bg-gradient-to-r from-purple-600 via-pink-500 to-red-500 rounded-full group hover:scale-105 transform transition-all duration-300 shadow-2xl hover:shadow-3xl animate-pulse">
                <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-purple-600 via-pink-500 to-red-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300 blur-xl"></span>
                <span class="relative flex items-center gap-3 text-xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                    Visualizza ROI Campagne
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </span>
            </button>
        </div>

        <!-- Canvas for Confetti -->
        <canvas id="confettiCanvas" style="position:fixed; top:0; left:0; width:100%; height:100%; pointer-events:none; z-index:9999; display:none;"></canvas>

        <!-- Excel Upload -->
        <div class="bg-white rounded-3xl p-6 shadow-sm ring-1 ring-slate-200">
            <h3 class="text-lg font-semibold mb-4">Importa contatti da Excel</h3>
            <input type="file" id="excelFileInput" accept=".xlsx,.csv" class="w-full text-sm" />
            <p id="excelMessage" class="text-sm text-green-600 mt-2" style="display:none;"></p>
        </div>

        <!-- Campaign Creation -->
        <div class="bg-white rounded-3xl p-6 shadow-sm ring-1 ring-slate-200">
            <h3 class="text-lg font-semibold mb-4">Nuova campagna</h3>
            <div class="space-y-4">
                <input id="campaignName" placeholder="Nome campagna" class="w-full rounded-2xl border px-4 py-3 text-sm">
                <textarea id="campaignBody" rows="3" placeholder="Messaggio..." class="w-full rounded-2xl border px-4 py-3 text-sm"></textarea>

                <div class="flex justify-between items-center">
                    <label class="text-sm font-medium">Destinatari (<span id="totalSelected">0</span>)</label>
                    <button id="selectAllBtn" class="text-indigo-600 text-sm">Seleziona tutti</button>
                </div>
                <div class="max-h-48 overflow-y-auto border rounded-2xl p-3 space-y-2" id="contactList">
                    @foreach($contacts as $contact)
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" value="{{ $contact->id }}" class="contact-checkbox">
                        <span>{{ $contact->name }} ({{ $contact->phone }})</span>
                    </label>
                    @endforeach
                </div>

                <button id="sendBtn" class="w-full rounded-2xl bg-indigo-600 py-3 text-white font-semibold hover:bg-indigo-700 disabled:opacity-50">
                    <span id="sendBtnText">Invia Messaggi</span>
                    <span id="sendBtnLoading" class="flex items-center justify-center gap-2" style="display:none;">
                        <svg class="animate-spin h-5 w-5" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Invio in corso...
                    </span>
                </button>

                <!-- Progress Bar -->
                <div id="progressBar" class="space-y-2" style="display:none;">
                    <div class="h-2 bg-slate-200 rounded-full overflow-hidden">
                        <div id="progressFill" class="h-full bg-indigo-600 transition-all duration-300" style="width:0%;"></div>
                    </div>
                    <p class="text-xs text-slate-500">
                        <span id="progressSent">0</span> / <span id="progressTotal">0</span> inviati
                        (<span id="progressFailed">0</span> falliti)
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1"></script>
    <script>
        function celebrateAndGo() {
            // কনফেটি ইফেক্ট
            const canvas = document.getElementById('confettiCanvas');
            canvas.style.display = 'block';
            
            const duration = 2 * 1000; // 2 সেকেন্ড
            const end = Date.now() + duration;

            (function frame() {
                confetti({
                    particleCount: 8,
                    angle: 60,
                    spread: 80,
                    origin: { x: 0, y: 0.7 },
                    colors: ['#6366f1', '#ec4899', '#f43f5e', '#10b981', '#f59e0b'],
                    shapes: ['circle', 'square', 'star'],
                    scalar: 1.5
                });
                confetti({
                    particleCount: 8,
                    angle: 120,
                    spread: 80,
                    origin: { x: 1, y: 0.7 },
                    colors: ['#6366f1', '#ec4899', '#f43f5e', '#10b981', '#f59e0b'],
                    shapes: ['circle', 'square', 'star'],
                    scalar: 1.5
                });

                if (Date.now() < end) {
                    requestAnimationFrame(frame);
                } else {
                    // কনফেটি শেষে ROI পেজে যাবে
                    canvas.style.display = 'none';
                    window.location.href = '/campaigns/roi';
                }
            }());
        }

        // ফাইল আপলোড করা হলে
        document.getElementById('excelFileInput').addEventListener('change', async function(e) {
            const file = e.target.files[0];
            if (!file) return;
            let form = new FormData();
            form.append('file', file);
            let res = await fetch('/campaigns/import-excel', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content') },
                body: form
            });
            let data = await res.json();
            if (data.success) {
                document.getElementById('excelMessage').style.display = 'block';
                document.getElementById('excelMessage').innerText = data.message;
                location.reload();
            }
        });

        // Select All
        document.getElementById('selectAllBtn').addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('.contact-checkbox');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            checkboxes.forEach(cb => cb.checked = !allChecked);
            updateTotalSelected();
        });

        // Update counter
        function updateTotalSelected() {
            const count = document.querySelectorAll('.contact-checkbox:checked').length;
            document.getElementById('totalSelected').innerText = count;
        }

        document.querySelectorAll('.contact-checkbox').forEach(cb => {
            cb.addEventListener('change', updateTotalSelected);
        });

        // Send Bulk
        document.getElementById('sendBtn').addEventListener('click', async function() {
            const name = document.getElementById('campaignName').value.trim();
            const body = document.getElementById('campaignBody').value.trim();
            const selected = Array.from(document.querySelectorAll('.contact-checkbox:checked')).map(cb => cb.value);

            if (!name || !body || selected.length === 0) {
                alert('Compila tutti i campi e seleziona destinatari');
                return;
            }

            // UI update
            document.getElementById('sendBtnText').style.display = 'none';
            document.getElementById('sendBtnLoading').style.display = 'flex';
            document.getElementById('sendBtn').disabled = true;
            document.getElementById('progressBar').style.display = 'block';
            document.getElementById('progressSent').innerText = '0';
            document.getElementById('progressTotal').innerText = selected.length;
            document.getElementById('progressFailed').innerText = '0';
            document.getElementById('progressFill').style.width = '0%';

            let res = await fetch('/campaigns', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                },
                body: JSON.stringify({
                    name: name,
                    body: body,
                    contacts: selected
                })
            });
            let data = await res.json();
            if (data.success) {
                let progressInterval = setInterval(async () => {
                    let progressRes = await fetch(`/campaigns/${data.campaign_id}/progress`);
                    let progressData = await progressRes.json();
                    document.getElementById('progressSent').innerText = progressData.sent;
                    document.getElementById('progressFailed').innerText = progressData.failed;
                    document.getElementById('progressFill').style.width = ((progressData.sent + progressData.failed) / progressData.total * 100) + '%';

                    if (progressData.status === 'completed' || progressData.status === 'failed') {
                        clearInterval(progressInterval);
                        document.getElementById('sendBtnText').style.display = 'inline';
                        document.getElementById('sendBtnLoading').style.display = 'none';
                        document.getElementById('sendBtn').disabled = false;
                    }
                }, 2000);
            } else {
                alert('Errore durante la creazione della campagna');
                document.getElementById('sendBtnText').style.display = 'inline';
                document.getElementById('sendBtnLoading').style.display = 'none';
                document.getElementById('sendBtn').disabled = false;
            }
        });
    </script>

    <style>
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        .animate-pulse {
            animation: pulse 2s infinite;
        }
        #roiButton:hover {
            animation: none;
            box-shadow: 0 0 30px rgba(236, 72, 153, 0.6), 0 0 60px rgba(99, 102, 241, 0.4);
        }
    </style>
</x-app-layout>