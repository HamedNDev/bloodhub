@extends('layouts.app')
@section('title', 'Tableau de bord')

@section('content')
<div class="max-w-5xl mx-auto">

    {{-- Greeting --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Bonjour, {{ auth()->user()->name }} 👋</h2>
        <p class="text-gray-500 text-sm mt-1">Voici un aperçu de vos demandes de sang.</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center text-xl">🩸</div>
            <div>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                <p class="text-sm text-gray-500">Total demandes</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-xl">⏳</div>
            <div>
                <p class="text-2xl font-bold text-blue-600">{{ $stats['en_cours'] }}</p>
                <p class="text-sm text-gray-500">En cours</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-xl">✅</div>
            <div>
                <p class="text-2xl font-bold text-green-600">{{ $stats['satisfaite'] }}</p>
                <p class="text-sm text-gray-500">Satisfaites</p>
            </div>
        </div>
    </div>

    {{-- Actions rapides --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <a href="{{ route('demandes.create') }}"
            class="bg-red-700 hover:bg-red-800 text-white rounded-2xl p-5 flex items-center gap-4 transition shadow">
            <span class="text-2xl">➕</span>
            <div>
                <p class="font-semibold">Nouvelle demande</p>
                <p class="text-red-200 text-xs">Soumettre un besoin urgent</p>
            </div>
        </a>
        <a href="{{ route('demandes.index') }}"
            class="bg-white hover:bg-gray-50 border rounded-2xl p-5 flex items-center gap-4 transition shadow-sm">
            <span class="text-2xl">📋</span>
            <div>
                <p class="font-semibold text-gray-700">Mes demandes</p>
                <p class="text-gray-400 text-xs">Suivre mes demandes</p>
            </div>
        </a>
        <a href="{{ route('stocks.public') }}"
            class="bg-white hover:bg-gray-50 border rounded-2xl p-5 flex items-center gap-4 transition shadow-sm">
            <span class="text-2xl">🏦</span>
            <div>
                <p class="font-semibold text-gray-700">Disponibilités</p>
                <p class="text-gray-400 text-xs">Stocks des banques de sang</p>
            </div>
        </a>
    </div>

    {{-- Demandes récentes --}}
    @if($recentes->isNotEmpty())
    <div class="bg-white rounded-2xl shadow-sm border">
        <div class="flex justify-between items-center px-6 py-4 border-b">
            <h3 class="font-semibold text-gray-700">Demandes récentes</h3>
            <a href="{{ route('demandes.index') }}" class="text-sm text-red-700 hover:underline">Voir tout</a>
        </div>
        <div class="divide-y">
            @foreach($recentes as $d)
            <div class="px-6 py-3 flex items-center justify-between hover:bg-gray-50">
                <div class="flex items-center gap-3">
                    <span class="bg-red-100 text-red-700 font-bold text-sm px-2.5 py-1 rounded-lg">{{ $d->groupe_sanguin }}</span>
                    <div>
                        <p class="text-sm font-medium text-gray-700">{{ $d->quantite }} poche(s) — {{ ucfirst($d->urgence) }}</p>
                        <p class="text-xs text-gray-400">{{ $d->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs px-2 py-1 rounded-full font-medium
                        {{ $d->statut === 'satisfaite' ? 'bg-green-100 text-green-700' :
                           ($d->statut === 'en_cours'  ? 'bg-blue-100 text-blue-700' :
                           ($d->statut === 'annulee'   ? 'bg-gray-100 text-gray-500' : 'bg-yellow-100 text-yellow-700')) }}">
                        {{ ucfirst(str_replace('_', ' ', $d->statut)) }}
                    </span>
                    <a href="{{ route('demandes.show', $d) }}" class="text-xs text-red-700 hover:underline">Voir</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection



