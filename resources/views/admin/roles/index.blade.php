<x-app-layout>
    <x-slot name="title">Gestisci Ruoli</x-slot>

    <div class="max-w-4xl mx-auto py-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Ruoli</h2>
            <a href="{{ route('admin.roles.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-2xl text-sm hover:bg-indigo-700">
                + Nuovo Ruolo
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-green-100 text-green-800 px-4 py-2 rounded-2xl">{{ session('success') }}</div>
        @endif

        <div class="bg-white rounded-2xl shadow ring-1 ring-slate-200 overflow-hidden">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="p-4 text-left">Nome</th>
                        <th class="p-4 text-left">Permessi</th>
                        <th class="p-4 text-right">Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                    <tr class="border-t border-slate-100">
                        <td class="p-4 font-medium">{{ $role->name }}</td>
                        <td class="p-4 text-sm text-slate-600">
                            @forelse($role->permissions as $perm)
                                <span class="inline-block bg-indigo-100 text-indigo-700 rounded-full px-2 py-0.5 text-xs mr-1 mb-1">{{ $perm->name }}</span>
                            @empty
                                <span class="text-slate-400">Nessun permesso</span>
                            @endforelse
                        </td>
                        <td class="p-4 text-right">
                            <a href="{{ route('admin.roles.edit', $role) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Modifica</a>
                            <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="inline-block" onsubmit="return confirm('Sei sicuro?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Elimina</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>