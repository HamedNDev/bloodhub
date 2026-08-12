@extends('layouts.admin')
@section('title', 'Utilisateurs')

@section('content')
<div class="max-w-6xl mx-auto space-y-5">

    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Gestion des utilisateurs</h2>
            <p class="text-gray-500 text-sm mt-0.5">{{ $users->total() }} utilisateurs enregistrés</p>
        </div>
    </div>

    {{-- Filtres --}}
    <form method="GET" class="bg-white rounded-2xl shadow-sm border p-4 flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher par nom ou email..."
            class="flex-1 min-w-48 border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-transparent">
        <select name="role" class="border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-400">
            <option value="">Tous les rôles</option>
            @foreach(['patient','donneur','hopital','banque_de_sang','admin'] as $r)
                <option value="{{ $r }}" {{ request('role') == $r ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$r)) }}</option>
            @endforeach
        </select>
        <button class="bg-red-700 text-white px-5 py-2 rounded-xl text-sm hover:bg-red-800 font-medium transition-colors">Filtrer</button>
        @if(request('search') || request('role'))
            <a href="{{ route('admin.users.index') }}" class="bg-gray-100 text-gray-600 px-5 py-2 rounded-xl text-sm hover:bg-gray-200 font-medium transition-colors">Réinitialiser</a>
        @endif
    </form>

    {{-- Compteurs par rôle --}}
    <div class="grid grid-cols-5 gap-3">
        @foreach([
            ['role'=>'patient',       'label'=>'Patients',    'color'=>'text-purple-600', 'bg'=>'bg-purple-50', 'border'=>'border-purple-100'],
            ['role'=>'donneur',       'label'=>'Donneurs',    'color'=>'text-red-600',    'bg'=>'bg-red-50',    'border'=>'border-red-100'],
            ['role'=>'hopital',       'label'=>'Hôpitaux',    'color'=>'text-blue-600',   'bg'=>'bg-blue-50',   'border'=>'border-blue-100'],
            ['role'=>'banque_de_sang','label'=>'Banques',     'color'=>'text-green-600',  'bg'=>'bg-green-50',  'border'=>'border-green-100'],
            ['role'=>'admin',         'label'=>'Admins',      'color'=>'text-gray-700',   'bg'=>'bg-gray-50',   'border'=>'border-gray-200'],
        ] as $rc)
        <a href="{{ route('admin.users.index', ['role'=>$rc['role']]) }}"
           class="bg-white rounded-xl border {{ $rc['border'] }} p-3 text-center hover:shadow-sm transition-shadow {{ request('role') === $rc['role'] ? 'ring-2 ring-red-400' : '' }}">
            <p class="text-lg font-bold {{ $rc['color'] }}">{{ \App\Models\User::where('role',$rc['role'])->count() }}</p>
            <p class="text-xs text-gray-500 mt-0.5">{{ $rc['label'] }}</p>
        </a>
        @endforeach
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wide">
                <tr>
                    <th class="px-5 py-3 text-left">Utilisateur</th>
                    <th class="px-5 py-3 text-left">Rôle</th>
                    <th class="px-5 py-3 text-left">Groupe</th>
                    <th class="px-5 py-3 text-left">Ville</th>
                    <th class="px-5 py-3 text-left">Téléphone</th>
                    <th class="px-5 py-3 text-left">Inscrit le</th>
                    <th class="px-5 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($users as $u)
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
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium
                            {{ $u->role==='admin' ? 'bg-purple-100 text-purple-700' :
                               ($u->role==='donneur' ? 'bg-red-100 text-red-700' :
                               ($u->role==='hopital' ? 'bg-blue-100 text-blue-700' :
                               ($u->role==='banque_de_sang' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'))) }}">
                            {{ ucfirst(str_replace('_',' ',$u->role)) }}
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        @if($u->groupe_sanguin)
                            <span class="bg-red-100 text-red-700 font-bold px-2 py-0.5 rounded-lg text-xs">{{ $u->groupe_sanguin }}</span>
                        @else
                            <span class="text-gray-300 text-xs">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $u->ville ?? '—' }}</td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $u->telephone ?? '—' }}</td>
                    <td class="px-5 py-3 text-gray-400 text-xs">{{ $u->created_at->format('d/m/Y') }}</td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.users.show', $u) }}" class="text-xs bg-blue-50 text-blue-700 hover:bg-blue-100 px-2.5 py-1 rounded-lg font-medium transition-colors">Voir</a>
                            <a href="{{ route('admin.users.edit', $u) }}" class="text-xs bg-gray-100 text-gray-600 hover:bg-gray-200 px-2.5 py-1 rounded-lg font-medium transition-colors">Modifier</a>
                            @if($u->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.destroy', $u) }}" onsubmit="return confirm('Supprimer {{ $u->name }} ?')">
                                @csrf @method('DELETE')
                                <button class="text-xs bg-red-50 text-red-600 hover:bg-red-100 px-2.5 py-1 rounded-lg font-medium transition-colors">Supprimer</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-5 py-10 text-center text-gray-400 text-sm">Aucun utilisateur trouvé.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-4 border-t bg-gray-50">{{ $users->links() }}</div>
    </div>
</div>
@endsection



