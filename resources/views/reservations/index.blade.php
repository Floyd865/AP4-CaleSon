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
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: #f5f5f5;
            min-height: 100vh;
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            padding: 24px 32px;
            margin-bottom: 24px;
            border-radius: 4px;
            border: 1px solid #e0e0e0;
        }

        h1 {
            color: #1a1a1a;
            font-size: 28px;
            font-weight: 600;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            border: none;
            border-radius: 3px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn-primary {
            background: #4a90e2;
            color: white;
        }

        .btn-primary:hover {
            background: #357abd;
        }

        .alert {
            padding: 14px 16px;
            border-radius: 3px;
            margin-bottom: 20px;
            font-size: 14px;
            border-left: 3px solid;
        }

        .alert-success {
            background: #e8f5e9;
            border-color: #4caf50;
            color: #2e7d32;
        }

        .alert-info {
            background: #e3f2fd;
            border-color: #2196f3;
            color: #1565c0;
        }

        .reservation-card {
            background: white;
            border-radius: 4px;
            padding: 28px;
            margin-bottom: 20px;
            border: 1px solid #e0e0e0;
            transition: box-shadow 0.2s;
        }

        .reservation-card:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid #f0f0f0;
        }

        .card-title {
            font-size: 22px;
            color: #1a1a1a;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .badges {
            display: flex;
            flex-direction: column;
            gap: 8px;
            align-items: flex-end;
        }

        .badge {
            padding: 5px 12px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-type {
            background: #f0f0f0;
            color: #555;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .badge-price {
            font-weight: 600;
        }

        .badge-price.free {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .badge-price.paid {
            background: #fff3e0;
            color: #e65100;
        }

        .badge-tickets {
            background: #e3f2fd;
            color: #1565c0;
        }

        .card-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 16px;
            margin-bottom: 20px;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #666;
            font-size: 14px;
        }

        .info-item svg {
            width: 18px;
            height: 18px;
            color: #999;
            flex-shrink: 0;
        }

        .info-item strong {
            color: #333;
        }

        .billets-section {
            background: #f9f9f9;
            padding: 16px;
            border-radius: 3px;
            margin-top: 16px;
        }

        .billets-section h4 {
            color: #333;
            margin-bottom: 12px;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .billet-item {
            background: white;
            padding: 12px 16px;
            margin-bottom: 8px;
            border-radius: 3px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-left: 3px solid #4a90e2;
        }

        .billet-item:last-child {
            margin-bottom: 0;
        }

        .billet-item strong {
            color: #333;
            font-size: 14px;
        }

        .billet-item small {
            color: #999;
            font-size: 13px;
        }

        .qr-code {
            font-family: 'Courier New', monospace;
            font-size: 11px;
            color: #666;
            background: #f0f0f0;
            padding: 4px 8px;
            border-radius: 3px;
        }

        .no-reservations {
            background: white;
            padding: 60px 40px;
            border-radius: 4px;
            text-align: center;
            border: 1px solid #e0e0e0;
        }

        .no-reservations svg {
            width: 64px;
            height: 64px;
            color: #ccc;
            margin-bottom: 20px;
        }

        .no-reservations h2 {
            color: #333;
            margin-bottom: 10px;
            font-size: 20px;
            font-weight: 600;
        }

        .no-reservations p {
            color: #666;
            margin-bottom: 24px;
        }

        @media (max-width: 768px) {
            header {
                flex-direction: column;
                gap: 16px;
                align-items: flex-start;
            }

            h1 {
                font-size: 24px;
            }

            .card-header {
                flex-direction: column;
                gap: 12px;
            }

            .badges {
                align-items: flex-start;
                flex-direction: row;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Mes Réservations</h1>
            <a href="{{ route('manifestations.index') }}" class="btn btn-primary">
                Retour aux manifestations
            </a>
        </header>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($reservations->count() > 0)
            <div class="alert alert-info">
                Vous avez {{ $reservations->count() }} réservation(s) en cours
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
                                <span class="badge badge-price paid">{{ number_format($reservation['manifestation']->prixmanif, 2, ',', ' ') }} €</span>
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
                        <h4>Vos billets</h4>
                        @foreach($reservation['billets'] as $billet)
                            <div class="billet-item">
                                <div>
                                    <strong>Billet #{{ $loop->iteration }}</strong>
                                    <br>
                                    <small>Réservé le {{ date('d/m/Y', strtotime($billet->datereserv)) }}</small>
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
                <a href="{{ route('manifestations.index') }}" class="btn btn-primary">
                    Découvrir les manifestations
                </a>
            </div>
        @endif
    </div>
</body>
</html>