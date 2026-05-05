<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <title>SNS Scopri tutti i bonus con un clic</title>
  <!-- Tailwind CSS v3 + Font Awesome 6 -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    html { scroll-behavior: smooth; }
    body { font-family: 'Inter', sans-serif; overflow-x: hidden; background: #fefefe; }
    /* Preloader fullscreen con testo BonusX */
    #preloader {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(145deg, #0b1120 0%, #111827 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 2000;
      transition: opacity 0.8s cubic-bezier(0.23, 1, 0.32, 1), visibility 0.8s;
      pointer-events: none;
      visibility: visible;
      opacity: 1;
    }
    #preloader.hide-preloader { opacity: 0; visibility: hidden; }
    .preloader-text {
      font-size: 3.2rem;
      font-weight: 800;
      letter-spacing: 10px;
      background: linear-gradient(135deg, #FFFFFF 20%, #A5F3FC 80%);
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
      text-transform: uppercase;
      animation: pulseGlow 1.6s ease-in-out infinite;
    }
    @keyframes pulseGlow {
      0% { opacity: 0.6; letter-spacing: 8px; filter: blur(0px);}
      50% { opacity: 1; letter-spacing: 14px; filter: blur(0.5px); text-shadow: 0 0 10px #2dd4bf;}
      100% { opacity: 0.6; letter-spacing: 8px; filter: blur(0px);}
    }
    /* Parallax */
    .parallax-bg {
      background-attachment: fixed;
      background-position: center;
      background-repeat: no-repeat;
      background-size: cover;
    }
    @media (max-width: 768px) { .parallax-bg { background-attachment: scroll; } }
    .mobile-menu {
      transition: transform 0.3s ease-in-out, opacity 0.3s;
      transform: translateX(100%);
      opacity: 0;
    }
    .mobile-menu.open { transform: translateX(0); opacity: 1; }
    .card-hover { transition: all 0.25s ease; }
    .card-hover:hover { transform: translateY(-6px); box-shadow: 0 25px 35px -12px rgba(0, 0, 0, 0.15); }
    button, a[role="button"], .whatsapp-trigger { cursor: pointer; }
  </style>
</head>
<body class="antialiased">

  <!-- PRELOADER con BONUSX al posto di SNS -->
  <div id="preloader">
    <div class="preloader-text">BonusX</div>
  </div>

  <!-- HEADER + NAVBAR RESPONSIVE -->
  <header class="fixed top-0 left-0 w-full bg-white/95 backdrop-blur-md shadow-md z-50 border-b border-gray-200/50">
    <div class="max-w-7xl mx-auto px-5 md:px-8">
      <div class="flex justify-between items-center py-4 md:py-5">
        <!-- logo / brand (non whatsapp) -->
        <div class="flex items-center gap-1" data-no-wa="true">
          <i class="fas fa-shield-alt text-2xl text-indigo-600"></i>
          <span class="text-2xl font-extrabold tracking-tight bg-gradient-to-r from-indigo-700 to-cyan-600 bg-clip-text text-transparent">BonusX</span>
        </div>
        <!-- Desktop menu (con data-no-wa per non aprire whatsapp) -->
        <nav class="hidden md:flex space-x-8 font-medium text-gray-700">
          <a href="#hero" data-no-wa="true" class="hover:text-indigo-600 transition">Home</a>
          <a href="#servizi" data-no-wa="true" class="hover:text-indigo-600 transition">Servizi</a>
          <a href="#funziona" data-no-wa="true" class="hover:text-indigo-600 transition">Come funziona</a>
          <a href="#numeri" data-no-wa="true" class="hover:text-indigo-600 transition">Impatto</a>
          <a href="#testimonianze" data-no-wa="true" class="hover:text-indigo-600 transition">Testimonianze</a>
          <a href="#contatto" data-no-wa="true" class="hover:text-indigo-600 transition">Contatti</a>
        </nav>
        <!-- Bottone CTA desktop -> apri WhatsApp -->
        <div class="hidden md:block">
          <a href="#" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-5 py-2 rounded-full transition shadow-md shadow-indigo-200 whatsapp-btn">Inizia ora →</a>
        </div>
        <!-- Hamburger (non whatsapp) -->
        <button id="menuBtn" class="block md:hidden text-gray-800 text-2xl focus:outline-none" data-no-wa="true">
          <i class="fas fa-bars"></i>
        </button>
      </div>
    </div>
    <!-- Mobile Menu overlay (solo voci di navigazione hanno data-no-wa, il grosso pulsante Inizia invece è whatsapp) -->
    <div id="mobileMenu" class="mobile-menu fixed top-0 right-0 w-full h-screen bg-white/95 backdrop-blur-xl z-40 flex flex-col items-center justify-center gap-8 text-xl font-semibold shadow-2xl md:hidden">
      <button id="closeMenuBtn" class="absolute top-6 right-6 text-3xl text-gray-700" data-no-wa="true"><i class="fas fa-times"></i></button>
      <a href="#hero" data-no-wa="true" class="hover:text-indigo-600 transition">Home</a>
      <a href="#servizi" data-no-wa="true" class="hover:text-indigo-600 transition">Servizi</a>
      <a href="#funziona" data-no-wa="true" class="hover:text-indigo-600 transition">Come funziona</a>
      <a href="#numeri" data-no-wa="true" class="hover:text-indigo-600 transition">Impatto</a>
      <a href="#testimonianze" data-no-wa="true" class="hover:text-indigo-600 transition">Testimonianze</a>
      <a href="#contatto" data-no-wa="true" class="hover:text-indigo-600 transition">Contatti</a>
      <!-- Questo pulsante INVIA a WhatsApp -->
      <a href="#" class="bg-indigo-600 text-white px-8 py-3 rounded-full w-48 text-center shadow-lg whatsapp-btn">Inizia ora</a>
    </div>
  </header>

  <main class="pt-20">

    <!-- SEZIONE 1: HERO con parallax -->
    <section id="hero" class="relative parallax-bg" style="background-image: linear-gradient(117deg, rgba(2,0,36,0.82) 0%, rgba(15,25,55,0.85) 100%), url('https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80'); background-attachment: fixed;">
      <div class="max-w-7xl mx-auto px-5 py-24 md:py-32 text-white">
        <div class="md:w-2/3 space-y-6">
          <span class="inline-block bg-indigo-500/20 backdrop-blur-sm px-4 py-1 rounded-full text-sm font-medium tracking-wide">✨ Piattaforma n°1 in Italia</span>
          <h1 class="text-4xl md:text-6xl font-extrabold leading-tight">Il tuo alleato che <span class="text-cyan-300">semplifica la burocrazia</span></h1>
          <p class="text-lg md:text-xl text-gray-200 max-w-2xl">Troviamo bonus che non sapevi di poter avere, ottimizziamo le tasse, gestiamo le pratiche difficili. Tu vivi, noi facciamo il resto.</p>
          <div class="flex flex-wrap gap-4 pt-4">
            <a href="#" class="bg-white text-indigo-800 font-bold px-7 py-3 rounded-full shadow-xl hover:shadow-2xl transition hover:scale-105 whatsapp-btn">Scopri i tuoi bonus →</a>
            <a href="#" class="border border-white/40 hover:bg-white/10 px-7 py-3 rounded-full transition whatsapp-btn">Guarda il video <i class="fa-regular fa-circle-play ml-1"></i></a>
          </div>
          <div class="flex flex-wrap gap-5 text-sm text-gray-200 pt-6">
            <span><i class="fa-regular fa-clock mr-1"></i> Nessuna coda</span>
            <span><i class="fa-regular fa-circle-check mr-1"></i> Assistenza continua</span>
            <span><i class="fa-solid fa-shield mr-1"></i> Professionisti qualificati</span>
          </div>
        </div>
      </div>
    </section>

    <!-- SEZIONE 2: SERVIZI (tutti i link/btn vanno su WhatsApp) -->
    <section id="servizi" class="py-20 px-5 bg-white">
      <div class="max-w-7xl mx-auto">
        <div class="text-center max-w-2xl mx-auto mb-14">
          <span class="text-indigo-600 font-semibold uppercase tracking-wide">Cosa offriamo</span>
          <h2 class="text-3xl md:text-4xl font-bold mt-2 text-gray-800">Oltre 600 bonus e agevolazioni, <span class="text-indigo-600">tutti in un click</span></h2>
          <p class="text-gray-500 mt-4">Bonus casa, istruzione, trasporti, lavoro, famiglia: scopri subito ciò che ti spetta.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-7">
          <div class="bg-gray-50 rounded-2xl p-6 card-hover border border-gray-100 shadow-sm">
            <div class="h-12 w-12 rounded-xl bg-indigo-100 flex items-center justify-center mb-5"><i class="fas fa-file-invoice-dollar text-2xl text-indigo-700"></i></div>
            <h3 class="text-xl font-bold text-gray-800">ISEE & Dichiarazione</h3>
            <p class="text-gray-500 mt-2">Calcolo ISEE in 24 ore, Modello 730 precompilato e detrazioni massimizzate senza errori.</p>
            <a href="#" class="inline-flex items-center mt-4 text-indigo-600 font-medium whatsapp-btn">Richiedi ora <i class="fas fa-arrow-right ml-1 text-sm"></i></a>
          </div>
          <div class="bg-gray-50 rounded-2xl p-6 card-hover border border-gray-100 shadow-sm">
            <div class="h-12 w-12 rounded-xl bg-emerald-100 flex items-center justify-center mb-5"><i class="fas fa-briefcase text-2xl text-emerald-700"></i></div>
            <h3 class="text-xl font-bold text-gray-800">NASpI / Disoccupazione</h3>
            <p class="text-gray-500 mt-2">Ottenere la disoccupazione rapidamente, supporto completo per la domanda INPS.</p>
            <a href="#" class="inline-flex items-center mt-4 text-indigo-600 font-medium whatsapp-btn">Scopri di più <i class="fas fa-arrow-right ml-1 text-sm"></i></a>
          </div>
          <div class="bg-gray-50 rounded-2xl p-6 card-hover border border-gray-100 shadow-sm">
            <div class="h-12 w-12 rounded-xl bg-amber-100 flex items-center justify-center mb-5"><i class="fas fa-baby-carriage text-2xl text-amber-700"></i></div>
            <h3 class="text-xl font-bold text-gray-800">Assegno Unico & Famiglia</h3>
            <p class="text-gray-500 mt-2">Gestiamo la domanda per ottenere il massimo importo mensile e bonus natalità.</p>
            <a href="#" class="inline-flex items-center mt-4 text-indigo-600 font-medium whatsapp-btn">Attiva ora <i class="fas fa-arrow-right ml-1 text-sm"></i></a>
          </div>
          <div class="bg-gray-50 rounded-2xl p-6 card-hover border border-gray-100 shadow-sm">
            <div class="h-12 w-12 rounded-xl bg-sky-100 flex items-center justify-center mb-5"><i class="fas fa-home text-2xl text-sky-700"></i></div>
            <h3 class="text-xl font-bold text-gray-800">Bonus Casa & Ristrutturazioni</h3>
            <p class="text-gray-500 mt-2">Ecobonus, bonus ristrutturazioni e Superbonus: verifica la tua idoneità.</p>
            <a href="#" class="inline-flex items-center mt-4 text-indigo-600 font-medium whatsapp-btn">Richiedi consulenza <i class="fas fa-arrow-right ml-1 text-sm"></i></a>
          </div>
          <div class="bg-gray-50 rounded-2xl p-6 card-hover border border-gray-100 shadow-sm">
            <div class="h-12 w-12 rounded-xl bg-rose-100 flex items-center justify-center mb-5"><i class="fas fa-graduation-cap text-2xl text-rose-700"></i></div>
            <h3 class="text-xl font-bold text-gray-800">Borse di studio & Università</h3>
            <p class="text-gray-500 mt-2">Opportunità regionali e nazionali per diritto allo studio, tasse agevolate.</p>
            <a href="#" class="inline-flex items-center mt-4 text-indigo-600 font-medium whatsapp-btn">Cerca borse <i class="fas fa-arrow-right ml-1 text-sm"></i></a>
          </div>
          <div class="bg-gray-50 rounded-2xl p-6 card-hover border border-gray-100 shadow-sm">
            <div class="h-12 w-12 rounded-xl bg-purple-100 flex items-center justify-center mb-5"><i class="fas fa-chalkboard-user text-2xl text-purple-700"></i></div>
            <h3 class="text-xl font-bold text-gray-800">Consulenza Premium</h3>
            <p class="text-gray-500 mt-2">Supporto telefonico con commercialisti e caf per casi complessi personalizzati.</p>
            <a href="#" class="inline-flex items-center mt-4 text-indigo-600 font-medium whatsapp-btn">Prenota ora <i class="fas fa-arrow-right ml-1 text-sm"></i></a>
          </div>
        </div>
      </div>
    </section>

    <!-- SEZIONE 3: COME FUNZIONA -->
    <section id="funziona" class="py-20 px-5 bg-gradient-to-br from-indigo-50 via-white to-sky-50">
      <div class="max-w-7xl mx-auto">
        <div class="text-center mb-12">
          <h2 class="text-3xl md:text-4xl font-bold text-gray-800">📲 Come funziona BonusX</h2>
          <p class="text-gray-500 max-w-2xl mx-auto mt-3">Semplice, veloce e senza burocrazia. Tre passaggi e ottieni i bonus che ti spettano.</p>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
          <div class="bg-white rounded-2xl p-7 shadow-md text-center card-hover">
            <div class="bg-indigo-100 w-14 h-14 rounded-full flex items-center justify-center mx-auto text-2xl font-black text-indigo-700">1</div>
            <h3 class="text-xl font-semibold mt-5">Crea il tuo profilo</h3>
            <p class="text-gray-500 mt-2">Rispondi a poche semplici domande, niente moduli complessi. In meno di 5 minuti.</p>
          </div>
          <div class="bg-white rounded-2xl p-7 shadow-md text-center card-hover">
            <div class="bg-indigo-100 w-14 h-14 rounded-full flex items-center justify-center mx-auto text-2xl font-black text-indigo-700">2</div>
            <h3 class="text-xl font-semibold mt-5">Scopri i bonus su misura</h3>
            <p class="text-gray-500 mt-2">L'algoritmo intelligente analizza oltre 600 agevolazioni e ti suggerisce quelle attive per te.</p>
          </div>
          <div class="bg-white rounded-2xl p-7 shadow-md text-center card-hover">
            <div class="bg-indigo-100 w-14 h-14 rounded-full flex items-center justify-center mx-auto text-2xl font-black text-indigo-700">3</div>
            <h3 class="text-xl font-semibold mt-5">Richiedi e ottieni</h3>
            <p class="text-gray-500 mt-2">Noi gestiamo la pratica con professionisti, tu ricevi i fondi senza stress.</p>
          </div>
        </div>
        <div class="text-center mt-12">
          <a href="#" class="inline-block bg-indigo-700 hover:bg-indigo-800 text-white px-8 py-3 rounded-xl font-semibold shadow-lg transition whatsapp-btn">Inizia subito – è gratuito</a>
        </div>
      </div>
    </section>

    <!-- SEZIONE 4: NUMERI E IMPATTO (parallax) -->
    <section id="numeri" class="parallax-bg py-20 px-5" style="background-image: linear-gradient(135deg, #0b1c3a 0%, #0a1630 100%), url('https://images.unsplash.com/photo-1521791136064-7986c2920216?q=80&w=2069&auto=format'); background-attachment: fixed; background-blend-mode: overlay;">
      <div class="max-w-7xl mx-auto text-white">
        <div class="text-center mb-12">
          <span class="text-cyan-300 uppercase tracking-wider font-semibold">fiducia & risultati</span>
          <h2 class="text-3xl md:text-4xl font-bold mt-2">BonusX in numeri: l'impatto reale</h2>
          <p class="text-gray-200 max-w-2xl mx-auto mt-3">Oltre 500.000 italiani hanno già ottenuto ciò che gli spetta.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 text-center">
          <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20">
            <div class="text-4xl md:text-5xl font-black text-cyan-300">570k+</div>
            <p class="text-lg font-medium mt-2">Utenti assistiti</p>
            <p class="text-sm text-gray-200">in tutta Italia</p>
          </div>
          <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20">
            <div class="text-4xl md:text-5xl font-black text-cyan-300">€67M+</div>
            <p class="text-lg font-medium mt-2">Bonus erogati</p>
            <p class="text-sm text-gray-200">agevolazioni pubbliche</p>
          </div>
          <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20">
            <div class="text-4xl md:text-5xl font-black text-cyan-300">600+</div>
            <p class="text-lg font-medium mt-2">Servizi disponibili</p>
            <p class="text-sm text-gray-200">dall'ISEE ai bonus aziendali</p>
          </div>
          <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20">
            <div class="text-4xl md:text-5xl font-black text-cyan-300">98%</div>
            <p class="text-lg font-medium mt-2">Clienti soddisfatti</p>
            <p class="text-sm text-gray-200">recensioni positive</p>
          </div>
        </div>
      </div>
    </section>

    <!-- SEZIONE 5: TESTIMONIANZE -->
    <section id="testimonianze" class="py-20 px-5 bg-white">
      <div class="max-w-7xl mx-auto">
        <div class="text-center mb-12">
          <i class="fas fa-quote-left text-indigo-300 text-4xl"></i>
          <h2 class="text-3xl md:text-4xl font-bold mt-2 text-gray-800">Cosa dicono di noi</h2>
          <p class="text-gray-500">Storie vere di chi ha semplificato la propria vita con BonusX</p>
        </div>
        <div class="grid md:grid-cols-2 gap-8">
          <div class="bg-gray-50 p-7 rounded-2xl shadow-sm border border-gray-100 relative">
            <i class="fas fa-star text-yellow-400 text-sm mb-2"></i><i class="fas fa-star text-yellow-400 text-sm"></i><i class="fas fa-star text-yellow-400 text-sm"></i><i class="fas fa-star text-yellow-400 text-sm"></i><i class="fas fa-star text-yellow-400 text-sm"></i>
            <p class="text-gray-700 mt-3 italic">"Grazie a BonusX ho scoperto l'Assegno Unico e ottenuto il rimborso IRPEF che non sapevo di avere. Professionisti super disponibili!"</p>
            <div class="flex items-center mt-5 gap-3">
              <div class="bg-indigo-200 rounded-full w-10 h-10 flex items-center justify-center"><i class="fas fa-user text-indigo-700"></i></div>
              <div><strong>Martina R.</strong><p class="text-gray-400 text-sm"> — Milano</p></div>
            </div>
          </div>
          <div class="bg-gray-50 p-7 rounded-2xl shadow-sm border border-gray-100 relative">
            <i class="fas fa-star text-yellow-400 text-sm mb-2"></i><i class="fas fa-star text-yellow-400 text-sm"></i><i class="fas fa-star text-yellow-400 text-sm"></i><i class="fas fa-star text-yellow-400 text-sm"></i><i class="fas fa-star text-yellow-400 text-sm"></i>
            <p class="text-gray-700 mt-3">"Ho richiesto la NASpI in tempi record, zero file e zero code. Letteralmente cambiato la mia prospettiva."</p>
            <div class="flex items-center mt-5 gap-3">
              <div class="bg-indigo-200 rounded-full w-10 h-10 flex items-center justify-center"><i class="fas fa-user-tie text-indigo-700"></i></div>
              <div><strong>Luca D.</strong><p class="text-gray-400 text-sm"> — Roma</p></div>
            </div>
          </div>
          <div class="bg-gray-50 p-7 rounded-2xl shadow-sm border border-gray-100 relative">
            <i class="fas fa-star text-yellow-400 text-sm mb-2"></i><i class="fas fa-star text-yellow-400 text-sm"></i><i class="fas fa-star text-yellow-400 text-sm"></i><i class="fas fa-star text-yellow-400 text-sm"></i><i class="fas fa-star text-yellow-400 text-sm"></i>
            <p class="text-gray-700 mt-3">"Finalmente una piattaforma che ti accompagna passo dopo passo. Mi hanno aiutato con il bonus ristrutturazione e ho risparmiato migliaia di euro!"</p>
            <div class="flex items-center mt-5 gap-3">
              <div class="bg-indigo-200 rounded-full w-10 h-10 flex items-center justify-center"><i class="fas fa-hard-hat text-indigo-700"></i></div>
              <div><strong>Giulia e Marco</strong><p class="text-gray-400 text-sm"> — Napoli</p></div>
            </div>
          </div>
          <div class="bg-gray-50 p-7 rounded-2xl shadow-sm border border-gray-100 relative">
            <i class="fas fa-star text-yellow-400 text-sm mb-2"></i><i class="fas fa-star text-yellow-400 text-sm"></i><i class="fas fa-star text-yellow-400 text-sm"></i><i class="fas fa-star text-yellow-400 text-sm"></i><i class="fas fa-star text-yellow-400 text-sm"></i>
            <p class="text-gray-700 mt-3">"Utilissimo per il welfare aziendale, i miei dipendenti hanno scoperto bonus che ignoravano completamente."</p>
            <div class="flex items-center mt-5 gap-3">
              <div class="bg-indigo-200 rounded-full w-10 h-10 flex items-center justify-center"><i class="fas fa-building text-indigo-700"></i></div>
              <div><strong>Alessia, HR manager</strong><p class="text-gray-400 text-sm"> — Torino</p></div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- SEZIONE 6: CONTATTI / NEWSLETTER (il bottone iscriviti porta su WhatsApp) -->
    <section id="contatto" class="relative bg-indigo-900 text-white py-20 px-5 overflow-hidden">
      <div class="absolute inset-0 opacity-20 bg-[url('https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?q=80&w=2070')] bg-cover bg-center"></div>
      <div class="relative max-w-5xl mx-auto text-center z-10">
        <h2 class="text-3xl md:text-5xl font-bold">Non perdere nessuna agevolazione</h2>
        <p class="text-indigo-100 text-lg mt-4 max-w-2xl mx-auto">Iscriviti alla newsletter e ricevi aggiornamenti su nuovi bonus, scadenze e consigli fiscali.</p>
        <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center max-w-lg mx-auto">
          <input type="email" placeholder="La tua email" class="px-6 py-3 rounded-full text-gray-800 w-full focus:outline-none">
          <button class="bg-cyan-400 hover:bg-cyan-500 text-gray-900 font-bold px-7 py-3 rounded-full transition shadow-lg whatsapp-btn">Iscriviti gratis</button>
        </div>
        <div class="mt-12 flex flex-wrap justify-center gap-6 text-sm text-indigo-200">
          <a href="#" data-no-wa="true" class="hover:text-white transition"><i class="fab fa-facebook-f mr-1"></i> Facebook</a>
          <a href="#" data-no-wa="true" class="hover:text-white transition"><i class="fab fa-instagram mr-1"></i> Instagram</a>
          <a href="#" data-no-wa="true" class="hover:text-white transition"><i class="fab fa-linkedin-in mr-1"></i> LinkedIn</a>
          <a href="#" data-no-wa="true" class="hover:text-white transition">info@bonusx.it</a>
          <a href="#" data-no-wa="true" class="hover:text-white transition">Privacy & Cookie</a>
        </div>
        <div class="mt-8 text-indigo-200 text-xs">© 2025 BonusX · semplifichiamo la burocrazia italiana. P.IVA 11237050965</div>
      </div>
    </section>

  </main>

  <script>
    (function() {
      // Preloader Hide
      window.addEventListener('load', function() {
        const preloader = document.getElementById('preloader');
        if(preloader) {
          setTimeout(() => preloader.classList.add('hide-preloader'), 400);
        }
      });

      // Mobile menu toggle
      const menuBtn = document.getElementById('menuBtn');
      const mobileMenu = document.getElementById('mobileMenu');
      const closeMenuBtn = document.getElementById('closeMenuBtn');
      function openMobileMenu() { mobileMenu.classList.add('open'); document.body.style.overflow = 'hidden'; }
      function closeMobileMenu() { mobileMenu.classList.remove('open'); document.body.style.overflow = ''; }
      if(menuBtn) menuBtn.addEventListener('click', openMobileMenu);
      if(closeMenuBtn) closeMenuBtn.addEventListener('click', closeMobileMenu);
      const mobileLinks = mobileMenu ? mobileMenu.querySelectorAll('a:not([data-no-wa])') : [];
      mobileLinks.forEach(link => link.addEventListener('click', closeMobileMenu));
      const allNavLinks = document.querySelectorAll('[data-no-wa="true"]');
      allNavLinks.forEach(link => link.addEventListener('click', (e) => { /* allow normal scroll */ }));

      // WHATSAPP INTEGRATION: numero +39 353 204 4997
      const WHATSAPP_NUMBER = "393532044997";
      const WHATSAPP_URL = `https://wa.me/${WHATSAPP_NUMBER}?text=Ciao%20BonusX%2C%20vorrei%20ricevere%20maggiori%20informazioni%20sui%20bonus%20disponibili.`;

      function openWhatsApp(event, targetElement) {
        // Se l'elemento o un suo genitore ha data-no-wa, non fare nulla
        let el = targetElement;
        while(el && el !== document.body) {
          if(el.hasAttribute && el.hasAttribute('data-no-wa')) return false;
          el = el.parentElement;
        }
        event.preventDefault();
        window.open(WHATSAPP_URL, '_blank');
        return false;
      }

      // Gestione globale: tutti i link e bottoni che hanno classe 'whatsapp-btn' oppure qualsiasi pulsante/ancora senza data-no-wa
      document.body.addEventListener('click', function(e) {
        let target = e.target.closest('a, button');
        if(!target) return;
        // Se ha data-no-wa direttamente o ereditato -> skip
        let checkNoWA = target.closest('[data-no-wa]');
        if(checkNoWA) return;
        // Se è un bottone o un link (anche senza classe) lo blocchiamo e mandiamo su WA
        // Ma evitiamo di intercettare link di navigazione interna che hanno href="#..." senza classe whatsapp? 
        // In ogni caso, se è un link che inizia con # ma non ha classe whatsapp-btn potrebbe essere menu (ma hanno già data-no-wa)
        // Per sicurezza escludiamo solo se href inizia con # e non ha la classe whatsapp-btn (per evitare conflitti con ancora scroll residue)
        // Nel nostro codice tutti i link che devono rimanere per scroll hanno data-no-wa, quindi questa condizione ulteriore è ridondante ma utile.
        if(target.tagName === 'A' && target.getAttribute('href') && target.getAttribute('href').startsWith('#') && !target.classList.contains('whatsapp-btn')) {
          // Se è ancora di navigazione senza data-no-wa (non dovrebbe accadere) non interferiamo
          return;
        }
        // per i bottoni o link CTA, inviamo a WhatsApp
        e.preventDefault();
        window.open(WHATSAPP_URL, '_blank');
      });
      
      // Assicuriamoci che anche i bottoni dinamici o gli elementi con classe .whatsapp-btn vengano catturati dall'evento sopra,
      // ma aggiungiamo anche un piccolo fix per elementi che potrebbero avere href definito e prevenire doppio open.
      console.log("BonusX landing pronta — tutti i pulsanti reindirizzano a WhatsApp +393532044997");
    })();
  </script>
</body>
</html>