@extends('layouts.app')
@section('title', 'Mes paiements')
@section('content')
<div class="max-w-4xl mx-auto">
    <h2 class="text-xl font-bold text-red-700 mb-6">💳 Historique des paiements</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
    @endif

    @if($paiements->isEmpty())
        <div class="bg-white rounded-xl shadow p-8 text-center text-gray-500">Aucun paiement effectué.</div>
    @else
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-green-50 text-green-700">
                    <tr>
                        <th class="p-3 text-left">Référence</th>
                        <th class="p-3 text-left">Demande</th>
                        <th class="p-3 text-left">Montant</th>
                        <th class="p-3 text-left">Méthode</th>
                        <th class="p-3 text-left">Statut</th>
                        <th class="p-3 text-left">Date</th>
                        <th class="p-3 text-left">Reçu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($paiements as $p)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="p-3 font-mono text-xs">{{ $p->reference }}</td>
                        <td class="p-3">#{{ $p->demande_id }} — {{ $p->demande->groupe_sanguin }}</td>
                        <td class="p-3 font-semibold">{{ number_format($p->montant, 0, ',', ' ') }} FCFA</td>
                        <td class="p-3">{{ $p->methode === 'orange_money' ? '🟠 Orange' : ($p->methode === 'mtn_momo' ? '🟡 MTN' : 'Autre') }}</td>
                        <td class="p-3">
                            <span class="px-2 py-1 rounded text-xs {{ $p->statut === 'confirme' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ ucfirst($p->statut) }}
                            </span>
                        </td>
                        <td class="p-3 text-gray-500">{{ $p->date_paiement?->format('d/m/Y H:i') ?? '—' }}</td>
                        <td class="p-3"><a href="{{ route('paiements.show', $p) }}" class="text-green-700 underline text-xs">Voir</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection



