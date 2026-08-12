@extends('layouts.app')
@section('title', 'Reçu de paiement')
@section('content')
<div class="max-w-md mx-auto bg-white rounded-xl shadow p-8">
    <div class="text-center mb-6">
        <p class="text-3xl">🩸</p>
        <h2 class="text-xl font-bold text-red-700">VitaFlow</h2>
        <p class="text-sm text-gray-500">Reçu de paiement</p>
    </div>

    <div class="border-t border-b py-4 space-y-3 text-sm mb-6">
        <div class="flex justify-between"><span class="text-gray-500">Référence</span><span class="font-mono font-bold">{{ $paiement->reference }}</span></div>
        <div class="flex justify-between"><span class="text-gray-500">Demande</span><span>#{{ $paiement->demande_id }} — {{ $paiement->demande->groupe_sanguin }}</span></div>
        <div class="flex justify-between"><span class="text-gray-500">Montant</span><span class="font-bold text-green-700">{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</span></div>
        <div class="flex justify-between"><span class="text-gray-500">Méthode</span>
            <span>{{ $paiement->methode === 'orange_money' ? '🟠 Orange Money' : ($paiement->methode === 'mtn_momo' ? '🟡 MTN MoMo' : 'Autre') }}</span>
        </div>
        <div class="flex justify-between"><span class="text-gray-500">Téléphone</span><span>{{ $paiement->numero_telephone }}</span></div>
        <div class="flex justify-between"><span class="text-gray-500">Statut</span>
            <span class="px-2 py-1 rounded text-xs {{ $paiement->statut === 'confirme' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                {{ ucfirst($paiement->statut) }}
            </span>
        </div>
        <div class="flex justify-between"><span class="text-gray-500">Date</span><span>{{ $paiement->date_paiement?->format('d/m/Y H:i') ?? '—' }}</span></div>
    </div>

    <div class="flex gap-3">
        <a href="{{ route('paiements.index') }}" class="flex-1 text-center bg-gray-200 text-gray-700 py-2 rounded hover:bg-gray-300 text-sm">← Retour</a>
        @if($paiement->statut === 'en_attente')
            <a href="{{ route('paiements.verifier', $paiement) }}" class="flex-1 text-center bg-blue-600 text-white py-2 rounded hover:bg-blue-700 text-sm">🔄 Vérifier statut</a>
        @else
            <button onclick="window.print()" class="flex-1 bg-red-700 text-white py-2 rounded hover:bg-red-800 text-sm">🖨️ Imprimer</button>
        @endif
    </div>
</div>
@endsection



