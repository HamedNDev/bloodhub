@extends('layouts.app')
@section('title', 'Tableau de bord — Donneur')

@section('content')
<div class="max-w-5xl mx-auto">

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Bonjour, {{ auth()->user()->name }} ❤️</h2>
        <p class="text-gray-500 text-sm mt-1">Merci de contribuer à sauver des vies.</p>
    </div>

    {{-- Profil donneur --}}
    <div class="bg-white rounded-2xl shadow-sm border p-6 mb-8">
        <div class="flex flex-wrap items-center gap-6">
            <div class="w-16 h-16 bg-red-700 rounded-2xl flex items-center justify-center text-white text-2xl font-bold">
                {{ auth()->user()->groupe_sanguin ?? '?' }}
            </div>
            <div class="flex-1">
                <p class="font-semibold text-gray-800 text-lg">{{ auth()->user()->name }}</p>
                <p class="text-gray-500 text-sm">{{ auth()->user()->ville ?? 'Ville non renseignée' }}</p>
            </div>
            <div class="flex gap-6 text-center">
                <div>
                    <p class="text-2xl font-bold text-red-700">{{ $donneur?->nombre_dons ?? 0 }}</p>
                    <p class="text-xs text-gray-500">Dons effectués</p>
                </div>
                <div>
                    <p class="text-sm font-semibold {{ $donneur?->disponible ? 'text-green-600' : 'text-gray-400' }}">
                        {{ $donneur?->disponible ? '✅ Disponible' : '⏸️ Indisponible' }}
                    </p>
                    <p class="text-xs text-gray-500">Statut</p>
                </div>
                @if($donneur?->dernier_don)
                <div>
                    <p class="text-sm font-semibold text-gray-700">{{ \Carbon\Carbon::parse($donneur->dernier_don)->format('d/m/Y') }}</p>
                    <p class="text-xs text-gray-500">Dernier don</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-red-700 text-white rounded-2xl p-5 flex items-center gap-4 shadow">
            <span class="text-2xl">🔔</span>
            <div>
                <p class="font-semibold">Alertes de don</p>
                <p class="text-red-200 text-xs">Demandes urgentes compatibles</p>
            </div>
        </div>
        <a href="{{ route('stocks.public') }}"
            class="bg-white hover:bg-gray-50 border rounded-2xl p-5 flex items-center gap-4 transition shadow-sm">
            <span class="text-2xl">🏦</span>
            <div>
                <p class="font-semibold text-gray-700">Disponibilités</p>
                <p class="text-gray-400 text-xs">État des banques de sang</p>
            </div>
        </a>
        <div class="bg-white border rounded-2xl p-5 flex items-center gap-4 shadow-sm">
            <span class="text-2xl">📅</span>
            <div>
                <p class="font-semibold text-gray-700">Prochain don</p>
                <p class="text-gray-400 text-xs">
                    @if($donneur?->dernier_don)
                        {{ \Carbon\Carbon::parse($donneur->dernier_don)->addDays(56)->format('d/m/Y') }}
                    @else
                        Dès maintenant
                    @endif
                </p>
            </div>
        </div>
    </div>

    {{-- Info don --}}
    <div class="bg-red-50 border border-red-200 rounded-2xl p-6">
        <h3 class="font-semibold text-red-700 mb-3">ℹ️ Le saviez-vous ?</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-red-800">
            <div class="flex items-start gap-2"><span>🩸</span><p>Un don de sang peut sauver jusqu'à <strong>3 vies</strong>.</p></div>
            <div class="flex items-start gap-2"><span>⏱️</span><p>Le don dure environ <strong>45 minutes</strong> au total.</p></div>
            <div class="flex items-start gap-2"><span>📅</span><p>On peut donner tous les <strong>56 jours</strong> (8 semaines).</p></div>
        </div>
    </div>

</div>
@endsection



