@extends('layouts.app')

@section('title', 'Mes Notifications')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Mes Notifications</h1>
        
        @if($notifications->count() > 0)
            <form action="{{ route('notifications.toutes-lues') }}" method="POST" class="inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                    Marquer toutes comme lues
                </button>
            </form>
        @endif
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        @if($notifications->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($notifications as $notification)
                    <div class="p-4 {{ !$notification->lue ? 'bg-blue-50 border-l-4 border-l-blue-500' : 'bg-white' }}">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-2">
                                    <h3 class="font-semibold text-gray-800">{{ $notification->titre }}</h3>
                                    @if(!$notification->lue)
                                        <span class="bg-blue-500 text-white text-xs px-2 py-1 rounded-full">Nouveau</span>
                                    @endif
                                    <span class="px-2 py-1 text-xs rounded-full
                                        @if($notification->type === 'demande_hopital') bg-blue-100 text-blue-800
                                        @elseif($notification->type === 'demande_banque') bg-green-100 text-green-800
                                        @elseif($notification->type === 'demande_admin') bg-purple-100 text-purple-800
                                        @elseif($notification->type === 'alerte_don') bg-red-100 text-red-800
                                        @elseif($notification->type === 'stock_insuffisant') bg-orange-100 text-orange-800
                                        @elseif($notification->type === 'statut_demande') bg-indigo-100 text-indigo-800
                                        @elseif($notification->type === 'demande_satisfaite') bg-green-100 text-green-800
                                        @elseif($notification->type === 'demande_annulee') bg-gray-100 text-gray-800
                                        @elseif($notification->type === 'remerciement') bg-pink-100 text-pink-800
                                        @elseif($notification->type === 'paiement') bg-yellow-100 text-yellow-800
                                        @elseif($notification->type === 'urgence') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        @php
                                            $typeLabels = [
                                                'demande_hopital' => 'Hôpital',
                                                'demande_banque' => 'Banque',
                                                'demande_admin' => 'Admin',
                                                'alerte_don' => 'Alerte Don',
                                                'stock_insuffisant' => 'Stock',
                                                'statut_demande' => 'Statut',
                                                'demande_satisfaite' => 'Satisfaite',
                                                'demande_annulee' => 'Annulée',
                                                'remerciement' => 'Merci',
                                                'paiement' => 'Paiement',
                                                'urgence' => 'Urgence'
                                            ];
                                        @endphp
                                        {{ $typeLabels[$notification->type] ?? ucfirst($notification->type) }}
                                    </span>
                                </div>
                                
                                <p class="text-gray-600 mb-2">{{ $notification->message }}</p>
                                
                                <div class="flex items-center justify-between">
                                    <small class="text-gray-500">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </small>
                                    
                                    @if($notification->demande_id)
                                        <a href="{{ route('demandes.show', $notification->demande_id) }}" 
                                           class="text-blue-500 hover:text-blue-700 text-sm font-medium">
                                            Voir la demande →
                                        </a>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-2 ml-4">
                                @if(!$notification->lue)
                                    <form action="{{ route('notifications.lue', $notification) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-blue-500 hover:text-blue-700 text-sm">
                                            Marquer comme lue
                                        </button>
                                    </form>
                                @endif
                                
                                <form action="{{ route('notifications.destroy', $notification) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-500 hover:text-red-700 text-sm"
                                            onclick="return confirm('Supprimer cette notification ?')">
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="px-4 py-3 border-t border-gray-200">
                {{ $notifications->links() }}
            </div>
        @else
            <div class="p-8 text-center">
                <div class="text-gray-400 mb-4">
                    <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17H4l5 5v-5z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v6"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune notification</h3>
                <p class="text-gray-500">Vous n'avez pas encore de notifications.</p>
            </div>
        @endif
    </div>
</div>
@endsection


