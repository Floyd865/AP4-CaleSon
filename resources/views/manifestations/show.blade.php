<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $manifestation->nommanif }}</title>
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
            max-width: 900px;
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
            padding: 32px;
            border-radius: 4px;
            margin-bottom: 20px;
            border: 1px solid #e0e0e0;
        }
        
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 20px;
        }
        
        h1 {
            color: #1a1a1a;
            font-size: 32px;
            font-weight: 600;
            flex: 1;
            line-height: 1.2;
        }
        
        .badges {
            display: flex;
            flex-direction: column;
            gap: 8px;
            align-items: flex-end;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 3px;
            font-size: 13px;
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
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 16px;
        }
        
        .info-item {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #666;
            font-size: 15px;
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
        
        .section {
            background: white;
            padding: 24px 32px;
            border-radius: 4px;
            margin-bottom: 20px;
            border: 1px solid #e0e0e0;
        }
        
        .section h2 {
            color: #333;
            font-size: 18px;
            margin-bottom: 12px;
            font-weight: 600;
        }
        
        .section p {
            color: #666;
            line-height: 1.6;
            font-size: 15px;
        }

        .affiche-info {
            background: #f9f9f9;
            padding: 16px;
            border-radius: 3px;
            border-left: 3px solid #4a90e2;
        }

        .affiche-info p {
            margin-bottom: 8px;
        }

        .affiche-info p:last-child {
            margin-bottom: 0;
        }
        
        .places-banner {
            background: white;
            color: #1a1a1a;
            padding: 24px 32px;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: center;
            border: 1px solid #e0e0e0;
        }
        
        .places-banner h3 {
            font-size: 16px;
            margin-bottom: 8px;
            color: #666;
            font-weight: 500;
        }
        
        .places-banner .places-number {
            font-size: 40px;
            font-weight: 700;
            margin-bottom: 4px;
            color: #4caf50;
        }

        .places-banner.warning .places-number {
            color: #ff9800;
        }

        .places-banner.danger .places-number {
            color: #f44336;
        }

        .places-banner p {
            color: #666;
            font-size: 14px;
        }
        
        .alert {
            padding: 14px 16px;
            border-radius: 3px;
            margin-bottom: 16px;
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
        
        .btn {
            display: inline-block;
            padding: 12px 24px;
            border: none;
            border-radius: 3px;
            font-size: 15px;
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
        
        .btn-success {
            background: #4caf50;
            color: white;
        }
        
        .btn-success:hover {
            background: #388e3c;
        }

        .btn-warning {
            background: #ff9800;
            color: white;
        }

        .btn-warning:hover {
            background: #f57c00;
        }
        
        .btn-secondary {
            background: #f0f0f0;
            color: #333;
        }
        
        .btn-secondary:hover {
            background: #e0e0e0;
        }
        
        .btn-disabled {
            background: #e0e0e0;
            color: #999;
            cursor: not-allowed;
        }

        .login-prompt {
            text-align: center;
            padding: 20px 0;
        }

        .login-prompt p {
            margin-bottom: 16px;
            color: #666;
        }

        .login-prompt .btn {
            margin: 0 6px;
        }

        .avis-section {
            background: white;
            padding: 32px;
            border-radius: 4px;
            border: 1px solid #e0e0e0;
            margin-bottom: 20px;
        }

        .avis-header {
            margin-bottom: 24px;
        }

        .avis-header h3 {
            font-size: 20px;
            color: #333;
            margin-bottom: 16px;
            font-weight: 600;
        }

        .avis-summary {
            display: grid;
            grid-template-columns: 180px 1fr;
            gap: 32px;
            padding: 24px;
            background: #f9f9f9;
            border-radius: 3px;
        }

        .average-note {
            text-align: center;
        }

        .note-number {
            font-size: 48px;
            font-weight: 700;
            color: #333;
            line-height: 1;
            display: block;
            margin-bottom: 8px;
        }

        .stars {
            margin-bottom: 8px;
        }

        .star {
            color: #ffa726;
            font-size: 20px;
        }

        .star.filled {
            color: #ff9800;
        }

        .total-avis {
            font-size: 13px;
            color: #666;
        }

        .note-distribution {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .distribution-row {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .note-label {
            font-size: 13px;
            color: #666;
            width: 40px;
        }

        .progress-bar {
            flex: 1;
            height: 8px;
            background: #e0e0e0;
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: #ff9800;
            border-radius: 4px;
        }

        .note-count {
            font-size: 13px;
            color: #666;
            width: 30px;
            text-align: right;
        }

        .add-avis-section {
            margin: 20px 0;
        }

        .avis-list {
            margin-top: 24px;
        }

        .avis-card {
            padding: 20px;
            border: 1px solid #e0e0e0;
            border-radius: 3px;
            margin-bottom: 16px;
            background: #fafafa;
        }

        .avis-card:last-child {
            margin-bottom: 0;
        }

        .avis-header-card {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
        }

        .avis-user {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #4a90e2;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 16px;
        }

        .avis-user strong {
            display: block;
            color: #333;
            font-size: 15px;
        }

        .avis-date {
            font-size: 13px;
            color: #999;
        }

        .avis-note {
            display: flex;
            gap: 2px;
        }

        .avis-commentaire {
            color: #666;
            line-height: 1.6;
            font-size: 14px;
        }

        .avis-actions {
            margin-top: 12px;
            display: flex;
            gap: 12px;
        }

        .btn-edit, .btn-delete {
            padding: 6px 14px;
            border: none;
            border-radius: 3px;
            font-size: 13px;
            cursor: pointer;
            font-weight: 500;
        }

        .btn-edit {
            background: #f0f0f0;
            color: #333;
        }

        .btn-edit:hover {
            background: #e0e0e0;
        }

        .btn-delete {
            background: #ffebee;
            color: #d32f2f;
        }

        .btn-delete:hover {
            background: #ffcdd2;
        }

        .actions {
            display: flex;
            gap: 12px;
            justify-content: space-between;
            margin-top: 20px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            .header, .section, .avis-section {
                padding: 20px;
            }

            h1 {
                font-size: 24px;
            }

            .header-top {
                flex-direction: column;
            }

            .badges {
                align-items: flex-start;
                flex-direction: row;
            }

            .avis-summary {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('manifestations.index') }}" class="back-link">← Retour à la liste</a>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif
        
        <div class="header">
            <div class="header-top">
                <h1>{{ $manifestation->nommanif }}</h1>
                <div class="badges">
                    <span class="badge badge-type">{{ $manifestation->type_manifestation }}</span>
                    @if(is_null($manifestation->prixmanif))
                        <span class="badge badge-price free">Gratuit</span>
                    @else
                        <span class="badge badge-price paid">{{ number_format($manifestation->prixmanif, 2, ',', ' ') }} €</span>
                    @endif
                </div>
            </div>
            
            <div class="info-grid">
                <div class="info-item">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>{{ date('d/m/Y à H:i', strtotime($manifestation->dateheure)) }}</span>
                </div>

                <div class="info-item">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>{{ $manifestation->libellelieu }}</span>
                </div>

                <div class="info-item">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span>{{ $manifestation->libellecom }}</span>
                </div>
            </div>
        </div>
        
        @if($manifestation->resumemanif)
        <div class="section">
            <h2>Description</h2>
            <p>{{ $manifestation->resumemanif }}</p>
        </div>
        @endif
        
        @if($affiche)
        <div class="section">
            <h2>Affiche</h2>
            <div class="affiche-info">
                <p><strong>{{ $affiche->nomAffiche }}</strong></p>
                @if(isset($affiche->description))
                    <p style="margin-top: 8px; color: #666;">{{ $affiche->description }}</p>
                @endif
            </div>
        </div>
        @endif
        
        @php
            $placesClass = '';
            if ($placesRestantes == 0) {
                $placesClass = 'danger';
            } elseif ($placesRestantes < $manifestation->effectif_complet * 0.2) {
                $placesClass = 'warning';
            }
        @endphp
        
        <div class="places-banner {{ $placesClass }}">
            <h3>Places disponibles</h3>
            <div class="places-number">{{ $placesRestantes }}</div>
            <p>sur {{ $manifestation->effectif_complet }} places au total</p>
        </div>
        
        <div class="section">
            <h2>Réservation</h2>
            
            @auth
                @if($placesRestantes > 0)
                    @if(is_null($manifestation->prixmanif) || $manifestation->prixmanif == 0)
                        <div class="alert alert-info">
                            Cette manifestation est gratuite. Vous pouvez réserver jusqu'à 4 places.
                        </div>
                        @if($type === 'atelier' && isset($date))
                            <a href="{{ route('reservations.create.atelier', ['id' => $manifestation->idmanif, 'date' => $date]) }}" class="btn btn-success">
                                Réserver gratuitement
                            </a>
                        @else
                            <a href="{{ route('reservations.create', ['type' => $type, 'id' => $manifestation->idmanif]) }}" class="btn btn-success">
                                Réserver gratuitement
                            </a>
                        @endif
                    @else
                        <div class="alert alert-warning">
                            Cette manifestation est payante ({{ number_format($manifestation->prixmanif, 2, ',', ' ') }} € par place).
                        </div>
                        @if($type === 'atelier' && isset($date))
                            <a href="{{ route('reservations.create.payant.atelier', ['type' => $type, 'id' => $manifestation->idmanif]) }}" class="btn btn-warning">
                                Réserver avec paiement
                            </a>
                        @else
                            <a href="{{ route('reservations.create.payant', ['type' => $type, 'id' => $manifestation->idmanif]) }}" class="btn btn-warning">
                                Réserver avec paiement
                            </a>
                        @endif
                    @endif
                @else
                    <div class="alert alert-error">
                        Il n'y a plus de places disponibles pour cette manifestation.
                    </div>
                    <button class="btn btn-disabled" disabled>Complet</button>
                @endif
            @else
                <div class="login-prompt">
                    <p>Vous devez être connecté pour réserver cette manifestation.</p>
                    <div>
                        <a href="{{ route('login') }}" class="btn btn-primary">Se connecter</a>
                        <a href="{{ route('register') }}" class="btn btn-secondary">Créer un compte</a>
                    </div>
                </div>
            @endauth
        </div>
        
        <div class="avis-section">
            <div class="avis-header">
                <h3>Avis des spectateurs</h3>
                @if($totalAvis > 0)
                    <div class="avis-summary">
                        <div class="average-note">
                            <span class="note-number">{{ number_format($averageNote, 1) }}</span>
                            <div class="stars">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($averageNote))
                                        <span class="star filled">★</span>
                                    @elseif($i - 0.5 <= $averageNote)
                                        <span class="star half">★</span>
                                    @else
                                        <span class="star">☆</span>
                                    @endif
                                @endfor
                            </div>
                            <span class="total-avis">{{ $totalAvis }} avis</span>
                        </div>
                        
                        <div class="note-distribution">
                            @for($i = 5; $i >= 1; $i--)
                                @php
                                    $count = $noteDistribution[$i] ?? 0;
                                    $percentage = $totalAvis > 0 ? ($count / $totalAvis) * 100 : 0;
                                @endphp
                                <div class="distribution-row">
                                    <span class="note-label">{{ $i }} ★</span>
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <span class="note-count">{{ $count }}</span>
                                </div>
                            @endfor
                        </div>
                    </div>
                @else
                    <p style="color: #666; text-align: center; padding: 20px; background: #f9f9f9; border-radius: 3px;">
                        Aucun avis pour le moment. Soyez le premier à donner votre avis !
                    </p>
                @endif
            </div>
            
            @auth
                @if($userHasReservation && !$userHasReviewed)
                    <div class="add-avis-section">
                        @if($type === 'atelier' && isset($date))
                            <a href="{{ route('avis.create.atelier', ['id' => $manifestation->idmanif, 'date' => $date]) }}" class="btn btn-primary">
                                Donner mon avis
                            </a>
                        @else
                            <a href="{{ route('avis.create', ['type' => $type, 'id' => $manifestation->idmanif]) }}" class="btn btn-primary">
                                Donner mon avis
                            </a>
                        @endif
                    </div>
                @elseif(!$userHasReservation)
                    <div class="alert alert-info">
                        Vous devez avoir réservé cette manifestation pour pouvoir donner un avis.
                    </div>
                @endif
            @endauth
            
            @if($avis->count() > 0)
                <div class="avis-list">
                    @foreach($avis as $singleAvis)
                        <div class="avis-card">
                            <div class="avis-header-card">
                                <div class="avis-user">
                                    <div class="user-avatar">{{ substr($singleAvis->user_name, 0, 1) }}</div>
                                    <div>
                                        <strong>{{ $singleAvis->user_name }}</strong>
                                        <div class="avis-date">{{ date('d/m/Y', strtotime($singleAvis->dateavis)) }}</div>
                                    </div>
                                </div>
                                <div class="avis-note">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $singleAvis->note)
                                            <span class="star filled">★</span>
                                        @else
                                            <span class="star">☆</span>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                            @if($singleAvis->commentaire)
                                <p class="avis-commentaire">{{ $singleAvis->commentaire }}</p>
                            @endif
                            
                            @if(auth()->check() && auth()->id() == $singleAvis->idspec)
                                <div class="avis-actions">
                                    <a href="{{ route('avis.edit', $singleAvis->idavis) }}" class="btn-edit">Modifier</a>
                                    <form action="{{ route('avis.destroy', $singleAvis->idavis) }}" method="POST" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet avis ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete">Supprimer</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        
        <div class="actions">
            <a href="{{ route('manifestations.index') }}" class="btn btn-secondary">
                Retour à la liste
            </a>
            
            @auth
                <a href="{{ route('reservations.index') }}" class="btn btn-primary">
                    Voir mes réservations
                </a>
            @endauth
        </div>
    </div>
</body>
</html>