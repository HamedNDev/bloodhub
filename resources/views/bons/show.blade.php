@extends('layouts.app')
@section('title', 'Bon de Paiement')
@section('content')
<div class="max-w-lg mx-auto">
    <div class="bg-white shadow-lg rounded-xl overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-red-600 px-6 py-6 text-center">
            <div class="text-4xl mb-2">🎁</div>
            <h1 class="text-2xl font-bold text-white">Bon de Paiement</h1>
            <p class="text-blue-100 text-sm mt-1">VitaFlow</p>
        </div>

        <div class="p-6 space-y-4">
            <div class="text-center">
                <p class="text-4xl font-bold text-red-600">{{ number_format($bon->montant, 0, ',', ' ') }} FCFA</p>
            </div>

            <div class="bg-gray-50 rounded-lg p-4 text-center">
                <p class="text-xs text-gray-500 mb-1">Code du bon</p>
                <p class="text-2xl font-mono font-bold text-gray-800 tracking-widest">{{ $bon->code }}</p>
            </div>

            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Offert par</span>
                    <span class="font-medium">{{ $bon->acheteur->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Pour</span>
                    <span class="font-medium">{{ $bon->beneficiaire->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Méthode</span>
                    <span class="font-medium">{{ str_replace('_', ' ', ucfirst($bon->methode)) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Statut</span>
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium
                        @if($bon->statut === 'actif') bg-green-100 text-green-800
                        @elseif($bon->statut === 'utilise') bg-gray-100 text-gray-600
                        @else bg-red-100 text-red-800 @endif">
                        {{ ucfirst($bon->statut) }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Expire le</span>
                    <span class="font-medium">{{ $bon->expire_le?->format('d/m/Y') ?? 'N/A' }}</span>
                </div>
                @if($bon->utilise_le)
                <div class="flex justify-between">
                    <span class="text-gray-500">Utilisé le</span>
                    <span class="font-medium">{{ $bon->utilise_le->format('d/m/Y H:i') }}</span>
                </div>
                @endif
            </div>

            @if($bon->message)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                    <p class="text-sm text-blue-800 italic">"{{ $bon->message }}"</p>
                </div>
            @endif

            <a href="{{ route('bons.index') }}" class="block text-center bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 rounded-lg font-medium transition-colors">
                Retour
            </a>
        </div>
    </div>
</div>
@endsection



