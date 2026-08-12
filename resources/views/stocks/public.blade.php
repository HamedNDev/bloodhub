@extends('layouts.public')
@section('title', 'Disponibilités du sang')
@section('content')
<div class="max-w-5xl mx-auto">
    <h2 class="text-xl font-bold text-red-700 mb-6">🩸 Disponibilités des produits sanguins</h2>

    @foreach($banques as $banque)
    <div class="bg-white rounded-xl shadow p-6 mb-6">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h3 class="font-bold text-lg">{{ $banque->nom }}</h3>
                <p class="text-sm text-gray-500">{{ $banque->ville }} — {{ $banque->telephone }}</p>
            </div>
        </div>
        <div class="grid grid-cols-4 md:grid-cols-8 gap-2">
            @foreach($groupes as $groupe)
                @php $stock = $banque->stocks->where('groupe_sanguin', $groupe)->first(); @endphp
                <div class="text-center p-2 rounded-lg {{ $stock && $stock->quantite > 0 ? ($stock->estCritique() ? 'bg-orange-100' : 'bg-green-100') : 'bg-gray-100' }}">
                    <p class="font-bold text-sm {{ $stock && $stock->quantite > 0 ? 'text-gray-800' : 'text-gray-400' }}">{{ $groupe }}</p>
                    <p class="text-xs {{ $stock && $stock->quantite > 0 ? ($stock->estCritique() ? 'text-orange-600' : 'text-green-600') : 'text-gray-400' }}">
                        {{ $stock ? $stock->quantite : '—' }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
    @endforeach

    @if($banques->isEmpty())
        <div class="bg-white rounded-xl shadow p-8 text-center text-gray-500">Aucune banque de sang enregistrée.</div>
    @endif
</div>
@endsection



