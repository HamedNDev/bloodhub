@extends('layouts.admin')

@section('title', 'Statistiques')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Statistiques de la Plateforme</h1>
    
    {{-- Cartes statistiques --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <span class="text-3xl">👥</span>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-800">{{ $stats['total_users'] }}</div>
                    <div class="text-sm text-gray-600">Utilisateurs total</div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <span class="text-3xl">🩸</span>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-red-600">{{ $stats['total_donneurs'] }}</div>
                    <div class="text-sm text-gray-600">Donneurs</div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <span class="text-3xl">🏥</span>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-blue-600">{{ $stats['total_hopitaux'] }}</div>
                    <div class="text-sm text-gray-600">Hôpitaux</div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <span class="text-3xl">🏦</span>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-green-600">{{ $stats['total_banques'] }}</div>
                    <div class="text-sm text-gray-600">Banques de sang</div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Statistiques des demandes --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Demandes de Sang</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total des demandes</span>
                    <span class="text-xl font-bold">{{ $stats['total_demandes'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">En cours</span>
                    <span class="text-lg font-semibold text-orange-600">{{ $stats['demandes_en_cours'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Satisfaites</span>
                    <span class="text-lg font-semibold text-green-600">{{ $stats['demandes_satisfaites'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Annulées</span>
                    <span class="text-lg font-semibold text-red-600">{{ $stats['demandes_annulees'] }}</span>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Répartition des Utilisateurs</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Patients</span>
                    <span class="text-lg font-semibold text-purple-600">{{ $stats['total_patients'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Donneurs</span>
                    <span class="text-lg font-semibold text-red-600">{{ $stats['total_donneurs'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Hôpitaux</span>
                    <span class="text-lg font-semibold text-blue-600">{{ $stats['total_hopitaux'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Banques de sang</span>
                    <span class="text-lg font-semibold text-green-600">{{ $stats['total_banques'] }}</span>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Demandes récentes --}}
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-800">Demandes Récentes</h3>
        </div>
        
        @if($demandes_recentes->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Demandeur</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Groupe</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantité</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Urgence</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($demandes_recentes as $demande)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $demande->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $demande->user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="bg-red-500 text-white px-2 py-1 rounded text-xs font-bold">
                                        {{ $demande->groupe_sanguin }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $demande->quantite }} ml</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 rounded text-xs
                                        @if($demande->urgence === 'critique') bg-red-100 text-red-800
                                        @elseif($demande->urgence === 'urgente') bg-orange-100 text-orange-800
                                        @else bg-yellow-100 text-yellow-800
                                        @endif">
                                        {{ ucfirst($demande->urgence) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 rounded text-xs
                                        @if($demande->statut === 'satisfaite') bg-green-100 text-green-800
                                        @elseif($demande->statut === 'en_cours') bg-blue-100 text-blue-800
                                        @elseif($demande->statut === 'annulee') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $demande->statut)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $demande->created_at->diffForHumans() }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-6 text-center text-gray-500">
                Aucune demande récente
            </div>
        @endif
    </div>
</div>
@endsection



