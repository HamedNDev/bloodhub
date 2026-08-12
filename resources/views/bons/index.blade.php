@extends('layouts.app')
@section('title', 'Bons de Paiement')
@section('content')
<div class="max-w-5xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Bons de Paiement</h1>
        <a href="{{ route('bons.create') }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
            🎁 Offrir un bon
        </a>
    </div>

    {{-- Utiliser un bon --}}
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <h3 class="font-semibold text-blue-800 mb-2">Utiliser un bon reçu</h3>
        <form action="{{ route('bons.utiliser') }}" method="POST" class="flex gap-3">
            @csrf
            <input type="text" name="code" placeholder="Entrez le code du bon (ex: ABCD1234EF)"
                   class="flex-1 border border-blue-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" required>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                Appliquer
            </button>
        </form>
        @error('code') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="bg-red-500 px-4 py-3">
                <h2 class="text-white font-semibold">🎁 Bons offerts ({{ $achetes->count() }})</h2>
            </div>
            @forelse($achetes as $bon)
                <div class="p-4 border-b border-gray-100">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-semibold text-gray-800">{{ number_format($bon->montant, 0, ',', ' ') }} FCFA</p>
                            <p class="text-sm text-gray-500">Pour: {{ $bon->beneficiaire->name }}</p>
                            <p class="text-xs font-mono bg-gray-100 px-2 py-1 rounded mt-1 inline-block">{{ $bon->code }}</p>
                            @if($bon->message)
                                <p class="text-xs text-gray-500 italic mt-1">"{{ $bon->message }}"</p>
                            @endif
                        </div>
                        <span class="px-2 py-1 rounded-full text-xs font-medium
                            @if($bon->statut === 'actif') bg-green-100 text-green-800
                            @elseif($bon->statut === 'utilise') bg-gray-100 text-gray-600
                            @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst($bon->statut) }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Expire: {{ $bon->expire_le?->format('d/m/Y') ?? 'N/A' }}</p>
                </div>
            @empty
                <p class="p-4 text-gray-500 text-sm">Aucun bon offert.</p>
            @endforelse
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="bg-green-500 px-4 py-3">
                <h2 class="text-white font-semibold">💌 Bons reçus ({{ $recus->count() }})</h2>
            </div>
            @forelse($recus as $bon)
                <div class="p-4 border-b border-gray-100">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-semibold text-gray-800">{{ number_format($bon->montant, 0, ',', ' ') }} FCFA</p>
                            <p class="text-sm text-gray-500">De: {{ $bon->acheteur->name }}</p>
                            <p class="text-xs font-mono bg-yellow-100 px-2 py-1 rounded mt-1 inline-block font-bold">{{ $bon->code }}</p>
                            @if($bon->message)
                                <p class="text-xs text-gray-500 italic mt-1">"{{ $bon->message }}"</p>
                            @endif
                        </div>
                        <span class="px-2 py-1 rounded-full text-xs font-medium
                            @if($bon->statut === 'actif') bg-green-100 text-green-800
                            @elseif($bon->statut === 'utilise') bg-gray-100 text-gray-600
                            @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst($bon->statut) }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Expire: {{ $bon->expire_le?->format('d/m/Y') ?? 'N/A' }}</p>
                </div>
            @empty
                <p class="p-4 text-gray-500 text-sm">Aucun bon reçu.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection



