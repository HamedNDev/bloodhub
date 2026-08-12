@extends('layouts.admin')
@section('title', 'Gestion des banques de sang')
@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-5">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Gestion des banques de sang</h2>
            <p class="text-gray-500 text-sm mt-0.5">{{ $banques->total() }} banques enregistrées</p>
        </div>
        <a href="{{ route('admin.banques.create') }}" class="bg-red-700 text-white px-5 py-2 rounded-xl text-sm hover:bg-red-800 font-medium transition-colors">+ Ajouter une banque</a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                <tr>
                    <th class="px-6 py-3 text-left">Nom</th>
                    <th class="px-6 py-3 text-left">Ville</th>
                    <th class="px-6 py-3 text-left">Stocks</th>
                    <th class="px-6 py-3 text-left">Statut</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($banques as $b)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 font-medium text-gray-700">{{ $b->nom }}</td>
                    <td class="px-6 py-3 text-gray-500">{{ $b->ville }}</td>
                    <td class="px-6 py-3 text-gray-500">{{ $b->stocks->count() }}/8 groupes</td>
                    <td class="px-6 py-3">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $b->actif ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $b->actif ? 'Actif' : 'Inactif' }}
                        </span>
                    </td>
                    <td class="px-6 py-3">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.banques.edit', $b) }}" class="text-gray-600 hover:underline text-xs font-medium">Modifier</a>
                            <form method="POST" action="{{ route('admin.banques.destroy', $b) }}" onsubmit="return confirm('Supprimer cette banque ?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline text-xs font-medium">Supprimer</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">Aucune banque enregistrée.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t">{{ $banques->links() }}</div>
    </div>
</div>
@endsection



