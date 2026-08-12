@extends('layouts.admin')
@section('title', 'Tableau de bord')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Bonjour, {{ auth()->user()->name }} 👋</h2>
            <p class="text-gray-500 text-sm mt-0.5">Vue d'ensemble de la plateforme VitaFlow</p>
        </div>
        <span class="text-xs text-gray-400 bg-white border rounded-lg px-3 py-1.5 shadow-sm">
            {{ now()->isoFormat('dddd D MMMM YYYY') }}
        </span>
    </div>

    {{-- ===== KPIs ===== --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach([
            ['label'=>'Utilisateurs',  'value'=>$stats['utilisateurs'],  'sub'=>$stats['donneurs'].' donneurs',    'icon'=>'👥', 'color'=>'text-purple-600', 'bg'=>'bg-purple-50',  'border'=>'border-purple-100'],
            ['label'=>'Demandes',      'value'=>$stats['demandes'],      'sub'=>$stats['en_attente'].' en attente','icon'=>'🩸', 'color'=>'text-red-600',    'bg'=>'bg-red-50',     'border'=>'border-red-100'],
            ['label'=>'Paiements',     'value'=>$stats['paiements'],     'sub'=>$stats['paiements_confirmes'].' confirmés','icon'=>'💳','color'=>'text-yellow-600','bg'=>'bg-yellow-50','border'=>'border-yellow-100'],
            ['label'=>'Cagnottes',     'value'=>$stats['cagnottes'],     'sub'=>$stats['cagnottes_actives'].' actives','icon'=>'💰','color'=>'text-orange-600','bg'=>'bg-orange-50','border'=>'border-orange-100'],
        ] as $kpi)
        <div class="bg-white rounded-2xl border {{ $kpi['border'] }} p-5 stat-card shadow-sm">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-2xl font-bold {{ $kpi['color'] }}">{{ $kpi['value'] }}</p>
                    <p class="text-sm font-semibold text-gray-700 mt-0.5">{{ $kpi['label'] }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $kpi['sub'] }}</p>
                </div>
                <div class="w-10 h-10 {{ $kpi['bg'] }} rounded-xl flex items-center justify-center text-xl">{{ $kpi['icon'] }}</div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ===== LIGNE 2 : Statuts + Stocks critiques + Entités ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- Statut des demandes --}}
        <div class="bg-white rounded-2xl border shadow-sm p-5">
            <h3 class="font-semibold text-gray-700 mb-4 flex items-center gap-2 text-sm">
                <span class="w-6 h-6 bg-red-100 rounded-lg flex items-center justify-center text-xs">🩸</span>
                Statut des demandes
            </h3>
            <div class="space-y-2.5">
                @foreach([
                    ['label'=>'En attente',  'value'=>$stats['en_attente'],  'dot'=>'bg-yellow-400', 'bar'=>'bg-yellow-400', 'text'=>'text-yellow-700'],
                    ['label'=>'En cours',    'value'=>$stats['en_cours'],    'dot'=>'bg-blue-400',   'bar'=>'bg-blue-400',   'text'=>'text-blue-700'],
                    ['label'=>'Satisfaites', 'value'=>$stats['satisfaites'], 'dot'=>'bg-green-400',  'bar'=>'bg-green-400',  'text'=>'text-green-700'],
                    ['label'=>'Annulées',    'value'=>$stats['annulees'],    'dot'=>'bg-gray-300',   'bar'=>'bg-gray-300',   'text'=>'text-gray-500'],
                ] as $s)
                @php $pct = $stats['demandes'] > 0 ? round(($s['value'] / $stats['demandes']) * 100) : 0; @endphp
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <div class="flex items-center gap-1.5">
                            <div class="w-2 h-2 rounded-full {{ $s['dot'] }}"></div>
                            <span class="text-xs text-gray-600">{{ $s['label'] }}</span>
                        </div>
                        <span class="text-xs font-bold {{ $s['text'] }}">{{ $s['value'] }}</span>
                    </div>
                    <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full {{ $s['bar'] }} rounded-full transition-all" style="width: {{ $pct }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
            <a href="{{ route('demandes.index') }}" class="block mt-4 text-xs text-red-600 hover:underline font-medium">Gérer les demandes →</a>
        </div>

        {{-- Stocks critiques --}}
        <div class="bg-white rounded-2xl border shadow-sm p-5">
            <h3 class="font-semibold text-gray-700 mb-4 flex items-center gap-2 text-sm">
                <span class="w-6 h-6 bg-orange-100 rounded-lg flex items-center justify-center text-xs">⚠️</span>
                Stocks critiques
                @if($stats['stocks_critiques'] > 0)
                    <span class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $stats['stocks_critiques'] }}</span>
                @endif
            </h3>
            @if($stocksCritiques->isEmpty())
                <div class="text-center py-6 text-gray-400">
                    <div class="text-3xl mb-2">✅</div>
                    <p class="text-xs">Tous les stocks sont OK</p>
                </div>
            @else
                <div class="space-y-2">
                    @foreach($stocksCritiques as $stock)
                    <div class="flex items-center justify-between bg-orange-50 border border-orange-100 rounded-xl px-3 py-2">
                        <div>
                            <span class="font-bold text-red-600 text-sm">{{ $stock->groupe_sanguin }}</span>
                            <p class="text-xs text-gray-500 truncate max-w-24">{{ $stock->banqueDeSang?->nom ?? '—' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-orange-600 text-sm">{{ $stock->quantite }}</p>
                            <p class="text-xs text-gray-400">min: {{ $stock->seuil_alerte }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                <a href="{{ route('stocks.index') }}" class="block mt-3 text-xs text-orange-600 hover:underline font-medium">Voir tous les stocks →</a>
            @endif
        </div>

        {{-- Entités --}}
        <div class="bg-white rounded-2xl border shadow-sm p-5">
            <h3 class="font-semibold text-gray-700 mb-4 flex items-center gap-2 text-sm">
                <span class="w-6 h-6 bg-blue-100 rounded-lg flex items-center justify-center text-xs">🏥</span>
                Entités enregistrées
            </h3>
            <div class="space-y-3">
                @foreach([
                    ['label'=>'Hôpitaux',      'value'=>$stats['hopitaux'], 'icon'=>'🏥', 'route'=>'admin.hopitaux.index', 'color'=>'text-blue-600',  'bg'=>'bg-blue-50'],
                    ['label'=>'Banques de sang','value'=>$stats['banques'],  'icon'=>'🏦', 'route'=>'admin.banques.index',  'color'=>'text-green-600', 'bg'=>'bg-green-50'],
                    ['label'=>'Donneurs actifs','value'=>$stats['donneurs'], 'icon'=>'❤️', 'route'=>'admin.users.index',    'color'=>'text-red-600',   'bg'=>'bg-red-50'],
                    ['label'=>'Patients',       'value'=>$stats['patients'], 'icon'=>'🧑‍⚕️','route'=>'admin.users.index',   'color'=>'text-purple-600','bg'=>'bg-purple-50'],
                ] as $e)
                <div class="flex items-center justify-between {{ $e['bg'] }} rounded-xl px-3 py-2.5">
                    <div class="flex items-center gap-2">
                        <span class="text-base">{{ $e['icon'] }}</span>
                        <span class="text-xs text-gray-600 font-medium">{{ $e['label'] }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-bold {{ $e['color'] }} text-sm">{{ $e['value'] }}</span>
                        <a href="{{ route($e['route']) }}" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ===== DEMANDES URGENTES + NOUVEAUX INSCRITS ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

        {{-- Demandes urgentes --}}
        <div class="bg-white rounded-2xl border shadow-sm overflow-hidden">
            <div class="flex justify-between items-center px-5 py-3.5 border-b bg-red-50">
                <h3 class="font-semibold text-gray-700 flex items-center gap-2 text-sm">
                    🚨 Demandes urgentes en attente
                    @if($urgentes->count() > 0)
                        <span class="bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $urgentes->count() }}</span>
                    @endif
                </h3>
                <a href="{{ route('demandes.index') }}" class="text-xs text-red-600 hover:underline font-medium">Voir tout</a>
            </div>
            @if($urgentes->isEmpty())
                <div class="text-center py-10 text-gray-400">
                    <div class="text-3xl mb-2">✅</div>
                    <p class="text-sm">Aucune demande urgente</p>
                </div>
            @else
                <div class="divide-y">
                    @foreach($urgentes as $d)
                    <div class="px-5 py-3 flex items-center justify-between hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <span class="bg-red-100 text-red-700 font-bold text-xs px-2 py-1 rounded-lg w-10 text-center">{{ $d->groupe_sanguin }}</span>
                            <div>
                                <p class="text-sm font-medium text-gray-700">{{ $d->user?->name ?? '—' }}</p>
                                <p class="text-xs text-gray-400">{{ $d->hopital?->nom ?? 'Sans hôpital' }} · {{ $d->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $d->urgence === 'critique' ? 'bg-red-100 text-red-700' : 'bg-orange-100 text-orange-700' }}">
                                {{ ucfirst($d->urgence) }}
                            </span>
                            <a href="{{ route('demandes.show', $d) }}" class="text-xs bg-red-600 text-white px-2.5 py-1 rounded-lg hover:bg-red-700 font-medium transition-colors">Traiter</a>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Nouveaux inscrits --}}
        <div class="bg-white rounded-2xl border shadow-sm overflow-hidden">
            <div class="flex justify-between items-center px-5 py-3.5 border-b bg-purple-50">
                <h3 class="font-semibold text-gray-700 flex items-center gap-2 text-sm">👥 Nouveaux inscrits</h3>
                <a href="{{ route('admin.users.index') }}" class="text-xs text-purple-600 hover:underline font-medium">Gérer</a>
            </div>
            <div class="divide-y">
                @forelse($nouveauxUsers as $u)
                <div class="px-5 py-3 flex items-center justify-between hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-purple-100 rounded-xl flex items-center justify-center text-xs font-bold text-purple-700">
                            {{ strtoupper(substr($u->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">{{ $u->name }}</p>
                            <p class="text-xs text-gray-400">{{ $u->email }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium
                            @if($u->role==='admin') bg-red-100 text-red-700
                            @elseif($u->role==='donneur') bg-pink-100 text-pink-700
                            @elseif($u->role==='hopital') bg-blue-100 text-blue-700
                            @elseif($u->role==='banque_de_sang') bg-green-100 text-green-700
                            @else bg-gray-100 text-gray-600 @endif">
                            {{ ucfirst(str_replace('_',' ',$u->role)) }}
                        </span>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $u->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-10 text-gray-400 text-sm">Aucun utilisateur.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ===== DERNIÈRES DEMANDES ===== --}}
    <div class="bg-white rounded-2xl border shadow-sm overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 border-b">
            <h3 class="font-semibold text-gray-700 flex items-center gap-2 text-sm">📋 Dernières demandes</h3>
            <a href="{{ route('demandes.index') }}" class="text-xs text-red-600 hover:underline font-medium">Voir tout →</a>
        </div>
        @if($recentes->isEmpty())
            <p class="text-center text-gray-400 py-10 text-sm">Aucune demande.</p>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="px-5 py-3 text-left">Patient</th>
                        <th class="px-5 py-3 text-left">Groupe</th>
                        <th class="px-5 py-3 text-left">Hôpital</th>
                        <th class="px-5 py-3 text-left">Banque</th>
                        <th class="px-5 py-3 text-left">Urgence</th>
                        <th class="px-5 py-3 text-left">Statut</th>
                        <th class="px-5 py-3 text-left">Date</th>
                        <th class="px-5 py-3 text-left"></th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($recentes as $d)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3 font-medium text-gray-700 text-xs">{{ $d->user?->name ?? '—' }}</td>
                        <td class="px-5 py-3"><span class="bg-red-100 text-red-700 font-bold px-2 py-0.5 rounded-lg text-xs">{{ $d->groupe_sanguin }}</span></td>
                        <td class="px-5 py-3 text-gray-500 text-xs">{{ $d->hopital?->nom ?? '—' }}</td>
                        <td class="px-5 py-3 text-gray-500 text-xs">{{ $d->banqueDeSang?->nom ?? '—' }}</td>
                        <td class="px-5 py-3">
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $d->urgence==='critique' ? 'bg-red-100 text-red-700' : ($d->urgence==='urgente' ? 'bg-orange-100 text-orange-700' : 'bg-gray-100 text-gray-600') }}">
                                {{ ucfirst($d->urgence) }}
                            </span>
                        </td>
                        <td class="px-5 py-3">
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $d->statut==='satisfaite' ? 'bg-green-100 text-green-700' : ($d->statut==='en_cours' ? 'bg-blue-100 text-blue-700' : ($d->statut==='annulee' ? 'bg-gray-100 text-gray-500' : 'bg-yellow-100 text-yellow-700')) }}">
                                {{ ucfirst(str_replace('_',' ',$d->statut)) }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-gray-400 text-xs">{{ $d->created_at->diffForHumans() }}</td>
                        <td class="px-5 py-3">
                            <a href="{{ route('demandes.show', $d) }}" class="text-xs text-red-600 hover:underline font-medium">Voir</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    {{-- ===== DERNIERS PAIEMENTS ===== --}}
    <div class="bg-white rounded-2xl border shadow-sm overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 border-b">
            <h3 class="font-semibold text-gray-700 flex items-center gap-2 text-sm">💳 Derniers paiements</h3>
            <a href="{{ route('paiements.index') }}" class="text-xs text-yellow-600 hover:underline font-medium">Voir tout →</a>
        </div>
        @if($derniersPaiements->isEmpty())
            <p class="text-center text-gray-400 py-8 text-sm">Aucun paiement.</p>
        @else
        <div class="divide-y">
            @foreach($derniersPaiements as $p)
            <div class="px-6 py-3 flex items-center justify-between hover:bg-gray-50 transition-colors">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-yellow-100 rounded-xl flex items-center justify-center text-sm">💳</div>
                    <div>
                        <p class="text-sm font-medium text-gray-700">{{ $p->user?->name ?? '—' }}</p>
                        <p class="text-xs text-gray-400">Réf: {{ $p->reference ?? '—' }} · {{ $p->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-bold text-gray-800 text-sm">{{ number_format($p->montant, 0, ',', ' ') }} FCFA</p>
                    <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $p->statut==='confirme' ? 'bg-green-100 text-green-700' : ($p->statut==='en_attente' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                        {{ ucfirst($p->statut) }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

</div>
@endsection



