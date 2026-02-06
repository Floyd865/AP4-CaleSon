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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 900px;
            margin: 0 auto;
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #667eea;
        }
        
        h1 {
            color: #333;
            font-size: 2em;
            flex: 1;
        }
        
        .badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin-left: 15px;
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
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .info-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }
        
        .info-card h3 {
            color: #667eea;
            font-size: 14px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        
        .info-card p {
            color: #333;
            font-size: 16px;
            font-weight: 600;
        }
        
        .description {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        
        .description h3 {
            color: #667eea;
            margin-bottom: 10px;
        }
        
        .description p {
            color: #666;
            line-height: 1.8;
        }
        
        .affiche-section {
            margin-bottom: 30px;
        }
        
        .affiche-section h3 {
            color: #667eea;
            margin-bottom: 10px;
        }
        
        .places-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 8px;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .places-info h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }
        
        .places-info .places-number {
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .places-info.warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }
        
        .places-info.danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }
        
        .reservation-section {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        
        .reservation-section h3 {
            color: #667eea;
            margin-bottom: 20px;
            font-size: 20px;
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
        
        .alert-error {
            background: #fee2e2;
            border-left: 4px solid #ef4444;
            color: #991b1b;
        }
        
        .alert-warning {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            color: #92400e;
        }
        
        .alert-info {
            background: #dbeafe;
            border-left: 4px solid #3b82f6;
            color: #1e40af;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
        }
        
        .btn-primary {
            background: #667eea;
            color: white;
        }
        
        .btn-primary:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-success {
            background: #10b981;
            color: white;
        }
        
        .btn-success:hover {
            background: #059669;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(16, 185, 129, 0.4);
        }
        
        .btn-warning {
            background: #f59e0b;
            color: white;
        }
        
        .btn-warning:hover {
            background: #d97706;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(245, 158, 11, 0.4);
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
        }
        
        .btn-disabled {
            background: #e5e7eb;
            color: #9ca3af;
            cursor: not-allowed;
        }
        
        .btn-disabled:hover {
            background: #e5e7eb;
            transform: none;
            box-shadow: none;
        }
        
        .actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        
        .login-prompt {
            background: #fef3c7;
            border: 2px solid #f59e0b;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }
        
        .login-prompt p {
            color: #92400e;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Messages flash -->
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
        
        <!-- En-tête -->
        <div class="header">
            <h1>{{ $manifestation->nommanif }}</h1>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <span class="badge badge-type">{{ $manifestation->type_manifestation }}</span>
                @if(is_null($manifestation->prixmanif))
                    <span class="badge badge-price free">Gratuit</span>
                @else
                    <span class="badge badge-price">{{ number_format($manifestation->prixmanif, 2, ',', ' ') }} €</span>
                @endif
            </div>
        </div>
        
        <!-- Informations principales -->
        <div class="info-grid">
            <div class="info-card">
                <h3>📅 Date et heure</h3>
                <p>{{ date('d/m/Y à H:i', strtotime($manifestation->dateheure)) }}</p>
            </div>
            
            <div class="info-card">
                <h3>📍 Lieu</h3>
                <p>{{ $manifestation->libellelieu }}</p>
            </div>
            
            <div class="info-card">
                <h3>👥 Public visé</h3>
                <p>{{ $manifestation->libellecom }}</p>
            </div>
            
            <div class="info-card">
                <h3>⏱️ Durée</h3>
                <p>{{ $manifestation->duree }} minutes</p>
            </div>
        </div>
        
        <!-- Description -->
        @if($manifestation->resumemanif)
        <div class="description">
            <h3>📝 Description</h3>
            <p>{{ $manifestation->resumemanif }}</p>
        </div>
        @endif
        
        <!-- Affiche -->
        @if($affiche)
        <div class="affiche-section">
            <h3>🎨 Affiche</h3>
            <div class="info-card">
                <p><strong>Nom :</strong> {{ $affiche->nomAffiche }}</p>
                @if(isset($affiche->description))
                    <p style="margin-top: 10px; color: #666;">{{ $affiche->description }}</p>
                @endif
            </div>
        </div>
        @endif
        
        <!-- Places restantes -->
        @php
            $placesClass = '';
            if ($placesRestantes == 0) {
                $placesClass = 'danger';
            } elseif ($placesRestantes < $manifestation->effectif_complet * 0.2) {
                $placesClass = 'warning';
            }
        @endphp
        
        <div class="places-info {{ $placesClass }}">
            <h3>Places disponibles</h3>
            <div class="places-number">{{ $placesRestantes }}</div>
            <p>sur {{ $manifestation->effectif_complet }} places au total</p>
        </div>
        
        <!-- Section de réservation -->
        <div class="reservation-section">
            <h3>🎫 Réserver cette manifestation</h3>
            
            @auth
                @if($placesRestantes > 0)
                    @if(is_null($manifestation->prixmanif) || $manifestation->prixmanif == 0)
                        <!-- Réservation gratuite -->
                        <div class="alert alert-info">
                            ℹ️ Cette manifestation est gratuite. Vous pouvez réserver jusqu'à 4 places.
                        </div>
                        <a href="{{ route('reservations.create', $manifestation->idmanif) }}" class="btn btn-success">
                            ✓ Réserver gratuitement
                        </a>
                    @else
                        <!-- Réservation payante -->
                        <div class="alert alert-warning">
                            💳 Cette manifestation est payante ({{ number_format($manifestation->prixmanif, 2, ',', ' ') }} € par place). Un paiement sera requis.
                        </div>
                        <a href="{{ route('reservations.create-payant', $manifestation->idmanif) }}" class="btn btn-warning">
                            💳 Réserver avec paiement
                        </a>
                    @endif
                @else
                    <!-- Plus de places -->
                    <div class="alert alert-error">
                        ❌ Désolé, il n'y a plus de places disponibles pour cette manifestation.
                    </div>
                    <button class="btn btn-disabled" disabled>Complet</button>
                @endif
            @else
                <!-- Utilisateur non connecté -->
                <div class="login-prompt">
                    <p>🔒 Vous devez être connecté pour réserver cette manifestation.</p>
                    <div style="display: flex; gap: 10px; justify-content: center;">
                        <a href="{{ route('login') }}" class="btn btn-primary">Se connecter</a>
                        <a href="{{ route('register') }}" class="btn btn-success">Créer un compte</a>
                    </div>
                </div>
            @endauth
        </div>
        
        <!-- Actions -->
        <div class="actions">
            <a href="{{ route('manifestations.index') }}" class="btn btn-secondary">
                ← Retour à la liste
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