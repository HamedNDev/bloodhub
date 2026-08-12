@extends('layouts.app')
@section('title', 'Mes Cagnottes')
@section('content')
<div class="max-w-5xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">💰 Mes Cagnottes</h1>
        <a href="{{ route('cagnottes.create') }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
            + Créer une cagnotte
        </a>
    </div>

    @if($cagnottes->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($cagnottes as $cagnotte)
                <div class="bg-white shadow-md rounded-xl overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-500 to-red-500 h-2"></div>
                    <div class="p-5">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="font-bold text-gray-800 text-lg">{{ $cagnotte->titre }}</h3>
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                @if($cagnotte->statut === 'active') bg-green-100 text-green-800
                                @elseif($cagnotte->statut === 'atteinte') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-600 @endif">
                                {{ ucfirst($cagnotte->statut) }}
                            </span>
                        </div>

                        <div class="mb-3">
                            <div class="flex justify-between text-xs text-gray-500 mb-1">
                                <span>{{ number_format($cagnotte->montant_collecte, 0, ',', ' ') }} FCFA</span>
                                <span>{{ $cagnotte->pourcentage() }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-blue-500 to-red-500 h-2 rounded-full"
                                     style="width: {{ $cagnotte->pourcentage() }}%"></div>
                            </div>
                            <p class="text-xs text-gray-400 mt-1">Objectif: {{ number_format($cagnotte->objectif, 0, ',', ' ') }} FCFA</p>
                        </div>

                        <div class="flex justify-between text-xs text-gray-400 mb-4">
                            <span>{{ $cagnotte->contributions->count() }} contribution(s)</span>
                            @if($cagnotte->expire_le)
                                <span>Expire: {{ $cagnotte->expire_le->format('d/m/Y') }}</span>
                            @endif
                        </div>

                        <div class="flex gap-2">
                            <a href="{{ route('cagnottes.voir', $cagnotte->slug) }}"
                               class="flex-1 text-center bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg text-sm font-medium transition-colors">
                                Voir
                            </a>
                            <button onclick="navigator.clipboard.writeText('{{ url('/cagnottes/' . $cagnotte->slug) }}')"
                                    class="flex-1 bg-blue-100 hover:bg-blue-200 text-blue-700 py-2 rounded-lg text-sm font-medium transition-colors">
                                📋 Copier le lien
                            </button>
                            @if($cagnotte->statut === 'active')
                                <form action="{{ route('cagnottes.destroy', $cagnotte) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('Fermer cette cagnotte ?')"
                                            class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-3 py-2 rounded-lg text-sm transition-colors">
                                        ✕
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-xl shadow-md p-12 text-center">
            <div class="text-6xl mb-4">💰</div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune cagnotte</h3>
            <p class="text-gray-500 mb-6">Créez une cagnotte pour collecter des fonds pour vos besoins médicaux.</p>
            <a href="{{ route('cagnottes.create') }}" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-medium">
                Créer ma première cagnotte
            </a>
        </div>
    @endif
</div>
@endsection



