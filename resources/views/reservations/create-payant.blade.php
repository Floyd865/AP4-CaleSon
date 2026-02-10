<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation - {{ $manifestation->nommanif }}</title>
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
            border-left: 3px solid #ff9800;
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
            background: #fff3e0;
            color: #e65100;
        }

        .alert {
            padding: 14px 16px;
            border-radius: 3px;
            margin-bottom: 20px;
            font-size: 14px;
            border-left: 3px solid;
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

        input, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #d0d0d0;
            border-radius: 3px;
            font-size: 14px;
            transition: border-color 0.2s;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #4a90e2;
        }

        .input-error {
            border-color: #f44336;
        }

        .error-message {
            color: #f44336;
            font-size: 13px;
            margin-top: 6px;
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
            margin: 28px 0;
            border: 1px solid #e0e0e0;
        }

        .summary h3 {
            color: #333;
            font-size: 16px;
            margin-bottom: 16px;
            font-weight: 600;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            font-size: 14px;
            color: #666;
        }

        .summary-row .value {
            font-weight: 500;
            color: #333;
        }

        .summary-row.total {
            border-top: 2px solid #e0e0e0;
            margin-top: 12px;
            padding-top: 16px;
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }

        .summary-row.total .value {
            color: #ff9800;
        }

        .payment-section {
            margin-top: 32px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 1px solid #e0e0e0;
        }

        .card-input-row {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 16px;
        }

        .security-note {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-left: 3px solid #22c55e;
            padding: 14px 16px;
            border-radius: 3px;
            margin-top: 20px;
            font-size: 13px;
            color: #166534;
        }

        .security-note strong {
            display: block;
            margin-bottom: 4px;
        }

        .buttons {
            display: flex;
            gap: 12px;
            margin-top: 32px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 3px;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: #4a90e2;
            color: white;
            flex: 1;
        }

        .btn-primary:hover {
            background: #357abd;
        }

        .btn-primary:disabled {
            background: #e0e0e0;
            color: #999;
            cursor: not-allowed;
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

            .card-input-row {
                grid-template-columns: 1fr;
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

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="header">
            <h1>Réservation payante</h1>
            <p class="subtitle">Finalisez votre réservation</p>
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

                <span class="info-badge">{{ number_format($manifestation->prixmanif, 2, ',', ' ') }} € / place</span>
            </div>

            @if($placesDejaReservees > 0)
                <div class="alert alert-info">
                    Vous avez déjà réservé <strong>{{ $placesDejaReservees }}</strong> place(s) pour cette manifestation.
                </div>
            @endif

            @if($maxPlacesDisponibles == 0)
                <div class="alert alert-warning">
                    Vous avez atteint la limite de 4 places par personne ou il n'y a plus de places disponibles.
                </div>
            @else
                @if(isset($type) && $type === 'atelier' && isset($date))
                    <form method="POST" action="{{ route('reservations.store.atelier.payant', ['id' => $manifestation->idmanif, 'date' => $date]) }}" id="paymentForm">
                @else
                    <form method="POST" action="{{ route('reservations.store.payant', ['type' => $type, 'id' => $manifestation->idmanif]) }}" id="paymentForm">
                @endif
                    @csrf

                    @if(isset($type) && $type === 'atelier' && isset($date))
                        <input type="hidden" name="date" value="{{ $date }}">
                    @endif

                    <div class="form-group">
                        <label for="nombre_places">Nombre de places</label>
                        <span class="form-hint">Maximum {{ $maxPlacesDisponibles }} place(s) disponible(s)</span>
                        
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
                                        onchange="updateTotal()"
                                    >
                                    <label for="places_{{ $i }}">
                                        {{ $i }} place{{ $i > 1 ? 's' : '' }}
                                    </label>
                                </div>
                            @endfor
                        </div>
                    </div>

                    <div class="summary">
                        <h3>Récapitulatif</h3>
                        <div class="summary-row">
                            <span class="label">Prix unitaire :</span>
                            <span class="value">{{ number_format($manifestation->prixmanif, 2, ',', ' ') }} €</span>
                        </div>
                        <div class="summary-row">
                            <span class="label">Nombre de places :</span>
                            <span class="value" id="nb-places-display">1</span>
                        </div>
                        <div class="summary-row total">
                            <span class="label">Total à payer :</span>
                            <span class="value" id="total-amount">{{ number_format($manifestation->prixmanif, 2, ',', ' ') }} €</span>
                        </div>
                    </div>

                    <div class="payment-section">
                        <div class="section-title">Informations de paiement</div>

                        <div class="form-group">
                            <label for="card_number">Numéro de carte bancaire *</label>
                            <input 
                                type="text" 
                                name="card_number" 
                                id="card_number" 
                                placeholder="1234 5678 9012 3456" 
                                maxlength="19"
                                value="{{ old('card_number') }}"
                                required
                                oninput="formatCardNumber(this)"
                            >
                            <span class="form-hint">16 chiffres (utilisez 4111111111111111 pour test)</span>
                            @error('card_number')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="card_name">Nom du titulaire *</label>
                            <input 
                                type="text" 
                                name="card_name" 
                                id="card_name" 
                                placeholder="Jean Dupont" 
                                value="{{ old('card_name') }}"
                                required
                                style="text-transform: uppercase"
                            >
                            <span class="form-hint">Tel qu'il apparaît sur la carte</span>
                            @error('card_name')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="card-input-row">
                            <div class="form-group">
                                <label for="card_expiry">Date d'expiration *</label>
                                <input 
                                    type="text" 
                                    name="card_expiry" 
                                    id="card_expiry" 
                                    placeholder="MM/AA" 
                                    maxlength="5"
                                    value="{{ old('card_expiry') }}"
                                    required
                                    oninput="formatExpiry(this)"
                                >
                                <span class="form-hint">Format : MM/AA</span>
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
                                    value="{{ old('card_cvv') }}"
                                    required
                                    oninput="this.value = this.value.replace(/\D/g, '')"
                                >
                                <span class="form-hint">3 chiffres au dos</span>
                                @error('card_cvv')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="security-note">
                            <strong>Paiement sécurisé</strong>
                            Vos informations bancaires sont cryptées et protégées.
                        </div>
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
                        <button type="submit" class="btn btn-primary" id="submit-btn">
                            Payer <span id="submit-amount">{{ number_format($manifestation->prixmanif, 2, ',', ' ') }} €</span>
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>

    <script>
        const prixUnitaire = {{ $manifestation->prixmanif }};

        function updateTotal() {
            const nbPlaces = parseInt(document.querySelector('input[name="nombre_places"]:checked').value);
            const total = prixUnitaire * nbPlaces;
            
            document.getElementById('nb-places-display').textContent = nbPlaces;
            document.getElementById('total-amount').textContent = total.toFixed(2).replace('.', ',') + ' €';
            document.getElementById('submit-amount').textContent = total.toFixed(2).replace('.', ',') + ' €';
        }

        function formatCardNumber(input) {
            let value = input.value.replace(/\D/g, '');
            let formatted = '';
            
            for (let i = 0; i < value.length && i < 16; i++) {
                if (i > 0 && i % 4 === 0) {
                    formatted += ' ';
                }
                formatted += value[i];
            }
            
            input.value = formatted;
        }

        function formatExpiry(input) {
            let value = input.value.replace(/\D/g, '');
            
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            
            input.value = value;
        }

        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            const cardNumber = document.getElementById('card_number').value.replace(/\s/g, '');
            const cvv = document.getElementById('card_cvv').value;
            const expiry = document.getElementById('card_expiry').value;

            if (cvv.length !== 3 || !/^[0-9]{3}$/.test(cvv)) {
                e.preventDefault();
                alert('Le CVV doit contenir exactement 3 chiffres');
                document.getElementById('card_cvv').classList.add('input-error');
                return false;
            }

            if (!/^(0[1-9]|1[0-2])\/[0-9]{2}$/.test(expiry)) {
                e.preventDefault();
                alert('La date d\'expiration doit être au format MM/AA');
                document.getElementById('card_expiry').classList.add('input-error');
                return false;
            }

            const [month, year] = expiry.split('/');
            const expiryDate = new Date(2000 + parseInt(year), parseInt(month) - 1);
            const today = new Date();
            
            if (expiryDate < today) {
                e.preventDefault();
                alert('Cette carte est expirée');
                document.getElementById('card_expiry').classList.add('input-error');
                return false;
            }

            const submitBtn = document.getElementById('submit-btn');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Traitement en cours...';
        });

        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('input', function() {
                this.classList.remove('input-error');
            });
        });
    </script>
</body>
</html>