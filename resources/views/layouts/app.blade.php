<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VitaFlow — @yield('title', 'Plateforme de gestion du sang')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .nav-link {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 14px; border-radius: 10px;
            font-size: 0.875rem; font-weight: 500;
            color: rgba(255,255,255,0.75);
            transition: all 0.15s ease;
        }
        .nav-link:hover { background: rgba(255,255,255,0.12); color: #fff; }
        .nav-link.active { background: rgba(255,255,255,0.18); color: #fff; font-weight: 600; }
        .nav-section { font-size: 0.65rem; font-weight: 700; letter-spacing: 0.1em;
            color: rgba(255,255,255,0.35); padding: 14px 14px 4px; text-transform: uppercase; }
        .badge { background: #ef4444; color: #fff; font-size: 0.65rem;
            font-weight: 700; padding: 1px 6px; border-radius: 999px; margin-left: auto; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen font-sans">

@auth
@php
    $user = auth()->user();
    $role = $user->role;
    $notifCount = \App\Models\Notification::where('user_id', $user->id)->where('lue', false)->count();
@endphp

<div class="flex min-h-screen">

    {{-- ===== SIDEBAR ===== --}}
    <aside class="w-60 bg-gradient-to-b from-red-700 to-red-900 text-white flex flex-col fixed h-full z-30 shadow-2xl">

        {{-- Logo --}}
        <div class="px-5 py-5 border-b border-red-600/50">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                <svg width="30" height="30" viewBox="0 0 32 32" fill="none">
                    <path d="M16 4C16 4 6 14 6 20A10 10 0 0026 20C26 14 16 4 16 4Z" fill="white" opacity="0.9"/>
                    <ellipse cx="12" cy="19" rx="2" ry="3" fill="rgba(239,68,68,0.5)"/>
                </svg>
                <span class="text-lg font-bold tracking-wide">
                    <span style="color:#93c5fd">Vita</span><span style="color:#fca5a5">Flow</span>
                </span>
            </a>
        </div>

        {{-- User card --}}
        <div class="px-4 py-3 border-b border-red-600/50">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center text-sm font-bold shrink-0">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="overflow-hidden">
                    <p class="text-sm font-semibold truncate text-white">{{ $user->name }}</p>
                    <span class="text-xs text-red-200">{{ str_replace('_', ' ', ucfirst($role)) }}</span>
                </div>
                @if($user->groupe_sanguin)
                    <span class="ml-auto bg-white/20 text-white text-xs font-bold px-2 py-0.5 rounded-lg shrink-0">
                        {{ $user->groupe_sanguin }}
                    </span>
                @endif
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 py-3 overflow-y-auto space-y-0.5">

            {{-- Commun à tous --}}
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span>🏠</span> Tableau de bord
            </a>

            <a href="{{ route('profil.show') }}" class="nav-link {{ request()->routeIs('profil.*') ? 'active' : '' }}">
                <span>👤</span> Mon profil
            </a>

            <a href="{{ route('notifications.index') }}" class="nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                <span>🔔</span> Notifications
                @if($notifCount > 0)
                    <span class="badge">{{ $notifCount }}</span>
                @endif
            </a>

            {{-- ===== PATIENT ===== --}}
            @if($role === 'patient')
                <p class="nav-section">Sang</p>
                <a href="{{ route('demandes.create') }}" class="nav-link {{ request()->routeIs('demandes.create') ? 'active' : '' }}">
                    <span>➕</span> Nouvelle demande
                </a>
                <a href="{{ route('demandes.index') }}" class="nav-link {{ request()->routeIs('demandes.index', 'demandes.show') ? 'active' : '' }}">
                    <span>🩸</span> Mes demandes
                </a>
                <a href="{{ route('stocks.public') }}" class="nav-link {{ request()->routeIs('stocks.public') ? 'active' : '' }}">
                    <span>📊</span> Disponibilités
                </a>

                <p class="nav-section">Finances</p>
                <a href="{{ route('paiements.index') }}" class="nav-link {{ request()->routeIs('paiements.*') ? 'active' : '' }}">
                    <span>💳</span> Paiements
                </a>
                <a href="{{ route('bons.index') }}" class="nav-link {{ request()->routeIs('bons.*') ? 'active' : '' }}">
                    <span>🎁</span> Bons de paiement
                </a>
                <a href="{{ route('cagnottes.index') }}" class="nav-link {{ request()->routeIs('cagnottes.index', 'cagnottes.create') ? 'active' : '' }}">
                    <span>💰</span> Mes cagnottes
                </a>
            @endif

            {{-- ===== HOPITAL ===== --}}
            @if($role === 'hopital')
                <p class="nav-section">Demandes</p>
                <a href="{{ route('demandes.create') }}" class="nav-link {{ request()->routeIs('demandes.create') ? 'active' : '' }}">
                    <span>➕</span> Nouvelle demande
                </a>
                <a href="{{ route('demandes.index') }}" class="nav-link {{ request()->routeIs('demandes.index', 'demandes.show') ? 'active' : '' }}">
                    <span>📋</span> Toutes les demandes
                </a>
                <a href="{{ route('stocks.public') }}" class="nav-link {{ request()->routeIs('stocks.public') ? 'active' : '' }}">
                    <span>📊</span> Disponibilités
                </a>

                <p class="nav-section">Finances</p>
                <a href="{{ route('paiements.index') }}" class="nav-link {{ request()->routeIs('paiements.*') ? 'active' : '' }}">
                    <span>💳</span> Paiements
                </a>
                <a href="{{ route('bons.index') }}" class="nav-link {{ request()->routeIs('bons.*') ? 'active' : '' }}">
                    <span>🎁</span> Bons de paiement
                </a>
                <a href="{{ route('cagnottes.index') }}" class="nav-link {{ request()->routeIs('cagnottes.index', 'cagnottes.create') ? 'active' : '' }}">
                    <span>💰</span> Cagnottes
                </a>
            @endif

            {{-- ===== DONNEUR ===== --}}
            @if($role === 'donneur')
                <p class="nav-section">Dons</p>
                <a href="{{ route('donneur.alertes') }}" class="nav-link {{ request()->routeIs('donneur.alertes') ? 'active' : '' }}">
                    <span>🚨</span> Alertes de don
                </a>
                <a href="{{ route('donneur.historique') }}" class="nav-link {{ request()->routeIs('donneur.historique') ? 'active' : '' }}">
                    <span>📅</span> Historique dons
                </a>
                <a href="{{ route('stocks.public') }}" class="nav-link {{ request()->routeIs('stocks.public') ? 'active' : '' }}">
                    <span>📊</span> Disponibilités
                </a>
            @endif

            {{-- ===== BANQUE DE SANG ===== --}}
            @if($role === 'banque_de_sang')
                <p class="nav-section">Gestion</p>
                <a href="{{ route('stocks.index') }}" class="nav-link {{ request()->routeIs('stocks.index') ? 'active' : '' }}">
                    <span>📦</span> Stocks
                </a>
                <a href="{{ route('demandes.index') }}" class="nav-link {{ request()->routeIs('demandes.*') ? 'active' : '' }}">
                    <span>📋</span> Demandes reçues
                </a>
                <a href="{{ route('stocks.public') }}" class="nav-link {{ request()->routeIs('stocks.public') ? 'active' : '' }}">
                    <span>👁️</span> Vue publique
                </a>
            @endif

            {{-- ===== ADMIN ===== --}}
            @if($role === 'admin')
                <p class="nav-section">Gestion</p>
                <a href="{{ route('demandes.index') }}" class="nav-link {{ request()->routeIs('demandes.*') ? 'active' : '' }}">
                    <span>🩸</span> Demandes
                </a>
                <a href="{{ route('stocks.index') }}" class="nav-link {{ request()->routeIs('stocks.index') ? 'active' : '' }}">
                    <span>📦</span> Stocks
                </a>
                <a href="{{ route('paiements.index') }}" class="nav-link {{ request()->routeIs('paiements.*') ? 'active' : '' }}">
                    <span>💳</span> Paiements
                </a>

                <p class="nav-section">Utilisateurs</p>
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <span>👥</span> Utilisateurs
                </a>
                <a href="{{ route('admin.hopitaux.index') }}" class="nav-link {{ request()->routeIs('admin.hopitaux.*') ? 'active' : '' }}">
                    <span>🏥</span> Hôpitaux
                </a>
                <a href="{{ route('admin.banques.index') }}" class="nav-link {{ request()->routeIs('admin.banques.*') ? 'active' : '' }}">
                    <span>🏦</span> Banques de sang
                </a>

                <p class="nav-section">Analyse</p>
                <a href="{{ route('admin.statistiques') }}" class="nav-link {{ request()->routeIs('admin.statistiques') ? 'active' : '' }}">
                    <span>📊</span> Statistiques
                </a>
                <a href="{{ route('cagnottes.index') }}" class="nav-link {{ request()->routeIs('cagnottes.*') ? 'active' : '' }}">
                    <span>💰</span> Cagnottes
                </a>
            @endif

            {{-- Commun en bas --}}
            <p class="nav-section">Communauté</p>
            <a href="{{ route('cagnottes.publique') }}" class="nav-link {{ request()->routeIs('cagnottes.publique') ? 'active' : '' }}">
                <span>🌍</span> Cagnottes publiques
            </a>
            <a href="{{ route('stocks.public') }}" class="nav-link">
                <span>📍</span> Disponibilités
            </a>

        </nav>

        {{-- Logout --}}
        <div class="px-3 py-3 border-t border-red-600/50">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="nav-link w-full text-left hover:bg-red-600/40">
                    <span>🚪</span> Déconnexion
                </button>
            </form>
        </div>
    </aside>

    {{-- ===== MAIN CONTENT ===== --}}
    <div class="flex-1 ml-60 flex flex-col min-h-screen">

        {{-- Top bar --}}
        <header class="bg-white border-b px-6 py-3 flex justify-between items-center sticky top-0 z-20 shadow-sm">
            <h1 class="text-gray-700 font-semibold text-sm">@yield('title', 'Tableau de bord')</h1>
            <div class="flex items-center gap-3">
                {{-- Notif bell --}}
                <a href="{{ route('notifications.index') }}" class="relative text-gray-400 hover:text-red-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    @if($notifCount > 0)
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-4 h-4 rounded-full flex items-center justify-center font-bold">
                            {{ $notifCount > 9 ? '9+' : $notifCount }}
                        </span>
                    @endif
                </a>
                <div class="text-xs text-gray-400">{{ $user->ville ?? 'Cameroun' }}</div>
            </div>
        </header>

        {{-- Flash messages --}}
        <div class="px-6 pt-4">
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-4 flex items-center gap-2 text-sm">
                    <span>✅</span> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-4 flex items-center gap-2 text-sm">
                    <span>❌</span> {{ session('error') }}
                </div>
            @endif
        </div>

        <main class="flex-1 px-6 pb-8 pt-2">
            @yield('content')
        </main>
    </div>
</div>

@else
<main>@yield('content')</main>
@endauth

</body>
</html>



