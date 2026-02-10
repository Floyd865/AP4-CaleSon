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
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: #f5f5f5;
            min-height: 100vh;
            padding: 20px;
            color: #333;
        }
        
        .container {
            max-width: 700px;
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

        .card {
            background-color: white;
            padding: 32px;
            border-radius: 4px;
            border: 1px solid #e0e0e0;
        }
        
        h1 {
            color: #1a1a1a;
            margin-bottom: 24px;
            font-size: 28px;
            font-weight: 600;
        }
        
        .manifestation-info {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 3px;
            margin-bottom: 32px;
            border-left: 3px solid #4a90e2;
        }
        
        .manifestation-info h2 {
            color: #333;
            font-size: 18px;
            margin-bottom: 12px;
            font-weight: 600;
        }
        
        .manifestation-info p {
            color: #666;
            margin-bottom: 6px;
            font-size: 14px;
        }

        .manifestation-info p:last-child {
            margin-bottom: 0;
        }
        
        .form-group {
            margin-bottom: 28px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 12px;
            font-weight: 500;
            color: #333;
            font-size: 15px;
        }
        
        .required {
            color: #f44336;
        }
        
        .star-rating {
            display: flex;
            gap: 8px;
            flex-direction: row-reverse;
            justify-content: flex-end;
            width: fit-content;
        }
        
        .star-rating input {
            display: none;
        }
        
        .star-rating label {
            font-size: 36px;
            color: #e0e0e0;
            cursor: pointer;
            transition: color 0.2s;
            margin: 0;
        }
        
        .star-rating label:hover,
        .star-rating label:hover ~ label,
        .star-rating input:checked ~ label {
            color: #ff9800;
        }
        
        .star-rating-description {
            margin-top: 12px;
            color: #666;
            font-size: 14px;
            font-weight: normal;
        }
        
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #d0d0d0;
            border-radius: 3px;
            font-size: 14px;
            font-family: inherit;
            resize: vertical;
            min-height: 120px;
            transition: border-color 0.2s;
        }
        
        textarea:focus {
            outline: none;
            border-color: #4a90e2;
        }
        
        .char-counter {
            text-align: right;
            font-size: 12px;
            color: #999;
            margin-top: 6px;
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
        
        .actions {
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
    </style>
</head>
<body>
    <div class="container">
        <a href="javascript:history.back()" class="back-link">← Retour</a>

        <div class="card">
            <h1>Modifier votre avis</h1>
            
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
                        placeholder="Partagez votre expérience avec les autres spectateurs..."
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
    </div>
    
    <script>
        const descriptions = {
            1: 'Très décevant',
            2: 'Décevant',
            3: 'Correct',
            4: 'Très bien',
            5: 'Excellent'
        };
        
        document.querySelectorAll('input[name="note"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.getElementById('ratingDescription').textContent = descriptions[this.value];
            });
        });
        
        function updateCharCounter() {
            const textarea = document.getElementById('commentaire');
            const counter = document.getElementById('charCount');
            counter.textContent = textarea.value.length;
        }
        
        updateCharCounter();
    </script>
</body>
</html>