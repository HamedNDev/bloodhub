@extends('layouts.app')
@section('title', 'Paiement')
@section('content')
<div class="max-w-lg mx-auto">

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">💳 Paiement des frais</h2>
        <p class="text-gray-500 text-sm mt-1">Demande #{{ $demande->id }} — Groupe {{ $demande->groupe_sanguin }} ({{ $demande->quantite }} poche(s))</p>
    </div>

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-5 flex items-center gap-2">
            <span>❌</span> {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 p-3 rounded-lg mb-5 text-sm">
            @foreach($errors->all() as $e) <p>{{ $e }}</p> @endforeach
        </div>
    @endif

    {{-- Info CamPay --}}
    <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 mb-6 flex items-start gap-3">
        <span class="text-xl">ℹ️</span>
        <div class="text-sm text-blue-800">
            <p class="font-semibold mb-1">Paiement sécurisé via CamPay</p>
            <p>Vous serez redirigé vers la plateforme CamPay pour finaliser votre paiement via <strong>Orange Money</strong> ou <strong>MTN Mobile Money</strong>.</p>
        </div>
    </div>

    {{-- Avertissement demo --}}
    <div class="bg-yellow-50 border border-yellow-300 rounded-2xl p-4 mb-6 flex items-start gap-3">
        <span class="text-xl">⚠️</span>
        <div class="text-sm text-yellow-800">
            <p class="font-semibold mb-1">Mode démonstration actif</p>
            <p>Le montant maximum autorisé est <strong>25 XAF</strong> sur l'environnement de test CamPay. Entrez un montant entre 1 et 25 pour tester.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border p-8">
        <form method="POST" action="{{ route('paiements.store', $demande) }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Montant (XAF) *</label>
                <input type="number" name="montant" value="{{ old('montant', 10) }}" min="1" max="25"
                    placeholder="Max 25 XAF en mode demo"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-400" required>
                <p class="text-xs text-yellow-600 mt-1">⚠️ Mode demo : maximum 25 XAF</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Méthode de paiement *</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-center gap-3 border-2 rounded-xl p-4 cursor-pointer hover:border-orange-400 transition has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50">
                        <input type="radio" name="methode" value="orange_money" {{ old('methode') == 'orange_money' ? 'checked' : '' }} class="text-orange-500" required>
                        <div>
                            <p class="font-semibold text-sm">🟠 Orange Money</p>
                            <p class="text-xs text-gray-400">Numéros 069x, 065x</p>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 border-2 rounded-xl p-4 cursor-pointer hover:border-yellow-400 transition has-[:checked]:border-yellow-500 has-[:checked]:bg-yellow-50">
                        <input type="radio" name="methode" value="mtn_momo" {{ old('methode') == 'mtn_momo' ? 'checked' : '' }} class="text-yellow-500">
                        <div>
                            <p class="font-semibold text-sm">🟡 MTN MoMo</p>
                            <p class="text-xs text-gray-400">Numéros 067x, 068x</p>
                        </div>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Numéro de téléphone *</label>
                <div class="flex">
                    <span class="bg-gray-100 border border-r-0 border-gray-300 rounded-l-lg px-3 py-2.5 text-sm text-gray-500">+237</span>
                    <input type="text" name="numero_telephone"
                        value="{{ old('numero_telephone', ltrim(auth()->user()->telephone ?? '', '237')) }}"
                        placeholder="6XXXXXXXX"
                        class="flex-1 border border-gray-300 rounded-r-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-400" required>
                </div>
                <p class="text-xs text-gray-400 mt-1">Entrez le numéro sans l'indicatif pays</p>
            </div>

            <div class="bg-gray-50 rounded-xl p-4 text-sm space-y-2">
                <div class="flex justify-between text-gray-600">
                    <span>Demande</span>
                    <span>#{{ $demande->id }} — {{ $demande->groupe_sanguin }}</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Quantité</span>
                    <span>{{ $demande->quantite }} poche(s)</span>
                </div>
                <div class="flex justify-between font-bold text-gray-800 border-t pt-2 mt-2">
                    <span>Total à payer</span>
                    <span id="montant-display">— FCFA</span>
                </div>
            </div>

            <button type="submit"
                class="w-full bg-red-700 hover:bg-red-800 text-white font-bold py-3 rounded-xl transition shadow flex items-center justify-center gap-2">
                <span>💳</span> Payer via CamPay
            </button>

            <a href="{{ route('demandes.show', $demande) }}"
                class="block text-center text-sm text-gray-500 hover:underline">Annuler</a>
        </form>
    </div>
</div>

<script>
    document.querySelector('[name="montant"]').addEventListener('input', function() {
        const val = parseInt(this.value) || 0;
        document.getElementById('montant-display').textContent = val.toLocaleString('fr-FR') + ' FCFA';
    });
</script>
@endsection


