@extends('layouts.app')
@section('title', 'Gestion des stocks')
@section('content')
<div class="max-w-4xl mx-auto">
    <h2 class="text-xl font-bold text-red-700 mb-2">📦 Stocks — {{ $banque->nom }}</h2>
    <p class="text-sm text-gray-500 mb-6">{{ $banque->ville }}</p>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($groupes as $groupe)
        @php $stock = $stocks[$groupe] ?? null; @endphp
        <div class="bg-white rounded-xl shadow p-5">
            <div class="flex justify-between items-center mb-3">
                <span class="text-2xl font-bold text-red-700">{{ $groupe }}</span>
                @if($stock)
                    <span class="text-sm font-semibold {{ $stock->estCritique() ? 'text-red-600' : 'text-green-600' }}">
                        {{ $stock->quantite }} poche(s) {{ $stock->estCritique() ? '⚠️' : '✅' }}
                    </span>
                @else
                    <span class="text-sm text-gray-400">Non renseigné</span>
                @endif
            </div>
            <form method="POST" action="{{ route('stocks.update', $groupe) }}" class="flex gap-2">
                @csrf @method('PATCH')
                <input type="number" name="quantite" placeholder="Quantité" min="0"
                    value="{{ $stock->quantite ?? 0 }}"
                    class="flex-1 border rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-red-400">
                <input type="number" name="seuil_alerte" placeholder="Seuil" min="0"
                    value="{{ $stock->seuil_alerte ?? 5 }}"
                    class="w-20 border rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-red-400">
                <button class="bg-red-700 text-white px-3 py-1 rounded text-sm hover:bg-red-800">Sauver</button>
            </form>
            <p class="text-xs text-gray-400 mt-1">Seuil d'alerte : {{ $stock->seuil_alerte ?? 5 }} | MAJ : {{ $stock?->derniere_mise_a_jour?->format('d/m H:i') ?? 'jamais' }}</p>
        </div>
        @endforeach
    </div>
</div>
@endsection



