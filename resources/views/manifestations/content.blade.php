<style>
    .filters {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(59, 130, 246, 0.2);
        padding: 24px;
        border-radius: 1rem;
        margin-bottom: 24px;
    }

    .filters h3 {
        margin-bottom: 16px;
        color: #60a5fa;
        font-size: 1.25rem;
        font-weight: 600;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
        margin-bottom: 16px;
    }

    .filter-group label {
        display: block;
        margin-bottom: 6px;
        font-weight: 500;
        color: #daac48;
        font-size: 14px;
    }

    .filter-group select,
    .filter-group input {
        width: 100%;
        padding: 10px 12px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 0.5rem;
        font-size: 14px;
        color: #e6b253;
        transition: all 0.3s ease;
    }

    .filter-group select:focus,
    .filter-group input:focus {
        outline: none;
        border-color: #3b82f6;
        background: rgba(255, 255, 255, 0.08);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .filter-actions {
        display: flex;
        gap: 12px;
        margin-top: 16px;
    }

    .btn-filter {
        padding: 10px 20px;
        border: none;
        border-radius: 0.5rem;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
    }

    .btn-filter-primary {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
    }

    .btn-filter-primary:hover {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        transform: translateY(-2px);
    }

    .btn-filter-secondary {
        background: rgba(255, 255, 255, 0.05);
        color: #f0c26d;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .btn-filter-secondary:hover {
        background: rgba(255, 255, 255, 0.08);
    }

    .manifestations-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 20px;
    }

    .manifestation-card {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 1rem;
        border: 1px solid rgba(59, 130, 246, 0.2);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .manifestation-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(59, 130, 246, 0.2);
        border-color: rgba(59, 130, 246, 0.5);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 20px 20px 0;
        gap: 12px;
    }

    .card-type {
        background: rgba(96, 165, 250, 0.2);
        color: #60a5fa;
        padding: 4px 10px;
        border-radius: 0.375rem;
        font-size: 12px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        white-space: nowrap;
    }

    .card-price {
        padding: 4px 10px;
        border-radius: 0.375rem;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
    }

    .card-price.free {
        background: rgba(34, 197, 94, 0.2);
        color: #86efac;
    }

    .card-price.paid {
        background: rgba(249, 115, 22, 0.2);
        color: #fb923c;
    }

    .manifestation-card.gratuite {
        border-left: 3px solid #4caf50;
    }

    .manifestation-card.payante {
        border-left: 3px solid #ff9800;
    }

    .card-body {
        padding: 16px 20px 20px;
    }

    .card-title {
        font-size: 1.25rem;
        color: #e2e8f0;
        margin-bottom: 12px;
        font-weight: 600;
        line-height: 1.3;
    }

    .card-info {
        color: #94a3b8;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }

    .card-info svg {
        width: 16px;
        height: 16px;
        color: #60a5fa;
        flex-shrink: 0;
    }

    .card-description {
        color: #cbd5e1;
        line-height: 1.5;
        margin: 12px 0 16px;
        font-size: 14px;
    }

    .card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 16px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .places-restantes {
        font-weight: 500;
        font-size: 14px;
        color: #86efac;
    }

    .places-restantes.low {
        color: #fb923c;
    }

    .places-restantes.full {
        color: #fca5a5;
    }

    .btn-details {
        padding: 8px 16px;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        text-decoration: none;
        border-radius: 0.375rem;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-details:hover {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        transform: translateY(-2px);
    }

    .no-results {
        background: rgba(255, 255, 255, 0.05);
        padding: 48px 32px;
        border-radius: 1rem;
        text-align: center;
        color: #cbd5e1;
        border: 1px solid rgba(59, 130, 246, 0.2);
    }

    .no-results h3 {
        color: #60a5fa;
        margin-bottom: 8px;
        font-size: 1.5rem;
    }
</style>

<div class="filters">
    <h3>Filtrer les résultats</h3>
    <form method="GET" action="{{ route('manifestations.index') }}">
        <div class="filter-grid">
            <div class="filter-group">
                <label for="type">Type de manifestation</label>
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
            <button type="submit" class="btn-filter btn-filter-primary">Appliquer les filtres</button>
            <a href="{{ route('manifestations.index') }}" class="btn-filter btn-filter-secondary">Réinitialiser</a>
        </div>
    </form>
</div>

@if($manifestations->count() > 0)
    <div class="manifestations-grid">
        @foreach($manifestations as $manif)
            <div class="manifestation-card {{ is_null($manif->prixmanif) ? 'gratuite' : 'payante' }}">
                <div class="card-header">
                    <span class="card-type">{{ $manif->type_manifestation }}</span>
                    @if(is_null($manif->prixmanif))
                        <span class="card-price free">Gratuit</span>
                    @else
                        <span class="card-price paid">{{ number_format($manif->prixmanif, 2, ',', ' ') }} €</span>
                    @endif
                </div>

                <div class="card-body">
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
                        @if($manif->type_slug === 'atelier' && $manif->date_slug)
                            <a href="{{ route('manifestations.show.atelier', ['id' => $manif->idmanif, 'date' => $manif->date_slug]) }}" class="btn-details">
                                Voir détails
                            </a>
                        @else
                            <a href="{{ route('manifestations.show', ['type' => $manif->type_slug, 'id' => $manif->idmanif]) }}" class="btn-details">
                                Voir détails
                            </a>
                        @endif
                    </div>
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