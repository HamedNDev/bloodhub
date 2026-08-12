<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VitaFlow Admin — @yield('title', 'Administration')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .nav-link {
            display: flex; align-items: center; gap: 10px;
            padding: 8px 12px; border-radius: 8px;
            font-size: 0.82rem; font-weight: 500;
            color: rgba(255,255,255,0.72);
            transition: all 0.15s ease;
            text-decoration: none;
        }
        .nav-link:hover { background: rgba(255,255,255,0.1); color: #fff; }
        .nav-link.active { background: rgba(255,255,255,0.16); color: #fff; font-weight: 600; }
        .nav-section {
            font-size: 0.6rem; font-weight: 700; letter-spacing: 0.12em;
            color: rgba(255,255,255,0.3); padding: 12px 12px 3px;
            text-transform: uppercase;
        }
        .stat-card { transition: transform 0.15s ease, box-shadow 0.15s ease; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
    </style>
</head>
<body class="bg-gray-100 min-h-screen font-sans">

@auth
@php
    if (!auth()->user()->isAdmin()) { abort(403, 'Accès réservé aux administrateurs.'); }
    $admin = auth()->user();
    $notifCount = \App\Models\Notification::where('user_id', $admin->id)->where('lue', false)->count();
    $urgentesCount = \App\Models\Demande::whereIn('urgence', ['urgente','critique'])->where('statut','en_attente')->count();
@endphp

<div class="flex min-h-screen">

    {{-- ===== SIDEBAR ADMIN ===== --}}
    <aside class="w-56 bg-gradient-to-b from-gray-900 via-red-950 to-gray-900 text-white flex flex-col fixed h-full z-30 shadow-2xl">

        {{-- Logo --}}
        <div class="px-4 py-4 border-b border-white/10">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
                <div class="w-8 h-8 bg-red-600 rounded-lg flex items-center justify-center shadow">
                    <svg width="18" height="18" viewBox="0 0 32 32" fill="none">
                        <path d="M16 4C16 4 6 14 6 20A10 10 0 0026 20C26 14 16 4 16 4Z" fill="white"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-white leading-none">VitaFlow</p>
                    <p class="text-xs text-red-300 font-medium">Administration</p>
                </div>
            </a>
        </div>

        {{-- Admin card --}}
        <div class="px-3 py-3 border-b border-white/10">
            <div class="flex items-center gap-2.5 bg-white/8 rounded-xl px-3 py-2">
                <div class="w-8 h-8 bg-red-600 rounded-lg flex items-center justify-center text-xs font-bold shrink-0">
                    {{ strtoupper(substr($admin->name, 0, 1)) }}
                </div>
                <div class="overflow-hidden">
                    <p class="text-xs font-semibold text-white truncate">{{ $admin->name }}</p>
                    <p class="text-xs text-red-300">Administrateur</p>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-2 py-2 overflow-y-auto space-y-0.5">

            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Tableau de bord
            </a>

            <p class="nav-section">Opérations</p>

            <a href="{{ route('demandes.index') }}" class="nav-link {{ request()->routeIs('demandes.*') ? 'active' : '' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Demandes
                @if($urgentesCount > 0)
                    <span class="ml-auto bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full font-bold">{{ $urgentesCount }}</span>
                @endif
            </a>

            <a href="{{ route('stocks.index') }}" class="nav-link {{ request()->routeIs('stocks.index') ? 'active' : '' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                Stocks
            </a>

            <a href="{{ route('paiements.index') }}" class="nav-link {{ request()->routeIs('paiements.*') ? 'active' : '' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                Paiements
            </a>

            <a href="{{ route('cagnottes.index') }}" class="nav-link {{ request()->routeIs('cagnottes.index','cagnottes.create') ? 'active' : '' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Cagnottes
            </a>

            <p class="nav-section">Utilisateurs</p>

            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                Utilisateurs
            </a>

            <a href="{{ route('admin.donneurs.index') }}" class="nav-link {{ request()->routeIs('admin.donneurs.*') ? 'active' : '' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                Donneurs
            </a>

            <a href="{{ route('admin.hopitaux.index') }}" class="nav-link {{ request()->routeIs('admin.hopitaux.*') ? 'active' : '' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                Hôpitaux
            </a>

            <a href="{{ route('admin.banques.index') }}" class="nav-link {{ request()->routeIs('admin.banques.*') ? 'active' : '' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>
                Banques de sang
            </a>

            <p class="nav-section">Analyse</p>

            <a href="{{ route('admin.statistiques') }}" class="nav-link {{ request()->routeIs('admin.statistiques') ? 'active' : '' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Statistiques
            </a>

            <a href="{{ route('admin.paiements.index') }}" class="nav-link {{ request()->routeIs('admin.paiements.*') ? 'active' : '' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                Finances
            </a>

            <a href="{{ route('admin.notifications.index') }}" class="nav-link {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                Alertes & Notifs
            </a>

            <p class="nav-section">Compte</p>

            <a href="{{ route('notifications.index') }}" class="nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                Notifications
                @if($notifCount > 0)
                    <span class="ml-auto bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full font-bold">{{ $notifCount }}</span>
                @endif
            </a>

            <a href="{{ route('profil.show') }}" class="nav-link {{ request()->routeIs('profil.*') ? 'active' : '' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Mon profil
            </a>

        </nav>

        {{-- Logout --}}
        <div class="px-2 py-3 border-t border-white/10">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="nav-link w-full text-left hover:bg-red-900/50">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Déconnexion
                </button>
            </form>
        </div>
    </aside>

    {{-- ===== MAIN ===== --}}
    <div class="flex-1 ml-56 flex flex-col min-h-screen">

        {{-- Top bar --}}
        <header class="bg-white border-b px-6 py-3 flex justify-between items-center sticky top-0 z-20 shadow-sm">
            <div class="flex items-center gap-2">
                <span class="text-xs text-gray-400 font-medium uppercase tracking-wide">Admin</span>
                <span class="text-gray-300">/</span>
                <h1 class="text-gray-700 font-semibold text-sm">@yield('title', 'Tableau de bord')</h1>
            </div>
            <div class="flex items-center gap-4">
                @if($urgentesCount > 0)
                <a href="{{ route('demandes.index') }}" class="flex items-center gap-1.5 bg-red-50 border border-red-200 text-red-700 text-xs font-medium px-3 py-1.5 rounded-lg hover:bg-red-100 transition-colors">
                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full animate-pulse"></span>
                    {{ $urgentesCount }} urgente{{ $urgentesCount > 1 ? 's' : '' }}
                </a>
                @endif
                <a href="{{ route('notifications.index') }}" class="relative text-gray-400 hover:text-red-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    @if($notifCount > 0)
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-4 h-4 rounded-full flex items-center justify-center font-bold">{{ $notifCount > 9 ? '9+' : $notifCount }}</span>
                    @endif
                </a>
                <div class="flex items-center gap-2 text-xs text-gray-500">
                    <div class="w-7 h-7 bg-red-600 rounded-lg flex items-center justify-center text-white font-bold text-xs">
                        {{ strtoupper(substr($admin->name, 0, 1)) }}
                    </div>
                    {{ $admin->name }}
                </div>
            </div>
        </header>

        {{-- Flash messages --}}
        <div class="px-6 pt-4 space-y-2">
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl flex items-center gap-2 text-sm">
                    <svg class="w-4 h-4 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl flex items-center gap-2 text-sm">
                    <svg class="w-4 h-4 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    {{ session('error') }}
                </div>
            @endif
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <main class="flex-1 px-6 pb-8 pt-4">
            @yield('content')
        </main>

        <footer class="px-6 py-3 border-t bg-white text-xs text-gray-400 flex justify-between">
            <span>VitaFlow Admin Panel</span>
            <span>{{ now()->format('d/m/Y H:i') }}</span>
        </footer>
    </div>
</div>

@else
    <script>window.location.href = "{{ route('login') }}";</script>
@endauth

</body>
</html>



