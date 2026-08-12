@extends('layouts.app')
@section('title', 'Tableau de bord — Banque de sang')

@section('content')
<div class="max-w-5xl mx-auto">

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">{{ $banque?->nom ?? auth()->user()->name }} 🏦</h2>
        <p class="text-gray-500 text-sm mt-1">{{ $banque?->ville }} — Gestion des stocks et demandes</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center text-xl">📋</div>
            <div>
                <p class="text-2xl font-bold text-gray-800">{{ $demandes }}</p>
                <p class="text-sm text-gray-500">Demandes actives</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border p-5 flex items-center gap-4">
            <div class="w-12 h-12 {{ $alertes > 0 ? 'bg-orange-100' : 'bg-green-100' }} rounded-xl flex items-center justify-center text-xl">
                {{ $alertes > 0 ? '⚠️' : '✅' }}
            </div>
            <div>
                <p class="text-2xl font-bold {{ $alertes > 0 ? 'text-orange-600' : 'text-green-600' }}">{{ $alertes }}</p>
                <p class="text-sm text-gray-500">Alertes stock</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center text-xl">📦</div>
            <div>
                <p class="text-2xl font-bold text-gray-800">{{ $stocks->count() }}/8</p>
                <p class="text-sm text-gray-500">Groupes renseignés</p>
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <a href="{{ route('stocks.index') }}"
            class="bg-red-700 hover:bg-red-800 text-white rounded-2xl p-5 flex items-center gap-4 transition shadow">
            <span class="text-2xl">📦</span>
            <div>
                <p class="font-semibold">Gérer les stocks</p>
                <p class="text-red-200 text-xs">Mettre à jour les quantités</p>
            </div>
        </a>
        <a href="{{ route('demandes.index') }}"
            class="bg-white hover:bg-gray-50 border rounded-2xl p-5 flex items-center gap-4 transition shadow-sm">
            <span class="text-2xl">📋</span>
            <div>
                <p class="font-semibold text-gray-700">Demandes reçues</p>
                <p class="text-gray-400 text-xs">Traiter les demandes</p>
            </div>
        </a>
        <a href="{{ route('stocks.public') }}"
            class="bg-white hover:bg-gray-50 border rounded-2xl p-5 flex items-center gap-4 transition shadow-sm">
            <span class="text-2xl">👁️</span>
            <div>
                <p class="font-semibold text-gray-700">Vue publique</p>
                <p class="text-gray-400 text-xs">Ce que voient les patients</p>
            </div>
        </a>
    </div>

    {{-- Aperçu stocks --}}
    <div class="bg-white rounded-2xl shadow-sm border">
        <div class="flex justify-between items-center px-6 py-4 border-b">
            <h3 class="font-semibold text-gray-700">État des stocks</h3>
            <a href="{{ route('stocks.index') }}" class="text-sm text-red-700 hover:underline">Gérer</a>
        </div>
        <div class="p-6 grid grid-cols-4 md:grid-cols-8 gap-3">
            @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $groupe)
                @php $s = $stocks[$groupe] ?? null; @endphp
                <div class="text-center p-3 rounded-xl border
                    {{ $s && $s->quantite > 0 ? ($s->estCritique() ? 'bg-orange-50 border-orange-200' : 'bg-green-50 border-green-200') : 'bg-gray-50 border-gray-200' }}">
                    <p class="font-bold text-sm text-gray-700">{{ $groupe }}</p>
                    <p class="text-lg font-bold {{ $s && $s->quantite > 0 ? ($s->estCritique() ? 'text-orange-600' : 'text-green-600') : 'text-gray-300' }}">
                        {{ $s ? $s->quantite : '—' }}
                    </p>
                    <p class="text-xs text-gray-400">poches</p>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection



