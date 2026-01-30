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

        .nav-buttons {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
            justify-content: center;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-secondary {
            background: white;
            color: #667eea;
        }

        .btn-secondary:hover {
            background: #f3f4f6;
            transform: translateY(-2px);
        }

        .reservations-list {
            display: grid;
            gap: 20px;
        }

        .reservation-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 20px;
            align-items: center;
            transition: all 0.3s;
        }

        .reservation-card.payante {
            border-left: 4px solid #f59e0b;
        }

        .reservation-card.gratuite {
            border-left: 4px solid #10b981;
        }

        .reservation-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.15);
        }

        .reservation-info {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .reservation-header {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .reservation-title {
            font-size: 1.5em;
            color: #333;
            font-weight: 600;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-type {
            background: #e0e7ff;
            color: #4338ca;
        }

        .badge-gratuit {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-payant {
            background: #fed7aa;
            color: #9a3412;
        }

        .info-row {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #666;
            font-size: 0.95em;
        }

        .info-row svg {
            width: 18px;
            height: 18px;
            color: #667eea;
            flex-shrink: 0;
        }

        .payment-info {
            background: #fef3c7;
            padding: 12px 15px;
            border-radius: 6px;
            margin-top: 8px;
            font-size: 0.9em;
        }

        .payment-info strong {
            color: #92400e;
        }

        .ticket-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            padding: 20px;
            background: #f9fafb;
            border-radius: 8px;
            min-width: 200px;
        }

        .ticket-number {
            font-family: 'Courier New', monospace;
            font-size: 13px;
            color: #666;
            background: white;
            padding: 8px 12px;
            border-radius: 4px;
            border: 1px dashed #ddd;
        }

        .qr-placeholder {
            width: 120px;
            height: 120px;
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            font-size: 12px;
            text-align: center;
            padding: 10px;
        }

        .no-reservations {
            background: white;
            padding: 60px 40px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .no-reservations svg {
            width: 80px;
            height: 80px;
            color: #d1d5db;
            margin-bottom: 20px;
        }

        .no-reservations h2 {
            color: #374151;
            margin-bottom: 10px;
            font-size: 1.8em;
        }

        .no-reservations p {
            color: #6b7280;
            margin-bottom: 25px;
        }

        .btn-primary {
            background: #667eea;
            color: white;
            padding: 14px 28px;
            font-size: 16px;
        }

        .btn-primary:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .status-upcoming {
            color: #10b981;
            font-weight: 600;
        }

        .status-past {
            color: #6b7280;
        }

        .success-message {
            background: #d1fae5;
            color: #065f46;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #6ee7b7;
            text-align: center;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .reservation-card {
                grid-template-columns: 1fr;
            }

            .ticket-section {
                width: 100%;
            }

            .header h1 {
                font-size: 2em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎫 Mes Réservations</h1>
            <p>Toutes vos réservations pour le festival</p>
        </div>

        <div class="nav-buttons">
            <a href="{{ route('manifestations.index') }}" class="btn btn-secondary">
                ← Retour aux manifestations
            </a>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                🏠 Tableau de bord
            </a>
        </div>

        @if(session('success'))
            <div class="success-message">
                ✓ {{ session('success') }}
            </div>
        @endif

        @if($reservations->count() > 0)
            <div class="reservations-list">
                @php
                    $groupedReservations = $reservations->groupBy('idmanif');
                @endphp

                @foreach($groupedReservations as $idmanif => $billets)
                    @php
                        $manifestation = $billets->first();
                        $isPast = strtotime($manifestation->dateheure) < time();
                        $isPayante = !is_null($manifestation->prix_paye);
                    @endphp

                    <div class="reservation-card {{ $isPayante ? 'payante' : 'gratuite' }}">
                        <div class="reservation-info">
                            <div class="reservation-header">
                                <h2 class="reservation-title">{{ $manifestation->nommanif }}</h2>
                                <span class="badge badge-type">{{ $manifestation->type_manifestation }}</span>
                                @if($isPayante)
                                    <span class="badge badge-payant">💳 Payant</span>
                                @else
                                    <span class="badge badge-gratuit">✓ Gratuit</span>
                                @endif
                            </div>

                            <div class="info-row {{ $isPast ? 'status-past' : 'status-upcoming' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>
                                    {{ date('d/m/Y à H:i', strtotime($manifestation->dateheure)) }}
                                    @if($isPast)
                                        <strong>(Passée)</strong>
                                    @else
                                        <strong>(À venir)</strong>
                                    @endif
                                </span>
                            </div>

                            <div class="info-row">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>{{ $manifestation->libellelieu }}</span>
                            </div>

                            <div class="info-row">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                </svg>
                                <span><strong>{{ $billets->count() }}</strong> place(s) réservée(s)</span>
                            </div>

                            @if($isPayante)
                                <div class="payment-info">
                                    <div>
                                        <strong>Montant payé :</strong> 
                                        {{ number_format($manifestation->montant_paye ?? ($billets->count() * $manifestation->prix_paye), 2, ',', ' ') }} €
                                    </div>
                                    @if($manifestation->reference_transaction)
                                        <div style="margin-top: 5px; font-size: 0.85em;">
                                            <strong>Référence :</strong> {{ $manifestation->reference_transaction }}
                                        </div>
                                    @endif
                                    @if($manifestation->date_transaction)
                                        <div style="margin-top: 5px; font-size: 0.85em;">
                                            <strong>Payé le :</strong> 
                                            {{ date('d/m/Y à H:i', strtotime($manifestation->date_transaction)) }}
                                        </div>
                                    @endif
                                </div>
                            @endif

                            @if($manifestation->resumemanif)
                                <div class="info-row">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>{{ Str::limit($manifestation->resumemanif, 100) }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="ticket-section">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 40px; height: 40px; color: #667eea;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                            <div style="text-align: center;">
                                <div style="font-weight: 600; color: #333; margin-bottom: 8px;">
                                    Billet{{ $billets->count() > 1 ? 's' : '' }}
                                </div>
                                @foreach($billets->take(3) as $billet)
                                    <div class="ticket-number">{{ $billet->numticket }}</div>
                                @endforeach
                                @if($billets->count() > 3)
                                    <div style="color: #666; font-size: 12px; margin-top: 5px;">
                                        + {{ $billets->count() - 3 }} autre(s)
                                    </div>
                                @endif
                            </div>
                            <div class="qr-placeholder">
                                QR Code envoyé par email
                            </div>
                            <small style="color: #6b7280; text-align: center;">
                                Présentez ce billet à l'entrée
                            </small>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="no-reservations">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                </svg>
                <h2>Aucune réservation</h2>
                <p>Vous n'avez pas encore réservé de place pour le festival.</p>
                <a href="{{ route('manifestations.index') }}" class="btn btn-primary">
                    🎭 Découvrir les manifestations
                </a>
            </div>
        @endif
    </div>
</body>
</html>