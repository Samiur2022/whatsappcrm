<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>
    <x-slot name="subtitle">Panoramica generale del tuo SNS CRM</x-slot>

    <div class="max-w-7xl mx-auto space-y-8">
        <!-- Top Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Conversations Card -->
            <a href="{{ route('conversations.index') }}" class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 hover:shadow-md transition block">
                <p class="text-sm text-slate-500">Conversazioni attive</p>
                <div class="text-3xl font-bold mt-2">{{ $openConversations }}</div>
                <span class="text-xs {{ $conversationIncrease >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $conversationIncrease }}% rispetto alla scorsa settimana
                </span>
            </a>
            <!-- Contacts Card -->
            <a href="{{ route('contacts.index') }}" class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 hover:shadow-md transition block">
                <p class="text-sm text-slate-500">Nuovi contatti</p>
                <div class="text-3xl font-bold mt-2">{{ $totalContacts }}</div>
                <span class="text-xs {{ $contactIncrease >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    +{{ $contactIncrease }}% questo mese
                </span>
            </a>
            <!-- Campaigns Card -->
            <a href="{{ route('campaigns.index') }}" class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 hover:shadow-md transition block">
                <p class="text-sm text-slate-500">Campagne attive</p>
                <div class="text-3xl font-bold mt-2">{{ $activeCampaigns }}</div>
                <span class="text-xs text-slate-500">{{ $plannedCampaigns }} pianificate per oggi</span>
            </a>
            <!-- Response Rate Card -->
            <a href="" class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 hover:shadow-md transition block">
                <p class="text-sm text-slate-500">Tasso di risposta</p>
                <div class="text-3xl font-bold mt-2">{{ $responseRate }}%</div>
                <span class="text-xs text-green-600">Prestazioni eccellenti</span>
            </a>
        </div>

        <!-- Chart + Recent Activities -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Chart -->
            <div class="lg:col-span-2 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h3 class="text-lg font-semibold mb-4">Andamento messaggi (7 giorni)</h3>
                <canvas id="messageChart" height="120"></canvas>
            </div>
            <!-- Recent Activities -->
            <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h3 class="text-lg font-semibold mb-4">Attività recenti</h3>
                <div class="space-y-4">
                    @forelse($recentCampaigns as $campaign)
                    <div class="border-b border-slate-100 pb-3 last:border-0">
                        <p class="text-sm font-medium text-slate-700">{{ $campaign->name }}</p>
                        <p class="text-xs text-slate-500">Creata da {{ $campaign->user->name ?? 'N/D' }} • {{ $campaign->created_at->diffForHumans() }}</p>
                    </div>
                    @empty
                    <p class="text-sm text-slate-500">Nessuna attività recente.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Today Summary -->
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h3 class="text-lg font-semibold mb-4">Riepilogo rapido</h3>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-center">
                <div>
                    <p class="text-sm text-slate-500">Messaggi inviati</p>
                    <p class="text-xl font-bold">{{ $todayStats['sent'] }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Messaggi ricevuti</p>
                    <p class="text-xl font-bold">{{ $todayStats['received'] }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Lead convertiti</p>
                    <p class="text-xl font-bold">{{ $todayStats['leads'] }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Operatori online</p>
                    <p class="text-xl font-bold">{{ $todayStats['online'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('messageChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartData['labels']),
                datasets: [
                    {
                        label: 'Inviati',
                        data: @json($chartData['outbound']),
                        borderColor: '#6366f1',
                        backgroundColor: 'rgba(99, 102, 241, 0.05)',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Ricevuti',
                        data: @json($chartData['inbound']),
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.05)',
                        fill: true,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>
</x-app-layout>