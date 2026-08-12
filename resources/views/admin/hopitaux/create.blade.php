@extends('layouts.admin')
@section('title', isset($hopital) ? 'Modifier hôpital' : 'Ajouter un hôpital')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-sm border p-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">{{ isset($hopital) ? '✏️ Modifier' : '🏥 Ajouter un hôpital' }}</h2>
        <a href="{{ route('admin.hopitaux.index') }}" class="text-sm text-gray-500 hover:underline">← Retour</a>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 p-3 rounded-lg mb-5 text-sm">
            @foreach($errors->all() as $e) <p>{{ $e }}</p> @endforeach
        </div>
    @endif

    <form method="POST" action="{{ isset($hopital) ? route('admin.hopitaux.update', $hopital) : route('admin.hopitaux.store') }}" class="space-y-4">
        @csrf
        @if(isset($hopital)) @method('PUT') @endif

        <div class="grid grid-cols-2 gap-4">
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom de l'hôpital *</label>
                <input name="nom" type="text" value="{{ old('nom', $hopital->nom ?? '') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-400" required>
            </div>
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Adresse *</label>
                <input name="adresse" type="text" value="{{ old('adresse', $hopital->adresse ?? '') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-400" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ville *</label>
                <input name="ville" type="text" value="{{ old('ville', $hopital->ville ?? '') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-400" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Région</label>
                <input name="region" type="text" value="{{ old('region', $hopital->region ?? '') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-400">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                <input name="telephone" type="text" value="{{ old('telephone', $hopital->telephone ?? '') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-400">
            </div>
            @if(!isset($hopital))
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email (compte) *</label>
                <input name="email" type="email" value="{{ old('email') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-400" required>
            </div>
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe *</label>
                <input name="password" type="password"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-400" required>
            </div>
            @endif
            @if(isset($hopital))
            <div class="col-span-2">
                <label class="flex items-center gap-2 text-sm cursor-pointer">
                    <input type="checkbox" name="actif" value="1" {{ old('actif', $hopital->actif) ? 'checked' : '' }} class="rounded">
                    Hôpital actif
                </label>
            </div>
            @endif
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="flex-1 bg-red-700 text-white py-2.5 rounded-lg hover:bg-red-800 font-semibold">
                {{ isset($hopital) ? 'Enregistrer' : 'Créer l\'hôpital' }}
            </button>
            <a href="{{ route('admin.hopitaux.index') }}" class="flex-1 text-center bg-gray-200 text-gray-700 py-2.5 rounded-lg hover:bg-gray-300">Annuler</a>
        </div>
    </form>
</div>
@endsection



