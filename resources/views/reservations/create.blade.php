<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Réserver - {{ $manifestation->nommanif }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            color: white;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .header p {
            font-size: 1.1em;
            opacity: 0.9;
        }

        .card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            margin-bottom: 20px;
        }

        .manifestation-info {
            border-left: 4px solid #667eea;
            padding-left: 20px;
            margin-bottom: 30px;
        }

        .manifestation-info h2 {
            color: #333;
            margin-bottom: 15px;
            font-size: 1.8em;
        }

        .info-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
            color: #666;
        }

        .info-row svg {
            width: 20px;
            height: 20px;
            color: #667eea;
        }

        .info-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin-top: 10px;
        }

        .badge-gratuit {
            background: #d1fae5;
            color: #065f46;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border-left: 4px solid #10b981;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }

        .alert-warning {
            background: #fef3c7;
            color: #92400e;
            border-left: 4px solid #f59e0b;
        }

        .alert-info {
            background: #dbeafe;
            color: #1e40af;
            border-left: 4px solid #3b82f6;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            font-size: 1.05em;
        }

        .form-group input[type="number"],
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s;
        }

        .form-group input[type="number"]:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-hint {
            font-size: 14px;
            color: #6b7280;
            margin-top: 6px;
            display: block;
        }

        .places-selector {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-top: 15px;
        }

        .place-option {
            position: relative;
        }

        .place-option input[type="radio"] {
            position: absolute;
            opacity: 0;
        }

        .place-option label {
            display: block;
            padding: 15px;
            background: #f9fafb;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 600;
        }

        .place-option input[type="radio"]:checked + label {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        .place-option input[type="radio"]:disabled + label {
            background: #f3f4f6;
            color: #9ca3af;
            cursor: not-allowed;
            opacity: 0.5;
        }

        .summary {
            background: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .summary h3 {
            color: #333;
            margin-bottom: 15px;
            font-size: 1.3em;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .summary-row:last-child {
            border-bottom: none;
            font-weight: 600;
            font-size: 1.1em;
            padding-top: 15px;
        }

        .summary-row .label {
            color: #666;
        }

        .summary-row .value {
            color: #333;
            font-weight: 600;
        }

        .buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            flex: 1;
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            text-align: center;
            display: inline-block;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover:not(:disabled) {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:disabled {
            background: #9ca3af;
            cursor: not-allowed;
        }

        .btn-secondary {
            background: #e5e7eb;
            color: #374151;
        }

        .btn-secondary:hover {
            background: #d1d5db;
        }

        .icon {
            width: 20px;
            height: 20px;
            display: inline-block;
            vertical-align: middle;
            margin-right: 5px;
        }

        @media (max-width: 768px) {
            .places-selector {
                grid-template-columns: repeat(2, 1fr);
            }

            .buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎫 Réservation</h1>
            <p>Réservez vos places gratuitement</p>
        </div>

        <!-- Messages de succès/erreur -->
        @if(session('success'))
            <div class="alert alert-success">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 24px; height: 24px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 24px; height: 24px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 24px; height: 24px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Informations sur la manifestation -->
        <div class="card">
            <div class="manifestation-info">
                <h2>{{ $manifestation->nommanif }}</h2>
                
                <div class="info-row">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    <span><strong>Type :</strong> {{ $manifestation->type_manifestation }}</span>
                </div>

                <div class="info-row">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span><strong>Date :</strong> {{ date('d/m/Y à H:i', strtotime($manifestation->dateheure)) }}</span>
                </div>

                <div class="info-row">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span><strong>Lieu :</strong> {{ $manifestation->libellelieu }}</span>
                </div>

                <div class="info-row">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span><strong>Places restantes :</strong> {{ $placesRestantes }} / {{ $manifestation->effectif_complet }}</span>
                </div>

                <span class="info-badge badge-gratuit">✓ Gratuit</span>
            </div>

            @if($placesDejaReservees > 0)
                <div class="alert alert-info">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 24px; height: 24px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Vous avez déjà réservé <strong>{{ $placesDejaReservees }}</strong> place(s) pour cette manifestation.
                </div>
            @endif

            @if($maxPlacesDisponibles == 0)
                <div class="alert alert-warning">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 24px; height: 24px;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    Vous avez atteint la limite de 4 places par personne ou il n'y a plus de places disponibles.
                </div>
            @else
                <!-- Formulaire de réservation -->
                @if(isset($type) && $type === 'atelier' && isset($date))
                    <form method="POST" action="{{ route('reservations.store.atelier', ['id' => $manifestation->idmanif, 'date' => $date]) }}" id="reservationForm">
                @else
                    <form method="POST" action="{{ route('reservations.store', ['type' => $type, 'id' => $manifestation->idmanif]) }}" id="reservationForm">
                @endif
                    @csrf

                    <div class="form-group">
                        <label for="nombre_places">Nombre de places à réserver</label>
                        <span class="form-hint">Maximum {{ $maxPlacesDisponibles }} place(s) disponible(s) pour vous</span>
                        
                        <div class="places-selector">
                            @for($i = 1; $i <= min(4, $maxPlacesDisponibles); $i++)
                                <div class="place-option">
                                    <input 
                                        type="radio" 
                                        name="nombre_places" 
                                        id="places_{{ $i }}" 
                                        value="{{ $i }}"
                                        {{ old('nombre_places', 1) == $i ? 'checked' : '' }}
                                        {{ $i > $maxPlacesDisponibles ? 'disabled' : '' }}
                                    >
                                    <label for="places_{{ $i }}">
                                        {{ $i }} place{{ $i > 1 ? 's' : '' }}
                                    </label>
                                </div>
                            @endfor
                        </div>
                    </div>

                    <!-- Résumé de la réservation -->
                    <div class="summary">
                        <h3>📋 Résumé de votre réservation</h3>
                        <div class="summary-row">
                            <span class="label">Manifestation :</span>
                            <span class="value">{{ $manifestation->nommanif }}</span>
                        </div>
                        <div class="summary-row">
                            <span class="label">Date :</span>
                            <span class="value">{{ date('d/m/Y à H:i', strtotime($manifestation->dateheure)) }}</span>
                        </div>
                        <div class="summary-row">
                            <span class="label">Nombre de places :</span>
                            <span class="value" id="summary_places">1 place</span>
                        </div>
                        <div class="summary-row">
                            <span class="label">Prix total :</span>
                            <span class="value" style="color: #10b981;">GRATUIT</span>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 24px; height: 24px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Vous recevrez un email de confirmation avec vos billets (QR Code) après la réservation.
                    </div>

                    <div class="buttons">
                        @if(isset($type) && $type === 'atelier' && isset($date))
                            <a href="{{ route('manifestations.show.atelier', ['id' => $manifestation->idmanif, 'date' => $date]) }}" class="btn btn-secondary">
                                ← Annuler
                            </a>
                        @else
                            <a href="{{ route('manifestations.show', ['type' => $type, 'id' => $manifestation->idmanif]) }}" class="btn btn-secondary">
                                ← Annuler
                            </a>
                        @endif
                        <button type="submit" class="btn btn-primary">
                            ✓ Confirmer la réservation
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>

    <script>
        // Mise à jour dynamique du résumé
        document.querySelectorAll('input[name="nombre_places"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const nbPlaces = this.value;
                const summaryPlaces = document.getElementById('summary_places');
                summaryPlaces.textContent = nbPlaces + (nbPlaces > 1 ? ' places' : ' place');
            });
        });
        });

        // Validation du formulaire
        document.getElementById('reservationForm')?.addEventListener('submit', function(e) {
            const selectedPlaces = document.querySelector('input[name="nombre_places"]:checked');
            if (!selectedPlaces) {
                e.preventDefault();
                alert('Veuillez sélectionner un nombre de places');
            }
        });
    </script>
</body>
</html>