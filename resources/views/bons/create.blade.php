@extends('layouts.app')
@section('title', 'Offrir un Bon de Paiement')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-red-600 px-6 py-4">
            <h1 class="text-xl font-bold text-white">🎁 Offrir un Bon de Paiement</h1>
            <p class="text-blue-100 text-sm mt-1">Aidez un proche à couvrir ses frais médicaux</p>
        </div>

        <form action="{{ route('bons.store') }}" method="POST" class="p-6 space-y-5">
            @csrf

            @if($demande)
                <input type="hidden" name="demande_id" value="{{ $demande->id }}">
                <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                    <p class="text-sm text-red-800">
                        <strong>Demande associée:</strong> Sang {{ $demande->groupe_sanguin }} — {{ $demande->quantite }}ml
                        ({{ ucfirst($demande->urgence) }})
                    </p>
                </div>
            @else
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Demande associée (optionnel)</label>
                    <select name="demande_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-red-500 focus:border-red-500">
                        <option value="">-- Aucune demande spécifique --</option>
                    </select>
                </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bénéficiaire (patient) *</label>
                <select name="beneficiaire_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-red-500 focus:border-red-500">
                    <option value="">-- Sélectionner un patient --</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}">{{ $patient->name }} ({{ $patient->email }})</option>
                    @endforeach
                </select>
                @error('beneficiaire_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Montant (FCFA) *</label>
                <input type="number" name="montant" min="500" step="100" placeholder="Ex: 5000"
                       value="{{ old('montant') }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-red-500 focus:border-red-500" required>
                @error('montant') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Méthode de paiement *</label>
                <select name="methode" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-red-500 focus:border-red-500">
                    <option value="orange_money">Orange Money</option>
                    <option value="mtn_momo">MTN MoMo</option>
                    <option value="autre">Autre</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Message personnel (optionnel)</label>
                <textarea name="message" rows="3" placeholder="Un mot d'encouragement pour le bénéficiaire..."
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-red-500 focus:border-red-500">{{ old('message') }}</textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg font-medium transition-colors">
                    🎁 Envoyer le bon
                </button>
                <a href="{{ route('bons.index') }}" class="flex-1 text-center bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 rounded-lg font-medium transition-colors">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection



