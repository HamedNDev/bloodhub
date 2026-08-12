@extends('layouts.app')

@section('title', 'Mes Alertes')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Mes Alertes de Don</h1>
        <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm">
            {{ $notifications->total() }} alerte(s)
        </span>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        @if($notifications->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($notifications as $notification)
                    <div class="p-4 {{ !$notification->lue ? 'bg-red-50 border-l-4 border-l-red-500' : 'bg-white' }}">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-2">
                                    <h3 class="font-semibold text-gray-800">{{ $notification->titre }}</h3>
                                    @if(!$notification->lue)
                                        <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">Urgent</span>
                                    @endif
                                </div>
                                
                                <p class="text-gray-600 mb-2">{{ $notification->message }}</p>
                                
                                @if($notification->demande)
                                    <div class="bg-gray-100 p-3 rounded-lg mb-2">
                                        <p class="text-sm"><strong>Groupe sanguin demandé :</strong> 
                                            <span class="bg-red-500 text-white px-2 py-1 rounded text-xs">
                                                {{ $notification->demande->groupe_sanguin }}
                                            </span>
                                        </p>
                                        <p class="text-sm"><strong>Quantité :</strong> {{ $notification->demande->quantite }} ml</p>
                                        <p class="text-sm"><strong>Urgence :</strong> 
                                            <span class="px-2 py-1 rounded text-xs
                                                @if($notification->demande->urgence === 'critique') bg-red-100 text-red-800
                                                @elseif($notification->demande->urgence === 'urgente') bg-orange-100 text-orange-800
                                                @else bg-yellow-100 text-yellow-800
                                                @endif">
                                                {{ ucfirst($notification->demande->urgence) }}
                                            </span>
                                        </p>
                                    </div>
                                @endif
                                
                                <small class="text-gray-500">
                                    {{ $notification->created_at->diffForHumans() }}
                                </small>
                            </div>
                            
                            <div class="flex items-center space-x-2 ml-4">
                                @if(!$notification->lue)
                                    <form action="{{ route('donneur.alerte.lue', $notification) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-blue-500 hover:text-blue-700 text-sm">
                                            Marquer comme lue
                                        </button>
                                    </form>
                                @endif
                                
                                @if($notification->demande)
                                    <a href="{{ route('demandes.show', $notification->demande) }}" 
                                       class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                        Voir la demande
                                    </a>
                                @endif
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
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune alerte</h3>
                <p class="text-gray-500">Aucune demande de don ne correspond à votre groupe sanguin pour le moment.</p>
            </div>
        @endif
    </div>
</div>
@endsection


