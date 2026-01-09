<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manifestations du Festival</title>
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

        .filters {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .filters h3 {
            margin-bottom: 15px;
            color: #667eea;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .filter-group label {
            margin-bottom: 5px;
            font-weight: 600;
            color: #333;
        }

        .filter-group select,
        .filter-group input {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .filter-actions {
            margin-top: 15px;
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #5568d3;
        }

        .btn-secondary {
            background: #e0e7ff;
            color: #667eea;
        }

        .btn-secondary:hover {
            background: #c7d2fe;
        }

        .manifestations-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
        }

        .manifestation-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .manifestation-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.2);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 15px;
        }

        .card-type {
            background: #667eea;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .card-price {
            background: #10b981;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .card-price.free {
            background: #6b7280;
        }

        .card-title {
            font-size: 1.5em;
            color: #333;
            margin-bottom: 10px;
        }

        .card-info {
            color: #666;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-info svg {
            width: 16px;
            height: 16px;
        }

        .card-description {
            color: #666;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }

        .places-restantes {
            font-weight: 600;
            color: #10b981;
        }

        .places-restantes.low {
            color: #f59e0b;
        }

        .places-restantes.full {
            color: #ef4444;
        }

        .no-results {
            background: white;
            padding: 40px;
            border-radius: 10px;
            text-align: center;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎭 Manifestations du Festival</h1>

        <!-- Filtres -->
        <div class="filters">
            <h3>Filtrer les manifestations</h3>
            <form method="GET" action="{{ route('manifestations.index') }}">
                <div class="filter-grid">
                    <div class="filter-group">
                        <label for="type">Type</label>
                        <select name="type" id="type">
                            <option value="">Tous les types</option>
                            @foreach($filterOptions['types'] as $type)
                                <option value="{{ $type }}" {{ $filters['type'] == $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="date">Date</label>
                        <input type="date" name="date" id="date" value="{{ $filters['date'] }}">
                    </div>

                    <div class="filter-group">
                        <label for="lieu">Lieu</label>
                        <select name="lieu" id="lieu">
                            <option value="">Tous les lieux</option>
                            @foreach($filterOptions['lieux'] as $lieu)
                                <option value="{{ $lieu->idlieu }}" {{ $filters['lieu'] == $lieu->idlieu ? 'selected' : '' }}>
                                    {{ $lieu->libellelieu }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="communaute">Public visé</label>
                        <select name="communaute" id="communaute">
                            <option value="">Tous les publics</option>
                            @foreach($filterOptions['communautes'] as $com)
                                <option value="{{ $com->idcom }}" {{ $filters['communaute'] == $com->idcom ? 'selected' : '' }}>
                                    {{ $com->libellecom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="payant">Tarif</label>
                        <select name="payant" id="payant">
                            <option value="">Tous les tarifs</option>
                            <option value="non" {{ $filters['payant'] == 'non' ? 'selected' : '' }}>Gratuit</option>
                            <option value="oui" {{ $filters['payant'] == 'oui' ? 'selected' : '' }}>Payant</option>
                        </select>
                    </div>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">Filtrer</button>
                    <a href="{{ route('manifestations.index') }}" class="btn btn-secondary">Réinitialiser</a>
                </div>
            </form>
        </div>

        <!-- Liste des manifestations -->
        @if($manifestations->count() > 0)
            <div class="manifestations-grid">
                @foreach($manifestations as $manif)
                    <div class="manifestation-card">
                        <div class="card-header">
                            <span class="card-type">{{ $manif->type_manifestation }}</span>
                            <span class="card-price {{ is_null($manif->prixmanif) ? 'free' : '' }}">
                                {{ is_null($manif->prixmanif) ? 'Gratuit' : $manif->prixmanif . ' €' }}
                            </span>
                        </div>

                        <h2 class="card-title">{{ $manif->nommanif }}</h2>

                        <div class="card-info">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>{{ date('d/m/Y à H:i', strtotime($manif->dateheure)) }}</span>
                        </div>

                        <div class="card-info">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>{{ $manif->libellelieu }}</span>
                        </div>

                        <div class="card-info">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span>{{ $manif->libellecom }}</span>
                        </div>

                        <p class="card-description">{{ $manif->resumemanif }}</p>

                        <div class="card-footer">
                            @php
                                $placesClass = '';
                                if ($manif->places_restantes == 0) {
                                    $placesClass = 'full';
                                } elseif ($manif->places_restantes < $manif->effectif_complet * 0.2) {
                                    $placesClass = 'low';
                                }
                            @endphp
                            <span class="places-restantes {{ $placesClass }}">
                                {{ $manif->places_restantes }} places restantes
                            </span>
                            <a href="{{ route('manifestations.show', $manif->idmanif) }}" class="btn btn-primary">
                                Voir détails
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="no-results">
                <h3>Aucune manifestation trouvée</h3>
                <p>Essayez de modifier vos filtres de recherche.</p>
            </div>
        @endif
    </div>
</body>
</html>