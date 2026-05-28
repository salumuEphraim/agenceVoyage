@extends('layouts.app')

@section('title', 'Admin Panel')

@section('content')
<section class="pt-32 pb-20">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-6 rounded-3xl border border-emerald-400/20 bg-emerald-400/10 px-6 py-4 text-emerald-100">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="mb-6 rounded-3xl border border-rose-400/20 bg-rose-400/10 px-6 py-4 text-rose-100">{{ $errors->first() }}</div>
        @endif

        <div class="flex flex-col gap-6 xl:flex-row xl:items-end xl:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.22em] text-cyan-300">Administration securisee</p>
                <h1 class="mt-3 font-['Playfair_Display'] text-4xl text-white md:text-5xl">Pilotage complet de CMKS Travel</h1>
                <p class="mt-4 max-w-3xl text-slate-300 leading-8">Cette interface permet d'ajouter des destinations, modifier les vols, mettre a jour automatiquement les prix et informer les clients par email.</p>
            </div>
            <form method="POST" action="{{ route('admin.flights.refresh-pricing') }}">
                @csrf
                <button type="submit" class="rounded-full bg-cyan-400 px-6 py-4 text-sm font-extrabold uppercase tracking-[0.18em] text-slate-950">Recalculer les prix</button>
            </form>
        </div>

        <div class="mt-10 grid gap-4 md:grid-cols-4">
            <div class="panel p-5"><p class="text-sm text-slate-400">Destinations</p><p class="mt-2 text-3xl font-black text-white">{{ $stats['destinations'] }}</p></div>
            <div class="panel p-5"><p class="text-sm text-slate-400">Vols</p><p class="mt-2 text-3xl font-black text-white">{{ $stats['flights'] }}</p></div>
            <div class="panel p-5"><p class="text-sm text-slate-400">Reservations</p><p class="mt-2 text-3xl font-black text-white">{{ $stats['bookings'] }}</p></div>
            <div class="panel p-5"><p class="text-sm text-slate-400">Revenu</p><p class="mt-2 text-3xl font-black text-white">{{ number_format((float) $stats['revenue'], 0, ',', ' ') }} EUR</p></div>
        </div>

        <div class="mt-8 grid gap-8 xl:grid-cols-2">
            <div class="panel p-8">
                <p class="text-sm uppercase tracking-[0.22em] text-cyan-300">Ajouter une destination</p>
                <form method="POST" action="{{ route('admin.destinations.store') }}" class="mt-6 grid gap-4">
                    @csrf
                    <div class="grid gap-4 md:grid-cols-2">
                        <input name="city" placeholder="Ville" class="form-field">
                        <input name="country" placeholder="Pays" class="form-field">
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <input name="continent" placeholder="Continent" class="form-field">
                        <input name="timezone" placeholder="Fuseau horaire" class="form-field">
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <input name="airport_name" placeholder="Nom de l'aeroport" class="form-field">
                        <input name="airport_code" placeholder="Code aeroport" class="form-field">
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <input name="latitude" placeholder="Latitude" class="form-field">
                        <input name="longitude" placeholder="Longitude" class="form-field">
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <input name="hero_image" placeholder="Chemin image" class="form-field">
                        <div></div>
                    </div>
                    <textarea name="description" rows="4" placeholder="Description destination" class="form-field"></textarea>
                    <div class="flex flex-wrap gap-6 text-sm text-slate-300">
                        <label class="flex items-center gap-3"><input type="checkbox" name="is_featured" value="1"> Vedette</label>
                        <label class="flex items-center gap-3"><input type="checkbox" name="is_active" value="1" checked> Active</label>
                    </div>
                    <button type="submit" class="rounded-full bg-white px-6 py-3 text-sm font-black uppercase tracking-[0.18em] text-slate-950">Ajouter</button>
                </form>
            </div>

            <div class="panel p-8">
                <p class="text-sm uppercase tracking-[0.22em] text-cyan-300">Ajouter un vol</p>
                <form method="POST" action="{{ route('admin.flights.store') }}" class="mt-6 grid gap-4">
                    @csrf
                    <div class="grid gap-4 md:grid-cols-2">
                        <select name="destination_id" class="form-field">
                            <option value="">Destination</option>
                            @foreach($destinations as $destination)
                                <option value="{{ $destination->id }}">{{ $destination->display_name }}</option>
                            @endforeach
                        </select>
                        <input name="airline" placeholder="Compagnie" class="form-field">
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <input name="flight_number" placeholder="Numero de vol" class="form-field">
                        <input name="cabin_class" value="Economy" placeholder="Cabine" class="form-field">
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <input name="departure_city" value="Lubumbashi" placeholder="Ville depart" class="form-field">
                        <input name="departure_country" value="RDC" placeholder="Pays depart" class="form-field">
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <input name="departure_airport" value="Luano Airport" placeholder="Aeroport depart" class="form-field">
                        <input name="departure_airport_code" value="FBM" placeholder="Code depart" class="form-field">
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <input name="departure_timezone" value="Africa/Lubumbashi" placeholder="Fuseau depart" class="form-field">
                        <input name="stops" placeholder="Escales separees par virgule" class="form-field">
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <input type="datetime-local" name="departure_at" class="form-field">
                        <input type="datetime-local" name="arrival_at" class="form-field">
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <input type="number" min="1" max="240" name="seats_available" placeholder="Places" class="form-field">
                        <input type="number" min="10" max="100" name="demand_index" placeholder="Indice demande" class="form-field">
                    </div>
                    <div class="grid gap-4 md:grid-cols-3">
                        <input type="number" step="0.01" name="base_price" placeholder="Prix base" class="form-field">
                        <input name="currency" value="EUR" placeholder="Devise" class="form-field">
                        <select name="status" class="form-field">
                            <option value="confirmed">Confirme</option>
                            <option value="delayed">Retarde</option>
                            <option value="cancelled">Annule</option>
                        </select>
                    </div>
                    <textarea name="baggage_info" rows="3" placeholder="Infos bagages" class="form-field"></textarea>
                    <button type="submit" class="rounded-full bg-cyan-400 px-6 py-3 text-sm font-black uppercase tracking-[0.18em] text-slate-950">Ajouter le vol</button>
                </form>
            </div>
        </div>

        <div class="mt-8 panel p-8">
            <p class="text-sm uppercase tracking-[0.22em] text-cyan-300">Modifier les destinations</p>
            <div class="mt-6 grid gap-6">
                @foreach($destinations as $destination)
                    <form method="POST" action="{{ route('admin.destinations.update', $destination) }}" class="rounded-[28px] border border-white/10 bg-white/5 p-5">
                        @csrf
                        @method('PATCH')
                        <div class="grid gap-4 lg:grid-cols-4">
                            <input name="city" value="{{ $destination->city }}" class="form-field">
                            <input name="country" value="{{ $destination->country }}" class="form-field">
                            <input name="continent" value="{{ $destination->continent }}" class="form-field">
                            <input name="timezone" value="{{ $destination->timezone }}" class="form-field">
                        </div>
                        <div class="mt-4 grid gap-4 lg:grid-cols-4">
                            <input name="airport_name" value="{{ $destination->airport_name }}" class="form-field">
                            <input name="airport_code" value="{{ $destination->airport_code }}" class="form-field">
                            <input name="latitude" value="{{ $destination->latitude }}" class="form-field">
                            <input name="longitude" value="{{ $destination->longitude }}" class="form-field">
                        </div>
                        <div class="mt-4 grid gap-4 lg:grid-cols-4">
                            <input name="hero_image" value="{{ $destination->hero_image }}" class="form-field">
                        </div>
                        <textarea name="description" rows="2" class="form-field mt-4">{{ $destination->description }}</textarea>
                        <div class="mt-4 flex flex-wrap items-center justify-between gap-4">
                            <div class="flex gap-6 text-sm text-slate-300">
                                <label class="flex items-center gap-3"><input type="checkbox" name="is_featured" value="1" @checked($destination->is_featured)> Vedette</label>
                                <label class="flex items-center gap-3"><input type="checkbox" name="is_active" value="1" @checked($destination->is_active)> Active</label>
                            </div>
                            <button type="submit" class="rounded-full bg-white px-5 py-3 text-xs font-black uppercase tracking-[0.18em] text-slate-950">Mettre a jour</button>
                        </div>
                    </form>
                @endforeach
            </div>
        </div>

        <div class="mt-8 panel p-8">
            <p class="text-sm uppercase tracking-[0.22em] text-cyan-300">Modifier les vols</p>
            <div class="mt-6 grid gap-6">
                @foreach($flights as $flight)
                    <form method="POST" action="{{ route('admin.flights.update', $flight) }}" class="rounded-[28px] border border-white/10 bg-white/5 p-5">
                        @csrf
                        @method('PATCH')
                        <div class="grid gap-4 lg:grid-cols-4">
                            <select name="destination_id" class="form-field">
                                @foreach($destinations as $destination)
                                    <option value="{{ $destination->id }}" @selected($flight->destination_id === $destination->id)>{{ $destination->display_name }}</option>
                                @endforeach
                            </select>
                            <input name="airline" value="{{ $flight->airline }}" class="form-field">
                            <input name="flight_number" value="{{ $flight->flight_number }}" class="form-field">
                            <input name="cabin_class" value="{{ $flight->cabin_class }}" class="form-field">
                        </div>
                        <div class="mt-4 grid gap-4 lg:grid-cols-5">
                            <input name="departure_city" value="{{ $flight->departure_city }}" class="form-field">
                            <input name="departure_country" value="{{ $flight->departure_country }}" class="form-field">
                            <input name="departure_airport" value="{{ $flight->departure_airport }}" class="form-field">
                            <input name="departure_airport_code" value="{{ $flight->departure_airport_code }}" class="form-field">
                            <input name="departure_timezone" value="{{ $flight->departure_timezone }}" class="form-field">
                        </div>
                        <div class="mt-4 grid gap-4 lg:grid-cols-4">
                            <input type="datetime-local" name="departure_at" value="{{ $flight->departure_at->format('Y-m-d\\TH:i') }}" class="form-field">
                            <input type="datetime-local" name="arrival_at" value="{{ $flight->arrival_at->format('Y-m-d\\TH:i') }}" class="form-field">
                            <input name="stops" value="{{ collect($flight->stops)->join(', ') }}" class="form-field">
                            <select name="status" class="form-field">
                                <option value="confirmed" @selected($flight->status === 'confirmed')>Confirme</option>
                                <option value="delayed" @selected($flight->status === 'delayed')>Retarde</option>
                                <option value="cancelled" @selected($flight->status === 'cancelled')>Annule</option>
                            </select>
                        </div>
                        <div class="mt-4 grid gap-4 lg:grid-cols-5">
                            <input type="number" min="1" max="240" name="seats_available" value="{{ $flight->seats_available }}" class="form-field">
                            <input type="number" min="10" max="100" name="demand_index" value="{{ $flight->demand_index }}" class="form-field">
                            <input type="number" step="0.01" name="base_price" value="{{ $flight->base_price }}" class="form-field">
                            <input name="currency" value="{{ $flight->currency }}" class="form-field">
                            <input name="baggage_info" value="{{ $flight->baggage_info }}" class="form-field">
                        </div>
                        <div class="mt-4 flex flex-wrap items-center justify-between gap-4">
                            <p class="text-sm text-slate-400">Prix actuel {{ number_format((float) $flight->current_price, 0, ',', ' ') }} {{ $flight->currency }} - {{ $flight->duration_label }} - {{ $flight->bookings->count() }} reservation(s)</p>
                            <button type="submit" class="rounded-full bg-cyan-400 px-5 py-3 text-xs font-black uppercase tracking-[0.18em] text-slate-950">Mettre a jour et notifier</button>
                        </div>
                    </form>
                @endforeach
            </div>
        </div>

        <div class="mt-8 panel p-8">
            <p class="text-sm uppercase tracking-[0.22em] text-cyan-300">Reservations clients</p>
            <div class="mt-6 overflow-x-auto">
                <table class="min-w-[960px] w-full text-left text-sm text-slate-300">
                    <thead class="text-xs uppercase tracking-[0.22em] text-slate-500">
                        <tr>
                            <th class="pb-4">Reference</th>
                            <th class="pb-4">Client</th>
                            <th class="pb-4">Vol</th>
                            <th class="pb-4">Itineraire</th>
                            <th class="pb-4">Prix</th>
                            <th class="pb-4">Statut</th>
                            <th class="pb-4">Derniere notif.</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                            <tr class="border-t border-white/10">
                                <td class="py-4 font-semibold text-white">{{ $booking->booking_reference }}</td>
                                <td class="py-4">{{ $booking->user->name }}<br><span class="text-xs text-slate-500">{{ $booking->contact_email }}</span></td>
                                <td class="py-4">{{ $booking->flight->airline }}<br><span class="text-xs text-slate-500">{{ $booking->flight->flight_number }}</span></td>
                                <td class="py-4">{{ $booking->flight->departure_city }} -> {{ $booking->flight->arrival_city }}</td>
                                <td class="py-4">{{ number_format((float) $booking->total_price, 0, ',', ' ') }} {{ $booking->currency }}</td>
                                <td class="py-4">{{ $booking->flight->status_label }}</td>
                                <td class="py-4">{{ $booking->last_notified_at ? $booking->last_notified_at->format('d/m/Y H:i') : 'Jamais' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
