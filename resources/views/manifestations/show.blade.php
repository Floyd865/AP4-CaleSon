@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $manifestation->idtheme }}</h1>
    
    <div class="card">
        <div class="card-body">
            <h5>Informations de la manifestation</h5>
            <p><strong>Thème :</strong> {{ $manifestation->idtheme }}</p>
            
            @if($affiche)
                <h5>Affiche</h5>
                <p><strong>Nom :</strong> {{ $affiche->nomAffiche }}</p>
                <!-- Ajoutez d'autres informations de l'affiche -->
            @endif
            
            @if($placesRestantes)
                <h5>Places disponibles</h5>
                <p>{{ $placesRestantes }} places restantes</p>
            @endif
        </div>
    </div>
    
    <a href="{{ route('manifestations.index') }}" class="btn btn-secondary mt-3">Retour</a>
</div>
@endsection