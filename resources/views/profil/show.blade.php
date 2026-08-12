@extends('layouts.app')

@section('title', 'Mon Profil')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="bg-red-500 px-6 py-4">
            <h1 class="text-xl font-bold text-white">Mon Profil</h1>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informations personnelles</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Nom</label>
                            <p class="text-gray-800">{{ $user->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Email</label>
                            <p class="text-gray-800">{{ $user->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Téléphone</label>
                            <p class="text-gray-800">{{ $user->telephone ?? 'Non renseigné' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Rôle</label>
                            <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm">
                                {{ str_replace('_', ' ', $user->role) }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informations médicales</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Groupe sanguin</label>
                            @if($user->groupe_sanguin)
                                <span class="bg-red-500 text-white font-bold px-3 py-1 rounded-full">
                                    {{ $user->groupe_sanguin }}
                                </span>
                            @else
                                <p class="text-gray-500">Non renseigné</p>
                            @endif
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Ville</label>
                            <p class="text-gray-800">{{ $user->ville ?? 'Non renseigné' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Quartier</label>
                            <p class="text-gray-800">{{ $user->quartier ?? 'Non renseigné' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 pt-6 border-t border-gray-200 flex gap-3">
                <a href="{{ route('profil.edit') }}" 
                   class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors">
                    Modifier le profil
                </a>
                <a href="{{ route('dashboard') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                    Retour au tableau de bord
                </a>
            </div>
        </div>
    </div>
</div>
@endsection


