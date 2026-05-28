<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CMKS Travel - @yield('title', 'Plateforme Voyage')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700,800&family=playfair-display:600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="min-h-screen bg-[var(--page-bg)] font-[Manrope] text-slate-100">
    <nav x-data="{ open: false, scrolled: false }"
         @scroll.window="scrolled = window.pageYOffset > 20"
         :class="scrolled ? 'bg-slate-950/90 backdrop-blur-xl border-b border-white/10 py-3' : 'bg-transparent py-5'"
         class="fixed inset-x-0 top-0 z-50 transition-all duration-300">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-6 lg:px-8">
            <a href="{{ route('home') }}" class="flex items-center gap-4">
                @if(file_exists(public_path('images/logo_cmks.png')))
                    <div class="h-14 w-14 overflow-hidden rounded-full border border-white/20 shadow-xl shadow-cyan-500/10">
                        <img src="{{ asset('images/logo_cmks.png') }}" alt="CMKS Travel" class="h-full w-full object-cover">
                    </div>
                @else
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-cyan-400 text-3xl font-black text-slate-950">C</div>
                @endif
                <span class="hidden text-3xl font-black tracking-tight text-white sm:block">CMKS<span class="text-cyan-400">.</span></span>
            </a>

            <div class="hidden items-center gap-8 md:flex">
                <a href="{{ route('home') }}" class="font-semibold text-white/80 transition hover:text-white">Accueil</a>
                <a href="{{ route('home') }}#destinations" class="font-semibold text-white/80 transition hover:text-white">Destinations</a>
                <a href="{{ route('dashboard') }}" class="font-semibold text-white/80 transition hover:text-white">Vols</a>
                <a href="{{ route('carte') }}" class="font-semibold text-white/80 transition hover:text-white">Carte</a>
                @auth
                    @if(auth()->user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" class="font-semibold text-white/80 transition hover:text-white">Admin</a>
                    @endif
                @endauth
            </div>

            <div class="hidden items-center gap-4 md:flex">
                @auth
                    <a href="{{ route('dashboard') }}" class="rounded-full bg-cyan-400 px-5 py-2.5 text-sm font-black uppercase tracking-[0.16em] text-slate-950 shadow-lg shadow-cyan-500/20 transition hover:bg-cyan-300">
                        Mon espace
                    </a>
                    @if(auth()->user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" class="rounded-full border border-white/15 px-5 py-2.5 text-sm font-bold text-white/90 transition hover:bg-white/10">
                            Admin panel
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-xs font-bold uppercase tracking-[0.24em] text-white/60 transition hover:text-rose-300">
                            Deconnexion
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-bold text-white transition hover:opacity-70">Connexion</a>
                    <a href="{{ route('register') }}" class="rounded-full bg-cyan-400 px-6 py-2.5 text-sm font-bold text-slate-950 transition hover:bg-cyan-300">
                        S'inscrire
                    </a>
                @endauth
            </div>

            <button @click="open = !open" class="p-2 text-white md:hidden">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div x-show="open" x-transition class="border-t border-white/10 bg-slate-950 md:hidden">
            <div class="space-y-4 px-6 py-8">
                <a href="{{ route('home') }}" class="block text-xl font-bold text-white">Accueil</a>
                <a href="{{ route('dashboard') }}" class="block text-xl font-bold text-white">Vols</a>
                <a href="{{ route('carte') }}" class="block text-xl font-bold text-white">Carte</a>
                @auth
                    @if(auth()->user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" class="block text-xl font-bold text-white">Admin</a>
                    @endif
                @endauth
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="border-t border-white/10 bg-slate-950 py-16 text-slate-300">
        <div class="mx-auto grid max-w-7xl gap-12 px-6 lg:grid-cols-4 lg:px-8">
            <div>
                <p class="text-xl font-black text-white">CMKS Travel</p>
                <p class="mt-4 text-sm leading-7 text-slate-400">Plateforme voyage professionnelle pour rechercher, reserver et suivre chaque trajet avec un niveau de service premium.</p>
            </div>
            <div>
                <p class="text-sm font-black uppercase tracking-[0.2em] text-white">Navigation</p>
                <div class="mt-4 space-y-3 text-sm">
                    <a href="{{ route('home') }}" class="block transition hover:text-cyan-300">Accueil</a>
                    <a href="{{ route('dashboard') }}" class="block transition hover:text-cyan-300">Dashboard</a>
                    <a href="{{ route('home') }}#destinations" class="block transition hover:text-cyan-300">Destinations</a>
                </div>
            </div>
            <div>
                <p class="text-sm font-black uppercase tracking-[0.2em] text-white">Suivi client</p>
                <div class="mt-4 space-y-3 text-sm text-slate-400">
                    <p>Prix dynamiques</p>
                    <p>Alertes de vol par email</p>
                    <p>Itineraires detailles</p>
                </div>
            </div>
            <div>
                <p class="text-sm font-black uppercase tracking-[0.2em] text-white">Contact</p>
                <div class="mt-4 space-y-3 text-sm text-slate-400">
                    <p>Lubumbashi, RDC</p>
                    <p>support@cmkstravel.test</p>
                    <p>+243 970 000 000</p>
                </div>
            </div>
        </div>
        <div class="mx-auto mt-12 max-w-7xl border-t border-white/10 px-6 pt-8 text-center text-xs uppercase tracking-[0.22em] text-slate-500 lg:px-8">
            CMKS Travel Agency 2026
        </div>
    </footer>
</body>
</html>
