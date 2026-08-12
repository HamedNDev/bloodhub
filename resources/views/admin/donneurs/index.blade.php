@extends('layouts.admin')
@section('title', 'Gestion des donneurs')

@section('content')
<div class="max-w-7xl mx-auto space-y-5">

    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Gestion des donneurs</h2>
            <p class="text-gray-500 text-sm mt-0.5">{{ $donneurs->total() }} donneurs enregistrés</p>
        </div>
    </div>

    {{-- Filtres --}}
    <form method="GET" class="bg-white rounded-2xl shadow-sm border p-4 flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom, email ou téléphone..."
            class="flex-1 min-w-48 border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-400">
        <select name="groupe" class="border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-400">
            <option value="">Tous les groupes</option>
            @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $g)
                <option value="{{ $g }}" {{ request('groupe') == $g ? 'selected' : '' }}>{{ $g }}</option>
            @endforeach
        </select>
        <select name="disponible" class="border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-400">
            <option value="">Disponibilité</option>
            <option value="1" {{ request('disponible') === '1' ? 'selected' : '' }}>Disponible</option>
            <option value="0" {{ request('disponible') === '0' ? 'selected' : '' }}>Non disponible</option>
        </select>
        <button class="bg-red-700 text-white px-5 py-2 rounded-xl text-sm hover:bg-red-800 font-medium transition-colors">Filtrer</button>
        @if(request()->hasAny(['search','groupe','disponible']))
            <a href="{{ route('admin.donneurs.index') }}" class="bg-gray-100 text-gray-600 px-5 py-2 rounded-xl text-sm hover:bg-gray-200 font-medium transition-colors">Réinitialiser</a>
        @endif
    </form>

    {{-- Stats rapides --}}
    <div class="grid grid-cols-4 gap-3">
        @foreach([
            ['label'=>'Total donneurs',    'value'=>\App\Models\User::where('role','donneur')->count(),                                                          'color'=>'text-red-600',   'bg'=>'bg-red-50'],
            ['label'=>'Disponibles',       'value'=>\App\Models\User::where('role','donneur')->whereHas('donneur',fn($q)=>$q->where('disponible',true))->count(),'color'=>'text-green-600', 'bg'=>'bg-green-50'],
            ['label'=>'Non disponibles',   'value'=>\App\Models\User::where('role','donneur')->whereHas('donneur',fn($q)=>$q->where('disponible',false))->count(),'color'=>'text-gray-600',  'bg'=>'bg-gray-50'],
            ['label'=>'Notifs actives',    'value'=>\App\Models\User::where('role','donneur')->whereHas('donneur',fn($q)=>$q->where('notifications_actives',true))->count(),'color'=>'text-blue-600','bg'=>'bg-blue-50'],
        ] as $s)
        <div class="bg-white rounded-xl border p-4 text-center shadow-sm">
            <p class="text-2xl font-bold {{ $s['color'] }}">{{ $s['value'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5">{{ $s['label'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                <tr>
                    <th class="px-5 py-3 text-left">Donneur</th>
                    <th class="px-5 py-3 text-left">Groupe</th>
                    <th class="px-5 py-3 text-left">Téléphone</th>
                    <th class="px-5 py-3 text-left">Ville</th>
                    <th class="px-5 py-3 text-left">Dons</th>
                    <th class="px-5 py-3 text-left">Dernier don</th>
                    <th class="px-5 py-3 text-left">Disponible</th>
                    <th class="px-5 py-3 text-left">Notifs</th>
                    <th class="px-5 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($donneurs as $u)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-red-100 text-red-700 rounded-xl flex items-center justify-center text-xs font-bold shrink-0">
                                {{ strtoupper(substr($u->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-700 text-xs">{{ $u->name }}</p>
                                <p class="text-xs text-gray-400">{{ $u->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3">
                        @if($u->groupe_sanguin)
                            <span class="bg-red-100 text-red-700 font-bold px-2 py-0.5 rounded-lg text-xs">{{ $u->groupe_sanguin }}</span>
                        @else
                            <span class="text-gray-300 text-xs">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $u->telephone ?? '—' }}</td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $u->ville ?? '—' }}</td>
                    <td class="px-5 py-3">
                        <span class="font-bold text-red-600 text-sm">{{ $u->donneur?->nombre_dons ?? 0 }}</span>
                    </td>
                    <td class="px-5 py-3 text-gray-400 text-xs">
                        {{ $u->donneur?->dernier_don ? $u->donneur->dernier_don->format('d/m/Y') : '—' }}
                    </td>
                    <td class="px-5 py-3">
                        <form method="POST" action="{{ route('admin.donneurs.toggle-disponible', $u) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="text-xs px-2 py-0.5 rounded-full font-medium transition-colors
                                {{ $u->donneur?->disponible ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                                {{ $u->donneur?->disponible ? '✓ Oui' : '✗ Non' }}
                            </button>
                        </form>
                    </td>
                    <td class="px-5 py-3">
                        <form method="POST" action="{{ route('admin.donneurs.toggle-actif', $u) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="text-xs px-2 py-0.5 rounded-full font-medium transition-colors
                                {{ $u->donneur?->notifications_actives ? 'bg-blue-100 text-blue-700 hover:bg-blue-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                                {{ $u->donneur?->notifications_actives ? '✓ Actif' : '✗ Inactif' }}
                            </button>
                        </form>
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.users.show', $u) }}" class="text-xs bg-blue-50 text-blue-700 hover:bg-blue-100 px-2.5 py-1 rounded-lg font-medium transition-colors">Voir</a>
                            <a href="{{ route('admin.users.edit', $u) }}" class="text-xs bg-gray-100 text-gray-600 hover:bg-gray-200 px-2.5 py-1 rounded-lg font-medium transition-colors">Modifier</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="px-5 py-10 text-center text-gray-400 text-sm">Aucun donneur trouvé.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-4 border-t bg-gray-50">{{ $donneurs->links() }}</div>
    </div>
</div>
@endsection



