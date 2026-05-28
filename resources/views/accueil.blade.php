@extends('layouts.app')

@section('title', 'Voyages Premium et Realistes')

@section('content')
<section class="relative overflow-hidden pt-32">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(34,211,238,0.20),_transparent_32%),radial-gradient(circle_at_bottom_right,_rgba(20,184,166,0.16),_transparent_26%),linear-gradient(135deg,_#020617_0%,_#0f172a_48%,_#082f49_100%)]"></div>
    <div class="relative mx-auto grid max-w-7xl gap-12 px-6 py-16 lg:grid-cols-[1.1fr_0.9fr] lg:px-8 lg:py-24">
        <div>
            <span class="inline-flex rounded-full border border-cyan-400/30 bg-white/5 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-cyan-200">
                Recherche, reservation et suivi de vol
            </span>
            <h1 class="mt-6 max-w-4xl font-['Playfair_Display'] text-5xl leading-tight text-white md:text-7xl">
                La version professionnelle de votre agence de voyage CMKS Travel.
            </h1>
            <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-300">
                Une application web moderne qui affiche les vrais itineraires, la duree de vol, le prix en temps reel, les statuts et les notifications automatiques au client.
            </p>
            <div class="mt-10 flex flex-col gap-4 sm:flex-row">
                <a href="{{ auth()->check() ? route('dashboard') : route('register') }}" class="inline-flex items-center justify-center rounded-full bg-cyan-400 px-7 py-4 text-sm font-extrabold uppercase tracking-[0.2em] text-slate-950 shadow-xl shadow-cyan-500/20 transition hover:bg-cyan-300">
                    {{ auth()->check() ? 'Ouvrir le dashboard' : 'Creer un compte' }}
                </a>
                <a href="#destinations" class="inline-flex items-center justify-center rounded-full border border-white/15 bg-white/5 px-7 py-4 text-sm font-bold uppercase tracking-[0.2em] text-white transition hover:bg-white/10">
                    Voir les destinations
                </a>
            </div>
        </div>

        <div class="panel p-6">
            <img src="{{ asset('images/destinations/avion1.jpg') }}" alt="CMKS Travel hero" class="h-72 w-full rounded-[28px] object-cover">
            <div class="mt-6 space-y-4">
                @foreach($featuredFlights as $flight)
                    <div class="rounded-3xl border border-white/10 bg-white/5 p-4">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-xs uppercase tracking-[0.22em] text-cyan-300">{{ $flight->airline }}</p>
                                <p class="mt-2 text-xl font-black text-white">{{ $flight->route_label }}</p>
                                <p class="mt-1 text-sm text-slate-400">{{ $flight->duration_label }} • {{ $flight->stops_count }} escale(s)</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-slate-400">Prix live</p>
                                <p class="text-2xl font-black text-white">{{ number_format((float) $flight->current_price, 0, ',', ' ') }} {{ $flight->currency }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section id="destinations" class="py-24">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mb-12 flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-cyan-300">Destinations</p>
                <h2 class="mt-3 font-['Playfair_Display'] text-4xl text-white md:text-5xl">Toutes les destinations actives du catalogue</h2>
            </div>
            <a href="{{ route('dashboard') }}" class="text-sm font-bold uppercase tracking-[0.2em] text-cyan-300 transition hover:text-cyan-200">Acceder au comparateur</a>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            @foreach($destinations as $destination)
                <article class="panel overflow-hidden">
                    <img src="{{ asset($destination->hero_image ?: 'images/destinations/Beautiful.jpg') }}" alt="{{ $destination->display_name }}" class="h-64 w-full object-cover">
                    <div class="p-7">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-xs uppercase tracking-[0.22em] text-cyan-300">{{ $destination->airport_code }} - {{ $destination->airport_name }}</p>
                                <h3 class="mt-3 text-2xl font-black text-white">{{ $destination->display_name }}</h3>
                            </div>
                            @if($destination->is_featured)
                                <span class="rounded-full bg-cyan-400/10 px-3 py-1 text-[10px] font-bold uppercase tracking-[0.18em] text-cyan-200">Vedette</span>
                            @else
                                <span class="rounded-full bg-white/5 px-3 py-1 text-[10px] font-bold uppercase tracking-[0.18em] text-slate-300">Nouveau</span>
                            @endif
                        </div>
                        <p class="mt-4 text-sm leading-7 text-slate-300">{{ $destination->description ?: 'Destination ajoutee depuis l administration CMKS Travel et disponible sur la page d accueil.' }}</p>
                        <div class="mt-5 flex items-center justify-between gap-4 border-t border-white/10 pt-4">
                            <p class="text-xs uppercase tracking-[0.18em] text-slate-400">{{ $destination->continent ?: 'Monde' }}</p>
                            <p class="text-sm font-bold text-white">
                                {{ $destination->flights_min_current_price ? 'A partir de ' . number_format((float) $destination->flights_min_current_price, 0, ',', ' ') . ' EUR' : 'Tarif sur demande' }}
                            </p>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="pb-24">
    <div class="mx-auto grid max-w-7xl gap-6 px-6 lg:grid-cols-3 lg:px-8">
        <div class="panel p-8">
            <p class="text-sm uppercase tracking-[0.22em] text-cyan-300">Prix dynamique</p>
            <p class="mt-4 text-lg leading-8 text-slate-300">Le tarif change selon la demande, la date de depart et le nombre de places restantes.</p>
        </div>
        <div class="panel p-8">
            <p class="text-sm uppercase tracking-[0.22em] text-cyan-300">Dashboard client</p>
            <p class="mt-4 text-lg leading-8 text-slate-300">Chaque reservation affiche l'itineraire complet, le statut du vol, le prix total paye et la duree reelle du trajet.</p>
        </div>
        <div class="panel p-8">
            <p class="text-sm uppercase tracking-[0.22em] text-cyan-300">Admin panel</p>
            <p class="mt-4 text-lg leading-8 text-slate-300">Les administrateurs peuvent ajouter des destinations, modifier les vols et notifier les clients en cas de changement.</p>
        </div>
    </div>
</section>
@endsection
