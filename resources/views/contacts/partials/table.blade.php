@php
    use App\Models\Contact;
@endphp

@forelse($contacts as $contact)
    <tr class="hover:bg-slate-50 transition-colors">
        <td class="px-6 py-4 max-w-0">
            <div class="truncate">
                <p class="font-medium text-slate-900 truncate">{{ $contact->name }}</p>
                <p class="text-sm text-slate-500">Cliente</p>
            </div>
        </td>
        <td class="px-6 py-4 text-sm text-slate-600 hidden sm:table-cell">{{ $contact->phone }}</td>
        <td class="px-6 py-4 text-sm text-slate-600 hidden md:table-cell truncate">{{ $contact->email ?? 'N/A' }}</td>
       <td class="px-6 py-4 text-sm text-slate-600 hidden lg:table-cell">
    @if($contact->file_path)
        <a href="{{ asset('storage/contacts/' . basename($contact->file_path)) }}" target="_blank" class="text-indigo-600 hover:text-indigo-500 underline">{{ basename($contact->file_path) }}</a>
    @else
        {{ $contact->phone }}
    @endif
</td>
        <td class="px-6 py-4">
            @php
                $statusColors = [
                    'new' => 'bg-gray-100 text-gray-700',
                    'active' => 'bg-blue-100 text-blue-700',
                    'pending' => 'bg-yellow-100 text-yellow-700',
                    'cancelled' => 'bg-red-100 text-red-700',
                    'success' => 'bg-green-100 text-green-700',
                ];
            @endphp
            <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium cursor-pointer {{ $statusColors[$contact->status] ?? 'bg-gray-100 text-gray-700' }}" onclick="openStatusModal({{ $contact->id }})">
                {{ Contact::$statuses[$contact->status] }}
            </span>
        </td>
        <td class="px-6 py-4 text-sm text-slate-600 hidden xl:table-cell">{{ $contact->last_contact_at?->format('d/m H:i') ?? 'Mai' }}</td>
        <td class="px-6 py-4 text-right">
            <div class="flex justify-end gap-2">
                <button onclick="viewContact({{ $contact->id }})" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Visualizza
                </button>
                <button onclick="openDeleteModal({{ $contact->id }})" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Elimina
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="px-6 py-12 text-center text-sm text-slate-500">
            Nessun contatto trovato. Aggiungi il primo contatto!
        </td>
    </tr>
@endforelse