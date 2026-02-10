<x-app-layout>
    <div style="max-width: 1400px; margin: 0 auto;">
        <div style="margin-bottom: 2rem;">
            <h1 style="font-size: 2.25rem; font-weight: 700; color: #e2e8f0; margin-bottom: 0.5rem;">
                Manifestations du Festival
            </h1>
            <p style="color: #94a3b8; font-size: 1.1rem;">
                Découvrez toutes les manifestations disponibles
            </p>
        </div>

        @include('manifestations.content', [
            'manifestations' => $manifestations,
            'filterOptions' => $filterOptions,
            'filters' => $filters
        ])
    </div>
</x-app-layout>