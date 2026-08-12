@extends('layouts.public')
@section('title', 'Inscription')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-red-700 via-red-600 to-red-800 flex items-center justify-center p-4">
    <div class="w-full max-w-lg">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-2xl shadow-lg mb-4">
                <span class="text-3xl">🩸</span>
            </div>
            <h1 class="text-3xl font-bold text-white">VitaFlow</h1>
            <p class="text-red-200 mt-1 text-sm">Créez votre compte et rejoignez la communauté</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <h2 class="text-xl font-bold text-gray-800 mb-1">Créer un compte</h2>
            <p class="text-gray-500 text-sm mb-6">Tous les champs marqués * sont obligatoires</p>

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-5 text-sm">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nom complet *</label>
                        <input name="name" type="text" value="{{ old('name') }}" placeholder="Jean Dupont"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-400 transition" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                        <input name="email" type="email" value="{{ old('email') }}" placeholder="vous@exemple.com"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-400 transition" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                        <input name="telephone" type="text" value="{{ old('telephone') }}" placeholder="6XXXXXXXX"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-400 transition">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rôle *</label>
                        <select name="role" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-400 transition" required>
                            <option value="">-- Sélectionner --</option>
                            <option value="patient"        {{ old('role') == 'patient' ? 'selected' : '' }}>🤒 Patient</option>
                            <option value="donneur"        {{ old('role') == 'donneur' ? 'selected' : '' }}>❤️ Donneur</option>
                            <option value="hopital"        {{ old('role') == 'hopital' ? 'selected' : '' }}>🏥 Hôpital</option>
                            <option value="banque_de_sang" {{ old('role') == 'banque_de_sang' ? 'selected' : '' }}>🏦 Banque de sang</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Groupe sanguin</label>
                        <select name="groupe_sanguin" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-400 transition">
                            <option value="">-- Optionnel --</option>
                            @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $g)
                                <option value="{{ $g }}" {{ old('groupe_sanguin') == $g ? 'selected' : '' }}>{{ $g }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ville</label>
                        <input name="ville" type="text" value="{{ old('ville') }}" placeholder="Yaoundé"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-400 transition">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe *</label>
                        <input name="password" type="password" placeholder="••••••••"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-400 transition" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirmer *</label>
                        <input name="password_confirmation" type="password" placeholder="••••••••"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-400 transition" required>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-red-700 hover:bg-red-800 text-white font-semibold py-2.5 rounded-lg transition duration-150 shadow mt-2">
                    Créer mon compte
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-6">
                Déjà un compte ?
                <a href="{{ route('login') }}" class="text-red-700 font-semibold hover:underline">Se connecter</a>
            </p>
        </div>
    </div>
</div>
@endsection



