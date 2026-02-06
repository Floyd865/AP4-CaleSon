<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Réservations</title>
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
            max-width: 1200px;
            margin: 0 auto;
        }

        h1 {
            color: white;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5em;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .actions-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-primary {
            background: white;
            color: #667eea;
        }

        .btn-primary:hover {
            background: #f0f0f0;
            transform: translateY(-2px);
        }

        .reservation-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .reservation-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.2);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .card-title {
            font-size: 1.8em;
            color: #333;
            margin-bottom: 5px;
        }

        .badges {
            display: flex;
            flex-direction: column;
            gap: 8px;
            align-items: flex-end;
        }

        .badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-type {
            background: #667eea;
            color: white;
        }

        .badge-price {
            background: #f59e0b;
            color: white;
        }

        .badge-price.free {
            background: #10b981;
        }

        .badge-tickets {
            background: #8b5cf6;
            color: white;
        }

        .card-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #666;
        }

        .info-item svg {
            width: 18px;
            height: 18px;
            color: #667eea;
        }

        .info-item strong {
            color: #333;
        }

        .billets-section {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
        }

        .billets-section h4 {
            color: #667eea;
            margin-bottom: 10px;
            font-size: 14px;
            text-transform: uppercase;
        }

        .billet-item {
            background: white;
            padding: 10px 15px;
            margin-bottom: 8px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-left: 3px solid #667eea;
        }

        .billet-item:last-child {
            margin-bottom: 0;
        }

        .qr-code {
            font-family: monospace;
            font-size: 11px;
            color: #666;
            background: #f0f0f0;
            padding: 4px 8px;
            border-radius: 3px;
        }

        .no-reservations {
            background: white;
            padding: 60px 40px;
            border-radius: 10px;
            text-align: center;
        }

        .no-reservations svg {
            width: 80px;
            height: 80px;
            color: #d1d5db;
            margin-bottom: 20px;
        }

        .no-reservations h2 {
            color: #333;
            margin-bottom: 10px;
        }

        .no-reservations p {
            color: #666;
            margin-bottom: 25px;
        }

        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #d1fae5;
            border-left: 4px solid #10b981;
            color: #065f46;
        }

        .alert-info {
            background: #dbeafe;
            border-left: 4px solid #3b82f6;
            color: #1e40af;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="actions-header">
            <h1>🎫 Mes Réservations</h1>
            <a href="{{ route('manifestations.index') }}" class="btn btn-primary">
                ← Retour aux manifestations
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($reservations->count() > 0)
            <div class="alert alert-info">
                ℹ️ Vous avez {{ $reservations->count() }} réservation(s) en cours
            </div>

            @foreach($reservations as $nomManif => $reservation)
                <div class="reservation-card">
                    <div class="card-header">
                        <div>
                            <h2 class="card-title">{{ $nomManif }}</h2>
                        </div>
                        <div class="badges">
                            <span class="badge badge-type">{{ $reservation['manifestation']->type_manifestation }}</span>
                            @if(is_null($reservation['manifestation']->prixmanif))
                                <span class="badge badge-price free">Gratuit</span>
                            @else
                                <span class="badge badge-price">{{ number_format($reservation['manifestation']->prixmanif, 2, ',', ' ') }} €</span>
                            @endif
                            <span class="badge badge-tickets">{{ $reservation['nombre_billets'] }} billet(s)</span>
                        </div>
                    </div>

                    <div class="card-info">
                        <div class="info-item">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>
                                <strong>Date :</strong>
                                {{ date('d/m/Y à H:i', strtotime($reservation['manifestation']->dateheure)) }}
                            </span>
                        </div>

                        <div class="info-item">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>
                                <strong>Lieu :</strong>
                                {{ $reservation['manifestation']->libellelieu }}
                            </span>
                        </div>
                    </div>

                    <div class="billets-section">
                        <h4>📋 Vos billets</h4>
                        @foreach($reservation['billets'] as $billet)
                            <div class="billet-item">
                                <div>
                                    <strong>Billet #{{ $loop->iteration }}</strong>
                                    <br>
                                    <small style="color: #666;">Réservé le {{ date('d/m/Y', strtotime($billet->datereserv)) }}</small>
                                </div>
                                <span class="qr-code">QR: {{ substr($billet->qr_code, 0, 20) }}...</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

        @else
            <div class="no-reservations">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                </svg>
                <h2>Aucune réservation</h2>
                <p>Vous n'avez pas encore réservé de places pour les manifestations.</p>
                <a href="{{ route('manifestations.index') }}" class="btn btn-primary" style="background: #667eea; color: white;">
                    Découvrir les manifestations
                </a>
            </div>
        @endif
    </div>
</body>
</html>