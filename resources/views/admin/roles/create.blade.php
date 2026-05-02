<x-app-layout>
    <x-slot name="title">Crea Ruolo</x-slot>
    <div class="max-w-xl mx-auto py-8">
        <h2 class="text-xl font-bold mb-4">Nuovo Ruolo</h2>
        <form method="POST" action="{{ route('admin.roles.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nome Ruolo</label>
                <input type="text" name="name" class="w-full border border-slate-300 rounded-lg px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Permessi</label>
                <div class="space-y-2">
                    @foreach($permissions as $perm)
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="permissions[]" value="{{ $perm->name }}" class="rounded border-slate-300">
                        <span class="text-sm">{{ $perm->name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-2xl hover:bg-indigo-700">Salva</button>
        </form>
    </div>
</x-app-layout>