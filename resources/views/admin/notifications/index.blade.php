@extends('layouts.admin')
@section('title', 'Alertes & Notifications')

@section('content')
<div class="max-w-7xl mx-auto space-y-5">

    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Alertes & Notifications</h2>
            <p class="text-gray-500 text-sm mt-0.5">Envoyer des alertes et gérer les notifications de la plateforme</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Formulaire d'envoi --}}
        <div class="lg:col-span-1 space-y-4">
            <div class="bg-white rounded-2xl border shadow-sm p-5">
                <h3 class="font-semibold text-gray-700 mb-4 flex items-center gap-2 text-sm">
                    <span class="w-6 h-6 bg-red-100 rounded-lg flex items-center justify-center text-xs">📢</span>
                    Envoyer une alerte
                </h3>
                <form method="POST" action="{{ route('admin.notifications.envoyer') }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Destinataires *</label>
                        <select name="destinataire" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-400" required>
                            <option value="donneurs">❤️ Tous les donneurs ({{ $totalDonneurs }})</option>
                            <option value="hopitaux">🏥 Tous les hôpitaux ({{ $totalHopitaux }})</option>
                            <option value="banques">🏦 Toutes les banques ({{ $totalBanques }})</option>
                            <option value="patients">🧑‍⚕️ Tous les patients ({{ $totalPatients }})</option>
                            <option value="tous">🌍 Tout le monde</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Type *</label>
                        <select name="type" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-400" required>
                            <option value="info">ℹ️ Information</option>
                            <option value="alerte_don">🩸 Alerte don</option>
                            <option value="urgence">🚨 Urgence</option>
                            <option value="stock_insuffisant">⚠️ Stock insuffisant</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Titre *</label>
                        <input type="text" name="titre" value="{{ old('titre') }}" placeholder="Titre de la notification..."
                            class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-400" required>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Message *</label>
                        <textarea name="message" rows="4" placeholder="Contenu du message..." required
                            class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-400 resize-none">{{ old('message') }}</textarea>
                    </div>
                    <button type="submit" class="w-full bg-red-700 text-white py-2.5 rounded-xl hover:bg-red-800 font-semibold text-sm transition-colors">
                        📤 Envoyer la notification
                    </button>
                </form>
            </div>

            {{-- Alertes rapides --}}
            <div class="bg-white rounded-2xl border shadow-sm p-5">
                <h3 class="font-semibold text-gray-700 mb-3 text-sm flex items-center gap-2">
                    <span class="w-6 h-6 bg-orange-100 rounded-lg flex items-center justify-center text-xs">⚡</span>
                    Alertes rapides
                </h3>
                <div class="space-y-2">
                    <form method="POST" action="{{ route('admin.notifications.envoyer') }}">
                        @csrf
                        <input type="hidden" name="destinataire" value="donneurs">
                        <input type="hidden" name="type" value="alerte_don">
                        <input type="hidden" name="titre" value="🩸 Besoin urgent de donneurs !">
                        <input type="hidden" name="message" value="La plateforme VitaFlow a besoin de donneurs de sang en urgence. Votre don peut sauver des vies. Rendez-vous dans le centre de collecte le plus proche.">
                        <button type="submit" class="w-full text-left bg-red-50 border border-red-100 text-red-700 px-3 py-2.5 rounded-xl text-xs font-medium hover:bg-red-100 transition-colors">
                            🩸 Alerte urgente aux donneurs
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.notifications.envoyer') }}">
                        @csrf
                        <input type="hidden" name="destinataire" value="hopitaux">
                        <input type="hidden" name="type" value="info">
                        <input type="hidden" name="titre" value="📋 Mise à jour de la plateforme">
                        <input type="hidden" name="message" value="Une mise à jour importante de la plateforme VitaFlow est disponible. Veuillez vérifier vos demandes en cours et mettre à jour vos informations si nécessaire.">
                        <button type="submit" class="w-full text-left bg-blue-50 border border-blue-100 text-blue-700 px-3 py-2.5 rounded-xl text-xs font-medium hover:bg-blue-100 transition-colors">
                            🏥 Info mise à jour aux hôpitaux
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.notifications.envoyer') }}">
                        @csrf
                        <input type="hidden" name="destinataire" value="banques">
                        <input type="hidden" name="type" value="stock_insuffisant">
                        <input type="hidden" name="titre" value="⚠️ Vérification des stocks requise">
                        <input type="hidden" name="message" value="Merci de vérifier et mettre à jour vos stocks de sang. Des demandes sont en attente sur la plateforme.">
                        <button type="submit" class="w-full text-left bg-orange-50 border border-orange-100 text-orange-700 px-3 py-2.5 rounded-xl text-xs font-medium hover:bg-orange-100 transition-colors">
                            🏦 Vérif stocks aux banques
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Historique des notifications --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border shadow-sm overflow-hidden">
            <div class="flex justify-between items-center px-5 py-4 border-b">
                <h3 class="font-semibold text-gray-700 text-sm flex items-center gap-2">
                    🔔 Historique des notifications
                    <span class="bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded-full">{{ $notifications->total() }}</span>
                </h3>
                <form method="POST" action="{{ route('admin.notifications.destroy-all') }}" onsubmit="return confirm('Supprimer TOUTES les notifications ?')">
                    @csrf @method('DELETE')
                    <button class="text-xs bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1.5 rounded-lg font-medium transition-colors">
                        🗑 Tout supprimer
                    </button>
                </form>
            </div>

            @if($notifications->isEmpty())
                <div class="text-center py-16 text-gray-400">
                    <div class="text-4xl mb-3">🔕</div>
                    <p class="text-sm">Aucune notification enregistrée</p>
                </div>
            @else
                <div class="divide-y overflow-y-auto" style="max-height: 600px;">
                    @foreach($notifications as $n)
                    <div class="px-5 py-3 flex items-start justify-between hover:bg-gray-50 transition-colors {{ $n->lue ? '' : 'bg-blue-50/30' }}">
                        <div class="flex items-start gap-3 flex-1 min-w-0">
                            <div class="w-8 h-8 rounded-xl flex items-center justify-center text-sm shrink-0
                                {{ $n->type==='urgence' ? 'bg-red-100' :
                                   ($n->type==='alerte_don' ? 'bg-pink-100' :
                                   ($n->type==='stock_insuffisant' ? 'bg-orange-100' : 'bg-blue-100')) }}">
                                {{ $n->type==='urgence' ? '🚨' : ($n->type==='alerte_don' ? '🩸' : ($n->type==='stock_insuffisant' ? '⚠️' : 'ℹ️')) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <p class="text-xs font-semibold text-gray-700">{{ $n->titre }}</p>
                                    @if(!$n->lue)
                                        <span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-500 mt-0.5 truncate">{{ $n->message }}</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-xs text-gray-400">→ {{ $n->user?->name ?? '—' }}</span>
                                    <span class="text-xs text-gray-300">·</span>
                                    <span class="text-xs text-gray-400">{{ $n->created_at->diffForHumans() }}</span>
                                    <span class="text-xs px-1.5 py-0.5 rounded font-medium
                                        {{ $n->type==='urgence' ? 'bg-red-100 text-red-600' :
                                           ($n->type==='alerte_don' ? 'bg-pink-100 text-pink-600' :
                                           ($n->type==='stock_insuffisant' ? 'bg-orange-100 text-orange-600' : 'bg-blue-100 text-blue-600')) }}">
                                        {{ str_replace('_',' ', ucfirst($n->type)) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('admin.notifications.destroy', $n) }}" class="ml-2 shrink-0">
                            @csrf @method('DELETE')
                            <button class="text-gray-300 hover:text-red-500 transition-colors text-lg leading-none">×</button>
                        </form>
                    </div>
                    @endforeach
                </div>
                <div class="px-5 py-3 border-t bg-gray-50">{{ $notifications->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection



