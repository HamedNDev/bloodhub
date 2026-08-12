@extends('layouts.app')
@section('title', 'Nouvelle demande')
@section('content')
<div class="max-w-lg mx-auto bg-white rounded-xl shadow p-8">
    <h2 class="text-xl font-bold text-red-700 mb-6">🩸 Nouvelle demande de sang</h2>

    @if($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            @foreach($errors->all() as $e) <p>{{ $e }}</p> @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('demandes.store') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium mb-1">Groupe sanguin *</label>
            <select name="groupe_sanguin" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-red-400 focus:outline-none" required>
                <option value="">-- Sélectionner --</option>
                @foreach($groupes as $g)
                    <option value="{{ $g }}" {{ old('groupe_sanguin') == $g ? 'selected' : '' }}>{{ $g }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Quantité (poches) *</label>
            <input type="number" name="quantite" value="{{ old('quantite', 1) }}" min="1"
                class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-red-400 focus:outline-none" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Niveau d'urgence *</label>
            <select name="urgence" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-red-400 focus:outline-none" required>
                <option value="normale" {{ old('urgence') == 'normale' ? 'selected' : '' }}>Normale</option>
                <option value="urgente" {{ old('urgence') == 'urgente' ? 'selected' : '' }}>Urgente</option>
                <option value="critique" {{ old('urgence') == 'critique' ? 'selected' : '' }}>Critique</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Hôpital concerné</label>
            <select name="hopital_id" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-red-400 focus:outline-none">
                <option value="">-- Sélectionner un hôpital --</option>
                @foreach($hopitaux as $h)
                    <option value="{{ $h->id }}" {{ old('hopital_id') == $h->id ? 'selected' : '' }}>{{ $h->nom }} — {{ $h->ville }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Date de besoin</label>
            <input type="datetime-local" name="date_besoin" value="{{ old('date_besoin') }}"
                class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-red-400 focus:outline-none">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Description / contexte</label>
            <textarea name="description" rows="3"
                class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-red-400 focus:outline-none">{{ old('description') }}</textarea>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="flex-1 bg-red-700 text-white py-2 rounded hover:bg-red-800 font-semibold">Soumettre</button>
            <a href="{{ route('demandes.index') }}" class="flex-1 text-center bg-gray-200 text-gray-700 py-2 rounded hover:bg-gray-300">Annuler</a>
        </div>
    </form>
</div>
@endsection



