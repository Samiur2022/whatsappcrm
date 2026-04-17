<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WhatsApp CRM · Messaggi intelligenti</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
        
        * { font-family: 'Inter', sans-serif; }
        
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-12px); }
        }
        
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.2); }
            50% { box-shadow: 0 0 20px 5px rgba(16, 185, 129, 0.1); }
        }
        
        .animate-float { animation: float 5s ease-in-out infinite; }
        .animate-glow { animation: pulse-glow 3s ease-in-out infinite; }
        
        .text-shadow-soft { text-shadow: 0 2px 10px rgba(0,0,0,0.05); }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 via-white to-emerald-50 text-slate-800 antialiased min-h-screen flex items-center justify-center p-4 md:p-6">

    <!-- CONTENITORE PRINCIPALE CENTRATO -->
    <div class="w-full max-w-5xl mx-auto text-center">
        
        <!-- LOGO / ICONA ANIMATA -->
        <div class="flex justify-center mb-8">
            <div class="animate-float bg-gradient-to-br from-emerald-500 to-green-600 w-24 h-24 md:w-28 md:h-28 rounded-3xl flex items-center justify-center shadow-2xl shadow-emerald-200/50 animate-glow">
                <svg class="w-14 h-14 md:w-16 md:h-16 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12.031 2.016c-5.515 0-9.984 4.468-9.984 9.984 0 1.769.461 3.493 1.338 4.992L2.015 22.5l5.742-1.508a9.946 9.946 0 0 0 4.274 1.008c5.515 0 9.984-4.468 9.984-9.984 0-5.515-4.468-9.984-9.984-9.984z"/>
                </svg>
            </div>
        </div>
        
        <!-- TITOLO PRINCIPALE GRANDE E ANIMATO -->
        <h1 class="text-4xl md:text-6xl lg:text-7xl font-extrabold mb-6 tracking-tight leading-tight">
            <span class="bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                Gestisci WhatsApp
            </span>
            <br>
            <span class="bg-gradient-to-r from-emerald-600 to-green-500 bg-clip-text text-transparent">
                con semplicità
            </span>
        </h1>
        
        <!-- SOTTOTITOLO -->
        <p class="text-lg md:text-2xl text-slate-600 max-w-3xl mx-auto mb-12 leading-relaxed">
            Il CRM pensato per chi comunica con i clienti via WhatsApp. 
            <span class="font-medium text-slate-700">Veloce, intuitivo, sempre con te.</span>
        </p>
        
        <!-- PULSANTI PRINCIPALI -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-16">
            <a href="#" class="group w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-10 py-5 rounded-2xl shadow-lg shadow-emerald-200 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl text-lg">
                <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                </svg>
                Inizia gratuitamente
            </a>
            <a href="#" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-white border-2 border-slate-200 hover:border-emerald-300 text-slate-700 font-medium px-8 py-5 rounded-2xl shadow-sm transition-all duration-300 transform hover:-translate-y-1 text-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Guarda demo
            </a>
        </div>
        
        <!-- STATISTICHE SEMPLICI -->
        <div class="flex flex-wrap items-center justify-center gap-8 md:gap-16 mb-16">
            <div class="text-center">
                <span class="block text-3xl md:text-4xl font-bold text-emerald-700">+5.000</span>
                <span class="text-sm md:text-base text-slate-500 uppercase tracking-wide">clienti attivi</span>
            </div>
            <div class="w-px h-8 bg-slate-300 hidden md:block"></div>
            <div class="text-center">
                <span class="block text-3xl md:text-4xl font-bold text-emerald-700">1M+</span>
                <span class="text-sm md:text-base text-slate-500 uppercase tracking-wide">messaggi / mese</span>
            </div>
            <div class="w-px h-8 bg-slate-300 hidden md:block"></div>
            <div class="text-center">
                <span class="block text-3xl md:text-4xl font-bold text-emerald-700">99.9%</span>
                <span class="text-sm md:text-base text-slate-500 uppercase tracking-wide">affidabilità</span>
            </div>
        </div>
        
        <!-- FEATURE CARD (3) RESPONSIVE E CENTRATE -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto">
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-200/80 hover:shadow-md transition-all duration-300">
                <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center mx-auto mb-5 text-emerald-700">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-slate-800 mb-2">Risposte rapide</h3>
                <p class="text-slate-500">Modelli predefiniti per rispondere ai clienti in un attimo.</p>
            </div>
            
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-200/80 hover:shadow-md transition-all duration-300">
                <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center mx-auto mb-5 text-emerald-700">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-slate-800 mb-2">Messaggi programmati</h3>
                <p class="text-slate-500">Invia comunicazioni automatiche all'orario perfetto.</p>
            </div>
            
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-200/80 hover:shadow-md transition-all duration-300">
                <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center mx-auto mb-5 text-emerald-700">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-slate-800 mb-2">Sicuro e privato</h3>
                <p class="text-slate-500">Crittografia end‑to‑end per ogni conversazione.</p>
            </div>
        </div>
        
        <!-- FOOTER MINIMO -->
        <p class="text-sm text-slate-400 mt-20">
            WhatsApp CRM — Il tuo messaggio, la nostra priorità.
        </p>
    </div>

</body>
</html>