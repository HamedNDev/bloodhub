@extends('layouts.public')
@section('title', $cagnotte->titre . ' — VitaFlow')
@section('content')
<div class="max-w-4xl mx-auto px-6 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Infos cagnotte --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-red-600 h-3"></div>
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h1 class="text-2xl font-bold text-gray-800">{{ $cagnotte->titre }}</h1>
                        <span class="px-3 py-1 rounded-full text-sm font-medium
                            @if($cagnotte->statut === 'active') bg-green-100 text-green-800
                            @elseif($cagnotte->statut === 'atteinte') bg-blue-100 text-blue-800
                            @else bg-gray-100 text-gray-600 @endif">
                            {{ ucfirst($cagnotte->statut) }}
                        </span>
                    </div>

                    @if($cagnotte->description)
                        <p class="text-gray-600 mb-6">{{ $cagnotte->description }}</p>
                    @endif

                    {{-- Barre de progression --}}
                    <div class="mb-6">
                        <div class="flex justify-between items-end mb-2">
                            <div>
                                <span class="text-3xl font-bold text-red-600">{{ number_format($cagnotte->montant_collecte, 0, ',', ' ') }}</span>
                                <span class="text-gray-500 text-sm"> FCFA collectés</span>
                            </div>
                            <span class="text-2xl font-bold text-gray-700">{{ $cagnotte->pourcentage() }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-4">
                            <div class="bg-gradient-to-r from-blue-500 to-red-500 h-4 rounded-full transition-all"
                                 style="width: {{ $cagnotte->pourcentage() }}%"></div>
                        </div>
                        <div class="flex justify-between text-sm text-gray-500 mt-2">
                            <span>{{ $cagnotte->contributions->count() }} contributeur(s)</span>
                            <span>Objectif: {{ number_format($cagnotte->objectif, 0, ',', ' ') }} FCFA</span>
                        </div>
                    </div>

                    <div class="flex gap-3 text-sm text-gray-500">
                        <span>Créé par <strong>{{ $cagnotte->createur->name }}</strong></span>
                        @if($cagnotte->expire_le)
                            <span>• Expire le {{ $cagnotte->expire_le->format('d/m/Y') }}</span>
                        @endif
                        @if($cagnotte->demande)
                            <span>• Sang <strong class="text-red-600">{{ $cagnotte->demande->groupe_sanguin }}</strong></span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Partage --}}
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                <h3 class="font-semibold text-blue-800 mb-2">📢 Partagez cette cagnotte</h3>
                <div class="flex gap-2">
                    <input type="text" value="{{ url('/cagnottes/' . $cagnotte->slug) }}" readonly
                           class="flex-1 border border-blue-300 rounded-lg px-3 py-2 text-sm bg-white" id="lien-cagnotte">
                    <button onclick="navigator.clipboard.writeText(document.getElementById('lien-cagnotte').value); this.textContent='✅ Copié!'"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        📋 Copier
                    </button>
                </div>
            </div>

            {{-- Liste des contributions --}}
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="font-bold text-gray-800">💝 Contributions ({{ $cagnotte->contributions->count() }})</h2>
                </div>
                @forelse($cagnotte->contributions->sortByDesc('created_at') as $contribution)
                    <div class="px-6 py-4 border-b border-gray-50 flex justify-between items-center">
                        <div>
                            <p class="font-medium text-gray-800">{{ $contribution->nomAffiche() }}</p>
                            @if($contribution->message)
                                <p class="text-sm text-gray-500 italic">"{{ $contribution->message }}"</p>
                            @endif
                            <p class="text-xs text-gray-400">{{ $contribution->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="font-bold text-green-600">+{{ number_format($contribution->montant, 0, ',', ' ') }} FCFA</span>
                    </div>
                @empty
                    <p class="px-6 py-4 text-gray-500 text-sm">Soyez le premier à contribuer !</p>
                @endforelse
            </div>
        </div>

        {{-- Formulaire de contribution --}}
        <div class="lg:col-span-1">
            @if($cagnotte->statut === 'active')
                <div class="bg-white rounded-xl shadow-md overflow-hidden sticky top-6">
                    <div class="bg-gradient-to-r from-blue-600 to-red-600 px-4 py-3">
                        <h2 class="text-white font-bold">💝 Contribuer</h2>
                    </div>
                    <form action="{{ route('cagnottes.contribuer', $cagnotte->slug) }}" method="POST" class="p-4 space-y-4">
                        @csrf

                        @if(session('success'))
                            <div class="bg-green-50 border border-green-200 text-green-800 px-3 py-2 rounded-lg text-sm">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(!auth()->check())
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Votre nom *</label>
                                <input type="text" name="nom_contributeur" value="{{ old('nom_contributeur') }}"
                                       placeholder="Votre nom" required
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-red-500 focus:border-red-500">
                                @error('nom_contributeur') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Montant (FCFA) *</label>
                            <div class="grid grid-cols-3 gap-2 mb-2">
                                @foreach([1000, 2000, 5000] as $montant)
                                    <button type="button" onclick="document.getElementById('montant').value={{ $montant }}"
                                            class="border border-gray-300 hover:border-red-500 hover:text-red-600 rounded-lg py-1 text-sm transition-colors">
                                        {{ number_format($montant, 0, ',', ' ') }}
                                    </button>
                                @endforeach
                            </div>
                            <input type="number" name="montant" id="montant" min="100" step="100"
                                   value="{{ old('montant') }}" placeholder="Autre montant" required
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-red-500 focus:border-red-500">
                            @error('montant') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Méthode *</label>
                            <select name="methode" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-red-500 focus:border-red-500">
                                <option value="orange_money">Orange Money</option>
                                <option value="mtn_momo">MTN MoMo</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Message (optionnel)</label>
                            <input type="text" name="message" value="{{ old('message') }}" placeholder="Un mot d'encouragement..."
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-red-500 focus:border-red-500">
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="anonyme" id="anonyme" value="1" class="rounded">
                            <label for="anonyme" class="text-sm text-gray-600">Contribuer anonymement</label>
                        </div>

                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-lg font-medium transition-colors">
                            💝 Contribuer maintenant
                        </button>
                    </form>
                </div>
            @else
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 text-center">
                    <div class="text-4xl mb-3">
                        @if($cagnotte->statut === 'atteinte') 🎉 @else 🔒 @endif
                    </div>
                    <p class="font-semibold text-gray-700">
                        @if($cagnotte->statut === 'atteinte') Objectif atteint ! @else Cagnotte fermée @endif
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection



