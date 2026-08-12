@extends('layouts.public')
@section('title', 'Cagnottes Solidaires — VitaFlow')
@section('content')
<div class="max-w-6xl mx-auto px-6 py-10">
    <div class="text-center mb-10">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">💰 Cagnottes Solidaires</h1>
        <p class="text-gray-500">Contribuez à aider des patients dans le besoin</p>
        @auth
            <a href="{{ route('cagnottes.create') }}" class="inline-block mt-4 bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-medium">
                + Créer une cagnotte
            </a>
        @else
            <a href="{{ route('register') }}" class="inline-block mt-4 bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-medium">
                Créer une cagnotte
            </a>
        @endauth
    </div>

    @if($cagnottes->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($cagnottes as $cagnotte)
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="bg-gradient-to-r from-blue-500 to-red-500 h-3"></div>
                    <div class="p-5">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="font-bold text-gray-800 text-lg leading-tight">{{ $cagnotte->titre }}</h3>
                            @if($cagnotte->demande)
                                <span class="bg-red-100 text-red-700 text-xs px-2 py-1 rounded-full font-medium ml-2 shrink-0">
                                    {{ $cagnotte->demande->groupe_sanguin }}
                                </span>
                            @endif
                        </div>

                        @if($cagnotte->description)
                            <p class="text-gray-500 text-sm mb-4 line-clamp-2">{{ $cagnotte->description }}</p>
                        @endif

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

                        <div class="flex justify-between items-center text-xs text-gray-400 mb-4">
                            <span>{{ $cagnotte->contributions->count() }} contributeur(s)</span>
                            @if($cagnotte->expire_le)
                                <span>Expire: {{ $cagnotte->expire_le->format('d/m/Y') }}</span>
                            @endif
                        </div>

                        <a href="{{ route('cagnottes.voir', $cagnotte->slug) }}"
                           class="block text-center bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg text-sm font-medium transition-colors">
                            Contribuer 💝
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">{{ $cagnottes->links() }}</div>
    @else
        <div class="text-center py-16 text-gray-400">
            <div class="text-6xl mb-4">💰</div>
            <p class="text-lg">Aucune cagnotte active pour le moment.</p>
        </div>
    @endif
</div>
@endsection



