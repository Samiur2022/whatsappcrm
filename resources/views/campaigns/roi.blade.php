<x-app-layout>
    <x-slot name="title">ROI Campagne</x-slot>
    <x-slot name="subtitle">Analisi del ritorno sull'investimento</x-slot>

    <div class="max-w-7xl mx-auto space-y-8">
        <!-- ROI Table -->
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200 overflow-x-auto">
            <h3 class="text-lg font-semibold mb-4">Dettaglio Campagne</h3>
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="p-4 text-left">Campagna</th>
                        <th class="p-4 text-right">Inviati</th>
                        <th class="p-4 text-right">Risposte</th>
                        <th class="p-4 text-right">Conv. %</th>
                        <th class="p-4 text-right">Costo</th>
                        <th class="p-4 text-right">Profitto</th>
                        <th class="p-4 text-right">ROI %</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roiData as $data)
                    <tr class="border-t border-slate-100">
                        <td class="p-4">{{ $data['name'] }}</td>
                        <td class="p-4 text-right">{{ $data['sent'] }}</td>
                        <td class="p-4 text-right">{{ $data['responses'] }}</td>
                        <td class="p-4 text-right">{{ $data['conversion_rate'] }}%</td>
                        <td class="p-4 text-right">€{{ $data['total_cost'] }}</td>
                        <td class="p-4 text-right font-semibold {{ $data['estimated_profit'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            €{{ $data['estimated_profit'] }}
                        </td>
                        <td class="p-4 text-right">
                            <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium
                                {{ $data['roi'] >= 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $data['roi'] }}%
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Comparison Chart -->
        <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h3 class="text-lg font-semibold mb-4">Confronto Campagne</h3>
            <canvas id="roiChart" height="100"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('roiChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($roiData->pluck('name')),
                datasets: [
                    {
                        label: 'Messaggi Inviati',
                        data: @json($roiData->pluck('sent')),
                        backgroundColor: '#6366f1'
                    },
                    {
                        label: 'Risposte Ricevute',
                        data: @json($roiData->pluck('responses')),
                        backgroundColor: '#10b981'
                    },
                    {
                        label: 'Profitto Stimato (€)',
                        data: @json($roiData->pluck('estimated_profit')),
                        backgroundColor: '#f59e0b'
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom' } },
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>
</x-app-layout>