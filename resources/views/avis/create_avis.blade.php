<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donner un avis - {{ $manifestation->nommanif }}</title>
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
            max-width: 700px;
            margin: 0 auto;
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        h1 {
            color: #667eea;
            margin-bottom: 10px;
        }
        
        .manifestation-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid #667eea;
        }
        
        .manifestation-info h2 {
            color: #333;
            font-size: 1.5em;
            margin-bottom: 10px;
        }
        
        .manifestation-info p {
            color: #666;
            margin-bottom: 5px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: #333;
            font-size: 16px;
        }
        
        .required {
            color: #ef4444;
        }
        
        .star-rating {
            display: flex;
            gap: 10px;
            flex-direction: row-reverse;
            justify-content: flex-end;
            width: fit-content;
        }
        
        .star-rating input {
            display: none;
        }
        
        .star-rating label {
            font-size: 40px;
            color: #d1d5db;
            cursor: pointer;
            transition: all 0.2s;
            margin: 0;
        }
        
        .star-rating label:hover,
        .star-rating label:hover ~ label,
        .star-rating input:checked ~ label {
            color: #f59e0b;
        }
        
        .star-rating-description {
            margin-top: 10px;
            color: #666;
            font-size: 14px;
            font-weight: normal;
        }
        
        textarea {
            width: 100%;
            padding: 15px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 15px;
            font-family: inherit;
            resize: vertical;
            min-height: 120px;
            transition: border-color 0.3s;
        }
        
        textarea:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .char-counter {
            text-align: right;
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .alert-error {
            background: #fee2e2;
            border-left: 4px solid #ef4444;
            color: #991b1b;
        }
        
        .actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }
        
        .btn {
            padding: 14px 30px;
            border: none;
            border-radius: 5px;
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
        }
        
        .btn-primary:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-primary:disabled {
            background: #d1d5db;
            cursor: not-allowed;
            transform: none;
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
        }
        
        .error-message {
            color: #ef4444;
            font-size: 14px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>✍️ Donner votre avis</h1>
        
        <div class="manifestation-info">
            <h2>{{ $manifestation->nommanif }}</h2>
            <p><strong>Type :</strong> {{ $manifestation->type_manifestation }}</p>
            <p><strong>Date :</strong> {{ date('d/m/Y à H:i', strtotime($manifestation->dateheure)) }}</p>
        </div>
        
        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif
        
        <form action="{{ $type === 'atelier' && isset($date) ? route('avis.store.atelier', ['id' => $manifestation->idmanif, 'date' => $date]) : route('avis.store', ['type' => $type, 'id' => $manifestation->idmanif]) }}" 
              method="POST" id="avisForm">
            @csrf
            
            @if($type === 'atelier' && isset($date))
                <input type="hidden" name="date" value="{{ $date }}">
            @endif
            
            <div class="form-group">
                <label>
                    Votre note <span class="required">*</span>
                </label>
                <div class="star-rating">
                    <input type="radio" id="star5" name="note" value="5" required>
                    <label for="star5">★</label>
                    
                    <input type="radio" id="star4" name="note" value="4">
                    <label for="star4">★</label>
                    
                    <input type="radio" id="star3" name="note" value="3">
                    <label for="star3">★</label>
                    
                    <input type="radio" id="star2" name="note" value="2">
                    <label for="star2">★</label>
                    
                    <input type="radio" id="star1" name="note" value="1">
                    <label for="star1">★</label>
                </div>
                <div class="star-rating-description" id="ratingDescription">
                    Cliquez sur les étoiles pour noter
                </div>
                @error('note')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="commentaire">
                    Votre commentaire (optionnel)
                </label>
                <textarea 
                    name="commentaire" 
                    id="commentaire" 
                    placeholder="Partagez votre expérience..."
                    maxlength="1000"
                    oninput="updateCharCounter()">{{ old('commentaire') }}</textarea>
                <div class="char-counter">
                    <span id="charCount">0</span> / 1000 caractères
                </div>
                @error('commentaire')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="actions">
                <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                    Publier mon avis
                </button>
                <a href="{{ $type === 'atelier' && isset($date) ? route('manifestations.show.atelier', ['id' => $manifestation->idmanif, 'date' => $date]) : route('manifestations.show', ['type' => $type, 'id' => $manifestation->idmanif]) }}" 
                   class="btn btn-secondary">
                    Annuler
                </a>
            </div>
        </form>
    </div>
    
    <script>
        const descriptions = {
            1: '⭐ Très décevant',
            2: '⭐⭐ Décevant',
            3: '⭐⭐⭐ Correct',
            4: '⭐⭐⭐⭐ Très bien',
            5: '⭐⭐⭐⭐⭐ Excellent'
        };
        
        // Mettre à jour la description de la note
        document.querySelectorAll('input[name="note"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.getElementById('ratingDescription').textContent = descriptions[this.value];
                document.getElementById('submitBtn').disabled = false;
            });
        });
        
        // Compteur de caractères
        function updateCharCounter() {
            const textarea = document.getElementById('commentaire');
            const counter = document.getElementById('charCount');
            counter.textContent = textarea.value.length;
        }
        
        // Initialiser le compteur
        updateCharCounter();
    </script>
</body>
</html>
