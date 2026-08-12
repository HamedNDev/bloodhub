@extends('layouts.admin')
@section('title', 'Détail — ' . $user->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-5">

    <div class="flex justify-between items-center">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-red-100 text-red-700 rounded-2xl flex items-center justify-center text-lg font-bold">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800">{{ $user->name }}</h2>
                <p class="text-gray-500 text-sm">{{ $user->email }}</p>
            </div>
        </div>
        <div class="flex gap-2">
            @if($user->role !== 'admin' && $user->id !== auth()->id())
            <form method="POST" action="{{ route('admin.users.promouvoir', $user) }}" onsubmit="return confirm('Promouvoir {{ $user->name }} en administrateur ?')">
                @csrf
                <button class="bg-purple-600 text-white px-4 py-2 rounded-xl text-sm hover:bg-purple-700 font-medium transition-colors">⬆ Promouvoir admin</button>
            </form>
            @endif
            <a href="{{ route('admin.users.edit', $user) }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-xl text-sm hover:bg-gray-200 font-medium transition-colors">Modifier</a>
            <a href="{{ route('admin.users.index') }}" class="bg-red-700 text-white px-4 py-2 rounded-xl text-sm hover:bg-red-800 font-medium transition-colors">← Retour</a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

        {{-- Infos principales --}}
        <div class="md:col-span-2 bg-white rounded-2xl shadow-sm border p-6">
            <h3 class="font-semibold text-gray-700 mb-4 text-sm flex items-center gap-2">
                <span class="w-5 h-5 bg-blue-100 rounded-lg flex items-center justify-center text-xs">ℹ️</span>
                Informations générales
            </h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div class="bg-gray-50 rounded-xl p-3">
                    <p class="text-xs text-gray-400 mb-0.5">Nom complet</p>
                    <p class="font-medium text-gray-700">{{ $user->name }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-3">
                    <p class="text-xs text-gray-400 mb-0.5">Email</p>
                    <p class="font-medium text-gray-700">{{ $user->email }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-3">
                    <p class="text-xs text-gray-400 mb-0.5">Téléphone</p>
                    <p class="font-medium text-gray-700">{{ $user->telephone ?? '—' }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-3">
                    <p class="text-xs text-gray-400 mb-0.5">Ville</p>
                    <p class="font-medium text-gray-700">{{ $user->ville ?? '—' }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-3">
                    <p class="text-xs text-gray-400 mb-0.5">Rôle</p>
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium
                        {{ $user->role==='admin' ? 'bg-purple-100 text-purple-700' :
                           ($user->role==='donneur' ? 'bg-red-100 text-red-700' :
                           ($user->role==='hopital' ? 'bg-blue-100 text-blue-700' :
                           ($user->role==='banque_de_sang' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'))) }}">
                        {{ ucfirst(str_replace('_',' ',$user->role)) }}
                    </span>
                </div>
                <div class="bg-gray-50 rounded-xl p-3">
                    <p class="text-xs text-gray-400 mb-0.5">Groupe sanguin</p>
                    @if($user->groupe_sanguin)
                        <span class="bg-red-100 text-red-700 font-bold px-2 py-0.5 rounded-lg text-xs">{{ $user->groupe_sanguin }}</span>
                    @else
                        <p class="text-gray-400 text-xs">Non renseigné</p>
                    @endif
                </div>
                <div class="bg-gray-50 rounded-xl p-3 col-span-2">
                    <p class="text-xs text-gray-400 mb-0.5">Inscrit le</p>
                    <p class="font-medium text-gray-700">{{ $user->created_at->format('d/m/Y à H:i') }}</p>
                </div>
            </div>
        </div>

        {{-- Stats --}}
        <div class="space-y-4">
            <div class="bg-white rounded-2xl shadow-sm border p-5">
                <h3 class="font-semibold text-gray-700 mb-3 text-sm">Statistiques</h3>
                <div class="space-y-2.5">
                    <div class="flex justify-between items-center bg-gray-50 rounded-xl px-3 py-2">
                        <span class="text-xs text-gray-500">Demandes</span>
                        <span class="font-bold text-gray-700">{{ $user->demandes->count() }}</span>
                    </div>
                    @if($user->donneur)
                    <div class="flex justify-between items-center bg-red-50 rounded-xl px-3 py-2">
                        <span class="text-xs text-gray-500">Dons effectués</span>
                        <span class="font-bold text-red-700">{{ $user->donneur->nombre_dons ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center bg-gray-50 rounded-xl px-3 py-2">
                        <span class="text-xs text-gray-500">Disponible</span>
                        <span class="text-xs font-medium {{ $user->donneur->disponible ? 'text-green-600' : 'text-gray-400' }}">
                            {{ $user->donneur->disponible ? '✅ Oui' : '❌ Non' }}
                        </span>
                    </div>
                    @endif
                </div>
            </div>

            @if($user->id !== auth()->id())
            <div class="bg-white rounded-2xl shadow-sm border p-5">
                <h3 class="font-semibold text-gray-700 mb-3 text-sm text-red-600">Zone dangereuse</h3>
                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Supprimer définitivement {{ $user->name }} ?')">
                    @csrf @method('DELETE')
                    <button class="w-full bg-red-50 border border-red-200 text-red-700 py-2 rounded-xl text-sm hover:bg-red-100 font-medium transition-colors">
                        🗑 Supprimer l'utilisateur
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>

    {{-- Dernières demandes --}}
    @if($user->demandes->isNotEmpty())
    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
        <div class="px-6 py-4 border-b">
            <h3 class="font-semibold text-gray-700 text-sm">Dernières demandes</h3>
        </div>
        <div class="divide-y">
            @foreach($user->demandes->take(5) as $d)
            <div class="px-6 py-3 flex items-center justify-between hover:bg-gray-50">
                <div class="flex items-center gap-3">
                    <span class="bg-red-100 text-red-700 font-bold text-xs px-2 py-1 rounded-lg">{{ $d->groupe_sanguin }}</span>
                    <span class="text-sm text-gray-600">{{ $d->quantite }} poche(s) — {{ ucfirst($d->urgence) }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs px-2 py-0.5 rounded-full {{ $d->statut==='satisfaite' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                        {{ ucfirst(str_replace('_',' ',$d->statut)) }}
                    </span>
                    <span class="text-xs text-gray-400">{{ $d->created_at->format('d/m/Y') }}</span>
                    <a href="{{ route('demandes.show', $d) }}" class="text-xs text-red-600 hover:underline">Voir</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection



