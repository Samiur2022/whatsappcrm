<div class="flex h-full flex-col">
    <div class="border-b border-white/10 px-6 py-6">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3" data-turbo="false">
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
        {{-- Dashboard - sempre visibile --}}
        <a href="{{ route('dashboard') }}" data-turbo="false"
           class="flex items-center rounded-2xl px-4 py-3 transition {{ request()->routeIs('dashboard') ? 'bg-white/10 font-medium text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
            📊 Dashboard
        </a>

        {{-- Conversazioni - visibile con permesso 'view conversations' --}}
        @can('view conversations')
        <a href="{{ route('conversations.index') }}" data-turbo="false"
           class="flex items-center rounded-2xl px-4 py-3 transition {{ request()->routeIs('conversations.*') ? 'bg-white/10 font-medium text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
            💬 Conversazioni
        </a>
        @endcan

        {{-- Contatti - solo con 'manage contacts' --}}
        @can('manage contacts')
        <a href="{{ route('contacts.index') }}" data-turbo="false"
           class="flex items-center rounded-2xl px-4 py-3 transition {{ request()->routeIs('contacts.*') ? 'bg-white/10 font-medium text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
            👥 Contatti
        </a>
        @endcan

        {{-- Campagne - solo con 'manage campaigns' --}}
        @can('manage campaigns')
        <a href="{{ route('campaigns.index') }}" data-turbo="false"
           class="flex items-center rounded-2xl px-4 py-3 transition {{ request()->routeIs('campaigns.*') ? 'bg-white/10 font-medium text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
            📨 Campagne
        </a>
        @endcan

        {{-- Promemoria --}}
        <a href="{{ route('reminders.index') }}" data-turbo="false"
           class="flex items-center rounded-2xl px-4 py-3 transition {{ request()->routeIs('reminders.*') ? 'bg-white/10 font-medium text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
            🔔 Promemoria
        </a>

        {{-- Gestisci Utenti - solo con 'manage users' --}}
        @can('manage users')
        <a href="{{ route('admin.users.index') }}" data-turbo="false"
           class="flex items-center rounded-2xl px-4 py-3 transition {{ request()->routeIs('admin.users.*') ? 'bg-white/10 font-medium text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
            👤 Gestisci Utenti
        </a>
        @endcan

        {{-- Gestisci Ruoli - solo con 'manage roles' --}}
        @can('manage roles')
        <a href="{{ route('admin.roles.index') }}" data-turbo="false"
           class="flex items-center rounded-2xl px-4 py-3 transition {{ request()->routeIs('admin.roles.*') ? 'bg-white/10 font-medium text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
            🛡️ Gestisci Ruoli
        </a>
        @endcan

        {{-- Impostazioni - solo con 'manage settings' --}}
        @can('manage settings')
        <a href="{{ route('settings.index') }}" data-turbo="false"
           class="flex items-center rounded-2xl px-4 py-3 transition {{ request()->routeIs('settings.*') ? 'bg-white/10 font-medium text-white' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
            ⚙️ Impostazioni
        </a>
        @endcan
    </nav>

    <div class="border-t border-white/10 p-4">
        <button onclick="logoutWithAnimation()"
                class="flex w-full items-center justify-center rounded-2xl bg-white/10 px-4 py-3 text-sm font-medium text-white transition hover:bg-white/20">
            🚪 Esci
        </button>
        <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
            @csrf
        </form>
    </div>
</div>

<!-- TV Off Curtain Overlay -->
<div id="tv-curtain" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; z-index:9999; overflow:hidden;">
    <div id="top-curtain" style="position:absolute; top:0; left:0; width:100%; height:50%; background:#000; transform:translateY(0); transition:transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);"></div>
    <div id="bottom-curtain" style="position:absolute; bottom:0; left:0; width:100%; height:50%; background:#000; transform:translateY(0); transition:transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);"></div>
    <div id="white-dot" style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%) scale(0); width:8px; height:8px; background:#fff; border-radius:50%; transition:transform 0.4s ease-out, opacity 0.2s ease-out 0.6s; opacity:1;"></div>
</div>

<script>
    function logoutWithAnimation() {
        const overlay = document.getElementById('tv-curtain');
        const topCurtain = document.getElementById('top-curtain');
        const bottomCurtain = document.getElementById('bottom-curtain');
        const whiteDot = document.getElementById('white-dot');

        overlay.style.display = 'block';

        requestAnimationFrame(() => {
            topCurtain.style.transform = 'translateY(100%)';
            bottomCurtain.style.transform = 'translateY(-100%)';

            setTimeout(() => {
                whiteDot.style.transform = 'translate(-50%, -50%) scale(1)';
            }, 500);

            setTimeout(() => {
                whiteDot.style.opacity = '0';
            }, 900);

            setTimeout(() => {
                document.getElementById('logout-form').submit();
            }, 1200);
        });
    }
</script>