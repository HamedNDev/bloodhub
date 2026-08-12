@extends('layouts.admin')
@section('title', 'Modifier — ' . $user->name)

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50 flex justify-between items-center">
            <h2 class="text-lg font-bold text-gray-800">Modifier l'utilisateur</h2>
            <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Retour</a>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-4">
                @csrf @method('PUT')

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nom complet *</label>
                        <input name="name" type="text" value="{{ old('name', $user->name) }}"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-400" required>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Email *</label>
                        <input name="email" type="email" value="{{ old('email', $user->email) }}"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-400" required>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Téléphone</label>
                        <input name="telephone" type="text" value="{{ old('telephone', $user->telephone) }}"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-400">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Ville</label>
                        <input name="ville" type="text" value="{{ old('ville', $user->ville) }}"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-400">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Rôle *</label>
                        <select name="role" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-400" required>
                            @foreach(['patient','donneur','hopital','banque_de_sang','admin'] as $r)
                                <option value="{{ $r }}" {{ old('role', $user->role) == $r ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$r)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Groupe sanguin</label>
                        <select name="groupe_sanguin" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-400">
                            <option value="">— Aucun —</option>
                            @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $g)
                                <option value="{{ $g }}" {{ old('groupe_sanguin', $user->groupe_sanguin) == $g ? 'selected' : '' }}>{{ $g }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex gap-3 pt-2 border-t">
                    <button type="submit" class="flex-1 bg-red-700 text-white py-2.5 rounded-xl hover:bg-red-800 font-semibold text-sm transition-colors">Enregistrer les modifications</button>
                    <a href="{{ route('admin.users.show', $user) }}" class="flex-1 text-center bg-gray-100 text-gray-700 py-2.5 rounded-xl hover:bg-gray-200 text-sm font-medium transition-colors">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection



