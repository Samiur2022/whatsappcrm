<header class="border-b border-slate-200 bg-white">
    <div class="flex items-center justify-between px-6 py-4 lg:px-8">
        <div class="flex items-center gap-4">
            <button
                type="button"
                class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-700 transition hover:bg-slate-50 lg:hidden"
                @click="sidebarOpen = true"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <div>
                <h1 class="text-xl font-bold text-slate-900">{{ $title ?? 'Dashboard' }}</h1>
                <p class="text-sm text-slate-500">{{ $subtitle ?? 'Panoramica generale del tuo CRM professionale' }}</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="hidden text-right sm:block">
                <p class="text-sm font-semibold text-slate-800">{{ Auth::user()->name ?? 'User' }}</p>
                <p class="text-xs text-slate-500">{{ Auth::user()->email ?? '' }}</p>
            </div>

            <div class="flex h-11 w-11 items-center justify-center rounded-full bg-slate-900 text-sm font-bold text-white">
                {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
            </div>
        </div>
    </div>
</header>