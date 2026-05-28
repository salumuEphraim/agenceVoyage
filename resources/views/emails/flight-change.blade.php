<div style="font-family: Arial, sans-serif; background: #f6f7fb; padding: 24px;">
    <div style="max-width: 640px; margin: 0 auto; background: #ffffff; border-radius: 18px; overflow: hidden; border: 1px solid #e5e7eb;">
        <div style="padding: 28px; background: linear-gradient(135deg, #0f172a, #1d4ed8); color: #ffffff;">
            <h1 style="margin: 0 0 10px; font-size: 24px;">CMKS Travel</h1>
            <p style="margin: 0; opacity: 0.9;">Mise a jour de votre reservation {{ $booking->booking_reference }}</p>
        </div>

        <div style="padding: 28px; color: #111827;">
            <p>Bonjour {{ $booking->user->name }},</p>
            <p>Une modification concernant votre vol <strong>{{ $flight->flight_number }}</strong> a ete detectee.</p>

            <ul style="padding-left: 20px; line-height: 1.7;">
                <li>Itineraire: {{ $flight->departure_city }} ({{ $flight->departure_airport_code }}) -> {{ $flight->arrival_city }} ({{ $flight->arrival_airport_code }})</li>
                <li>Depart: {{ $flight->departure_at->format('d/m/Y H:i') }} - {{ $flight->departure_timezone }}</li>
                <li>Arrivee: {{ $flight->arrival_at->format('d/m/Y H:i') }} - {{ $flight->arrival_timezone }}</li>
                <li>Duree estimee: {{ $flight->duration_label }}</li>
                <li>Statut: {{ $flight->status_label }}</li>
                <li>Prix actuel: {{ number_format((float) $flight->current_price, 0, ',', ' ') }} {{ $flight->currency }}</li>
            </ul>

            @if(isset($changes['current_price']))
                <p><strong>Prix mis a jour:</strong> {{ number_format((float) $changes['current_price']['old'], 0, ',', ' ') }} {{ $flight->currency }} -> {{ number_format((float) $changes['current_price']['new'], 0, ',', ' ') }} {{ $flight->currency }}</p>
            @endif

            <p>Notre equipe reste mobilisee pour assurer un suivi clair, rapide et fiable de votre voyage.</p>
            <p style="margin-bottom: 0;">L'equipe CMKS Travel</p>
        </div>
    </div>
</div>
