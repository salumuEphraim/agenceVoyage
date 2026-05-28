@extends('layouts.app')

@section('title', 'Carte des Destinations')

@section('content')
<section class="pt-32 pb-20">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mb-10">
            <p class="text-sm uppercase tracking-[0.22em] text-cyan-300">Carte dynamique</p>
            <h1 class="mt-3 font-['Playfair_Display'] text-4xl text-white md:text-6xl">Destinations pilotees par la base CMKS Travel</h1>
            <p class="mt-4 max-w-3xl text-lg leading-8 text-slate-300">
                Les pays et villes affiches ici correspondent uniquement aux destinations enregistrees sur la page d'accueil et dans l'administration.
            </p>
        </div>

        <div class="grid gap-8 lg:grid-cols-[320px_1fr]">
            <aside class="panel p-8">
                <h2 class="text-3xl font-black text-white">Filtres</h2>

                <div class="mt-8 space-y-6">
                    <div>
                        <label for="continent-filter" class="mb-2 block text-sm font-medium text-slate-300">Continent</label>
                        <select id="continent-filter" class="form-field">
                            <option value="">Tous</option>
                            @foreach($continents as $continent)
                                <option value="{{ $continent }}">{{ $continent }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="budget-filter" class="mb-2 block text-sm font-medium text-slate-300">Budget maximum</label>
                        <input id="budget-filter" type="range" min="300" max="2000" value="2000" class="w-full accent-cyan-400">
                        <p class="mt-2 text-sm text-slate-400"><span id="budget-value">2000</span> EUR</p>
                    </div>

                    <button id="apply-filters" type="button" class="w-full rounded-2xl bg-cyan-400 px-6 py-4 text-sm font-extrabold uppercase tracking-[0.18em] text-slate-950">
                        Appliquer filtres
                    </button>
                </div>

                <div class="mt-10 border-t border-white/10 pt-8">
                    <h3 class="text-2xl font-black text-white">Populaires</h3>
                    <div class="mt-5 space-y-3">
                        @foreach($destinations->take(8) as $destination)
                            <button
                                type="button"
                                class="popular-destination block text-left text-base font-semibold text-cyan-300 transition hover:text-cyan-200"
                                data-lat="{{ $destination->latitude }}"
                                data-lng="{{ $destination->longitude }}">
                                {{ $destination->city }}, {{ $destination->country }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </aside>

            <div class="panel overflow-hidden p-3">
                <div id="map" class="h-[680px] w-full rounded-[28px]"></div>
            </div>
        </div>
    </div>
</section>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const allDestinations = @json($mapDestinations);

        const map = L.map('map').setView([11, 14], 2);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const markerLayer = L.layerGroup().addTo(map);
        const budgetFilter = document.getElementById('budget-filter');
        const budgetValue = document.getElementById('budget-value');
        const continentFilter = document.getElementById('continent-filter');

        function createMarker(destination) {
            return L.marker([destination.latitude, destination.longitude], {
                icon: L.divIcon({
                    className: 'custom-marker',
                    html: `<div class="map-marker">${destination.city.slice(0, 3)}</div>`,
                    iconSize: [46, 46],
                    iconAnchor: [23, 23]
                })
            }).bindPopup(`
                <div style="min-width:220px">
                    <strong>${destination.city}, ${destination.country}</strong><br>
                    ${destination.airport_name} (${destination.airport_code})<br>
                    Budget: ${destination.price_label}
                </div>
            `);
        }

        function renderMarkers() {
            markerLayer.clearLayers();

            const selectedContinent = continentFilter.value;
            const maxBudget = parseInt(budgetFilter.value, 10);

            const filtered = allDestinations.filter(destination => {
                if (selectedContinent && destination.continent !== selectedContinent) {
                    return false;
                }

                return destination.price === null || destination.price <= maxBudget;
            });

            filtered.forEach(destination => createMarker(destination).addTo(markerLayer));

            if (filtered.length > 0) {
                const bounds = L.latLngBounds(filtered.map(destination => [destination.latitude, destination.longitude]));
                map.fitBounds(bounds, { padding: [50, 50] });
            }
        }

        budgetFilter.addEventListener('input', function () {
            budgetValue.textContent = this.value;
        });

        document.getElementById('apply-filters').addEventListener('click', renderMarkers);

        document.querySelectorAll('.popular-destination').forEach(button => {
            button.addEventListener('click', function () {
                map.flyTo([parseFloat(this.dataset.lat), parseFloat(this.dataset.lng)], 6, { duration: 1.2 });
            });
        });

        renderMarkers();
    });
</script>

<style>
    .custom-marker {
        background: transparent;
        border: none;
    }

    .map-marker {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 46px;
        height: 46px;
        border-radius: 9999px;
        border: 4px solid white;
        background: linear-gradient(135deg, #22d3ee, #2563eb);
        color: white;
        font-weight: 800;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.35);
        text-transform: uppercase;
    }
</style>
@endsection
