@extends('layouts.app')

@section('title', 'Dashboard Client')

@section('content')
<section class="pt-32 pb-20">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-6 rounded-3xl border border-emerald-400/20 bg-emerald-400/10 px-6 py-4 text-emerald-100">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="mb-6 rounded-3xl border border-rose-400/20 bg-rose-400/10 px-6 py-4 text-rose-100">{{ $errors->first() }}</div>
        @endif

        <div class="grid gap-8 xl:grid-cols-[1.1fr_0.9fr]">
            <div class="panel p-8">
                <p class="text-sm uppercase tracking-[0.22em] text-cyan-300">Dashboard voyageur</p>
                <h1 class="mt-4 font-['Playfair_Display'] text-4xl text-white md:text-5xl">Bonjour {{ auth()->user()->name }}, vos itineraires et les meilleurs vols sont centralises ici.</h1>
                <div class="mt-8 grid gap-4 md:grid-cols-3">
                    <div class="rounded-3xl bg-white/5 p-5">
                        <p class="text-sm text-slate-400">Reservations</p>
                        <p class="mt-2 text-3xl font-black text-white">{{ $stats['activeBookings'] }}</p>
                    </div>
                    <div class="rounded-3xl bg-white/5 p-5">
                        <p class="text-sm text-slate-400">Total paye</p>
                        <p class="mt-2 text-3xl font-black text-white">{{ number_format((float) $stats['totalSpend'], 0, ',', ' ') }} EUR</p>
                    </div>
                    <div class="rounded-3xl bg-white/5 p-5">
                        <p class="text-sm text-slate-400">Prochain depart</p>
                        <p class="mt-2 text-lg font-black text-white">{{ $stats['nextFlight'] ? \Illuminate\Support\Carbon::parse($stats['nextFlight'])->format('d/m/Y H:i') : 'Aucun' }}</p>
                    </div>
                </div>
            </div>

            <div class="panel p-8">
                <p class="text-sm uppercase tracking-[0.22em] text-cyan-300">Recherche</p>
                <form method="GET" action="{{ route('dashboard') }}" class="mt-6 grid gap-4">
                    <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Ville, pays, compagnie..." class="form-field">
                    <div class="grid gap-4 md:grid-cols-2">
                        <input type="date" name="departure_date" value="{{ $filters['departure_date'] ?? '' }}" class="form-field">
                        <select name="status" class="form-field">
                            <option value="">Tous les statuts</option>
                            <option value="confirmed" @selected(($filters['status'] ?? '') === 'confirmed')>Confirme</option>
                            <option value="delayed" @selected(($filters['status'] ?? '') === 'delayed')>Retarde</option>
                            <option value="cancelled" @selected(($filters['status'] ?? '') === 'cancelled')>Annule</option>
                        </select>
                    </div>
                    <button type="submit" class="rounded-full bg-cyan-400 px-6 py-4 text-sm font-extrabold uppercase tracking-[0.18em] text-slate-950">Actualiser les offres</button>
                </form>
            </div>
        </div>

        <div class="mt-8 grid gap-8 xl:grid-cols-[1.12fr_0.88fr]">
            <div class="space-y-6">
                @forelse($flights as $flight)
                    <article class="panel p-6">
                        <div class="flex flex-col gap-6 xl:flex-row xl:items-center xl:justify-between">
                            <div class="flex-1">
                                <div class="flex flex-wrap items-center gap-3">
                                    <span class="rounded-full bg-white/5 px-3 py-1 text-xs uppercase tracking-[0.2em] text-cyan-300">{{ $flight->airline }}</span>
                                    <span class="rounded-full bg-white/5 px-3 py-1 text-xs uppercase tracking-[0.2em] text-slate-300">{{ $flight->flight_number }}</span>
                                    <span class="rounded-full px-3 py-1 text-xs uppercase tracking-[0.2em] {{ $flight->status === 'confirmed' ? 'bg-emerald-400/10 text-emerald-200' : ($flight->status === 'delayed' ? 'bg-amber-400/10 text-amber-200' : 'bg-rose-400/10 text-rose-200') }}">{{ $flight->status_label }}</span>
                                </div>

                                <div class="mt-5 grid items-center gap-5 md:grid-cols-[auto_1fr_auto]">
                                    <div>
                                        <p class="text-sm text-slate-400">{{ $flight->departure_airport_code }}</p>
                                        <p class="text-2xl font-black text-white">{{ $flight->departure_at->format('H:i') }}</p>
                                        <p class="text-sm text-slate-300">{{ $flight->departure_city }}</p>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-3">
                                            <div class="h-px flex-1 bg-gradient-to-r from-cyan-400/0 via-cyan-300 to-cyan-400/0"></div>
                                            <p class="text-xs uppercase tracking-[0.24em] text-cyan-300">{{ $flight->duration_label }}</p>
                                            <div class="h-px flex-1 bg-gradient-to-r from-cyan-400/0 via-cyan-300 to-cyan-400/0"></div>
                                        </div>
                                        <p class="mt-3 text-center text-sm text-slate-400">{{ $flight->stops_count === 0 ? 'Vol direct' : collect($flight->stops)->join(' • ') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-slate-400">{{ $flight->arrival_airport_code }}</p>
                                        <p class="text-2xl font-black text-white">{{ $flight->arrival_at->format('H:i') }}</p>
                                        <p class="text-sm text-slate-300">{{ $flight->arrival_city }}</p>
                                    </div>
                                </div>

                                <div class="mt-5 grid gap-4 text-sm text-slate-300 md:grid-cols-3">
                                    <div class="rounded-2xl bg-white/5 p-4">
                                        <p class="text-slate-400">Itineraire</p>
                                        <p class="mt-2 font-semibold text-white">{{ $flight->departure_city }} -> {{ $flight->arrival_city }}</p>
                                    </div>
                                    <div class="rounded-2xl bg-white/5 p-4">
                                        <p class="text-slate-400">Bagages</p>
                                        <p class="mt-2 font-semibold text-white">{{ $flight->baggage_info }}</p>
                                    </div>
                                    <div class="rounded-2xl bg-white/5 p-4">
                                        <p class="text-slate-400">Disponibilite</p>
                                        <p class="mt-2 font-semibold text-white">{{ $flight->seats_available }} siege(s)</p>
                                    </div>
                                </div>
                            </div>

                            <div class="w-full rounded-[28px] border border-white/10 bg-white/5 p-6 xl:w-72">
                                <p class="text-sm text-slate-400">Prix en temps reel</p>
                                <p class="mt-2 text-4xl font-black text-white">{{ number_format((float) $flight->current_price, 0, ',', ' ') }}</p>
                                <p class="text-sm text-slate-400">{{ $flight->currency }} par passager</p>
                                <p class="mt-4 text-xs uppercase tracking-[0.2em] text-cyan-300">Maj {{ optional($flight->price_last_updated_at)->diffForHumans() ?? 'realtime' }}</p>
                                <form method="POST" action="{{ route('bookings.store', $flight) }}" class="mt-6 grid gap-4">
                                    @csrf
                                    <select name="passengers" class="form-field">
                                        @for($i = 1; $i <= 4; $i++)
                                            <option value="{{ $i }}">{{ $i }} passager(s)</option>
                                        @endfor
                                    </select>
                                    <button type="submit" class="rounded-full bg-white px-5 py-3 text-sm font-black uppercase tracking-[0.18em] text-slate-950">Reserver ce vol</button>
                                </form>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="panel p-8 text-slate-300">Aucun vol ne correspond actuellement a votre recherche.</div>
                @endforelse
            </div>

            <div class="space-y-6">
                <div class="panel p-6">
                    <p class="text-sm uppercase tracking-[0.22em] text-cyan-300">Mes reservations</p>
                    <div class="mt-6 space-y-4">
                        @forelse($bookings as $booking)
                            <div class="rounded-[26px] border border-white/10 bg-white/5 p-5">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="text-xs uppercase tracking-[0.18em] text-cyan-300">{{ $booking->booking_reference }}</p>
                                        <p class="mt-2 text-xl font-black text-white">{{ $booking->flight->route_label }}</p>
                                        <p class="mt-1 text-sm text-slate-400">{{ $booking->flight->airline }} • {{ $booking->flight->flight_number }}</p>
                                    </div>
                                    <span class="rounded-full px-3 py-1 text-xs uppercase tracking-[0.2em] {{ $booking->flight->status === 'confirmed' ? 'bg-emerald-400/10 text-emerald-200' : ($booking->flight->status === 'delayed' ? 'bg-amber-400/10 text-amber-200' : 'bg-rose-400/10 text-rose-200') }}">{{ $booking->flight->status_label }}</span>
                                </div>
                                <div class="mt-5 space-y-3 text-sm text-slate-300">
                                    <div class="rounded-2xl bg-slate-950/40 p-4">
                                        <p class="text-slate-400">Itineraire complet</p>
                                        <p class="mt-2 font-semibold text-white">
                                            {{ $booking->flight->departure_city }} ({{ $booking->flight->departure_airport_code }})
                                            @foreach($booking->flight->stops ?? [] as $stop)
                                                -> {{ $stop }}
                                            @endforeach
                                            -> {{ $booking->flight->arrival_city }} ({{ $booking->flight->arrival_airport_code }})
                                        </p>
                                    </div>
                                    <div class="grid gap-3 md:grid-cols-2">
                                        <div class="rounded-2xl bg-slate-950/40 p-4">
                                            <p class="text-slate-400">Prix total paye</p>
                                            <p class="mt-2 font-semibold text-white">{{ number_format((float) $booking->total_price, 0, ',', ' ') }} {{ $booking->currency }}</p>
                                        </div>
                                        <div class="rounded-2xl bg-slate-950/40 p-4">
                                            <p class="text-slate-400">Duree du vol</p>
                                            <p class="mt-2 font-semibold text-white">{{ $booking->flight->duration_label }}</p>
                                        </div>
                                    </div>
                                    <div class="rounded-2xl bg-slate-950/40 p-4">
                                        <p class="text-slate-400">Notification email</p>
                                        <p class="mt-2 font-semibold text-white">{{ $booking->last_notified_at ? 'Envoyee le ' . $booking->last_notified_at->format('d/m/Y H:i') : 'Aucune notification envoyee' }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-3xl border border-dashed border-white/10 p-6 text-sm text-slate-400">Aucune reservation pour l'instant.</div>
                        @endforelse
                    </div>
                </div>

                <div class="panel p-6">
                    <p class="text-sm uppercase tracking-[0.22em] text-cyan-300">Destinations suivies</p>
                    <div class="mt-5 grid gap-3">
                        @foreach($destinations->take(4) as $destination)
                            <div class="rounded-2xl bg-white/5 p-4">
                                <p class="font-bold text-white">{{ $destination->display_name }}</p>
                                <p class="text-sm text-slate-400">{{ $destination->airport_name }} • {{ $destination->timezone }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
