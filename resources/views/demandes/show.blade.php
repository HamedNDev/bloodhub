@extends('layouts.app')
@section('title', 'Détail demande')
@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-xl shadow p-8">
    <div class="flex justify-between items-start mb-6">
        <h2 class="text-xl font-bold text-red-700">🩸 Demande #{{ $demande->id }}</h2>
        <a href="{{ route('demandes.index') }}" class="text-sm text-gray-500 underline">← Retour</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-2 gap-4 text-sm mb-6">
        <div><span class="font-medium text-gray-500">Groupe sanguin</span><p class="text-2xl font-bold text-red-700">{{ $demande->groupe_sanguin }}</p></div>
        <div><span class="font-medium text-gray-500">Quantité</span><p class="text-lg font-semibold">{{ $demande->quantite }} poche(s)</p></div>
        <div><span class="font-medium text-gray-500">Urgence</span><p>{{ ucfirst($demande->urgence) }}</p></div>
        <div><span class="font-medium text-gray-500">Statut</span>
            <p class="font-semibold
                {{ $demande->statut === 'satisfaite' ? 'text-green-600' :
                   ($demande->statut === 'en_cours' ? 'text-blue-600' :
                   ($demande->statut === 'annulee' ? 'text-gray-400' : 'text-yellow-600')) }}">
                {{ ucfirst(str_replace('_', ' ', $demande->statut)) }}
            </p>
        </div>
        @if($demande->hopital)
        <div><span class="font-medium text-gray-500">Hôpital</span><p>{{ $demande->hopital->nom }}</p></div>
        @endif
        @if($demande->banqueDeSang)
        <div><span class="font-medium text-gray-500">Banque assignée</span><p>{{ $demande->banqueDeSang->nom }}</p></div>
        @endif
        @if($demande->date_besoin)
        <div><span class="font-medium text-gray-500">Date souhaitée</span><p>{{ $demande->date_besoin->format('d/m/Y H:i') }}</p></div>
        @endif
        <div><span class="font-medium text-gray-500">Créée le</span><p>{{ $demande->created_at->format('d/m/Y H:i') }}</p></div>
    </div>

    @if($demande->description)
        <div class="bg-gray-50 rounded p-3 mb-6 text-sm"><span class="font-medium">Description :</span> {{ $demande->description }}</div>
    @endif

    {{-- Mise à jour statut (admin, hopital, banque) --}}
    @if(in_array(auth()->user()->role, ['admin', 'hopital', 'banque_de_sang']))
        <form method="POST" action="{{ route('demandes.statut', $demande) }}" class="flex gap-3 mb-4">
            @csrf @method('PATCH')
            <select name="statut" class="border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-400">
                @foreach(['en_attente', 'en_cours', 'satisfaite', 'annulee'] as $s)
                    <option value="{{ $s }}" {{ $demande->statut === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                @endforeach
            </select>
            <button class="bg-red-700 text-white px-4 py-2 rounded text-sm hover:bg-red-800">Mettre à jour</button>
        </form>
    @endif

    {{-- Paiement --}}
    @if($demande->user_id === auth()->id() && $demande->statut === 'en_cours' && !$demande->paiement)
        <a href="{{ route('paiements.create', $demande) }}" class="block text-center bg-green-600 text-white py-2 rounded hover:bg-green-700">💳 Effectuer le paiement</a>
    @elseif($demande->paiement)
        <div class="bg-green-50 border border-green-200 rounded p-3 text-sm text-green-700">
            ✅ Paiement effectué — Réf : {{ $demande->paiement->reference }}
            <a href="{{ route('paiements.show', $demande->paiement) }}" class="underline ml-2">Voir le reçu</a>
        </div>
    @endif
</div>
@endsection



