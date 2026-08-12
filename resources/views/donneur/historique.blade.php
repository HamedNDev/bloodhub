@extends('layouts.app')

@section('title', 'Historique des Dons')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Statistiques du donneur --}}
        <div class="lg:col-span-1">
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="bg-red-500 px-4 py-3">
                    <h2 class="text-lg font-bold text-white">Mes Statistiques</h2>
                </div>
                <div class="p-4">
                    <div class="space-y-4">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-red-600">{{ $donneur->nombre_dons }}</div>
                            <div class="text-sm text-gray-600">Don(s) effectué(s)</div>
                        </div>
                        
                        <div class="border-t pt-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium">Statut</span>
                                <span class="px-2 py-1 rounded-full text-xs {{ $donneur->disponible ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $donneur->disponible ? 'Disponible' : 'Indisponible' }}
                                </span>
                            </div>
                            
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium">Dernier don</span>
                                <span class="text-sm text-gray-600">
                                    @if($donneur->dernier_don)
                                        @if($donneur->dernier_don instanceof \Carbon\Carbon)
                                            {{ $donneur->dernier_don->diffForHumans() }}
                                        @else
                                            {{ \Carbon\Carbon::parse($donneur->dernier_don)->diffForHumans() }}
                                        @endif
                                    @else
                                        Jamais
                                    @endif
                                </span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium">Notifications</span>
                                <span class="px-2 py-1 rounded-full text-xs {{ $donneur->notifications_actives ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $donneur->notifications_actives ? 'Activées' : 'Désactivées' }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="border-t pt-4 space-y-2">
                            <form action="{{ route('donneur.toggle') }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full {{ $donneur->disponible ? 'bg-gray-500 hover:bg-gray-600' : 'bg-green-500 hover:bg-green-600' }} text-white px-4 py-2 rounded transition-colors">
                                    {{ $donneur->disponible ? 'Me rendre indisponible' : 'Me rendre disponible' }}
                                </button>
                            </form>
                            
                            <form action="{{ route('donneur.enregistrer-don') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded transition-colors" 
                                        onclick="return confirm('Confirmer l\\'enregistrement d\\'un nouveau don ?')">
                                    Enregistrer un don
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Historique des notifications --}}
        <div class="lg:col-span-2">
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="bg-red-500 px-4 py-3">
                    <h2 class="text-lg font-bold text-white">Historique des Notifications</h2>
                </div>
                
                @if($notifications->count() > 0)
                    <div class="divide-y divide-gray-200">
                        @foreach($notifications as $notification)
                            <div class="p-4">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2 mb-1">
                                            <h3 class="font-medium text-gray-800">{{ $notification->titre }}</h3>
                                            <span class="px-2 py-1 text-xs rounded-full
                                                @if($notification->type === 'alerte_don') bg-red-100 text-red-800
                                                @elseif($notification->type === 'demande') bg-yellow-100 text-yellow-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ ucfirst(str_replace('_', ' ', $notification->type)) }}
                                            </span>
                                        </div>
                                        
                                        <p class="text-sm text-gray-600 mb-1">{{ $notification->message }}</p>
                                        <small class="text-gray-500">{{ $notification->created_at->diffForHumans() }}</small>
                                    </div>
                                    
                                    @if(!$notification->lue)
                                        <span class="bg-blue-500 text-white text-xs px-2 py-1 rounded-full">Nouveau</span>
                                    @endif
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun historique</h3>
                        <p class="text-gray-500">Vous n'avez pas encore de notifications dans l'historique.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection


