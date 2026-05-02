<div class="flex h-full flex-col">
    <div class="border-b border-white/10 px-6 py-6">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-indigo-500 text-lg font-bold">
                S
            </div>
            <div>
                <div class="text-base font-semibold">SNS CRM</div>
                <div class="text-xs text-slate-400">Pannello professionale</div>
            </div>
        </a>
    </div>

    <nav class="flex-1 space-y-2 px-4 py-6 text-sm">
        {{-- Dashboard  sempre visibile --}}
        <a
            href="{{ route('dashboard') }}"
            class="flex items-center rounded-2xl px-4 py-3 transition {{ request()->routeIs('dashboard') ? 'bg-white/10 font-medium text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
            Dashboard
        </a>

        {{-- Conversazioni  visibile con permesso 'view conversations' --}}
        @can('view conversations')
        <a
            href="{{ route('conversations.index') }}"
            class="flex items-center rounded-2xl px-4 py-3 transition {{ request()->routeIs('conversations.*') ? 'bg-white/10 font-medium text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
            Conversazioni
        </a>
        @endcan

        {{-- Contatti solo con 'manage contacts' --}}
        @can('manage contacts')
        <a
            href="{{ route('contacts.index') }}"
            class="flex items-center rounded-2xl px-4 py-3 transition {{ request()->routeIs('contacts.*') ? 'bg-white/10 font-medium text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
            Contatti
        </a>
        @endcan

        {{-- Campagne  solo con 'manage campaigns' --}}
        @can('manage campaigns')
        <a
            href="{{ route('campaigns.index') }}"
            class="flex items-center rounded-2xl px-4 py-3 transition {{ request()->routeIs('campaigns.*') ? 'bg-white/10 font-medium text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
            Campagne
        </a>
        @endcan



        {{-- Gestisci Utenti  solo con 'manage users' --}}
        @can('manage users')
        <a
            href="{{ route('admin.users.index') }}"
            class="flex items-center rounded-2xl px-4 py-3 transition {{ request()->routeIs('admin.users.*') ? 'bg-white/10 font-medium text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
            Gestisci Utenti
        </a>
        @endcan

        {{-- Gestisci Ruoli  solo con 'manage roles' --}}
        @can('manage roles')
        <a
            href="{{ route('admin.roles.index') }}"
            class="flex items-center rounded-2xl px-4 py-3 transition {{ request()->routeIs('admin.roles.*') ? 'bg-white/10 font-medium text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
            Gestisci Ruoli
        </a>
        @endcan

        {{-- Impostazioni  solo con 'manage settings' --}}
        @can('manage settings')
        <a
            href="{{ route('settings.index') }}"
            class="flex items-center rounded-2xl px-4 py-3 transition {{ request()->routeIs('settings.*') ? 'bg-white/10 font-medium text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
            Impostazioni
        </a>
        @endcan
    </nav>

    <div class="border-t border-white/10 p-4">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button
                type="submit"
                class="flex w-full items-center justify-center rounded-2xl bg-white/10 px-4 py-3 text-sm font-medium text-white transition hover:bg-white/20">
                Esci
            </button>
        </form>
    </div>
</div>