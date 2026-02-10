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
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: #f5f5f5;
            min-height: 100vh;
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 16px;
            color: #4a90e2;
            text-decoration: none;
            font-size: 14px;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .header {
            background: white;
            padding: 28px 32px;
            border-radius: 4px;
            margin-bottom: 20px;
            border: 1px solid #e0e0e0;
        }

        h1 {
            color: #1a1a1a;
            font-size: 28px;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .subtitle {
            color: #666;
            font-size: 15px;
        }

        .card {
            background: white;
            border-radius: 4px;
            padding: 28px 32px;
            margin-bottom: 20px;
            border: 1px solid #e0e0e0;
        }

        .manifestation-info {
            border-left: 3px solid #4a90e2;
            padding-left: 20px;
            margin-bottom: 28px;
        }

        .manifestation-info h2 {
            color: #333;
            margin-bottom: 16px;
            font-size: 22px;
            font-weight: 600;
        }

        .info-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
            color: #666;
            font-size: 14px;
        }

        .info-row svg {
            width: 18px;
            height: 18px;
            color: #999;
            flex-shrink: 0;
        }

        .info-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 3px;
            font-size: 13px;
            font-weight: 500;
            margin-top: 12px;
            background: #e8f5e9;
            color: #2e7d32;
        }

        .alert {
            padding: 14px 16px;
            border-radius: 3px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            border-left: 3px solid;
        }

        .alert-success {
            background: #e8f5e9;
            border-color: #4caf50;
            color: #2e7d32;
        }

        .alert-error {
            background: #ffebee;
            border-color: #f44336;
            color: #c62828;
        }

        .alert-warning {
            background: #fff3e0;
            border-color: #ff9800;
            color: #e65100;
        }

        .alert-info {
            background: #e3f2fd;
            border-color: #2196f3;
            color: #1565c0;
        }

        .alert svg {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            font-weight: 500;
            color: #333;
            margin-bottom: 8px;
            font-size: 15px;
        }

        .form-hint {
            font-size: 13px;
            color: #666;
            margin-top: 6px;
            display: block;
        }

        .places-selector {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-top: 12px;
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
            padding: 16px;
            background: #f9f9f9;
            border: 2px solid #e0e0e0;
            border-radius: 3px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            font-weight: 500;
        }

        .place-option input[type="radio"]:checked + label {
            background: #4a90e2;
            color: white;
            border-color: #4a90e2;
        }

        .place-option input[type="radio"]:disabled + label {
            background: #f5f5f5;
            color: #bbb;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .summary {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 3px;
            margin-bottom: 20px;
        }

        .summary h3 {
            margin-bottom: 16px;
            color: #333;
            font-size: 16px;
            font-weight: 600;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .summary-row:last-child {
            margin-bottom: 0;
        }

        .summary-row .label {
            color: #666;
        }

        .summary-row .value {
            font-weight: 500;
            color: #333;
        }

        .buttons {
            display: flex;
            gap: 12px;
            margin-top: 24px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 500;
            transition: background-color 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: #4a90e2;
            color: white;
        }

        .btn-primary:hover {
            background: #357abd;
        }

        .btn-secondary {
            background: #f0f0f0;
            color: #333;
        }

        .btn-secondary:hover {
            background: #e0e0e0;
        }

        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            .header, .card {
                padding: 20px;
            }

            h1 {
                font-size: 24px;
            }

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
        <a href="javascript:history.back()" class="back-link">← Retour</a>

        @if(session('success'))
            <div class="alert alert-success">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="header">
            <h1>Réservation</h1>
            <p class="subtitle">Réservez vos places gratuitement</p>
        </div>

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

                <span class="info-badge">Gratuit</span>
            </div>

            @if($placesDejaReservees > 0)
                <div class="alert alert-info">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Vous avez déjà réservé <strong>{{ $placesDejaReservees }}</strong> place(s) pour cette manifestation.
                </div>
            @endif

            @if($maxPlacesDisponibles == 0)
                <div class="alert alert-warning">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    Vous avez atteint la limite de 4 places par personne ou il n'y a plus de places disponibles.
                </div>
            @else
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

                    <div class="summary">
                        <h3>Résumé de votre réservation</h3>
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
                            <span class="value" style="color: #2e7d32;">GRATUIT</span>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Vous recevrez un email de confirmation avec vos billets (QR Code) après la réservation.
                    </div>

                    <div class="buttons">
                        @if(isset($type) && $type === 'atelier' && isset($date))
                            <a href="{{ route('manifestations.show.atelier', ['id' => $manifestation->idmanif, 'date' => $date]) }}" class="btn btn-secondary">
                                Annuler
                            </a>
                        @else
                            <a href="{{ route('manifestations.show', ['type' => $type, 'id' => $manifestation->idmanif]) }}" class="btn btn-secondary">
                                Annuler
                            </a>
                        @endif
                        <button type="submit" class="btn btn-primary">
                            Confirmer la réservation
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>

    <script>
        document.querySelectorAll('input[name="nombre_places"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const nbPlaces = this.value;
                const summaryPlaces = document.getElementById('summary_places');
                summaryPlaces.textContent = nbPlaces + (nbPlaces > 1 ? ' places' : ' place');
            });
        });

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