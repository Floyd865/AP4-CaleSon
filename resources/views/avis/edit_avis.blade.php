<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier mon avis - {{ $manifestation->nommanif }}</title>
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
        
        .alert-success {
            background: #d1fae5;
            border-left: 4px solid #10b981;
            color: #065f46;
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
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>✏️ Modifier votre avis</h1>
        
        <div class="manifestation-info">
            <h2>{{ $manifestation->nommanif }}</h2>
            <p><strong>Type :</strong> {{ $manifestation->type_manifestation }}</p>
            <p><strong>Date :</strong> {{ date('d/m/Y à H:i', strtotime($manifestation->dateheure)) }}</p>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        <form action="{{ route('avis.update', $avis->idavis) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label>
                    Votre note <span class="required">*</span>
                </label>
                <div class="star-rating">
                    <input type="radio" id="star5" name="note" value="5" {{ $avis->note == 5 ? 'checked' : '' }} required>
                    <label for="star5">★</label>
                    
                    <input type="radio" id="star4" name="note" value="4" {{ $avis->note == 4 ? 'checked' : '' }}>
                    <label for="star4">★</label>
                    
                    <input type="radio" id="star3" name="note" value="3" {{ $avis->note == 3 ? 'checked' : '' }}>
                    <label for="star3">★</label>
                    
                    <input type="radio" id="star2" name="note" value="2" {{ $avis->note == 2 ? 'checked' : '' }}>
                    <label for="star2">★</label>
                    
                    <input type="radio" id="star1" name="note" value="1" {{ $avis->note == 1 ? 'checked' : '' }}>
                    <label for="star1">★</label>
                </div>
                <div class="star-rating-description" id="ratingDescription">
                    {{ $avis->note }} étoile{{ $avis->note > 1 ? 's' : '' }}
                </div>
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
                    oninput="updateCharCounter()">{{ old('commentaire', $avis->commentaire) }}</textarea>
                <div class="char-counter">
                    <span id="charCount">0</span> / 1000 caractères
                </div>
            </div>
            
            <div class="actions">
                <button type="submit" class="btn btn-primary">
                    Enregistrer les modifications
                </button>
                <a href="javascript:history.back()" class="btn btn-secondary">
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
