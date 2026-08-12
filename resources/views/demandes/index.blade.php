@extends('layouts.app')
@section('title', 'Mes demandes')
@section('content')
<div class="max-w-5xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-red-700">📋 Demandes de sang</h2>
        <a href="{{ route('demandes.create') }}" class="bg-red-700 text-white px-4 py-2 rounded hover:bg-red-800 text-sm">+ Nouvelle demande</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
    @endif

    @if($demandes->isEmpty())
        <div class="bg-white rounded-xl shadow p-8 text-center text-gray-500">Aucune demande pour le moment.</div>
    @else
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-red-50 text-red-700">
                    <tr>
                        <th class="p-3 text-left">Groupe</th>
                        <th class="p-3 text-left">Quantité</th>
                        <th class="p-3 text-left">Urgence</th>
                        <th class="p-3 text-left">Statut</th>
                        <th class="p-3 text-left">Date</th>
                        <th class="p-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($demandes as $demande)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="p-3 font-bold text-red-700">{{ $demande->groupe_sanguin }}</td>
                        <td class="p-3">{{ $demande->quantite }} poche(s)</td>
                        <td class="p-3">
                            <span class="px-2 py-1 rounded text-xs font-semibold
                                {{ $demande->urgence === 'critique' ? 'bg-red-100 text-red-700' :
                                   ($demande->urgence === 'urgente' ? 'bg-orange-100 text-orange-700' : 'bg-gray-100 text-gray-700') }}">
                                {{ ucfirst($demande->urgence) }}
                            </span>
                        </td>
                        <td class="p-3">
                            <span class="px-2 py-1 rounded text-xs font-semibold
                                {{ $demande->statut === 'satisfaite' ? 'bg-green-100 text-green-700' :
                                   ($demande->statut === 'en_cours' ? 'bg-blue-100 text-blue-700' :
                                   ($demande->statut === 'annulee' ? 'bg-gray-100 text-gray-500' : 'bg-yellow-100 text-yellow-700')) }}">
                                {{ ucfirst(str_replace('_', ' ', $demande->statut)) }}
                            </span>
                        </td>
                        <td class="p-3 text-gray-500">{{ $demande->created_at->format('d/m/Y') }}</td>
                        <td class="p-3">
                            <a href="{{ route('demandes.show', $demande) }}" class="text-red-700 underline text-xs">Voir</a>
                            @if($demande->statut === 'en_cours' && !$demande->paiement)
                                | <a href="{{ route('paiements.create', $demande) }}" class="text-green-700 underline text-xs">Payer</a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection



