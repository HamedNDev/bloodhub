@extends('layouts.public')
@section('title', 'Connexion')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-red-700 via-red-600 to-red-800 flex items-center justify-center p-4">
    <div class="w-full max-w-md">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-2xl shadow-lg mb-4">
                <span class="text-3xl">🩸</span>
            </div>
            <h1 class="text-3xl font-bold text-white">VitaFlow</h1>
            <p class="text-red-200 mt-1 text-sm">Plateforme transfusionnelle du Cameroun</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <h2 class="text-xl font-bold text-gray-800 mb-1">Connexion</h2>
            <p class="text-gray-500 text-sm mb-6">Accédez à votre espace VitaFlow</p>

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-5 text-sm">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Adresse email</label>
                    <input name="email" type="email" value="{{ old('email') }}" placeholder="vous@exemple.com"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-transparent transition" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                    <input name="password" type="password" placeholder="••••••••"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-transparent transition" required>
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-red-600 focus:ring-red-400">
                        Se souvenir de moi
                    </label>
                </div>

                <button type="submit"
                    class="w-full bg-red-700 hover:bg-red-800 text-white font-semibold py-2.5 rounded-lg transition duration-150 shadow">
                    Se connecter
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-6">
                Pas encore de compte ?
                <a href="{{ route('register') }}" class="text-red-700 font-semibold hover:underline">S'inscrire</a>
            </p>
        </div>

        <p class="text-center text-red-200 text-xs mt-6">© {{ date('Y') }} VitaFlow — Cameroun</p>
    </div>
</div>
@endsection



