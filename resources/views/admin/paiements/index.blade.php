@extends('layouts.admin')
@section('title', 'Gestion financière')

@section('content')
<div class="max-w-7xl mx-auto space-y-5">

    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Gestion financière</h2>
            <p class="text-gray-500 text-sm mt-0.5">Historique de toutes les transactions</p>
        </div>
    </div>

    {{-- KPIs --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        @foreach([
            ['label'=>'Total transactions', 'value'=>$stats['total'],     'color'=>'text-gray-700',   'bg'=>'bg-gray-50',   'border'=>'border-gray-200'],
            ['label'=>'Confirmés',          'value'=>$stats['confirmes'], 'color'=>'text-green-600',  'bg'=>'bg-green-50',  'border'=>'border-green-100'],
            ['label'=>'En attente',         'value'=>$stats['attente'],   'color'=>'text-yellow-600', 'bg'=>'bg-yellow-50', 'border'=>'border-yellow-100'],
            ['label'=>'Échoués',            'value'=>$stats['echoues'],   'color'=>'text-red-600',    'bg'=>'bg-red-50',    'border'=>'border-red-100'],
            ['label'=>'Montant total (FCFA)','value'=>number_format($stats['montant'],0,',',' '), 'color'=>'text-blue-600','bg'=>'bg-blue-50','border'=>'border-blue-100'],
        ] as $kpi)
        <div class="bg-white rounded-2xl border {{ $kpi['border'] }} p-4 shadow-sm">
            <div class="w-8 h-8 {{ $kpi['bg'] }} rounded-xl flex items-center justify-center text-sm mb-2">💳</div>
            <p class="text-lg font-bold {{ $kpi['color'] }}">{{ $kpi['value'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5">{{ $kpi['label'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- Filtres --}}
    <form method="GET" class="bg-white rounded-2xl shadow-sm border p-4 flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom, email ou référence..."
            class="flex-1 min-w-48 border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-400">
        <select name="statut" class="border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-400">
            <option value="">Tous les statuts</option>
            <option value="confirme"   {{ request('statut')=='confirme'   ? 'selected':'' }}>Confirmé</option>
            <option value="en_attente" {{ request('statut')=='en_attente' ? 'selected':'' }}>En attente</option>
            <option value="echoue"     {{ request('statut')=='echoue'     ? 'selected':'' }}>Échoué</option>
        </select>
        <button class="bg-red-700 text-white px-5 py-2 rounded-xl text-sm hover:bg-red-800 font-medium transition-colors">Filtrer</button>
        @if(request()->hasAny(['search','statut']))
            <a href="{{ route('admin.paiements.index') }}" class="bg-gray-100 text-gray-600 px-5 py-2 rounded-xl text-sm hover:bg-gray-200 font-medium transition-colors">Réinitialiser</a>
        @endif
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                <tr>
                    <th class="px-5 py-3 text-left">Utilisateur</th>
                    <th class="px-5 py-3 text-left">Référence</th>
                    <th class="px-5 py-3 text-left">Demande</th>
                    <th class="px-5 py-3 text-left">Montant</th>
                    <th class="px-5 py-3 text-left">Méthode</th>
                    <th class="px-5 py-3 text-left">Téléphone</th>
                    <th class="px-5 py-3 text-left">Statut</th>
                    <th class="px-5 py-3 text-left">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($paiements as $p)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 bg-yellow-100 rounded-lg flex items-center justify-center text-xs font-bold text-yellow-700">
                                {{ strtoupper(substr($p->user?->name ?? '?', 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-700">{{ $p->user?->name ?? '—' }}</p>
                                <p class="text-xs text-gray-400">{{ $p->user?->email ?? '' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-xs text-gray-500 font-mono">{{ $p->reference ?? '—' }}</td>
                    <td class="px-5 py-3 text-xs text-gray-500">
                        @if($p->demande)
                            <span class="bg-red-100 text-red-700 font-bold px-1.5 py-0.5 rounded text-xs">{{ $p->demande->groupe_sanguin }}</span>
                            <span class="text-gray-400 ml-1">#{{ $p->demande_id }}</span>
                        @else
                            <span class="text-gray-300">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-3">
                        <span class="font-bold text-gray-800 text-sm">{{ number_format($p->montant, 0, ',', ' ') }}</span>
                        <span class="text-xs text-gray-400">FCFA</span>
                    </td>
                    <td class="px-5 py-3 text-xs text-gray-500">{{ ucfirst(str_replace('_',' ',$p->methode ?? '—')) }}</td>
                    <td class="px-5 py-3 text-xs text-gray-500">{{ $p->numero_telephone ?? '—' }}</td>
                    <td class="px-5 py-3">
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium
                            {{ $p->statut==='confirme' ? 'bg-green-100 text-green-700' :
                               ($p->statut==='en_attente' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                            {{ ucfirst(str_replace('_',' ',$p->statut)) }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-xs text-gray-400">{{ $p->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-5 py-10 text-center text-gray-400 text-sm">Aucune transaction trouvée.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-4 border-t bg-gray-50">{{ $paiements->links() }}</div>
    </div>
</div>
@endsection



