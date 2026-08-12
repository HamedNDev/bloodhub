@extends('layouts.admin')
@section('title', 'Gestion des hôpitaux')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-5">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Gestion des hôpitaux</h2>
            <p class="text-gray-500 text-sm mt-0.5">{{ $hopitaux->total() }} hôpitaux enregistrés</p>
        </div>
        <a href="{{ route('admin.hopitaux.create') }}" class="bg-red-700 text-white px-5 py-2 rounded-xl text-sm hover:bg-red-800 font-medium transition-colors">+ Ajouter un hôpital</a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                <tr>
                    <th class="px-6 py-3 text-left">Nom</th>
                    <th class="px-6 py-3 text-left">Ville</th>
                    <th class="px-6 py-3 text-left">Région</th>
                    <th class="px-6 py-3 text-left">Téléphone</th>
                    <th class="px-6 py-3 text-left">Statut</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($hopitaux as $h)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 font-medium text-gray-700">{{ $h->nom }}</td>
                    <td class="px-6 py-3 text-gray-500">{{ $h->ville }}</td>
                    <td class="px-6 py-3 text-gray-500">{{ $h->region ?? '—' }}</td>
                    <td class="px-6 py-3 text-gray-500">{{ $h->telephone ?? '—' }}</td>
                    <td class="px-6 py-3">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $h->actif ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $h->actif ? 'Actif' : 'Inactif' }}
                        </span>
                    </td>
                    <td class="px-6 py-3">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.hopitaux.edit', $h) }}" class="text-gray-600 hover:underline text-xs font-medium">Modifier</a>
                            <form method="POST" action="{{ route('admin.hopitaux.destroy', $h) }}" onsubmit="return confirm('Supprimer cet hôpital ?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline text-xs font-medium">Supprimer</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-8 text-center text-gray-400">Aucun hôpital enregistré.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t">{{ $hopitaux->links() }}</div>
    </div>
</div>
@endsection



