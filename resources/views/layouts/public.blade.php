<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VitaFlow — @yield('title', 'Plateforme de gestion du sang au Cameroun')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-gray-800 font-sans">

{{-- Navbar publique --}}
<nav class="bg-white border-b shadow-sm sticky top-0 z-50">
    <div class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">
        <a href="{{ route('home') }}" class="flex items-center gap-2">
                <svg width="30" height="30" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16 4 C16 4 6 14 6 20 A10 10 0 0 0 26 20 C26 14 16 4 16 4Z" fill="#ef4444"/>
                    <ellipse cx="12" cy="19" rx="2" ry="3" fill="rgba(255,255,255,0.3)"/>
                </svg>
                <span class="text-xl font-bold">
                    <span class="text-blue-600">Vita</span><span class="text-red-600">Flow</span>
                </span>
            </a>
        <div class="hidden md:flex items-center gap-6 text-sm font-medium text-gray-600">
            <a href="{{ route('home') }}" class="hover:text-red-700 transition">Accueil</a>
            <a href="{{ route('stocks.public') }}" class="hover:text-red-700 transition">Disponibilités</a>
            <a href="{{ route('home') }}#comment-ca-marche" class="hover:text-red-700 transition">Comment ça marche</a>
        </div>
        <div class="flex items-center gap-3">
            @auth
                <a href="{{ route('dashboard') }}" class="bg-red-700 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-800 font-medium">Mon espace</a>
            @else
                <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-red-700 font-medium">Connexion</a>
                <a href="{{ route('register') }}" class="bg-red-700 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-800 font-medium">S'inscrire</a>
            @endauth
        </div>
    </div>
</nav>

@yield('content')

{{-- Footer --}}
<footer class="bg-gray-900 text-gray-400 mt-20">
    <div class="max-w-6xl mx-auto px-6 py-12 grid grid-cols-1 md:grid-cols-3 gap-8">
        <div>
            <div class="flex items-center gap-2 mb-3">
                <svg width="24" height="24" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16 4 C16 4 6 14 6 20 A10 10 0 0 0 26 20 C26 14 16 4 16 4Z" fill="#ef4444"/>
                </svg>
                <span class="text-white font-bold text-lg">
                    <span class="text-blue-400">Vita</span><span class="text-red-400">Flow</span>
                </span>
            </div>
            <p class="text-sm leading-relaxed">Plateforme numérique de gestion et de mise en relation pour l'accès aux produits sanguins au Cameroun.</p>
        </div>
        <div>
            <h4 class="text-white font-semibold mb-3">Liens rapides</h4>
            <ul class="space-y-2 text-sm">
                <li><a href="{{ route('home') }}" class="hover:text-white transition">Accueil</a></li>
                <li><a href="{{ route('stocks.public') }}" class="hover:text-white transition">Disponibilités</a></li>
                <li><a href="{{ route('cagnottes.publique') }}" class="hover:text-white transition">Cagnottes</a></li>
                <li><a href="{{ route('register') }}" class="hover:text-white transition">S'inscrire</a></li>
                <li><a href="{{ route('login') }}" class="hover:text-white transition">Connexion</a></li>
            </ul>
        </div>
        <div>
            <h4 class="text-white font-semibold mb-3">Contact</h4>
            <ul class="space-y-2 text-sm">
                <li>📧 contact@vitaflow.cm</li>
                <li>📞 +237 698025244</li>
                <li>📍 Douala, Cameroun</li>
            </ul>
        </div>
    </div>
    <div class="border-t border-gray-800 px-6 py-4 text-center text-xs">
        © {{ date('Y') }} VitaFlow — Tous droits réservés
    </div>
</footer>

</body>
</html>



