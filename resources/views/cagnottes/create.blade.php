@extends('layouts.app')
@section('title', 'Créer une Cagnotte')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white shadow-md rounded-xl overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-red-600 px-6 py-4">
            <h1 class="text-xl font-bold text-white">💰 Créer une Cagnotte</h1>
            <p class="text-blue-100 text-sm mt-1">Collectez des fonds pour couvrir vos frais médicaux</p>
        </div>

        <form action="{{ route('cagnottes.store') }}" method="POST" class="p-6 space-y-5">
            @csrf

            @if($demande)
                <input type="hidden" name="demande_id" value="{{ $demande->id }}">
                <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                    <p class="text-sm text-red-800">
                        <strong>Demande associée:</strong> Sang {{ $demande->groupe_sanguin }} — {{ $demande->quantite }}ml
                    </p>
                </div>
            @else
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Demande associée (optionnel)</label>
                    <select name="demande_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-red-500 focus:border-red-500">
                        <option value="">-- Aucune demande spécifique --</option>
                        @foreach($demandes as $d)
                            <option value="{{ $d->id }}">Demande #{{ $d->id }} — {{ $d->groupe_sanguin }} ({{ ucfirst($d->statut) }})</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Titre de la cagnotte *</label>
                <input type="text" name="titre" value="{{ old('titre') }}" required
                       placeholder="Ex: Aide pour transfusion sanguine urgente"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-red-500 focus:border-red-500">
                @error('titre') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="4" placeholder="Expliquez votre situation pour encourager les contributions..."
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-red-500 focus:border-red-500">{{ old('description') }}</textarea>
                @error('description') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Objectif (FCFA) *</label>
                <input type="number" name="objectif" min="1000" step="500" value="{{ old('objectif') }}" required
                       placeholder="Ex: 50000"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-red-500 focus:border-red-500">
                @error('objectif') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date d'expiration (optionnel)</label>
                <input type="date" name="expire_le" value="{{ old('expire_le') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-red-500 focus:border-red-500">
                @error('expire_le') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg font-medium transition-colors">
                    🚀 Lancer la cagnotte
                </button>
                <a href="{{ route('cagnottes.index') }}" class="flex-1 text-center bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 rounded-lg font-medium transition-colors">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection



