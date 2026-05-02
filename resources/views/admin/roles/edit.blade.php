<x-app-layout>
    <x-slot name="title">Modifica Ruolo</x-slot>
    <div class="max-w-xl mx-auto py-8">
        <h2 class="text-xl font-bold mb-4">Modifica Ruolo: {{ $role->name }}</h2>
        <form method="POST" action="{{ route('admin.roles.update', $role) }}">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nome Ruolo</label>
                <input type="text" name="name" value="{{ old('name', $role->name) }}" class="w-full border border-slate-300 rounded-lg px-3 py-2" required>
                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Permessi</label>
                <div class="space-y-2">
                    @foreach($permissions as $perm)
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="permissions[]" value="{{ $perm->name }}"
                            {{ in_array($perm->name, old('permissions', $rolePermissions ?? [])) ? 'checked' : '' }}
                            class="rounded border-slate-300">
                        <span class="text-sm">{{ $perm->name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-2xl hover:bg-indigo-700">Aggiorna Ruolo</button>
                <a href="{{ route('admin.roles.index') }}" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-2xl hover:bg-gray-300">Annulla</a>
            </div>
        </form>
    </div>
</x-app-layout>