<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation Payante - {{ $manifestation->nommanif }}</title>
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
            font-size: 2em;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .info-section {
            background: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .info-section h2 {
            color: #333;
            margin-bottom: 15px;
            font-size: 1.4em;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #666;
        }

        .info-value {
            color: #333;
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-error {
            background: #fee;
            color: #c00;
            border: 1px solid #fcc;
        }

        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        input, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #667eea;
        }

        small {
            display: block;
            margin-top: 5px;
            color: #666;
            font-size: 13px;
        }

        .payment-summary {
            background: linear-gradient(135deg, #e0e7ff 0%, #ddd6fe 100%);
            padding: 25px;
            border-radius: 10px;
            margin: 25px 0;
        }

        .price-line {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 16px;
            color: #333;
        }

        .total-line {
            font-size: 22px;
            font-weight: 700;
            color: #667eea;
            border-top: 2px solid #667eea;
            padding-top: 12px;
            margin-top: 12px;
        }

        .section-title {
            font-size: 1.3em;
            color: #333;
            margin: 30px 0 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-preview {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 25px;
            border-radius: 15px;
            color: white;
            margin-bottom: 25px;
            min-height: 200px;
            position: relative;
            box-shadow: 0 8px 16px rgba(102, 126, 234, 0.3);
        }

        .card-chip {
            width: 50px;
            height: 40px;
            background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
            border-radius: 8px;
            margin-bottom: 25px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .card-number-display {
            font-family: 'Courier New', monospace;
            font-size: 22px;
            letter-spacing: 3px;
            margin-bottom: 20px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
        }

        .card-details {
            display: flex;
            justify-content: space-between;
        }

        .card-detail-item {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .card-detail-label {
            font-size: 11px;
            opacity: 0.8;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .card-detail-value {
            font-size: 16px;
            font-weight: 600;
        }

        .card-input-row {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 15px;
        }

        .security-badge {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 15px;
            background: #f0fdf4;
            border-radius: 8px;
            margin-top: 20px;
            font-size: 14px;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .security-icon {
            font-size: 24px;
        }

        .btn {
            padding: 14px 28px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: #667eea;
            color: white;
            width: 100%;
        }

        .btn-primary:hover:not(:disabled) {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-primary:disabled {
            background: #d1d5db;
            cursor: not-allowed;
        }

        .btn-secondary {
            background: #e5e7eb;
            color: #374151;
        }

        .btn-secondary:hover {
            background: #d1d5db;
        }

        .form-actions {
            margin-top: 30px;
            display: flex;
            gap: 15px;
        }

        @media (max-width: 768px) {
            .card-input-row {
                grid-template-columns: 1fr;
            }

            .header h1 {
                font-size: 1.5em;
            }

            .card-preview {
                padding: 20px;
                min-height: 180px;
            }

            .card-number-display {
                font-size: 18px;
            }
        }

        .input-error {
            border-color: #ef4444 !important;
        }

        .error-message {
            color: #ef4444;
            font-size: 13px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>💳 Réservation Payante</h1>
            <p>Paiement sécurisé pour votre événement</p>
        </div>

        <div class="card">
            <!-- Informations de la manifestation -->
            <div class="info-section">
                <h2>📋 {{ $manifestation->nommanif }}</h2>
                <div class="info-row">
                    <span class="info-label">Type</span>
                    <span class="info-value">{{ $manifestation->type_manifestation }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Date</span>
                    <span class="info-value">{{ date('d/m/Y à H:i', strtotime($manifestation->dateheure)) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Lieu</span>
                    <span class="info-value">{{ $manifestation->libellelieu }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Prix unitaire</span>
                    <span class="info-value">{{ number_format($manifestation->prixmanif, 2, ',', ' ') }} €</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Places restantes</span>
                    <span class="info-value">{{ $placesRestantes }}</span>
                </div>
            </div>

            <!-- Alertes -->
            @if(session('error'))
                <div class="alert alert-error">
                    ⚠️ {{ session('error') }}
                </div>
            @endif

            @if($placesDejaReservees > 0)
                <div class="alert alert-warning">
                    ℹ️ Vous avez déjà réservé {{ $placesDejaReservees }} place(s) pour cette manifestation.
                </div>
            @endif

            <!-- Formulaire de réservation -->
            <form method="POST" action="{{ route('reservations.store-payant', $manifestation->idmanif) }}" id="payment-form">
                @csrf

                <!-- Sélection du nombre de places -->
                <div class="form-group">
                    <label for="nombre_places">Nombre de places *</label>
                    <select name="nombre_places" id="nombre_places" required onchange="updateTotal()">
                        @for($i = 1; $i <= $maxPlacesDisponibles; $i++)
                            <option value="{{ $i }}" {{ old('nombre_places') == $i ? 'selected' : '' }}>
                                {{ $i }} place{{ $i > 1 ? 's' : '' }}
                            </option>
                        @endfor
                    </select>
                </div>

                <!-- Récapitulatif du paiement -->
                <div class="payment-summary">
                    <div class="price-line">
                        <span>Prix unitaire :</span>
                        <span>{{ number_format($manifestation->prixmanif, 2, ',', ' ') }} €</span>
                    </div>
                    <div class="price-line">
                        <span>Nombre de places :</span>
                        <span id="nb-places-display">{{ old('nombre_places', 1) }}</span>
                    </div>
                    <div class="price-line total-line">
                        <span>Total à payer :</span>
                        <span id="total-amount">{{ number_format($manifestation->prixmanif * old('nombre_places', 1), 2, ',', ' ') }} €</span>
                    </div>
                </div>

                <!-- Section Paiement -->
                <h3 class="section-title">
                    <span>💳</span>
                    <span>Informations de paiement</span>
                </h3>

                <!-- Prévisualisation de la carte -->
                <div class="card-preview">
                    <div class="card-chip"></div>
                    <div class="card-number-display" id="card-display">•••• •••• •••• ••••</div>
                    <div class="card-details">
                        <div class="card-detail-item">
                            <div class="card-detail-label">Titulaire</div>
                            <div class="card-detail-value" id="name-display">VOTRE NOM</div>
                        </div>
                        <div class="card-detail-item">
                            <div class="card-detail-label">Expire</div>
                            <div class="card-detail-value" id="expiry-display">MM/AA</div>
                        </div>
                    </div>
                </div>

                <!-- Numéro de carte -->
                <div class="form-group">
                    <label for="card_number">Numéro de carte bancaire *</label>
                    <input 
                        type="text" 
                        name="card_number" 
                        id="card_number" 
                        placeholder="1234567890123456" 
                        maxlength="16"
                        pattern="[0-9]{16}"
                        value="{{ old('card_number') }}"
                        required
                        oninput="formatCardNumber(this); updateCardDisplay()"
                    >
                    <small>16 chiffres sans espaces (ex: 4111111111111111 pour test)</small>
                    @error('card_number')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Nom sur la carte -->
                <div class="form-group">
                    <label for="card_name">Nom du titulaire *</label>
                    <input 
                        type="text" 
                        name="card_name" 
                        id="card_name" 
                        placeholder="JEAN DUPONT" 
                        value="{{ old('card_name') }}"
                        required
                        style="text-transform: uppercase"
                        oninput="updateCardDisplay()"
                    >
                    <small>Nom tel qu'il apparaît sur la carte</small>
                    @error('card_name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Date d'expiration et CVV -->
                <div class="card-input-row">
                    <div class="form-group">
                        <label for="card_expiry">Date d'expiration *</label>
                        <input 
                            type="text" 
                            name="card_expiry" 
                            id="card_expiry" 
                            placeholder="MM/AA" 
                            maxlength="5"
                            pattern="(0[1-9]|1[0-2])\/[0-9]{2}"
                            value="{{ old('card_expiry') }}"
                            required
                            oninput="formatExpiry(this); updateCardDisplay()"
                        >
                        <small>Format : MM/AA (ex: 12/28)</small>
                        @error('card_expiry')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="card_cvv">CVV *</label>
                        <input 
                            type="text" 
                            name="card_cvv" 
                            id="card_cvv" 
                            placeholder="123" 
                            maxlength="3"
                            pattern="[0-9]{3}"
                            value="{{ old('card_cvv') }}"
                            required
                        >
                        <small>3 chiffres au dos</small>
                        @error('card_cvv')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Badge de sécurité -->
                <div class="security-badge">
                    <span class="security-icon">🔒</span>
                    <div>
                        <strong>Paiement 100% sécurisé</strong><br>
                        <small>Vos données bancaires sont cryptées et protégées</small>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="form-actions">
                    <a href="{{ route('manifestations.show', $manifestation->idmanif) }}" class="btn btn-secondary" style="flex: 0 0 auto;">
                        ← Retour
                    </a>
                    <button type="submit" class="btn btn-primary" id="submit-btn">
                        💳 Payer <span id="submit-amount">{{ number_format($manifestation->prixmanif, 2, ',', ' ') }} €</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const prixUnitaire = {{ $manifestation->prixmanif }};

        // Mettre à jour le total à payer
        function updateTotal() {
            const nbPlaces = parseInt(document.getElementById('nombre_places').value);
            const total = prixUnitaire * nbPlaces;
            
            document.getElementById('nb-places-display').textContent = nbPlaces;
            document.getElementById('total-amount').textContent = total.toFixed(2).replace('.', ',') + ' €';
            document.getElementById('submit-amount').textContent = total.toFixed(2).replace('.', ',') + ' €';
        }

        // Formater le numéro de carte (garder uniquement les chiffres)
        function formatCardNumber(input) {
            let value = input.value.replace(/\D/g, '');
            input.value = value;
        }

        // Formater la date d'expiration (MM/AA)
        function formatExpiry(input) {
            let value = input.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            input.value = value;
        }

        // Mettre à jour l'affichage de la carte
        function updateCardDisplay() {
            const cardNumber = document.getElementById('card_number').value;
            const cardName = document.getElementById('card_name').value;
            const cardExpiry = document.getElementById('card_expiry').value;

            // Afficher le numéro de carte masqué
            if (cardNumber.length > 0) {
                let displayed = '';
                for (let i = 0; i < cardNumber.length; i += 4) {
                    if (i > 0) displayed += ' ';
                    displayed += cardNumber.substring(i, i + 4);
                }
                // Compléter avec des points
                while (displayed.replace(/ /g, '').length < 16) {
                    if (displayed.length % 5 === 4) displayed += ' ';
                    displayed += '•';
                }
                document.getElementById('card-display').textContent = displayed;
            } else {
                document.getElementById('card-display').textContent = '•••• •••• •••• ••••';
            }

            // Afficher le nom
            document.getElementById('name-display').textContent = cardName || 'VOTRE NOM';

            // Afficher la date d'expiration
            document.getElementById('expiry-display').textContent = cardExpiry || 'MM/AA';
        }

        // Validation avant soumission
        document.getElementById('payment-form').addEventListener('submit', function(e) {
            const cardNumber = document.getElementById('card_number').value;
            const cvv = document.getElementById('card_cvv').value;
            const expiry = document.getElementById('card_expiry').value;

            // Vérifier le numéro de carte
            if (cardNumber.length !== 16 || !/^[0-9]{16}$/.test(cardNumber)) {
                e.preventDefault();
                alert('Le numéro de carte doit contenir exactement 16 chiffres');
                document.getElementById('card_number').classList.add('input-error');
                return false;
            }

            // Vérifier le CVV
            if (cvv.length !== 3 || !/^[0-9]{3}$/.test(cvv)) {
                e.preventDefault();
                alert('Le CVV doit contenir exactement 3 chiffres');
                document.getElementById('card_cvv').classList.add('input-error');
                return false;
            }

            // Vérifier la date d'expiration
            if (!/^(0[1-9]|1[0-2])\/[0-9]{2}$/.test(expiry)) {
                e.preventDefault();
                alert('La date d\'expiration doit être au format MM/AA');
                document.getElementById('card_expiry').classList.add('input-error');
                return false;
            }

            // Vérifier que la carte n'est pas expirée
            const [month, year] = expiry.split('/');
            const expiryDate = new Date(2000 + parseInt(year), parseInt(month) - 1);
            const today = new Date();
            
            if (expiryDate < today) {
                e.preventDefault();
                alert('Cette carte est expirée');
                document.getElementById('card_expiry').classList.add('input-error');
                return false;
            }

            // Désactiver le bouton pour éviter les doubles soumissions
            const submitBtn = document.getElementById('submit-btn');
            submitBtn.disabled = true;
            submitBtn.textContent = '⏳ Traitement en cours...';
        });

        // Retirer les erreurs lors de la saisie
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('input', function() {
                this.classList.remove('input-error');
            });
        });
    </script>
</body>
</html>